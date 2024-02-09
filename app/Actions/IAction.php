<?php

namespace App\Actions;

use App\Dto\IDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface IAction
{
    public function handle(IDto $dto): mixed;
}
