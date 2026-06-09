<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    protected $table = 'search_histories';

    protected $fillable = [
        'user_id',
        'query',
    ];

    /**
     * Get the user that performed this search.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
