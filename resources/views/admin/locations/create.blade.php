@extends('admin.layouts.app')

@section('content')
<style>
    .loc-form-wrap { padding: 24px; max-width: 980px; }
    .loc-form-head {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .loc-form-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .loc-form-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .loc-form-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .loc-form-head .breadcrumbs a:hover { text-decoration: underline; }

    .form-grid {
        display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px; align-items: start;
    }
    @media (max-width: 1099px) { .form-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 18px 22px; border-bottom: 1px solid #eef0f4; display: flex; align-items: center; gap: 10px; }
    .panel-head h3 { font-size: 16.5px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-body { padding: 24px; }

    .field { margin-bottom: 22px; }
    .field:last-child { margin-bottom: 0; }
    .field label {
        display: flex; align-items: baseline; flex-wrap: wrap;
        font-size: 13.5px; font-weight: 600; color: #374151;
        margin-bottom: 8px;
    }
    .field label .req { color: #dc2626; margin-left: 4px; }
    .field label .hint {
        font-weight: 500; color: #9ca3af; font-size: 12px;
        margin-left: 8px;
    }
    .field .help-text { font-size: 12.5px; color: #6b7280; margin-top: 8px; line-height: 1.5; }
    .field input[type="text"] {
        width: 100%; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 12px 14px; font-size: 14.5px; font-family: inherit;
        color: #0f172a; background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .field input[type="text"]::placeholder { color: #b5b5b5; }
    .field input:focus { outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); background: #fff; }
    .field input:hover:not(:focus) { border-color: #d4d4d8; }
    .field input.is-invalid { border-color: #dc2626; }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; display: block; }
    /* Two-column row of fields — uses .field children directly so spacing inherits cleanly */
    .row-2 {
        display: grid; grid-template-columns: 1fr 1fr; gap: 18px;
        margin-bottom: 22px;
    }
    .row-2 > .field { margin-bottom: 0; }
    @media (max-width: 575px) { .row-2 { grid-template-columns: 1fr; gap: 22px; } }
    /* Visual section divider inside the panel-body */
    .field-section {
        padding-top: 22px;
        margin-top: 22px;
        border-top: 1px dashed #ececec;
    }
    .field-section:first-child { padding-top: 0; margin-top: 0; border-top: none; }
    .section-label {
        font-size: 11px; font-weight: 700; color: #0a0a0a;
        text-transform: uppercase; letter-spacing: 1.4px;
        margin: 0 0 14px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-label::before {
        content: ""; width: 14px; height: 2px; background: #0a0a0a; border-radius: 2px;
    }

    .form-foot {
        padding: 16px 22px; background: #fafbff; border-top: 1px solid #eef0f4;
        display: flex; justify-content: flex-end; gap: 10px; align-items: center;
    }
    .btn {
        padding: 11px 22px; border-radius: 10px;
        font-weight: 600; font-size: 14.5px;
        border: none; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        white-space: nowrap;
    }
    .btn-primary {
        background: #0a0a0a !important; color: #fff !important;
        border: 1px solid #0a0a0a !important;
        box-shadow: 0 6px 14px rgba(10,10,10,.18) !important;
    }
    .btn-primary:hover { transform: translateY(-1px); background: #1a1a1a !important; box-shadow: 0 10px 22px rgba(10,10,10,.28) !important; color: #fff !important; }
    .btn-outline { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; color: #111827; }

    .info-card {
        background: #0a0a0a; color: #fff; border-radius: 16px;
        padding: 26px 24px; position: relative; overflow: hidden;
        margin-bottom: 22px;
    }
    .info-card::before {
        content: ""; position: absolute; right: -60px; top: -60px;
        width: 220px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.32), transparent 70%);
        pointer-events: none;
    }
    .info-card::after {
        content: ""; position: absolute; left: -60px; bottom: -60px;
        width: 200px; height: 200px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,87,34,.28), transparent 70%);
        pointer-events: none;
    }
    .info-card > * { position: relative; z-index: 1; }
    .info-card .eyebrow {
        display: inline-block; background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.4px; padding: 5px 12px; border-radius: 999px;
        margin-bottom: 14px;
    }
    .info-card h4 { font-size: 18px; font-weight: 700; margin: 0 0 10px; line-height: 1.35; }
    .info-card p { font-size: 13.5px; color: rgba(255,255,255,.78); line-height: 1.65; margin: 0 0 16px; }
    .info-card ul { list-style: none; padding: 0; margin: 0; }
    .info-card ul li {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 9px 0; font-size: 13.5px;
        color: rgba(255,255,255,.85);
        border-top: 1px solid rgba(255,255,255,.08);
    }
    .info-card ul li:first-child { border-top: none; padding-top: 0; }
    .info-card ul li i { color: #ffb866; font-size: 16px; flex-shrink: 0; margin-top: 1px; }

    .alert-box { padding: 14px 18px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; }
    .alert-box.danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-box ul { margin: 8px 0 0 18px; }
</style>

<div class="loc-form-wrap">
    @if ($errors->any())
        <div class="alert-box danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="loc-form-head">
        <div>
            <h1>Add Location</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.locations.index') }}">Locations</a>
                <span class="mx-1">/</span>
                <span>Add</span>
            </div>
        </div>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back to Locations
        </a>
    </div>

    <form action="{{ route('admin.locations.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div>
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-geo-alt"></i> Location Details</h3>
                    </div>
                    <div class="panel-body">
                        {{-- Primary section --}}
                        <div class="field-section">
                            <p class="section-label">Primary</p>
                            <div class="field">
                                <label for="name">
                                    State / City Name <span class="req">*</span>
                                    <span class="hint">— shown to candidates as the job location</span>
                                </label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}"
                                       class="@error('name') is-invalid @enderror"
                                       placeholder="e.g. Texas, California, Houston, Los Angeles" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <p class="help-text">Use the official state or city name — this appears publicly on every related job listing.</p>
                            </div>
                        </div>

                        {{-- Optional details --}}
                        <div class="field-section">
                            <p class="section-label">Additional Details <span style="color:#9ca3af; font-weight:500; font-size:11px; letter-spacing:0.4px; text-transform:none; margin-left:4px;">(optional)</span></p>

                            <div class="row-2">
                                <div class="field">
                                    <label for="area">
                                        Area / Region
                                        <span class="hint">— sub-area within the city</span>
                                    </label>
                                    <input id="area" type="text" name="area" value="{{ old('area') }}"
                                           class="@error('area') is-invalid @enderror"
                                           placeholder="e.g. Downtown, North Side, Brooklyn">
                                    @error('area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label for="country">
                                        Country
                                        <span class="hint">— defaults to USA</span>
                                    </label>
                                    <input id="country" type="text" name="country" value="{{ old('country', 'United States') }}"
                                           class="@error('country') is-invalid @enderror"
                                           placeholder="United States">
                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="field">
                                <label for="postal_code">
                                    Postal / ZIP Code
                                    <span class="hint">— enables ZIP-level filtering</span>
                                </label>
                                <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}"
                                       class="@error('postal_code') is-invalid @enderror"
                                       placeholder="e.g. 75201" style="max-width: 280px;">
                                @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <p class="help-text">Postal codes power hyper-local filtering on the public job listings page.</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-foot">
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Create Location</button>
                    </div>
                </div>
            </div>

            <aside>
                <div class="info-card">
                    <span class="eyebrow"><i class="bi bi-info-circle"></i> Quick Tip</span>
                    <h4>Locations power filtering & SEO</h4>
                    <p>Job seekers find roles by state, city and ZIP. Each location row connects to all jobs in that area.</p>
                    <ul>
                        <li><i class="bi bi-check-circle"></i><span>Use the official state or city name — appears publicly.</span></li>
                        <li><i class="bi bi-check-circle"></i><span>Area is optional — useful for big metros (Brooklyn, Bronx).</span></li>
                        <li><i class="bi bi-check-circle"></i><span>ZIP codes enable precise hyper-local filtering.</span></li>
                        <li><i class="bi bi-check-circle"></i><span>You can add multiple locations with the same state name.</span></li>
                    </ul>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection
