<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\React;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactController extends Controller
{
    /**
     * React to a specific post.
     */
    public function react(Request $request, Post $post): JsonResponse
    {
        $userId = Auth::id();

        $request->validate([
            'type' => 'required|string|in:like,love,haha,wow,sad,angry',
        ]);

        $type = $request->input('type');

        // Check if user already reacted to this post
        $existingReact = React::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReact) {
            if ($existingReact->type === $type) {
                // Remove reaction if clicked the exact same type (toggle off)
                $existingReact->delete();
                $reacted = false;

                // Deduct 2 coins from post owner (if not self)
                if ($post->user_id !== $userId) {
                    $post->user->addCoins(-2, 'reaction_removed', "Reaction removed on post in: " . $post->thread->title);
                }
            } else {
                // Update reaction type if clicked a different one
                $existingReact->update(['type' => $type]);
                $reacted = true;
            }
        } else {
            // Create brand new reaction
            React::create([
                'post_id' => $post->id,
                'user_id' => $userId,
                'type' => $type,
            ]);
            $reacted = true;

            // Reward 2 coins to post owner (if not self)
            if ($post->user_id !== $userId) {
                $post->user->addCoins(2, 'reaction_received', "Received reaction on post in: " . $post->thread->title);

                \App\Models\SystemNotification::create([
                    'user_id' => $post->user_id,
                    'title' => 'New Reaction on your Post',
                    'message' => Auth::user()->name . ' reacted with ' . ucfirst($type) . ' to your post in: "' . $post->thread->title . '"',
                    'link' => route('threads.show', $post->thread->slug) . '#post-' . $post->id,
                    'show_alert' => true,
                ]);
            }
        }

        // Get updated statistics for reactions on this post
        $stats = $post->reacts()
            ->select('type', \DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // Check user's current active reaction
        $userReact = $post->reacts()->where('user_id', $userId)->first();

        return response()->json([
            'success' => true,
            'reacted' => $reacted,
            'active_type' => $userReact ? $userReact->type : null,
            'stats' => $stats,
            'total_count' => $post->reacts()->count(),
        ]);
    }
}
