<article class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden hover:shadow-md transition-shadow">
    <div class="flex flex-col md:flex-row">
        @if($post->featured_image)
        <div class="md:w-56 flex-shrink-0">
            <a href="{{ $post->permalink }}">
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                     alt="{{ $post->title }}"
                     class="w-full h-40 object-cover">
            </a>
        </div>
        @endif
        <div class="p-5 flex-1">
            <div class="flex flex-wrap gap-2 mb-2">
                @foreach($post->categories as $cat)
                    <a href="{{ url('/category/' . $cat->slug) }}"
                       class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $cat->name }}</a>
                @endforeach
            </div>
            <h2 class="text-lg font-bold mb-2">
                <a href="{{ $post->permalink }}" class="text-gray-800 hover:text-blue-600">{{ $post->title }}</a>
            </h2>
            @if($post->excerpt)
                <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $post->excerpt }}</p>
            @endif
            <div class="flex items-center justify-between text-xs text-gray-400">
                <span>{{ $post->author->display_name }} &bull; {{ $post->published_at?->format('M j, Y') }}</span>
                <a href="{{ $post->permalink }}" class="text-blue-600 font-medium">Read →</a>
            </div>
        </div>
    </div>
</article>
