<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Commands\BackToMainMenuCommand;
use App\Services\Telegram\Commands\ShowMySubscriptionsCommand;
use App\Services\Telegram\Commands\ShowSubscriptionsListCommand;
use App\Services\Telegram\Commands\StartTelegramCommand;
use App\Services\Telegram\Commands\UnsubscribeCommand;

final class TelegramCommandBus
{
    /**
     * Commands registry
     * @var array|string[]
     */
    public static array $commands = [
        StartTelegramCommand::class,
        ShowSubscriptionsListCommand::class,
        ShowMySubscriptionsCommand::class,
        UnsubscribeCommand::class,
        BackToMainMenuCommand::class
    ];
}
