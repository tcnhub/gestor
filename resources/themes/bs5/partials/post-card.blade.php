<article class="card mb-4">
    <div class="row g-0">
        @if($post->featured_image)
        <div class="col-4 col-md-3">
            <a href="{{ $post->permalink }}" class="d-block h-100">
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                     alt="{{ $post->title }}"
                     class="img-fluid h-100 w-100"
                     style="object-fit:cover; border-radius:.75rem 0 0 .75rem; min-height:130px">
            </a>
        </div>
        <div class="col-8 col-md-9">
        @else
        <div class="col-12">
        @endif
            <div class="card-body p-4">
                <div class="mb-2">
                    @foreach($post->categories as $cat)
                        <a href="{{ url('/category/' . $cat->slug) }}"
                           class="badge badge-category bg-primary bg-opacity-10 text-primary text-decoration-none me-1">{{ $cat->name }}</a>
                    @endforeach
                </div>
                <h5 class="fw-bold mb-2">
                    <a href="{{ $post->permalink }}" class="text-dark text-decoration-none">{{ $post->title }}</a>
                </h5>
                @if($post->excerpt)
                    <p class="text-muted small mb-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                        {{ $post->excerpt }}
                    </p>
                @endif
                <div class="post-meta d-flex flex-wrap gap-3">
                    <span><i class="fa-solid fa-user me-1"></i>{{ $post->author->display_name }}</span>
                    <span><i class="fa-solid fa-calendar me-1"></i>{{ $post->published_at?->format('M j, Y') }}</span>
                    <span class="ms-auto">
                        <a href="{{ $post->permalink }}" class="btn btn-outline-primary btn-sm">
                            Read <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</article>
