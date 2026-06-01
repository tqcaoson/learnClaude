<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'board_id',
        'featured_image',
        'slug',
        'views_count',
        'is_locked',
        'hidden_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'hidden_at' => 'datetime',
        'is_locked' => 'boolean',
        'views_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class, 'target_id')->where('target_type', 'post');
    }

    public function likesCount(): int
    {
        return $this->reactions()->count();
    }

    public function isLikedBy(?User $user): bool
    {
        return $user && $this->reactions()->where('user_id', $user->id)->exists();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isHidden(): bool
    {
        return $this->hidden_at !== null;
    }

    public function isLocked(): bool
    {
        return $this->is_locked === true;
    }
}
