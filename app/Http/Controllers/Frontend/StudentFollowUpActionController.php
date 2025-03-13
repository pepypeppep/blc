<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CourseProgress;
use App\Models\FollowUpAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Order\app\Models\Enrollment;
use Illuminate\Support\Str;

class StudentFollowUpActionController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 10);
        $limit = $request->get('limit', 1);

        $items = FollowUpAction::where('user_id', Auth::user()->id)
            ->with(['course' => function ($q) {
                $q->withTrashed()->select('id', 'title', 'thumbnail');
            }])
            ->paginate($page);
        return view('frontend.student-dashboard.follow-up-action.index', compact('items'));
    }

    public function create()
    {
        //data course
        $items = CourseProgress::with('course')
            ->where(
                'user_id',
                userAuth()->id,
            )
            ->where('watched', 1)
            ->get();

        return view('frontend.student-dashboard.follow-up-action.create', compact('items'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'course_id' => 'required',
            'summary' => 'required',
            'file_path' => 'required|mimes:pdf|max:30720',
        ], [
            'course_id.required' => 'Kursus harus diisi',
            'summary.required' => 'Ringkasan harus diisi',
            'file_path.required' => 'Wajib Mengunggah file pdf',
            'file_path.mimes' => 'File harus berupa pdf',
            'file_path.max' => 'Ukuran file maksimal 30 MB',
        ]);

        $followUpAction = new FollowUpAction();
        $followUpAction->user_id = userAuth()->id;
        $followUpAction->course_id = $request->course_id;
        $followUpAction->summary = $request->summary;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            // Bersihkan karakter khusus
            $fileOriginalName = $file->getClientOriginalName();
            $fileSanitizedName = preg_replace('/[^A-Za-z0-9\-]/', '-', pathinfo($fileOriginalName, PATHINFO_FILENAME));
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'rtl/' . Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;

            $file->storeAs('private', $fileName, 'local');

            $followUpAction->file_path = Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;
        }

        if ($followUpAction->save()) {
            return redirect()->route('student.follow-up-action.index')->with('success', 'Rencana tindak lanjut berhasil dibuat.');
        }

        return redirect()->route('student.follow-up-action.index')->withInput()->withErrors('Gagal membuat rencana tindak lanjut.');
    }
    public function edit($id)
    {
        $followUpAction = FollowUpAction::findOrFail($id);

        $items = CourseProgress::with('course')
            ->where(
                'user_id',
                userAuth()->id,
            )
            ->where('watched', 1)
            ->get();

        return view('frontend.student-dashboard.follow-up-action.edit', compact('followUpAction', 'items'));
    }

    public function update(Request $request, $id)
    {
        $followUpAction = FollowUpAction::findOrFail($id);
        $request->validate([
            'course_id' => 'required',
            'summary' => 'required',
            'file_path' => 'sometimes|mimes:pdf|max:30720',
        ], [
            'course_id.required' => 'Kursus harus diisi',
            'summary.required' => 'Ringkasan harus diisi',
            'file_path.required' => 'Wajib Mengunggah file pdf',
            'file_path.mimes' => 'File harus berupa pdf',
            'file_path.max' => 'Ukuran file maksimal 30 MB',
        ]);
        $followUpAction->user_id = userAuth()->id;
        $followUpAction->course_id = $request->course_id;
        $followUpAction->summary = $request->summary;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            // Bersihkan karakter khusus
            $fileOriginalName = $file->getClientOriginalName();
            $fileSanitizedName = preg_replace('/[^A-Za-z0-9\-]/', '-', pathinfo($fileOriginalName, PATHINFO_FILENAME));
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'rtl/' . Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;

            $file->storeAs('private', $fileName, 'local');

            $followUpAction->file_path = Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;
        }


        if ($followUpAction->save()) {
            return redirect()->route('student.follow-up-action.index')->with('success', 'Rencana tindak lanjut berhasil diperbarui.');
        }

        return redirect()->route('student.follow-up-action.index')->withInput()->withErrors('Rencana tindak lanjut gagal diperbarui.');
    }

    public function destroy($id)
    {
        $followUpAction = FollowUpAction::findOrFail($id);
        if ($followUpAction->delete()) {
            return redirect()->route('student.follow-up-action.index')->with('success', 'Rencana tindak lanjut berhasil dihapus.');
        }

        return redirect()->route('student.follow-up-action.index')->withInput()->withErrors('Rencana tindak lanjut gagal dihapus.');
    }
    public function show($id)
    {
        $item = FollowUpAction::where('user_id', Auth::user()->id)
            ->with(['course' => function ($q) {
                $q->withTrashed()->select('id', 'title', 'thumbnail');
            }])
            ->where('id', $id)
            ->firstOrFail();

        return view('frontend.student-dashboard.follow-up-action.show', compact('item'));
    }
}