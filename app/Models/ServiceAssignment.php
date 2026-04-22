<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAssignment extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'service_assignments';

    protected $fillable = [
        'service_schedule_id',
        'member_id',
        'service_role_id',
    ];

    public function serviceSchedule()
    {
        return $this->belongsTo(ServiceSchedule::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function serviceRole()
    {
        return $this->belongsTo(ServiceRole::class);
    }
}
