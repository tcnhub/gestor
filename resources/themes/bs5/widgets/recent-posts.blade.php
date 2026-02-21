@php
    $count = $widget->settings['count'] ?? 5;
    $recentPosts = \App\Models\Post::published()
        ->ofType('post')
        ->with('categories')
        ->orderByDesc('published_at')
        ->limit($count)
        ->get();
@endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="fa-solid fa-clock me-2"></i>{{ $widget->settings['title'] ?? 'Recent Posts' }}
    </div>
    <div class="list-group list-group-flush">
        @forelse($recentPosts as $rPost)
            <a href="{{ $rPost->permalink }}"
               class="list-group-item list-group-item-action px-3 py-2 text-decoration-none">
                <div class="d-flex gap-3 align-items-start">
                    @if($rPost->featured_image)
                        <img src="{{ $rPost->featured_image }}" alt="{{ $rPost->title }}"
                             class="rounded flex-shrink-0"
                             style="width:60px;height:45px;object-fit:cover;">
                    @else
                        <div class="rounded flex-shrink-0 bg-light d-flex align-items-center justify-content-center"
                             style="width:60px;height:45px;">
                            <i class="fa-solid fa-image text-muted small"></i>
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <div class="fw-semibold small text-dark lh-sm mb-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $rPost->title }}
                        </div>
                        <div class="text-muted" style="font-size:.75rem;">
                            <i class="fa-regular fa-calendar me-1"></i>
                            {{ $rPost->published_at?->format('M j, Y') }}
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="list-group-item text-muted small text-center py-3">
                <i class="fa-solid fa-inbox me-1"></i> No posts yet.
            </div>
        @endforelse
    </div>
</div>
