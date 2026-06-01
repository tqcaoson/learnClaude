@extends('layouts.app')

@section('title', 'Edit Comment')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="card-title mb-1">✏️ Edit Comment</h1>
                    <p class="text-muted mb-4">
                        Posted: {{ $comment->created_at->format('M d, Y H:i') }}
                        &bull; You have 15 minutes to edit
                    </p>

                    <form method="POST" action="{{ route('comments.update', $comment->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="content" class="form-label">Comment</label>
                            <textarea
                                class="form-control @error('content') is-invalid @enderror"
                                id="content"
                                name="content"
                                rows="6"
                                required
                            >{{ old('content', $comment->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">✅ Save Changes</button>
                            <a href="{{ route('posts.show', $comment->post_id) }}" class="btn btn-secondary">❌ Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
