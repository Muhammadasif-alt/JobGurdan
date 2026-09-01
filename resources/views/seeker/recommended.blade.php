@extends('seeker.layouts.app')

@section('content')
<style>
    .rec-wrap { padding: 24px; max-width: 1200px; }
    .rec-head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .rec-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .rec-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .rec-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    .rec-filter {
        background: #fff; border: 1px solid #eef0f4;
        border-radius: 14px; padding: 14px; margin-bottom: 22px;
        display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px;
    }
    @media (max-width: 720px) { .rec-filter { grid-template-columns: 1fr; } }
    .rec-filter input, .rec-filter select {
        padding: 11px 14px; border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 14px; width: 100%; background: #fff;
    }
    .rec-filter input:focus, .rec-filter select:focus { outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.08); }
    .rec-filter button {
        background: #0a0a0a; color: #fff; border: none;
        padding: 11px 22px; border-radius: 10px;
        font-size: 14px; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .rec-filter button:hover { background: #1a1a1a; }

    /* Job grid */
    .rec-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px;
    }
    @media (max-width: 991px) { .rec-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .rec-grid { grid-template-columns: 1fr; } }

    .rec-card {
        position: relative;
        background: #fff; border: 1px solid #eef0f4; border-radius: 16px;
        padding: 20px; display: flex; flex-direction: column;
        transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
    }
    .rec-card:hover { border-color: #0a0a0a; box-shadow: 0 16px 32px rgba(15,23,42,.08); transform: translateY(-2px); }
    .rec-card .match-badge {
        position: absolute; top: 14px; right: 14px;
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 999px;
        font-size: 11.5px; font-weight: 800; letter-spacing: .3px;
        text-transform: uppercase;
    }
    .rec-card .match-badge.high   { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .rec-card .match-badge.mid    { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .rec-card .match-badge.low    { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }

    .rec-card .logo {
        width: 48px; height: 48px; border-radius: 12px;
        background: #f5f5f7; display: inline-flex; align-items: center; justify-content: center;
        overflow: hidden; flex-shrink: 0; margin-bottom: 14px;
    }
    .rec-card .logo img { width: 80%; height: 80%; object-fit: contain; }

    .rec-card h3 {
        font-size: 16px; font-weight: 700; color: #0a0a0a;
        line-height: 1.4; margin: 0 0 6px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .rec-card .company { font-size: 13px; color: #5e2bff; font-weight: 600; margin-bottom: 12px; }
    .rec-card .meta { list-style: none; padding: 0; margin: 0 0 14px; flex-grow: 1; }
    .rec-card .meta li { font-size: 12.5px; color: #555; margin-bottom: 5px; display: flex; align-items: center; gap: 7px; }
    .rec-card .meta li i { color: #0a0a0a; font-size: 13px; }

    .rec-card .why-match {
        background: #f8faff; border-left: 2.5px solid #5e2bff;
        padding: 8px 12px; border-radius: 6px;
        font-size: 11.5px; color: #4b5563; line-height: 1.5;
        margin-bottom: 14px;
    }
    .rec-card .why-match strong { color: #5e2bff; font-weight: 700; }
    .rec-card .why-match ul { margin: 4px 0 0; padding-left: 16px; }
    .rec-card .why-match li { margin: 2px 0; }

    .rec-card .cta {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        background: #0a0a0a; color: #fff !important;
        padding: 10px 16px; border-radius: 10px;
        font-size: 13px; font-weight: 600; text-decoration: none;
    }
    .rec-card .cta:hover { background: #1a1a1a; }

    /* Empty state */
    .rec-empty {
        text-align: center; padding: 60px 30px;
        background: #fff; border: 1px solid #eef0f4; border-radius: 16px;
        margin-top: 18px;
    }
    .rec-empty i { font-size: 44px; color: #c7c7cc; margin-bottom: 12px; display: inline-block; }
    .rec-empty h2 { font-size: 20px; font-weight: 800; color: #0a0a0a; margin: 0 0 6px; }
    .rec-empty p  { color: #6b7280; font-size: 14px; margin: 0 0 18px; line-height: 1.6; }
    .rec-empty .btn-cta {
        display: inline-flex; align-items: center; gap: 6px;
        background: #0a0a0a; color: #fff !important;
        padding: 10px 18px; border-radius: 10px;
        font-size: 13.5px; font-weight: 700; text-decoration: none;
    }

    .info-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f3efff; color: #5e2bff; border: 1px solid #ddd6fe;
        padding: 5px 12px; border-radius: 999px;
        font-size: 12px; font-weight: 600;
    }
</style>

<main class="app-main"><div class="app-content"><div class="rec-wrap">

    <div class="rec-head">
        <div>
            <h1>Recommended Jobs for You</h1>
            <div class="breadcrumbs">
                <a href="{{ route('seeker.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Recommended</span>
            </div>
        </div>
        <span class="info-pill">
            <i class="bi bi-stars"></i> Personalized to your profile · {{ $jobs->total() }} matches
        </span>
    </div>

    {{-- ===== Filters ===== --}}
    <form method="GET" class="rec-filter">
        <input type="text" name="keywords" placeholder="Keywords (e.g. React, designer, sales)" value="{{ $filters['keywords'] ?? '' }}">
        <select name="location">
            <option value="">All locations</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->name }}" @selected(($filters['location'] ?? '') === $loc->name)>{{ $loc->name }}</option>
            @endforeach
        </select>
        <select name="job_type">
            <option value="">Any job type</option>
            @foreach(['Full Time','Part Time','Contract','Internship','Remote','Hybrid'] as $jt)
                <option value="{{ $jt }}" @selected(($filters['job_type'] ?? '') === $jt)>{{ $jt }}</option>
            @endforeach
        </select>
        <button type="submit"><i class="bi bi-funnel"></i> Filter</button>
    </form>

    {{-- ===== Results ===== --}}
    @if($jobs->isEmpty())
        <div class="rec-empty">
            <i class="bi bi-stars"></i>
            <h2>No matching jobs yet</h2>
            <p>
                @if(empty(trim((string) $user->skills)))
                    Add your skills to your profile so we can match you with relevant jobs.
                @else
                    Try removing some filters, or check back tomorrow — we sync new jobs every hour.
                @endif
            </p>
            <a href="{{ route('seeker.profile') }}" class="btn-cta">
                <i class="bi bi-pencil-square"></i>
                @if(empty(trim((string) $user->skills))) Complete your profile @else Edit your profile @endif
            </a>
        </div>
    @else
        <div class="rec-grid">
            @foreach($jobs as $job)
                @php
                    $score = (int) ($job->match_score ?? 0);
                    $level = $score >= 70 ? 'high' : ($score >= 40 ? 'mid' : 'low');
                    $slug  = \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''));
                @endphp
                <div class="rec-card">
                    <span class="match-badge {{ $level }}">
                        <i class="bi bi-stars"></i> {{ $score }}% match
                    </span>

                    <div class="logo">
                        <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}" alt="{{ $job->advertiser->name ?? 'Company' }}">
                    </div>

                    <h3>{{ $job->position }}</h3>
                    @if($job->advertiser)
                        <div class="company">{{ $job->advertiser->name }}</div>
                    @endif

                    <ul class="meta">
                        @if($job->location)
                            <li><i class="bi bi-geo-alt"></i> {{ $job->location->name }}</li>
                        @endif
                        @if($job->job_type)
                            <li><i class="bi bi-briefcase"></i> {{ $job->job_type }}</li>
                        @endif
                        @if($job->category)
                            <li><i class="bi bi-tag"></i> {{ $job->category->name }}</li>
                        @endif
                        <li><i class="bi bi-clock"></i> {{ $job->created_at->diffForHumans() }}</li>
                    </ul>

                    @if(!empty($job->match_reasons))
                        <div class="why-match">
                            <strong>Why this matches:</strong>
                            <ul>
                                @foreach(array_slice($job->match_reasons, 0, 3) as $r)
                                    <li>{{ $r }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <a href="{{ route('jobs.show', $slug) }}" class="cta">
                        View job <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $jobs->withQueryString()->links() }}
        </div>
    @endif

</div></div></main>
@endsection
