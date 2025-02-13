<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class VacancyParticipantController extends Controller
{
    public function updateStatus(Request $request, $vacancyUserId)
    {
        $request->validate([
            'status' => 'required|in:rejected,assesment,eligible,ineligible',
            'description' => 'nullable'
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            // Get vacancy user data
            $vacancyUser = VacancyUser::findOrFail($vacancyUserId);

            // Update Vacancy Status
            $vacancyUser->update([
                'status' => $request->status
            ]);

            if ($request->status === 'rejected' || $request->status === 'assesment') {
                $name = "Verifikasi Berkas";
            } elseif ($request->status === 'eligible' || $request->status === 'ineligible') {
                $name = "Asessment";
            }

            // Update Vacancy Log
            $request->merge([
                'vacancy_user_id' => $vacancyUser->id,
                'name' => $name,
                'description' => $request->description
            ]);
            vacancyLog($request);

            // Commit transaction
            DB::commit();

            return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy status updated successfully.');
        } catch (\Throwable $th) {
            // Rollback transaction
            DB::rollBack();

            return redirect()->route('admin.vacancies.index')->with('error', $th->getMessage());
        }
    }

    public function uploadFile(Request $request, $vacancyId, $userId)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Get vacancy data
            $vacancy = Vacancy::findOrFail($vacancyId);

            // Get vacancy user data
            $vacancyUser = $vacancy->users()->where('user_id', $userId)->firstOrFail();

            // Get vacancy attachment data
            $vacancyAttachment = $vacancy->vacancyAttachments()->where('name', 'SK')->where('is_active', true)->firstOrFail();

            // Upload file
            $file = $request->file('file');
            $fileName = "perjanjian_kerja/" . now()->year . "/" . now()->month . "/perjanjian_kerja_" . $vacancyId . "_" . $vacancyUser->name . ".pdf";
            Storage::disk('private')->put($fileName, $file);
            // Storage::disk('private')->putFileAs('vacancies', $file, $fileName);
            // Storage::disk('private')->put($fileName, file_get_contents($file));

            // Create Vacancy User Attachment
            $vacancyUserAttachment = VacancyUserAttachment::create([
                'vacancy_user_id' => $vacancyUser->id,
                'vacancy_attachment_id' => $vacancyAttachment->id,
                'file' => $fileName,
                'category' => $vacancyAttachment->category
            ]);

            // Commit transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('admin.vacancies.index')->with('success', 'File uploaded successfully.');
        } catch (\Throwable $th) {
            // Rollback transaction
            DB::rollBack();

            // Redirect with error message
            return redirect()->route('admin.vacancies.index')->with('error', $th->getMessage());
        }
    }

    public function getFile($vacancyAttachmentId, $userId)
    {
        $VacancyUserAttachment = VacancyUserAttachment::where('vacancy_user_id', $userId)
            ->where('vacancy_attachment_id', $vacancyAttachmentId)->first();

        return Storage::disk('private')->response($VacancyUserAttachment->file);
    }
}
