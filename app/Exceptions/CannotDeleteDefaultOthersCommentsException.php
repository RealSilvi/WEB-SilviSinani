<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class CannotDeleteDefaultOthersCommentsException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json(["error" => true, "message" => $this->getMessage()],405);
    }
}
