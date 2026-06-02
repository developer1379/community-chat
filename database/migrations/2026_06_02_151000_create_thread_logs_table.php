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
        Schema::create('thread_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('thread_id')->nullable()->index();
            $table->uuid('user_id')->index();
            $table->string('action'); // 'edit', 'delete'
            $table->json('changes')->nullable(); // JSON object showing before/after changes
            $table->timestamps();

            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_logs');
    }
};
