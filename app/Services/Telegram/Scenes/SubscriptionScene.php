<?php

namespace App\Services\Telegram\Scenes;

use App\Models\TelegramUser;
use App\Services\Telegram\Concerns\InteractionWithUserSubscriptions;
use App\Services\Telegram\Contracts\TelegramSceneInterface;
use App\Services\Telegram\TelegramActionBus;
use App\Services\Telegram\TelegramActionEnum;
use App\Services\Telegram\User\TelegramUserService;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

final class SubscriptionScene implements TelegramSceneInterface
{
    use InteractionWithUserSubscriptions;

    /**
     * @var TelegramUser|null
     */
    private static ?TelegramUser $user = null;
    private static ?string $unit = null;

    /**
     * @param User $user
     * @return void
     */
    public static function init(User $user): void
    {
        /** @var TelegramUserService $telegramUserService */
        $telegramUserService = app(TelegramUserService::class);

        self::$user = $telegramUserService->findByUsername($user->username());
    }

    /**
     * @param string $unit
     * @return void
     */
    public static function setUnit(string $unit): void
    {
        self::$unit = $unit;
    }

    /**
     * @param string $key
     * @return string
     */
    public static function message(string $key): string
    {
        if($key === TelegramActionEnum::ShowSubscriptionsList->value)
        {
            return self::showList(self::aggregateAvailableSubscriptions(self::$user), 'Доступные подписки');
        }

        if($key === TelegramActionEnum::ShowMySubscriptions->value)
        {
            return self::showList(self::aggregateUserSubscriptions(self::$user), 'Активные подписки');
        }

        if($key === TelegramActionEnum::UnsubscribeForAll->value)
        {
            return self::unsubscribe();
        }

        if($key === TelegramActionEnum::SubscribeOnUpdates->value)
        {
            return self::subscribe();
        }

        return '';
    }

    /**
     * @param array $data
     * @return array
     */
    public static function keyboard(array $data): array
    {
        return [
            ReplyButton::make(TelegramActionBus::take(TelegramActionEnum::ToWelcomeScene)),
            ReplyButton::make(TelegramActionBus::take(TelegramActionEnum::SubscribeOnUpdates)),
            ReplyButton::make(TelegramActionBus::take(TelegramActionEnum::UnsubscribeForAll)),
        ];
    }

    /**
     * @param string $text
     * @return bool
     */
    public static function isValidSubscription(string $text): bool
    {
        if (in_array($text, self::aggregateAvailableSubscriptions(self::$user), true)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $subscriptions
     * @param string $header
     * @return string
     */
    private static function showList(array $subscriptions, string $header): string
    {
        $data = 'Список подписок пуст';

        if(count($subscriptions) > 0)
        {
            $data = implode("\n", Arr::flatten($subscriptions));
        }

        return "<b>$header</b>" . "\n\n"
            . $data;
    }

    /**
     * @return string
     */
    private static function subscribe(): string
    {
        if(self::$user->isSubscribedTo(self::$unit))
        {
            return 'Вы уже подписаны на это заведение';
        }

        return self::subscribeTo(self::$user->id, self::$unit)
            ? 'Вы успешно подписаны на обновления заведения: ' . self::$unit
            : 'Не удалось подписаться на обновления, повторите попытку позже';
    }

    /**
     * @return string
     */
    private static function unsubscribe(): string
    {
        self::unsubscribeForAll(self::$user);

        return 'Вы успешно отписаны от всех обновлений';
    }
}
