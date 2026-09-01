@extends('user.layouts.master')
@section('title', $company->name . ' - Jobs')

@section('content')
<style>
    /* === Page-level brand overrides (light hero + dark accents) === */
    .utf-page-heading-area {
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%) !important;
        padding: 110px 0 90px !important;
        border-bottom: 1px solid #ececec !important;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    .utf-page-heading-area::before {
        content: '';
        position: absolute;
        right: -120px; top: -120px;
        width: 360px; height: 360px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.08), transparent 70%);
        pointer-events: none;
    }
    .utf-page-heading-area::after {
        content: '';
        position: absolute;
        left: -100px; bottom: -120px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,87,34,.07), transparent 70%);
        pointer-events: none;
    }
    /* Force breadcrumb ABOVE h1 (legacy theme floats it right — override) */
    .utf-page-heading-area #breadcrumbs {
        position: static !important;
        float: none !important;
        display: block !important;
        margin: 0 0 18px !important;
        order: -1 !important;
        text-align: center !important;
    }
    .utf-page-heading-area #breadcrumbs ul {
        background: transparent !important;
        padding: 0 !important;
        display: inline-flex !important;
        gap: 8px;
        list-style: none;
        margin: 0;
        justify-content: center;
    }
    .utf-page-heading-area #breadcrumbs ul li,
    .utf-page-heading-area #breadcrumbs ul li a {
        color: #555 !important;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: .5px;
        text-transform: uppercase;
    }
    .utf-page-heading-area #breadcrumbs ul li:not(:last-child)::after {
        content: '›';
        margin-left: 8px;
        color: #c7c7cc;
        font-weight: 400;
    }
    .utf-page-heading-area #breadcrumbs ul li a:hover { color: #ff8a00 !important; }
    .utf-page-heading-area #breadcrumbs ul li:last-child { color: #0a0a0a !important; }
    .utf-page-heading-area h1 {
        font-size: clamp(36px, 5vw, 62px) !important;
        font-weight: 800 !important;
        letter-spacing: -1.5px;
        color: #0a0a0a !important;
        margin: 0 auto 0 !important;
        max-width: 1100px;
        line-height: 1.1 !important;
        position: relative; z-index: 2;
    }
    .utf-page-heading-area h1 .accent {
        background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Company overview card */
    .utf-company-overview .company-desc h3 { color: #0a0a0a !important; font-weight: 700; }
    .utf-company-overview .company-desc i { color: #0a0a0a !important; }
    .utf-company-overview .company-desc a { color: #0a0a0a !important; font-weight: 600; }

    /* Sidebar widgets */
    .utf-sidebar-widget-item h3 {
        color: #0a0a0a !important;
        font-weight: 700 !important;
        border-bottom: 2px solid #0a0a0a !important;
        display: inline-block;
        padding-bottom: 8px;
    }
    .utf-sidebar-widget-item input[type="checkbox"] { accent-color: #0a0a0a !important; }

    /* Job listing cards */
    .utf-job-listing-item:hover { border-color: #0a0a0a !important; box-shadow: 0 16px 32px rgba(15,23,42,.08) !important; }
    .utf-job-listing-item h3, .utf-job-listing-item h3 a { color: #0a0a0a !important; }
    .utf-job-listing-item .utf-job-listing-footer ul li i { color: #0a0a0a !important; }
    .utf-job-listing-item h3 a:hover { color: #0a0a0a !important; text-decoration: underline; }

    /* Buttons */
    .button, .button.ripple-effect, .utf-listing-container-area .button {
        background: #0a0a0a !important;
        border: 1px solid #0a0a0a !important;
        color: #fff !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
    }
    .button:hover { background: #1a1a1a !important; color: #fff !important; }
</style>

<!-- Page Title -->
<div class="utf-page-heading-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav id="breadcrumbs" data-aos="fade-up" data-aos-duration="600">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('jobs.companies') }}">Companies</a></li>
                        <li>{{ $company->name }}</li>
                    </ul>
                </nav>
                <h1 data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">Jobs at <span class="accent">{{ $company->name }}</span></h1>
            </div>
        </div>
    </div>
</div>

<!-- Company Jobs Section -->
<div class="utf-listing-container-area padding-bottom-70">
    <div class="container">
        <div class="row">
            <!-- Search Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="utf-sidebar-container-aera">
                    <!-- Company Info -->
                    <div class="utf-sidebar-widget-item">
                        <div class="utf-company-overview margin-bottom-30">
                            <div class="company-logo-img">
                                <img src="{{ $company->logo ? asset('public/storage/' . $company->logo) : asset('public/user/images/companies.jpg') }}"
                                     alt="{{ $company->name }}" class="img-fluid">
                            </div>
                            <div class="company-desc">
                                <h3>{{ $company->name }}</h3>
                                @if($company->website)
                                <p><i class="icon-feather-globe"></i>
                                    <a href="{{ $company->website }}" target="_blank" rel="nofollow">
                                        {{ parse_url($company->website, PHP_URL_HOST) }}
                                    </a>
                                </p>
                                @endif
                                @if($company->location)
                                <p><i class="icon-material-outline-location-on"></i>
                                    {{ $company->location->name }}
                                    @if($company->location->area)
                                        , {{ $company->location->area }}
                                    @endif
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Location Filter -->
                    <div class="utf-sidebar-widget-item">
                        <h3>Filter by Location</h3>
                        <select class="selectpicker" data-live-search="true" title="All Locations">
                            @foreach($locations as $location)
                                <option>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Categories -->
                    <div class="utf-sidebar-widget-item">
                        <h3>Job Categories</h3>
                        <ul class="utf-job-listing">
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('jobs.category', $category->slug) }}">
                                    {{ $category->name }}
                                    <span class="counter">{{ $category->jobs_count }} </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Popular States (uses footer composer data — only states with active jobs) -->
                    @if(($footerStates ?? collect())->isNotEmpty())
                    <div class="utf-sidebar-widget-item">
                        <h3>Popular States</h3>
                        <ul class="utf-job-listing">
                            @foreach($footerStates as $state)
                            <li>
                                <a href="{{ route('jobs.search', ['location' => $state->name]) }}">
                                    Jobs in {{ $state->name }}
                                    <span class="counter">{{ number_format($state->job_count) }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Filter dark-mode fix only (Why Apply moved to full-width row below listings) -->
                    <style>
                        html.dark-mode .utf-sidebar-widget-item .bootstrap-select > .btn {
                            background: var(--site-input-bg) !important;
                            border-color: var(--site-input-bd) !important;
                            color: var(--site-text) !important;
                            padding: 12px 14px !important;
                        }
                        html.dark-mode .utf-sidebar-widget-item .bootstrap-select > .btn .filter-option-inner-inner,
                        html.dark-mode .utf-sidebar-widget-item .bootstrap-select > .btn .filter-option,
                        html.dark-mode .utf-sidebar-widget-item .bootstrap-select > .btn .filter-option-inner {
                            color: var(--site-text) !important;
                        }
                        html.dark-mode .utf-sidebar-widget-item .bootstrap-select.show > .btn,
                        html.dark-mode .utf-sidebar-widget-item .bootstrap-select > .btn:focus {
                            border-color: #ff8a00 !important;
                        }
                    </style>
                </div>
            </div>

            <!-- Job Listings -->
            <div class="col-lg-8 col-md-12">
                <style>
                    /* Match /jobs listing card pattern */
                    .jobs-grid {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 20px;
                        margin-top: 20px;
                    }
                    @media (max-width: 991px) { .jobs-grid { grid-template-columns: repeat(2, 1fr); } }
                    @media (max-width: 575px) { .jobs-grid { grid-template-columns: 1fr; } }

                    .job-card {
                        display: flex; flex-direction: column;
                        background: #fff; border: 1px solid #ececec;
                        border-radius: 14px; padding: 22px 20px;
                        text-decoration: none; color: inherit; height: 100%;
                        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
                    }
                    .job-card:hover {
                        border-color: #0a0a0a;
                        box-shadow: 0 14px 32px rgba(15,23,42,.08);
                        transform: translateY(-3px);
                    }
                    .job-card-top {
                        display: flex; justify-content: space-between; align-items: flex-start;
                        margin-bottom: 14px;
                    }
                    .job-card-logo {
                        width: 56px; height: 56px; border-radius: 12px;
                        background: #f5f5f7;
                        display: flex; align-items: center; justify-content: center;
                        overflow: hidden; flex-shrink: 0;
                    }
                    .job-card-logo img { max-width: 80%; max-height: 80%; object-fit: contain; }
                    .job-card-badge {
                        font-size: 11px; font-weight: 700;
                        padding: 5px 11px; border-radius: 6px;
                        color: #fff;
                        display: inline-flex; align-items: center; gap: 4px;
                        white-space: nowrap; text-transform: uppercase; letter-spacing: .5px;
                    }
                    .job-card-badge.green  { background: #047857; }
                    .job-card-badge.yellow { background: #b45309; }
                    .job-card-title {
                        font-size: 16.5px; font-weight: 700; color: #0a0a0a;
                        margin: 0 0 12px; line-height: 1.35;
                        display: -webkit-box; -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical; overflow: hidden; min-height: 46px;
                    }
                    .job-card-meta { list-style: none; padding: 0; margin: 0 0 18px; flex-grow: 1; }
                    .job-card-meta li {
                        font-size: 13px; color: #555; margin-bottom: 6px;
                        display: flex; align-items: center; gap: 8px;
                    }
                    .job-card-meta li i { color: #0a0a0a; font-size: 14px; }
                    .job-card-button {
                        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
                        background: #0a0a0a; color: #fff !important;
                        padding: 11px 14px; border-radius: 10px;
                        font-size: 13.5px; font-weight: 600;
                        margin-top: auto;
                        transition: background .2s ease;
                        border: 1.5px solid #0a0a0a;
                    }
                    .job-card:hover .job-card-button { background: #1a1a1a; border-color: #1a1a1a; }

                    .no-jobs {
                        grid-column: 1 / -1; text-align: center;
                        padding: 60px 20px; background: #fafafa;
                        border: 1px dashed #ddd; border-radius: 14px;
                    }
                    .no-jobs i { font-size: 50px; color: #c7c7cc; }
                    .no-jobs h4 { font-size: 18px; color: #0a0a0a; margin: 14px 0 6px; font-weight: 700; }
                    .no-jobs p { color: #777; margin: 0; }
                </style>

                <div class="jobs-grid">
                    @forelse($jobs as $job)
                        <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? '')) ) }}" class="job-card">
                            <div class="job-card-top">
                                <div class="job-card-logo">
                                    <img src="{{ $company->logo_url ?? asset('public/user/images/jobimages.png') }}"
                                         alt="{{ $company->name }}" loading="lazy">
                                </div>
                                <span class="job-card-badge {{ ($job->employment_type ?? '') == 'Part Time' ? 'yellow' : 'green' }}">
                                    <i class="icon-material-outline-business-center"></i>
                                    {{ $job->employment_type ?? 'Full Time' }}
                                </span>
                            </div>

                            <h3 class="job-card-title">{{ $job->position }}</h3>

                            <ul class="job-card-meta">
                                @if($job->category)
                                    <li><i class="icon-feather-briefcase"></i> {{ $job->category->name }}</li>
                                @endif
                                @if($job->location)
                                    <li><i class="icon-material-outline-location-on"></i>
                                        {{ $job->location->name }}{{ $job->location->area ? ', ' . $job->location->area : '' }}
                                    </li>
                                @endif
                                <li><i class="icon-material-outline-access-time"></i>
                                    {{ $job->created_at->diffForHumans() }}
                                </li>
                            </ul>

                            <span class="job-card-button">Browse Job <i class="icon-feather-arrow-right"></i></span>
                        </a>
                    @empty
                        <div class="no-jobs">
                            <i class="icon-feather-search"></i>
                            <h4>No open jobs right now</h4>
                            <p>{{ $company->name }} doesn't have any active openings at the moment. Check back soon.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                {{ $jobs->onEachSide(0)->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     Why Apply Here — 4-card horizontal row
     ============================================================ --}}
<section class="cj-why-section">
    <div class="container">
        <header class="cj-section-head">
            <span class="eyebrow">Why Apply Through Us</span>
            <h2>Built for U.S. <span class="accent">Job Seekers</span></h2>
            <p>Every role on Jobs in USA goes through our verification process before it reaches you.</p>
        </header>
        <div class="cj-why-grid">
            <div class="cj-why-card">
                <div class="cj-why-ico"><i class="icon-feather-check-circle"></i></div>
                <h3>Verified Employers</h3>
                <p>Every company is manually screened before any listing goes live on the platform.</p>
            </div>
            <div class="cj-why-card">
                <div class="cj-why-ico"><i class="icon-feather-zap"></i></div>
                <h3>One-Click Apply</h3>
                <p>Save your profile once, then apply to dozens of relevant roles in seconds.</p>
            </div>
            <div class="cj-why-card">
                <div class="cj-why-ico"><i class="icon-feather-lock"></i></div>
                <h3>Safe &amp; Secure</h3>
                <p>Your personal data is never sold or shared with third parties — guaranteed.</p>
            </div>
            <div class="cj-why-card">
                <div class="cj-why-ico"><i class="icon-feather-clock"></i></div>
                <h3>Daily Fresh Listings</h3>
                <p>Our platform refreshes every 24 hours so you see new opportunities first.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     Why this company — SEO row with image + text + floating badges
     ============================================================ --}}
<section class="cj-seo-section">
    <div class="container">
        <div class="cj-seo-grid">
            <div class="cj-seo-content">
                <span class="eyebrow">Hiring Now</span>
                <h2>Explore opportunities at <span class="accent">{{ $company->name }}</span></h2>
                <p class="cj-seo-lead">
                    {{ $company->name }} is one of the verified U.S. employers actively hiring on Jobs in USA.
                    Browse open roles across {{ $jobs->total() ?? 'multiple' }} positions — apply free, get matched faster, and connect directly with the hiring team.
                </p>
                <p>
                    Whether you're looking for a full-time role, a seasonal opportunity, or a long-term career move,
                    {{ $company->name }} regularly posts fresh positions. Our verified-employer badge means every
                    listing comes from a real, screened company — no spam, no recycled posts, no fake recruiters.
                </p>
                <ul class="cj-seo-list">
                    <li><i class="icon-feather-check-circle"></i> Direct application — no third-party redirects</li>
                    <li><i class="icon-feather-check-circle"></i> 100% free to apply for job seekers</li>
                    <li><i class="icon-feather-check-circle"></i> Real-time alerts when new roles post</li>
                    <li><i class="icon-feather-check-circle"></i> Mobile-friendly application flow</li>
                </ul>
            </div>
            <div class="cj-seo-visual">
                <img src="{{ asset('public/user/images/single-company.webp') }}"
                     onerror="this.onerror=null;this.src='{{ asset('public/user/images/single-company.jpg') }}'"
                     alt="Apply to {{ $company->name }} jobs" loading="lazy" decoding="async">
                <div class="cj-float-badge tl">
                    <div class="ico green"><i class="icon-feather-check-circle"></i></div>
                    <div class="text">
                        <strong>Verified</strong>
                        <span>Trusted employer</span>
                    </div>
                </div>
                <div class="cj-float-badge br">
                    <div class="ico"><i class="icon-feather-zap"></i></div>
                    <div class="text">
                        <strong>{{ $jobs->total() ?? 0 }} Open Roles</strong>
                        <span>Updated daily</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     Stats / trust bar
     ============================================================ --}}
<section class="cj-stats-section">
    <div class="container">
        <div class="cj-stats-grid">
            <div class="cj-stat"><strong>230K+</strong><span>Verified Jobs</span></div>
            <div class="cj-stat"><strong>10K+</strong><span>Hiring Employers</span></div>
            <div class="cj-stat"><strong>50</strong><span>U.S. States</span></div>
            <div class="cj-stat"><strong>100%</strong><span>Free for Seekers</span></div>
        </div>
    </div>
</section>

<style>
/* ===== Section base styles ===== */
.cj-why-section, .cj-seo-section, .cj-stats-section {
    padding: 70px 0;
    background: #fff;
    position: relative;
}
.cj-why-section { background: #fafafa; border-top: 1px solid #ececec; }
.cj-seo-section { background: #fff; }
.cj-stats-section {
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    padding: 50px 0;
}

.cj-section-head { text-align: center; max-width: 720px; margin: 0 auto 44px; }
.cj-section-head .eyebrow {
    display: inline-block;
    background: rgba(255,138,0,.10);
    color: #ff8a00;
    font-size: 11px;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 999px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 14px;
}
.cj-section-head h2 {
    font-size: clamp(26px, 3.2vw, 38px);
    font-weight: 800;
    color: #0a0a0a;
    line-height: 1.15;
    letter-spacing: -.6px;
    margin: 0 0 12px;
}
.cj-section-head h2 .accent {
    background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
    -webkit-background-clip: text; background-clip: text;
    -webkit-text-fill-color: transparent;
}
.cj-section-head p { color: #555; font-size: 16px; line-height: 1.65; margin: 0; }

/* ===== Why Apply — 4 horizontal cards ===== */
.cj-why-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}
@media (max-width: 991px) { .cj-why-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px) { .cj-why-grid { grid-template-columns: 1fr; } }
.cj-why-card {
    background: #fff;
    border: 1px solid #ececec;
    border-radius: 16px;
    padding: 28px 24px;
    text-align: left;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    position: relative;
    overflow: hidden;
}
.cj-why-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #ff5722, #ff8a00, #ffab40);
    opacity: 0; transition: opacity .25s ease;
}
.cj-why-card:hover {
    transform: translateY(-4px);
    border-color: #ff8a00;
    box-shadow: 0 20px 40px rgba(15,23,42,.10);
}
.cj-why-card:hover::before { opacity: 1; }
.cj-why-ico {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #ff8a00, #ff5722);
    color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 16px;
}
.cj-why-card h3 {
    font-size: 17px;
    font-weight: 800;
    color: #0a0a0a;
    margin: 0 0 8px;
    border: none; padding: 0; display: block;
}
.cj-why-card p { color: #555; font-size: 13.5px; line-height: 1.55; margin: 0; }

/* ===== SEO Section (image + text + floating badges) ===== */
.cj-seo-grid {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 60px;
    align-items: center;
}
@media (max-width: 991px) {
    .cj-seo-grid { grid-template-columns: 1fr; gap: 40px; }
}
.cj-seo-content .eyebrow {
    display: inline-block;
    background: rgba(16,185,129,.10);
    color: #047857;
    font-size: 11px;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 999px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 14px;
}
.cj-seo-content h2 {
    font-size: clamp(26px, 3vw, 36px);
    font-weight: 800;
    color: #0a0a0a;
    line-height: 1.15;
    letter-spacing: -.6px;
    margin: 0 0 16px;
}
.cj-seo-content h2 .accent {
    background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
    -webkit-background-clip: text; background-clip: text;
    -webkit-text-fill-color: transparent;
}
.cj-seo-content .cj-seo-lead { color: #1a1a1a; font-size: 16px; line-height: 1.65; margin: 0 0 14px; font-weight: 500; }
.cj-seo-content p { color: #555; font-size: 15px; line-height: 1.7; margin: 0 0 18px; }
.cj-seo-list { list-style: none; padding: 0; margin: 18px 0 0; }
.cj-seo-list li {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0; color: #1a1a1a; font-size: 14.5px; font-weight: 500;
}
.cj-seo-list li i { color: #10b981; font-size: 18px; }
.cj-seo-visual { position: relative; }
.cj-seo-visual img {
    width: 100%;
    height: 460px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 30px 60px rgba(15,23,42,.15);
}
@media (max-width: 991px) { .cj-seo-visual img { height: 360px; } }
@media (max-width: 575px) { .cj-seo-visual img { height: 280px; } }
.cj-float-badge {
    position: absolute;
    background: #fff;
    border-radius: 14px;
    padding: 14px 18px;
    box-shadow: 0 14px 32px rgba(15,23,42,.15);
    display: flex; align-items: center; gap: 12px;
    min-width: 180px;
}
.cj-float-badge.tl { top: 20px; left: -20px; }
.cj-float-badge.br { bottom: 20px; right: -20px; }
.cj-float-badge .ico {
    width: 36px; height: 36px; border-radius: 10px;
    background: #0a0a0a; color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.cj-float-badge .ico.green { background: #10b981; }
.cj-float-badge .text strong { display: block; color: #0a0a0a; font-size: 14.5px; font-weight: 800; }
.cj-float-badge .text span { color: #777; font-size: 12px; }
@media (max-width: 575px) {
    .cj-float-badge.tl { top: 10px; left: 10px; }
    .cj-float-badge.br { bottom: 10px; right: 10px; }
}

/* ===== Stats bar ===== */
.cj-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}
@media (max-width: 575px) { .cj-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; } }
.cj-stat { text-align: center; }
.cj-stat strong {
    display: block;
    font-size: clamp(28px, 3vw, 38px);
    font-weight: 800;
    color: #fff;
    letter-spacing: -.5px;
    margin-bottom: 6px;
    background: linear-gradient(135deg, #ff8a00, #ff5722);
    -webkit-background-clip: text; background-clip: text;
    -webkit-text-fill-color: transparent;
}
.cj-stat span {
    color: #cbd5e1;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 600;
}

/* ===== Dark mode ===== */
html.dark-mode .cj-why-section { background: var(--site-bg) !important; border-top-color: var(--site-card-bd) !important; }
html.dark-mode .cj-seo-section { background: var(--site-bg) !important; }
html.dark-mode .cj-section-head h2,
html.dark-mode .cj-seo-content h2 { color: #fff !important; }
html.dark-mode .cj-section-head p,
html.dark-mode .cj-seo-content p { color: #cbd5e1 !important; }
html.dark-mode .cj-seo-content .cj-seo-lead { color: #fff !important; }
html.dark-mode .cj-why-card {
    background: var(--site-card-bg) !important;
    border-color: var(--site-card-bd) !important;
}
html.dark-mode .cj-why-card h3 { color: #fff !important; }
html.dark-mode .cj-why-card p { color: #cbd5e1 !important; }
html.dark-mode .cj-seo-list li { color: #e5e7eb !important; }
html.dark-mode .cj-float-badge {
    background: var(--site-card-bg) !important;
    border: 1px solid var(--site-card-bd);
}
html.dark-mode .cj-float-badge .text strong { color: #fff !important; }
html.dark-mode .cj-float-badge .text span { color: var(--site-muted) !important; }
</style>
@endsection

@push('scripts')
<!-- Initialize Select Picker -->
<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
    });
</script>
@endpush
