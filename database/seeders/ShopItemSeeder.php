<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShopItem;

class ShopItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Username Style',
                'category' => 'Feature Updates',
                'description' => 'Add username colour, shadow, and other styles to make your profile name stand out.',
                'price' => 1999.00,
                'stock' => null,
                'sold_count' => 303,
                'rating' => 4.67,
                'rating_count' => 3,
                'duration' => 'Permanent',
                'key' => 'username_style',
            ],
            [
                'name' => 'Username Change',
                'category' => 'User Access',
                'description' => 'Allows you to change your username on the forum.',
                'price' => 5000.00,
                'stock' => null,
                'sold_count' => 190,
                'rating' => 4.0,
                'rating_count' => 1,
                'duration' => 'One-time',
                'key' => 'username_change',
            ],
            [
                'name' => 'Sticky Thread',
                'category' => 'Promot your content',
                'description' => 'Pin your thread on top of the first page of the forum',
                'price' => 3999.00,
                'stock' => 10,
                'sold_count' => 758,
                'rating' => 5.0,
                'rating_count' => 4,
                'duration' => '1 months',
                'key' => 'sticky_thread',
            ],
            [
                'name' => 'Thread Title Style',
                'category' => 'Promot your content',
                'description' => 'Highlight your thread title with bold letters, any color, shadow, and glow effect for more attention!!!',
                'price' => 1999.00,
                'stock' => 50,
                'sold_count' => 348,
                'rating' => 5.0,
                'rating_count' => 6,
                'duration' => '1 months',
                'key' => 'thread_title_style',
            ],
            [
                'name' => 'Thread Highlight',
                'category' => 'Promot your content',
                'description' => 'Highlight your thread title in lists to make it stand out from other threads.',
                'price' => 1499.00,
                'stock' => null,
                'sold_count' => 364,
                'rating' => 5.0,
                'rating_count' => 2,
                'duration' => '1 months',
                'key' => 'thread_highlight',
            ],
        ];

        // Delete any items that are no longer working
        $keys = array_column($items, 'key');
        ShopItem::whereNotIn('key', $keys)->delete();

        foreach ($items as $item) {
            ShopItem::updateOrCreate(['key' => $item['key']], $item);
        }
    }
}
