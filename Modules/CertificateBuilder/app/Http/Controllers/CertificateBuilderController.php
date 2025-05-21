<?php

namespace Modules\CertificateBuilder\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Modules\CertificateBuilder\app\Http\Requests\CertificateUpdateRequest;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\CertificateBuilder\app\Models\CertificateBuilderItem;

class CertificateBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = CertificateBuilder::paginate();
        return view('certificatebuilder::index', compact('certificates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $certificate = CertificateBuilder::first();
        $certificateItems = CertificateBuilderItem::all();

        return view('certificatebuilder::create', compact('certificate', 'certificateItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'background' => ['required', 'image', 'max:3000'],
            'background2' => ['nullable', 'image', 'max:3000'],
        ], [
            'background.required' => __('Background is required'),
            'background.image' => __('Background must be an image file'),
            'background.max' => __('Background must not be greater than 3000 kilobytes'),
            'background.mimes' => __('Background must be a file of type: png, jpg'),
            'background2.image' => __('Background must be an image file'),
            'background2.max' => __('Background must not be greater than 3000 kilobytes'),
            'background2.mimes' => __('Background must be a file of type: png, jpg'),
        ]);

        $randName = strtotime(now());
        $bgFile = $request->file('background');
        $bgName = 'certificates/' . now()->year . '/bg_' . $randName . '.png';
        Storage::disk('private')->put($bgName, file_get_contents($bgFile));

        $bgName2 = null;

        if ($request->hasFile('background2')) {
            $bgFile2 = $request->file('background2');
            $bgName2 = 'certificates/' . now()->year . '/bg_2_' . $randName . '.png';
            Storage::disk('private')->put($bgName2, file_get_contents($bgFile2));
        }

        $certificate = CertificateBuilder::create([
            'title' => "Penghargaan untuk [student_name]",
            'sub_title' => "Atas terselesaikannya [course]",
            'description' => "Sertifikat ini diberikan sebagai pengakuan atas keberhasilan penyelesaian [course] yang ditawarkan pada platform [platform_name]. Penerimanya, [student_name], telah menunjukkan dedikasi dan kemahiran yang terpuji.",
            'signature' => "backend/img/QRCode.png",
            'background' => $bgName,
            'background2' => $bgName2,
            'title2' => "Penghargaan untuk [student_name]",
            'sub_title2' => "Atas terselesaikannya [course]",
            'description2' => "Sertifikat ini diberikan sebagai pengakuan atas keberhasilan penyelesaian [course] yang ditawarkan pada platform [platform_name]. Penerimanya, [student_name], telah menunjukkan dedikasi dan kemahiran yang terpuji.",
            'signature2' => "backend/img/QRCode.png",
        ]);

        //Front Attributes
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'title',
            'x_position' => '290',
            'y_position' => '207'
        ]);
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'sub_title',
            'x_position' => '348',
            'y_position' => '237'
        ]);
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'description',
            'x_position' => '25',
            'y_position' => '289'
        ]);
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'signature',
            'x_position' => '418',
            'y_position' => '375'
        ]);

        //Back Attributes
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'title2',
            'x_position' => '290',
            'y_position' => '207'
        ]);
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'sub_title2',
            'x_position' => '348',
            'y_position' => '237'
        ]);
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'description2',
            'x_position' => '25',
            'y_position' => '289'
        ]);
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'signature2',
            'x_position' => '418',
            'y_position' => '375'
        ]);

        return redirect()->route('admin.certificate-builder.edit', $certificate->id)->with(['messege' => __('Created successfully'), 'alert-type' => 'success']);
    }

    function updateItem(Request $request)
    {
        CertificateBuilderItem::updateOrCreate(
            [
                'certificate_builder_id' => $request->certificate_builder_id,
                'element_id' => $request->element_id
            ],
            [
                'x_position' => $request->x_position,
                'y_position' => $request->y_position
            ]
        );

        return response(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    public function edit($id)
    {
        $certificate = CertificateBuilder::findOrFail($id);
        $certificateItems = CertificateBuilderItem::where('certificate_builder_id', $id)->get();

        return view('certificatebuilder::edit', compact('certificate', 'certificateItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificateUpdateRequest $request, $id)
    {
        $certificate = CertificateBuilder::findOrFail($id);

        $sgName = $certificate->signature;
        $bgName = $certificate->background;

        $sg2Name = $certificate->signature2;
        $bg2Name = $certificate->background2;

        $randName = strtotime(now());
        if ($request->hasFile('background')) {
            $bgFile = $request->file('background');
            $bgName = 'certificates/' . now()->year . '/bg_' . $randName . '.png';
            Storage::disk('private')->put($bgName, file_get_contents($bgFile));
        }
        if ($request->hasFile('signature')) {
            $sgFile = $request->file('signature');
            $sgName = 'certificates/' . now()->year . '/sg_' . $randName . '.png';
            Storage::disk('private')->put($sgName, file_get_contents($sgFile));
        }

        if ($request->hasFile('background2')) {
            $bgFile = $request->file('background2');
            $bgName = 'certificates/' . now()->year . '/bg_2_' . $randName . '.png';
            Storage::disk('private')->put($bgName, file_get_contents($bgFile));
        }
        if ($request->hasFile('signature2')) {
            $sgFile = $request->file('signature2');
            $sgName = 'certificates/' . now()->year . '/sg_2_' . $randName . '.png';
            Storage::disk('private')->put($sgName, file_get_contents($sgFile));
        }

        $certificate->update([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'description' => $request->description,
            'signature' => $sgName,
            'background' => $bgName,
            'title2' => $request->title2,
            'sub_title2' => $request->sub_title2,
            'description2' => $request->description2,
            'signature2' => $sg2Name,
            'background2' => $bg2Name,
            'signer_nik' => $request->signer_nik,
            'signer2_nik' => $request->signer2_nik
        ]);

        return redirect()->back()->with(['messege' => __('Updated successfully'), 'alert-type' => 'success']);
    }

    public function destroy($id)
    {
        $certificate = CertificateBuilder::findOrFail($id);
        $certificateItems = CertificateBuilderItem::where('certificate_builder_id', $id)->get();
        foreach ($certificateItems as $item) {
            $item->delete();
        }

        if (filled($certificate->background) && Storage::disk('private')->exists($certificate->background)) {
            Storage::disk('private')->delete($certificate->background);
        }

        if (filled($certificate->background2) && Storage::disk('private')->exists($certificate->background2)) {
            Storage::disk('private')->delete($certificate->background2);
        }
        $certificate->delete();

        return redirect()->back()->with(['messege' => __('Deleted successfully'), 'alert-type' => 'success']);
    }

    public function getBg($id)
    {
        $certificate = CertificateBuilder::findOrFail($id);

        return response()->file(Storage::disk('private')->path($certificate->background));
    }

    public function getSg($id)
    {
        $certificate = CertificateBuilder::findOrFail($id);

        return response()->file(Storage::disk('private')->path($certificate->signature));
    }

    public function getBg2($id)
    {
        $certificate = CertificateBuilder::findOrFail($id);

        return response()->file(Storage::disk('private')->path($certificate->background2));
    }

    public function getSg2($id)
    {
        $certificate = CertificateBuilder::findOrFail($id);

        return response()->file(Storage::disk('private')->path($certificate->signature2));
    }
}
