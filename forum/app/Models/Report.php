<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'status' => 'string',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function getReportableAttribute(): ?Model
    {
        $class = match ($this->reportable_type) {
            'post' => Post::class,
            'comment' => Comment::class,
            default => null,
        };

        return $class ? $class::find($this->reportable_id) : null;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('reportable_type', $type);
    }
}
