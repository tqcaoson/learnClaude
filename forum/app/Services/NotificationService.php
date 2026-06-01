<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\NotificationRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class NotificationService
{
    public function __construct(
        private readonly NotificationRepository $repository
    ) {}

    public function getForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getForUser($userId, $perPage);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->repository->getUnreadCount($userId);
    }

    public function markRead(int $notificationId, int $userId): bool
    {
        return $this->repository->markRead($notificationId, $userId);
    }

    public function markAllRead(int $userId): int
    {
        return $this->repository->markAllRead($userId);
    }

    public function notifyNewComment(Post $post, Comment $comment, User $commenter): void
    {
        if ($post->user_id === $commenter->id) {
            return;
        }

        $this->repository->create([
            'user_id' => $post->user_id,
            'type'    => 'new_comment',
            'title'   => 'New comment on your post',
            'body'    => $commenter->name . ' commented: ' . Str::limit($comment->content, 80),
            'url'     => '/posts/' . ($post->slug ?? $post->id) . '/view',
        ]);
    }

    public function notifyReply(Comment $parent, Comment $reply, User $replier): void
    {
        if ($parent->user_id === $replier->id) {
            return;
        }

        $this->repository->create([
            'user_id' => $parent->user_id,
            'type'    => 'reply_comment',
            'title'   => 'Someone replied to your comment',
            'body'    => $replier->name . ' replied: ' . Str::limit($reply->content, 80),
            'url'     => '/posts/' . ($reply->post_id ?? '') . '/view',
        ]);
    }
}
