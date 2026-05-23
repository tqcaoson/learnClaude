<?php

namespace App\Actions\Post;

use App\DTOs\PostDTO;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;

class CreatePostAction
{
    public function __construct(
        private PostService $postService,
    ) {}

    public function execute(PostDTO $dto, User $user): Post
    {
        return $this->postService->create($dto, $user);
    }
}
