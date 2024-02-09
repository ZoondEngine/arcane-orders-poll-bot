<?php

namespace App\Actions;

use App\Dto\IDto;
use App\Extensions\ClassExtensions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Throwable;

abstract class CrudAction implements IAction
{
    /**
     * @var string|null Required parameter for take model
     */
    protected static ?string $model = null;

    /**
     * @var bool Optional parameter, indicates need to call hooks or not
     */
    protected static bool $hooks = false;

    /**
     * Find one model and returns builder
     *
     * @throws Throwable
     */
    protected function find(int $id): Model|Builder
    {
        return $this->getModelQuery()->findOrFail($id);
    }

    /**
     * Get builder for model class
     *
     * @throws Throwable
     */
    protected function getModelQuery(): Builder
    {
        throw_if(
            ! $this->validateModel(),
            new InvalidArgumentException('$model property was not declared or invalid')
        );

        /** @var Model $model */
        $model = app(static::$model);

        return $model->newQuery();
    }

    protected function validateModel(): bool
    {
        return ClassExtensions::children(static::$model, Model::class);
    }

    /**
     * Lifecycle hook, called before handle
     */
    protected function before(IDto &$dto, Model|Builder $instance): void
    {
    }

    /**
     * Lifecycle hook, called after handle
     */
    protected function after(IDto &$dto, Model|Builder $instance): void
    {
    }

    protected function authorized(IDto $dto, Model|Builder $instance): bool
    {
        return true;
    }
}
