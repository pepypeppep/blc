<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UploadRequirementFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyDetailUserAttachment;
use Illuminate\Support\Facades\Validator;

class StudentPendidikanLanjutanController extends Controller
{
    // list pendidikan 
    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 10);
        $vacancies = Vacancy::published()->paginate($perPage);

        return view('frontend.student-dashboard.continuing-education.index', compact('vacancies'));
    }

    // list pendidikan yang sudah diambil
    function continuingEducation()
    {
        $vacancies = Vacancy::whereHas('users', function ($query) {
            $query->where('user_id', userAuth()->id); // next update with value_type, unor, dll
        })->with(['details', 'unors', 'users'])->paginate(10);

        return view('frontend.student-dashboard.continuing-education.index', compact('vacancies'));
    }

    // detail pendidikan
    function continuingEducationDetail($id) {
        $vacancy = Vacancy::with('details')->findOrFail($id);

        return view('frontend.student-dashboard.continuing-education.show', compact('vacancy'));
    }


    public function uploadRequirementFile(UploadRequirementFileRequest $request)
    {
        $validated = $request->validated();

        $file = $request->file('file');
        $file_name = file_upload($file, 'uploads/vacancy/attachments/');

        $result = VacancyDetailUserAttachment::create([
            'vacancy_detail_id' => $validated['vacancy_detail_id'],
            'vacancy_user_id' => $validated['vacancy_user_id'],
            'file_name' => $file_name,
        ]);

        if (!$result) {
            return redirect()->back()->with(['messege' => __('Upload file requirement failed'), 'alert-type' => 'error']);
        }


        return redirect()->back()->with(['messege' => __('Upload file requirement successfully'), 'alert-type' => 'success']);
    }

    // pengajuan pendaftaran
    public function register(Request $request, $vacancyId)
    {
        $vacancy = Vacancy::published()->findOrFail($vacancyId);

        $vacancy->users()->attach(userAuth()->id, [
            'status' => 'registered',
        ]);

        return redirect()->route('vacancies-participant.index')->with('success', 'Register successfully!');
    }

    public function laporSemester(){
        
    }


    public function uploadFile(Request $request, $vacancyDetailId, $vacancyUserId)
    {

        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'message' => 'No file uploaded.',
            ], 400);
        }

        $file = $request->file('file');

        $validator = Validator::make(['file' => $file], [
            'file' => 'required|file|mimes:pdf,docx,jpg,png|max:3000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid file upload.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();

        try {
            $filePath = $file->storeAs(
                'vacancy_attachment',
                $fileName,
                'public'
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload the file.',
                'error' => $e->getMessage(),
            ], 500);
        }

        try {
            $attachment = VacancyDetailUserAttachment::create([
                'vacancy_detail_id' => $vacancyDetailId,
                'vacancy_user_id' => $vacancyUserId,
                'file' => $filePath,
            ]);
        } catch (\Exception $e) {

            Storage::disk('public')->delete($filePath);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save attachment information.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'File uploaded successfully.',
            'file_path' => Storage::url($filePath),
        ], 200);
    }
}
