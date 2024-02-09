<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;

class AbstractResourceCollection extends ResourceCollection
{
    public $collects;

    /**
     * Indicates if the collection keys should be preserved.
     */
    public bool $preserveKeys = false;

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, string $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }

    public function toResponse($request): JsonResponse
    {
        $response = parent::toResponse($request);
        $data = collect($response->getData());

        $meta = json_decode(json_encode($data->get('meta')), true);
        $pagination = $this->removePagination($meta);

        if (count($pagination) > 0) {
            $returnable = [
                'success' => $data->get('success'),
                'meta' => $meta,
                'data' => [
                    'pagination' => $pagination,
                    'content' => $data->get('data'),
                ],
            ];
        } else {
            $returnable = [
                'success' => $data->get('success'),
                'meta' => $data->get('meta'),
                'data' => $data->get('data'),
            ];
        }

        if ($data->has('links')) {
            $returnable['links'] = $data->get('links');
        }

        return JsonResponse::fromJsonString(json_encode($returnable));
    }

    private function removePagination(array &$capsule): array
    {
        if (Arr::has($capsule, [
            'current_page',
            'from',
            'last_page',
            'links',
            'path',
            'per_page',
            'to',
            'total',
        ])) {
            $buffer = Arr::only($capsule, [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ]);

            $capsule = Arr::except($capsule, [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ]);

            return $buffer;
        }

        return [];
    }

    public function with(Request $request): array
    {
        return Response::additional();
    }
}
