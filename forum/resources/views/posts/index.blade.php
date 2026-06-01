@extends('layouts.app')

@section('title', 'Posts - Community Forum')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-1">📚 Recent Posts</h1>
            <p class="text-muted">Discover knowledge shared by our community</p>
        </div>
        @auth
            <div class="col-md-4 text-end">
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">
                    ✏️ Create Post
                </a>
            </div>
        @else
            <div class="col-md-4 text-end">
                <a href="{{ route('auth.login') }}" class="btn btn-primary btn-lg">
                    Login to Create
                </a>
            </div>
        @endauth
    </div>

    @forelse($posts as $post)
        <div class="card post-item shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-2">
                            <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small mb-2">
                            <strong>👤 {{ $post->user->name }}</strong>
                            on {{ $post->created_at->format('M d, Y') }}
                            @if($post->created_at->diffInMinutes(now()) < 60)
                                <span class="badge bg-info">Just now</span>
                            @endif
                            &nbsp;·&nbsp; 👁️ {{ number_format($post->views_count) }} views
                        </p>
                    </div>
                    @if($post->isDraft())
                        <span class="badge bg-warning text-dark">Draft</span>
                    @else
                        <span class="badge bg-success">Published</span>
                    @endif
                </div>
                
                <p class="card-text text-secondary">{{ Str::limit($post->content, 200) }}</p>
                
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">
                        Read More →
                    </a>
                    @auth
                        @if(auth()->id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-outline-secondary">
                                ✏️ Edit
                            </a>
                            <form method="POST" action="{{ route('posts.delete', $post->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Delete this post permanently?')">
                                    🗑️ Delete
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center py-5">
            <h4>📭 No posts yet</h4>
            <p>Be the first to share your knowledge!</p>
            @auth
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create First Post</a>
            @else
                <a href="{{ route('auth.register') }}" class="btn btn-primary">Register & Create Post</a>
            @endauth
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-5">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
@endsection
