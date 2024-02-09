<?php

namespace App\Exceptions\Converter\Handlers;

use App\Exceptions\Defines\NotFoundException;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

final class NotFoundExceptionHandler extends FormatExceptionHandler
{
    public function handle(Throwable $e, Closure $next): JsonResponse
    {
        if ($e instanceof ModelNotFoundException) {
            return parent::handle(new NotFoundException(__('Not found'), 404), $next);
        }

        return $next($e);
    }
}
