<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function index(Request $request): View
    {
        $status = $request->query('status');

        if ($status && !in_array($status, ['published', 'draft'])) {
            $status = null;
        }

        $posts = $this->postRepository->getUserPostsFiltered(auth()->id(), $status, 10);

        return view('my.posts', compact('posts', 'status'));
    }
}
