<?php

namespace App\Services\Telegram\Contracts;

use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;

interface TelegramSceneInterface
{
    public static function init(User $user): void;
    public static function message(string $key): string;
    public static function keyboard(array $data): array;
}
