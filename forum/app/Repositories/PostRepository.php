<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Pagination\Paginator;

class PostRepository
{
    public function findById(int $id): ?Post
    {
        return Post::find($id);
    }

    public function findByIdWithRelations(int $id): ?Post
    {
        return Post::with(['user', 'comments.user'])->find($id);
    }

    public function getPaginatedPublished(int $perPage = 25)
    {
        return Post::where('status', 'published')
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function getUserPosts(int $userId, int $perPage = 25)
    {
        return Post::where('user_id', $userId)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);
        return $post;
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    public function restore(Post $post): bool
    {
        return $post->restore();
    }
}
