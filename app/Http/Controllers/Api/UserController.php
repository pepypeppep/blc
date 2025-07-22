<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get list of users",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Keyword to search",
     *         in="query",
     *         name="keyword",
     *         required=false,
     *         example="John Doe",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $users = User::select('id', 'nip', 'name', 'jabatan', 'asn_status', 'instansi_id')
            ->with('instansi:id,name')
            ->where('name', 'like', '%' . $request->keyword . '%')
            ->where('is_banned', 'no')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }
}
