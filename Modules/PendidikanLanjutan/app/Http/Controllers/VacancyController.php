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
        $vacancies = Vacancy::orderByDesc('updated_at')->get();

        return view('pendidikanlanjutan::Vacancy.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pendidikanlanjutan::Vacancy.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date|before:end_at',
            'end_at' => 'nullable|date|after:start_at',
            'year' => 'required|digits:4|integer|between:1900,'.date('Y'),
            'unor_ids.*' => 'required|exists:unors,id',
            'education_level' => 'required|string',
            'study_program' => 'required|string',
            'minimum_rank' => 'required|string',
            'employment_status' => 'required|string',
            'funding_source' => 'required|string',
            'formasi_count' => 'required|integer',
            'retirement_age' => 'required|integer'
        ], [
            'name.required' => 'Nama lowongan wajib diisi.',
            'start_at.before' => 'Tanggal mulai harus sebelum tanggal berakhir.',
            'end_at.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.between' => 'Tahun harus antara 1900 hingga '.date('Y').'.',
            'unor_ids.*.required' => 'ID Unor wajib diisi.',
            'unor_ids.*.exists' => 'ID Unor yang dipilih tidak valid.',
            'education_level.required' => 'Jenjang pendidikan lanjutan wajib diisi.',
            'education_level.string' => 'Jenjang pendidikan lanjutan harus berupa teks.',
            'study_program.required' => 'Program studi lanjutan wajib diisi.',
            'study_program.string' => 'Program studi lanjutan harus berupa teks.',
            'minimum_rank.required' => 'Pangkat minimum wajib diisi.',
            'minimum_rank.string' => 'Pangkat minimum harus berupa teks.',
            'employment_status.required' => 'Status kepegawaian wajib diisi.',
            'employment_status.string' => 'Status kepegawaian harus berupa teks.',
            'funding_source.required' => 'Sumber pendanaan wajib diisi.',
            'funding_source.string' => 'Sumber pendanaan harus berupa teks.',
            'formasi_count.required' => 'Jumlah formasi wajib diisi.',
            'formasi_count.integer' => 'Jumlah formasi harus berupa angka.',
            'retirement_age.required' => 'Batas usia pensiun wajib diisi.',
            'retirement_age.integer' => 'Batas usia pensiun harus berupa angka.'
        ]);

        DB::transaction(function () use ($request, $validated) {
            // Membuat vacancy baru
            $vacancy = Vacancy::create($request->only([
                'name',
                'description',
                'start_at',
                'end_at',
                'year',
            ]));

            // Menambahkan Vacancy Details
            $details = [
                'education_level' => $validated['education_level'],
                'study_program' => $validated['study_program'],
                'minimum_rank' => $validated['minimum_rank'],
                'employment_status' => $validated['employment_status'],
                'funding_source' => $validated['funding_source'],
                'formasi_count' => $validated['formasi_count'],
                'retirement_age' => $validated['retirement_age'],
            ];

            foreach ($details as $name => $value) {
                $vacancy->details()->create([
                    'name' => $name,
                    'category' => 'syarat',
                    'type' => $name,
                    'value_type' => $value,
                ]);
            }

            // Menambahkan Unor terkait dengan Vacancy
            $vacancy->unors()->attach($request->unor_ids);
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

        return view('pendidikanlanjutan::Vacancy.edit', compact('vacancy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
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
            'name.required' => 'Nama lowongan wajib diisi.',
            'start_at.before' => 'Tanggal mulai harus sebelum tanggal berakhir.',
            'end_at.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.between' => 'Tahun harus antara 1900 hingga '.date('Y').'.',
            'vacancy_details.required' => 'Detail lowongan wajib diisi.',
            'unor_ids.*.required' => 'ID Unor wajib diisi.',
            'unor_ids.*.exists' => 'ID Unor yang dipilih tidak valid.',
        ]);

        DB::transaction(function () use ($request, $id) {
            // Mencari vacancy yang akan diupdate
            $vacancy = Vacancy::findOrFail($id);

            // Memperbarui data Vacancy
            $vacancy->update($request->only([
                'name',
                'description',
                'start_at',
                'end_at',
                'year',
            ]));

            // Memperbarui Vacancy Details tanpa menghapus
            foreach ($request->vacancy_details as $vacancy_detail) {
                // Cek jika detail sudah ada, maka update, jika tidak ada buat baru
                if (isset($vacancy_detail['id']) && $vacancy_detail['id']) {
                    $existingDetail = $vacancy->details()->find($vacancy_detail['id']);
                    if ($existingDetail) {
                        $existingDetail->update($vacancy_detail);
                    }
                } else {
                    // Jika belum ada id, buat detail baru
                    $vacancy->details()->create($vacancy_detail);
                }
            }

            // Mengupdate Unor terkait dengan Vacancy
            $vacancy->unors()->sync($request->unor_ids);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Vacancy updated successfully.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vacancy = Vacancy::findOrFail($id);
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
