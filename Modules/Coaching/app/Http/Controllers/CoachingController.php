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
            'coachees:id,name', 'joinedCoachees:id,name', 'coachingSessions.details.coachingUser.coachee'
        ])->findOrFail($id);

        authorizeCoachAccess($coaching);
        $certificates = CertificateBuilder::paginate();

        return view('coaching::show', compact('coaching', 'certificates'));
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
}
