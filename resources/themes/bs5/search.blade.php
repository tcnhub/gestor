@extends('bs5::layouts.app')
@section('title', $query ? 'Search: "' . $query . '"' : 'Search')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="fw-bold mb-3">
            <i class="fa-solid fa-magnifying-glass me-2"></i>
            {{ $query ? 'Results for "' . $query . '"' : 'Search' }}
        </h1>
        <form action="/search" method="GET" class="d-flex gap-2" style="max-width:540px">
            <input type="search" name="q" value="{{ $query }}" placeholder="Search posts..."
                   class="form-control form-control-lg">
            <button type="submit" class="btn btn-light btn-lg px-4">
                <i class="fa-solid fa-magnifying-glass text-primary"></i>
            </button>
        </form>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            @if($query)
                @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <p class="text-muted small mb-4">
                        <i class="fa-solid fa-list-check me-1"></i>
                        Found <strong>{{ $posts->total() }}</strong> result(s) for "{{ $query }}"
                    </p>
                @endif

                @forelse($posts as $post)
                    @include('bs5::partials.post-card', compact('post'))
                @empty
                    <div class="card">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="fa-solid fa-face-frown fa-3x mb-3"></i>
                            <h5>No results found</h5>
                            <p class="small">Try different keywords or browse categories.</p>
                            <a href="/" class="btn btn-outline-primary btn-sm mt-1">
                                <i class="fa-solid fa-house me-1"></i>Back to Home
                            </a>
                        </div>
                    </div>
                @endforelse

                @if($posts instanceof \Illuminate\Pagination\AbstractPaginator)
                    <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
                @endif
            @else
                <div class="card">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fa-solid fa-magnifying-glass fa-3x mb-3"></i>
                        <h5>Start typing to search</h5>
                        <p class="small">Enter keywords above to search through all posts.</p>
                    </div>
                </div>
            @endif
        </div>
        <aside class="col-lg-4">
            @php echo dynamic_sidebar('sidebar-1') @endphp
        </aside>
    </div>
</div>
@endsection
