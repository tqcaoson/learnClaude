<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestPosts = Post::with('user')
            ->where('status', 'published')
            ->latest()
            ->limit(5)
            ->get();

        $categories = Category::withCount('boards')->limit(8)->get();

        $stats = [
            'posts'      => Post::where('status', 'published')->count(),
            'users'      => User::count(),
            'categories' => Category::count(),
        ];

        return view('home', compact('latestPosts', 'categories', 'stats'));
    }
}
