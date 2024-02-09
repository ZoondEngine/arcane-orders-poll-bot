<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Contracts\TelegramCommandInterface;
use App\Services\Telegram\Scenes\SubscriptionScene;
use App\Services\Telegram\TelegramActionBus;
use App\Services\Telegram\TelegramActionEnum;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Models\TelegraphChat;

final class ShowMySubscriptionsCommand implements TelegramCommandInterface
{
    /**
     * @param string $text
     * @return bool
     */
    public function shouldBeHandle(string $text): bool
    {
        return $text === TelegramActionBus::take(TelegramActionEnum::ShowMySubscriptions);
    }

    /**
     * @param Message $message
     * @param TelegraphChat $chat
     * @return void
     */
    public function handle(Message $message, TelegraphChat $chat): void
    {
        SubscriptionScene::init($message->from());

        $chat->message(SubscriptionScene::message(TelegramActionEnum::ShowMySubscriptions->value))
            ->replyKeyboard(ReplyKeyboard::make()->buttons(SubscriptionScene::keyboard([])))
            ->send();
    }
}
