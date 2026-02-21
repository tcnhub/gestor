<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $widget->title ?? 'Recent Posts' }}</h3>
    @php
        $count = $settings['count'] ?? 5;
        $recentPosts = \App\Models\Post::published()->ofType('post')
            ->with('author')
            ->orderByDesc('published_at')
            ->limit($count)
            ->get();
    @endphp
    <ul class="space-y-3">
        @foreach($recentPosts as $post)
        <li class="flex gap-3">
            @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                     alt="{{ $post->title }}" class="w-12 h-12 rounded object-cover flex-shrink-0">
            @else
                <div class="w-12 h-12 bg-blue-100 rounded flex-shrink-0"></div>
            @endif
            <div>
                <a href="{{ $post->permalink }}" class="text-sm font-medium text-gray-800 hover:text-blue-600 line-clamp-2">
                    {{ $post->title }}
                </a>
                <p class="text-xs text-gray-400 mt-1">{{ $post->published_at?->format('M j, Y') }}</p>
            </div>
        </li>
        @endforeach
    </ul>
</div>
