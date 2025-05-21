<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;

class NotificationController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->check()) {
                $user = auth()->user();
                $notifications = Notification::where('user_id', $user->id)->limit(5)->orderByDesc('id')->get();
                $counter = Notification::where('user_id', $user->id)->where('is_read', 0)->count();
            } else {
                $notifications = [];
                $counter = 0;
            }

            return view('frontend.partials.notification-list', compact('notifications', 'counter'));
        } else {
            if (auth()->check()) {
                $user = auth()->user();
                $notifications = Notification::where('user_id', $user->id)->limit(5)->orderByDesc('id')->get();
                $counter = Notification::where('user_id', $user->id)->where('is_read', 0)->count();
            } else {
                $notifications = [];
                $counter = 0;
            }

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

    function sendNotification(Request $request)
    {
        $request['user_id'] = 1;
        $request['title'] = '[BLC] Pemberitahuan';
        $request['body'] = "Pemberitahuan dari Admin " . now()->format('d-m-Y H:i:s');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string', //Nama aplikasi
            'body' => 'required|string', //Teks pemberitahuan
        ]);

        $user = User::find($request->user_id);
        $fcm = $user->fcm_token;

        if (!$fcm) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        $title = $request->title;
        $description = $request->body;
        // $projectId = config('services.fcm.project_id'); # INSERT COPIED PROJECT ID
        $projectId = 'bantul-lms';

        $credentialsFilePath = Storage::path('json/bantul-lms-firebase.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "data" => [
                    "link" => "http://127.0.0.1:8000/student/continuing-education-registration/1",
                    "path" => json_encode(["module" => "pendidikan-lanjutan", "id" => 1]),
                    "sender" => json_encode([
                        "name" => "BLC Administrator"
                    ]),
                    "timestamp" => now()->toISOString()
                ],
                "notification" => [
                    "title" => $title,
                    "body" => $description,
                ],
                // "android" => [
                //     "priority" => "high"
                // ],
                // "apns" => [
                //     "headers" => [
                //         "apns-priority" => "10"
                //     ]
                // ]
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
}
