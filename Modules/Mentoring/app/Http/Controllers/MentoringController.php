<?php

namespace Modules\Mentoring\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringReview;
use Modules\Mentoring\app\Models\MentoringSession;
use Modules\CertificateBuilder\app\Http\Requests\CertificateUpdateRequest;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\CertificateBuilder\app\Models\CertificateBuilderItem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Modules\Mentoring\app\Models\MentoringSigner;

class MentoringController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $mentors = Mentoring::with('mentor')
            ->withCount('mentee')
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            // ->where('status', '!=', 'draft')
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        $statusCounts = Mentoring::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $totalMentors = Mentoring::count();

        return view('mentoring::index', compact('mentors', 'statusCounts', 'totalMentors', 'status'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mentoring::create');
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
        $mentoring = Mentoring::with('mentor', 'mentee', 'mentoringSessions')->find($id);

        if (!$mentoring) {
            return redirect()->back()->with([
                'message' => 'Mentoring not found',
                'alert-type' => 'error'
            ]);
        }

        $hasIncompleteSessions = $mentoring->mentoringSessions->contains(function ($session) {
            return empty($session->activity);
        });

        // Cek apakah file surat tersedia secara fisik
        $fileExists = false;
        if ($mentoring->mentor_availability_letter) {
            $filePath = 'public/' . $mentoring->mentor_availability_letter;
            $fileExists = Storage::exists($filePath);
        }

        $certificates = CertificateBuilder::paginate();

        return view('mentoring::show', compact('mentoring', 'hasIncompleteSessions', 'certificates', 'fileExists'));
    }
    public function viewImage($id)
    {
        $session = MentoringSession::findOrFail($id);
        if (Storage::disk('private')->exists($session->image)) {
            return Storage::disk('private')->response($session->image);
        } else {
            abort(404);
        }
    }

    public function showDocument($id, $type)
    {
        $mentoring = Mentoring::findOrFail($id);
        return $mentoring->getDocumentResponse($type);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('mentoring::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Update certificate for mentoring
     */
    public function updateCertificate(Request $request, $id): JsonResponse
    {
        $request->validate([
            'certificate_id' => ['required', 'exists:certificate_builders,id'],
            'tte_depan' => ['nullable', 'exists:users,id'],
            'tte_belakang' => ['nullable', 'exists:users,id'],
        ]);

        $mentoring = Mentoring::findOrFail($id);
        $mentoring->certificate_id = $request->certificate_id;
        $mentoring->save();

        // Update TTE Depan (step 1)
        if ($request->has('tte_depan')) {
            MentoringSigner::updateOrCreate([
                'mentoring_id' => $id,
                'step' => 1,
            ], [
                'user_id' => $request->tte_depan,
                'type' => 'signature',
            ]);
        }

        // Update TTE Belakang (step 2)
        if ($request->has('tte_belakang')) {
            MentoringSigner::updateOrCreate([
                'mentoring_id' => $id,
                'step' => 2,
            ], [
                'user_id' => $request->tte_belakang,
                'type' => 'signature',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Certificate updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get users for TTE select2 dropdown
     */
    public function getUsers(Request $request): JsonResponse
    {
        $query = User::query()
            ->whereNotNull('nik')
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%');
            });

        $users = $query->select('id', 'name', 'email', 'jabatan')
            ->limit(20)
            ->get();

        return response()->json($users);
    }
}
