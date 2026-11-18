<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if user is Admin Keuangan
     */
    public function isAdminKeuangan(): bool
    {
        return in_array($this->role, ['admin_keuangan', 'keuangan']);
    }

    /**
     * Check if user has specific role(s)
     */
    public function hasRole(...$roles): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($this->role, $roles);
    }

    /**
     * Get human-readable role label
     */
    public function getRoleLabelAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Admin';
        }

        if ($this->isAdminKeuangan()) {
            return 'Admin Keuangan';
        }

        return ucwords(str_replace('_', ' ', $this->role ?? 'User'));
    }
}
