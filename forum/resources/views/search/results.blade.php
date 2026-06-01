@extends('layouts.app')

@section('title', $query ? 'Search: ' . $query : 'Search')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <h2 class="mb-4">Search Posts</h2>

        {{-- Search Form --}}
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('search.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="q" class="form-label fw-semibold">Keywords</label>
                            <input
                                type="text"
                                id="q"
                                name="q"
                                class="form-control"
                                placeholder="Search posts..."
                                value="{{ $query }}"
                                maxlength="200"
                            >
                        </div>
                        <div class="col-md-3">
                            <label for="tag" class="form-label fw-semibold">Tag</label>
                            <input
                                type="text"
                                id="tag"
                                name="tag"
                                class="form-control"
                                placeholder="Tag slug"
                                value="{{ $filters['tag'] ?? '' }}"
                                maxlength="100"
                            >
                        </div>
                        <div class="col-md-3">
                            <label for="author" class="form-label fw-semibold">Author</label>
                            <input
                                type="text"
                                id="author"
                                name="author"
                                class="form-control"
                                placeholder="Author name"
                                value="{{ $filters['author'] ?? '' }}"
                                maxlength="100"
                            >
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Search
                            </button>
                            @if($query || !empty($filters['tag']) || !empty($filters['author']))
                                <a href="{{ route('search.index') }}" class="btn btn-outline-secondary ms-2">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Results --}}
        @if($query !== '')
            <p class="text-muted mb-3">
                Found <strong>{{ number_format($total) }}</strong> result(s) for
                <strong>"{{ $query }}"</strong>
                @if(!empty($filters['tag'])) in tag <em>{{ $filters['tag'] }}</em>@endif
                @if(!empty($filters['author'])) by author <em>{{ $filters['author'] }}</em>@endif
            </p>

            @if($results && $results->count() > 0)
                @foreach($results as $post)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">
                                        <a href="{{ route('posts.show', $post->slug ?? $post->id) }}" class="text-decoration-none text-dark">
                                            {{ $post->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        By <strong>{{ $post->user->name ?? 'Unknown' }}</strong>
                                        &middot;
                                        {{ $post->created_at->diffForHumans() }}
                                        @if($post->reactions_count ?? false)
                                            &middot; {{ $post->reactions_count }} reactions
                                        @endif
                                    </p>
                                    <p class="card-text text-secondary mb-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 200) }}
                                    </p>
                                    @if($post->tags && $post->tags->count() > 0)
                                        <div>
                                            @foreach($post->tags as $tag)
                                                <a href="{{ route('search.index') }}?tag={{ $tag->slug }}" class="badge bg-secondary text-decoration-none me-1">
                                                    {{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center mt-4">
                    {{ $results->appends(['q' => $query, 'tag' => $filters['tag'] ?? '', 'author' => $filters['author'] ?? ''])->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted fs-5">No posts found matching your search.</p>
                    <p class="text-muted">Try different keywords or remove filters.</p>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <p class="text-muted fs-5">Enter keywords above to search posts.</p>
            </div>
        @endif
    </div>
</div>
@endsection
