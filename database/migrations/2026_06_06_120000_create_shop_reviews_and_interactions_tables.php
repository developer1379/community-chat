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
        Schema::create('shop_item_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shop_item_id');
            $table->uuid('user_id');
            $table->integer('rating');
            $table->text('review')->nullable();
            $table->timestamps();

            $table->foreign('shop_item_id')->references('id')->on('shop_items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['shop_item_id', 'user_id']); // One review per user per item
        });

        Schema::create('shop_item_interactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shop_item_id');
            $table->uuid('user_id');
            $table->string('type'); // 'like', 'bookmark'
            $table->timestamps();

            $table->foreign('shop_item_id')->references('id')->on('shop_items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['shop_item_id', 'user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_item_interactions');
        Schema::dropIfExists('shop_item_reviews');
    }
};
