<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusView extends Model
{
    protected $fillable = [
        'status_owner_id',
        'viewer_id',
    ];

    /**
     * Get the user who owns the status.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_owner_id');
    }

    /**
     * Get the user who viewed the status.
     */
    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }
}
