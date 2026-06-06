<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'views_count',
        'is_pinned',
        'is_locked',
        'is_featured',
        'tags',
    ];
    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
    /**
     * Get the category that owns the thread.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that created the thread.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all posts for this thread.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the first post (body of the thread).
     */
    public function firstPost(): HasOne
    {
        return $this->hasOne(Post::class)->oldestOfMany();
    }

    /**
     * Get the latest post for the thread.
     */
    public function lastPost(): HasOne
    {
        return $this->hasOne(Post::class)->latestOfMany();
    }

    /**
     * Get attachments linked directly to the thread (optional).
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
