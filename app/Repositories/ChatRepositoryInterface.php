<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ChatRepositoryInterface
{
    public function getUserConversations(string $userId): Collection;
    public function getConversationMessages(string $conversationId, int $limit = 50): Collection;
    public function sendMessage(string $conversationId, string $senderId, string $body): Message;
    public function startConversation(string $userOneId, string $userTwoId): Conversation;
    public function getOrCreateConversation(string $userOneId, string $userTwoId): Conversation;
    public function getConversation(string $conversationId): ?Conversation;
    public function markAsRead(string $conversationId, string $userId): void;
    public function getUnreadCount(string $userId): int;
}
