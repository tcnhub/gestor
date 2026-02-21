@php
    $categories = \App\Models\Category::categories()
        ->withCount(['posts' => fn($q) => $q->published()])
        ->orderBy('name')
        ->get();
@endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="fa-solid fa-folder me-2"></i>{{ $widget->settings['title'] ?? 'Categories' }}
    </div>
    <div class="list-group list-group-flush">
        @forelse($categories as $cat)
            <a href="/category/{{ $cat->slug }}"
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-3 py-2 text-decoration-none">
                <span class="small">
                    <i class="fa-solid fa-angle-right me-2 text-primary" style="font-size:.7rem;"></i>
                    {{ $cat->name }}
                </span>
                <span class="badge bg-primary rounded-pill">{{ $cat->posts_count }}</span>
            </a>
        @empty
            <div class="list-group-item text-muted small text-center py-3">
                <i class="fa-solid fa-folder-open me-1"></i> No categories yet.
            </div>
        @endforelse
    </div>
</div>
