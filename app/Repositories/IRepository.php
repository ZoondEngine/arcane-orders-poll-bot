<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface IRepository
{
    public function query(): Builder;
}
