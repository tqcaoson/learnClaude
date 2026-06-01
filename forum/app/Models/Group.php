<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Group extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'avatar',
        'type',
        'status',
        'members_count',
        'posts_count',
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
        'members_count' => 'integer',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot('role', 'joined_at');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(GroupInvitation::class);
    }

    public function isPublic(): bool
    {
        return $this->type === 'public';
    }

    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function getAvatarUrl(): string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    public function isMember(User $user): bool
    {
        return GroupMember::where('group_id', $this->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function getMemberRole(User $user): ?string
    {
        $member = GroupMember::where('group_id', $this->id)
            ->where('user_id', $user->id)
            ->first();

        return $member?->role;
    }

    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function canPost(User $user): bool
    {
        return $this->isPublic() || $this->isMember($user);
    }

    public function canView(User $user): bool
    {
        return $this->isPublic() || $this->isMember($user);
    }
}
