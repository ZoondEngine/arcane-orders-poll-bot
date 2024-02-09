<?php

namespace App\Repositories\TelegramUser;

use App\Models\TelegramUser;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class TelegramUserRepository extends AbstractRepository
{
    /**
     * @param TelegramUser $model
     */
    public function __construct(
        private readonly TelegramUser $model
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
