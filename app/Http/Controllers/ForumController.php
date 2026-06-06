<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ThreadRepositoryInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\ImgBBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    protected CategoryRepositoryInterface $categoryRepo;
    protected ThreadRepositoryInterface $threadRepo;
    protected PostRepositoryInterface $postRepo;
    protected UserRepositoryInterface $userRepo;
    protected ImgBBService $imgBBService;

    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        ThreadRepositoryInterface $threadRepo,
        PostRepositoryInterface $postRepo,
        UserRepositoryInterface $userRepo,
        ImgBBService $imgBBService
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->threadRepo = $threadRepo;
        $this->postRepo = $postRepo;
        $this->userRepo = $userRepo;
        $this->imgBBService = $imgBBService;
    }

    public function home()
    {
        $categories = $this->categoryRepo->getAllWithStats();

        // Fetch featured threads
        $featuredThreads = Thread::where('is_featured', true)
            ->with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch most liked thread of the day (preferring those with attachments)
        $mostLikedThread = Thread::whereHas('attachments')
            ->with(['user', 'category', 'attachments'])
            ->withCount(['posts as total_reacts' => function ($query) {
                $query->join('reacts', 'posts.id', '=', 'reacts.post_id');
            }])
            ->orderBy('total_reacts', 'desc')
            ->first();

        if (!$mostLikedThread) {
            $mostLikedThread = Thread::with(['user', 'category', 'attachments'])
                ->withCount(['posts as total_reacts' => function ($query) {
                    $query->join('reacts', 'posts.id', '=', 'reacts.post_id');
                }])
                ->orderBy('total_reacts', 'desc')
                ->first();
        }

        // Sidebar stats
        $stats = [
            'users_count' => $this->userRepo->getTotalCount(),
            'threads_count' => $this->threadRepo->getTotalCount(),
            'posts_count' => $this->postRepo->getTotalCount(),
            'latest_user' => $this->userRepo->getLatestUser(),
        ];

        // Active threads sidebar
        $activeThreads = $this->threadRepo->getActiveThreads(5);

        // Online users
        $onlineUsers = $this->userRepo->getActiveUsers(6);

        // Fetch Latest Threads
        $latestThreads = Thread::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch Viral Threads (sorted by views_count desc)
        $viralThreads = Thread::with(['user', 'category'])
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Fetch top reacted threads for highlights row (preferring attachments)
        $topReactedThreads = Thread::whereHas('attachments')
            ->with(['user', 'category', 'attachments'])
            ->withCount(['posts as total_reacts' => function ($query) {
                $query->join('reacts', 'posts.id', '=', 'reacts.post_id');
            }])
            ->orderBy('total_reacts', 'desc')
            ->take(12)
            ->get();

        if ($topReactedThreads->isEmpty()) {
            $topReactedThreads = Thread::with(['user', 'category', 'attachments'])
                ->withCount(['posts as total_reacts' => function ($query) {
                    $query->join('reacts', 'posts.id', '=', 'reacts.post_id');
                }])
                ->orderBy('total_reacts', 'desc')
                ->take(12)
                ->get();
        }

        return view('forum.home', compact('categories', 'stats', 'activeThreads', 'onlineUsers', 'featuredThreads', 'latestThreads', 'viralThreads', 'mostLikedThread', 'topReactedThreads'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $threads = collect();
        if ($query) {
            $threads = Thread::where('title', 'like', "%{$query}%")
                ->orWhereHas('posts', function($q) use ($query) {
                    $q->where('content', 'like', "%{$query}%");
                })
                ->with(['user', 'category', 'lastPost.user'])
                ->latest()
                ->paginate(15);
        }

        return view('forum.search', compact('threads', 'query'));
    }

    public function category(string $slug)
    {
        $category = $this->categoryRepo->findBySlug($slug);
        $threads = $this->threadRepo->getCategoryThreadsPaginated($category, 15);

        return view('forum.category', compact('category', 'threads'));
    }

    public function thread(string $slug)
    {
        $thread = $this->threadRepo->findBySlug($slug);
        $this->threadRepo->incrementViews($thread);
        $posts = $this->postRepo->getThreadPostsPaginated($thread, 10);

        return view('forum.thread', compact('thread', 'posts'));
    }

    public function reply(Request $request, Thread $thread)
    {

        if ($thread->is_locked) {
            return back()->with('error', 'This thread is locked and cannot be replied to.');
        }

        $request->validate([
            'content' => ['required', 'string', 'min:3'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        $post = $this->postRepo->createPost([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Handle ImgBB attachment uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Upload to ImgBB instead of local storage!
                $imageUrl = $this->imgBBService->upload($file);
                
                if ($imageUrl) {
                    $this->postRepo->createAttachment([
                        'post_id' => $post->id,
                        'thread_id' => $thread->id,
                        'user_id' => Auth::id(),
                        'file_path' => $imageUrl, // save ImgBB absolute url
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                    ]);
                }
            }
        }

        $thread->touch();

        // Reward user with 5 coins for posting a reply
        Auth::user()->addCoins(5, 'reply_posted', "Replied to thread: " . $thread->title);

        return back()->with('success', 'Your reply has been posted successfully!');
    }

    public function editPost(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to edit this post.');
        }

        if ($post->thread->is_locked) {
            return back()->with('error', 'This thread is locked.');
        }

        $request->validate([
            'content' => ['required', 'string', 'min:3'],
        ]);

        $post->update([
            'content' => $request->content,
        ]);

        // Invalidate corresponding thread posts cache so edits are visible immediately
        \Illuminate\Support\Facades\Cache::forget("forum.thread.{$post->thread_id}.posts.page.1");
        \Illuminate\Support\Facades\Cache::forget("forum.thread.{$post->thread_id}.posts.page.2");
        \Illuminate\Support\Facades\Cache::forget("forum.thread.{$post->thread_id}.posts.page.3");
        \Illuminate\Support\Facades\Cache::forget("forum.thread.{$post->thread_id}.posts.page.4");

        return back()->with('success', 'Your post has been successfully edited!');
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
        ]);

        if ($request->hasFile('image')) {
            $url = $this->imgBBService->upload($request->file('image'));
            if ($url) {
                return response()->json(['url' => $url]);
            }
        }

        return response()->json(['error' => 'Failed to upload image'], 400);
    }

    public function mediaIndex(Request $request)
    {
        $search = $request->input('q');
        
        $query = \App\Models\Attachment::where('file_type', 'like', 'image/%')
            ->where('is_private', false)
            ->whereHas('user', function($q) {
                $q->where('is_private', false);
            });

        if ($search) {
            $query->where('file_name', 'like', "%{$search}%");
        }

        $media = $query->with(['thread', 'user'])->latest()->paginate(24);

        return view('forum.media', compact('media', 'search'));
    }
}
