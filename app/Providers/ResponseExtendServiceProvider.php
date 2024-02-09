<?php

namespace App\Providers;

use App\Extensions\ResponseExtensions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseExtendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('convert', function (JsonResponse $response) {
            return ResponseExtensions::pack($response);
        });

        Response::macro('additional', function (int $statusCode = 200) {
            return ResponseExtensions::headers($statusCode);
        });
    }
}
