<?php

namespace App\Models\Relations;

use App\Models\TelegramUserSubscription;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTelegramUserSubscriptions
{
    /**
     * @return HasMany
     */
    public function telegramUserSubscriptions(): HasMany
    {
        return $this->hasMany(TelegramUserSubscription::class);
    }
}
