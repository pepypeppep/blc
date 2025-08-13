<?php

namespace Modules\Mentoring\app\Http\Controllers;

use app\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Mentoring\app\Models\Mentoring;
use App\Services\CoachingMentoringSessionChecker;
use Modules\Mentoring\app\Models\MentoringSession;
use Modules\Mentoring\app\Models\MentoringFeedback;

class MenteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = userAuth();
        $mentorings = Mentoring::where('mentee_id', $user->id)->orderByDesc('id')->paginate(10);

        return view('frontend.student-dashboard.mentoring.mentee.index', compact('mentorings'));
    }


    public function create()
    {
        $mentors = User::all();
        return view('frontend.student-dashboard.mentoring.mentee.create', compact('mentors'));
    }

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
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['sessions' => 'Maaf Anda hanya diperbolehkan mengajukan maksimal 2 pertemuan dalam satu bulan. Permintaan Anda melebihi batas yang telah ditentukan.']);
            }
        }

        $checkCoaching = (new CoachingMentoringSessionChecker())->canAddCoachingSessions($user, $validated['sessions']);
        if (!$checkCoaching['can_proceed']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sessions' => $checkCoaching['reason']]);
        }

        $checkCoaching2 = (new CoachingMentoringSessionChecker())->canAddCoaching2Sessions($user, $validated['sessions']);
        if (!$checkCoaching2['can_proceed']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sessions' => $checkCoaching2['reason']]);
        }

        $checkMentoring = (new CoachingMentoringSessionChecker())->canAddMentoringSessions($user, $validated['sessions']);
        if (!$checkMentoring['can_proceed']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sessions' => $checkMentoring['reason']]);
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

        return redirect()->route('student.mentee.index')->with(['messege' => 'Tema mentoring berhasil ditambahkan!', 'alert-type' => 'success']);
    }

    public function show($id)
    {
        $mentoring = Mentoring::with('mentor', 'mentoringSessions')->findOrFail($id);
        $hasIncompleteSessions = $mentoring->mentoringSessions->contains(function ($session) {
            return empty($session->activity);
        });
        return view('frontend.student-dashboard.mentoring.mentee.show', compact('mentoring', 'hasIncompleteSessions'));
    }

    public function submitForApproval($id)
    {
        $mentoring = Mentoring::findOrFail($id);
        if ($mentoring->status !== Mentoring::STATUS_DRAFT) {
            return back()->with('error', 'Mentoring sudah diajukan.');
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

        return redirect()->route('student.mentee.index')->with(['messege' => 'Mentoring berhasil diajukan!', 'alert-type' => 'success']);
    }

    public function updateSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:mentoring_sessions,id',
            'activity' => 'required|string',
            'obstacle' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png|max:2048',
        ]);

        $session = MentoringSession::with('mentoring')->findOrFail($request->session_id);
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

        return redirect()->back()->with(['messege' => 'Detail pertemuan berhasil diperbarui!', 'alert-type' => 'success']);
    }

    public function updateFinalReport(Request $request, Mentoring $mentoring)
    {
        $request->validate([
            'final_report' => 'required|file|mimes:pdf|max:5120',
        ]);

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

        return redirect()->back()->with(['messege' => 'Laporan akhir berhasil diunggah!', 'alert-type' => 'success']);
    }

    public function showDocument($id, $type)
    {
        $mentoring = Mentoring::findOrFail($id);
        return $mentoring->getDocumentResponse($type);
    }

    public function viewImage($id)
    {
        $session = MentoringSession::findOrFail($id);
        if (Storage::disk('private')->exists($session->image)) {
            return Storage::disk('private')->response($session->image);
        } else {
            abort(404);
        }
    }

    public function feedback(Request $request, $id)
    {
        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentee_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if (empty($mentoring->final_report)) {
            return redirect()->back()->with(['messege' => 'Penilaian untuk mentor hanya bisa dilakukan jika laporan akhir telah diunggah', 'alert-type' => 'error']);
        }

        $feedback = MentoringFeedback::where('mentoring_id', $mentoring->id)->first();

        return view('frontend.student-dashboard.mentoring.mentee.feedback', compact('mentoring', 'feedback'));
    }

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

        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentee_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if (empty($mentoring->final_report)) {
            return redirect()->back()->with(['messege' => 'Penilaian untuk mentor hanya bisa dilakukan jika laporan akhir telah diunggah', 'alert-type' => 'error']);
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

        return redirect()->route('student.mentee.index')->with(['messege' => 'Penilaian untuk mentor berhasil dilakukan', 'alert-type' => 'success']);
    }
}
