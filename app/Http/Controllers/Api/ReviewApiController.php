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
            $reviews = CourseReview::with('course:id,title,slug,thumbnail', 'user:id,name')
                ->where('user_id', $user_id)
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

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        try {
            $review = CourseReview::firstOrCreate([
                'course_id' => $request->course_id,
                'user_id' => $request->user_id,
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil disimpan.',
                'data' => $review,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
