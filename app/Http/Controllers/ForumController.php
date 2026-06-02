<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ThreadRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
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

        return view('forum.home', compact('categories', 'stats', 'activeThreads', 'onlineUsers'));
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
