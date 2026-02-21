@extends('default::layouts.app')

@section('title', 'Archive: ' . ($month ? date('F', mktime(0,0,0,$month)) . ' ' : '') . $year)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">
        Archive: {{ $month ? date('F', mktime(0,0,0,$month)) . ' ' : '' }}{{ $year }}
    </h1>
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="flex-1">
            @forelse($posts as $post)
                @include('default::partials.post-card', compact('post'))
            @empty
                <p class="text-gray-500">No posts for this period.</p>
            @endforelse
            <div class="mt-8">{{ $posts->links() }}</div>
        </div>
        <aside class="lg:w-80">@php echo dynamic_sidebar('sidebar-1') @endphp</aside>
    </div>
</div>
@endsection
