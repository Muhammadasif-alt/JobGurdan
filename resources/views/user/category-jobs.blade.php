@extends('user.layouts.master')
@section('title', 'Jobs in '.$category->name.' — Browse Verified ' . $category->name . ' Roles in the USA')
@section('meta_description', 'Find verified ' . $category->name . ' jobs across the United States. Browse ' . $jobs->total() . ' active openings, filter by location and job type, and apply with one click on JobGader.')
@section('canonical', route('jobs.category', $category->slug))

@section('content')

<style>
    /* === Hero — light brand gradient with dark accents === */
    .cat-hero {
        position: relative;
        padding: 80px 0 70px;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        overflow: hidden;
        color: #1b3a6b;
        border-bottom: 1px solid #ececec;
    }
    .cat-hero::before {
        content: '';
        position: absolute;
        right: -120px; top: -120px;
        width: 360px; height: 360px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.08), transparent 70%);
        pointer-events: none;
    }
    .cat-hero::after {
        content: '';
        position: absolute;
        left: -100px; bottom: -120px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(47, 127, 201,.07), transparent 70%);
        pointer-events: none;
    }
    .cat-hero-inner {
        position: relative;
        z-index: 2;
        max-width: 900px;
    }
    .cat-hero-bc {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #e5e5e7;
        padding: 7px 16px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 600;
        margin-bottom: 18px;
        list-style: none;
        color: #555;
    }
    .cat-hero-bc li { display: inline-flex; align-items: center; gap: 8px; }
    .cat-hero-bc li + li::before { content: '›'; color: #b5b5b5; }
    .cat-hero-bc a { color: #1b3a6b; text-decoration: none; font-weight: 600; }
    .cat-hero-bc a:hover { text-decoration: underline; }
    .cat-hero-bc li:last-child { color: #1b3a6b; font-weight: 700; }

    .cat-hero-tag {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #1b3a6b;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        margin-bottom: 18px;
    }
    .cat-hero h1 {
        font-size: clamp(28px, 3.4vw, 46px);
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 18px;
        letter-spacing: -.6px;
        color: #1b3a6b;
    }
    .cat-hero h1 span {
        background: linear-gradient(90deg, #1b3a6b, #4a90d9);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .cat-hero p.lead {
        color: #555;
        font-size: 16px;
        max-width: 720px;
        line-height: 1.7;
        margin-bottom: 28px;
    }
    .cat-hero-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, max-content));
        gap: 32px;
        flex-wrap: wrap;
    }
    @media (max-width: 575px) { .cat-hero-stats { grid-template-columns: repeat(2, 1fr); gap: 22px 28px; } }
    .cat-hero-stat { display: flex; flex-direction: column; gap: 4px; }
    .cat-hero-stat .num {
        font-size: 28px;
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1;
        letter-spacing: -.4px;
    }
    .cat-hero-stat .label {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
    }

    /* Job grid */
    .cat-results-section { padding: 60px 0; background: #fafafa; }
    .cat-results-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 14px;
    }
    .cat-results-head .count { font-size: 14px; color: #666; }
    .cat-results-head .count strong { color: #16305a; }

    .cat-job-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    @media (max-width: 1199px) { .cat-job-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 700px)  { .cat-job-grid { grid-template-columns: 1fr; } }

    .cat-job-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 10px;
        padding: 22px 20px 20px;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: all .25s ease;
        position: relative;
    }
    .cat-job-card:hover {
        border-color: #1b3a6b;
        box-shadow: 0 18px 36px rgba(15,23,42,.08);
        transform: translateY(-3px);
        color: inherit;
    }
    .cat-job-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }
    .cat-job-logo {
        width: 50px; height: 50px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .cat-job-logo img { max-width: 80%; max-height: 80%; object-fit: contain; }
    .cat-job-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #1b3a6b;
        white-space: nowrap;
    }
    .cat-job-badge.green { background: #1b3a6b; color: #fff; }
    .cat-job-badge.blue { background: #f3f4f6; color: #1b3a6b; }
    .cat-job-card h3 {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
        color: #16305a;
        margin-bottom: 6px;
    }
    .cat-job-company {
        font-size: 13px;
        color: #666;
        margin-bottom: 12px;
    }
    .cat-job-meta {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 14px;
        padding-bottom: 14px;
        border-bottom: 1px dashed #ececec;
    }
    .cat-job-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #666;
    }
    .cat-job-meta span i { color: #1b3a6b; font-size: 13px; }
    .cat-job-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .cat-job-time { font-size: 11px; color: #999; }
    .cat-job-apply {
        font-size: 12px;
        font-weight: 700;
        color: #1b3a6b;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Sidebar */
    .cat-sidebar { background: #fff; border-radius: 10px; padding: 22px 20px; border: 1px solid #ececec; margin-bottom: 22px; }
    .cat-sidebar h3 { font-size: 15px; font-weight: 700; color: #1b3a6b; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #1b3a6b; display: inline-block; }
    .cat-sidebar .form-control { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; }
    .cat-sidebar ul { list-style: none; padding: 0; margin: 0; }
    .cat-sidebar ul li { margin-bottom: 8px; }
    .cat-sidebar ul li a {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 12px;
        font-size: 13px;
        color: #444;
        border-radius: 5px;
        text-decoration: none;
        transition: all .2s ease;
    }
    .cat-sidebar ul li a:hover { background: #f3f4f6; color: #1b3a6b; }
    .cat-sidebar ul li a .counter { color: #999; font-size: 11px; }
    .cat-sidebar ul li a:hover .counter { color: #1b3a6b; }

    /* Job type checkboxes */
    .cat-jobtype-list { display: flex; flex-direction: column; gap: 4px; }
    .cat-jobtype-item {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        color: #444 !important;
        border-radius: 5px;
        cursor: pointer;
        transition: background .15s ease;
        margin: 0 !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
        white-space: nowrap;
    }
    .cat-jobtype-item:hover { background: #f3f4f6; color: #1b3a6b !important; }
    .cat-jobtype-item input[type="checkbox"] {
        appearance: auto !important;
        -webkit-appearance: checkbox !important;
        width: 16px !important;
        height: 16px !important;
        margin: 0 !important;
        padding: 0 !important;
        flex-shrink: 0;
        accent-color: #1b3a6b;
        cursor: pointer;
    }

    /* Trust section */
    .cat-trust-section {
        padding: 70px 0;
        background: #fff;
        border-top: 1px solid #ececec;
    }
    .cat-trust-head { text-align: center; margin-bottom: 40px; }
    .cat-trust-head h2 { font-size: 30px; font-weight: 700; color: #16305a; margin-bottom: 10px; }
    .cat-trust-head p { font-size: 15px; color: #5a5a5a; max-width: 640px; margin: 0 auto; }
    .cat-trust-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
    @media (max-width: 991px) { .cat-trust-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .cat-trust-grid { grid-template-columns: 1fr; } }
    .cat-trust-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 10px;
        padding: 28px 22px;
        text-align: center;
        transition: all .25s ease;
    }
    .cat-trust-card:hover {
        border-color: #1b3a6b;
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(15,23,42,.08);
    }
    .cat-trust-card .ico {
        width: 56px; height: 56px;
        border-radius: 12px;
        background: #1b3a6b;
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 22px;
        margin-bottom: 16px;
    }
    .cat-trust-card h4 { font-size: 16px; font-weight: 700; margin-bottom: 8px; color: #16305a; }
    .cat-trust-card p { font-size: 13px; color: #666; line-height: 1.7; margin: 0; }

    @media (max-width: 768px) {
        .cat-hero { padding: 50px 0 44px; }
        .cat-hero h1 { font-size: 30px; }
        .cat-hero-stats { gap: 18px; }
        .cat-hero-stat .num { font-size: 22px; }
    }
</style>

{{-- Hero --}}
<section class="cat-hero">
    <div class="container">
        <div class="cat-hero-inner">
            <ul class="cat-hero-bc">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('jobs.categories') }}">Categories</a></li>
                <li>{{ $category->name }}</li>
            </ul>
            <span class="cat-hero-tag">Verified Listings</span>
            <h1>Find <span>{{ $category->name }}</span> jobs across the USA</h1>
            <p class="lead">Browse {{ number_format($jobs->total()) }} active {{ strtolower($category->name) }} openings from verified employers nationwide. Filter by location, job type, and salary — and apply with one click. Free for job seekers, always.</p>
            <div class="cat-hero-stats">
                <div class="cat-hero-stat">
                    <div class="num">{{ number_format($jobs->total()) }}</div>
                    <div class="label">Active Jobs</div>
                </div>
                <div class="cat-hero-stat">
                    <div class="num">50</div>
                    <div class="label">U.S. States</div>
                </div>
                <div class="cat-hero-stat">
                    <div class="num">100%</div>
                    <div class="label">Verified Employers</div>
                </div>
                <div class="cat-hero-stat">
                    <div class="num">Free</div>
                    <div class="label">For Job Seekers</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Results --}}
<section class="cat-results-section">
    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-4 col-md-12">
                @php
                    $activeLocation = request('location', '');
                    $activeJobTypes = (array) request('job_type', []);
                @endphp
                <form method="GET" action="{{ route('jobs.category', $category->slug) }}" id="catFiltersForm">
                    <div class="cat-sidebar">
                        <h3>Filter By Location</h3>
                        <select class="form-control" name="location" id="locationFilter" onchange="document.getElementById('catFiltersForm').submit();">
                            <option value="">All Locations</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->name }}" @selected($activeLocation === $location->name)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="cat-sidebar">
                        <h3>Job Type</h3>
                        <div class="cat-jobtype-list">
                            <label class="cat-jobtype-item">
                                <input type="checkbox" name="job_type[]" value="full_time" @checked(in_array('full_time', $activeJobTypes, true)) onchange="document.getElementById('catFiltersForm').submit();">
                                <span>Full Time</span>
                            </label>
                            <label class="cat-jobtype-item">
                                <input type="checkbox" name="job_type[]" value="part_time" @checked(in_array('part_time', $activeJobTypes, true)) onchange="document.getElementById('catFiltersForm').submit();">
                                <span>Part Time</span>
                            </label>
                            <label class="cat-jobtype-item">
                                <input type="checkbox" name="job_type[]" value="freelance" @checked(in_array('freelance', $activeJobTypes, true)) onchange="document.getElementById('catFiltersForm').submit();">
                                <span>Freelance</span>
                            </label>
                        </div>
                        @if($activeLocation !== '' || ! empty($activeJobTypes))
                            <div style="margin-top:14px;">
                                <a href="{{ route('jobs.category', $category->slug) }}" style="font-size:13px;color:#2f7fc9;text-decoration:underline;">Clear filters</a>
                            </div>
                        @endif
                    </div>
                </form>

                <div class="cat-sidebar">
                    <h3>Other Categories</h3>
                    <ul>
                        @foreach($categories as $cat)
                            @if($cat->id != $category->id)
                                <li>
                                    <a href="{{ route('jobs.category', $cat->slug) }}">
                                        <span><i class="{{ $cat->icon ?? 'icon-line-awesome-briefcase' }}"></i> {{ $cat->name }}</span>
                                        <span class="counter">{{ $cat->jobs_count }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Job grid --}}
            <div class="col-lg-8 col-md-12">
                <div class="cat-results-head">
                    <div class="count">Showing <strong>{{ $jobs->firstItem() }}–{{ $jobs->lastItem() }}</strong> of <strong>{{ number_format($jobs->total()) }}</strong> jobs</div>
                </div>

                @if($jobs->isEmpty())
                    <div class="cat-sidebar" style="text-align:center;padding:40px 20px;">
                        <p style="margin:0;color:#666;">No jobs found in this category yet. Please check back soon — we add new listings every day.</p>
                    </div>
                @else
                    <div class="cat-job-grid">
                        @foreach($jobs as $job)
                            <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? '')) ) }}" class="cat-job-card">
                                <div class="cat-job-top">
                                    <div class="cat-job-logo">
                                        <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}" alt="{{ $job->advertiser->name ?? 'Company' }}" loading="lazy" decoding="async">
                                    </div>
                                    @if($job->job_type === 'full_time')
                                        <span class="cat-job-badge green">Full Time</span>
                                    @elseif($job->job_type === 'part_time')
                                        <span class="cat-job-badge blue">Part Time</span>
                                    @else
                                        <span class="cat-job-badge">{{ ucfirst(str_replace('_',' ',$job->job_type ?? 'Open')) }}</span>
                                    @endif
                                </div>
                                <h3>{{ \Illuminate\Support\Str::limit($job->position, 60) }}</h3>
                                <div class="cat-job-company">{{ $job->advertiser->name ?? 'Company' }}</div>
                                <div class="cat-job-meta">
                                    <span><i class="icon-material-outline-location-on"></i> {{ $job->location->name ?? 'Location not specified' }}</span>
                                    <span><i class="icon-material-outline-account-balance-wallet"></i>
                                        @if($job->salary_minimum && $job->salary_maximum)
                                            {{ $job->salary_currency }} {{ number_format($job->salary_minimum) }}–{{ number_format($job->salary_maximum) }}
                                        @else
                                            Salary not specified
                                        @endif
                                    </span>
                                </div>
                                <div class="cat-job-bottom">
                                    <span class="cat-job-time"><i class="icon-material-outline-access-time"></i> {{ $job->created_at->diffForHumans() }}</span>
                                    <span class="cat-job-apply">Apply <i class="icon-feather-arrow-right"></i></span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    {{ $jobs->onEachSide(2)->links() }}
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Why Apply Through JobGader --}}
<section class="cat-trust-section">
    <div class="container">
        <div class="cat-trust-head">
            <h2>Why apply for {{ $category->name }} jobs through JobGader?</h2>
            <p>We've built the most trusted way for American job seekers to find verified roles — with employer transparency, smart matching, and zero spam.</p>
        </div>
        <div class="cat-trust-grid">
            <div class="cat-trust-card">
                <div class="ico"><i class="icon-feather-shield"></i></div>
                <h4>Verified Employers</h4>
                <p>Every employer on our platform is reviewed by our team — no scams, no ghost jobs, no fake listings.</p>
            </div>
            <div class="cat-trust-card">
                <div class="ico"><i class="icon-feather-zap"></i></div>
                <h4>Apply in One Click</h4>
                <p>Save your resume once and apply to multiple {{ strtolower($category->name) }} roles instantly — no repeat forms.</p>
            </div>
            <div class="cat-trust-card">
                <div class="ico"><i class="icon-feather-bell"></i></div>
                <h4>Smart Job Alerts</h4>
                <p>Get notified the moment a new {{ strtolower($category->name) }} role matching your preferences goes live.</p>
            </div>
            <div class="cat-trust-card">
                <div class="ico"><i class="icon-feather-users"></i></div>
                <h4>Trusted by Millions</h4>
                <p>Over 10 million American job seekers use JobGader to find their next opportunity. Join free today.</p>
            </div>
        </div>
    </div>
</section>

@endsection