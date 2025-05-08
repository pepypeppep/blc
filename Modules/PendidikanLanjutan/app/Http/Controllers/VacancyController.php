<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Imports\VacanciesImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Modules\PendidikanLanjutan\app\Models\Unor;
use Modules\PendidikanLanjutan\app\Models\Study;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterAttachment;

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
        $instansi = Instansi::get();

        return view('pendidikanlanjutan::Vacancy.create', compact('studies', 'instansi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'study_id' => 'required|integer|exists:studies,id',
            'instansi_id' => 'required|integer|exists:instansis,id',
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
            'instansi_id.required' => 'Instansi wajib diisi.',
            'instansi_id.exists' => 'Instansi yang dipilih tidak valid.',
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
                'instansi_id',
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
        $attachments = VacancyMasterAttachment::get();
        $instansi = Instansi::get();

        return view('pendidikanlanjutan::Vacancy.edit', compact('vacancy', 'instansi', 'studies', 'attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'study_id' => 'required|integer|exists:studies,id',
            'instansi_id' => 'required|integer|exists:instansis,id',
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
            'instansi_id.required' => 'Instansi wajib diisi.',
            'instansi_id.exists' => 'Instansi yang dipilih tidak valid.',
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
                'instansi_id',
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


    public function import(Request $request)
    {
        try {
            $import = new VacanciesImport();
            Excel::import($import, $request->file('vacancies'));
    
            return redirect()->route('admin.vacancies.index')->with('success', "Vacancy imported successfully. Successfully imported {$import->imported} data. Duplicates skipped: {$import->skipped}.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.vacancies.index')->with('error', $e->getMessage());
        }
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

    public function updateAttachments(Request $request, $vacancyId)
    {
        $vacancy = Vacancy::findOrFail($vacancyId);

        $request->validate([
            'attachments.*' => 'required|integer|exists:vacancy_master_attachments,id',
        ]);

        $vacancyAttachments = VacancyAttachment::where('vacancy_id', $vacancyId)->where('category', 'syarat')->get()->pluck('id')->toArray();
        $diffs = array_diff($vacancyAttachments, $request->attachments);
        if (count($diffs) > 0) {
            foreach ($diffs as $key => $diffId) {
                VacancyAttachment::where('id', $diffId)->delete();
            }
        }

        foreach ($request->attachments as $key => $att) {
            $attachment = VacancyMasterAttachment::findOrFail($att);
            $isRequired = $request->is_required[$attachment->id] ?? 0;

            $x = VacancyAttachment::updateOrCreate([
                'vacancy_id' => $vacancyId,
                'name' => $attachment->name
            ], [
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat',
                'is_required' => $isRequired
            ]);
            // dd($request->all());
        }

        return redirect()->back()->with('success', 'Attachment updated successfully.');
    }

    public function sidebarCounter()
    {
        $verif = VacancyUser::where('status', VacancyUser::STATUS_VERIFICATION)->count();
        $asses = VacancyUser::where('status', VacancyUser::STATUS_ASSESSMENT)->count();
        $sk = VacancyUser::where('status', VacancyUser::STATUS_ELIGIBLE)->count();
        $rpt = VacancyUser::where('status', VacancyUser::STATUS_REPORT)->count();
        $ext = VacancyUser::where('status', VacancyUser::STATUS_EXTEND)->count();
        $act = VacancyUser::where('status', VacancyUser::STATUS_ACTIVATION)->count();

        return response()->json([
            'verif' => $verif,
            'asses' => $asses,
            'sk' => $sk,
            'rpt' => $rpt,
            'ext' => $ext,
            'act' => $act,
        ]);
    }
}
