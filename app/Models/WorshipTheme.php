<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorshipTheme extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['month', 'theme_title'];

    protected $casts = [
        'month' => 'date',
    ];

    public function titles()
    {
        return $this->hasMany(WorshipTitle::class);
    }
}
