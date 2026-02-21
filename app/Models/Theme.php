<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'version', 'author', 'preview_image', 'active', 'settings',
    ];

    protected $casts = [
        'active' => 'boolean',
        'settings' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function activate(): void
    {
        // Deactivate all other themes
        static::where('id', '!=', $this->id)->update(['active' => false]);
        $this->update(['active' => true]);
    }

    public function getViewPathAttribute(): string
    {
        return resource_path("themes/{$this->slug}");
    }
}
