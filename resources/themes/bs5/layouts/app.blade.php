<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', site_name()) | {{ site_name() }}</title>
    <meta name="description" content="@yield('meta_description', site_tagline())">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="robots" content="{{ setting('meta_robots', 'index,follow') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', site_name())">
    <meta property="og:description" content="@yield('og_description', site_tagline())">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #0d6efd;
            --cms-dark: #1a1a2e;
            --cms-card-shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        body { background: #f8f9fc; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .navbar-brand { font-weight: 700; font-size: 1.4rem; letter-spacing: -.5px; }
        .navbar { box-shadow: 0 1px 6px rgba(0,0,0,.08); }
        .card { border: 0; box-shadow: var(--cms-card-shadow); border-radius: .75rem; overflow: hidden; }
        .card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.12); transition: box-shadow .2s; }
        .badge-category { font-size: .7rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; }
        .post-meta { font-size: .82rem; color: #6c757d; }
        .post-meta i { color: var(--bs-primary); }
        .sidebar-widget { border-radius: .75rem; overflow: hidden; margin-bottom: 1.5rem; }
        .widget-title { font-size: 1rem; font-weight: 700; letter-spacing: -.2px; padding: 1rem 1.25rem; background: #fff; border-bottom: 2px solid var(--bs-primary); margin: 0; }
        .widget-body { background: #fff; padding: 1.25rem; }
        .hero-section { background: linear-gradient(135deg, var(--cms-dark) 0%, #16213e 60%, #0f3460 100%); color: #fff; min-height: 420px; display: flex; align-items: flex-end; position: relative; overflow: hidden; }
        .hero-section .hero-img { position: absolute; inset: 0; object-fit: cover; width: 100%; height: 100%; opacity: .45; }
        .hero-section .hero-content { position: relative; z-index: 2; padding: 2.5rem; }
        .hero-section .hero-badge { background: var(--bs-primary); font-size: .7rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: .3rem .75rem; border-radius: 20px; display: inline-block; margin-bottom: .75rem; }
        .post-thumbnail { width: 100%; height: 200px; object-fit: cover; }
        .post-thumbnail-sm { width: 80px; height: 80px; object-fit: cover; border-radius: .5rem; flex-shrink: 0; }
        .footer-dark { background: #1a1a2e; color: #adb5bd; }
        .footer-dark a { color: #adb5bd; text-decoration: none; }
        .footer-dark a:hover { color: #fff; }
        .footer-dark .footer-brand { color: #fff; font-weight: 700; font-size: 1.3rem; }
        .footer-dark hr { border-color: rgba(255,255,255,.1); }
        .search-form .form-control { border-right: 0; }
        .search-form .btn { border-left: 0; }
        .comment-bubble { background: #fff; border-radius: .75rem; padding: 1.25rem; box-shadow: var(--cms-card-shadow); }
        .comment-bubble.reply { background: #f8f9fc; margin-left: 3.5rem; }
        .avatar-sm { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .avatar-md { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .tag-cloud a { display: inline-block; margin: .2rem; padding: .25rem .7rem; background: #f1f3f5; color: #495057; border-radius: 20px; font-size: .78rem; text-decoration: none; transition: all .15s; }
        .tag-cloud a:hover { background: var(--bs-primary); color: #fff; }
        .breadcrumb-nav { background: #fff; border-bottom: 1px solid #e9ecef; }
        .share-btn { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: .85rem; }
        .toc-sidebar { position: sticky; top: 80px; }
        /* Pagination */
        .pagination .page-link { border-radius: .5rem !important; margin: 0 2px; }
    </style>

    @stack('styles')
    @php do_action('wp_head') @endphp

    @if(setting('google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ setting('google_analytics_id') }}');</script>
    @endif
</head>
<body>
@php do_action('wp_body_open') @endphp

{{-- ===== TOP BAR ===== --}}
<div class="d-none d-md-block bg-dark text-white py-1 small">
    <div class="container d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-calendar-days me-1 text-primary"></i>{{ now()->format('l, F j, Y') }}</span>
        <span>
            @auth
                <i class="fa-solid fa-circle-user me-1"></i>{{ auth()->user()->display_name }}
                &nbsp;|&nbsp;
                @if(auth()->user()->canAccessPanel(\Filament\Facades\Filament::getDefaultPanel()))
                    <a href="/admin" class="text-primary text-decoration-none me-2"><i class="fa-solid fa-gauge me-1"></i>Admin</a>
                @endif
                <form method="POST" action="/logout" class="d-inline">@csrf
                    <button class="btn btn-link btn-sm text-white p-0 text-decoration-none"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</button>
                </form>
            @else
                <a href="/login" class="text-white text-decoration-none me-2"><i class="fa-solid fa-right-to-bracket me-1"></i>Login</a>
                <a href="/register" class="text-primary text-decoration-none"><i class="fa-solid fa-user-plus me-1"></i>Register</a>
            @endauth
        </span>
    </div>
</div>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary" href="/">
            <i class="fa-solid fa-layer-group me-2"></i>{{ site_name() }}
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @php $primaryMenu = get_nav_menu('primary') @endphp
                @if($primaryMenu)
                    @foreach($primaryMenu->items as $item)
                        <li class="nav-item{{ $item->children->count() ? ' dropdown' : '' }}">
                            @if($item->children->count())
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ $item->title }}</a>
                                <ul class="dropdown-menu">
                                    @foreach($item->children as $child)
                                        <li><a class="dropdown-item" href="{{ $child->resolved_url }}" target="{{ $child->target }}">{{ $child->title }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                <a class="nav-link {{ $item->css_class }}" href="{{ $item->resolved_url }}" target="{{ $item->target }}">{{ $item->title }}</a>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                @endif
            </ul>

            {{-- Search --}}
            <form action="/search" method="GET" class="d-flex search-form me-2">
                <input class="form-control form-control-sm" type="search" name="q" placeholder="Search..." value="{{ request('q') }}">
                <button class="btn btn-outline-primary btn-sm" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            @guest
                <a href="/login" class="btn btn-outline-primary btn-sm me-1">Login</a>
                <a href="/register" class="btn btn-primary btn-sm">Register</a>
            @endguest
        </div>
    </div>
</nav>

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show m-0 rounded-0 border-0" role="alert">
    <div class="container"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show m-0 rounded-0 border-0" role="alert">
    <div class="container"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
</div>
@endif

{{-- ===== CONTENT ===== --}}
<main>
    @yield('content')
</main>

{{-- ===== FOOTER ===== --}}
<footer class="footer-dark mt-5 pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="footer-brand mb-2"><i class="fa-solid fa-layer-group me-2 text-primary"></i>{{ site_name() }}</div>
                <p class="small mb-3">{{ site_tagline() }}</p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-secondary btn-sm share-btn"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm share-btn"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm share-btn"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm share-btn"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                @php echo dynamic_sidebar('footer-1') @endphp
            </div>
            <div class="col-md-4">
                @php echo dynamic_sidebar('footer-2') @endphp
            </div>
        </div>

        @php $footerMenu = get_nav_menu('footer') @endphp
        @if($footerMenu)
        <hr>
        <div class="d-flex flex-wrap gap-3 small">
            @foreach($footerMenu->items as $item)
                <a href="{{ $item->resolved_url }}">{{ $item->title }}</a>
            @endforeach
        </div>
        @endif

        <hr>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-muted">
            <span>&copy; {{ date('Y') }} {{ site_name() }}. All rights reserved.</span>
            <span>Powered by <a href="#" class="text-primary text-decoration-none">Laravel CMS</a></span>
        </div>
    </div>
</footer>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
@php do_action('wp_footer') @endphp
</body>
</html>
