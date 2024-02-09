<?php

namespace App\Services\Oeda\Dto;

use App\Dto\TransportDto;

final class OedaOrderProductDto extends TransportDto
{
    /**
     * @param string $name
     * @param int $totalPrice
     * @param OedaOrderProductCategoryDto $category
     * @param array $properties
     */
    public function __construct(
        public readonly string $name,
        public readonly int $totalPrice,
        public readonly OedaOrderProductCategoryDto $category,
        public readonly array $properties,
    )
    {
    }

    /**
     * @param array $data
     * @return OedaOrderProductDto
     */
    public static function fromJson(array $data): OedaOrderProductDto
    {
        $properties = collect($data['variant']['properties'])->transform(function(mixed $element) {
            return OedaOrderProductPropertyDto::fromJson($element);
        })->toArray();

        return new OedaOrderProductDto(
            $data['name'],
            $data['total'],
            OedaOrderProductCategoryDto::fromJson($data['category']),
            $properties
        );
    }

    /**
     * @param array $data
     * @return array
     */
    public static function fromJsonMass(array $data): array
    {
        return collect($data)->transform(function(mixed $product) {
            return self::fromJson($product);
        })->toArray();
    }
}
