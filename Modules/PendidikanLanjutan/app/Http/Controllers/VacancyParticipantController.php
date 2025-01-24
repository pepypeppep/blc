<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyDetailUserAttachment;

class VacancyParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $vacancies = Vacancy::paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Vacancies successfully rendered.',
            'data' => $vacancies,
        ], 200);

        // return view('pendidikanlanjutan::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('pendidikanlanjutan::create');
    }

    public function register(Request $request, $vacancyId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $vacancy = Vacancy::findOrFail($vacancyId);
        $user = User::findOrFail($validated['user_id']);

        $vacancy->users()->attach($user->id, [
            'status' => 'registered',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Participant successfully registered to the vacancy.',
            'data' => $user,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $vacancy = Vacancy::with('details', 'unors')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Vacancy details successfully rendered.',
            'data' => $vacancy,
        ], 200);

        // return view('pendidikanlanjutan::show');
    }

    public function uploadFile(Request $request, $vacancyDetailId, $vacancyUserId){
        
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

        $fileName = time().'_'.$file->getClientOriginalName();

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pendidikanlanjutan::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
