<?php

use Illuminate\Http\JsonResponse;

if (! function_exists('apiResponse')) {
    /**
     * Generate a consistent JSON API response
     *
     * @param  mixed  $data
     * @return JsonResponse
     */
    function apiResponse($data = null, string $message = '', bool $success = true, int $status = 200)
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $status);
    }
}
