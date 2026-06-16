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
        'prefix',
        'title',
        'slug',
        'views_count',
        'is_pinned',
        'is_locked',
        'is_featured',
        'is_title_styled',
        'is_highlighted',
        'title_color',
        'title_animation',
        'tags',
    ];

    public static $prefixes = [
        'AI Fake' => [
            'group' => 'Thread Type',
            'bg' => '#f59e0b',
            'color' => '#1e1b4b',
        ],
        'Bollywood Actress' => [
            'group' => 'Indian',
            'bg' => '#3b82f6',
            'color' => '#ffffff',
        ],
        'Telugu Actress' => [
            'group' => 'Indian',
            'bg' => '#10b981',
            'color' => '#ffffff',
        ],
        'Tamil Actress' => [
            'group' => 'Indian',
            'bg' => '#ef4444',
            'color' => '#ffffff',
        ],
        'Kannada Actress' => [
            'group' => 'Indian',
            'bg' => '#8b5cf6',
            'color' => '#ffffff',
        ],
        'Punjabi Actress' => [
            'group' => 'Indian',
            'bg' => '#f59e0b',
            'color' => '#ffffff',
        ],
        'Odia actress' => [
            'group' => 'Indian',
            'bg' => '#06b6d4',
            'color' => '#ffffff',
        ],
        'South Actress' => [
            'group' => 'Indian',
            'bg' => '#14b8a6',
            'color' => '#ffffff',
        ],
        'Malayalam Actress' => [
            'group' => 'Indian',
            'bg' => '#ec4899',
            'color' => '#ffffff',
        ],
        'Bengali Actress' => [
            'group' => 'Indian',
            'bg' => '#2563eb',
            'color' => '#ffffff',
        ],
        'Marathi Actress' => [
            'group' => 'Indian',
            'bg' => '#ea580c',
            'color' => '#ffffff',
        ],
        'Bhojpuri Actress' => [
            'group' => 'Indian',
            'bg' => '#84cc16',
            'color' => '#ffffff',
        ],
        'Indian Actress' => [
            'group' => 'Indian',
            'bg' => '#475569',
            'color' => '#ffffff',
        ],
        'Pakistani Actress' => [
            'group' => 'Other Countries',
            'bg' => '#16a34a',
            'color' => '#ffffff',
        ],
        'Srilankan Actress' => [
            'group' => 'Other Countries',
            'bg' => '#0284c7',
            'color' => '#ffffff',
        ],
        'Bangladeshi Actress' => [
            'group' => 'Other Countries',
            'bg' => '#dc2626',
            'color' => '#ffffff',
        ],
        'Other Celebrity' => [
            'group' => 'Other Countries',
            'bg' => '#4b5563',
            'color' => '#ffffff',
        ],
        'Hentai/Cartoon' => [
            'group' => 'Other theme',
            'bg' => '#db2777',
            'color' => '#ffffff',
        ],
        'Shemale' => [
            'group' => 'Other theme',
            'bg' => '#7c3aed',
            'color' => '#ffffff',
        ],
        'TV Actress' => [
            'group' => 'Other theme',
            'bg' => '#2563eb',
            'color' => '#ffffff',
        ],
        'How to' => [
            'group' => 'Help & Support',
            'bg' => '#0d9488',
            'color' => '#ffffff',
        ],
        'Suggestion' => [
            'group' => 'Help & Support',
            'bg' => '#ca8a04',
            'color' => '#ffffff',
        ],
        'Tips' => [
            'group' => 'Help & Support',
            'bg' => '#0891b2',
            'color' => '#ffffff',
        ],
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
            'is_title_styled' => 'boolean',
            'is_highlighted' => 'boolean',
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

    /**
     * Get the styled prefix badge HTML.
     */
    public function getPrefixBadgeAttribute(): string
    {
        if (!$this->prefix || !isset(self::$prefixes[$this->prefix])) {
            return '';
        }
        $config = self::$prefixes[$this->prefix];
        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shadow-sm mr-1.5 align-middle select-none" style="background-color: ' . $config['bg'] . '; color: ' . $config['color'] . ';">' . e($this->prefix) . '</span>';
    }
}
