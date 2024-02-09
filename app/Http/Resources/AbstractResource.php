<?php

namespace App\Http\Resources;

use App\Http\Responses\WrappedResourceResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;

abstract class AbstractResource extends JsonResource
{
    protected static bool $default = false;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->with = static::getAdditionalData();
    }

    protected static function getAdditionalData(): array
    {
        return Response::additional();
    }

    public static function collection($resource): AbstractResourceCollection
    {
        return tap(static::newCollection($resource), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }

    protected static function newCollection($resource): AbstractResourceCollection
    {
        return new AbstractResourceCollection($resource, static::class);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->convert();
    }

    abstract protected function convert(): array;

    public function toResponse($request): JsonResponse
    {
        return (new WrappedResourceResponse($this))->toResponse($request);
    }
}
