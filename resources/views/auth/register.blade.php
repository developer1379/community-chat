@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12 px-4 sm:px-0">
    <!-- Brand Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl shadow-purple-500/20 mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
            <span class="material-symbols-outlined text-white text-3xl font-light">person_add</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Create Account</h1>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Join the XenProfessional community today.</p>
    </div>

    <!-- Registration Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden relative">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        
        <!-- Step Indicator -->
        <div class="px-8 pt-8 sm:px-10 flex items-center justify-between">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-450 dark:text-slate-500">
                Setup Progress
            </span>
            <div class="flex items-center gap-1.5">
                <span id="step-dot-1" class="w-2.5 h-2.5 rounded-full transition-all duration-300 bg-purple-600 shadow-sm shadow-purple-500/50"></span>
                <span class="h-0.5 w-4 bg-slate-200 dark:bg-slate-850"></span>
                <span id="step-dot-2" class="w-2.5 h-2.5 rounded-full transition-all duration-300 bg-slate-200 dark:bg-slate-800"></span>
            </div>
        </div>

        <div class="p-8 sm:p-10 space-y-6">
            <!-- Google Login Button (only visible on step 1 to keep it clean) -->
            <div id="google-login-container">
                <a href="{{ route('auth.google.redirect') }}" class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-white border-2 border-slate-100 dark:bg-slate-950 dark:border-slate-850 rounded-2xl text-sm font-bold text-slate-700 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900 hover:border-slate-250 dark:hover:border-slate-800 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                         <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                         <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                         <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                         <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Sign up with Google
                </a>

                <!-- Divider -->
                <div class="relative flex items-center py-4">
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
                    <span class="flex-shrink-0 mx-4 text-[9px] font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest">Or Register Manually</span>
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
                </div>
            </div>

            <!-- Multi-step Form -->
            <form id="registration-form" action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <!-- STEP 1: Username & Profile Setup -->
                <div id="step-panel-1" class="space-y-5 transition-all duration-300">
                    <div class="space-y-2 text-left">
                        <label for="name" class="text-[11px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">account_circle</span>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-12 py-3.5 text-slate-800 dark:text-white text-sm font-semibold focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 dark:placeholder:text-slate-650 placeholder:font-medium shadow-inner shadow-slate-100/50 dark:shadow-none" placeholder="Choose a display name" required autofocus>
                            
                            <!-- Uniqueness spinner / check badge -->
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <span id="username-spinner" class="hidden animate-spin h-4 w-4 border-2 border-purple-500 border-t-transparent rounded-full"></span>
                                <span id="username-ok-badge" class="hidden material-symbols-outlined text-emerald-500 text-[20px] font-bold">check_circle</span>
                                <span id="username-err-badge" class="hidden material-symbols-outlined text-rose-500 text-[20px] font-bold">cancel</span>
                            </span>
                        </div>
                        
                        <!-- Real-time Validation Message -->
                        <p id="username-feedback" class="text-[11px] font-bold mt-1.5 ml-1 text-slate-450 dark:text-slate-500">
                            Usernames must be unique and contain no special characters.
                        </p>
                        
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1.5 ml-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Step 1 Button -->
                    <div class="pt-2">
                        <button type="button" id="btn-next-step" class="w-full relative group overflow-hidden bg-slate-900 dark:bg-slate-850 hover:bg-slate-800 text-sm font-bold text-white py-4 rounded-2xl shadow-lg shadow-slate-900/20 dark:shadow-none cursor-pointer transition-all">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Continue to Account Details
                                <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </span>
                            <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Credentials (Email / Passwords) -->
                <div id="step-panel-2" class="hidden space-y-5 transition-all duration-300">
                    <!-- Email Field -->
                    <div class="space-y-2 text-left">
                        <label for="email" class="text-[11px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">mail</span>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 dark:text-white text-sm font-semibold focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 dark:placeholder:text-slate-650 placeholder:font-medium shadow-inner shadow-slate-100/50 dark:shadow-none" placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1.5 ml-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2 text-left">
                        <label for="password" class="text-[11px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">lock</span>
                            </span>
                            <input type="password" id="password" name="password" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 dark:text-white text-sm font-semibold focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 dark:placeholder:text-slate-650 placeholder:font-medium shadow-inner shadow-slate-100/50 dark:shadow-none" placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1.5 ml-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Field -->
                    <div class="space-y-2 text-left">
                        <label for="password_confirmation" class="text-[11px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">lock_reset</span>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 dark:text-white text-sm font-semibold focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 dark:placeholder:text-slate-650 placeholder:font-medium shadow-inner shadow-slate-100/50 dark:shadow-none" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Step 2 Buttons -->
                    <div class="grid grid-cols-3 gap-3 pt-2">
                        <button type="button" id="btn-prev-step" class="col-span-1 border border-slate-250 dark:border-slate-850 hover:bg-slate-50 dark:hover:bg-slate-850 text-xs font-bold text-slate-650 dark:text-slate-300 py-4 rounded-2xl cursor-pointer transition-all">
                            Back
                        </button>
                        
                        <button type="submit" class="col-span-2 relative group overflow-hidden bg-slate-900 dark:bg-slate-850 hover:bg-slate-800 text-sm font-bold text-white py-4 rounded-2xl shadow-lg shadow-slate-900/20 dark:shadow-none cursor-pointer transition-all">
                            <span class="relative z-10 flex items-center justify-center gap-1.5">
                                Register Account
                                <span class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </span>
                            <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer Link -->
        <div class="bg-slate-50 dark:bg-slate-950/30 border-t border-slate-100 dark:border-slate-850 p-6 text-center">
            <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                Already registered? <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 hover:underline">Sign In here</a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const nameInput = document.getElementById('name');
    const feedbackText = document.getElementById('username-feedback');
    const spinner = document.getElementById('username-spinner');
    const badgeOk = document.getElementById('username-ok-badge');
    const badgeErr = document.getElementById('username-err-badge');
    
    const btnNext = document.getElementById('btn-next-step');
    const btnPrev = document.getElementById('btn-prev-step');
    const stepPanel1 = document.getElementById('step-panel-1');
    const stepPanel2 = document.getElementById('step-panel-2');
    const stepDot1 = document.getElementById('step-dot-1');
    const stepDot2 = document.getElementById('step-dot-2');
    const googleContainer = document.getElementById('google-login-container');
    
    let isUsernameAvailable = false;
    let checkTimeout = null;

    // Detect if Laravel backend errors exist on credentials step
    let activeStep = 1;
    @if($errors->has('email') || $errors->has('password'))
        activeStep = 2;
    @endif

    function goToStep(step) {
        if (step === 2) {
            // Hide Step 1 panel & google container
            stepPanel1.classList.add('hidden');
            googleContainer.classList.add('hidden');
            // Show Step 2 panel
            stepPanel2.classList.remove('hidden');
            // Update dots
            stepDot1.classList.remove('bg-purple-600', 'shadow-purple-500/50');
            stepDot1.classList.add('bg-slate-200', 'dark:bg-slate-800');
            stepDot2.classList.add('bg-purple-600', 'shadow-purple-500/50');
            stepDot2.classList.remove('bg-slate-200', 'dark:bg-slate-800');
            
            // Require fields on step 2 for native browser validation
            document.getElementById('email').required = true;
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;
            
            activeStep = 2;
        } else {
            // Show Step 1 panel & google container
            stepPanel1.classList.remove('hidden');
            googleContainer.classList.remove('hidden');
            // Hide Step 2 panel
            stepPanel2.classList.add('hidden');
            // Update dots
            stepDot1.classList.add('bg-purple-600', 'shadow-purple-500/50');
            stepDot1.classList.remove('bg-slate-200', 'dark:bg-slate-800');
            stepDot2.classList.remove('bg-purple-600', 'shadow-purple-500/50');
            stepDot2.classList.add('bg-slate-200', 'dark:bg-slate-800');
            
            // Remove requirement from step 2 fields during step 1 to prevent validation block
            document.getElementById('email').required = false;
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            
            activeStep = 1;
        }
    }

    // Initialize step based on backend state
    if (activeStep === 2) {
        goToStep(2);
    }

    // Validation handler
    function checkUsername(username) {
        if (!username || username.trim().length < 3) {
            feedbackText.innerText = "Username must be at least 3 characters.";
            feedbackText.className = "text-[11px] font-bold mt-1.5 ml-1 text-rose-500";
            badgeOk.classList.add('hidden');
            badgeErr.classList.remove('hidden');
            spinner.classList.add('hidden');
            isUsernameAvailable = false;
            return;
        }

        // Check format
        const cleanRegex = /^[A-Za-z0-9\s-_]+$/;
        if (!cleanRegex.test(username)) {
            feedbackText.innerText = "No special characters allowed (letters, numbers, space, dash, underscore only).";
            feedbackText.className = "text-[11px] font-bold mt-1.5 ml-1 text-rose-500";
            badgeOk.classList.add('hidden');
            badgeErr.classList.remove('hidden');
            spinner.classList.add('hidden');
            isUsernameAvailable = false;
            return;
        }

        spinner.classList.remove('hidden');
        badgeOk.classList.add('hidden');
        badgeErr.classList.add('hidden');

        fetch("{{ route('register.check-username') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: username })
        })
        .then(r => r.json())
        .then(data => {
            spinner.classList.add('hidden');
            if (data.available) {
                feedbackText.innerText = "Awesome, that username is available!";
                feedbackText.className = "text-[11px] font-bold mt-1.5 ml-1 text-emerald-500";
                badgeOk.classList.remove('hidden');
                badgeErr.classList.add('hidden');
                isUsernameAvailable = true;
            } else {
                feedbackText.innerText = "Sorry, that username is already taken.";
                feedbackText.className = "text-[11px] font-bold mt-1.5 ml-1 text-rose-500";
                badgeOk.classList.add('hidden');
                badgeErr.classList.remove('hidden');
                isUsernameAvailable = false;
            }
        })
        .catch(err => {
            console.error(err);
            spinner.classList.add('hidden');
        });
    }

    nameInput.addEventListener('input', (e) => {
        clearTimeout(checkTimeout);
        const val = e.target.value;
        checkTimeout = setTimeout(() => {
            checkUsername(val);
        }, 400);
    });

    // Handle stepping
    btnNext.addEventListener('click', () => {
        const val = nameInput.value.trim();
        
        if (!val) {
            nameInput.reportValidity();
            return;
        }

        // Perform final check or step transition if available
        if (isUsernameAvailable) {
            goToStep(2);
        } else {
            // Re-run checks if needed
            checkUsername(val);
            // Shake username input or show warning
            nameInput.focus();
        }
    });

    btnPrev.addEventListener('click', () => {
        goToStep(1);
    });
});
</script>
@endsection
