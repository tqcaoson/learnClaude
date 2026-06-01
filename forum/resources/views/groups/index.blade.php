@extends('layouts.app')

@section('title', 'Groups - Community Forum')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>👥 Community Groups</h2>
    @auth
        <a href="{{ route('groups.create') }}" class="btn btn-primary">➕ Create Group</a>
    @endauth
</div>

<form method="GET" action="{{ route('groups.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search groups..." value="{{ $search ?? '' }}">
        <button type="submit" class="btn btn-outline-primary">🔍 Search</button>
        @if ($search)
            <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">✕ Clear</a>
        @endif
    </div>
</form>

@if ($groups->isEmpty())
    <div class="text-center py-5">
        <p class="text-muted fs-5">No groups found. Be the first to create one!</p>
        @auth
            <a href="{{ route('groups.create') }}" class="btn btn-primary">Create a Group</a>
        @endauth
    </div>
@else
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        @foreach ($groups as $group)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $group->getAvatarUrl() }}" alt="{{ $group->name }}" class="rounded-circle me-3" width="48" height="48" style="object-fit: cover;">
                            <div>
                                <h5 class="card-title mb-0">
                                    <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">{{ $group->name }}</a>
                                </h5>
                                <span class="badge {{ $group->isPublic() ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $group->isPublic() ? '🌐 Public' : '🔒 Private' }}
                                </span>
                            </div>
                        </div>
                        @if ($group->description)
                            <p class="card-text text-muted small">{{ Str::limit($group->description, 100) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">👤 {{ number_format($group->members_count) }} members</small>
                            <small class="text-muted">by {{ $group->owner->name ?? 'Unknown' }}</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        @auth
                            @if ($group->isMember(auth()->user()))
                                <a href="{{ route('groups.show', $group->slug) }}" class="btn btn-sm btn-outline-primary w-100">View Group</a>
                            @elseif ($group->isPublic())
                                <form method="POST" action="{{ route('groups.join', $group->slug) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success w-100">Join Group</button>
                                </form>
                            @else
                                <a href="{{ route('groups.show', $group->slug) }}" class="btn btn-sm btn-outline-secondary w-100">🔒 Private</a>
                            @endif
                        @else
                            <a href="{{ route('groups.show', $group->slug) }}" class="btn btn-sm btn-outline-primary w-100">View Group</a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $groups->withQueryString()->links() }}
@endif
@endsection
