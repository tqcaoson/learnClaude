<?php

namespace App\Repositories;

use App\Models\Reaction;

class ReactionRepository
{
    public function toggle(int $userId, string $targetType, int $targetId): bool
    {
        $existing = Reaction::where('user_id', $userId)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        Reaction::create([
            'user_id' => $userId,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'reaction_type' => 'like',
        ]);

        return true;
    }

    public function getCount(string $targetType, int $targetId): int
    {
        return Reaction::where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->count();
    }

    public function userLiked(int $userId, string $targetType, int $targetId): bool
    {
        return Reaction::where('user_id', $userId)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->exists();
    }
}
