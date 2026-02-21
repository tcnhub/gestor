<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\View\Factory;

class ThemeService
{
    protected string $activeTheme = 'default';

    public function __construct(protected Factory $view)
    {
        $this->loadActiveTheme();
    }

    protected function loadActiveTheme(): void
    {
        $theme = Cache::remember('active_theme', 3600, function () {
            return Theme::active()->first();
        });

        if ($theme) {
            $this->activeTheme = $theme->slug;
        }

        $this->registerThemeViewNamespace($this->activeTheme);
    }

    public function getActiveTheme(): string
    {
        return $this->activeTheme;
    }

    public function registerThemeViewNamespace(string $themeSlug): void
    {
        $themePath = resource_path("themes/{$themeSlug}");

        if (File::isDirectory($themePath)) {
            $this->view->addNamespace($themeSlug, $themePath);
        }
    }

    public function getAvailableThemes(): array
    {
        $themesPath = resource_path('themes');
        $themes = [];

        if (!File::isDirectory($themesPath)) {
            return $themes;
        }

        foreach (File::directories($themesPath) as $themeDir) {
            $configFile = $themeDir . '/theme.json';
            if (File::exists($configFile)) {
                $config = json_decode(File::get($configFile), true);
                $themes[] = array_merge($config, [
                    'slug' => basename($themeDir),
                    'path' => $themeDir,
                ]);
            }
        }

        return $themes;
    }

    public function activateTheme(string $slug): bool
    {
        $theme = Theme::firstOrCreate(
            ['slug' => $slug],
            $this->getThemeConfig($slug)
        );

        $theme->activate();
        Cache::forget('active_theme');

        $this->activeTheme = $slug;
        $this->registerThemeViewNamespace($slug);

        return true;
    }

    protected function getThemeConfig(string $slug): array
    {
        $configFile = resource_path("themes/{$slug}/theme.json");

        if (!File::exists($configFile)) {
            return ['name' => ucfirst($slug), 'version' => '1.0.0'];
        }

        return json_decode(File::get($configFile), true) ?? [];
    }

    public function getSidebars(): array
    {
        $configFile = resource_path("themes/{$this->activeTheme}/sidebars.json");

        if (!File::exists($configFile)) {
            return [
                ['id' => 'sidebar-1', 'name' => 'Main Sidebar'],
                ['id' => 'footer-1', 'name' => 'Footer Column 1'],
                ['id' => 'footer-2', 'name' => 'Footer Column 2'],
            ];
        }

        return json_decode(File::get($configFile), true) ?? [];
    }
}
