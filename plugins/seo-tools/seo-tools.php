<?php

/**
 * SEO Tools Plugin for Laravel CMS
 */

// Add Twitter Card meta tags to head
add_action('wp_head', function () {
    $post = view()->shared('post', null);
    if (!$post) return;

    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . e($post->meta_title ?? $post->title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . e($post->meta_description ?? $post->excerpt) . '">' . "\n";
    if ($post->featured_image) {
        echo '<meta name="twitter:image" content="' . asset('storage/' . $post->featured_image) . '">' . "\n";
    }
});

// Add JSON-LD structured data
add_action('wp_head', function () {
    $post = view()->shared('post', null);
    if (!$post || $post->type !== 'post') return;

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $post->excerpt,
        'author' => [
            '@type' => 'Person',
            'name' => $post->author->display_name,
        ],
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
    ];

    if ($post->featured_image) {
        $schema['image'] = asset('storage/' . $post->featured_image);
    }

    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
});

// Filter content to add reading time estimate
add_filter('the_content', function ($content) {
    if (!request()->is('blog/*')) return $content;

    $wordCount = str_word_count(strip_tags($content));
    $readingTime = ceil($wordCount / 200); // avg 200 words per minute

    $badge = '<div class="flex items-center gap-1 text-sm text-gray-500 mb-6 p-3 bg-gray-50 rounded-lg">'
        . '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        . "<span>Estimated reading time: {$readingTime} min ({$wordCount} words)</span></div>";

    return $badge . $content;
});
