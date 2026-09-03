@extends('user.layouts.master')
@php
    $blogCat = trim((string) request('category', ''));
    if ($blogCat !== '') {
        $blogCatLbl = ucwords(str_replace('-', ' ', $blogCat));
        $blogTitle = "{$blogCatLbl} Articles — Career Blog | JobGader";
        $blogDesc = "Read {$blogCatLbl} articles on JobGader — expert tips, U.S. employment trends and actionable advice to advance your career.";
    } else {
        $blogTitle = 'Career Advice — Employment & Business News | JobGader';
        $blogDesc = 'Read the latest career advice, recruitment insights, salary guides, remote work tips and U.S. industry trends. Expert articles to help you land your next job faster.';
    }
@endphp
@section('title', $blogTitle)
@section('meta_description', $blogDesc)
@section('meta_keywords', 'career advice, recruitment insights, employment news, business news, resume writing, interview tips, salary guide usa, remote work tips')
@section('og_title', 'Career Advice & Employment News | JobGader')
@section('og_description', 'Career advice, recruitment insights and U.S. employment news — everything you need to advance your career in the United States.')
@section('og_image', asset('public/user/images/blog-compact-post-01.jpg'))
@section('canonical', route('blog.index'))
@section('content')

@php
    // Fallback chain: featured_image (resolved) → onerror swaps to default thumb.
    // We only resolve the URL here; the <img onerror=""> handles broken paths so the
    // grid never shows a missing-image icon.
    $blogFallback = asset('public/user/images/blog-compact-post-01.jpg');
    $blogImg = function ($path) use ($blogFallback) {
        if (! $path) {
            return $blogFallback;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'public/')) {
            return asset($path);
        }
        if (str_starts_with($path, '/')) {
            return url($path);
        }

        return asset('public/storage/'.$path);
    };
    $blogOnError = "this.onerror=null;this.src='{$blogFallback}';";
@endphp

<style>
    /* === Career Advice — Magazine layout (full-width sections, no sidebars) === */
    .ca-wrap { background: #f7f8fa; padding: 0 0 60px; }
    .ca-wrap .container { max-width: 1280px; }

    /* === Hero — full-width magazine-style banner === */
    .ca-hero {
        position: relative;
        background:
            radial-gradient(circle at 12% 20%, rgba(27, 58, 107,.10) 0, transparent 40%),
            radial-gradient(circle at 88% 80%, rgba(94,43,255,.08) 0, transparent 45%),
            linear-gradient(180deg, #f8faff 0%, #ffffff 60%, #f5f5f7 100%);
        padding: 80px 0 70px;
        margin-bottom: 40px;
        border-bottom: 1px solid #ececec;
        overflow: hidden;
    }
    .ca-hero .container { position: relative; z-index: 2; max-width: 1280px; }
    .ca-hero .eyebrow {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        font-weight: 700;
        font-size: 11px;
        padding: 6px 14px;
        border-radius: 999px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 18px;
    }
    .ca-hero h1 {
        font-size: clamp(32px, 4.2vw, 52px);
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.1;
        letter-spacing: -1.5px;
        margin: 0 0 14px;
        max-width: 820px;
    }
    .ca-hero h1 .accent {
        background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #ffab40);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .ca-hero .sub {
        font-size: 17px;
        color: #555;
        line-height: 1.6;
        margin: 0 0 28px;
        max-width: 680px;
    }
    .ca-hero-stats { display: flex; gap: 36px; flex-wrap: wrap; }
    .ca-hero-stats .stat strong {
        display: block;
        font-size: 26px;
        font-weight: 800;
        color: #1b3a6b;
        letter-spacing: -.3px;
        line-height: 1;
    }
    .ca-hero-stats .stat span {
        font-size: 12px;
        color: #777;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-top: 4px;
        display: inline-block;
    }

    /* Category pills below hero */
    .ca-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .ca-head h2 { font-size: 18px; font-weight: 800; color: #1b3a6b; margin: 0; letter-spacing: -.2px; text-transform: uppercase; }
    .ca-cat-pills { display: inline-flex; gap: 8px; flex-wrap: wrap; }
    .ca-cat-pills a {
        padding: 7px 14px; border-radius: 999px;
        background: #fff; border: 1px solid #ececec;
        color: #16305a; font-size: 13px; font-weight: 600;
        text-decoration: none; transition: all .15s ease;
    }
    .ca-cat-pills a:hover { background: #f5f5f7; border-color: #1b3a6b; }
    .ca-cat-pills a.active { background: linear-gradient(135deg, #1b3a6b, #2f7fc9); border-color: #1b3a6b; color: #fff; }

    /* Section title */
    .ca-section-title {
        display: flex; align-items: center; justify-content: space-between;
        margin: 36px 0 16px; padding-bottom: 10px;
        border-bottom: 2px solid #ececec;
    }
    .ca-section-title h2 { font-size: 18px; font-weight: 800; color: #1b3a6b; margin: 0; letter-spacing: -.2px; text-transform: uppercase; }
    .ca-section-title .more-link { color: #1b3a6b; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .ca-section-title .more-link:hover { color: #2f7fc9; }

    /* === Featured grid (1 large + 4 small) === */
    .ca-featured-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 18px;
        height: 460px;
    }
    @media (max-width: 991px) {
        .ca-featured-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; height: auto; }
        .ca-feature-main { grid-column: 1 / -1; aspect-ratio: 16 / 9; height: auto; }
        .ca-feature-small { aspect-ratio: 16 / 9; height: auto; }
    }
    @media (max-width: 575px) {
        .ca-featured-grid { grid-template-columns: 1fr; }
        .ca-feature-main { aspect-ratio: 16 / 9; height: auto; }
    }
    .ca-feature-main, .ca-feature-small {
        position: relative; overflow: hidden;
        border-radius: 12px;
        text-decoration: none; display: block;
        background: #16305a;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .ca-feature-main { grid-row: 1 / span 2; }
    .ca-feature-main:hover, .ca-feature-small:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 32px rgba(15,23,42,.18);
    }
    .ca-feature-main img, .ca-feature-small img {
        width: 100%; height: 100%; object-fit: contain;
        transition: transform .35s ease;
    }
    .ca-feature-main:hover img, .ca-feature-small:hover img { transform: none; }
    .ca-feature-main::after, .ca-feature-small::after {
        content: ""; position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 30%, rgba(0,0,0,.85) 100%);
        pointer-events: none;
    }
    .ca-feature-content { position: absolute; left: 0; right: 0; bottom: 0; padding: 18px 20px; color: #fff; z-index: 1; }
    .ca-feature-main .ca-feature-content { padding: 24px 26px; }
    .ca-feature-content .cat-pill {
        display: inline-block;
        background: rgba(27, 58, 107,.92); color: #fff;
        font-size: 10.5px; font-weight: 700;
        padding: 4px 10px; border-radius: 4px;
        margin-bottom: 8px; text-transform: uppercase; letter-spacing: .6px;
    }
    .ca-feature-content h3 { color: #fff; margin: 0; font-weight: 700; line-height: 1.25; }
    .ca-feature-main .ca-feature-content h3 { font-size: 22px; }
    .ca-feature-small .ca-feature-content h3 {
        font-size: 14px;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }

    /* === Recent News (3-col × 2 rows = 6 cards) === */
    .ca-recent-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    @media (max-width: 900px) { .ca-recent-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .ca-recent-grid { grid-template-columns: 1fr; } }
    .ca-recent-card {
        display: flex; flex-direction: column;
        text-decoration: none;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .ca-recent-card:hover {
        transform: translateY(-3px);
        border-color: #1b3a6b;
        box-shadow: 0 14px 30px rgba(15,23,42,.10);
    }
    .ca-recent-card .thumb { width: 100%; aspect-ratio: 16 / 9; overflow: hidden; background: #eef5fc; }
    .ca-recent-card .thumb img { width: 100%; height: 100%; object-fit: contain; transition: transform .35s ease; }
    .ca-recent-card:hover .thumb img { transform: none; }
    .ca-recent-card .info { padding: 16px 18px; flex-grow: 1; display: flex; flex-direction: column; }
    .ca-recent-card .cat-tag {
        display: inline-block;
        font-size: 10.5px; font-weight: 700;
        color: #1b3a6b; text-transform: uppercase; letter-spacing: .8px;
        margin-bottom: 8px;
    }
    .ca-recent-card h4 {
        font-size: 15.5px; font-weight: 700; color: #1b3a6b;
        margin: 0 0 10px; line-height: 1.35;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        flex-grow: 1;
    }
    .ca-recent-card:hover h4 { color: #1b3a6b; }
    .ca-recent-card .meta { color: #777; font-size: 12px; }

    /* === Most Popular (2-col × 3 rows = 6 ranked cards) === */
    .ca-popular-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 720px) { .ca-popular-grid { grid-template-columns: 1fr; } }
    .ca-popular-item {
        display: grid;
        grid-template-columns: 48px 90px 1fr;
        gap: 14px;
        align-items: center;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 14px 16px;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .ca-popular-item:hover {
        transform: translateY(-2px);
        border-color: #1b3a6b;
        box-shadow: 0 10px 22px rgba(15,23,42,.08);
    }
    .ca-popular-item .rank {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
        color: #fff;
        border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 17px; font-weight: 800;
        box-shadow: 0 4px 10px rgba(27, 58, 107,.30);
    }
    .ca-popular-item .thumb {
        width: 90px; height: 70px;
        border-radius: 8px; overflow: hidden;
        background: #f3f4f6;
    }
    .ca-popular-item .thumb img { width: 100%; height: 100%; object-fit: cover; }
    .ca-popular-item h4 {
        font-size: 14px; font-weight: 700; color: #1b3a6b;
        margin: 0; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .ca-popular-item:hover h4 { color: #1b3a6b; }

    /* === Recruitment Insights (3-col × 1 row) === */
    .ca-insights-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    @media (max-width: 800px) { .ca-insights-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 500px) { .ca-insights-grid { grid-template-columns: 1fr; } }
    .ca-insight-card {
        position: relative; aspect-ratio: 16 / 9;
        border-radius: 12px; overflow: hidden;
        text-decoration: none; display: block;
        background: #16305a;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .ca-insight-card:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(15,23,42,.18); }
    .ca-insight-card img { width: 100%; height: 100%; object-fit: contain; transition: transform .35s ease; }
    .ca-insight-card:hover img { transform: none; }
    .ca-insight-card::after {
        content: ""; position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,.85) 100%);
    }
    .ca-insight-card .ca-feature-content { padding: 16px 18px; }
    .ca-insight-card h3 {
        font-size: 15px; font-weight: 700; color: #fff;
        line-height: 1.3; margin: 0;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }

    /* === More News (4-col grid, paginated 8 per page) === */
    .ca-morenews-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
    @media (max-width: 1100px) { .ca-morenews-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 800px)  { .ca-morenews-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 500px)  { .ca-morenews-grid { grid-template-columns: 1fr; } }
    .ca-morenews-card {
        display: flex; flex-direction: column;
        text-decoration: none;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .ca-morenews-card:hover {
        transform: translateY(-3px);
        border-color: #1b3a6b;
        box-shadow: 0 12px 26px rgba(15,23,42,.10);
    }
    .ca-morenews-card .thumb { width: 100%; aspect-ratio: 16 / 9; overflow: hidden; background: #eef5fc; }
    .ca-morenews-card .thumb img { width: 100%; height: 100%; object-fit: contain; transition: transform .35s ease; }
    .ca-morenews-card:hover .thumb img { transform: none; }
    .ca-morenews-card .info { padding: 14px 16px; flex-grow: 1; display: flex; flex-direction: column; }
    .ca-morenews-card h4 {
        font-size: 14.5px; font-weight: 700; color: #1b3a6b;
        margin: 0 0 8px; line-height: 1.35;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        flex-grow: 1;
    }
    .ca-morenews-card:hover h4 { color: #1b3a6b; }
    .ca-morenews-card .byline { color: #777; font-size: 11.5px; }
    .ca-morenews-card .byline .author { color: #1b3a6b; font-weight: 600; margin-right: 5px; }

    .ca-pagination { margin-top: 28px; }

    /* === Dark mode === */
    html.dark-mode .ca-wrap { background: var(--site-bg) !important; }
    html.dark-mode .ca-head h1 { color: #fff !important; }
    html.dark-mode .ca-head .sub { color: #cbd5e1 !important; }
    html.dark-mode .ca-cat-pills a {
        background: rgba(255,255,255,.06) !important;
        border-color: rgba(255,255,255,.12) !important;
        color: #e5e7eb !important;
    }
    html.dark-mode .ca-cat-pills a:hover { background: rgba(255,255,255,.10) !important; border-color: rgba(255,255,255,.25) !important; }
    html.dark-mode .ca-section-title { border-bottom-color: var(--site-card-bd) !important; }
    html.dark-mode .ca-section-title h2 { color: #fff !important; }
    html.dark-mode .ca-recent-card,
    html.dark-mode .ca-popular-item,
    html.dark-mode .ca-morenews-card {
        background: var(--site-card-bg) !important;
        border-color: var(--site-card-bd) !important;
        color: var(--site-text) !important;
    }
    html.dark-mode .ca-recent-card h4,
    html.dark-mode .ca-popular-item h4,
    html.dark-mode .ca-morenews-card h4 { color: #fff !important; }
    html.dark-mode .ca-recent-card .meta,
    html.dark-mode .ca-morenews-card .byline { color: var(--site-muted) !important; }
    html.dark-mode .ca-recent-card:hover h4,
    html.dark-mode .ca-popular-item:hover h4,
    html.dark-mode .ca-morenews-card:hover h4 { color: #1b3a6b !important; }
</style>

{{-- ===== Hero ===== --}}
<section class="ca-hero">
    <div class="container">
        <span class="eyebrow">Career & Employment News</span>
        <h1>Insights, advice and stories <br><span class="accent">to help you grow</span></h1>
        <p class="sub">Guides on visa sponsorship, pay and entry requirements for work in the {{ $coverage->shortList() }} &mdash; written from official sources, and clear about which routes are closed.</p>
        <div class="ca-hero-stats">
            <div class="stat"><strong>{{ number_format(\App\Models\Blog::where('status','published')->count()) }}+</strong><span>Articles</span></div>
            <div class="stat"><strong>{{ \App\Models\BlogCatgories::count() }}</strong><span>Topics</span></div>
            <div class="stat"><strong>Weekly</strong><span>New Guides</span></div>
        </div>
    </div>
</section>

<section class="ca-wrap">
    <div class="container">

        {{-- Category pills --}}
        <div class="ca-head">
            <h2>Browse by Topic</h2>
            @if($categories->isNotEmpty())
                <div class="ca-cat-pills">
                    <a href="{{ route('blog.index') }}" class="{{ ! $categorySlug ? 'active' : '' }}">All</a>
                    @foreach($categories->take(5) as $cat)
                        <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                           class="{{ $categorySlug === $cat->slug ? 'active' : '' }}">{{ $cat->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===== Featured grid (1 large + 4 small) ===== --}}
        @if($featured)
            <div class="ca-featured-grid">
                <a href="{{ route('blog.show', $featured->slug ?? $featured->id) }}" class="ca-feature-main">
                    <img src="{{ $blogImg($featured->featured_image) }}" alt="{{ $featured->title }}" loading="eager" onerror="{{ $blogOnError }}">
                    <div class="ca-feature-content">
                        @if($featured->category)
                            <span class="cat-pill">{{ $featured->category->name }}</span>
                        @endif
                        <h3>{{ $featured->title }}</h3>
                    </div>
                </a>

                @foreach($secondaryFeatured as $post)
                    <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="ca-feature-small">
                        <img src="{{ $blogImg($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" onerror="{{ $blogOnError }}">
                        <div class="ca-feature-content">
                            <h3>{{ $post->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- ===== Recent News (3 cols × 2 rows = 6 cards) ===== --}}
        @if($recentNews->isNotEmpty())
            <div class="ca-section-title">
                <h2>Recent News</h2>
                <a href="{{ route('blog.index') }}" class="more-link">More <i class="icon-feather-chevron-right"></i></a>
            </div>
            <div class="ca-recent-grid">
                @foreach($recentNews as $post)
                    <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="ca-recent-card">
                        <div class="thumb">
                            <img src="{{ $blogImg($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" onerror="{{ $blogOnError }}">
                        </div>
                        <div class="info">
                            @if($post->category)
                                <span class="cat-tag">{{ $post->category->name }}</span>
                            @endif
                            <h4>{{ $post->title }}</h4>
                            <div class="meta">{{ optional($post->published_at)->format('F j, Y') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- ===== Most Popular (2 cols × 3 rows = 6 ranked cards) ===== --}}
        @if($mostPopular->isNotEmpty())
            <div class="ca-section-title">
                <h2>Most Popular</h2>
                <a href="{{ route('blog.index') }}" class="more-link">More <i class="icon-feather-chevron-right"></i></a>
            </div>
            <div class="ca-popular-grid">
                @foreach($mostPopular as $i => $post)
                    <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="ca-popular-item">
                        <div class="rank">{{ $i + 1 }}</div>
                        <div class="thumb">
                            <img src="{{ $blogImg($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" onerror="{{ $blogOnError }}">
                        </div>
                        <h4>{{ $post->title }}</h4>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- ===== Recruitment Insights (3 cols × 1 row) ===== --}}
        @if($recruitmentInsights->isNotEmpty())
            <div class="ca-section-title">
                <h2>Recruitment Insights</h2>
                <a href="{{ route('blog.index') }}" class="more-link">More <i class="icon-feather-chevron-right"></i></a>
            </div>
            <div class="ca-insights-grid">
                @foreach($recruitmentInsights as $post)
                    <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="ca-insight-card">
                        <img src="{{ $blogImg($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" onerror="{{ $blogOnError }}">
                        <div class="ca-feature-content">
                            <h3>{{ $post->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- ===== More News (4 cols × 2 rows = 8 per page, paginated) ===== --}}
        @if($moreNews->count())
            <div class="ca-section-title">
                <h2>More News</h2>
            </div>
            <div class="ca-morenews-grid">
                @foreach($moreNews as $post)
                    <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="ca-morenews-card">
                        <div class="thumb">
                            <img src="{{ $blogImg($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" onerror="{{ $blogOnError }}">
                        </div>
                        <div class="info">
                            <h4>{{ $post->title }}</h4>
                            <div class="byline">
                                @if($post->author_name)
                                    <span class="author">{{ $post->author_name }}</span>
                                @elseif($post->author)
                                    <span class="author">{{ $post->author->name }}</span>
                                @endif
                                {{ optional($post->published_at)->format('M j, Y') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($moreNews->hasPages())
                <div class="ca-pagination">
                    {{ $moreNews->onEachSide(0)->links('vendor.pagination.custom') }}
                </div>
            @endif
        @endif

    </div>
</section>

@endsection
