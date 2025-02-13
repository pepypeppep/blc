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
        $userId = userAuth()->id;
        $attachments = VacancyUserAttachment::whereHas('vacancyattachment', function ($query) use ($userId) {
            $query->where('vacancy_id', $userId);
        })->where('vacancy_user_id', $vacancy->id)->get();
        $reports = VacancyReport::where('vacancy_user_id', $vacancy->id)->get();
        return view('frontend.student-dashboard.continuing-education.registration.show', compact('vacancy', 'logs', 'attachments', 'reports'));
    }

    // detail pendidikan
    function continuingEducationDetail($id)
    {
        $vacancy = Vacancy::with(['study', 'users' => function ($query) {
            $query->where('user_id', userAuth()->id)->whereNotIn('status', ['register']); // next update with value_type, unor, dll
        }])->findOrFail($id);
        // dd($vacancy);
        $base = VacancyAttachment::syarat()->where('vacancy_id', $id)->where('is_active', 1);
        $vacancyConditions = $base->with('attachment')->get();
        $vacancyTakeConditions = $base->whereHas('attachment', function ($query) use ($vacancy) {
            if ($vacancy->users->first()) {
                $query->where('vacancy_user_id', $vacancy->users->first()->id);
            }
        })->get();

        $meetCondition = (count($vacancyTakeConditions) == count($vacancyConditions));

        return view('frontend.student-dashboard.continuing-education.show', compact('vacancy', 'vacancyConditions', 'meetCondition'));
    }


    public function uploadRequirementFile(UploadRequirementFileRequest $request, $id)
    {
        $request->validated();

        $attachment = VacancyAttachment::findOrFail($id);

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

        $fileName = "vacancy/" . now()->year . "/attachments" . "/" . str_replace([' ', '/'], '_', $attachment->name) . "_" . str_replace(' ', '_', userAuth()->name) . ".pdf";
        Storage::disk('private')->put($fileName, file_get_contents($file));

        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->first();

        $result = VacancyUserAttachment::create([
            'vacancy_attachment_id' => $attachment->id,
            'vacancy_user_id' => $vacancyUser->id,
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
    public function register($vacancyId)
    {
        $vacancy = Vacancy::findOrFail($vacancyId);

        if (!$vacancy) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyAttachments = VacancyAttachment::syarat()->where('vacancy_id', $vacancyId)->where('is_active', 1)->get();

        if (!$vacancyAttachments) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->first();
        $base = VacancyAttachment::syarat()->where('vacancy_id', $vacancyId)->where('is_active', 1);
        $vacancyConditions = $base->with('attachment')->get();
        $vacancyTakeConditions = $base->whereHas('attachment', function ($query) use ($vacancyUser) {
            $query->where('vacancy_user_id', $vacancyUser->id);
        })->get();

        $meetCondition = (count($vacancyTakeConditions) == count($vacancyConditions));

        if (!$meetCondition) {
            return redirect()->back()->with(['messege' => 'Anda belum mengupload semua syarat', 'alert-type' => 'error']);
        }

        $closedDate = $vacancy->close_at;

        if ($closedDate < now()) {
            return redirect()->back()->with(['messege' => 'Pendaftaran sudah ditutup', 'alert-type' => 'error']);
        }

        if ($vacancy->users()->where('user_id', userAuth()->id)->exists()) {
            return redirect()->back()->with(['messege' => 'Anda sudah terdaftar', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        $vacancyUser = VacancyUser::create([
            'vacancy_id' => $vacancyId,
            'user_id' => userAuth()->id,
            'status' => 'verification',
        ]);

        $vacancyId = VacancyUser::where('user_id', userAuth()->id)->where('vacancy_id', $vacancyId)->first()->id;

        VacancyLogs::create([
            'vacancy_user_id' => $vacancyId,
            'name' => 'Pendaftaran',
            'description' => 'Telah melakukan pendaftaran',
            'status' => 'success',
        ]);

        if (!$vacancyUser) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => 'Pendaftaran gagal', 'alert-type' => 'error']);
        }

        DB::commit();
        return redirect('student/continuing-education-registration/' . $vacancyId)->with(['message' => 'Pendaftaran berhasil', 'alert-type' => 'success']);
    }

    public function vacancyReportSubmit(StudentVacancyReportRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->first();

        $reportExist = VacancyReport::where('vacancy_user_id', $vacancyUser->id)->where('name', $validated['name'])->exists();

        if ($reportExist) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => 'Laporan sudah ada', 'alert-type' => 'error']);
        }


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
