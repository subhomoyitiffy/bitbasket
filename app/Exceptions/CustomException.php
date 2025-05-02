<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Exception;

class CustomException extends Exception
{

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}
