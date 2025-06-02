<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CourseProgress;
use App\Models\FollowUpAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FollowUpActionResponse;
use Modules\Order\app\Models\Enrollment;
use Yajra\DataTables\Facades\DataTables;

class StudentFollowUpActionController extends Controller
{
    public function index(Request $request)
    {

        // $items = FollowUpAction::query()
        //     ->whereHas('course.enrollments', function ($q) {
        //         $q->where('user_id', auth()->id())
        //             ->where('has_access', 1);
        //     })
        //     ->with([
        //         'course' => function ($q) {
        //             $q->withTrashed()->select('id', 'title', 'thumbnail');
        //         }
        //     ])
        //     ->with('chapter')
        //     ->get();
        // dd($items->toArray());

        if ($request->ajax()) {
            $items = FollowUpAction::query()
                ->whereHas('course.enrollments', function ($q) {
                    $q->where('user_id', auth()->id())
                        ->where('has_access', 1);
                })
                ->with([
                    'course' => function ($q) {
                        $q->withTrashed()->select('courses.id', 'title', 'thumbnail');
                    }
                ])
                ->with('chapter')->get();

            return DataTables::of($items)
                ->addColumn('action', function ($item) {
                    $show = route('student.follow-up-action.show',  $item->id);
                    $button = "
                    <a href='{$show}' class='align-middle' data-bs-toggle='tooltip' data-bs-original-title='Tindak Lanjuti RTL ini'>
                                            <i class='fas fa-eye'></i> Melihat
                                        </a>";
                    return $button;
                })
                ->make(true);
        }


        return view('frontend.student-dashboard.follow-up-action.index');
    }

    public function create()
    {
        // abort(404);
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
            'follow_up_action_id' => 'required',
            'summary' => 'required',
            'file_path' => 'required|mimes:pdf|max:30720',
        ], [
            'follow_up_action_id.required' => 'Objek Rencana Tindak Lanjut harus diisi',
            'summary.required' => 'Ringkasan harus diisi',
            'file_path.required' => 'Wajib Mengunggah file pdf',
            'file_path.mimes' => 'File harus berupa pdf',
            'file_path.max' => 'Ukuran file maksimal 30 MB',
        ]);

        $response = new FollowUpActionResponse;
        $response->participant_id = Auth::user()->id;
        $response->follow_up_action_id = $request->follow_up_action_id;
        $response->participant_response = $request->summary;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            // Bersihkan karakter khusus
            $fileOriginalName = $file->getClientOriginalName();
            $fileSanitizedName = preg_replace('/[^A-Za-z0-9\-]/', '-', pathinfo($fileOriginalName, PATHINFO_FILENAME));
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'rtl/' . Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;

            $file->storeAs('private', $fileName, 'local');

            $response->participant_file = Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;
        }

        if ($response->save()) {
            CourseProgress::where('user_id', userAuth()->id)
                ->where('course_id', $response->course_id)
                ->where('chapter_id', $response->chapter_id)
                ->where('lesson_id', $response->id)
                ->update([
                    'watched' => 1
                ]);
            $course = Course::find($response->course_id);

            return redirect()->route('student.learning.index', $course->slug)->with(['alert-type' => 'success', 'message' => __('Rencana tindak lanjut berhasil dibuat')]);
        }

        return redirect()->route('student.follow-up-action.show', $request->follow_up_action_id)->withInput()->withErrors('Gagal membuat rencana tindak lanjut.');
    }
    public function edit($id)
    {
        // abort(404);
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
        $response = FollowUpActionResponse::findOrFail($id);

        $request->validate([
            'summary' => 'required',
            'file_path' => 'required|mimes:pdf|max:30720',
        ], [
            'summary.required' => 'Ringkasan harus diisi',
            'file_path.required' => 'Wajib Mengunggah file pdf',
            'file_path.mimes' => 'File harus berupa pdf',
            'file_path.max' => 'Ukuran file maksimal 30 MB',
        ]);

        $response->participant_response = $request->summary;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            // Bersihkan karakter khusus
            $fileOriginalName = $file->getClientOriginalName();
            $fileSanitizedName = preg_replace('/[^A-Za-z0-9\-]/', '-', pathinfo($fileOriginalName, PATHINFO_FILENAME));
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'rtl/' . Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;

            $file->storeAs('private', $fileName, 'local');

            $response->participant_file = Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;
        }


        if ($response->save()) {
            CourseProgress::where('user_id', userAuth()->id)
                ->where('course_id', $response->course_id)
                ->where('chapter_id', $response->chapter_id)
                ->where('lesson_id', $response->id)
                ->update([
                    'watched' => 1
                ]);
            $course = Course::find($response->course_id);

            return redirect()->route('student.learning.index', $course->slug)->with(['alert-type' => 'success', 'message' => __('Rencana tindak lanjut berhasil diperbarui.')]);
        }

        return redirect()->route('student.follow-up-action.show', $response->follow_up_action_id)->withInput()->withErrors('Rencana tindak lanjut gagal diperbarui.');
    }

    public function destroy($id)
    {
        abort(404);
        // $followUpAction = FollowUpAction::findOrFail($id);
        // if ($followUpAction->delete()) {
        //     return redirect()->route('student.follow-up-action.index')->with('success', 'Rencana tindak lanjut berhasil dihapus.');
        // }

        // return redirect()->route('student.follow-up-action.index')->withInput()->withErrors('Rencana tindak lanjut gagal dihapus.');
    }
    public function show($id)
    {

        $item = FollowUpAction::query()
            ->whereHas('course.enrollments', function ($q) {
                $q->where('user_id', Auth::user()->id)
                    ->where('has_access', 1);
            })
            ->with([
                'course' => function ($q) {
                    $q->withTrashed()->select('courses.id', 'title', 'thumbnail');
                }
            ])
            ->with('chapter', 'followUpActionResponse')
            ->where('id', $id)
            ->first();
        // dd($item->toArray());

        return view('frontend.student-dashboard.follow-up-action.show', compact('item'));
    }
}
