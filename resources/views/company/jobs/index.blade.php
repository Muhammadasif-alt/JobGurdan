@extends('company.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">
<style>
    .cj-wrap { padding: 24px; }
    .cj-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .cj-head h1 {
        font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .cj-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .cj-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .cj-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 22px; }
    @media (max-width:767px){ .cj-stats { grid-template-columns: 1fr; } }
    .stat-card {
        background: #fff; border: 1px solid #eef0f4; border-radius: 14px;
        padding: 20px 22px; display: block; text-decoration:none !important;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(15,23,42,.08); }
    .stat-card .ic { width: 42px; height: 42px; border-radius: 11px; background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 10px; }
    .stat-card .lbl { font-size: 12px; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
    .stat-card .val { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.1; margin: 4px 0 0; letter-spacing: -.4px; }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; }
    .panel-head { display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; border-bottom: 1px solid #eef0f4; flex-wrap: wrap; gap: 12px; }
    .panel-head h3 { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head .badge-soft { background: #0a0a0a; color: #fff; font-weight: 700; font-size: 13px; padding: 4px 11px; border-radius: 999px; }
    .panel-actions { display: inline-flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .search-box { position: relative; }
    .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 16px; pointer-events: none; }
    .search-box input { height: 42px; padding: 0 14px 0 40px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 14px; min-width: 240px; outline: none; }
    .search-box input:focus { border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); }

    .btn-soft { display: inline-flex; align-items: center; gap: 6px; height: 42px; padding: 0 16px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none !important; border: 1px solid #e5e7eb; background: #fff; color: #374151 !important; transition: all .15s ease; }
    .btn-soft:hover { border-color: #0a0a0a; color: #0a0a0a !important; }
    .btn-soft.primary { background: #0a0a0a !important; color: #fff !important; border-color: #0a0a0a; box-shadow: 0 6px 14px rgba(10,10,10,.18); }
    .btn-soft.primary:hover { transform: translateY(-1px); background: #1a1a1a !important; }

    .panel .table { margin: 0; }
    .panel .table thead th { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #6b7280; background: #f9fafb; padding: 16px 18px; border-bottom: 1px solid #eef0f4; border-top:none; white-space: nowrap; }
    .panel .table tbody td { font-size: 15px; color: #374151; padding: 16px 18px; border-top: 1px solid #f3f4f6; vertical-align: middle; }
    .panel .table tbody tr:hover td { background: #fafbff; }

    .pos-cell .pos { font-weight: 700; color: #0f172a; font-size: 14.5px; }
    .pos-cell .meta { font-size: 12.5px; color: #6b7280; margin-top: 3px; display: inline-flex; gap: 14px; flex-wrap: wrap; }
    .pos-cell .meta span { display: inline-flex; align-items: center; gap: 4px; }
    .badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; font-size: 12.5px; font-weight: 700; border-radius: 999px; white-space: nowrap; }
    .badge-pill.green  { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
    .badge-pill.gray   { background: #f3f4f6; color: #6b7280; }
    .badge-pill.red    { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .row-actions { display: inline-flex; gap: 6px; }
    .row-actions a, .row-actions button {
        width: 38px; height: 38px; border-radius: 8px; font-size: 16px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
        cursor: pointer; transition: all .15s ease;
        text-decoration: none; padding: 0;
    }
    .row-actions a:hover, .row-actions button:hover { transform: translateY(-1px); }
    .row-actions .a-edit:hover { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .row-actions form { display: inline; margin: 0; }

    .empty-state { text-align: center; padding: 70px 20px; }
    .empty-state i { font-size: 56px; color: #d1d5db; }
    .empty-state h4 { color: #0f172a; font-weight: 700; margin: 14px 0 6px; font-size: 18px; }
    .empty-state p { color: #6b7280; margin: 0 0 22px; font-size: 14.5px; }

    .panel-foot { padding: 18px 22px; border-top: 1px solid #eef0f4; background: #fbfbfd; display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }

    .custom-alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .custom-alert.success { background: #f3f4f6; color: #0a0a0a; border: 1px solid #e5e7eb; }
    .custom-alert button { background: transparent; border: none; opacity: .6; }
</style>

<div class="cj-wrap">
    @if (session('success'))
        <div class="custom-alert success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="cj-head">
        <div>
            <h1>My Job Postings</h1>
            <div class="breadcrumbs">
                <a href="{{ route('company.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Jobs</span>
            </div>
        </div>
        <a href="{{ route('company.jobs.create') }}" class="btn-soft primary">
            <i class="bi bi-plus-lg"></i> Post New Job
        </a>
    </div>

    <div class="cj-stats">
        <div class="stat-card">
            <div class="ic"><i class="bi bi-briefcase"></i></div>
            <p class="lbl">Total Jobs</p>
            <h3 class="val">{{ number_format($stats['total'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="ic"><i class="bi bi-check-circle"></i></div>
            <p class="lbl">Active Listings</p>
            <h3 class="val">{{ number_format($stats['active'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <div class="ic"><i class="bi bi-graph-up-arrow"></i></div>
            <p class="lbl">Posted This Week</p>
            <h3 class="val">{{ number_format($stats['this_week'] ?? 0) }}</h3>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-briefcase-fill" style="color:#0a0a0a"></i>
                All Jobs
                @if(method_exists($jobs, 'total'))
                    <span class="badge-soft">{{ number_format($jobs->total()) }}</span>
                @endif
            </h3>
            <div class="panel-actions">
                <form method="GET" action="{{ route('company.jobs.index') }}" class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search by job title…" autocomplete="off">
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Posted</th>
                        <th style="width: 130px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td>
                                <div class="pos-cell">
                                    <div class="pos">{{ $job->position }}</div>
                                    <div class="meta">
                                        @if($job->location)
                                            <span><i class="bi bi-geo-alt"></i> {{ $job->location->name }}</span>
                                        @endif
                                        @if($job->category)
                                            <span><i class="bi bi-tag"></i> {{ $job->category->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $job->job_type ?: $job->employment_type ?: '—' }}</td>
                            <td>
                                @if($job->salary_minimum || $job->salary_maximum)
                                    {{ $job->salary_currency ?: '$' }}{{ number_format((float)$job->salary_minimum) }}
                                    @if($job->salary_maximum) – {{ $job->salary_currency ?: '$' }}{{ number_format((float)$job->salary_maximum) }} @endif
                                    <small class="text-muted d-block">{{ $job->salary_period ?: '' }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php $st = $job->status ?: 'active'; @endphp
                                @if($st === 'active')
                                    <span class="badge-pill green"><span class="dot"></span> Active</span>
                                @elseif($st === 'draft')
                                    <span class="badge-pill gray">Draft</span>
                                @else
                                    <span class="badge-pill red">Closed</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ optional($job->created_at)->diffForHumans() }}</small></td>
                            <td style="text-align: right;">
                                <div class="row-actions">
                                    <a href="{{ route('company.jobs.edit', $job) }}" class="a-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('company.jobs.destroy', $job) }}" method="POST"
                                          onsubmit="return confirm('Delete this job? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="a-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-briefcase"></i>
                                @if(!empty($search))
                                    <h4>No jobs match "{{ $search }}"</h4>
                                    <p>Try a different keyword or <a href="{{ route('company.jobs.index') }}">view all</a>.</p>
                                @else
                                    <h4>No job postings yet</h4>
                                    <p>Post your first job to start receiving applications.</p>
                                    <a href="{{ route('company.jobs.create') }}" class="btn-soft primary"><i class="bi bi-plus-lg"></i> Post First Job</a>
                                @endif
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
            <div class="panel-foot">{{ $jobs->onEachSide(1)->links() }}</div>
        @endif
    </div>
</div>
</div></div></main>
@endsection
