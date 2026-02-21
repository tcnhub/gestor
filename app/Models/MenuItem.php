<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id', 'parent_id', 'title', 'url', 'target', 'icon',
        'order', 'linkable_type', 'linkable_id', 'css_class',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function linkable()
    {
        return $this->morphTo();
    }

    public function getResolvedUrlAttribute(): string
    {
        if ($this->linkable_type && $this->linkable_id) {
            $model = $this->linkable;
            if ($model && method_exists($model, 'getPermalinkAttribute')) {
                return $model->permalink;
            }
        }
        return $this->url ?? '#';
    }
}
