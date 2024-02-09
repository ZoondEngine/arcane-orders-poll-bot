<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property Carbon $last_updated_at
 * @property int $last_order_number
 */
class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_updated_at',
        'last_order_number'
    ];

    protected $casts = [
        'last_updated_at' => 'datetime'
    ];
}
