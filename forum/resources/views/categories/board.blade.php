@extends('layouts.app')

@section('title', $board->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item">{{ $board->category->name }}</li>
                    <li class="breadcrumb-item active">{{ $board->name }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-1">📋 {{ $board->name }}</h1>
                    @if($board->description)
                        <p class="text-muted mb-0">{{ $board->description }}</p>
                    @endif
                </div>
                @auth
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">✍️ New Post</a>
                @endauth
            </div>

            @forelse($posts as $post)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-1">
                            <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <div class="text-muted small mb-2">
                            By <strong>{{ $post->user->name }}</strong> &bull;
                            {{ $post->created_at->format('M d, Y') }} &bull;
                            👁️ {{ $post->views_count }} views
                        </div>
                        @if($post->tags->isNotEmpty())
                            <div>
                                @foreach($post->tags as $tag)
                                    <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">No posts in this board yet.</div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
