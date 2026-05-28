<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasUuids;

    protected $fillable = [
        'post_id',
        'thread_id',
        'user_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    /**
     * Get the user who uploaded the attachment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post that owns the attachment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the thread that owns the attachment.
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
