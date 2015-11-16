<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Friend;
use App\Models\Notification;
use Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', '=', $user->id)->get();
        foreach ($notifications as $notification) {
            // If notification is a friend request
            if ($notification->notification_type === 0) {
                // Get the requested user
                // Add message saying "<user> wants to add you as a friend"
            }
        }

        $unreadNotificationsCount = Notification::where('user_id', '=', $user->id)->where('status', '=', 'unread')->count();

        // Mark notifications as read
        // TODO: Add support for marking notifications as unread

        return view('profile.notifications', compact('user', 'notifications', 'unreadNotificationsCount'));
    }
}
