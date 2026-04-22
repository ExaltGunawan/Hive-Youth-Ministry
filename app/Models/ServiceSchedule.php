<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSchedule extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'worship_title_id',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function worshipTitle()
    {
        return $this->belongsTo(WorshipTitle::class);
    }

    public function assignments()
    {
        return $this->hasMany(ServiceAssignment::class);
    }
}
