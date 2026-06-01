@extends('layouts.app')

@section('title', $group->name . ' - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>👥 {{ $group->name }}</h3>
        <a href="{{ route('admin.groups.index') }}" class="text-decoration-none text-muted small">← Back to groups</a>
    </div>
    <div class="d-flex gap-2">
        @if ($group->isSuspended())
            <form method="POST" action="{{ route('admin.groups.activate', $group->slug) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-success">Activate</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.groups.suspend', $group->slug) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-warning">Suspend</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.groups.destroy', $group->slug) }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this group permanently?')">Delete</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">Group Info</div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ $group->getAvatarUrl() }}" alt="{{ $group->name }}" class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                </div>
                <table class="table table-sm">
                    <tr><th>Slug</th><td>{{ $group->slug }}</td></tr>
                    <tr><th>Type</th><td>
                        <span class="badge {{ $group->isPublic() ? 'bg-success' : 'bg-warning text-dark' }}">{{ $group->type }}</span>
                    </td></tr>
                    <tr><th>Status</th><td>
                        <span class="badge {{ $group->isSuspended() ? 'bg-danger' : 'bg-success' }}">{{ $group->status }}</span>
                    </td></tr>
                    <tr><th>Owner</th><td>{{ $group->owner->name ?? 'Unknown' }}</td></tr>
                    <tr><th>Members</th><td>{{ $group->members_count }}</td></tr>
                    <tr><th>Posts</th><td>{{ $group->posts_count }}</td></tr>
                    <tr><th>Created</th><td>{{ $group->created_at->format('M j, Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Members ({{ $group->groupMembers->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($group->groupMembers as $member)
                            <tr>
                                <td>{{ $member->user->name ?? 'Unknown' }}</td>
                                <td>
                                    @if ($member->role === 'owner')
                                        <span class="badge bg-warning text-dark">Owner</span>
                                    @elseif ($member->role === 'moderator')
                                        <span class="badge bg-info">Moderator</span>
                                    @else
                                        <span class="badge bg-secondary">Member</span>
                                    @endif
                                </td>
                                <td><small>{{ $member->joined_at->format('M j, Y') }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
