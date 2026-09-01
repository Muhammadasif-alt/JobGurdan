@extends('seeker.layouts.app')
@section('title', 'My Job Alerts')

@section('content')
<style>
    .ja-wrap { max-width: 980px; margin: 0 auto; padding: 30px 20px 80px; }
    .ja-head { display: flex; justify-content: space-between; align-items: end; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
    .ja-head h1 { font-size: 28px; font-weight: 800; color: #0a0a0a; margin: 0 0 6px; }
    .ja-head p  { color: #555; font-size: 14px; margin: 0; }
    .ja-new {
        background: #0a0a0a; color: #fff !important;
        padding: 12px 22px; border-radius: 8px;
        font-weight: 700; font-size: 14px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
        transition: background .15s ease;
    }
    .ja-new:hover { background: #ff8a00; }

    .ja-list { display: grid; gap: 12px; }
    .ja-card {
        background: #fff; border: 1px solid #ececec; border-radius: 12px;
        padding: 18px 20px;
        display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: center;
    }
    @media (max-width: 640px) {
        .ja-card { grid-template-columns: 1fr; }
        .ja-card .actions { width: 100%; justify-content: stretch; }
        .ja-card .actions form, .ja-card .actions a { flex: 1; }
        .ja-card .actions button, .ja-card .actions a { width: 100%; justify-content: center; }
    }
    .ja-card .summary { font-size: 15px; color: #0a0a0a; }
    .ja-card .summary strong { font-weight: 700; }
    .ja-card .meta { color: #6b7280; font-size: 13px; margin-top: 4px; display: flex; gap: 16px; flex-wrap: wrap; }
    .ja-card .meta .badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 3px 10px; border-radius: 999px;
        font-size: 12px; font-weight: 700;
    }
    .ja-card .meta .badge.active { background: #dcfce7; color: #15803d; }
    .ja-card .meta .badge.paused { background: #fee2e2; color: #b91c1c; }

    .ja-card .actions { display: inline-flex; gap: 8px; }
    .ja-card .actions a, .ja-card .actions button {
        background: #fff; color: #0a0a0a; border: 1px solid #e5e5e7;
        padding: 8px 14px; border-radius: 8px;
        font-size: 13px; font-weight: 600;
        text-decoration: none; cursor: pointer; font-family: inherit;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .15s ease;
    }
    .ja-card .actions a:hover, .ja-card .actions button:hover { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .ja-card .actions .btn-delete:hover { background: #b91c1c; border-color: #b91c1c; color: #fff; }

    .ja-empty {
        text-align: center; padding: 60px 24px; background: #fff;
        border: 1px dashed #d1d5db; border-radius: 14px;
    }
    .ja-empty h2 { font-size: 20px; font-weight: 800; color: #0a0a0a; margin: 0 0 8px; }
    .ja-empty p  { color: #555; max-width: 460px; margin: 0 auto 20px; }

    .ja-flash {
        background: #dcfce7; color: #15803d; padding: 12px 16px;
        border-radius: 10px; font-weight: 600; font-size: 14px;
        margin-bottom: 20px;
    }
</style>

<div class="ja-wrap">
    <div class="ja-head">
        <div>
            <h1>My Job Alerts</h1>
            <p>Get notified by email when new jobs matching your interests are posted.</p>
        </div>
        <a href="{{ route('seeker.job-alerts.create') }}" class="ja-new">
            <i class="icon-feather-plus"></i> New Alert
        </a>
    </div>

    @if(session('success'))
        <div class="ja-flash">{{ session('success') }}</div>
    @endif

    @if($alerts->isEmpty())
        <div class="ja-empty">
            <h2>No job alerts yet</h2>
            <p>Subscribe to get an email when new jobs match your search — by keyword, location, or category.</p>
            <a href="{{ route('seeker.job-alerts.create') }}" class="ja-new">
                <i class="icon-feather-plus"></i> Create Your First Alert
            </a>
        </div>
    @else
        <div class="ja-list">
            @foreach($alerts as $alert)
                <div class="ja-card">
                    <div>
                        <div class="summary">
                            @if($alert->keywords)
                                "<strong>{{ $alert->keywords }}</strong>"
                            @endif
                            @if($alert->location)
                                {{ $alert->keywords ? 'in' : 'Jobs in' }} <strong>{{ $alert->location->name }}</strong>
                            @endif
                            @if($alert->category)
                                {{ ($alert->keywords || $alert->location) ? '·' : 'Category:' }} <strong>{{ $alert->category->name }}</strong>
                            @endif
                        </div>
                        <div class="meta">
                            <span class="badge {{ $alert->is_active ? 'active' : 'paused' }}">
                                {{ $alert->is_active ? '● Active' : '⏸ Paused' }}
                            </span>
                            <span>Frequency: <strong>{{ ucfirst($alert->frequency) }}</strong></span>
                            <span>Created {{ $alert->created_at->diffForHumans() }}</span>
                            @if($alert->last_sent_at)
                                <span>Last sent: {{ $alert->last_sent_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="actions">
                        <form method="POST" action="{{ route('seeker.job-alerts.toggle', $alert) }}" style="margin:0">
                            @csrf
                            <button type="submit">{{ $alert->is_active ? 'Pause' : 'Resume' }}</button>
                        </form>
                        <a href="{{ route('seeker.job-alerts.edit', $alert) }}">Edit</a>
                        <form method="POST" action="{{ route('seeker.job-alerts.destroy', $alert) }}" style="margin:0"
                              onsubmit="return confirm('Delete this alert?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">{{ $alerts->links() }}</div>
    @endif
</div>
@endsection
