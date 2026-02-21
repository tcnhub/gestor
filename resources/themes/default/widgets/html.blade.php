<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    @if($widget->title)
    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $widget->title }}</h3>
    @endif
    <div class="text-sm text-gray-700">
        {!! $settings['content'] ?? '' !!}
    </div>
</div>
