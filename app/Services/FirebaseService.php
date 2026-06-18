<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected ?string $databaseUrl;
    protected ?string $secret;

    public function __construct()
    {
        $this->databaseUrl = config('firebase.database_url');
        $this->secret = config('firebase.secret');
    }

    /**
     * Send a lightweight ping to the user's chat node in Firebase.
     */
    public function triggerChatPing(string $userId, string $conversationId): void
    {
        $this->sendPing("users/{$userId}/chat", [
            'conversation_id' => $conversationId,
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Send a lightweight ping to the user's notification node in Firebase.
     */
    public function triggerNotificationPing(string $userId): void
    {
        $this->sendPing("users/{$userId}/notification", [
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Helper to perform HTTP PUT/PATCH to Firebase REST API.
     */
    protected function sendPing(string $path, array $data): void
    {
        if (!$this->databaseUrl) {
            return;
        }

        dispatch(function () use ($path, $data) {
            try {
                // Clean database URL (remove trailing slashes)
                $url = rtrim($this->databaseUrl, '/') . '/' . $path . '.json';

                // Append legacy Database Secret if configured
                if ($this->secret) {
                    $url .= '?auth=' . urlencode($this->secret);
                }

                // Perform non-blocking lightweight PUT request
                Http::timeout(3)->put($url, $data);
            } catch (\Exception $e) {
                // Log warning but prevent blocking the main request
                Log::warning("Firebase Ping Failed: " . $e->getMessage());
            }
        })->afterResponse();
    }
}
