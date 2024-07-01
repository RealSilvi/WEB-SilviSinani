<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CannotDeleteDefaultProfileException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json(['error' => true, 'message' => $this->getMessage()], 405);
    }
}
