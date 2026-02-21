@extends('default::layouts.app')

@section('title', 'Category: ' . $category->name)
@section('meta_description', $category->meta_description ?? $category->description)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Category: {{ $category->name }}</h1>
        @if($category->description)
            <p class="text-gray-600 mt-2">{{ $category->description }}</p>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <div class="flex-1">
            @forelse($posts as $post)
                @include('default::partials.post-card', compact('post'))
            @empty
                <p class="text-gray-500">No posts in this category yet.</p>
            @endforelse
            <div class="mt-8">{{ $posts->links() }}</div>
        </div>
        <aside class="lg:w-80">
            @php echo dynamic_sidebar('sidebar-1') @endphp
        </aside>
    </div>
</div>
@endsection
