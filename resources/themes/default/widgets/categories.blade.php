<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $widget->title ?? 'Categories' }}</h3>
    @php
        $cats = \App\Models\Category::categories()
            ->withCount('posts')
            ->orderBy('name')
            ->get();
    @endphp
    <ul class="space-y-2">
        @foreach($cats as $cat)
        <li class="flex justify-between items-center">
            <a href="{{ url('/category/' . $cat->slug) }}" class="text-sm text-gray-700 hover:text-blue-600">
                {{ $cat->name }}
            </a>
            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $cat->posts_count }}</span>
        </li>
        @endforeach
    </ul>
</div>
