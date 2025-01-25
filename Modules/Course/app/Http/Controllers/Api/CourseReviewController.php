<?php

namespace Modules\Course\app\Http\Controllers\Api;

use App\Models\CourseReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class CourseReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = CourseReview::query();
            $query->with(['course:title,id']);
            $query->whereHas('course')->whereHas('user');
            $query->when($request->keyword, fn($q) => $q->whereHas('course', fn($q) => $q->where('title', 'like', "%{$request->keyword}%")));

            $query->when($request->status, fn($q) => $q->where('status', $request->status));
            if ($request->participant_id && $request->filled('participant_id')) {
                $query->whereHas('enrollments', function ($q) use ($request) {
                    $q->where('user_id', $request->participant_id);
                });
            }
            $orderBy = $request->order_by == 1 ? 'asc' : 'desc';
            $reviews = $request->get('par-page') == 'all' ?
                $query->orderBy('id', $orderBy)->get() :
                $query->orderBy('id', $orderBy)->paginate($request->get('par-page') ?? null)->withQueryString();

            return response()->json([
                'status' => 'success',
                'data' => $reviews
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
    public function show($id)
    {
        return view('course::show');
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
