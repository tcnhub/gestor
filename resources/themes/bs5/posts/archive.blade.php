@extends('bs5::layouts.app')
@section('title', 'Archive: ' . ($month ? date('F', mktime(0,0,0,$month)) . ' ' : '') . $year)

@section('content')
<div class="bg-info text-white py-5">
    <div class="container">
        <h1 class="fw-bold mb-0">
            <i class="fa-solid fa-calendar-days me-2"></i>
            {{ $month ? date('F', mktime(0,0,0,$month)) . ' ' : '' }}{{ $year }}
        </h1>
    </div>
</div>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            @forelse($posts as $post)
                @include('bs5::partials.post-card', compact('post'))
            @empty
                <div class="alert alert-light text-center">No posts for this period.</div>
            @endforelse
            <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
        </div>
        <aside class="col-lg-4">@php echo dynamic_sidebar('sidebar-1') @endphp</aside>
    </div>
</div>
@endsection
