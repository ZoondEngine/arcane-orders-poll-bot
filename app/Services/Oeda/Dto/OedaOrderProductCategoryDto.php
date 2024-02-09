<?php

namespace App\Services\Oeda\Dto;

use App\Dto\TransportDto;

final class OedaOrderProductCategoryDto extends TransportDto
{
    /**
     * @param string $name
     */
    public function __construct(
        public readonly string $name
    )
    {
    }

    /**
     * @param array $data
     * @return OedaOrderProductCategoryDto
     */
    public static function fromJson(array $data): OedaOrderProductCategoryDto
    {
        return new OedaOrderProductCategoryDto(
            $data['name']
        );
    }
}
