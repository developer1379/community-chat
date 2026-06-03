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
            $table->string('title_color')->nullable()->after('title_badge');
        });

        Schema::table('threads', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('is_locked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('title_color');
        });

        Schema::table('threads', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};
