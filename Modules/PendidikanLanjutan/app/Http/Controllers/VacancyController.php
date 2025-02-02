<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\PendidikanLanjutan\app\Models\Unor;
use Modules\PendidikanLanjutan\app\Models\Study;
use Modules\PendidikanLanjutan\app\Models\Vacancy;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vacancies = Vacancy::orderByDesc('updated_at')->paginate();

        return view('pendidikanlanjutan::Vacancy.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $studies = Study::all();

        return view('pendidikanlanjutan::Vacancy.create', compact('studies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'study_id' => 'required|integer|exists:studies,id',
            'education_level' => 'required|string',
            'employment_grade' => 'required|string',
            'employment_status' => 'required|string',
            'cost_type' => 'required|string',
            'formation' => 'required|integer',
            'age_limit' => 'required|integer',
            'description' => 'nullable|string',
            'year' => 'required|digits:4|integer|between:1900,' . date('Y'),

            // 'start_at' => 'nullable|date|before:end_at',
            // 'end_at' => 'nullable|date|after:start_at',
        ], [
            'study_id.required' => 'Program studi wajib diisi.',
            'study_id.exists' => 'Program studi yang dipilih tidak valid.',
            'education_level.required' => 'Jenjang pendidikan wajib diisi.',
            'employment_grade.required' => 'Pangkat/Golongan pekerjaan wajib diisi.',
            'employment_status.required' => 'Status pekerjaan wajib diisi.',
            'cost_type.required' => 'Jenis biaya wajib diisi.',
            'formation.required' => 'Formasi wajib diisi.',
            'age_limit.required' => 'Umur wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.between' => 'Tahun harus antara 2024 hingga ' . date('Y') . '.',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // Membuat vacancy baru
            $vacancy = Vacancy::create($request->only([
                'study_id',
                'education_level',
                'employment_grade',
                'employment_status',
                'cost_type',
                'formation',
                'age_limit',
                'description',
                'year',
            ]));

            // Menambahkan Vacancy Details
            // $details = [
            //     'education_level' => $validated['education_level'],
            //     'study_program' => $validated['study_program'],
            //     'minimum_rank' => $validated['minimum_rank'],
            //     'employment_status' => $validated['employment_status'],
            //     'funding_source' => $validated['funding_source'],
            //     'formasi_count' => $validated['formasi_count'],
            //     'retirement_age' => $validated['retirement_age'],
            // ];

            // foreach ($details as $name => $value) {
            //     $vacancy->details()->create([
            //         'name' => $name,
            //         'category' => 'syarat',
            //         'type' => $name,
            //         'value_type' => $value,
            //     ]);
            // }

            // // Menambahkan Unor terkait dengan Vacancy
            // $vacancy->unors()->attach($request->unor_ids);
        });

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        return view('pendidikanlanjutan::Vacancy.show', compact('vacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        $studies = Study::all();

        return view('pendidikanlanjutan::Vacancy.edit', compact('vacancy', 'studies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'study_id' => 'required|integer|exists:studies,id',
            'education_level' => 'required|string',
            'employment_grade' => 'required|string',
            'employment_status' => 'required|string',
            'cost_type' => 'required|string',
            'formation' => 'required|integer',
            'age_limit' => 'required|integer',
            'description' => 'nullable|string',
            'year' => 'required|digits:4|integer|between:1900,' . date('Y'),

            // 'start_at' => 'nullable|date|before:end_at',
            // 'end_at' => 'nullable|date|after:start_at',
        ], [
            'study_id.required' => 'Program studi wajib diisi.',
            'study_id.exists' => 'Program studi yang dipilih tidak valid.',
            'education_level.required' => 'Jenjang pendidikan wajib diisi.',
            'employment_grade.required' => 'Pangkat/Golongan pekerjaan wajib diisi.',
            'employment_status.required' => 'Status pekerjaan wajib diisi.',
            'cost_type.required' => 'Jenis biaya wajib diisi.',
            'formation.required' => 'Formasi wajib diisi.',
            'age_limit.required' => 'Umur wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.between' => 'Tahun harus antara 2024 hingga ' . date('Y') . '.',
        ]);

        DB::transaction(function () use ($request, $id) {
            $vacancy = Vacancy::findOrFail($id);
            $vacancy->update($request->only([
                'study_id',
                'education_level',
                'employment_grade',
                'employment_status',
                'cost_type',
                'formation',
                'age_limit',
                'description',
                'year',
            ]));
        });

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vacancy = Vacancy::with('users')->findOrFail($id);
        if ($vacancy->users()->exists()) {
            return redirect()->route('admin.vacancies.index')->with('error', 'Vacancy cannot be deleted because it has participants.');
        }
        $vacancy->delete();

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy deleted successfully.');
    }

    public function updatePublicationStatus($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        if ($vacancy->published_at === null) {
            $vacancy->published_at = now();
            $message = 'Vacancy published successfully.';
        } else {
            $vacancy->published_at = null;
            $message = 'Vacancy unpublished successfully.';
        }

        $vacancy->save();

        return redirect()->route('admin.vacancies.index')->with('success', $message);
    }
}
