<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Note extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'id_pembuat',
        'title',
        'value',
        'allowed_viewers',
        'conclusion',
        'attendance',
    ];

    protected $casts = [
        'allowed_viewers' => 'array',
        'attendance' => 'json',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_pembuat');
    }
}
