<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'parent_id', 'title', 'slug', 'content', 'excerpt',
        'status', 'type', 'featured_image', 'meta_title', 'meta_description',
        'meta_keywords', 'published_at', 'scheduled_at', 'menu_order',
        'comment_status', 'ping_status', 'password', 'template',
        'is_featured', 'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_featured' => 'boolean',
        'views' => 'integer',
        'menu_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->where('categories.type', 'category');
    }

    public function tags()
    {
        return $this->belongsToMany(Category::class, 'category_post')->where('categories.type', 'tag');
    }

    public function revisions()
    {
        return $this->hasMany(PostRevision::class)->latest();
    }

    public function meta()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('status', 'approved')->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function getMeta(string $key, $default = null)
    {
        $meta = $this->meta()->where('key', $key)->first();
        return $meta ? $meta->value : $default;
    }

    public function setMeta(string $key, $value): void
    {
        $this->meta()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'publish')->where('published_at', '<=', now());
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function getPermalinkAttribute(): string
    {
        $structure = setting('permalink_structure', '/%postname%/');
        return rtrim(url('/'), '/') . $this->buildPermalink($structure);
    }

    private function buildPermalink(string $structure): string
    {
        $replacements = [
            '%year%' => $this->published_at?->format('Y') ?? now()->format('Y'),
            '%monthnum%' => $this->published_at?->format('m') ?? now()->format('m'),
            '%day%' => $this->published_at?->format('d') ?? now()->format('d'),
            '%postname%' => $this->slug,
            '%post_id%' => $this->id,
            '%author%' => $this->author?->name ?? 'admin',
            '%category%' => $this->categories->first()?->slug ?? 'uncategorized',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $structure);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
