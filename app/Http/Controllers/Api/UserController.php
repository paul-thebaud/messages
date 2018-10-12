<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
