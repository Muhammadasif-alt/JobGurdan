@extends('user.layouts.master')

@section('title', $seeker->name.' — Job Seeker Profile | Jobs in USA')
@section('meta_description', $seeker->name.' is actively looking for opportunities in '.$profile['city'].'. View skills, experience and contact details on Jobs in USA.')

@section('content')

@php
    use App\Http\Controllers\Public\JobSeekerPublicController;

    $initials = collect(preg_split('/\s+/', trim($seeker->name)))
        ->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
@endphp

<style>
    .seeker-page { background: #f5f5f7; padding: 40px 0 70px; }

    .seeker-breadcrumb { font-size: 13.5px; color: #6b7280; margin-bottom: 20px; }
    .seeker-breadcrumb a { color: #0a0a0a; font-weight: 600; text-decoration: none; }
    .seeker-breadcrumb a:hover { text-decoration: underline; }
    .seeker-breadcrumb .sep { margin: 0 8px; color: #cbd5e1; }

    /* === Profile header === */
    .sk-detail-hero {
        position: relative;
        background: #0a0a0a; color: #fff;
        border-radius: 22px; padding: 40px 40px 36px;
        overflow: hidden;
        margin-bottom: 28px;
    }
    .sk-detail-hero::before, .sk-detail-hero::after {
        content: ""; position: absolute;
        border-radius: 50%; filter: blur(80px); opacity: .35;
        pointer-events: none;
    }
    .sk-detail-hero::before { width: 360px; height: 360px; background: #ff5722; top: -120px; right: -100px; }
    .sk-detail-hero::after  { width: 320px; height: 320px; background: #5e2bff; bottom: -120px; left: -100px; }
    .sk-detail-hero > * { position: relative; z-index: 2; }

    .sk-detail-hero-row {
        display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap;
    }
    .seeker-avatar-lg {
        width: 96px; height: 96px; border-radius: 22px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        color: #fff; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 36px; letter-spacing: -1px;
        flex-shrink: 0;
    }
    .seeker-meta { flex: 1; min-width: 0; }
    .seeker-status {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(52,211,153,.16); border: 1px solid rgba(52,211,153,.30);
        color: #34d399; font-size: 11.5px; font-weight: 700;
        padding: 5px 12px; border-radius: 999px;
        letter-spacing: 1px; text-transform: uppercase;
        margin-bottom: 10px;
    }
    .seeker-status .dot {
        width: 7px; height: 7px; background: #34d399;
        border-radius: 50%; animation: dotPulse 1.6s infinite;
    }
    @keyframes dotPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(52,211,153,.7); }
        70%      { box-shadow: 0 0 0 8px rgba(52,211,153,0); }
    }
    .seeker-name {
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800; letter-spacing: -.6px;
        margin: 0 0 6px;
    }
    .seeker-headline {
        font-size: 16px; color: rgba(255,255,255,.85);
        line-height: 1.5; margin: 0 0 16px;
    }
    .seeker-quick-meta { display: flex; gap: 22px; flex-wrap: wrap; font-size: 14px; color: rgba(255,255,255,.78); }
    .seeker-quick-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .seeker-quick-meta i { color: #ffd54f; font-size: 16px; }

    .seeker-cta-col { display: flex; flex-direction: column; gap: 10px; flex-shrink: 0; }
    .seeker-cta-col .btn-light, .seeker-cta-col .btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 12px 22px; border-radius: 12px;
        font-weight: 700; font-size: 14.5px;
        text-decoration: none !important; white-space: nowrap;
        transition: transform .15s ease;
    }
    .seeker-cta-col .btn-light { background: #fff; color: #0a0a0a !important; }
    .seeker-cta-col .btn-light:hover { transform: translateY(-2px); }
    .seeker-cta-col .btn-ghost { background: transparent; color: #fff !important; border: 1.5px solid rgba(255,255,255,.30); }
    .seeker-cta-col .btn-ghost:hover { background: rgba(255,255,255,.10); }

    /* === Two-column body === */
    .seeker-body {
        display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 24px; align-items: start;
    }
    @media (max-width: 991px) { .seeker-body { grid-template-columns: 1fr; } }

    .panel {
        background: #fff; border: 1px solid #ececec;
        border-radius: 16px; padding: 26px 28px;
        margin-bottom: 22px;
    }
    .panel h3 {
        font-size: 17px; font-weight: 700; color: #0a0a0a;
        margin: 0 0 16px; display: flex; align-items: center; gap: 10px;
    }
    .panel h3 i { color: #5e2bff; }
    .panel p { font-size: 14.5px; line-height: 1.75; color: #555; margin: 0; }
    .panel p + p { margin-top: 10px; }

    .skill-grid { display: flex; flex-wrap: wrap; gap: 8px; }
    .skill-grid .skill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 999px;
        background: #fafbff; border: 1px solid #e5e7eb;
        font-size: 13.5px; font-weight: 600; color: #0a0a0a;
    }
    .skill-grid .skill i { color: #ff5722; font-size: 12px; }

    /* Info table */
    .info-table { display: grid; grid-template-columns: max-content 1fr; gap: 12px 24px; }
    .info-table dt { font-size: 12.5px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; padding-top: 2px; }
    .info-table dd { margin: 0; font-size: 14.5px; color: #0a0a0a; font-weight: 600; }
    .info-table dd a { color: #0a0a0a; text-decoration: none; border-bottom: 1px solid #cbd5e1; }
    .info-table dd a:hover { border-color: #0a0a0a; }

    /* === Sidebar === */
    .seeker-side .panel-dark {
        background: #0a0a0a; color: #fff;
        border-radius: 16px; padding: 26px 24px;
        position: relative; overflow: hidden; margin-bottom: 22px;
    }
    .seeker-side .panel-dark::before {
        content: ""; position: absolute; right: -60px; top: -60px;
        width: 220px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.32), transparent 70%);
        pointer-events: none;
    }
    .seeker-side .panel-dark > * { position: relative; z-index: 1; }
    .seeker-side .panel-dark .eyebrow {
        display: inline-block; background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.4px; padding: 5px 12px; border-radius: 999px;
        margin-bottom: 14px;
    }
    .seeker-side .panel-dark h4 { font-size: 17px; font-weight: 700; margin: 0 0 8px; line-height: 1.35; }
    .seeker-side .panel-dark p { font-size: 13.5px; color: rgba(255,255,255,.82); margin: 0 0 14px; line-height: 1.65; }
    .seeker-side .panel-dark a.btn-light {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; color: #0a0a0a !important; font-weight: 700; font-size: 13.5px;
        padding: 11px 18px; border-radius: 10px; text-decoration: none;
    }
    .seeker-side .panel-dark a.btn-light:hover { transform: translateY(-1px); }

    /* Related seekers in sidebar */
    .related-list { display: flex; flex-direction: column; gap: 10px; }
    .related-card {
        display: flex; align-items: center; gap: 12px;
        padding: 12px; border-radius: 12px;
        text-decoration: none !important; color: inherit !important;
        transition: background .15s ease;
    }
    .related-card:hover { background: #fafbff; }
    .related-card .av {
        width: 44px; height: 44px; border-radius: 11px;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14px; flex-shrink: 0;
    }
    .related-card .info { flex: 1; min-width: 0; }
    .related-card .nm { font-weight: 700; font-size: 14px; color: #0a0a0a; line-height: 1.3; }
    .related-card .ct { font-size: 12px; color: #6b7280; margin-top: 2px; }

    /* Big back-button to listing */
    .back-row { margin-top: 12px; }
    .back-row a {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 14px; font-weight: 600; color: #0a0a0a;
        text-decoration: none;
    }
    .back-row a:hover { gap: 12px; transition: gap .15s ease; }

    /* === CV download accent === */
    .sk-detail-hero .btn-download {
        background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
        color: #fff !important;
        box-shadow: 0 6px 14px rgba(255,138,0,.30);
    }
    .sk-detail-hero .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(255,138,0,.45);
    }

    /* === "Why {Name} stands out" — motivational section === */
    .sk-why-section {
        margin-top: 36px;
        padding: 48px 36px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 22px;
    }
    .sk-section-head { text-align: center; max-width: 720px; margin: 0 auto 36px; }
    .sk-section-head .eyebrow {
        display: inline-block; background: rgba(255,138,0,.10); color: #ff8a00;
        font-size: 11.5px; font-weight: 800; letter-spacing: 1.5px;
        text-transform: uppercase; padding: 5px 14px; border-radius: 999px;
        margin-bottom: 14px;
    }
    .sk-section-head h2 {
        font-size: clamp(22px, 2.6vw, 30px); font-weight: 800;
        color: #0a0a0a; line-height: 1.2; margin: 0 0 12px; letter-spacing: -.4px;
    }
    .sk-section-head p { color: #555; font-size: 15px; line-height: 1.65; margin: 0; }

    .sk-why-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px;
    }
    @media (max-width: 991px) { .sk-why-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .sk-why-grid { grid-template-columns: 1fr; } }

    .sk-why-card {
        background: #fafbff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 22px 20px;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .sk-why-card:hover {
        transform: translateY(-3px);
        border-color: #ff8a00;
        box-shadow: 0 14px 28px rgba(15,23,42,.08);
    }
    .sk-why-ico {
        width: 44px; height: 44px; border-radius: 12px;
        background: linear-gradient(135deg, #0a0a0a, #1f1f1f);
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 12px;
        box-shadow: 0 6px 14px rgba(10,10,10,.18);
    }
    .sk-why-card h4 { font-size: 15.5px; font-weight: 800; color: #0a0a0a; margin: 0 0 6px; }
    .sk-why-card p  { font-size: 13.5px; line-height: 1.55; color: #555; margin: 0; }

    /* === Motivational CTA section === */
    .sk-motivate-section { margin-top: 28px; }
    .sk-motivate-card {
        position: relative;
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        color: #fff;
        border-radius: 24px;
        padding: 56px 48px;
        overflow: hidden;
        text-align: center;
    }
    .sk-motivate-card::before, .sk-motivate-card::after {
        content: ""; position: absolute;
        border-radius: 50%; filter: blur(80px); opacity: .35;
        pointer-events: none;
    }
    .sk-motivate-card::before { width: 380px; height: 380px; background: #ff5722; top: -120px; right: -100px; }
    .sk-motivate-card::after  { width: 340px; height: 340px; background: #5e2bff; bottom: -120px; left: -100px; }
    .sk-motivate-text { position: relative; z-index: 2; max-width: 740px; margin: 0 auto; }
    .sk-motivate-text .eyebrow {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.10); color: #ffd54f;
        border: 1px solid rgba(255,255,255,.18);
        font-size: 11.5px; font-weight: 800; letter-spacing: 1.5px;
        text-transform: uppercase; padding: 6px 14px; border-radius: 999px;
        margin-bottom: 16px;
    }
    .sk-motivate-text h2 {
        font-size: clamp(26px, 3vw, 38px); font-weight: 800;
        line-height: 1.15; letter-spacing: -.5px; margin: 0 0 14px;
        color: #fff;
    }
    .sk-motivate-text > p {
        font-size: 15.5px; line-height: 1.7; color: rgba(255,255,255,.82);
        margin: 0 0 26px;
    }

    .sk-motivate-stats {
        display: flex; justify-content: center; gap: 50px;
        margin: 0 0 28px;
        padding: 20px 0;
        border-top: 1px solid rgba(255,255,255,.15);
        border-bottom: 1px solid rgba(255,255,255,.15);
        flex-wrap: wrap;
    }
    .sk-motivate-stats > div { text-align: center; }
    .sk-motivate-stats strong {
        display: block; font-size: 26px; font-weight: 800; color: #ffab40; line-height: 1;
    }
    .sk-motivate-stats span {
        display: block; font-size: 11.5px; color: rgba(255,255,255,.65);
        text-transform: uppercase; letter-spacing: 1.4px; margin-top: 4px;
    }

    .sk-motivate-actions {
        display: inline-flex; gap: 12px; flex-wrap: wrap; justify-content: center;
    }
    .sk-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 24px; border-radius: 12px;
        font-weight: 700; font-size: 14.5px;
        text-decoration: none !important;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .sk-btn.primary {
        background: linear-gradient(135deg, #ff8a00, #ff5722);
        color: #fff !important;
        box-shadow: 0 8px 18px rgba(255,138,0,.30);
    }
    .sk-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(255,138,0,.45); }
    .sk-btn.outline {
        background: transparent; color: #fff !important;
        border: 1.5px solid rgba(255,255,255,.30);
    }
    .sk-btn.outline:hover { background: rgba(255,255,255,.10); border-color: rgba(255,255,255,.50); }

    /* === Dark mode === */
    html.dark-mode .seeker-page { background: var(--site-bg, #0f1216) !important; }
    html.dark-mode .seeker-breadcrumb { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .seeker-breadcrumb a { color: #ff8a00 !important; }

    html.dark-mode .panel {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .panel h3 { color: #fff !important; }
    html.dark-mode .panel p { color: var(--site-muted, #cbd5e1) !important; }
    html.dark-mode .skill-grid .skill {
        background: rgba(255,138,0,.10) !important;
        border-color: rgba(255,138,0,.25) !important;
        color: #ff8a00 !important;
    }
    html.dark-mode .info-table dt { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .info-table dd { color: #fff !important; }
    html.dark-mode .info-table dd a { color: #fff !important; border-bottom-color: rgba(255,255,255,.20) !important; }
    html.dark-mode .back-row a { color: #ff8a00 !important; }

    html.dark-mode .related-card:hover { background: rgba(255,255,255,.06) !important; }
    html.dark-mode .related-card .av {
        background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
    }
    html.dark-mode .related-card .nm { color: #fff !important; }
    html.dark-mode .related-card .ct { color: var(--site-muted, #b8c0cc) !important; }

    html.dark-mode .sk-why-section {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .sk-section-head h2 { color: #fff !important; }
    html.dark-mode .sk-section-head p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .sk-why-card {
        background: rgba(255,255,255,.03) !important;
        border-color: rgba(255,255,255,.08) !important;
    }
    html.dark-mode .sk-why-card:hover { border-color: #ff8a00 !important; }
    html.dark-mode .sk-why-card h4 { color: #fff !important; }
    html.dark-mode .sk-why-card p  { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .sk-why-ico {
        background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
        box-shadow: 0 6px 14px rgba(255,138,0,.30) !important;
    }
</style>

<div class="seeker-page">
    <div class="container">
        <div class="seeker-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span class="sep">/</span>
            <a href="{{ route('job-seekers.index') }}">Job Seekers</a>
            <span class="sep">/</span>
            <span>{{ $seeker->name }}</span>
        </div>

        {{-- ============ HERO ============ --}}
        <section class="sk-detail-hero">
            <div class="sk-detail-hero-row">
                <div class="seeker-avatar-lg">{{ $initials ?: 'U' }}</div>
                <div class="seeker-meta">
                    <span class="seeker-status"><span class="dot"></span> Actively looking</span>
                    <h1 class="seeker-name">{{ $seeker->name }}</h1>
                    <p class="seeker-headline">{{ $profile['headline'] }}</p>
                    <div class="seeker-quick-meta">
                        <span><i class="icon-feather-map-pin"></i> {{ $profile['city'] }}</span>
                        <span><i class="icon-feather-briefcase"></i> {{ $profile['experience_years'] }} yrs experience</span>
                        <span><i class="icon-feather-clock"></i> Open to {{ $profile['open_to'] }}</span>
                    </div>
                </div>
                <div class="seeker-cta-col">
                    <a href="{{ route('register') }}" class="btn-light"><i class="icon-feather-send"></i> Send a Job Offer</a>
                    @if($seeker->cv_path)
                        <a href="{{ asset('public/storage/' . $seeker->cv_path) }}"
                           class="btn-light btn-download"
                           download
                           target="_blank" rel="noopener">
                            <i class="icon-feather-download"></i> Download CV
                        </a>
                    @endif
                    <a href="{{ route('jobs.index') }}" class="btn-ghost"><i class="icon-feather-bookmark"></i> Save Profile</a>
                </div>
            </div>
        </section>

        {{-- ============ BODY ============ --}}
        <div class="seeker-body">
            <div class="seeker-main">
                <div class="panel">
                    <h3><i class="icon-feather-user"></i> About</h3>
                    <p>{{ $seeker->name }} is a {{ $profile['experience_years'] }}-year veteran based in {{ $profile['city'] }}, currently exploring {{ $profile['open_to'] }} opportunities across the United States.</p>
                    <p>{{ $profile['headline'] }} — comfortable working in fast-paced environments, with a strong track record of meeting deadlines and collaborating across teams. Available to start immediately for the right role.</p>
                </div>

                <div class="panel">
                    <h3><i class="icon-feather-zap"></i> Top Skills</h3>
                    <div class="skill-grid">
                        @foreach($profile['skills'] as $skill)
                            <span class="skill"><i class="icon-feather-check"></i> {{ $skill }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="panel">
                    <h3><i class="icon-feather-target"></i> Job Preferences</h3>
                    <dl class="info-table">
                        <dt>Preferred Location</dt>
                        <dd>{{ $profile['city'] }}</dd>

                        <dt>Open To</dt>
                        <dd>{{ $profile['open_to'] }} positions, on-site or remote</dd>

                        <dt>Experience</dt>
                        <dd>{{ $profile['experience_years'] }} years in the industry</dd>

                        <dt>Available From</dt>
                        <dd>Immediately</dd>

                        <dt>Username</dt>
                        <dd>&#64;{{ $seeker->username }}</dd>

                        <dt>Joined</dt>
                        <dd>{{ optional($seeker->created_at)->format('M Y') }}</dd>
                    </dl>
                </div>

                <div class="back-row">
                    <a href="{{ route('job-seekers.index') }}"><i class="icon-feather-arrow-left"></i> Back to all job seekers</a>
                </div>
            </div>

            <aside class="seeker-side">
                <div class="panel-dark">
                    <span class="eyebrow"><i class="icon-feather-briefcase"></i> For Employers</span>
                    <h4>Hire {{ explode(' ', $seeker->name)[0] }} for your team</h4>
                    <p>Post a matching job in seconds and reach this candidate plus 100,000+ other active U.S. seekers. Free for your first listing.</p>
                    <a href="{{ route('register') }}" class="btn-light"><i class="icon-feather-plus"></i> Post a Job</a>
                </div>

                @if($relatedSeekers->count())
                    <div class="panel" style="padding: 20px 22px;">
                        <h3 style="font-size: 15px; margin-bottom: 12px;"><i class="icon-feather-users"></i> Similar Talent</h3>
                        <div class="related-list">
                            @foreach($relatedSeekers as $rs)
                                @php
                                    $rsProfile  = JobSeekerPublicController::profileFor($rs);
                                    $rsInitials = collect(preg_split('/\s+/', trim($rs->name)))
                                        ->filter()->take(2)
                                        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                        ->implode('');
                                @endphp
                                <a href="{{ route('job-seekers.show', $rs->username) }}" class="related-card">
                                    <div class="av">{{ $rsInitials ?: 'U' }}</div>
                                    <div class="info">
                                        <div class="nm">{{ $rs->name }}</div>
                                        <div class="ct">{{ $rsProfile['city'] }} · {{ $rsProfile['experience_years'] }} yrs</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>

        {{-- ============ Why hire ============ --}}
        @php $firstName = explode(' ', $seeker->name)[0]; @endphp
        <section class="sk-why-section">
            <div class="sk-section-head">
                <span class="eyebrow">Why {{ $firstName }}</span>
                <h2>{{ $firstName }} stands out — and here's why employers move fast</h2>
                <p>Every job seeker on Jobs in USA is verified, active, and reachable within 24 hours. Skip cold-emailing — start a conversation today.</p>
            </div>
            <div class="sk-why-grid">
                <div class="sk-why-card">
                    <div class="sk-why-ico"><i class="icon-feather-shield"></i></div>
                    <h4>Verified Profile</h4>
                    <p>Identity, work history, and contact details reviewed by our trust team — no fake profiles.</p>
                </div>
                <div class="sk-why-card">
                    <div class="sk-why-ico"><i class="icon-feather-zap"></i></div>
                    <h4>Actively Looking</h4>
                    <p>{{ $firstName }} logged in this week and is ready to interview. Open to new roles right now.</p>
                </div>
                <div class="sk-why-card">
                    <div class="sk-why-ico"><i class="icon-feather-clock"></i></div>
                    <h4>Fast Response</h4>
                    <p>Average reply time under 24 hours. No ghosting, no waiting weeks for callbacks.</p>
                </div>
                <div class="sk-why-card">
                    <div class="sk-why-ico"><i class="icon-feather-award"></i></div>
                    <h4>{{ $profile['experience_years'] }}+ Years Experience</h4>
                    <p>Proven track record in {{ $profile['city'] }} — comfortable in fast-paced, high-volume environments.</p>
                </div>
            </div>
        </section>

        {{-- ============ Motivation / next-step CTA ============ --}}
        <section class="sk-motivate-section">
            <div class="sk-motivate-card">
                <div class="sk-motivate-text">
                    <span class="eyebrow"><i class="icon-feather-trending-up"></i> Take the next step</span>
                    <h2>One great hire can change everything</h2>
                    <p>Hiring the right person isn't just a checkbox — it's how teams grow, businesses scale, and great work gets done. Reach out to {{ $firstName }} today, or post a role and let candidates come to you.</p>
                    <div class="sk-motivate-stats">
                        <div><strong>14 days</strong><span>Avg. time to interview</span></div>
                        <div><strong>92%</strong><span>Employer satisfaction</span></div>
                        <div><strong>100K+</strong><span>Active U.S. seekers</span></div>
                    </div>
                    <div class="sk-motivate-actions">
                        <a href="{{ route('register') }}" class="sk-btn primary">
                            <i class="icon-feather-send"></i> Send a Job Offer
                        </a>
                        <a href="{{ route('jobs.index') }}" class="sk-btn outline">
                            Post a Job Instead <i class="icon-feather-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
