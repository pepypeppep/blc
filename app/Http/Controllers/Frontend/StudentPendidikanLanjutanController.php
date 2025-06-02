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
use App\Http\Requests\Frontend\StudentActivationRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyLogs;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancySchedule;
use App\Http\Requests\Frontend\StudentVacancyReportRequest;
use App\Http\Requests\Frontend\UploadRequirementFileRequest;
use Modules\PendidikanLanjutan\app\Models\VacancyActivation;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyDetail;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterReportFiles;
use Modules\PendidikanLanjutan\app\Models\VacancyUserDirect;

class StudentPendidikanLanjutanController extends Controller
{
    // list pendidikan
    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 10);
        $schedule = VacancySchedule::where('year', now()->year)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();

        $vacancyDirects = VacancyUserDirect::where('user_id', userAuth()->id)->get();
        $vacancies = [];

        if ($vacancyDirects->count() > 0) {
            $vacancyDirectIds = $vacancyDirects->pluck('vacancy_id')->toArray();
            $vacancies = Vacancy::whereIn('id', $vacancyDirectIds)->where('instansi_id', userAuth()->instansi_id)->where('year', $schedule->year ?? -1)->paginate($perPage);
        } else {
            $vacancies = Vacancy::where('instansi_id', userAuth()->instansi_id)->where('year', $schedule->year ?? -1)->paginate($perPage);
            $vacancyDirects = collect();
        }

        return view('frontend.student-dashboard.continuing-education.index', compact('vacancies', 'schedule'));
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
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::with(['vacancy.study', 'user.unor', 'user.instansi'])->findOrFail($id);

        if ($vacancyUser->vacancy->isEligible($user)) {
            $vacancyUser->update([
                'status' => VacancyUser::STATUS_REGISTER,
            ]);
            return redirect()->back()->with(['messege' => $vacancyUser->vacancy->isEligible($user), 'alert-type' => 'error']);
        }

        $logs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->orderBy('created_at', 'desc')->get();
        $attachments = VacancyUserAttachment::whereHas('vacancyattachment', function ($query) use ($vacancyUser) {
            $query->where('vacancy_id', $vacancyUser->vacancy_id);
        })->where('vacancy_user_id', $vacancyUser->id)->get();
        $lampirans = VacancyAttachment::lampiran()->where('vacancy_id', $vacancyUser->vacancy_id)->get();
        $reports = VacancyReport::where('vacancy_user_id', $vacancyUser->id)->orderBy('name')->get();
        $reportsFiles = VacancyMasterReportFiles::where('is_active', 1)->get();
        $activations = VacancyAttachment::aktivasi()->where('vacancy_id', $vacancyUser->vacancy_id)->get();
        $userActivation = VacancyActivation::where('vacancy_user_id', $vacancyUser->id)->get();
        return view('frontend.student-dashboard.continuing-education.registration.show', compact('vacancyUser', 'logs', 'attachments', 'lampirans', 'reports', 'reportsFiles', 'activations', 'userActivation'));
    }

    // detail pendidikan
    function continuingEducationDetail($id)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $schedule = VacancySchedule::where('year', now()->year)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();
        $vacancy = Vacancy::with(['study', 'users' => function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereNotIn('status', [VacancyUser::STATUS_REGISTER]); // next update with value_type, unor, dll
        }])->where('year', $schedule->year ?? -1)->findOrFail($id);

        $isEligible = $vacancy->isEligible($user);
        if ($isEligible) {
            return redirect()->back()->with(['messege' => $isEligible, 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $id)->first();
        $vacancyDetail = VacancyDetail::where('vacancy_id', $id)->get();

        $bup = $user->bup ?? 0;
        $passJenjangPendidikanTerakhir = educationFilter($vacancy->education_level, $user->tingkat_pendidikan);

        $base = VacancyAttachment::syarat()->where('vacancy_id', $id)->where('is_active', 1);
        $vacancyConditions = $base->with('attachment')->get();
        $vacancyTakeConditions = $base->whereHas('attachment', function ($query) use ($vacancy) {
            if ($vacancy->users->first()) {
                $query->where('vacancy_user_id', $vacancy->users->first()->id);
            }
        })->get();

        $meetCondition = (count($vacancyTakeConditions) == count($vacancyConditions));


        return view('frontend.student-dashboard.continuing-education.show', compact('vacancy', 'vacancyUser', 'vacancyDetail', 'bup', 'vacancyConditions', 'meetCondition', 'passJenjangPendidikanTerakhir'));
    }


    public function uploadRequirementFile(UploadRequirementFileRequest $request, $id)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $request->validated();

        $attachment = VacancyAttachment::findOrFail($id);

        if (!$attachment) {
            return redirect()->back()->withFragment('attachment_container')->with(['messege' => __('Attachment not found'), 'alert-type' => 'error']);
        }

        $vacancy = Vacancy::findOrFail($attachment->vacancy_id);

        if (!$vacancy) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $isEligible = $vacancy->isEligible(userAuth());
        if ($isEligible) {
            return redirect()->back()->with(['messege' => $isEligible, 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $vacancy->id)->first();
        if (!$vacancyUser) {
            return redirect()->back()->with(['messege' => 'Anda belum terdaftar', 'alert-type' => 'error']);
        }

        if (!$vacancyUser->vacancy_detail_id || $vacancyUser->vacancy_detail_id == 0) {
            return redirect()->back()->with(['messege' => 'Anda belum memiliki skema program pendidikan lanjutan', 'alert-type' => 'error']);
        }

        $vacancyUserAttachment = VacancyUserAttachment::with('vacancyattachment')->where('vacancy_attachment_id', $attachment->id)->where('vacancy_user_id', $vacancyUser->id)->first();
        $file = $request->file('file');

        $validator = Validator::make(['file' => $file], [
            'file' => 'required|file|mimes:' . $attachment->type . '|max:' . $attachment->max_size . '',
        ]);

        if ($validator->fails()) {
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
            return redirect()->back()->withFragment('attachment_container')->with(['messege' => __('Upload file requirement failed'), 'alert-type' => 'error']);
        }

        return redirect()->back()->withFragment('attachment_container')->with(['messege' => __('Upload file requirement successfully'), 'alert-type' => 'success']);
    }

    public function viewRequirementFile($id, $user_id)
    {
        $VacancyUserAttachment = VacancyUserAttachment::where('vacancy_user_id', $user_id)
            ->where('vacancy_attachment_id', $id)->first();


        if (!$VacancyUserAttachment) {
            return abort(404);
        }

        return response()->file(storage_path('app/private/' . $VacancyUserAttachment->file));
    }

    public function ajukanDaftar(Request $request, $vacancyId)
    {
        $request->validate([
            'schema_id' => 'required',
        ], [
            'schema_id.required' => 'Silahkan pilih skema program pendidikan lanjutan',
        ]);

        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancyDetail = VacancyDetail::where('id', $request->schema_id)->where('vacancy_id', $vacancyId)->first();

        if (!$vacancyDetail) {
            return redirect()->back()->with(['messege' => 'Skema program pendidikan lanjutan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $vacancyId)->first();

        if ($vacancyUser) {
            return redirect()->back()->with(['messege' => 'Anda sudah terdaftar', 'alert-type' => 'error']);
        }

        $vacancy = Vacancy::findOrFail($vacancyId);

        if (!$vacancy) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $isEligible = $vacancy->isEligible($user);
        if ($isEligible) {
            return redirect()->back()->with(['messege' => $isEligible, 'alert-type' => 'error']);
        }

        $schedule = VacancySchedule::where('year', now()->year)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();

        $vacancyDirects = VacancyUserDirect::where('user_id', userAuth()->id)->get();

        if ($vacancyDirects->count() > 0) {
            $vacancyDirectIds = $vacancyDirects->pluck('vacancy_id')->toArray();
            $vacancies = Vacancy::whereIn('id', $vacancyDirectIds)->where('instansi_id', userAuth()->instansi_id)->where('year', $schedule->year ?? -1)->get();

            if ($vacancies->count() > 0 && !$vacancies->contains($vacancy)) {
                return redirect()->back()->with(['messege' => 'Anda hanya dapat mendaftar program pendidikan lanjutan yang sudah diundang / diinvite', 'alert-type' => 'error']);
            }
        }



        $result = VacancyUser::firstOrCreate([
            'user_id' => $user->id,
            'vacancy_id' => $vacancy->id,
            'vacancy_detail_id' => $request->schema_id,
            'status' => VacancyUser::STATUS_REGISTER
        ]);

        if (!$result) {
            return redirect()->back()->with(['messege' => 'Pendaftaran gagal', 'alert-type' => 'error']);
        }

        return redirect()->route('student.continuing-education.show', $vacancy->id)->with(['messege' => 'Pendaftaran berhasil', 'alert-type' => 'success']);
    }

    // pengajuan pendaftaran
    public function ajukanBerkas(Request $request, $vacancyId)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancy = Vacancy::findOrFail($vacancyId);

        if (!$vacancy) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $isEligible = $vacancy->isEligible($user);
        if ($isEligible) {
            return redirect()->back()->with(['messege' => $isEligible, 'alert-type' => 'error']);
        }

        $vacancyAttachments = VacancyAttachment::syarat()->where('vacancy_id', $vacancy->id)->where('is_active', 1)->get();

        if (!$vacancyAttachments) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $vacancy->id)->first();
        if (!$vacancyUser) {
            return redirect()->back()->with(['messege' => 'Anda belum terdaftar', 'alert-type' => 'error']);
        }

        if (!$vacancyUser->vacancy_detail_id || $vacancyUser->vacancy_detail_id == 0) {
            return redirect()->back()->with(['messege' => 'Anda belum memiliki skema program pendidikan lanjutan', 'alert-type' => 'error']);
        }

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

        $instansi = Instansi::findOrFail($user->instansi_id);

        $vacancyUser->update([
            'employment_grade' => $user->golongan,
            'last_position' => $user->jabatan,
            'instansi' => $instansi->name,
            'cost_type' => $vacancy->cost_type,
            'education_level' => $user->tingkat_pendidikan,
            'last_education' => $user->pendidikan,
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
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $id)->first();

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
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $vacancyReport = VacancyReport::with('vacancyUser')->findOrFail($reportId);

        if ($vacancyReport->vacancyUser->vacancy_id != $id) {
            return redirect()->back()->with(['messege' => __('Pendidikan Lanjutan tidak sama dengan yang dipilih'), 'alert-type' => 'error']);
        }

        if ($vacancyReport->vacancyUser->user_id != $user->id) {
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

    public function vacancyReportDelete($id, $reportId)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancyReport = VacancyReport::with('vacancyUser')->findOrFail($reportId);

        if ($vacancyReport->vacancyUser->vacancy_id != $id) {
            return redirect()->back()->with(['messege' => __('Pendidikan Lanjutan tidak sama dengan yang dipilih'), 'alert-type' => 'error']);
        }

        if ($vacancyReport->vacancyUser->user_id != $user->id) {
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
            'description' => 'Laporan telah dihapus oleh ' . $user->name,
            'status' => 'success',
        ]);

        return redirect()->back()->with(['messege' => __('Delete vacancy report successfully'), 'alert-type' => 'success']);
    }

    public function vacancyReportView($id, $reportId)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancyReport = VacancyReport::with('vacancyUser')->findOrFail($reportId);

        if ($vacancyReport->vacancyUser->vacancy_id != $id) {
            return redirect()->back()->with(['messege' => __('Pendidikan Lanjutan tidak sama dengan yang dipilih'), 'alert-type' => 'error']);
        }

        if ($vacancyReport->vacancyUser->user_id != $user->id) {
            return redirect()->back()->with(['messege' => __('Pengguna tidak terdaftar pada pendidikan lanjutan yang dipilih'), 'alert-type' => 'error']);
        }

        $filePath = Storage::disk('private')->path($vacancyReport->file);
        return response()->file($filePath);
    }

    public function uploadRequirementActivation(StudentActivationRequest $request, $vacancyAttachmentId)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancyAttachment = VacancyAttachment::findOrFail($vacancyAttachmentId);

        if ($vacancyAttachment->category != 'aktivasi') {
            return redirect()->back()->with(['messege' => 'Pendidikan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $vacancyAttachment->vacancy_id)->with('vacancy')->first();

        if (!$vacancyUser) {
            return redirect()->back()->with(['messege' => 'Pendidikan tidak ditemukan', 'alert-type' => 'error']);
        }

        if ($vacancyUser->status == VacancyUser::STATUS_REGISTER) {
            return redirect()->back()->with(['messege' => 'Anda belum terdaftar', 'alert-type' => 'error']);
        }

        if ($vacancyUser->status == VacancyUser::STATUS_VERIFICATION) {
            return redirect()->back()->with(['messege' => 'Anda belum diverifikasi', 'alert-type' => 'error']);
        }

        $vacancyActivation = VacancyActivation::where('vacancy_user_id', $vacancyUser->id)->where('vacancy_attachment_id', $vacancyAttachment->id)->first();

        $file = $request->file('file');
        $fileName = "pendidikan_lanjutan/" . now()->year . "/aktivasi" . "/" . $vacancyUser->vacancy_id . "/" . now()->month . "_" . str_replace([' ', '/'], '_', $vacancyAttachment->name) . "_" . str_replace(' ', '_', userAuth()->name) . ".pdf";


        if ($vacancyActivation) {
            Storage::disk('private')->delete($vacancyActivation->file);
            Storage::disk('private')->put($fileName, file_get_contents($file));
            $result = $vacancyActivation->update([
                'file' => $fileName,
                'status' => 'pending',
            ]);
            if (!$result) {
                return redirect()->back()->with(['messege' => 'Upload file gagal', 'alert-type' => 'error']);
            }

            VacancyLogs::create([
                'vacancy_user_id' => $vacancyUser->id,
                'name' => 'Update file aktivasi',
                'description' => $vacancyAttachment->name . ' telah diupdate',
                'status' => 'success',
            ]);

            return redirect()->back()->with(['messege' => 'File berhasil diupdate', 'alert-type' => 'success']);
        }

        Storage::disk('private')->put($fileName, file_get_contents($file));
        $result = VacancyActivation::create([
            'vacancy_user_id' => $vacancyUser->id,
            'vacancy_attachment_id' => $vacancyAttachment->id,
            'name' => $fileName,
            'file' => $fileName,
            'status' => 'pending',
        ]);
        if (!$result) {
            return redirect()->back()->with(['messege' => 'Upload file gagal', 'alert-type' => 'error']);
        }
        VacancyLogs::create([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => 'Upload file aktivasi',
            'description' => $vacancyAttachment->name . ' telah diupload',
            'status' => 'success',
        ]);
        return redirect()->back()->with(['messege' => 'Upload file berhasil', 'alert-type' => 'success']);
    }

    public function deleteRequirementActivation($vacancyAttachmentId, $userActivationId)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancyAttachment = VacancyAttachment::findOrFail($vacancyAttachmentId);
        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $vacancyAttachment->vacancy_id)->with('vacancy')->first();
        $vacancyActivation = VacancyActivation::findOrFail($userActivationId);

        if ($vacancyActivation->vacancy_user_id != $vacancyUser->id) {
            return redirect()->back()->with(['messege' => 'File tidak ditemukan', 'alert-type' => 'error']);
        }
        if ($vacancyActivation->vacancy_attachment_id != $vacancyAttachment->id) {
            return redirect()->back()->with(['messege' => 'File tidak ditemukan', 'alert-type' => 'error']);
        }

        Storage::disk('private')->delete($vacancyActivation->file);
        $vacancyActivation->delete();
        return redirect()->back()->with(['messege' => 'File berhasil dihapus', 'alert-type' => 'success']);
    }

    public function viewRequirementActivation($vacancyAttachmentId, $userActivationId)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancyAttachment = VacancyAttachment::findOrFail($vacancyAttachmentId);
        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $vacancyAttachment->vacancy_id)->with('vacancy')->first();
        $vacancyActivation = VacancyActivation::findOrFail($userActivationId);

        if ($vacancyActivation->vacancy_user_id != $vacancyUser->id) {
            return redirect()->back()->with(['messege' => 'File tidak ditemukan', 'alert-type' => 'error']);
        }
        if ($vacancyActivation->vacancy_attachment_id != $vacancyAttachment->id) {
            return redirect()->back()->with(['messege' => 'File tidak ditemukan', 'alert-type' => 'error']);
        }

        $filePath = Storage::disk('private')->path($vacancyActivation->file);
        return response()->file($filePath);
    }

    // pengajuan pendaftaran
    public function ajukanKembali(Request $request, $vacancyId)
    {
        $user = userAuth();
        if (!$user->canAccessContinuingEducation()) {
            return redirect()->back()->with(['messege' => __('Anda tidak memiliki akses ke program pendidikan lanjutan'), 'alert-type' => 'error']);
        }

        $vacancy = Vacancy::findOrFail($vacancyId);

        if (!$vacancy) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyAttachments = VacancyAttachment::syarat()->where('vacancy_id', $vacancy->id)->where('is_active', 1)->get();

        if (!$vacancyAttachments) {
            return redirect()->back()->with(['messege' => 'Lowongan tidak ditemukan', 'alert-type' => 'error']);
        }

        $vacancyUser = VacancyUser::where('user_id', $user->id)->where('vacancy_id', $vacancy->id)->first();

        $closedDate = $vacancy->close_at;

        if ($closedDate < now()) {
            return redirect()->back()->with(['messege' => 'Pendaftaran sudah ditutup', 'alert-type' => 'error']);
        }

        if ($vacancy->users()->where('user_id', $user->id)->whereNotIn('status', [VacancyUser::STATUS_REJECTED])->exists()) {
            return redirect()->back()->with(['messege' => 'Anda sudah terdaftar', 'alert-type' => 'error']);
        }

        DB::beginTransaction();

        $vacancyUser->update([
            'status' => VacancyUser::STATUS_VERIFICATION,
        ]);

        $request->merge([
            'vacancy_user_id' => $vacancyUser->id,
            'name' => 'Pengajuan kembali',
            'status' => $vacancyUser->status,
            'description' => 'Telah melakukan perbaikan syarat pendaftaran',
        ]);

        vacancyLog($request);

        if (!$vacancyUser) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => 'Pengajuan kembali gagal', 'alert-type' => 'error']);
        }

        DB::commit();
        return redirect('student/continuing-education-registration/' . $vacancyUser->id)->with(['message' => 'Pengajuan kembali berhasil', 'alert-type' => 'success']);
    }
}
