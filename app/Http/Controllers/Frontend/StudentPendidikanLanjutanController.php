<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StudentVacancyReportRequest;
use App\Http\Requests\Frontend\UploadRequirementFileRequest;
use App\Models\VacancyReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyLogs;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class StudentPendidikanLanjutanController extends Controller
{
    // list pendidikan 
    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 10);
        $vacancies = Vacancy::paginate($perPage);

        return view('frontend.student-dashboard.continuing-education.index', compact('vacancies'));
    }

    // list pendidikan yang sudah diambil
    function registered()
    {
        $vacancies = Vacancy::whereHas('users', function ($query) {
            $query->where('user_id', userAuth()->id); // next update with value_type, unor, dll
        })->with(['users', 'study'])->paginate(10);

        // dd($vacancies->first());

        return view('frontend.student-dashboard.continuing-education.registration.index', compact('vacancies'));
    }

    function registeredDetail($id)
    {
        $vacancy = VacancyUser::with(['vacancy', 'user'])->findOrFail($id);
        $logs = VacancyLogs::where('vacancy_user_id', $id)->get();
        $attachments = VacancyUserAttachment::with('vacancyattachment')->where('vacancy_user_id', $vacancy->user_id)->get();
        $reports = VacancyReport::where('vacancy_user_id', $vacancy->id)->get();

        // dd($attachments);
        return view('frontend.student-dashboard.continuing-education.registration.show', compact('vacancy', 'logs', 'attachments', 'reports'));
    }

    // detail pendidikan
    function continuingEducationDetail($id)
    {
        $vacancy = Vacancy::with('study')->findOrFail($id);
        $vacancyConditions = VacancyAttachment::syarat()->with('attachment')->where('vacancy_id', $id)->where('is_active', 1)->get();

        // dd($vacancyConditions);
        return view('frontend.student-dashboard.continuing-education.show', compact('vacancy', 'vacancyConditions'));
    }


    public function uploadRequirementFile(UploadRequirementFileRequest $request)
    {
        $validated = $request->validated();

        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->first();

        $userAttachment = VacancyUserAttachment::where('vacancy_attachment_id', $validated['attachment_id'])->where('vacancy_user_id', $vacancyUser->user_id)->first();

        if ($userAttachment) {
            return redirect()->back()->with(['messege' => __('File requirement already uploaded'), 'alert-type' => 'error']);
        }

        $attachment = VacancyAttachment::findOrFail($validated['attachment_id']);

        if (!$attachment) {
            return redirect()->back()->with(['messege' => __('Attachment not found'), 'alert-type' => 'error']);
        }

        $file = $request->file('file');

        $validator = Validator::make(['file' => $file], [
            'file' => 'required|file|mimes:' . $attachment->type . '|max:' . $attachment->max_size . '',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['messege' => $validator->errors()->first(), 'alert-type' => 'error']);
        }

        $fileName = "vacancy/" . now()->year . "/attachments" . "/" . str_replace([' ', '/'], '_', $attachment->name) . "_" . str_replace(' ', '_', $vacancyUser->user->name) . ".pdf";
        Storage::disk('private')->put($fileName, file_get_contents($file));

        $result = VacancyUserAttachment::create([
            'vacancy_attachment_id' => $attachment->id,
            'vacancy_user_id' => $vacancyUser->user->id,
            'file' => $fileName,
            'category' => $attachment->category
        ]);

        VacancyLogs::create([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => 'Upload file requirement',
            'description' => 'Mengupload file requirement ' . $attachment->name,
            'status' => 'success',
        ]);

        if (!$result) {
            return redirect()->back()->with(['messege' => __('Upload file requirement failed'), 'alert-type' => 'error']);
        }


        return redirect()->back()->with(['messege' => __('Upload file requirement successfully'), 'alert-type' => 'success']);
    }

    // pengajuan pendaftaran
    public function register(Request $request, $vacancyId)
    {
        $vacancy = Vacancy::findOrFail($vacancyId);

        $closedDate = $vacancy->close_at;

        if ($closedDate < now()) {
            return redirect()->back()->with(['messege' => 'Pendaftaran sudah ditutup', 'alert-type' => 'error']);
        }

        if ($vacancy->users()->where('user_id', userAuth()->id)->exists()) {
            return redirect()->back()->with(['messege' => 'Anda sudah terdaftar', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::create([
            'vacancy_id' => $vacancyId,
            'user_id' => userAuth()->id,
            'status' => 'register',
        ]);

        if (!$vacancyUser) {
            return redirect()->back()->with(['messege' => 'Pendaftaran gagal', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['messege' => 'Pendaftaran berhasil', 'alert-type' => 'success']);
    }

    public function vacancyReportSubmit(StudentVacancyReportRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->first();


        $file = $request->file('file');
        $fileName = "laporan_semester/" . now()->year . "/laporan_semester_" . $vacancyUser->vacancy_id . "_" . $vacancyUser->user->name . ".pdf";
        Storage::disk('private')->put($fileName, file_get_contents($file));

        $result = VacancyReport::create([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => $validated['name'],
            'file' => $fileName,
            'status' => 'pending',
        ]);

        VacancyLogs::create([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => $validated['name'],
            'description' => 'Telah mengirim laporan',
            'status' => 'success',
        ]);

        if (!$result) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => __('Create vacancy report failed'), 'alert-type' => 'error']);
        }

        DB::commit();
        return redirect()->back()->with(['messege' => __('Create vacancy report successfully'), 'alert-type' => 'success']);
    }

    public function checkProgramStudy(int $vacancyId)
    {
        $vacancy = Vacancy::findOrFail($vacancyId);
        $checking = [];
    }
}
