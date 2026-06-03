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
        'title_badge',
        'signature',
        'is_private',
        'coins',
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
        $threadsCount = $this->threads()->count();
        $postsCount = $this->posts()->count();
        
        // Sum reactions received on posts created by this user
        $postIds = $this->posts()->pluck('id');
        $reactionsCount = \App\Models\React::whereIn('post_id', $postIds)->count();
        
        return ($threadsCount * 10) + ($postsCount * 5) + ($reactionsCount * 2);
    }

    /**
     * Get computed Anime/Otaku Rank Tier based on activity points.
     */
    public function getComputedAnimeTierAttribute(): array
    {
        $pts = $this->activity_points;

        if ($pts >= 1000) {
            return ['name' => 'Pirate King 🏴‍☠️', 'color' => '#e11d48', 'badge' => 'Legendary'];
        } elseif ($pts >= 500) {
            return ['name' => 'Soul Reaper 💀', 'color' => '#7c3aed', 'badge' => 'Epic'];
        } elseif ($pts >= 200) {
            return ['name' => 'Super Saiyan ⚡', 'color' => '#d97706', 'badge' => 'Elite'];
        } elseif ($pts >= 50) {
            return ['name' => 'Guild Adventurer 🛡️', 'color' => '#2563eb', 'badge' => 'Active'];
        }

        return ['name' => 'Wandering Ninja 🍃', 'color' => '#16a34a', 'badge' => 'Beginner'];
    }

    /**
     * Get the user's avatar URL, falling back to a themed anime character avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        return route('media.proxy.avatar', $this->id);
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
}
