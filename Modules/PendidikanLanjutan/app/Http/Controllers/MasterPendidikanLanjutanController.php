<?php

namespace Modules\PendidikanLanjutan\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterReportFiles;
use Illuminate\Http\Request;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterAttachment;

class MasterPendidikanLanjutanController extends Controller
{
    public function reportFileIndex(Request $request)
    {
        $query = VacancyMasterReportFiles::query();

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        if ($request->has('order_by')) {
            $order = $request->input('order_by');
            $query->orderBy('name', $order == 1 ? 'asc' : 'desc');
        }

        $perPage = $request->has('par-page') ? $request->input('par-page') : 10;

        $reports = $query->paginate($perPage);

        $submenu = 'Berkas Laporan';

        return view('pendidikanlanjutan::master.report.index', compact('reports', 'submenu'));
    }

    public function reportFileStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'is_active' => 'required|boolean',
        ]);

        VacancyMasterReportFiles::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }

    public function reportFileEdit($id)
    {
        $report = VacancyMasterReportFiles::find($id);

        return view('pendidikanlanjutan::master.report.show', compact('report'));
    }

    public function reportFileUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        VacancyMasterReportFiles::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    public function attachmentFileIndex(Request $request)
    {
        $query = VacancyMasterAttachment::query();

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        if ($request->has('order_by')) {
            $order = $request->input('order_by');
            $query->orderBy('name', $order == 1 ? 'asc' : 'desc');
        }

        $perPage = $request->has('par-page') ? $request->input('par-page') : 10;

        $attachments = $query->paginate($perPage);

        $submenu = 'Berkas Lampiran';

        return view('pendidikanlanjutan::master.attachment.index', compact('attachments', 'submenu'));
    }

    public function attachmentFileStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
        ]);

        VacancyMasterAttachment::create([
            'name' => $request->name,
            'category' => $request->category
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }

    public function attachmentFileEdit($id)
    {
        $attachment = VacancyMasterAttachment::find($id);

        return view('pendidikanlanjutan::master.attachment.show', compact('attachment'));
    }

    public function attachmentFileUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable',
            'category' => 'nullable'
        ]);

        VacancyMasterAttachment::where('id', $id)->update([
            'name' => $request->name,
            'category' => $request->category
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }
}
