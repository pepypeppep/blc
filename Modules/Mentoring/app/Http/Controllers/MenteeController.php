<?php

namespace Modules\Mentoring\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringSession;

class MenteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $menteeTopics = Mentoring::where('mentee_id', $user->id)->paginate(10);
        return view('frontend.student-dashboard.mentoring.mentee.index', compact('menteeTopics'));
    }

    public function create(Request $request): object
    {
        $mentors = User::all();
        return view('frontend.student-dashboard.mentoring.mentee.create', compact('mentors'));
    }

    public function store(Request $request): object
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'purpose' => 'required',
            'total_session' => 'required|integer|min:1|max:7',
            'file_name' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:2048',
        ], [
            'file.required' => 'File is required',
            'file_name.required' => 'File name is required',
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'purpose.required' => 'Purpose is required',
            'total_session.required' => 'Total session is required',
            'file.mimes' => 'File must be pdf',
            'file.max' => 'File size must be less than 2MB',
        ]);

        for ($i = 1; $i <= $request->total_session; $i++) {
            if (!$request->hasAny(['session_' . $i, 'session_date_' . $i])) {
                return redirect()->back()->with(['messege' => 'Session ' . $i . ' is required', 'alert-type' => 'error']);
            }
        }

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $filename = 'surat-kesediaan-mentor' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('private')->put('mentoring/documents/'  . $filename, file_get_contents($file));

            $mentoring = Mentoring::create([
                'title' => $request->title,
                'description' => $request->description,
                'purpose' => $request->purpose,
                'total_session' => $request->total_session,
                'mentor_id' => $request->mentor,
                'mentee_id' => userAuth()->id,
                'mentor_availability_letter' => $path,
                'status' => Mentoring::STATUS_DRAFT,
            ]);

            for ($i = 1; $i <= $request->total_session; $i++) {
                Mentoringsession::create([
                    'mentoring_id' => $mentoring->id,
                    'activity' => $request['session_' . $i],
                    'description' => $request['session_description_' . $i],
                    'mentoring_date' => $request['session_date_' . $i],
                    'status' => MentoringSession::STATUS_PENDING,

                ]);
            }

            DB::commit();
            return redirect()->route('student.mentee.index')->with(['messege' => __('Mentoring Topic Added'), 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => $th->getMessage(), 'alert-type' => 'error']);
        }
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $mentoring = Mentoring::with(["mentoringSessions" => function ($query) {
            $query->orderBy('mentoring_date', 'asc');
        }])->where('id', $id)->where('mentee_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        return view('frontend.student-dashboard.mentoring.mentee.show', compact('mentoring'));
    }

    public function ajukan(Request $request, $id)
    {
        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentee_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($mentoring->status != Mentoring::STATUS_DRAFT) {
            return redirect()->back()->with(['messege' => 'Pengajuan hanya bisa dilakukan jika memiliki status Draft', 'alert-type' => 'error']);
        }

        $mentoring->status = Mentoring::STATUS_SUBMISSION;
        $mentoring->save();

        return redirect()->route('student.mentee.show', $mentoring->id)->with(['messege' => 'Pengajuan berhasil', 'alert-type' => 'success']);
    }

    public function lapor(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg|max:2048',
            'activity' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ], [
            'activity.required' => 'Activity is required',
            'description.required' => 'Description is required',
            'file.required' => 'File is required',
            'file.mimes' => 'File must be jpeg, png, or jpg',
            'file.max' => 'File size must be less than 2MB',
        ]);

        $user = auth()->user();
        $session = MentoringSession::with('mentoring')->where('id', $id)->first();

        if (!$session) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($session->mentoring->mentee_id != $user->id) {
            return redirect()->back()->with(['messege' => 'Anda tidak memiliki akses ke sesi ini', 'alert-type' => 'error']);
        }

        if ($session->mentoring->status != Mentoring::STATUS_PROCESS) {
            return redirect()->back()->with(['messege' => 'Laporan hanya bisa dilakukan jika memiliki status Process', 'alert-type' => 'error']);
        }

        if ($session->mentoring_date > \Carbon\Carbon::now()) {
            return redirect()->back()->with(['messege' => 'Laporan hanya bisa dilakukan setelah tanggal sesi dimulai', 'alert-type' => 'error']);
        }

        try {
            $file = $request->file('file');
            $filename = 'laporan-photo' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('private')->put('mentoring/photos/' . $session->mentoring_id . '/' . $filename, file_get_contents($file));

            if (!$path) {
                return redirect()->back()->with(['messege' => 'Gagal menyimpan file', 'alert-type' => 'error']);
            }


            $session->activity = $request->activity;
            $session->description = $request->description;
            $session->image = $filename;
            $session->status = MentoringSession::STATUS_REPORTED;
            $session->save();

            return redirect()->route('student.mentee.show', $session->mentoring_id)->with(['messege' => 'Laporan berhasil dikirim', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['messege' => $th->getMessage(), 'alert-type' => 'error']);
        }
    }

    public function getReportFoto($id)
    {
        $user = auth()->user();
        $session = MentoringSession::with('mentoring')->where('id', $id)->first();

        if (!$session) {
            return redirect()->back()->with(['messege' => 'Sesi mentoring tidak ditemukan', 'alert-type' => 'error']);
        }

        if ($session->image) {
            $filePath = 'mentoring/photos/' . $session->mentoring_id . '/' . $session->image;
            if (Storage::disk('private')->exists($filePath)) {
                return Storage::disk('private')->download($filePath);
            } else {
                return redirect()->back()->with(['messege' => 'File tidak ditemukan', 'alert-type' => 'error']);
            }
        } else {
            return redirect()->back()->with(['messege' => 'Tidak ada foto laporan yang tersedia', 'alert-type' => 'error']);
        }
    }

    public function getSuratKesediaan($id)
    {
        $user = auth()->user();
        $mentoring = Mentoring::where('id', $id)->where('mentee_id', $user->id)->orWhere('mentor_id', $user->id)->first();

        if (!$mentoring) {
            return redirect()->back()->with(['messege' => 'Mentoring not found', 'alert-type' => 'error']);
        }

        if ($mentoring->mentor_availability_letter) {
            $filePath = 'mentoring/documents/' . $mentoring->mentor_availability_letter;
            if (Storage::disk('private')->exists($filePath)) {
                return Storage::disk('private')->download($filePath);
            } else {
                return redirect()->back()->with(['messege' => 'File not found', 'alert-type' => 'error']);
            }
        } else {
            return redirect()->back()->with(['messege' => 'No availability letter found', 'alert-type' => 'error']);
        }
    }
}
