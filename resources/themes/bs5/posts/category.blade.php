@extends('bs5::layouts.app')

@section('title', 'Category: ' . $category->name)
@section('meta_description', $category->meta_description ?? $category->description)

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-dark small">
                <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item active text-white">{{ $category->name }}</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-1"><i class="fa-solid fa-folder-open me-2"></i>{{ $category->name }}</h1>
        @if($category->description)
            <p class="mb-0 text-white-75">{{ $category->description }}</p>
        @endif
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <span class="text-muted small">{{ $posts->total() }} posts in this category</span>
            </div>
            @forelse($posts as $post)
                @include('bs5::partials.post-card', compact('post'))
            @empty
                <div class="card"><div class="card-body text-center py-5 text-muted">
                    <i class="fa-solid fa-inbox fa-2x mb-2"></i><p>No posts in this category yet.</p>
                </div></div>
            @endforelse
            <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
        </div>
        <aside class="col-lg-4">
            @php echo dynamic_sidebar('sidebar-1') @endphp
        </aside>
    </div>
</div>
@endsection
