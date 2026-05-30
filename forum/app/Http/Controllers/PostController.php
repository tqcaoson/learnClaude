<?php

namespace App\Http\Controllers;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Post\UpdatePostAction;
use App\DTOs\PostDTO;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository,
        private CreatePostAction $createPostAction,
        private UpdatePostAction $updatePostAction,
        private DeletePostAction $deletePostAction,
    ) {}

    public function index(): View
    {
        $posts = $this->postRepository->getPaginatedPublished(perPage: 10);
        return view('posts.index', compact('posts'));
    }

    public function show($id): View
    {
        $post = $this->postRepository->findByIdWithRelations($id);

        if (!$post || ($post->isDraft() && (!auth()->check() || auth()->id() !== $post->user_id))) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        try {
            $dto = PostDTO::fromRequest($request);
            $post = $this->createPostAction->execute($dto, auth()->user());

            return redirect()->route("posts.show", ["id" => $post->id])
                ->with('success', 'Post created successfully! 🎉');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id): View
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            abort(404);
        }

        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, $id)
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            abort(404);
        }

        $this->authorize('update', $post);

        try {
            $dto = PostDTO::fromRequest($request);
            $post = $this->updatePostAction->execute($dto, $post);

            return redirect()->route("posts.show", ["id" => $post->id])
                ->with('success', 'Post updated successfully! ✏️');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            abort(404);
        }

        $this->authorize('delete', $post);

        try {
            $this->deletePostAction->execute($post);

            return redirect()->route('posts.index')->with('success', 'Post deleted successfully! 🗑️');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
