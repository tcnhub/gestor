@extends('bs5::layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
<div class="bg-light border-bottom py-4">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                @if($post->parent)
                    <li class="breadcrumb-item"><a href="{{ $post->parent->permalink }}">{{ $post->parent->title }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $post->title }}</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-0 mt-2">{{ $post->title }}</h1>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="post-content lh-lg">
                        {!! apply_filters('the_content', $post->content) !!}
                    </div>

                    @if($post->comment_status === 'open')
                    <div id="comments" class="mt-5 pt-4 border-top">
                        <h5 class="fw-bold mb-4">
                            <i class="fa-solid fa-comments text-primary me-2"></i>Comments
                        </h5>
                        @forelse($post->comments as $comment)
                        <div class="d-flex gap-3 mb-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->author_name) }}&size=40"
                                 class="avatar-sm" alt="{{ $comment->author_name }}">
                            <div class="flex-fill comment-bubble">
                                <strong class="small">{{ $comment->author_name }}</strong>
                                <span class="text-muted ms-2" style="font-size:.73rem">{{ $comment->created_at->diffForHumans() }}</span>
                                <p class="mb-0 mt-2 small">{{ $comment->content }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted small">No comments yet.</p>
                        @endforelse

                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Leave a Comment</h6>
                            <form action="{{ url('/posts/' . $post->id . '/comments') }}" method="POST">
                                @csrf
                                @include('bs5::partials.comment-form')
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <aside class="col-lg-4">
            @php echo dynamic_sidebar('sidebar-1') @endphp
        </aside>
    </div>
</div>
@endsection
