@extends('default::layouts.app')

@section('title', site_name())
@section('meta_description', site_tagline())

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(isset($featuredPost) && $featuredPost)
    {{-- Featured Post Hero --}}
    <div class="mb-12 relative rounded-2xl overflow-hidden bg-gray-900 text-white">
        @if($featuredPost->featured_image)
            <img src="{{ asset('storage/' . $featuredPost->featured_image) }}"
                 alt="{{ $featuredPost->title }}"
                 class="w-full h-96 object-cover opacity-60">
        @else
            <div class="h-96 bg-gradient-to-r from-blue-600 to-blue-800"></div>
        @endif
        <div class="absolute inset-0 flex items-end p-8">
            <div class="max-w-2xl">
                @foreach($featuredPost->categories as $cat)
                    <span class="inline-block px-3 py-1 bg-blue-600 text-sm rounded-full mb-3">{{ $cat->name }}</span>
                @endforeach
                <h1 class="text-3xl md:text-4xl font-bold mb-3">
                    <a href="{{ $featuredPost->permalink }}" class="hover:underline">{{ $featuredPost->title }}</a>
                </h1>
                @if($featuredPost->excerpt)
                    <p class="text-gray-200 mb-4">{{ $featuredPost->excerpt }}</p>
                @endif
                <div class="text-sm text-gray-300">
                    By {{ $featuredPost->author->display_name }} &bull;
                    {{ $featuredPost->published_at?->format(setting('date_format', 'F j, Y')) }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Main Content --}}
        <div class="flex-1">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Latest Posts</h2>

            @forelse($posts as $post)
            <article class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex flex-col md:flex-row">
                    @if($post->featured_image)
                    <div class="md:w-64 flex-shrink-0">
                        <a href="{{ $post->permalink }}">
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-48 md:h-full object-cover">
                        </a>
                    </div>
                    @endif
                    <div class="p-6 flex-1">
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($post->categories as $cat)
                                <a href="{{ url('/category/' . $cat->slug) }}"
                                   class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-full hover:bg-blue-100">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                        <h2 class="text-xl font-bold mb-2">
                            <a href="{{ $post->permalink }}" class="text-gray-800 hover:text-blue-600 transition-colors">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @if($post->excerpt)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $post->excerpt }}</p>
                        @endif
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <div class="flex items-center gap-3">
                                <span><i class="fas fa-user mr-1"></i>{{ $post->author->display_name }}</span>
                                <span><i class="fas fa-calendar mr-1"></i>{{ $post->published_at?->format(setting('date_format', 'M j, Y')) }}</span>
                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($post->views) }}</span>
                            </div>
                            <a href="{{ $post->permalink }}" class="text-blue-600 hover:underline font-medium text-sm">
                                Read more →
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-newspaper text-4xl mb-4"></i>
                <p class="text-lg">No posts published yet.</p>
            </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="lg:w-80">
            @php echo dynamic_sidebar('sidebar-1') @endphp
        </aside>
    </div>
</div>
@endsection
