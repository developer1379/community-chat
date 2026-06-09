<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar_path',
        'banner_color',
        'banner_path',
        'banner_updates_count',
        'title_badge',
        'title_color',
        'username_animation',
        'title_color_updates_count',
        'signature',
        'is_private',
        'coins',
        'is_blocked',
        'is_onboarded',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_private' => 'boolean',
            'is_blocked' => 'boolean',
            'is_onboarded' => 'boolean',
        ];
    }

    /**
     * Get the threads created by the user.
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Get the posts created by the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get search histories for the user.
     */
    public function searchHistories(): HasMany
    {
        return $this->hasMany(SearchHistory::class);
    }

    /**
     * Get attachments uploaded by the user.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get the users that follow this user.
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * Get the users that this user follows.
     */
    public function following(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Check if this user is following another user.
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Get all conversations user is a participant in.
     */
    public function conversations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Conversation::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id);
    }

    /**
     * Count unread messages for the user across all conversations.
     */
    public function unreadMessagesCount(): int
    {
        // Conversations where user is a participant and has unread messages from other users.
        $conversationIds = Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id)
            ->pluck('id');

        return Message::whereIn('conversation_id', $conversationIds)
            ->where('sender_id', '!=', $this->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get dynamic Otaku activity points based on threads, replies, and reactions.
     */
    public function getActivityPointsAttribute(): int
    {
        if ($this->isAdmin()) {
            return 1200; // Admins default to maximum rank (Pirate King) for testing all capabilities
        }

        $threadsCount = $this->threads()->count();
        $postsCount = $this->posts()->count();
        
        // Sum reactions received on posts created by this user
        $postIds = $this->posts()->pluck('id');
        $reactionsCount = \App\Models\React::whereIn('post_id', $postIds)->count();
        
        return ($threadsCount * 10) + ($postsCount * 5) + ($reactionsCount * 2);
    }

    public function getComputedAnimeTierAttribute(): array
    {
        if ($this->isAdmin()) {
            $pk = \App\Models\RankMilestone::where('level', 20)->first();
            if ($pk) {
                return [
                    'name' => $pk->name . ' ' . $pk->icon,
                    'color' => $pk->color,
                    'badge' => $pk->badge,
                    'level' => $pk->level
                ];
            }
        }

        $milestones = \App\Models\RankMilestone::orderBy('level', 'asc')->get();
        
        $currentMilestone = $milestones->first();
        foreach ($milestones as $ms) {
            if ($this->coins >= $ms->coins_required) {
                $currentMilestone = $ms;
            } else {
                break;
            }
        }

        if (!$currentMilestone) {
            return ['name' => 'Wandering Ninja 🍃', 'color' => '#16a34a', 'badge' => 'Beginner', 'level' => 1];
        }

        return [
            'name' => $currentMilestone->name . ' ' . $currentMilestone->icon,
            'color' => $currentMilestone->color,
            'badge' => $currentMilestone->badge,
            'level' => $currentMilestone->level
        ];
    }

    public function getCoinsAttribute($value): int
    {
        return (int) ($value ?? 0);
    }

    /**
     * Get the user's avatar URL, falling back to a themed anime character avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        return route('media.proxy.avatar', $this->id);
    }

    /**
     * Get custom username style classes if purchased.
     */
    public function getUsernameStyleAttribute(): string
    {
        $classes = [];
        if ($this->hasActiveShopItem('username_style')) {
            $classes[] = 'text-indigo-650 dark:text-indigo-400 font-extrabold drop-shadow-[0_0.8px_0.8px_rgba(99,102,241,0.15)] shadow-indigo-500/20';
        }
        
        if ($this->username_animation) {
            if ($this->username_animation === 'glow') $classes[] = 'animate-glow';
            elseif ($this->username_animation === 'pulse') $classes[] = 'animate-pulse';
            elseif ($this->username_animation === 'crackle') $classes[] = 'animate-bolt';
            elseif ($this->username_animation === 'shimmer') $classes[] = 'animate-shimmer';
        }
        
        return implode(' ', $classes);
    }

    /**
     * Get custom username inline color styles based on custom title_color.
     */
    public function getUsernameStyleCssAttribute(): string
    {
        if ($this->title_color) {
            return 'color: ' . $this->title_color . ' !important;';
        }
        return '';
    }

    /**
     * Get all purchased items for the user.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(PurchasedItem::class, 'user_id');
    }

    /**
     * Check if user has an active purchased shop item by key.
     */
    public function hasActiveShopItem(string $key): bool
    {
        return $this->purchases()
            ->whereHas('shopItem', function ($q) use ($key) {
                $q->where('key', $key);
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Get all coin transactions for this user.
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CoinTransaction::class)->orderBy('created_at', 'desc');
    }

    /**
     * Safely add/deduct coins and record a transaction audit.
     */
    public function addCoins(int $amount, string $type, string $description): bool
    {
        $this->coins += $amount;
        $saved = $this->save();

        if ($saved) {
            $this->transactions()->create([
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
            ]);
        }

        return $saved;
    }

    /**
     * Get the bug reports submitted by this user.
     */
    public function bugReports(): HasMany
    {
        return $this->hasMany(BugReport::class);
    }

    /**
     * Get the admin record for this user.
     */
    public function admin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    /**
     * Helper to verify if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->admin()->exists();
    }

    /**
     * Get the system notifications for this user.
     */
    public function systemNotifications(): HasMany
    {
        return $this->hasMany(SystemNotification::class);
    }
}
