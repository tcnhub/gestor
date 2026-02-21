<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $perPage = (int) Setting::get('posts_per_page', 10);

        $posts = collect();

        if (!empty($query)) {
            $posts = Post::published()
                ->ofType('post')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                        ->orWhere('content', 'LIKE', "%{$query}%")
                        ->orWhere('excerpt', 'LIKE', "%{$query}%");
                })
                ->with(['author', 'categories'])
                ->orderByDesc('published_at')
                ->paginate($perPage)
                ->withQueryString();
        }

        return theme_view('search', compact('posts', 'query'));
    }
}
