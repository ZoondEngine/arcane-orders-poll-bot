<?php

namespace App\Repositories;

use App\Dto\AbstractDto;
use App\Dto\DeleteDto;
use App\Dto\IDto;
use App\Dto\TransportDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

abstract class AbstractRepository implements IRepository
{
    /**
     * @var int Default page
     */
    protected static int $page = 1;

    /**
     * @var int Default records per page
     */
    protected static int $records = 10;

    /**
     * @var array Relations array
     */
    protected static array $relations = [];

    /**
     * @var bool Preserve search mechanism
     */
    protected static bool $preserveSearch = false;

    /**
     * @var bool Preserve filtering mechanism
     */
    protected static bool $preserveFilters = false;

    /**
     * @var array|null[]
     */
    protected static array $actions = [
        'create' => null,
        'update' => null,
        'delete' => null,
    ];

    /**
     * @throws Throwable
     */
    public function getOrCreate(
        TransportDto $dto,
        array $only = []
    ): Model
    {
        $query = $this->query();
        $properties = count($only) > 0
            ? Arr::only($dto->properties(), $only)
            : $dto->properties();

        foreach ($properties as $key => $value) {
            $query = $query->where($key, $value);
        }

        return $query->exists()
            ? $query->first()
            : $this->create($dto);
    }

    /**
     * @param int $id
     * @param array $relations
     * @return Model
     */
    public function find(int $id, array $relations = []): Model
    {
        $query = $this->query();

        if (count($relations) > 0) {
           $query = $query->with($relations);
        }

        return $query->findOrFail($id);
    }

    /**
     * @throws Throwable
     */
    public function create(TransportDto $data): Model
    {
        return $this->callAction('create', $data);
    }

    /**
     * @param string $field
     * @param object|null $value
     * @param string|null $condition
     * @param string $boolean
     * @return Builder
     */
    public function where(
        string $field,
        object $value = null,
        string $condition = null,
        string $boolean = 'and'
    ): Builder
    {
        return $this->query()->where(
            $field,
            $condition,
            $value,
            $boolean
        );
    }

    /**
     * @throws Throwable
     */
    public function callAction(string $name, IDto $data): ?Model
    {
        throw_if(
            ! Arr::has(static::$actions, [$name])
            || empty(static::$actions[$name]),
            new InvalidArgumentException(
                "Invalid argument for call action '$name'"
            )
        );

        return (new static::$actions[$name]())->handle($data);
    }

    /**
     * @throws Throwable
     */
    public function update(AbstractDto $data): Model
    {
        return $this->callAction('update', $data);
    }

    /**
     * @throws Throwable
     */
    public function delete(DeleteDto $data): bool
    {
        return (bool) $this->callAction('delete', $data);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function raw(): Collection
    {
        return $this->filteredQuery()->get();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function filteredQuery(): Builder|Model
    {
        return $this->filters($this->load($this->query()));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function filters(Builder $builder): Builder
    {
        if (! static::$preserveFilters) {
            if (request()->has('filters')) {
                foreach (request()->get('filters') as $key => $value) {
                    $builder = $builder->where($key, $value);
                }
            }
        }

        if (! static::$preserveSearch) {
            if (request()->has('search') && request()->has('search-by')) {
                $search = request()->get('search');
                $by = request()->get('search-by');
                $builder = $builder->where($by, 'LIKE', '%'.$search.'%');
            }
        }

        return $builder;
    }

    protected function load(Model|Builder $builder): Builder
    {
        return $builder->with(static::$relations);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function applyPagination(Builder $builder): LengthAwarePaginator
    {
        return $this->filters(
            $this->load($builder)
        )->paginate(
            request()->input('records', static::$records),
            page: request()->input('page', static::$page)
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->applyPagination($this->query());
    }
}
