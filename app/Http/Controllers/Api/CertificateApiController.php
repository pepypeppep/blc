<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseProgress;
use App\Models\CourseChapterItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Order\app\Models\Enrollment;
use Modules\CertificateRecognition\app\Models\CertificateRecognitionEnrollment;

class CertificateApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/certificates",
     *     summary="Get certificates for student",
     *     tags={"Certificates"},
     *     @OA\Parameter(
     *         description="User ID of student",
     *         in="query",
     *         name="user_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Daftar sertifikat ditemukan."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="courses",
     *                         type="object",
     *                         @OA\Property(
     *                             property="course",
     *                             type="string",
     *                             example="Pelatihan 1"
     *                         ),
     *                         @OA\Property(
     *                             property="completed_date",
     *                             type="string",
     *                             format="date-time",
     *                             example="2022-01-01 00:00:00"
     *                         ),
     *                         @OA\Property(
     *                             property="certificate_url",
     *                             type="string",
     *                             format="uri",
     *                             example="https://example.com/certificates/1"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="mentoring",
     *                         type="array",
     *                         items={}
     *                     ),
     *                     @OA\Property(
     *                         property="coaching",
     *                         type="array",
     *                         items={}
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Tidak ada pelatihan yang terdaftar untuk user ID 1"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Terjadi kesalahan: "
     *             )
     *         )
     *     )
     * )
     */
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
                        'courses' => [
                            'course' => $course->title,
                            'completed_date' => $completed_date,
                            'certificate_url' => route('student.download-certificate', $course->id)
                        ],
                        'mentoring' => [],
                        'coaching' => [],
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

    public function getStudentCertificates(Request $request)
    {
        $user_id = $request->input('user_id');
        try {
            $certificates = CertificateRecognitionEnrollment::with('user:id,name', 'certificateRecognition.certificate')->where('user_id', $user_id)->orderByDesc('id')->get();
            foreach ($certificates as $key => $cert) {
                $certId = $cert->certificateRecognition->certificate->id;
                $courseIds = Course::where('certificate_id', $certId)->get()->pluck('id');
                $enrollments = Enrollment::where('user_id', $user_id)->whereIn('course_id', $courseIds)->get();
                $courseId = $enrollments->first()->course_id;

                $certificates[$key]->certificate_url = route('student.download-certificate', $courseId);
            }
            if (count($certificates) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sertifikat tidak ditemukan.',
                ], 200);
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
        if ($key !== env('BANTARA_CALLBACK_KEY') || $key = '' || $key === null) {
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
