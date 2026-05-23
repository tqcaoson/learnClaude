<?php

namespace App\Http\Controllers;

use App\Actions\Comment\CreateCommentAction;
use App\Actions\Comment\DeleteCommentAction;
use App\DTOs\CommentDTO;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use Illuminate\Auth\Access\AuthorizationException;

class CommentController extends Controller
{
    public function __construct(
        private PostRepository $postRepository,
        private CommentRepository $commentRepository,
        private CreateCommentAction $createCommentAction,
        private DeleteCommentAction $deleteCommentAction,
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
