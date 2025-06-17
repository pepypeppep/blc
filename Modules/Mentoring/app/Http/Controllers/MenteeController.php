<?php

namespace Modules\Mentoring\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringSession;
use app\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = userAuth();
        $mentorings = Mentoring::where('mentee_id', $user->id)->orderByDesc('id')->paginate();

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
            'total_session' => 'required|integer|min:3',
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
            $fileName = $path . 'mentor_letter' . $file->getClientOriginalExtension();
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

        return redirect()->route('student.mentee.index')->with('success', 'Tema mentoring berhasil ditambah!');
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
            'link' => route('student.mentor.index'),
            'path' => null,
        ]);

        return redirect()->route('student.mentee.index')->with('success', 'Mentoring berhasil diajukan.');
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

        $session->save();

        //kirim notifikasi
        sendNotification([
            'user_id' => $session->mentoring->mentor_id,
            'title' => 'Laporan Pertemuan Baru',
            'body' => "Mentee telah melaporkan hasil pertemuan. Silakan periksa laporan tersebut.",
            'link' => route('student.mentor.index'),
            'path' => null,
        ]);

        return back()->with('success', 'Detail pertemuan berhasil diperbarui.');
    }

    public function updateFinalReport(Request $request, Mentoring $mentoring)
    {
        $request->validate([
            'final_report' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('final_report')) {
            $path = 'mentoring/' . now()->year . '/' . $mentoring->id . '/';
            $file = $request->file('final_report');
            $fileName = $path . 'final_report' . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));

            $mentoring->update([
                'final_report' => $fileName
            ]);
        }

        //kirim notifikasi
        sendNotification([
            'user_id' => $mentoring->mentor_id,
            'title' => 'Laporan Akhir Telah Diunggah',
            'body' => "Mentee telah mengunggah laporan akhir mentoring. Silakan periksa dokumen tersebut.",
            'link' => route('student.mentor.index'),
            'path' => null,
        ]);

        return redirect()->back()->with('success', 'Laporan akhir berhasil diunggah.');
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
}
