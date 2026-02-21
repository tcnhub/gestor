# Laravel CMS

A full-featured, open-source Content Management System built with **Laravel 12**, inspired by WordPress but designed for modern PHP developers. Powered by **Filament PHP** for the admin panel and **Spatie** packages for permissions, media management, and more.

---

## Features

### Content Management
- **Posts** — Create, edit, delete, publish, schedule posts with full WYSIWYG editor
- **Pages** — Hierarchical static pages with custom templates
- **Custom Post Types** — Register new content types via admin (portfolios, events, etc.)
- **Post Revisions** — Automatic revision history with restore capability
- **Post Meta / Custom Fields** — Add arbitrary key/value fields to any post
- **Media Library** — Upload and manage images, videos, and files via Spatie MediaLibrary
- **Categories & Tags** — Hierarchical categories and flat tags
- **Featured Images** — Set thumbnail images for posts and pages

### User Management & RBAC
- **Roles:** Admin, Editor, Author, Contributor, Subscriber
- **Permissions:** Fine-grained permission system via Spatie Permission
- **User Profiles** — Display name, bio, website, avatar
- **API Tokens** — Laravel Sanctum for REST API authentication

### Admin Dashboard (Filament PHP)
- Responsive modern admin panel at `/admin`
- Dashboard stats (posts, pages, users, comments, views, media)
- Full CRUD for all content types with rich forms
- Settings panel with tabs (General, Reading, Discussion, Permalinks, SEO, Media)
- Plugin management (activate/deactivate)
- Theme management
- User management with role assignment

### Theme System
- Multiple theme support with easy switching
- Blade-based templates in `resources/themes/{slug}/`
- **WordPress-like hooks:** `add_action()`, `do_action()`, `add_filter()`, `apply_filters()`
- Dynamic sidebars and widget areas
- Custom Blade directives: `@action`, `@filter`, `@sidebar`, `@navmenu`

### Plugin System
- Plugin directory at `plugins/{slug}/`
- Auto-load active plugins at boot
- Sample plugins: Contact Form, SEO Tools

### Frontend Features
- Responsive design with Tailwind CSS
- Threaded comments with moderation
- Full-text search with pagination
- SEO ready — meta tags, Open Graph, sitemap.xml, robots.txt
- Configurable permalink structures
- Archive pages by year, month, author, category, tag
- Google Analytics integration

---

## Requirements

- PHP >= 8.2
- MySQL 8.0+ / MariaDB 10.3+ or PostgreSQL 13+
- Composer 2.x
- Node.js 18+

---

## Quick Installation

```bash
# 1. Clone and install
git clone <repo-url> my-cms && cd my-cms
composer install && npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate
# Edit .env with your database credentials

# 3. Run the installer
php artisan cms:install

# 4. Build assets and serve
npm run build && php artisan serve
```

**Default Credentials:**
| Role   | Email                | Password |
|--------|----------------------|----------|
| Admin  | admin@example.com    | password |
| Editor | editor@example.com   | password |

> ⚠️ Change these passwords immediately after installation!

---

## Project Structure

```
├── app/
│   ├── Console/Commands/CmsInstall.php   # Install wizard
│   ├── Filament/Resources/               # Admin resources (Post, User, etc.)
│   ├── Filament/Pages/Settings.php       # Settings admin page
│   ├── Filament/Widgets/StatsOverviewWidget.php
│   ├── Helpers/cms_helpers.php           # setting(), site_name(), hooks, etc.
│   ├── Http/Controllers/Frontend/        # HomeController, PostController, etc.
│   ├── Models/                           # Post, User, Category, Comment, etc.
│   ├── Providers/CmsServiceProvider.php  # Boot plugins, register Blade directives
│   └── Services/
│       ├── HookService.php               # WordPress-like actions/filters
│       ├── ThemeService.php              # Theme switching & view namespacing
│       └── PluginService.php             # Plugin loading and management
├── database/
│   ├── migrations/                       # All CMS table migrations
│   └── seeders/CmsSeeder.php             # Demo data + RolesAndPermissionsSeeder
├── plugins/
│   ├── contact-form/                     # Sample contact form plugin
│   └── seo-tools/                        # Sample SEO plugin
├── resources/themes/default/            # Default Tailwind CSS theme
└── routes/web.php                        # Frontend routes
```

---

## Creating a Theme

```
resources/themes/my-theme/
├── theme.json          # Metadata
├── layouts/app.blade.php
├── home.blade.php
├── posts/show.blade.php
├── pages/default.blade.php
├── search.blade.php
├── auth/login.blade.php
├── auth/register.blade.php
├── partials/post-card.blade.php
└── widgets/
    ├── search.blade.php
    ├── recent-posts.blade.php
    └── categories.blade.php
```

### Hook System

```php
// Add to theme or plugin
add_action('wp_head', function() {
    echo '<link rel="stylesheet" href="' . theme_asset('css/app.css') . '">';
});

add_filter('the_content', function($content) {
    return wpautop($content); // transform content
});
```

---

## Creating a Plugin

```
plugins/my-plugin/
├── plugin.json         # Metadata
├── my-plugin.php       # Main plugin file
└── views/              # Plugin Blade views
```

```php
// my-plugin.php
add_action('plugin_activated_my-plugin', function() {
    // Setup on activation
});

add_filter('the_content', function($content) {
    return $content . '<p>By my plugin!</p>';
});
```

---

## Available Artisan Commands

```bash
php artisan cms:install          # Install (migrate + seed + storage link)
php artisan cms:install --fresh  # Fresh install (wipes database)
php artisan migrate              # Run pending migrations
php artisan db:seed              # Re-seed data
```

---

## License

MIT — Open source, free to use, modify, and distribute.
