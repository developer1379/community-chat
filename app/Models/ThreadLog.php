<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreadLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'thread_id',
        'user_id',
        'action',
        'changes',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    /**
     * Get the thread that is associated with this log.
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class)->withTrashed();
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
