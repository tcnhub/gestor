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
        Schema::table('users', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->string('bio')->nullable()->after('display_name');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('website')->nullable()->after('avatar');
            $table->string('status')->default('active')->after('website'); // active, inactive, banned
            $table->timestamp('last_login_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'bio', 'avatar', 'website', 'status', 'last_login_at']);
        });
    }
};
