<?php

namespace App\Dto;

final class DeleteDto extends AbstractDto
{
    public function __construct(
        public int $id
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }
}
