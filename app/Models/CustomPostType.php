<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPostType extends Model
{
    protected $fillable = [
        'name', 'label', 'singular_label', 'description',
        'supports', 'public', 'hierarchical', 'has_archive',
        'rewrite_slug', 'settings',
    ];

    protected $casts = [
        'supports' => 'array',
        'settings' => 'array',
        'public' => 'boolean',
        'hierarchical' => 'boolean',
        'has_archive' => 'boolean',
    ];

    public function posts()
    {
        return Post::where('type', $this->name);
    }

    public function getDefaultSupports(): array
    {
        return ['title', 'editor', 'thumbnail', 'revisions'];
    }
}
