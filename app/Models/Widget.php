<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = ['sidebar', 'type', 'title', 'settings', 'order', 'active'];

    protected $casts = [
        'settings' => 'array',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order');
    }

    public function scopeForSidebar($query, string $sidebar)
    {
        return $query->where('sidebar', $sidebar);
    }
}
