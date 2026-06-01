@extends('layouts.app')

@section('title', 'Community Forum — Bài Viết Mới Nhất')

@section('content')
<div class="content-grid">

    {{-- ===== LEFT: Post list ===== --}}
    <div class="main-col">

        {{-- Section header --}}
        <div class="section-header">
            <h2><i class="bi bi-clock-history me-2 text-primary"></i>Bài Viết Mới Nhất</h2>
            <div class="d-flex align-items-center gap-2">
                @auth
                <a href="{{ route('posts.create') }}" class="btn btn-sm btn-primary" style="font-size:0.82rem;">
                    <i class="bi bi-pencil-square me-1"></i>Viết bài
                </a>
                @endauth
                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.82rem;">Xem tất cả →</a>
            </div>
        </div>

        {{-- Post list --}}
        @forelse($latestPosts as $post)
        <div class="post-card">
            <div class="post-avatar">
                {{ strtoupper(substr($post->user->name ?? '?', 0, 2)) }}
            </div>
            <div class="post-body">
                <div class="post-author-row">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('profile.show') ? route('profile.show', $post->user_id) : '#' }}">
                        {{ $post->user->name ?? 'Unknown' }}
                    </a>
                    <span class="mx-1">·</span>
                    {{ $post->created_at->diffForHumans() }}
                    @if($post->category ?? null)
                        <span class="mx-1">·</span>
                        <span class="post-tag">{{ $post->category->name }}</span>
                    @endif
                </div>
                <a href="{{ route('posts.show', $post->id) }}" class="post-title">{{ $post->title }}</a>
                <div class="post-excerpt">{{ Str::limit(strip_tags($post->content), 150) }}</div>
                <div class="post-meta">
                    <span><i class="bi bi-eye me-1"></i>{{ number_format($post->views_count ?? 0) }}</span>
                    <span><i class="bi bi-chat me-1"></i>{{ $post->comments_count ?? 0 }}</span>
                    @if(($post->tags ?? collect())->count())
                        @foreach($post->tags->take(3) as $tag)
                            <span class="post-tag">{{ $tag->name }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="post-vote">
                <button class="vote-btn"><i class="bi bi-chevron-up"></i></button>
                <span class="vote-count">{{ $post->reactions_count ?? 0 }}</span>
            </div>
        </div>
        @empty
        <div class="post-card justify-content-center text-muted" style="font-size:0.875rem;">
            Chưa có bài viết nào. Hãy là người đầu tiên chia sẻ!
        </div>
        @endforelse

        {{-- Stats row --}}
        <div class="row g-2 mt-2">
            <div class="col-4">
                <div class="text-center p-3 bg-white rounded border" style="font-size:0.82rem;">
                    <div class="fw-bold text-primary fs-5">{{ number_format($stats['posts']) }}</div>
                    <div class="text-muted">Bài viết</div>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center p-3 bg-white rounded border" style="font-size:0.82rem;">
                    <div class="fw-bold text-success fs-5">{{ number_format($stats['users']) }}</div>
                    <div class="text-muted">Thành viên</div>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center p-3 bg-white rounded border" style="font-size:0.82rem;">
                    <div class="fw-bold text-warning fs-5">{{ number_format($stats['categories']) }}</div>
                    <div class="text-muted">Chủ đề</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: Sidebar ===== --}}
    <aside class="sidebar-right">

        {{-- CTA for guests --}}
        @guest
        <div class="cta-banner">
            <h5>Tham gia cộng đồng</h5>
            <p>Đăng ký để chia sẻ bài viết, bình luận và kết nối với các thành viên khác.</p>
            <a href="{{ route('auth.register') }}" class="btn btn-light btn-sm w-100 fw-semibold mb-1">Đăng ký miễn phí</a>
            <a href="{{ route('auth.login') }}" class="btn btn-outline-light btn-sm w-100" style="font-size:0.8rem;">Đã có tài khoản? Đăng nhập</a>
        </div>
        @endguest

        {{-- Câu hỏi mới nhất --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header"><i class="bi bi-question-circle me-1"></i>Chủ Đề Mới Nhất</div>
            <div class="sidebar-card-body">
                @forelse($categories as $category)
                <a href="{{ route('categories.index') }}" class="sidebar-item">
                    <span>{{ $category->name }}</span>
                    <span class="sidebar-badge">{{ $category->boards_count }}</span>
                </a>
                @empty
                <div class="px-3 py-2 text-muted" style="font-size:0.82rem;">Chưa có chủ đề.</div>
                @endforelse
            </div>
        </div>

        {{-- Quick links --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header"><i class="bi bi-link-45deg me-1"></i>Liên Kết Nhanh</div>
            <div class="sidebar-card-body">
                <a href="{{ route('posts.index') }}" class="sidebar-item">
                    <span><i class="bi bi-file-text me-2 text-muted"></i>Tất cả bài viết</span>
                </a>
                @if(\Illuminate\Support\Facades\Route::has('groups.index'))
                <a href="{{ route('groups.index') }}" class="sidebar-item">
                    <span><i class="bi bi-people me-2 text-muted"></i>Nhóm thảo luận</span>
                </a>
                @endif
                @if(\Illuminate\Support\Facades\Route::has('search.index'))
                <a href="{{ route('search.index') }}" class="sidebar-item">
                    <span><i class="bi bi-search me-2 text-muted"></i>Tìm kiếm</span>
                </a>
                @endif
                @auth
                <a href="{{ route('posts.create') }}" class="sidebar-item">
                    <span><i class="bi bi-pencil-square me-2 text-primary"></i>Viết bài mới</span>
                </a>
                @endauth
            </div>
        </div>

    </aside>
</div>
@endsection
