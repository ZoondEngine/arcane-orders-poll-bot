<?php

namespace App\Services\Oeda\Dto;

use App\Dto\TransportDto;

final class OedaOrderAddressDto extends TransportDto
{
    /**
     * @param string $address
     * @param int|null $apartment
     * @param int|null $entrance
     * @param int|null $floor
     * @param string|null $intercomCode
     */
    public function __construct(
        public readonly string $address,
        public readonly ?int $apartment = null,
        public readonly ?int $entrance = null,
        public readonly ?int $floor = null,
        public readonly ?string $intercomCode = null
    )
    {
    }

    /**
     * @param array $data
     * @return OedaOrderAddressDto
     */
    public static function fromJson(array $data): OedaOrderAddressDto
    {
        return new OedaOrderAddressDto(
            $data['address'],
            $data['apartment'],
            $data['entrance'],
            $data['floor'],
            $data['intercom_code']
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $str = $this->address;

        if(!empty($this->entrance))
        {
            $str .= ', ' . $this->entrance;
        }

        if(!empty($this->floor))
        {
            $str .= ', ' . $this->floor;
        }

        if(!empty($this->intercomCode))
        {
            $str .= ', домофон: ' . $this->intercomCode;
        }

        return $str;
    }
}
