<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $categories = Category::all();
        $threads = Thread::latest()->get();
        $users = User::where('is_private', false)
            ->where('is_blocked', false)
            ->get();

        $content = view('forum.sitemap', compact('categories', 'threads', 'users'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
