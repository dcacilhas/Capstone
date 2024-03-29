<?php

namespace App\Http\Middleware;

use App\Models\User;
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

        // User is a guest or is logged in but requesting the profile of a different user
        // Protects against users trying to access another user's edit profile/edit account pages
        if (Auth::guest() || strcasecmp($request->username, Auth::user()->username) !== 0) {
            return abort(403);
        }

        return $next($request);
    }
}
