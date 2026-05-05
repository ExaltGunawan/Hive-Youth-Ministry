<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WithdrawalRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'withdrawal_date',
        'notes',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WithdrawalItem::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(WithdrawalComment::class)->orderBy('created_at', 'asc');
    }
}
