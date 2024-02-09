<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Contracts\TelegramCommandInterface;
use App\Services\Telegram\Scenes\WelcomeScene;
use App\Services\Telegram\TelegramActionBus;
use App\Services\Telegram\TelegramActionEnum;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Models\TelegraphChat;

final class StartTelegramCommand implements TelegramCommandInterface
{
    /**
     * @param string $text
     * @return bool
     */
    public function shouldBeHandle(string $text): bool
    {
        return $text === TelegramActionBus::take(TelegramActionEnum::Start);
    }

    /**
     * @param Message $message
     * @param TelegraphChat $chat
     * @return void
     */
    public function handle(Message $message, TelegraphChat $chat): void
    {
        // init the welcome scene
        WelcomeScene::init($message->from());

        $chat->message(WelcomeScene::message(''))
            ->replyKeyboard(ReplyKeyboard::make()->buttons(WelcomeScene::keyboard([])))
            ->send();
    }
}
