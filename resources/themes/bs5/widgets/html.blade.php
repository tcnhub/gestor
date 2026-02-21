<div class="card border-0 shadow-sm mb-4">
    @if(!empty($widget->settings['title']))
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="fa-solid fa-code me-2"></i>{{ $widget->settings['title'] }}
        </div>
    @endif
    <div class="card-body">
        {!! $widget->settings['content'] ?? '' !!}
    </div>
</div>
