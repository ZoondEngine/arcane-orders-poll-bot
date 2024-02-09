<?php

namespace App\Services\Telegram\Concerns;

use App\Extensions\ArrayExtensions;
use App\Models\TelegramUser;
use App\Models\TelegramUserSubscription;
use App\Services\Telegram\User\Subscription\TelegramUserSubscriptionService;
use Illuminate\Support\Arr;

trait InteractionWithUserSubscriptions
{
    /**
     * @param TelegramUser $user
     * @return array
     */
    public static function aggregateUserSubscriptions(
        TelegramUser $user
    ): array
    {
        return $user->load([
            'telegramUserSubscriptions'
        ])->telegramUserSubscriptions->transform(function(TelegramUserSubscription $subscription) {
            return [
                'unit' => $subscription->unit
            ];
        })->toArray();
    }

    /**
     * @param TelegramUser $user
     * @return void
     */
    public static function unsubscribeForAll(TelegramUser $user): void
    {
        $user->load([
            'telegramUserSubscriptions'
        ])->telegramUserSubscriptions->each->delete();
    }

    public static function subscribeTo(int $userId, string $unit): bool
    {
        /** @var TelegramUserSubscriptionService $telegramUserSubscriptionService */
        $telegramUserSubscriptionService = app(TelegramUserSubscriptionService::class);
        return $telegramUserSubscriptionService->create($userId, $unit);
    }

    /**
     * @param TelegramUser $user
     * @return array
     */
    public static function aggregateAvailableSubscriptions(TelegramUser $user): array
    {
        // can't find some other units or some api route for getting them all
        $allSubscriptions = [
            ['unit' => 'Заведение для тестов 123']
        ];
        $alreadySubscribed = self::aggregateUserSubscriptions($user);

        return array_diff(
            Arr::flatten($allSubscriptions),
            Arr::flatten($alreadySubscribed)
        );
    }
}
