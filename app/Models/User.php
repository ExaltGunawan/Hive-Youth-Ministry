<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Notifiable, HasRoles, SoftDeletes;

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->member?->photo ? Storage::url($this->member->photo) : null;
    }

    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->role) {
                $user->syncRoles([$user->role]);
            }

            // Sync email to Member
            if ($user->member_id && $user->member) {
                $user->member->update(['email' => $user->email]);
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected $fillable = [
        'member_id',
        'email',
        'password',
        'role',
        'divisi_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function getNameAttribute()
    {
        return $this->member?->nama_lengkap ?? $this->email;
    }
}