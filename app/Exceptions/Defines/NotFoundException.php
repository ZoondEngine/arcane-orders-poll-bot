<?php

namespace App\Exceptions\Defines;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class NotFoundException extends ModelNotFoundException
{
    public function __construct(string $message, int $code, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
