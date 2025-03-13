<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VacancyReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyLogs;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class PendidikanLanjutanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        checkAdminHasPermissionAndThrowException('pendidikanlanjutan.view');

        // code ..

        return view('pendidikanlanjutan::Vacancy.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        checkAdminHasPermissionAndThrowException('pendidikanlanjutan.create');

        // code ..

        return view('pendidikanlanjutan::Vacancy.create');
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
        return view('pendidikanlanjutan::Vacancy.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pendidikanlanjutan::Vacancy.edit');
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

    public function indexPeserta(Request $request)
    {
        checkAdminHasPermissionAndThrowException('pendidikanlanjutan.pendaftar');

        $query = VacancyUser::with(['vacancy', 'vacancy.study', 'user']);

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->has('order_by')) {
            $order = $request->input('order_by');
            $query->orderBy('name', $order == 1 ? 'asc' : 'desc');
        }

        $perPage = $request->has('par-page') ? $request->input('par-page') : 10;

        if (checkAdminHasPermission('pendidikanlanjutan.verifikasi')) {
            $vacancyUsers = $query->paginate($perPage);
        } else {
            $vacancyUsers = $query->whereHas('user', function ($query) {
                $query->where('instansi_id', adminAuth()->instansi_id);
            })->paginate($perPage);
        }

        $submenu = 'Daftar Pegawai';

        return view('pendidikanlanjutan::Peserta.index', compact('vacancyUsers', 'submenu'));
    }

    public function indexVerif()
    {
        checkAdminHasPermissionAndThrowException('pendidikanlanjutan.verifikasi');
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'verification')->paginate(10);
        $submenu = 'Verifikasi';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showVerif($id)
    {
        checkAdminHasPermissionAndThrowException('pendidikanlanjutan.verifikasi');
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'verification')
            ->findOrFail($id);

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment', 'vacancyuser')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->where('category', 'syarat')
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('vacancyUser', 'vacancyUserAttachments'));
    }

    public function indexAssesment()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'assessment')->paginate(10);
        $submenu = 'Assesment';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showAssesment($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'assessment')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->where('category', 'syarat')
            ->get();

        $logs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->get();
        $verifLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Verifikasi Berkas')->orderByDesc('created_at')->get();
        $assLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Asessment')->orderByDesc('created_at')->get();

        $sectionLog = (object) [
            'verifLogs' => $verifLogs,
            'assLogs' => $assLogs
        ];

        return view('pendidikanlanjutan::Submenu.show', compact('vacancyUser', 'logs', 'sectionLog', 'vacancyUserAttachments'));
    }

    public function indexSK()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'eligible')->paginate(10);
        $submenu = 'Surat Keputusan';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showSK($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'eligible')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->where('category', 'syarat')
            ->get();

        $logs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->get();
        $verifLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Verifikasi Berkas')->orderByDesc('created_at')->get();
        $assLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Asessment')->orderByDesc('created_at')->get();

        $sectionLog = (object) [
            'verifLogs' => $verifLogs,
            'assLogs' => $assLogs
        ];

        // $vacancyUserAttachmentSK = VacancyUserAttachment::with('vacancyAttachment')
        //     ->where('vacancy_user_id', $vacancyUser->id)
        //     ->where('category', 'lampiran')
        //     ->whereHas('vacancyAttachment', function ($query) {
        //         $query->whereIn('name', ['SK', 'Petikan']);
        //     })
        //     ->get();

        $vacancyAttachments = VacancyAttachment::with('vacancy', 'attachment')->where('vacancy_id', $vacancyUser->vacancy_id)->where('category', 'lampiran')->get();
        // dd($vacancyAttachments);

        $vacancyReports = VacancyReport::with('vacancyuser')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('logs', 'sectionLog', 'vacancyUser', 'vacancyUserAttachments', 'vacancyAttachments', 'vacancyReports'));
    }

    public function indexReport()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'report')->paginate(10);
        $submenu = 'Laporan';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showReport($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'report')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->where('category', 'syarat')
            ->get();

        $logs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->get();
        $verifLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Verifikasi Berkas')->orderByDesc('created_at')->get();
        $assLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Asessment')->orderByDesc('created_at')->get();

        $sectionLog = (object) [
            'verifLogs' => $verifLogs,
            'assLogs' => $assLogs
        ];

        $vacancyAttachments = VacancyAttachment::with('vacancy', 'attachment')->where('vacancy_id', $vacancyUser->vacancy_id)->where('category', 'lampiran')->get();

        $vacancyReports = VacancyReport::with('vacancyuser')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('logs', 'sectionLog', 'vacancyUser', 'vacancyUserAttachments', 'vacancyAttachments', 'vacancyReports'));
    }

    public function indexExtend()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'extend')->paginate(10);
        $submenu = 'Laporan';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showExtend($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'extend')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->where('category', 'syarat')
            ->get();

        $logs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->get();
        $verifLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Verifikasi Berkas')->orderByDesc('created_at')->get();
        $assLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Asessment')->orderByDesc('created_at')->get();

        $sectionLog = (object) [
            'verifLogs' => $verifLogs,
            'assLogs' => $assLogs
        ];

        $vacancyAttachments = VacancyAttachment::with('vacancy', 'attachment')->where('vacancy_id', $vacancyUser->vacancy_id)->where('category', 'lampiran')->get();

        $vacancyReports = VacancyReport::with('vacancyuser')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('logs', 'sectionLog', 'vacancyUser', 'vacancyUserAttachments', 'vacancyAttachments', 'vacancyReports'));
    }

    public function indexDone()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'done')->paginate(10);
        $submenu = 'Laporan';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showDone($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'done')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->where('category', 'syarat')
            ->get();

        $logs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->get();
        $verifLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Verifikasi Berkas')->orderByDesc('created_at')->get();
        $assLogs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('name', 'Asessment')->orderByDesc('created_at')->get();

        $sectionLog = (object) [
            'verifLogs' => $verifLogs,
            'assLogs' => $assLogs
        ];

        $vacancyAttachments = VacancyAttachment::with('vacancy', 'attachment')->where('vacancy_id', $vacancyUser->vacancy_id)->where('category', 'lampiran')->get();

        $vacancyReports = VacancyReport::with('vacancyuser')
            ->where('vacancy_user_id', $vacancyUser->id)
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('logs', 'sectionLog', 'vacancyUser', 'vacancyUserAttachments', 'vacancyAttachments', 'vacancyReports'));
    }
}
