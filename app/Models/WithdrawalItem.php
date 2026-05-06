<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalItem extends Model
{
    use \Spatie\Activitylog\Traits\LogsActivity;

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    protected $fillable = [
        'withdrawal_request_id',
        'rka_item_id',
        'requested_amount',
        'actual_amount',
        'proof_images',
    ];

    protected $casts = [
        'proof_images' => 'array',
    ];

    public function withdrawalRequest(): BelongsTo
    {
        return $this->belongsTo(WithdrawalRequest::class);
    }

    public function rkaItem(): BelongsTo
    {
        return $this->belongsTo(RkaItem::class);
    }
}
