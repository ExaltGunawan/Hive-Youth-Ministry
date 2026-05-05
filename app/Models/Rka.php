<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rka extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // Use string for ID

    protected $fillable = [
        'id', // Manual ID
        'name',
        'fiscal_year',
        'description',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(RkaItem::class);
    }
}
