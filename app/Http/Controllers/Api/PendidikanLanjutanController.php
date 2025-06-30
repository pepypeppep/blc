<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyLogs;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancySchedule;

class PendidikanLanjutanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pendidikan-lanjutan",
     *     summary="Get pendidikan lanjutan",
     *     description="Get pendidikan lanjutan",
     *     tags={"Pendidikan Lanjutan"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $user = User::where('id', $request->user_id)->firstOrFail();
            $schedule = VacancySchedule::where('year', now()->year)
                ->where('start_at', '<=', now())
                ->where('end_at', '>=', now())
                ->first();
            $vacancies = Vacancy::with('instansi:id,name', 'study:id,name')->where('instansi_id', $user->instansi_id)
                ->where('year', $schedule->year ?? -1)->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Vacancy retrieved successfully',
                'data' => $vacancies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vacancy',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/pendidikan-lanjutan/{id}",
     *     summary="Get pendidikan lanjutan by id",
     *     description="Get pendidikan lanjutan by id",
     *     tags={"Pendidikan Lanjutan"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vacancy id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        try {
            $user = User::where('id', $request->user_id)->firstOrFail();

            $schedule = VacancySchedule::where('year', now()->year)
                ->where('start_at', '<=', now())
                ->where('end_at', '>=', now())
                ->first();
            $vacancy = Vacancy::with(['details', 'employeeGrade:id,name', 'instansi:id,name', 'study:id,name', 'users' => function ($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotIn('status', [VacancyUser::STATUS_REGISTER]); // next update with value_type, unor, dll
            }])->where('year', $schedule->year ?? -1)->findOrFail($id);

            if ($vacancy->instansi_id != $user->instansi_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lowongan tidak ditemukan',
                    'error' => 'Lowongan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Vacancy retrieved successfully',
                'data' => $vacancy
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/pendidikan-lanjutan/{id}/logs",
     *     summary="Get pendidikan lanjutan logs",
     *     description="Get pendidikan lanjutan logs",
     *     tags={"Pendidikan Lanjutan"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vacancy id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function logs(Request $request, $id)
    {
        try {
            $user = User::where('id', $request->user_id)->firstOrFail();

            $vacancyUser = VacancyUser::where('vacancy_id', $id)->where('user_id', $user->id)->firstOrFail();
            $logs = VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->orderByDesc('id')->get();

            return response()->json([
                'success' => true,
                'message' => 'Vacancy logs retrieved successfully',
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vacancy logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/pendidikan-lanjutan/riwayat",
     *     summary="Get riwayat pendidikan lanjutan",
     *     description="Get riwayat pendidikan lanjutan",
     *     tags={"Pendidikan Lanjutan"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function history(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $user = User::where('id', $request->user_id)->firstOrFail();
            $vacancies = Vacancy::with('instansi:id,name', 'study:id,name', 'users.user:id,name')
                // ->whereIn('id', $vacancyUserIds)
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Vacancy retrieved successfully',
                'data' => $vacancies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vacancy',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
