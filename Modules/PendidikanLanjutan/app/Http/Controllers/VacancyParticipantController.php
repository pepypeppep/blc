<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VacancyReport;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyLogs;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class VacancyParticipantController extends Controller
{
    public function updateStatus(Request $request, $vacancyUserId)
    {
        $request->validate([
            'status' => 'required|in:draft_verification,draft_assessment,rejected,assessment,eligible,ineligible,report,extend,done',
            'description' => 'nullable'
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            // Get vacancy user data
            $vacancyUser = VacancyUser::findOrFail($vacancyUserId);

            if ($request->status == 'draft_verification') {
                $log = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)
                    ->where('status', 'verification')->latest()->first();

                if ($log) {
                    $log->update([
                        'draft_notes' => $request->description
                    ]);

                    // Commit transaction
                    DB::commit();

                    return redirect()->route('admin.vacancies.verification.show', $vacancyUser->id . '#verif')->with('success', 'Draft saved successfully.');
                }
            }

            if ($request->status == 'draft_assessment') {
                $log = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)
                    ->where('status', 'assessment')->latest()->first();

                if ($log) {
                    $log->update([
                        'draft_notes' => $request->description
                    ]);

                    // Commit transaction
                    DB::commit();

                    return redirect()->route('admin.vacancies.assessment.show', $vacancyUser->id . '#assessment')->with('success', 'Draft saved successfully.');
                }
            }

            // if ($request->status == 'draft_extend') {
            //     $log = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)
            //         ->where('status', 'extend')->latest()->first();

            //     if ($log) {
            //         $log->update([
            //             'draft_notes' => $request->description
            //         ]);

            //         // Commit transaction
            //         DB::commit();

            //         return redirect()->route('admin.vacancies.assessment.show', $vacancyUser->id . '#assessment')->with('success', 'Draft saved successfully.');
            //     }
            // }

            // Update Vacancy Status
            $vacancyUser->update([
                'status' => $request->status
            ]);

            $attachment = null;
            $redirectTo = 'admin.vacancies.verification.index';
            if ($request->status === 'rejected' || $request->status === 'assessment') {
                $name = "Verifikasi Berkas";
            } elseif ($request->status === 'eligible' || $request->status === 'ineligible') {
                $name = "Asessment";
                $redirectTo = 'admin.vacancies.assessment.index';
            } elseif ($request->status === 'report') {
                $name = "Laporan";
                $request->merge([
                    'description' => 'Lampiran telah diunggah'
                ]);
                $redirectTo = 'admin.vacancies.report.index';
            } elseif ($request->status === 'extend') {
                $name = "Perpanjang Waktu";

                // Upload file
                $file = $request->file('file');
                $fileName = "extend/" . now()->year . "/" . now()->month . "/" . $vacancyUser->id . "/berkas_perpanjang_" . $vacancyUser->vacancy->id . "_" . $vacancyUser->user->name . ".pdf";
                Storage::disk('private')->put($fileName, file_get_contents($file));
                $attachment = $fileName;
                $redirectTo = 'admin.vacancies.extend.index';
            } elseif ($request->status === 'done') {
                $name = "Selesai";
                $redirectTo = 'admin.vacancies.done.index';
            }

            // Update Vacancy Log
            $request->merge([
                'vacancy_user_id' => $vacancyUser->id,
                'name' => $name,
                'description' => $request->description,
                'attachment' => $attachment
            ]);
            vacancyLog($request);

            // Commit transaction
            DB::commit();

            return redirect()->route($redirectTo)->with('success', 'Vacancy status updated successfully.');
        } catch (\Throwable $th) {
            // Rollback transaction
            DB::rollBack();

            return redirect()->route('admin.vacancies.verification.index')->with('error', $th->getMessage());
        }
    }

    public function uploadFile(Request $request, $vacancyId, $vacancyUserId)
    {
        $request->validate([
            'file' => 'required|mimes:pdf',
            'title' => 'required|string'
        ]);

        try {
            // Start transaction
            DB::beginTransaction();

            // Get vacancy data
            $vacancy = Vacancy::findOrFail($vacancyId);

            // Get vacancy user data
            $vacancyUser = VacancyUser::findOrFail($vacancyUserId);

            $title = trim(str_replace("Unggah", "", $request->title));
            // Get vacancy attachment data
            $vacancyAttachment = VacancyAttachment::where('vacancy_id', $vacancyId)->where('category', 'lampiran')->where('name', $title)->where('is_active', true)->firstOrFail();

            $type = str_replace(" ", "_", strtolower($title));
            // Upload file
            $file = $request->file('file');

            $check = VacancyUserAttachment::where('vacancy_user_id', $vacancyUser->id)
                ->where('vacancy_attachment_id', $vacancyAttachment->id)
                ->where('category', $vacancyAttachment->category)
                ->first();

            if ($check) {
                if ($check->status == 'assign') {
                    $assignFile = str_replace(["draft_", "final_"], "assign_", $check->file);
                    if (Storage::disk('private')->exists($assignFile)) {
                        Storage::disk('private')->delete($assignFile);
                    }
                    $fileName = "pendidikan_lanjutan/" . now()->year . "/lampiran/" . $vacancy->id . "/" . $type . "/" . now()->month . "_final_" . $type . "_" . $vacancyUser->user->name . ".pdf";
                    $check->update([
                        'file' => $fileName,
                        'status' => 'final'
                    ]);
                }
            } else {
                $fileName = "pendidikan_lanjutan/" . now()->year . "/lampiran/" . $vacancy->id . "/" . $type . "/" . now()->month . "_draft_" . $type . "_" . $vacancyUser->user->name . ".pdf";

                if ($title == "Perjanjian Kinerja") {
                    $status = 'final';
                } else {
                    $status = 'draft';
                }

                // Create Vacancy User Attachment
                $vacancyUserAttachment = VacancyUserAttachment::create([
                    'vacancy_user_id' => $vacancyUser->id,
                    'vacancy_attachment_id' => $vacancyAttachment->id,
                    'file' => $fileName,
                    'category' => $vacancyAttachment->category,
                    'status' => $status
                ]);
            }

            Storage::disk('private')->put($fileName, file_get_contents($file));

            // Commit transaction
            DB::commit();

            // Redirect with success message
            return redirect()->back()->with('success', 'File uploaded successfully.')->withFragment('sk');
        } catch (\Throwable $th) {
            // Rollback transaction
            DB::rollBack();

            // Redirect with error message
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function updateReportStatus(Request $request, $vacancyReportId)
    {
        $request->validate([
            'status' => 'required|in:rejected,accepted',
            'description' => 'nullable'
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            // Get vacancy report data
            $vacancyReport = VacancyReport::findOrFail($vacancyReportId);

            // Update Vacancy Report Status
            $vacancyReport->update([
                'status' => $request->status,
                'note' => $request->description
            ]);

            // Update Vacancy Report Log
            $request->merge([
                'vacancy_user_id' => $vacancyReport->vacancy_user_id,
                'name' => "Verifikasi Laporan",
                'status' => $request->status,
                'description' => $request->description
            ]);
            vacancyLog($request);

            // Commit transaction
            DB::commit();

            return redirect()->back()->with('success', 'Vacancy report status updated successfully.');
        } catch (\Throwable $th) {
            // Rollback transaction
            DB::rollBack();
            dd($th->getMessage());

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getFile($vacancyAttachmentId, $userId)
    {
        $VacancyUserAttachment = VacancyUserAttachment::where('vacancy_user_id', $userId)
            ->where('vacancy_attachment_id', $vacancyAttachmentId)->first();

        // return Storage::disk('private')->response($VacancyUserAttachment->file);
        return response()->download(Storage::disk('private')->path($VacancyUserAttachment->file));
        // return response()->streamDownload(Storage::disk('private')->path($VacancyUserAttachment->file), $VacancyUserAttachment->file);
    }

    public function getDraftFile($vacancyAttachmentId, $userId)
    {
        $VacancyUserAttachment = VacancyUserAttachment::where('vacancy_user_id', $userId)
            ->where('vacancy_attachment_id', $vacancyAttachmentId)->first();

        // return Storage::disk('private')->response(str_replace(['assign_', 'final_'], 'draft_', $VacancyUserAttachment->file));
        return response()->download(Storage::disk('private')->path(str_replace(['assign_', 'final_'], 'draft_', $VacancyUserAttachment->file)));
        // return response()->streamDownload(Storage::disk('private')->path(str_replace(['assign_', 'final_'], 'draft_', $VacancyUserAttachment->file)), str_replace(['assign_', 'final_'], 'draft_', $VacancyUserAttachment->file));
    }

    public function getReportFile($reportId)
    {
        $vacancyReport = VacancyReport::findOrFail($reportId);

        // return Storage::disk('private')->response($vacancyReport->file);
        return response()->download(Storage::disk('private')->path($vacancyReport->file));
        // return response()->streamDownload(Storage::disk('private')->path($vacancyReport->file), $vacancyReport->file);
    }
}
