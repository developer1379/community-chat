<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Display community leaderboard/rankings.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');

        // Fetch all users
        $allUsers = User::all();

        // Sort all users by activity points in memory to support dynamic SQLite attribute calculations
        $rankedUsers = $allUsers->map(function ($user) {
            $user->calculated_points = $user->activity_points;
            $user->anime_tier = $user->computed_anime_tier;
            return $user;
        })->sortByDesc('calculated_points');

        // Apply tab filters
        if ($tab === 'creatives') {
            $rankedUsers = $rankedUsers->filter(function ($user) {
                $badge = strtolower($user->title_badge ?? '');
                return str_contains($badge, 'artist') || 
                       str_contains($badge, 'illustrator') || 
                       str_contains($badge, 'cosplay') || 
                       str_contains($badge, 'animator') || 
                       str_contains($badge, 'designer') || 
                       str_contains($badge, 'creator') ||
                       str_contains($badge, 'mangaka');
            });
        } elseif ($tab === 'critics') {
            $rankedUsers = $rankedUsers->filter(function ($user) {
                $badge = strtolower($user->title_badge ?? '');
                return str_contains($badge, 'critic') || 
                       str_contains($badge, 'reviewer') || 
                       str_contains($badge, 'historian') || 
                       str_contains($badge, 'analyst') || 
                       str_contains($badge, 'theorist') || 
                       str_contains($badge, 'writer');
            });
        } elseif ($tab === 'guild') {
            $rankedUsers = $rankedUsers->filter(function ($user) {
                $badge = strtolower($user->title_badge ?? '');
                return str_contains($badge, 'admin') || 
                       str_contains($badge, 'moderator') || 
                       str_contains($badge, 'staff') || 
                       str_contains($badge, 'guild') || 
                       str_contains($badge, 'shinigami') || 
                       str_contains($badge, 'master') || 
                       str_contains($badge, 'founder');
            });
        }

        return view('forum.rankings', [
            'users' => $rankedUsers,
            'currentTab' => $tab,
        ]);
    }
}
