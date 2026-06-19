<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasUuids;

    protected $fillable = [
        'thread_id',
        'user_id',
        'content',
    ];

    protected static function booted()
    {
        static::created(function ($post) {
            Thread::clearHomepageCaches();
        });

        static::updated(function ($post) {
            Thread::clearHomepageCaches();
        });

        static::deleted(function ($post) {
            Thread::clearHomepageCaches();
        });
    }

    /**
     * Get the thread that contains the post.
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Get the user who made the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get attachments associated with this post.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get reactions associated with this post.
     */
    public function reacts(): HasMany
    {
        return $this->hasMany(React::class);
    }

    /**
     * Get a sentence summarizing who reacted to this post.
     */
    public function getReactsSentenceAttribute(): string
    {
        $reacts = $this->reacts;
        $total = $reacts->count();
        if ($total === 0) {
            return '';
        }

        $names = $reacts->pluck('user.name')->unique()->toArray();
        $names = array_values(array_filter($names));

        if (empty($names)) {
            return "$total " . ($total === 1 ? 'person' : 'people');
        }

        if (count($names) === 1) {
            return $names[0];
        }

        if (count($names) === 2) {
            return $names[0] . ' and ' . $names[1];
        }

        if (count($names) === 3) {
            return $names[0] . ', ' . $names[1] . ' and ' . $names[2];
        }

        $othersCount = $total - 3;
        return $names[0] . ', ' . $names[1] . ', ' . $names[2] . ' and ' . $othersCount . ' ' . ($othersCount === 1 ? 'other' : 'others');
    }

    /**
     * Clear cached posts pages for a thread.
     */
    public static function clearThreadPostsCache($threadId)
    {
        for ($i = 1; $i <= 10; $i++) {
            \Illuminate\Support\Facades\Cache::forget("forum.thread.{$threadId}.posts.page.{$i}");
        }
    }
}
