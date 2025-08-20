<?php

namespace Modules\Mentoring\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringReview;
use Modules\Mentoring\app\Models\MentoringSession;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $mentorTopics = Mentoring::where('mentor_id', $user->id)
            ->where(function ($query) {
                $query->where('status', Mentoring::STATUS_SUBMISSION)
                    ->orWhere('status', Mentoring::STATUS_PROCESS)
                    ->orWhere('status', Mentoring::STATUS_EVALUATION)
                    ->orWhere('status', Mentoring::STATUS_VERIFICATION)
                    ->orWhere('status', Mentoring::STATUS_DONE);
            })
            ->orderByDesc('id')
            ->paginate(10);
        return view('frontend.student-dashboard.mentoring.mentor.index', compact('mentorTopics'));
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $mentoring = Mentoring::with(["mentoringSessions" => function ($query) {
            $query->orderBy('mentoring_date', 'asc');
        }])->where('id', $id)->where('mentor_id', $user->id)->first();
        $hasIncompleteSessions = $mentoring->mentoringSessions->contains(function ($session) {
            return empty($session->activity);
        });

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        return view('frontend.student-dashboard.mentoring.mentor.show', compact('mentoring', 'hasIncompleteSessions'));
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required',
        ], [
            'reason.required' => 'Alasan tidak boleh kosong',
        ]);

        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentor_id', $user->id)->first();

        if (!$mentoring) {
            return response()->json(['status' => 'error', 'message' => 'Mentoring not found'], 404);
        }

        if ($mentoring->status != Mentoring::STATUS_SUBMISSION) {
            return response()->json(['status' => 'error', 'message' => 'Penolakan hanya bisa dilakukan jika masih dalam status pengajuan'], 400);
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
                'submodule' => 'mentor',
                'id' => $mentoring->id,
                'slug' => null
            ]
        ]);

        return response()->json(['status' => 'success', 'message' => 'Berhasil menolak pengajuan mentoring'], 200);
    }

    public function approve(Request $request, $id)
    {
        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentor_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($mentoring->status != Mentoring::STATUS_SUBMISSION) {
            return redirect()->back()->with(['messege' => 'Persetujuan hanya bisa dilakukan jika masih dalam status pengajuan', 'alert-type' => 'error']);
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
                'submodule' => 'mentor',
                'id' => $mentoring->id,
                'slug' => null
            ]
        ]);

        return redirect()->route('student.mentor.index')->with(['messege' => 'Pengajuan berhasil', 'alert-type' => 'success']);
    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'mentoring_date' => 'required',
            'mentoring_note' => 'required',
            'mentoring_instructions' => 'required',
        ], [
            'mentoring_date.required' => 'Tanggal mentoring tidak boleh kosong',
            'mentoring_note.required' => 'Catatan mentoring tidak boleh kosong',
            'mentoring_instructions.required' => 'Instruksi mentoring tidak boleh kosong',
        ]);

        // dd($request->all(), $id);
        $user = auth()->user();
        $session = MentoringSession::with('mentoring')->where('id', $id)->first();

        // dd($session, $id);
        if (!$session) {
            return redirect()->back()->with(['messege' => 'Sesi mentoring tidak ditemukan', 'alert-type' => 'error']);
        }

        if ($session->status != MentoringSession::STATUS_REPORTED) {
            return redirect()->back()->with(['messege' => 'Sesi mentoring belum ada laporan', 'alert-type' => 'error']);
        }

        $mentoring = Mentoring::where('id', $session->mentoring_id)->where('mentor_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($mentoring->status != Mentoring::STATUS_PROCESS) {
            return redirect()->back()->with(['messege' => 'Review hanya bisa dilakukan jika mentoring dalam status proses', 'alert-type' => 'error']);
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
                'submodule' => 'mentor',
                'id' => $mentoring->id,
                'slug' => null
            ]
        ]);


        $sessionCount = MentoringSession::where('mentoring_id', $mentoring->id)
            ->where('status', MentoringSession::STATUS_REVIEWED)
            ->count();

        if ($sessionCount == count($mentoring->mentoringSessions)) {
            $mentoring->status = Mentoring::STATUS_EVALUATION;
            $mentoring->save();
        }

        return redirect()->route('student.mentor.show', $mentoring->id)->with(['messege' => 'Berhasil memberikan review', 'alert-type' => 'success']);
    }

    public function evaluasi(Request $request, $id)
    {
        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentor_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($mentoring->status != Mentoring::STATUS_EVALUATION && ($mentoring->status != Mentoring::STATUS_VERIFICATION || $mentoring->status != Mentoring::STATUS_DONE)) {
            return redirect()->back()->with(['messege' => 'Evaluasi hanya bisa dilakukan jika mentoring dalam status penilaian', 'alert-type' => 'error']);
        }

        $review = MentoringReview::where('mentoring_id', $mentoring->id)->first();

        return view('frontend.student-dashboard.mentoring.mentor.evaluasi', compact('mentoring', 'review'));
    }

    public function evaluasiStore(Request $request, $id)
    {
        $request->validate([
            'target' => 'required|in:0,1',
            'target_description' => 'required|string',
            'tingkat_disiplin' => 'required|integer|min:1|max:100',
            // 'disiplin_description' => 'required|string',
            'kerjasama' => 'required|integer|min:1|max:100',
            // 'kerjasama_description' => 'required|string',
            'inisiatif' => 'required|integer|min:1|max:100',
            // 'inisiatif_description' => 'required|string',
            'penguasaan_materi' => 'required|integer|min:1|max:100',
            // 'penguasaan_materi_description' => 'required|string',
        ], [
            'target.required' => 'Target tidak boleh kosong',
            'target.in' => 'Target harus 0 (Tidak) atau 1 (Ya)',
            'target_description.required' => 'Deskripsi target tidak boleh kosong',
            'tingkat_disiplin.required' => 'Tingkat disiplin tidak boleh kosong',
            'tingkat_disiplin.min' => 'Tingkat disiplin minimal 1',
            'tingkat_disiplin.max' => 'Tingkat disiplin maksimal 100',
            // 'disiplin_description.required' => 'Deskripsi disiplin tidak boleh kosong',
            'kerjasama.required' => 'Kerjasama tidak boleh kosong',
            'kerjasama.min' => 'Kerjasama minimal 1',
            'kerjasama.max' => 'Kerjasama maksimal 100',
            // 'kerjasama_description.required' => 'Deskripsi kerjasama tidak boleh kosong',
            'inisiatif.required' => 'Inisiatif tidak boleh kosong',
            'inisiatif.min' => 'Inisiatif minimal 1',
            'inisiatif.max' => 'Inisiatif maksimal 100',
            // 'inisiatif_description.required' => 'Deskripsi inisiatif tidak boleh kosong',
            'penguasaan_materi.required' => 'Penguasaan materi tidak boleh kosong',
            'penguasaan_materi.integer' => 'Penguasaan materi harus berupa angka',
            'penguasaan_materi.min' => 'Penguasaan materi minimal 1',
            'penguasaan_materi.max' => 'Penguasaan materi maksimal 100',
            // 'penguasaan_materi_description.required' => 'Deskripsi penguasaan materi tidak boleh kosong',
        ]);



        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentor_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($mentoring->status != Mentoring::STATUS_EVALUATION) {
            return redirect()->back()->with(['messege' => 'Evaluasi hanya bisa dilakukan jika mentoring dalam status penilaian', 'alert-type' => 'error']);
        }

        $review = MentoringReview::where('mentoring_id', $mentoring->id)->first();
        if (!$review) {
            $review = new MentoringReview();
            $review->mentoring_id = $mentoring->id;
            $review->is_target = $request->target;
            $review->target_description = $request->target_description;
            $review->discipline = $request->tingkat_disiplin;
            // $review->discipline_description = $request->disiplin_description;
            $review->discipline_description = '-';
            $review->teamwork = $request->kerjasama;
            // $review->teamwork_description = $request->kerjasama_description;
            $review->teamwork_description = '-';
            $review->initiative = $request->inisiatif;
            // $review->initiative_description = $request->inisiatif_description;
            $review->initiative_description = '-';
            $review->material_mastery = $request->penguasaan_materi;
            // $review->material_mastery_description = $request->penguasaan_materi_description;
            $review->material_mastery_description = '-';
            $review->save();
        } else {
            $review->is_target = $request->target;
            $review->target_description = $request->target_description;
            $review->discipline = $request->tingkat_disiplin;
            // $review->discipline_description = $request->disiplin_description;
            $review->discipline_description = '-';
            $review->teamwork = $request->kerjasama;
            // $review->teamwork_description = $request->kerjasama_description;
            $review->teamwork_description = '-';
            $review->initiative = $request->inisiatif;
            // $review->initiative_description = $request->inisiatif_description;
            $review->initiative_description = '-';
            $review->material_mastery = $request->penguasaan_materi;
            // $review->material_mastery_description = $request->penguasaan_materi_description;
            $review->material_mastery_description = '-';
            $review->save();
        }

        return redirect()->back()->with(['messege' => 'Evaluasi berhasil', 'alert-type' => 'success']);
    }

    public function kirimEvaluasi(Request $request, $id)
    {
        $request->validate([
            'target' => 'required|in:0,1',
            'target_description' => 'required|string',
            'tingkat_disiplin' => 'required|integer|min:1|max:100',
            // 'disiplin_description' => 'required|string',
            'kerjasama' => 'required|integer|min:1|max:100',
            // 'kerjasama_description' => 'required|string',
            'inisiatif' => 'required|integer|min:1|max:100',
            // 'inisiatif_description' => 'required|string',
            'penguasaan_materi' => 'required|integer|min:1|max:100',
            // 'penguasaan_materi_description' => 'required|string',
        ], [
            'target.required' => 'Target tidak boleh kosong',
            'target.in' => 'Target harus 0 (Tidak) atau 1 (Ya)',
            'target_description.required' => 'Deskripsi target tidak boleh kosong',
            'tingkat_disiplin.required' => 'Tingkat disiplin tidak boleh kosong',
            'tingkat_disiplin.min' => 'Tingkat disiplin minimal 1',
            'tingkat_disiplin.max' => 'Tingkat disiplin maksimal 100',
            // 'disiplin_description.required' => 'Deskripsi disiplin tidak boleh kosong',
            'kerjasama.required' => 'Kerjasama tidak boleh kosong',
            'kerjasama.min' => 'Kerjasama minimal 1',
            'kerjasama.max' => 'Kerjasama maksimal 100',
            // 'kerjasama_description.required' => 'Deskripsi kerjasama tidak boleh kosong',
            'inisiatif.required' => 'Inisiatif tidak boleh kosong',
            'inisiatif.min' => 'Inisiatif minimal 1',
            'inisiatif.max' => 'Inisiatif maksimal 100',
            // 'inisiatif_description.required' => 'Deskripsi inisiatif tidak boleh kosong',
            'penguasaan_materi.required' => 'Penguasaan materi tidak boleh kosong',
            'penguasaan_materi.integer' => 'Penguasaan materi harus berupa angka',
            'penguasaan_materi.min' => 'Penguasaan materi minimal 1',
            'penguasaan_materi.max' => 'Penguasaan materi maksimal 100',
            // 'penguasaan_materi_description.required' => 'Deskripsi penguasaan materi tidak boleh kosong',
        ]);

        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentor_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($mentoring->status != Mentoring::STATUS_EVALUATION) {
            return redirect()->back()->with(['messege' => 'Evaluasi hanya bisa dilakukan jika mentoring dalam status penilaian', 'alert-type' => 'error']);
        }

        $review = MentoringReview::where('mentoring_id', $mentoring->id)->first();
        if (!$review) {
            $review = new MentoringReview();
            $review->mentoring_id = $mentoring->id;
            $review->is_target = $request->target;
            $review->target_description = $request->target_description;
            $review->discipline = $request->tingkat_disiplin;
            // $review->discipline_description = $request->disiplin_description;
            $review->discipline_description = '-';
            $review->teamwork = $request->kerjasama;
            // $review->teamwork_description = $request->kerjasama_description;
            $review->teamwork_description = '-';
            $review->initiative = $request->inisiatif;
            // $review->initiative_description = $request->inisiatif_description;
            $review->initiative_description = '-';
            $review->material_mastery = $request->penguasaan_materi;
            // $review->material_mastery_description = $request->penguasaan_materi_description;
            $review->material_mastery_description = '-';
            $review->save();
        }

        $mentoring->status = Mentoring::STATUS_VERIFICATION;
        $mentoring->save();

        return redirect()->route('student.mentor.index')->with(['messege' => 'Evaluasi berhasil dikirim', 'alert-type' => 'success']);
    }

    public function updateSession(Request $request)
    {
        $request->validate([
            'mentoring_date' => 'required|date',
            'session_id' => 'required|string',
        ], [
            'mentoring_date.required' => 'Tanggal mentoring tidak boleh kosong',
            'session_id.required' => 'ID sesi tidak boleh kosong',
        ]);

        $user = auth()->user();
        $sessions = MentoringSession::with('mentoring.mentoringSessions')->whereHas('Mentoring', function ($query) use ($user) {
            $query->where('mentor_id', $user->id);
        })->where('id', $request->session_id)->first();

        $monthlyCount = [];
        foreach ($sessions->mentoring->mentoringSessions as $session) {
            $monthKey = \Carbon\Carbon::parse($session->mentoring_date)->format('Y-m');
            if (strval($session->id) === $request->session_id) {
                $monthKey = \Carbon\Carbon::parse($request->mentoring_date)->format('Y-m');
            }


            if (!isset($monthlyCount[$monthKey])) {
                $monthlyCount[$monthKey] = 0;
            }

            $monthlyCount[$monthKey]++;


            if ($monthlyCount[$monthKey] > 2) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['mentoring_date' => 'Maaf Anda hanya diperbolehkan mengajukan maksimal 2 pertemuan dalam satu bulan. Permintaan Anda melebihi batas yang telah ditentukan.']);
            }
        }

        $session = MentoringSession::where('id', $request->session_id)->first();

        if (!$session) {
            return redirect()->back()->with(['messege' => 'Sesi mentoring tidak ditemukan', 'alert-type' => 'error']);
        }

        if ($session->status != MentoringSession::STATUS_PENDING) {
            return redirect()->back()->with(['messege' => 'Tanggal mentoring hanya bisa diubah saat mentor belum menerima sesi', 'alert-type' => 'error']);
        }

        $session->mentoring_date = $request->mentoring_date;
        $session->save();

        return redirect()->back()->with(['messege' => 'Sesi mentoring berhasil diperbarui', 'alert-type' => 'success']);
    }
}
