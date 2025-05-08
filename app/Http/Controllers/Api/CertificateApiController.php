<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseChapterItem;
use App\Models\CourseProgress;
use Illuminate\Support\Facades\Storage;
use Modules\Order\app\Models\Enrollment;

class CertificateApiController extends Controller
{
    public function getCertificatesForStudent(Request $request)
    {
        $user_id = $request->input('user_id');
        try {
            $enrollments = Enrollment::with([
                'course' => function ($q) {
                    $q->withTrashed();
                },
                'user' => function ($q) {
                    $q->select('id', 'name');
                }
            ])
                ->where('user_id', $user_id)
                ->orderByDesc('id')
                ->get();

            if ($enrollments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pelatihan yang terdaftar untuk user ID ' . $user_id,
                ], 404);
            }

            $certificates = [];

            foreach ($enrollments as $enrollment) {
                $course = $enrollment->course;

                if (!$course || $course->certificate != 1) {
                    continue;
                }

                $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })->count();

                $courseLectureCompletedByUser = CourseProgress::where('user_id', $user_id)
                    ->where('course_id', $course->id)
                    ->where('watched', 1)
                    ->count();

                $courseCompletedPercent = $courseLectureCount > 0 ? ($courseLectureCompletedByUser / $courseLectureCount) * 100 : 0;

                if ($courseCompletedPercent == 100) {
                    $completed_date = formatDate(CourseProgress::where('user_id', $user_id)
                        ->where('course_id', $course->id)
                        ->where('watched', 1)
                        ->latest()
                        ->first()->created_at);

                    // $certificate = CertificateBuilder::first();
                    // $certificateItems = CertificateBuilderItem::all();

                    // $html = view('frontend.student-dashboard.certificate.index', compact('certificateItems', 'certificate'))->render();

                    // $html = str_replace('[student_name]', $enrollment->user->name, $html);
                    // $html = str_replace('[platform_name]', Cache::get('setting')->app_name, $html);
                    // $html = str_replace('[course]', $course->title, $html);
                    // $html = str_replace('[date]', $completed_date, $html);
                    // $html = str_replace('[instructor_name]', $course->instructor->name, $html);

                    $certificates[] = [
                        'course' => $course->title,
                        // 'certificate_html' => $html,
                        'completed_date' => $completed_date,
                        'certificate_url' => route('student.download-certificate', $course->id)
                    ];
                }
            }

            if (count($certificates) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sertifikat tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar sertifikat ditemukan.',
                'data' => $certificates,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/bantara-callback/{enrollmentID}",
     *     summary="Post PDF file from Bantara",
     *     tags={"Bantara"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",   
     *                 @OA\Property(
     *                     property="file",
     *                     type="file",
     *                     format="binary",
     *                     description="PDF file from Bantara",
     *                 ),
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Document ID",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User information",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example="true"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="File uploaded successfully"
     *             ),
     *         )
     *     )
     * )
     */
    public function bantaraCallback(Enrollment $enrollment, Request $request)
    {
        // validate request header key
        $key = $request->header('Authorization');
        if (!$key) {
            return response(['success' => false, 'message' => 'Invalid request header key'], 403);
        }

        // trim bearer
        $key = str_replace('Bearer ', '', $key);

        // validate key
        if ($key !== appConfig('bantara_callback_key') || $key = '' || $key === null) {
            return response(['success' => false, 'message' => 'Invalid api key'], 403);
        }

        $file = $request->file('file');

        // check if file is pdf
        if ($file->getClientOriginalExtension() !== 'pdf') {
            return response(['success' => false, 'message' => 'File must be pdf'], 400);
        }

        // check if file size is less than 100mb
        if ($file->getSize() > 100 * 1024 * 1024) {
            return response(['success' => false, 'message' => 'File size must be less than 100mb'], 400);
        }

        $path = Storage::disk('private')->putFileAs(
            sprintf('certificates/%s', now()->year),
            $file,
            sprintf('%s-certificate.pdf', $enrollment->id),
        );

        if (!$path) {
            return response(['success' => false, 'message' => 'File upload failed'], 500);
        }

        $enrollment->certificate_path = $path;
        $enrollment->certificate_status = 'signed';
        $enrollment->save();

        return response(['success' => true, 'message' => 'File uploaded successfully'], 200);
    }
}
