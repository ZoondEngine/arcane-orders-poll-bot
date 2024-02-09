<?php

namespace App\Models;

use App\Models\Relations\HasTelegramUserSubscriptions;
use App\Services\Oeda\Dto\OedaOrderDto;
use App\Services\Telegram\Concerns\InteractionWithOrders;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

/**
 * @property int $id
 * @property string $username
 * @property int $telegram_id
 * @property HasMany|?TelegramUserSubscription[] $telegramUserSubscriptions
 */
class TelegramUser extends Model
{
    use HasFactory;
    use HasTelegramUserSubscriptions;
    use InteractionWithOrders;

    protected $fillable = [
        'username',
        'telegram_id'
    ];

    /**
     * @param OedaOrderDto $dto
     * @return void
     */
    public function notifyAboutOrder(OedaOrderDto $dto): void
    {
        Log::error(self::orderToString($dto));
        Telegraph::message(self::orderToString($dto))->send();
    }

    /**
     * @param string $unit
     * @return bool
     */
    public function isSubscribedTo(string $unit): bool
    {
        if(!$this->relationLoaded('telegramUserSubscriptions'))
        {
            $this->load('telegramUserSubscriptions');
        }

        return $this->telegramUserSubscriptions
            ->where('unit', $unit)
            ->count() > 0;
    }
}
