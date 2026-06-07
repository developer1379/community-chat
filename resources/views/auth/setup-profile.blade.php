@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto my-12 px-4 sm:px-0">
    <!-- Brand Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-gradient-to-tr from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl shadow-purple-500/20 mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
            <span class="material-symbols-outlined text-white text-3xl font-light">face</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Configure Your Profile</h1>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Finish setting up your display identity before joining the community.</p>
    </div>

    <!-- Onboarding Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden relative">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-purple-500 via-pink-500 to-orange-500"></div>
        
        <form action="{{ route('register.save-setup-profile') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-10 space-y-6">
            @csrf

            <!-- 1. Username selection -->
            <div class="space-y-2 text-left">
                <label for="name" class="text-[11px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-[18px]">account_circle</span>
                    </span>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-12 py-3.5 text-slate-800 dark:text-white text-sm font-semibold focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 dark:placeholder:text-slate-650 placeholder:font-medium shadow-inner" placeholder="Enter display username" required autofocus>
                    
                    <!-- Uniqueness status indicator -->
                    <span class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <span id="username-spinner" class="hidden animate-spin h-4 w-4 border-2 border-purple-500 border-t-transparent rounded-full"></span>
                        <span id="username-ok-badge" class="hidden material-symbols-outlined text-emerald-500 text-[20px] font-bold">check_circle</span>
                        <span id="username-err-badge" class="hidden material-symbols-outlined text-rose-500 text-[20px] font-bold">cancel</span>
                    </span>
                </div>
                <p id="username-feedback" class="text-[11px] font-bold mt-1.5 ml-1 text-slate-450 dark:text-slate-500">
                    Your username must be unique.
                </p>
                @error('name')
                    <p class="text-xs text-rose-500 mt-1.5 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Avatar Selection or Custom Upload -->
            <div class="space-y-4 text-left">
                <label class="text-[11px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Profile Avatar</label>
                
                <!-- Display active selected avatar preview -->
                <div class="flex items-center gap-4 p-4 border border-slate-150 dark:border-slate-800 rounded-2xl bg-slate-50/30 dark:bg-slate-950/20">
                    <div class="relative w-16 h-16 rounded-full overflow-hidden border border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 flex-shrink-0">
                        <img id="avatar-preview" src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="Avatar preview">
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Avatar Preview</h4>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Customize your avatar below by uploading a custom image or selecting one of our premium preset avatars.</p>
                    </div>
                </div>

                <!-- Custom Avatar Image Upload Option -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Option A: Upload Image file</span>
                    <div class="relative">
                        <input type="file" id="avatar_file" name="avatar_file" accept="image/*" class="hidden" onchange="previewUploadedFile(this)">
                        <label for="avatar_file" class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-dashed border-slate-300 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-850 hover:border-purple-400 transition-all font-bold text-xs text-slate-650 dark:text-slate-350">
                            <span class="material-symbols-outlined text-sm">cloud_upload</span>
                            Choose Custom Image File (JPG, PNG, WebP)
                        </label>
                    </div>
                    @error('avatar_file')
                        <p class="text-xs text-rose-500 mt-1.5 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Selection Grid of presets -->
                <div class="space-y-3">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Option B: Choose a Preset Avatar</span>
                    <input type="hidden" id="selected-preset" name="avatar_preset" value="">
                    
                    <div class="grid grid-cols-4 sm:grid-cols-8 gap-3">
                        <!-- Custom default selection slot for Google picture if it exists -->
                        @if($user->avatar_path && str_starts_with($user->avatar_path, 'http'))
                            <div onclick="selectPreset('{{ $user->avatar_path }}', this)" class="avatar-option-item relative aspect-square rounded-full overflow-hidden border-2 border-purple-500 cursor-pointer shadow-md select-none ring-2 ring-purple-500/20">
                                <img src="{{ $user->avatar_path }}" class="w-full h-full object-cover" alt="Google">
                                <div class="absolute inset-0 bg-purple-500/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-xs bg-purple-500 rounded-full p-0.5 font-bold">done</span>
                                </div>
                            </div>
                        @endif

                        @foreach($presets as $preset)
                            <!-- Preset options -->
                            <div onclick="selectPreset('{{ $preset }}', this)" class="avatar-option-item relative aspect-square rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-800 hover:border-purple-400 cursor-pointer hover:scale-105 transition-all select-none bg-slate-50 dark:bg-slate-950">
                                <img src="{{ $preset }}" class="w-full h-full object-cover animate-pulse-slow" alt="Preset">
                                <div class="checkmark-overlay hidden absolute inset-0 bg-purple-500/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-xs bg-purple-500 rounded-full p-0.5 font-bold">done</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="pt-4 flex flex-col gap-2">
                <button type="submit" id="btn-submit" class="w-full relative group overflow-hidden bg-slate-900 dark:bg-slate-850 hover:bg-slate-800 text-sm font-bold text-white py-4 rounded-2xl shadow-lg shadow-slate-900/20 cursor-pointer transition-all">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Complete Profile Setup
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                    </span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let isUsernameAvailable = true; // Google-created username defaults to available but they can check again

function selectPreset(url, element) {
    // Set hidden input
    document.getElementById('selected-preset').value = url;
    
    // Clear file input representation
    document.getElementById('avatar_file').value = '';
    
    // Reset borders on preset list
    document.querySelectorAll('.avatar-option-item').forEach(item => {
        item.classList.remove('border-purple-500', 'ring-2', 'ring-purple-500/20');
        item.classList.add('border-slate-200', 'dark:border-slate-800');
        const overlay = item.querySelector('.checkmark-overlay');
        if (overlay) overlay.classList.add('hidden');
    });

    // Mark current selected preset
    element.classList.add('border-purple-500', 'ring-2', 'ring-purple-500/20');
    element.classList.remove('border-slate-200', 'dark:border-slate-800');
    const overlay = element.querySelector('.checkmark-overlay');
    if (overlay) overlay.classList.remove('hidden');

    // Update main preview img source
    document.getElementById('avatar-preview').src = url;
}

function previewUploadedFile(input) {
    if (input.files && input.files[0]) {
        // Clear preset selection representation
        document.getElementById('selected-preset').value = '';
        document.querySelectorAll('.avatar-option-item').forEach(item => {
            item.classList.remove('border-purple-500', 'ring-2', 'ring-purple-500/20');
            item.classList.add('border-slate-200', 'dark:border-slate-800');
            const overlay = item.querySelector('.checkmark-overlay');
            if (overlay) overlay.classList.add('hidden');
        });

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const nameInput = document.getElementById('name');
    const feedbackText = document.getElementById('username-feedback');
    const spinner = document.getElementById('username-spinner');
    const badgeOk = document.getElementById('username-ok-badge');
    const badgeErr = document.getElementById('username-err-badge');
    const form = document.querySelector('form');
    
    let checkTimeout = null;

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
            // If the name is exactly the current user's name, treat it as available
            if (data.available || username.toLowerCase() === "{{ strtolower($user->name) }}") {
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

    form.addEventListener('submit', (e) => {
        if (!isUsernameAvailable) {
            e.preventDefault();
            nameInput.focus();
            checkUsername(nameInput.value);
        }
    });
});
</script>
@endsection
