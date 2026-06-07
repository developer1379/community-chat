<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\ImgBBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected UserRepositoryInterface $userRepo;
    protected ImgBBService $imgBBService;

    public function __construct(
        UserRepositoryInterface $userRepo,
        ImgBBService $imgBBService
    ) {
        $this->userRepo = $userRepo;
        $this->imgBBService = $imgBBService;
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function checkUsername(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $exists = \App\Models\User::where('name', $request->name)->exists();

        return response()->json([
            'available' => !$exists,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'title_badge' => 'New Member',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Welcome to the community! Your registration was successful.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->is_blocked) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account has been suspended by the administrator.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            
            $intended = redirect()->intended(route('home'))->getTargetUrl();
            if (str_contains($intended, '/dms/') || str_contains($intended, '/unread-count')) {
                return redirect()->route('home')->with('success', 'Logged in successfully. Welcome back!');
            }
            
            return redirect()->to($intended)->with('success', 'Logged in successfully. Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    public function profile(string $name)
    {
        $user = $this->userRepo->findByName($name);
        
        $isOwner = Auth::check() && Auth::id() === $user->id;
        $isProfilePrivate = $user->is_private && !$isOwner;

        // Get user's threads and images
        $threads = [];
        $attachments = [];

        if (!$isProfilePrivate) {
            $threads = $user->threads()->with(['category', 'firstPost'])->latest()->take(10)->get();
            
            $attachmentsQuery = $user->attachments()->where('file_type', 'like', 'image/%');
            if (!$isOwner) {
                $attachmentsQuery->where('is_private', false);
            }
            $attachments = $attachmentsQuery->latest()->take(12)->get();
        }
        
        return view('auth.profile', compact('user', 'threads', 'attachments', 'isProfilePrivate'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'signature' => ['nullable', 'string', 'max:500'],
            'banner_color' => ['required', 'string'],
            'title_badge' => ['nullable', 'string', 'max:50'],
            'title_color' => ['nullable', 'string', 'max:7', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
        ]);

        $data = [
            'signature' => $request->signature,
            'is_private' => $request->has('is_private'),
        ];

        $tier = $user->computed_anime_tier;
        $level = $tier['level'] ?? 1;
        $isAdmin = $user->isAdmin();

        // Level 12 (Super Saiyan) required for custom banner color / background banner image
        if ($level >= 12 || $isAdmin) {
            $data['banner_color'] = $request->banner_color;
            if ($request->hasFile('banner')) {
                // Upload custom banner to ImgBB!
                $bannerUrl = $this->imgBBService->upload($request->file('banner'));
                if ($bannerUrl) {
                    $data['banner_path'] = $bannerUrl; // save ImgBB URL
                }
            }
        }

        // Level 16 (Soul Reaper) required for custom title badge text color
        if (($level >= 16 || $isAdmin) && $request->has('title_color')) {
            $data['title_color'] = $request->title_color;
        }

        // Level 20 (Pirate King) required for custom title badge text
        if ($level >= 20 || $isAdmin) {
            $data['title_badge'] = $request->title_badge;
        }

        if ($request->hasFile('avatar')) {
            // Upload avatar to ImgBB and use returning URL!
            $avatarUrl = $this->imgBBService->upload($request->file('avatar'));
            if ($avatarUrl) {
                $data['avatar_path'] = $avatarUrl; // save ImgBB URL directly
            }
        }

        $this->userRepo->updateProfile($user, $data);

        return back()->with('success', 'Your profile card has been updated successfully!');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Failed to authenticate with Google. Please try again.'
            ]);
        }

        // Search for user by google_id or email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id and avatar if not set
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
            if (!$user->avatar_path && $googleUser->getAvatar()) {
                $user->update(['avatar_path' => $googleUser->getAvatar()]);
            }
        } else {
            // Generate a unique name from Google name
            $baseName = $googleUser->getName() ?: 'User';
            $name = Str::slug($baseName);
            // Ensure unique username
            $counter = 1;
            while (User::where('name', $name)->exists()) {
                $name = Str::slug($baseName) . '-' . $counter;
                $counter++;
            }

            // Create new user
            $user = User::create([
                'name' => $name,
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(24)), // Random secure password
                'avatar_path' => $googleUser->getAvatar(),
                'title_badge' => 'New Member',
                'is_onboarded' => false, // Set to false to trigger onboarding flow!
            ]);
        }

        Auth::login($user, true);

        if (!$user->is_onboarded) {
            return redirect()->route('register.setup-profile')->with('info', 'Please finalize your profile details.');
        }

        return redirect()->route('home')->with('success', 'Logged in successfully with Google. Welcome!');
    }

    /**
     * Show the profile onboarding configuration page.
     */
    public function showSetupProfile()
    {
        $user = Auth::user();
        if ($user->is_onboarded) {
            return redirect()->route('home');
        }

        $presets = [
            'https://api.dicebear.com/7.x/bottts/svg?seed=Felix',
            'https://api.dicebear.com/7.x/bottts/svg?seed=Aneka',
            'https://api.dicebear.com/7.x/adventurer/svg?seed=Nala',
            'https://api.dicebear.com/7.x/adventurer/svg?seed=Buster',
            'https://api.dicebear.com/7.x/fun-emoji/svg?seed=Gizmo',
            'https://api.dicebear.com/7.x/fun-emoji/svg?seed=Maggie',
            'https://api.dicebear.com/7.x/pixel-art/svg?seed=Luna',
            'https://api.dicebear.com/7.x/pixel-art/svg?seed=Cooper'
        ];

        return view('auth.setup-profile', compact('user', 'presets'));
    }

    /**
     * Save the configured onboarding details (unique name and custom or preset avatar).
     */
    public function saveSetupProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->is_onboarded) {
            return redirect()->route('home');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->id],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'avatar_preset' => ['nullable', 'string', 'url'],
        ]);

        $avatarPath = $user->avatar_path;

        // 1. Check custom file upload first
        if ($request->hasFile('avatar_file')) {
            $uploadedUrl = $this->imgBBService->upload($request->file('avatar_file'));
            if ($uploadedUrl) {
                $avatarPath = $uploadedUrl;
            }
        } 
        // 2. Check preset select second
        elseif ($request->filled('avatar_preset')) {
            $avatarPath = $request->avatar_preset;
        }

        $user->update([
            'name' => $request->name,
            'avatar_path' => $avatarPath,
            'is_onboarded' => true,
        ]);

        return redirect()->route('home')->with('success', 'Profile configured successfully! Welcome to the community.');
    }

    public function toggleMediaPrivacy(\App\Models\Attachment $attachment)
    {
        if (Auth::id() !== $attachment->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $attachment->is_private = !$attachment->is_private;
        $attachment->save();

        return response()->json([
            'success' => true,
            'is_private' => $attachment->is_private,
            'message' => $attachment->is_private ? 'Media is now Private.' : 'Media is now Public.'
        ]);
    }

    public function updateUsername(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $purchase = $user->purchases()
            ->whereHas('shopItem', function ($q) {
                $q->where('key', 'username_change');
            })
            ->first();

        if (!$purchase) {
            return redirect()->back()->with('error', 'You must purchase Username Change feature first.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        $purchase->delete();

        return redirect()->route('profile.show', $user->name)->with('success', 'Your username has been updated successfully!');
    }

    public function updateUsernameStyle(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->hasActiveShopItem('username_style')) {
            return redirect()->back()->with('error', 'You must purchase Username Style upgrade first.');
        }

        $request->validate([
            'title_color' => ['nullable', 'string', 'max:7', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'title_badge' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update([
            'title_color' => $request->title_color,
            'title_badge' => $request->title_badge,
        ]);

        return redirect()->back()->with('success', 'Your custom username style has been applied!');
    }

    public function updateThreadUpgrades(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'thread_id' => ['required', 'uuid', 'exists:threads,id'],
        ]);

        $thread = \App\Models\Thread::findOrFail($request->thread_id);

        if ($thread->user_id !== $user->id) {
            abort(403);
        }

        // Validate first
        if ($request->has('apply_featured')) {
            $hasItem = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'featured_homepage_thread');
                })
                ->exists();
            if (!$hasItem) {
                return redirect()->back()->with('error', 'You must purchase Featured homepage thread upgrade first.');
            }
        }

        if ($request->has('apply_sticky')) {
            $hasItem = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'sticky_thread');
                })
                ->exists();
            if (!$hasItem) {
                return redirect()->back()->with('error', 'You must purchase Sticky Thread upgrade first.');
            }
        }

        if ($request->has('apply_title_style')) {
            $hasItem = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'thread_title_style');
                })
                ->exists();
            if (!$hasItem) {
                return redirect()->back()->with('error', 'You must purchase Thread Title Style upgrade first.');
            }
        }

        if ($request->has('apply_highlight')) {
            $hasItem = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'thread_highlight');
                })
                ->exists();
            if (!$hasItem) {
                return redirect()->back()->with('error', 'You must purchase Thread Highlight upgrade first.');
            }
        }

        // Apply and consume
        if ($request->has('apply_featured')) {
            $purchase = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'featured_homepage_thread');
                })
                ->first();
            if ($purchase) {
                $thread->update(['is_featured' => true]);
                $purchase->delete();
            }
        }

        if ($request->has('apply_sticky')) {
            $purchase = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'sticky_thread');
                })
                ->first();
            if ($purchase) {
                $thread->update(['is_pinned' => true]);
                $purchase->delete();
            }
        }

        if ($request->has('apply_title_style')) {
            $purchase = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'thread_title_style');
                })
                ->first();
            if ($purchase) {
                $thread->update(['is_title_styled' => true]);
                $purchase->delete();
            }
        }

        if ($request->has('apply_highlight')) {
            $purchase = $user->purchases()
                ->whereHas('shopItem', function ($q) {
                    $q->where('key', 'thread_highlight');
                })
                ->first();
            if ($purchase) {
                $thread->update(['is_highlighted' => true]);
                $purchase->delete();
            }
        }

        // Flush dynamic caches
        \Illuminate\Support\Facades\Cache::forget('forum.categories');
        \Illuminate\Support\Facades\Cache::forget('forum.active_threads');

        return redirect()->back()->with('success', 'Thread upgrades applied successfully!');
    }
}
