@extends('layouts.app')

@section('title', $user->name . '\'s Member Profile | XenForo Professional')
@section('meta_description', $user->name . ' - Joined community on ' . $user->created_at->format('M d, Y') . '. Check out their recent discussions, uploads, and updates.')
@section('meta_keywords', $user->name . ', member profile, forum user, conversations, posts')
@section('og_type', 'profile')

@section('content')
<!-- JSON-LD Structured Schema for User Profile -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "{{ e($user->name) }}",
  "url": "{{ url()->current() }}",
  "image": "{{ $user->avatar_url }}"
}
</script>
<div class="space-y-8">
    <!-- Premium Profile Hero Card -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-white">
        <!-- Dynamic Gradient or Custom Uploaded Image Banner Background -->
        <div id="profile-banner-bg" class="h-44 sm:h-56 relative bg-cover bg-center" style="background: {{ $user->banner_path ? 'url(' . $user->banner_path . ')' : $user->banner_color }}">
            <div class="absolute inset-0 bg-black/10 backdrop-blur-[1px]"></div>
            
            @auth
                @if(Auth::id() === $user->id)
                    <button onclick="document.getElementById('banner').click();" class="absolute top-4 right-4 bg-slate-900/60 hover:bg-slate-900/80 text-white rounded-xl px-3 py-1.5 text-xs font-bold transition-all backdrop-blur-sm border border-white/20 flex items-center gap-1.5 cursor-pointer z-20 shadow-md" title="Edit Cover Photo">
                        <span class="material-symbols-outlined text-sm">photo_camera</span>
                        <span>Edit Cover</span>
                    </button>
                @endif
            @endauth
        </div>

        <!-- User Stats & Info Section -->
        <div class="bg-white p-6 sm:p-8 relative border-t border-slate-200 flex flex-col sm:flex-row items-center sm:items-end justify-between gap-6">
            <!-- User Avatar & Core info -->
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-5 -mt-20 sm:-mt-24 z-10 text-center sm:text-left">
                <!-- Large Avatar frame -->
                <div class="w-32 h-32 rounded-3xl overflow-hidden border-4 border-white bg-slate-50 shadow-lg relative group/avatar">
                    <img id="profile-avatar-img" src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">

                    @auth
                        @if(Auth::id() === $user->id)
                            <div onclick="document.getElementById('avatar').click();" class="absolute inset-0 bg-black/45 opacity-0 group-hover/avatar:opacity-100 transition-all flex flex-col items-center justify-center cursor-pointer text-white z-20 font-bold text-[9px] uppercase tracking-wider" title="Change Avatar">
                                <span class="material-symbols-outlined text-lg mb-0.5">photo_camera</span>
                                <span>Edit</span>
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="space-y-1.5 pb-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                        <!-- Title Badge -->
                        <span class="text-xs px-3 py-0.5 rounded-full font-bold uppercase tracking-wider text-white shadow-sm" style="background: {{ $user->banner_color }}">
                            {{ $user->title_badge }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold">Joined community on {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Quick stats counts & follow button -->
            <div class="flex flex-col items-center sm:items-end gap-3 z-10 pb-2 flex-shrink-0">
                <div class="flex gap-8 text-center">
                    <div class="w-20">
                        <span class="block text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->threads()->count() }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Threads</span>
                    </div>
                    <div class="w-20">
                        <span class="block text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->posts()->count() }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Messages</span>
                    </div>
                    <div class="w-24">
                        <span class="block text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->attachments()->count() }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Uploads</span>
                    </div>
                </div>

                @auth
                    @if(Auth::id() !== $user->id)
                        <button type="button" 
                                onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                id="follow-btn-{{ $user->id }}" 
                                class="w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm
                                {{ Auth::user()->isFollowing($user) 
                                    ? 'bg-blue-50 border-blue-200 text-blue-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 group/follow active:scale-97' 
                                    : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100 active:scale-97' }}">
                            @if(Auth::user()->isFollowing($user))
                                <span class="material-symbols-outlined text-[11px] group-hover/follow:hidden">check</span>
                                <span class="group-hover/follow:hidden font-bold">Following</span>
                                <span class="material-symbols-outlined text-[11px] hidden group-hover/follow:inline-block">person_remove</span>
                                <span class="hidden group-hover/follow:inline font-bold">Unfollow</span>
                            @else
                                <span class="material-symbols-outlined text-[11px]">person_add</span>
                                <span class="font-bold">Follow Member</span>
                            @endif
                        </button>

                        <button type="button" 
                                onclick="startDirectChat('{{ $user->name }}')" 
                                class="w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 active:scale-97 flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[11px]">chat</span>
                            <span class="font-bold">Send Message</span>
                        </button>
                    @endif
                @endauth

                <!-- Share Profile Button -->
                <button type="button" 
                        onclick="copyProfileLink()" 
                        class="w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 active:scale-97 flex items-center justify-center gap-1.5 shadow-sm"
                        title="Copy profile link to clipboard">
                    <span class="material-symbols-outlined text-[14px]">share</span>
                    <span class="font-bold">Share Profile</span>
                </button>
            </div>
        </div>

        <!-- Signature quote if it exists -->
        @if($user->signature)
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 text-xs text-slate-650 italic text-center sm:text-left font-medium">
                💬 "{{ $user->signature }}"
            </div>
        @endif
    </div>

    <!-- Main Content Panels -->
    @if($isProfilePrivate)
        <div class="max-w-xl mx-auto my-12 bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-12 text-center space-y-6">
            <div class="w-20 h-20 mx-auto bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-4xl">lock</span>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">This Profile is Private</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto font-bold leading-relaxed">
                    {{ $user->name }} has chosen to keep their profile activity private.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('home') }}" class="xen-button text-xs font-bold text-white px-6 py-2.5 rounded-xl shadow-md cursor-pointer inline-flex items-center gap-1.5 hover:opacity-90">
                    <span class="material-symbols-outlined text-sm">home</span>
                    <span>Return to Discussions</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Section: Threads & Customization Forms (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Customization Panel (Shown only to the owner) -->
                @auth
                    @if(Auth::id() === $user->id)
                        <div class="mui-card overflow-hidden bg-white border border-slate-200 shadow-lg rounded-2xl">
                            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                                <h3 class="font-bold text-slate-700 text-xs flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">settings</span>
                                    Customize Profile Card
                                </h3>
                            </div>
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 bg-white">
                                @csrf
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <!-- Avatar Upload -->
                                    <div class="space-y-1.5">
                                        <label for="avatar" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Upload New Avatar</label>
                                        <input type="file" id="avatar" name="avatar" class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                    </div>

                                    <!-- Banner Upload -->
                                    <div class="space-y-1.5">
                                        <label for="banner" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Upload Cover Photo</label>
                                        <input type="file" id="banner" name="banner" class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                    </div>

                                    <!-- Custom title badge -->
                                    <div class="space-y-1.5">
                                        <label for="title_badge" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Custom Title Badge</label>
                                        <input type="text" id="title_badge" name="title_badge" value="{{ old('title_badge', $user->title_badge) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-800 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="New Member, Staff, Guru...">
                                    </div>
                                </div>

                                <!-- Custom Banner Color (Gradients Preset) -->
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Choose Profile Theme Gradient</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #6366f1, #a855f7)" {{ $user->banner_color === 'linear-gradient(135deg, #6366f1, #a855f7)' ? 'checked' : '' }} class="mr-2 text-blue-600 focus:ring-blue-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 shadow-inner"></span>
                                        </label>

                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #ec4899, #8b5cf6)" {{ $user->banner_color === 'linear-gradient(135deg, #ec4899, #8b5cf6)' ? 'checked' : '' }} class="mr-2 text-pink-600 focus:ring-pink-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-pink-500 to-violet-500 shadow-inner"></span>
                                        </label>

                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #f97316, #ef4444)" {{ $user->banner_color === 'linear-gradient(135deg, #f97316, #ef4444)' ? 'checked' : '' }} class="mr-2 text-orange-600 focus:ring-orange-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-orange-500 to-red-500 shadow-inner"></span>
                                        </label>

                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #06b6d4, #3b82f6)" {{ $user->banner_color === 'linear-gradient(135deg, #06b6d4, #3b82f6)' ? 'checked' : '' }} class="mr-2 text-cyan-600 focus:ring-cyan-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 shadow-inner"></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Profile Privacy Setting -->
                                <div class="space-y-1.5 p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs text-blue-600">visibility</span>
                                                Profile Privacy
                                            </h4>
                                            <p class="text-[10px] font-medium text-slate-550">When private, other community members won't be able to see your discussions, uploads, and signature.</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer select-none">
                                            <input type="checkbox" name="is_private" value="1" {{ $user->is_private ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Custom Signature -->
                                <div class="space-y-1.5">
                                    <label for="signature" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Forum Signature Quote</label>
                                    <textarea id="signature" name="signature" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-800 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-400" placeholder="Write a short custom signature to display at the footer of all your posts...">{{ old('signature', $user->signature) }}</textarea>
                                </div>

                                <!-- Save changes -->
                                <div class="text-right pt-3 border-t border-slate-100">
                                    <button type="submit" class="xen-button text-xs font-bold text-white px-5 py-2 rounded-xl shadow-md cursor-pointer">
                                        Save Profile Card
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endauth

                <!-- Recent Discussions list by this user -->
                <div class="mui-card overflow-hidden shadow-lg border border-slate-200 bg-white rounded-2xl">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider">📝 Recent Discussions</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($threads as $thread)
                            <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                                <div class="space-y-0.5">
                                    <h4 class="font-bold text-slate-800 text-xs hover:text-blue-600 transition-colors">
                                        <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                    </h4>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-450">
                                        <span class="font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 border border-blue-100">{{ $thread->category->name }}</span>
                                        <span>•</span>
                                        <span>Created {{ $thread->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-xs font-bold text-slate-700 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 flex-shrink-0">
                                    {{ $thread->views_count }} views
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-xs text-slate-400">
                                No threads have been posted by this member yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Section: Media Gallery Grid (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Media Upload Gallery grid -->
                <div class="mui-card p-6 border border-slate-200 bg-white shadow-lg rounded-2xl">
                    <h3 class="text-xs font-bold tracking-wider text-slate-500 uppercase mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-pink-500 text-sm">photo_library</span>
                        Media Showroom
                    </h3>
                    
                    @if(count($attachments) > 0)
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($attachments as $attach)
                                <div class="relative group rounded-xl overflow-hidden bg-slate-50 border border-slate-200 shadow-sm">
                                    <!-- Padlock Toggle for Owner -->
                                    @auth
                                        @if(Auth::id() === $user->id)
                                            <button onclick="toggleAttachmentPrivacy('{{ $attach->id }}')" 
                                                    id="privacy-btn-{{ $attach->id }}"
                                                    class="absolute top-1.5 left-1.5 w-6 h-6 rounded-lg bg-slate-900/60 hover:bg-slate-900/80 text-white flex items-center justify-center transition-all backdrop-blur-sm border border-white/10 cursor-pointer z-10" 
                                                    title="{{ $attach->is_private ? 'Make Public' : 'Make Private' }}">
                                                <span class="material-symbols-outlined text-[13px] font-bold" id="privacy-icon-{{ $attach->id }}">
                                                    {{ $attach->is_private ? 'lock' : 'lock_open' }}
                                                </span>
                                            </button>
                                        @endif
                                    @endauth

                                    <button onclick="openLightbox('{{ $attach->url }}', '{{ $attach->file_name }}')" class="block w-full h-24 overflow-hidden cursor-zoom-in text-left p-0 border-0 outline-none w-full">
                                        <img src="{{ $attach->url }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" alt="uploaded media">
                                    </button>
                                    <!-- Check if GIF -->
                                    @if(str_contains($attach->file_name, '.gif') || str_contains($attach->file_type, 'gif'))
                                        <span class="absolute top-1.5 right-1.5 px-1 py-0.5 rounded text-[7px] font-bold bg-pink-500 text-white uppercase tracking-widest">
                                            GIF
                                        </span>
                                    @endif
                                    <div class="bg-slate-100/80 p-1.5 text-[8px] text-slate-500 border-t border-slate-200 flex items-center justify-between">
                                        <span class="truncate pr-2 font-medium">{{ $attach->file_name }}</span>
                                        <a href="{{ route('threads.show', $attach->thread->slug) }}" class="hover:text-blue-600 transition-colors font-bold">
                                            🔗
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 border-2 border-slate-200 border-dashed rounded-xl">
                            <span class="text-2xl block mb-2 opacity-50">🖼️</span>
                            <p class="text-xs text-slate-450 max-w-[200px] mx-auto font-medium">No custom images or GIFs uploaded by this member yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@auth
    @if(Auth::id() === $user->id)
        <!-- JS Live Profile/Avatar/Banner Previewer & Save Scroller Cue -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const avatarInput = document.getElementById('avatar');
                const bannerInput = document.getElementById('banner');
                const saveCard = document.querySelector('form[action*="profile/update"]');
                const submitBtn = saveCard ? saveCard.querySelector('button[type="submit"]') : null;

                function highlightSaveButton() {
                    if (submitBtn) {
                        submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                        submitBtn.classList.add('animate-pulse');
                        submitBtn.innerText = '💾 Save Changes!';
                        submitBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                if (avatarInput) {
                    avatarInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const objectUrl = URL.createObjectURL(file);
                            const imgEl = document.getElementById('profile-avatar-img');
                            const placeholderEl = document.getElementById('profile-avatar-placeholder');
                            
                            if (imgEl) {
                                imgEl.src = objectUrl;
                                imgEl.classList.remove('hidden');
                            }
                            if (placeholderEl) {
                                placeholderEl.classList.add('hidden');
                            }
                            highlightSaveButton();
                        }
                    });
                }

                if (bannerInput) {
                    bannerInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const objectUrl = URL.createObjectURL(file);
                            const bannerBg = document.getElementById('profile-banner-bg');
                            if (bannerBg) {
                                bannerBg.style.backgroundImage = `url('${objectUrl}')`;
                            }
                            highlightSaveButton();
                        }
                    });
                }
            });

            function toggleAttachmentPrivacy(attachmentId) {
                const btn = document.getElementById(`privacy-btn-${attachmentId}`);
                const icon = document.getElementById(`privacy-icon-${attachmentId}`);
                if (!btn || !icon) return;

                btn.disabled = true;

                fetch(`/media/${attachmentId}/toggle-privacy`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Toggle privacy failed.');
                    return response.json();
                })
                .then(data => {
                    btn.disabled = false;
                    if (data.success) {
                        if (data.is_private) {
                            icon.innerText = 'lock';
                            btn.title = 'Make Public';
                            Swal.fire({
                                icon: 'info',
                                title: 'Privacy Updated',
                                text: 'This image is now Private and visible only to you.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            icon.innerText = 'lock_open';
                            btn.title = 'Make Private';
                            Swal.fire({
                                icon: 'success',
                                title: 'Privacy Updated',
                                text: 'This image is now Public and visible to all community members.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    console.error('Privacy Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Action Failed',
                        text: 'Could not change media privacy. Please try again.',
                        confirmButtonColor: '#0f172a'
                    });
                });
            }
        </script>
    @endif
@endauth

@auth
    @if(Auth::id() !== $user->id)
        <!-- Follow System Asynchronous API Controller -->
        <script>
            function toggleFollowUser(username, userId) {
                const btn = document.getElementById(`follow-btn-${userId}`);
                if (!btn) return;

                btn.disabled = true;

                fetch(`/members/${encodeURIComponent(username)}/follow`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Follow toggle failed.');
                    return response.json();
                })
                .then(data => {
                    btn.disabled = false;
                    if (data.success) {
                        if (data.following) {
                            btn.className = "w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm bg-blue-50 border-blue-200 text-blue-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 group/follow active:scale-97";
                            btn.innerHTML = `
                                <span class="material-symbols-outlined text-[11px] group-hover/follow:hidden">check</span>
                                <span class="group-hover/follow:hidden font-bold">Following</span>
                                <span class="material-symbols-outlined text-[11px] hidden group-hover/follow:inline-block">person_remove</span>
                                <span class="hidden group-hover/follow:inline font-bold">Unfollow</span>
                            `;
                        } else {
                            btn.className = "w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm bg-white border-slate-200 text-slate-700 hover:bg-slate-100 active:scale-97";
                            btn.innerHTML = `
                                <span class="material-symbols-outlined text-[11px]">person_add</span>
                                <span class="font-bold">Follow Member</span>
                            `;
                        }
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    console.error('Follow Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Action Failed',
                        text: 'Could not toggle follow status. Please try again.',
                        confirmButtonColor: '#0f172a'
                    });
                });
            }
        </script>
    @endif
@endauth

<!-- Profile Sharing Clipboard Script -->
<script>
    function copyProfileLink() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Copied',
                text: 'Profile link copied to clipboard!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }).catch(err => {
            console.error('Clipboard write failed, using fallback:', err);
            const textarea = document.createElement('textarea');
            textarea.value = link;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                Swal.fire({
                    icon: 'success',
                    title: 'Link Copied',
                    text: 'Profile link copied to clipboard!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Copy Failed',
                    text: 'Could not copy link automatically. Please copy the URL from your address bar.',
                    confirmButtonColor: '#0f172a'
                });
            }
            document.body.removeChild(textarea);
        });
    }
</script>
@endsection
