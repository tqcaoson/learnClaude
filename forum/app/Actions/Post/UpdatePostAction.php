<?php

namespace App\Actions\Post;

use App\DTOs\PostDTO;
use App\Models\Post;
use App\Services\PostService;

class UpdatePostAction
{
    public function __construct(
        private PostService $postService,
    ) {}

    public function execute(PostDTO $dto, Post $post): Post
    {
        return $this->postService->update($dto, $post);
    }
}
