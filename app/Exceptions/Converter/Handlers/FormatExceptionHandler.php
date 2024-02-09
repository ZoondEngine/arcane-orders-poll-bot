<?php

namespace App\Exceptions\Converter\Handlers;

use App\Exceptions\Converter\Contracts\IExceptionHandler;
use App\Extensions\ResponseExtensions;
use Closure;
use Illuminate\Http\JsonResponse;
use Throwable;

class FormatExceptionHandler implements IExceptionHandler
{
    public function handle(Throwable $e, Closure $next): JsonResponse
    {
        return $this->convert($e);
    }

    protected function convert(Throwable $e): JsonResponse
    {
        return ResponseExtensions::pack(JsonResponse::fromJsonString(
            json_encode([
                'errors' => [
                    'message' => empty($e->getMessage())
                        ? 'Unknown error'
                        : $e->getMessage(),
                ],
            ]),
            ! $this->validCode($e->getCode()) ? 401 : $e->getCode()
        ));
    }

    protected function validCode(mixed $code): bool
    {
        if(empty($code))
        {
            return false;
        }

        if(!is_int($code))
        {
            return false;
        }

        return $code < 512
            && $code > 99;
    }
}
