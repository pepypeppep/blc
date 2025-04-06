<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StudentPelatihanStoreRequest;
use App\Http\Requests\Frontend\StudentPelatihanUpdateRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Article\app\Models\Article;
use Modules\Order\app\Models\Enrollment;

class StudentPengetahuanController extends Controller
{
    public function index(): View
    {
        $pengetahuans = Article::where('author_id', userAuth()->id)->with('enrollment.course')->get();
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
        if ($request->enrollment != null) {
            $enrollment = Enrollment::where('user_id', userAuth()->id)->where('id', $request->enrollment)->first();
            if (!$enrollment) {
                return redirect()->back()->with(['messege' => __('Enrollment not found'), 'alert-type' => 'error']);
            }
        }


        $path = 'pengetahuan';
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
        $result = Article::create([
            'slug' => generateUniqueSlug(Article::class, $request->title) . '_' . now()->timestamp,
            'author_id' => userAuth()->id,
            'category' => $request->category,
            'enrollment_id' => $request->enrollment != null ? $enrollment->id : null,
            'title' => $request->title,
            'thumbnail' => $thumbnailName,
            'visibility' => $request->visibility,
            'allow_comment' => $request->allow_comment == '1' ? '1' : '0',
            'link' => $request->link,
            'file' => $fileName ?? null,
            'content' => $request->content,
            'status' => Article::STATUS_DRAFT,
        ]);

        if (isset($request->tags)) {
            $tags = [];
            foreach ($request->tags as $tag) {
                $res = Tag::firstOrCreate(['name' => $tag]);
                array_push($tags, $res->id);
            }
            $pengetahuan = Article::where('slug', $result->slug)->first();
            // dd($pengetahuan);
            $pengetahuan->articleTags()->attach($tags);
            $pengetahuan->save();
        }

        if ($result) {
            DB::commit();
            return redirect()->route('student.pengetahuan.index')->with(['messege' => __('Pengetahuan created successfully'), 'alert-type' => 'success']);
        } else {
            DB::rollBack();
            return redirect()->back()->with(['messege' => __('Pengetahuan created failed'), 'alert-type' => 'error']);
        }
    }

    public function edit($slug)
    {
        $pengetahuan = Article::where('slug', $slug)->with(['enrollment.course', 'articleTags'])->first();
        if (!$pengetahuan) {
            return redirect()->back()->with(['messege' => __('Pengetahuan not found'), 'alert-type' => 'error']);
        }
        $enrollments = Enrollment::where('user_id', userAuth()->id)->with('course')->get();
        $tags = Tag::all();
        return view('frontend.student-dashboard.pengetahuan.edit', compact('pengetahuan', 'enrollments', 'tags'));
    }

    public function update($slug, StudentPelatihanUpdateRequest $request)
    {
        $pengetahuan = Article::where('slug', $slug)->with('enrollment.course')->first();
        if (!$pengetahuan) {
            return redirect()->back()->with(['messege' => __('Pengetahuan not found'), 'alert-type' => 'error']);
        }

        if ($pengetahuan->status != Article::STATUS_DRAFT && $pengetahuan->status != Article::STATUS_VERIFICATION) {
            return redirect()->back()->with(['messege' => __('Pengetahuan cannot be updated because it has status ' . $pengetahuan->status . ''), 'alert-type' => 'error']);
        }

        if ($request->enrollment != null) {
            $enrollment = Enrollment::where('user_id', userAuth()->id)->where('id', $request->enrollment)->first();
            if (!$enrollment) {
                return redirect()->back()->with(['messege' => __('Enrollment not found'), 'alert-type' => 'error']);
            }
        }
        

        $path = 'pengetahuan';

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
            'slug' => generateUniqueSlug(Article::class, $request->title) . '_' . now()->timestamp,
            'author_id' => userAuth()->id,
            'category' => $request->category,
            'enrollment_id' => isset($request->enrollment_id) ? $enrollment->id : $pengetahuan->enrollment_id,
            'title' => $request->title,
            'thumbnail' => $thumbnailName,
            'visibility' => $request->visibility,
            'allow_comment' => $request->allow_comment == 'on' ? '1' : '0',
            'link' => $request->link,
            'file' => $fileName ?? null,
            'content' => $request->content,
            'status' => Article::STATUS_DRAFT,
        ]);

        if (isset($request->tags)) {
            $tags = [];
            foreach ($request->tags as $tag) {
                $res = Tag::firstOrCreate(['name' => $tag]);
                array_push($tags, $res->id);
            }
            $pengetahuan->pengetahuanTags()->attach($tags);
            $pengetahuan->save();
        }

        if ($result) {
            DB::commit();
            return redirect()->route('student.pengetahuan.index')->with(['messege' => __('Pengetahuan updated successfully'), 'alert-type' => 'success']);
        } else {
            DB::rollBack();
            return redirect()->back()->with(['messege' => __('Pengetahuan updated failed'), 'alert-type' => 'error']);
        }
    }

    public function view($id)
    {
        $pengetahuan = Article::where('id', $id)->first();
        if (Storage::disk('public')->exists($pengetahuan->thumbnail)) {
            return Storage::disk('public')->response($pengetahuan->thumbnail);
        } else {
            abort(404);
        }
    }
}
