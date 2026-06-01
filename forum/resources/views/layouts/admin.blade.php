<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Community Forum</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- AdminLTE 4 --}}
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css" rel="stylesheet">
    <style>
        .app-sidebar .nav-link.active,
        .app-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1) !important;
            border-radius: 6px;
        }
        .small-box .inner p { font-size: 0.9rem; }
    </style>
    @yield('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    {{-- ===== NAVBAR TOP ===== --}}
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list fs-5"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="bi bi-house me-1"></i>Forum
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                {{-- Notifications --}}
                @if(\Illuminate\Support\Facades\Route::has('notifications.index'))
                <li class="nav-item dropdown">
                    <a class="nav-link" href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell fs-5"></i>
                        @php
                            try {
                                $adminUnread = app(\App\Services\NotificationService::class)->getUnreadCount(auth()->id());
                            } catch (\Exception $e) { $adminUnread = 0; }
                        @endphp
                        @if($adminUnread > 0)
                            <span class="navbar-badge badge text-bg-danger">{{ $adminUnread > 99 ? '99+' : $adminUnread }}</span>
                        @endif
                    </a>
                </li>
                @endif

                {{-- User menu --}}
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white me-1"
                             style="width:30px;height:30px;font-size:0.75rem;font-weight:700;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                        </div>
                        <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header bg-primary text-white py-3 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white text-primary mb-2"
                                 style="width:56px;height:56px;font-size:1.2rem;font-weight:700;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                            </div>
                            <p class="mb-0 fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <small class="opacity-75">{{ auth()->user()->role ?? 'admin' }}</small>
                        </li>
                        <li class="user-footer d-flex gap-2 p-2">
                            @if(\Illuminate\Support\Facades\Route::has('profile.show'))
                            <a href="{{ route('profile.show', auth()->id()) }}" class="btn btn-sm btn-outline-secondary flex-fill">
                                <i class="bi bi-person me-1"></i>Profile
                            </a>
                            @endif
                            <form method="POST" action="{{ route('auth.logout') }}" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="app-sidebar bg-dark navbar-dark shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <span class="brand-text fw-bold fs-5">
                    <i class="bi bi-speedometer2 me-2 text-primary"></i>AdminPanel
                </span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

                    <li class="nav-header text-uppercase" style="font-size:0.68rem;letter-spacing:.1em;color:#6c757d;padding:.75rem 1rem .25rem;">
                        Dashboard
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase" style="font-size:0.68rem;letter-spacing:.1em;color:#6c757d;padding:.75rem 1rem .25rem;">
                        Management
                    </li>
                    @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Route::has('admin.categories.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Route::has('admin.groups.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.groups.index') }}" class="nav-link {{ request()->routeIs('admin.groups.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-collection"></i>
                            <p>Groups</p>
                        </a>
                    </li>
                    @endif

                    <li class="nav-header text-uppercase" style="font-size:0.68rem;letter-spacing:.1em;color:#6c757d;padding:.75rem 1rem .25rem;">
                        Moderation
                    </li>
                    @if(\Illuminate\Support\Facades\Route::has('admin.reports.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-flag"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Route::has('admin.stats.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.stats.index') }}" class="nav-link {{ request()->routeIs('admin.stats.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bar-chart-line"></i>
                            <p>Statistics</p>
                        </a>
                    </li>
                    @endif

                    <li class="nav-header text-uppercase" style="font-size:0.68rem;letter-spacing:.1em;color:#6c757d;padding:.75rem 1rem .25rem;">
                        Forum
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('posts.index') }}" class="nav-link">
                            <i class="nav-icon bi bi-file-text"></i>
                            <p>View Posts</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="nav-icon bi bi-box-arrow-left"></i>
                            <p>Back to Forum</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">@yield('page-title', 'Dashboard')</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- Flash messages --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong><i class="bi bi-x-circle me-1"></i>Errors:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <i class="bi bi-x-circle me-1"></i>{{ session('error') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ session('warning') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <strong>Community Forum</strong> &copy; {{ date('Y') }}. Admin Panel.
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
@yield('scripts')
<script>
    document.querySelectorAll('.alert:not([id])').forEach(a => {
        setTimeout(() => { try { new bootstrap.Alert(a).close(); } catch(e){} }, 6000);
    });
    document.querySelectorAll('form').forEach(f => {
        f.addEventListener('submit', () => {
            f.querySelectorAll('button[type=submit]').forEach(b => {
                b.disabled = true;
            });
        });
    });
</script>
</body>
</html>
