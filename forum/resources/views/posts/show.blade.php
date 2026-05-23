@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Post Content -->
            <article class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="card-title mb-3">{{ $post->title }}</h1>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <strong class="d-block mb-1">👤 {{ $post->user->name }}</strong>
                            <small class="text-muted">
                                📅 {{ $post->created_at->format('F d, Y') }}
                                @if($post->created_at->ne($post->updated_at))
                                    <br>✏️ Updated {{ $post->updated_at->format('F d, Y') }}
                                @endif
                            </small>
                        </div>
                        @auth
                            @if(auth()->id() === $post->user_id)
                                <div class="btn-group" role="group">
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
                                </div>
                            @endif
                        @endauth
                    </div>

                    <div class="post-content mb-4">
                        {!! nl2br(htmlspecialchars($post->content)) !!}
                    </div>

                    @if($post->isDraft())
                        <div class="alert alert-warning mb-0">
                            ⚠️ This is a draft post and is only visible to you
                        </div>
                    @endif
                </div>
            </article>

            <!-- Comments Section -->
            <section class="comments-section">
                <h3 class="mb-4">
                    💬 Comments ({{ $post->comments->count() }})
                </h3>

                @include('comments.partials._comments', ['comments' => $post->comments])

                <!-- Add Comment Form -->
                @auth
                    <div class="card shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">✍️ Add Your Comment</h5>
                            <form method="POST" action="{{ route('comments.store', $post->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="content" class="form-label">Comment</label>
                                    <textarea 
                                        class="form-control @error('content') is-invalid @enderror" 
                                        id="content" 
                                        name="content" 
                                        rows="4" 
                                        placeholder="Share your thoughts..."
                                        required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Post Comment</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mt-4">
                        <a href="{{ route('auth.login') }}" class="alert-link">Login</a> to comment on this post.
                    </div>
                @endauth
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title">ℹ️ Post Details</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Author:</dt>
                        <dd class="col-sm-6"><strong>{{ $post->user->name }}</strong></dd>

                        <dt class="col-sm-6">Status:</dt>
                        <dd class="col-sm-6">
                            @if($post->isDraft())
                                <span class="badge bg-warning text-dark">Draft</span>
                            @else
                                <span class="badge bg-success">Published</span>
                            @endif
                        </dd>

                        <dt class="col-sm-6">Published:</dt>
                        <dd class="col-sm-6 small">{{ $post->created_at->format('M d, Y') }}</dd>

                        <dt class="col-sm-6">Comments:</dt>
                        <dd class="col-sm-6">{{ $post->comments->count() }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Back Link -->
            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                ← Back to Posts
            </a>
        </div>
    </div>
@endsection
