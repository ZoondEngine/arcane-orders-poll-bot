<?php

namespace App\Services\Telegram;

use Illuminate\Support\Arr;

final class TelegramActionBus
{
    /**
     * @var array|string[]
     */
    public static array $actions = [
        'start' => '/start',
        'show-subscriptions-list' => 'Покажите список заведений',
        'show-my-subscriptions' => 'Покажите список моих подписок',
        'subscribe' => 'Подписаться на обновления',
        'unsubscribe' => 'Отписаться от всех обновлений',
        'to-welcome-scene' => 'В главное меню'
    ];

    /**
     * @param TelegramActionEnum $action
     * @return string|null
     */
    public static function take(TelegramActionEnum $action): ?string
    {
        return Arr::has(self::$actions, $action->value)
            ? self::$actions[$action->value]
            : null;
    }
}
