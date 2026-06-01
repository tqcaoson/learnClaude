<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\ModerationLog;
use App\Models\Post;
use App\Models\User;

class ModerationService
{
    public function hidePost(Post $post, User $moderator, string $reason = ''): void
    {
        $post->update(['hidden_at' => now()]);
        $this->logAction($moderator, 'hide_post', 'post', $post->id, $reason);
    }

    public function showPost(Post $post, User $moderator): void
    {
        $post->update(['hidden_at' => null]);
        $this->logAction($moderator, 'show_post', 'post', $post->id, '');
    }

    public function lockPost(Post $post, User $moderator, string $reason = ''): void
    {
        $post->update(['is_locked' => true]);
        $this->logAction($moderator, 'lock_post', 'post', $post->id, $reason);
    }

    public function unlockPost(Post $post, User $moderator): void
    {
        $post->update(['is_locked' => false]);
        $this->logAction($moderator, 'unlock_post', 'post', $post->id, '');
    }

    public function hideComment(Comment $comment, User $moderator, string $reason = ''): void
    {
        $comment->update(['hidden_at' => now()]);
        $this->logAction($moderator, 'hide_comment', 'comment', $comment->id, $reason);
    }

    public function showComment(Comment $comment, User $moderator): void
    {
        $comment->update(['hidden_at' => null]);
        $this->logAction($moderator, 'show_comment', 'comment', $comment->id, '');
    }

    private function logAction(User $moderator, string $action, string $targetType, int $targetId, string $reason): void
    {
        ModerationLog::create([
            'moderator_id' => $moderator->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'reason' => $reason ?: null,
        ]);
    }
}
