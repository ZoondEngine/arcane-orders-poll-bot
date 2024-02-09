<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Commands\SubscribeOnUpdatesCommand;
use App\Services\Telegram\Contracts\TelegramCommandInterface;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

final class TelegramWebhookHandler extends WebhookHandler
{
    /**
     * @param Stringable $text
     * @return void
     */
    protected function handleChatMessage(Stringable $text): void
    {
        if(!$this->handleBetweenCommands($text))
        {
            if($text->value() === TelegramActionBus::take(TelegramActionEnum::SubscribeOnUpdates))
            {
                $this->reply(__('Укажите название заведения для подписки и отправьте боту'));
            }
            else
            {
                $subscribeCommand = new SubscribeOnUpdatesCommand($this->message->from());

                if($subscribeCommand->shouldBeHandle($text->value()))
                {
                    $subscribeCommand->handle($this->message, $this->chat);
                }
                else
                {
                    $this->reply(__('Мне неизвестна указанная команда или вы уже подписаны на указанное заведение'));
                }
            }
        }
    }

    /**
     * @param Stringable $text
     * @return void
     */
    protected function handleUnknownCommand(Stringable $text): void
    {
        if(!$this->handleBetweenCommands($text))
        {
            $this->reply(__('Мне неизвестна указанная команда'));
        }
    }

    /**
     * @param Stringable $text
     * @return bool
     */
    private function handleBetweenCommands(Stringable $text): bool
    {
        /** @var TelegramCommandInterface $command */
        foreach (TelegramCommandBus::$commands as $command)
        {
            /** @var TelegramCommandInterface $instance */
            $instance = app($command);

            if($instance->shouldBeHandle($text->value()))
            {
                $instance->handle($this->message, $this->chat);
                return true;
            }
        }

        return false;
    }
}
