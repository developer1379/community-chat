<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopItem extends Model
{
    use HasUuids;

    protected $table = 'shop_items';

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'stock',
        'sold_count',
        'rating',
        'rating_count',
        'duration',
        'key',
    ];

    /**
     * Get purchases for this item.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(PurchasedItem::class, 'shop_item_id');
    }

    /**
     * Get reviews for this item.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ShopItemReview::class, 'shop_item_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get interactions (likes, bookmarks) for this item.
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(ShopItemInteraction::class, 'shop_item_id');
    }

    /**
     * Check if liked by user.
     */
    public function isLikedByUser(?string $userId): bool
    {
        if (!$userId) return false;
        return $this->interactions()->where('user_id', $userId)->where('type', 'like')->exists();
    }

    /**
     * Check if bookmarked by user.
     */
    public function isBookmarkedByUser(?string $userId): bool
    {
        if (!$userId) return false;
        return $this->interactions()->where('user_id', $userId)->where('type', 'bookmark')->exists();
    }
}
