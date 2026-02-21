@extends('bs5::layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('meta_keywords', $post->meta_keywords)
@section('og_title', $post->title)
@section('og_description', $post->excerpt)
@if($post->featured_image)
    @section('og_image', asset('storage/' . $post->featured_image))
@endif

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-nav py-2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                @foreach($post->categories->take(1) as $cat)
                    <li class="breadcrumb-item"><a href="{{ url('/category/' . $cat->slug) }}">{{ $cat->name }}</a></li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        {{-- Article --}}
        <div class="col-lg-8">
            <article>
                {{-- Categories --}}
                <div class="mb-3">
                    @foreach($post->categories as $cat)
                        <a href="{{ url('/category/' . $cat->slug) }}"
                           class="badge badge-category bg-primary text-white text-decoration-none me-1 fs-6">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                    @if($post->is_featured)
                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-star me-1"></i>Featured</span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="display-6 fw-bold mb-3">{{ $post->title }}</h1>

                {{-- Meta bar --}}
                <div class="card mb-4 border-0 bg-light rounded-3">
                    <div class="card-body py-3">
                        <div class="d-flex flex-wrap align-items-center gap-3 post-meta">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $post->author->avatar_url }}" class="avatar-sm" alt="{{ $post->author->name }}">
                                <div>
                                    <div class="fw-semibold small text-dark">{{ $post->author->display_name }}</div>
                                    <div class="text-muted" style="font-size:.72rem">Author</div>
                                </div>
                            </div>
                            <span class="vr d-none d-md-block"></span>
                            <span><i class="fa-solid fa-calendar-days me-1"></i>{{ $post->published_at?->format(setting('date_format','F j, Y')) }}</span>
                            <span><i class="fa-solid fa-clock me-1"></i>{{ $post->published_at?->format(setting('time_format','g:i a')) }}</span>
                            <span><i class="fa-solid fa-eye me-1"></i>{{ number_format($post->views) }} views</span>
                            <span><i class="fa-solid fa-comments me-1"></i>{{ $post->allComments->where('status','approved')->count() }} comments</span>
                        </div>
                    </div>
                </div>

                {{-- Featured image --}}
                @if($post->featured_image)
                    <figure class="mb-4">
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                             alt="{{ $post->title }}"
                             class="img-fluid w-100 rounded-3" style="max-height:480px; object-fit:cover;">
                    </figure>
                @endif

                {{-- Content --}}
                <div class="post-content lh-lg">
                    {!! apply_filters('the_content', $post->content) !!}
                </div>

                {{-- Tags --}}
                @if($post->tags->count())
                <div class="mt-4 pt-4 border-top">
                    <span class="fw-semibold text-muted small me-2"><i class="fa-solid fa-tags me-1"></i>Tags:</span>
                    @foreach($post->tags as $tag)
                        <a href="{{ url('/tag/' . $tag->slug) }}"
                           class="badge bg-secondary bg-opacity-10 text-secondary text-decoration-none me-1 mb-1 fw-normal py-2 px-3">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
                @endif

                {{-- Share --}}
                <div class="mt-4 pt-4 border-top d-flex align-items-center gap-2 flex-wrap">
                    <span class="fw-semibold text-muted small me-1"><i class="fa-solid fa-share-nodes me-1"></i>Share:</span>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                       target="_blank" class="btn btn-sm btn-outline-info share-btn" title="Twitter/X">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       target="_blank" class="btn btn-sm btn-outline-primary share-btn" title="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}"
                       target="_blank" class="btn btn-sm share-btn" style="border-color:#0077b5; color:#0077b5" title="LinkedIn">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                       target="_blank" class="btn btn-sm btn-outline-success share-btn" title="WhatsApp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </div>

                {{-- Author Bio --}}
                @if($post->author->bio)
                <div class="card mt-4 border-0 bg-light rounded-3">
                    <div class="card-body d-flex gap-3 align-items-start">
                        <img src="{{ $post->author->avatar_url }}" class="avatar-md" alt="{{ $post->author->name }}">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $post->author->display_name }}</h6>
                            @if($post->author->website)
                                <a href="{{ $post->author->website }}" class="small text-primary d-block mb-1">
                                    <i class="fa-solid fa-link me-1"></i>{{ $post->author->website }}
                                </a>
                            @endif
                            <p class="small text-muted mb-0">{{ $post->author->bio }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Prev / Next --}}
                <div class="row g-3 mt-4">
                    @if($prevPost)
                    <div class="col-6">
                        <a href="{{ $prevPost->permalink }}" class="card h-100 text-decoration-none border border-light-subtle">
                            <div class="card-body p-3">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-arrow-left me-1"></i>Previous</div>
                                <div class="fw-semibold small text-dark" style="line-clamp:2; overflow:hidden">{{ Str::limit($prevPost->title, 55) }}</div>
                            </div>
                        </a>
                    </div>
                    @else <div class="col-6"></div>
                    @endif
                    @if($nextPost)
                    <div class="col-6">
                        <a href="{{ $nextPost->permalink }}" class="card h-100 text-decoration-none border border-light-subtle text-end">
                            <div class="card-body p-3">
                                <div class="text-muted small mb-1">Next <i class="fa-solid fa-arrow-right ms-1"></i></div>
                                <div class="fw-semibold small text-dark">{{ Str::limit($nextPost->title, 55) }}</div>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->count())
                <div class="mt-5">
                    <h4 class="fw-bold mb-4"><i class="fa-solid fa-circle-nodes text-primary me-2"></i>Related Posts</h4>
                    <div class="row g-3">
                        @foreach($relatedPosts as $related)
                        <div class="col-md-4">
                            <a href="{{ $related->permalink }}" class="card h-100 text-decoration-none">
                                @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}"
                                         alt="{{ $related->title }}" class="card-img-top" style="height:150px; object-fit:cover;">
                                @else
                                    <div class="bg-primary bg-opacity-10" style="height:150px;"></div>
                                @endif
                                <div class="card-body p-3">
                                    <p class="small fw-semibold text-dark mb-1">{{ Str::limit($related->title, 60) }}</p>
                                    <p class="text-muted" style="font-size:.75rem">{{ $related->published_at?->format('M j, Y') }}</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Comments Section --}}
                @if($post->comment_status === 'open')
                <div id="comments" class="mt-5">
                    <h4 class="fw-bold mb-4">
                        <i class="fa-solid fa-comments text-primary me-2"></i>
                        Comments ({{ $post->allComments->where('status','approved')->count() }})
                    </h4>

                    @forelse($post->comments as $comment)
                    <div class="mb-4">
                        <div class="comment-bubble d-flex gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->author_name) }}&size=40&color=0d6efd&background=e8f0fe"
                                 class="avatar-sm" alt="{{ $comment->author_name }}">
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <strong class="small">
                                            @if($comment->author_url)
                                                <a href="{{ $comment->author_url }}" class="text-decoration-none">{{ $comment->author_name }}</a>
                                            @else
                                                {{ $comment->author_name }}
                                            @endif
                                        </strong>
                                        <span class="text-muted ms-2" style="font-size:.75rem">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <button class="btn btn-link btn-sm text-muted p-0 text-decoration-none"
                                            onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('d-none')">
                                        <i class="fa-solid fa-reply me-1"></i>Reply
                                    </button>
                                </div>
                                <p class="mb-0 small">{!! nl2br(e($comment->content)) !!}</p>
                            </div>
                        </div>

                        {{-- Replies --}}
                        @foreach($comment->replies as $reply)
                        <div class="comment-bubble reply d-flex gap-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->author_name) }}&size=36&color=6c757d&background=f1f3f5"
                                 class="avatar-sm" style="width:36px;height:36px" alt="{{ $reply->author_name }}">
                            <div class="flex-fill">
                                <strong class="small">{{ $reply->author_name }}</strong>
                                <span class="text-muted ms-2" style="font-size:.72rem">{{ $reply->created_at->diffForHumans() }}</span>
                                <p class="mb-0 small mt-1">{!! nl2br(e($reply->content)) !!}</p>
                            </div>
                        </div>
                        @endforeach

                        {{-- Reply form --}}
                        <div id="reply-{{ $comment->id }}" class="d-none mt-2 ms-5">
                            <form action="{{ url('/posts/' . $post->id . '/comments') }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                @include('bs5::partials.comment-form', ['compact' => true])
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-light border text-center small text-muted">
                        <i class="fa-regular fa-comment-dots fa-2x d-block mb-2"></i>
                        No comments yet. Be the first to share your thoughts!
                    </div>
                    @endforelse

                    {{-- New comment form --}}
                    <div class="card mt-4">
                        <div class="card-header bg-white fw-bold">
                            <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Leave a Comment
                        </div>
                        <div class="card-body">
                            <form action="{{ url('/posts/' . $post->id . '/comments') }}" method="POST">
                                @csrf
                                @include('bs5::partials.comment-form')
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-secondary mt-4 small">
                    <i class="fa-solid fa-lock me-2"></i>Comments are closed for this post.
                </div>
                @endif
            </article>
        </div>

        {{-- Sidebar --}}
        <aside class="col-lg-4">
            <div class="toc-sidebar">
                @php echo dynamic_sidebar('sidebar-1') @endphp
            </div>
        </aside>
    </div>
</div>
@endsection
