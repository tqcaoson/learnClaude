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
        $data = [
            'user_id' => $user->id,
            'title' => $dto->title,
            'content' => $dto->content,
            'status' => $dto->status,
        ];

        if ($dto->boardId !== null) {
            $data['board_id'] = $dto->boardId;
        }

        if ($dto->featuredImagePath !== null) {
            $data['featured_image'] = $dto->featuredImagePath;
        }

        return $this->postRepository->create($data);
    }

    public function update(PostDTO $dto, Post $post): Post
    {
        $data = [
            'title' => $dto->title,
            'content' => $dto->content,
            'status' => $dto->status,
            'board_id' => $dto->boardId,
        ];

        if ($dto->featuredImagePath !== null) {
            $data['featured_image'] = $dto->featuredImagePath;
        }

        return $this->postRepository->update($post, $data);
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
