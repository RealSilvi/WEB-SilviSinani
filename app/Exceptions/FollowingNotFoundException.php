<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class FollowingNotFoundException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json(["error" => true, "message" => $this->getMessage()],406);
    }
}
