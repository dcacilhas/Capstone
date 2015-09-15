<?php

namespace App\Http\Middleware;

use App\User;
use Auth;
use Closure;

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
        if (!User::where('username', '=', $request->username)->exists()) {
            return abort(404);
        }

        if (Auth::guest() || strcasecmp($request->username, Auth::user()->username) !== 0) {
            $user = User::where('username', $request->username)->first();

            return view('profile/home', ['user' => $user]);
        }

        return $next($request);
    }
}
