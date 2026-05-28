<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Thread;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ThreadRepositoryInterface
{
    public function getActiveThreads(int $limit = 5): Collection;
    public function getCategoryThreadsPaginated(Category $category, int $perPage = 15): LengthAwarePaginator;
    public function findBySlug(string $slug): ?Thread;
    public function createThread(array $data): Thread;
    public function incrementViews(Thread $thread): void;
    public function getTotalCount(): int;
}
