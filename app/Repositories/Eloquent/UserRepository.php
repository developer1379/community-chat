<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getActiveUsers(int $limit = 6): Collection
    {
        return User::latest()->take($limit)->get();
    }

    public function findByName(string $name): ?User
    {
        return User::where('name', $name)->firstOrFail();
    }

    public function getLatestUser(): ?User
    {
        return User::latest()->first();
    }

    public function getTotalCount(): int
    {
        return User::count();
    }

    public function updateProfile(User $user, array $data): void
    {
        $user->update($data);
    }

    public function getMembersFiltered(string $search = '', string $filter = 'all', ?string $currentUserId = null)
    {
        $query = User::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('title_badge', 'like', '%' . $search . '%');
            });
        }

        if ($filter === 'following' && $currentUserId) {
            $query->whereHas('followers', function ($q) use ($currentUserId) {
                $q->where('follower_id', $currentUserId);
            });
        } elseif ($filter === 'followers' && $currentUserId) {
            $query->whereHas('following', function ($q) use ($currentUserId) {
                $q->where('following_id', $currentUserId);
            });
        } elseif ($filter === 'active') {
            $query->withCount('posts')->orderByDesc('posts_count');
        } elseif ($filter === 'newest') {
            $query->latest();
        } else {
            $query->orderBy('name');
        }

        return $query->paginate(12)->withQueryString();
    }

    public function followUser(string $followerId, string $followingId): bool
    {
        $follower = User::find($followerId);
        if ($follower && $followerId !== $followingId) {
            $follower->following()->syncWithoutDetaching([$followingId]);
            return true;
        }
        return false;
    }

    public function unfollowUser(string $followerId, string $followingId): bool
    {
        $follower = User::find($followerId);
        if ($follower) {
            $follower->following()->detach($followingId);
            return true;
        }
        return false;
    }
}
