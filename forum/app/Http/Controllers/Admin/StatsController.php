<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users'      => User::count(),
            'new_users_today'  => User::whereDate('created_at', today())->count(),
            'new_users_week'   => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_posts'      => Post::withoutTrashed()->count(),
            'published_posts'  => Post::where('status', 'published')->count(),
            'draft_posts'      => Post::where('status', 'draft')->count(),
            'total_comments'   => Comment::withoutTrashed()->count(),
            'total_groups'     => 0,
            'pending_reports'  => 0,
            'total_reactions'  => 0,
        ];

        try {
            $stats['total_groups'] = \App\Models\Group::count();
        } catch (\Exception $e) {
            $stats['total_groups'] = 0;
        }

        try {
            $stats['pending_reports'] = Report::where('status', 'pending')->count();
        } catch (\Exception $e) {
            $stats['pending_reports'] = 0;
        }

        try {
            $stats['total_reactions'] = Reaction::count();
        } catch (\Exception $e) {
            $stats['total_reactions'] = 0;
        }

        $postsPerDay = Post::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $usersPerDay = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topTags = collect();
        try {
            $topTags = Tag::withCount('posts')->orderByDesc('posts_count')->limit(10)->get();
        } catch (\Exception $e) {
            $topTags = collect();
        }

        $recentPosts = Post::with('user')->latest()->limit(5)->get();
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.stats.index', compact(
            'stats',
            'postsPerDay',
            'usersPerDay',
            'topTags',
            'recentPosts',
            'recentUsers'
        ));
    }
}
