@extends('admin.layouts.app')

@section('content')
<style>
    /* === Contact Messages styles === */
    .msg-wrap { padding: 24px; }
    .msg-head {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .msg-head h1 {
        font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .msg-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .msg-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .msg-head .breadcrumbs a:hover { text-decoration: underline; }

    .msg-stats {
        display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 22px;
    }
    @media (max-width: 1199px) { .msg-stats { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 767px)  { .msg-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { .msg-stats { grid-template-columns: 1fr; } }
    .stat-card.spam .icon-wrap { background: linear-gradient(135deg, #5e2bff 0%, #ff5722 100%); }
    .spam-badge { display:inline-flex; align-items:center; gap:4px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; font-weight:700; font-size:11px; padding:2px 8px; border-radius:999px; }
    .spam-badge.legit { background:#ecfdf5; color:#047857; border-color:#d1fae5; }
    .spam-badge.suspicious { background:#fef3c7; color:#92400e; border-color:#fde68a; }
    .ai-scan-btn { display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%); color:#fff; border:none; border-radius:10px; padding:9px 14px; font-size:13.5px; font-weight:700; cursor:pointer; box-shadow:0 4px 10px rgba(94,43,255,.25); }
    .ai-scan-btn:hover { transform: translateY(-1px); box-shadow:0 6px 14px rgba(94,43,255,.35); }
    .stat-card {
        position: relative; background: #fff; border: 1px solid #eef0f4;
        border-radius: 14px; padding: 20px 22px; overflow: hidden;
        cursor: pointer;
        text-decoration: none !important;
        display: block;
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
    .stat-card.new .icon-wrap { background: #dc2626; }

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
    .panel .table tbody tr.unread { background: #fafbff; }
    .panel .table tbody tr.unread td { font-weight: 600; }
    .panel .table tbody tr:hover td { background: #f8faff; }

    .from-cell { display: flex; align-items: center; gap: 12px; min-width: 220px; }
    .from-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14px;
        flex-shrink: 0;
    }
    .from-info { min-width: 0; }
    .from-name { font-weight: 700; color: #0f172a; font-size: 14.5px; line-height: 1.3; }
    .from-email { font-size: 12.5px; color: #6b7280; margin-top: 2px; }

    .subject-cell {
        font-weight: 600; color: #0f172a;
        max-width: 360px;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    }

    .badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; font-size: 12.5px; font-weight: 700; border-radius: 999px; white-space: nowrap; }
    .badge-pill.new     { background: #dc2626; color: #fff; }
    .badge-pill.read    { background: #f3f4f6; color: #6b7280; }
    .badge-pill.replied { background: #0a0a0a; color: #fff; }
    .badge-pill::before {
        content: ""; width: 6px; height: 6px; border-radius: 50%;
        background: currentColor; opacity: .9;
    }

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
        .msg-wrap { padding: 16px; }
        .panel-head { flex-direction: column; align-items: stretch; }
        .panel-actions { justify-content: stretch; }
        .search-box, .search-box input { width: 100%; }
    }
</style>

<div class="msg-wrap">
    @if (session('success'))
        <div class="custom-alert success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="msg-head">
        <div>
            <h1>Contact Messages</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Contact Messages</span>
            </div>
        </div>
    </div>

    {{-- Stat cards (clickable status filters) --}}
    <div class="msg-stats">
        <a href="{{ route('admin.contact-messages.index') }}" class="stat-card {{ empty($status) && empty($spam) ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-envelope"></i></div>
            <p class="label">Total Messages</p>
            <h3 class="value">{{ number_format($stats['total'] ?? 0) }}</h3>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" class="stat-card new {{ ($status ?? '') === 'new' ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-bell-fill"></i></div>
            <p class="label">New / Unread</p>
            <h3 class="value">{{ number_format($stats['new'] ?? 0) }}</h3>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="stat-card {{ ($status ?? '') === 'read' ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-envelope-open"></i></div>
            <p class="label">Read</p>
            <h3 class="value">{{ number_format($stats['read'] ?? 0) }}</h3>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'replied']) }}" class="stat-card {{ ($status ?? '') === 'replied' ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-reply-fill"></i></div>
            <p class="label">Replied</p>
            <h3 class="value">{{ number_format($stats['replied'] ?? 0) }}</h3>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['spam' => 'yes']) }}" class="stat-card spam {{ ($spam ?? '') === 'yes' ? 'active' : '' }}">
            <div class="icon-wrap"><i class="bi bi-shield-exclamation"></i></div>
            <p class="label">AI: Spam</p>
            <h3 class="value">{{ number_format($stats['spam'] ?? 0) }}</h3>
        </a>
    </div>

    {{-- AI Scan + Delete-all-spam toolbar --}}
    @if(($stats['total'] ?? 0) > 0)
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px;align-items:center;">
            <form method="POST" action="{{ route('admin.contact-messages.scan-spam') }}" style="display:inline;">
                @csrf
                <button type="submit" class="ai-scan-btn" title="Score unscored messages with AI (max 50 at a time)">
                    <i class="bi bi-stars"></i> Run AI Spam Scan
                </button>
            </form>
            @if(($stats['spam'] ?? 0) > 0)
                <button type="button" class="bulk-btn danger" data-scope="all_spam"
                        style="display:inline-flex;align-items:center;gap:6px;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:10px;padding:9px 14px;font-size:13.5px;font-weight:700;cursor:pointer;">
                    <i class="bi bi-trash"></i> Delete All Spam ({{ $stats['spam'] }})
                </button>
            @endif
            <span style="color:#6b7280;font-size:13px;">AI scans up to 50 unscored messages per click.</span>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-envelope" style="color:#0a0a0a"></i>
                @if($status === 'new')      New Messages
                @elseif($status === 'read')    Read Messages
                @elseif($status === 'replied') Replied Messages
                @else                       All Messages
                @endif
                @if(method_exists($messages, 'total'))
                    <span class="badge-soft">{{ number_format($messages->total()) }}</span>
                @endif
            </h3>
            <div class="panel-actions">
                <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="search-box">
                    @if(!empty($status))<input type="hidden" name="status" value="{{ $status }}">@endif
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search by name, email, subject…" autocomplete="off">
                    @if(!empty($search))
                        <a href="{{ route('admin.contact-messages.index', ['status' => $status]) }}" class="clear-btn" title="Clear search"><i class="bi bi-x-circle-fill"></i></a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Hidden bulk-action forms (out-of-flow so they don't nest with per-row delete forms) --}}
        @if($messages->count())
            <form id="bulkSelectedForm" action="{{ route('admin.contact-messages.bulk-destroy') }}" method="POST" style="display:none;">
                @csrf
                <div id="bulkSelectedIds"></div>
            </form>
            <form id="bulkScopeForm" action="{{ route('admin.contact-messages.bulk-destroy') }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="scope" id="bulkScopeInput" value="">
            </form>

            {{-- Bulk action toolbar --}}
            <div class="bulk-toolbar">
                <span class="bulk-count" id="bulkCount">0 selected</span>
                <button type="button" class="bulk-btn danger" id="bulkDeleteBtn" disabled>
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
                <button type="button" class="bulk-btn warn" data-scope="all_new">
                    <i class="bi bi-envelope-x"></i> Delete All Unread
                </button>
                <button type="button" class="bulk-btn danger-outline" data-scope="all">
                    <i class="bi bi-trash3"></i> Delete All
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 38px;">
                            <input type="checkbox" id="checkAll" class="row-check" aria-label="Select all">
                        </th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th style="width: 100px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $m)
                        @php
                            $name = trim(($m->first_name ?? '') . ' ' . ($m->last_name ?? '')) ?: ($m->full_name ?? '—');
                            $initials = collect(preg_split('/\s+/', $name))->filter()->take(2)
                                ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                            $isNew = ($m->status ?? '') === 'new';
                        @endphp
                        <tr class="{{ $isNew ? 'unread' : '' }}">
                            <td>
                                <input type="checkbox" class="row-check msg-check" name="ids[]" value="{{ $m->id }}" aria-label="Select message">
                            </td>
                            <td>
                                <div class="from-cell">
                                    <div class="from-avatar">{{ $initials ?: 'M' }}</div>
                                    <div class="from-info">
                                        <div class="from-name">{{ $name }}</div>
                                        <div class="from-email">{{ $m->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.contact-messages.show', $m) }}" style="text-decoration:none; color:inherit;">
                                    <div class="subject-cell">{{ $m->subject ?? '(no subject)' }}</div>
                                </a>
                                @if(!is_null($m->spam_score))
                                    @php
                                        $sc = (int) $m->spam_score;
                                        $cls = $sc >= 60 ? 'spam-badge' : ($sc >= 31 ? 'spam-badge suspicious' : 'spam-badge legit');
                                        $lbl = $sc >= 60 ? 'Spam' : ($sc >= 31 ? 'Suspicious' : 'Legit');
                                    @endphp
                                    <span class="{{ $cls }}" title="{{ $m->spam_reason ?: '' }}" style="margin-top:4px;">
                                        <i class="bi bi-stars"></i> {{ $lbl }} · {{ $sc }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($m->status === 'new')
                                    <span class="badge-pill new">New</span>
                                @elseif($m->status === 'read')
                                    <span class="badge-pill read">Read</span>
                                @else
                                    <span class="badge-pill replied">Replied</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ optional($m->created_at)->format('M d, Y') }}</small>
                                <br>
                                <small class="text-muted">{{ optional($m->created_at)->format('H:i') }}</small>
                            </td>
                            <td style="text-align: right;">
                                <div class="row-actions">
                                    <a href="{{ route('admin.contact-messages.show', $m) }}" class="a-view" title="View"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('admin.contact-messages.destroy', $m) }}" method="POST"
                                          onsubmit="return confirm('Delete this message? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="a-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-envelope"></i>
                                    @if(!empty($search))
                                        <h4>No messages match "{{ $search }}"</h4>
                                        <p>Try a different search term, or clear the filter to see all messages.</p>
                                        <a href="{{ route('admin.contact-messages.index') }}" class="btn-soft"><i class="bi bi-arrow-counterclockwise"></i> Clear Filters</a>
                                    @elseif(!empty($status))
                                        <h4>No {{ $status }} messages</h4>
                                        <p>There are no messages with this status right now.</p>
                                        <a href="{{ route('admin.contact-messages.index') }}" class="btn-soft"><i class="bi bi-arrow-counterclockwise"></i> View All</a>
                                    @else
                                        <h4>No messages yet</h4>
                                        <p>When visitors submit the contact form, their messages will appear here.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($messages) && method_exists($messages, 'links') && $messages->hasPages())
            <div class="panel-foot">
                {{ $messages->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* === Bulk action toolbar === */
    .bulk-toolbar {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 22px; border-bottom: 1px solid #eef0f4;
        background: #fafbff; flex-wrap: wrap;
    }
    .bulk-count {
        font-size: 13px; font-weight: 700; color: #0a0a0a;
        margin-right: auto;
    }
    .bulk-btn {
        display: inline-flex; align-items: center; gap: 6px;
        height: 36px; padding: 0 14px;
        border-radius: 8px; border: 1px solid transparent;
        font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all .15s ease;
        background: #fff; color: #374151;
    }
    .bulk-btn:disabled { opacity: .45; cursor: not-allowed; }
    .bulk-btn.warn { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
    .bulk-btn.warn:hover:not(:disabled) { background: #c2410c; color: #fff; }
    .bulk-btn.danger { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
    .bulk-btn.danger:hover:not(:disabled) { background: #b91c1c; color: #fff; }
    .bulk-btn.danger-outline { background: #fff; color: #b91c1c; border-color: #fecaca; }
    .bulk-btn.danger-outline:hover { background: #fef2f2; }

    .row-check {
        width: 16px; height: 16px;
        cursor: pointer;
        accent-color: #0a0a0a;
    }
</style>

<script>
    (function () {
        const checkAll = document.getElementById('checkAll');
        const rows     = document.querySelectorAll('.msg-check');
        const count    = document.getElementById('bulkCount');
        const btn      = document.getElementById('bulkDeleteBtn');
        const idsBox   = document.getElementById('bulkSelectedIds');
        const selForm  = document.getElementById('bulkSelectedForm');
        const scopeForm  = document.getElementById('bulkScopeForm');
        const scopeInput = document.getElementById('bulkScopeInput');
        if (!checkAll || !rows.length) return;

        function refresh() {
            const selected = document.querySelectorAll('.msg-check:checked').length;
            if (count) count.textContent = selected + ' selected';
            if (btn) btn.disabled = selected === 0;
            checkAll.checked = selected === rows.length && rows.length > 0;
            checkAll.indeterminate = selected > 0 && selected < rows.length;
        }

        checkAll.addEventListener('change', () => {
            rows.forEach(cb => { cb.checked = checkAll.checked; });
            refresh();
        });
        rows.forEach(cb => cb.addEventListener('change', refresh));
        refresh();

        // Delete Selected — collect checked IDs and submit
        if (btn && idsBox && selForm) {
            btn.addEventListener('click', () => {
                const ids = Array.from(document.querySelectorAll('.msg-check:checked')).map(cb => cb.value);
                if (!ids.length) return;
                if (!confirm('Delete ' + ids.length + ' selected message(s)? This cannot be undone.')) return;
                idsBox.innerHTML = ids.map(id => `<input type="hidden" name="ids[]" value="${id}">`).join('');
                selForm.submit();
            });
        }

        // Delete All Unread / Delete All / Delete All Spam
        document.querySelectorAll('[data-scope]').forEach(b => {
            b.addEventListener('click', () => {
                const scope = b.dataset.scope;
                let msg;
                if (scope === 'all') {
                    msg = 'PERMANENTLY delete ALL messages (read & replied too)? This cannot be undone.';
                } else if (scope === 'all_spam') {
                    msg = 'Delete ALL messages flagged as spam by AI? This cannot be undone.';
                } else {
                    msg = 'Delete ALL unread messages? Most spam is in here.';
                }
                if (!confirm(msg)) return;
                scopeInput.value = scope;
                scopeForm.submit();
            });
        });
    })();
</script>

@endsection
