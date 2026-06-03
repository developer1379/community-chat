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

        // 3. Seed Threads & Posts (Replies) inside General Discussion
        $thread1 = Thread::create([
            'category_id' => $catGeneral->id,
            'user_id' => $user3->id,
            'title' => 'Building Future Community Hubs with High-End Aesthetics',
            'slug' => 'building-future-community-hubs-with-high-end-aesthetics',
            'views_count' => 156,
            'is_pinned' => true,
        ]);

        Post::create([
            'thread_id' => $thread1->id,
            'user_id' => $user3->id,
            'content' => "Welcome everyone to our next-generation community hub discussion!\n\nTo build a highly active community, the user interface must be absolutely premium. Generic designs feel dated and push members away. Modern web design is all about soft glassmorphic panels, curated HSL color gradients, clean typography (like Outfit and Plus Jakarta Sans), and smooth micro-animations.\n\nLet's use this thread to compile the best design frameworks and principles for a stunning forum app!",
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
            'title' => 'Why TailwindCSS v4 with Zero-Vite Lag is the Next Big Thing',
            'slug' => 'why-tailwindcss-v4-with-zero-vite-lag-is-the-next-big-thing',
            'views_count' => 84,
        ]);

        Post::create([
            'thread_id' => $thread2->id,
            'user_id' => $user1->id,
            'content' => "Tailwind v4 delivers unprecedented compilation speed, but using the browser-based Tailwind runtime CDN during initial prototyping is a total game-changer. It allows us to build complex material designs with HSL tailor-made variables on the fly with absolutely zero compilation setup!\n\nHere is a simple example of how to configure our corporate color tokens:\n\n```css\n:root {\n  --color-primary: #2563eb;\n  --color-slate-dark: #0f172a;\n  --border-radius-card: 16px;\n}\n```\n\nWhat are your thoughts on bypassing asset compilation during initial prototyping phases?",
        ]);

        Post::create([
            'thread_id' => $thread2->id,
            'user_id' => $user2->id,
            'content' => "Bypassing Vite compiler makes local environment testing super portable! It means anyone can download the repository, run the migrations and seeders, and start the app instantly without having to run `npm install` and `npm run dev` in the background.\n\nIt is super clean!",
        ]);

        // 5. Seed Threads inside Support
        $thread3 = Thread::create([
            'category_id' => $catSupport->id,
            'user_id' => $user2->id,
            'title' => 'Decoupling Database Access with Repository & Interface Pattern',
            'slug' => 'decoupling-database-access-with-repository-pattern',
            'views_count' => 42,
        ]);

        Post::create([
            'thread_id' => $thread3->id,
            'user_id' => $user2->id,
            'content' => "When building scalable Laravel applications, I always recommend decoupling database queries from HTTP Controllers.\n\nBy creating a `ThreadRepositoryInterface` and binding it to a concrete `ThreadRepository` inside `AppServiceProvider`, controllers only interact with the interface. This makes unit testing extremely simple because we can swap database implementations or mock the repository layers entirely without touching a single controller line.\n\nWho else is using this architecture?",
        ]);
    }
}
