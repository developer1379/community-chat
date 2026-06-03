<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugReport extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'steps',
        'severity',
        'status',
    ];

    /**
     * Get the user who reported the bug.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
