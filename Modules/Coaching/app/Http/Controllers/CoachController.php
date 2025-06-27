<?php

namespace Modules\Coaching\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingAssessment;
use Modules\Coaching\app\Models\CoachingSession;
use Modules\Coaching\app\Models\CoachingSessionDetail;
use Modules\Coaching\app\Models\CoachingUser;
use app\Models\User;
use Illuminate\Support\Facades\Storage;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = userAuth();
        $coachings = Coaching::where('coach_id', $user->id)->orderByDesc('id')->paginate(10);

        return view('frontend.student-dashboard.coaching.coach.index', compact('coachings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coachees = User::all();
        return view('frontend.student-dashboard.coaching.coach.create', compact('coachees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = userAuth();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'main_issue' => 'required|string',
            'purpose' => 'required|string',
            'total_session' => 'required|integer|min:3|max:24',
            'sessions' => 'required|array|min:3',
            'sessions.*' => 'required|date',
            'learning_resources' => 'nullable|string',
            'coachee' => 'required|array|min:1|max:10',
            'coachee.*' => 'exists:users,id',
            'file' => 'required|file|mimes:pdf|max:5120',
        ]);

        // Cek ketentuan pelaksanaan pertemuan
        $monthlyCount = [];
        $sessions = collect($validated['sessions'])->map(fn($s) => \Carbon\Carbon::parse($s))->sort()->values();

        // Validasi harus ditahun yang sama
        $firstYear = $sessions->first()->year;
        if (!$sessions->every(fn($date) => $date->year === $firstYear)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sessions' => 'Semua tanggal sesi harus berada dalam tahun yang sama.']);
        }

        // Validasi tanggal harus berurutan
        for ($i = 1; $i < $sessions->count(); $i++) {
            if ($sessions[$i]->lt($sessions[$i - 1])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['sessions' => 'Tanggal sesi harus berurutan dari yang paling awal ke paling akhir.']);
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
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['sessions' => 'Maaf Anda hanya diperbolehkan mengajukan maksimal 2 pertemuan dalam satu bulan. Permintaan Anda melebihi batas yang telah ditentukan.']);
            }
        }

        $coaching = Coaching::create([
            'title' => $validated['title'],
            'main_issue' => $validated['main_issue'],
            'purpose' => $validated['purpose'],
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

        return redirect()->route('student.coach.index')->with(['messege' => 'Tema coaching berhasil ditambahkan!', 'alert-type' => 'success']);
    }

    /**
     * Show the specified resource.
     */
    public function show($coachingId)
    {
        $coaching = Coaching::with([
            'coachees', 'joinedCoachees', 'coachingSessions.details.coachingUser.coachee'
        ])->findOrFail($coachingId);

        authorizeCoachAccess($coaching);

        return view('frontend.student-dashboard.coaching.coach.show', compact('coaching'));
    }

    public function initiateConsensus($coachingId)
    {
        $coaching = Coaching::with('coachees')->findOrFail($coachingId);

        authorizeCoachAccess($coaching);

        if ($coaching->status !== Coaching::STATUS_DRAFT) {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => 'Status coaching sudah bukan draft.']);
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

        return redirect()->route('student.coach.index')->with(['messege' => 'Status coaching sudah menjadi konsensus!', 'alert-type' => 'success']);
    }

    public function processCoaching($coachingId)
    {
        $coaching = Coaching::with('joinedCoachees')->findOrFail($coachingId);

        authorizeCoachAccess($coaching);

        if ($coaching->joinedCoachees()->count() === 0) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'messege' => 'Minimal satu coachee harus bergabung untuk memulai proses coaching.'
            ]);
        }

        if ($coaching->status !== Coaching::STATUS_CONSENSUS) {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => 'Status coaching sudah bukan konsensus.']);

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

        return redirect()->route('student.coach.index')->with(['messege' => 'Status coaching sudah menjadi proses!', 'alert-type' => 'success']);
    }

    public function assessment(Request $request, $coachingId, $coacheeId )
    {
        $user = auth()->user();
        $data = CoachingUser::with(['coachee', 'assessment', 'coaching'])
                ->where('id', $coacheeId)
                ->forCoach($user->id, $coachingId)
                ->first();

        if (!$data) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses penilaian ini.');
        }

        authorizeCoachAccess($data->coaching);

        return view('frontend.student-dashboard.coaching.coach.assessment', compact('data'));
    }

    public function assessmentStore(Request $request, $coachingId, $coacheeId)
    {
        $validated = $request->validate([
            'goal_achieved' => 'required|in:0,1',
            'goal_description' => 'required|string',
            'discipline_level' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'discipline_description' => 'required|string',
            'teamwork_level' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'teamwork_description' => 'required|string',
            'initiative_level' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'initiative_description' => 'required|string',
        ], [
            'goal_achieved.required' => 'Target tidak boleh kosong',
            'goal_achieved.in' => 'Target harus 0 (Tidak) atau 1 (Ya)',
            'goal_description.required' => 'Deskripsi target tidak boleh kosong',
            'discipline_level.required' => 'Tingkat disiplin tidak boleh kosong',
            'discipline_level.in' => 'Tingkat disiplin harus antara 1 sampai 10',
            'discipline_description.required' => 'Deskripsi disiplin tidak boleh kosong',
            'teamwork_level.required' => 'Kerjasama tidak boleh kosong',
            'teamwork_level.in' => 'Kerjasama harus antara 1 sampai 10',
            'teamwork_description.required' => 'Deskripsi kerjasama tidak boleh kosong',
            'initiative_level.required' => 'Inisiatif tidak boleh kosong',
            'initiative_level.in' => 'Inisiatif harus antara 1 sampai 10',
            'initiative_description.required' => 'Deskripsi inisiatif tidak boleh kosong',
        ]);

        $user = auth()->user();
        $coaching = Coaching::where('id', $coachingId)
                    ->where('coach_id', $user->id)
                    ->firstOrFail();

        if ($coaching->status != Coaching::STATUS_EVALUATION) {
            return redirect()->back()->with([
                'messege' => 'Penilaian hanya bisa dilakukan jika coaching dalam status penilaian', 
                'alert-type' => 'error'
            ]);
        }
        
        $coachingUser = CoachingUser::with('coaching')
            ->where('coaching_id', $coachingId)
            ->where('user_id', $coacheeId)
            ->whereHas('coaching', fn($q) => $q->where('coach_id', $user->id))
            ->firstOrFail();

        CoachingAssessment::updateOrCreate(
            ['coaching_user_id' => $coachingUser->id],
            [
                'goal_achieved' => $validated['goal_achieved'],
                'goal_description' => $validated['goal_description'],
                'discipline_level' => $validated['discipline_level'],
                'discipline_description' => $validated['discipline_description'],
                'teamwork_level' => $validated['teamwork_level'],
                'teamwork_description' => $validated['teamwork_description'],
                'initiative_level' => $validated['initiative_level'],
                'initiative_description' => $validated['initiative_description'],
            ]
        );

        return redirect()->route('student.coach.index')->with([
            'messege' => 'Penilaian berhasil dilakukan', 
            'alert-type' => 'success'
        ]);
    }

    public function assessmentSubmit(Request $request, $coachingId, $coacheeId)
    {
        $validated = $request->validate([
            'goal_achieved' => 'required|in:0,1',
            'goal_description' => 'required|string',
            'discipline_level' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'discipline_description' => 'required|string',
            'teamwork_level' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'teamwork_description' => 'required|string',
            'initiative_level' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'initiative_description' => 'required|string',
        ], [
            '*.required' => 'Isian tidak boleh kosong',
            '*.in' => 'Nilai yang dimasukkan tidak valid',
        ]);
        
        $user = auth()->user();
        $coaching = Coaching::where('id', $coachingId)
            ->where('coach_id', $user->id)
            ->firstOrFail();

        if ($coaching->status != Coaching::STATUS_EVALUATION) {
            return redirect()->back()->with([
                'messege' => 'Penilaian hanya bisa dilakukan jika coaching dalam status penilaian', 
                'alert-type' => 'error'
            ]);
        }

        $coachingUser = CoachingUser::with('coaching')
            ->where('coaching_id', $coachingId)
            ->where('user_id', $coacheeId)
            ->whereHas('coaching', fn($q) => $q->where('coach_id', $user->id))
            ->firstOrFail();

        CoachingAssessment::updateOrCreate(
            ['coaching_user_id' => $coachingUser->id],
            [
                'goal_achieved' => $validated['goal_achieved'],
                'goal_description' => $validated['goal_description'],
                'discipline_level' => $validated['discipline_level'],
                'discipline_description' => $validated['discipline_description'],
                'teamwork_level' => $validated['teamwork_level'],
                'teamwork_description' => $validated['teamwork_description'],
                'initiative_level' => $validated['initiative_level'],
                'initiative_description' => $validated['initiative_description'],
            ]
        );

        $coaching->update(['status' => Coaching::STATUS_DONE]);

        return redirect()->route('student.coach.index')->with(['messege' => 'Penilaian berhasil dikirim', 'alert-type' => 'success']);
    }

    public function showDocumentSpt($id)
    {
        $coaching = Coaching::findOrFail($id);
        $path = $coaching->spt;

        return getPrivateFile($path);
    }

    public function showReport($id)
    {
        $coachee = CoachingUser::findOrFail($id);
        $path = $coachee->final_report;

        return getPrivateFile($path);
    }

    public function viewImage($detailId)
    {
        $session = CoachingSessionDetail::findOrFail($detailId);

        return getPrivateFile($session->image);
    }

    public function reviewStore(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:coaching_sessions,id',
            'coaching_user_id' => 'required|exists:coaching_users,id',
            'review_note' => 'required|string',
            'review_instruction' => 'nullable|string',
        ]);

        $coachingUser = CoachingUser::with('coaching')
        ->findOrFail($validated['coaching_user_id']);

        if (!$coachingUser) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses penilaian ini.');
        }

        authorizeCoachAccess($coachingUser->coaching);

        $detail = CoachingSessionDetail::firstOrNew([
            'coaching_session_id' => $validated['session_id'],
            'coaching_user_id' => $validated['coaching_user_id'],
        ]);

        $detail->coaching_note = $validated['review_note'];
        $detail->coaching_instructions = $validated['review_instruction'];
        $detail->save();

        return redirect()->back()->with(['messege' => 'Review berhasil disimpan', 'alert-type' => 'success']);

    }
}
