<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class AssesmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vacancyUsers = VacancyUser::with(['vacancy', 'vacancy.study', 'user'])->where('status', 'assesment')->paginate(10);

        return view('pendidikanlanjutan::Submenu.index', compact('vacancyUsers'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vacancy = Vacancy::findOrFail($id);

        return view('pendidikanlanjutan::Vacancy.edit', compact('vacancy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    
    }

}
