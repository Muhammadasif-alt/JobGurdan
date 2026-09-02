@extends('user.layouts.master')
@php
    $jobPos = trim((string) (request('keywords') ?: request('position') ?: ''));
    $jobLoc = trim((string) (request('location') ?: ''));
    $posLabel = $jobPos !== '' ? ucwords($jobPos) : '';
    $locLabel = $jobLoc !== '' ? ucwords($jobLoc) : '';
    if ($posLabel && $locLabel) {
        $pageTitle = "{$posLabel} Jobs in {$locLabel} — Apply Free | JobGader";
        $pageDesc = "Browse verified {$posLabel} jobs in {$locLabel}. Apply free with one click on JobGader — new openings daily.";
    } elseif ($posLabel) {
        $pageTitle = "{$posLabel} JobGader — Hiring Now, Apply Free | JobGader";
        $pageDesc = "Search {$posLabel} jobs across all 50 U.S. states. Verified listings, free to apply on JobGader — new openings every day.";
    } elseif ($locLabel) {
        $pageTitle = "Jobs in {$locLabel} — Browse Verified Listings | JobGader";
        $pageDesc = "Find jobs in {$locLabel} across every industry. Verified listings, free to apply on JobGader — updated daily.";
    } else {
        $pageTitle = 'Browse JobGader — Find Verified Job Listings, Apply Free';
        $pageDesc = 'Search ' . number_format($heroStats['total_jobs'] ?? 0) . '+ verified jobs across all 50 U.S. states. Filter by state, area, or ZIP. Free to apply, new openings daily on JobGader.';
    }
@endphp
@section('title', $pageTitle)
@section('meta_description', $pageDesc)
@section('meta_keywords', 'browse jobs usa, job listings, search jobs america, apply jobs free, hiring near me, jobs by state, jobs by zip')
@section('content')

<style>
    /* === Jobs Hero (matches home — light gradient, dark text) === */
    .jobs-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 70px 0 50px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .jobs-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 12% 20%, rgba(27, 58, 107,.04) 0, transparent 40%),
            radial-gradient(circle at 88% 80%, rgba(27, 58, 107,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .jobs-hero .container { position: relative; z-index: 2; text-align: center; }
    .jobs-hero .eyebrow {
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
    .jobs-hero h1 {
        font-size: clamp(30px, 4.4vw, 52px);
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 0 18px;
        max-width: 920px;
        margin-left: auto;
        margin-right: auto;
    }
    .jobs-hero h1 .accent {
        background: linear-gradient(90deg, #1b3a6b, #4a90d9);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .jobs-hero p {
        color: #555;
        font-size: clamp(15px, 1.5vw, 17px);
        line-height: 1.65;
        max-width: 720px;
        margin: 0 auto 30px;
    }
    .jobs-hero .hero-stats {
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
    .jobs-hero .hero-stats .stat strong {
        display: block;
        font-size: 24px;
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.1;
        letter-spacing: -.5px;
    }
    .jobs-hero .hero-stats .stat span {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #777;
        font-weight: 600;
    }

    /* === Filter Bar (dark) === */
    .jobs-filter-section {
        padding: 28px 0 0;
        background: #fff;
    }
    .jobs-filter-bar {
        background: #1b3a6b;
        color: #fff;
        border-radius: 14px;
        padding: 22px 26px;
        box-shadow: 0 8px 24px rgba(15,23,42,.06);
    }
    .jobs-filter-bar .filter-head {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 16px;
        color: #fff;
    }
    .jobs-filter-bar form .row { row-gap: 10px; }
    .jobs-filter-bar .input-wrap { position: relative; }
    .jobs-filter-bar input,
    .jobs-filter-bar select.form-control,
    .jobs-filter-bar .select2-container--bootstrap .select2-selection--single {
        background: #fff !important;
        border: 1px solid #e5e5e7 !important;
        height: 48px !important;
        line-height: 46px !important;
        border-radius: 10px !important;
        font-size: 14.5px !important;
        color: #16305a !important;
        padding-left: 40px !important;
        box-shadow: none !important;
        width: 100% !important;
    }
    .jobs-filter-bar .select2-container--bootstrap .select2-selection--single .select2-selection__rendered {
        line-height: 46px !important;
        padding-left: 0 !important;
        color: #16305a !important;
    }
    .jobs-filter-bar .select2-container--bootstrap .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 8px !important;
    }
    .jobs-filter-bar .input-wrap > i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 17px;
        z-index: 5;
        pointer-events: none;
    }
    .jobs-filter-bar .btn-search {
        background: #fff !important;
        color: #1b3a6b !important;
        border: 1.5px solid #fff !important;
        font-weight: 700 !important;
        font-size: 14.5px !important;
        height: 48px !important;
        border-radius: 10px !important;
        width: 100% !important;
        cursor: pointer;
        transition: all .15s ease;
    }
    .jobs-filter-bar .btn-search:hover {
        background: #f5f5f7 !important;
        transform: translateY(-1px);
    }
    /* Make disabled selects look clearly inactive */
    .jobs-filter-bar select:disabled,
    .jobs-filter-bar .select2-container--disabled .select2-selection {
        opacity: .55;
        cursor: not-allowed;
    }

    /* === Results header === */
    .jobs-results-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 26px 0 18px;
        margin-top: 8px;
    }
    .jobs-results-header .count { color: #555; font-size: 14.5px; }
    .jobs-results-header .count strong { color: #1b3a6b; font-weight: 700; }
    .jobs-results-header .sort-by {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #555;
    }
    .jobs-results-header .sort-by select {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        padding: 8px 30px 8px 12px;
        font-size: 14px;
        font-weight: 600;
        color: #1b3a6b;
        cursor: pointer;
    }

    /* === Job Cards === */
    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    @media (max-width: 1199px) { .jobs-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 900px)  { .jobs-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px)  { .jobs-grid { grid-template-columns: 1fr; } }

    .job-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 22px 20px;
        text-decoration: none;
        color: inherit;
        height: 100%;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .job-card:hover {
        border-color: #1b3a6b;
        box-shadow: 0 14px 32px rgba(15,23,42,.08);
        transform: translateY(-3px);
    }
    .job-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .job-card-logo {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #f5f5f7;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .job-card-logo img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }
    .job-card-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 11px;
        border-radius: 6px;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .job-card-badge.green  { background: #047857; }
    .job-card-badge.yellow { background: #b45309; }
    .job-card-title {
        font-size: 16.5px;
        font-weight: 700;
        color: #1b3a6b;
        margin: 0 0 12px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 46px;
    }
    .job-card-meta {
        list-style: none;
        padding: 0;
        margin: 0 0 18px;
        flex-grow: 1;
    }
    .job-card-meta li {
        font-size: 13px;
        color: #555;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .job-card-meta li i {
        color: #1b3a6b;
        font-size: 14px;
    }
    .job-card-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #1b3a6b;
        color: #fff !important;
        padding: 11px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        margin-top: auto;
        transition: background .2s ease;
        border: 1.5px solid #1b3a6b;
    }
    .job-card:hover .job-card-button { background: #16305a; border-color: #16305a; }

    /* No results card */
    .no-jobs {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: #fafafa;
        border: 1px dashed #ddd;
        border-radius: 14px;
    }
    .no-jobs i { font-size: 50px; color: #c7c7cc; }
    .no-jobs h4 { font-size: 18px; color: #1b3a6b; margin: 14px 0 6px; font-weight: 700; }
    .no-jobs p { color: #777; margin: 0; }

    /* Pagination — dark */
    .jobs-pagination { padding: 30px 0 20px; display: flex; justify-content: center; }
    .jobs-pagination ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .jobs-pagination li a, .jobs-pagination li span {
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        color: #1b3a6b;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all .15s ease;
    }
    .jobs-pagination li a:hover { background: #f5f5f7; border-color: #1b3a6b; }
    .jobs-pagination li a.current-page,
    .jobs-pagination li.active a {
        background: #1b3a6b;
        color: #fff;
        border-color: #1b3a6b;
    }
    .jobs-pagination li a.disabled { opacity: .35; cursor: not-allowed; pointer-events: none; }

    /* === Related Section: Browse by Category & State === */
    .jobs-explore-section {
        padding: 70px 0 60px;
        background: #fafafa;
        border-top: 1px solid #ececec;
        margin-top: 30px;
    }
    .jobs-explore-section .section-head {
        text-align: center;
        max-width: 760px;
        margin: 0 auto 40px;
    }
    .jobs-explore-section .section-head .eyebrow {
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
    .jobs-explore-section h2 {
        font-size: clamp(24px, 3vw, 34px);
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin: 0 0 12px;
    }
    .jobs-explore-section .section-head p {
        color: #555;
        font-size: 15px;
        line-height: 1.6;
        margin: 0;
    }
    .explore-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 991px) { .explore-grid { grid-template-columns: 1fr; } }
    .explore-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 28px 26px;
    }
    .explore-card h3 {
        font-size: 17px;
        font-weight: 700;
        color: #1b3a6b;
        margin: 0 0 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .explore-card h3::before {
        content: "";
        width: 4px;
        height: 18px;
        background: #1b3a6b;
        border-radius: 2px;
    }
    .explore-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .explore-pills a {
        background: #fff;
        border: 1px solid #ececec;
        color: #16305a;
        padding: 9px 16px;
        border-radius: 999px;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .explore-pills a:hover {
        background: #1b3a6b;
        border-color: #1b3a6b;
        color: #fff;
        transform: translateY(-1px);
    }
    .explore-pills a .num {
        background: #f5f5f7;
        color: #555;
        font-size: 11.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        transition: all .15s ease;
    }
    .explore-pills a:hover .num {
        background: rgba(255,255,255,.18);
        color: #fff;
    }

    /* === Trust strip below === */
    .jobs-trust-strip {
        margin-top: 32px;
        padding: 24px 28px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
    @media (max-width: 768px) { .jobs-trust-strip { grid-template-columns: repeat(2, 1fr); } }
    .jobs-trust-strip .trust-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .jobs-trust-strip .trust-item .ico {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #1b3a6b;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .jobs-trust-strip .trust-item h5 {
        font-size: 14px;
        font-weight: 700;
        color: #1b3a6b;
        margin: 0 0 3px;
    }
    .jobs-trust-strip .trust-item p {
        font-size: 12.5px;
        color: #777;
        line-height: 1.5;
        margin: 0;
    }

    @media (max-width: 768px) {
        .jobs-hero { padding: 50px 0 36px; }
        .jobs-hero .hero-stats { gap: 18px; padding: 14px 22px; }
        .jobs-hero .hero-stats .stat strong { font-size: 20px; }
    }
</style>

<!-- Hero Section -->
<section class="jobs-hero">
    <div class="container">
        <span class="eyebrow" data-aos="fade-down" data-aos-duration="600">
            @if(request()->anyFilled(['position','location','area','postal_code']))
                Search Results
            @else
                Job Search
            @endif
        </span>
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Find Your Next <span class="accent">Career Move</span> in the USA</h1>
        <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">Browse {{ number_format($heroStats['total_jobs'] ?? 0) }}+ verified job listings across all 50 U.S. states. Filter by state, area or ZIP — apply free in one click and connect directly with verified employers.</p>
        <div class="hero-stats">
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
            <div class="stat">
                <strong>100%</strong>
                <span>Free to Apply</span>
            </div>
        </div>
    </div>
</section>

<!-- Filter Bar -->
<section class="jobs-filter-section">
    <div class="container">
        <div class="jobs-filter-bar">
            <h4 class="filter-head">
                @if (request()->anyFilled(['position', 'location', 'area', 'postal_code']))
                    Search Results
                @else
                    All Job Listings
                @endif
            </h4>

            <form method="GET" action="{{ route('jobs.index') }}" id="jobSearchForm">
                <div class="row">
                    <!-- Position Search -->
                    <div class="col-md-3 mb-2 mb-md-0">
                        <div class="input-wrap">
                            <input type="text" name="position" placeholder="Job position, title, keywords"
                                autocomplete="off" data-autocomplete="jobs"
                                value="{{ request('position') }}" class="form-control">
                            <i class="icon-material-outline-work"></i>
                        </div>
                    </div>

                    <!-- State / Location Dropdown -->
                    <div class="col-md-3 mb-2 mb-md-0">
                        <div class="input-wrap">
                            <select class="form-control select2" id="location" name="location"
                                data-placeholder="All States">
                                <option value="">All States</option>
                                @foreach ($uniqueLocations as $loc)
                                    <option value="{{ $loc->name }}"
                                        {{ request('location') == $loc->name ? 'selected' : '' }}>
                                        {{ $loc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="icon-material-outline-location-on"></i>
                        </div>
                    </div>

                    <!-- Area Dropdown -->
                    <div class="col-md-2 mb-2 mb-md-0">
                        <div class="input-wrap">
                            <select class="form-control select2" id="area" name="area"
                                data-placeholder="All Areas" {{ !request('location') ? 'disabled' : '' }}>
                                <option value="">All Areas</option>
                                @if (request('location'))
                                    @foreach ($uniqueAreas->where('name', request('location'))->pluck('area')->unique()->filter() as $area)
                                        <option value="{{ $area }}"
                                            {{ request('area') == $area ? 'selected' : '' }}>
                                            {{ $area }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <i class="icon-material-outline-map"></i>
                        </div>
                    </div>

                    <!-- Postal Code Dropdown -->
                    <div class="col-md-2 mb-2 mb-md-0">
                        <div class="input-wrap">
                            <select class="form-control select2" id="postal_code" name="postal_code"
                                data-placeholder="All ZIPs" {{ !request('area') ? 'disabled' : '' }}>
                                <option value="">All ZIPs</option>
                                @if (request('location') && request('area'))
                                    @foreach ($uniquePostalCodes->where('name', request('location'))->where('area', request('area'))->pluck('postal_code')->unique()->filter() as $postal)
                                        <option value="{{ $postal }}"
                                            {{ request('postal_code') == $postal ? 'selected' : '' }}>
                                            {{ $postal }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <i class="icon-material-outline-pin-drop"></i>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn-search">Search Jobs</button>
                    </div>
                </div>
            </form>

            {{-- ===== Quick skill / keyword filters ===== --}}
            @if(!empty($topSkills))
                <div class="jobs-skills-strip" aria-label="Filter jobs by skill">
                    <span class="lbl"><i class="bi bi-stars"></i> Popular skills:</span>
                    @foreach($topSkills as $skill)
                        <a href="{{ route('jobs.index', ['position' => $skill]) }}"
                           class="skill-pill {{ strcasecmp(request('position', ''), $skill) === 0 ? 'is-active' : '' }}">
                            {{ $skill }}
                        </a>
                    @endforeach
                </div>
                <style>
                    .jobs-skills-strip {
                        display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
                        margin-top: 14px; padding: 12px 4px 0;
                    }
                    .jobs-skills-strip .lbl {
                        font-size: 12.5px; font-weight: 700;
                        color: #1b3a6b; text-transform: uppercase; letter-spacing: 1px;
                        margin-right: 6px; display: inline-flex; align-items: center; gap: 5px;
                    }
                    .jobs-skills-strip .skill-pill {
                        display: inline-flex; align-items: center;
                        background: rgba(255,255,255,.08);
                        border: 1px solid rgba(255,255,255,.15);
                        color: #fff !important;
                        font-size: 12.5px; font-weight: 600;
                        padding: 5px 12px; border-radius: 999px;
                        text-decoration: none !important;
                        transition: all .15s ease;
                    }
                    .jobs-skills-strip .skill-pill:hover {
                        background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
                        border-color: #1b3a6b;
                        color: #fff !important;
                        transform: translateY(-1px);
                    }
                    .jobs-skills-strip .skill-pill.is-active {
                        background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
                        border-color: #1b3a6b;
                        color: #fff !important;
                    }
                </style>
            @endif
        </div>
    </div>
</section>

<!-- Results header + sort -->
<div class="container">
    <div class="jobs-results-header">
        <div class="count">
            @if ($jobs->total() > 0)
                Showing <strong>{{ $jobs->firstItem() }}–{{ $jobs->lastItem() }}</strong>
                of <strong>{{ number_format($jobs->total()) }}</strong> jobs
            @else
                <strong>No jobs found</strong>
            @endif
        </div>
        <div class="sort-by">
            <span>Sort By:</span>
            <select id="sortSelect">
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                    {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}"
                    {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'position_asc']) }}"
                    {{ request('sort') == 'position_asc' ? 'selected' : '' }}>Position (A-Z)</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'position_desc']) }}"
                    {{ request('sort') == 'position_desc' ? 'selected' : '' }}>Position (Z-A)</option>
            </select>
        </div>
    </div>

    <!-- Job Cards Grid -->
    <div class="jobs-grid">
        @forelse($jobs as $job)
            <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}"
                class="job-card">
                <div class="job-card-top">
                    <div class="job-card-logo">
                        <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}"
                            alt="{{ $job->advertiser->name ?? 'Company' }}" loading="lazy">
                    </div>
                    <span class="job-card-badge {{ ($job->employment_type ?? '') == 'Part Time' ? 'yellow' : 'green' }}">
                        <i class="icon-material-outline-business-center"></i>
                        {{ $job->employment_type ?? 'Full Time' }}
                    </span>
                </div>

                <h3 class="job-card-title">{{ $job->position }}</h3>

                <ul class="job-card-meta">
                    <li><i class="icon-feather-briefcase"></i>
                        {{ $job->category?->name ?? ($job->advertiser->name ?? 'N/A') }}</li>
                    @if ($job->location)
                        <li><i class="icon-material-outline-location-on"></i>
                            {{ $job->location->name }}{{ $job->location->area ? ', ' . $job->location->area : '' }}{{ $job->location->postal_code ? ' (' . $job->location->postal_code . ')' : '' }}
                        </li>
                    @endif
                    <li><i class="icon-material-outline-access-time"></i>
                        {{ $job->created_at->diffForHumans() }}</li>
                </ul>

                <span class="job-card-button">Browse Job <i class="icon-feather-arrow-right"></i></span>
            </a>
        @empty
            <div class="no-jobs">
                <i class="icon-feather-search"></i>
                <h4>No jobs match your search</h4>
                <p>Try changing your filters or removing some criteria to see more results.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($jobs->hasPages())
        @push('meta')
            @if($jobs->onFirstPage() === false)
                <link rel="prev" href="{{ $jobs->previousPageUrl() }}">
            @endif
            @if($jobs->hasMorePages())
                <link rel="next" href="{{ $jobs->nextPageUrl() }}">
            @endif
        @endpush
        {{ $jobs->onEachSide(2)->links() }}
    @endif
</div>

<!-- Related Section: Explore by Category & State + Trust Strip -->
<section class="jobs-explore-section" aria-labelledby="explore-heading">
    <div class="container">
        <header class="section-head">
            <span class="eyebrow">Explore More</span>
            <h2 id="explore-heading">Browse Jobs by Industry &amp; State</h2>
            <p>Narrow your search to the role and location that fits you best — explore the top hiring industries and most active U.S. job markets right now.</p>
        </header>

        <div class="explore-grid">
            <div class="explore-card">
                <h3>Top Industries</h3>
                <div class="explore-pills">
                    @foreach($topCategories as $cat)
                        <a href="{{ route('jobs.category', $cat->slug) }}" title="Browse {{ $cat->name }} jobs">
                            {{ $cat->name }}
                            <span class="num">{{ number_format($cat->jobs_count) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="explore-card">
                <h3>Top Hiring States</h3>
                <div class="explore-pills">
                    @foreach($topStates as $state)
                        <a href="{{ route('jobs.index', ['location' => $state->name]) }}" title="Browse jobs in {{ $state->name }}">
                            {{ $state->name }}
                            <span class="num">{{ number_format($state->job_count) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Trust strip -->
        <div class="jobs-trust-strip">
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-shield"></i></div>
                <div>
                    <h5>Verified Employers</h5>
                    <p>Every employer is reviewed for safety and legitimacy before listing.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-zap"></i></div>
                <div>
                    <h5>Apply in One Click</h5>
                    <p>Save your resume once and apply to multiple roles instantly.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-bell"></i></div>
                <div>
                    <h5>Smart Job Alerts</h5>
                    <p>Get notified the moment a matching role goes live in your area.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-users"></i></div>
                <div>
                    <h5>Trusted Nationwide</h5>
                    <p>Millions of job seekers across all 50 U.S. states use JobGader.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize select2
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap'
            });

            const uniqueAreas = @json($uniqueAreas->values());
            const uniquePostalCodes = @json($uniquePostalCodes->values());
            const $location = $('#location');
            const $area = $('#area');
            const $postal = $('#postal_code');

            function dedupe(arr) { return Array.from(new Set(arr)); }

            function populateAreas(selectedLocation, selectedArea = null) {
                $area.prop('disabled', !selectedLocation);
                let options = ['<option value="">All Areas</option>'];
                if (!selectedLocation) {
                    $area.html(options.join('')).trigger('change');
                    return;
                }
                const areas = uniqueAreas
                    .filter(a => a.name === selectedLocation)
                    .map(a => a.area)
                    .filter(Boolean);
                dedupe(areas).forEach(ar => {
                    const selected = (ar === selectedArea) ? ' selected' : '';
                    options.push(`<option value="${ar}"${selected}>${ar}</option>`);
                });
                $area.html(options.join('')).trigger('change');
            }

            function populatePostalCodes(selectedLocation, selectedArea, selectedPostal = null) {
                $postal.prop('disabled', !(selectedLocation && selectedArea));
                let options = ['<option value="">All ZIPs</option>'];
                if (!(selectedLocation && selectedArea)) {
                    $postal.html(options.join('')).trigger('change');
                    return;
                }
                const zips = uniquePostalCodes
                    .filter(p => p.name === selectedLocation && p.area === selectedArea)
                    .map(p => p.postal_code)
                    .filter(Boolean);
                dedupe(zips).forEach(z => {
                    const selected = (z === selectedPostal) ? ' selected' : '';
                    options.push(`<option value="${z}"${selected}>${z}</option>`);
                });
                $postal.html(options.join('')).trigger('change');
            }

            $location.on('change', function() {
                populateAreas($(this).val());
                populatePostalCodes('', '', null);
            });

            $area.on('change', function() {
                populatePostalCodes($location.val(), $(this).val());
            });

            // Initialize with existing values
            const initialLocation = "{{ request('location') }}";
            const initialArea     = "{{ request('area') }}";
            const initialPostal   = "{{ request('postal_code') }}";

            if (initialLocation) {
                populateAreas(initialLocation, initialArea);
                if (initialArea) {
                    populatePostalCodes(initialLocation, initialArea, initialPostal);
                }
            }

            // Sort change → reload
            $('#sortSelect').on('change', function() {
                window.location.href = this.value;
            });
        });
    </script>
@endpush
