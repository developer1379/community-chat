<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display premium My Wallet screen with coin statistics.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Retrieve transaction logs with pagination
        $transactions = $user->transactions()
            ->paginate(15);

        // Calculate custom milestone tier progress
        $coins = $user->coins;
        if ($coins >= 5000) {
            $currentTier = 'Pirate King 🏴‍☠️';
            $nextTier = 'Supreme Deity 👑';
            $target = 10000;
            $percent = 100;
        } elseif ($coins >= 1000) {
            $currentTier = 'Soul Reaper 💀';
            $nextTier = 'Pirate King 🏴‍☠️';
            $target = 5000;
            $percent = min(100, (int)(($coins - 1000) / (4000) * 100));
        } elseif ($coins >= 500) {
            $currentTier = 'Super Saiyan ⚡';
            $nextTier = 'Soul Reaper 💀';
            $target = 1000;
            $percent = min(100, (int)(($coins - 500) / (500) * 100));
        } elseif ($coins >= 100) {
            $currentTier = 'Guild Adventurer 🛡️';
            $nextTier = 'Super Saiyan ⚡';
            $target = 500;
            $percent = min(100, (int)(($coins - 100) / (400) * 100));
        } else {
            $currentTier = 'Wandering Ninja 🍃';
            $nextTier = 'Guild Adventurer 🛡️';
            $target = 100;
            $percent = min(100, (int)($coins / 100 * 100));
        }

        return view('auth.wallet', compact('user', 'transactions', 'currentTier', 'nextTier', 'target', 'percent'));
    }
}
