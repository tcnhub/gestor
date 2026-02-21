<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->whereIn('type', ['post', 'page'])
            ->select(['slug', 'type', 'updated_at', 'published_at'])
            ->orderByDesc('updated_at')
            ->get();

        $categories = Category::where('type', 'category')->get();

        $content = view('sitemap', compact('posts', 'categories'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($robots)->header('Content-Type', 'text/plain');
    }
}
