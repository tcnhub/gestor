@extends('bs5::layouts.app')
@section('title', 'Author: ' . $author->display_name)

@section('content')
<div class="bg-dark text-white py-5">
    <div class="container">
        <div class="d-flex align-items-center gap-4">
            <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}"
                 class="rounded-circle border border-3 border-primary"
                 style="width:90px;height:90px;object-fit:cover">
            <div>
                <h1 class="fw-bold mb-1 fs-3">{{ $author->display_name }}</h1>
                @if($author->bio)
                    <p class="text-white-50 small mb-1">{{ $author->bio }}</p>
                @endif
                @if($author->website)
                    <a href="{{ $author->website }}" class="text-primary small">
                        <i class="fa-solid fa-link me-1"></i>{{ $author->website }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <p class="text-muted small mb-4">{{ $posts->total() }} posts by this author</p>
            @forelse($posts as $post)
                @include('bs5::partials.post-card', compact('post'))
            @empty
                <div class="alert alert-light text-center">No posts by this author yet.</div>
            @endforelse
            <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
        </div>
        <aside class="col-lg-4">@php echo dynamic_sidebar('sidebar-1') @endphp</aside>
    </div>
</div>
@endsection
