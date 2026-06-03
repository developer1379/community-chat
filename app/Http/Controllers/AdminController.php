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
        return view('admin.settings', compact('user'));
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

        return redirect()->back()->with('success', "Alert notification sent to {$user->name} successfully.");
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
        $categories = \App\Models\Category::withCount('threads')->orderBy('order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = true;

        \App\Models\Category::create($validated);

        \Illuminate\Support\Facades\Cache::forget('forum.categories');

        return redirect()->back()->with('success', 'Category created successfully.');
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
}
