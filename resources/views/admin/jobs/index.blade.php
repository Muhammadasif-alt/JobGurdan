@extends('admin.layouts.app')

@section('content')
<style>
    /* === Jobs Management custom styles === */
    .jobs-wrap { padding: 24px; }
    .jobs-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .jobs-head h1 {
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
    .jobs-head .breadcrumbs {
        font-size: 14px;
        color: #6b7280;
        margin-top: 4px;
    }
    .jobs-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .jobs-head .breadcrumbs a:hover { text-decoration: underline; }

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
    .panel-actions { display: inline-flex; gap: 8px; flex-wrap: wrap; }
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
        white-space: nowrap;
    }
    .btn-soft:hover { background: #f3f4f6; color: #111827; }
    .btn-soft.primary { background: #0a0a0a; color: #fff; border-color: #0a0a0a; box-shadow: 0 6px 14px rgba(10,10,10,.18); }
    .btn-soft.primary:hover { color: #fff; background: #1a1a1a; border-color: #1a1a1a; transform: translateY(-1px); box-shadow: 0 10px 22px rgba(10,10,10,.28); }
    .btn-soft.success { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .btn-soft.success:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
    .btn-soft.info    { background: #fff; color: #0a0a0a; border-color: #e5e7eb; }
    .btn-soft.info:hover { background: #f3f4f6; color: #0a0a0a; }
    .btn-soft.danger  { background: #fff; color: #dc2626; border-color: #fee2e2; }
    .btn-soft.danger:hover { background: #fef2f2; }

    /* Table */
    .panel .table { margin: 0; }
    .panel .table thead th {
        font-size: 13.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #6b7280;
        background: #f9fafb;
        padding: 16px 16px;
        border-bottom: 1px solid #eef0f4;
        border-top: none;
        white-space: nowrap;
    }
    .panel .table tbody td {
        font-size: 15.5px;
        color: #374151;
        padding: 18px 16px;
        border-top: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .panel .table tbody tr:hover td { background: #fafbff; }
    .panel .table tbody td small { font-size: 14px !important; }
    .panel .table .pos {
        font-weight: 600;
        color: #0f172a;
        max-width: 280px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
        font-size: 15.5px;
    }
    .badge-pill {
        display: inline-block;
        padding: 6px 13px;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 999px;
        background: #f3f4f6;
        color: #0a0a0a;
        white-space: nowrap;
    }
    .badge-pill.green { background: #f3f4f6; color: #0a0a0a; }
    .badge-pill.gray  { background: #f3f4f6; color: #4b5563; }

    /* Action buttons */
    .row-actions { display: inline-flex; gap: 6px; }
    .row-actions a, .row-actions button {
        width: 40px; height: 40px;
        border-radius: 8px;
        font-size: 17px;
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

    /* Pagination — centered, numbered */
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
    .panel-foot .pagination {
        margin: 0;
        display: inline-flex;
        gap: 4px;
    }
    .panel-foot .pagination .page-item .page-link {
        min-width: 42px;
        height: 42px;
        padding: 0 13px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        color: #374151;
        font-size: 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        margin: 0;
        transition: all .15s ease;
    }
    .panel-foot .pagination .page-item .page-link:hover {
        background: #f3f4f6;
        color: #0a0a0a;
        border-color: #0a0a0a;
        z-index: 1;
    }
    .panel-foot .pagination .page-item.active .page-link {
        background: #0a0a0a;
        color: #fff;
        border-color: #0a0a0a;
        box-shadow: 0 6px 14px rgba(10,10,10,.20);
    }
    .panel-foot .pagination .page-item.disabled .page-link {
        background: #f9fafb;
        color: #9ca3af;
        cursor: not-allowed;
    }
    .panel-foot .pagination-info {
        font-size: 15px;
        color: #6b7280;
    }
    .panel-foot .pagination-info strong { color: #111827; }

    /* Alerts */
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
</style>

<div class="jobs-wrap">
    {{-- Flash --}}
    @if (session('success'))
        <div class="custom-alert success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="jobs-head">
        <div>
            <h1>Job Management</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Jobs</span>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-briefcase" style="color:#0a0a0a"></i>
                All Jobs
                @if(isset($jobs) && method_exists($jobs, 'total'))
                    <span class="badge-soft">{{ number_format($jobs->total()) }}</span>
                @endif
            </h3>
            <div class="panel-actions">
                <a href="{{ route('admin.jobs.export', ['type' => 'xlsx']) }}" class="btn-soft success">
                    <i class="bi bi-download"></i> Export
                </a>
                <a href="{{ route('admin.jobs.import.form') }}" class="btn-soft info">
                    <i class="bi bi-upload"></i> Import
                </a>
                <a href="{{ route('admin.jobs.create') }}" class="btn-soft primary">
                    <i class="bi bi-plus-lg"></i> Post New Job
                </a>
                <form id="delete-all-form" method="POST" action="{{ route('admin.cleanup') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="confirmation" value="">
                    <button type="button" id="btn-delete-all" class="btn-soft danger">
                        <i class="bi bi-trash"></i> Delete All
                    </button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Employer</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Area</th>
                        <th>Country</th>
                        <th>Salary</th>
                        <th>Posted</th>
                        <th style="width: 130px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs ?? [] as $job)
                        <tr>
                            <td><div class="pos">{{ $job->position ?? 'N/A' }}</div></td>
                            <td>{{ $job->advertiser?->name ?? '—' }}</td>
                            <td>
                                @if($job->advertiser?->type)
                                    <span class="badge-pill gray">{{ $job->advertiser->type }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($job->category?->name)
                                    <span class="badge-pill">{{ $job->category->name }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $job->location?->name ?? '—' }}
                                @if ($job->location?->postal_code)
                                    <br><small class="text-muted">{{ $job->location->postal_code }}</small>
                                @endif
                            </td>
                            <td>{{ $job->location?->area ?? '—' }}</td>
                            <td>{{ $job->location?->country ?? '—' }}</td>
                            <td>
                                @if ($job->salary_minimum || $job->salary_maximum)
                                    <span class="badge-pill green">
                                        @if ($job->salary_minimum && $job->salary_maximum)
                                            ${{ number_format($job->salary_minimum) }}–${{ number_format($job->salary_maximum) }}
                                        @elseif($job->salary_minimum)
                                            ${{ number_format($job->salary_minimum) }}+
                                        @else
                                            ≤ ${{ number_format($job->salary_maximum) }}
                                        @endif
                                    </span>
                                    @if ($job->salary_period)
                                        <br><small class="text-muted">{{ $job->salary_period }}</small>
                                    @endif
                                @else
                                    <small class="text-muted">Not specified</small>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $job->created_at?->format('M d, Y') ?? '—' }}</small></td>
                            <td style="text-align: right;">
                                <div class="row-actions">
                                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="a-view" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.jobs.edit', $job->id) }}" class="a-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}"
                                          onsubmit="return confirm('Delete this job? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="a-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="bi bi-briefcase"></i>
                                    <h4>No jobs found</h4>
                                    <p>Get started by posting your first job opening.</p>
                                    <a href="{{ route('admin.jobs.create') }}" class="btn-soft primary">
                                        <i class="bi bi-plus-lg"></i> Post Your First Job
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($jobs) && method_exists($jobs, 'links') && $jobs->hasPages())
            <div class="panel-foot">
                <div class="pagination-info">
                    Showing <strong>{{ $jobs->firstItem() }}–{{ $jobs->lastItem() }}</strong>
                    of <strong>{{ number_format($jobs->total()) }}</strong> jobs
                </div>
                {{ $jobs->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('btn-delete-all')?.addEventListener('click', function (e) {
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
@endsection
