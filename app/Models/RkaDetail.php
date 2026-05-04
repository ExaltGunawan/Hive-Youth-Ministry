<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RkaDetail extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'rka_id',
        'item_name',
        'amount',
        'balance',
        'category',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->balance)) {
                $model->balance = $model->amount;
            }
        });
    }

    public function rka()
    {
        return $this->belongsTo(Rka::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(WithdrawalSchedule::class, 'rka_detail_id');
    }
}
