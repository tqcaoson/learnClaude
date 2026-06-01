<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Moderation\HidePostAction;
use App\Actions\Moderation\LockPostAction;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Services\ModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function __construct(
        private HidePostAction $hidePostAction,
        private LockPostAction $lockPostAction,
        private ModerationService $moderationService,
    ) {}

    public function hidePost(Request $request, int $id): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $post = Post::findOrFail($id);
        $this->hidePostAction->execute($post, auth()->user(), $request->input('reason', ''));

        return redirect()->back()->with('success', 'Post hidden successfully.');
    }

    public function showPost(Request $request, int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        $this->moderationService->showPost($post, auth()->user());

        return redirect()->back()->with('success', 'Post is now visible.');
    }

    public function lockPost(Request $request, int $id): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $post = Post::findOrFail($id);
        $this->lockPostAction->execute($post, auth()->user(), $request->input('reason', ''));

        return redirect()->back()->with('success', 'Post locked successfully.');
    }

    public function unlockPost(Request $request, int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        $this->moderationService->unlockPost($post, auth()->user());

        return redirect()->back()->with('success', 'Post unlocked successfully.');
    }

    public function hideComment(Request $request, int $id): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $comment = Comment::findOrFail($id);
        $this->moderationService->hideComment($comment, auth()->user(), $request->input('reason', ''));

        return redirect()->back()->with('success', 'Comment hidden successfully.');
    }

    public function showComment(Request $request, int $id): RedirectResponse
    {
        $comment = Comment::findOrFail($id);
        $this->moderationService->showComment($comment, auth()->user());

        return redirect()->back()->with('success', 'Comment is now visible.');
    }
}
