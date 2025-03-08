<?php

namespace Modules\Pegawai\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        checkAdminHasPermissionAndThrowException('pegawai.view');

        // code ..

        return view('pegawai::Pegawai.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        checkAdminHasPermissionAndThrowException('pegawai.create');

        // code ..

        return view('pegawai::Pegawai.create');
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
        return view('pegawai::Pegawai.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pegawai::Pegawai.edit');
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

    public function indexPendidikanLanjutan(Request $request)
    {
        checkAdminHasPermissionAndThrowException('pegawai.pendidikanlanjutan');
        $search = $request->get('keyword') ?? null;
        $year = $request->get('year') ?? null;
        $order = $request->get('order') ?? null;
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])
            ->when($search, function ($q, $search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->when($year, function ($q, $year) {
                $q->whereYear('created_at', $year);
            })
            ->whereIn('status', [VacancyUser::STATUS_ASSESSMENT, VacancyUser::STATUS_ELIGIBLE, VacancyUser::STATUS_EXTEND, VacancyUser::STATUS_REPORT, VacancyUser::STATUS_REJECTED])
            ->orderBy('created_at', $order == 1 ? 'asc' : 'desc')
            ->paginate(10);
        $submenu = 'Daftar Pegawai Pendidikan Lanjutan';

        return view('pegawai::PendidikanLanjutan.index', compact('vacancyUsers', 'submenu'));
    }
}
