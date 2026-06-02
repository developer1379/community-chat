<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coin_rules', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. 'thread_created'
            $table->string('label');
            $table->string('description');
            $table->integer('amount');
            $table->string('icon'); // e.g. 3D emoji or Material symbol name
            $table->timestamps();
        });

        // Seed default earning rules
        DB::table('coin_rules')->insert([
            [
                'id' => 'thread_created',
                'label' => 'Start New Thread',
                'description' => 'Publish engaging discussion topics in any category',
                'amount' => 15,
                'icon' => '🔥',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'reply_posted',
                'label' => 'Post a Reply',
                'description' => 'Contribute helpful comments and responses inside discussions',
                'amount' => 5,
                'icon' => '⚡',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'reaction_received',
                'label' => 'Receive Reactions',
                'description' => 'Earn gold coins when other members react to your posts',
                'amount' => 2,
                'icon' => '🏆',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_rules');
    }
};
