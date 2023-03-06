<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    private function jsonResponse(bool $success, array $jsonData, int $statusCode): JsonResponse
    {
        // Add success info as a first key
        $jsonData = ['success' => $success] + $jsonData;
        return response()->json($jsonData, $statusCode);
    }

    protected function jsonResponseSuccess(array $jsonData, int $statusCode = 200): JsonResponse
    {
        return $this->jsonResponse(true, $jsonData, $statusCode);
    }

    protected function jsonResponseError(string|array $errorMessages, int $statusCode = 400): JsonResponse
    {
        return $this->jsonResponse(
            false,
            [
                'error' => [
                    'info'   => is_string($errorMessages) ? array($errorMessages) : $errorMessages
                ]
            ],
            $statusCode
        );
    }
}
