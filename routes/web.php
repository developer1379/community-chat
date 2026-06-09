<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

// Forum Homepage & Search
Route::get('/', [ForumController::class, 'home'])->name('home');
Route::get('/search', [ForumController::class, 'search'])->name('search');
Route::delete('/search/history/clear', [ForumController::class, 'clearSearchHistory'])->name('search.history.clear');
Route::delete('/search/history/{id}', [ForumController::class, 'deleteSearchHistory'])->name('search.history.delete');
Route::get('/media', [ForumController::class, 'mediaIndex'])->name('media.index');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/check-username', [AuthController::class, 'checkUsername'])->name('register.check-username');
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
    Route::get('/register/setup-profile', [AuthController::class, 'showSetupProfile'])->name('register.setup-profile');
    Route::post('/register/setup-profile', [AuthController::class, 'saveSetupProfile'])->name('register.save-setup-profile');

    // Thread creation
    Route::get('/categories/{category:slug}/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads/store', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/threads/{thread:slug}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::put('/threads/{thread:id}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('/threads/{thread:id}', [ThreadController::class, 'destroy'])->name('threads.destroy');
    Route::post('/threads/{thread:id}/feature', [ThreadController::class, 'feature'])->name('threads.feature');
    Route::post('/threads/{thread:id}/pin', [ThreadController::class, 'pin'])->name('threads.pin');
    Route::post('/threads/{thread:id}/customize-title', [ThreadController::class, 'customizeTitle'])->name('threads.customize-title');
    
    // Post replies
    Route::post('/threads/{thread:slug}/reply', [ForumController::class, 'reply'])->name('threads.reply');
    Route::put('/posts/{post}', [ForumController::class, 'editPost'])->name('posts.update');
    
    // Profile Updates
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update-username', [AuthController::class, 'updateUsername'])->name('profile.update_username');
    Route::post('/profile/update-username-style', [AuthController::class, 'updateUsernameStyle'])->name('profile.update_username_style');
    Route::post('/profile/update-thread-upgrades', [AuthController::class, 'updateThreadUpgrades'])->name('profile.update_thread_upgrades');
    
    // Direct Quill uploads endpoint to ImgBB
    Route::post('/media/upload', [ForumController::class, 'uploadMedia'])->name('media.upload');
    Route::post('/media/{attachment}/toggle-privacy', [AuthController::class, 'toggleMediaPrivacy'])->name('media.toggle-privacy');
    
    // Follow Toggle Action
    Route::post('/members/{user:name}/follow', [\App\Http\Controllers\FollowController::class, 'toggleFollow'])->name('members.follow');

    // Chat routes
    Route::get('/dms/conversations', [\App\Http\Controllers\ChatController::class, 'index']);
    Route::get('/dms/admins', [\App\Http\Controllers\ChatController::class, 'getAdmins']);
    Route::get('/dms/conversations/{conversation}', [\App\Http\Controllers\ChatController::class, 'show']);
    Route::post('/dms/conversations/{conversation}/send', [\App\Http\Controllers\ChatController::class, 'store']);
    Route::post('/dms/start/{username}', [\App\Http\Controllers\ChatController::class, 'startConversation']);
    Route::post('/dms/conversations/{conversation}/read', [\App\Http\Controllers\ChatController::class, 'markRead']);
    Route::get('/dms/unread-count', [\App\Http\Controllers\ChatController::class, 'unreadCount']);
    Route::get('/dms/search-users', [\App\Http\Controllers\ChatController::class, 'searchUsers']);
    Route::put('/dms/messages/{messageId}', [\App\Http\Controllers\ChatController::class, 'updateMessage']);
    Route::delete('/dms/messages/{messageId}', [\App\Http\Controllers\ChatController::class, 'deleteMessage']);
    Route::post('/posts/{post}/react', [\App\Http\Controllers\ReactController::class, 'react']);
    
    // Wallet Routes
    Route::get('/wallet', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');

    // Shop Routes
    Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{id}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
    Route::post('/shop/{id}/purchase', [\App\Http\Controllers\ShopController::class, 'purchase'])->name('shop.purchase');
    Route::post('/shop/{id}/like', [\App\Http\Controllers\ShopController::class, 'toggleLike'])->name('shop.like');
    Route::post('/shop/{id}/bookmark', [\App\Http\Controllers\ShopController::class, 'toggleBookmark'])->name('shop.bookmark');
    Route::post('/shop/{id}/review', [\App\Http\Controllers\ShopController::class, 'storeReview'])->name('shop.review');

    // Bug Reporting
    Route::get('/bugs/report', [\App\Http\Controllers\BugReportController::class, 'create'])->name('bugs.create');
    Route::post('/bugs/report', [\App\Http\Controllers\BugReportController::class, 'store'])->name('bugs.store');

    // User Notification Endpoints
    Route::get('/notifications', [\App\Http\Controllers\AdminController::class, 'notificationsIndex'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [\App\Http\Controllers\AdminController::class, 'readNotification'])->name('notifications.read');
    Route::get('/notifications/system', [\App\Http\Controllers\AdminController::class, 'getUserNotifications']);
    Route::post('/notifications/system/clear', [\App\Http\Controllers\AdminController::class, 'clearUserNotifications']);
    Route::post('/notifications/system/{notification}/dismiss-alert', [\App\Http\Controllers\AdminController::class, 'dismissNotificationAlert']);

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/bugs', [\App\Http\Controllers\AdminController::class, 'bugs'])->name('bugs.index');
        Route::post('/bugs/{bug}/resolve', [\App\Http\Controllers\AdminController::class, 'resolveBug'])->name('bugs.resolve');
        Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
        
        // User Management
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
        Route::post('/users/{user}/block', [\App\Http\Controllers\AdminController::class, 'blockUser'])->name('users.block');
        Route::post('/users/{user}/notify', [\App\Http\Controllers\AdminController::class, 'notifyUser'])->name('users.notify');
        Route::get('/users/{user}/chats', [\App\Http\Controllers\AdminController::class, 'userChats'])->name('users.chats');
        Route::get('/users/{user}/chats/{conversation}', [\App\Http\Controllers\AdminController::class, 'viewUserChat'])->name('users.chats.view');
        Route::get('/users/{user}/search-history', [\App\Http\Controllers\AdminController::class, 'userSearchHistory'])->name('users.search-history');
        Route::delete('/users/{user}/search-history/clear', [\App\Http\Controllers\AdminController::class, 'clearUserSearchHistory'])->name('users.search-history.clear');

        // Category Management
        Route::get('/categories', [\App\Http\Controllers\AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [\App\Http\Controllers\AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [\App\Http\Controllers\AdminController::class, 'updateCategory'])->name('categories.update');
        Route::post('/categories/{category}/toggle', [\App\Http\Controllers\AdminController::class, 'toggleCategory'])->name('categories.toggle');
        Route::delete('/categories/{category}', [\App\Http\Controllers\AdminController::class, 'destroyCategory'])->name('categories.destroy');

        // Shop Management
        Route::get('/shop', [\App\Http\Controllers\AdminController::class, 'shop'])->name('shop.index');
        Route::post('/shop', [\App\Http\Controllers\AdminController::class, 'storeShopItem'])->name('shop.store');
        Route::put('/shop/{id}', [\App\Http\Controllers\AdminController::class, 'updateShopItem'])->name('shop.update');
        Route::delete('/shop/{id}', [\App\Http\Controllers\AdminController::class, 'destroyShopItem'])->name('shop.destroy');
    });
});

// Public profile page
Route::get('/profile/{user:name}', [AuthController::class, 'profile'])->name('profile.show');

// Public members list directory
Route::get('/members', [\App\Http\Controllers\FollowController::class, 'index'])->name('members.index');

// User Rankings & Leaderboard
Route::get('/rankings', [\App\Http\Controllers\RankingController::class, 'index'])->name('rankings.index');

// Rules & Guide Page
Route::view('/rules', 'forum.rules')->name('rules');

// Image proxy endpoints
Route::get('/attachments/{attachment}', [\App\Http\Controllers\MediaProxyController::class, 'proxyAttachment'])->name('media.proxy.attachment');
Route::get('/avatars/{user}', [\App\Http\Controllers\MediaProxyController::class, 'proxyAvatar'])->name('media.proxy.avatar');

// Dynamic XML Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');


