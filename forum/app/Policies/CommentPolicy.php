<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {
        if ($user->id !== $comment->user_id) {
            return false;
        }

        return $comment->created_at->diffInMinutes(now()) <= 15;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isModeratorOrAdmin();
    }
}
