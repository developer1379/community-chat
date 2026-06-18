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
        Schema::create('status_views', function (Blueprint $table) {
            $table->id();
            $table->uuid('status_owner_id');
            $table->uuid('viewer_id');
            $table->timestamps();

            $table->foreign('status_owner_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('viewer_id')->references('id')->on('users')->cascadeOnDelete();

            // Unique index to ensure a user is logged once per status
            $table->unique(['status_owner_id', 'viewer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_views');
    }
};
