@extends('layouts.app')

@section('title', $group->name . ' - Members')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>👥 {{ $group->name }} - Members</h3>
        <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none text-muted small">← Back to group</a>
    </div>
    <span class="badge bg-secondary fs-6">{{ $group->members_count }} members</span>
</div>

@auth
    @if (auth()->user()->isAdmin() || in_array($group->getMemberRole(auth()->user()), ['owner', 'moderator']))
        <div class="card mb-4">
            <div class="card-header">📨 Invite Member</div>
            <div class="card-body">
                <form method="POST" action="{{ route('groups.invite', $group->slug) }}">
                    @csrf
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                        <button type="submit" class="btn btn-primary">Send Invite</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Member</th>
                    <th>Role</th>
                    <th>Joined</th>
                    @auth
                        @if (auth()->user()->isAdmin() || in_array($group->getMemberRole(auth()->user()), ['owner', 'moderator']))
                            <th>Actions</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $member->user->getAvatarUrl() }}" alt="{{ $member->user->name }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                {{ $member->user->name }}
                            </div>
                        </td>
                        <td>
                            @if ($member->role === 'owner')
                                <span class="badge bg-warning text-dark">👑 Owner</span>
                            @elseif ($member->role === 'moderator')
                                <span class="badge bg-info">🛡️ Moderator</span>
                            @else
                                <span class="badge bg-secondary">Member</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $member->joined_at->format('M j, Y') }}</small>
                        </td>
                        @auth
                            @if (auth()->user()->isAdmin() || in_array($group->getMemberRole(auth()->user()), ['owner', 'moderator']))
                                <td>
                                    @if ($member->role !== 'owner' && $member->user_id !== auth()->id())
                                        <div class="d-flex gap-2">
                                            <form method="POST" action="{{ route('groups.members.role', [$group->slug, $member->user_id]) }}">
                                                @csrf @method('PATCH')
                                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="member" {{ $member->role === 'member' ? 'selected' : '' }}>Member</option>
                                                    <option value="moderator" {{ $member->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                                </select>
                                            </form>
                                            <form method="POST" action="{{ route('groups.members.kick', [$group->slug, $member->user_id]) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this member?')">Kick</button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            @endif
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $members->links() }}
</div>
@endsection
