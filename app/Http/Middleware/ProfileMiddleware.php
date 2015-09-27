<?php

namespace App\Http\Middleware;

use App\User;
use Auth;
use Closure;
use Illuminate\Support\Facades\Input;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // User does not exist
        if (!User::where('username', '=', $request->username)->exists()) {
            return abort(404);
        }

        // User is a guest or is requesting the profile of a different user
        if (Auth::guest() || strcasecmp($request->username, Auth::user()->username) !== 0) {
            $user = User::where('username', $request->username)->first();

            if ($request->route()->getName() === 'profile') {
                return view('profile/home', ['user' => $user]);
            }

            // TODO: May need to refactor. 'series' will definitely need Model info from database.
            if ($request->route()->getName() === 'profile/list') {
                $status = $status = Input::get('status');
                return view('profile/list', ['user' => $user, 'series' => [], 'status' => $status]);
            }

            return view('profile/home', ['user' => $user]);
        }

        return $next($request);
    }
}
