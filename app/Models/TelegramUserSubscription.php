<?php

namespace App\Models;

use App\Models\Relations\BelongsToTelegramUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $telegram_user_id
 * @property string $unit
 * @property BelongsTo|?TelegramUser $telegramUser
 */
class TelegramUserSubscription extends Model
{
    use HasFactory;
    use BelongsToTelegramUser;

    protected $fillable = [
        'telegram_user_id',
        'unit'
    ];
}
