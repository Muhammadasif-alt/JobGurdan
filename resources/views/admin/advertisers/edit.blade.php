@extends('admin.layouts.app')

@section('content')
<style>
    /* === Edit Employer page styles === */
    .emp-form-wrap { padding: 24px; max-width: 980px; }
    .emp-form-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 22px;
    }
    .emp-form-head h1 {
        font-size: 26px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .emp-form-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .emp-form-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .emp-form-head .breadcrumbs a:hover { text-decoration: underline; }

    .form-grid {
        display: grid;
        grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px;
        align-items: start;
    }
    @media (max-width: 1099px) { .form-grid { grid-template-columns: 1fr; } }

    .panel {
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 22px;
    }
    .panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid #eef0f4;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .panel-head h3 {
        font-size: 16.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-body { padding: 24px; }

    .field { margin-bottom: 20px; }
    .field:last-child { margin-bottom: 0; }
    .field label {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }
    .field label .req { color: #dc2626; }
    .field label .hint { font-weight: 500; color: #9ca3af; font-size: 12px; margin-left: 6px; }
    .field .help-text { font-size: 12.5px; color: #6b7280; margin-top: 6px; }
    .field input[type="text"], .field input[type="url"], .field select, .field textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 14.5px;
        font-family: inherit;
        color: #0f172a;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .field input:focus, .field select:focus, .field textarea:focus {
        outline: none;
        border-color: #0a0a0a;
        box-shadow: 0 0 0 3px rgba(10,10,10,.10);
    }
    .field input.is-invalid, .field textarea.is-invalid { border-color: #dc2626; }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; display: block; }
    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 575px) { .row-2 { grid-template-columns: 1fr; } }

    .form-foot {
        padding: 16px 22px;
        background: #fafbff;
        border-top: 1px solid #eef0f4;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn {
        padding: 11px 22px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14.5px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        white-space: nowrap;
    }
    .btn-primary {
        background: #0a0a0a !important;
        color: #fff !important;
        border: 1px solid #0a0a0a !important;
        box-shadow: 0 6px 14px rgba(10,10,10,.18) !important;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        background: #1a1a1a !important;
        box-shadow: 0 10px 22px rgba(10,10,10,.28) !important;
        color: #fff !important;
    }
    .btn-outline { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; color: #111827; }
    .btn-danger-outline { background: #fff !important; color: #dc2626 !important; border: 1px solid #fee2e2 !important; }
    .btn-danger-outline:hover { background: #fef2f2 !important; color: #b91c1c !important; }

    /* Side meta card */
    .meta-card {
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 22px;
    }
    .meta-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 14px;
        text-transform: uppercase;
        letter-spacing: .8px;
    }
    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid #f3f4f6;
        font-size: 13.5px;
    }
    .meta-row:first-of-type { border-top: none; }
    .meta-row .lbl { color: #6b7280; }
    .meta-row .val { color: #0f172a; font-weight: 600; }

    .info-card {
        background: #0a0a0a;
        color: #fff;
        border-radius: 16px;
        padding: 22px;
        position: relative;
        overflow: hidden;
    }
    .info-card::before {
        content: "";
        position: absolute;
        right: -60px; top: -60px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.32), transparent 70%);
        pointer-events: none;
    }
    .info-card > * { position: relative; z-index: 1; }
    .info-card .eyebrow {
        display: inline-block;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.4px;
        padding: 5px 12px;
        border-radius: 999px;
        margin-bottom: 12px;
    }
    .info-card h4 { font-size: 16px; font-weight: 700; margin: 0 0 8px; }
    .info-card p { font-size: 13px; color: rgba(255,255,255,.78); line-height: 1.6; margin: 0; }

    .alert-box {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-size: 13.5px;
    }
    .alert-box.danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-box ul { margin: 8px 0 0 18px; }
</style>

<div class="emp-form-wrap">
    @if ($errors->any())
        <div class="alert-box danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="emp-form-head">
        <div>
            <h1>Edit Employer</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.advertisers.index') }}">Employers</a>
                <span class="mx-1">/</span>
                <span>{{ $advertiser->name ?? 'Edit' }}</span>
            </div>
        </div>
        <a href="{{ route('admin.advertisers.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back to Employers
        </a>
    </div>

    <form action="{{ route('admin.advertisers.update', $advertiser) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div>
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-building"></i> Employer Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="field">
                            <label for="name">
                                Employer Name <span class="req">*</span>
                                <span class="hint">— company or organisation name</span>
                            </label>
                            <input id="name" type="text" name="name" value="{{ old('name', $advertiser->name) }}"
                                   class="@error('name') is-invalid @enderror"
                                   placeholder="e.g. Acme Logistics, Sheetz Inc, Aramark"
                                   required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label for="type">
                                Employer Type
                                <span class="hint">— optional category</span>
                            </label>
                            <input id="type" type="text" name="type" value="{{ old('type', $advertiser->type) }}"
                                   class="@error('type') is-invalid @enderror"
                                   placeholder="e.g. Direct Employer, Staffing Agency">
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label for="logo">
                                Company Logo
                                <span class="hint">— PNG / JPG / WebP / SVG, max 2MB</span>
                            </label>
                            @if($advertiser->logo)
                                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px; padding: 12px; background: #fafbff; border: 1px solid #eef0f4; border-radius: 10px;">
                                    <img src="{{ asset('public/storage/' . $advertiser->logo) }}"
                                         alt="Current logo"
                                         style="width: 64px; height: 64px; object-fit: contain; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px;">
                                    <div style="flex: 1;">
                                        <div style="font-size: 13px; color: #374151; font-weight: 600;">Current logo</div>
                                        <label style="display: inline-flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 12.5px; color: #b91c1c; cursor: pointer;">
                                            <input type="checkbox" name="remove_logo" value="1" style="margin: 0;">
                                            Remove this logo
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input id="logo" type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                   class="@error('logo') is-invalid @enderror">
                            <p class="help-text">
                                @if($advertiser->logo)
                                    Upload a new file to replace the current logo, or check "Remove" above.
                                @else
                                    Square logos work best (e.g. 200×200). Will appear next to every job from this employer.
                                @endif
                            </p>
                            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-tags"></i> Reference Codes <span style="font-size:12px; color:#6b7280; font-weight:500; margin-left:6px;">(optional)</span></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row-2">
                            <div class="field" style="margin: 0;">
                                <label for="sender_reference">Sender Reference</label>
                                <input id="sender_reference" type="text" name="sender_reference" value="{{ old('sender_reference', $advertiser->sender_reference) }}"
                                       class="@error('sender_reference') is-invalid @enderror"
                                       placeholder="e.g. acme-logistics">
                                @error('sender_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <p class="help-text">Used internally to identify the source feed.</p>
                            </div>
                            <div class="field" style="margin: 0;">
                                <label for="display_reference">Display Reference</label>
                                <input id="display_reference" type="text" name="display_reference" value="{{ old('display_reference', $advertiser->display_reference) }}"
                                       class="@error('display_reference') is-invalid @enderror"
                                       placeholder="e.g. ACME-2026">
                                @error('display_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <p class="help-text">Visible reference shown alongside listings.</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-foot">
                        <div style="display:inline-flex; gap:10px; flex-wrap:wrap;">
                            <a href="{{ route('admin.advertisers.index') }}" class="btn btn-outline">
                                <i class="bi bi-x-lg"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2"></i> Update Employer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <aside>
                <div class="meta-card">
                    <h4>Employer Info</h4>
                    <div class="meta-row">
                        <span class="lbl">ID</span>
                        <span class="val">#{{ $advertiser->id }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="lbl">Total Jobs</span>
                        <span class="val">{{ number_format($advertiser->jobs()->count()) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="lbl">Created</span>
                        <span class="val">{{ optional($advertiser->created_at)->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="lbl">Last Updated</span>
                        <span class="val">{{ optional($advertiser->updated_at)->format('M d, Y') ?? '—' }}</span>
                    </div>
                </div>

                <div class="info-card">
                    <span class="eyebrow"><i class="bi bi-info-circle"></i> Heads up</span>
                    <h4>Editing won't affect existing job links</h4>
                    <p>Changes apply to all jobs already attached to this employer. Renaming will update the employer name shown on every job listing.</p>
                </div>
            </aside>
        </div>
    </form>

    {{-- Danger zone --}}
    <div class="panel" style="margin-top: 22px;">
        <div class="panel-head">
            <h3 style="color: #b91c1c;"><i class="bi bi-exclamation-triangle" style="color: #b91c1c !important;"></i> Danger Zone</h3>
        </div>
        <div class="panel-body" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
            <div>
                <div style="font-weight:700; color:#0f172a; font-size:14.5px; margin-bottom:4px;">Delete this employer</div>
                <div style="font-size:13px; color:#6b7280; max-width:520px;">Once deleted, this employer and all their job postings will be permanently removed. This cannot be undone.</div>
            </div>
            <form action="{{ route('admin.advertisers.destroy', $advertiser) }}" method="POST"
                  onsubmit="return confirm('Permanently delete this employer? All their job posts will also be deleted. This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger-outline">
                    <i class="bi bi-trash"></i> Delete Employer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
