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

    {{-- Tailwind CSS CDN (replace with compiled in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')

    @php do_action('wp_head') @endphp

    @if(setting('google_analytics_id'))
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ setting('google_analytics_id') }}');
    </script>
    @endif
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    @php do_action('wp_body_open') @endphp

    {{-- Navigation --}}
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-blue-600">
                        {{ site_name() }}
                    </a>
                </div>

                {{-- Primary Navigation --}}
                <div class="hidden md:flex items-center space-x-1">
                    @php
                        $primaryMenu = get_nav_menu('primary');
                    @endphp
                    @if($primaryMenu)
                        @foreach($primaryMenu->items as $item)
                            <a href="{{ $item->resolved_url }}"
                               target="{{ $item->target }}"
                               class="px-3 py-2 text-sm text-gray-700 hover:text-blue-600 rounded-md transition-colors {{ $item->css_class }}">
                                {{ $item->title }}
                            </a>
                            @if($item->children->count())
                                {{-- Dropdown for sub-items --}}
                            @endif
                        @endforeach
                    @else
                        <a href="/" class="px-3 py-2 text-sm text-gray-700 hover:text-blue-600">Home</a>
                        <a href="/blog" class="px-3 py-2 text-sm text-gray-700 hover:text-blue-600">Blog</a>
                    @endif

                    {{-- Search --}}
                    <form action="/search" method="GET" class="flex items-center ml-4">
                        <input type="search" name="q" placeholder="Search..."
                               class="px-3 py-1.5 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                               value="{{ request('q') }}">
                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-r-md hover:bg-blue-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Auth Links --}}
                    @auth
                        <div class="relative ml-4 flex items-center space-x-2">
                            @if(auth()->user()->canAccessPanel(\Filament\Facades\Filament::getDefaultPanel()))
                                <a href="/admin" class="text-sm text-blue-600 hover:underline">Admin</a>
                            @endif
                            <a href="/profile" class="text-sm text-gray-700 hover:text-blue-600">
                                {{ auth()->user()->display_name }}
                            </a>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-red-600">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="/login" class="ml-4 text-sm text-gray-700 hover:text-blue-600">Login</a>
                        <a href="/register" class="ml-2 px-4 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Register</a>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="p-2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white px-4 py-2">
            @if($primaryMenu ?? null)
                @foreach($primaryMenu->items as $item)
                    <a href="{{ $item->resolved_url }}" class="block py-2 text-sm text-gray-700">{{ $item->title }}</a>
                @endforeach
            @else
                <a href="/" class="block py-2 text-sm text-gray-700">Home</a>
                <a href="/blog" class="block py-2 text-sm text-gray-700">Blog</a>
            @endif
            @auth
                <a href="/profile" class="block py-2 text-sm text-gray-700">Profile</a>
                <form method="POST" action="/logout">@csrf
                    <button class="block py-2 text-sm text-red-600">Logout</button>
                </form>
            @else
                <a href="/login" class="block py-2 text-sm text-gray-700">Login</a>
                <a href="/register" class="block py-2 text-sm text-blue-600">Register</a>
            @endauth
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 max-w-7xl mx-auto mt-4">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error') || $errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 max-w-7xl mx-auto mt-4">
            <p class="text-red-700">{{ session('error') ?? $errors->first() }}</p>
        </div>
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white font-semibold mb-4">{{ site_name() }}</h3>
                    <p class="text-sm">{{ site_tagline() }}</p>
                </div>
                <div>
                    @php echo dynamic_sidebar('footer-1') @endphp
                </div>
                <div>
                    @php echo dynamic_sidebar('footer-2') @endphp
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} {{ site_name() }}. Powered by Laravel CMS.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

    @stack('scripts')

    @php do_action('wp_footer') @endphp
</body>
</html>
