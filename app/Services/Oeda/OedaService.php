<?php

namespace App\Services\Oeda;

use App\Models\Poll;
use App\Models\TelegramUser;
use App\Repositories\Poll\PollRepository;
use App\Services\Oeda\Dto\OedaOrderDto;
use App\Services\Telegram\User\TelegramUserService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

final class OedaService
{
    /**
     * @param PollRepository $pollRepository
     * @param TelegramUserService $userService
     */
    public function __construct(
        private readonly PollRepository $pollRepository,
        private readonly TelegramUserService $userService
    )
    {
    }

    /**
     * @return void
     */
    public function notifyIfHasActualOrders(): void
    {
        $orders = $this->orders();

        // prevent loading all users if orders count was 0
        if(count($orders) <= 0)
        {
            return;
        }

        $users = $this->userService->getAll(['telegramUserSubscriptions']);

        foreach($orders as $order)
        {
            /** @var TelegramUser $user */
            foreach($users as $user)
            {
                if($user->isSubscribedTo($order->unit))
                {
                    $user->notifyAboutOrder($order);
                }
            }
        }
    }

    /**
     * @return array|OedaOrderDto[]
     */
    public function orders(): array
    {
        return collect($this->receive())->transform(function(mixed $order) {
            return OedaOrderDto::fromJson($order);
        })->toArray();
    }

    /**
     * @return array
     */
    private function receive(): array
    {
        /** @var Poll $lastPoll */
        $lastPoll = $this->pollRepository
            ->query()
            ->latest()
            ->first();

        $remoteOrders = $this->getRemoteOrders();

        // if-guard pattern
        if(!Arr::has($remoteOrders, ['success', 'data']))
        {
            return [];
        }

        if(!$remoteOrders['success'])
        {
            return [];
        }

        if(!empty($lastPoll))
        {
            if(!$this->hasActualOrders(
                $lastPoll->last_updated_at,
                Carbon::parse($remoteOrders['data']['last_update']))
            )
            {
                return [];
            }
        }

        $this->pollRepository->query()->create([
            'last_updated_at' => $remoteOrders['data']['last_update'],
            'last_order_number' => Arr::first($remoteOrders['data']['orders'])['id']
        ]);

        return empty($lastPoll)
            ? $remoteOrders['data']['orders']
            : $this->onlyFreshOrders($remoteOrders['data']['orders'], $lastPoll->last_order_number);
    }

    /**
     * @param array $orders
     * @param int $beforeNumber
     * @return array
     */
    private function onlyFreshOrders(array $orders, int $beforeNumber): array
    {
        $freshOrders = [];

        foreach($orders as $order)
        {
            printf("%d == %d\r\n", $order['id'], $beforeNumber);

            if($order['id'] == $beforeNumber)
                break;
            else
                $freshOrders[] = $order;
        }

        return $freshOrders;
    }

    /**
     * @param Carbon $latestPollAt
     * @param Carbon $currentPollAt
     * @return bool
     */
    private function hasActualOrders(Carbon $latestPollAt, Carbon $currentPollAt): bool
    {
        return $latestPollAt < $currentPollAt;
    }

    /**
     * @return array
     */
    private function getRemoteOrders(): array
    {
        // hardcode, it's bad I know, but it's a test case
        return Http::get(
            'https://api.tuktuk.oeda.site/api/unit/72/order?api_key=ba65886f-dfc1-47cc-9bed-705f06d90001&page=1&per_page=20'
        )->json();
    }
}
