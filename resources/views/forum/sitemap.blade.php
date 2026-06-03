{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Routes -->
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('rules') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ route('rankings.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('members.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>

    <!-- Category Routes -->
    @foreach ($categories as $category)
        <url>
            <loc>{{ route('categories.show', $category->slug) }}</loc>
            <lastmod>{{ $category->updated_at ? $category->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>hourly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    <!-- Thread Routes -->
    @foreach ($threads as $thread)
        <url>
            <loc>{{ route('threads.show', $thread->slug) }}</loc>
            <lastmod>{{ $thread->updated_at ? $thread->updated_at->toAtomString() : $thread->created_at->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    <!-- Member Profile Routes -->
    @foreach ($users as $user)
        <url>
            <loc>{{ route('profile.show', $user->name) }}</loc>
            <lastmod>{{ $user->updated_at ? $user->updated_at->toAtomString() : $user->created_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
