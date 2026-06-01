@extends('layouts.app')

@section('title', 'Admin - Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>👥 User Management</h2>
    <span class="text-muted">Total: {{ $users->total() }}</span>
</div>

{{-- Search --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" placeholder="Search by name or email..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">🔍 Search</button>
            </div>
            @if ($search)
                <div class="col-md-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-muted small">{{ $user->id }}</td>
                            <td>
                                <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td class="text-muted small">{{ $user->email }}</td>
                            <td>
                                @if ($user->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @elseif ($user->isModerator())
                                    <span class="badge bg-warning text-dark">Moderator</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->isBanned())
                                    <span class="badge bg-danger">Banned</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('admin.users.show', ['id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">View</a>

                                    @if (auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.role', ['id' => $user->id]) }}" class="d-flex gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="form-select form-select-sm" style="width: auto;">
                                                <option value="user" {{ ($user->role->value ?? $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                                <option value="moderator" {{ ($user->role->value ?? $user->role) === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                                <option value="admin" {{ ($user->role->value ?? $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Set</button>
                                        </form>

                                        @if ($user->isBanned())
                                            <form method="POST" action="{{ route('admin.users.activate', ['id' => $user->id]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.ban', ['id' => $user->id]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Ban this user?')">Ban</button>
                                            </form>
                                        @endif

                                        @if (!$user->isAdmin())
                                            <form method="POST" action="{{ route('admin.users.destroy', ['id' => $user->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Permanently delete this user?')">Delete</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
