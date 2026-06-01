<?php

namespace App\Models;

use App\Enums\UserRoleEnum;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class User extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'email_verification_token',
        'role',
        'status',
        'avatar',
        'bio',
        'location',
        'website_url',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'email_verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'role' => UserRoleEnum::class,
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role === UserRoleEnum::MODERATOR;
    }

    public function isModeratorOrAdmin(): bool
    {
        return in_array($this->role, [UserRoleEnum::MODERATOR, UserRoleEnum::ADMIN]);
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function getAvatarUrl(): string
    {
        if ($this->avatar) {
            return Storage::disk('public')->url($this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? $this->email);
    }
}
