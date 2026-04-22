<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 

    protected $casts = [
        'etnis' => 'array',
        'minat_pelayanan' => 'array',
        'hobi_interest' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}