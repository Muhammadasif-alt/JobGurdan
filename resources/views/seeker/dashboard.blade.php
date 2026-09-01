@extends('seeker.layouts.app')

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
        border-radius: 50%;
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
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 16px; margin-bottom: 28px;
    }
    @media (max-width: 991px) { .dash-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .dash-stats { grid-template-columns: 1fr; } }
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
    .stat-card .val { font-size: 28px; font-weight: 800; color: #0a0a0a; line-height: 1.1; margin: 6px 0 0; letter-spacing: -.5px; }

    /* Two column layout */
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

    /* Recommended job cards */
    .job-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 12px; padding: 14px;
    }
    @media (max-width: 700px) { .job-grid { grid-template-columns: 1fr; } }
    .job-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 16px;
        text-decoration: none !important;
        color: inherit !important;
        display: block;
        transition: all .2s ease;
    }
    .job-card:hover {
        border-color: #0a0a0a;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15,23,42,.08);
    }
    .job-card-top { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .job-card .logo {
        width: 40px; height: 40px; border-radius: 10px;
        background: #f3f4f6;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 13px; color: #0a0a0a;
        flex-shrink: 0;
    }
    .job-card .when { font-size: 11.5px; color: #9ca3af; margin-left: auto; white-space: nowrap; }
    .job-card .pos {
        font-weight: 700; color: #0a0a0a; font-size: 14.5px;
        line-height: 1.35; margin-bottom: 6px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .job-card .company { font-size: 12.5px; color: #6b7280; margin-bottom: 8px; }
    .job-card .meta {
        display: flex; gap: 14px; flex-wrap: wrap;
        font-size: 12px; color: #6b7280;
    }
    .job-card .meta span { display: inline-flex; align-items: center; gap: 4px; }
    .job-card .meta i { font-size: 13px; }
    .empty {
        padding: 40px 22px; text-align: center;
        color: #6b7280; font-size: 14px;
    }
    .empty i { display: block; font-size: 40px; color: #d1d5db; margin-bottom: 8px; }

    /* Tag chips */
    .chip-grid {
        display: flex; flex-wrap: wrap; gap: 8px;
        padding: 16px 18px;
    }
    .chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 13px;
        background: #f3f4f6;
        color: #0a0a0a !important;
        border-radius: 999px;
        font-size: 13px; font-weight: 600;
        text-decoration: none !important;
        transition: all .15s ease;
    }
    .chip:hover { background: #0a0a0a; color: #fff !important; transform: translateY(-1px); }
    .chip .count {
        font-size: 11px; font-weight: 600;
        background: rgba(0,0,0,.08); color: inherit;
        padding: 2px 7px; border-radius: 999px;
        margin-left: 2px;
    }
    .chip:hover .count { background: rgba(255,255,255,.18); }

    /* Sidebar profile + tips */
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
    .qa-card h4 { font-size: 18px; font-weight: 700; margin: 0 0 10px; line-height: 1.35; }
    .qa-card p { font-size: 13.5px; color: rgba(255,255,255,.78); margin: 0 0 16px; line-height: 1.6; }
    .qa-card ul { list-style: none; padding: 0; margin: 0 0 18px; }
    .qa-card ul li {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 9px 0; font-size: 13.5px;
        color: rgba(255,255,255,.85);
        border-top: 1px solid rgba(255,255,255,.08);
    }
    .qa-card ul li:first-child { border-top: none; padding-top: 0; }
    .qa-card ul li i { color: #ffb866; font-size: 16px; flex-shrink: 0; margin-top: 1px; }
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
    .quick-list a > i:first-child {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: #f3f4f6;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .quick-list a:hover > i:first-child { background: #0a0a0a; color: #fff; }
    .quick-list a .arrow { margin-left: auto; color: #9ca3af; font-size: 13px; }
</style>

@php
    $initials = collect(preg_split('/\s+/', trim($user->name ?? 'JS')))
        ->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
@endphp

<div class="dash-wrap">
    {{-- Hero --}}
    <section class="dash-hero">
        <div class="dash-hero-row">
            <div class="dash-hero-greet">
                <div class="dash-hero-avatar">{{ $initials ?: 'JS' }}</div>
                <div>
                    <span class="dash-eyebrow"><i class="bi bi-person"></i> Job Seeker</span>
                    <h1>Hi {{ explode(' ', $user->name)[0] ?? 'there' }}, ready to find your next role?</h1>
                    <p class="sub">Browse fresh openings, save your favourites, and apply with one click.</p>
                </div>
            </div>
            <div class="dash-hero-cta">
                <a href="{{ route('jobs.index') }}" class="btn-dark"><i class="bi bi-search"></i> Find Jobs</a>
                <a href="{{ route('jobs.categories') }}" class="btn-outline"><i class="bi bi-grid"></i> Browse Categories</a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="dash-stats">
        <div class="stat-card">
            <div class="ico"><i class="bi bi-briefcase"></i></div>
            <p class="lbl">Open Jobs</p>
            <h3 class="val">{{ number_format($stats['open_jobs'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="ico"><i class="bi bi-graph-up-arrow"></i></div>
            <p class="lbl">New This Week</p>
            <h3 class="val">{{ number_format($stats['this_week'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="ico"><i class="bi bi-people"></i></div>
            <p class="lbl">Hiring Companies</p>
            <h3 class="val">{{ number_format($stats['companies'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="ico"><i class="bi bi-grid"></i></div>
            <p class="lbl">Job Categories</p>
            <h3 class="val">{{ number_format($stats['categories'] ?? 0) }}</h3>
        </div>
    </section>

    <div class="grid-2">
        <div>
            {{-- Recommended jobs --}}
            <div class="panel">
                <div class="panel-head">
                    <h3 id="recHeading">
                        <i class="bi bi-lightning-fill"></i>
                        @if($aiMatched === 'matched')
                            Recommended for You
                            <span style="display:inline-flex;align-items:center;gap:4px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:999px;margin-left:8px;letter-spacing:.3px;">
                                <i class="bi bi-stars"></i> AI MATCHED
                            </span>
                        @elseif($aiMatched === 'pending')
                            Latest Jobs
                            <span id="recAiBadge" style="display:inline-flex;align-items:center;gap:4px;background:#f3f4f6;color:#6b7280;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:999px;margin-left:8px;letter-spacing:.3px;">
                                <span class="spinner-border spinner-border-sm" style="width:10px;height:10px;border-width:1.5px;"></span> Finding AI matches…
                            </span>
                        @else
                            Recommended Jobs
                        @endif
                    </h3>
                    <a href="{{ route('jobs.index') }}" class="see-all">View all <i class="bi bi-arrow-right"></i></a>
                </div>
                @if($aiMatched === 'none' && (empty($user->skills) && empty($user->headline)))
                    <div style="padding:14px 20px;background:#fffaf0;border-bottom:1px solid #fde68a;color:#92400e;font-size:13px;">
                        <i class="bi bi-info-circle"></i>
                        Fill in your <a href="{{ route('seeker.profile') }}" style="color:#92400e;font-weight:700;text-decoration:underline;">profile skills &amp; headline</a> to get AI-personalized job recommendations.
                    </div>
                @endif
                <div id="recGrid">
                    @if($recommendedJobs->isNotEmpty())
                        <div class="job-grid">
                            @foreach($recommendedJobs as $job)
                                @php
                                    $advName = $job->advertiser->name ?? 'Company';
                                    $advInit = mb_strtoupper(mb_substr($advName, 0, 1));
                                    $score = $job->ai_score ?? null;
                                    $scoreColor = $score === null ? null : ($score >= 70 ? '#047857' : ($score >= 40 ? '#c2410c' : '#6b7280'));
                                    $scoreBg    = $score === null ? null : ($score >= 70 ? '#ecfdf5' : ($score >= 40 ? '#fff7ed' : '#f3f4f6'));
                                @endphp
                                <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}" class="job-card" style="position:relative;">
                                    @if($score !== null)
                                        <span style="position:absolute;top:10px;right:10px;background:{{ $scoreBg }};color:{{ $scoreColor }};font-size:11px;font-weight:800;padding:3px 9px;border-radius:999px;border:1px solid currentColor;">
                                            {{ $score }}% match
                                        </span>
                                    @endif
                                    <div class="job-card-top">
                                        <div class="logo">{{ $advInit }}</div>
                                        <span class="when">{{ optional($job->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <div class="pos">{{ $job->position }}</div>
                                    <div class="company">{{ $advName }}</div>
                                    <div class="meta">
                                        @if($job->location)
                                            <span><i class="bi bi-geo-alt"></i> {{ $job->location->name }}</span>
                                        @endif
                                        @if($job->category)
                                            <span><i class="bi bi-tag"></i> {{ $job->category->name }}</span>
                                        @endif
                                    </div>
                                    @if(!empty($job->ai_reason))
                                        <div style="margin-top:10px;padding-top:10px;border-top:1px dashed #e5e7eb;font-size:11.5px;color:#5e2bff;font-style:italic;line-height:1.5;">
                                            <i class="bi bi-stars"></i> {{ $job->ai_reason }}
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="empty">
                            <i class="bi bi-briefcase"></i>
                            No openings right now — check back soon, new jobs are added every day.
                        </div>
                    @endif
                </div>
            </div>

            @if($aiMatched === 'pending')
                <script>
                (function () {
                    function escapeHtml(s) { return (s||'').toString().replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
                    function colorsFor(score) {
                        if (score >= 70) return ['#047857','#ecfdf5'];
                        if (score >= 40) return ['#c2410c','#fff7ed'];
                        return ['#6b7280','#f3f4f6'];
                    }
                    fetch('{{ route('seeker.dashboard.ai-matches') }}', {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(r => r.ok ? r.json() : null)
                    .then(json => {
                        if (!json || !json.ok || !Array.isArray(json.jobs) || json.jobs.length === 0) {
                            const b = document.getElementById('recAiBadge');
                            if (b) b.style.display = 'none';
                            return;
                        }
                        const heading = document.getElementById('recHeading');
                        if (heading) {
                            heading.innerHTML = '<i class="bi bi-lightning-fill"></i> Recommended for You ' +
                                '<span style="display:inline-flex;align-items:center;gap:4px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:999px;margin-left:8px;letter-spacing:.3px;">' +
                                '<i class="bi bi-stars"></i> AI MATCHED</span>';
                        }
                        const grid = document.getElementById('recGrid');
                        if (!grid) return;
                        const cards = json.jobs.map(j => {
                            const init = (j.company || 'C').charAt(0).toUpperCase();
                            const [c, bg] = j.ai_score != null ? colorsFor(j.ai_score) : [null, null];
                            const badge = j.ai_score != null
                                ? `<span style="position:absolute;top:10px;right:10px;background:${bg};color:${c};font-size:11px;font-weight:800;padding:3px 9px;border-radius:999px;border:1px solid currentColor;">${j.ai_score}% match</span>`
                                : '';
                            const reason = j.ai_reason
                                ? `<div style="margin-top:10px;padding-top:10px;border-top:1px dashed #e5e7eb;font-size:11.5px;color:#5e2bff;font-style:italic;line-height:1.5;"><i class="bi bi-stars"></i> ${escapeHtml(j.ai_reason)}</div>`
                                : '';
                            const loc = j.location ? `<span><i class="bi bi-geo-alt"></i> ${escapeHtml(j.location)}</span>` : '';
                            const cat = j.category ? `<span><i class="bi bi-tag"></i> ${escapeHtml(j.category)}</span>` : '';
                            return `<a href="${j.url}" class="job-card" style="position:relative;">${badge}
                                <div class="job-card-top"><div class="logo">${init}</div><span class="when">${escapeHtml(j.when || '')}</span></div>
                                <div class="pos">${escapeHtml(j.position)}</div>
                                <div class="company">${escapeHtml(j.company)}</div>
                                <div class="meta">${loc}${cat}</div>
                                ${reason}</a>`;
                        }).join('');
                        grid.innerHTML = `<div class="job-grid">${cards}</div>`;
                    })
                    .catch(() => {
                        const b = document.getElementById('recAiBadge');
                        if (b) b.style.display = 'none';
                    });
                })();
                </script>
            @endif

            {{-- Top categories --}}
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-grid"></i> Popular Categories</h3>
                    <a href="{{ route('jobs.categories') }}" class="see-all">View all <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="chip-grid">
                    @forelse($topCategories as $cat)
                        <a href="{{ route('jobs.category', $cat->slug) }}" class="chip">
                            {{ $cat->name }}
                            <span class="count">{{ number_format($cat->jobs_count ?? 0) }}</span>
                        </a>
                    @empty
                        <div class="empty" style="padding: 20px; width: 100%;">No categories yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Top locations --}}
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-geo-alt"></i> Top Hiring States</h3>
                    <a href="{{ route('jobs.locations') }}" class="see-all">View all <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="chip-grid">
                    @forelse($topLocations as $loc)
                        <a href="{{ route('jobs.location', $loc->id) }}" class="chip">
                            <i class="bi bi-geo-alt"></i> {{ $loc->name }}
                            <span class="count">{{ number_format($loc->jobs_count ?? 0) }}</span>
                        </a>
                    @empty
                        <div class="empty" style="padding: 20px; width: 100%;">No locations yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside>
            <div class="qa-card">
                <span class="eyebrow"><i class="bi bi-bullseye"></i> Profile Tips</span>
                <h4>Stand out to U.S. employers</h4>
                <p>Complete profiles get 5× more views. Add a few details and get matched with the right roles.</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i><span>Add a clear photo and headline</span></li>
                    <li><i class="bi bi-check-circle"></i><span>List your skills and recent experience</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Set job alerts for instant matches</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Apply to 3+ jobs to boost visibility</span></li>
                </ul>
                <a href="{{ route('jobs.index') }}" class="btn-light"><i class="bi bi-search"></i> Start Searching</a>
            </div>

            <div class="quick-list">
                <a href="{{ route('jobs.index') }}">
                    <i class="bi bi-search"></i>
                    <span>Search All Jobs</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <a href="{{ route('jobs.companies') }}">
                    <i class="bi bi-people"></i>
                    <span>Browse Companies</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <a href="{{ route('jobs.locations') }}">
                    <i class="bi bi-map"></i>
                    <span>Jobs by Location</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <a href="{{ route('blog.index') }}">
                    <i class="bi bi-book"></i>
                    <span>Career Tips Blog</span>
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
