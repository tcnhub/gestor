<?php

use App\Models\Setting;
use App\Services\HookService;
use App\Services\ThemeService;

if (!function_exists('setting')) {
    function setting(string $key, $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('site_name')) {
    function site_name(): string
    {
        return setting('site_name', config('app.name', 'Laravel CMS'));
    }
}

if (!function_exists('site_tagline')) {
    function site_tagline(): string
    {
        return setting('site_tagline', 'Just another Laravel CMS');
    }
}

if (!function_exists('site_url')) {
    function site_url(): string
    {
        return rtrim(setting('site_url', config('app.url')), '/');
    }
}

// WordPress-like hook system
if (!function_exists('add_action')) {
    function add_action(string $hook, callable $callback, int $priority = 10): void
    {
        app(HookService::class)->addAction($hook, $callback, $priority);
    }
}

if (!function_exists('do_action')) {
    function do_action(string $hook, ...$args): void
    {
        app(HookService::class)->doAction($hook, ...$args);
    }
}

if (!function_exists('add_filter')) {
    function add_filter(string $hook, callable $callback, int $priority = 10): void
    {
        app(HookService::class)->addFilter($hook, $callback, $priority);
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters(string $hook, $value, ...$args): mixed
    {
        return app(HookService::class)->applyFilters($hook, $value, ...$args);
    }
}

if (!function_exists('has_filter')) {
    function has_filter(string $hook): bool
    {
        return app(HookService::class)->hasFilter($hook);
    }
}

if (!function_exists('active_theme')) {
    function active_theme(): string
    {
        return app(ThemeService::class)->getActiveTheme();
    }
}

if (!function_exists('theme_asset')) {
    function theme_asset(string $path): string
    {
        return asset('themes/' . active_theme() . '/' . ltrim($path, '/'));
    }
}

if (!function_exists('theme_view')) {
    function theme_view(string $view, array $data = []): \Illuminate\Contracts\View\View
    {
        return view(active_theme() . '::' . $view, $data);
    }
}

if (!function_exists('get_nav_menu')) {
    function get_nav_menu(string $location): ?\App\Models\Menu
    {
        return \App\Models\Menu::where('location', $location)->first();
    }
}

if (!function_exists('the_nav_menu')) {
    function the_nav_menu(string $location, array $args = []): string
    {
        $menu = get_nav_menu($location);
        if (!$menu) return '';

        $view = $args['view'] ?? 'partials.nav-menu';
        return view(active_theme() . '::' . $view, ['menu' => $menu, 'args' => $args])->render();
    }
}

if (!function_exists('get_sidebar_widgets')) {
    function get_sidebar_widgets(string $sidebar): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Widget::active()->forSidebar($sidebar)->get();
    }
}

if (!function_exists('dynamic_sidebar')) {
    function dynamic_sidebar(string $sidebar): string
    {
        $widgets = get_sidebar_widgets($sidebar);
        $output = '';
        foreach ($widgets as $widget) {
            $output .= render_widget($widget);
        }
        return $output;
    }
}

if (!function_exists('render_widget')) {
    function render_widget(\App\Models\Widget $widget): string
    {
        try {
            $view = active_theme() . '::widgets.' . $widget->type;
            if (!view()->exists($view)) {
                $view = 'cms::widgets.' . $widget->type;
            }
            return view($view, ['widget' => $widget, 'settings' => $widget->settings ?? []])->render();
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('cms_pagination')) {
    function cms_pagination($items): string
    {
        return $items->links()->toHtml();
    }
}
