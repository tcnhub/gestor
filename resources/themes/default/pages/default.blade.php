@extends('default::layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <article class="flex-1 max-w-3xl">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">{{ $post->title }}</h1>
            <div class="prose prose-blue max-w-none">
                {!! apply_filters('the_content', $post->content) !!}
            </div>

            @if($post->comment_status === 'open')
            <div id="comments" class="mt-12">
                <h3 class="text-xl font-bold mb-6">Comments</h3>
                @forelse($post->comments as $comment)
                <div class="mb-4 p-4 bg-white rounded-xl border border-gray-100">
                    <strong class="text-sm">{{ $comment->author_name }}</strong>
                    <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                    <p class="text-sm text-gray-700 mt-2">{{ $comment->content }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-sm">No comments yet.</p>
                @endforelse

                <div class="mt-6 bg-white p-6 rounded-xl border">
                    <h4 class="font-bold mb-4">Leave a Comment</h4>
                    <form action="{{ url('/posts/' . $post->id . '/comments') }}" method="POST">
                        @csrf
                        @include('default::partials.comment-form')
                    </form>
                </div>
            </div>
            @endif
        </article>
        <aside class="lg:w-80">@php echo dynamic_sidebar('sidebar-1') @endphp</aside>
    </div>
</div>
@endsection
