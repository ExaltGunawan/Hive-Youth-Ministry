<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRole extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['role_name'];

    public function assignments()
    {
        return $this->hasMany(ServiceAssignment::class);
    }
}
