<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getActiveUsers(int $limit = 6): Collection;
    public function findByName(string $name): ?User;
    public function getLatestUser(): ?User;
    public function getTotalCount(): int;
    public function updateProfile(User $user, array $data): void;
    public function getMembersFiltered(string $search = '', string $filter = 'all', ?string $currentUserId = null);
    public function followUser(string $followerId, string $followingId): bool;
    public function unfollowUser(string $followerId, string $followingId): bool;
}
