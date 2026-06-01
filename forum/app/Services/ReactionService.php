<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ReactionRepository;

class ReactionService
{
    public function __construct(
        private ReactionRepository $reactionRepository,
    ) {}

    public function toggle(User $user, string $targetType, int $targetId): array
    {
        $liked = $this->reactionRepository->toggle($user->id, $targetType, $targetId);
        $count = $this->reactionRepository->getCount($targetType, $targetId);

        return [
            'liked' => $liked,
            'count' => $count,
        ];
    }
}
