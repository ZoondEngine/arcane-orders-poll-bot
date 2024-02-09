<?php

namespace App\Actions\Crud;

use App\Actions\CrudAction;
use App\Dto\IDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Throwable;

abstract class UpdateAction extends CrudAction
{
    /**
     * @throws Throwable
     */
    public function handle(IDto $dto): Model|Builder
    {
        $instance = $this->find($dto->id());

        throw_if(! $this->authorized($dto, $instance), 'Unauthorized access');

        if (static::$hooks) {
            $this->before($dto, $instance);
        }

        $instance->update(Arr::except($dto->properties(), ['id']));

        if (static::$hooks) {
            $this->after($dto, $instance);
        }

        return $instance;
    }
}
