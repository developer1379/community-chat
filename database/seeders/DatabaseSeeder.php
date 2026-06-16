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

        $catNsfwParent = Category::create([
            'name' => 'NSFW Media Classes',
            'slug' => 'nsfw-media-classes',
            'description' => 'Standard classification categories for filtering and moderating NSFW content.',
            'icon' => 'folder',
            'order' => 5,
        ]);

        $catDrawings = Category::create([
            'parent_id' => $catNsfwParent->id,
            'name' => 'Drawings',
            'slug' => 'nsfw-drawings',
            'description' => 'Safe-for-work (SFW) drawings, including anime, digital art, and illustrations.',
            'icon' => 'palette',
            'order' => 1,
        ]);

        $catHentai = Category::create([
            'parent_id' => $catNsfwParent->id,
            'name' => 'Hentai',
            'slug' => 'nsfw-hentai',
            'description' => 'Not-safe-for-work (NSFW) cartoon, anime, or illustrated sexual content.',
            'icon' => 'explicit',
            'order' => 2,
        ]);

        $catNeutral = Category::create([
            'parent_id' => $catNsfwParent->id,
            'name' => 'Neutral',
            'slug' => 'nsfw-neutral',
            'description' => 'Safe-for-work (SFW) everyday images of people, objects, and environments.',
            'icon' => 'image',
            'order' => 3,
        ]);

        $catPorn = Category::create([
            'parent_id' => $catNsfwParent->id,
            'name' => 'Porn',
            'slug' => 'nsfw-porn',
            'description' => 'Not-safe-for-work (NSFW) explicit photographic media or sexual acts.',
            'icon' => 'explicit',
            'order' => 4,
        ]);

        $catSexy = Category::create([
            'parent_id' => $catNsfwParent->id,
            'name' => 'Sexy',
            'slug' => 'nsfw-sexy',
            'description' => 'Provocative or suggestive media (e.g. swimwear, underwear) without explicit acts.',
            'icon' => 'favorite',
            'order' => 5,
        ]);

        // Seed Threads and Media for NSFW Categories
        // Drawings
        $threadDrawings = Thread::create([
            'category_id' => $catDrawings->id,
            'user_id' => $user1->id,
            'title' => 'Digital illustration and Anime Sketchbook',
            'slug' => 'digital-illustration-and-anime-sketchbook',
            'views_count' => 120,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadDrawings->id,
            'user_id' => $user1->id,
            'content' => "Welcome to the digital illustration show! Post your latest sketches, tablet work, vector art, and high quality anime character designs here.",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadDrawings->id,
            'user_id' => $user1->id,
            'file_name' => 'digital-art-drawing.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Hentai
        $threadHentai = Thread::create([
            'category_id' => $catHentai->id,
            'user_id' => $user3->id,
            'title' => 'Adult Manga Illustration Styles and Anatomy Research',
            'slug' => 'adult-manga-illustration-styles-and-anatomy-research',
            'views_count' => 310,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadHentai->id,
            'user_id' => $user3->id,
            'content' => "Discussing the complex anatomy, lineart styles, and rendering processes in mature/adult manga illustration workflows. Please share reference concepts for training models.",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadHentai->id,
            'user_id' => $user3->id,
            'file_name' => 'hentai-style-concept.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Neutral
        $threadNeutral = Thread::create([
            'category_id' => $catNeutral->id,
            'user_id' => $user2->id,
            'title' => 'Everyday Life and Cityscapes Photography',
            'slug' => 'everyday-life-and-cityscapes-photography',
            'views_count' => 95,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadNeutral->id,
            'user_id' => $user2->id,
            'content' => "Share your street photography, landscape snapshots, and everyday object captures. Great for model training reference of neutral SFW content.",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadNeutral->id,
            'user_id' => $user2->id,
            'file_name' => 'neutral-dog-photo.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Porn
        $threadPorn = Thread::create([
            'category_id' => $catPorn->id,
            'user_id' => $admin->id,
            'title' => 'Explicit Photo Moderation Guidelines and Classification',
            'slug' => 'explicit-photo-moderation-guidelines-and-classification',
            'views_count' => 540,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadPorn->id,
            'user_id' => $admin->id,
            'content' => "Establishing robust data moderation pipelines for explicit content. We are testing algorithms using classic art references representing human figures.",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadPorn->id,
            'user_id' => $admin->id,
            'file_name' => 'fine-art-figure.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Sexy
        $threadSexy = Thread::create([
            'category_id' => $catSexy->id,
            'user_id' => $user1->id,
            'title' => 'Fashion & Glamour Modeling Showcase',
            'slug' => 'fashion-and-glamour-modeling-showcase',
            'views_count' => 280,
            'is_featured' => false,
        ]);

        Post::create([
            'thread_id' => $threadSexy->id,
            'user_id' => $user1->id,
            'content' => "A gallery dedicated to swimsuit, glamour, and pin-up photography modeling, focused on lighting setups, camera lenses, and post-production techniques.",
        ]);

        \App\Models\Attachment::create([
            'thread_id' => $threadSexy->id,
            'user_id' => $user1->id,
            'file_name' => 'glamour-pose.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // 3. Seed Threads & Posts (Replies) inside General Discussion
        $thread1 = Thread::create([
            'category_id' => $catGeneral->id,
            'user_id' => $user3->id,
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

        // Additional seeds for NSFW categories to enrich dataset
        // Drawings 2
        $tDraw2 = Thread::create([
            'category_id' => $catDrawings->id,
            'user_id' => $user2->id,
            'title' => 'Retro 90s Anime Aesthetics and Background Art',
            'slug' => 'retro-90s-anime-aesthetics-and-background-art',
            'views_count' => 180,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tDraw2->id,
            'user_id' => $user2->id,
            'content' => "Let's appreciate the hand-painted cell backgrounds of 90s anime. The color choices and detailed scenery are incredible.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tDraw2->id,
            'user_id' => $user2->id,
            'file_name' => 'retro-anime-girl.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Drawings 3
        $tDraw3 = Thread::create([
            'category_id' => $catDrawings->id,
            'user_id' => $user3->id,
            'title' => 'Concept Art: Cyberpunk City Street View',
            'slug' => 'concept-art-cyberpunk-city-street-view',
            'views_count' => 240,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tDraw3->id,
            'user_id' => $user3->id,
            'content' => "Sharing some futuristic cyberpunk concept designs and neon street sketches from my latest project.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tDraw3->id,
            'user_id' => $user3->id,
            'file_name' => 'cyberpunk-neon-street.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1601042879364-f3947d3f9c16?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Hentai 2
        $tHentai2 = Thread::create([
            'category_id' => $catHentai->id,
            'user_id' => $user1->id,
            'title' => 'Adult Webtoon Line Art & Coloring Workflows',
            'slug' => 'adult-webtoon-line-art-and-coloring-workflows',
            'views_count' => 380,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tHentai2->id,
            'user_id' => $user1->id,
            'content' => "Tips on shading skin and clothing highlights for mature webtoon series. What digital brushes do you prefer?",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tHentai2->id,
            'user_id' => $user1->id,
            'file_name' => 'stylized-illustration.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1560942485-b2a11cc13456?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Hentai 3
        $tHentai3 = Thread::create([
            'category_id' => $catHentai->id,
            'user_id' => $user2->id,
            'title' => 'Manga Screen Toning & Shading Techniques',
            'slug' => 'manga-screen-toning-and-shading-techniques',
            'views_count' => 290,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tHentai3->id,
            'user_id' => $user2->id,
            'content' => "How to apply dot screen tones for adult manga scenes. Here is a sample page study showing the shading.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tHentai3->id,
            'user_id' => $user2->id,
            'file_name' => 'abstract-paint-flow.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Neutral 2
        $tNeut2 = Thread::create([
            'category_id' => $catNeutral->id,
            'user_id' => $user3->id,
            'title' => 'Workspace Setup & Minimalist Desks',
            'slug' => 'workspace-setup-and-minimalist-desks',
            'views_count' => 110,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tNeut2->id,
            'user_id' => $user3->id,
            'content' => "Show us where you code and design! Here is my current desk setup with a dual monitor setup.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tNeut2->id,
            'user_id' => $user3->id,
            'file_name' => 'minimal-desk-workspace.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Neutral 3
        $tNeut3 = Thread::create([
            'category_id' => $catNeutral->id,
            'user_id' => $user1->id,
            'title' => 'Coffee Art and Local Cafe Discoveries',
            'slug' => 'coffee-art-and-local-cafe-discoveries',
            'views_count' => 150,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tNeut3->id,
            'user_id' => $user1->id,
            'content' => "Tried a new coffee shop downtown and got this beautiful latte art! Share your local cafe pictures.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tNeut3->id,
            'user_id' => $user1->id,
            'file_name' => 'latte-art-coffee.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Porn 2
        $tPorn2 = Thread::create([
            'category_id' => $catPorn->id,
            'user_id' => $user2->id,
            'title' => 'Chiaroscuro Figure Lighting & Studio Setup',
            'slug' => 'chiaroscuro-figure-lighting-and-studio-setup',
            'views_count' => 610,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tPorn2->id,
            'user_id' => $user2->id,
            'content' => "Using strong shadows and highlights to emphasize human muscle form in artistic studio shoots.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tPorn2->id,
            'user_id' => $user2->id,
            'file_name' => 'abstract-shadow-study.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Porn 3
        $tPorn3 = Thread::create([
            'category_id' => $catPorn->id,
            'user_id' => $user3->id,
            'title' => 'Fine-Art Sculptures of Classical Antiquity',
            'slug' => 'fine-art-sculptures-of-classical-antiquity',
            'views_count' => 450,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tPorn3->id,
            'user_id' => $user3->id,
            'content' => "Studying the anatomical accuracy and posture of Renaissance marble statues of figures.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tPorn3->id,
            'user_id' => $user3->id,
            'file_name' => 'museum-classical-sculpture.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1605721911519-3dfeb3be25e7?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Sexy 2
        $tSexy2 = Thread::create([
            'category_id' => $catSexy->id,
            'user_id' => $user2->id,
            'title' => 'Beach Fashion & Summer Swimwear Poses',
            'slug' => 'beach-fashion-and-summer-swimwear-poses',
            'views_count' => 310,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tSexy2->id,
            'user_id' => $user2->id,
            'content' => "Outdoor summer fashion photography tips. Managing direct sunlight reflections and shadows on swimwear.",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tSexy2->id,
            'user_id' => $user2->id,
            'file_name' => 'beach-sunset-swimwear.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1504198453319-5ce911bafcde?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Sexy 3
        $tSexy3 = Thread::create([
            'category_id' => $catSexy->id,
            'user_id' => $user3->id,
            'title' => 'Glamour Editorial Portrait Lighting Guides',
            'slug' => 'glamour-editorial-portrait-lighting-guides',
            'views_count' => 420,
            'is_featured' => false,
        ]);
        Post::create([
            'thread_id' => $tSexy3->id,
            'user_id' => $user3->id,
            'content' => "Using softboxes and ring lights to get clean editorial beauty portraits. What setups do you use?",
        ]);
        \App\Models\Attachment::create([
            'thread_id' => $tSexy3->id,
            'user_id' => $user3->id,
            'file_name' => 'beauty-portrait-woman.jpg',
            'file_path' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=800&q=80',
            'file_type' => 'image/jpeg',
        ]);

        // Call the Shop Seeder to populate catalog items
        $this->call(\Database\Seeders\ShopItemSeeder::class);
    }
}
