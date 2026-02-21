@extends('default::layouts.app')

@section('title', 'Author: ' . $author->display_name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-8 flex items-center gap-6">
        <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="w-20 h-20 rounded-full">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $author->display_name }}</h1>
            @if($author->bio)<p class="text-gray-600 mt-1">{{ $author->bio }}</p>@endif
            @if($author->website)
                <a href="{{ $author->website }}" class="text-sm text-blue-600 mt-1 block">{{ $author->website }}</a>
            @endif
        </div>
    </div>
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="flex-1">
            @forelse($posts as $post)
                @include('default::partials.post-card', compact('post'))
            @empty
                <p class="text-gray-500">No posts by this author yet.</p>
            @endforelse
            <div class="mt-8">{{ $posts->links() }}</div>
        </div>
        <aside class="lg:w-80">@php echo dynamic_sidebar('sidebar-1') @endphp</aside>
    </div>
</div>
@endsection
