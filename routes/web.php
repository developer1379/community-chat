<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

// Forum Homepage
Route::get('/', [ForumController::class, 'home'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Categories
Route::get('/categories/{category:slug}', [ForumController::class, 'category'])->name('categories.show');

// Threads
Route::get('/threads/{thread:slug}', [ForumController::class, 'thread'])->name('threads.show');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Thread creation
    Route::get('/categories/{category:slug}/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads/store', [ThreadController::class, 'store'])->name('threads.store');
    
    // Post replies
    Route::post('/threads/{thread:slug}/reply', [ForumController::class, 'reply'])->name('threads.reply');
    
    // Profile Updates
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Direct Quill uploads endpoint to ImgBB
    Route::post('/media/upload', [ForumController::class, 'uploadMedia'])->name('media.upload');
    
    // Follow Toggle Action
    Route::post('/members/{user:name}/follow', [\App\Http\Controllers\FollowController::class, 'toggleFollow'])->name('members.follow');

    // Chat routes
    Route::get('/chat/conversations', [\App\Http\Controllers\ChatController::class, 'index']);
    Route::get('/chat/conversations/{conversation}', [\App\Http\Controllers\ChatController::class, 'show']);
    Route::post('/chat/conversations/{conversation}/send', [\App\Http\Controllers\ChatController::class, 'store']);
    Route::post('/chat/start/{username}', [\App\Http\Controllers\ChatController::class, 'startConversation']);
    Route::post('/chat/conversations/{conversation}/read', [\App\Http\Controllers\ChatController::class, 'markRead']);
    Route::get('/chat/unread-count', [\App\Http\Controllers\ChatController::class, 'unreadCount']);
    Route::get('/chat/search-users', [\App\Http\Controllers\ChatController::class, 'searchUsers']);
    Route::post('/posts/{post}/react', [\App\Http\Controllers\ReactController::class, 'react']);
});

// Public profile page
Route::get('/profile/{user:name}', [AuthController::class, 'profile'])->name('profile.show');

// Public members list directory
Route::get('/members', [\App\Http\Controllers\FollowController::class, 'index'])->name('members.index');

