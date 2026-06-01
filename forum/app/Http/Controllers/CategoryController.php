<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Repositories\CategoryRepository;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {}

    public function index(): View
    {
        $categories = $this->categoryRepository->getAllWithBoards();

        return view('categories.index', compact('categories'));
    }

    public function show(string $slug): View
    {
        $board = Board::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = $board->posts()
            ->with('user', 'tags')
            ->where('status', 'published')
            ->latest()
            ->paginate(15);

        return view('categories.board', compact('board', 'posts'));
    }
}
