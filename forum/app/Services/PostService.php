<?php

namespace App\Services;

use App\DTOs\PostDTO;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;

class PostService
{
    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function create(PostDTO $dto, User $user): Post
    {
        return $this->postRepository->create([
            'user_id' => $user->id,
            'title' => $dto->title,
            'content' => $dto->content,
            'status' => $dto->status,
        ]);
    }

    public function update(PostDTO $dto, Post $post): Post
    {
        return $this->postRepository->update($post, [
            'title' => $dto->title,
            'content' => $dto->content,
            'status' => $dto->status,
        ]);
    }

    public function delete(Post $post): bool
    {
        return $this->postRepository->delete($post);
    }

    public function canEdit(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function canDelete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
