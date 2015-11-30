<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;
use Fenos\Notifynder\Models\Notification;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile');
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = $user->getNotifications();
        $user->readAllNotifications();
        // TODO: Add support for marking notifications as read/unread (all or individual)

        return view('profile.notifications', compact('user', 'notifications'));
    }

    /**
     * Route for dismissing a notification.
     *
     * @param $username
     * @param $notificationId
     * @return bool
     */
    public function dismiss($username, $notificationId)
    {
        $deleted = Notification::where('id', $notificationId)->delete();
        if ($deleted) {
            echo true;
        } else {
            return false;
        }
    }
}
