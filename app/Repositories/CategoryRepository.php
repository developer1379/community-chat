<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllWithStats(): Collection
    {
        return Cache::remember('forum.categories', 3600, function () {
            $categories = Category::withCount('threads')
                ->orderBy('order')
                ->get();

            foreach ($categories as $cat) {
                $cat->posts_count = Post::whereIn('thread_id', $cat->threads->pluck('id'))->count();
                $cat->latest_thread = $cat->threads()->with(['user', 'lastPost.user'])->latest()->first();
            }

            return $categories;
        });
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->firstOrFail();
    }
}
