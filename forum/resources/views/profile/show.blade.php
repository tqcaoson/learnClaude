@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">

                <h4 class="card-title mb-1">{{ $user->name }}</h4>

                @if ($user->isAdmin())
                    <span class="badge bg-danger mb-2">Admin</span>
                @elseif ($user->isModerator())
                    <span class="badge bg-warning text-dark mb-2">Moderator</span>
                @endif

                @if ($user->isBanned())
                    <span class="badge bg-secondary mb-2">Banned</span>
                @endif

                <p class="text-muted small mb-2">{{ $user->email }}</p>

                @if ($user->bio)
                    <p class="card-text">{{ $user->bio }}</p>
                @endif

                @if ($user->location)
                    <p class="text-muted small mb-1">📍 {{ $user->location }}</p>
                @endif

                @if ($user->website_url)
                    <p class="mb-2">
                        <a href="{{ $user->website_url }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                            🌐 {{ $user->website_url }}
                        </a>
                    </p>
                @endif

                <p class="text-muted small mb-3">Joined {{ $user->created_at->format('M Y') }}</p>

                @auth
                    @if (auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">✏️ Edit Profile</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h5 class="mb-3">📰 Posts by {{ $user->name }}</h5>

        @forelse ($posts as $post)
            <div class="card mb-3 post-item">
                <div class="card-body">
                    <h6 class="card-title mb-1">
                        <a href="{{ route('posts.show', ['id' => $post->id]) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h6>
                    <p class="card-text text-muted small">{{ Str::limit($post->content, 150) }}</p>
                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No posts yet.</div>
        @endforelse

        {{ $posts->links() }}
    </div>
</div>
@endsection
