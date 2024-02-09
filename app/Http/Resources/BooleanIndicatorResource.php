<?php

namespace App\Http\Resources;

class BooleanIndicatorResource extends AbstractResource
{
    protected function convert(): array
    {
        return [
            'operation' => $this->resource?->operation ?? 'api',
            'result' => $this->resource->result,
        ];
    }
}
