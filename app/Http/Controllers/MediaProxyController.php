<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class MediaProxyController extends Controller
{
    /**
     * Proxy attachments.
     */
    public function proxyAttachment(Attachment $attachment)
    {
        // Security check: if the attachment is private, only the owner can access it
        if ($attachment->is_private) {
            if (!auth()->check() || auth()->id() !== $attachment->user_id) {
                return abort(403, 'Unauthorized access to private attachment.');
            }
        }

        $url = $attachment->file_path;

        // If it is not a remote URL, return normally
        if (!str_starts_with($url, 'http')) {
            $path = storage_path('app/public/' . $url);
            if (file_exists($path)) {
                return response()->file($path);
            }
            return abort(404);
        }

        return $this->proxy($url, $attachment->file_type);
    }

    /**
     * Proxy user avatars.
     */
    public function proxyAvatar(User $user)
    {
        $url = $user->avatar_path;

        if (!$url) {
            // Default fallback using DiceBear URL
            $animeSeeds = ['Luffy', 'Zoro', 'Nami', 'Goku', 'Naruto', 'Sasuke', 'Kakashi', 'Hinata', 'Deku', 'Bakugo', 'Saber', 'Asuka', 'Rei', 'Kirito', 'Asuna', 'Rem', 'Emilia'];
            $hash = crc32($user->name);
            $seed = $animeSeeds[abs($hash) % count($animeSeeds)];
            $url = "https://api.dicebear.com/7.x/adventurer/svg?seed=" . $seed;
            $contentType = 'image/svg+xml';
        } else {
            if (!str_starts_with($url, 'http')) {
                $path = storage_path('app/public/' . $url);
                if (file_exists($path)) {
                    return response()->file($path);
                }
                return abort(404);
            }

            // Determine content type dynamically
            $contentType = 'image/jpeg';
            if (str_ends_with(strtolower($url), '.png')) {
                $contentType = 'image/png';
            } elseif (str_ends_with(strtolower($url), '.gif')) {
                $contentType = 'image/gif';
            } elseif (str_ends_with(strtolower($url), '.svg')) {
                $contentType = 'image/svg+xml';
            }
        }

        return $this->proxy($url, $contentType);
    }

    /**
     * Perform the actual proxy stream request.
     */
    protected function proxy(string $url, string $contentType)
    {
        try {
            // Fetch content from external server (e.g. ImgBB / DiceBear)
            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {
                return response($response->body(), 200)
                    ->header('Content-Type', $contentType)
                    ->header('Cache-Control', 'public, max-age=31536000, immutable'); // Cache for 1 year
            }
        } catch (\Exception $e) {
            // Log warning, failover to direct redirect
        }

        // Redirect as fallback if proxy fails
        return redirect($url);
    }
}
