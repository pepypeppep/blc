<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Models\User;
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
use Modules\PendidikanLanjutan\app\Models\VacancyDetail;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyUserDirect;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = now()->year;

        $query = Vacancy::query();

        $query->when($request->keyword, function ($q) {
            $q->whereHas('study', function ($q) {
                $q->where('name', 'ilike', '%' . request('keyword') . '%');
            });
        });

        $query->when($request->year, function ($q) {
            $q->where('year', request('year'));
        });

        $query->when($request->education_level, function ($q) {
            $q->where('education_level', request('education_level'));
        });

        $query->when($request->instansi, function ($q) {
            $q->whereHas('instansi', function ($q) {
                $q->where('name', 'ilike', '%' . request('instansi') . '%');
            });
        });

        $vacancies = $query->orderByDesc('id')->paginate($request->get('par-page') ?? null)->withQueryString();

        if ($request->year) {
            $year = $request->year;
        }

        $currVacancy = Vacancy::whereNull('transferred_from')->where('year', $year)->get();
        $prevVacancy = 0;
        foreach ($vacancies as $key => $vacancy) {
            $prevVacancy += Vacancy::where('study_id', $vacancy->study_id)
                ->where('education_level', $vacancy->education_level)
                ->where('instansi_id', $vacancy->instansi_id)
                ->where('is_full', false)
                ->whereNull('transferred_from')
                ->where('year', '!=', $year)
                ->count();
        }

        return view('pendidikanlanjutan::Vacancy.index', compact('vacancies', 'currVacancy', 'prevVacancy'));
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
            'formation' => 'required|integer',
            'description' => 'nullable|string',
            'year' => 'required|digits:4|integer|between:1900,' . date('Y')
        ], [
            'study_id.required' => 'Program studi wajib diisi.',
            'study_id.exists' => 'Program studi yang dipilih tidak valid.',
            'instansi_id.required' => 'Instansi wajib diisi.',
            'instansi_id.exists' => 'Instansi yang dipilih tidak valid.',
            'education_level.required' => 'Jenjang pendidikan wajib diisi.',
            'employment_grade.required' => 'Pangkat/Golongan pekerjaan wajib diisi.',
            'formation.required' => 'Formasi wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.between' => 'Tahun harus antara 2024 hingga ' . date('Y') . '.',
        ]);

        $vacancy = null;

        DB::transaction(function () use ($request, &$vacancy) {
            // Membuat vacancy baru
            $vacancy = Vacancy::create($request->only([
                'study_id',
                'instansi_id',
                'education_level',
                'employment_grade',
                'formation',
                'description',
                'year',
            ]));
        });

        return redirect()->route('admin.vacancies.edit', $vacancy->id)->with('success', 'Vacancy created successfully.');
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
        $directMembers = VacancyUserDirect::where('vacancy_id', $id)->get();
        $users = User::whereNotIn('id', $directMembers->pluck('user_id'))->where('role', 'student')->get();
        $members = VacancyUser::where('vacancy_id', $id)->get();

        return view('pendidikanlanjutan::Vacancy.edit', compact('vacancy', 'instansi', 'studies', 'attachments', 'directMembers', 'users', 'members'));
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
            'formation' => 'required|integer',
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
            'formation.required' => 'Formasi wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.between' => 'Tahun harus antara 2024 hingga ' . date('Y') . '.',
        ]);


        $vacancy = null;

        DB::transaction(function () use ($request, $id, &$vacancy) {
            $vacancy = Vacancy::findOrFail($id);
            $vacancy->update($request->only([
                'study_id',
                'instansi_id',
                'education_level',
                'employment_grade',
                'formation',
                'description',
                'year',
            ]));
        });

        return redirect()->route('admin.vacancies.edit', $vacancy->id)->with('success', 'Vacancy created successfully.');
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

    public function updateVacancyDetail($id, Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'employment_status' => 'required|array',
            'employment_status.*' => 'in:Tidak diberhentikan dari Jabatan,Diberhentikan dari Jabatan',
            'cost_type' => 'required|array',
            'cost_type.*' => 'in:APBD,Non APBD,Mandiri',
            'age_limit' => 'required|array',
            'age_limit.*' => 'integer',
        ], [
            'employment_status.required' => 'Status pegawai wajib diisi.',
            'employment_status.array' => 'Status pegawai harus berupa array.',
            'employment_status.*.in' => 'Status pegawai tidak valid.',
            'cost_type.required' => 'Tipe biaya wajib diisi.',
            'cost_type.array' => 'Tipe biaya harus berupa array.',
            'cost_type.*.in' => 'Tipe biaya tidak valid.',
            'age_limit.required' => 'Batas usia wajib diisi.',
            'age_limit.array' => 'Batas usia harus berupa array.',
            'age_limit.*.integer' => 'Batas usia harus berupa angka.'
        ]);

        DB::transaction(function () use ($request, $id) {
            $existingDetails = VacancyDetail::where('vacancy_id', $id)->get();
            $inputCount = count($request->employment_status);

            for ($i = 0; $i < $inputCount; $i++) {
                if (isset($existingDetails[$i])) {
                    // Update existing record
                    $existingDetails[$i]->update([
                        'employment_status' => $request->employment_status[$i],
                        'cost_type' => $request->cost_type[$i],
                        'age_limit' => $request->age_limit[$i],
                    ]);
                } else {
                    // Create new record if it doesn't exist
                    VacancyDetail::create([
                        'vacancy_id' => $id,
                        'employment_status' => $request->employment_status[$i],
                        'cost_type' => $request->cost_type[$i],
                        'age_limit' => $request->age_limit[$i],
                    ]);
                }
            }

            // Delete excess records (if input has fewer items than existing records)
            if ($existingDetails->count() > $inputCount) {
                VacancyDetail::where('vacancy_id', $id)
                    ->whereNotIn('id', $existingDetails->take($inputCount)->pluck('id'))
                    ->delete();
            }
        });

        return redirect()->route('admin.vacancies.edit', $id)->with('success', 'Vacancy updated successfully.');
    }

    public function transferVacancy($year)
    {
        $vacancies = Vacancy::where('year', $year)->get();

        foreach ($vacancies as $vacancy) {
            $prev = Vacancy::where('year', $year - 1)
                ->where('study_id', $vacancy->study_id)
                ->where('education_level', $vacancy->education_level)
                ->where('instansi_id', $vacancy->instansi_id)
                ->where('is_full', false)
                ->whereNull('transferred_from')
                ->first();
            if ($prev) {
                $vacancy->update([
                    'formation' => $vacancy->formation + ($prev->formation - $prev->accepted),
                    'transferred_from' => $prev->id,
                    'amount_transferred' => $prev->formation - $prev->accepted,
                    'transferred_at' => now()
                ]);
                $prev->update([
                    'transferred_to' => $vacancy->id,
                    'is_full' => true
                ]);
            }
        }

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy transfered successfully.');
    }

    public function directInvite($id, Request $request)
    {
        $vacancy = Vacancy::find($id);

        foreach ($request->users as $key => $user) {
            VacancyUserDirect::updateOrCreate([
                'vacancy_id' => $id,
                'user_id' => $user
            ], []);
        }

        $vacancy->update([
            'is_direct_invited' => true
        ]);

        return redirect()->route('admin.vacancies.edit', $id)->with('success', 'Pengundangan berhasil dikirim.');
    }
}
