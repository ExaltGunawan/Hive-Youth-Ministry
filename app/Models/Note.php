<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;
    use HasFactory;

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
