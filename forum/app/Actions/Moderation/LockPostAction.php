<?php

namespace App\Actions\Moderation;

use App\Models\Post;
use App\Models\User;
use App\Services\ModerationService;

class LockPostAction
{
    public function __construct(
        private ModerationService $moderationService,
    ) {}

    public function execute(Post $post, User $moderator, string $reason = ''): void
    {
        $this->moderationService->lockPost($post, $moderator, $reason);
    }
}
