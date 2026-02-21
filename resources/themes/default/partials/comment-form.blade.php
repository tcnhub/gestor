<div class="grid grid-cols-1 {{ isset($compact) ? '' : 'sm:grid-cols-2' }} gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
        <input type="text" name="author_name" required
               value="{{ old('author_name', auth()->user()?->name) }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
        <input type="email" name="author_email" required
               value="{{ old('author_email', auth()->user()?->email) }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    @if(!isset($compact))
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
        <input type="url" name="author_url"
               value="{{ old('author_url', auth()->user()?->website) }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    @endif
</div>
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Comment *</label>
    <textarea name="content" rows="{{ isset($compact) ? 2 : 4 }}" required
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('content') }}</textarea>
</div>
<button type="submit"
        class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
    {{ isset($compact) ? 'Post Reply' : 'Post Comment' }}
</button>
