<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ChatRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\ImgBBService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected ChatRepositoryInterface $chatRepository;
    protected UserRepositoryInterface $userRepository;
    protected ImgBBService $imgBBService;

    public function __construct(
        ChatRepositoryInterface $chatRepository,
        UserRepositoryInterface $userRepository,
        ImgBBService $imgBBService
    ) {
        $this->chatRepository = $chatRepository;
        $this->userRepository = $userRepository;
        $this->imgBBService = $imgBBService;
    }

    /**
     * Get all conversations for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $conversations = $this->chatRepository->getUserConversations($userId);

        $formatted = $conversations->map(function (Conversation $conv) use ($userId) {
            $otherUser = $conv->otherUser($userId);
            $lastMessage = $conv->messages->first();

            return [
                'id' => $conv->id,
                'other_user' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar_url' => $otherUser->avatar_path ? asset('storage/' . $otherUser->avatar_path) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($otherUser->email))) . '?d=mp',
                    'title_badge' => $otherUser->title_badge,
                ],
                'last_message' => $lastMessage ? [
                    'body' => $lastMessage->body,
                    'created_at' => $lastMessage->created_at->diffForHumans(),
                    'sender_id' => $lastMessage->sender_id,
                ] : null,
                'unread_count' => $conv->messages()
                    ->where('sender_id', '!=', $userId)
                    ->where('is_read', false)
                    ->count(),
            ];
        });

        return response()->json($formatted);
    }

    /**
     * Fetch messages in a conversation.
     */
    public function show(Conversation $conversation): JsonResponse
    {
        $userId = Auth::id();

        // Ensure user is authorized to view conversation
        if ($conversation->user_one_id !== $userId && $conversation->user_two_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark messages as read
        $this->chatRepository->markAsRead($conversation->id, $userId);

        $messages = $this->chatRepository->getConversationMessages($conversation->id);

        $formatted = $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'body' => $msg->body,
                'sender_id' => $msg->sender_id,
                'created_at' => $msg->created_at->diffForHumans(),
                'is_own' => $msg->sender_id === Auth::id(),
            ];
        });

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $formatted,
        ]);
    }

    /**
     * Store/send a message in a conversation.
     */
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $userId = Auth::id();

        if ($conversation->user_one_id !== $userId && $conversation->user_two_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'body' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);

        if (!$request->filled('body') && !$request->hasFile('image')) {
            return response()->json(['error' => 'A message body or image is required.'], 422);
        }

        $body = $request->input('body');

        if ($request->hasFile('image')) {
            $url = $this->imgBBService->upload($request->file('image'));
            if (!$url) {
                return response()->json(['error' => 'Failed to upload image to ImgBB.'], 500);
            }
            $body = $url;
        }

        $message = $this->chatRepository->sendMessage(
            $conversation->id,
            $userId,
            $body
        );

        // Broadcast the MessageSent event to the chat channel
        broadcast(new \App\Events\MessageSent($message, $conversation->id))->toOthers();

        // Broadcast the NotificationReceived event to the recipient user's channel
        $recipientId = $conversation->otherUser($userId)->id;
        $isImg = preg_match('/^https?:\/\/[^\s]+?\.(jpe?g|png|gif|webp|bmp)(?:\?[^\s]*)?$/i', trim($message->body)) || 
                 str_starts_with(trim($message->body), 'https://i.ibb.co/');
        $bodyPreview = $isImg ? '📷 Image attachment' : $message->body;

        broadcast(new \App\Events\NotificationReceived(
            $recipientId,
            'new_message',
            [
                'conversation_id' => $conversation->id,
                'sender_id' => $userId,
                'sender_name' => Auth::user()->name,
                'body_preview' => $bodyPreview,
                'created_at' => $message->created_at->diffForHumans(),
            ]
        ))->toOthers();

        return response()->json([
            'id' => $message->id,
            'body' => $message->body,
            'sender_id' => $message->sender_id,
            'created_at' => $message->created_at->diffForHumans(),
            'is_own' => true,
        ]);
    }

    /**
     * Start a conversation with a specific user by username.
     */
    public function startConversation(string $username): JsonResponse
    {
        $currentUser = Auth::user();
        $otherUser = User::where('name', $username)->first();

        if (!$otherUser) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($currentUser->id === $otherUser->id) {
            return response()->json(['error' => 'Cannot start a conversation with yourself'], 400);
        }

        $conversation = $this->chatRepository->getOrCreateConversation($currentUser->id, $otherUser->id);

        return response()->json([
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Mark a conversation's messages as read.
     */
    public function markRead(Conversation $conversation): JsonResponse
    {
        $userId = Auth::id();

        if ($conversation->user_one_id !== $userId && $conversation->user_two_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->chatRepository->markAsRead($conversation->id, $userId);

        return response()->json(['success' => true]);
    }

    /**
     * Get global unread message count for the auth user.
     */
    public function unreadCount(): JsonResponse
    {
        $count = $this->chatRepository->getUnreadCount(Auth::id());
        return response()->json(['unread_count' => $count]);
    }

    /**
     * Search users for direct messaging autocomplete.
     */
    public function searchUsers(Request $request): JsonResponse
    {
        $query = $request->query('q', '');
        $currentUserId = Auth::id();

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', $currentUserId)
            ->where('name', 'like', '%' . $query . '%')
            ->take(10)
            ->get();

        $formatted = $users->map(function (User $u) {
            return [
                'name' => $u->name,
                'title_badge' => $u->title_badge,
                'avatar_url' => $u->avatar_path ? asset('storage/' . $u->avatar_path) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($u->email))) . '?d=mp',
            ];
        });

        return response()->json($formatted);
    }

    /**
     * Get dynamic, real-time stats and presence details for a user hover card.
     */
    public function userCardDetails(string $username): JsonResponse
    {
        $user = User::where('name', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $currentUserId = Auth::id();
        $isFollowing = $currentUserId ? $user->followers()->where('follower_id', $currentUserId)->exists() : false;
        
        // Consistent real-time simulated presence details
        $isOnline = false;
        $lastActive = 'Offline';
        
        if ($currentUserId === $user->id) {
            // The currently logged-in user is ALWAYS online
            $isOnline = true;
            $lastActive = 'Online now';
        } elseif (strtolower($user->name) === 'admin' || strtolower($user->name) === 'founder') {
            $isOnline = true;
            $lastActive = 'Online now';
        } else {
            $hash = crc32($user->name);
            $mod = $hash % 10;
            if ($mod < 3) {
                $isOnline = true;
                $lastActive = 'Online now';
            } elseif ($mod < 6) {
                $minutes = ($hash % 45) + 2;
                $lastActive = 'Active ' . $minutes . 'm ago';
            } else {
                $hours = ($hash % 18) + 1;
                $lastActive = 'Active ' . $hours . 'h ago';
            }
        }

        // Format avatar URL (Google OAuth avatars vs standard storage vs Gravatar fallback)
        $avatarUrl = null;
        if ($user->avatar_path) {
            $avatarUrl = str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path);
        } else {
            $avatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?d=mp';
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'avatar_url' => $avatarUrl,
            'title_badge' => $user->title_badge ?? 'Member',
            'joined' => $user->created_at->format('M Y'),
            'threads_count' => $user->threads()->count(),
            'posts_count' => $user->posts()->count(),
            'uploads_count' => $user->attachments()->count(),
            'banner_color' => $user->banner_color ?? '#2563eb',
            'banner_path' => $user->banner_path ? asset('storage/' . $user->banner_path) : null,
            'is_following' => $isFollowing,
            'is_online' => $isOnline,
            'last_active' => $lastActive,
            'is_self' => $currentUserId === $user->id,
        ]);
    }
}
