<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StudentPelatihanStoreRequest;
use App\Http\Requests\Frontend\StudentPelatihanUpdateRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Order\app\Models\Enrollment;
use Modules\Pengetahuan\app\Models\Pengetahuan;

class StudentPengetahuanController extends Controller
{
    public function index(): View
    {
        $pengetahuans = Pengetahuan::where('user_id', userAuth()->id)->with('enrollment.course')->get();
        return view('frontend.student-dashboard.pengetahuan.index', compact('pengetahuans'));
    }

    public function create(): View
    {
        $enrollments = Enrollment::where('user_id', userAuth()->id)->with('course')->get();
        $tags = Tag::all();
        return view('frontend.student-dashboard.pengetahuan.create', compact('enrollments', 'tags'));
    }

    public function store(StudentPelatihanStoreRequest $request)
    {
        $enrollment = Enrollment::where('user_id', userAuth()->id)->where('id', $request->enrollment)->first();

        if (!$enrollment) {
            return redirect()->back()->with('error', __('You are not enrolled in this course'));
        }

        $path = 'pengetahuan/' . $enrollment->course->id;
        if ($request->category == 'video') {
            $request->validate([
                'link' => 'required|url',
            ]);
        } elseif ($request->category == 'document') {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240',
            ]);

            $file = $request->file('file');
            $fileName = $path . "/" . now()->month . "_" . "document_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $file->getClientOriginalExtension();
            Storage::disk('public')->put($fileName, file_get_contents($file));
        }
        if ($request->category == 'blog') {
            $request->validate([
                'content' => 'required',
            ]);
        }

        $thumbnail = $request->file('thumbnail');

        $thumbnailName = $path . "/" . now()->month . "_" . "thumbnail_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $thumbnail->getClientOriginalExtension();
        Storage::disk('public')->put($thumbnailName, file_get_contents($thumbnail));


        DB::beginTransaction();
        $result = Pengetahuan::create([
            'slug' => generateUniqueSlug(Pengetahuan::class, $request->title),
            'user_id' => userAuth()->id,
            'category' => $request->category,
            'enrollment_id' => $enrollment->id,
            'title' => $request->title,
            'thumbnail' => $thumbnailName,
            'visibility' => $request->visibility,
            'allow_comment' => $request->allow_comment == '1' ? '1' : '0',
            'link' => $request->link,
            'file' => $fileName ?? null,
            'content' => $request->content,
            'status' => Pengetahuan::STATUS_DRAFT,
        ]);

        if (isset($validated['tags'])) {
            $tags = [];
            foreach ($validated['tags'] as $tag) {
                $res = Tag::firstOrCreate(['name' => $tag]);
                array_push($tags, $res->id);
            }
            $pengetahuan = Pengetahuan::where('slug', $result->slug)->first();
            $pengetahuan->pengetahuanTags()->attach($tags);
            $pengetahuan->save();
        }

        if ($result) {
            DB::commit();
            return redirect()->route('student.pengetahuan.index')->with(['message' => __('Pengetahuan created successfully'), 'alert-type' => 'success']);
        } else {
            DB::rollBack();
            return redirect()->back()->with(['message' => __('Pengetahuan created failed'), 'alert-type' => 'error']);
        }
    }

    public function edit($slug)
    {
        $pengetahuan = Pengetahuan::where('slug', $slug)->with('enrollment.course')->first();
        if (!$pengetahuan) {
            return redirect()->back()->with(['message' => __('Pengetahuan not found'), 'alert-type' => 'error']);
        }
        $enrollments = Enrollment::where('user_id', userAuth()->id)->with('course')->get();
        $tags = Tag::all();
        return view('frontend.student-dashboard.pengetahuan.edit', compact('pengetahuan', 'enrollments', 'tags'));
    }

    public function update($slug, StudentPelatihanUpdateRequest $request)
    {
        $pengetahuan = Pengetahuan::where('slug', $slug)->with('enrollment.course')->first();
        if (!$pengetahuan) {
            return redirect()->back()->with(['message' => __('Pengetahuan not found'), 'alert-type' => 'error']);
        }

        if ($pengetahuan->status != Pengetahuan::STATUS_DRAFT && $pengetahuan->status != Pengetahuan::STATUS_VERIFICATION) {
            return redirect()->back()->with(['message' => __('Pengetahuan cannot be updated because it has status ' . $pengetahuan->status . ''), 'alert-type' => 'error']);
        }

        $path = 'pengetahuan/' . $pengetahuan->enrollment->course->id;
        if ($request->category == 'video') {
            $request->validate([
                'link' => 'required|url',
            ]);
        } elseif ($request->category == 'document') {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240',
            ]);

            $file = $request->file('file');
            $fileName = $path . "/" . now()->month . "_" . "document_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $file->getClientOriginalExtension();
            Storage::disk('public')->put($fileName, file_get_contents($file));
        }
        if ($request->category == 'blog') {
            $request->validate([
                'content' => 'required',
            ]);
        }
        $thumbnail = $request->file('thumbnail');
        if ($thumbnail) {
            $thumbnailName = $path . "/" . now()->month . "_" . "thumbnail_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $thumbnail->getClientOriginalExtension();
            Storage::disk('public')->put($thumbnailName, file_get_contents($thumbnail));
        } else {
            $thumbnailName = $pengetahuan->thumbnail;
        }

        DB::beginTransaction();

        $result = $pengetahuan->update([
            'slug' => generateUniqueSlug(Pengetahuan::class, $request->title),
            'user_id' => userAuth()->id,
            'category' => $request->category,
            'enrollment_id' => $pengetahuan->enrollment_id,
            'title' => $request->title,
            'thumbnail' => $thumbnailName,
            'visibility' => $request->visibility,
            'allow_comment' => $request->allow_comment == 'on' ? '1' : '0',
            'link' => $request->link,
            'file' => $fileName ?? null,
            'content' => $request->content,
            'status' => Pengetahuan::STATUS_DRAFT,
        ]);

        if (isset($validated['tags'])) {
            $tags = [];
            foreach ($validated['tags'] as $tag) {
                $res = Tag::firstOrCreate(['name' => $tag]);
                array_push($tags, $res->id);
            }
            $pengetahuan->pengetahuanTags()->attach($tags);
            $pengetahuan->save();
        }

        if ($result) {
            DB::commit();
            return redirect()->route('student.pengetahuan.index')->with(['message' => __('Pengetahuan updated successfully'), 'alert-type' => 'success']);
        } else {
            DB::rollBack();
            return redirect()->back()->with(['message' => __('Pengetahuan updated failed'), 'alert-type' => 'error']);
        }
    }

    public function view($id)
    {
        $pengetahuan = Pengetahuan::where('id', $id)->first();
        if (Storage::disk('public')->exists($pengetahuan->thumbnail)) {
            return Storage::disk('public')->response($pengetahuan->thumbnail);
        } else {
            abort(404);
        }
    }
}
