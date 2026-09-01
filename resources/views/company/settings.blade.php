@extends('company.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">

@include('_partials.settings-styles')

<div class="st-wrap">
    <div class="st-head">
        <div>
            <h1>Settings</h1>
            <div class="breadcrumbs">
                <a href="{{ route('company.dashboard') }}">Dashboard</a>
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
            <form action="{{ route('company.settings.account') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-person-circle"></i> Account Information</h3>
                        <p>Your company name (as shown on listings), login email, and contact phone.</p>
                    </div>
                    <div class="panel-body">
                        @include('_partials.photo-upload', [
                            'user'        => $user,
                            'photoLabel'  => 'Company Logo',
                            'removeRoute' => 'company.settings.photo.remove',
                        ])

                        <div class="field">
                            <label>Company Name <span class="req">*</span></label>
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

            {{-- Company Details --}}
            <form action="{{ route('company.settings.details') }}" method="POST">
                @csrf @method('PUT')
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-building"></i> Company Details</h3>
                        <p>Information shown on your public profile and job listings.</p>
                    </div>
                    <div class="panel-body">
                        <div class="field">
                            <label>Tagline / Headline <span class="hint">— a one-liner about your company</span></label>
                            <input type="text" name="headline" value="{{ old('headline', $user->headline) }}"
                                   placeholder="e.g. Modern logistics partner for U.S. retailers" maxlength="191">
                        </div>

                        <div class="field">
                            <label>About the Company</label>
                            <textarea name="bio" placeholder="What does your company do? What's it like to work there?">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <div class="row-2">
                            <div class="field">
                                <label>Website</label>
                                <input type="url" name="website" value="{{ old('website', $user->website) }}" placeholder="https://yourcompany.com">
                            </div>
                            <div class="field">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="HQ city / full address">
                            </div>
                        </div>

                        <div class="field" style="margin-bottom:0;">
                            <label>Company Size</label>
                            <select name="company_size">
                                <option value="">— Select —</option>
                                @foreach(['1-10','11-50','51-200','201-500','501-1000','1000+'] as $sz)
                                    <option value="{{ $sz }}" {{ old('company_size', $user->company_size) === $sz ? 'selected' : '' }}>{{ $sz }} employees</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-foot">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save Details</button>
                    </div>
                </div>
            </form>

            {{-- Password --}}
            <form action="{{ route('company.settings.password') }}" method="POST">
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
                <span class="eyebrow"><i class="bi bi-lightbulb"></i> Profile Boost</span>
                <h4>Complete profiles get 5× more applications</h4>
                <p>Companies with a tagline, website and "About" section build trust faster — candidates apply quicker.</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i><span>Add a clear tagline that explains your business in one line</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Mention what makes your team a great place to work</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Link your website so candidates can research you</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Pick the right company size — sets candidate expectations</span></li>
                </ul>
            </div>
        </aside>
    </div>
</div>
</div></div></main>
@endsection
