<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Order\app\Models\Enrollment;

class StudentPengetahuanController extends Controller
{
    public function index(): View
    {
        $enrolls = Enrollment::with(['course' => function ($q) {
            $q->withTrashed();
        }])->where('user_id', userAuth()->id)->orderByDesc('id')->paginate(10);
        return view('frontend.student-dashboard.pengetahuan.index', compact('enrolls'));
    }
}
