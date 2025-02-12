<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function indexVerif()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'verification')->paginate(10);
        $submenu = 'Verifikasi';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showVerif($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'verification')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->user_id)
            ->where('category', 'syarat')
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('vacancyUser', 'vacancyUserAttachments'));
    }

    public function indexAssesment()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'assesment')->paginate(10);
        $submenu = 'Assesment';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showAssesment($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'assesment')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->user_id)
            ->where('category', 'syarat')
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('vacancyUser', 'vacancyUserAttachments'));
    }

    public function indexSK()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'passed')->paginate(10);
        $submenu = 'Surat Keputusan';

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers', 'submenu'));
    }

    public function showSK($id)
    {
        $vacancyUser = VacancyUser::with(['user'])->where('status', 'passed')
            ->where('id', $id)
            ->first();

        $vacancyUserAttachments = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->user_id)
            ->where('category', 'syarat')
            ->get();

        $vacancyUserAttachmentSK = VacancyUserAttachment::with('vacancyAttachment')
            ->where('vacancy_user_id', $vacancyUser->user_id)
            ->where('category', 'lampiran')
            ->whereHas('vacancyAttachment', function ($query) {
                $query->whereIn('name', ['SK', 'Petikan']);
            })
            ->get();

        return view('pendidikanlanjutan::Submenu.show', compact('vacancyUser', 'vacancyUserAttachments', 'vacancyUserAttachmentSK'));
    }
}
