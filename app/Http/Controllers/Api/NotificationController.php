<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/update-device-token",
     *     summary="Update device token",
     *     description="Update device token",
     *     tags={"Notifications"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="fcm_token",
     *                 type="string",
     *                 example="fcm token",
     *                 description="FCM token"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Device token updated successfully"
     *     )
     * )
     */
    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = User::find($request->user()->id);
        $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'Device token updated successfully']);
    }

    /**
     * @OA\Get(
     *     path="/notifications",
     *     summary="Get notification list",
     *     tags={"Notifications"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="notifications",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="user_id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="title",
     *                             type="string",
     *                             example="title"
     *                         ),
     *                         @OA\Property(
     *                             property="body",
     *                             type="string",
     *                             example="body"
     *                         ),
     *                         @OA\Property(
     *                             property="is_read",
     *                             type="boolean",
     *                             example=false
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="counter",
     *                     type="integer",
     *                     example=0
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Server error"
     *             )
     *         )
     *     )
     * )
     */
    public function list(Request $request)
    {
        try {
            $user = User::where('id', $request->user_id)->first();
            $notifications = Notification::where('user_id', $user->id)->orderByDesc('id')->paginate(5);

            return response()->json([
                'status' => 'success',
                'data' => $notifications
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/notifications/read",
     *     summary="Read notifications",
     *     description="Read notifications",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="query",
     *         name="user_id",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error"
     *     )
     * )
     */
    public function read(Request $request)
    {
        try {
            $user = User::where('id', $request->user_id)->first();
            Notification::where('user_id', $user->id)->where('is_read', 0)->update(['is_read' => 1]);

            return response()->json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
