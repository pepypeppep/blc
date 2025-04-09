<?php

namespace Modules\Course\app\Http\Controllers;

use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\app\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use App\Events\UserBadgeUpdated;

class CourseVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $enrollmentUsers = Enrollment::with('user', 'course')
            ->where('course_id', $id)
            ->where(function ($query) {
                $query->whereNull('has_access');
            })
            ->get();

        $submenu = 'Verifikasi';

        return view('course::course-verification.index', compact('enrollmentUsers', 'submenu', 'id'));
    }

    public function updateEnrollmentStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'course_id' => 'required|exists:courses,id',
            'status' => 'nullable|in:0,1',
            'reason' => 'nullable|string'
        ]);

        foreach ($validated['user_ids'] as $userId) {
            Enrollment::where('user_id', $userId)
                ->where('course_id', $validated['course_id'])
                ->update([
                    'has_access' => $validated['status'],
                    'notes' => $validated['status'] == 0 ? $validated['reason'] : null,
                ]);

            if ($validated['status'] == 1) {
                $status = "Telah disetujui oleh Admin";
            } else {
                $status = "Telah ditolak oleh Admin";
            }

            // Send notification
            sendNotification([
                'user_id' => $userId,
                'title' => 'Permintaan bergabung pelatihan',
                'body' => "Permintaan bergabung pelatihan Anda " . $status,
                'link' => route('student.enrolled-courses'),
                'path' => [
                    'module' => 'course',
                    'id' => $validated['course_id']
                ]
            ]);
        }

        event(new UserBadgeUpdated($request->user_ids));

        return response()->json(['message' => 'Enrollment status updated successfully.']);
    }


    public function rejectedList($id)
    {
        $submenu = 'Verifikasi Rejected';
        $rejectedUsers = Enrollment::with('user', 'course')
            ->where('course_id', $id)
            ->where('has_access', 0)
            ->whereNotNull('has_access')
            ->get();

        return view('course::course-verification.rejected', compact('rejectedUsers', 'submenu'));
    }
}
