<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Plugin;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Theme;
use App\Models\User;
use App\Models\Widget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // =====================
        // Default Settings
        // =====================
        $settings = [
            ['key' => 'site_name', 'value' => 'Laravel CMS', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Just another Laravel CMS site', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'A powerful CMS built with Laravel', 'group' => 'general'],
            ['key' => 'admin_email', 'value' => 'admin@example.com', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'UTC', 'group' => 'general'],
            ['key' => 'date_format', 'value' => 'F j, Y', 'group' => 'general'],
            ['key' => 'time_format', 'value' => 'g:i a', 'group' => 'general'],
            ['key' => 'posts_per_page', 'value' => '10', 'group' => 'reading'],
            ['key' => 'allow_comments', 'value' => '1', 'group' => 'discussion'],
            ['key' => 'comment_moderation', 'value' => '1', 'group' => 'discussion'],
            ['key' => 'permalink_structure', 'value' => '/%postname%/', 'group' => 'permalinks'],
            ['key' => 'meta_robots', 'value' => 'index,follow', 'group' => 'seo'],
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'reading'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // =====================
        // Default Theme
        // =====================
        Theme::firstOrCreate(['slug' => 'default'], [
            'name' => 'Default Theme',
            'description' => 'The default Laravel CMS theme',
            'version' => '1.0.0',
            'author' => 'Laravel CMS',
            'active' => true,
        ]);

        // =====================
        // Sample Plugins
        // =====================
        Plugin::firstOrCreate(['slug' => 'contact-form'], [
            'name' => 'Contact Form',
            'description' => 'A simple contact form plugin for your site.',
            'version' => '1.0.0',
            'author' => 'Laravel CMS',
            'active' => false,
        ]);

        Plugin::firstOrCreate(['slug' => 'seo-tools'], [
            'name' => 'SEO Tools',
            'description' => 'Enhanced SEO features including meta boxes, sitemap customization, and more.',
            'version' => '1.0.0',
            'author' => 'Laravel CMS',
            'active' => false,
        ]);

        // =====================
        // Admin User
        // =====================
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'display_name' => 'Site Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Site administrator',
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $admin->assignRole('admin');

        // Sample Editor
        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor',
                'display_name' => 'Jane Editor',
                'email' => 'editor@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $editor->assignRole('editor');

        // =====================
        // Categories & Tags
        // =====================
        $techCat = Category::firstOrCreate(['slug' => 'technology'], [
            'name' => 'Technology', 'type' => 'category',
            'description' => 'Technology news and articles',
        ]);
        $tutCat = Category::firstOrCreate(['slug' => 'tutorials'], [
            'name' => 'Tutorials', 'type' => 'category',
            'description' => 'Step by step guides',
        ]);
        $newsCat = Category::firstOrCreate(['slug' => 'news'], [
            'name' => 'News', 'type' => 'category',
        ]);

        $laravelTag = Category::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel', 'type' => 'tag']);
        $phpTag = Category::firstOrCreate(['slug' => 'php'], ['name' => 'PHP', 'type' => 'tag']);
        $webTag = Category::firstOrCreate(['slug' => 'web-development'], ['name' => 'Web Development', 'type' => 'tag']);

        // =====================
        // Sample Posts
        // =====================
        $post1 = Post::firstOrCreate(['slug' => 'welcome-to-laravel-cms'], [
            'user_id' => $admin->id,
            'title' => 'Welcome to Laravel CMS',
            'content' => '<p>Welcome to <strong>Laravel CMS</strong> — a powerful, open-source content management system built with the Laravel PHP framework.</p>
<p>This CMS provides a WordPress-like experience with the power and flexibility of Laravel. Features include:</p>
<ul>
<li>Role-based access control (Admin, Editor, Author, Contributor, Subscriber)</li>
<li>Full post and page management with revisions</li>
<li>Media library with Spatie MediaLibrary</li>
<li>Custom post types</li>
<li>Plugin system with hooks/filters (WordPress-like)</li>
<li>Theme system with Blade templates</li>
<li>SEO-ready with sitemaps and meta tags</li>
<li>Comments with moderation</li>
<li>Full-text search</li>
</ul>
<p>Log into the <a href="/admin">admin dashboard</a> to get started!</p>',
            'excerpt' => 'Welcome to Laravel CMS — a powerful, open-source CMS built on Laravel with WordPress-like features.',
            'status' => 'publish',
            'type' => 'post',
            'is_featured' => true,
            'published_at' => now()->subDays(1),
            'comment_status' => 'open',
            'meta_title' => 'Welcome to Laravel CMS',
            'meta_description' => 'A powerful CMS built with the Laravel PHP framework.',
        ]);
        $post1->categories()->sync([$techCat->id]);
        $post1->tags()->sync([$laravelTag->id, $phpTag->id]);

        $post2 = Post::firstOrCreate(['slug' => 'getting-started-with-laravel'], [
            'user_id' => $editor->id,
            'title' => 'Getting Started with Laravel: A Beginner\'s Guide',
            'content' => '<p>Laravel is the most popular PHP framework for web development. In this tutorial, we\'ll cover the basics of getting started with Laravel.</p>
<h2>Requirements</h2>
<ul>
<li>PHP 8.2+</li>
<li>Composer</li>
<li>MySQL/PostgreSQL</li>
<li>Node.js (for asset compilation)</li>
</ul>
<h2>Installation</h2>
<pre><code>composer create-project laravel/laravel my-app
cd my-app
php artisan serve</code></pre>
<p>Visit <code>http://localhost:8000</code> to see your new Laravel application.</p>',
            'excerpt' => 'Learn how to get started with Laravel, the most popular PHP framework for web development.',
            'status' => 'publish',
            'type' => 'post',
            'published_at' => now()->subDays(3),
            'comment_status' => 'open',
        ]);
        $post2->categories()->sync([$tutCat->id, $techCat->id]);
        $post2->tags()->sync([$laravelTag->id, $phpTag->id, $webTag->id]);

        $post3 = Post::firstOrCreate(['slug' => 'laravel-cms-features-overview'], [
            'user_id' => $admin->id,
            'title' => 'Laravel CMS Features Overview',
            'content' => '<p>Laravel CMS comes packed with features to help you build and manage your website efficiently.</p>
<h2>Admin Dashboard</h2>
<p>Powered by Filament PHP, the admin dashboard provides a clean, responsive interface for managing all your content.</p>
<h2>Plugin System</h2>
<p>Extend the CMS functionality with plugins. Our WordPress-inspired hook system (actions and filters) makes it easy to build custom plugins.</p>
<h2>Theme System</h2>
<p>Swap themes easily. Create custom themes using Laravel Blade templates with full access to the hook system.</p>',
            'excerpt' => 'An overview of all the powerful features included in Laravel CMS.',
            'status' => 'publish',
            'type' => 'post',
            'published_at' => now()->subDays(5),
            'comment_status' => 'open',
        ]);
        $post3->categories()->sync([$techCat->id]);
        $post3->tags()->sync([$laravelTag->id]);

        // Sample Page
        Post::firstOrCreate(['slug' => 'about'], [
            'user_id' => $admin->id,
            'title' => 'About',
            'content' => '<p>This is the about page. You can edit this content from the admin dashboard.</p>
<p>Laravel CMS is an open-source content management system built with the Laravel PHP framework. It provides a WordPress-like experience for developers who prefer to work with Laravel.</p>',
            'excerpt' => 'Learn more about this site and the Laravel CMS platform.',
            'status' => 'publish',
            'type' => 'page',
            'published_at' => now(),
            'template' => 'default',
        ]);

        Post::firstOrCreate(['slug' => 'contact'], [
            'user_id' => $admin->id,
            'title' => 'Contact',
            'content' => '<p>Get in touch with us. This is the contact page.</p>',
            'status' => 'publish',
            'type' => 'page',
            'published_at' => now(),
            'template' => 'default',
        ]);

        // =====================
        // Navigation Menus
        // =====================
        $primaryMenu = Menu::firstOrCreate(['slug' => 'primary-menu'], [
            'name' => 'Primary Menu',
            'location' => 'primary',
        ]);

        if ($primaryMenu->allItems()->count() === 0) {
            MenuItem::create(['menu_id' => $primaryMenu->id, 'title' => 'Home', 'url' => '/', 'order' => 1]);
            MenuItem::create(['menu_id' => $primaryMenu->id, 'title' => 'Blog', 'url' => '/', 'order' => 2]);
            MenuItem::create(['menu_id' => $primaryMenu->id, 'title' => 'About', 'url' => '/about', 'order' => 3]);
            MenuItem::create(['menu_id' => $primaryMenu->id, 'title' => 'Contact', 'url' => '/contact', 'order' => 4]);
        }

        $footerMenu = Menu::firstOrCreate(['slug' => 'footer-menu'], [
            'name' => 'Footer Menu',
            'location' => 'footer',
        ]);

        if ($footerMenu->allItems()->count() === 0) {
            MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'Privacy Policy', 'url' => '/privacy', 'order' => 1]);
            MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'Terms of Service', 'url' => '/terms', 'order' => 2]);
        }

        // =====================
        // Widgets
        // =====================
        if (Widget::count() === 0) {
            Widget::create(['sidebar' => 'sidebar-1', 'type' => 'search', 'title' => 'Search', 'order' => 1, 'active' => true]);
            Widget::create(['sidebar' => 'sidebar-1', 'type' => 'recent-posts', 'title' => 'Recent Posts', 'order' => 2, 'active' => true, 'settings' => ['count' => 5]]);
            Widget::create(['sidebar' => 'sidebar-1', 'type' => 'categories', 'title' => 'Categories', 'order' => 3, 'active' => true]);
            Widget::create(['sidebar' => 'sidebar-1', 'type' => 'tags', 'title' => 'Popular Tags', 'order' => 4, 'active' => true]);
            Widget::create([
                'sidebar' => 'footer-1',
                'type' => 'html',
                'title' => 'About Us',
                'order' => 1,
                'active' => true,
                'settings' => ['content' => '<p>Laravel CMS is an open-source content management system built with Laravel.</p>'],
            ]);
        }

        $this->command->info('CMS seeded successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Editor: editor@example.com / password');
    }
}
