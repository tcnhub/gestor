<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="fa-solid fa-magnifying-glass me-2"></i>{{ $widget->settings['title'] ?? 'Search' }}
    </div>
    <div class="card-body">
        <form action="/search" method="GET" class="d-flex gap-2">
            <input type="search" name="q" class="form-control"
                   placeholder="{{ $widget->settings['placeholder'] ?? 'Search posts...' }}"
                   value="{{ request('q') }}">
            <button type="submit" class="btn btn-primary px-3">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
</div>
