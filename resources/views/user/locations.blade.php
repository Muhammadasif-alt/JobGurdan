@extends('user.layouts.master')
@section('title', 'Browse Jobs by Location — Find Verified Jobs in Every U.S. State')
@section('meta_description', 'Search ' . number_format($heroStats['total_jobs'] ?? 0) . '+ verified jobs across ' . number_format($heroStats['total_states'] ?? 50) . ' U.S. states. Find local opportunities in your city, area, or ZIP — apply free on Jobs in USA.')
@section('meta_keywords', 'jobs by location usa, jobs near me, find jobs by city, browse jobs by state, jobs by zip code, local jobs america')

@section('content')

<style>
    /* === Locations Hero (light gradient, dark text — matches home/jobs/companies) === */
    .loc-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 70px 0 60px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .loc-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: radial-gradient(circle at 12% 20%, rgba(10,10,10,.04) 0, transparent 40%),
                          radial-gradient(circle at 88% 80%, rgba(10,10,10,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .loc-hero .container { position: relative; z-index: 2; text-align: center; }
    .loc-hero .breadcrumbs-mini { color: #777; font-size: 13px; margin-bottom: 14px; }
    .loc-hero .breadcrumbs-mini a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .loc-hero .breadcrumbs-mini a:hover { text-decoration: underline; }
    .loc-hero .eyebrow {
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
    .loc-hero h1 {
        color: #0a0a0a;
        font-size: clamp(30px, 4.4vw, 52px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 0 18px;
        max-width: 920px;
        margin-left: auto; margin-right: auto;
    }
    .loc-hero h1 .accent {
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .loc-hero p.lead {
        color: #555;
        font-size: clamp(15px, 1.5vw, 17px);
        line-height: 1.65;
        max-width: 720px;
        margin: 0 auto 28px;
    }
    .loc-hero .hero-stats {
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
    .loc-hero .hero-stats .stat strong {
        display: block;
        font-size: 24px;
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.1;
        letter-spacing: -.5px;
    }
    .loc-hero .hero-stats .stat span {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #777;
        font-weight: 600;
    }

    /* === SEO Split Section === */
    .loc-seo-section { padding: 80px 0; background: #fff; }
    .loc-seo-grid {
        display: grid;
        grid-template-columns: 1fr 1.05fr;
        gap: 60px;
        align-items: center;
    }
    @media (max-width: 991px) { .loc-seo-grid { grid-template-columns: 1fr; gap: 40px; } }

    .loc-seo-visual { position: relative; min-height: 460px; }
    .loc-seo-frame {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(15, 23, 42, .12);
        animation: locFloat 6s ease-in-out infinite;
    }
    .loc-seo-frame::before {
        content: ""; position: absolute; inset: 0;
        background: linear-gradient(135deg, transparent 50%, rgba(10,10,10,.10) 100%);
        z-index: 2; pointer-events: none;
    }
    .loc-seo-frame img {
        width: 100%;
        min-height: 460px;
        object-fit: cover;
        display: block;
        transition: transform .8s ease;
    }
    .loc-seo-visual:hover .loc-seo-frame img { transform: scale(1.05); }
    .loc-seo-visual::before {
        content: ""; position: absolute;
        top: -30px; left: -30px;
        width: 200px; height: 200px;
        background: linear-gradient(135deg, #0a0a0a, #404040);
        border-radius: 50%;
        opacity: .04; z-index: 0;
        animation: locFloat 8s ease-in-out infinite reverse;
    }
    .loc-seo-visual::after {
        content: ""; position: absolute;
        bottom: -20px; right: -20px;
        width: 160px; height: 160px;
        background: linear-gradient(135deg, #0a0a0a, #404040);
        border-radius: 50%;
        opacity: .03; z-index: 0;
        animation: locFloat 10s ease-in-out infinite;
    }
    .loc-float-stat {
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
        animation: locFloatBadge 5s ease-in-out infinite;
    }
    .loc-float-stat.top { top: 32px; right: -28px; }
    .loc-float-stat.bot { bottom: 38px; left: -28px; animation-delay: .8s; }
    .loc-float-stat .ico {
        width: 42px; height: 42px;
        border-radius: 10px;
        background: #0a0a0a;
        color: #fff;
        display: inline-flex;
        align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .loc-float-stat .ico.green { background: #047857; }
    .loc-float-stat .text strong {
        display: block;
        font-size: 15px;
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.2;
    }
    .loc-float-stat .text span { font-size: 12px; color: #777; font-weight: 500; }

    .loc-seo-content .eyebrow {
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
    .loc-seo-content h2 {
        font-size: clamp(26px, 3vw, 38px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.15;
        letter-spacing: -.6px;
        margin: 0 0 18px;
    }
    .loc-seo-content > p {
        color: #555;
        font-size: 15.5px;
        line-height: 1.75;
        margin: 0 0 18px;
        max-width: 540px;
    }
    .loc-feature-list {
        list-style: none;
        padding: 0;
        margin: 0 0 28px;
    }
    .loc-feature-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14.5px;
        color: #1a1a1a;
        line-height: 1.55;
        margin-bottom: 12px;
    }
    .loc-feature-list li i {
        color: #0a0a0a;
        font-size: 18px;
        margin-top: 1px;
        flex-shrink: 0;
    }
    .loc-cta-btn {
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
    .loc-cta-btn:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }

    @keyframes locFloat {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-12px); }
    }
    @keyframes locFloatBadge {
        0%, 100% { transform: translateY(0) translateX(0); }
        50%      { transform: translateY(-10px) translateX(4px); }
    }

    /* === Locations Grid === */
    .loc-grid-section {
        padding: 70px 0 80px;
        background: #fafafa;
        border-top: 1px solid #ececec;
    }
    .loc-grid-head {
        text-align: center;
        max-width: 760px;
        margin: 0 auto 48px;
    }
    .loc-grid-head .eyebrow {
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
    .loc-grid-head h2 {
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin: 0 0 12px;
    }
    .loc-grid-head p { color: #555; font-size: 15.5px; line-height: 1.65; margin: 0; }

    .loc-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    @media (max-width: 991px) { .loc-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .loc-grid { grid-template-columns: 1fr; } }

    .loc-card {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        height: 220px;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none !important;
        background-size: cover;
        background-position: center;
        transition: transform .3s ease, box-shadow .3s ease;
        isolation: isolate;
    }
    .loc-card::before {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(10,10,10,.10) 0%, rgba(10,10,10,.55) 60%, rgba(10,10,10,.85) 100%);
        z-index: 1;
        transition: background .25s ease;
    }
    .loc-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 22px 44px rgba(15,23,42,.18);
    }
    .loc-card:hover::before {
        background: linear-gradient(180deg, rgba(10,10,10,.15) 0%, rgba(10,10,10,.65) 50%, rgba(10,10,10,.92) 100%);
    }
    .loc-card-content {
        position: relative;
        z-index: 2;
        padding: 22px 22px 20px;
        color: #fff;
    }
    .loc-card-content h3 {
        font-size: 19px;
        font-weight: 800;
        color: #fff;
        margin: 0 0 4px;
        letter-spacing: -.3px;
        line-height: 1.2;
    }
    .loc-card-content h3 .area {
        font-weight: 500;
        font-size: 15px;
        color: rgba(255,255,255,.85);
    }
    .loc-card-content .zip {
        display: inline-block;
        font-size: 12.5px;
        color: rgba(255,255,255,.85);
        margin-top: 4px;
        letter-spacing: .5px;
    }
    .loc-card-content .jobs-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,.18);
        color: #fff;
        backdrop-filter: blur(8px);
        font-size: 12.5px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 999px;
        margin-top: 10px;
        border: 1px solid rgba(255,255,255,.25);
    }
    .loc-card-content .jobs-badge::before {
        content: "";
        width: 6px; height: 6px;
        background: #34d399;
        border-radius: 50%;
        animation: pulseDot 1.5s infinite;
    }
    @keyframes pulseDot {
        0%, 100% { box-shadow: 0 0 0 0 rgba(52,211,153,.5); }
        70%      { box-shadow: 0 0 0 6px rgba(52,211,153,0); }
    }
    .loc-card .corner-arrow {
        position: absolute;
        top: 18px; right: 18px;
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.25);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        z-index: 2;
        transition: all .25s ease;
    }
    .loc-card:hover .corner-arrow {
        background: #fff;
        color: #0a0a0a;
        transform: rotate(-45deg);
    }

    .loc-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border: 1px dashed #ddd;
        border-radius: 14px;
    }
    .loc-empty i { font-size: 50px; color: #c7c7cc; }
    .loc-empty h4 { font-size: 18px; color: #0a0a0a; margin: 14px 0 6px; font-weight: 700; }
    .loc-empty p { color: #777; margin: 0; }

    /* Pagination — dark */
    .loc-pagination { padding: 40px 0 0; display: flex; justify-content: center; }
    .loc-pagination ul {
        list-style: none; margin: 0; padding: 0;
        display: inline-flex; gap: 6px; flex-wrap: wrap;
    }
    .loc-pagination li a, .loc-pagination li span {
        min-width: 40px; height: 40px;
        padding: 0 12px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        color: #0a0a0a;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center; justify-content: center;
        text-decoration: none;
        transition: all .15s ease;
    }
    .loc-pagination li a:hover { background: #f5f5f7; border-color: #0a0a0a; }
    .loc-pagination li a.current-page,
    .loc-pagination li.active a {
        background: #0a0a0a;
        color: #fff;
        border-color: #0a0a0a;
    }
    .loc-pagination li.disabled span { opacity: .35; cursor: not-allowed; }

    /* === Top Hiring States — eye-catching pre-CTA section === */
    .top-states-section {
        padding: 80px 0;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    .top-states-section::before {
        content: "";
        position: absolute;
        top: -100px; left: 50%;
        transform: translateX(-50%);
        width: 800px; height: 800px;
        background: radial-gradient(circle, rgba(10,10,10,.03) 0%, transparent 60%);
        pointer-events: none;
    }
    .top-states-section .container { position: relative; z-index: 2; }
    .top-states-head {
        text-align: center;
        max-width: 760px;
        margin: 0 auto 48px;
    }
    .top-states-head .eyebrow {
        display: inline-block;
        background: #f5f5f7;
        color: #0a0a0a;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 14px;
    }
    .top-states-head h2 {
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin: 0 0 12px;
    }
    .top-states-head p { color: #555; font-size: 15.5px; line-height: 1.65; margin: 0; }

    .top-states-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }
    @media (max-width: 768px) { .top-states-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .top-states-grid { grid-template-columns: 1fr; } }

    .top-state-card {
        position: relative;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 28px 24px;
        text-decoration: none !important;
        color: inherit;
        transition: all .3s ease;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .top-state-card::after {
        content: "";
        position: absolute;
        right: -30px; top: -30px;
        width: 120px; height: 120px;
        background: #0a0a0a;
        border-radius: 50%;
        opacity: .03;
        transition: transform .35s ease, opacity .25s ease;
    }
    .top-state-card:hover {
        transform: translateY(-4px);
        border-color: #0a0a0a;
        box-shadow: 0 18px 40px rgba(15,23,42,.10);
    }
    .top-state-card:hover::after { transform: scale(1.5); opacity: .06; }
    .top-state-card .rank {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #0a0a0a;
        color: #fff;
        font-size: 20px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .top-state-card .info { min-width: 0; }
    .top-state-card .info h3 {
        font-size: 18px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 0 0 4px;
        letter-spacing: -.2px;
    }
    .top-state-card .info .count {
        font-size: 13.5px;
        color: #555;
    }
    .top-state-card .info .count strong { color: #0a0a0a; font-weight: 800; }
    .top-state-card .arrow {
        margin-left: auto;
        color: #c7c7cc;
        font-size: 18px;
        transition: all .25s ease;
    }
    .top-state-card:hover .arrow { color: #0a0a0a; transform: translateX(4px); }

    @media (max-width: 768px) {
        .loc-hero { padding: 50px 0 40px; }
        .loc-hero .hero-stats { gap: 18px; padding: 14px 22px; }
        .loc-hero .hero-stats .stat strong { font-size: 20px; }
        .loc-seo-visual { min-height: 360px; }
        .loc-seo-frame img { min-height: 360px; }
        .loc-float-stat.top { right: 16px; }
        .loc-float-stat.bot { left: 16px; }
    }
</style>

<!-- Hero -->
<section class="loc-hero">
    <div class="container">
        <div class="breadcrumbs-mini">
            <a href="{{ url('/') }}">Home</a> &nbsp;&rsaquo;&nbsp; Browse Locations
        </div>
        <span class="eyebrow" data-aos="fade-down" data-aos-duration="600">Browse by Location</span>
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Find Verified Jobs in Every <span class="accent">U.S. State &amp; City</span></h1>
        <p class="lead" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">Search {{ number_format($heroStats['total_jobs'] ?? 0) }}+ jobs across {{ number_format($heroStats['total_states'] ?? 50) }} U.S. states. Whether you're looking for opportunities in your hometown or relocating across the country, find roles in the city, area, or ZIP code that fits you best.</p>
        <div class="hero-stats">
            <div class="stat">
                <strong>{{ number_format($heroStats['total_states'] ?? 50) }}+</strong>
                <span>U.S. States</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_locations'] ?? 0) }}+</strong>
                <span>Cities &amp; Areas</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_jobs'] ?? 0) }}+</strong>
                <span>Active Jobs</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_companies'] ?? 0) }}+</strong>
                <span>Employers</span>
            </div>
        </div>
    </div>
</section>

<!-- SEO Split Section -->
<section class="loc-seo-section">
    <div class="container">
        <div class="loc-seo-grid">
            <!-- Left: Image with floating badges -->
            <div class="loc-seo-visual" aria-hidden="true">
                <div class="loc-seo-frame">
                    <img src="{{ asset('public/user/images/popular-location-02.jpg') }}"
                         alt="Browse jobs by U.S. state, city, and ZIP code"
                         loading="lazy">
                </div>
                <div class="loc-float-stat top">
                    <div class="ico green"><i class="icon-feather-map-pin"></i></div>
                    <div class="text">
                        <strong>{{ number_format($heroStats['total_locations'] ?? 0) }}+</strong>
                        <span>Cities covered</span>
                    </div>
                </div>
                <div class="loc-float-stat bot">
                    <div class="ico"><i class="icon-feather-briefcase"></i></div>
                    <div class="text">
                        <strong>{{ number_format($heroStats['total_jobs'] ?? 0) }}+</strong>
                        <span>Verified openings</span>
                    </div>
                </div>
            </div>

            <!-- Right: Content + button -->
            <div class="loc-seo-content">
                <span class="eyebrow">Why Search by Location</span>
                <h2>Find local jobs in the city or state where you actually want to work</h2>
                <p>Commute matters. So does community. Our location-based search lets you target verified job openings in the exact U.S. state, metro area, city, or ZIP code you call home — or the place you're relocating to. From bustling tech hubs to quiet rural towns, we cover all 50 states with jobs you can actually apply for.</p>

                <ul class="loc-feature-list">
                    <li><i class="icon-feather-check-circle"></i> Drill down by state, area, and ZIP — find jobs that match your zone</li>
                    <li><i class="icon-feather-check-circle"></i> Daily updated listings from verified U.S. employers across every region</li>
                    <li><i class="icon-feather-check-circle"></i> Remote &amp; hybrid filters available for flexible work arrangements</li>
                    <li><i class="icon-feather-check-circle"></i> Local job alerts notify you when a role goes live nearby</li>
                </ul>

                <a href="{{ route('jobs.index') }}" class="loc-cta-btn">
                    Search Jobs by Location <i class="icon-feather-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Locations Grid -->
<section class="loc-grid-section">
    <div class="container">
        <header class="loc-grid-head">
            <span class="eyebrow">All Locations</span>
            <h2>Explore Every Location We Cover</h2>
            <p>Click any city or state to see the latest verified job openings in that area.</p>
        </header>

        <div class="loc-grid">
            @forelse($locations as $location)
                @php
                    $cardImg = $locationImages[crc32($location->name) % count($locationImages)] ?? $defaultImage;
                    $searchParams = ['location' => $location->name];
                @endphp
                <a href="{{ route('jobs.search', $searchParams) }}"
                   class="loc-card"
                   style="background-image: url('{{ asset($cardImg) }}');"
                   title="View jobs in {{ $location->name }}">
                    <span class="corner-arrow"><i class="icon-feather-arrow-up-right"></i></span>
                    <div class="loc-card-content">
                        <h3>Jobs in {{ $location->name }}</h3>
                        <span class="jobs-badge">
                            {{ number_format($location->jobs_count) }} {{ \Illuminate\Support\Str::plural('Job', $location->jobs_count) }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="loc-empty">
                    <i class="icon-feather-map"></i>
                    <h4>No locations yet</h4>
                    <p>Check back soon — we're adding new cities and states every week.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        {{ $locations->onEachSide(0)->links('vendor.pagination.custom') }}
    </div>
</section>

<!-- Top Hiring States — eye-catcher before CTA -->
@if($topStates->count())
<section class="top-states-section" aria-labelledby="top-states-heading">
    <div class="container">
        <header class="top-states-head">
            <span class="eyebrow">Most Active Markets</span>
            <h2 id="top-states-heading">Top Hiring States Right Now</h2>
            <p>These U.S. states are leading the way with the most active job openings on Jobs in USA. Click any state to explore current verified roles nearby.</p>
        </header>

        <div class="top-states-grid">
            @foreach($topStates as $idx => $state)
                <a href="{{ route('jobs.index', ['location' => $state->name]) }}" class="top-state-card"
                   title="Browse jobs in {{ $state->name }}">
                    <div class="rank">#{{ $idx + 1 }}</div>
                    <div class="info">
                        <h3>{{ $state->name }}</h3>
                        <span class="count"><strong>{{ number_format($state->job_count) }}</strong> active jobs hiring now</span>
                    </div>
                    <i class="icon-feather-arrow-right arrow"></i>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
