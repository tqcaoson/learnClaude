@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="card-title mb-1">✍️ Create New Post</h1>
                    <p class="text-muted mb-4">Share your knowledge with the community</p>

                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">📌 Post Title</label>
                            <input
                                type="text"
                                class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                placeholder="What's the topic of your post?"
                                value="{{ old('title') }}"
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
                                placeholder="Write your detailed content here... (minimum 10 characters)"
                                required
                            >{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                Markdown and basic HTML are supported
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="featured_image" class="form-label">🖼️ Featured Image</label>
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
                            <small class="form-text text-muted">Max 2MB. Supported formats: JPG, PNG, GIF.</small>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">🏷️ Tags</label>
                            <input
                                type="text"
                                class="form-control @error('tags') is-invalid @enderror"
                                id="tags-input"
                                placeholder="Enter tags separated by commas (e.g. laravel, php, webdev)"
                                value="{{ old('tags') ? implode(', ', (array) old('tags')) : '' }}"
                            >
                            <div id="tags-hidden-inputs"></div>
                            @error('tags')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Max 10 tags, each up to 50 characters.</small>
                        </div>

                        @if($boards->isNotEmpty())
                            <div class="mb-3">
                                <label for="board_id" class="form-label">📋 Board</label>
                                <select class="form-select @error('board_id') is-invalid @enderror" id="board_id" name="board_id">
                                    <option value="">-- No Board --</option>
                                    @foreach($boards as $board)
                                        <option value="{{ $board->id }}" {{ old('board_id') == $board->id ? 'selected' : '' }}>
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
                                <option value="published" selected>
                                    📢 Published - Visible to everyone
                                </option>
                                <option value="draft">
                                    📝 Draft - Only visible to you
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                ✅ Create Post
                            </button>
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-lg">
                                ❌ Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">💡 Tips for Good Posts</h5>
                    <ul class="mb-0">
                        <li>Use clear and descriptive titles</li>
                        <li>Organize content with paragraphs and line breaks</li>
                        <li>Be respectful and professional</li>
                        <li>You can edit or delete your posts anytime</li>
                        <li>Drafts are only visible to you</li>
                    </ul>
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
