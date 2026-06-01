{{-- ========== General Navigation ========== --}}
<div class="sidebar-section-title">General</div>
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link" href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : '/' }}">🏠 Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('posts.index') }}">📰 Posts</a>
    </li>
    @if(\Illuminate\Support\Facades\Route::has('search.index'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('search.index') }}">🔍 Search</a>
    </li>
    @endif
    @if(\Illuminate\Support\Facades\Route::has('groups.index'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('groups.index') }}">👥 Groups</a>
    </li>
    @endif
    @if(\Illuminate\Support\Facades\Route::has('categories.index'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">🗂️ Categories</a>
    </li>
    @endif
</ul>

@auth
    <hr>
    {{-- ========== Authenticated User Section ========== --}}
    <div class="sidebar-section-title">My Account</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('posts.create') }}">✍️ Create Post</a>
        </li>
        @if(\Illuminate\Support\Facades\Route::has('profile.show'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.show', auth()->id()) }}">👤 Profile</a>
        </li>
        @endif
        @if(\Illuminate\Support\Facades\Route::has('my.posts.index'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('my.posts.index') }}">📝 My Posts</a>
        </li>
        @endif
        @if(\Illuminate\Support\Facades\Route::has('my.comments.index'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('my.comments.index') }}">💬 My Comments</a>
        </li>
        @endif
        @if(\Illuminate\Support\Facades\Route::has('my.groups.index'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('my.groups.index') }}">👥 My Groups</a>
        </li>
        @endif
        @if(\Illuminate\Support\Facades\Route::has('notifications.index'))
        <li class="nav-item">
            <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                🔔 Notifications
                @php
                    try {
                        $navUnreadCount = app(\App\Services\NotificationService::class)->getUnreadCount(auth()->id());
                    } catch (\Exception $e) {
                        $navUnreadCount = 0;
                    }
                @endphp
                @if($navUnreadCount > 0)
                    <span class="badge bg-danger ms-1" style="font-size:0.65rem;">
                        {{ $navUnreadCount > 99 ? '99+' : $navUnreadCount }}
                    </span>
                @endif
            </a>
        </li>
        @endif
    </ul>

    {{-- ========== Moderator Section ========== --}}
    @if(auth()->user()->isModerator())
        <hr>
        <div class="sidebar-section-title">Moderation</div>
        <ul class="nav flex-column">
            @if(\Illuminate\Support\Facades\Route::has('admin.reports.index'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.reports.index') }}">🚩 Reports</a>
            </li>
            @endif
        </ul>
    @endif

    {{-- ========== Admin Section ========== --}}
    @if(auth()->user()->isAdmin())
        <hr>
        <div class="sidebar-section-title">Admin</div>
        <ul class="nav flex-column">
            @if(\Illuminate\Support\Facades\Route::has('admin.dashboard'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            </li>
            @endif
            @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">👤 Manage Users</a>
            </li>
            @endif
            @if(\Illuminate\Support\Facades\Route::has('admin.categories.index'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">🗂️ Manage Categories</a>
            </li>
            @endif
            @if(\Illuminate\Support\Facades\Route::has('admin.groups.index'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.groups.index') }}">👥 Manage Groups</a>
            </li>
            @endif
            @if(\Illuminate\Support\Facades\Route::has('admin.reports.index'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.reports.index') }}">🚩 Reports</a>
            </li>
            @endif
            @if(\Illuminate\Support\Facades\Route::has('admin.stats.index'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.stats.index') }}">📈 Stats</a>
            </li>
            @endif
        </ul>
    @endif
@endauth

@guest
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('auth.login') }}">🔐 Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('auth.register') }}">📝 Register</a>
        </li>
    </ul>
@endguest
