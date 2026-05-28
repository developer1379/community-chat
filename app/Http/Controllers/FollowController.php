<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Display the members directory list.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $filter = $request->query('filter', 'all');

        $users = $this->userRepo->getMembersFiltered($search, $filter, Auth::id());

        return view('auth.members', compact('users', 'search', 'filter'));
    }

    /**
     * Toggle follow status of a user (Asynchronous API).
     */
    public function toggleFollow(Request $request, User $user)
    {
        $currentUserId = Auth::id();

        if ($currentUserId === $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'You cannot follow yourself.'
            ], 400);
        }

        /** @var User $currentUser */
        $currentUser = Auth::user();
        $isFollowing = $currentUser->isFollowing($user);

        if ($isFollowing) {
            $this->userRepo->unfollowUser($currentUserId, $user->id);
            $following = false;
        } else {
            $this->userRepo->followUser($currentUserId, $user->id);
            $following = true;
        }

        // Return the updated follower statistics
        return response()->json([
            'success' => true,
            'following' => $following,
            'followers_count' => $user->followers()->count()
        ]);
    }
}
