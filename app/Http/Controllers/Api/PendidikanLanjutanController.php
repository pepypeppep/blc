<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Illuminate\Http\Request;

class PendidikanLanjutanController extends Controller
{
    public function index()
    {
        try {
            $vacancy = Vacancy::all();

            return response()->json([
                'success' => true,
                'message' => 'Vacancy retrieved successfully',
                'data' => $vacancy
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vacancy',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $query = Vacancy::where('id', $id);

            $course = $query->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Vacancy retrieved successfully',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
