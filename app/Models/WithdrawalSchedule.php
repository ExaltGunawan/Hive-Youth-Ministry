<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawalSchedule extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'pengambil_id',
        'rka_detail_id',
        'jumlah_diambil',
        'status',
        'notes',
    ];

    public function pengambil()
    {
        return $this->belongsTo(User::class, 'pengambil_id');
    }

    public function rkaDetail()
    {
        return $this->belongsTo(RkaDetail::class);
    }
}
