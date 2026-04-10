<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function successReponse( $data, string $message, int $code)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function failedResponse(?array $data, string $message, int $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    
}
