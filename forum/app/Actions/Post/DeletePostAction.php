<?php

namespace App\Actions\Post;

use App\Models\Post;
use App\Services\PostService;

class DeletePostAction
{
    public function __construct(
        private PostService $postService,
    ) {}

    public function execute(Post $post): bool
    {
        return $this->postService->delete($post);
    }
}
