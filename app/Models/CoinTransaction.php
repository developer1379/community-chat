<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinTransaction extends Model
{
    use HasUuids;

    // Disable standard timestamps, we only use custom created_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
    ];

    protected $dates = [
        'created_at',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
