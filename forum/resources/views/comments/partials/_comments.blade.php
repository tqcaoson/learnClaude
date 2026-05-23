@forelse($comments as $comment)
    <div class="card shadow-sm mb-3 border-start border-primary border-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <strong class="d-block text-dark">{{ $comment->user->name }}</strong>
                    <small class="text-muted">
                        📅 {{ $comment->created_at->format('M d, Y') }}
                        @if($comment->created_at->diffInMinutes(now()) < 60)
                            <span class="badge bg-info">Just now</span>
                        @elseif($comment->created_at->diffInHours(now()) < 24)
                            <span class="badge bg-info">Today</span>
                        @endif
                    </small>
                </div>
                @auth
                    @if(auth()->id() === $comment->user_id)
                        <form method="POST" action="{{ route('comments.delete', $comment->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Delete this comment permanently?')"
                                    title="Delete comment">
                                🗑️ Delete
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
            <p class="card-text mt-3 mb-0">{{ $comment->content }}</p>
        </div>
    </div>
@empty
    <div class="alert alert-info mt-4">
        <p class="mb-0">💬 No comments yet. <strong>Be the first to share your thoughts!</strong></p>
    </div>
@endforelse
