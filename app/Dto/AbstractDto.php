<?php

namespace App\Dto;

use App\Extensions\ArrayExtensions;

abstract class AbstractDto implements IDto
{
    public function property(string $key): ?string
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        return null;
    }

    public function properties(): array
    {
        return array_filter(
            ArrayExtensions::transformToSnake($this),
            fn ($value) => ! empty($value)
        );
    }

    abstract public function id(): int;

    public function change(string $key, string $value): void
    {
        $this->{$key} = $value;
    }
}
