<?php

namespace App\Services\Oeda\Dto;

use App\Dto\TransportDto;
use Carbon\Carbon;

final class OedaOrderDto extends TransportDto
{
    /**
     * @param int $id
     * @param string $unit
     * @param string $number
     * @param string $type
     * @param Carbon $createdAt
     * @param Carbon $shouldBeDeliveredAt
     * @param string $phoneNumber
     * @param OedaOrderAddressDto $address
     * @param array $products
     * @param int $amount
     * @param int $deliveryCost
     * @param int $totalAmount
     * @param string $currency
     * @param OedaOrderCommentsDto $comments
     */
    public function __construct(
        public readonly int $id,
        public readonly string $unit,
        public readonly string $number,
        public readonly string $type,
        public readonly Carbon $createdAt,
        public readonly Carbon $shouldBeDeliveredAt,
        public readonly string $phoneNumber,
        public readonly OedaOrderAddressDto $address,
        public readonly array $products,
        public readonly int $amount,
        public readonly int $deliveryCost,
        public readonly int $totalAmount,
        public readonly string $payMethod,
        public readonly string $currency,
        public readonly OedaOrderCommentsDto $comments
    )
    {
    }

    /**
     * @param array $data
     * @return OedaOrderDto
     */
    public static function fromJson(array $data): OedaOrderDto
    {
        return new OedaOrderDto(
            $data['id'],
            $data['unit'],
            $data['number'],
            $data['delivery_method'],
            Carbon::parse($data['timestamp_create']),
            Carbon::parse($data['order_time_at']),
            $data['payload']['client']['phone'],
            OedaOrderAddressDto::fromJson($data['client_address']),
            OedaOrderProductDto::fromJsonMass($data['payload']['products']),
            $data['amount'],
            $data['delivery_cost'],
            $data['total_amount'],
            $data['payment']['name'],
            $data['currency'],
            OedaOrderCommentsDto::fromJson($data['payload']['comments'])
        );
    }
}
