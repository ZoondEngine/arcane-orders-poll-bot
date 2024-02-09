<?php

namespace App\Services\Telegram\Contracts;

use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\Models\TelegraphChat;

interface TelegramCommandInterface
{
    public function shouldBeHandle(string $text): bool;

    public function handle(Message $message, TelegraphChat $chat): void;
}
