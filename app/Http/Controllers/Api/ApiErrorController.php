<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiErrorController extends ApiController
{
    public function methodNotAllowed(Request $request, string $allowedMethods): JsonResponse
    {
        return $this->jsonResponseError('Method ' . $request->method() . ' is not allowed.', 405)
            ->header('Allow', $allowedMethods);
    }

    public function onlyGetMethodAllowed(Request $request): JsonResponse
    {
        return $this->methodNotAllowed($request, 'GET');
    }
}
