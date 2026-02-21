<?php

/**
 * Contact Form Plugin for Laravel CMS
 *
 * This plugin demonstrates the plugin system by adding a contact form.
 */

// Register plugin activation hook
add_action('plugin_activated_contact-form', function () {
    // Create a contact page if it doesn't exist
    \App\Models\Post::firstOrCreate(
        ['slug' => 'contact'],
        [
            'user_id' => 1,
            'title' => 'Contact',
            'content' => '[contact_form]',
            'status' => 'publish',
            'type' => 'page',
            'published_at' => now(),
        ]
    );
});

// Add routes for contact form
add_action('cms_loaded', function () {
    \Illuminate\Support\Facades\Route::post('/contact-form/submit', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        // Send email notification
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "From: {$request->name} <{$request->email}>\n\nSubject: {$request->subject}\n\n{$request->message}",
                function ($mail) use ($request) {
                    $mail->to(setting('admin_email', 'admin@example.com'))
                        ->subject('Contact Form: ' . $request->subject);
                }
            );
        } catch (\Exception $e) {
            // Silently fail if mail not configured
        }

        return back()->with('contact_success', 'Your message has been sent. We will get back to you soon!');
    })->name('contact.submit');
});

// Add shortcode filter for [contact_form]
add_filter('the_content', function ($content) {
    if (strpos($content, '[contact_form]') !== false) {
        $form = view('contact-form::form')->render();
        $content = str_replace('[contact_form]', $form, $content);
    }
    return $content;
});

// Register plugin views
app()->booted(function () {
    $pluginViewPath = base_path('plugins/contact-form/views');
    if (is_dir($pluginViewPath)) {
        app('view')->addNamespace('contact-form', $pluginViewPath);
    }
});
