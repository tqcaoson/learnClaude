<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModerationLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'moderator_id',
        'action',
        'target_type',
        'target_id',
        'reason',
    ];

    protected $dates = ['created_at'];

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }
}
