<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->getNotifications();
        $unreadNotificationsCount = $user->countNotificationsNotRead();

        // Mark notifications as read
        // TODO: Add support for marking notifications as read/unread

        return view('profile.notifications', compact('user', 'notifications', 'unreadNotificationsCount'));
    }
}
