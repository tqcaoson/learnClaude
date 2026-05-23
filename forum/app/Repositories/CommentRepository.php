<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    public function findById(int $id): ?Comment
    {
        return Comment::find($id);
    }

    public function getPostComments(int $postId)
    {
        return Comment::where('post_id', $postId)
            ->with('user')
            ->latest()
            ->get();
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }
}
