<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Unor;
use App\Models\Instansi;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\VacancyReport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyLogs;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use App\Http\Requests\Frontend\StudentVacancyReportRequest;
use App\Http\Requests\Frontend\UploadRequirementFileRequest;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterReportFiles;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class StudentPendidikanLanjutanController extends Controller
{
    // list pendidikan
    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 10);
        $vacancies = Vacancy::where('instansi_id', userAuth()->instansi_id)->where('open_at', '<', now())->where('close_at', '>', now())->paginate($perPage);

        return view('frontend.student-dashboard.continuing-education.index', compact('vacancies'));
    }

    // list pendidikan yang sudah diambil
    function registered()
    {
        $vacancies = Vacancy::whereHas('users', function ($query) {
            $query->where('user_id', userAuth()->id);
        })->paginate(10);
        return view('frontend.student-dashboard.continuing-education.registration.index', compact('vacancies'));
    }

    function registeredDetail($id)
    {
        $vacancy = VacancyUser::with(['vacancy.study', 'user.unor', 'user.instansi'])->findOrFail($id);
        $logs = VacancyLogs::where('vacancy_user_id', $vacancy->id)->orderBy('created_at', 'desc')->get();
        $attachments = VacancyUserAttachment::whereHas('vacancyattachment', function ($query) use ($vacancy) {
            $query->where('vacancy_id', $vacancy->vacancy_id);
        })->where('vacancy_user_id', $vacancy->id)->get();
        $lampirans = VacancyAttachment::lampiran()->where('vacancy_id', $vacancy->vacancy_id)->get();
        $reports = VacancyReport::where('vacancy_user_id', $vacancy->id)->orderBy('name')->get();
        $reportsFiles = VacancyMasterReportFiles::where('is_active', 1)->get();
        return view('frontend.student-dashboard.continuing-education.registration.show', compact('vacancy', 'logs', 'attachments', 'lampirans', 'reports','reportsFiles'));
    }

    // detail pendidikan
    function continuingEducationDetail($id)
    {
        $user = userAuth();
        $vacancy = Vacancy::with(['study', 'users' => function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereNotIn('status', [VacancyUser::STATUS_REGISTER]); // next update with value_type, unor, dll
        }])->findOrFail($id);

        if ($vacancy->instansi_id != $user->instansi_id) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        if ($vacancy->close_at < now()) {
            return redirect()->back()->with(['messege' => 'Pendaftaran sudah ditutup', 'alert-type' => 'error']);
        }

        if ($vacancy->open_at > now()) {
            return redirect()->back()->with(['messege' => 'Pendaftaran belum dibuka', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $id)->first();

        $passAgeLimit = $vacancy->age_limit >=  (Carbon::parse($user->date_of_birth)->diffInYears(Carbon::now()));
        $passEmployeeGrade = strtolower($vacancy->employment_grade) === strtolower($user->golongan);
        $base = VacancyAttachment::syarat()->where('vacancy_id', $id)->where('is_active', 1);
        $vacancyConditions = $base->with('attachment')->get();
        $vacancyTakeConditions = $base->whereHas('attachment', function ($query) use ($vacancy) {
            if ($vacancy->users->first()) {
                $query->where('vacancy_user_id', $vacancy->users->first()->id);
            }
        })->get();

        $meetCondition = (count($vacancyTakeConditions) == count($vacancyConditions));

        return view('frontend.student-dashboard.continuing-education.show', compact('vacancy', 'vacancyUser', 'vacancyConditions', 'meetCondition', 'passAgeLimit', 'passEmployeeGrade'));
    }


    public function uploadRequirementFile(UploadRequirementFileRequest $request, $id)
    {
        $request->validated();

        $attachment = VacancyAttachment::findOrFail($id);

        if (!$attachment) {
            return redirect()->back()->withFragment('attachment_container')->with(['messege' => __('Attachment not found'), 'alert-type' => 'error']);
        }
        DB::beginTransaction();

        VacancyUser::firstOrCreate([
            'user_id' => userAuth()->id,
            'vacancy_id' => $attachment->vacancy_id
        ], [
            'status' => VacancyUser::STATUS_REGISTER,
        ]);

        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->where('vacancy_id', $attachment->vacancy_id)->first();
        $vacancyUserAttachment = VacancyUserAttachment::with('vacancyattachment')->where('vacancy_attachment_id', $attachment->id)->where('vacancy_user_id', $vacancyUser->id)->first();
        $file = $request->file('file');

        $validator = Validator::make(['file' => $file], [
            'file' => 'required|file|mimes:' . $attachment->type . '|max:' . $attachment->max_size . '',
        ]);

        if ($validator->fails()) {
            DB::rollBack();
            return redirect()->back()->withFragment('attachment_container')->with(['messege' => $validator->errors()->first(), 'alert-type' => 'error']);
        }

        $fileName = "pendidikan_lanjutan/" . now()->year . "/syarat" . "/" . $attachment->vacancy_id . "/" . now()->month . "_" . str_replace([' ', '/'], '_', $attachment->name) . "_" . str_replace(' ', '_', userAuth()->name) . ".pdf";
        Storage::disk('private')->put($fileName, file_get_contents($file));

        $request->merge([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => 'Upload Berkas',
            'status' => $vacancyUser->status,
            'description' => 'File ' . $attachment->name . ' telah diupload',
        ]);
        if ($vacancyUserAttachment) {
            $result = $vacancyUserAttachment->update([
                'file' => $fileName
            ]);
            $request->merge([
                'name' => $vacancyUserAttachment->vacancyattachment->name,
            ]);
        } else {
            $result = VacancyUserAttachment::create([
                'vacancy_attachment_id' => $attachment->id,
                'vacancy_user_id' => $vacancyUser->id,
                'file' => $fileName,
                'category' => $attachment->category
            ]);
        }

        vacancyLog($request);

        if (!$result) {
            DB::rollBack();
            return redirect()->back()->withFragment('attachment_container')->with(['messege' => __('Upload file requirement failed'), 'alert-type' => 'error']);
        }


        DB::commit();
        return redirect()->back()->withFragment('attachment_container')->with(['messege' => __('Upload file requirement successfully'), 'alert-type' => 'success']);
    }

    // pengajuan pendaftaran
    public function register(Request $request, $vacancyId)
    {
        $vacancy = Vacancy::findOrFail($vacancyId);

        if (!$vacancy) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyAttachments = VacancyAttachment::syarat()->where('vacancy_id', $vacancy->id)->where('is_active', 1)->get();

        if (!$vacancyAttachments) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->where('vacancy_id', $vacancy->id)->first();
        $base = VacancyAttachment::syarat()->where('vacancy_id', $vacancy->id)->where('is_active', 1);
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

        if ($vacancy->users()->where('user_id', userAuth()->id)->whereNotIn('status', [VacancyUser::STATUS_REGISTER])->exists()) {
            return redirect()->back()->with(['messege' => 'Anda sudah terdaftar', 'alert-type' => 'error']);
        }

        DB::beginTransaction();

        $auth = userAuth();
        $instansi = Instansi::findOrFail($auth->instansi_id);

        $vacancyUser->update([
            'employment_grade' => $auth->golongan,
            'last_position' => $auth->jabatan,
            'instansi' => $instansi->name,
            'cost_type' => $vacancy->cost_type,
            'education_level' => $auth->tingkat_pendidikan,
            'last_education' => $auth->pendidikan,
            'status' => VacancyUser::STATUS_VERIFICATION
        ]);

        $request->merge([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => 'Pendaftaran',
            'status' => $vacancyUser->status,
            'description' => 'Telah melakukan pendaftaran',
        ]);

        vacancyLog($request);

        if (!$vacancyUser) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => 'Pendaftaran gagal', 'alert-type' => 'error']);
        }

        DB::commit();
        return redirect('student/continuing-education-registration/' . $vacancyUser->id)->with(['message' => 'Pendaftaran berhasil', 'alert-type' => 'success']);
    }

    public function vacancyReportSubmit(StudentVacancyReportRequest $request, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        $vacancyUser = VacancyUser::where('user_id', userAuth()->id)->where('vacancy_id', $id)->first();

        $reportFile = VacancyMasterReportFiles::where('id', $validated['name'])->first();
        
        $reportExist = VacancyReport::where('vacancy_user_id', $vacancyUser->id)->where('name', $reportFile->name)->exists();

        if ($reportExist) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => 'Laporan sudah ada', 'alert-type' => 'error']);
        }



        $file = $request->file('file');
        $fileName = "pendidikan_lanjutan/" . now()->year . "/laporan_semester" . "/" . $vacancyUser->vacancy_id . "/" . now()->month . "_" . str_replace([' ', '/'], '_', $reportFile->name) . "_" . str_replace(' ', '_', userAuth()->name) . ".pdf";
        Storage::disk('private')->put($fileName, file_get_contents($file));

        $result = VacancyReport::create([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => $reportFile->name,
            'file' => $fileName,
            'status' => 'pending',
        ]);

        VacancyLogs::create([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => $reportFile->name,
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

    public function vacancyReportUpdate(Request $request, $id, $reportId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $vacancyReport = VacancyReport::with('vacancyUser')->findOrFail($reportId);

        if ($vacancyReport->vacancyUser->vacancy_id != $id) {
            return redirect()->back()->with(['messege' => __('Pendidikan Lanjutan tidak sama dengan yang dipilih'), 'alert-type' => 'error']);
        }

        if ($vacancyReport->vacancyUser->user_id != userAuth()->id) {
            return redirect()->back()->with(['messege' => __('Anda tidak terdaftar sebagai peserta'), 'alert-type' => 'error']);
        }
        if ($vacancyReport->status == 'accepted') {
            return redirect()->back()->with(['messege' => __('Laporan yang telah disetujui tidak dapat diubah'), 'alert-type' => 'error']);
        }

        Storage::disk('private')->delete($vacancyReport->file);

        $fileName = "pendidikan_lanjutan/" . now()->year . "/laporan_semester" . "/" . $id . "/" . now()->month . "_" . str_replace([' ', '/'], '_', $vacancyReport->name) . "_" . str_replace(' ', '_', userAuth()->name) . ".pdf";
        Storage::disk('private')->put($fileName, file_get_contents($request->file('file')));
        $request->merge([
            'file' => $fileName,
            'name' => $vacancyReport->name,
        ]);
        $result = $vacancyReport->update([
            'file' => $fileName,
            'status' => 'pending',
        ]);
        if (!$result) {
            return redirect()->back()->with(['messege' => __('Update vacancy report failed'), 'alert-type' => 'error']);
        }

        VacancyLogs::create([
            'vacancy_user_id' => $vacancyReport->vacancy_user_id,
            'name' => $vacancyReport->name,
            'description' => 'Laporan telah diperbarui oleh ' . userAuth()->name,
            'status' => 'success',
        ]);

        return redirect()->back()->with(['messege' => __('Update vacancy report successfully'), 'alert-type' => 'success']);
    }

    public function vacancyReportDelete($id, $reportId){
        $vacancyReport = VacancyReport::with('vacancyUser')->findOrFail($reportId);

        if ($vacancyReport->vacancyUser->vacancy_id != $id) {
            return redirect()->back()->with(['messege' => __('Pendidikan Lanjutan tidak sama dengan yang dipilih'), 'alert-type' => 'error']);
        }

        if ($vacancyReport->vacancyUser->user_id != userAuth()->id) {
            return redirect()->back()->with(['messege' => __('Pengguna tidak terdaftar pada pendidikan lanjutan yang dipilih'), 'alert-type' => 'error']);
        }
        if ($vacancyReport->status == 'accepted') {
            return redirect()->back()->with(['messege' => __('Laporan yang telah disetujui tidak dapat dihapus'), 'alert-type' => 'error']);
        }
        Storage::disk('private')->delete($vacancyReport->file);

        $result = $vacancyReport->delete();

        if (!$result) {
            return redirect()->back()->with(['messege' => __('Delete vacancy report failed'), 'alert-type' => 'error']);
        }

        VacancyLogs::create([
            'vacancy_user_id' => $vacancyReport->vacancy_user_id,
            'name' => $vacancyReport->name,
            'description' => 'Laporan telah dihapus oleh ' . userAuth()->name,
            'status' => 'success',
        ]);

        return redirect()->back()->with(['messege' => __('Delete vacancy report successfully'), 'alert-type' => 'success']);
    }

    public function vacancyReportView($id, $reportId)
    {
        $vacancyReport = VacancyReport::with('vacancyUser')->findOrFail($reportId);

        if ($vacancyReport->vacancyUser->vacancy_id != $id) {
            return redirect()->back()->with(['messege' => __('Pendidikan Lanjutan tidak sama dengan yang dipilih'), 'alert-type' => 'error']);
        }

        if ($vacancyReport->vacancyUser->user_id != userAuth()->id) {
            return redirect()->back()->with(['messege' => __('Pengguna tidak terdaftar pada pendidikan lanjutan yang dipilih'), 'alert-type' => 'error']);
        }

        $filePath = Storage::disk('private')->path($vacancyReport->file);
        return response()->file($filePath);
    }
}
