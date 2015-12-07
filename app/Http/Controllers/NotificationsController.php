<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;
use Fenos\Notifynder\Models\Notification;

class NotificationsController extends Controller
{
    // TODO: Send notification email if notification email setting is set
    // TODO: Add support for marking notifications as read/unread (all or individual)

    public function __construct()
    {
        $this->middleware('auth.profile');
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = $user->getNotifications();
//        $user->readAllNotifications();

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
