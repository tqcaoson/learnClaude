<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\Comment;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCommentNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly NotificationService $service
    ) {}

    public function handle(CommentCreated $event): void
    {
        $this->service->notifyNewComment($event->post, $event->comment, $event->author);

        if ($event->comment->parent_id) {
            $parent = Comment::find($event->comment->parent_id);
            if ($parent) {
                $this->service->notifyReply($parent, $event->comment, $event->author);
            }
        }
    }
}
