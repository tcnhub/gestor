<?php

namespace App\Services;

class HookService
{
    protected array $actions = [];
    protected array $filters = [];

    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        $this->actions[$hook][$priority][] = $callback;
        ksort($this->actions[$hook]);
    }

    public function doAction(string $hook, ...$args): void
    {
        if (!isset($this->actions[$hook])) {
            return;
        }

        foreach ($this->actions[$hook] as $priorityGroup) {
            foreach ($priorityGroup as $callback) {
                $callback(...$args);
            }
        }
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        $this->filters[$hook][$priority][] = $callback;
        ksort($this->filters[$hook]);
    }

    public function applyFilters(string $hook, $value, ...$args): mixed
    {
        if (!isset($this->filters[$hook])) {
            return $value;
        }

        foreach ($this->filters[$hook] as $priorityGroup) {
            foreach ($priorityGroup as $callback) {
                $value = $callback($value, ...$args);
            }
        }

        return $value;
    }

    public function hasFilter(string $hook): bool
    {
        return isset($this->filters[$hook]) && !empty($this->filters[$hook]);
    }

    public function removeAction(string $hook, callable $callback, int $priority = 10): void
    {
        if (isset($this->actions[$hook][$priority])) {
            $this->actions[$hook][$priority] = array_filter(
                $this->actions[$hook][$priority],
                fn ($cb) => $cb !== $callback
            );
        }
    }

    public function removeFilter(string $hook, callable $callback, int $priority = 10): void
    {
        if (isset($this->filters[$hook][$priority])) {
            $this->filters[$hook][$priority] = array_filter(
                $this->filters[$hook][$priority],
                fn ($cb) => $cb !== $callback
            );
        }
    }
}
