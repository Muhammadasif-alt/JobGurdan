@extends('admin.layouts.app')

@section('content')
<style>
    .msg-show-wrap { padding: 24px; max-width: 1080px; }
    .msg-show-head {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .msg-show-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .msg-show-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .msg-show-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .msg-show-head .breadcrumbs a:hover { text-decoration: underline; }

    .msg-grid {
        display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px; align-items: start;
    }
    @media (max-width: 1099px) { .msg-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 18px 22px; border-bottom: 1px solid #eef0f4; display: flex; align-items: center; gap: 10px; }
    .panel-head h3 { font-size: 16.5px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-body { padding: 24px; }

    /* From row */
    .from-row {
        display: flex; align-items: center; gap: 14px;
        padding-bottom: 18px; margin-bottom: 18px;
        border-bottom: 1px solid #f3f4f6;
    }
    .from-avatar {
        width: 52px; height: 52px; border-radius: 50%;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 18px;
        flex-shrink: 0;
    }
    .from-info { min-width: 0; }
    .from-name { font-weight: 700; color: #0f172a; font-size: 17px; line-height: 1.3; }
    .from-email { font-size: 13.5px; color: #6b7280; margin-top: 3px; display: inline-flex; align-items: center; gap: 6px; }
    .from-email a { color: #0a0a0a; font-weight: 600; }
    .from-meta {
        margin-top: 4px;
        font-size: 12.5px; color: #9ca3af;
        display: inline-flex; align-items: center; gap: 6px;
    }

    .subject-line {
        font-size: 18px; font-weight: 700; color: #0f172a;
        margin: 0 0 18px;
    }
    .subject-line .lbl { font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px; }

    .message-body {
        background: #f8faff;
        border: 1px solid #eef0f4;
        border-radius: 12px;
        padding: 22px 24px;
        font-size: 15px; line-height: 1.75;
        color: #1f2937;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* Side panel */
    .badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; font-size: 12.5px; font-weight: 700; border-radius: 999px; white-space: nowrap; }
    .badge-pill.new     { background: #dc2626; color: #fff; }
    .badge-pill.read    { background: #f3f4f6; color: #6b7280; }
    .badge-pill.replied { background: #0a0a0a; color: #fff; }
    .badge-pill::before {
        content: ""; width: 6px; height: 6px; border-radius: 50%;
        background: currentColor; opacity: .9;
    }

    .meta-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-top: 1px solid #f3f4f6;
        font-size: 13.5px;
    }
    .meta-row:first-of-type { border-top: none; }
    .meta-row .lbl { color: #6b7280; }
    .meta-row .val { color: #0f172a; font-weight: 600; }

    /* Status update form */
    .status-form { display: flex; gap: 8px; align-items: center; }
    .status-form select {
        flex: 1;
        height: 40px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0 12px;
        font-size: 14px;
        color: #0f172a;
        background: #fff;
        cursor: pointer;
    }
    .status-form select:focus { outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); }

    .btn {
        padding: 10px 16px; border-radius: 8px;
        font-weight: 600; font-size: 13.5px;
        border: none; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        white-space: nowrap;
    }
    .btn-primary {
        background: #0a0a0a !important; color: #fff !important;
        border: 1px solid #0a0a0a !important;
    }
    .btn-primary:hover { background: #1a1a1a !important; transform: translateY(-1px); }
    .btn-outline { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; color: #111827; }
    .btn-danger-outline { background: #fff !important; color: #dc2626 !important; border: 1px solid #fee2e2 !important; }
    .btn-danger-outline:hover { background: #fef2f2 !important; color: #b91c1c !important; }

    .action-list { display: flex; flex-direction: column; gap: 10px; }
    .action-list a, .action-list button {
        width: 100%;
        justify-content: center;
        padding: 11px 16px;
        font-size: 14px;
    }

    .quick-row {
        display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;
    }

    .custom-alert {
        padding: 12px 16px; border-radius: 12px; margin-bottom: 18px;
        display: flex; justify-content: space-between; align-items: center; gap: 10px;
        font-size: 13.5px;
    }
    .custom-alert.success { background: #f3f4f6; color: #0a0a0a; border: 1px solid #e5e7eb; }
    .custom-alert button { background: transparent; border: none; color: inherit; opacity: .6; }
    .custom-alert button:hover { opacity: 1; }
</style>

<div class="msg-show-wrap">
    @if (session('success'))
        <div class="custom-alert success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    @php
        $name = trim(($message->first_name ?? '') . ' ' . ($message->last_name ?? '')) ?: ($message->full_name ?? '—');
        $initials = collect(preg_split('/\s+/', $name))->filter()->take(2)
            ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    @endphp

    <div class="msg-show-head">
        <div>
            <h1>Message Details</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.contact-messages.index') }}">Contact Messages</a>
                <span class="mx-1">/</span>
                <span>{{ \Illuminate\Support\Str::limit($message->subject ?? 'View', 36) }}</span>
            </div>
        </div>
        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back to Inbox
        </a>
    </div>

    <div class="msg-grid">
        {{-- Main message panel --}}
        <div>
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-envelope-open"></i> Message</h3>
                </div>
                <div class="panel-body">
                    <div class="from-row">
                        <div class="from-avatar">{{ $initials ?: 'M' }}</div>
                        <div class="from-info">
                            <div class="from-name">{{ $name }}</div>
                            <div class="from-email">
                                <i class="bi bi-envelope"></i>
                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                            </div>
                            <div class="from-meta">
                                <i class="bi bi-clock"></i>
                                {{ optional($message->created_at)->format('M d, Y \a\t H:i') ?? '—' }}
                                · {{ optional($message->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <div class="subject-line">
                        <span class="lbl">Subject</span>
                        {{ $message->subject ?? '(no subject)' }}
                    </div>

                    <div class="message-body">{{ $message->message }}</div>

                    <div class="quick-row">
                        <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject ?? '') }}" class="btn btn-primary">
                            <i class="bi bi-reply-fill"></i> Reply via Email
                        </a>
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline">
                            <i class="bi bi-list-ul"></i> Back to Inbox
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar: status + actions --}}
        <aside>
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-flag"></i> Status</h3>
                </div>
                <div class="panel-body">
                    <div style="margin-bottom: 14px;">
                        @if($message->status === 'new')
                            <span class="badge-pill new">New</span>
                        @elseif($message->status === 'read')
                            <span class="badge-pill read">Read</span>
                        @else
                            <span class="badge-pill replied">Replied</span>
                        @endif
                    </div>

                    <form action="{{ route('admin.contact-messages.update', $message) }}" method="POST" class="status-form">
                        @csrf
                        @method('PUT')
                        <select name="status">
                            <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2"></i> Update
                        </button>
                    </form>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-info-circle"></i> Details</h3>
                </div>
                <div class="panel-body">
                    <div class="meta-row">
                        <span class="lbl">Message ID</span>
                        <span class="val">#{{ $message->id }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="lbl">From</span>
                        <span class="val">{{ $name }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="lbl">Email</span>
                        <span class="val" style="font-size: 12.5px;">{{ $message->email }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="lbl">Received</span>
                        <span class="val">{{ optional($message->created_at)->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="lbl">Time</span>
                        <span class="val">{{ optional($message->created_at)->format('H:i') ?? '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h3 style="color: #b91c1c;"><i class="bi bi-trash" style="color: #b91c1c !important;"></i> Danger Zone</h3>
                </div>
                <div class="panel-body">
                    <p style="font-size: 13px; color: #6b7280; margin: 0 0 14px;">Permanently delete this message. This cannot be undone.</p>
                    <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST"
                          onsubmit="return confirm('Permanently delete this message? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger-outline" style="width: 100%; justify-content: center;">
                            <i class="bi bi-trash"></i> Delete Message
                        </button>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
