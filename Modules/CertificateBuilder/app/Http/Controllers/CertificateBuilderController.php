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
            // 'title' => ['required', 'string', 'max:255'],
            // 'sub_title' => ['nullable', 'string', 'max:255'],
            // 'description' => ['nullable', 'string', 'max:600'],
            // 'signature' => ['nullable', 'image', 'max:3000', 'mimes:png,jpg', 'dimensions:max_width=500,min_height=10'],
        ], [
            'background.required' => __('Background is required'),
            'background.image' => __('Background must be an image file'),
            'background.max' => __('Background must not be greater than 3000 kilobytes'),
            'background.mimes' => __('Background must be a file of type: png, jpg'),
            // 'title.required' => __('Title is required'),
            // 'title.string' => __('Title must be a string'),
            // 'title.max' => __('Title must not be greater than 255 characters'),
            // 'sub_title.string' => __('Sub title must be a string'),
            // 'sub_title.max' => __('Sub title must not be greater than 255 characters'),
            // 'description.string' => __('Description must be a string'),
            // 'description.max' => __('Description must not be greater than 600 characters'),
            // 'signature.image' => __('Signature must be an image file'),
            // 'signature.max' => __('Signature must not be greater than 3000 kilobytes'),
            // 'signature.mimes' => __('Signature must be a file of type: png, jpg'),
            // 'signature.dimensions' => __('Signature must have a minimum height of 10 pixels and a maximum width of 500 pixels'),
        ]);

        $bgFile = $request->file('background');
        $bgName = 'certificates/bg_' . strtotime(now()) . '.png';
        Storage::disk('private')->put($bgName, file_get_contents($bgFile));
        // $sgFile = $request->file('signature');
        // $sgName = 'certificates/sg_' . strtotime(now()) . '.png';
        // Storage::disk('private')->put($sgName, file_get_contents($sgFile));

        $certificate = CertificateBuilder::create([
            'title' => "Penghargaan untuk [student_name]",
            'sub_title' => "Atas terselesaikannya [course]",
            'description' => "Sertifikat ini diberikan sebagai pengakuan atas keberhasilan penyelesaian [course] yang ditawarkan pada platform [platform_name]. Penerimanya, [student_name], telah menunjukkan dedikasi dan kemahiran yang terpuji.",
            'signature' => "backend/img/QRCode.png",
            'background' => $bgName
        ]);

        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'title',
            'x_position' => '328',
            'y_position' => '180'
        ]);
        CertificateBuilderItem::create([
            'certificate_builder_id' => $certificate->id,
            'element_id' => 'sub_title',
            'x_position' => '376',
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
            'x_position' => '426',
            'y_position' => '389'
        ]);

        return redirect()->route('admin.certificate-builder.edit', $certificate->id)->with(['messege' => __('Created successfully'), 'alert-type' => 'success']);
    }

    function updateItem(Request $request)
    {

        CertificateBuilderItem::updateOrCreate(
            ['element_id' => $request->element_id],
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

        if ($request->hasFile('background')) {
            $bgFile = $request->file('background');
            $bgName = 'certificates/bg_' . strtotime(now()) . '.' . $bgFile->getClientOriginalExtension();
            Storage::disk('private')->put($bgName, file_get_contents($bgFile));
        }
        if ($request->hasFile('signature')) {
            $sgFile = $request->file('signature');
            $sgName = 'certificates/sg_' . strtotime(now()) . '.' . $sgFile->getClientOriginalExtension();
            Storage::disk('private')->put($sgName, file_get_contents($sgFile));
        }

        $certificate->update([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'description' => $request->description,
            'signature' => $sgName,
            'background' => $bgName
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
        if (Storage::disk('private')->exists($certificate->background)) {
            Storage::disk('private')->delete($certificate->background);
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
}
