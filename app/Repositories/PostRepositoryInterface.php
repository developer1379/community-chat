<?php

namespace App\Repositories;

use App\Models\Thread;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function getThreadPostsPaginated(Thread $thread, int $perPage = 10): LengthAwarePaginator;
    public function createPost(array $data): Post;
    public function createAttachment(array $data): void;
    public function getTotalCount(): int;
}
