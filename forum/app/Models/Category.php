<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public function scopeOrdered($q): void
    {
        $q->orderBy('sort_order');
    }
}
