<?php

namespace Modules\Pengetahuan\app\Http\Controllers;

use App\Http\Controllers\Controller;

class PengetahuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pengetahuan::index');
    }
}
