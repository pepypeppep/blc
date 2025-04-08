<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $user = userAuth();
            $notifications = Notification::where('user_id', $user->id)->limit(5)->orderByDesc('id')->get();
            $counter = Notification::where('user_id', $user->id)->where('is_read', 0)->count();

            return view('frontend.partials.notification-list', compact('notifications', 'counter'));
        } else {
            $user = userAuth();
            $notifications = Notification::where('user_id', $user->id)->orderByDesc('id')->paginate();
            $counter = Notification::where('user_id', $user->id)->where('is_read', 0)->count();

            return view('frontend.pages.notification-show', compact('notifications', 'counter'));
        }
    }

    public function getNotificationCount(Request $request)
    {
        if ($request->ajax()) {
            $user = userAuth();
            $counter = Notification::where('user_id', $user->id)->where('is_read', 0)->count();

            return $counter;
        }
    }

    public function read(Request $request)
    {
        try {
            $user = userAuth();
            Notification::where('user_id', $user->id)->where('is_read', 0)->update(['is_read' => 1]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
