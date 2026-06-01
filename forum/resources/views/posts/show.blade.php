@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Post Content -->
            <article class="card shadow-sm mb-4">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="Featured image" style="max-height:300px; object-fit:cover;">
                @endif
                <div class="card-body">
                    <h1 class="card-title mb-3">{{ $post->title }}</h1>

                    @if($post->tags->isNotEmpty())
                        <div class="mb-3">
                            @foreach($post->tags as $tag)
                                <span class="badge bg-secondary me-1">🏷️ {{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <strong class="d-block mb-1">👤 {{ $post->user->name }}</strong>
                            <small class="text-muted">
                                📅 {{ $post->created_at->format('F d, Y') }}
                                @if($post->created_at->ne($post->updated_at))
                                    <br>✏️ Updated {{ $post->updated_at->format('F d, Y') }}
                                @endif
                                <br>👁️ {{ $post->views_count }} views
                            </small>
                        </div>
                        @auth
                            <div class="d-flex gap-1 flex-wrap">
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
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reportModal"
                                            data-type="App\Models\Post"
                                            data-id="{{ $post->id }}">
                                        🚩 Report
                                    </button>
                                @endif
                            </div>
                        @endauth
                    </div>

                    <div class="post-content mb-4">
                        {!! nl2br(htmlspecialchars($post->content)) !!}
                    </div>

                    <!-- Like Section -->
                    <div class="like-section d-flex align-items-center gap-2">
                        @auth
                            <button class="btn btn-sm {{ $post->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-danger' }}"
                                    onclick="toggleLike('post', {{ $post->id }}, this)">
                                ❤️ Like
                            </button>
                        @endauth
                        <span class="like-count text-muted">{{ $post->likesCount() }} likes</span>
                    </div>

                    @if($post->isDraft())
                        <div class="alert alert-warning mb-0 mt-3">
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

                @foreach($post->comments->whereNull('parent_id') as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>👤 {{ $comment->user->name }}</strong>
                                    <small class="text-muted ms-2">{{ $comment->created_at->format('M d, Y H:i') }}</small>
                                </div>
                                <div class="d-flex gap-1">
                                    @auth
                                        @if(auth()->id() === $comment->user_id && $comment->created_at->diffInMinutes(now()) <= 15)
                                            <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-sm btn-outline-secondary">✏️ Edit</a>
                                        @endif
                                        @if(auth()->id() === $comment->user_id)
                                            <form method="POST" action="{{ route('comments.delete', $comment->id) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this comment?')">🗑️</button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reportModal"
                                                    data-type="App\Models\Comment"
                                                    data-id="{{ $comment->id }}">
                                                🚩
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <p class="mb-2">{{ $comment->content }}</p>

                            <!-- Comment like section -->
                            <div class="like-section d-flex align-items-center gap-2 mb-2">
                                @auth
                                    <button class="btn btn-sm {{ $comment->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-danger' }}"
                                            onclick="toggleLike('comment', {{ $comment->id }}, this)">
                                        ❤️
                                    </button>
                                @endauth
                                <span class="like-count text-muted small">{{ $comment->likesCount() }}</span>
                                @auth
                                    <button class="btn btn-sm btn-link p-0 text-muted" data-bs-toggle="collapse" data-bs-target="#reply-form-{{ $comment->id }}">
                                        💬 Reply
                                    </button>
                                @endauth
                            </div>

                            <!-- Reply form -->
                            @auth
                                <div class="collapse" id="reply-form-{{ $comment->id }}">
                                    <form method="POST" action="{{ route('comments.reply', $comment->id) }}" class="mt-2">
                                        @csrf
                                        <div class="input-group">
                                            <textarea class="form-control" name="content" rows="2" placeholder="Write a reply..." required></textarea>
                                            <button type="submit" class="btn btn-primary">Reply</button>
                                        </div>
                                    </form>
                                </div>
                            @endauth

                            <!-- Nested replies -->
                            @if($comment->replies->isNotEmpty())
                                <div class="ms-4 mt-3 border-start ps-3">
                                    @foreach($comment->replies as $reply)
                                        <div class="mb-2 pb-2 border-bottom">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>👤 {{ $reply->user->name }}</strong>
                                                    <small class="text-muted ms-2">{{ $reply->created_at->format('M d, Y H:i') }}</small>
                                                </div>
                                                @auth
                                                    @if(auth()->id() === $reply->user_id)
                                                        <form method="POST" action="{{ route('comments.delete', $reply->id) }}" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this reply?')">🗑️</button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                            <p class="mb-0 mt-1">{{ $reply->content }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

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

                        <dt class="col-sm-6">Views:</dt>
                        <dd class="col-sm-6">{{ $post->views_count }}</dd>

                        @if($post->board)
                            <dt class="col-sm-6">Board:</dt>
                            <dd class="col-sm-6">
                                <a href="{{ route('boards.show', $post->board->slug) }}">{{ $post->board->name }}</a>
                            </dd>
                        @endif
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

{{-- Report Modal --}}
@auth
@if(\Illuminate\Support\Facades\Route::has('reports.store'))
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="reportModalLabel">🚩 Report Content</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('reports.store') }}">
                @csrf
                <input type="hidden" name="reportable_type" id="report_type">
                <input type="hidden" name="reportable_id" id="report_id">
                <div class="modal-body">
                    <label for="report_reason" class="form-label small fw-semibold">Reason</label>
                    <textarea class="form-control" id="report_reason" name="reason" rows="3"
                              placeholder="Describe the issue..." required></textarea>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-warning">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

@section('scripts')
<script>
// Populate report modal with the correct type/id
var reportModal = document.getElementById('reportModal');
if (reportModal) {
    reportModal.addEventListener('show.bs.modal', function(event) {
        var btn = event.relatedTarget;
        document.getElementById('report_type').value = btn.getAttribute('data-type');
        document.getElementById('report_id').value   = btn.getAttribute('data-id');
        document.getElementById('report_reason').value = '';
    });
}

async function toggleLike(type, id, btn) {
    const res = await fetch('/reactions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ target_type: type, target_id: id })
    });
    const data = await res.json();
    btn.closest('.like-section').querySelector('.like-count').textContent = data.count + (type === 'post' ? ' likes' : '');
    btn.classList.toggle('btn-danger', data.liked);
    btn.classList.toggle('btn-outline-danger', !data.liked);
}
</script>
@endsection
