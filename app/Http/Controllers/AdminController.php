<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of all submitted bug reports.
     */
    public function bugs()
    {
        $bugs = BugReport::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.bugs.index', compact('bugs'));
    }

    /**
     * Mark a specific bug report as resolved.
     */
    public function resolveBug(BugReport $bug)
    {
        $bug->update(['status' => 'resolved']);
        return redirect()->back()->with('success', 'Bug report marked as resolved successfully!');
    }

    /**
     * Show the administrator settings panel.
     */
    public function settings()
    {
        $user = Auth::user();
        $imgbbKey = config('services.imgbb.key', 'cd4cbd15d854cce8d541bc9b8ddc56ad');
        
        $firebaseSettings = [
            'api_key' => \App\Models\Setting::get('firebase_api_key', config('firebase.api_key')),
            'auth_domain' => \App\Models\Setting::get('firebase_auth_domain', config('firebase.auth_domain')),
            'database_url' => \App\Models\Setting::get('firebase_database_url', config('firebase.database_url')),
            'project_id' => \App\Models\Setting::get('firebase_project_id', config('firebase.project_id')),
            'storage_bucket' => \App\Models\Setting::get('firebase_storage_bucket', config('firebase.storage_bucket')),
            'messaging_sender_id' => \App\Models\Setting::get('firebase_messaging_sender_id', config('firebase.messaging_sender_id')),
            'app_id' => \App\Models\Setting::get('firebase_app_id', config('firebase.app_id')),
            'secret' => \App\Models\Setting::get('firebase_secret', env('FIREBASE_DATABASE_SECRET')),
        ];

        return view('admin.settings', compact('user', 'imgbbKey', 'firebaseSettings'));
    }

    /**
     * Update administrator email and password settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The provided current password does not match our records.'])
                ->withInput();
        }

        // Update email
        $user->email = $validated['email'];

        // Update password if a new one is provided
        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('admin.settings')->with('success', 'Administrator account settings updated successfully!');
    }

    /**
     * Update the ImgBB API Key in .env file.
     */
    public function updateImgBBSettings(Request $request)
    {
        $request->validate([
            'imgbb_api_key' => 'required|string|max:255',
        ]);

        $key = 'IMGBB_API_KEY';
        $val = $request->input('imgbb_api_key');

        try {
            $path = base_path('.env');
            if (file_exists($path)) {
                $content = file_get_contents($path);

                if (preg_match("/^{$key}=.*/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $content);
                } else {
                    $content .= "\n{$key}={$val}";
                }

                file_put_contents($path, $content);
                
                // Clear configuration cache
                \Illuminate\Support\Facades\Artisan::call('config:clear');
            } else {
                return redirect()->back()->with('error', '.env file not found. Could not save settings.');
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to update .env: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'ImgBB API key saved to .env and updated successfully!');
    }

    /**
     * Test a proposed ImgBB API Key connection.
     */
    public function testImgBBSettings(Request $request)
    {
        $request->validate([
            'imgbb_api_key' => 'nullable|string|max:255',
        ]);

        $apiKey = $request->input('imgbb_api_key');
        
        $imgBB = app(\App\Services\ImgBBService::class);
        $result = $imgBB->testConnection($apiKey);

        return response()->json($result);
    }

    /**
     * Fetch unread system notifications for the authenticated user.
     */
    public function getUserNotifications()
    {
        $notifications = \App\Models\SystemNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($notifications);
    }

    /**
     * Clear (mark as read) all system notifications for the authenticated user.
     */
    public function clearUserNotifications()
    {
        \App\Models\SystemNotification::where('user_id', Auth::id())
            ->update(['is_read' => true]);
            
        return response()->json(['status' => 'success']);
    }

    /**
     * Display a list of all users for administration.
     */
    public function users()
    {
        $users = \App\Models\User::where('id', '!=', Auth::id())->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Block or unblock a user.
     */
    public function blockUser(\App\Models\User $user)
    {
        $user->update(['is_blocked' => !$user->is_blocked]);
        $status = $user->is_blocked ? 'suspended' : 'reinstated';
        return redirect()->back()->with('success', "User account {$user->name} has been {$status} successfully.");
    }

    /**
     * Send a custom system notification warning to a user.
     */
    public function notifyUser(Request $request, \App\Models\User $user)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:5',
        ]);

        \App\Models\SystemNotification::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'is_read' => false,
            'show_alert' => $request->boolean('show_alert'),
        ]);

        app(\App\Services\FirebaseService::class)->triggerNotificationPing($user->id);

        return redirect()->back()->with('success', "Alert notification sent to {$user->name} successfully.");
    }

    /**
     * Save dynamic Firebase database credentials.
     */
    public function updateFirebaseSettings(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string|max:255',
            'auth_domain' => 'nullable|string|max:255',
            'database_url' => 'nullable|url|max:255',
            'project_id' => 'nullable|string|max:255',
            'storage_bucket' => 'nullable|string|max:255',
            'messaging_sender_id' => 'nullable|string|max:255',
            'app_id' => 'nullable|string|max:255',
            'secret' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            \App\Models\Setting::set('firebase_' . $key, $value);
        }

        // Clear config cache to force reloading from database
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        return redirect()->back()->with('success', 'Firebase configuration settings updated successfully!');
    }

    /**
     * List all direct message chats involving a specific user.
     */
    public function userChats(\App\Models\User $user)
    {
        $conversations = \App\Models\Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo'])
            ->get();

        return view('admin.users.chats', compact('user', 'conversations'));
    }

    /**
     * View the full message history of a specific conversation log.
     */
    public function viewUserChat(\App\Models\User $user, \App\Models\Conversation $conversation)
    {
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized access to this conversation log.');
        }

        $messages = \App\Models\Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.users.chat_messages', compact('user', 'conversation', 'messages'));
    }

    /**
     * Dismiss the popup screen alert for a system notification.
     */
    public function dismissNotificationAlert(\App\Models\SystemNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->update(['show_alert' => false]);
        return response()->json(['status' => 'success']);
    }

    /**
     * Display a listing of all categories.
     */
    public function categories()
    {
        $categories = \App\Models\Category::with(['parent'])->withCount('threads')->orderBy('order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = true;
        $validated['parent_id'] = $request->input('parent_id') ?: null;

        if ($request->hasFile('icon_image')) {
            $imgBB = app(\App\Services\ImgBBService::class);
            $iconUrl = $imgBB->uploadResizedIcon($request->file('icon_image'), 128);
            if ($iconUrl) {
                $validated['icon'] = $iconUrl;
            }
        }

        \App\Models\Category::create($validated);

        \Illuminate\Support\Facades\Cache::forget('forum.categories');

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Request $request, \App\Models\Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id|different:id',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['parent_id'] = $request->input('parent_id') ?: null;

        if ($request->hasFile('icon_image')) {
            $imgBB = app(\App\Services\ImgBBService::class);
            $iconUrl = $imgBB->uploadResizedIcon($request->file('icon_image'), 128);
            if ($iconUrl) {
                $validated['icon'] = $iconUrl;
            }
        }

        $category->update($validated);

        \Illuminate\Support\Facades\Cache::forget('forum.categories');

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    /**
     * Toggle active status of a category.
     */
    public function toggleCategory(\App\Models\Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        \Illuminate\Support\Facades\Cache::forget('forum.categories');

        $status = $category->is_active ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Category '{$category->name}' has been {$status} successfully.");
    }

    /**
     * Delete a category.
     */
    public function destroyCategory(\App\Models\Category $category)
    {
        if ($category->threads()->count() > 0) {
            return redirect()->back()->with('error', "Cannot delete category '{$category->name}' because it contains active threads.");
        }

        $category->delete();

        \Illuminate\Support\Facades\Cache::forget('forum.categories');

        return redirect()->back()->with('success', "Category '{$category->name}' has been deleted successfully.");
    }

    /**
     * Display a listing of shop items in admin panel.
     */
    public function shop()
    {
        $shopItems = \App\Models\ShopItem::orderBy('category')->orderBy('name')->get();
        return view('admin.shop.index', compact('shopItems'));
    }

    /**
     * Store a new shop item.
     */
    public function storeShopItem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Feature Updates,Promot your content,User Access,Private threads',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'duration' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:shop_items,key',
        ]);

        \App\Models\ShopItem::create($validated);

        return redirect()->back()->with('success', 'Shop item created successfully.');
    }

    /**
     * Update an existing shop item.
     */
    public function updateShopItem(Request $request, string $id)
    {
        $shopItem = \App\Models\ShopItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Feature Updates,Promot your content,User Access,Private threads',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'duration' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:shop_items,key,' . $id,
        ]);

        $shopItem->update($validated);

        return redirect()->back()->with('success', 'Shop item updated successfully.');
    }

    /**
     * Delete a shop item.
     */
    public function destroyShopItem(string $id)
    {
        $shopItem = \App\Models\ShopItem::findOrFail($id);
        $shopItem->delete();

        return redirect()->back()->with('success', 'Shop item deleted successfully.');
    }

    /**
     * Show all notifications for the authenticated user.
     */
    public function notificationsIndex(Request $request)
    {
        $notifications = \App\Models\SystemNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('auth.notifications', compact('notifications'));
    }

    /**
     * Mark a notification as read and redirect to target link.
     */
    public function readNotification(\App\Models\SystemNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($notification->link) {
            return redirect()->to($notification->link);
        }

        return redirect()->route('home');
    }

    /**
     * View the search history log of a specific user.
     */
    public function userSearchHistory(\App\Models\User $user)
    {
        $history = \App\Models\SearchHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.users.search_history', compact('user', 'history'));
    }

    /**
     * Clear the search history log of a specific user.
     */
    public function clearUserSearchHistory(\App\Models\User $user)
    {
        \App\Models\SearchHistory::where('user_id', $user->id)->delete();
        return redirect()->back()->with('success', "Search history for {$user->name} has been cleared.");
    }
}
