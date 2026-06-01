<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Community Forum')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --sidebar-width: 250px;
            --topbar-height: 52px;
        }

        body {
            background-color: var(--light-bg);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Top bar */
        .top-bar {
            height: var(--topbar-height);
            background: linear-gradient(90deg, #1a1d20 0%, #343a40 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .top-bar .brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            letter-spacing: -0.5px;
            white-space: nowrap;
        }

        .top-bar .brand:hover {
            color: #90caf9;
        }

        .top-bar .user-area {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .top-bar .user-area .user-name {
            color: #adb5bd;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .top-bar .btn-logout {
            font-size: 0.8rem;
            padding: 0.25rem 0.65rem;
            white-space: nowrap;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            bottom: 0;
            background: #fff;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            z-index: 1020;
            padding: 1rem 0 2rem 0;
        }

        .sidebar .sidebar-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #adb5bd;
            padding: 1rem 1rem 0.35rem 1rem;
            margin-top: 0.25rem;
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 0.45rem 1rem;
            border-radius: 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: background 0.15s, color 0.15s;
        }

        .sidebar .nav-link:hover {
            background: #f0f4ff;
            color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            background: #e8f0fe;
            color: var(--primary-color);
            font-weight: 600;
            border-right: 3px solid var(--primary-color);
        }

        .sidebar hr {
            margin: 0.4rem 1rem;
            border-color: #e9ecef;
        }

        /* Wrapper layout */
        .page-wrapper {
            display: flex;
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }

        .sidebar-placeholder {
            width: var(--sidebar-width);
            flex-shrink: 0;
        }

        .main-area {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            padding: 1.75rem 1.5rem;
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-16px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .alert-success { background-color: #d4edda; color: #155724; border-left: 4px solid var(--success-color); }
        .alert-danger  { background-color: #f8d7da; color: #721c24; border-left: 4px solid var(--danger-color); }
        .alert-warning { background-color: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .alert-info    { background-color: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        }

        .btn-primary { background-color: var(--primary-color); }
        .btn-primary:hover { background-color: #0056b3; }

        .form-control, .form-select {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        .post-item {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
        }

        footer {
            background: linear-gradient(135deg, #343a40 0%, #23272b 100%);
            color: white;
            text-align: center;
            padding: 1.5rem 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        footer p { margin: 0; font-size: 0.9rem; opacity: 0.85; }

        /* Mobile sidebar via offcanvas */
        @media (max-width: 767.98px) {
            .sidebar { display: none; }
            .sidebar-placeholder { display: none; }
            .main-content { padding: 1rem; }
        }

        .is-loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>

    {{-- ============ TOP BAR ============ --}}
    <div class="top-bar">
        {{-- Mobile toggle --}}
        <button class="btn btn-sm btn-outline-secondary d-md-none me-2 text-white border-secondary"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar"
                aria-controls="mobileSidebar"
                style="padding:0.25rem 0.5rem;">
            &#9776;
        </button>

        <a class="brand" href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : '/' }}">📚 Community Forum</a>

        <div class="user-area">
            @auth
                <span class="user-name d-none d-sm-inline">👤 {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('auth.logout') }}" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light btn-logout">🚪 Logout</button>
                </form>
            @else
                <a href="{{ route('auth.login') }}" class="btn btn-sm btn-outline-light btn-logout">🔐 Login</a>
                <a href="{{ route('auth.register') }}" class="btn btn-sm btn-primary btn-logout">📝 Register</a>
            @endauth
        </div>
    </div>

    {{-- ============ MOBILE OFFCANVAS SIDEBAR ============ --}}
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar" style="width:var(--sidebar-width);top:var(--topbar-height);">
        <div class="offcanvas-header border-bottom py-2">
            <h6 class="offcanvas-title mb-0">Navigation</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('layouts.partials.sidebar-nav')
        </div>
    </div>

    {{-- ============ PAGE WRAPPER ============ --}}
    <div class="page-wrapper">

        {{-- Desktop sidebar --}}
        <div class="sidebar-placeholder d-none d-md-block">
            <nav class="sidebar" aria-label="Main navigation">
                @include('layouts.partials.sidebar-nav')
            </nav>
        </div>

        {{-- Main content --}}
        <div class="main-area">
            <div class="main-content">

                {{-- Flash messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>❌ Errors encountered:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <footer>
                <p>📚 Community Forum &copy; 2026 | Built with ❤️ using Laravel</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <script>
        // Auto-hide non-error alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            if (!alert.querySelector('.alert-danger ul')) {
                setTimeout(() => {
                    try { new bootstrap.Alert(alert).close(); } catch(e) {}
                }, 5000);
            }
        });

        // Disable submit buttons on form submission to prevent double-submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                this.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('is-loading');
                });
            });
        });

        // Mark active sidebar links
        (function() {
            const path = window.location.pathname;
            document.querySelectorAll('.sidebar .nav-link, .offcanvas .nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && href !== '#' && path.startsWith(href) && href !== '/') {
                    link.classList.add('active');
                } else if (href === '/' && path === '/') {
                    link.classList.add('active');
                }
            });
        })();
    </script>
</body>
</html>
