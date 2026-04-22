<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorshipTitle extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'worship_titles';

    protected $fillable = [
        'worship_theme_id',
        'date',
        'title',
        'scripture',
        'background_context',
        'objective',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function theme()
    {
        return $this->belongsTo(WorshipTheme::class, 'worship_theme_id');
    }

    public function serviceSchedules()
    {
        return $this->hasMany(ServiceSchedule::class);
    }
}
