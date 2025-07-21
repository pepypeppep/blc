<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringReview;
use Modules\Mentoring\app\Models\MentoringSession;

class MentorApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/mentoring/mentor",
     *     summary="Get mentor topics",
     *     description="Get mentor topics",
     *     tags={"Mentor"},
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
            $data = Mentoring::with('mentor:id,name', 'mentee:id,name')->where('mentor_id', $request->user()->id)->orderByDesc('id')->paginate(10);

            return $this->successResponse($data, 'Mentor topics fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/mentoring/mentor/{id}",
     *     summary="Get mentor topic by id",
     *     description="Get mentor topic by id",
     *     tags={"Mentor"},
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
            $mentoring = Mentoring::with('mentor:id,name', 'mentee:id,name', 'mentoringSessions')->where('mentor_id', $request->user()->id)->findOrFail($id);
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
     *     path="/mentoring/mentor/{id}/reject",
     *     summary="Reject mentoring by id",
     *     description="Reject mentoring by id",
     *     tags={"Mentor"},
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="reason",
     *                 type="string",
     *                 example="Alasan penolakan"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mentoring rejected successfully"
     *     )
     * )
     */
    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required',
            ], [
                'reason.required' => 'Alasan tidak boleh kosong',
            ]);

            $mentoring = Mentoring::where('id', $id)->where('mentor_id', $request->user()->id)->first();

            if (!$mentoring) {
                return $this->errorResponse('Mentoring not found', [], 404);
            }

            if ($mentoring->status != Mentoring::STATUS_SUBMISSION) {
                return $this->errorResponse('Penolakan hanya bisa dilakukan jika masih dalam status pengajuan', [], 400);
            }

            $mentoring->status = Mentoring::STATUS_REJECT;
            $mentoring->reason = $request->reason;
            $mentoring->save();

            // Send notification
            sendNotification([
                'user_id' => $mentoring->mentee_id,
                'title' => 'Pengajuan Mentoring Ditolak',
                'body' => "Pengajuan mentoring Anda untuk topik '{$mentoring->title}' telah ditolak oleh mentor, dengan alasan: {$request->reason}",
                'link' => route('student.mentee.show', $mentoring->id),
                'path' => [
                    'module' => 'mentoring',
                    'id' => $mentoring->id,
                ]
            ]);

            return $this->successResponse([], 'Berhasil menolak pengajuan mentoring');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/mentoring/mentor/{id}/approve",
     *     summary="Approve mentoring",
     *     description="Approve mentoring",
     *     tags={"Mentor"},
     *     security={{"bearer": {}}},
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
     *         description="Mentoring approved successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mentoring not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function approve(Request $request, $id)
    {
        try {
            $mentoring = Mentoring::where('id', $id)->where('mentor_id', $request->user()->id)->first();

            if (!$mentoring) {
                return $this->errorResponse('Mentoring not found', [], 404);
            }

            if ($mentoring->status != Mentoring::STATUS_SUBMISSION) {
                return $this->errorResponse('Penolakan hanya bisa dilakukan jika masih dalam status pengajuan', [], 400);
            }

            $mentoring->status = Mentoring::STATUS_PROCESS;
            $mentoring->save();

            // Send notification
            sendNotification([
                'user_id' => $mentoring->mentee_id,
                'title' => 'Pengajuan Mentoring Disetujui',
                'body' => "Pengajuan mentoring Anda untuk topik '{$mentoring->title}' telah disetujui oleh mentor.",
                'link' => route('student.mentee.show', $mentoring->id),
                'path' => [
                    'module' => 'mentoring',
                    'id' => $mentoring->id,
                ]
            ]);

            return $this->successResponse([], 'Mentoring berhasil disetujui');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/mentoring/mentor/{id}/review",
     *     summary="Memberikan ulasan mentoring",
     *     description="Memberikan ulasan mentoring",
     *     operationId="mentoringReview",
     *     tags={"Mentor"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="ID Mentoring Session",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="mentoring_date",
     *                 type="string",
     *                 format="date-time",
     *                 example="2022-01-01 00:00:00"
     *             ),
     *             @OA\Property(
     *                 property="mentoring_note",
     *                 type="string",
     *                 example="Catatan mentoring"
     *             ),
     *             @OA\Property(
     *                 property="mentoring_instructions",
     *                 type="string",
     *                 example="Instruksi mentoring"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil memberikan ulasan"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mentoring not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function review(Request $request, $id)
    {
        try {
            $request->validate([
                'mentoring_date' => 'required',
                'mentoring_note' => 'required',
                'mentoring_instructions' => 'required',
            ], [
                'mentoring_date.required' => 'Tanggal mentoring tidak boleh kosong',
                'mentoring_note.required' => 'Catatan mentoring tidak boleh kosong',
                'mentoring_instructions.required' => 'Instruksi mentoring tidak boleh kosong',
            ]);

            $session = MentoringSession::with('mentoring')->where('id', $id)->first();

            if (!$session) {
                return $this->errorResponse('Sesi mentoring tidak ditemukan', [], 404);
            }

            if ($session->status != MentoringSession::STATUS_REPORTED) {
                return $this->errorResponse('Sesi mentoring belum ada laporan', [], 404);
            }

            $mentoring = Mentoring::where('id', $session->mentoring_id)->where('mentor_id', $request->user()->id)->first();

            if (!$mentoring) {
                return $this->errorResponse('Mentoring not found', [], 404);
            }

            if ($mentoring->status != Mentoring::STATUS_PROCESS) {
                return $this->errorResponse('Review hanya bisa dilakukan jika mentoring dalam status proses', [], 400);
            }

            $session->mentoring_note = $request->mentoring_note;
            $session->mentoring_instructions = $request->mentoring_instructions;
            $session->status = MentoringSession::STATUS_REVIEWED;
            $session->save();

            // Send notification
            sendNotification([
                'user_id' => $mentoring->mentee_id,
                'title' => 'Mentoring telah Direview oleh Mentor',
                'body' => "Sesi mentoring Anda untuk topik '{$mentoring->title}' telah direview oleh mentor.",
                'link' => route('student.mentee.show', $mentoring->id),
                'path' => [
                    'module' => 'mentoring',
                    'id' => $mentoring->id,
                ]
            ]);

            $sessionCount = MentoringSession::where('mentoring_id', $mentoring->id)
                ->where('status', MentoringSession::STATUS_REVIEWED)
                ->count();

            if ($sessionCount == count($mentoring->mentoringSessions)) {
                $mentoring->status = Mentoring::STATUS_EVALUATION;
                $mentoring->save();
            }

            return $this->successResponse([], 'Berhasil memberikan ulasan');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/mentoring/mentor/{id}/store-evaluation",
     *     summary="Kirim evaluasi mentoring",
     *     description="Kirim evaluasi mentoring",
     *     tags={"Mentor"},
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
     *         description="Evaluasi mentoring",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"target", "target_description", "tingkat_disiplin", "disiplin_description", "kerjasama", "kerjasama_description", "inisiatif", "inisiatif_description", "penguasaan_materi", "penguasaan_materi_description"},
     *             @OA\Property(property="target", type="integer", example=1, description="Target"),
     *             @OA\Property(property="target_description", type="string", example="Target description", description="Deskripsi target"),
     *             @OA\Property(property="tingkat_disiplin", type="integer", example=80, description="Tingkat disiplin"),
     *             @OA\Property(property="disiplin_description", type="string", example="Deskripsi disiplin", description="Deskripsi disiplin"),
     *             @OA\Property(property="kerjasama", type="integer", example=80, description="Kerjasama"),
     *             @OA\Property(property="kerjasama_description", type="string", example="Deskripsi kerjasama", description="Deskripsi kerjasama"),
     *             @OA\Property(property="inisiatif", type="integer", example=80, description="Inisiatif"),
     *             @OA\Property(property="inisiatif_description", type="string", example="Deskripsi inisiatif", description="Deskripsi inisiatif"),
     *             @OA\Property(property="penguasaan_materi", type="integer", example=80, description="Penguasaan materi"),
     *             @OA\Property(property="penguasaan_materi_description", type="string", example="Deskripsi penguasaan materi", description="Deskripsi penguasaan materi"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluasi berhasil dikirim",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Evaluasi gagal dikirim",
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
     *         description="Evaluasi gagal dikirim",
     *     ),
     * )
     */
    public function evaluasiStore(Request $request, $id)
    {
        try {
            $request->validate([
                'target' => 'required|in:0,1',
                'target_description' => 'required|string',
                'tingkat_disiplin' => 'required|integer|min:1|max:100',
                'disiplin_description' => 'required|string',
                'kerjasama' => 'required|integer|min:1|max:100',
                'kerjasama_description' => 'required|string',
                'inisiatif' => 'required|integer|min:1|max:100',
                'inisiatif_description' => 'required|string',
                'penguasaan_materi' => 'required|integer|min:1|max:100',
                'penguasaan_materi_description' => 'required|string',
            ], [
                'target.required' => 'Target tidak boleh kosong',
                'target.in' => 'Target harus 0 (Tidak) atau 1 (Ya)',
                'target_description.required' => 'Deskripsi target tidak boleh kosong',
                'tingkat_disiplin.required' => 'Tingkat disiplin tidak boleh kosong',
                'tingkat_disiplin.min' => 'Tingkat disiplin minimal 1',
                'tingkat_disiplin.max' => 'Tingkat disiplin maksimal 100',
                'disiplin_description.required' => 'Deskripsi disiplin tidak boleh kosong',
                'kerjasama.required' => 'Kerjasama tidak boleh kosong',
                'kerjasama.min' => 'Kerjasama minimal 1',
                'kerjasama.max' => 'Kerjasama maksimal 100',
                'kerjasama_description.required' => 'Deskripsi kerjasama tidak boleh kosong',
                'inisiatif.required' => 'Inisiatif tidak boleh kosong',
                'inisiatif.min' => 'Inisiatif minimal 1',
                'inisiatif.max' => 'Inisiatif maksimal 100',
                'inisiatif_description.required' => 'Deskripsi inisiatif tidak boleh kosong',
                'penguasaan_materi.required' => 'Penguasaan materi tidak boleh kosong',
                'penguasaan_materi.integer' => 'Penguasaan materi harus berupa angka',
                'penguasaan_materi.min' => 'Penguasaan materi minimal 1',
                'penguasaan_materi.max' => 'Penguasaan materi maksimal 100',
                'penguasaan_materi_description.required' => 'Deskripsi penguasaan materi tidak boleh kosong',
            ]);

            $mentoring = Mentoring::where('id', $id)->where('mentor_id', $request->user()->id)->first();

            if (!$mentoring) {
                return $this->errorResponse('Mentoring not found', [], 404);
            }

            if ($mentoring->status != Mentoring::STATUS_EVALUATION) {
                return $this->errorResponse('Evaluasi hanya bisa dilakukan jika mentoring dalam status penilaian', [], 400);
            }

            $review = MentoringReview::where('mentoring_id', $mentoring->id)->first();
            if (!$review) {
                $review = new MentoringReview();
                $review->mentoring_id = $mentoring->id;
                $review->is_target = $request->target;
                $review->target_description = $request->target_description;
                $review->discipline = $request->tingkat_disiplin;
                $review->discipline_description = $request->disiplin_description;
                $review->teamwork = $request->kerjasama;
                $review->teamwork_description = $request->kerjasama_description;
                $review->initiative = $request->inisiatif;
                $review->initiative_description = $request->inisiatif_description;
                $review->material_mastery = $request->penguasaan_materi;
                $review->material_mastery_description = $request->penguasaan_materi_description;
                $review->save();
            }

            $mentoring->status = Mentoring::STATUS_DONE;
            $mentoring->save();

            return $this->successResponse([], 'Evaluasi berhasil dikirim');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/mentoring/mentor/{id}/evaluasi",
     *     summary="Menampilkan data mentoring untuk evaluasi",
     *     description="Menampilkan data mentoring untuk evaluasi",
     *     operationId="mentoringEvaluasi",
     *     tags={"Mentor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="ID mentoring",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menampilkan data mentoring"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Mentoring tidak ditemukan atau status tidak valid"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mentoring not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function evaluasi(Request $request, $id)
    {
        try {
            $mentoring = Mentoring::with('mentor:id,name', 'mentee:id,name')->where('id', $id)->where('mentor_id', $request->user()->id)->first();

            if (!$mentoring) {
                return $this->errorResponse('Mentoring not found', [], 404);
            }

            if ($mentoring->status != Mentoring::STATUS_EVALUATION && $mentoring->status != Mentoring::STATUS_DONE) {
                return $this->errorResponse('Evaluasi hanya bisa dilakukan jika mentoring dalam status penilaian', [], 400);
            }

            $review = MentoringReview::where('mentoring_id', $mentoring->id)->first();

            return $this->successResponse(['mentoring' => $mentoring, 'review' => $review], 'Berhasil menampilkan data mentoring');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/mentoring/mentor/update-session",
     *     summary="Update mentoring session",
     *     description="Update mentoring session",
     *     tags={"Mentor"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Session and new mentoring date",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"session_id", "mentoring_date"},
     *             @OA\Property(property="session_id", type="integer", example=1),
     *             @OA\Property(property="mentoring_date", type="string", example="2022-01-01 09:00:00", format="date-time"),
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
                'mentoring_date' => 'required|date',
                'session_id' => 'required|integer',
            ], [
                'mentoring_date.required' => 'Tanggal mentoring tidak boleh kosong',
                'session_id.required' => 'ID sesi tidak boleh kosong',
            ]);

            $session = MentoringSession::whereHas('Mentoring', function ($query) use ($request) {
                $query->where('mentor_id', $request->user()->id);
            })->where('id', $request->session_id)->first();

            if (!$session) {
                return $this->errorResponse('Sesi mentoring tidak ditemukan', [], 404);
            }

            if ($session->status != MentoringSession::STATUS_PENDING) {
                return $this->errorResponse('Tanggal mentoring hanya bisa diubah saat mentor belum menerima sesi', [], 400);
            }

            $session->mentoring_date = $request->mentoring_date;
            $session->save();

            return $this->successResponse([], 'Sesi mentoring berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
