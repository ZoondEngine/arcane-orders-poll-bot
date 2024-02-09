<?php

namespace App\Repositories\Poll;

use App\Models\Poll;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class PollRepository extends AbstractRepository
{
    /**
     * @param Poll $model
     */
    public function __construct(
        private readonly Poll $model
    )
    {
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }
}
