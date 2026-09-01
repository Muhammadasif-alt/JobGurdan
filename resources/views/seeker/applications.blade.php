@extends('seeker.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">

<style>
    .ap-wrap { padding: 24px; max-width: 1080px; }
    .ap-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .ap-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .ap-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .ap-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    .stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 22px; }
    @media (max-width:767px){ .stats { grid-template-columns: 1fr; } }
    .stat-card { background: #fff; border: 1px solid #ececec; border-radius: 14px; padding: 18px 22px; }
    .stat-card .ic { width: 38px; height: 38px; border-radius: 10px; background: #0a0a0a; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 10px; }
    .stat-card .lbl { font-size: 12px; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
    .stat-card .val { font-size: 26px; font-weight: 800; color: #0a0a0a; line-height: 1.1; margin: 4px 0 0; }

    .panel { background: #fff; border: 1px solid #ececec; border-radius: 16px; overflow: hidden; }
    .panel-head { padding: 16px 22px; border-bottom: 1px solid #ececec; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .panel-head h3 { font-size: 17px; font-weight: 700; color: #0a0a0a; margin: 0; display: flex; align-items: center; gap: 8px; }
    .panel-head .badge-soft { background: #0a0a0a; color: #fff; padding: 4px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 700; }

    .application {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 22px; border-top: 1px solid #f3f4f6;
        text-decoration: none !important; color: inherit !important;
        transition: background .15s ease;
    }
    .application:first-child { border-top: none; }
    .application:hover { background: #fafbff; }
    .application .logo {
        width: 48px; height: 48px; border-radius: 12px;
        background: #f3f4f6; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; color: #0a0a0a; font-size: 16px; flex-shrink: 0;
    }
    .application .info { flex: 1; min-width: 0; }
    .application .title { font-weight: 700; color: #0a0a0a; font-size: 15px; line-height: 1.3; margin: 0 0 4px; }
    .application .meta { font-size: 12.5px; color: #6b7280; display: inline-flex; gap: 14px; flex-wrap: wrap; }
    .application .meta span { display: inline-flex; align-items: center; gap: 4px; }
    .application .status { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; flex-shrink: 0; }
    .application .status.pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .application .status.accepted { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
    .application .status.rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .application .when { font-size: 12px; color: #9ca3af; white-space: nowrap; }

    .empty { text-align: center; padding: 70px 20px; }
    .empty > i { font-size: 56px; color: #d1d5db; display: block; margin: 0 auto 6px; }
    .empty h4 { color: #0a0a0a; font-weight: 700; margin: 14px 0 6px; font-size: 18px; }
    .empty p { color: #6b7280; margin: 0 0 22px; font-size: 14.5px; }
    .empty .btn i { font-size: 14px; color: inherit; display: inline-block; margin: 0; }
    @media (max-width: 575px) {
        .empty { padding: 48px 16px; }
        .empty > i { font-size: 48px; }
        .empty h4 { font-size: 16px; }
        .empty p { font-size: 13.5px; }
    }

    .btn { padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none !important; display: inline-flex; align-items: center; gap: 6px; }
    .btn-primary { background: #0a0a0a; color: #fff !important; border: 1px solid #0a0a0a; }
    .btn-primary:hover { background: #1a1a1a; }
</style>

<div class="ap-wrap">
    <div class="ap-head">
        <div>
            <h1>My Applications</h1>
            <div class="breadcrumbs">
                <a href="{{ route('seeker.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Applications</span>
            </div>
        </div>
        <a href="{{ route('jobs.index') }}" target="_blank" class="btn btn-primary"><i class="bi bi-search"></i> Find More Jobs</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="ic"><i class="bi bi-file-earmark-text"></i></div>
            <p class="lbl">Total Applications</p>
            <h3 class="val">{{ $applications->count() }}</h3>
        </div>
        <div class="stat-card">
            <div class="ic"><i class="bi bi-hourglass-split"></i></div>
            <p class="lbl">Pending Review</p>
            <h3 class="val">{{ $applications->count() }}</h3>
        </div>
        <div class="stat-card">
            <div class="ic"><i class="bi bi-bookmark-check"></i></div>
            <p class="lbl">Saved Jobs</p>
            <h3 class="val">0</h3>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3><i class="bi bi-file-earmark-text"></i> Recent Applications</h3>
            @if($applications->count())
                <span class="badge-soft">{{ $applications->count() }}</span>
            @endif
        </div>

        @forelse($applications as $job)
            @php
                $advName = $job->advertiser->name ?? 'Company';
                $advInit = mb_strtoupper(mb_substr($advName, 0, 1));
            @endphp
            <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}" class="application">
                <div class="logo">{{ $advInit }}</div>
                <div class="info">
                    <h4 class="title">{{ $job->position }}</h4>
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
                <span class="status pending"><i class="bi bi-hourglass-split"></i> {{ $job->status_label }}</span>
                <span class="when">{{ $job->applied_at->diffForHumans() }}</span>
            </a>
        @empty
            <div class="empty">
                <i class="bi bi-file-earmark-text"></i>
                <h4>You haven't applied to any jobs yet</h4>
                <p>Start browsing thousands of verified U.S. jobs and apply with one click.</p>
                <a href="{{ route('jobs.index') }}" target="_blank" class="btn btn-primary"><i class="bi bi-search"></i> Browse Jobs</a>
            </div>
        @endforelse
    </div>
</div>
</div></div></main>
@endsection
