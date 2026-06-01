<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Post;
use App\Models\Report;
use App\Policies\CommentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\PostPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register authorization policies
        \Illuminate\Support\Facades\Gate::policy(Post::class, PostPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Comment::class, CommentPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Group::class, GroupPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Report::class, ReportPolicy::class);
    }
}
