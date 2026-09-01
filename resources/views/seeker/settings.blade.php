@extends('seeker.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">

@include('_partials.settings-styles')

<div class="st-wrap">
    <div class="st-head">
        <div>
            <h1>Settings</h1>
            <div class="breadcrumbs">
                <a href="{{ route('seeker.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Settings</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix:</strong>
            <ul style="margin: 6px 0 0 18px;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="st-grid">
        <div>
            {{-- Account Information --}}
            <form action="{{ route('seeker.settings.account') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-person-circle"></i> Account Information</h3>
                        <p>Your name, email and contact number.</p>
                    </div>
                    <div class="panel-body">
                        @include('_partials.photo-upload', [
                            'user'        => $user,
                            'photoLabel'  => 'Profile Photo',
                            'removeRoute' => 'seeker.settings.photo.remove',
                        ])

                        <div class="field">
                            <label>Full Name <span class="req">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>Email <span class="req">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="field">
                                <label>Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 555-0101">
                            </div>
                        </div>
                    </div>
                    <div class="form-foot">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save Changes</button>
                    </div>
                </div>
            </form>

            {{-- Password --}}
            <form action="{{ route('seeker.settings.password') }}" method="POST">
                @csrf @method('PUT')
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-shield-lock"></i> Change Password</h3>
                        <p>Use a long, random password to keep your account secure.</p>
                    </div>
                    <div class="panel-body">
                        <div class="field">
                            <label>Current Password <span class="req">*</span></label>
                            <input type="password" name="current_password" autocomplete="current-password" required>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>New Password <span class="req">*</span></label>
                                <input type="password" name="password" autocomplete="new-password" minlength="8" required>
                            </div>
                            <div class="field">
                                <label>Confirm New Password <span class="req">*</span></label>
                                <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-foot">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-shield-check"></i> Update Password</button>
                    </div>
                </div>
            </form>
        </div>

        <aside>
            <div class="info-card">
                <span class="eyebrow"><i class="bi bi-shield-check"></i> Security</span>
                <h4>Keep your account safe</h4>
                <p>A strong password and up-to-date contact info protect your applications and personal data.</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i><span>Use 8+ characters with mixed case, numbers, and symbols</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Don't reuse passwords from other sites</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Verify your email is correct so you don't miss interview invites</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Add a phone so employers can reach you fast</span></li>
                </ul>
            </div>
        </aside>
    </div>
</div>
</div></div></main>
@endsection
