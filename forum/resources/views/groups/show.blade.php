@extends('layouts.app')

@section('title', $group->name . ' - Community Forum')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-start">
            <img src="{{ $group->getAvatarUrl() }}" alt="{{ $group->name }}" class="rounded-circle me-4" width="80" height="80" style="object-fit: cover;">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="mb-1">{{ $group->name }}</h2>
                        <div class="mb-2">
                            <span class="badge {{ $group->isPublic() ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $group->isPublic() ? '🌐 Public' : '🔒 Private' }}
                            </span>
                            @if ($group->isSuspended())
                                <span class="badge bg-danger">⛔ Suspended</span>
                            @endif
                        </div>
                        @if ($group->description)
                            <p class="text-muted">{{ $group->description }}</p>
                        @endif
                        <small class="text-muted">
                            👤 {{ number_format($group->members_count) }} members &bull;
                            Owner: {{ $group->owner->name ?? 'Unknown' }}
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        @auth
                            @if ($group->isOwner(auth()->user()))
                                <a href="{{ route('groups.edit', $group->slug) }}" class="btn btn-sm btn-outline-secondary">✏️ Edit</a>
                            @elseif ($group->isMember(auth()->user()))
                                <form method="POST" action="{{ route('groups.leave', $group->slug) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Leave this group?')">Leave</button>
                                </form>
                            @elseif ($group->isPublic())
                                <form method="POST" action="{{ route('groups.join', $group->slug) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Join Group</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="groupTab">
    <li class="nav-item">
        <a class="nav-link active" href="#posts" data-bs-toggle="tab">📰 Posts ({{ $group->posts_count }})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('groups.members', $group->slug) }}">👥 Members ({{ $group->members_count }})</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="posts">
        @if ($posts->isEmpty())
            <p class="text-muted text-center py-4">No posts in this group yet.</p>
        @else
            @foreach ($posts as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none">{{ $post->title }}</a>
                        </h5>
                        <p class="card-text text-muted small">{{ Str::limit($post->content, 150) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">by {{ $post->user->name ?? 'Unknown' }}</small>
                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                        </div>

                        @auth
                            @if (auth()->user()->isModeratorOrAdmin())
                                <div class="mt-2 d-flex gap-2">
                                    @if (!$post->hidden_at)
                                        <form method="POST" action="{{ route('admin.posts.hide', $post->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">Hide</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.posts.show', $post->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success">Show</button>
                                        </form>
                                    @endif
                                    @if (!$post->is_locked)
                                        <form method="POST" action="{{ route('admin.posts.lock', $post->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Lock</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.posts.unlock', $post->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Unlock</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
            {{ $posts->links() }}
        @endif
    </div>
</div>
@endsection
