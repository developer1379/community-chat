<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopItemReview extends Model
{
    use HasUuids;

    protected $table = 'shop_item_reviews';

    protected $fillable = [
        'shop_item_id',
        'user_id',
        'rating',
        'review',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shopItem(): BelongsTo
    {
        return $this->belongsTo(ShopItem::class);
    }
}
