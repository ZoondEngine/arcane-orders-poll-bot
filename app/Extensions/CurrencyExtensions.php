<?php

namespace App\Extensions;

final class CurrencyExtensions
{
    public static function minor(float $amount): int
    {
        return (int) bcmul((string) $amount, '100');
    }
}
