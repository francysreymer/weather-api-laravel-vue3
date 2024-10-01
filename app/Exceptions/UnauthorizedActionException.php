<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnauthorizedActionException extends Exception
{
    public function __construct(
        private string $message = 'You are not authorized to perform this action'
    ) {
        parent::__construct($this->message, Response::HTTP_FORBIDDEN);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}