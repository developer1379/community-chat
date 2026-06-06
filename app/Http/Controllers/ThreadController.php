<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ThreadRepositoryInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\ImgBBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
    protected CategoryRepositoryInterface $categoryRepo;
    protected ThreadRepositoryInterface $threadRepo;
    protected PostRepositoryInterface $postRepo;
    protected ImgBBService $imgBBService;

    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        ThreadRepositoryInterface $threadRepo,
        PostRepositoryInterface $postRepo,
        ImgBBService $imgBBService
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->threadRepo = $threadRepo;
        $this->postRepo = $postRepo;
        $this->imgBBService = $imgBBService;
    }

    public function create(string $slug)
    {
        $category = $this->categoryRepo->findBySlug($slug);
        $categories = $this->categoryRepo->getAllWithStats();
        return view('forum.create', compact('category', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'tags' => ['nullable', 'string', 'max:255'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        $slug = Str::slug($request->title) . '-' . Str::random(5);

        $thread = $this->threadRepo->createThread([
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
            'tags' => $request->tags,
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
                        'file_path' => $imageUrl,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                    ]);
                }
            }
        }

        // Reward user with 15 coins for creating a thread
        Auth::user()->addCoins(15, 'thread_created', "Created thread: " . $thread->title);

        return redirect()->route('threads.show', $thread->slug)->with('success', 'Your thread has been created successfully!');
    }

    public function edit(string $slug)
    {
        $thread = $this->threadRepo->findBySlug($slug);
        abort_if(Auth::id() !== $thread->user_id, 403);
        $categories = $this->categoryRepo->getAllWithStats();
        
        return view('forum.edit', compact('thread', 'categories'));
    }

    public function update(Request $request, \App\Models\Thread $thread)
    {
        abort_if(Auth::id() !== $thread->user_id, 403);

        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'tags' => ['nullable', 'string', 'max:255'],
        ]);

        $oldData = [
            'title' => $thread->title,
            'category_id' => $thread->category_id,
            'tags' => $thread->tags,
            'content' => $thread->firstPost?->content,
        ];

        $thread->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'tags' => $request->tags,
        ]);

        $thread->firstPost?->update([
            'content' => $request->content,
        ]);

        $newData = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'tags' => $request->tags,
            'content' => $request->content,
        ];

        \App\Models\ThreadLog::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'action' => 'edit',
            'changes' => [
                'before' => $oldData,
                'after' => $newData,
            ],
        ]);

        return redirect()->route('threads.show', $thread->slug)->with('success', 'Your thread has been updated successfully!');
    }

    public function destroy(\App\Models\Thread $thread)
    {
        abort_if(Auth::id() !== $thread->user_id, 403);

        \App\Models\ThreadLog::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'action' => 'delete',
            'changes' => [
                'title' => $thread->title,
                'slug' => $thread->slug,
            ],
        ]);

        $thread->delete();

        return redirect()->route('home')->with('success', 'Your thread has been deleted.');
    }

    public function feature(\App\Models\Thread $thread)
    {
        abort_if(Auth::id() !== $thread->user_id, 403);

        if ($thread->is_featured) {
            return redirect()->back()->with('error', 'This thread is already featured.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $hasShopFeature = $user->hasActiveShopItem('featured_homepage_thread');

        if (!$hasShopFeature && $user->coins < 50) {
            return redirect()->back()->with('error', 'You do not have enough coins to feature this thread. (Requires 50 coins)');
        }

        // Deduct coins if not active in shop
        if (!$hasShopFeature) {
            $user->addCoins(-50, 'thread_featured', "Featured thread: " . $thread->title);
        }

        // Mark as featured
        $thread->update(['is_featured' => true]);

        return redirect()->back()->with('success', 'Your thread has been featured on the homepage successfully!');
    }

    public function pin(\App\Models\Thread $thread)
    {
        abort_if(Auth::id() !== $thread->user_id, 403);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $hasShopFeature = $user->hasActiveShopItem('sticky_thread');

        if (!$hasShopFeature) {
            return redirect()->back()->with('error', 'You need to purchase the Sticky Thread feature from the shop to pin threads.');
        }

        // Toggle pin
        $thread->update(['is_pinned' => !$thread->is_pinned]);
        $status = $thread->is_pinned ? 'pinned to top' : 'unpinned';

        return redirect()->back()->with('success', "Your thread has been {$status} successfully!");
    }
}
