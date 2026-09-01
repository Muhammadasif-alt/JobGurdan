@extends('user.layouts.master')
@section('title', $blog->meta_title ?? ($blog->title . ' | Jobs in USA Career Blog'))
@section('meta_description', $blog->meta_description ?? $blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content), 160))
@section('meta_keywords', $blog->tags ?? ($blog->category?->name ?? ''))
@section('og_title', $blog->title)
@section('og_description', $blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content), 160))
@section('canonical', route('blog.show', $blog->slug))

@push('meta')
    @php
        // Image path resolver — handles both seeder paths ("public/user/images/...") and admin uploads ("blogs/...")
        $resolveImg = function ($path, $fallback = 'public/user/images/blog-single-post-01.jpg') {
            if (!$path) return asset($fallback);
            if (str_starts_with($path, 'public/') || str_starts_with($path, 'http')) return asset($path);
            return asset('public/storage/' . $path);
        };
        $ogImg = $resolveImg($blog->featured_image);
    @endphp
    <meta property="og:image" content="{{ $ogImg }}">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $ogImg }}">
    <meta property="article:published_time" content="{{ optional($blog->published_at)->toIso8601String() }}">
    @if($blog->category)
        <meta property="article:section" content="{{ $blog->category->name }}">
    @endif

    {{-- JSON-LD: BlogPosting --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BlogPosting",
        "headline": {!! json_encode($blog->title) !!},
        "image": {!! json_encode($ogImg) !!},
        "datePublished": {!! json_encode(optional($blog->published_at)->toIso8601String()) !!},
        "dateModified": {!! json_encode(optional($blog->updated_at)->toIso8601String()) !!},
        "author": { "@@type": "Person", "name": {!! json_encode($blog->author_name ?? $blog->author?->name ?? 'Jobs in USA Editorial') !!} },
        "publisher": {
            "@@type": "Organization",
            "name": "Jobs in USA",
            "logo": { "@@type": "ImageObject", "url": "{{ asset('public/user/images/Jobs in USA.png') }}" }
        },
        "description": {!! json_encode($blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content), 160)) !!},
        "mainEntityOfPage": { "@@type": "WebPage", "@@id": "{{ route('blog.show', $blog->slug) }}" }
    }
    </script>

    {{-- JSON-LD: BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Blog", "item": "{{ route('blog.index') }}" },
            { "@@type": "ListItem", "position": 3, "name": {!! json_encode($blog->title) !!} }
        ]
    }
    </script>
@endpush

@section('content')

@php
    // Reusable image resolver inline (push closures aren't available here)
    $blogImg = function ($path) {
        if (!$path) return asset('public/user/images/blog-compact-post-01.jpg');
        if (str_starts_with($path, 'public/') || str_starts_with($path, 'http')) return asset($path);
        return asset('public/storage/' . $path);
    };
@endphp

<style>
    /* === Blog Detail Hero (matches home theme) === */
    .blog-detail-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 60px 0 50px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .blog-detail-hero::before {
        content: ""; position: absolute; inset: 0;
        background-image: radial-gradient(circle at 12% 20%, rgba(10,10,10,.04) 0, transparent 40%),
                          radial-gradient(circle at 88% 80%, rgba(10,10,10,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .blog-detail-hero .container { position: relative; z-index: 2; }
    .blog-detail-hero .breadcrumbs-mini {
        font-size: 13px;
        color: #777;
        margin-bottom: 18px;
    }
    .blog-detail-hero .breadcrumbs-mini a {
        color: #0a0a0a;
        text-decoration: none;
        font-weight: 600;
    }
    .blog-detail-hero .breadcrumbs-mini a:hover { text-decoration: underline; }
    .blog-detail-hero .breadcrumbs-mini .sep { color: #c7c7cc; margin: 0 6px; }
    .blog-detail-hero .cat-pill {
        display: inline-block;
        background: #0a0a0a;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 13px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
        text-decoration: none;
    }
    .blog-detail-hero h1 {
        color: #0a0a0a;
        font-size: clamp(28px, 4vw, 44px);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1px;
        margin: 0 0 20px;
        max-width: 880px;
    }
    .blog-detail-hero .blog-meta {
        display: flex;
        gap: 22px;
        flex-wrap: wrap;
        font-size: 14px;
        color: #555;
        margin-bottom: 0;
    }
    .blog-detail-hero .blog-meta span {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .blog-detail-hero .blog-meta i {
        color: #0a0a0a;
        font-size: 16px;
    }
    .blog-detail-hero .blog-meta strong { color: #0a0a0a; font-weight: 700; }

    /* === Article body === */
    .blog-detail-section { padding: 60px 0; background: #fff; }

    .blog-article {
        background: #fff;
        border-radius: 16px;
    }
    .blog-article-thumb {
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 32px;
        max-height: 480px;
    }
    .blog-article-thumb img {
        width: 100%;
        height: auto;
        display: block;
        max-height: 480px;
        object-fit: cover;
    }

    /* === Rich content rendering === */
    .blog-content {
        font-size: 16px;
        line-height: 1.85;
        color: #2a2a2a;
    }
    .blog-content p {
        margin: 0 0 20px;
        color: #2a2a2a;
        font-size: 16px;
        line-height: 1.85;
    }
    .blog-content h2 {
        font-size: clamp(22px, 2.6vw, 28px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.3;
        letter-spacing: -.4px;
        margin: 36px 0 16px;
        padding-left: 14px;
        position: relative;
    }
    .blog-content h2::before {
        content: "";
        position: absolute;
        left: 0; top: 8px;
        width: 4px; height: 22px;
        background: #0a0a0a;
        border-radius: 2px;
    }
    .blog-content h3 {
        font-size: 20px;
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.3;
        margin: 28px 0 12px;
    }
    .blog-content h4 {
        font-size: 17px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 24px 0 10px;
    }
    .blog-content ul, .blog-content ol {
        list-style: none;
        margin: 0 0 22px;
        padding: 0;
    }
    .blog-content ul li, .blog-content ol li {
        position: relative;
        padding-left: 28px;
        font-size: 16px;
        line-height: 1.75;
        color: #2a2a2a;
        margin-bottom: 10px;
    }
    .blog-content ul li::before {
        content: "";
        position: absolute;
        left: 0; top: 9px;
        width: 14px; height: 14px;
        border-radius: 50%;
        background: #0a0a0a;
    }
    .blog-content ul li::after {
        content: "✓";
        position: absolute;
        left: 3px; top: 4px;
        color: #fff;
        font-size: 9px;
        font-weight: 800;
    }
    .blog-content ol { counter-reset: list-counter; }
    .blog-content ol li {
        counter-increment: list-counter;
    }
    .blog-content ol li::before {
        content: counter(list-counter);
        position: absolute;
        left: 0; top: 1px;
        width: 22px; height: 22px;
        background: #0a0a0a;
        color: #fff;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .blog-content blockquote {
        margin: 26px 0;
        padding: 22px 28px;
        background: #fafafa;
        border-left: 4px solid #0a0a0a;
        border-radius: 0 12px 12px 0;
        font-size: 17px;
        line-height: 1.7;
        color: #1a1a1a;
        font-style: italic;
    }
    .blog-content a {
        color: #0a0a0a;
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1.5px solid #0a0a0a;
        transition: opacity .15s ease;
    }
    .blog-content a:hover { opacity: .65; }
    .blog-content strong { color: #0a0a0a; font-weight: 700; }
    .blog-content em { font-style: italic; color: #2a2a2a; }
    .blog-content code {
        background: #f5f5f7;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 14px;
        color: #0a0a0a;
        font-family: monospace;
    }
    .blog-content pre {
        background: #0a0a0a;
        color: #f5f5f7;
        padding: 18px 22px;
        border-radius: 10px;
        overflow-x: auto;
        margin: 22px 0;
    }
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 22px 0;
    }
    .blog-content hr {
        border: none;
        border-top: 1px solid #ececec;
        margin: 32px 0;
    }

    /* Tags */
    .blog-tags-row {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #ececec;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .blog-tags-row .label {
        font-size: 13px;
        font-weight: 700;
        color: #0a0a0a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-right: 8px;
    }
    .blog-tags-row a {
        background: #fafafa;
        border: 1px solid #ececec;
        color: #1a1a1a;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        text-decoration: none;
        transition: all .15s ease;
    }
    .blog-tags-row a:hover {
        background: #0a0a0a;
        color: #fff;
        border-color: #0a0a0a;
    }

    /* Social share */
    .blog-share-row {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #ececec;
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .blog-share-row .label {
        font-size: 13px;
        font-weight: 700;
        color: #0a0a0a;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .blog-share-row ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: inline-flex;
        gap: 8px;
    }
    .blog-share-row ul li a {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: #fafafa;
        border: 1px solid #ececec;
        color: #0a0a0a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
        transition: all .2s ease;
    }
    .blog-share-row ul li a:hover {
        background: #0a0a0a;
        color: #fff;
        border-color: #0a0a0a;
        transform: translateY(-2px);
    }

    /* Author card */
    .blog-author-card {
        margin-top: 40px;
        padding: 28px;
        background: #fafafa;
        border-radius: 16px;
        border: 1px solid #ececec;
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .blog-author-card .avatar {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a0a0a, #404040);
        color: #fff;
        font-size: 24px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .blog-author-card .info h4 {
        font-size: 16px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 0 0 4px;
    }
    .blog-author-card .info p {
        margin: 0;
        font-size: 14px;
        color: #555;
        line-height: 1.5;
    }

    /* === Sidebar === */
    .blog-side {
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: sticky;
        top: 90px;
    }
    .blog-side-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 22px;
    }
    .blog-side-card h3 {
        font-size: 14px;
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 16px;
        padding: 0 0 12px;
        border-bottom: 2px solid #0a0a0a;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        display: inline-block;
    }
    .blog-side-promo {
        background: #0a0a0a;
        border-radius: 16px;
        padding: 26px 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .blog-side-promo::before, .blog-side-promo::after {
        content: ""; position: absolute;
        border-radius: 50%;
        filter: blur(50px);
        opacity: .3;
        pointer-events: none;
    }
    .blog-side-promo::before { width: 180px; height: 180px; background: #ff5722; top: -50px; right: -40px; }
    .blog-side-promo::after { width: 140px; height: 140px; background: #5e2bff; bottom: -40px; left: -30px; }
    .blog-side-promo > * { position: relative; z-index: 2; }
    .blog-side-promo .eyebrow {
        display: inline-block;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.20);
        color: rgba(255,255,255,.85);
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        padding: 5px 11px;
        border-radius: 999px;
        margin-bottom: 12px;
    }
    .blog-side-promo h4 {
        color: #fff;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.3;
        margin: 0 0 8px;
    }
    .blog-side-promo p {
        color: rgba(255,255,255,.78);
        font-size: 13px;
        line-height: 1.55;
        margin: 0 0 16px;
    }
    .blog-side-promo .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #0a0a0a !important;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        transition: all .15s ease;
    }
    .blog-side-promo .btn:hover { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(0,0,0,.25); }

    .latest-posts-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 14px; }
    .latest-posts-list li { display: flex; gap: 12px; align-items: flex-start; }
    .latest-posts-list .thumb {
        width: 64px; height: 64px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f5f5f7;
    }
    .latest-posts-list .thumb img { width: 100%; height: 100%; object-fit: cover; }
    .latest-posts-list .info h4 { font-size: 13.5px; line-height: 1.4; margin: 0 0 4px; font-weight: 600; }
    .latest-posts-list .info h4 a { color: #0a0a0a; text-decoration: none; }
    .latest-posts-list .info h4 a:hover { color: #404040; }
    .latest-posts-list .info .date { font-size: 11.5px; color: #777; text-transform: uppercase; letter-spacing: .8px; }

    .cat-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 6px; }
    .cat-list li a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 12px;
        background: #fafafa;
        border-radius: 8px;
        color: #1a1a1a;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s ease;
    }
    .cat-list li a:hover { background: #0a0a0a; color: #fff; transform: translateX(3px); }
    .cat-list li a .count {
        font-size: 11.5px;
        background: #fff;
        padding: 2px 8px;
        border-radius: 999px;
        color: #555;
        font-weight: 700;
    }
    .cat-list li a:hover .count { background: rgba(255,255,255,.18); color: #fff; }

    /* === Related Posts === */
    .related-posts-section { padding: 60px 0 80px; background: #fafafa; border-top: 1px solid #ececec; }
    .related-posts-head {
        margin-bottom: 30px;
        text-align: center;
    }
    .related-posts-head h2 {
        font-size: clamp(24px, 2.8vw, 32px);
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 8px;
        letter-spacing: -.4px;
    }
    .related-posts-head p { color: #555; font-size: 15px; margin: 0; }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    @media (max-width: 991px) { .related-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .related-grid { grid-template-columns: 1fr; } }
    .related-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: all .25s ease;
    }
    .related-card:hover {
        border-color: #0a0a0a;
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(15,23,42,.10);
    }
    .related-card-thumb { height: 180px; overflow: hidden; background: #f5f5f7; }
    .related-card-thumb img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
    }
    .related-card:hover .related-card-thumb img { transform: scale(1.05); }
    .related-card-body { padding: 18px 20px 22px; }
    .related-card-body .meta { font-size: 12px; color: #777; margin-bottom: 8px; }
    .related-card-body h4 {
        font-size: 16px;
        font-weight: 700;
        color: #0a0a0a;
        line-height: 1.35;
        margin: 0 0 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .related-card-body p {
        font-size: 13px;
        color: #555;
        line-height: 1.65;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 991px) {
        .blog-side { position: static; margin-top: 30px; }
    }
</style>

<!-- Hero / Title -->
<section class="blog-detail-hero">
    <div class="container">
        <div class="breadcrumbs-mini">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            <a href="{{ route('blog.index') }}">Blog</a>
            @if($blog->category)
                <span class="sep">›</span>
                <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}">{{ $blog->category->name }}</a>
            @endif
        </div>
        @if($blog->category)
            <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}" class="cat-pill">{{ $blog->category->name }}</a>
        @endif
        <h1>{{ $blog->title }}</h1>
        <div class="blog-meta">
            <span><i class="icon-feather-user"></i> By <strong>{{ $blog->author_name ?? $blog->author?->name ?? 'Jobs in USA Editorial' }}</strong></span>
            @if($blog->published_at)
                <span><i class="icon-feather-calendar"></i> {{ $blog->published_at->format('M d, Y') }}</span>
            @endif
            @if($blog->reading_time)
                <span><i class="icon-feather-clock"></i> {{ $blog->reading_time }} min read</span>
            @endif
        </div>
    </div>
</section>

<!-- Article + Sidebar -->
<section class="blog-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8">
                <article class="blog-article">
                    <div class="blog-article-thumb">
                        <img src="{{ $blogImg($blog->featured_image) }}" alt="{{ $blog->title }}" loading="eager">
                    </div>

                    <div class="blog-content">
                        {!! $blog->content !!}
                    </div>

                    @if($blog->tags)
                        <div class="blog-tags-row">
                            <span class="label">Tags:</span>
                            @foreach (array_filter(array_map('trim', explode(',', $blog->tags))) as $tag)
                                <a href="{{ route('blog.index', ['tag' => $tag]) }}">{{ $tag }}</a>
                            @endforeach
                        </div>
                    @endif

                    <div class="blog-share-row">
                        <span class="label">Share:</span>
                        <ul>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $blog->slug)) }}" target="_blank" rel="noopener" title="Facebook" aria-label="Share on Facebook">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                            </a></li>
                            <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" title="Twitter / X" aria-label="Share on Twitter">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.66l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/></svg>
                            </a></li>
                            <li><a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $blog->slug)) }}" target="_blank" rel="noopener" title="LinkedIn" aria-label="Share on LinkedIn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14zM8.34 18.34v-8H5.67v8h2.67zM7 8.96a1.55 1.55 0 1 0 0-3.1 1.55 1.55 0 0 0 0 3.1zm11.34 9.38v-4.6c0-2.32-1.24-3.4-2.9-3.4-1.34 0-1.94.74-2.28 1.26v-1.08h-2.66c.04.74 0 8 0 8h2.66v-4.46c0-.24.02-.48.09-.66.18-.48.62-.98 1.36-.98.96 0 1.34.73 1.34 1.8v4.3h2.39z"/></svg>
                            </a></li>
                            <li><a href="https://wa.me/?text={{ urlencode($blog->title . ' — ' . route('blog.show', $blog->slug)) }}" target="_blank" rel="noopener" title="WhatsApp" aria-label="Share on WhatsApp">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.91 9.91 0 0 0 4.79 1.22c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.02zM12.04 20.15a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.24-8.23a8.2 8.2 0 0 1 5.82 2.41 8.2 8.2 0 0 1 2.41 5.82c0 4.54-3.69 8.24-8.23 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.7-.8-.23-.09-.39-.13-.56.12-.16.25-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.02 2.57.12.16 1.76 2.69 4.27 3.77.6.26 1.06.41 1.43.53.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28z"/></svg>
                            </a></li>
                            <li><a href="mailto:?subject={{ urlencode($blog->title) }}&body={{ urlencode(route('blog.show', $blog->slug)) }}" title="Email"><i class="icon-feather-mail"></i></a></li>
                        </ul>
                    </div>

                    {{-- Author card --}}
                    @php
                        $authorName = $blog->author_name ?? $blog->author?->name ?? 'Jobs in USA Editorial';
                        $authorInitial = mb_strtoupper(mb_substr($authorName, 0, 1));
                    @endphp
                    <div class="blog-author-card">
                        <div class="avatar">{{ $authorInitial }}</div>
                        <div class="info">
                            <h4>{{ $authorName }}</h4>
                            <p>Helping U.S. job seekers land verified roles faster — with practical career advice, salary insights, and resume tips written by hiring experts.</p>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-4">
                <aside class="blog-side">
                    <!-- Promo card -->
                    <div class="blog-side-promo">
                        <span class="eyebrow">Get Hired Faster</span>
                        <h4>Browse 230K+ verified U.S. jobs</h4>
                        <p>Sign up free, set your preferences, and let matching jobs come to you — no scams, no ghost listings.</p>
                        <a href="{{ route('register') }}" class="btn">
                            Create Free Account <i class="icon-feather-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Latest posts -->
                    <div class="blog-side-card">
                        <h3>Latest Posts</h3>
                        <ul class="latest-posts-list">
                            @forelse($latestBlogs as $latest)
                                <li>
                                    <div class="thumb">
                                        <img src="{{ $blogImg($latest->featured_image) }}" alt="{{ $latest->title }}" loading="lazy">
                                    </div>
                                    <div class="info">
                                        <h4><a href="{{ route('blog.show', $latest->slug) }}">{{ $latest->title }}</a></h4>
                                        <span class="date">{{ optional($latest->published_at)->format('M d, Y') }}</span>
                                    </div>
                                </li>
                            @empty
                                <li class="text-muted">No posts yet</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Categories -->
                    @if($categories && $categories->count())
                        <div class="blog-side-card">
                            <h3>Categories</h3>
                            <ul class="cat-list">
                                @foreach($categories as $cat)
                                    <li>
                                        <a href="{{ route('blog.index', ['category' => $cat->slug]) }}">
                                            {{ $cat->name }}
                                            <span class="count">{{ $cat->blogs_count ?? 0 }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</section>

<!-- Related Posts -->
@if($relatedPosts && $relatedPosts->count())
    <section class="related-posts-section">
        <div class="container">
            <header class="related-posts-head">
                <h2>You may also like</h2>
                <p>More career tips and job search insights from the Jobs in USA team.</p>
            </header>
            <div class="related-grid">
                @foreach($relatedPosts as $related)
                    <a href="{{ route('blog.show', $related->slug) }}" class="related-card">
                        <div class="related-card-thumb">
                            <img src="{{ $blogImg($related->featured_image) }}" alt="{{ $related->title }}" loading="lazy">
                        </div>
                        <div class="related-card-body">
                            <div class="meta">
                                {{ optional($related->published_at)->format('M d, Y') }}
                                @if($related->author_name || $related->author?->name)
                                    · By {{ $related->author_name ?? $related->author?->name }}
                                @endif
                            </div>
                            <h4>{{ $related->title }}</h4>
                            <p>{{ $related->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($related->content), 100) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
