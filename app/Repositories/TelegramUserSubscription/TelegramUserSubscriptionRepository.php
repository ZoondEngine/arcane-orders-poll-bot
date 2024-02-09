<?php

namespace App\Repositories\TelegramUserSubscription;

use App\Models\TelegramUserSubscription;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class TelegramUserSubscriptionRepository extends AbstractRepository
{
    /**
     * @param TelegramUserSubscription $model
     */
    public function __construct(
        private readonly TelegramUserSubscription $model
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
