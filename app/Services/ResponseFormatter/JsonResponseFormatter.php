<?php

namespace App\Services\ResponseFormatter;

use Illuminate\Http\JsonResponse;

class JsonResponseFormatter implements ResponseFormatterInterface
{
    public function format($data, $status)
    {
        return response()->json($data, $status);
    }
}