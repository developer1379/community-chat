<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\ImgBBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Logged in successfully. Welcome back!');
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
        
        // Get user's threads and images
        $threads = $user->threads()->with(['category', 'firstPost'])->latest()->take(10)->get();
        $attachments = $user->attachments()->where('file_type', 'like', 'image/%')->latest()->take(12)->get();
        
        return view('auth.profile', compact('user', 'threads', 'attachments'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'signature' => ['nullable', 'string', 'max:500'],
            'banner_color' => ['required', 'string'],
            'title_badge' => ['nullable', 'string', 'max:50'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
        ]);

        $data = [
            'signature' => $request->signature,
            'banner_color' => $request->banner_color,
        ];

        if ($request->filled('title_badge')) {
            $data['title_badge'] = $request->title_badge;
        }

        if ($request->hasFile('avatar')) {
            // Upload avatar to ImgBB and use returning URL!
            $avatarUrl = $this->imgBBService->upload($request->file('avatar'));
            if ($avatarUrl) {
                $data['avatar_path'] = $avatarUrl; // save ImgBB URL directly
            }
        }

        if ($request->hasFile('banner')) {
            // Upload custom banner to ImgBB!
            $bannerUrl = $this->imgBBService->upload($request->file('banner'));
            if ($bannerUrl) {
                $data['banner_path'] = $bannerUrl; // save ImgBB URL
            }
        }

        $this->userRepo->updateProfile($user, $data);

        return back()->with('success', 'Your profile card has been updated successfully!');
    }
}
