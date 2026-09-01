@extends('user.layouts.master')
@section('title', 'Job Categories — Browse Jobs by Industry in the USA')
@section('meta_description', 'Browse ' . number_format($heroStats['total_categories'] ?? 0) . '+ job categories on Jobs in USA. Find healthcare, IT, construction, retail, sales, education, finance and more — verified U.S. jobs across every industry.')
@section('meta_keywords', 'job categories usa, jobs by industry, healthcare jobs, IT jobs, construction jobs, retail jobs, sales jobs, education jobs, browse jobs by category')
@section('content')

<style>
    /* === Categories Hero (matches home/jobs — light gradient, dark text) === */
    .cat-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 70px 0 60px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .cat-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: radial-gradient(circle at 12% 20%, rgba(10,10,10,.04) 0, transparent 40%),
                          radial-gradient(circle at 88% 80%, rgba(10,10,10,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .cat-hero .container { position: relative; z-index: 2; text-align: center; }
    .cat-hero .breadcrumbs-mini { color: #777; font-size: 13px; margin-bottom: 14px; }
    .cat-hero .breadcrumbs-mini a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .cat-hero .breadcrumbs-mini a:hover { text-decoration: underline; }
    .cat-hero .eyebrow {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 18px;
        box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .cat-hero h1 {
        color: #0a0a0a;
        font-size: clamp(30px, 4.4vw, 52px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 0 18px;
        max-width: 920px;
        margin-left: auto; margin-right: auto;
    }
    .cat-hero h1 .accent {
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .cat-hero p.lead {
        color: #555;
        font-size: clamp(15px, 1.5vw, 17px);
        line-height: 1.65;
        max-width: 720px;
        margin: 0 auto 28px;
    }
    .cat-hero .hero-stats {
        display: inline-flex;
        gap: 32px;
        padding: 20px 36px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(15,23,42,.05);
        flex-wrap: wrap;
        justify-content: center;
    }
    .cat-hero .hero-stats .stat strong {
        display: block;
        font-size: 24px;
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.1;
        letter-spacing: -.5px;
    }
    .cat-hero .hero-stats .stat span {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #777;
        font-weight: 600;
    }

    /* === SEO Split Section (image + content + button) === */
    .cat-seo-section { padding: 80px 0; background: #fff; }
    .cat-seo-grid {
        display: grid;
        grid-template-columns: 1fr 1.05fr;
        gap: 60px;
        align-items: center;
    }
    @media (max-width: 991px) { .cat-seo-grid { grid-template-columns: 1fr; gap: 40px; } }

    .cat-seo-visual {
        position: relative;
        min-height: 460px;
    }
    .cat-seo-frame {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(15, 23, 42, .12);
        animation: catFloat 6s ease-in-out infinite;
    }
    .cat-seo-frame::before {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(135deg, transparent 50%, rgba(10,10,10,.10) 100%);
        z-index: 2; pointer-events: none;
    }
    .cat-seo-frame img {
        width: 100%;
        min-height: 460px;
        object-fit: cover;
        display: block;
        transition: transform .8s ease;
    }
    .cat-seo-visual:hover .cat-seo-frame img { transform: scale(1.05); }

    .cat-seo-visual::before {
        content: ""; position: absolute;
        top: -30px; left: -30px;
        width: 200px; height: 200px;
        background: linear-gradient(135deg, #0a0a0a, #404040);
        border-radius: 50%;
        opacity: .04; z-index: 0;
        animation: catFloat 8s ease-in-out infinite reverse;
    }
    .cat-seo-visual::after {
        content: ""; position: absolute;
        bottom: -20px; right: -20px;
        width: 160px; height: 160px;
        background: linear-gradient(135deg, #0a0a0a, #404040);
        border-radius: 50%;
        opacity: .03; z-index: 0;
        animation: catFloat 10s ease-in-out infinite;
    }

    .cat-float-stat {
        position: absolute;
        background: #fff;
        border-radius: 14px;
        padding: 14px 18px;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .12);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 3;
        min-width: 200px;
        animation: catFloatBadge 5s ease-in-out infinite;
    }
    .cat-float-stat.top { top: 32px; right: -28px; }
    .cat-float-stat.bot { bottom: 38px; left: -28px; animation-delay: .8s; }
    .cat-float-stat .ico {
        width: 42px; height: 42px;
        border-radius: 10px;
        background: #0a0a0a;
        color: #fff;
        display: inline-flex;
        align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .cat-float-stat .ico.green { background: #047857; }
    .cat-float-stat .text strong {
        display: block;
        font-size: 15px;
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.2;
    }
    .cat-float-stat .text span {
        font-size: 12px;
        color: #777;
        font-weight: 500;
    }

    .cat-seo-content .eyebrow {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 18px;
    }
    .cat-seo-content h2 {
        font-size: clamp(26px, 3vw, 38px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.15;
        letter-spacing: -.6px;
        margin: 0 0 18px;
    }
    .cat-seo-content > p {
        color: #555;
        font-size: 15.5px;
        line-height: 1.75;
        margin: 0 0 18px;
        max-width: 540px;
    }
    .cat-feature-list {
        list-style: none;
        padding: 0;
        margin: 0 0 28px;
    }
    .cat-feature-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14.5px;
        color: #1a1a1a;
        line-height: 1.55;
        margin-bottom: 12px;
    }
    .cat-feature-list li i {
        color: #0a0a0a;
        font-size: 18px;
        margin-top: 1px;
        flex-shrink: 0;
    }
    .cat-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #0a0a0a;
        color: #fff !important;
        font-size: 15px;
        font-weight: 600;
        padding: 14px 28px;
        border-radius: 10px;
        text-decoration: none !important;
        border: 1.5px solid #0a0a0a;
        transition: all .15s ease;
    }
    .cat-cta-btn:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }

    @keyframes catFloat {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-12px); }
    }
    @keyframes catFloatBadge {
        0%, 100% { transform: translateY(0) translateX(0); }
        50%      { transform: translateY(-10px) translateX(4px); }
    }

    /* === Categories Grid Section === */
    .cat-grid-section { padding: 70px 0 80px; background: #fafafa; border-top: 1px solid #ececec; }
    .cat-grid-head {
        text-align: center;
        max-width: 760px;
        margin: 0 auto 48px;
    }
    .cat-grid-head .eyebrow {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 14px;
    }
    .cat-grid-head h2 {
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin: 0 0 12px;
    }
    .cat-grid-head p {
        color: #555;
        font-size: 15.5px;
        line-height: 1.65;
        margin: 0;
    }

    .cat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
    @media (max-width: 1199px) { .cat-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .cat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { .cat-grid { grid-template-columns: 1fr; } }

    .cat-card {
        position: relative;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 28px 22px 24px;
        text-decoration: none !important;
        color: inherit;
        transition: all .25s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .cat-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: #0a0a0a;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s ease;
    }
    .cat-card:hover {
        transform: translateY(-5px);
        border-color: #0a0a0a;
        box-shadow: 0 18px 36px rgba(15, 23, 42, .10);
    }
    .cat-card:hover::before { transform: scaleX(1); }
    .cat-card .openings-pill {
        position: absolute;
        top: 18px;
        right: 18px;
        background: #ecfdf5;
        color: #047857;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .cat-card .openings-pill::before {
        content: "";
        width: 5px; height: 5px;
        background: #10b981;
        border-radius: 50%;
    }
    .cat-card .openings-pill.zero {
        background: #f5f5f7;
        color: #777;
    }
    .cat-card .openings-pill.zero::before { background: #c7c7cc; }
    .cat-card .ico-box {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #0a0a0a;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-top: 6px;
        transition: background .25s ease;
    }
    .cat-card:hover .ico-box { background: #1a1a1a; }
    .cat-card h3 {
        font-size: 16.5px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 0;
        line-height: 1.3;
    }
    .cat-card .jobs-count {
        font-size: 13px;
        color: #777;
        margin: 0;
    }
    .cat-card .jobs-count strong { color: #0a0a0a; font-weight: 700; }
    .cat-card .arrow-cta {
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px dashed #ececec;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #0a0a0a;
        text-transform: uppercase;
        letter-spacing: .5px;
        transition: gap .15s ease;
    }
    .cat-card:hover .arrow-cta { gap: 10px; }

    /* Empty state */
    .cat-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border: 1px dashed #ddd;
        border-radius: 14px;
    }
    .cat-empty i { font-size: 50px; color: #c7c7cc; }
    .cat-empty h4 { font-size: 18px; color: #0a0a0a; margin: 14px 0 6px; font-weight: 700; }
    .cat-empty p { color: #777; margin: 0; }

    /* Pagination — dark */
    .cat-pagination { padding: 40px 0 0; display: flex; justify-content: center; }
    .cat-pagination ul {
        list-style: none;
        margin: 0; padding: 0;
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .cat-pagination li a, .cat-pagination li span {
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        color: #0a0a0a;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all .15s ease;
    }
    .cat-pagination li a:hover { background: #f5f5f7; border-color: #0a0a0a; }
    .cat-pagination li a.current-page,
    .cat-pagination li.active a {
        background: #0a0a0a;
        color: #fff;
        border-color: #0a0a0a;
    }
    .cat-pagination li.disabled span { opacity: .35; cursor: not-allowed; }

    @media (max-width: 768px) {
        .cat-hero { padding: 50px 0 40px; }
        .cat-hero .hero-stats { gap: 18px; padding: 14px 22px; }
        .cat-hero .hero-stats .stat strong { font-size: 20px; }
        .cat-seo-visual { min-height: 360px; }
        .cat-seo-frame img { min-height: 360px; }
        .cat-float-stat.top { right: 16px; }
        .cat-float-stat.bot { left: 16px; }
    }
</style>

@php
    // Friendly icon map for known category names — falls back to briefcase
    $catIconMap = [
        'Healthcare' => 'icon-line-awesome-medkit', 'Healthcare & Medical' => 'icon-line-awesome-medkit',
        'IT' => 'icon-line-awesome-laptop', 'I.T. & Communications' => 'icon-line-awesome-laptop', 'IT Engineer' => 'icon-line-awesome-laptop',
        'Construction' => 'icon-line-awesome-cog',
        'Education' => 'icon-line-awesome-graduation-cap', 'Education & Training' => 'icon-line-awesome-graduation-cap',
        'Marketing' => 'icon-feather-pie-chart', 'Sales' => 'icon-line-awesome-line-chart',
        'Banking' => 'icon-line-awesome-bank', 'Banking & Financial Services' => 'icon-line-awesome-bank',
        'Retail' => 'icon-line-awesome-shopping-bag', 'Retail & Consumer Products' => 'icon-line-awesome-shopping-bag',
        'Transport' => 'icon-line-awesome-truck', 'Transport & Logistics' => 'icon-line-awesome-truck',
        'Hospitality' => 'icon-line-awesome-suitcase', 'Hospitality & Tourism' => 'icon-line-awesome-suitcase',
        'Trades' => 'icon-line-awesome-wrench', 'Trades & Services' => 'icon-line-awesome-wrench',
        'Call Centre / CustomerService' => 'icon-line-awesome-phone', 'Customer Service' => 'icon-line-awesome-phone',
        'Administration' => 'icon-line-awesome-folder-open',
        'Engineering' => 'icon-line-awesome-cogs',
        'Government & Defence' => 'icon-line-awesome-shield',
        'Executive Positions' => 'icon-line-awesome-briefcase',
        'Community & Sport' => 'icon-line-awesome-users',
        'Consulting & Corporate Strategy' => 'icon-line-awesome-line-chart',
        'Advert / Media / Entertainment' => 'icon-line-awesome-camera',
        'Accounting' => 'icon-line-awesome-calculator',
        'Design & Art' => 'icon-line-awesome-cubes',
    ];
@endphp

<!-- Hero -->
<section class="cat-hero">
    <div class="container">
        <div class="breadcrumbs-mini">
            <a href="{{ url('/') }}">Home</a> &nbsp;&rsaquo;&nbsp; Job Categories
        </div>
        <span class="eyebrow" data-aos="fade-down" data-aos-duration="600">Browse by Industry</span>
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Find Your Perfect Job <span class="accent">Category</span> in the USA</h1>
        <p class="lead" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">Explore {{ number_format($heroStats['total_categories'] ?? 0) }}+ job categories across every American industry — from healthcare and IT to construction, retail, education, and beyond. Narrow your search and apply to verified roles in seconds.</p>
        <div class="hero-stats">
            <div class="stat">
                <strong>{{ number_format($heroStats['total_categories'] ?? 0) }}+</strong>
                <span>Categories</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_jobs'] ?? 0) }}+</strong>
                <span>Active Jobs</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_companies'] ?? 0) }}+</strong>
                <span>Employers</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_locations'] ?? 50) }}+</strong>
                <span>Locations</span>
            </div>
        </div>
    </div>
</section>

<!-- SEO Split Section -->
<section class="cat-seo-section">
    <div class="container">
        <div class="cat-seo-grid">
            <!-- Left: Image with floating stats -->
            <div class="cat-seo-visual" aria-hidden="true">
                <div class="cat-seo-frame">
                    <img src="{{ asset('public/user/images/home-background-03.webp') }}"
                         alt="Browse jobs by industry across the United States"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('public/user/images/home-background-03.jpg') }}'">
                </div>
                <div class="cat-float-stat top">
                    <div class="ico green"><i class="icon-feather-briefcase"></i></div>
                    <div class="text">
                        <strong>{{ number_format($heroStats['total_jobs'] ?? 0) }}+</strong>
                        <span>Open positions</span>
                    </div>
                </div>
                <div class="cat-float-stat bot">
                    <div class="ico"><i class="icon-feather-shield"></i></div>
                    <div class="text">
                        <strong>Verified</strong>
                        <span>Trusted employers only</span>
                    </div>
                </div>
            </div>

            <!-- Right: Content + button -->
            <div class="cat-seo-content">
                <span class="eyebrow">Why Browse by Category</span>
                <h2>Find the right job, faster — by industry that fits your skills</h2>
                <p>Every career path is different, and so is every job seeker. Our category-based search lets you focus on the industries you know best — whether you're a registered nurse, a software developer, a CDL driver, or a sales professional. Each category is updated daily with new openings from verified U.S. employers.</p>

                <ul class="cat-feature-list">
                    <li><i class="icon-feather-check-circle"></i> Curated job listings across {{ number_format($heroStats['total_categories'] ?? 0) }}+ industries — no irrelevant noise</li>
                    <li><i class="icon-feather-check-circle"></i> Real-time openings count for every category you browse</li>
                    <li><i class="icon-feather-check-circle"></i> Verified employers only — no scams, no ghost jobs, no surprises</li>
                    <li><i class="icon-feather-check-circle"></i> Smart job alerts so you never miss a matching role</li>
                </ul>

                <a href="{{ route('jobs.index') }}" class="cat-cta-btn">
                    Browse All Jobs <i class="icon-feather-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="cat-grid-section">
    <div class="container">
        <header class="cat-grid-head">
            <span class="eyebrow">All Categories</span>
            <h2>Explore Every Job Category We Cover</h2>
            <p>Click any category to view the latest verified openings, filter by state and ZIP, and apply free in one click.</p>
        </header>

        <div class="cat-grid">
            @forelse($categories as $category)
                @php
                    $iconClass = $catIconMap[$category->name] ?? ($category->icon ?? 'icon-line-awesome-briefcase');
                    $jobsCount = $category->jobs_count ?? 0;
                    $hasOpenings = ($category->active_openings ?? 0) > 0;
                @endphp
                <a href="{{ route('jobs.category', ['category' => $category->slug]) }}" class="cat-card"
                   title="Browse {{ $category->name }} jobs">
                    @if($hasOpenings)
                        <span class="openings-pill">{{ $category->active_openings }} {{ \Illuminate\Support\Str::plural('opening', $category->active_openings) }}</span>
                    @else
                        <span class="openings-pill zero">No openings</span>
                    @endif

                    <div class="ico-box" aria-hidden="true">
                        <i class="{{ $iconClass }}"></i>
                    </div>

                    <h3>{{ $category->name }}</h3>
                    <p class="jobs-count"><strong>{{ number_format($jobsCount) }}</strong> {{ \Illuminate\Support\Str::plural('Job', $jobsCount) }} available</p>

                    <span class="arrow-cta">
                        Explore <i class="icon-feather-arrow-right"></i>
                    </span>
                </a>
            @empty
                <div class="cat-empty">
                    <i class="icon-feather-folder"></i>
                    <h4>No categories yet</h4>
                    <p>Check back soon — we're adding new industries every week.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        {{ $categories->onEachSide(2)->links() }}
    </div>
</section>

@endsection
