<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Generate successful API response
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse(
        $data = [],
        ?string $message = null,
        int $code = 200,
    ): JsonResponse {
        $meta = [];

        // Extract pagination meta if data is paginated
        if ($data instanceof LengthAwarePaginator) {
            $meta = $this->getPaginationMeta($data);
            $data = $data->items();
        }

        $response = [
            'code' => $code,
            'status' => true,
            'message' => $message ?? 'Success',
            'data' => $data,
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Generate error API response
     *
     * @param string|null $message
     * @param array $errors
     * @param int $code
     * @param mixed $data
     * @return JsonResponse
     */
    protected function errorResponse(
        ?string $message = null,
        array $errors = [],
        int $code = 400,
        $data = []
    ): JsonResponse {
        $response = [
            'code' => $code,
            'status' => false,
            'message' => $message ?? 'Error occurred',
            'error' => $errors,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * Extract pagination meta from paginator
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    private function getPaginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }
}
