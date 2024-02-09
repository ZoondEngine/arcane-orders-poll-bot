<?php

namespace App\Http\Responses;

use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Support\Arr;

final class WrappedResourceResponse extends ResourceResponse
{
    /**
     * @param  array  $with
     * @param  array  $additional
     */
    protected function wrap($data, $with = [], $additional = []): array
    {
        $merged = parent::wrap($data, $with, $additional);

        if (Arr::has($merged, [
            'success',
            'meta',
            'data',
        ])) {
            return [
                'success' => $merged['success'],
                'meta' => $merged['meta'],
                'data' => $merged['data'],
            ];
        }

        return $merged;
    }
}
