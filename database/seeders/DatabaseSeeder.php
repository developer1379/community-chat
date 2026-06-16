<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Dynamic Users
        $admin = User::create([
            'name' => 'Antigravity Developer',
            'email' => 'admin@xenforo.test',
            'password' => Hash::make('password123'),
            'coins' => 99999,
            'title_badge' => 'Founder & Admin',
            'banner_color' => 'linear-gradient(135deg, #ec4899, #8b5cf6)',
            'signature' => '💡 "Building future community hubs with high-end aesthetics."',
        ]);

        \App\Models\Admin::create([
            'user_id' => $admin->id,
        ]);

        $user1 = User::create([
            'name' => 'TailwindWizard',
            'email' => 'wizard@tailwind.test',
            'password' => Hash::make('password123'),
            'title_badge' => 'CSS Guru',
            'banner_color' => 'linear-gradient(135deg, #06b6d4, #3b82f6)',
            'signature' => '⚡ "Utility first, compromise second. styling at the speed of light!"',
        ]);

        $user2 = User::create([
            'name' => 'LaravelFanatic',
            'email' => 'fanatic@laravel.test',
            'password' => Hash::make('password123'),
            'title_badge' => 'Artisan Expert',
            'banner_color' => 'linear-gradient(135deg, #f97316, #ef4444)',
            'signature' => '🚀 "Code is poetry. Write elegant models and decoupled repositories."',
        ]);

        $user3 = User::create([
            'name' => 'XenGuru',
            'email' => 'guru@xenforo.test',
            'password' => Hash::make('password123'),
            'title_badge' => 'UX Master',
            'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
            'signature' => '✨ "Aesthetics are not an option, they are the standard."',
        ]);

        // 2. Seed Categories
        $catGeneral = Category::create([
            'name' => 'General Discussion',
            'slug' => 'general-discussion',
            'description' => 'Talk about anything community-related, off-topic stuff, and general chit-chat.',
            'icon' => 'chat-bubble-left-right',
            'order' => 1,
        ]);

        $catImages = Category::create([
            'name' => 'Images & GIFs Showroom',
            'slug' => 'images-and-gifs',
            'description' => 'Upload your coolest custom imagery, animation loops, and visual memes!',
            'icon' => 'photo',
            'order' => 2,
        ]);

        $catWebDev = Category::create([
            'name' => 'Web Dev & XenForo Styles',
            'slug' => 'web-dev-styles',
            'description' => 'Show off your development frameworks, beautiful themes, and web-app styles.',
            'icon' => 'sparkles',
            'order' => 3,
        ]);

        $catSupport = Category::create([
            'name' => 'Tech Support & Inquiries',
            'slug' => 'tech-support',
            'description' => 'Discuss hosting environments, databases, local environments, and code troubleshooting.',
            'icon' => 'cpu-chip',
            'order' => 4,
        ]);

        $catMediaParent = Category::create([
            'name' => 'Digital Media Hub',
            'slug' => 'digital-media-hub',
            'description' => 'A centralized hub for sharing, discussing, and learning modern digital design and creation techniques.',
            'icon' => 'folder',
            'order' => 5,
        ]);

        $catAiArt = Category::create([
            'parent_id' => $catMediaParent->id,
            'name' => 'AI Art & Creative Tools',
            'slug' => 'ai-art-tools',
            'description' => 'Discuss and share artwork generated using AI tools, prompts, and diffusion models.',
            'icon' => 'smart_toy',
            'order' => 1,
        ]);

        $catVideoVfx = Category::create([
            'parent_id' => $catMediaParent->id,
            'name' => 'Video Editing & VFX',
            'slug' => 'video-editing-vfx',
            'description' => 'Post your video projects, transitions, color grading, VFX shots, and editing workflows.',
            'icon' => 'movie',
            'order' => 2,
        ]);

        $catGraphics = Category::create([
            'parent_id' => $catMediaParent->id,
            'name' => 'Graphics & Design',
            'slug' => 'graphics-design',
            'description' => 'Vector design, typography, brand identities, UI/UX layouts, and 3D modeling showcase.',
            'icon' => 'palette',
            'order' => 3,
        ]);

        $catPhotoLighting = Category::create([
            'parent_id' => $catMediaParent->id,
            'name' => 'Photography & Lighting',
            'slug' => 'photography-lighting',
            'description' => 'Camera gear, studio lighting set-ups, photo-editing tutorials, and outdoor photography.',
            'icon' => 'photo_camera',
            'order' => 4,
        ]);

        // Seed Threads and Media for Digital Media Categories
        // AI Art 1
        $threadAiArt = Thread::create([
            'category_id' => $catAiArt->id,
            'user_id' => $user1->id,
            'prefix' => 'AI Fake',
            'title' => 'Prompt Engineering and Model Fine-Tuning',
            'slug' => 'prompt-engineering-and-model-fine-tuning',
            'views_count' => 120,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadAiArt->id,
            'user_id' => $user1->id,
            'content' => "How are you adjusting your CFG scales and negative prompts to get high-quality photorealistic portrait outputs?",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadAiArt->id,
            'user_id' => $user1->id,
            'file_name' => 'ai-portrait.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Video Editing 1
        $threadVideo = Thread::create([
            'category_id' => $catVideoVfx->id,
            'user_id' => $user3->id,
            'prefix' => 'How to',
            'title' => 'Color Grading Workflows in DaVinci Resolve',
            'slug' => 'color-grading-workflows-in-davinci-resolve',
            'views_count' => 310,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadVideo->id,
            'user_id' => $user3->id,
            'content' => "Comparing color spaces: DWG vs ACES. Which grading workspace provides the best dynamic range for film look?",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadVideo->id,
            'user_id' => $user3->id,
            'file_name' => 'color-grading.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Graphics 1
        $threadGraphics = Thread::create([
            'category_id' => $catGraphics->id,
            'user_id' => $user2->id,
            'prefix' => 'Tips',
            'title' => 'UI/UX Glassmorphism Landing Page Designs',
            'slug' => 'ui-ux-glassmorphism-landing-page-designs',
            'views_count' => 95,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadGraphics->id,
            'user_id' => $user2->id,
            'content' => "Sharing a UI concept of a glassmorphic dashboard with frosted panels and deep blue accent colors.",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadGraphics->id,
            'user_id' => $user2->id,
            'file_name' => 'glassmorphic-ui.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Photography 1
        $threadPhoto = Thread::create([
            'category_id' => $catPhotoLighting->id,
            'user_id' => $admin->id,
            'prefix' => 'Tips',
            'title' => 'Studio Portrait Lighting Setups',
            'slug' => 'studio-portrait-lighting-setups',
            'views_count' => 540,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadPhoto->id,
            'user_id' => $admin->id,
            'content' => "Using a standard 3-point lighting setup with softboxes. Here is a portrait shot showing the light wrap.",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadPhoto->id,
            'user_id' => $admin->id,
            'file_name' => 'portrait-photo.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // 3. Seed Threads & Posts (Replies) inside General Discussion
        $thread1 = Thread::create([
            'category_id' => $catGeneral->id,
            'user_id' => $user3->id,
            'prefix' => 'Tips',
            'title' => 'Building Future Community Hubs with High-End Aesthetics',
            'slug' => 'building-future-community-hubs-with-high-end-aesthetics',
            'views_count' => 156,
            'is_pinned' => true,
            'is_featured' => true,
        ]);

        Post::create([
            'thread_id' => $thread1->id,
            'user_id' => $user3->id,
            'content' => "Welcome everyone to our next-generation community hub discussion!\n\nTo build a highly active community, the user interface must be absolutely premium. Generic designs feel dated and push members away. Modern web design is all about soft glassmorphic panels, curated HSL color gradients, clean typography (like Outfit and Plus Jakarta Sans), and smooth micro-animations.\n\nLet's use this thread to compile the best design frameworks and principles for a stunning forum app!",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $thread1->id,
            'user_id' => $user3->id,
            'file_name' => 'featured-ui-design.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        Post::create([
            'thread_id' => $thread1->id,
            'user_id' => $user1->id,
            'content' => "Completely agree, @XenGuru!\n\nPairing a clean light mode with deep blue accent colors and high contrast slate text really helps with readability. Also, reducing vertical padding slightly creates a beautifully compact layout that xenforo users love.",
        ]);

        Post::create([
            'thread_id' => $thread1->id,
            'user_id' => $admin->id,
            'content' => "Excellent points. We also integrated server-side query caching across categories and thread paginations to ensure page loading times are incredibly fast (sub 50ms!). High performance is just as important as aesthetics.",
        ]);

        // 4. Seed Threads inside Web Dev Styles
        $thread2 = Thread::create([
            'category_id' => $catWebDev->id,
            'user_id' => $user1->id,
            'prefix' => 'How to',
            'title' => 'Why TailwindCSS v4 with Zero-Vite Lag is the Next Big Thing',
            'slug' => 'why-tailwindcss-v4-with-zero-vite-lag-is-the-next-big-thing',
            'views_count' => 84,
            'is_featured' => true,
        ]);

        Post::create([
            'thread_id' => $thread2->id,
            'user_id' => $user1->id,
            'content' => "Tailwind v4 delivers unprecedented compilation speed, but using the browser-based Tailwind runtime CDN during initial prototyping is a total game-changer. It allows us to build complex material designs with HSL tailor-made variables on the fly with absolutely zero compilation setup!\n\nHere is a simple example of how to configure our corporate color tokens:\n\n```css\n:root {\n  --color-primary: #2563eb;\n  --color-slate-dark: #0f172a;\n  --border-radius-card: 16px;\n}\n```\n\nWhat are your thoughts on bypassing asset compilation during initial prototyping phases?",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $thread2->id,
            'user_id' => $user1->id,
            'file_name' => 'tailwind-v4.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        Post::create([
            'thread_id' => $thread2->id,
            'user_id' => $user2->id,
            'content' => "Bypassing Vite compiler makes local environment testing super portable! It means anyone can download the repository, run the migrations and seeders, and start the app instantly without having to run `npm install` and `npm run dev` in the background.\n\nIT is super clean!",
        ]);

        // 5. Seed Threads inside Support
        $thread3 = Thread::create([
            'category_id' => $catSupport->id,
            'user_id' => $user2->id,
            'prefix' => 'Suggestion',
            'title' => 'Decoupling Database Access with Repository & Interface Pattern',
            'slug' => 'decoupling-database-access-with-repository-pattern',
            'views_count' => 42,
            'is_featured' => true,
        ]);

        Post::create([
            'thread_id' => $thread3->id,
            'user_id' => $user2->id,
            'content' => "When building scalable Laravel applications, I always recommend decoupling database queries from HTTP Controllers.\n\nBy creating a `ThreadRepositoryInterface` and binding it to a concrete `ThreadRepository` inside `AppServiceProvider`, controllers only interact with the interface. This makes unit testing extremely simple because we can swap database implementations or mock the repository layers entirely without touching a single controller line.\n\nWho else is using this architecture?",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $thread3->id,
            'user_id' => $user2->id,
            'file_name' => 'repository-pattern.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Additional seeds for Digital Media Hub categories to enrich dataset
        // AI Art 2
        $tDraw2 = Thread::create([
            'category_id' => $catAiArt->id,
            'user_id' => $user2->id,
            'prefix' => 'Bollywood Actress',
            'title' => 'Generating Consistent Characters in Midjourney',
            'slug' => 'generating-consistent-characters-in-midjourney',
            'views_count' => 180,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tDraw2->id,
            'user_id' => $user2->id,
            'content' => "Sharing some tips on using the character weight parameter to preserve consistency across different scenes.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tDraw2->id,
            'user_id' => $user2->id,
            'file_name' => 'consistent-character-study.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // AI Art 3
        $tDraw3 = Thread::create([
            'category_id' => $catAiArt->id,
            'user_id' => $user3->id,
            'prefix' => 'Telugu Actress',
            'title' => 'Stable Diffusion WebUI Extensions & Workflow Optimizations',
            'slug' => 'stable-diffusion-webui-extensions-and-optimizations',
            'views_count' => 240,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tDraw3->id,
            'user_id' => $user3->id,
            'content' => "A compilation of the best ControlNet, face-restore, and upscaler extensions for standard production pipelines.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tDraw3->id,
            'user_id' => $user3->id,
            'file_name' => 'stable-diffusion-art.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Video Editing 2
        $tHentai2 = Thread::create([
            'category_id' => $catVideoVfx->id,
            'user_id' => $user1->id,
            'prefix' => 'Tamil Actress',
            'title' => '3D Camera Tracking in After Effects',
            'slug' => '3d-camera-tracking-in-after-effects',
            'views_count' => 380,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tHentai2->id,
            'user_id' => $user1->id,
            'content' => "A tutorial on achieving perfect camera matches for compositing 3D text into drone footage.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tHentai2->id,
            'user_id' => $user1->id,
            'file_name' => 'after-effects-composite.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1601042879364-f3947d3f9c16?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Video Editing 3
        $tHentai3 = Thread::create([
            'category_id' => $catVideoVfx->id,
            'user_id' => $user2->id,
            'prefix' => 'Kannada Actress',
            'title' => 'VFX Compositing and Keying Green Screen Footage',
            'slug' => 'vfx-compositing-and-keying-green-screen-footage',
            'views_count' => 290,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tHentai3->id,
            'user_id' => $user2->id,
            'content' => "Tips on obtaining clean edges around fine details like hair when using Keylight. Show us your composites!",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tHentai3->id,
            'user_id' => $user2->id,
            'file_name' => 'green-screen-production.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Graphics 2
        $tNeut2 = Thread::create([
            'category_id' => $catGraphics->id,
            'user_id' => $user3->id,
            'prefix' => 'Pakistani Actress',
            'title' => 'Logo Design and Typography Hierarchy',
            'slug' => 'logo-design-and-typography-hierarchy',
            'views_count' => 110,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tNeut2->id,
            'user_id' => $user3->id,
            'content' => "Here is my latest minimal branding project. Focusing on geometric shapes and modern sans-serif typography.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tNeut2->id,
            'user_id' => $user3->id,
            'file_name' => 'logo-concept.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Graphics 3
        $tNeut3 = Thread::create([
            'category_id' => $catGraphics->id,
            'user_id' => $user1->id,
            'prefix' => 'Srilankan Actress',
            'title' => '3D Blender Hard-Surface Modeling Practices',
            'slug' => '3d-blender-hard-surface-modeling-practices',
            'views_count' => 150,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tNeut3->id,
            'user_id' => $user1->id,
            'content' => "Studying topology layout for clean edge flow and subdivision surface modifiers. Here is a render of my latest mechanical model.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tNeut3->id,
            'user_id' => $user1->id,
            'file_name' => 'blender-render.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Photography 2
        $tPorn2 = Thread::create([
            'category_id' => $catPhotoLighting->id,
            'user_id' => $user2->id,
            'prefix' => 'Bangladeshi Actress',
            'title' => 'Golden Hour Landscape Photography Tips',
            'slug' => 'golden-hour-landscape-photography-tips',
            'views_count' => 610,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tPorn2->id,
            'user_id' => $user2->id,
            'content' => "How to capture maximum dynamic range during sunsets without clipping the sky or crushing shadows.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tPorn2->id,
            'user_id' => $user2->id,
            'file_name' => 'sunset-landscape.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Photography 3
        $tPorn3 = Thread::create([
            'category_id' => $catPhotoLighting->id,
            'user_id' => $user3->id,
            'prefix' => 'Hentai/Cartoon',
            'title' => 'Mirrorless Cameras vs DSLRs in 2026',
            'slug' => 'mirrorless-cameras-vs-dslrs-in-2026',
            'views_count' => 450,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tPorn3->id,
            'user_id' => $user3->id,
            'content' => "Let's review the autofocus tracking and sensor stabilization improvements in mirrorless bodies vs older DSLR systems.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tPorn3->id,
            'user_id' => $user3->id,
            'file_name' => 'camera-body-sensor.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Call the Shop Seeder to populate catalog items
        $this->call(\Database\Seeders\ShopItemSeeder::class);
    }
}
