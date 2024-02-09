<?php

namespace App\Services\Telegram;

enum TelegramActionEnum: string
{
    case Start = 'start';
    case ShowSubscriptionsList = 'show-subscriptions-list';
    case ShowMySubscriptions = 'show-my-subscriptions';
    case SubscribeOnUpdates = 'subscribe';
    case UnsubscribeForAll = 'unsubscribe';
    case ToWelcomeScene = 'to-welcome-scene';
}
