<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'group_id',
        'name',
        'email',
        'password',
        'role', // user, group_admin, group_member, fasil, facilitator, admin, admin_siyota, super_admin
        'is_active',
        'position_title',
        'phone',
        'avatar_url',
        'google_id',
    ];

    protected $attributes = [
        'role' => 'user',
        'is_active' => true,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Check if user has USER role (Kelompok Tani / Usaha)
     */
    public function isUser(): bool
    {
        return in_array($this->role, ['user', 'group_admin', 'group_member']);
    }

    /**
     * Check if user has FASIL role (Fasilitator Pendamping SiYota)
     */
    public function isFasil(): bool
    {
        return in_array($this->role, ['fasil', 'facilitator']);
    }

    /**
     * Check if user has ADMIN role (Administrator SiYota)
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'admin_siyota']);
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isAdminSiYota(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_siyota', 'admin']);
    }

    public function isFacilitator(): bool
    {
        return in_array($this->role, ['fasil', 'facilitator', 'super_admin', 'admin_siyota', 'admin']);
    }

    public function isGroupAdmin(): bool
    {
        return in_array($this->role, ['group_admin', 'user', 'super_admin']);
    }

    public function isGroupMember(): bool
    {
        return $this->role === 'group_member';
    }

    /**
     * Get clean role label
     */
    public function getCleanRoleLabelAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'ADMIN';
        }
        if ($this->isFasil()) {
            return 'FASIL';
        }
        if ($this->role === 'group_member') {
            return 'ANGGOTA';
        }

        return 'USER';
    }

    /**
     * Redirect route path based on 3 roles
     */
    public function getHomeRouteAttribute(): string
    {
        if ($this->isAdmin()) {
            return route('admin.dashboard');
        }

        if ($this->isFasil()) {
            return route('facilitator.dashboard');
        }

        return route('group.dashboard');
    }
}
