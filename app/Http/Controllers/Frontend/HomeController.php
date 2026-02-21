<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $perPage = (int) Setting::get('posts_per_page', 10);

        $posts = Post::published()
            ->ofType('post')
            ->with(['author', 'categories'])
            ->orderByDesc('published_at')
            ->paginate($perPage);

        $featuredPost = Post::published()
            ->ofType('post')
            ->where('is_featured', true)
            ->with(['author', 'categories'])
            ->first();

        return theme_view('home', compact('posts', 'featuredPost'));
    }
}
