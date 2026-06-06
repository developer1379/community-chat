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
}
