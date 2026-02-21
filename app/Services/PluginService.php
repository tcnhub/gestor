<?php

namespace App\Services;

use App\Models\Plugin;
use Illuminate\Support\Facades\File;

class PluginService
{
    protected array $loadedPlugins = [];

    public function loadActivePlugins(): void
    {
        $plugins = Plugin::active()->get();

        foreach ($plugins as $plugin) {
            $this->loadPlugin($plugin->slug);
        }
    }

    public function loadPlugin(string $slug): bool
    {
        $pluginFile = base_path("plugins/{$slug}/{$slug}.php");

        if (!File::exists($pluginFile)) {
            return false;
        }

        if (!in_array($slug, $this->loadedPlugins)) {
            require_once $pluginFile;
            $this->loadedPlugins[] = $slug;
        }

        return true;
    }

    public function getAvailablePlugins(): array
    {
        $pluginsPath = base_path('plugins');
        $plugins = [];

        if (!File::isDirectory($pluginsPath)) {
            return $plugins;
        }

        foreach (File::directories($pluginsPath) as $pluginDir) {
            $slug = basename($pluginDir);
            $configFile = $pluginDir . '/plugin.json';

            if (File::exists($configFile)) {
                $config = json_decode(File::get($configFile), true);
                $dbPlugin = Plugin::where('slug', $slug)->first();

                $plugins[] = array_merge($config ?? [], [
                    'slug' => $slug,
                    'path' => $pluginDir,
                    'active' => $dbPlugin ? $dbPlugin->active : false,
                ]);
            }
        }

        return $plugins;
    }

    public function activatePlugin(string $slug): bool
    {
        $plugin = Plugin::where('slug', $slug)->firstOrCreate(
            ['slug' => $slug],
            $this->getPluginConfig($slug)
        );

        $plugin->activate();
        $this->loadPlugin($slug);

        // Call plugin activation hook
        do_action("plugin_activated_{$slug}");

        return true;
    }

    public function deactivatePlugin(string $slug): bool
    {
        $plugin = Plugin::where('slug', $slug)->first();

        if ($plugin) {
            $plugin->deactivate();
            do_action("plugin_deactivated_{$slug}");
        }

        return true;
    }

    protected function getPluginConfig(string $slug): array
    {
        $configFile = base_path("plugins/{$slug}/plugin.json");

        if (!File::exists($configFile)) {
            return ['name' => ucfirst($slug), 'version' => '1.0.0'];
        }

        return json_decode(File::get($configFile), true) ?? [];
    }
}
