<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchasedItem extends Model
{
    use HasUuids;

    protected $table = 'purchased_items';

    protected $fillable = [
        'user_id',
        'shop_item_id',
        'expires_at',
    ];

    /**
     * Get the user who bought this.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shop item.
     */
    public function shopItem(): BelongsTo
    {
        return $this->belongsTo(ShopItem::class, 'shop_item_id');
    }
}
