<?php

namespace App\Http\Controllers;

use App\Actions\Comment\CreateCommentAction;
use App\Actions\Comment\DeleteCommentAction;
use App\Actions\Comment\UpdateCommentAction;
use App\DTOs\CommentDTO;
use App\Http\Requests\CommentEditRequest;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use App\Services\NotificationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private PostRepository $postRepository,
        private CommentRepository $commentRepository,
        private CreateCommentAction $createCommentAction,
        private DeleteCommentAction $deleteCommentAction,
        private UpdateCommentAction $updateCommentAction,
    ) {}

    public function store(CommentRequest $request, int $postId)
    {
        $post = $this->postRepository->findById($postId);

        if (!$post) {
            abort(404);
        }

        try {
            $dto = CommentDTO::fromRequest($request);
            $this->createCommentAction->execute($dto, $post, auth()->user());

            return back()->with('success', '💬 Your comment has been posted! Thanks for contributing.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '❌ Failed to post comment: ' . $e->getMessage());
        }
    }

    public function reply(Request $request, int $id)
    {
        $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        $parentComment = $this->commentRepository->findById($id);

        if (!$parentComment) {
            abort(404);
        }

        $post = $this->postRepository->findById($parentComment->post_id);

        if (!$post) {
            abort(404);
        }

        try {
            $reply = $this->commentRepository->create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
                'content' => $request->input('content'),
                'parent_id' => $id,
            ]);

            app(NotificationService::class)->notifyReply($parentComment, $reply, auth()->user());

            return back()->with('success', '💬 Reply posted successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '❌ Failed to post reply: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $comment = $this->commentRepository->findByIdWithUser($id);

        if (!$comment) {
            abort(404);
        }

        if (auth()->id() !== $comment->user_id) {
            return redirect()->back()->with('error', '❌ You can only edit your own comments.');
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(CommentEditRequest $request, int $id)
    {
        $comment = $this->commentRepository->findById($id);

        if (!$comment) {
            abort(404);
        }

        try {
            $this->authorize('update', $comment);
        } catch (AuthorizationException $e) {
            return back()->with('error', '❌ You cannot edit this comment (15-minute window may have passed).');
        }

        try {
            $this->updateCommentAction->execute($comment, $request->input('content'));

            return redirect()->route('posts.show', $comment->post_id)
                ->with('success', '✏️ Comment updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '❌ Failed to update comment: ' . $e->getMessage());
        }
    }

    public function delete(int $commentId)
    {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            abort(404);
        }

        try {
            $this->authorize('delete', $comment);
        } catch (AuthorizationException $e) {
            return back()->with('error', '❌ You can only delete your own comments.');
        }

        try {
            $this->deleteCommentAction->execute($comment);

            return back()->with('success', '🗑️ Comment deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Failed to delete comment: ' . $e->getMessage());
        }
    }
}
