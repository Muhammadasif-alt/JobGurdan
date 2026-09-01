@extends('admin.layouts.app')

@section('content')
<style>
    /* === Users Management styles === */
    .usr-wrap { padding: 24px; }
    .usr-head {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .usr-head h1 {
        font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .usr-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .usr-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .usr-head .breadcrumbs a:hover { text-decoration: underline; }

    .usr-stats {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px;
    }
    @media (max-width: 991px) { .usr-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .usr-stats { grid-template-columns: 1fr; } }
    .stat-card {
        position: relative; background: #fff; border: 1px solid #eef0f4;
        border-radius: 14px; padding: 20px 22px; overflow: hidden;
        text-decoration: none !important; display: block;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .stat-card::before {
        content: ""; position: absolute; top: 0; left: 0; right: 0;
        height: 3px; background: #0a0a0a;
        transform: scaleX(0); transform-origin: left;
        transition: transform .25s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(15,23,42,.08); border-color: #0a0a0a; }
    .stat-card:hover::before { transform: scaleX(1); }
    .stat-card.active { border-color: #0a0a0a; box-shadow: 0 14px 28px rgba(10,10,10,.12); }
    .stat-card.active::before { transform: scaleX(1); }
    .stat-card .icon-wrap {
        width: 42px; height: 42px; border-radius: 11px;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 10px;
    }
    .stat-card .label { font-size: 12px; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
    .stat-card .value { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.1; margin: 4px 0 0; letter-spacing: -.4px; }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; }
    .panel-head {
        display: flex; justify-content: space-between; align-items: center;
        padding: 18px 22px; border-bottom: 1px solid #eef0f4;
        flex-wrap: wrap; gap: 12px;
    }
    .panel-head h3 { font-size: 19px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 .badge-soft { background: #0a0a0a; color: #fff; font-weight: 700; font-size: 13.5px; padding: 4px 11px; border-radius: 999px; }
    .panel-actions { display: inline-flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    .search-box { position: relative; display: inline-flex; align-items: center; }
    .search-box i { position: absolute; left: 14px; color: #6b7280; font-size: 16px; pointer-events: none; }
    .search-box input {
        height: 42px; padding: 0 14px 0 40px;
        border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 14px; color: #0f172a; background: #fff;
        min-width: 240px; outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .search-box input:focus { border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); }
    .search-box .clear-btn { position: absolute; right: 8px; background: none; border: none; color: #9ca3af; cursor: pointer; padding: 6px; display: inline-flex; align-items: center; }
    .search-box .clear-btn:hover { color: #0a0a0a; }

    .panel .table { margin: 0; }
    .panel .table thead th {
        font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px;
        color: #6b7280; background: #f9fafb;
        padding: 16px 18px; border-bottom: 1px solid #eef0f4;
        border-top: none; white-space: nowrap;
    }
    .panel .table tbody td { font-size: 15px; color: #374151; padding: 16px 18px; border-top: 1px solid #f3f4f6; vertical-align: middle; }
    .panel .table tbody tr:hover td { background: #fafbff; }

    .usr-cell { display: flex; align-items: center; gap: 12px; min-width: 220px; }
    .usr-avatar {
        width: 42px; height: 42px; border-radius: 50%;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14.5px;
        flex-shrink: 0;
    }
    .usr-info { min-width: 0; }
    .usr-name { font-weight: 700; color: #0f172a; font-size: 14.5px; line-height: 1.3; }
    .usr-username { font-size: 12.5px; color: #6b7280; margin-top: 2px; font-family: ui-monospace, Menlo, monospace; }

    .badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; font-size: 12.5px; font-weight: 700; border-radius: 999px; white-space: nowrap; }
    .badge-pill.dark   { background: #0a0a0a; color: #fff; }
    .badge-pill.gray   { background: #f3f4f6; color: #4b5563; }
    .badge-pill.green  { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
    .badge-pill.muted  { background: #f3f4f6; color: #9ca3af; }
    .badge-pill.violet { background: #f3efff; color: #5b21b6; border: 1px solid #ddd6fe; }
    .badge-pill.orange { background: #fff4ed; color: #c2410c; border: 1px solid #ffedd5; }
    .badge-pill .dot   { width: 6px; height: 6px; border-radius: 50%; background: currentColor; opacity: .85; }

    .row-actions { display: inline-flex; gap: 6px; }
    .row-actions a, .row-actions button {
        width: 38px; height: 38px; border-radius: 8px;
        font-size: 16px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
        cursor: pointer; transition: all .15s ease;
        text-decoration: none; padding: 0;
    }
    .row-actions a:hover, .row-actions button:hover { transform: translateY(-1px); }
    .row-actions .a-view:hover   { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-edit:hover   { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .row-actions form { display: inline; margin: 0; }

    .empty-state { text-align: center; padding: 70px 20px; }
    .empty-state i { font-size: 56px; color: #d1d5db; }
    .empty-state h4 { color: #0f172a; font-weight: 700; margin: 14px 0 6px; font-size: 18px; }
    .empty-state p { color: #6b7280; margin: 0 0 22px; font-size: 14.5px; }

    .panel-foot {
        padding: 18px 22px; border-top: 1px solid #eef0f4;
        background: #fbfbfd;
        display: flex; justify-content: center; align-items: center;
        flex-wrap: wrap; gap: 12px;
    }

    .custom-alert {
        padding: 12px 16px; border-radius: 12px; margin-bottom: 18px;
        display: flex; justify-content: space-between; align-items: center; gap: 10px;
        font-size: 13.5px;
    }
    .custom-alert.success { background: #f3f4f6; color: #0a0a0a; border: 1px solid #e5e7eb; }
    .custom-alert button { background: transparent; border: none; color: inherit; opacity: .6; }
    .custom-alert button:hover { opacity: 1; }

    @media (max-width: 575px) {
        .usr-wrap { padding: 16px; }
        .panel-head { flex-direction: column; align-items: stretch; }
        .panel-actions { justify-content: stretch; }
        .search-box, .search-box input { width: 100%; }
    }
</style>

<div class="usr-wrap">
    @if (session('success'))
        <div class="custom-alert success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="usr-head">
        <div>
            <h1>Users</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Users</span>
            </div>
        </div>
    </div>

    {{-- Stat cards (clickable role filters) --}}
    <div class="usr-stats">
        <a href="{{ route('admin.users.index') }}" class="stat-card {{ empty($role) ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-people-fill"></i></div>
            <p class="label">Total Users</p>
            <h3 class="value">{{ number_format($stats['total'] ?? 0) }}</h3>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'job_seeker']) }}" class="stat-card {{ ($role ?? '') === 'job_seeker' ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-person-fill"></i></div>
            <p class="label">Job Seekers</p>
            <h3 class="value">{{ number_format($stats['job_seekers'] ?? 0) }}</h3>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'company']) }}" class="stat-card {{ ($role ?? '') === 'company' ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-building-fill"></i></div>
            <p class="label">Companies</p>
            <h3 class="value">{{ number_format($stats['companies'] ?? 0) }}</h3>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="stat-card {{ ($role ?? '') === 'admin' ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-shield-lock-fill"></i></div>
            <p class="label">Administrators</p>
            <h3 class="value">{{ number_format($stats['admins'] ?? 0) }}</h3>
        </a>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-people" style="color:#0a0a0a"></i>
                @switch($role)
                    @case('admin')      Administrators @break
                    @case('company')    Companies @break
                    @case('job_seeker') Job Seekers @break
                    @case('user')       Regular Users @break
                    @default            All Users
                @endswitch
                @if(method_exists($users, 'total'))
                    <span class="badge-soft">{{ number_format($users->total()) }}</span>
                @endif
            </h3>
            <div class="panel-actions">
                <form method="GET" action="{{ route('admin.users.index') }}" class="search-box">
                    @if(!empty($role))<input type="hidden" name="role" value="{{ $role }}">@endif
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search by name, username, email…" autocomplete="off">
                    @if(!empty($search))
                        <a href="{{ route('admin.users.index', ['role' => $role]) }}" class="clear-btn" title="Clear search"><i class="bi bi-x-circle-fill"></i></a>
                    @endif
                </form>
                <a href="{{ route('admin.users.create') }}" class="btn-soft primary">
                    <i class="bi bi-plus-lg"></i> Add User
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th style="width: 130px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        @php
                            $initials = collect(preg_split('/\s+/', trim($u->name ?? 'NA')))
                                ->filter()->take(2)
                                ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                ->implode('');
                        @endphp
                        <tr>
                            <td>
                                <div class="usr-cell">
                                    <div class="usr-avatar">{{ $initials ?: 'U' }}</div>
                                    <div class="usr-info">
                                        <div class="usr-name">{{ $u->name }}</div>
                                        <div class="usr-username">@&zwj;{{ $u->username ?? 'no-username' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @switch($u->role ?? 'user')
                                    @case('admin')
                                        <span class="badge-pill dark"><i class="bi bi-shield-lock-fill" style="font-size:10px"></i> Admin</span>
                                        @break
                                    @case('company')
                                        <span class="badge-pill orange"><i class="bi bi-building-fill" style="font-size:10px"></i> Company</span>
                                        @break
                                    @case('job_seeker')
                                        <span class="badge-pill violet"><i class="bi bi-person-fill" style="font-size:10px"></i> Job Seeker</span>
                                        @break
                                    @default
                                        <span class="badge-pill gray">User</span>
                                @endswitch
                            </td>
                            <td>{{ $u->phone ?? '—' }}</td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge-pill green"><span class="dot"></span>Active</span>
                                @else
                                    <span class="badge-pill muted"><span class="dot"></span>Inactive</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ optional($u->created_at)->format('M d, Y') ?? '—' }}</small></td>
                            <td style="text-align: right;">
                                <div class="row-actions">
                                    <a href="{{ route('admin.users.show', $u) }}" class="a-view" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.users.edit', $u) }}" class="a-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST"
                                              onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="a-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @else
                                        <button class="a-delete" disabled title="Cannot delete your own account" style="opacity:.4; cursor:not-allowed;"><i class="bi bi-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    @if(!empty($search))
                                        <h4>No users match "{{ $search }}"</h4>
                                        <p>Try a different search term, or clear the filter to see everyone.</p>
                                        <a href="{{ route('admin.users.index') }}" class="btn-soft"><i class="bi bi-arrow-counterclockwise"></i> Clear Filters</a>
                                    @elseif(!empty($role))
                                        @php
                                            $roleLabels = [
                                                'admin'      => 'administrators',
                                                'company'    => 'companies',
                                                'job_seeker' => 'job seekers',
                                                'user'       => 'regular users',
                                            ];
                                        @endphp
                                        <h4>No {{ $roleLabels[$role] ?? 'users' }} yet</h4>
                                        <p>There are no users with this role right now.</p>
                                        <a href="{{ route('admin.users.index') }}" class="btn-soft"><i class="bi bi-arrow-counterclockwise"></i> View All</a>
                                    @else
                                        <h4>No users yet</h4>
                                        <p>Add your first admin or user to get started.</p>
                                        <a href="{{ route('admin.users.create') }}" class="btn-soft primary"><i class="bi bi-plus-lg"></i> Add First User</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($users) && method_exists($users, 'links') && $users->hasPages())
            <div class="panel-foot">
                {{ $users->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
