@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="card-title mb-1">✏️ Edit Post</h1>
                    <p class="text-muted mb-4">Last modified: {{ $post->updated_at->format('M d, Y H:i') }}</p>

                    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="title" class="form-label">📌 Post Title</label>
                            <input
                                type="text"
                                class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                placeholder="Post title"
                                value="{{ old('title', $post->title) }}"
                                required
                                autofocus
                            >
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">📝 Content</label>
                            <textarea
                                class="form-control @error('content') is-invalid @enderror"
                                id="content"
                                name="content"
                                rows="12"
                                placeholder="Your post content..."
                                required
                            >{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                Last updated: {{ $post->updated_at->format('F d, Y H:i') }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="featured_image" class="form-label">🖼️ Featured Image</label>
                            @if($post->featured_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Current featured image" style="max-height:150px; border-radius:4px;">
                                    <small class="d-block text-muted">Upload a new image to replace</small>
                                </div>
                            @endif
                            <input
                                type="file"
                                class="form-control @error('featured_image') is-invalid @enderror"
                                id="featured_image"
                                name="featured_image"
                                accept="image/*"
                            >
                            @error('featured_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Max 2MB.</small>
                        </div>

                        <div class="mb-3">
                            <label for="tags-input" class="form-label">🏷️ Tags</label>
                            <input
                                type="text"
                                class="form-control @error('tags') is-invalid @enderror"
                                id="tags-input"
                                placeholder="Enter tags separated by commas"
                                value="{{ old('tags') ? implode(', ', (array) old('tags')) : $post->tags->pluck('name')->implode(', ') }}"
                            >
                            <div id="tags-hidden-inputs"></div>
                            @error('tags')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($boards->isNotEmpty())
                            <div class="mb-3">
                                <label for="board_id" class="form-label">📋 Board</label>
                                <select class="form-select @error('board_id') is-invalid @enderror" id="board_id" name="board_id">
                                    <option value="">-- No Board --</option>
                                    @foreach($boards as $board)
                                        <option value="{{ $board->id }}" {{ old('board_id', $post->board_id) == $board->id ? 'selected' : '' }}>
                                            {{ $board->category->name }} / {{ $board->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('board_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="status" class="form-label">🎯 Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>
                                    📢 Published - Visible to everyone
                                </option>
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>
                                    📝 Draft - Only visible to you
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                ✅ Save Changes
                            </button>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-secondary btn-lg">
                                ❌ Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const tagsInput = document.getElementById('tags-input');
    const tagsContainer = document.getElementById('tags-hidden-inputs');

    tagsInput.closest('form').addEventListener('submit', function () {
        tagsContainer.innerHTML = '';
        const tags = tagsInput.value.split(',').map(t => t.trim()).filter(t => t.length > 0);
        tags.forEach(tag => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'tags[]';
            input.value = tag;
            tagsContainer.appendChild(input);
        });
    });
</script>
@endsection
