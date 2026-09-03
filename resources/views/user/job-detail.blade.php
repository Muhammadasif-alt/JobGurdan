@extends('user.layouts.master')
@section('title', $job->position . ' at ' . ($job->advertiser->name ?? 'Top Employer') . ' — ' . ($job->location->name ?? 'USA') . ' | JobGader')
@section('meta_description', $job->meta_description ?: ('Apply for ' . $job->position . ' at ' . ($job->advertiser->name ?? 'top employer') . ' in ' . ($job->location->name ?? 'USA') . '. ' . \Illuminate\Support\Str::limit(strip_tags($job->description ?? ''), 130)))
@section('og_title', $job->position . ' at ' . ($job->advertiser->name ?? 'Top Employer'))
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($job->description ?? 'Apply now on JobGader.'), 160))
@section('canonical', route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))))

@push('meta')
    {{-- SEO keywords meta tag — auto-extracted from job description --}}
    @if($job->seo_keywords)
        <meta name="keywords" content="{{ $job->seo_keywords }}">
    @endif
@endpush

@section('content')

@php
    $applyUrl = $job->application_url ?: '#';
    $hasApply = !empty($job->application_url);
    $jobTypeLabel = $job->employment_type ? ucfirst(str_replace('_',' ',$job->employment_type)) : ($job->job_type ? ucfirst(str_replace('_',' ',$job->job_type)) : 'Open');
    $salaryRange = null;
    if (($job->salary_minimum ?? 0) > 0 || ($job->salary_maximum ?? 0) > 0) {
        $cur = $job->salary_currency ?? 'USD';
        $salaryRange = $cur . ' ' . number_format($job->salary_minimum ?? 0) . ' – ' . number_format($job->salary_maximum ?? 0);
        if ($job->salary_period) { $salaryRange .= ' / ' . ucfirst($job->salary_period); }
    }
@endphp

<style>
    /* === Hero — light gradient + dark text (matches home theme) === */
    .jd-hero {
        position: relative;
        padding: 50px 0 60px;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        overflow: hidden;
        color: #1b3a6b;
        border-bottom: 1px solid #f0f0f3;
    }
    .jd-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 12% 20%, rgba(27, 58, 107,.04) 0, transparent 40%),
                    radial-gradient(circle at 88% 80%, rgba(27, 58, 107,.03) 0, transparent 45%);
    }
    .jd-hero-inner { position: relative; z-index: 2; }
    .jd-hero-bc {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #e5e5e7;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 12.5px;
        margin-bottom: 22px;
        list-style: none;
        box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .jd-hero-bc li { display: inline-flex; align-items: center; gap: 8px; }
    .jd-hero-bc li + li::before { content: '›'; color: #c7c7cc; }
    .jd-hero-bc a { color: #555; text-decoration: none; font-weight: 600; transition: color .15s ease; }
    .jd-hero-bc a:hover { color: #1b3a6b; }
    .jd-hero-bc li:last-child { color: #1b3a6b; font-weight: 700; }

    .jd-hero-row {
        display: grid;
        grid-template-columns: 100px minmax(0, 1fr);
        gap: 28px;
        align-items: center;
    }
    @media (max-width: 768px) {
        .jd-hero-row {
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
            text-align: center;
            justify-items: center;
        }
    }
    @media (max-width: 575px) {
        .jd-hero { padding: 20px 0 24px; }
        .jd-hero-bc {
            font-size: 11px;
            padding: 5px 11px;
            margin-bottom: 14px;
            max-width: 100%;
            gap: 5px;
            line-height: 1.5;
        }
        .jd-hero-bc li { gap: 5px; }
        .jd-hero-row { gap: 10px; }
    }
    .jd-hero-logo {
        width: 100px; height: 100px;
        border-radius: 16px;
        background: #fff;
        border: 1px solid #ececec;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        box-shadow: 0 14px 32px rgba(15,23,42,.08);
        flex-shrink: 0;
    }
    @media (max-width: 575px) {
        .jd-hero-logo { width: 60px; height: 60px; border-radius: 12px; }
    }
    .jd-hero-logo img { max-width: 75%; max-height: 75%; object-fit: contain; }
    .jd-hero-text {
        min-width: 0;
        max-width: 100%;
    }
    .jd-hero-text h1 {
        font-size: clamp(20px, 3vw, 36px);
        font-weight: 800;
        line-height: 1.2;
        color: #1b3a6b;
        margin: 0 0 14px;
        letter-spacing: -.5px;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    @media (max-width: 575px) {
        .jd-hero-text h1 { font-size: 17px; letter-spacing: -.2px; margin: 0 0 8px; line-height: 1.25; }
    }
    .jd-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px 22px;
        font-size: 14.5px;
        color: #555;
        align-items: center;
        justify-content: inherit;
    }
    @media (max-width: 575px) {
        .jd-hero-meta { gap: 6px 12px; font-size: 12px; justify-content: center; line-height: 1.4; }
        .jd-hero-meta i { font-size: 14px; }
        .jd-hero-meta span.badge-pill { font-size: 10px; padding: 3px 9px; letter-spacing: .8px; }
    }
    .jd-hero-meta span { display: inline-flex; align-items: center; gap: 7px; }
    .jd-hero-meta i { color: #1b3a6b; font-size: 16px; }
    .jd-hero-meta span.badge-pill {
        background: #1b3a6b;
        border: 1px solid #1b3a6b;
        color: #fff;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .jd-hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #1b3a6b;
        color: #fff !important;
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        border: 1.5px solid #1b3a6b;
        transition: all .15s ease;
    }
    .jd-hero-cta:hover {
        background: #16305a;
        border-color: #16305a;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }
    .jd-hero-cta.disabled {
        background: #e5e5e7;
        color: #999 !important;
        border-color: #e5e5e7;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Layout */
    .jd-section { padding: 50px 0 70px; background: #fafafa; }
    .jd-row {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 28px;
        align-items: start;
    }
    @media (max-width: 991px) { .jd-row { grid-template-columns: 1fr; } }

    .jd-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 32px 30px;
        margin-bottom: 24px;
    }
    .jd-card h2 {
        font-size: 19px;
        font-weight: 800;
        color: #1b3a6b;
        margin: 0 0 18px;
        padding-bottom: 14px;
        border-bottom: 2px solid #1b3a6b;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -.2px;
    }
    .jd-card h2 i { color: #1b3a6b; }

    .jd-description {
        font-size: 15.5px;
        line-height: 1.85;
        color: #2a2a2a;
    }
    .jd-description p { margin-bottom: 16px; }
    .jd-description strong { color: #1b3a6b; font-weight: 700; }
    .jd-description h2, .jd-description h3, .jd-description h4 {
        color: #1b3a6b;
        font-weight: 800;
        margin: 24px 0 12px;
    }
    .jd-description h2 { font-size: 22px; }
    .jd-description h3 { font-size: 18px; }
    .jd-description h4 { font-size: 16px; }
    .jd-description ul, .jd-description ol { margin: 14px 0 14px 24px; }
    .jd-description ul li, .jd-description ol li { margin-bottom: 6px; line-height: 1.7; }
    .jd-description br { line-height: 2.4; }

    /* === Formatted description (output of JobDescriptionFormatter) === */
    .jd-formatted .jd-h {
        font-size: 17px; font-weight: 800; color: #1b3a6b;
        margin: 22px 0 10px; padding-bottom: 8px;
        border-bottom: 2px solid #1b3a6b;
        display: inline-block; letter-spacing: -.2px;
    }
    .jd-formatted .jd-h:first-child { margin-top: 0; }
    .jd-formatted p { margin: 0 0 14px; color: #2a2a2a; }
    .jd-formatted .jd-empty { color: #9ca3af; font-style: italic; }
    .jd-formatted .jd-ul, .jd-formatted .jd-ol {
        list-style: none; padding-left: 0; margin: 8px 0 16px;
    }
    .jd-formatted .jd-ul li, .jd-formatted .jd-ol li {
        position: relative; padding-left: 26px; margin-bottom: 8px;
        line-height: 1.7;
    }
    .jd-formatted .jd-ul li::before {
        content: ""; position: absolute; left: 8px; top: 11px;
        width: 8px; height: 8px; border-radius: 50%;
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
    }
    .jd-formatted .jd-ol {
        counter-reset: jd-counter;
    }
    .jd-formatted .jd-ol li {
        counter-increment: jd-counter;
    }
    .jd-formatted .jd-ol li::before {
        content: counter(jd-counter);
        position: absolute; left: 0; top: 0;
        width: 22px; height: 22px;
        background: #1b3a6b; color: #fff;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11.5px; font-weight: 800;
    }
    .jd-formatted .jd-link {
        color: #2f7fc9; font-weight: 600;
        border-bottom: 1.5px solid #2f7fc9;
        padding-bottom: 1px;
        text-decoration: none;
    }
    .jd-formatted .jd-link:hover { color: #c44114; border-color: #c44114; }
    .jd-description a {
        color: #1b3a6b;
        font-weight: 600;
        border-bottom: 1.5px solid #1b3a6b;
        text-decoration: none;
        transition: opacity .15s ease;
    }
    .jd-description a:hover { opacity: .65; }

    /* Sidebar */
    .jd-aside { position: sticky; top: 90px; }

    /* Apply card — dark with brand-color glow (matches modern CTA) */
    /* === Sidebar — Why Apply Through Us card === */
    .jd-why-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 26px 24px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(15,23,42,.05);
    }
    .jd-why-card .eyebrow {
        display: inline-block;
        background: #fff4e6;
        border: 1px solid #ffd9b3;
        color: #b45309;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
        margin-bottom: 12px;
    }
    .jd-why-card h4 {
        color: #1b3a6b;
        font-size: 17px;
        font-weight: 800;
        margin: 0 0 18px;
        line-height: 1.3;
        letter-spacing: -.2px;
    }
    .jd-why-list { list-style: none; padding: 0; margin: 0; }
    .jd-why-list li {
        display: grid;
        grid-template-columns: 36px 1fr;
        gap: 12px;
        align-items: flex-start;
        padding: 12px 0;
        border-top: 1px dashed #ececec;
    }
    .jd-why-list li:first-child { border-top: 0; padding-top: 0; }
    .jd-why-list li:last-child { padding-bottom: 0; }
    .jd-why-list .ico {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(27, 58, 107,.25);
        flex-shrink: 0;
    }
    .jd-why-list strong {
        display: block;
        color: #1b3a6b;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .jd-why-list span {
        display: block;
        color: #555;
        font-size: 12.5px;
        line-height: 1.5;
    }
    html.dark-mode .jd-why-card {
        background: var(--site-card-bg) !important;
        border-color: var(--site-card-bd) !important;
        box-shadow: var(--site-shadow) !important;
    }
    html.dark-mode .jd-why-card .eyebrow {
        background: rgba(27, 58, 107,.15) !important;
        border-color: rgba(27, 58, 107,.30) !important;
        color: #ffab40 !important;
    }
    html.dark-mode .jd-why-card h4 { color: #fff !important; }
    html.dark-mode .jd-why-list li { border-top-color: var(--site-card-bd) !important; }
    html.dark-mode .jd-why-list strong { color: #fff !important; }
    html.dark-mode .jd-why-list span { color: #cbd5e1 !important; }

    .jd-aside-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 28px 26px;
        margin-bottom: 22px;
    }
    .jd-aside-card h3 {
        font-size: 14px;
        font-weight: 800;
        color: #1b3a6b;
        margin: 0 0 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #1b3a6b;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }
    .jd-overview-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .jd-overview-list li {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .jd-overview-list .ico {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: #1b3a6b;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .jd-overview-list .label { font-size: 11px; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px; }
    .jd-overview-list .value { font-size: 14px; color: #1b3a6b; font-weight: 700; line-height: 1.3; }

    /* Apply CTA banner — dark theme */
    .jd-apply-banner {
        background: #1b3a6b;
        color: #fff;
        text-align: center;
        padding: 40px 30px;
        border-radius: 16px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .jd-apply-banner::before, .jd-apply-banner::after {
        content: ""; position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: .25;
        pointer-events: none;
    }
    .jd-apply-banner::before { width: 280px; height: 280px; background: #2f7fc9; top: -80px; right: -60px; }
    .jd-apply-banner::after { width: 220px; height: 220px; background: #5e2bff; bottom: -60px; left: -50px; }
    .jd-apply-banner > * { position: relative; z-index: 2; }
    .jd-apply-banner h3 { color: #fff; font-size: 24px; font-weight: 800; margin: 0 0 10px; letter-spacing: -.4px; }
    .jd-apply-banner p { color: rgba(255,255,255,0.78); font-size: 14.5px; margin: 0 0 22px; line-height: 1.6; }
    .jd-apply-banner a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        color: #1b3a6b;
        padding: 14px 32px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14.5px;
        text-decoration: none;
        transition: all .15s ease;
    }
    .jd-apply-banner a:hover { transform: translateY(-1px); box-shadow: 0 12px 28px rgba(0,0,0,.25); }
    .jd-apply-banner a.disabled { opacity: 0.5; cursor: not-allowed; }

    /* Explore links */
    .jd-explore-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .jd-explore-links a {
        background: #fafafa;
        border: 1px solid #ececec;
        color: #16305a;
        padding: 9px 16px;
        border-radius: 999px;
        font-size: 13px;
        text-decoration: none;
        font-weight: 500;
        transition: all .15s ease;
    }
    .jd-explore-links a:hover {
        background: #1b3a6b;
        color: #fff;
        border-color: #1b3a6b;
        transform: translateY(-1px);
    }

    /* Related jobs */
    .jd-related {
        padding: 70px 0;
        background: #fff;
        border-top: 1px solid #ececec;
    }
    .jd-related-head { text-align: center; margin-bottom: 40px; }
    .jd-related-head h2 {
        font-size: clamp(24px, 2.8vw, 32px);
        font-weight: 800;
        color: #1b3a6b;
        margin: 0 0 8px;
        letter-spacing: -.4px;
    }
    .jd-related-head p { color: #555; font-size: 15px; margin: 0; }
    .jd-related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    @media (max-width: 991px) { .jd-related-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .jd-related-grid { grid-template-columns: 1fr; } }
    .jd-related-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 22px 20px;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: all .25s ease;
    }
    .jd-related-card:hover {
        border-color: #1b3a6b;
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(15,23,42,.10);
        color: inherit;
    }
    .jd-related-top { display: flex; gap: 12px; align-items: center; margin-bottom: 12px; }
    .jd-related-logo {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: #f5f5f7;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .jd-related-logo img { max-width: 80%; max-height: 80%; object-fit: contain; }
    .jd-related-card h3 { font-size: 15px; font-weight: 700; color: #1b3a6b; margin: 0 0 4px; line-height: 1.3; }
    .jd-related-card .company { font-size: 12px; color: #777; }
    .jd-related-card .meta { font-size: 12.5px; color: #555; display: flex; gap: 14px; flex-wrap: wrap; margin-top: 12px; }
    .jd-related-card .meta i { color: #1b3a6b; margin-right: 4px; }

    @media (max-width: 768px) {
        .jd-hero { padding: 40px 0 50px; }
        .jd-card { padding: 22px 20px; }
    }
</style>

{{-- Hero --}}
<section class="jd-hero">
    <div class="container">
        <div class="jd-hero-inner">
            <ul class="jd-hero-bc">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('jobs.index') }}">Jobs</a></li>
                @if($job->location)
                    <li><a href="{{ route('jobs.location', $job->location->id) }}">{{ $job->location->name }}</a></li>
                @endif
                <li>{{ \Illuminate\Support\Str::limit($job->position, 40) }}</li>
            </ul>
            <div class="jd-hero-row">
                <div class="jd-hero-logo">
                    <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}" alt="{{ $job->advertiser->name ?? 'Company' }}">
                </div>
                <div class="jd-hero-text">
                    <h1>{{ $job->position }}</h1>
                    <div class="jd-hero-meta">
                        @if($job->advertiser)
                            <span><i class="icon-material-outline-business-center"></i>{{ $job->advertiser->name }}</span>
                        @endif
                        @if($job->location)
                            <span><i class="icon-material-outline-location-on"></i>{{ $job->location->name }}@if($job->location->area), {{ $job->location->area }}@endif</span>
                        @endif
                        <span class="badge-pill">{{ $jobTypeLabel }}</span>
                        @if($salaryRange)
                            <span><i class="icon-material-outline-account-balance-wallet"></i>{{ $salaryRange }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Body --}}
<section class="jd-section">
    <div class="container">
        <div class="jd-row">
            <div>
                <div class="jd-card">
                    <h2><i class="icon-feather-info"></i> Job Description</h2>
                    <div class="jd-description jd-formatted">
                        {!! \App\Support\JobDescriptionFormatter::toHtml($job->description) !!}
                    </div>
                </div>

                @if($job->requirements)
                    <div class="jd-card">
                        <h2><i class="icon-feather-check-square"></i> Requirements</h2>
                        <div class="jd-description jd-formatted">
                            {!! \App\Support\JobDescriptionFormatter::toHtml($job->requirements) !!}
                        </div>
                    </div>
                @endif

                @if($job->benefits)
                    <div class="jd-card">
                        <h2><i class="icon-feather-award"></i> Benefits</h2>
                        <div class="jd-description">
                            {!! $job->benefits !!}
                        </div>
                    </div>
                @endif

                {{-- Apply Banner (above Skills so the primary CTA is visible without scrolling past the keyword list) --}}
                @php $isSaved = auth()->check() && auth()->user()->hasSavedJob($job); @endphp
                <div class="jd-apply-banner">
                    <h3>Ready to apply for this {{ $job->position }} role?</h3>
                    <p>Take the next step in your career — submit your application directly with the employer.</p>
                    <div class="jd-apply-actions">
                        <a href="{{ $applyUrl }}"
                           class="jd-apply-primary {{ !$hasApply ? 'disabled' : '' }}"
                           @if($hasApply) target="_blank" rel="noopener" @endif>
                            <i class="icon-line-awesome-briefcase"></i>
                            {{ $hasApply ? 'Apply For This Position' : 'Application Not Available' }}
                        </a>
                        <form method="POST" action="{{ route('jobs.save', $job) }}" class="jd-save-form" data-save-job>
                            @csrf
                            <button type="submit" class="jd-save-btn {{ $isSaved ? 'is-saved' : '' }}"
                                    aria-pressed="{{ $isSaved ? 'true' : 'false' }}"
                                    title="{{ $isSaved ? 'Remove from saved jobs' : 'Save this job for later' }}">
                                <i class="icon-feather-bookmark"></i>
                                <span class="label">{{ $isSaved ? 'Saved' : 'Save Job' }}</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ===== Auto-extracted skills / keywords (clickable) — placed below the apply CTA ===== --}}
                @php $skills = $job->seo_keywords_array ?? []; @endphp
                @if(!empty($skills))
                    <div class="jd-card">
                        <h2><i class="icon-feather-tag"></i> Skills &amp; Keywords</h2>
                        <p style="color:#6b7280;font-size:13.5px;margin:0 0 14px;">Auto-detected from this job's description. Click any skill to find similar roles.</p>
                        <div class="jd-skills">
                            @foreach($skills as $skill)
                                <a href="{{ route('jobs.index', ['position' => $skill]) }}" class="jd-skill" rel="nofollow">
                                    {{ $skill }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <style>
                        .jd-skills { display: flex; flex-wrap: wrap; gap: 8px; }
                        .jd-skill {
                            display: inline-flex; align-items: center;
                            background: #f5f5f7; color: #1b3a6b !important;
                            font-size: 13px; font-weight: 600;
                            padding: 7px 14px; border-radius: 999px;
                            text-decoration: none !important;
                            border: 1px solid #e5e5e7;
                            transition: all .15s ease;
                        }
                        .jd-skill:hover {
                            background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
                            border-color: #1b3a6b;
                            color: #fff !important;
                            transform: translateY(-1px);
                        }
                        html.dark-mode .jd-skill {
                            background: rgba(255,255,255,.06) !important;
                            border-color: rgba(255,255,255,.12) !important;
                            color: #e5e7eb !important;
                        }
                        html.dark-mode .jd-skill:hover {
                            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
                            border-color: #1b3a6b !important;
                            color: #fff !important;
                        }
                    </style>
                @endif

                <style>
                    .jd-apply-banner .jd-apply-actions {
                        display: flex; gap: 12px; flex-wrap: wrap; align-items: center;
                        margin-top: 18px;
                    }
                    .jd-apply-banner .jd-apply-primary {
                        display: inline-flex; align-items: center; gap: 10px;
                        background: #fff; color: #1b3a6b !important;
                        padding: 14px 28px; border-radius: 10px;
                        font-weight: 700; font-size: 15px; text-decoration: none;
                        transition: all .15s ease;
                    }
                    .jd-apply-banner .jd-apply-primary:hover {
                        background: #1b3a6b; color: #fff !important;
                        transform: translateY(-1px); box-shadow: 0 12px 24px rgba(27, 58, 107,.25);
                    }
                    .jd-apply-banner .jd-apply-primary.disabled {
                        opacity: .55; pointer-events: none; cursor: not-allowed;
                    }
                    .jd-save-form { margin: 0; }
                    .jd-save-btn {
                        display: inline-flex; align-items: center; gap: 8px;
                        background: transparent; color: #fff;
                        border: 1.5px solid rgba(255,255,255,.35);
                        padding: 13px 22px; border-radius: 10px;
                        font-weight: 700; font-size: 15px;
                        cursor: pointer; font-family: inherit;
                        transition: all .15s ease;
                    }
                    .jd-save-btn:hover {
                        background: rgba(255,255,255,.10);
                        border-color: #fff;
                    }
                    .jd-save-btn.is-saved {
                        background: #fff; color: #1b3a6b; border-color: #fff;
                    }
                    .jd-save-btn.is-saved i { color: #2f7fc9; }
                    .jd-save-btn i { font-size: 16px; }
                </style>

                {{-- Internal links --}}
                <div class="jd-card">
                    <h2><i class="icon-feather-compass"></i> Explore More</h2>
                    <div class="jd-explore-links">
                        @if($job->location)
                            <a href="{{ url('/jobs?location=' . urlencode($job->location->name)) }}">More jobs in {{ $job->location->name }}</a>
                        @endif
                        @if($job->category && $job->category->slug)
                            <a href="{{ route('jobs.category', $job->category->slug) }}">More {{ $job->category->name }} jobs</a>
                        @endif
                        <a href="{{ url('/jobs?position=' . urlencode($job->position)) }}">Similar {{ $job->position }} jobs</a>
                        <a href="{{ route('jobs.index') }}">Browse all jobs</a>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside>
                <div class="jd-aside">
                    <div class="jd-why-card">
                        <span class="eyebrow">Why Apply Through Us</span>
                        <h4>Trusted by job seekers across the USA</h4>
                        <ul class="jd-why-list">
                            <li>
                                <span class="ico"><i class="icon-feather-check-circle"></i></span>
                                <div>
                                    <strong>Verified Employer</strong>
                                    <span>Every company on JobGader is reviewed by our trust &amp; safety team.</span>
                                </div>
                            </li>
                            <li>
                                <span class="ico"><i class="icon-feather-zap"></i></span>
                                <div>
                                    <strong>Free for Job Seekers</strong>
                                    <span>No hidden fees — applying and creating alerts is 100% free.</span>
                                </div>
                            </li>
                            <li>
                                <span class="ico"><i class="icon-feather-shield"></i></span>
                                <div>
                                    <strong>Safe &amp; Secure</strong>
                                    <span>We never share your data without your consent.</span>
                                </div>
                            </li>
                            <li>
                                <span class="ico"><i class="icon-feather-clock"></i></span>
                                <div>
                                    <strong>Daily Fresh Listings</strong>
                                    <span>Thousands of new verified roles added every day across all 50 states.</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="jd-aside-card">
                        <h3>Job Overview</h3>
                        <ul class="jd-overview-list">
                            <li>
                                <div class="ico"><i class="icon-material-outline-business-center"></i></div>
                                <div>
                                    <div class="label">Job Type</div>
                                    <div class="value">{{ $jobTypeLabel }}</div>
                                </div>
                            </li>
                            @if($job->category)
                                <li>
                                    <div class="ico"><i class="icon-line-awesome-suitcase"></i></div>
                                    <div>
                                        <div class="label">Category</div>
                                        <div class="value">{{ $job->category->name }}</div>
                                    </div>
                                </li>
                            @endif
                            @if($salaryRange)
                                <li>
                                    <div class="ico"><i class="icon-line-awesome-money"></i></div>
                                    <div>
                                        <div class="label">Salary Range</div>
                                        <div class="value">{{ $salaryRange }}</div>
                                    </div>
                                </li>
                            @endif
                            @if($job->advertiser)
                                <li>
                                    <div class="ico"><i class="icon-line-awesome-building"></i></div>
                                    <div>
                                        <div class="label">Company</div>
                                        <div class="value">{{ $job->advertiser->name }}</div>
                                    </div>
                                </li>
                            @endif
                            @if($job->location)
                                <li>
                                    <div class="ico"><i class="icon-material-outline-location-on"></i></div>
                                    <div>
                                        <div class="label">Location</div>
                                        <div class="value">{{ $job->location->name }}@if($job->location->area), {{ $job->location->area }}@endif</div>
                                    </div>
                                </li>
                            @endif
                            <li>
                                <div class="ico"><i class="icon-line-awesome-calendar"></i></div>
                                <div>
                                    <div class="label">Date Posted</div>
                                    <div class="value">{{ $job->created_at?->format('M d, Y') ?? date('M d, Y') }}</div>
                                </div>
                            </li>
                            @if($job->work_hours)
                                <li>
                                    <div class="ico"><i class="icon-line-awesome-clock-o"></i></div>
                                    <div>
                                        <div class="label">Work Hours</div>
                                        <div class="value">{{ $job->work_hours }}</div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- Related Jobs --}}
@if(!empty($relatedJobs) && $relatedJobs->count())
    <section class="jd-related">
        <div class="container">
            <div class="jd-related-head">
                <h2>Similar Jobs You Might Like</h2>
                <p>Other roles matching your interests in {{ $job->category->name ?? 'this category' }} or {{ $job->location->name ?? 'your area' }}.</p>
            </div>
            <div class="jd-related-grid">
                @foreach($relatedJobs as $rel)
                    <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($rel->position . '-' . ($rel->location->name ?? ''))) }}" class="jd-related-card">
                        <div class="jd-related-top">
                            <div class="jd-related-logo">
                                <img src="{{ $rel->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}" alt="{{ $rel->advertiser->name ?? 'Company' }}" loading="lazy" decoding="async">
                            </div>
                            <div>
                                <h3>{{ \Illuminate\Support\Str::limit($rel->position, 50) }}</h3>
                                <span class="company">{{ $rel->advertiser->name ?? 'Company' }}</span>
                            </div>
                        </div>
                        <div class="meta">
                            @if($rel->location)
                                <span><i class="icon-material-outline-location-on"></i>{{ $rel->location->name }}</span>
                            @endif
                            <span><i class="icon-material-outline-business-center"></i>{{ ucfirst(str_replace('_',' ',$rel->employment_type ?? $rel->job_type ?? 'Open')) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection

{{--
    JSON-LD JobPosting + BreadcrumbList — pushed into <head> so Google's crawler picks the schema
    up before parsing body content. Audited against Google's "JobPosting" structured data spec:
      - validThrough is guaranteed to be in the future (60 days from now or job->valid_through,
        whichever is later) — past dates cause Google to deindex the listing.
      - directApply removed because applications redirect to the advertiser's own ATS, not ours.
      - baseSalary uses median when only one bound is set; min/max only when both are present.
      - employmentType is normalized to Google's enum (FULL_TIME, PART_TIME, CONTRACTOR, …).
--}}
@push('head')
@php
    $jobDescriptionHtml = $job->description ?? '';
    if ($job->requirements) { $jobDescriptionHtml .= '<h3>Requirements</h3>' . $job->requirements; }
    if ($job->benefits)     { $jobDescriptionHtml .= '<h3>Benefits</h3>'     . $job->benefits; }

    /*
     * Google's JobPosting guidelines expect the page to describe a single, real
     * opening. StructuredDataService decides that from the apply link, and the
     * blog spotlight pages use the same rule so the two never disagree. The
     * posting itself is built there too, so both pages publish identical facts.
     */
    $structuredData = app(\App\Services\StructuredDataService::class);
    $describesSingleVacancy = $structuredData->describesSingleVacancy($job->application_url);

    $jobPosting = ['@context' => 'https://schema.org']
        + $structuredData->jobPosting($job, url()->current(), $jobDescriptionHtml);
@endphp
@if ($describesSingleVacancy)
<script type="application/ld+json">
{!! json_encode($jobPosting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": {!! json_encode(url('/')) !!} },
        { "@@type": "ListItem", "position": 2, "name": "Jobs", "item": {!! json_encode(route('jobs.index')) !!} }@if($job->location),
        { "@@type": "ListItem", "position": 3, "name": {!! json_encode($job->location->name) !!}, "item": {!! json_encode(route('jobs.location', $job->location->id)) !!} },
        { "@@type": "ListItem", "position": 4, "name": {!! json_encode($job->position) !!}, "item": {!! json_encode(url()->current()) !!} }
        @else,
        { "@@type": "ListItem", "position": 3, "name": {!! json_encode($job->position) !!}, "item": {!! json_encode(url()->current()) !!} }
        @endif
    ]
}
</script>
@endpush