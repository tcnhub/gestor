<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function show(string $slug)
    {
        $post = Cache::remember("post_{$slug}", 300, function () use ($slug) {
            return Post::published()
                ->where('slug', $slug)
                ->where('type', 'post')
                ->with(['author', 'categories', 'tags', 'comments.replies', 'comments.user'])
                ->firstOrFail();
        });

        $post->incrementViews();

        $prevPost = Post::published()
            ->ofType('post')
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first();

        $nextPost = Post::published()
            ->ofType('post')
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first();

        $relatedPosts = Post::published()
            ->ofType('post')
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $post->categories->pluck('id')))
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->get();

        return theme_view('posts.show', compact('post', 'prevPost', 'nextPost', 'relatedPosts'));
    }

    public function showPage(string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->where('type', 'page')
            ->firstOrFail();

        $post->incrementViews();

        // Allow custom page templates
        $template = $post->template ?? 'default';
        $view = "pages.{$template}";

        if (!view()->exists(active_theme() . '::' . $view)) {
            $view = 'pages.default';
        }

        return theme_view($view, compact('post'));
    }

    public function byCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->where('type', 'category')->firstOrFail();
        $perPage = (int) Setting::get('posts_per_page', 10);

        $posts = $category->posts()
            ->published()
            ->with(['author', 'categories'])
            ->orderByDesc('published_at')
            ->paginate($perPage);

        return theme_view('posts.category', compact('category', 'posts'));
    }

    public function byTag(string $slug)
    {
        $tag = Category::where('slug', $slug)->where('type', 'tag')->firstOrFail();
        $perPage = (int) Setting::get('posts_per_page', 10);

        $posts = $tag->posts()
            ->published()
            ->with(['author', 'categories'])
            ->orderByDesc('published_at')
            ->paginate($perPage);

        return theme_view('posts.tag', compact('tag', 'posts'));
    }

    public function byAuthor(string $name)
    {
        $author = \App\Models\User::where('name', $name)->firstOrFail();
        $perPage = (int) Setting::get('posts_per_page', 10);

        $posts = Post::published()
            ->ofType('post')
            ->where('user_id', $author->id)
            ->with(['author', 'categories'])
            ->orderByDesc('published_at')
            ->paginate($perPage);

        return theme_view('posts.author', compact('author', 'posts'));
    }

    public function archive(int $year, ?int $month = null)
    {
        $perPage = (int) Setting::get('posts_per_page', 10);

        $query = Post::published()
            ->ofType('post')
            ->whereYear('published_at', $year);

        if ($month) {
            $query->whereMonth('published_at', $month);
        }

        $posts = $query->with(['author', 'categories'])
            ->orderByDesc('published_at')
            ->paginate($perPage);

        return theme_view('posts.archive', compact('posts', 'year', 'month'));
    }

    public function storeComment(Request $request, int $postId)
    {
        $post = Post::findOrFail($postId);

        if ($post->comment_status === 'closed') {
            return back()->withErrors(['comment' => 'Comments are closed for this post.']);
        }

        $request->validate([
            'author_name' => 'required|string|max:100',
            'author_email' => 'required|email|max:255',
            'author_url' => 'nullable|url|max:255',
            'content' => 'required|string|max:5000',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        $needsModeration = (bool) Setting::get('comment_moderation', true);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'author_url' => $request->author_url,
            'content' => $request->content,
            'status' => $needsModeration ? 'pending' : 'approved',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $message = $needsModeration
            ? 'Your comment is awaiting moderation.'
            : 'Your comment has been posted.';

        return back()->with('success', $message);
    }
}
