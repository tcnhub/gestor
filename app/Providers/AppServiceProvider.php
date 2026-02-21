<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Use Tailwind pagination
        Paginator::useTailwind();

        // Prevent lazy loading in development
        Model::preventLazyLoading(!$this->app->isProduction());

        // Share common data with all views
        View::composer('*', function ($view) {
            try {
                $view->with('siteName', Setting::get('site_name', config('app.name')));
                $view->with('siteTagline', Setting::get('site_tagline', ''));
            } catch (\Exception $e) {
                $view->with('siteName', config('app.name'));
                $view->with('siteTagline', '');
            }
        });

        // Register observer for post revisions
        Post::updating(function (Post $post) {
            if ($post->isDirty(['title', 'content', 'excerpt']) && $post->exists) {
                \App\Models\PostRevision::create([
                    'post_id' => $post->id,
                    'user_id' => auth()->id() ?? $post->user_id,
                    'title' => $post->getOriginal('title'),
                    'content' => $post->getOriginal('content'),
                    'excerpt' => $post->getOriginal('excerpt'),
                ]);
            }
        });
    }
}
