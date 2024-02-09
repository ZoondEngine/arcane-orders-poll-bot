<?php

namespace App\Extensions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class ResponseExtensions
{
    public static function pack(JsonResponse $jsonResponse): JsonResponse
    {
        return Response::json(array_merge(self::headers($jsonResponse->getStatusCode()), [
            'data' => json_decode($jsonResponse->content()),
        ]), $jsonResponse->getStatusCode());
    }

    public static function headers(int $code = 200): array
    {
        return [
            'success' => $code === ResponseAlias::HTTP_OK,
            'meta' => [
                'id' => Str::uuid(),
                'code' => $code,
                'message' => ResponseAlias::$statusTexts[$code],
            ],
        ];
    }
}
