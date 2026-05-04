<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenditureReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'month',
        'year',
        'withdrawal_schedule_id',
        'actual_amount',
        'proof_image',
        'notes',
        'created_by',
    ];

    public function withdrawalSchedule()
    {
        return $this->belongsTo(WithdrawalSchedule::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
