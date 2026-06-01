<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('boards')->ordered()->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $slug = $this->generateUniqueCategorySlug($request->input('name'));

        Category::create([
            'name'        => $request->input('name'),
            'slug'        => $slug,
            'description' => $request->input('description'),
            'sort_order'  => $request->input('sort_order', 0),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'sort_order'  => $request->input('sort_order', $category->sort_order),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = Category::withCount('boards')->findOrFail($id);

        if ($category->boards_count > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category that still has boards.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function storeboard(Request $request, int $categoryId): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        Category::findOrFail($categoryId);

        $slug = $this->generateUniqueBoardSlug($request->input('name'));

        Board::create([
            'category_id' => $categoryId,
            'name'        => $request->input('name'),
            'slug'        => $slug,
            'description' => $request->input('description'),
            'sort_order'  => $request->input('sort_order', 0),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Board created successfully.');
    }

    public function updateboard(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $board = Board::findOrFail($id);

        $board->update([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'sort_order'  => $request->input('sort_order', $board->sort_order),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Board updated successfully.');
    }

    public function destroyboard(int $id): RedirectResponse
    {
        $board = Board::withCount('posts')->findOrFail($id);

        if ($board->posts_count > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete board that still has posts.');
        }

        $board->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Board deleted successfully.');
    }

    private function generateUniqueCategorySlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    private function generateUniqueBoardSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (Board::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
