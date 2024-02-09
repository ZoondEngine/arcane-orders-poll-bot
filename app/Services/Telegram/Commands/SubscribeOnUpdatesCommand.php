<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Contracts\TelegramCommandInterface;
use App\Services\Telegram\Scenes\SubscriptionScene;
use App\Services\Telegram\Scenes\WelcomeScene;
use App\Services\Telegram\TelegramActionBus;
use App\Services\Telegram\TelegramActionEnum;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;

final class SubscribeOnUpdatesCommand implements TelegramCommandInterface
{
    public function __construct(
        private readonly User $user
    )
    {
    }

    /**
     * @param string $text
     * @return bool
     */
    public function shouldBeHandle(string $text): bool
    {
        SubscriptionScene::init($this->user);

        return SubscriptionScene::isValidSubscription($text);
    }

    /**
     * @param Message $message
     * @param TelegraphChat $chat
     * @return void
     */
    public function handle(Message $message, TelegraphChat $chat): void
    {
        SubscriptionScene::init($message->from());
        SubscriptionScene::setUnit($message->text());

        $chat->message(SubscriptionScene::message(TelegramActionEnum::SubscribeOnUpdates->value))
            ->replyKeyboard(ReplyKeyboard::make()->buttons(WelcomeScene::keyboard([])))
            ->send();
    }
}
