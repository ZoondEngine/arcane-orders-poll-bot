<?php

namespace App\Exceptions\Converter;

use App\Exceptions\Converter\Handlers\FormatExceptionHandler;
use App\Exceptions\Converter\Handlers\NotFoundExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Pipeline;
use Throwable;

final class ExceptionConverter
{
    /**
     * @var array|string[]
     */
    public array $handlers = [
        NotFoundExceptionHandler::class,
        FormatExceptionHandler::class,
    ];

    public function handle(Throwable $throwable, string $via = 'handle'): Response|JsonResponse
    {
        return Pipeline::send($throwable)
            ->through($this->handlers)
            ->via($via)
            ->then(fn (Response|JsonResponse $response) => $response);
    }

    public static function defaults(): ExceptionConverter
    {
        return self::through([]);
    }

    public static function through(array|string $classes): ExceptionConverter
    {
        $service = new ExceptionConverter();
        $service->handlers = array_merge(
            $service->handlers,
            is_array($classes)
                ? $classes
                : [$classes]
        );

        return $service;
    }
}
