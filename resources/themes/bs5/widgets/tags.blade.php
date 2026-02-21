@php
    $tags = \App\Models\Category::tags()
        ->withCount(['posts' => fn($q) => $q->published()])
        ->orderByDesc('posts_count')
        ->limit(30)
        ->get();
@endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="fa-solid fa-tags me-2"></i>{{ $widget->settings['title'] ?? 'Tags' }}
    </div>
    <div class="card-body">
        @forelse($tags as $tag)
            @php
                $sizes = ['fs-6', 'fs-6', 'fs-5'];
                $sz = $sizes[min((int)($tag->posts_count / 2), 2)];
            @endphp
            <a href="/tag/{{ $tag->slug }}"
               class="badge text-decoration-none me-1 mb-2 {{ $sz }}"
               style="background-color:var(--bs-primary-bg-subtle, #cfe2ff);color:var(--bs-primary-text-emphasis, #052c65);">
                {{ $tag->name }}
                <span class="opacity-75 ms-1" style="font-size:.7em;">({{ $tag->posts_count }})</span>
            </a>
        @empty
            <p class="text-muted small mb-0">
                <i class="fa-solid fa-tag me-1"></i> No tags yet.
            </p>
        @endforelse
    </div>
</div>
