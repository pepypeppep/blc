<?php

namespace Modules\Mentoring\app\Http\Controllers;

use App\Http\Controllers\Controller;
class MenteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('frontend.student-dashboard.mentoring.mentee.index');
    }
}
