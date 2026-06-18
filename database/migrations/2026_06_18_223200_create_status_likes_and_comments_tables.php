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
        Schema::create('status_likes', function (Blueprint $table) {
            $table->id();
            $table->uuid('status_owner_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('status_owner_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unique(['status_owner_id', 'user_id']);
        });

        Schema::create('status_comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('status_owner_id');
            $table->uuid('user_id');
            $table->text('comment');
            $table->timestamps();

            $table->foreign('status_owner_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_comments');
        Schema::dropIfExists('status_likes');
    }
};
