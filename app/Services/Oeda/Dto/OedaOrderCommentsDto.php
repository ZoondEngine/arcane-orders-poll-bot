<?php

namespace App\Services\Oeda\Dto;

use App\Dto\TransportDto;

final class OedaOrderCommentsDto extends TransportDto
{
    /**
     * @param string|null $courier
     * @param string|null $kitchen
     */
    public function __construct(
        public readonly ?string $courier = null,
        public readonly ?string $kitchen = null
    )
    {
    }

    /**
     * @param array $data
     * @return OedaOrderCommentsDto
     */
    public static function fromJson(array $data): OedaOrderCommentsDto
    {
        return new OedaOrderCommentsDto(
            $data['courier'],
            $data['kitchen']
        );
    }
}
