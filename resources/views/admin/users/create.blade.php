@extends('admin.layouts.app')

@section('content')
<style>
    .usr-form-wrap { padding: 24px; max-width: 1080px; }
    .usr-form-head {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .usr-form-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .usr-form-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .usr-form-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .usr-form-head .breadcrumbs a:hover { text-decoration: underline; }

    .form-grid {
        display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px; align-items: start;
    }
    @media (max-width: 1099px) { .form-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 16px 22px; border-bottom: 1px solid #eef0f4; display: flex; align-items: center; gap: 10px; }
    .panel-head h3 { font-size: 16.5px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-body { padding: 20px 22px; }

    /* Section dividers — compact, hidden by default; opt-in via .show */
    .field-section { padding-top: 14px; margin-top: 14px; border-top: 1px dashed #ececec; }
    .field-section:first-child { padding-top: 0; margin-top: 0; border-top: none; }
    .section-label { display: none; } /* labels add too much vertical clutter */

    .field { margin-bottom: 14px; }
    .field:last-child { margin-bottom: 0; }
    .field label {
        display: flex; align-items: baseline; flex-wrap: wrap;
        font-size: 13.5px; font-weight: 600; color: #374151;
        margin-bottom: 6px;
    }
    .field label .req { color: #dc2626; margin-left: 4px; }
    .field label .hint { font-weight: 500; color: #9ca3af; font-size: 12px; margin-left: 8px; }
    .field .help-text { font-size: 12.5px; color: #6b7280; margin-top: 8px; line-height: 1.5; }
    .field input[type="text"],
    .field input[type="email"],
    .field input[type="password"],
    .field input[type="tel"],
    .field select {
        width: 100%; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 10px 14px; font-size: 14px; font-family: inherit;
        color: #0f172a; background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .field input::placeholder { color: #b5b5b5; }
    .field input:focus, .field select:focus { outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); }
    .field input:hover:not(:focus), .field select:hover:not(:focus) { border-color: #d4d4d8; }
    .field input.is-invalid, .field select.is-invalid { border-color: #dc2626; }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; display: block; }

    /* Input with icon prefix (email, phone) */
    .field-icon { position: relative; }
    .field-icon i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 16px; pointer-events: none; z-index: 2;
    }
    .field-icon input { padding-left: 42px !important; }

    /* Username with @ prefix */
    .field-prefix { position: relative; }
    .field-prefix .prefix {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-weight: 600; font-size: 14.5px; pointer-events: none;
        font-family: ui-monospace, Menlo, monospace;
    }
    .field-prefix input { padding-left: 32px !important; }

    .row-2 {
        display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
        margin-bottom: 14px;
    }
    .row-2 > .field { margin-bottom: 0; }
    @media (max-width: 575px) { .row-2 { grid-template-columns: 1fr; gap: 14px; } }

    /* Role pill picker — compact 4-in-a-row on wide screens */
    .role-pills { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    @media (max-width: 991px) { .role-pills { grid-template-columns: repeat(2, 1fr); } }
    .role-pills label { cursor: pointer; margin: 0; display: block; }
    .role-pills label input { display: none; }
    .role-pills .pill {
        display: flex; align-items: center; gap: 8px;
        padding: 9px 12px;
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        background: #fff; transition: all .15s ease;
    }
    .role-pills .pill .top { display: flex; align-items: center; gap: 8px; font-weight: 700; color: #0f172a; font-size: 13.5px; }
    .role-pills .pill .top i { width: 28px; height: 28px; border-radius: 7px; background: #f3f4f6; color: #0a0a0a; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
    .role-pills .pill .desc { display: none; }
    .role-pills label:hover .pill { border-color: #0a0a0a; }
    .role-pills label input:checked + .pill {
        border-color: #0a0a0a; background: #fafbff;
        box-shadow: 0 0 0 3px rgba(10,10,10,.06);
    }
    .role-pills label input:checked + .pill .top i { background: #0a0a0a; color: #fff; }

    /* Toggle switch */
    .toggle-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 14px;
        border: 1px solid #eef0f4; border-radius: 10px;
        background: #fafbff;
    }
    .toggle-row .label { font-weight: 600; color: #0f172a; font-size: 14px; }
    .toggle-row .desc  { font-size: 12.5px; color: #6b7280; margin-top: 2px; }
    .switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .switch .slider {
        position: absolute; inset: 0;
        background: #d1d5db; border-radius: 999px;
        transition: background .15s ease; cursor: pointer;
    }
    .switch .slider::before {
        content: ""; position: absolute; top: 3px; left: 3px;
        width: 18px; height: 18px; background: #fff; border-radius: 50%;
        transition: transform .15s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .switch input:checked + .slider { background: #0a0a0a; }
    .switch input:checked + .slider::before { transform: translateX(20px); }

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

    /* Password visibility toggle */
    .password-wrap { position: relative; }
    .password-wrap input { padding-right: 44px !important; }
    .password-wrap .toggle-pw {
        position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
        width: 32px; height: 32px;
        background: transparent; border: none; cursor: pointer;
        color: #9ca3af; border-radius: 6px;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .password-wrap .toggle-pw:hover { color: #0a0a0a; background: #f3f4f6; }
</style>

<div class="usr-form-wrap">
    @if ($errors->any())
        <div class="alert-box danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-box danger">{{ session('error') }}</div>
    @endif

    <div class="usr-form-head">
        <div>
            <h1>Add User</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.users.index') }}">Users</a>
                <span class="mx-1">/</span>
                <span>Add</span>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            <div>
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-person-circle"></i> Account Details</h3>
                    </div>
                    <div class="panel-body">
                        @include('_partials.photo-upload', [
                            'user'        => new \App\Models\User(),
                            'photoLabel'  => 'Profile Photo',
                        ])

                        {{-- Identity section --}}
                        <div class="field-section">
                            <p class="section-label">Identity</p>
                            <div class="row-2">
                                <div class="field">
                                    <label for="name">Full Name <span class="req">*</span></label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                                           class="@error('name') is-invalid @enderror"
                                           placeholder="e.g. Sarah Johnson" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label for="username">
                                        Username <span class="req">*</span>
                                        <span class="hint">— letters, numbers, _ and . only</span>
                                    </label>
                                    <div class="field-prefix">
                                        <span class="prefix">@</span>
                                        <input id="username" type="text" name="username" value="{{ old('username') }}"
                                               class="@error('username') is-invalid @enderror"
                                               pattern="[A-Za-z0-9_.]{3,50}"
                                               title="3-50 chars, letters/numbers/dots/underscores"
                                               placeholder="sarah_j" required>
                                    </div>
                                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Contact section --}}
                        <div class="field-section">
                            <p class="section-label">Contact</p>
                            <div class="row-2">
                                <div class="field">
                                    <label for="email">Email Address <span class="req">*</span></label>
                                    <div class="field-icon">
                                        <i class="bi bi-envelope"></i>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                                               class="@error('email') is-invalid @enderror"
                                               placeholder="user@example.com" required>
                                    </div>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label for="phone">
                                        Phone
                                        <span class="hint">— optional</span>
                                    </label>
                                    <div class="field-icon">
                                        <i class="bi bi-telephone"></i>
                                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                                               class="@error('phone') is-invalid @enderror"
                                               placeholder="+1 555-0101">
                                    </div>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Password section --}}
                        <div class="field-section">
                            <p class="section-label">Password</p>
                            <div class="row-2">
                                <div class="field">
                                    <label for="password">Password <span class="req">*</span></label>
                                    <div class="password-wrap">
                                        <input id="password" type="password" name="password"
                                               class="@error('password') is-invalid @enderror"
                                               placeholder="At least 6 characters" required minlength="6">
                                        <button type="button" class="toggle-pw" onclick="togglePassword('password', this)" title="Show/hide password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label for="password_confirmation">Confirm Password <span class="req">*</span></label>
                                    <div class="password-wrap">
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                               placeholder="Re-enter password" required minlength="6">
                                        <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', this)" title="Show/hide password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Permissions section --}}
                        <div class="field-section">
                            <div class="field">
                                <label>Role <span class="req">*</span></label>
                                <div class="role-pills">
                                    <label>
                                        <input type="radio" name="role" value="job_seeker" {{ old('role', 'job_seeker') === 'job_seeker' ? 'checked' : '' }} required>
                                        <div class="pill">
                                            <div class="top">
                                                <i class="bi bi-person-fill"></i>
                                                Job Seeker
                                            </div>
                                            <div class="desc">Browses listings, saves jobs, and applies to positions.</div>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="role" value="company" {{ old('role') === 'company' ? 'checked' : '' }}>
                                        <div class="pill">
                                            <div class="top">
                                                <i class="bi bi-building-fill"></i>
                                                Company
                                            </div>
                                            <div class="desc">Posts jobs and reviews candidate applications.</div>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
                                        <div class="pill">
                                            <div class="top">
                                                <i class="bi bi-shield-lock-fill"></i>
                                                Administrator
                                            </div>
                                            <div class="desc">Full admin access — manages everything in the panel.</div>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="role" value="user" {{ old('role') === 'user' ? 'checked' : '' }}>
                                        <div class="pill">
                                            <div class="top">
                                                <i class="bi bi-person-circle"></i>
                                                Generic User
                                            </div>
                                            <div class="desc">Legacy generic account (rarely needed for new users).</div>
                                        </div>
                                    </label>
                                </div>
                                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="field" style="margin-bottom:0;">
                                <label>Account Status</label>
                                <div class="toggle-row">
                                    <div>
                                        <div class="label">Active account</div>
                                        <div class="desc">Inactive users cannot sign in until reactivated.</div>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-foot">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Create User</button>
                    </div>
                </div>
            </div>

            <aside>
                <div class="info-card">
                    <span class="eyebrow"><i class="bi bi-info-circle"></i> Quick Tip</span>
                    <h4>Create accounts thoughtfully</h4>
                    <p>Choose the right role — admins can edit everything, users can only browse and apply. You can change roles later.</p>
                    <ul>
                        <li><i class="bi bi-check-circle"></i><span>Username must be unique and only letters, digits, dots, underscores.</span></li>
                        <li><i class="bi bi-check-circle"></i><span>Password is at least 6 characters — encourage strong combinations.</span></li>
                        <li><i class="bi bi-check-circle"></i><span>Inactive accounts are locked out of sign-in until reactivated.</span></li>
                        <li><i class="bi bi-check-circle"></i><span>Admins can post jobs, manage employers, and access this panel.</span></li>
                    </ul>
                </div>
            </aside>
        </div>
    </form>
</div>

<script>
    function togglePassword(id, btn) {
        const inp = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (!inp) return;
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            inp.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection
