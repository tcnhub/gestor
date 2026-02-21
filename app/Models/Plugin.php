<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'version', 'author', 'author_url', 'active', 'settings',
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
        $this->update(['active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['active' => false]);
    }
}
