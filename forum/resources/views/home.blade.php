@extends('layouts.app')

@section('title', 'Community Forum — Home')

@section('content')

{{-- ===== Hero ===== --}}
<div class="p-4 p-md-5 mb-4 rounded-3 text-white" style="background: linear-gradient(135deg,#1a73e8 0%,#0d47a1 100%);">
    <div class="py-2">
        <h1 class="display-6 fw-bold">📚 Community Forum</h1>
        <p class="col-md-9 fs-5 mb-3">A place to share knowledge, ask questions, and connect with fellow learners. Join thousands of members and start the conversation today.</p>
        @guest
            <a href="{{ route('auth.register') }}" class="btn btn-light btn-lg fw-semibold me-2">🚀 Join the Discussion</a>
            <a href="{{ route('auth.login') }}" class="btn btn-outline-light btn-lg">🔐 Login</a>
        @else
            <a href="{{ route('posts.create') }}" class="btn btn-light btn-lg fw-semibold me-2">✍️ Create a Post</a>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-light btn-lg">📰 Browse Posts</a>
        @endguest
    </div>
</div>

{{-- ===== Stats ===== --}}
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-primary">{{ number_format($stats['posts']) }}</div>
                <div class="text-muted small">📰 Posts</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-success">{{ number_format($stats['users']) }}</div>
                <div class="text-muted small">👤 Members</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-warning">{{ number_format($stats['categories']) }}</div>
                <div class="text-muted small">🗂️ Categories</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- ===== Latest Posts ===== --}}
    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">🕒 Latest Posts</h4>
            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">View All →</a>
        </div>

        @forelse($latestPosts as $post)
            <div class="card post-item shadow-sm mb-2">
                <div class="card-body py-3">
                    <h6 class="card-title mb-1">
                        <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark fw-semibold">
                            {{ $post->title }}
                        </a>
                    </h6>
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <span>👤 {{ $post->user->name }}</span>
                        <span>·</span>
                        <span>📅 {{ $post->created_at->diffForHumans() }}</span>
                        <span>·</span>
                        <span>👁️ {{ $post->views_count }} views</span>
                    </div>
                    <p class="card-text text-secondary small mb-0 mt-1">{{ Str::limit($post->content, 120) }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No posts yet. Be the first to share!</div>
        @endforelse
    </div>

    {{-- ===== Popular Categories ===== --}}
    <div class="col-lg-5">
        <h4 class="mb-3">🗂️ Categories</h4>
        @forelse($categories as $category)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="fw-medium">{{ $category->name }}</span>
                <span class="badge bg-secondary rounded-pill">{{ $category->boards_count }} boards</span>
            </div>
        @empty
            <p class="text-muted">No categories yet.</p>
        @endforelse

        @guest
            <div class="card mt-4 border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">🎉 Join the community</h5>
                    <p class="card-text text-muted small">Create an account to post, comment, and connect with others.</p>
                    <a href="{{ route('auth.register') }}" class="btn btn-primary w-100">📝 Register for Free</a>
                </div>
            </div>
        @endguest
    </div>
</div>

@endsection
