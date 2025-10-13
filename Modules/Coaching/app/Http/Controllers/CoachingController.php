<?php

namespace Modules\Coaching\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingAssessment;
use Modules\Coaching\app\Models\CoachingSession;
use Modules\Coaching\app\Models\CoachingSessionDetail;
use Modules\Coaching\app\Models\CoachingUser;
use app\Models\User;
use Illuminate\Support\Facades\Storage;
use Modules\CertificateBuilder\app\Http\Requests\CertificateUpdateRequest;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\CertificateBuilder\app\Models\CertificateBuilderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use iio\libmergepdf\Merger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoachingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $coachs = Coaching::with('coachees', 'coach')
            ->withCount('joinedCoachees')
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            // ->where('status', '!=', 'draft')
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        $statusCounts = Coaching::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $totalCoachs = Coaching::count();

        return view('coaching::index', compact('coachs', 'statusCounts', 'totalCoachs', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coaching::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function assessment(Request $request, $coachingId, $coacheeId )
    // {
    //     $user = auth()->user();
    //     $data = CoachingUser::with(['coachee', 'assessment', 'coaching'])
    //             ->where('coaching_id', $coachingId)
    //             ->where('user_id', $coacheeId)
    //             ->forCoach($user->id, $coachingId)
    //             ->first();

    //     if (!$data) {
    //         abort(403, 'Anda tidak memiliki izin untuk mengakses penilaian ini.');
    //     }

    //     authorizeCoachAccess($data->coaching);

    //     return view('frontend.student-dashboard.coaching.coach.assessment', compact('data'));
    // }

    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $coaching = Coaching::with([
            'coachees:id,name',
            'joinedCoachees:id,name',
            'coachingSessions.details.coachingUser.coachee'
        ])->findOrFail($id);

        authorizeCoachAccess($coaching);
        $certificates = CertificateBuilder::paginate();


        $templatesList = Storage::disk('templates')->files();

        $templates = [];
        foreach ($templatesList as $template) {
            if (!str_ends_with($template, '.html')) {
                continue;
            }
            $templates[] = $template;
        }

        return view('coaching::show', compact('coaching', 'certificates', 'templates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('coaching::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
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

    public function showDocumentSpt($id)
    {
        $coaching = Coaching::findOrFail($id);
        $path = $coaching->spt;

        return getPrivateFile($path);
    }

    public function showReport($id)
    {
        $coachee = CoachingUser::findOrFail($id);
        $path = $coachee->final_report;

        return getPrivateFile($path);
    }

    public function viewImage($detailId)
    {
        $session = CoachingSessionDetail::findOrFail($detailId);

        return getPrivateFile($session->image);
    }

    public function reviewStore(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:coaching_sessions,id',
            'coaching_user_id' => 'required|exists:coaching_users,id',
            'review_note' => 'required|string',
            'review_instruction' => 'nullable|string',
        ]);

        $coachingUser = CoachingUser::with('coaching')
            ->findOrFail($validated['coaching_user_id']);

        if (!$coachingUser) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses ini.');
        }

        authorizeCoachAccess($coachingUser->coaching);

        $detail = CoachingSessionDetail::where([
            'coaching_session_id' => $validated['session_id'],
            'coaching_user_id' => $validated['coaching_user_id'],
        ])->first();

        if (!$detail || $detail->activity === null) {
            return redirect()->back()->with([
                'messege' => 'Gagal menyimpan review. Laporan dari coachee belum tersedia.',
                'alert-type' => 'error',
            ]);
        }

        $detail->coaching_note = $validated['review_note'];
        $detail->coaching_instructions = $validated['review_instruction'];
        $detail->save();

        return redirect()->back()->with(['messege' => 'Review berhasil disimpan', 'alert-type' => 'success']);
    }
}
