@extends('default::layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('meta_keywords', $post->meta_keywords)
@section('og_title', $post->title)
@section('og_description', $post->excerpt)
@if($post->featured_image)
    @section('og_image', asset('storage/' . $post->featured_image))
@endif

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Article --}}
        <article class="flex-1 max-w-3xl">
            {{-- Featured Image --}}
            @if($post->featured_image)
            <div class="mb-6 rounded-xl overflow-hidden">
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                     alt="{{ $post->title }}"
                     class="w-full h-64 md:h-96 object-cover">
            </div>
            @endif

            {{-- Categories --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($post->categories as $cat)
                    <a href="{{ url('/category/' . $cat->slug) }}"
                       class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-100">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-8 pb-6 border-b border-gray-100">
                <span class="flex items-center gap-1">
                    <i class="fas fa-user text-blue-500"></i>
                    <a href="{{ url('/author/' . $post->author->name) }}" class="hover:text-blue-600">
                        {{ $post->author->display_name }}
                    </a>
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-calendar text-blue-500"></i>
                    {{ $post->published_at?->format(setting('date_format', 'F j, Y')) }}
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-eye text-blue-500"></i>
                    {{ number_format($post->views) }} views
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-comments text-blue-500"></i>
                    {{ $post->allComments->where('status', 'approved')->count() }} comments
                </span>
            </div>

            {{-- Content --}}
            <div class="prose prose-blue max-w-none prose-headings:text-gray-900 prose-a:text-blue-600">
                {!! apply_filters('the_content', $post->content) !!}
            </div>

            {{-- Tags --}}
            @if($post->tags->count())
            <div class="mt-8 pt-6 border-t border-gray-100">
                <span class="text-sm font-medium text-gray-600 mr-2">Tags:</span>
                @foreach($post->tags as $tag)
                    <a href="{{ url('/tag/' . $tag->slug) }}"
                       class="inline-block text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full mr-2 mb-2 hover:bg-gray-200">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
            @endif

            {{-- Share Links --}}
            <div class="mt-6 pt-6 border-t border-gray-100 flex items-center gap-4">
                <span class="text-sm font-medium text-gray-600">Share:</span>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                   target="_blank" class="text-sky-500 hover:text-sky-600">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                   target="_blank" class="text-blue-600 hover:text-blue-700">
                    <i class="fab fa-facebook text-xl"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}"
                   target="_blank" class="text-blue-800 hover:text-blue-900">
                    <i class="fab fa-linkedin text-xl"></i>
                </a>
            </div>

            {{-- Author Bio --}}
            @if($post->author->bio)
            <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}"
                         class="w-16 h-16 rounded-full">
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $post->author->display_name }}</h3>
                        @if($post->author->website)
                            <a href="{{ $post->author->website }}" class="text-sm text-blue-600">{{ $post->author->website }}</a>
                        @endif
                    </div>
                </div>
                <p class="text-gray-600 text-sm">{{ $post->author->bio }}</p>
            </div>
            @endif

            {{-- Post Navigation --}}
            <div class="mt-8 grid grid-cols-2 gap-4">
                @if($prevPost)
                <a href="{{ $prevPost->permalink }}" class="p-4 bg-white rounded-xl border border-gray-100 hover:border-blue-300 transition-colors">
                    <span class="text-xs text-gray-400">← Previous</span>
                    <p class="text-sm font-medium text-gray-800 mt-1 line-clamp-2">{{ $prevPost->title }}</p>
                </a>
                @else <div></div>
                @endif

                @if($nextPost)
                <a href="{{ $nextPost->permalink }}" class="p-4 bg-white rounded-xl border border-gray-100 hover:border-blue-300 transition-colors text-right">
                    <span class="text-xs text-gray-400">Next →</span>
                    <p class="text-sm font-medium text-gray-800 mt-1 line-clamp-2">{{ $nextPost->title }}</p>
                </a>
                @endif
            </div>

            {{-- Related Posts --}}
            @if($relatedPosts->count())
            <div class="mt-12">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Related Posts</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($relatedPosts as $related)
                    <a href="{{ $related->permalink }}" class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        @if($related->featured_image)
                            <img src="{{ asset('storage/' . $related->featured_image) }}"
                                 alt="{{ $related->title }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-r from-blue-100 to-blue-200"></div>
                        @endif
                        <div class="p-4">
                            <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $related->title }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $related->published_at?->format('M j, Y') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Comments --}}
            @if($post->comment_status === 'open')
            <div id="comments" class="mt-12">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    Comments ({{ $post->allComments->where('status', 'approved')->count() }})
                </h3>

                @forelse($post->comments as $comment)
                <div class="mb-6">
                    <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->author_name) }}&size=40"
                             alt="{{ $comment->author_name }}" class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <span class="font-medium text-sm text-gray-900">
                                        @if($comment->author_url)
                                            <a href="{{ $comment->author_url }}" class="hover:text-blue-600">{{ $comment->author_name }}</a>
                                        @else
                                            {{ $comment->author_name }}
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')"
                                        class="text-xs text-blue-600 hover:underline">Reply</button>
                            </div>
                            <p class="text-sm text-gray-700">{!! nl2br(e($comment->content)) !!}</p>
                        </div>
                    </div>

                    {{-- Replies --}}
                    @foreach($comment->replies as $reply)
                    <div class="ml-12 mt-3 flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->author_name) }}&size=32"
                             alt="{{ $reply->author_name }}" class="w-8 h-8 rounded-full">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-sm text-gray-900">{{ $reply->author_name }}</span>
                                <span class="text-xs text-gray-400 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700">{!! nl2br(e($reply->content)) !!}</p>
                        </div>
                    </div>
                    @endforeach

                    {{-- Reply Form --}}
                    <div id="reply-{{ $comment->id }}" class="hidden ml-12 mt-3">
                        <form action="{{ url('/posts/' . $post->id . '/comments') }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            @include('default::partials.comment-form', ['compact' => true])
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">No comments yet. Be the first!</p>
                @endforelse

                {{-- Comment Form --}}
                <div class="mt-8 bg-white rounded-xl border border-gray-100 p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Leave a Comment</h4>
                    <form action="{{ url('/posts/' . $post->id . '/comments') }}" method="POST">
                        @csrf
                        @include('default::partials.comment-form')
                    </form>
                </div>
            </div>
            @endif
        </article>

        {{-- Sidebar --}}
        <aside class="lg:w-80 flex-shrink-0">
            @php echo dynamic_sidebar('sidebar-1') @endphp
        </aside>
    </div>
</div>
@endsection
