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
        Schema::create('rank_milestones', function (Blueprint $table) {
            $table->integer('level')->primary();
            $table->string('name');
            $table->integer('coins_required');
            $table->string('icon'); // Colorful 3D material icon or emoji representation
            $table->string('color'); // Glow HSL or Hex code
            $table->string('badge'); // e.g. 'Beginner', 'Elite', 'Legendary'
            $table->timestamps();
        });

        // Seed 20 progressive stages/levels
        $milestones = [
            ['level' => 1, 'name' => 'Wandering Ninja', 'coins_required' => 0, 'icon' => '🍃', 'color' => '#10b981', 'badge' => 'Novice'],
            ['level' => 2, 'name' => 'Guild Recruit', 'coins_required' => 100, 'icon' => '🛡️', 'color' => '#3b82f6', 'badge' => 'Recruit'],
            ['level' => 3, 'name' => 'Iron Adventurer', 'coins_required' => 250, 'icon' => '⚔️', 'color' => '#6b7280', 'badge' => 'Active'],
            ['level' => 4, 'name' => 'Bronze Warrior', 'coins_required' => 450, 'icon' => '🥉', 'color' => '#b45309', 'badge' => 'Warrior'],
            ['level' => 5, 'name' => 'Silver Defender', 'coins_required' => 700, 'icon' => '🥈', 'color' => '#9ca3af', 'badge' => 'Guardian'],
            ['level' => 6, 'name' => 'Golden Knight', 'coins_required' => 1000, 'icon' => '🥇', 'color' => '#fbbf24', 'badge' => 'Elite'],
            ['level' => 7, 'name' => 'Platinum Paladin', 'coins_required' => 1350, 'icon' => '💠', 'color' => '#22d3ee', 'badge' => 'Champion'],
            ['level' => 8, 'name' => 'Emerald Healer', 'coins_required' => 1750, 'icon' => '💚', 'color' => '#34d399', 'badge' => 'Support'],
            ['level' => 9, 'name' => 'Sapphire Mage', 'coins_required' => 2200, 'icon' => '💙', 'color' => '#60a5fa', 'badge' => 'Sorcerer'],
            ['level' => 10, 'name' => 'Ruby Warlock', 'coins_required' => 2700, 'icon' => '❤️', 'color' => '#f87171', 'badge' => 'Warlock'],
            ['level' => 11, 'name' => 'Diamond Mystic', 'coins_required' => 3300, 'icon' => '💎', 'color' => '#a5f3fc', 'badge' => 'Master'],
            ['level' => 12, 'name' => 'Super Saiyan', 'coins_required' => 4000, 'icon' => '⚡', 'color' => '#f59e0b', 'badge' => 'Hero'],
            ['level' => 13, 'name' => 'Elite Ranger', 'coins_required' => 4800, 'icon' => '🏹', 'color' => '#10b981', 'badge' => 'Marksman'],
            ['level' => 14, 'name' => 'Master Assassin', 'coins_required' => 5700, 'icon' => '🗡️', 'color' => '#ef4444', 'badge' => 'Assassin'],
            ['level' => 15, 'name' => 'Shadow Shinobi', 'coins_required' => 6700, 'icon' => '🥷', 'color' => '#4b5563', 'badge' => 'Shadow'],
            ['level' => 16, 'name' => 'Soul Reaper', 'coins_required' => 7800, 'icon' => '💀', 'color' => '#8b5cf6', 'badge' => 'Reaper'],
            ['level' => 17, 'name' => 'Astral Guardian', 'coins_required' => 9000, 'icon' => '🌌', 'color' => '#ec4899', 'badge' => 'Epic'],
            ['level' => 18, 'name' => 'Legendary Hero', 'coins_required' => 10500, 'icon' => '👑', 'color' => '#f59e0b', 'badge' => 'Legend'],
            ['level' => 19, 'name' => 'Mythic Overlord', 'coins_required' => 12500, 'icon' => '🐉', 'color' => '#dc2626', 'badge' => 'Overlord'],
            ['level' => 20, 'name' => 'Pirate King', 'coins_required' => 15000, 'icon' => '🏴‍☠️', 'color' => '#be123c', 'badge' => 'Legendary'],
        ];

        foreach ($milestones as $ms) {
            $ms['created_at'] = now();
            $ms['updated_at'] = now();
            DB::table('rank_milestones')->insert($ms);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rank_milestones');
    }
};
