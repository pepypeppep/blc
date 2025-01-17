<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\PendidikanLanjutan\app\Models\Unor;
use Modules\PendidikanLanjutan\app\Models\Vacancy;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vacancies = Vacancy::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Vacancy successfully rendered.',
            'data' => $vacancies,
        ], 200);

        // return view('PendidikanLanjutan::vacancies.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('PendidikanLanjutan::vacancies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'periode_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date|before:end_at',
            'end_at' => 'nullable|date|after:start_at',
            'year' => 'required|digits:4|integer|between:1900,'.date('Y'),
            'vacancy_details' => 'required|array|min:1',
            'vacancy_details.*.name' => 'required|string|max:255',
            'vacancy_details.*.category' => 'required|string|max:255',
            'vacancy_details.*.type' => 'nullable|string|max:255',
            'vacancy_details.*.type_value' => 'nullable|string|max:255',
            'vacancy_details.*.description' => 'nullable|string',
            'unor_ids.*' => 'required|exists:unors,id',
        ], [
            'periode_id.required' => 'Periode ID wajib diisi.',
            'name.required' => 'Nama lowongan wajib diisi.',
            'start_at.before' => 'Tanggal mulai harus sebelum tanggal berakhir.',
            'end_at.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.between' => 'Tahun harus antara 1900 hingga '.date('Y').'.',
            'vacancy_details.required' => 'Detail lowongan wajib diisi.',
        ]);

        DB::transaction(function () use ($request) {
            // Membuat vacancy baru
            $vacancy = Vacancy::create($request->only([
                'periode_id',
                'name',
                'description',
                'start_at',
                'end_at',
                'year',
            ]));

            // Menambahkan Vacancy Details
            foreach ($request->vacancy_details as $vacancy_detail) {
                $vacancy->details()->create($vacancy_detail);
            }

            // Menambahkan Unor terkait dengan Vacancy
            $vacancy->unors()->attach($request->unor_ids);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Vacancy created successfully.',
        ], 200);

        // return redirect()->route('vacancies.index')->with('success', 'Vacancy created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        return view('PendidikanLanjutan::vacancies.show', compact('vacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        return view('PendidikanLanjutan::vacancies.edit', compact('vacancy'));
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
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->delete();

        return redirect()->route('vacancies.index');
    }
}
