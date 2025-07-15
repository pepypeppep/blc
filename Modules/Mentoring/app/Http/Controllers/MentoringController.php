<?php

namespace Modules\Mentoring\app\Http\Controllers;

use App\Http\Controllers\Controller;
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
            // ->orderByDesc('updated_at')
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
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $mentoring = Mentoring::with('mentor','mentee','mentoringSessions')->find($id);

        if (!$mentoring) {
            return redirect()->back()->with([
                'message' => 'Mentoring not found',
                'alert-type' => 'error'
            ]);
        }

        $hasIncompleteSessions = $mentoring->mentoringSessions->contains(function ($session) {
            return empty($session->activity);
        });


        $certificates = CertificateBuilder::paginate();

        return view('mentoring::show', compact('mentoring', 'hasIncompleteSessions', 'certificates'));
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
