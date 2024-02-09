<?php

namespace App\Services\Oeda\Dto;

use App\Dto\TransportDto;

final class OedaOrderProductPropertyDto extends TransportDto
{
    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(
        public readonly string $name,
        public readonly string $value
    )
    {
    }

    /**
     * @param array $data
     * @return OedaOrderProductPropertyDto
     */
    public static function fromJson(array $data): OedaOrderProductPropertyDto
    {
        return new OedaOrderProductPropertyDto(
            $data['name'],
            $data['value']
        );
    }
}
