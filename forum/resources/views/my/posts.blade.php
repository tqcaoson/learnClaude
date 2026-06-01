@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">📝 My Posts</h1>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">✍️ New Post</a>
            </div>

            <!-- Filter -->
            <div class="mb-4">
                <div class="btn-group" role="group">
                    <a href="{{ route('my.posts.index') }}" class="btn btn-sm {{ !$status ? 'btn-dark' : 'btn-outline-dark' }}">
                        All
                    </a>
                    <a href="{{ route('my.posts.index', ['status' => 'published']) }}" class="btn btn-sm {{ $status === 'published' ? 'btn-success' : 'btn-outline-success' }}">
                        Published
                    </a>
                    <a href="{{ route('my.posts.index', ['status' => 'draft']) }}" class="btn btn-sm {{ $status === 'draft' ? 'btn-warning' : 'btn-outline-warning' }}">
                        Draft
                    </a>
                </div>
            </div>

            @forelse($posts as $post)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <div class="text-muted small">
                                    {{ $post->created_at->format('M d, Y') }} &bull;
                                    👁️ {{ $post->views_count }} views
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-3">
                                @if($post->isDraft())
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @else
                                    <span class="badge bg-success">Published</span>
                                @endif
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-outline-secondary">✏️ Edit</a>
                                <form method="POST" action="{{ route('posts.delete', $post->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this post?')">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    @if($status)
                        No {{ $status }} posts found.
                    @else
                        You haven't created any posts yet. <a href="{{ route('posts.create') }}">Create your first post!</a>
                    @endif
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
