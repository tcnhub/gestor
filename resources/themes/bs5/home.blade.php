@extends('bs5::layouts.app')

@section('title', site_name())
@section('meta_description', site_tagline())

@section('content')

{{-- ===== HERO (Featured Post) ===== --}}
@if(isset($featuredPost) && $featuredPost)
<section class="hero-section">
    @if($featuredPost->featured_image)
        <img src="{{ asset('storage/' . $featuredPost->featured_image) }}"
             alt="{{ $featuredPost->title }}" class="hero-img">
    @endif
    <div class="hero-content w-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    @foreach($featuredPost->categories->take(2) as $cat)
                        <a href="{{ url('/category/' . $cat->slug) }}" class="hero-badge text-white text-decoration-none me-1">{{ $cat->name }}</a>
                    @endforeach
                    <h1 class="display-5 fw-bold mt-2 mb-3">
                        <a href="{{ $featuredPost->permalink }}" class="text-white text-decoration-none">{{ $featuredPost->title }}</a>
                    </h1>
                    @if($featuredPost->excerpt)
                        <p class="lead text-white-50 mb-3">{{ Str::limit($featuredPost->excerpt, 160) }}</p>
                    @endif
                    <div class="d-flex align-items-center gap-3 text-white-50 small">
                        <span><i class="fa-solid fa-user me-1"></i>{{ $featuredPost->author->display_name }}</span>
                        <span><i class="fa-solid fa-calendar me-1"></i>{{ $featuredPost->published_at?->format(setting('date_format', 'M j, Y')) }}</span>
                        <span><i class="fa-solid fa-eye me-1"></i>{{ number_format($featuredPost->views) }}</span>
                    </div>
                    <a href="{{ $featuredPost->permalink }}" class="btn btn-primary mt-3">
                        Read Article <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== MAIN CONTENT ===== --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- Post List --}}
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0 me-auto">
                    <i class="fa-solid fa-newspaper text-primary me-2"></i>Latest Posts
                </h2>
                <a href="/search" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-rss me-1"></i> All Posts
                </a>
            </div>

            @forelse($posts as $post)
            <article class="card mb-4">
                <div class="row g-0">
                    @if($post->featured_image)
                    <div class="col-md-4">
                        <a href="{{ $post->permalink }}">
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 class="img-fluid h-100 w-100" style="object-fit:cover; border-radius:.75rem 0 0 .75rem;">
                        </a>
                    </div>
                    <div class="col-md-8">
                    @else
                    <div class="col-12">
                    @endif
                        <div class="card-body p-4">
                            {{-- Categories --}}
                            <div class="mb-2">
                                @foreach($post->categories as $cat)
                                    <a href="{{ url('/category/' . $cat->slug) }}"
                                       class="badge badge-category bg-primary bg-opacity-10 text-primary text-decoration-none me-1">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                            {{-- Title --}}
                            <h3 class="h5 fw-bold mb-2">
                                <a href="{{ $post->permalink }}" class="text-dark text-decoration-none">{{ $post->title }}</a>
                            </h3>
                            {{-- Excerpt --}}
                            @if($post->excerpt)
                                <p class="text-muted small mb-3">{{ Str::limit($post->excerpt, 120) }}</p>
                            @endif
                            {{-- Meta --}}
                            <div class="post-meta d-flex flex-wrap gap-3 mb-3">
                                <span><i class="fa-solid fa-user me-1"></i>{{ $post->author->display_name }}</span>
                                <span><i class="fa-solid fa-calendar me-1"></i>{{ $post->published_at?->format(setting('date_format', 'M j, Y')) }}</span>
                                <span><i class="fa-solid fa-eye me-1"></i>{{ number_format($post->views) }}</span>
                                <span><i class="fa-solid fa-comments me-1"></i>{{ $post->allComments->where('status','approved')->count() }}</span>
                            </div>
                            <a href="{{ $post->permalink }}" class="btn btn-outline-primary btn-sm">
                                Read More <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="fa-solid fa-inbox fa-3x mb-3 text-secondary"></i>
                    <h5>No posts published yet</h5>
                    <p class="small">Check back soon!</p>
                </div>
            </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="col-lg-4">
            @php echo dynamic_sidebar('sidebar-1') @endphp
        </aside>
    </div>
</div>
@endsection
