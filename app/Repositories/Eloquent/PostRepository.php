<?php

namespace App\Repositories\Eloquent;

use App\Models\Thread;
use App\Models\Post;
use App\Models\Attachment;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PostRepository implements PostRepositoryInterface
{
    public function getThreadPostsPaginated(Thread $thread, int $perPage = 10): LengthAwarePaginator
    {
        return Cache::remember("forum.thread.{$thread->id}.posts.page." . request('page', 1), 300, function () use ($thread, $perPage) {
            return $thread->posts()->with(['user', 'attachments', 'reacts.user'])->paginate($perPage);
        });
    }

    public function createPost(array $data): Post
    {
        $post = Post::create($data);

        // Invalidate cache since stats and reply listings changed
        Post::clearThreadPostsCache($post->thread_id);
        Cache::forget("forum.categories");
        Cache::forget("forum.posts_count");
        Cache::forget("forum.active_threads");

        return $post;
    }

    public function createAttachment(array $data): void
    {
        Attachment::create($data);
        
        // Invalidate corresponding thread post cache since attachments are updated
        if (isset($data['thread_id'])) {
            Post::clearThreadPostsCache($data['thread_id']);
        }
    }

    public function getTotalCount(): int
    {
        return Cache::remember('forum.posts_count', 3600, function () {
            return Post::count();
        });
    }
}
