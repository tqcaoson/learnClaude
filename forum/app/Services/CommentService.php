<?php

namespace App\Services;

use App\DTOs\CommentDTO;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\CommentRepository;

class CommentService
{
    public function __construct(
        private CommentRepository $commentRepository,
        private NotificationService $notificationService,
    ) {}

    public function create(CommentDTO $dto, Post $post, User $user): Comment
    {
        $comment = $this->commentRepository->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => $dto->content,
        ]);

        $this->notificationService->notifyNewComment($post, $comment, $user);

        return $comment;
    }

    public function delete(Comment $comment): bool
    {
        return $this->commentRepository->delete($comment);
    }

    public function canDelete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
