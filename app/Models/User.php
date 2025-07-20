<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'status_reason',
        'suspended_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user is disabled
     */
    public function isDisabled(): bool
    {
        return $this->status === 'disabled';
    }

    /**
     * Suspend user
     */
    public function suspend(string $reason = null): void
    {
        $this->update([
            'status' => 'suspended',
            'status_reason' => $reason,
            'suspended_at' => now(),
        ]);
    }

    /**
     * Activate user
     */
    public function activate(): void
    {
        $this->update([
            'status' => 'active',
            'status_reason' => null,
            'suspended_at' => null,
        ]);
    }

    /**
     * Disable user
     */
    public function disable(string $reason = null): void
    {
        $this->update([
            'status' => 'disabled',
            'status_reason' => $reason,
            'suspended_at' => null,
        ]);
    }
}
