<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SendResponse
{
    protected function failed($data, $message, $statusCode = 401): JsonResponse
    {
        return $this->success($data, $message, $statusCode);
    }

    protected function success($data, $message, int $statusCode = 200): JsonResponse
    {
        $response = [
            'code' => $statusCode,
            'message' => $message,
            'status' => $statusCode === 200 ? 'success' : 'error',
            'data' => $data,
        ];
        if (app()->environment('local')) {
            $response['benchmark'] = $this->checkRouteBenchmark();
        }

        return response()->json($response, $statusCode);
    }

    protected function checkRouteBenchmark(): array
    {
        return [
            'memory_taken' => round(memory_get_peak_usage() / (1024 * 1024), 2).'MB',
            'execution_time' => round(microtime(true) - LARAVEL_START, 2).' seconds',
            'resource_cost' => (round(microtime(true) - LARAVEL_START, 2)) * (round(memory_get_peak_usage() / (1024 * 1024), 2)),
        ];
    }
}
