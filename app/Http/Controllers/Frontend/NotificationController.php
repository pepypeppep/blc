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

    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required|string',
        ]);

        $user = User::find($request->user_id);
        $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'Device token updated successfully']);
    }

    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return back()->with('success', 'Notification send successfully.');
    }
}
