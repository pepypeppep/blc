<?php

namespace Modules\Course\app\Http\Controllers;

use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\app\Models\Enrollment;
use Illuminate\Http\JsonResponse;

class CourseVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $enrollmentUsers = Enrollment::with('user', 'course')->where('course_id', $id)->get();
        $submenu = 'Verifikasi';

        return view('course::course-verification.index', compact('enrollmentUsers', 'submenu'));
    }

    public function updateEnrollmentStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'status' => 'required|boolean'
        ]);

        Enrollment::whereIn('user_id', $validated['user_ids'])->update(['has_access' => $validated['status']]);

        return response()->json(['message' => 'Enrollment status updated successfully.']);
    }


}
