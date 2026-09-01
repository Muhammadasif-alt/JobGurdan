@extends('admin.layouts.app')

@section('content')
<style>
    /* === Employers Management custom styles === */
    .emp-wrap { padding: 24px; }
    .emp-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 22px;
    }
    .emp-head h1 {
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
    .emp-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .emp-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .emp-head .breadcrumbs a:hover { text-decoration: underline; }

    /* Stat cards */
    .emp-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }
    @media (max-width: 767px) { .emp-stats { grid-template-columns: 1fr; } }
    .stat-card {
        position: relative;
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 14px;
        padding: 20px 22px;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
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
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(15,23,42,.08); }
    .stat-card:hover::before { transform: scaleX(1); }
    .stat-card .icon-wrap {
        width: 42px; height: 42px;
        border-radius: 11px;
        background: #0a0a0a;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 10px;
    }
    .stat-card .label {
        font-size: 12px; color: #6b7280; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1px; margin: 0;
    }
    .stat-card .value {
        font-size: 28px; font-weight: 800; color: #0f172a;
        line-height: 1.1; margin: 4px 0 0; letter-spacing: -.4px;
    }

    /* Panel */
    .panel {
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 16px;
        overflow: hidden;
    }
    .panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        border-bottom: 1px solid #eef0f4;
        flex-wrap: wrap;
        gap: 12px;
    }
    .panel-head h3 {
        font-size: 19px; font-weight: 700; color: #0f172a;
        margin: 0; display: inline-flex; align-items: center; gap: 8px;
    }
    .panel-head h3 .badge-soft {
        background: #0a0a0a; color: #fff;
        font-weight: 700; font-size: 13.5px;
        padding: 4px 11px; border-radius: 999px;
    }
    .panel-actions { display: inline-flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    /* Search box */
    .search-box {
        position: relative;
        display: inline-flex;
        align-items: center;
    }
    .search-box i {
        position: absolute;
        left: 14px;
        color: #6b7280;
        font-size: 16px;
        pointer-events: none;
    }
    .search-box input {
        height: 42px;
        padding: 0 14px 0 40px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        color: #0f172a;
        background: #fff;
        min-width: 240px;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .search-box input:focus {
        border-color: #0a0a0a;
        box-shadow: 0 0 0 3px rgba(10,10,10,.10);
    }
    .search-box .clear-btn {
        position: absolute;
        right: 8px;
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 6px;
        display: inline-flex;
        align-items: center;
    }
    .search-box .clear-btn:hover { color: #0a0a0a; }

    /* Table */
    .panel .table { margin: 0; }
    .panel .table thead th {
        font-size: 13px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .8px;
        color: #6b7280; background: #f9fafb;
        padding: 16px 18px; border-bottom: 1px solid #eef0f4;
        border-top: none; white-space: nowrap;
    }
    .panel .table tbody td {
        font-size: 15px; color: #374151;
        padding: 16px 18px; border-top: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .panel .table tbody tr:hover td { background: #fafbff; }

    /* Employer cell with avatar */
    .emp-cell { display: flex; align-items: center; gap: 12px; min-width: 200px; }
    .emp-avatar {
        width: 44px; height: 44px;
        border-radius: 11px;
        background: #0a0a0a;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        flex-shrink: 0;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(10,10,10,.18);
    }
    .emp-info { min-width: 0; }
    .emp-name {
        font-weight: 700; color: #0f172a;
        font-size: 15px; line-height: 1.3;
    }
    .emp-meta {
        font-size: 12.5px; color: #6b7280;
        margin-top: 2px;
    }

    .badge-pill {
        display: inline-block;
        padding: 5px 12px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 999px;
        background: #f3f4f6;
        color: #0a0a0a;
        white-space: nowrap;
    }
    .badge-pill.dark { background: #0a0a0a; color: #fff; }
    .badge-pill.gray { background: #f3f4f6; color: #6b7280; }

    .ref-mono {
        font-family: ui-monospace, "SF Mono", Menlo, monospace;
        font-size: 12.5px;
        color: #4b5563;
        background: #f3f4f6;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* Action buttons */
    .row-actions { display: inline-flex; gap: 6px; }
    .row-actions a, .row-actions button {
        width: 38px; height: 38px;
        border-radius: 8px;
        font-size: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #6b7280;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none;
        padding: 0;
    }
    .row-actions a:hover, .row-actions button:hover { transform: translateY(-1px); }
    .row-actions .a-view:hover   { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-edit:hover   { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .row-actions form { display: inline; margin: 0; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 70px 20px;
    }
    .empty-state i { font-size: 56px; color: #d1d5db; }
    .empty-state h4 { color: #0f172a; font-weight: 700; margin: 14px 0 6px; font-size: 18px; }
    .empty-state p { color: #6b7280; margin: 0 0 22px; font-size: 14.5px; }

    /* Pagination wrapper */
    .panel-foot {
        padding: 18px 22px;
        border-top: 1px solid #eef0f4;
        background: #fbfbfd;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    /* Custom alert */
    .custom-alert {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
    }
    .custom-alert.success { background: #f3f4f6; color: #0a0a0a; border: 1px solid #e5e7eb; }
    .custom-alert button { background: transparent; border: none; color: inherit; opacity: .6; }
    .custom-alert button:hover { opacity: 1; }

    @media (max-width: 575px) {
        .emp-wrap { padding: 16px; }
        .panel-head { flex-direction: column; align-items: stretch; }
        .panel-actions { justify-content: stretch; }
        .search-box, .search-box input { width: 100%; }
    }
</style>

<div class="emp-wrap">
    {{-- Flash --}}
    @if (session('success'))
        <div class="custom-alert success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="emp-head">
        <div>
            <h1>Employers</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Employers</span>
            </div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="emp-stats">
        <div class="stat-card">
            <div class="icon-wrap"><i class="bi bi-building"></i></div>
            <p class="label">Total Employers</p>
            <h3 class="value">{{ number_format($stats['total'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="icon-wrap"><i class="bi bi-briefcase-fill"></i></div>
            <p class="label">Hiring Now</p>
            <h3 class="value">{{ number_format($stats['with_jobs'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="icon-wrap"><i class="bi bi-bar-chart-line"></i></div>
            <p class="label">Total Jobs Posted</p>
            <h3 class="value">{{ number_format($stats['total_jobs'] ?? 0) }}</h3>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-building" style="color:#0a0a0a"></i>
                All Employers
                @if(method_exists($advertisers, 'total'))
                    <span class="badge-soft">{{ number_format($advertisers->total()) }}</span>
                @endif
            </h3>
            <div class="panel-actions">
                <form method="GET" action="{{ route('admin.advertisers.index') }}" class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search employers…" autocomplete="off">
                    @if(!empty($search))
                        <a href="{{ route('admin.advertisers.index') }}" class="clear-btn" title="Clear search">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </form>
                <a href="{{ route('admin.advertisers.create') }}" class="btn-soft primary">
                    <i class="bi bi-plus-lg"></i> Add Employer
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employer</th>
                        <th>Type</th>
                        <th>Jobs Posted</th>
                        <th>Sender Ref</th>
                        <th>Display Ref</th>
                        <th>Created</th>
                        <th style="width: 130px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advertisers ?? [] as $adv)
                        @php
                            $initials = collect(preg_split('/\s+/', trim($adv->name ?? 'NA')))
                                ->filter()->take(2)
                                ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                ->implode('');
                            $jobCount = $adv->jobs_count ?? 0;
                        @endphp
                        <tr>
                            <td>
                                <div class="emp-cell">
                                    <div class="emp-avatar"@if($adv->logo) style="background:#fff;"@endif>
                                        @if($adv->logo)
                                            <img src="{{ asset('public/storage/' . $adv->logo) }}" alt="{{ $adv->name }}" style="width:100%; height:100%; object-fit:contain; padding:4px;">
                                        @else
                                            {{ $initials ?: 'E' }}
                                        @endif
                                    </div>
                                    <div class="emp-info">
                                        <div class="emp-name">{{ $adv->name ?? 'Unnamed Employer' }}</div>
                                        <div class="emp-meta">ID #{{ $adv->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($adv->type)
                                    <span class="badge-pill gray">{{ $adv->type }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($jobCount > 0)
                                    <span class="badge-pill dark">{{ number_format($jobCount) }} {{ $jobCount === 1 ? 'job' : 'jobs' }}</span>
                                @else
                                    <span class="badge-pill gray">No jobs</span>
                                @endif
                            </td>
                            <td>
                                @if($adv->sender_reference)
                                    <span class="ref-mono">{{ $adv->sender_reference }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($adv->display_reference)
                                    <span class="ref-mono">{{ $adv->display_reference }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ optional($adv->created_at)->format('M d, Y') ?? '—' }}</small></td>
                            <td style="text-align: right;">
                                <div class="row-actions">
                                    <a href="{{ route('admin.advertisers.show', $adv) }}" class="a-view" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.advertisers.edit', $adv) }}" class="a-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.advertisers.destroy', $adv) }}" method="POST"
                                          onsubmit="return confirm('Delete this employer? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="a-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-building"></i>
                                    @if(!empty($search))
                                        <h4>No employers match "{{ $search }}"</h4>
                                        <p>Try a different search term, or clear the filter to see everyone.</p>
                                        <a href="{{ route('admin.advertisers.index') }}" class="btn-soft">
                                            <i class="bi bi-arrow-counterclockwise"></i> Clear Search
                                        </a>
                                    @else
                                        <h4>No employers yet</h4>
                                        <p>Start by adding your first employer — once added, they can post jobs to the platform.</p>
                                        <a href="{{ route('admin.advertisers.create') }}" class="btn-soft primary">
                                            <i class="bi bi-plus-lg"></i> Add First Employer
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($advertisers) && method_exists($advertisers, 'links') && $advertisers->hasPages())
            <div class="panel-foot">
                {{ $advertisers->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
