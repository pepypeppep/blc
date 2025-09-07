<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingSession;
use Modules\Coaching\app\Models\CoachingSessionDetail;
use Modules\Coaching\app\Models\CoachingUser;

class CoacheeApiController extends Controller
{
    use ApiResponse;

    // List Coaching
    /**
     * @OA\Get(
     *     path="/coaching/coachee",
     *     tags={"Coachee"},
     *     summary="List Coaching Sessions",
     *     description="Retrieve a list of coaching sessions for the coachee",
     *     operationId="listCoachingSessions",
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="List of coaching sessions")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Logic to list coaching sessions for the coachee
        try {
            $dataQuery = Coaching::with(['coachees' => function ($q) use ($request) {
                $q->select('users.id', 'name', 'email')->where('users.id', $request->user()->id);
            }, 'coach:id,name,email'])->whereHas('coachees', function ($q) use ($request) {
                $q->where('users.id', $request->user()->id);
            })->where('status', '!=', 'draft');

            if ($request->has('search')) {
                $dataQuery->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('coach', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    });
            }

            $coachingSessions = $dataQuery->paginate(10);

            return $this->successResponse($coachingSessions, 'List of coaching sessions');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    // Detail Coaching
    /**
     * @OA\Get(
     *     path="/coaching/coachee/{id}",
     *     tags={"Coachee"},
     *     summary="Get Coaching Session Details",
     *     description="Retrieve details of a specific coaching session",
     *     operationId="getCoachingSessionDetails",
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Coaching session details"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        // Logic to show details of a specific coaching session
        try {
            $coaching = Coaching::with([
                'coach:id,name',
                'coachees:id,name,email',
                'coachingSessions.details' => function ($q) use ($request) {
                    $q->whereHas('coachingUser', function ($q) use ($request) {
                        $q->where('user_id', $request->user()->id);
                    })->with(['coachingUser' => function ($q) use ($request) {
                        $q->where('user_id', $request->user()->id);
                    }]);
                }
            ])
                ->where('status', '!=', 'draft')
                ->whereHas('coachees', function ($q) use ($request) {
                    $q->where('users.id', $request->user()->id);
                })
                ->findOrFail($id);

            return $this->successResponse($coaching, 'Coaching session details');
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this->errorResponse('Coaching session not found', [], 404);
            }

            return $this->errorResponse($e->getMessage(), [], 404);
        }
    }


    // Approval Konsensus coaching
    /**
     * @OA\Post(
     *     path="/coaching/coachee/{id}/approval",
     *     tags={"Coachee"},
     *     summary="Approve or Reject Coaching Session",
     *     description="Approve or reject a coaching session",
     *     operationId="approveCoachingSession",
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="is_approved", type="boolean"),
     *             @OA\Property(property="reason", type="string", example="Not available at this time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="You have approved the coaching session")
     *         )
     *     )
     * )
     */
    public function approval(Request $request, $id)
    {
        $request->validate([
            'is_approved' => 'required|boolean',
            'reason' => 'nullable|string|max:255|required_if:is_approved,false',
        ], [
            'reason.required' => 'Alasan harus diisi.',
            'is_approved.required' => 'Status persetujuan harus diisi.',
            'is_approved.boolean' => 'Status persetujuan harus berupa true atau false.',
        ]);
        // Logic to approve consensus for a coaching session
        try {
            $coachingUser = CoachingUser::with('coaching')->where('user_id', $request->user()->id)->where('coaching_id', $id)->first();

            if ($coachingUser->coaching->status == 'Draft') {
                return $this->errorResponse('Coaching tidak tersedia.', [], 403);
            }

            if (!$coachingUser) {
                return $this->errorResponse('Anda tidak memiliki izin untuk mengakses Coaching ini.', [], 403);
            }

            if ($coachingUser->isRejected()) {
                return $this->errorResponse('Anda tidak bisa bergabung Coaching ini, karena anda sudah menolak Coaching ini.', [], 403);
            }

            if ($coachingUser->is_joined == 1) {
                return $this->errorResponse('Anda sudah bergabung Coaching ini.', [], 403);
            }

            $message = $request->is_approved ? __('Anda telah menyetujui Coaching ini') : __('Anda telah menolak Coaching ini');

            if ($request->is_approved) {
                $coachingUser->is_joined = 1;
                $coachingUser->joined_at = now();
                $coachingUser->notes = null;
            } else {
                $coachingUser->is_joined = 0;
                $coachingUser->notes = $request->reason;
            }
            $coachingUser->save();

            return $this->successResponse(null, $message);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }
    }

    // Update session coaching
    /**
     * @OA\Post(
     *     path="/coaching/coachee/update-session",
     *     tags={"Coachee"},
     *     summary="Update Coaching Session Report",
     *     description="Update the report for a coaching session",
     *     operationId="updateCoachingSessionReport",
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"session_id", "activity", "image"},
     *               @OA\Property(property="session_id", type="integer"),
     *               @OA\Property(property="activity", type="string"),
     *               @OA\Property(property="image", type="string", format="binary")
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Report successfully saved")
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {

        $request->validate([
            'session_id' => 'required',
            'activity' => 'required',
        ], [
            'session_id.required' => 'Sesi harus dipilih.',
            'activity.required' => 'Kegiatan harus dipilih.',
        ]);

        if (!$request->hasFile('image')) {
            return $this->errorResponse(__('File laporan kegiatan harus diunggah.'), [], 400);
        }

        if (!$request->file('image')->isValid()) {
            return $this->errorResponse(__('File laporan kegiatan tidak valid.'), [], 400);
        }

        // check file type
        $allowedFileTypes = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $fileExtension = $request->file('image')->getClientOriginalExtension();
        if (!in_array($fileExtension, $allowedFileTypes)) {
            return $this->errorResponse(__('Hanya file gambar yang diperbolehkan untuk laporan kegiatan.'), [], 400);
        }

        // check file size
        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($request->file('image')->getSize() > $maxFileSize) {
            return $this->errorResponse(__('Ukuran file laporan akhir tidak boleh lebih dari 2 MB.'), [], 400);
        }

        $user = $request->user();
        $details = CoachingSessionDetail::with('session.coaching.coachingSessions')->where('coaching_session_id', $request->session_id)->where('coaching_user_id', $user->id)->first();

        if (!$details) {
            $season = CoachingSession::with('coaching.coachingSessions')->where('id', $request->session_id)->first();

            if ($season->coaching->status != Coaching::STATUS_PROCESS) {
                return $this->errorResponse(__('Pengisian laporan hanya dapat dilakukan pada sesi Coaching yang sedang berlangsung.'), [], 400);
            }

            $coachingUser = CoachingUser::where('user_id', $user->id)->where('coaching_id', $season->coaching_id)->where('is_joined', 1)->first();

            if (!$coachingUser) {
                return $this->errorResponse(__('Anda tidak memiliki izin untuk mengakses Coaching ini.'), [], 403);
            }

            if ($coachingUser->is_joined == 0) {
                return $this->errorResponse(__('Anda belum bergabung Coaching ini.'), [], 403);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = 'coaching/' . $season->coaching_id . '/' . now()->year . '/' . $season->id . '/coaching_session' . $season->coaching->coachingSessions->count() . '.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));
                $request->merge(['fileName' => $fileName]);
            }

            $result = CoachingSessionDetail::create([
                'coaching_session_id' => $season->id,
                'coaching_user_id' => $coachingUser->id,
                'activity' => "Pertemuan " . $season->coaching->coachingSessions->count(),
                'description' => $request->activity,
                'image' => $request->fileName,
            ]);

            if ($result) {
                return $this->successResponse(null, __('Laporan berhasil disimpan'));
            }

            return $this->errorResponse(__('Laporan gagal disimpan'), [], 500);
        }

        if ($details->session->coaching->status != Coaching::STATUS_PROCESS) {
            return $this->errorResponse(__('Pengisian laporan hanya dapat dilakukan pada sesi Coaching yang sedang berlangsung.'), [], 400);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'coaching/' . $details->session->coaching_id . '/' . now()->year . '/' . $details->session->id . '/coaching_session' . $details->session->coaching->coachingSessions->count() . '.' . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));
            $request->merge(['fileName' => $fileName]);
        }

        $result = $details->update([
            'description' => $request->activity,
            'image' => $request->fileName,
        ]);


        if ($result) {
            return $this->successResponse(null, __('Laporan berhasil disimpan'));
        }

        return $this->errorResponse(__('Laporan gagal disimpan'), [], 500);
    }

    // Pengajuan laporan final mentoring
    /**
     * @OA\Post(
     *     path="/coaching/coachee/submit-final-report/{coachingId}",
     *     tags={"Coachee"},
     *     summary="Submit Final Report for Coaching",
     *     description="Submit the final report for a coaching session",
     *     operationId="submitFinalReport",
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="coachingId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"final_report"},
     *               @OA\Property(property="final_report", type="string", format="binary")
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Laporan berhasil disimpan")
     *         )
     *     )
     * )
     */
    public function submitFinalReport(Request $request, $coachingId)
    {
        if (!$request->hasFile('final_report')) {
            return $this->errorResponse(__('File laporan akhir harus diunggah.'), [], 400);
        }

        if (!$request->file('final_report')->isValid()) {
            return $this->errorResponse(__('File laporan akhir tidak valid.'), [], 400);
        }

        // check file type
        $allowedFileTypes = ['pdf'];
        $fileExtension = $request->file('final_report')->getClientOriginalExtension();
        if (!in_array($fileExtension, $allowedFileTypes)) {
            return $this->errorResponse(__('Hanya file PDF yang diperbolehkan untuk laporan akhir.'), [], 400);
        }

        // check file size
        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        if ($request->file('final_report')->getSize() > $maxFileSize) {
            return $this->errorResponse(__('Ukuran file laporan akhir tidak boleh lebih dari 5 MB.'), [], 400);
        }

        try {
            $user = $request->user();
            $coachingUser = CoachingUser::where('user_id', $user->id)->where('coaching_id', $coachingId)->first();

            if (!$coachingUser) {
                return $this->errorResponse(__('Anda belum bergabung Coaching ini.'), [], 403);
            }

            $details = CoachingSessionDetail::with(['session.coaching.coachingSessions' => function ($q) use ($coachingId) {
                $q->where('coaching_id', $coachingId);
            }])
                ->whereNotNull('coaching_note')
                ->whereNotNull('coaching_instructions')
                ->where('coaching_user_id', $coachingUser->id)->count();

            $sessionsCount = CoachingSession::where('coaching_id', $coachingId)->count();

            if ($details < $sessionsCount) {
                return $this->errorResponse(__('Pastikan Anda telah mengirimkan laporan dan telah ditinjau oleh Coach untuk setiap sesi sebelum mengirimkan laporan akhir.'), [], 400);
            }

            if ($request->hasFile('final_report')) {
                $file = $request->file('final_report');
                $fileName = 'coaching/'  . $coachingUser->coaching_id . '/' . now()->year . '/' . 'final_report' . '/coaching_user' . $coachingUser->id . '.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));
                $request->merge(['fileName' => $fileName]);
            }

            $result = $coachingUser->update([
                'final_report' => $request->fileName,
            ]);

            if ($result) {
                return $this->successResponse(null, __('Laporan berhasil disimpan'));
            }

            return $this->errorResponse(__('Laporan gagal disimpan'), [], 500);
        } catch (\Exception $e) {
            return $this->errorResponse(__('Terjadi kesalahan: ') . $e->getMessage(), [], 500);
        }
    }

    public function showDocument($id, $module, $type)
    {
        if ($module == 'coaching_session_detail') {
            $data = CoachingSessionDetail::findOrFail($id);
        }
        if ($module == 'coaching') {
            $data = Coaching::findOrFail($id);
        }
        if ($module == 'coaching_user') {
            $data = CoachingUser::findOrFail($id);
        }
        if (Storage::disk('private')->exists($data->$type)) {
            return response()->file(Storage::disk('private')->path($data->$type));
        } else {
            return null;
        }
    }
}
