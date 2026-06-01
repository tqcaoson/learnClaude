@extends('layouts.app')

@section('title', 'My Groups - Community Forum')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>👥 My Groups</h3>
    <a href="{{ route('groups.create') }}" class="btn btn-primary">➕ Create Group</a>
</div>

@if ($memberships->isEmpty())
    <div class="text-center py-5">
        <p class="text-muted fs-5">You have not joined any groups yet.</p>
        <a href="{{ route('groups.index') }}" class="btn btn-outline-primary">Browse Groups</a>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach ($memberships as $membership)
            @php $group = $membership->group; @endphp
            @if ($group)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $group->getAvatarUrl() }}" alt="{{ $group->name }}" class="rounded-circle me-3" width="48" height="48" style="object-fit: cover;">
                                <div>
                                    <h5 class="mb-0">
                                        <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">{{ $group->name }}</a>
                                    </h5>
                                    @if ($membership->role === 'owner')
                                        <span class="badge bg-warning text-dark">👑 Owner</span>
                                    @elseif ($membership->role === 'moderator')
                                        <span class="badge bg-info">🛡️ Moderator</span>
                                    @else
                                        <span class="badge bg-secondary">Member</span>
                                    @endif
                                </div>
                            </div>
                            @if ($group->description)
                                <p class="text-muted small">{{ Str::limit($group->description, 80) }}</p>
                            @endif
                            <small class="text-muted">👤 {{ number_format($group->members_count) }} members &bull; Joined {{ $membership->joined_at->format('M j, Y') }}</small>
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-between">
                            <a href="{{ route('groups.show', $group->slug) }}" class="btn btn-sm btn-outline-primary">View</a>
                            @if ($membership->role !== 'owner')
                                <form method="POST" action="{{ route('groups.leave', $group->slug) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Leave this group?')">Leave</button>
                                </form>
                            @else
                                <a href="{{ route('groups.edit', $group->slug) }}" class="btn btn-sm btn-outline-secondary">Manage</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
@endsection
