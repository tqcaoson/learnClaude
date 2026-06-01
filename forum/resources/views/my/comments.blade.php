@extends('layouts.app')

@section('title', 'My Comments')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">💬 My Comments</h1>

            @forelse($comments as $comment)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>On:</strong>
                            @if($comment->post)
                                <a href="{{ route('posts.show', $comment->post_id) }}" class="text-decoration-none">
                                    {{ $comment->post->title }}
                                </a>
                            @else
                                <span class="text-muted">[Post deleted]</span>
                            @endif
                            <small class="text-muted ms-2">{{ $comment->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        <p class="mb-0 text-muted">{{ Str::limit($comment->content, 200) }}</p>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">You haven't posted any comments yet.</div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
@endsection
