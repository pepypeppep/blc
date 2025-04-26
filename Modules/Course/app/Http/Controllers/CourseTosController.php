<?php

namespace Modules\Course\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Course\app\Models\CourseTos;

class CourseTosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('course::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tos = CourseTos::find(1);

        return view('course::course.course-tos.create', compact('tos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        try {
            $tos = CourseTos::updateOrCreate([
                'id' => 1
            ], [
                'description' => $request->description
            ]);

            return redirect()->back()->with(['alert-type' => 'success', 'messege' => __('Term of Service saved successfully')]);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => $th->getMessage()]);
        }
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
