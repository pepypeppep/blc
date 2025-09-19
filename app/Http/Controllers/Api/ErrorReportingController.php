<?php

namespace App\Http\Controllers\Api;

use App\Models\ErrorReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ErrorReportingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/report-error",
     *     operationId="report",
     *     tags={"Error Reporting"},
     *     summary="Report Error",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="module_id", type="integer", example=71),
     *             @OA\Property(property="module", type="string", example="Course"),
     *             @OA\Property(property="error_code", type="integer", example=1150),
     *             @OA\Property(property="title", type="string", example="Video Youtube {lessonId} Error"),
     *             @OA\Property(property="description", type="string", example="Playback on other Websites has been disabled by the video owner."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil melaporkan masalah",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Berhasil melaporkan masalah"),
     *         ),
     *     ),
     * )
     */
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
            'user_id' => $request->user()->id,
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
