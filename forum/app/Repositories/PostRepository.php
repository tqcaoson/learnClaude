<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class PostRepository
{
    public function findById($id): ?Post
    {
        return Post::find($id);
    }

    public function findByIdWithRelations($id): ?Post
    {
        return Post::with(['user', 'board', 'tags', 'comments' => function ($q) {
            $q->with(['user', 'replies.user']);
        }])->find($id);
    }

    public function getPaginatedPublished($perPage = 25)
    {
        return Post::where('status', 'published')
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function getUserPosts($userId, $perPage = 25)
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

    public function incrementViews(int $postId): void
    {
        DB::table('posts')->where('id', $postId)->increment('views_count');
    }

    public function getUserPostsFiltered(int $userId, ?string $status, int $perPage = 10)
    {
        $query = Post::where('user_id', $userId)->with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }
}
