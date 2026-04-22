<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'divisi_id',
        'schedule_name',
        'sub_schedule',
        'tempat',
        'sub_tempat',
        'jam',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}
