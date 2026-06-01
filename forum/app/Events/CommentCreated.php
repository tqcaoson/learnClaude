<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentCreated
{
    public function __construct(
        public readonly Comment $comment,
        public readonly Post $post,
        public readonly User $author,
    ) {}
}
