<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Article\app\Models\Article;
use Modules\CertificateRecognition\app\Models\CompetencyDevelopment;
use Modules\CertificateRecognition\app\Models\PersonalCertificateRecognition;

class PersonalCertificateRecognitionController extends Controller
{
    public function index()
    {
        $pengakuans = PersonalCertificateRecognition::with('competency_development')->where('user_id', auth()->user()->id)->paginate(10);

        return view('frontend.student-dashboard.personal-certificate-recognition.index', compact('pengakuans'));
    }

    public function create()
    {
        $competencies = CompetencyDevelopment::all();

        return view('frontend.student-dashboard.personal-certificate-recognition.create', compact('competencies'));
    }

    public function show($id)
    {
        $pengakuan = PersonalCertificateRecognition::with('competency_development')
            ->where('user_id', auth()->user()->id)
            ->where('id', $id)->first();

        if (!$pengakuan) {
            return redirect()->back()->with(['messege' => __('Pengakuan sertifikat tidak ditemukan'), 'alert-type' => 'error']);
        }

        return view('frontend.student-dashboard.personal-certificate-recognition.show', compact('pengakuan'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        // try {
        $request->validate([
            'competency_development_id' => 'required|exists:competency_developments,id',
            'title' => 'required',
            'organization' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'report_file' => 'required|file|mimes:pdf',
            'certificate_number' => 'required',
            'certificate_date' => 'required|date',
            'jp' => 'required|integer',
            'official_position' => 'required',
            'graduation_predicate' => 'nullable',
            'certificate_file' => 'required|file|mimes:pdf',
            'award_file' => 'nullable|file|mimes:pdf',
        ], [
            'report_file.required' => __('The report file is required'),
            'report_file.mimes' => __('The report file must be a PDF file'),
            'certificate_file.required' => __('The certificate file is required'),
            'certificate_file.mimes' => __('The certificate file must be a PDF file'),
            'award_file.mimes' => __('The award file must be a PDF file'),
            'award_file.nullable' => __('The award file is required'),
            'competency_development_id.required' => __('The competency development is required'),
            'competency_development_id.exists' => __('The competency development does not exist'),
            'title.required' => __('The title is required'),
            'organization.required' => __('The organization is required'),
            'start_date.required' => __('The start date is required'),
            'start_date.date' => __('The start date must be a valid date'),
            'end_date.required' => __('The end date is required'),
            'end_date.date' => __('The end date must be a valid date'),
            'certificate_number.required' => __('The certificate number is required'),
            'certificate_date.required' => __('The certificate date is required'),
            'certificate_date.date' => __('The certificate date must be a valid date'),
            'jp.required' => __('The JP is required'),
            'jp.integer' => __('The JP must be an integer'),
            'official_position.required' => __('The official position is required'),
            'graduation_predicate.nullable' => __('The graduation predicate is required'),
            'graduation_predicate.string' => __('The graduation predicate must be a string'),
        ]);

        $data = PersonalCertificateRecognition::create([
            'user_id' => auth()->user()->id,
            'competency_development_id' => $request->competency_development_id,
            'title' => $request->title,
            'organization' => $request->organization,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'certificate_number' => $request->certificate_number,
            'certificate_date' => $request->certificate_date,
            'jp' => $request->jp,
            'official_position' => $request->official_position,
            'graduation_predicate' => $request->graduation_predicate,
            'status' => 'draft'
        ]);

        $path = 'pengakuan-sertifikat/' . now()->year . '/' . now()->month . '/' . $data->id . '/';

        $reportFile = $request->file('report_file');
        $reportFileName = $path . "report_" . str_replace([' ', '/'], '_', $request->title) . ".pdf";
        Storage::disk('private')->put($reportFileName, file_get_contents($reportFile));

        $certificateFile = $request->file('certificate_file');
        $certificateFileName = $path . "rcertificate_" . str_replace([' ', '/'], '_', $request->title) . ".pdf";
        Storage::disk('private')->put($certificateFileName, file_get_contents($certificateFile));

        $awardFileName = null;
        if ($request->file('award_file')) {
            $awardFile = $request->file('award_file');
            $awardFileName = $path . "award_" . str_replace([' ', '/'], '_', $request->title) . ".pdf";
            Storage::disk('private')->put($awardFileName, file_get_contents($awardFile));
        }

        $data->update([
            'report_file' => $reportFileName,
            'certificate_file' => $certificateFileName,
            'award_file' => $awardFileName,
        ]);
        DB::commit();

        return redirect()->route('student.pengakuan-sertifikat.index')->with(['messege' => 'Pengakuan sertifikat berhasil dibuat', 'alert-type' => 'success']);
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     DB::rollBack();
        //     return redirect()->back()->with(['messege' => __('Pengakuan sertifikat gagal dibuat'), 'alert-type' => 'error']);
        // }
    }

    public function edit($id)
    {
        $pengakuan = PersonalCertificateRecognition::where('id', $id)->first();

        if (!$pengakuan) {
            return redirect()->back()->with(['messege' => __('Pengakuan sertifikat tidak ditemukan'), 'alert-type' => 'error']);
        }

        $competencies = CompetencyDevelopment::all();

        return view('frontend.student-dashboard.personal-certificate-recognition.edit', compact('pengakuan', 'competencies'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        // try {
        $request->validate([
            'competency_development_id' => 'required|exists:competency_developments,id',
            'title' => 'required',
            'organization' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'report_file' => 'nullable|file|mimes:pdf',
            'certificate_number' => 'required',
            'certificate_date' => 'required|date',
            'jp' => 'required|integer',
            'official_position' => 'required',
            'graduation_predicate' => 'nullable',
            'certificate_file' => 'nullable|file|mimes:pdf',
            'award_file' => 'nullable|file|mimes:pdf',
        ], [
            'report_file.mimes' => __('The report file must be a PDF file'),
            'certificate_file.mimes' => __('The certificate file must be a PDF file'),
            'award_file.mimes' => __('The award file must be a PDF file'),
            'award_file.nullable' => __('The award file is required'),
            'competency_development_id.required' => __('The competency development is required'),
            'competency_development_id.exists' => __('The competency development does not exist'),
            'title.required' => __('The title is required'),
            'organization.required' => __('The organization is required'),
            'start_date.required' => __('The start date is required'),
            'start_date.date' => __('The start date must be a valid date'),
            'end_date.required' => __('The end date is required'),
            'end_date.date' => __('The end date must be a valid date'),
            'certificate_number.required' => __('The certificate number is required'),
            'certificate_date.required' => __('The certificate date is required'),
            'certificate_date.date' => __('The certificate date must be a valid date'),
            'jp.required' => __('The JP is required'),
            'jp.integer' => __('The JP must be an integer'),
            'official_position.required' => __('The official position is required'),
            'graduation_predicate.nullable' => __('The graduation predicate is required'),
            'graduation_predicate.string' => __('The graduation predicate must be a string'),
        ]);

        $data = PersonalCertificateRecognition::where('id', $request->id)->where('status', 'pending')->first();

        $data->update([
            'competency_development_id' => $request->competency_development_id,
            'title' => $request->title,
            'organization' => $request->organization,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'certificate_number' => $request->certificate_number,
            'certificate_date' => $request->certificate_date,
            'jp' => $request->jp,
            'official_position' => $request->official_position,
            'graduation_predicate' => $request->graduation_predicate,
        ]);

        $path = 'pengakuan-sertifikat/' . now()->year . '/' . now()->month . '/' . $data->id . '/';

        $reportFileName = $data->report_file;
        if ($request->file('report_file')) {
            if (Storage::disk('private')->exists($reportFileName)) {
                Storage::disk('private')->delete($reportFileName);
            }

            $reportFile = $request->file('report_file');
            $reportFileName = $path . "report_" . str_replace([' ', '/'], '_', $request->title) . ".pdf";
            Storage::disk('private')->put($reportFileName, file_get_contents($reportFile));
        }

        $certificateFileName = $data->certificate_file;
        if ($request->file('certificate_file')) {
            if (Storage::disk('private')->exists($certificateFileName)) {
                Storage::disk('private')->delete($certificateFileName);
            }

            $certificateFile = $request->file('certificate_file');
            $certificateFileName = $path . "rcertificate_" . str_replace([' ', '/'], '_', $request->title) . ".pdf";
            Storage::disk('private')->put($certificateFileName, file_get_contents($certificateFile));
        }

        $awardFileName = $data->award_file;
        if ($request->file('award_file')) {
            if (Storage::disk('private')->exists($awardFileName)) {
                Storage::disk('private')->delete($awardFileName);
            }

            $awardFile = $request->file('award_file');
            $awardFileName = $path . "award_" . str_replace([' ', '/'], '_', $request->title) . ".pdf";
            Storage::disk('private')->put($awardFileName, file_get_contents($awardFile));
        }

        $data->update([
            'report_file' => $reportFileName,
            'certificate_file' => $certificateFileName,
            'award_file' => $awardFileName,
        ]);
        DB::commit();

        return redirect()->route('student.pengakuan-sertifikat.index')->with(['messege' => 'Pengakuan sertifikat berhasil dibuat', 'alert-type' => 'success']);
    }

    public function destroy(Request $request, int $id)
    {
        $pengakuan = PersonalCertificateRecognition::find($id);
        if ($pengakuan) {
            $article = Article::where('certificate_recognition_id', $id)->first();
            if ($article && $article->status != Article::STATUS_PUBLISHED) {
                $article->delete();
            } elseif ($article && $article->status == Article::STATUS_PUBLISHED) {
                return redirect()->back()->with(['messege' => __('Pengakuan sertifikat tidak dapat dihapus karena artikel yang terkait telah dipublish'), 'alert-type' => 'error']);
            }

            $pengakuan->delete();
            return redirect()->route('student.pengakuan-sertifikat.index')->with(['messege' => 'Pengakuan sertifikat berhasil dihapus', 'alert-type' => 'success']);
        }
        return redirect()->route('student.pengakuan-sertifikat.index')->with(['messege' => 'Pengakuan sertifikat gagal dihapus', 'alert-type' => 'error']);
    }

    public function attachment($id, $file)
    {
        $pengakuan = PersonalCertificateRecognition::with('competency_development')->where('id', $id)->first();

        if (!$pengakuan) {
            return redirect()->back()->with(['messege' => __('Pengakuan sertifikat tidak ditemukan'), 'alert-type' => 'error']);
        }

        if (Storage::disk('private')->exists($pengakuan->$file)) {
            return Storage::disk('private')->response($pengakuan->$file);
        } else {
            abort(404);
        }
    }
}
