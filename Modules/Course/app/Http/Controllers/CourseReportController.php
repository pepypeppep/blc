<?php

namespace Modules\Course\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ErrorReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseReport = ErrorReport::paginate(10);

        return view('course::course-report.index', compact('courseReport'));
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
    public function store(Request $request)
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
    public function update(Request $request, $id)
    {
        $errorReport = ErrorReport::find($id);
        $errorReport->update([
            'status' => $errorReport->status == 'reported' ? 'solved' : 'reported',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menyelesaikan error',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
