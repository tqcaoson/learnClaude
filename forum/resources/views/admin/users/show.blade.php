@extends('layouts.admin')

@section('title', 'Admin - User: ' . $user->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">← Back to Users</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                <h5>{{ $user->name }}</h5>
                <p class="text-muted small mb-2">{{ $user->email }}</p>

                @if ($user->isAdmin())
                    <span class="badge bg-danger mb-2">Admin</span>
                @elseif ($user->isModerator())
                    <span class="badge bg-warning text-dark mb-2">Moderator</span>
                @else
                    <span class="badge bg-secondary mb-2">User</span>
                @endif

                @if ($user->isBanned())
                    <br><span class="badge bg-danger">Banned</span>
                @else
                    <br><span class="badge bg-success">Active</span>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0">📊 Stats</h6></div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 mb-0">{{ $postsCount }}</div>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 mb-0">{{ $commentsCount }}</div>
                        <small class="text-muted">Comments</small>
                    </div>
                </div>
                <hr>
                <p class="mb-1 small"><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                <p class="mb-0 small"><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Actions --}}
        @if (auth()->id() !== $user->id)
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">⚙️ Actions</h6></div>
                <div class="card-body d-flex gap-3 flex-wrap align-items-center">
                    <form method="POST" action="{{ route('admin.users.role', ['id' => $user->id]) }}" class="d-flex gap-2 align-items-center">
                        @csrf
                        @method('PATCH')
                        <label class="mb-0 fw-semibold">Role:</label>
                        <select name="role" class="form-select form-select-sm" style="width: auto;">
                            <option value="user" {{ ($user->role->value ?? $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            <option value="moderator" {{ ($user->role->value ?? $user->role) === 'moderator' ? 'selected' : '' }}>Moderator</option>
                            <option value="admin" {{ ($user->role->value ?? $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update Role</button>
                    </form>

                    @if ($user->isBanned())
                        <form method="POST" action="{{ route('admin.users.activate', ['id' => $user->id]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Activate User</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.ban', ['id' => $user->id]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to ban this user?')">Ban User</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        {{-- Recent Posts --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0">📰 Recent Posts</h6></div>
            <div class="card-body">
                @forelse ($recentPosts as $post)
                    <div class="mb-3 pb-3 border-bottom">
                        <h6 class="mb-1">
                            <a href="{{ route('posts.show', ['id' => $post->id]) }}" class="text-decoration-none">{{ $post->title }}</a>
                        </h6>
                        <p class="text-muted small mb-0">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-muted mb-0">No posts yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
