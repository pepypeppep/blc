<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringSession;
use Modules\Mentoring\app\Models\MentoringFeedback;

class MenteeApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/mentoring/mentee",
     *     summary="Get mentor topics",
     *     description="Get mentor topics",
     *     tags={"Mentee"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $data = Mentoring::with('mentor:id,name', 'mentee:id,name')->where('mentee_id', $request->user()->id)->orderByDesc('id')->paginate(10);

            return $this->successResponse($data, 'Mentor topics fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/mentoring/mentee/{id}",
     *     summary="Get mentor topic by id",
     *     description="Get mentor topic by id",
     *     tags={"Mentee"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Mentoring id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        try {
            $mentoring = Mentoring::with('mentor:id,name', 'mentee:id,name', 'mentoringSessions')->where('mentee_id', $request->user()->id)->findOrFail($id);
            $hasIncompleteSessions = $mentoring->mentoringSessions->contains(function ($session) {
                return empty($session->activity);
            });
            return $this->successResponse(['mentoring' => $mentoring, 'hasIncompleteSessions' => $hasIncompleteSessions], 'Mentor topics fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/mentoring/mentee",
     *     summary="Create mentoring topic",
     *     description="Create mentoring topic",
     *     tags={"Mentee"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title", "main_issue", "purpose", "total_session", "sessions", "mentor", "file"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Judul mentoring"
     *                 ),
     *                 @OA\Property(
     *                     property="main_issue",
     *                     type="string",
     *                     example="Isu utama mentoring"
     *                 ),
     *                 @OA\Property(
     *                     property="purpose",
     *                     type="string",
     *                     example="Tujuan mentoring"
     *                 ),
     *                 @OA\Property(
     *                     property="total_session",
     *                     type="integer",
     *                     example=3
     *                 ),
     *                 @OA\Property(
     *                     property="sessions",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="date-time",
     *                         example="2025-07-01 08:00:00"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="mentor",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $user = User::findOrFail($request->user()->id);
            $request->merge([
                'sessions' => explode(',', $request->input('sessions'))
            ]);
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'main_issue' => 'required|string',
                'purpose' => 'required|string',
                'total_session' => 'required|integer|min:3|max:24',
                'sessions' => 'required|array|min:3|max:24',
                'sessions.*' => 'required|date',
                'mentor' => 'required|exists:users,id',
                'file' => 'required|file|mimes:pdf|max:5120',
            ]);

            //Cek ketentuan pelaksanaan pertemuan
            $monthlyCount = [];
            foreach ($validated['sessions'] as $session) {
                $monthKey = \Carbon\Carbon::parse($session)->format('Y-m');

                if (!isset($monthlyCount[$monthKey])) {
                    $monthlyCount[$monthKey] = 0;
                }

                $monthlyCount[$monthKey]++;

                if ($monthlyCount[$monthKey] > 2) {
                    return $this->errorResponse('Maaf Anda hanya diperbolehkan mengajukan maksimal 2 pertemuan dalam satu bulan. Permintaan Anda melebihi batas yang telah ditentukan.', [], 500);
                }
            }

            $mentoring = Mentoring::create([
                'title' => $validated['title'],
                'description' => $validated['main_issue'],
                'purpose' => $validated['purpose'],
                'total_session' => $validated['total_session'],
                'mentor_id' => $validated['mentor'],
                'mentee_id' => $user->id,
                'status' => Mentoring::STATUS_DRAFT,
            ]);

            if ($request->hasFile('file')) {
                $path = 'mentoring/' . now()->year . '/' . $mentoring->id . '/';
                $file = $request->file('file');
                $fileName = $path . 'mentor_letter.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));

                $mentoring->update([
                    'mentor_availability_letter' => $fileName
                ]);
            }

            foreach ($validated['sessions'] as $dateTime) {
                MentoringSession::create([
                    'mentoring_id' => $mentoring->id,
                    'mentoring_date' => $dateTime,
                ]);
            }

            return $this->successResponse([], 'Tema mentoring berhasil ditambahkan!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/mentoring/mentee/{id}/submit-approval",
     *     summary="Submit mentoring for approval",
     *     description="Submit mentoring for approval",
     *     tags={"Mentee"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Mentoring id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mentoring submitted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error submitting mentoring"
     *     )
     * )
     */
    public function submitForApproval(Request $request, $id)
    {
        try {
            $mentoring = Mentoring::where('mentee_id', $request->user()->id)->findOrFail($id);
            if ($mentoring->status !== Mentoring::STATUS_DRAFT) {
                return $this->errorResponse('Mentoring sudah diajukan.', [], 500);
            }

            $mentoring->status = Mentoring::STATUS_SUBMISSION;
            $mentoring->updated_at = now();
            $mentoring->save();

            //kirim notifikasi
            sendNotification([
                'user_id' => $mentoring->mentor_id,
                'title' => 'Pengajuan Mentoring Baru',
                'body' => "Seorang mentee telah mengajukan permohonan mentoring. Silakan tinjau dan tindak lanjuti.",
                'link' => route('student.mentor.show', $mentoring->id),
                'path' => [
                    'module' => 'mentoring',
                    'id' => $mentoring->id,
                ]
            ]);
            return $this->successResponse([], 'Mentoring berhasil diajukan!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/mentoring/mentee/update-session",
     *     summary="Update mentoring session",
     *     description="Update mentoring session",
     *     tags={"Mentee"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Session and Image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"session_id", "activity", "obstacle","image"},
     *                 @OA\Property(property="session_id", type="integer", example=1),
     *                 @OA\Property(property="activity", type="string", example="Belajar Laravel"),
     *                 @OA\Property(property="obstacle", type="string", example="Tidak ada hambatan"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Session updated successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error updating session"
     *     )
     * )
     */
    public function updateSession(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|exists:mentoring_sessions,id',
                'activity' => 'required|string',
                'obstacle' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png|max:2048',
            ]);

            $session = MentoringSession::with('mentoring')->findOrFail($request->session_id);
            if ($session->mentoring->status != Mentoring::STATUS_PROCESS) {
                return $this->errorResponse('Mentoring belum disetujui oleh Mentor.', [], 500);
            }

            $session->activity = $request->activity;
            $session->description = $request->obstacle;

            if ($request->hasFile('image')) {
                $path = 'mentoring/' . now()->year . '/' . $session->mentoring_id . '/';
                $img = $request->file('image');
                $fileName = $path . 'documentation/' . $session->id . "." . $img->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($img));
                $session->image = $fileName;
            }

            $session->status = MentoringSession::STATUS_REPORTED;
            $session->save();

            //kirim notifikasi
            sendNotification([
                'user_id' => $session->mentoring->mentor_id,
                'title' => 'Laporan Pertemuan Baru',
                'body' => "Mentee telah melaporkan hasil pertemuan. Silakan periksa laporan tersebut.",
                'link' => route('student.mentor.show', $session->mentoring->id),
                'path' => [
                    'module' => 'mentoring',
                    'id' => $session->mentoring->id,
                ]
            ]);

            return $this->successResponse([], 'Detail pertemuan berhasil diperbarui!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/mentoring/mentee/{id}/final-report",
     *     summary="Update final report",
     *     description="Update final report",
     *     tags={"Mentee"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Mentoring ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Final report PDF",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"final_report"},
     *                 @OA\Property(property="final_report", type="string", format="binary"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Final report updated successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error updating final report"
     *     )
     * )
     */
    public function updateFinalReport(Request $request, $id)
    {
        try {
            $request->validate([
                'final_report' => 'required|file|mimes:pdf|max:5120',
            ]);

            $mentoring = Mentoring::with('mentoringSessions')->where('mentee_id', $request->user()->id)->findOrFail($id);
            if (count($mentoring->mentoringSessions) != count($mentoring->mentoringSessions->where('status', MentoringSession::STATUS_REVIEWED))) {
                return $this->errorResponse('Mentor belum menanggapi semua pertemuan.', [], 500);
            }

            if ($request->hasFile('final_report')) {
                $path = 'mentoring/' . now()->year . '/' . $mentoring->id . '/';
                $file = $request->file('final_report');
                $fileName = $path . 'final_report.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));

                $mentoring->update([
                    'final_report' => $fileName,
                    'status' => Mentoring::STATUS_EVALUATION
                ]);
            }

            //kirim notifikasi
            sendNotification([
                'user_id' => $mentoring->mentor_id,
                'title' => 'Laporan Akhir Telah Diunggah',
                'body' => "Mentee telah mengunggah laporan akhir mentoring. Silakan periksa dokumen tersebut.",
                'link' => route('student.mentor.show', $mentoring->id),
                'path' => [
                    'module' => 'mentoring',
                    'id' => $mentoring->id,
                ]
            ]);

            return $this->successResponse([], 'Laporan akhir berhasil diunggah!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function showDocument($id, $type)
    {
        $mentoring = Mentoring::findOrFail($id);
        if (Storage::disk('private')->exists($mentoring->$type)) {
            return response()->file(Storage::disk('private')->path($mentoring->$type));
        } else {
            return null;
        }
    }

    public function showDocumentSession($id, $type)
    {
        $mentoring = MentoringSession::findOrFail($id);
        if (Storage::disk('private')->exists($mentoring->$type)) {
            return response()->file(Storage::disk('private')->path($mentoring->$type));
        } else {
            return null;
        }
    }

    /**
     * @OA\Post(
     *     path="/mentoring/mentee/{id}/store-feedback",
     *     summary="Kirim penilaian mentor",
     *     description="Kirim penilaian mentor",
     *     tags={"Mentee"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="ID mentoring",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nilai Mentor (Feedback)",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"mentoring_ability", "punctuality_attendance", "method_media_usage", "attitude_behavior", "inspirational_ability", "motivational_ability", "feedback_description"},
     *             @OA\Property(property="mentoring_ability", type="integer", example=80, description="Kemampuan mentoring"),
     *             @OA\Property(property="punctuality_attendance", type="integer", example="80", description="Ketepatan Waktu dan Kehadiran"),
     *             @OA\Property(property="method_media_usage", type="integer", example=80, description="Penggunaan Metode dan Media Pembimbing"),
     *             @OA\Property(property="attitude_behavior", type="integer", example=80, description="Sikap dan Perilaku"),
     *             @OA\Property(property="inspirational_ability", type="integer", example=80, description="Pemberian Inspirasi"),
     *             @OA\Property(property="motivational_ability", type="integer", example=80, description="Pemberian Motivasi"),
     *             @OA\Property(property="feedback_description", type="string", example="Deskripsi penilaian, catatan, saran", description="Catatan/saran"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Penilaian berhasil dikirim",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Penilaian gagal dikirim",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mentoring not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Penilaian gagal dikirim",
     *     ),
     * )
     */
    public function feedbackStore(Request $request, $id)
    {
        $request->validate([
            'mentoring_ability' => 'required|integer|min:1|max:100',
            'punctuality_attendance' => 'required|integer|min:1|max:100',
            'method_media_usage' => 'required|integer|min:1|max:100',
            'attitude_behavior' => 'required|integer|min:1|max:100',
            'inspirational_ability' => 'required|integer|min:1|max:100',
            'motivational_ability' => 'required|integer|min:1|max:100',
            'feedback_description' => 'required|string',
        ], [
            'mentoring_ability.required' => 'Kemampuan mentoring tidak boleh kosong',
            'mentoring_ability.min' => 'Kemampuan mentoring minimal 1',
            'mentoring_ability.max' => 'Kemampuan mentoring maksimal 100',
            'punctuality_attendance.required' => 'Ketepatan Waktu dan Kehadiran tidak boleh kosong',
            'punctuality_attendance.min' => 'Ketepatan Waktu dan Kehadiran minimal 1',
            'punctuality_attendance.max' => 'Ketepatan Waktu dan Kehadiran maksimal 100',
            'method_media_usage.required' => 'Penggunaan Metode dan Media Pembimbing tidak boleh kosong',
            'method_media_usage.min' => 'Penggunaan Metode dan Media Pembimbing minimal 1',
            'method_media_usage.max' => 'Penggunaan Metode dan Media Pembimbing maksimal 100',
            'attitude_behavior.required' => 'Sikap dan Perilaku tidak boleh kosong',
            'attitude_behavior.min' => 'Sikap dan Perilaku minimal 1',
            'attitude_behavior.max' => 'Sikap dan Perilaku maksimal 100',
            'inspirational_ability.required' => 'Pemberian Inspirasi tidak boleh kosong',
            'inspirational_ability.min' => 'Pemberian Inspirasi minimal 1',
            'inspirational_ability.max' => 'Pemberian Inspirasi maksimal 100',
            'motivational_ability.required' => 'Pemberian Motivasi tidak boleh kosong',
            'motivational_ability.min' => 'Pemberian Motivasi minimal 1',
            'motivational_ability.max' => 'Pemberian Motivasi maksimal 100',
            'feedback_description.required' => 'Deskripsi penilaian tidak boleh kosong',
        ]);

        $mentoring = Mentoring::where('id', $id)->where('mentee_id', $request->user()->id)->first();

        if (!$mentoring) {
            return $this->errorResponse('Mentoring not found.', [], 404);
        }

        if (empty($mentoring->final_report)) {
            return $this->errorResponse('Penilaian untuk mentor hanya bisa dilakukan jika laporan akhir telah diunggah.', [], 500);
        }

        $feedback = MentoringFeedback::firstOrNew(['mentoring_id' => $mentoring->id]);
        $feedback->fill([
            'mentoring_ability'       => $request->mentoring_ability,
            'punctuality_attendance'  => $request->punctuality_attendance,
            'method_media_usage'      => $request->method_media_usage,
            'attitude_behavior'       => $request->attitude_behavior,
            'inspirational_ability'   => $request->inspirational_ability,
            'motivational_ability'    => $request->motivational_ability,
            'feedback_description'    => $request->feedback_description,
            'mentor_id'               => $mentoring->mentor_id,
        ]);
        $feedback->save();

        return $this->successResponse([], 'Penilaian untuk mentor berhasil dilakukan');
    }
}
