<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Favourite;
use App\Models\Friend;
use App\Models\Genre;
use App\Models\ListEpisodesWatched;
use App\Models\Lists;
use App\Models\Show;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile', ['except' => ['index']]);
    }

    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $canViewProfile = $this->canViewProfile($user);
        if ($canViewProfile) {
            $user->gender = DB::table('genders')->where('gender', '=', $user->gender)->value('description');
            $recentEpsWatched = $user->episodesWatched()
                ->withSeries()
                ->mostRecent()->take(5)->get();
            foreach ($recentEpsWatched as $ep) {
                $ep->formatted = sprintf('S%02dE%02d', $ep->season, $ep->EpisodeNumber);
            }
            $favourites = $user->favourites()
                ->with(['show' => function ($query) {
                    $query->select('id', 'SeriesName');
                }])
                ->orderBy('sort_order', 'asc')
                ->take(5)
                ->get();

            $statistics = new Collection;
            $genres = Genre::all();
            $genreTotal = 0;

            // Statistics: Add show count by status (Watching, Completed, etc.)
            $lists = $user->getList()->with('show', 'episodesWatched')->get();
            foreach (DB::table('list_statuses')->get() as $listStatus) {
                $count = $lists->where('list_status', $listStatus->list_status)->count();
                $value = ($count == 1) ? number_format($count) . ' show' : $count . ' shows';
                $statistics->put(camel_case($listStatus->description),
                    ['title' => $listStatus->description, 'value' => $value]);
            }

            // Statistics: Add total time watched and episodes watched
            $totalMinutesWatched = 0;
            foreach ($lists as $list) {
                $runtime = $list->show->Runtime;
                $totalMinutesWatched += $runtime * $list->episodesWatched->count();

                // Genres: Add count and calculate total
                foreach ($genres as $key => $row) {
                    if (str_contains($list->show->Genre, $row->genre)) {
                        (isset($row->count)) ? $row->count++ : $row->count = 1;
                        $genreTotal++;
                    }
                }
            }
            $genres = $genres->sortByDesc('count');
            $statistics->put('totalTimeWatched',
                ['title' => 'Time Watched', 'value' => $this->minutesToString($totalMinutesWatched)]);
            $statistics->put('epsWatched', [
                'title' => 'Episodes Watched',
                'value' => number_format($user->episodesWatched()->count())
            ]);

            // Check if already friends or request has already been sent
            $loggedInUser = Auth::user();
            if (isset($loggedInUser) && $user->id !== $loggedInUser->id) {
                $alreadyRequested = Friend::getFriendsOrRequested($loggedInUser, $user)->exists();
                if ($alreadyRequested) {
                    $friendIds = Friend::getFriendIds($user);
                    $areFriends = in_array($loggedInUser->id, $friendIds);
                    // If you are friends then get shows in common
                    if ($areFriends) {
                        $showIdsInCommon = DB::table('list as l1')->join('list as l2', function ($query) use ($loggedInUser, $user) {
                            $query->on('l2.series_id', '=', 'l1.series_id')
                                ->where('l2.user_id', '=', $user->id)
                                ->where('l1.user_id', '=', $loggedInUser->id);
                        })->select('l1.series_id')->lists('series_id');

                        $showsInCommon = Show::whereIn('id', $showIdsInCommon)->select(['id', 'SeriesName'])->get();
                    }
                } else {
                    $areFriends = false;
                }
            }
        }

        return view('profile.home',
            compact('user', 'recentEpsWatched', 'favourites', 'statistics', 'genres', 'canViewProfile',
                'alreadyRequested', 'areFriends', 'showsInCommon'));
    }

    /**
     * Convert minutes to a nicely formatted string.
     *
     * @param $minutes
     * @return string
     */
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

    /**
     * Route that handles showing the edit profile page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditProfile()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Route that handles showing the edit account page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditAccount()
    {
        return view('profile.account', ['user' => Auth::user()]);
    }

    /**
     * Route that handles submitting profile changes.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postProfile(Request $request)
    {
        $this->validate($request, [
            'gender' => 'in:NULL,M,F',
            'birthday' => 'before:' . Carbon::now()->toDateString(),
            'location' => 'max:50',
            'notification_email' => 'in:0,1',
            'profile_visibility' => 'in:0,1,2',
            'list_visibility' => 'in:0,1,2',
            'avatar' => 'image|max:1500'
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

        $user = Auth::user();
        $user->fill($input);
        $user->save();
        $user->updateIndex();

        return back()->with('status', 'Profile successfully updated!');
    }

    /**
     * Route that handles uploading an avatar.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function uploadAvatar(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'image|max:1500'
        ]);

        $user = Auth::user();
        $user->fill($request->all());
        if ($user->save()) {
            $user->updateIndex();
            return back()->with('status', 'Avatar successfully updated!');
        } else {
            return back()->withErrors('There was an error when uploading your avatar.');
        }
    }

    /**
     * Route that handles removing an avatar.
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function removeAvatar()
    {
        $user = Auth::user();
        if ($user->avatar->originalFileName()) {
            $user->avatar = STAPLER_NULL;
            if ($user->save()) {
                $user->updateIndex();
                return back()->with('status', 'Avatar successfully removed!');

            } else {
                return back()->withErrors('There was an error when removing your avatar.');
            }
        } else {
            return back()->withErrors('You have no avatar to be removed.');
        }
    }

    /**
     * Route that handles changing a user's email.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|confirmed|max:255|unique:users',
            'password' => 'required',
        ]);

        $user = Auth::user();
        $input = $request->all();

        // http://stackoverflow.com/questions/21802638/laravel-use-hash-as-validator
        if (Hash::check($input['password'], $user->password)) {
            $user->fill(['email' => $input['email']]);
            $user->save();
            $user->updateIndex();
        } else {
            return redirect()->route('profile/account', [$user->username])
                ->withErrors('The password is incorrect.')
                ->withInput();
        }

        return back()->with('status', 'Email successfully updated!');
    }

    /**
     * Route that handles changing a user's password.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postPassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
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

    /**
     *
     *
     * @param $user
     * @return bool
     */
    // TODO: Use authorization for this? http://laravel.com/docs/5.1/authorization
    private function canViewProfile($user)
    {
        // If user is viewing their own profile or profile visibility is public
        if (Auth::check() && Auth::user()->username === $user->username || $user->profile_visibility === 0) {
            return true;
        } else {
            // If user's profile is private
            if ($user->profile_visibility === 1) {
                return false;
            }

            // If user's profile is set to friends only
            if ($user->profile_visibility === 2) {
                // TODO: Extract this to model
                $friendIds = DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
                    $query->on('f1.user_id', '=', 'f2.friend_id')->on('f1.friend_id', '=',
                        'f2.user_id')->where('f1.user_id',
                        '=', $user->id);
                })->select('f1.friend_id')->lists('friend_id');

                if (Auth::check() && in_array(Auth::user()->id, $friendIds)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
