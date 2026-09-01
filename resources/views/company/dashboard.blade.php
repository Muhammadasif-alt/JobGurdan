@extends('company.layouts.app')

@section('content')

<main class="app-main"><div class="app-content"><div class="container-fluid">
<style>
    .dash-wrap { max-width: 1280px; margin: 0 auto; padding: 40px 24px 70px; }

    /* Hero */
    .dash-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 60%, #f5f5f7 100%);
        border: 1px solid #ececec;
        border-radius: 22px;
        padding: 36px 36px 32px;
        margin-bottom: 28px;
        overflow: hidden;
    }
    .dash-hero::before {
        content: ""; position: absolute;
        right: -100px; top: -100px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.10), transparent 70%);
        pointer-events: none;
    }
    .dash-hero::after {
        content: ""; position: absolute;
        left: -80px; bottom: -100px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,87,34,.08), transparent 70%);
        pointer-events: none;
    }
    .dash-hero-row {
        position: relative; z-index: 1;
        display: flex; justify-content: space-between; align-items: center;
        gap: 22px; flex-wrap: wrap;
    }
    .dash-hero-greet { display: flex; align-items: center; gap: 18px; }
    .dash-hero-avatar {
        width: 64px; height: 64px;
        border-radius: 16px;
        background: #0a0a0a;
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 22px;
        box-shadow: 0 10px 24px rgba(10,10,10,.20);
        flex-shrink: 0;
    }
    .dash-eyebrow {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid #e5e5e7;
        padding: 5px 12px; border-radius: 999px;
        font-size: 11.5px; font-weight: 700;
        color: #0a0a0a; text-transform: uppercase; letter-spacing: 1.4px;
        margin-bottom: 8px;
    }
    .dash-hero h1 {
        font-size: clamp(24px, 2.6vw, 34px);
        font-weight: 800; letter-spacing: -.5px;
        margin: 0 0 6px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .dash-hero .sub { color: #555; font-size: 14.5px; margin: 0; }
    .dash-hero-cta { display: inline-flex; gap: 10px; flex-wrap: wrap; }
    .btn-dark, .btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 22px; border-radius: 10px;
        font-weight: 600; font-size: 14.5px;
        text-decoration: none !important;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        white-space: nowrap;
    }
    .btn-dark { background: #0a0a0a; color: #fff !important; border: 1px solid #0a0a0a; box-shadow: 0 6px 14px rgba(10,10,10,.18); }
    .btn-dark:hover { transform: translateY(-1px); background: #1a1a1a; box-shadow: 0 12px 24px rgba(10,10,10,.28); }
    .btn-outline { background: #fff; color: #0a0a0a !important; border: 1px solid #e5e5e7; }
    .btn-outline:hover { background: #f3f4f6; }

    /* Stats */
    .dash-stats {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 16px; margin-bottom: 28px;
    }
    @media (max-width: 767px) { .dash-stats { grid-template-columns: 1fr; } }
    .stat-card {
        position: relative;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 22px 24px;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card::before {
        content: ""; position: absolute; top: 0; left: 0; right: 0;
        height: 3px; background: #0a0a0a;
        transform: scaleX(0); transform-origin: left;
        transition: transform .25s ease;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 18px 36px rgba(15,23,42,.08); }
    .stat-card:hover::before { transform: scaleX(1); }
    .stat-card .ico {
        width: 44px; height: 44px; border-radius: 12px;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 12px;
        box-shadow: 0 6px 14px rgba(10,10,10,.18);
    }
    .stat-card .lbl { font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin: 0; }
    .stat-card .val { font-size: 30px; font-weight: 800; color: #0a0a0a; line-height: 1.1; margin: 6px 0 0; letter-spacing: -.5px; }

    /* Two-column section */
    .grid-2 {
        display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px; align-items: start;
    }
    @media (max-width: 991px) { .grid-2 { grid-template-columns: 1fr; } }

    .panel {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 22px;
    }
    .panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid #ececec;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 10px;
    }
    .panel-head h3 {
        font-size: 17px; font-weight: 700; color: #0a0a0a;
        margin: 0; display: inline-flex; align-items: center; gap: 8px;
    }
    .panel-head h3 i { color: #0a0a0a; }
    .panel-head a.see-all {
        font-size: 13.5px; color: #0a0a0a; text-decoration: none;
        font-weight: 600;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .panel-head a.see-all:hover { text-decoration: underline; }
    .panel-body { padding: 0; }

    /* Job rows */
    .job-row {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 22px;
        border-top: 1px solid #f3f4f6;
        text-decoration: none !important;
        color: inherit !important;
        transition: background .15s ease;
    }
    .job-row:first-child { border-top: none; }
    .job-row:hover { background: #fafbff; }
    .job-row .logo {
        width: 44px; height: 44px;
        border-radius: 11px;
        background: #f3f4f6;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14px;
        color: #0a0a0a;
        flex-shrink: 0;
    }
    .job-row .info { flex: 1; min-width: 0; }
    .job-row .pos {
        font-weight: 700; color: #0a0a0a; font-size: 14.5px;
        line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    }
    .job-row .meta {
        font-size: 12.5px; color: #6b7280;
        margin-top: 3px;
        display: inline-flex; align-items: center; gap: 12px;
        flex-wrap: wrap;
    }
    .job-row .meta span { display: inline-flex; align-items: center; gap: 4px; }
    .job-row .meta i { font-size: 13px; }
    .job-row .when {
        font-size: 12px; color: #9ca3af;
        white-space: nowrap;
    }
    .empty {
        padding: 40px 22px; text-align: center;
        color: #6b7280; font-size: 14px;
    }
    .empty i { display: block; font-size: 40px; color: #d1d5db; margin-bottom: 8px; }

    /* Quick actions sidebar */
    .qa-card {
        background: #0a0a0a;
        color: #fff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
    }
    .qa-card::before {
        content: ""; position: absolute;
        right: -60px; top: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.32), transparent 70%);
        pointer-events: none;
    }
    .qa-card::after {
        content: ""; position: absolute;
        left: -60px; bottom: -60px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,87,34,.28), transparent 70%);
        pointer-events: none;
    }
    .qa-card > * { position: relative; z-index: 1; }
    .qa-card .eyebrow {
        display: inline-block;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1.4px;
        padding: 5px 12px; border-radius: 999px;
        margin-bottom: 14px;
    }
    .qa-card h4 { font-size: 18px; font-weight: 700; margin: 0 0 8px; }
    .qa-card p { font-size: 13.5px; color: rgba(255,255,255,.78); margin: 0 0 16px; line-height: 1.6; }
    .qa-card .btn-light {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; color: #0a0a0a !important;
        font-weight: 700; font-size: 13.5px;
        padding: 10px 18px; border-radius: 10px;
        text-decoration: none !important;
        transition: transform .15s ease;
    }
    .qa-card .btn-light:hover { transform: translateY(-1px); }

    .quick-list {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 8px;
    }
    .quick-list a {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        text-decoration: none !important;
        color: #0a0a0a !important;
        font-size: 14px; font-weight: 600;
        transition: background .15s ease;
    }
    .quick-list a:hover { background: #f3f4f6; }
    .quick-list a i:first-child {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: #f3f4f6;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .quick-list a:hover i:first-child { background: #0a0a0a; color: #fff; }
    .quick-list a .arrow { margin-left: auto; color: #9ca3af; font-size: 13px; }
</style>

@php
    $initials = collect(preg_split('/\s+/', trim($user->name ?? 'CO')))
        ->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
@endphp

<div class="dash-wrap">
    {{-- Hero --}}
    <section class="dash-hero">
        <div class="dash-hero-row">
            <div class="dash-hero-greet">
                <div class="dash-hero-avatar">{{ $initials ?: 'CO' }}</div>
                <div>
                    <span class="dash-eyebrow"><i class="bi bi-briefcase"></i> Company Dashboard</span>
                    <h1>Welcome back, {{ explode(' ', $user->name)[0] ?? 'there' }}!</h1>
                    <p class="sub">Manage your job postings, review applications, and grow your team.</p>
                </div>
            </div>
            <div class="dash-hero-cta">
                <a href="{{ route('jobs.index') }}" class="btn-outline"><i class="bi bi-search"></i> Browse All Jobs</a>
                <a href="{{ route('company.jobs.create') }}" class="btn-dark"><i class="bi bi-plus-lg"></i> Post a New Job</a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="dash-stats">
        <div class="stat-card">
            <div class="ico"><i class="bi bi-briefcase"></i></div>
            <p class="lbl">Total Live Jobs</p>
            <h3 class="val">{{ number_format($stats['total_jobs'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="ico"><i class="bi bi-check-circle"></i></div>
            <p class="lbl">Active Postings</p>
            <h3 class="val">{{ number_format($stats['active_jobs'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="ico"><i class="bi bi-graph-up-arrow"></i></div>
            <p class="lbl">Posted This Week</p>
            <h3 class="val">{{ number_format($stats['this_week'] ?? 0) }}</h3>
        </div>
    </section>

    <div class="grid-2">
        {{-- Recent jobs --}}
        <div>
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-clock"></i> Latest Job Postings</h3>
                    <a href="{{ route('jobs.index') }}" class="see-all">View all <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="panel-body">
                    @forelse($recentJobs as $job)
                        @php
                            $advName = $job->advertiser->name ?? 'Company';
                            $advInit = mb_strtoupper(mb_substr($advName, 0, 1));
                        @endphp
                        <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}" class="job-row">
                            <div class="logo">{{ $advInit }}</div>
                            <div class="info">
                                <div class="pos">{{ $job->position }}</div>
                                <div class="meta">
                                    <span><i class="bi bi-briefcase"></i> {{ $advName }}</span>
                                    @if($job->location)
                                        <span><i class="bi bi-geo-alt"></i> {{ $job->location->name }}</span>
                                    @endif
                                    @if($job->category)
                                        <span><i class="bi bi-tag"></i> {{ $job->category->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="when">{{ optional($job->created_at)->diffForHumans() }}</span>
                        </a>
                    @empty
                        <div class="empty">
                            <i class="bi bi-briefcase"></i>
                            No job postings yet — click "Post a New Job" to get started.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar quick actions --}}
        <aside>
            <div class="qa-card">
                <span class="eyebrow"><i class="bi bi-lightning-fill"></i> Get Hiring Faster</span>
                <h4>Reach 100,000+ U.S. job seekers</h4>
                <p>Post a verified job and have it surfaced to candidates within minutes. Free for the first listing.</p>
                <a href="{{ route('company.jobs.create') }}" class="btn-light"><i class="bi bi-plus-lg"></i> Post a New Job</a>
            </div>

            <div class="quick-list">
                <a href="{{ route('jobs.index') }}">
                    <i class="bi bi-search"></i>
                    <span>Browse All Jobs</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <a href="{{ route('jobs.companies') }}">
                    <i class="bi bi-people"></i>
                    <span>Top Employers</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <a href="{{ route('jobs.categories') }}">
                    <i class="bi bi-grid"></i>
                    <span>Job Categories</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <a href="{{ route('contact.us') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Contact Support</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
            </div>
        </aside>
    </div>
</div>
</div></div></main>
@endsection
