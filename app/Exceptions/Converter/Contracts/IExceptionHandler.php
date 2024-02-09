<?php

namespace App\Exceptions\Converter\Contracts;

use Closure;
use Illuminate\Http\JsonResponse;
use Throwable;

interface IExceptionHandler
{
    public function handle(Throwable $e, Closure $next): JsonResponse;
}
