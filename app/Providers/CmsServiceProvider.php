<?php

namespace App\Providers;

use App\Services\HookService;
use App\Services\PluginService;
use App\Services\ThemeService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register helper functions
        require_once app_path('Helpers/cms_helpers.php');

        $this->app->singleton(HookService::class);
        $this->app->singleton(ThemeService::class);
        $this->app->singleton(PluginService::class);
    }

    public function boot(): void
    {
        // Load active plugins
        $this->app->booted(function () {
            try {
                app(PluginService::class)->loadActivePlugins();
            } catch (\Exception $e) {
                // Silently fail during install
            }

            // Fire cms_loaded action
            do_action('cms_loaded');
        });

        // Register Blade directives
        $this->registerBladeDirectives();

        // Register CMS view namespace
        $this->loadViewsFrom(resource_path('views/cms'), 'cms');
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('action', function ($expression) {
            return "<?php do_action($expression); ?>";
        });

        Blade::directive('filter', function ($expression) {
            return "<?php echo apply_filters($expression); ?>";
        });

        Blade::directive('sidebar', function ($expression) {
            return "<?php echo dynamic_sidebar($expression); ?>";
        });

        Blade::directive('navmenu', function ($expression) {
            return "<?php echo the_nav_menu($expression); ?>";
        });

        Blade::directive('sitename', function () {
            return "<?php echo site_name(); ?>";
        });

        Blade::directive('sitetagline', function () {
            return "<?php echo site_tagline(); ?>";
        });
    }
}
