<?php

namespace App\Services\Telegram\User\Subscription;

use App\Repositories\TelegramUserSubscription\TelegramUserSubscriptionRepository;

final class TelegramUserSubscriptionService
{
    public function __construct(
        public readonly TelegramUserSubscriptionRepository $repository
    )
    {
    }

    /**
     * @param int $telegramUserId
     * @param string $unit
     * @return bool
     */
    public function create(int $telegramUserId, string $unit): bool
    {
        return !empty($this->repository->query()->create([
            'telegram_user_id' => $telegramUserId,
            'unit' => $unit
        ]));
    }
}
