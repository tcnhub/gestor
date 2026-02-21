@extends('default::layouts.app')

@section('title', 'Search: ' . $query)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        Search Results{{ $query ? ': "' . $query . '"' : '' }}
    </h1>
    @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <p class="text-gray-500 mb-8">{{ $posts->total() }} result(s) found.</p>
    @endif

    <div class="mb-8">
        <form action="/search" method="GET" class="flex gap-2">
            <input type="search" name="q" value="{{ $query }}"
                   placeholder="Search posts..."
                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Search</button>
        </form>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <div class="flex-1">
            @if($query)
                @forelse($posts as $post)
                    @include('default::partials.post-card', compact('post'))
                @empty
                    <div class="text-center py-16 text-gray-500">
                        <i class="fas fa-search text-4xl mb-4"></i>
                        <p>No results found for "{{ $query }}"</p>
                    </div>
                @endforelse
                @if($posts instanceof \Illuminate\Pagination\AbstractPaginator)
                    <div class="mt-8">{{ $posts->links() }}</div>
                @endif
            @endif
        </div>
        <aside class="lg:w-80">@php echo dynamic_sidebar('sidebar-1') @endphp</aside>
    </div>
</div>
@endsection
