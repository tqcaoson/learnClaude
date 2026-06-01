<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Community Forum')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --nav-height: 56px;
            --brand-color: #3d5afe;
        }

        body {
            background: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding-top: var(--nav-height);
        }

        /* ===== TOP NAVBAR ===== */
        .main-navbar {
            height: var(--nav-height);
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1040;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            gap: 1rem;
        }

        .navbar-brand-logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--brand-color);
            text-decoration: none;
            letter-spacing: -1px;
            white-space: nowrap;
        }
        .navbar-brand-logo:hover { color: #1a237e; }

        .navbar-nav-links {
            display: flex;
            align-items: center;
            gap: 0.1rem;
            list-style: none;
            margin: 0; padding: 0;
        }
        .navbar-nav-links a {
            color: #424242;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            white-space: nowrap;
            transition: background 0.15s, color 0.15s;
        }
        .navbar-nav-links a:hover, .navbar-nav-links a.active {
            background: #ede7f6;
            color: var(--brand-color);
        }

        .navbar-search {
            flex: 1;
            max-width: 480px;
            position: relative;
        }
        .navbar-search input {
            width: 100%;
            border: 1px solid #e0e0e0;
            border-radius: 24px;
            padding: 0.4rem 1rem 0.4rem 2.4rem;
            font-size: 0.875rem;
            background: #f5f5f5;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .navbar-search input:focus {
            border-color: var(--brand-color);
            background: #fff;
        }
        .navbar-search .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9e9e9e;
            font-size: 0.9rem;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-left: auto;
            white-space: nowrap;
        }

        .btn-login {
            font-size: 0.85rem;
            padding: 0.35rem 0.9rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            color: #424242;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-login:hover { background: #f5f5f5; color: #212121; }

        .btn-register {
            font-size: 0.85rem;
            padding: 0.35rem 0.9rem;
            border-radius: 6px;
            background: #3d5afe;
            color: #fff;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-register:hover { background: #1a237e; color: #fff; }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--brand-color);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* ===== PAGE LAYOUT ===== */
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
        }

        @media (max-width: 900px) {
            .content-grid { grid-template-columns: 1fr; }
            .sidebar-right { display: none; }
            .navbar-nav-links { display: none; }
        }

        /* ===== POST LIST (left col) ===== */
        .post-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            display: flex;
            gap: 0.85rem;
            transition: box-shadow 0.15s;
        }
        .post-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.08); }

        .post-card .post-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #e8eaf6;
            color: var(--brand-color);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            flex-shrink: 0;
        }

        .post-card .post-body { flex: 1; min-width: 0; }

        .post-card .post-author-row {
            font-size: 0.78rem;
            color: #757575;
            margin-bottom: 0.25rem;
        }
        .post-card .post-author-row a { color: #424242; font-weight: 500; text-decoration: none; }
        .post-card .post-author-row a:hover { color: var(--brand-color); }

        .post-card .post-title {
            font-size: 0.975rem;
            font-weight: 600;
            color: #212121;
            text-decoration: none;
            display: block;
            margin-bottom: 0.3rem;
            line-height: 1.4;
        }
        .post-card .post-title:hover { color: var(--brand-color); }

        .post-card .post-excerpt {
            font-size: 0.82rem;
            color: #616161;
            line-height: 1.5;
            margin-bottom: 0.5rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .post-card .post-meta {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            font-size: 0.76rem;
            color: #9e9e9e;
        }

        .post-card .post-tag {
            background: #f3f4f6;
            color: #424242;
            padding: 0.15rem 0.55rem;
            border-radius: 12px;
            font-size: 0.73rem;
        }

        /* ===== VOTE COLUMN ===== */
        .post-vote {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.1rem;
            min-width: 36px;
            padding-top: 0.25rem;
        }
        .post-vote .vote-btn {
            background: none; border: none; padding: 0;
            color: #bdbdbd; font-size: 1rem; cursor: pointer;
            line-height: 1;
            transition: color 0.15s;
        }
        .post-vote .vote-btn:hover { color: #3d5afe; }
        .post-vote .vote-count {
            font-size: 0.78rem;
            font-weight: 600;
            color: #616161;
        }

        /* ===== RIGHT SIDEBAR ===== */
        .sidebar-right .sidebar-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .sidebar-right .sidebar-card-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.85rem;
            font-weight: 700;
            color: #212121;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .sidebar-right .sidebar-card-body { padding: 0.5rem 0; }
        .sidebar-right .sidebar-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            color: #424242;
            text-decoration: none;
            transition: background 0.12s;
        }
        .sidebar-right .sidebar-item:hover { background: #f5f5f5; }
        .sidebar-right .sidebar-badge {
            background: #ede7f6;
            color: var(--brand-color);
            padding: 0.1rem 0.5rem;
            border-radius: 12px;
            font-size: 0.73rem;
            font-weight: 600;
        }

        /* ===== ALERTS ===== */
        .alert {
            border-radius: 8px;
            border: none;
            font-size: 0.875rem;
        }
        .alert-success { background: #e8f5e9; color: #1b5e20; border-left: 3px solid #4caf50; }
        .alert-danger  { background: #ffebee; color: #b71c1c; border-left: 3px solid #f44336; }
        .alert-warning { background: #fff8e1; color: #e65100; border-left: 3px solid #ff9800; }
        .alert-info    { background: #e3f2fd; color: #0d47a1; border-left: 3px solid #2196f3; }

        /* ===== SECTION HEADER ===== */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e8e8e8;
        }
        .section-header h2 {
            font-size: 1rem;
            font-weight: 700;
            color: #212121;
            margin: 0;
        }
        .section-header .view-toggle {
            display: flex; gap: 0.4rem;
        }
        .section-header .view-toggle button {
            background: none; border: 1px solid #e0e0e0;
            border-radius: 4px; padding: 0.2rem 0.5rem;
            color: #757575; cursor: pointer;
            transition: background 0.12s;
        }
        .section-header .view-toggle button:hover,
        .section-header .view-toggle button.active {
            background: #ede7f6; border-color: var(--brand-color); color: var(--brand-color);
        }

        /* ===== CTA BANNER ===== */
        .cta-banner {
            background: linear-gradient(135deg, #3d5afe 0%, #1a237e 100%);
            border-radius: 8px;
            color: #fff;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            text-align: center;
        }
        .cta-banner h5 { font-size: 1rem; font-weight: 700; margin-bottom: 0.35rem; }
        .cta-banner p  { font-size: 0.82rem; margin-bottom: 0.75rem; opacity: 0.9; }

        /* ===== PAGINATION ===== */
        .pagination .page-link { color: var(--brand-color); }
        .pagination .page-item.active .page-link { background: var(--brand-color); border-color: var(--brand-color); }

        /* ===== FOOTER ===== */
        footer {
            background: #212121;
            color: #bdbdbd;
            text-align: center;
            padding: 1.25rem 1rem;
            font-size: 0.82rem;
            margin-top: 2rem;
        }

        .is-loading { opacity: 0.6; pointer-events: none; }
    </style>
    @yield('styles')
</head>
<body>

    {{-- ===== TOP NAVBAR ===== --}}
    <nav class="main-navbar">
        <a class="navbar-brand-logo" href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : '/' }}">FORUM</a>

        <ul class="navbar-nav-links">
            <li><a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">Bài Viết</a></li>
            @if(\Illuminate\Support\Facades\Route::has('groups.index'))
            <li><a href="{{ route('groups.index') }}" class="{{ request()->routeIs('groups.*') ? 'active' : '' }}">Thảo Luận</a></li>
            @endif
            @if(\Illuminate\Support\Facades\Route::has('categories.index'))
            <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Chủ Đề</a></li>
            @endif
        </ul>

        @if(\Illuminate\Support\Facades\Route::has('search.index'))
        <div class="navbar-search d-none d-md-block">
            <i class="bi bi-search search-icon"></i>
            <form action="{{ route('search.index') }}" method="GET">
                <input type="text" name="q" placeholder="Tìm kiếm..." value="{{ request('q') }}">
            </form>
        </div>
        @endif

        <div class="navbar-right">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-login" title="Admin Panel">
                        <i class="bi bi-speedometer2"></i>
                    </a>
                @endif
                @if(\Illuminate\Support\Facades\Route::has('notifications.index'))
                <a href="{{ route('notifications.index') }}" class="btn-login position-relative">
                    <i class="bi bi-bell"></i>
                    @php
                        try {
                            $topbarUnread = app(\App\Services\NotificationService::class)->getUnreadCount(auth()->id());
                        } catch (\Exception $e) { $topbarUnread = 0; }
                    @endphp
                    @if($topbarUnread > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
                            {{ $topbarUnread > 99 ? '99+' : $topbarUnread }}
                        </span>
                    @endif
                </a>
                @endif
                <div class="dropdown">
                    <button class="btn p-0 border-0 bg-transparent d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <span class="d-none d-lg-inline" style="font-size:0.85rem;color:#424242;">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:0.875rem;min-width:180px;">
                        @if(\Illuminate\Support\Facades\Route::has('profile.show'))
                        <li><a class="dropdown-item" href="{{ route('profile.show', auth()->id()) }}"><i class="bi bi-person me-2"></i>Trang cá nhân</a></li>
                        @endif
                        @if(\Illuminate\Support\Facades\Route::has('my.posts.index'))
                        <li><a class="dropdown-item" href="{{ route('my.posts.index') }}"><i class="bi bi-file-text me-2"></i>Bài viết của tôi</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('posts.create') }}"><i class="bi bi-pencil-square me-2"></i>Viết bài mới</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('auth.login') }}" class="btn-login">Đăng nhập</a>
                <a href="{{ route('auth.register') }}" class="btn-register">Đăng ký</a>
            @endauth
        </div>
    </nav>

    {{-- ===== PAGE ===== --}}
    <main class="page-container">

        {{-- Flash messages --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <strong>Có lỗi xảy ra:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-3">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>Community Forum &copy; {{ date('Y') }} &mdash; Nền tảng chia sẻ kiến thức</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <script>
        document.querySelectorAll('.alert:not(.alert-danger)').forEach(a => {
            setTimeout(() => { try { new bootstrap.Alert(a).close(); } catch(e){} }, 5000);
        });
        document.querySelectorAll('form').forEach(f => {
            f.addEventListener('submit', () => {
                f.querySelectorAll('button[type=submit]').forEach(b => {
                    b.disabled = true; b.classList.add('is-loading');
                });
            });
        });
    </script>
</body>
</html>
