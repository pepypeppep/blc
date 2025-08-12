<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingAssessment;
use Modules\Coaching\app\Models\CoachingSession;
use Modules\Coaching\app\Models\CoachingSessionDetail;
use Modules\Coaching\app\Models\CoachingUser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CoachApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/coaching/coach",
     *     summary="Get coaching topics",
     *     description="Get coaching topics",
     *     tags={"Coach"},
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
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $data = Coaching::with('coach:id,name')->where('coach_id', $request->user()->id)->orderByDesc('id')->paginate($request->per_page ?? 10);;

            return $this->successResponse($data, 'Coaching topics fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/coaching/coach/{id}",
     *     summary="Get coaching topic detail by id",
     *     description="Get coaching topic detail by id",
     *     tags={"Coach"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Coaching id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        try {
            $coaching = Coaching::with(
                'coach:id,name',
                'coachees:id,name',
                'joinedCoachees:id,name',
                'coachingSessions.details.coachingUser.coachee:id,name',
                'coachingSessions.details.coachingUser.assessment'
            )->where('coach_id', $request->user()->id)->findOrFail($id);
            $coaching->joinedCoachees->each(function ($coachee) {
                $coachee->pivot->load('assessment');
            });
            $coaching->coachees->each(function ($coachee) {
                $coachee->pivot->load('assessment');
            });

            authorizeCoachAccess($coaching);

            return $this->successResponse(['coaching' => $coaching], 'Coaching topics fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/coaching/coach",
     *     summary="Create coaching topic",
     *     description="Create coaching topic",
     *     tags={"Coach"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title", "goal", "reality", "option", "way_forward", "success_indicator", "total_session", "sessions", "coachee", "file"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Judul coaching"
     *                 ),
     *                 @OA\Property(
     *                     property="goal",
     *                     type="string",
     *                     example="Pokok permasalahan coaching"
     *                 ),
     *                 @OA\Property(
     *                     property="reality",
     *                     type="string",
     *                     example="Tujuan coaching"
     *                 ),
     *                @OA\Property(
     *                    property="option",
     *                    type="string",
     *                   example="Pilihan yang tersedia untuk mencapai tujuan"
     *                ),
     *                @OA\Property(
     *                    property="way_forward",
     *                   type="string",
     *                   example="Langkah-langkah yang akan diambil untuk mencapai tujuan"
     *                ),
     *                @OA\Property(
     *                    property="success_indicator",
     *                   type="string",
     *                  example="Indikator keberhasilan yang diharapkan"
     *                ),
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
     *                     property="learning_resources",
     *                     type="string",
     *                     example="Sumber belajar coaching"
     *                 ),
     *                 @OA\Property(
     *                     property="coachee",
     *                     type="array",
     *                     @OA\Items(
     *                         type="integer",
     *                         example=1
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed. Conditions: same year, date sequence (earliest to latest), max 2 sessions/month."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $user = User::findOrFail($request->user()->id);
            $request->merge([
                'sessions' => explode(',', $request->input('sessions')),
                'coachee' => explode(',', $request->input('coachee')),
            ]);
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'goal' => 'required|string',
                'reality' => 'required|string',
                'option' => 'required|string',
                'way_forward' => 'required|string',
                'success_indicator' => 'required|string',
                'total_session' => 'required|integer|min:3|max:24',
                'sessions' => 'required|array|min:3|max:24',
                'sessions.*' => 'required|date',
                'learning_resources' => 'nullable|string',
                'coachee' => 'required|array|min:1|max:10',
                'coachee.*' => 'exists:users,id',
                'file' => 'required|file|mimes:pdf|max:5120',
            ], [
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul maksimal 255 karakter.',

                'goal.required' => 'Goal wajib diisi.',
                'goal.string' => 'Goal harus berupa teks.',

                'reality.required' => 'Reality wajib diisi.',
                'reality.string' => 'Reality harus berupa teks.',

                'option.required' => 'Option wajib diisi.',
                'option.string' => 'Option harus berupa teks.',

                'way_forward.required' => 'Way Forward wajib diisi.',
                'way_forward.string' => 'Way Forward harus berupa teks.',

                'success_indicator.required' => 'Success Indicator wajib diisi.',
                'success_indicator.string' => 'Success Indicator harus berupa teks.',

                'total_session.required' => 'Jumlah sesi wajib diisi.',
                'total_session.integer' => 'Jumlah sesi harus berupa angka.',
                'total_session.min' => 'Minimal jumlah sesi adalah 3.',
                'total_session.max' => 'Maksimal jumlah sesi adalah 24.',

                'sessions.required' => 'Daftar sesi wajib diisi.',
                'sessions.array' => 'Daftar sesi harus berupa array.',
                'sessions.min' => 'Minimal harus terdapat 3 sesi.',
                'sessions.max' => 'Maksimal hanya boleh 24 sesi.',
                'sessions.*.required' => 'Tanggal sesi wajib diisi.',
                'sessions.*.date' => 'Setiap sesi harus berupa tanggal yang valid.',

                'learning_resources.string' => 'Sumber belajar harus berupa teks.',

                'coachee.required' => 'Minimal satu coachee harus dipilih.',
                'coachee.array' => 'Data coachee harus berupa array.',
                'coachee.min' => 'Minimal satu coachee harus dipilih.',
                'coachee.max' => 'Maksimal 10 coachee dapat dipilih.',
                'coachee.*.exists' => 'Data coachee tidak valid.',

                'file.required' => 'File wajib diunggah.',
                'file.file' => 'File tidak valid.',
                'file.mimes' => 'File harus berupa PDF.',
                'file.max' => 'Ukuran file maksimal 5MB.',
            ]);

            // Cek ketentuan pelaksanaan pertemuan
            $monthlyCount = [];
            $sessions = collect($validated['sessions'])->map(fn($s) => \Carbon\Carbon::parse($s))->sort()->values();

            // Validasi harus ditahun yang sama
            $firstYear = $sessions->first()->year;
            if (!$sessions->every(fn($date) => $date->year === $firstYear)) {
                return $this->errorResponse('Semua tanggal sesi harus berada dalam tahun yang sama.', [], 422);
            }

            // Validasi tanggal harus berurutan
            for ($i = 1; $i < $sessions->count(); $i++) {
                if ($sessions[$i]->lt($sessions[$i - 1])) {
                    return $this->errorResponse('Tanggal sesi harus berurutan dari yang paling awal ke paling akhir.', [], 422);
                }
            }

            // Validasi maksimal 2 sesi per bulan
            foreach ($sessions as $session) {
                $monthKey = $session->format('Y-m');

                if (!isset($monthlyCount[$monthKey])) {
                    $monthlyCount[$monthKey] = 0;
                }

                $monthlyCount[$monthKey]++;

                if ($monthlyCount[$monthKey] > 2) {
                    return $this->errorResponse('Maaf Anda hanya diperbolehkan mengajukan maksimal 2 pertemuan dalam satu bulan. Permintaan Anda melebihi batas yang telah ditentukan.', [], 422);
                }
            }

            $coaching = Coaching::create([
                'title' => $validated['title'],
                'goal' => $validated['goal'],
                'reality' => $validated['reality'],
                'option' => $validated['option'],
                'way_forward' => $validated['way_forward'],
                'success_indicator' => $validated['success_indicator'],
                'total_session' => $validated['total_session'],
                'learning_resources' => $validated['learning_resources'],
                'coach_id' => $user->id,
                'status' => Coaching::STATUS_DRAFT,
            ]);

            $coaching->coachees()->sync($request->coachee);

            if ($request->hasFile('file')) {
                $path = 'coaching/' . now()->year . '/' . $coaching->id . '/';
                $file = $request->file('file');
                $fileName = $path . 'spt.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));

                $coaching->update([
                    'spt' => $fileName
                ]);
            }

            foreach ($validated['sessions'] as $dateTime) {
                CoachingSession::create([
                    'coaching_id' => $coaching->id,
                    'coaching_date' => $dateTime,
                ]);
            }
            return $this->successResponse([], 'Tema coaching berhasil ditambahkan!');
        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/coaching/coach/{id}/initiate-consensus",
     *     summary="Submit coaching for coachee confirmation",
     *     description="Allows coach to propose a coaching session and request confirmation from coachee",
     *     tags={"Coach"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Coaching id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coaching request submitted successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed. Coaching status must be in 'draft' state to proceed"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to submit coaching request"
     *     )
     * )
     */
    public function initiateConsensus($coachingId)
    {
        try {
            $coaching = Coaching::with('coachees')->findOrFail($coachingId);

            authorizeCoachAccess($coaching);

            if ($coaching->status !== Coaching::STATUS_DRAFT) {
                return $this->errorResponse('Status coaching sudah bukan draft.', [], 422);
            }

            $coaching->status = Coaching::STATUS_CONSENSUS;
            $coaching->updated_at = now();
            $coaching->save();

            //kirim notifikasi
            foreach ($coaching->coachees as $coachee) {
                sendNotification([
                    'user_id' => $coachee->id,
                    'title' => 'Lanjutkan Proses Coaching – Konsensus Telah Dibuka',
                    'body' => "Seorang coach telah menginisiasi konsensus untuk sesi coaching ini. Silakan tinjau dan berikan keputusan Anda: setujui untuk bergabung, atau tolak dengan menyertakan alasan.",
                    'link' => route('student.coachee.index'),
                    'path' => null,
                ]);
            }

            return $this->successResponse([], 'Pengajuan berhasil. Status coaching sudah menjadi konsensus!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/coaching/coach/{id}/process-coaching",
     *     summary="Initiate coaching process",
     *     description="Allows scheduled coaching sessions to proceed. Session reports can be submitted by coachee, and coach is able to review coachee's reports",
     *     tags={"Coach"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Coaching id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coaching process started successfully."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed. Possible reasons: no coachee has joined, or coaching status is not in 'consensus' stage."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to start coaching request"
     *     )
     * )
     */
    public function processCoaching($coachingId)
    {
        $coaching = Coaching::with('joinedCoachees')->findOrFail($coachingId);

        authorizeCoachAccess($coaching);

        if ($coaching->joinedCoachees()->count() === 0) {
            return $this->errorResponse('Minimal satu coachee harus bergabung untuk memulai proses coaching.', [], 422);
        }

        if ($coaching->status !== Coaching::STATUS_CONSENSUS) {
            return $this->errorResponse('Status coaching sudah bukan konsensus.', [], 422);
        }

        $coaching->status = Coaching::STATUS_PROCESS;
        $coaching->updated_at = now();
        $coaching->save();

        //kirim notifikasi
        foreach ($coaching->joinedCoachees as $coachee) {
            sendNotification([
                'user_id' => $coachee->id,
                'title' => 'Ikuti Sesi Pertemuan – Coaching Telah Dimulai',
                'body' => "Coach telah memulai proses coaching. Ikuti sesi pertemuan sesuai jadwal dan laporkan hasil penugasan",
                'link' => route('student.coachee.index'),
                'path' => null,
            ]);
        }

        return $this->successResponse([], 'Status coaching sudah menjadi proses!');
    }

    /**
     * @OA\Put(
     *     path="/coaching/coach/review",
     *     summary="Submit Coaching Session Review",
     *     description="Allows coach to submit a review or feedback for the completed coaching session.",
     *     operationId="coachingReview",
     *     tags={"Coach"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="session_id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="coaching_user_id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="review_note",
     *                 type="string",
     *                 example="Catatan ulasan"
     *             ),
     *             @OA\Property(
     *                 property="review_instruction",
     *                 type="string",
     *                 example="Instruksi untuk coachee"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coaching reviewed successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden – You do not have permission to access this resource"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed. Coachee report is not available for this session."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function reviewStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|exists:coaching_sessions,id',
                'coaching_user_id' => 'required|exists:coaching_users,id',
                'review_note' => 'required|string',
                'review_instruction' => 'nullable|string',
            ]);

            $coachingUser = CoachingUser::with('coaching')->findOrFail($validated['coaching_user_id']);

            if (!$coachingUser) {
                return $this->errorResponse('Anda tidak memiliki izin untuk mengakses penilaian ini.', [], 403);
            }

            authorizeCoachAccess($coachingUser->coaching);

            $detail = CoachingSessionDetail::where([
                'coaching_session_id' => $validated['session_id'],
                'coaching_user_id' => $validated['coaching_user_id'],
            ])->first();

            if (!$detail || $detail->activity === null) {
                return $this->errorResponse('Gagal menyimpan review. Laporan dari coachee belum tersedia.', [], 422);
            }

            $detail->coaching_note = $validated['review_note'];
            $detail->coaching_instructions = $validated['review_instruction'];
            $detail->save();

            return $this->successResponse([], 'Review berhasil disimpan');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\RequestBody(
     *      request="AssessmentRequestBody",
     *      required=true,
     *      description="Coaching assessment",
     *      @OA\JsonContent(
     *          type="object",
     *          required={"goal_achieved", "goal_description", "discipline_level", "teamwork_level", "initiative_level"},
     *          @OA\Property(property="goal_achieved", type="integer", example=1, description="Target"),
     *          @OA\Property(property="goal_description", type="string", example="Target description", description="Deskripsi target"),
     *          @OA\Property(property="discipline_level", type="integer", example=80, description="Tingkat disiplin"),
     *          @OA\Property(property="teamwork_level", type="integer", example=80, description="Kerjasama"),
     *          @OA\Property(property="initiative_level", type="integer", example=80, description="Inisiatif"),
     *      ),
     *  ),
     */
    protected function processAssessment(Request $request, $coachingId, $coacheeId)
    {
        $validated = $request->validate([
            'goal_achieved' => 'required|in:0,1',
            'goal_description' => 'required|string',
            'discipline_level' => 'required|integer|min:1|max:100',
            // 'discipline_description' => 'required|string',
            'teamwork_level' => 'required|integer|min:1|max:100',
            // 'teamwork_description' => 'required|string',
            'initiative_level' => 'required|integer|min:1|max:100',
            // 'initiative_description' => 'required|string',
        ], [
            'goal_achieved.required' => 'Target tidak boleh kosong',
            'goal_achieved.in' => 'Target harus 0 (Tidak) atau 1 (Ya)',
            'goal_description.required' => 'Deskripsi target tidak boleh kosong',
            'discipline_level.required' => 'Tingkat disiplin tidak boleh kosong',
            'discipline_level.in' => 'Tingkat disiplin harus antara 1 sampai 10',
            // 'discipline_description.required' => 'Deskripsi disiplin tidak boleh kosong',
            'teamwork_level.required' => 'Kerjasama tidak boleh kosong',
            'teamwork_level.in' => 'Kerjasama harus antara 1 sampai 10',
            // 'teamwork_description.required' => 'Deskripsi kerjasama tidak boleh kosong',
            'initiative_level.required' => 'Inisiatif tidak boleh kosong',
            'initiative_level.in' => 'Inisiatif harus antara 1 sampai 10',
            // 'initiative_description.required' => 'Deskripsi inisiatif tidak boleh kosong',
        ]);

        $user_id = $request->user()->id;
        $coaching = Coaching::where('id', $coachingId)
            ->where('coach_id', $user_id)
            ->firstOrFail();

        if ($coaching->status == Coaching::STATUS_DONE) {
            throw new \Exception('Status coaching sudah selesai. Penilaian tidak dapat dilakukan lagi', 422);
        }

        $coachingUser = CoachingUser::with('coaching')
            ->where('coaching_id', $coachingId)
            ->where('user_id', $coacheeId)
            ->whereHas('coaching', fn($q) => $q->where('coach_id', $user_id))
            ->firstOrFail();

        if (empty($coachingUser->final_report)) {
            throw new \Exception('Penilaian hanya bisa dilakukan jika laporan akhir telah diunggah coachee', 422);
        }

        CoachingAssessment::updateOrCreate(
            ['coaching_user_id' => $coachingUser->id],
            [
                'goal_achieved' => $validated['goal_achieved'],
                'goal_description' => $validated['goal_description'],
                'discipline_level' => $validated['discipline_level'],
                // 'discipline_description' => $validated['discipline_description'],
                'discipline_description' => '-',
                'teamwork_level' => $validated['teamwork_level'],
                // 'teamwork_description' => $validated['teamwork_description'],
                'teamwork_description' => '-',
                'initiative_level' => $validated['initiative_level'],
                // 'initiative_description' => $validated['initiative_description'],
                'initiative_description' => '-',
            ]
        );

        return $coaching;
    }

    /**
     * @OA\Get(
     *     path="/coaching/coach/{id}/assessment/{coacheeId}",
     *     summary="Get coaching assessment",
     *     description="Allows coach to get assessment data.",
     *     tags={"Coach"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="ID coaching",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1),
     *     ),
     *     @OA\Parameter(
     *         description="ID coachee",
     *         in="path",
     *         name="coacheeId",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coaching assessment completed successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "code": 200,
     *                 "status": true,
     *                 "message": "Penilaian berhasil diambil",
     *                 "data": {
     *                     "id": 1,
     *                     "goal_achieved": 1,
     *                     "goal_description": "Target description",
     *                     "discipline_level": 80,
     *                     "discipline_description": "-",
     *                     "teamwork_level": 80,
     *                     "teamwork_description": "-",
     *                     "initiative_level": 80,
     *                     "initiative_description": "-",
     *                     "coaching_user_id": 1,
     *                     "created_at": "2025-08-11T00:49:20.000000Z",
     *                     "updated_at": "2025-08-11T07:09:05.000000Z"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Request cannot be processed due to invalid coaching status",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     * )
     */
    public function assessment(Request $request, $coachingId, $coacheeId)
    {
        try {
            $user_id = $request->user()->id;

            $coachingUser = CoachingUser::with('coaching')
                ->where('coaching_id', $coachingId)
                ->where('user_id', $coacheeId)
                ->whereHas('coaching', fn($q) => $q->where('coach_id', $user_id))
                ->firstOrFail();

            $data = CoachingAssessment::where('coaching_user_id', $coachingUser->id)->firstOrFail();

            return $this->successResponse($data, 'Penilaian berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/coaching/coach/{id}/assessment-store/{coacheeId}",
     *     summary="Save coaching assessment",
     *     description="Allows coach to save assessment data as a draft before final submission.",
     *     tags={"Coach"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="ID coaching",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1),
     *     ),
     *     @OA\Parameter(
     *         description="ID coachee",
     *         in="path",
     *         name="coacheeId",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1),
     *     ),
     *     @OA\RequestBody(
     *         description="Assessment data",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"goal_achieved", "discipline_level", "teamwork_level", "initiative_level", "goal_description"},
     *             @OA\Property(property="goal_achieved", type="integer", example=1, description="Target"),
     *             @OA\Property(property="discipline_level", type="integer", example=80, description="Tingkat disiplin"),
     *             @OA\Property(property="teamwork_level", type="integer", example=80, description="Kerjasama"),
     *             @OA\Property(property="initiative_level", type="integer", example=80, description="Inisiatif"),
     *             @OA\Property(property="goal_description", type="string", example="Target description", description="Deskripsi target"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coaching assessment completed successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Request cannot be processed due to invalid coaching status",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     * )
     */
    public function assessmentStore(Request $request, $coachingId, $coacheeId)
    {
        try {
            $this->processAssessment($request, $coachingId, $coacheeId);

            return $this->successResponse([], 'Penilaian berhasil dilakukan');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    // /**
    //  * @OA\Post(
    //  *     path="/coaching/coach/{id}/assessment-submit/{coacheeId}",
    //  *     summary="Submit coaching assessment",
    //  *     description="Allows coach to submit an assessment for the completed coaching session.",
    //  *     tags={"Coach"},
    //  *     security={{"bearer":{}}},
    //  *     @OA\Parameter(
    //  *         description="ID coaching",
    //  *         in="path",
    //  *         name="id",
    //  *         required=true,
    //  *         @OA\Schema(type="integer", format="int64", example=1),
    //  *     ),
    //  *     @OA\Parameter(
    //  *         description="ID coachee",
    //  *         in="path",
    //  *         name="coacheeId",
    //  *         required=true,
    //  *         @OA\Schema(type="integer", format="int64", example=1),
    //  *     ),
    //  *     @OA\Parameter(
    //  *         description="ID user",
    //  *         in="query",
    //  *         name="user_id",
    //  *         required=true,
    //  *         example=1,
    //  *         @OA\Schema(
    //  *             type="integer",
    //  *             format="int64",
    //  *             example=1
    //  *         )
    //  *     ),
    //  *     @OA\RequestBody(ref="#/components/requestBodies/AssessmentRequestBody"),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Coaching assessment submited successfully",
    //  *     ),
    //  *     @OA\Response(
    //  *         response=422,
    //  *         description="Request cannot be processed due to invalid coaching status",
    //  *     ),
    //  *     @OA\Response(
    //  *         response=500,
    //  *         description="Internal server error",
    //  *     ),
    //  * )
    //  */
    // public function assessmentSubmit(Request $request, $coachingId, $coacheeId)
    // {
    //     try {
    //         $coaching = $this->processAssessment($request, $coachingId, $coacheeId);

    //         $coaching->update(['status' => Coaching::STATUS_DONE]);

    //         return $this->successResponse([], 'Penilaian berhasil dikirim');
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage(), [], 500);
    //     }
    // }

    /**
     * @OA\Post(
     *     path="/coaching/coach/update-session",
     *     summary="Update coaching session date",
     *     description="Update coaching session date",
     *     tags={"Coach"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Session and new coaching date",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"session_id", "coaching_date"},
     *             @OA\Property(property="session_id", type="integer", example=1),
     *             @OA\Property(property="coaching_date", type="string", example="2022-01-01 09:00:00", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Session updated successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden – You do not have permission to update"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed. Possible reasons: session not found, coaching report already reviewed by coach",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error updating session"
     *     )
     * )
     */
    public function changeSessionDate(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'coaching_date' => 'required|date',
        ], [
            'session_id.required' => 'Sesi pertemuan harus dipilih',
            'coaching_date.required' => 'Tanggal coaching tidak boleh kosong',
        ]);

        $session = CoachingSession::whereHas('Coaching', function ($query) use ($request) {
            $query->where('coach_id', $request->user()->id);
        })->where('id', $request->session_id)
            ->with('details')
            ->first();

        if (!$session) {
            return $this->errorResponse('Sesi tidak ditemukan.', [], 422);
        }

        if ($session->coaching->coach_id != $request->user()->id) {
            return $this->errorResponse('Anda tidak memiliki izin untuk mengubah coaching ini.', [], 403);
        }

        $hasReview = $session->details->contains(function ($detail) {
            return !empty($detail->coaching_note);
        });

        if ($hasReview) {
            return $this->errorResponse('Tanggal coaching tidak dapat diubah karena laporan pertemuan telah ditanggapi oleh coach.', [], 422);
        }

        $session->coaching_date_changed = $session->coaching_date;
        $session->coaching_date = $request->coaching_date;
        $session->save();

        return $this->successResponse([], 'Tanggal coaching berhasil diperbarui.');
    }

    /**
     * @OA\Put(
     *     path="/coaching/coach/{id}/finish-coaching",
     *     summary="Mark coaching as completed",
     *     description="Updates the status of the coaching to 'done' after ensuring all joined coachees have been assessed.",
     *     tags={"Coach"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Coaching id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coaching finished successfully."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed. Possible reasons: unassessed coachees or incorrect coaching status."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to finish coaching"
     *     )
     * )
     */
    public function finishCoaching($coachingId)
    {
        $coaching = Coaching::with('joinedCoachees')->findOrFail($coachingId);

        authorizeCoachAccess($coaching);

        if (!$coaching->isAllCoacheesAssessed()) {
            return $this->errorResponse('Masih ada coachee yang belum dinilai.', [], 422);
        }

        if ($coaching->status !== Coaching::STATUS_PROCESS) {
            return $this->errorResponse('Status coaching harus dalam status proses untuk diselesaikan.', [], 422);
        }

        $coaching->status = Coaching::STATUS_DONE;
        $coaching->updated_at = now();
        $coaching->save();

        return $this->successResponse([], 'Penilaian telah terkirim ke BKPSDM. Coaching berhasil diselesaikan!');
    }
}
