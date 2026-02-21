<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $widget->title ?? 'Tags' }}</h3>
    @php
        $tags = \App\Models\Category::tags()->withCount('posts')->having('posts_count', '>', 0)->orderBy('name')->get();
    @endphp
    <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
            <a href="{{ url('/tag/' . $tag->slug) }}"
               class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 transition-colors">
                {{ $tag->name }} ({{ $tag->posts_count }})
            </a>
        @endforeach
    </div>
</div>
