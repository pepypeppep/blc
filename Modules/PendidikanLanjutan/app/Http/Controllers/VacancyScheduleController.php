<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PendidikanLanjutan\app\Models\VacancySchedule;

class VacancyScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $submenu = 'Schedule';
        $schedules = VacancySchedule::paginate();

        return view('pendidikanlanjutan::master.schedule.index', compact('submenu', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pendidikanlanjutan::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_at' => ['required', 'date', 'before:end_at'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'year' => ['required', 'digits:4', 'integer', 'between:1900,' . date('Y')],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $check_year = VacancySchedule::whereYear('start_at', $request->year)->first();
            if ($check_year) {
                return redirect()->back()->with(['messege' => 'Schedule for current year is exist', 'alert-type' => 'warning']);
            } else {
                $schedule = new VacancySchedule;
                $schedule->start_at = $request->start_at;
                $schedule->end_at = $request->end_at;
                $schedule->year = $request->year;
                $schedule->description = $request->description;
                $schedule->save();
            }

            return redirect()->route('pendidikanlanjutan.schedule.index')->with(['messege' => 'Data Berhasil Ditambahkan', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with(['messege' => $th->getMessage(), 'alert-type' => 'error']);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('pendidikanlanjutan::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pendidikanlanjutan::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'start_at' => ['required', 'date', 'before:end_at'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $schedule = VacancySchedule::find($id);
            $schedule->start_at = $request->start_at;
            $schedule->end_at = $request->end_at;
            $schedule->description = $request->description;
            $schedule->save();

            return redirect()->back()->with(['messege' => 'Data Berhasil Diubah', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with(['messege' => $th->getMessage(), 'alert-type' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
