<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Favourite;
use App\Models\ListEpisodesWatched;
use App\Models\Lists;
use App\Models\Show;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile', ['only' => ['showEditProfile', 'showEditAccount']]);
    }

    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $user->gender = DB::table('genders')->where('gender', '=', $user->gender)->value('description');
        $recentEpsWatched = DB::table('list_episodes_watched')
            ->where('user_id', $user->id)
            ->select('list.series_id', 'tvseries.SeriesName', 'tvepisodes.EpisodeName', 'tvepisodes.EpisodeNumber',
                'tvseasons.season')
            ->join('tvepisodes', 'list_episodes_watched.episode_id', '=', 'tvepisodes.id')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->join('tvseries', 'tvepisodes.seriesid', '=', 'tvseries.id')
            ->join('list', 'list_episodes_watched.list_id', '=', 'list.id')
            ->join('users', 'list.user_id', '=', 'users.id')
            ->orderBy('list_episodes_watched.updated_at', 'desc')
            ->orderBy('list_episodes_watched.created_at', 'desc')
            ->orderBy('tvseasons.season', 'desc')
            ->orderBy('tvepisodes.EpisodeNumber', 'desc')
            ->take(5)
            ->get();

        foreach ($recentEpsWatched as $ep) {
            $ep->formatted = sprintf('S%02dE%02d', $ep->season, $ep->EpisodeNumber);
        }

        $favourites = Favourite::join('tvseries', 'favourites.series_id', '=', 'tvseries.id')
            ->where('user_id', $user->id)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Statistics: Add show count by status (Watching, Completed, etc.)
        $statistics = new Collection;
        foreach (DB::table('list_statuses')->get() as $listStatus) {
            $count = Lists::where('list_status', $listStatus->list_status)->where('user_id', $user->id)->count('series_id');
            $value = ($count > 1) ? number_format($count) . ' shows' : $count . ' show';
            $statistics->put(camel_case($listStatus->description), ['title' => $listStatus->description, 'value' => $value]);
        }


        $totalMinutesWatched = 0;
        foreach (User::find($user->id)->getList()->get() as $show) {
            $runtime = (int)Show::where('id', $show->series_id)->value('runtime');
            $epsWatched = ListEpisodesWatched::where('list_id', $show->id)->count();
            $totalMinutesWatched += $runtime * $epsWatched;
        }
        $statistics->put('totalTimeWatched', ['title' => 'Total Time Watched', 'value' => $this->minutesToString($totalMinutesWatched)]);
        $statistics->put('epsWatched', ['title' => 'Episodes Watched', 'value' => number_format(ListEpisodesWatched::getUserEpisodesWatched($user->id)->count())]);

        return view('profile/home', compact('user', 'recentEpsWatched', 'favourites', 'statistics'));
    }

    public function showEditProfile()
    {
        return view('profile/edit', ['user' => Auth::user()]);
    }

    public function showEditAccount()
    {
        return view('profile/account', ['user' => Auth::user()]);
    }

    public function postProfile(Request $request)
    {
        $user = Auth::user();

        // TODO: Abstract this out: http://laravel.com/docs/5.1/validation#form-request-validation
        $this->validate($request, [
            'gender' => 'in:NULL,M,F',
            'birthday' => 'before:' . Carbon::now()->toDateString(),
            'location' => 'max:50',
            'notification_email' => 'in:0,1',
            'profile_visibility' => 'in:0,1,2',
            'list_visibility' => 'in:0,1,2'
        ]);

        $input = $request->all();

        if ($input['gender'] === 'NULL') {
            $input['gender'] = null;
        }

        if ($input['birthday'] === '') {
            $input['birthday'] = null;
        }

        if ($input['location'] === '') {
            $input['location'] = null;
        }

        if ($input['about'] === '') {
            $input['about'] = null;
        }

        $user->fill($input);
        $user->save();

        return back()->with('status', 'Profile successfully updated!');
    }

    public function postEmail(Request $request)
    {
        $user = Auth::user();

        // TODO: Abstract this out: http://laravel.com/docs/5.1/validation#form-request-validation
        $this->validate($request, [
            'email' => 'required|email|confirmed|max:255|unique:users',
            'password' => 'required',
        ]);

        $input = $request->all();

        // http://stackoverflow.com/questions/21802638/laravel-use-hash-as-validator
        if (Hash::check($input['password'], $user->password)) {
            $user->fill(['email' => $input['email']]);
            $user->save();
        } else {
            return redirect()->route('profile/account', [$user->username])
                ->withErrors('The password is incorrect.')
                ->withInput();
        }

        return back()->with('status', 'Email successfully updated!');
    }

    public function postPassword(Request $request)
    {
        $user = Auth::user();

        // TODO: Abstract this out: http://laravel.com/docs/5.1/validation#form-request-validation
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $input = $request->all();

        // http://stackoverflow.com/questions/21802638/laravel-use-hash-as-validator
        if (Hash::check($input['current_password'], $user->password)) {
            if (Hash::check($input['password'], $user->password)) {
                return redirect()->route('profile/account', [$user->username])
                    ->withErrors('The new password must be different than current password.')
                    ->withInput();
            }

            $user->fill(['password' => bcrypt($input['password'])]);
            $user->save();
        } else {
            return redirect()->route('profile/account', [$user->username])
                ->withErrors('The current password is incorrect.')
                ->withInput();
        }

        return back()->with('status', 'Password successfully updated!');
    }

    private function minutesToString($minutes)
    {
        $seconds = $minutes * 60;
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
        if ($seconds < 3600) {
            $str = $dtF->diff($dtT)->format('%i minutes');
        } elseif ($seconds < 7200) {
            $str = $dtF->diff($dtT)->format('%h hour, %i minutes');
        } elseif ($seconds < 86400) {
            $str = $dtF->diff($dtT)->format('%h hours, %i minutes');
        } elseif ($seconds < 172800) {
            $str = $dtF->diff($dtT)->format('%a day, %h hours, %i minutes');
        } else {
            $str = $dtF->diff($dtT)->format('%a days, %h hours, %i minutes');
        }
        return $str;
    }
}
