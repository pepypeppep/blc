<?php

namespace Modules\Course\app\Http\Controllers\Api;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Course::query();
            $query->when($request->keyword, fn($q) => $q->where('title', 'like', '%' . request('keyword') . '%'));
            $query->when($request->category, function ($q) use ($request) {
                $q->whereHas('category', function ($q) use ($request) {
                    $q->where('id', $request->category);
                });
            });
            $query->when($request->date && $request->filled('date'), fn($q) => $q->whereDate('start_date', $request->date));
            $query->when($request->instructor && $request->filled('instructor'), function ($q) use ($request) {
                $q->where('instructor_id', $request->instructor);
            });
            $query->withCount('enrollments');
            if ($request->participant_id && $request->filled('participant_id')) {
                $query->whereHas('enrollments', function ($q) use ($request) {
                    $q->where('user_id', $request->participant_id);
                });
            }
            $orderBy = $request->order_by == 'asc' ? 'asc' : 'desc';
            $courses = $request->par_page == 'all' ?
                $query->orderBy('id', $orderBy)->get() :
                $query->orderBy('id', $orderBy)->paginate($request->par_page ?? null)->withQueryString();

            return response()->json([
                'status' => 'success',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('course::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($slug)
    {
        $course = Course::active()->with(['chapters' => function ($query) {
            $query->orderBy('order', 'asc')->with(['chapterItems', 'chapterItems.lesson', 'chapterItems.quiz']);
        }, 'levels', 'reviews'])->withCount(['reviews' => function ($query) {
            $query->where('status', 1)->whereHas('course')->whereHas('user');
        }])->where('slug', $slug)->firstOrFail();
        $course->thumbnail = "/course/$course->slug/file";

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function file($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $path = $course->thumbnail;
        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);
        $response = response()->make($file, 200);
        $response->header("Content-Type", $mimeType);

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('course::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
