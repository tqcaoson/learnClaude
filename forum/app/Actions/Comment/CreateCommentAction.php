<?php

namespace App\Actions\Comment;

use App\DTOs\CommentDTO;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\CommentService;

class CreateCommentAction
{
    public function __construct(
        private CommentService $commentService,
    ) {}

    public function execute(CommentDTO $dto, Post $post, User $user): Comment
    {
        return $this->commentService->create($dto, $post, $user);
    }
}
