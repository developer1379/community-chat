<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopItemInteraction extends Model
{
    use HasUuids;

    protected $table = 'shop_item_interactions';

    protected $fillable = [
        'shop_item_id',
        'user_id',
        'type', // 'like', 'bookmark'
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
