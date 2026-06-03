<?php

namespace App\Repositories\Eloquent;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class ChatRepository implements ChatRepositoryInterface
{
    public function getUserConversations(string $userId): Collection
    {
        return Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'messages' => function ($query) {
                $query->latest()->take(1);
            }])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function getConversationMessages(string $conversationId, int $limit = 50): Collection
    {
        return Message::where('conversation_id', $conversationId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->take($limit)
            ->get();
    }

    public function sendMessage(string $conversationId, string $senderId, string $body): Message
    {
        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'body' => $body,
            'is_read' => false,
        ]);

        Conversation::where('id', $conversationId)->update([
            'last_message_at' => Carbon::now(),
        ]);

        return $message;
    }

    public function startConversation(string $userOneId, string $userTwoId): Conversation
    {
        // Ensure userOneId is always the smaller string to maintain the unique compound index
        $firstId = $userOneId < $userTwoId ? $userOneId : $userTwoId;
        $secondId = $userOneId < $userTwoId ? $userTwoId : $userOneId;

        return Conversation::firstOrCreate([
            'user_one_id' => $firstId,
            'user_two_id' => $secondId,
        ]);
    }

    public function getOrCreateConversation(string $userOneId, string $userTwoId): Conversation
    {
        return $this->startConversation($userOneId, $userTwoId);
    }

    public function getConversation(string $conversationId): ?Conversation
    {
        return Conversation::with(['userOne', 'userTwo'])->find($conversationId);
    }

    public function markAsRead(string $conversationId, string $userId): void
    {
        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getUnreadCount(string $userId): int
    {
        $conversationIds = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->pluck('id');

        return Message::whereIn('conversation_id', $conversationIds)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function getMessage(string $messageId): ?Message
    {
        return Message::find($messageId);
    }

    public function updateMessage(string $messageId, string $body): Message
    {
        $message = Message::findOrFail($messageId);
        $message->update([
            'body' => $body,
            'is_edited' => true,
        ]);
        return $message;
    }

    public function deleteMessage(string $messageId): void
    {
        Message::destroy($messageId);
    }
}
