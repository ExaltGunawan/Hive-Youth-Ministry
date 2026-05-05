<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RkaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rka_id',
        'item_name',
        'price',
        'quantity',
        'notes',
    ];

    public function rka()
    {
        return $this->belongsTo(Rka::class);
    }

    public function withdrawalItems()
    {
        return $this->hasMany(WithdrawalItem::class);
    }

    public function getRemainingBalanceAttribute(): float
    {
        $totalBudget = (float)($this->price * $this->quantity);

        $activeRequests = $this->withdrawalItems()
            ->whereHas('withdrawalRequest', function ($query) {
                $query->whereIn('status', ['submitted', 'approved', 'more_info', 'actualized']);
            })
            ->get();

        $usedAmount = $activeRequests->sum(function ($item) {
            if ($item->withdrawalRequest->status === 'actualized') {
                return (float)($item->actual_amount ?? $item->requested_amount);
            }
            return (float)$item->requested_amount;
        });

        return max(0, $totalBudget - $usedAmount);
    }
}
