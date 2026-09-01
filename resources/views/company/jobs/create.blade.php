@extends('company.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">

@php $job = new \App\Models\Job(); @endphp

<div class="cf-page" style="padding: 24px; max-width: 1080px;">
    <div class="cj-form-head" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
        <div>
            <h1 style="font-size:26px;font-weight:800;margin:0;letter-spacing:-.4px;background:linear-gradient(90deg,#0a0a0a,#404040);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent;">
                Post a New Job
            </h1>
            <div class="breadcrumbs" style="font-size:14px;color:#6b7280;margin-top:4px;">
                <a href="{{ route('company.dashboard') }}" style="color:#0a0a0a;text-decoration:none;font-weight:600;">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('company.jobs.index') }}" style="color:#0a0a0a;text-decoration:none;font-weight:600;">Jobs</a>
                <span class="mx-1">/</span>
                <span>Post New</span>
            </div>
        </div>
        <a href="{{ route('company.jobs.index') }}" class="btn btn-outline" style="padding:11px 22px;border-radius:10px;font-weight:600;text-decoration:none;background:#fff;color:#374151;border:1px solid #e5e7eb;display:inline-flex;align-items:center;gap:6px;">
            <i class="bi bi-arrow-left"></i> Back to Jobs
        </a>
    </div>

    @include('company.jobs._form', [
        'job'    => $job,
        'action' => route('company.jobs.store'),
        'method' => 'POST',
    ])
</div>
</div></div></main>
@endsection
