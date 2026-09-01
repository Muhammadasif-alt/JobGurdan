@extends('company.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">

@php
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
@endphp

<style>
    .cp-wrap { padding: 24px; max-width: 980px; }
    .cp-head { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom: 22px; }
    .cp-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg,#0a0a0a,#404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .cp-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .cp-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    .cp-hero {
        position: relative; background: #0a0a0a; color: #fff;
        border-radius: 22px; padding: 36px 36px 30px; overflow: hidden;
        margin-bottom: 24px;
    }
    .cp-hero::before, .cp-hero::after {
        content: ""; position: absolute; border-radius: 50%;
        filter: blur(80px); opacity: .35; pointer-events: none;
    }
    .cp-hero::before { width: 320px; height: 320px; background: #ff5722; top: -120px; right: -100px; }
    .cp-hero::after { width: 280px; height: 280px; background: #5e2bff; bottom: -100px; left: -80px; }
    .cp-hero > * { position: relative; z-index: 2; }

    .cp-hero-row { display: flex; gap: 22px; align-items: center; flex-wrap: wrap; }
    .cp-avatar {
        width: 88px; height: 88px; border-radius: 20px;
        background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18);
        color: #fff; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 32px; flex-shrink: 0;
    }
    .cp-hero h2 { font-size: 28px; font-weight: 800; margin: 0 0 6px; letter-spacing: -.5px; }
    .cp-hero .role-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18);
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.4px;
        padding: 5px 12px; border-radius: 999px;
    }
    .cp-hero p { color: rgba(255,255,255,.78); margin: 8px 0 0; font-size: 14.5px; }

    .cp-stats { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; margin-bottom: 22px; }
    @media (max-width:575px){ .cp-stats { grid-template-columns: 1fr; } }
    .stat-card { background: #fff; border: 1px solid #ececec; border-radius: 14px; padding: 18px 22px; }
    .stat-card .lbl { font-size: 12px; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
    .stat-card .val { font-size: 28px; font-weight: 800; color: #0a0a0a; line-height: 1.1; margin: 4px 0 0; }

    .panel { background: #fff; border: 1px solid #ececec; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 16px 22px; border-bottom: 1px solid #ececec; }
    .panel-head h3 { font-size: 16.5px; font-weight: 700; color: #0a0a0a; margin: 0; display: flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #5e2bff; }
    .panel-body { padding: 24px 26px; }

    .info-grid { display: grid; grid-template-columns: max-content 1fr; gap: 14px 28px; }
    .info-grid dt { font-size: 12.5px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; padding-top: 2px; }
    .info-grid dd { margin: 0; font-size: 14.5px; color: #0a0a0a; font-weight: 600; }

    .btn { padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none !important; display: inline-flex; align-items: center; gap: 6px; }
    .btn-primary { background: #0a0a0a; color: #fff !important; border: 1px solid #0a0a0a; }
    .btn-primary:hover { background: #1a1a1a; }
    .btn-outline { background: #fff; color: #374151 !important; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; }
</style>

<div class="cp-wrap">
    <div class="cp-head">
        <div>
            <h1>Company Profile</h1>
            <div class="breadcrumbs">
                <a href="{{ route('company.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Profile</span>
            </div>
        </div>
        <a href="{{ url('/user/profile') }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit Account</a>
    </div>

    {{-- Hero --}}
    <section class="cp-hero">
        <div class="cp-hero-row">
            <div class="cp-avatar">{{ $initials ?: 'CO' }}</div>
            <div>
                <span class="role-pill"><i class="bi bi-building"></i> Company Account</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $advertiser->description ?? 'Hiring on Jobs in USA — connect with verified U.S. job seekers.' }}</p>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <div class="cp-stats">
        <div class="stat-card">
            <p class="lbl">Total Jobs Posted</p>
            <h3 class="val">{{ number_format($stats['total_jobs'] ?? 0) }}</h3>
        </div>
        <div class="stat-card">
            <p class="lbl">Active Listings</p>
            <h3 class="val">{{ number_format($stats['active'] ?? 0) }}</h3>
        </div>
    </div>

    {{-- Account info --}}
    <div class="panel">
        <div class="panel-head"><h3><i class="bi bi-person-badge"></i> Account Information</h3></div>
        <div class="panel-body">
            <dl class="info-grid">
                <dt>Company Name</dt><dd>{{ $user->name }}</dd>
                <dt>Username</dt><dd>&#64;{{ $user->username ?? '—' }}</dd>
                <dt>Email</dt><dd>{{ $user->email }}</dd>
                <dt>Phone</dt><dd>{{ $user->phone ?: '—' }}</dd>
                <dt>Joined</dt><dd>{{ optional($user->created_at)->format('M d, Y') }}</dd>
                <dt>Status</dt>
                <dd>
                    @if($user->is_active)
                        <span style="background:#ecfdf5;color:#047857;border:1px solid #d1fae5;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;">Active</span>
                    @else
                        <span style="background:#f3f4f6;color:#6b7280;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;">Inactive</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="panel">
        <div class="panel-head"><h3><i class="bi bi-lightning"></i> Quick Actions</h3></div>
        <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('company.jobs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Post a New Job</a>
            <a href="{{ route('company.jobs.index') }}" class="btn btn-outline"><i class="bi bi-briefcase"></i> Manage Jobs</a>
            <a href="{{ route('job-seekers.index') }}" target="_blank" class="btn btn-outline"><i class="bi bi-people"></i> Browse Talent</a>
            <a href="{{ url('/user/profile') }}" class="btn btn-outline"><i class="bi bi-gear"></i> Account Settings</a>
        </div>
    </div>
</div>
</div></div></main>
@endsection
