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
        $pengetahuans = Article::where('author_id', userAuth()->id)->with('enrollment.course')->orderBy('created_at', 'desc')->paginate(5);
        return view('frontend.student-dashboard.pengetahuan.index', compact('pengetahuans'));
    }

    public function show($slug): View
    {
        $pengetahuan = Article::where('slug', $slug)->with(['enrollment.course', 'articleTags'])->first();
        return view('frontend.student-dashboard.pengetahuan.show', compact('pengetahuan'));
    }

    public function create(): View
    {
        $user = userAuth();
        $enrollments = Enrollment::where('user_id', $user->id)->with('course')->get();
        $articles = Article::where('author_id', $user->id)->with('enrollment.course')->get();
        $alreadyTakenCourses = $articles->pluck('enrollment.course')->unique()->pluck('id')->toArray();

        $enrollments = $enrollments->filter(function ($enrollment) use ($alreadyTakenCourses) {
            return !in_array($enrollment->course->id, $alreadyTakenCourses);
        });

        $completedCourses = $enrollments->filter(function ($enrollment) {
            return $enrollment->course->iscompleted();
        });


        $tags = Tag::all();
        return view('frontend.student-dashboard.pengetahuan.create', compact('completedCourses', 'tags'));
    }

    public function store(StudentPelatihanStoreRequest $request)
    {
        if ($request->enrollment != null) {
            $enrollment = Enrollment::where('user_id', userAuth()->id)->where('id', $request->enrollment)->first();
            if (!$enrollment) {
                return redirect()->back()->with(['messege' => __('Enrollment not found'), 'alert-type' => 'error']);
            }

            $article = Article::where('enrollment_id', $enrollment->id)->first();
            if ($article) {
                return redirect()->back()->with(['messege' => __('Pengetahuan already created for this enrollment'), 'alert-type' => 'error']);
            }
        }

        DB::beginTransaction();

        $result = Article::create([
            'slug' => generateUniqueSlug(Article::class, $request->title) . '_' . now()->timestamp,
            'author_id' => userAuth()->id,
            'category' => $request->category,
            'enrollment_id' => $request->enrollment != null ? $enrollment->id : null,
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'allow_comments' => $request->allow_comments == 'on' ? '1' : '0',
            'link' => $request->link,
            'content' => $request->content,
            'status' => Article::STATUS_DRAFT,
        ]);

        $path = 'pengetahuan/' . now()->year . '/' . now()->month . '/' . $result->id . '/';
        if ($request->category == 'video') {
            $request->validate([
                'link' => 'required|url',
            ]);
        } elseif ($request->category == 'document') {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240',
            ]);

            $file = $request->file('file');
            $fileName = $path . "document_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));
        }
        if ($request->category == 'blog') {
            $request->validate([
                'content' => 'required',
            ]);
        }

        $thumbnail = $request->file('thumbnail');

        $thumbnailName = $path . "thumbnail_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $thumbnail->getClientOriginalExtension();
        Storage::disk('private')->put($thumbnailName, file_get_contents($thumbnail));

        $result->update([
            'thumbnail' => $thumbnailName,
            'file' => $fileName ?? null,
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

    public function destroy($slug)
    {
        $pengetahuan = Article::where('slug', $slug)->first();
        if (!$pengetahuan) {
            return redirect()->back()->with(['messege' => __('Pengetahuan not found'), 'alert-type' => 'error']);
        }
        if ($pengetahuan->status != Article::STATUS_DRAFT && $pengetahuan->status != Article::STATUS_REJECTED) {
            return abort(404);
        }
        if ($pengetahuan->author_id != userAuth()->id) {
            return redirect()->back()->with(['messege' => __('You are not allowed to delete this pengetahuan'), 'alert-type' => 'error']);
        }
        $result = $pengetahuan->delete();
        if ($result) {
            return redirect()->route('student.pengetahuan.index')->with(['messege' => __('Pengetahuan deleted successfully'), 'alert-type' => 'success']);
        } else {
            return redirect()->back()->with(['messege' => __('Pengetahuan deleted failed'), 'alert-type' => 'error']);
        }
    }

    public function edit($slug)
    {
        $pengetahuan = Article::where('slug', $slug)->with(['enrollment.course', 'articleTags'])->first();
        if (!$pengetahuan) {
            return redirect()->back()->with(['messege' => __('Pengetahuan not found'), 'alert-type' => 'error']);
        }

        if ($pengetahuan->status != Article::STATUS_DRAFT && $pengetahuan->status != Article::STATUS_REJECTED) {
            return abort(404);
        }

        $user = userAuth();
        $enrollments = Enrollment::where('user_id', $user->id)->with('course')->get();
        $completedCourses = $enrollments->filter(function ($enrollment) {
            return $enrollment->course->iscompleted();
        });
        $tags = Tag::all();
        return view('frontend.student-dashboard.pengetahuan.edit', compact('pengetahuan', 'completedCourses', 'tags'));
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

            if ($pengetahuan->enrollment_id != $enrollment->id) {
                $article = Article::where('enrollment_id', $enrollment->id)->first();
                if ($article) {
                    return redirect()->back()->with(['messege' => __('Pengetahuan already created for this enrollment'), 'alert-type' => 'error']);
                }
            }
        }


        $path = 'pengetahuan/' . now()->year . '/' . now()->month . '/' . $pengetahuan->id . '/';

        if ($request->category == 'document' && $request->file('file') !== null) {
            $file = $request->file('file');
            $fileName = $path . "document_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));
        }


        if ($request->category == 'blog') {
            $request->validate([
                'content' => 'required',
            ]);
        }
        if (null !== $request->file('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            if ($thumbnail) {
                $thumbnailName = $path . "thumbnail_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', userAuth()->name) . "." . $thumbnail->getClientOriginalExtension();
                Storage::disk('private')->put($thumbnailName, file_get_contents($thumbnail));
            } else {
                $thumbnailName = $pengetahuan->thumbnail;
            }
        }


        DB::beginTransaction();

        $result = $pengetahuan->update([
            'slug' => generateUniqueSlug(Article::class, $request->title) . '_' . now()->timestamp,
            'author_id' => userAuth()->id,
            'category' => $request->category,
            'enrollment_id' => isset($request->enrollment_id) ? $enrollment->id : $pengetahuan->enrollment_id,
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailName ?? $pengetahuan->thumbnail,
            'visibility' => $request->visibility,
            'allow_comments' => $request->allow_comments == 'on' ? '1' : '0',
            'link' => $request->link ?? $pengetahuan->link,
            'file' => $fileName ?? $pengetahuan->file,
            'content' => $request->content,
            'status' => Article::STATUS_DRAFT,
        ]);

        if (isset($request->tags)) {
            $tags = [];
            foreach ($request->tags as $tag) {
                $res = Tag::firstOrCreate(['name' => $tag]);
                array_push($tags, $res->id);
            }
            $pengetahuan->articleTags()->sync($tags);
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
        $pengetahuan = Article::with('enrollment.course')->where('id', $id)->first();
        if (Storage::disk('private')->exists($pengetahuan->thumbnail)) {
            return Storage::disk('private')->response($pengetahuan->thumbnail);
        } else {
            abort(404);
        }
    }

    public function ajukanPengetahuan($slug)
    {
        $pengetahuan = Article::where('slug', $slug)->where('author_id', userAuth()->id)->first();

        if (!$pengetahuan) {
            return redirect()->back()->with(['messege' => __('Pengetahuan not found'), 'alert-type' => 'error']);
        }

        if ($pengetahuan->status == Article::STATUS_PUBLISHED) {
            return redirect()->back()->with(['messege' => __('Pengetahuan already published'), 'alert-type' => 'error']);
        }

        if ($pengetahuan->status == Article::STATUS_VERIFICATION) {
            return redirect()->back()->with(['messege' => __('Pengetahuan sedang diverifikasi'), 'alert-type' => 'error']);
        }

        $pengetahuan->status = Article::STATUS_VERIFICATION;
        $pengetahuan->save();

        return redirect()->route('student.pengetahuan.index')->with(['messege' => __('Pengetahuan berhasil diajukan untuk diverifikasi'), 'alert-type' => 'success']);
    }
}
