<?php

namespace App\Http\Controllers\Api;

use App\Models\ErrorReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorReportingController extends Controller
{
    public function report(Request $request)
    {
        $request->validate([
            'module_id' => 'required|integer',
            'module' => 'required|string',
            'error_code' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $errorReport = ErrorReport::firstOrCreate([
            'module_id' => $request->module_id,
            'module' => $request->module,
            'error_code' => $request->error_code,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melaporkan masalah',
        ], 200);
    }
}
