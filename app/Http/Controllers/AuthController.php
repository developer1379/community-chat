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
        $points = $user->activity_points;

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

        // Super Saiyan rank (level 2, points >= 200) required for custom banner color / background banner image
        if ($points >= 200) {
            $data['banner_color'] = $request->banner_color;
            if ($request->hasFile('banner')) {
                // Upload custom banner to ImgBB!
                $bannerUrl = $this->imgBBService->upload($request->file('banner'));
                if ($bannerUrl) {
                    $data['banner_path'] = $bannerUrl; // save ImgBB URL
                }
            }
        }

        // Soul Reaper rank (level 3, points >= 500) required for custom title badge text color
        if ($points >= 500 && $request->has('title_color')) {
            $data['title_color'] = $request->title_color;
        }

        // Pirate King rank (level 4, points >= 1000) required for custom title badge text
        if ($points >= 1000) {
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
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('home')->with('success', 'Logged in successfully with Google. Welcome!');
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
}
