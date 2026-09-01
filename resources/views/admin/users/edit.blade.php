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

    .field-section { padding-top: 14px; margin-top: 14px; border-top: 1px dashed #ececec; }
    .field-section:first-child { padding-top: 0; margin-top: 0; border-top: none; }
    .section-label { display: none; }

    .field { margin-bottom: 14px; }
    .field:last-child { margin-bottom: 0; }
    .field label {
        display: flex; align-items: baseline; flex-wrap: wrap;
        font-size: 13.5px; font-weight: 600; color: #374151;
        margin-bottom: 6px;
    }
    .field label .req { color: #dc2626; margin-left: 4px; }
    .field label .hint { font-weight: 500; color: #9ca3af; font-size: 12px; margin-left: 8px; }
    .field .help-text { font-size: 12.5px; color: #6b7280; margin-top: 6px; line-height: 1.5; }
    .field input[type="text"], .field input[type="email"], .field input[type="password"], .field input[type="tel"], .field select {
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

    .field-icon { position: relative; }
    .field-icon i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 16px; pointer-events: none; z-index: 2; }
    .field-icon input { padding-left: 42px !important; }

    .field-prefix { position: relative; }
    .field-prefix .prefix {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-weight: 600; font-size: 14.5px; pointer-events: none;
        font-family: ui-monospace, Menlo, monospace;
    }
    .field-prefix input { padding-left: 32px !important; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
    .row-2 > .field { margin-bottom: 0; }
    @media (max-width: 575px) { .row-2 { grid-template-columns: 1fr; gap: 14px; } }

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
    .role-pills label input:checked + .pill { border-color: #0a0a0a; background: #fafbff; box-shadow: 0 0 0 3px rgba(10,10,10,.06); }
    .role-pills label input:checked + .pill .top i { background: #0a0a0a; color: #fff; }

    .toggle-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 14px;
        border: 1px solid #eef0f4; border-radius: 10px; background: #fafbff;
    }
    .toggle-row .label { font-weight: 600; color: #0f172a; font-size: 14px; }
    .toggle-row .desc  { font-size: 12.5px; color: #6b7280; margin-top: 2px; }
    .switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .switch .slider { position: absolute; inset: 0; background: #d1d5db; border-radius: 999px; transition: background .15s ease; cursor: pointer; }
    .switch .slider::before { content: ""; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; background: #fff; border-radius: 50%; transition: transform .15s ease; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
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
    .btn-primary { background: #0a0a0a !important; color: #fff !important; border: 1px solid #0a0a0a !important; box-shadow: 0 6px 14px rgba(10,10,10,.18) !important; }
    .btn-primary:hover { transform: translateY(-1px); background: #1a1a1a !important; box-shadow: 0 10px 22px rgba(10,10,10,.28) !important; color: #fff !important; }
    .btn-outline { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; color: #111827; }
    .btn-danger-outline { background: #fff !important; color: #dc2626 !important; border: 1px solid #fee2e2 !important; }
    .btn-danger-outline:hover { background: #fef2f2 !important; color: #b91c1c !important; }

    .meta-card { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; padding: 22px; margin-bottom: 22px; }
    .meta-card-head { display: flex; align-items: center; gap: 12px; padding-bottom: 16px; margin-bottom: 12px; border-bottom: 1px solid #f3f4f6; }
    .meta-avatar { width: 52px; height: 52px; border-radius: 50%; background: #0a0a0a; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px; flex-shrink: 0; }
    .meta-name { font-weight: 700; color: #0f172a; font-size: 15px; line-height: 1.3; }
    .meta-email { font-size: 12.5px; color: #6b7280; margin-top: 2px; word-break: break-all; }
    .meta-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-top: 1px solid #f3f4f6; font-size: 13.5px; }
    .meta-row:first-of-type { border-top: none; }
    .meta-row .lbl { color: #6b7280; }
    .meta-row .val { color: #0f172a; font-weight: 600; }

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

    .alert-box { padding: 14px 18px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; }
    .alert-box.danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-box ul { margin: 8px 0 0 18px; }
</style>

@php
    $initials = collect(preg_split('/\s+/', trim($user->name ?? 'NA')))
        ->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
@endphp

<div class="usr-form-wrap">
    @if ($errors->any())
        <div class="alert-box danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="usr-form-head">
        <div>
            <h1>Edit User</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.users.index') }}">Users</a>
                <span class="mx-1">/</span>
                <span>{{ $user->name }}</span>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div>
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-person-circle"></i> Account Details</h3>
                    </div>
                    <div class="panel-body">
                        @include('_partials.photo-upload', [
                            'user'       => $user,
                            'photoLabel' => 'Profile Photo',
                            'removeUrl'  => route('admin.users.photo.remove', $user),
                        ])

                        <div class="field-section">
                            <p class="section-label">Identity</p>
                            <div class="row-2">
                                <div class="field">
                                    <label for="name">Full Name <span class="req">*</span></label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                                           class="@error('name') is-invalid @enderror" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label for="username">
                                        Username <span class="req">*</span>
                                        <span class="hint">— letters, numbers, _ and . only</span>
                                    </label>
                                    <div class="field-prefix">
                                        <span class="prefix">@</span>
                                        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}"
                                               class="@error('username') is-invalid @enderror"
                                               pattern="[A-Za-z0-9_.]{3,50}"
                                               title="3-50 chars, letters/numbers/dots/underscores"
                                               required>
                                    </div>
                                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="field-section">
                            <p class="section-label">Contact</p>
                            <div class="row-2">
                                <div class="field">
                                    <label for="email">Email Address <span class="req">*</span></label>
                                    <div class="field-icon">
                                        <i class="bi bi-envelope"></i>
                                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                                               class="@error('email') is-invalid @enderror" required>
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
                                        <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                               class="@error('phone') is-invalid @enderror">
                                    </div>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="field-section">
                            <p class="section-label">Change Password <span style="color:#9ca3af; font-weight:500; font-size:11px; letter-spacing:0.4px; text-transform:none; margin-left:4px;">(leave blank to keep current)</span></p>
                            <div class="row-2">
                                <div class="field">
                                    <label for="password">New Password</label>
                                    <div class="password-wrap">
                                        <input id="password" type="password" name="password"
                                               class="@error('password') is-invalid @enderror"
                                               placeholder="At least 6 characters" minlength="6">
                                        <button type="button" class="toggle-pw" onclick="togglePassword('password', this)" title="Show/hide password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <div class="password-wrap">
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                               placeholder="Re-enter new password" minlength="6">
                                        <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', this)" title="Show/hide password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="help-text" style="margin-top:0;">Only fill in if you want to change the user's password.</p>
                        </div>

                        <div class="field-section">
                            <p class="section-label">Permissions &amp; Access</p>
                            <div class="field">
                                <label>Role <span class="req">*</span></label>
                                <div class="role-pills">
                                    <label>
                                        <input type="radio" name="role" value="job_seeker" {{ old('role', $user->role) === 'job_seeker' ? 'checked' : '' }} required>
                                        <div class="pill">
                                            <div class="top"><i class="bi bi-person-fill"></i> Job Seeker</div>
                                            <div class="desc">Browses listings, saves jobs, and applies to positions.</div>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="role" value="company" {{ old('role', $user->role) === 'company' ? 'checked' : '' }}>
                                        <div class="pill">
                                            <div class="top"><i class="bi bi-building-fill"></i> Company</div>
                                            <div class="desc">Posts jobs and reviews candidate applications.</div>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="role" value="admin" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                                        <div class="pill">
                                            <div class="top"><i class="bi bi-shield-lock-fill"></i> Administrator</div>
                                            <div class="desc">Full admin access — manages everything in the panel.</div>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="role" value="user" {{ old('role', $user->role) === 'user' ? 'checked' : '' }}>
                                        <div class="pill">
                                            <div class="top"><i class="bi bi-person-circle"></i> Generic User</div>
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
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-foot">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Update User</button>
                    </div>
                </div>
            </div>

            <aside>
                <div class="meta-card">
                    <div class="meta-card-head">
                        <div class="meta-avatar">{{ $initials ?: 'U' }}</div>
                        <div>
                            <div class="meta-name">{{ $user->name }}</div>
                            <div class="meta-email">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="meta-row"><span class="lbl">User ID</span><span class="val">#{{ $user->id }}</span></div>
                    <div class="meta-row"><span class="lbl">Username</span><span class="val">@&zwj;{{ $user->username ?? '—' }}</span></div>
                    <div class="meta-row"><span class="lbl">Role</span><span class="val text-capitalize">{{ $user->role ?? 'user' }}</span></div>
                    <div class="meta-row"><span class="lbl">Status</span><span class="val">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></div>
                    <div class="meta-row"><span class="lbl">Joined</span><span class="val">{{ optional($user->created_at)->format('M d, Y') ?? '—' }}</span></div>
                    <div class="meta-row"><span class="lbl">Last Updated</span><span class="val">{{ optional($user->updated_at)->format('M d, Y') ?? '—' }}</span></div>
                </div>
            </aside>
        </div>
    </form>

    {{-- Danger zone --}}
    @if($user->id !== auth()->id())
        <div class="panel" style="margin-top: 22px;">
            <div class="panel-head">
                <h3 style="color: #b91c1c;"><i class="bi bi-exclamation-triangle" style="color: #b91c1c !important;"></i> Danger Zone</h3>
            </div>
            <div class="panel-body" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                <div>
                    <div style="font-weight:700; color:#0f172a; font-size:14.5px; margin-bottom:4px;">Delete this user</div>
                    <div style="font-size:13px; color:#6b7280; max-width:520px;">Once deleted, this user account cannot be recovered. They'll be unable to sign in.</div>
                </div>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('Permanently delete this user? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-outline">
                        <i class="bi bi-trash"></i> Delete User
                    </button>
                </form>
            </div>
        </div>
    @endif
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
