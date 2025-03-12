<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseReview;

class ReviewApiController extends Controller
{
    public function reviews(Request $request)
    {
        $user_id = $request->input('user_id');
        try {
            $reviews = CourseReview::where('user_id', $user_id)
                ->orderByDesc('id')
                ->get();

            if ($reviews->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada ulasan ditemukan untuk user ID ' . $user_id,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar ulasan yang pernah diberikan.',
                'data' => $reviews,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
