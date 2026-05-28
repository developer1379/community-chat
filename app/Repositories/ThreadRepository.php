<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Thread;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ThreadRepository implements ThreadRepositoryInterface
{
    public function getActiveThreads(int $limit = 5): Collection
    {
        return Cache::remember('forum.active_threads', 3600, function () use ($limit) {
            return Thread::with(['user', 'category'])
                ->withCount('posts')
                ->orderBy('updated_at', 'desc')
                ->take($limit)
                ->get();
        });
    }

    public function getCategoryThreadsPaginated(Category $category, int $perPage = 15): LengthAwarePaginator
    {
        // Don't cache paginated views to prevent deep pagination issues, or cache briefly by category ID
        return Cache::remember("forum.category.{$category->id}.threads.page." . request('page', 1), 300, function () use ($category, $perPage) {
            return $category->threads()
                ->with(['user', 'firstPost', 'lastPost.user'])
                ->withCount('posts')
                ->paginate($perPage);
        });
    }

    public function findBySlug(string $slug): ?Thread
    {
        return Thread::where('slug', $slug)->firstOrFail();
    }

    public function createThread(array $data): Thread
    {
        $thread = Thread::create($data);

        // Flush dynamic forum caches to reflect new thread
        Cache::forget('forum.categories');
        Cache::forget('forum.active_threads');
        Cache::forget('forum.threads_count');
        Cache::forget("forum.category.{$thread->category_id}.threads.page.1");

        return $thread;
    }

    public function incrementViews(Thread $thread): void
    {
        $thread->increment('views_count');
    }

    public function getTotalCount(): int
    {
        return Cache::remember('forum.threads_count', 3600, function () {
            return Thread::count();
        });
    }
}
