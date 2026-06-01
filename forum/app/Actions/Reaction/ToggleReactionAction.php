<?php

namespace App\Actions\Reaction;

use App\Repositories\ReactionRepository;

class ToggleReactionAction
{
    public function __construct(
        private ReactionRepository $reactionRepository,
    ) {}

    public function execute(int $userId, string $type, int $id): bool
    {
        return $this->reactionRepository->toggle($userId, $type, $id);
    }
}
