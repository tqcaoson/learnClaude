<?php

namespace App\Actions\Comment;

use App\Models\Comment;

class UpdateCommentAction
{
    public function execute(Comment $comment, string $content): Comment
    {
        $comment->content = $content;
        $comment->save();

        return $comment;
    }
}
