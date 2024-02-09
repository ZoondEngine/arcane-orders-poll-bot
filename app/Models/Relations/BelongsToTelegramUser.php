<?php

namespace App\Models\Relations;

use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTelegramUser
{
    /**
     * @return BelongsTo
     */
    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class);
    }
}
