<?php

namespace App\Services\Telegram\Concerns;

use App\Services\Oeda\Dto\OedaOrderDto;
use App\Services\Oeda\Dto\OedaOrderProductDto;

trait InteractionWithOrders
{
    public static function orderToString(OedaOrderDto $dto): string
    {
        return 'Название заведения: ' . $dto->unit . "\n"
            . 'Номер заказа: ' . $dto->number . "\n\n"
            . 'Тип заказа: ' . $dto->type . "\n"
            . 'Заказ создан: ' . $dto->createdAt . "\n"
            . 'Заказ привезут: ' . $dto->shouldBeDeliveredAt . "\n"
            . 'Контактный телефон: ' . $dto->phoneNumber . "\n"
            . 'Адрес получения: ' . (string)$dto->address . "\n\n"
            . "Состав заказа: \n"
            . self::orderProductsToString($dto->products) . "\n\n"
            . 'Сумма заказа: ' . $dto->amount . $dto->currency ."\n"
            . 'Оплата за доставку: ' . $dto->deliveryCost . $dto->currency ."\n"
            . 'ИТОГО: ' . $dto->totalAmount . $dto->currency ."\n"
            . 'Способ оплаты: ' . $dto->payMethod . "\n"
            . 'Комментарий повару: ' . $dto->comments->kitchen . "\n"
            . 'Комментарий курьеру: ' . $dto->comments->courier;
    }

    /**
     * @param array|OedaOrderProductDto[] $products
     * @return string
     */
    private static function orderProductsToString(array $products): string
    {
        $stringifyProducts = [];

        foreach($products as $product)
        {
            $stringifyProducts[] = $product->category->name . ' ' . $product->name;
        }

        return implode("\n", $stringifyProducts);
    }
}
