@extends('layouts.admin')

@section('title', 'Groups Management - Admin')
@section('page-title', 'Groups Management - Admin')

@section('content')
<h3 class="mb-4">👥 Groups Management</h3>

<form method="GET" action="{{ route('admin.groups.index') }}" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Search by name or slug..."
               value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="type" class="form-select">
            <option value="">All Types</option>
            <option value="public" {{ request('type') === 'public' ? 'selected' : '' }}>Public</option>
            <option value="private" {{ request('type') === 'private' ? 'selected' : '' }}>Private</option>
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

@if ($groups->isEmpty())
    <p class="text-muted text-center py-4">No groups found.</p>
@else
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Members</th>
                        <th>Posts</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td>
                                <a href="{{ route('admin.groups.show', $group->slug) }}" class="text-decoration-none fw-semibold">{{ $group->name }}</a>
                                <br><small class="text-muted">{{ $group->slug }}</small>
                            </td>
                            <td>{{ $group->owner->name ?? 'Unknown' }}</td>
                            <td>
                                <span class="badge {{ $group->isPublic() ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $group->isPublic() ? 'Public' : 'Private' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $group->isSuspended() ? 'bg-danger' : 'bg-success' }}">
                                    {{ $group->isSuspended() ? 'Suspended' : 'Active' }}
                                </span>
                            </td>
                            <td>{{ number_format($group->members_count) }}</td>
                            <td>{{ number_format($group->posts_count) }}</td>
                            <td><small>{{ $group->created_at->format('M j, Y') }}</small></td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    @if ($group->isSuspended())
                                        <form method="POST" action="{{ route('admin.groups.activate', $group->slug) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.groups.suspend', $group->slug) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning">Suspend</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.groups.destroy', $group->slug) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete group {{ addslashes($group->name) }}? This cannot be undone.')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $groups->withQueryString()->links() }}
    </div>
@endif
@endsection
