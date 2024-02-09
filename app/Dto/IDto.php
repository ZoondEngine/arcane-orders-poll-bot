<?php

namespace App\Dto;

interface IDto
{
    public function properties(): array;

    public function property(string $key): ?string;

    public function id(): int;

    public function change(string $key, string $value): void;
}
