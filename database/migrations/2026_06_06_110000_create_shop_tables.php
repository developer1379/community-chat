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
        Schema::create('shop_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('category'); // Feature Updates, Promot your content, User Access, Private threads
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->nullable(); // null = unlimited
            $table->integer('sold_count')->default(0);
            $table->decimal('rating', 3, 2)->default(5.0);
            $table->integer('rating_count')->default(0);
            $table->string('duration')->default('Permanent');
            $table->string('key')->unique();
            $table->timestamps();
        });

        Schema::create('purchased_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('shop_item_id');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shop_item_id')->references('id')->on('shop_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchased_items');
        Schema::dropIfExists('shop_items');
    }
};
