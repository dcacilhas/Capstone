<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile');
    }

    public function index($username)
    {
        $user = User::where('username', $username)->first();

        return view('profile/home', ['user' => $user]);
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
            'birthday' => 'before:' . \Carbon\Carbon::now()->toDateString(),
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

        return redirect()->route('profile/edit', ['username' => $user->username])->with('status', 'Profile successfully updated!');
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

        return redirect()->route('profile/account', ['username' => $user->username])->with('status', 'Email successfully updated!');
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

        return redirect()->route('profile/account', ['username' => $user->username])->with('status', 'Password successfully updated!');
    }
}
