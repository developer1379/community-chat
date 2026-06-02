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

        $coins = $user->coins;

        // Fetch rank milestones dynamically from the database (20 stages!)
        $milestones = \App\Models\RankMilestone::orderBy('level', 'asc')->get();
        
        $currentMilestone = $milestones->first();
        $nextMilestone = null;
        
        foreach ($milestones as $ms) {
            if ($coins >= $ms->coins_required) {
                $currentMilestone = $ms;
            } else {
                $nextMilestone = $ms;
                break;
            }
        }
        
        if (!$nextMilestone) {
            $nextMilestone = $currentMilestone;
            $percent = 100;
            $target = $currentMilestone->coins_required;
        } else {
            $prevReq = $currentMilestone->coins_required;
            $nextReq = $nextMilestone->coins_required;
            $denom = $nextReq - $prevReq;
            $percent = $denom > 0 ? min(100, (int)(($coins - $prevReq) / $denom * 100)) : 100;
            $target = $nextMilestone->coins_required;
        }

        $currentTier = $currentMilestone->name . ' ' . $currentMilestone->icon;
        $nextTier = $nextMilestone->name . ' ' . $nextMilestone->icon;

        // Fetch coin rules dynamically from the database
        $rules = \App\Models\CoinRule::all();

        return view('auth.wallet', compact('user', 'transactions', 'currentTier', 'nextTier', 'target', 'percent', 'rules', 'milestones', 'currentMilestone'));
    }
}
