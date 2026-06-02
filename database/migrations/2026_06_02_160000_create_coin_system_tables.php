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
        // 1. Add coins column to users table (giving a 100 coin welcome gift!)
        Schema::table('users', function (Blueprint $table) {
            $table->integer('coins')->default(100)->after('is_private');
        });

        // 2. Create the coin_transactions table
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->integer('amount');
            $table->string('type');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('coins');
        });
    }
};
