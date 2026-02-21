<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $widget->title ?? 'Search' }}</h3>
    <form action="/search" method="GET" class="flex gap-2">
        <input type="search" name="q" value="{{ request('q') }}"
               placeholder="Search..."
               class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>
