<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\CertificateRecognition\app\Models\CompetencyDevelopment;
use Modules\CertificateRecognition\app\Models\PersonalCertificateRecognition;

class PersonalCertificateRecognitionController extends Controller
{
    public function index()
    {
        $pengakuans = PersonalCertificateRecognition::where('user_id', auth()->user()->id)->paginate(10);

        return view('frontend.student-dashboard.personal-certificate-recognition.index', compact('pengakuans'));
    }

    public function create()
    {
        $competencies = CompetencyDevelopment::all();

        return view('frontend.student-dashboard.personal-certificate-recognition.create', compact('competencies'));
    }
}
