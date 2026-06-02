<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

// Forum Homepage & Search
Route::get('/', [ForumController::class, 'home'])->name('home');
Route::get('/search', [ForumController::class, 'search'])->name('search');
Route::get('/media', [ForumController::class, 'mediaIndex'])->name('media.index');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Google Authentication
    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Categories
Route::get('/categories/{category:slug}', [ForumController::class, 'category'])->name('categories.show');

// Threads
Route::get('/threads/{thread:slug}', [ForumController::class, 'thread'])->name('threads.show');

// Dynamic Live Hover Card Details
Route::get('/dms/user-card/{username}', [\App\Http\Controllers\ChatController::class, 'userCardDetails']);


// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Thread creation
    Route::get('/categories/{category:slug}/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads/store', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/threads/{thread:slug}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::put('/threads/{thread:id}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('/threads/{thread:id}', [ThreadController::class, 'destroy'])->name('threads.destroy');
    
    // Post replies
    Route::post('/threads/{thread:slug}/reply', [ForumController::class, 'reply'])->name('threads.reply');
    
    // Profile Updates
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Direct Quill uploads endpoint to ImgBB
    Route::post('/media/upload', [ForumController::class, 'uploadMedia'])->name('media.upload');
    Route::post('/media/{attachment}/toggle-privacy', [AuthController::class, 'toggleMediaPrivacy'])->name('media.toggle-privacy');
    
    // Follow Toggle Action
    Route::post('/members/{user:name}/follow', [\App\Http\Controllers\FollowController::class, 'toggleFollow'])->name('members.follow');

    // Chat routes
    Route::get('/dms/conversations', [\App\Http\Controllers\ChatController::class, 'index']);
    Route::get('/dms/conversations/{conversation}', [\App\Http\Controllers\ChatController::class, 'show']);
    Route::post('/dms/conversations/{conversation}/send', [\App\Http\Controllers\ChatController::class, 'store']);
    Route::post('/dms/start/{username}', [\App\Http\Controllers\ChatController::class, 'startConversation']);
    Route::post('/dms/conversations/{conversation}/read', [\App\Http\Controllers\ChatController::class, 'markRead']);
    Route::get('/dms/unread-count', [\App\Http\Controllers\ChatController::class, 'unreadCount']);
    Route::get('/dms/search-users', [\App\Http\Controllers\ChatController::class, 'searchUsers']);
    Route::post('/posts/{post}/react', [\App\Http\Controllers\ReactController::class, 'react']);
    
    // Wallet Routes
    Route::get('/wallet', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
});

// Public profile page
Route::get('/profile/{user:name}', [AuthController::class, 'profile'])->name('profile.show');

// Public members list directory
Route::get('/members', [\App\Http\Controllers\FollowController::class, 'index'])->name('members.index');

// User Rankings & Leaderboard
Route::get('/rankings', [\App\Http\Controllers\RankingController::class, 'index'])->name('rankings.index');

// Rules & Guide Page
Route::view('/rules', 'forum.rules')->name('rules');


