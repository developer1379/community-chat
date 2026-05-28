<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ThreadRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
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
        return view('forum.create', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        $slug = Str::slug($request->title) . '-' . Str::random(5);

        $thread = $this->threadRepo->createThread([
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
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

        return redirect()->route('threads.show', $thread->slug)->with('success', 'Your thread has been created successfully!');
    }
}
