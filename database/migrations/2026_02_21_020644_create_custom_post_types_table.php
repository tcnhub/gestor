<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('custom_post_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'portfolio'
            $table->string('label'); // e.g., 'Portfolios'
            $table->string('singular_label'); // e.g., 'Portfolio'
            $table->text('description')->nullable();
            $table->json('supports')->nullable(); // ['title', 'editor', 'thumbnail', 'comments', 'revisions']
            $table->boolean('public')->default(true);
            $table->boolean('hierarchical')->default(false);
            $table->boolean('has_archive')->default(true);
            $table->string('rewrite_slug')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_post_types');
    }
};
