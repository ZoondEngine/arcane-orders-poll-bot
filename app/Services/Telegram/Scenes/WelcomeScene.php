<?php

namespace App\Services\Telegram\Scenes;

use App\Services\Telegram\Contracts\TelegramSceneInterface;
use App\Services\Telegram\TelegramActionBus;
use App\Services\Telegram\TelegramActionEnum;
use App\Services\Telegram\User\TelegramUserService;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Keyboard\ReplyButton;

final class WelcomeScene implements TelegramSceneInterface
{
    /**
     * @param string $key
     * @return string
     */
    public static function message(string $key): string
    {
        return "<b>Добро пожаловать</b>"
            . "\n\n"
            . "Данный бот умеет отслеживать заказы из заведений, попробуйте подписаться на заказ";
    }

    /**
     * @param array $data
     * @return array
     */
    public static function keyboard(array $data): array
    {
        return [
            ReplyButton::make(TelegramActionBus::take(TelegramActionEnum::ShowSubscriptionsList)),
            ReplyButton::make(TelegramActionBus::take(TelegramActionEnum::ShowMySubscriptions))
        ];
    }

    public static function init(User $user): void
    {
        /** @var TelegramUserService $telegramUserService */
        $telegramUserService = app(TelegramUserService::class);
        $telegramUser = $telegramUserService->findByUsername($user->username());

        if(empty($telegramUser))
        {
            $telegramUserService->create($user->username(), $user->id());
        }
    }
}
