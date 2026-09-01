@extends('admin.layouts.app')

@section('content')
@php
    $recent = collect($recent_jobs ?? []);
    $recent = $recent->sortByDesc(fn($j) => $j->created_at ?? null);

    $topCategories = collect($jobsByCategory ?? []);
    if ($topCategories->isEmpty()) {
        $topCategories = $recent
            ->groupBy(fn($j) => $j->category?->name ?? ($j->category_id ?? 'Unknown'))
            ->map(fn($g, $k) => ['name' => $k, 'count' => $g->count()])
            ->values()->sortByDesc('count')->take(8)->values();
    } else {
        $topCategories = collect($topCategories)
            ->sortByDesc(fn($i) => $i['jobs_count'] ?? ($i['count'] ?? 0))
            ->take(8)->values();
    }

    $topLocations = collect($jobsByLocation ?? []);
    if ($topLocations->isEmpty()) {
        $topLocations = $recent
            ->groupBy(fn($j) => $j->location?->name ?? ($j->location_id ?? 'Unknown'))
            ->map(fn($g, $k) => ['name' => $k, 'count' => $g->count()])
            ->values()->sortByDesc('count')->take(8)->values();
    } else {
        $topLocations = collect($topLocations)
            ->sortByDesc(fn($i) => $i['jobs_count'] ?? ($i['count'] ?? 0))
            ->take(8)->values();
    }

    // Compute max count for relative bar fill
    $maxCatCount = $topCategories->max(fn($i) => $i['jobs_count'] ?? ($i['count'] ?? ($i->count ?? 0))) ?: 1;
    $maxLocCount = $topLocations->max(fn($i) => $i['jobs_count'] ?? ($i['count'] ?? ($i->count ?? 0))) ?: 1;
@endphp

<style>
    /* === Dashboard custom styles (Jobs in USA — dark #0a0a0a brand) === */
    .dash-wrap { padding: 24px 24px 36px; }
    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 22px;
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 60%, #f5f5f7 100%);
        border: 1px solid #eef0f4;
        border-radius: 16px;
        padding: 22px 24px;
        overflow: hidden;
    }
    .dash-header::before {
        content: "";
        position: absolute;
        right: -60px; top: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.10), transparent 70%);
        pointer-events: none;
    }
    .dash-header::after {
        content: "";
        position: absolute;
        left: -50px; bottom: -60px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,87,34,.08), transparent 70%);
        pointer-events: none;
    }
    .dash-header > * { position: relative; z-index: 1; }
    .dash-header .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #0a0a0a;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.4px;
        padding: 5px 12px;
        border-radius: 999px;
        margin-bottom: 8px;
    }
    .dash-header h1 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .dash-header .subtitle {
        font-size: 14.5px;
        color: #6b7280;
        margin-top: 4px;
    }
    .dash-header .btn-primary-grad {
        background: #0a0a0a;
        border: 1px solid #0a0a0a;
        color: #fff;
        font-weight: 600;
        font-size: 14.5px;
        padding: 11px 22px;
        border-radius: 10px;
        box-shadow: 0 8px 18px rgba(10,10,10,.20);
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .dash-header .btn-primary-grad:hover {
        transform: translateY(-1px);
        background: #1a1a1a;
        box-shadow: 0 12px 24px rgba(10,10,10,.30);
        color: #fff;
    }

    /* Stat cards */
    .stat-card {
        position: relative;
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 16px;
        padding: 22px;
        overflow: hidden;
        height: 100%;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .stat-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: #0a0a0a;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .25s ease;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 18px 36px rgba(15,23,42,.10); border-color: transparent; }
    .stat-card:hover::before { transform: scaleX(1); }
    .stat-card .icon-wrap {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        margin-bottom: 14px;
        background: #0a0a0a;
        box-shadow: 0 8px 18px rgba(10,10,10,.18);
    }
    .stat-card .stat-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
    }
    .stat-card .stat-value {
        font-size: 38px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        margin: 8px 0 0;
        letter-spacing: -.5px;
    }
    .stat-card .stat-foot {
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px dashed #eef0f4;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #0a0a0a;
        transition: gap .15s ease;
    }
    .stat-card .stat-foot:hover { gap: 10px; color: #0a0a0a; }
    .stat-card::after {
        content: "";
        position: absolute;
        right: -30px; bottom: -30px;
        width: 120px; height: 120px;
        border-radius: 50%;
        opacity: .04;
        background: #0a0a0a;
        pointer-events: none;
    }
    .stat-card.c-blue   .icon-wrap { background: #0a0a0a; }
    .stat-card.c-green  .icon-wrap { background: #0a0a0a; }
    .stat-card.c-orange .icon-wrap { background: #0a0a0a; }
    .stat-card.c-pink   .icon-wrap { background: #0a0a0a; }

    /* Panels */
    .panel {
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        border-bottom: 1px solid #eef0f4;
        flex-wrap: wrap;
        gap: 10px;
    }
    .panel-head h3 {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .panel-head h3 .badge-soft {
        background: #0a0a0a;
        color: #fff;
        font-weight: 700;
        font-size: 13.5px;
        padding: 4px 11px;
        border-radius: 999px;
    }
    .panel-head .actions { display: inline-flex; gap: 8px; flex-wrap: wrap; }
    .btn-soft {
        padding: 10px 18px;
        border-radius: 9px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        font-size: 14.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all .15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-soft:hover { background: #f3f4f6; color: #111827; }
    .btn-soft.primary { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .btn-soft.primary:hover { background: #1a1a1a; color: #fff; }
    .btn-soft.success { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .btn-soft.success:hover { background: #1a1a1a; color: #fff; }
    .btn-soft.danger  { background: #fff; color: #dc2626; border-color: #fee2e2; }
    .btn-soft.danger:hover  { background: #fef2f2; }

    /* Table tweak */
    .panel .table { margin: 0; }
    .panel .table thead th {
        font-size: 13.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #6b7280;
        background: #f9fafb;
        padding: 16px 18px;
        border-bottom: 1px solid #eef0f4;
        border-top: none;
    }
    .panel .table tbody td {
        font-size: 15.5px;
        color: #374151;
        padding: 16px 18px;
        border-top: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .panel .table tbody tr:hover td { background: #fafbff; }
    .panel .table tbody td small { font-size: 14px !important; }
    .panel .table .id-pill {
        display: inline-block;
        background: #f3f4f6;
        color: #0a0a0a;
        font-weight: 700;
        font-size: 13.5px;
        padding: 5px 11px;
        border-radius: 6px;
    }
    .panel .table .pos { font-weight: 600; color: #0f172a; font-size: 15.5px; }

    /* Top list (categories / locations) */
    .top-list { padding: 14px 16px; }
    .top-list-item {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 8px;
        padding: 12px 6px;
        border-bottom: 1px solid #f3f4f6;
    }
    .top-list-item:last-child { border-bottom: none; }
    .top-list-item .name {
        font-size: 15.5px;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .top-list-item .count-pill {
        background: #f3f4f6;
        color: #0a0a0a;
        font-weight: 700;
        font-size: 13.5px;
        padding: 4px 13px;
        border-radius: 999px;
    }
    .top-list-item .count-pill.blue { background: #f3f4f6; color: #0a0a0a; }
    .top-list-item .bar {
        grid-column: 1 / -1;
        height: 4px;
        background: #f3f4f6;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 6px;
    }
    .top-list-item .bar > span {
        display: block;
        height: 100%;
        border-radius: 999px;
        background: #0a0a0a;
    }
    .top-list-item .bar.blue > span { background: #0a0a0a; }
    .panel-foot {
        padding: 14px 18px;
        border-top: 1px solid #eef0f4;
        text-align: right;
    }
</style>

<div class="dash-wrap">
    <div class="dash-header">
        <div>
            <span class="eyebrow"><i class="bi bi-shield-check"></i> Admin Overview</span>
            <h1>Dashboard</h1>
            <div class="subtitle">Overview of jobs, employers, categories, and locations.</div>
        </div>
        <a href="{{ route('admin.jobs.index') }}" class="btn-primary-grad">
            <i class="bi bi-briefcase"></i> Manage Jobs
        </a>
    </div>

    <!-- Stat cards -->
    <div class="row g-3 mb-2">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card c-blue">
                <div class="icon-wrap"><i class="bi bi-briefcase"></i></div>
                <p class="stat-label">Total Jobs</p>
                <h3 class="stat-value">{{ number_format($stats['total_jobs'] ?? 0) }}</h3>
                <a href="{{ route('admin.jobs.index') }}" class="stat-foot">View all jobs <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card c-green">
                <div class="icon-wrap"><i class="bi bi-tags"></i></div>
                <p class="stat-label">Categories</p>
                <h3 class="stat-value">{{ number_format($stats['total_categories'] ?? 0) }}</h3>
                <a href="{{ route('admin.categories.index') }}" class="stat-foot">Manage categories <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card c-orange">
                <div class="icon-wrap"><i class="bi bi-building"></i></div>
                <p class="stat-label">Employers</p>
                <h3 class="stat-value">{{ number_format($stats['total_advertisers'] ?? 0) }}</h3>
                <a href="{{ route('admin.advertisers.index') }}" class="stat-foot">Manage employers <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card c-pink">
                <div class="icon-wrap"><i class="bi bi-geo-alt"></i></div>
                <p class="stat-label">Locations</p>
                <h3 class="stat-value">{{ number_format($stats['total_locations'] ?? 0) }}</h3>
                <a href="{{ route('admin.locations.index') }}" class="stat-foot">Manage locations <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <!-- Recent Jobs -->
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-head">
                    <h3>
                        <i class="bi bi-clock-history" style="color:#0a0a0a"></i>
                        Recent Jobs
                        <span class="badge-soft">{{ $recent->count() }}</span>
                    </h3>
                    <div class="actions">
                        <a href="{{ route('admin.jobs.index') }}" class="btn-soft"><i class="bi bi-list-ul"></i> View All</a>
                        <a href="{{ route('admin.jobs.create') }}" class="btn-soft success"><i class="bi bi-plus-lg"></i> Create Job</a>
                        <form id="delete-all-form" method="POST" action="{{ route('admin.cleanup') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="confirmation" value="">
                            <button type="button" id="btn-delete-all" class="btn-soft danger"><i class="bi bi-trash"></i> Delete All</button>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:80px">ID</th>
                                <th>Position</th>
                                <th>Category</th>
                                <th>Employer</th>
                                <th>Location</th>
                                <th style="width:160px">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent as $job)
                                <tr>
                                    <td><span class="id-pill">#{{ $job->id }}</span></td>
                                    <td><span class="pos">{{ $job->position ?? 'N/A' }}</span></td>
                                    <td>{{ $job->category?->name ?? ($job->category_id ?? '—') }}</td>
                                    <td>{{ $job->advertiser?->name ?? ($job->advertiser_id ?? '—') }}</td>
                                    <td>{{ $job->location?->name ?? ($job->location_id ?? '—') }}</td>
                                    <td><small class="text-muted">{{ optional($job->created_at)->format('M d, Y · H:i') ?? '—' }}</small></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No jobs found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($recent, 'links'))
                    <div class="panel-foot">{{ $recent->links() }}</div>
                @endif
            </div>
        </div>

        <!-- Top categories & locations -->
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-tags" style="color:#0a0a0a"></i> Top Categories</h3>
                </div>
                <div class="top-list">
                    @forelse($topCategories as $cat)
                        @php
                            $count = $cat['jobs_count'] ?? ($cat['count'] ?? ($cat->count ?? 0));
                            $pct = max(6, round(($count / $maxCatCount) * 100));
                        @endphp
                        <div class="top-list-item">
                            <span class="name">{{ $cat['name'] ?? ($cat->name ?? 'N/A') }}</span>
                            <span class="count-pill">{{ number_format($count) }}</span>
                            <div class="bar"><span style="width: {{ $pct }}%"></span></div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">No data</div>
                    @endforelse
                </div>
                <div class="panel-foot">
                    <a href="{{ route('admin.categories.index') }}" class="btn-soft">Manage Categories <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-geo-alt" style="color:#0a0a0a"></i> Top Locations</h3>
                </div>
                <div class="top-list">
                    @forelse($topLocations as $loc)
                        @php
                            $count = $loc['jobs_count'] ?? ($loc['count'] ?? ($loc->count ?? 0));
                            $pct = max(6, round(($count / $maxLocCount) * 100));
                        @endphp
                        <div class="top-list-item">
                            <span class="name">{{ $loc['name'] ?? ($loc->name ?? 'N/A') }}</span>
                            <span class="count-pill blue">{{ number_format($count) }}</span>
                            <div class="bar blue"><span style="width: {{ $pct }}%"></span></div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">No data</div>
                    @endforelse
                </div>
                <div class="panel-foot">
                    <a href="{{ route('admin.locations.index') }}" class="btn-soft">Manage Locations <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.getElementById('btn-delete-all')?.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('This will permanently delete ALL jobs, categories, employers and locations and cannot be undone. Continue?')) {
                return;
            }
            const v = prompt('Type DELETE to confirm irreversible deletion:');
            if (v !== 'DELETE') {
                alert('Confirmation not matched. Aborting.');
                return;
            }
            const input = document.querySelector('#delete-all-form input[name="confirmation"]');
            if (input) input.value = 'DELETE';
            document.getElementById('delete-all-form').submit();
        });
    </script>
@endpush
