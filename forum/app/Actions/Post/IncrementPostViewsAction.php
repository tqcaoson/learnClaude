<?php

namespace App\Actions\Post;

use App\Repositories\PostRepository;

class IncrementPostViewsAction
{
    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function execute(int $postId): void
    {
        $this->postRepository->incrementViews($postId);
    }
}
