<?php

namespace App\Actions;

use App\Dto\IDto;

abstract class SomeAction
{
    abstract public function handle(IDto $dto): IDto;
}
