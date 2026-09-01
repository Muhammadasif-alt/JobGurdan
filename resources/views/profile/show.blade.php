@php
    // Resolve dashboard layout based on logged-in user's role so the profile
    // edit page lives inside the same chrome (sidebar, header) as the rest of
    // their dashboard, instead of Jetstream's bare layout.
    $user   = auth()->user();
    $layout = match (true) {
        $user?->isAdmin()      => 'admin.layouts.app',
        $user?->isCompany()    => 'company.layouts.app',
        $user?->isJobSeeker()  => 'seeker.layouts.app',
        default                => 'company.layouts.app',
    };

    $errors        = $errors ?? new \Illuminate\Support\ViewErrorBag;
    $profileErrors = $errors->getBag('updateProfileInformation');
    $passwordErrors= $errors->getBag('updatePassword');

    $initials = collect(preg_split('/\s+/', trim($user->name ?? '')))->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

    $sizes = ['', '1-10 employees', '11-50 employees', '51-200 employees', '201-500 employees', '501-1000 employees', '1000+ employees'];
@endphp

@extends($layout)

@section('content')
<style>
    .pf-wrap   { padding: 24px; max-width: 1100px; }
    .pf-head   { margin-bottom: 22px; }
    .pf-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg,#0a0a0a,#404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .pf-head .sub { font-size: 14px; color: #6b7280; margin-top: 4px; }

    .pf-card {
        background: #fff; border: 1px solid #ececec; border-radius: 16px;
        padding: 28px 28px; margin-bottom: 22px;
        box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .pf-card-head { display: flex; align-items: center; gap: 14px; margin-bottom: 22px; padding-bottom: 18px; border-bottom: 1px solid #f1f1f4; }
    .pf-card-head .ico {
        width: 44px; height: 44px; border-radius: 12px; background: #f5f5f7;
        display: inline-flex; align-items: center; justify-content: center;
        color: #0a0a0a; font-size: 18px;
    }
    .pf-card-head h2 { font-size: 17px; font-weight: 700; margin: 0; color: #0a0a0a; }
    .pf-card-head p  { font-size: 13px; color: #6b7280; margin: 2px 0 0; }

    .pf-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px 22px; }
    @media (max-width: 768px) { .pf-grid { grid-template-columns: 1fr; } }
    .pf-grid .full { grid-column: 1 / -1; }

    .pf-label { font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; text-transform: uppercase; letter-spacing: .4px; }
    .pf-input, .pf-textarea, .pf-select {
        width: 100%; padding: 11px 14px;
        background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 14px; color: #0a0a0a;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .pf-input:focus, .pf-textarea:focus, .pf-select:focus {
        outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.08);
    }
    .pf-textarea { min-height: 110px; resize: vertical; line-height: 1.5; }
    .pf-help     { font-size: 12px; color: #6b7280; margin-top: 6px; }
    .pf-error    { font-size: 12px; color: #b91c1c; margin-top: 6px; }

    .pf-photo-row { display: flex; align-items: center; gap: 18px; margin-bottom: 22px; }
    .pf-photo {
        width: 78px; height: 78px; border-radius: 50%; overflow: hidden;
        background: linear-gradient(135deg,#0a0a0a,#404040);
        color: #fff; display: inline-flex; align-items: center; justify-content: center;
        font-size: 26px; font-weight: 700; flex-shrink: 0;
    }
    .pf-photo img { width: 100%; height: 100%; object-fit: cover; }
    .pf-photo-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    .pf-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px; border-radius: 10px;
        font-size: 13.5px; font-weight: 600; cursor: pointer;
        border: 1px solid transparent; transition: all .15s ease;
        text-decoration: none;
    }
    .pf-btn-primary  { background: #0a0a0a; border-color: #0a0a0a; color: #fff !important; }
    .pf-btn-primary:hover  { background: #1a1a1a; transform: translateY(-1px); box-shadow: 0 8px 16px rgba(10,10,10,.18); }
    .pf-btn-ghost    { background: #fff; border-color: #e5e7eb; color: #0a0a0a; }
    .pf-btn-ghost:hover    { background: #f9fafb; border-color: #d1d5db; }
    .pf-btn-danger   { background: #fff; border-color: #fecaca; color: #b91c1c; }
    .pf-btn-danger:hover   { background: #fef2f2; border-color: #fca5a5; }

    .pf-card-foot { display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 18px; padding-top: 18px; border-top: 1px solid #f1f1f4; }
    .pf-saved {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 12px; border-radius: 999px;
        background: #ecfdf5; color: #047857;
        font-size: 12px; font-weight: 600;
    }

    .pf-danger-zone {
        border: 1px solid #fecaca; background: #fff7f7;
    }
    .pf-danger-zone .pf-card-head .ico { background: #fee2e2; color: #b91c1c; }
    .pf-danger-zone h2 { color: #b91c1c; }
</style>

<main class="app-main"><div class="app-content"><div class="pf-wrap">

    {{-- Header --}}
    <div class="pf-head">
        <h1>My Profile</h1>
        <div class="sub">Manage your account information, password and security.</div>
    </div>

    {{-- =========================================================
         PROFILE INFORMATION
       ========================================================= --}}
    <div class="pf-card">
        <div class="pf-card-head">
            <span class="ico"><i class="bi bi-person-circle"></i></span>
            <div>
                <h2>Profile Information</h2>
                <p>Update your account's profile information, email and contact details.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Profile Photo --}}
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="pf-photo-row">
                <div class="pf-photo">
                    @if ($user->profile_photo_path)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                    @else
                        {{ $initials ?: 'U' }}
                    @endif
                </div>
                <div>
                    <div style="font-weight:600;color:#0a0a0a;margin-bottom:6px;">Profile photo</div>
                    <div class="pf-photo-actions">
                        <input type="file" name="photo" id="pf-photo-input" accept="image/png,image/jpeg" style="display:none;">
                        <label for="pf-photo-input" class="pf-btn pf-btn-ghost" style="cursor:pointer;">
                            <i class="bi bi-image"></i> Upload new
                        </label>
                    </div>
                    <div class="pf-help">JPG or PNG, max 1 MB. Square images look best.</div>
                    @if ($profileErrors->has('photo'))
                        <div class="pf-error">{{ $profileErrors->first('photo') }}</div>
                    @endif
                </div>
            </div>
            @endif

            <div class="pf-grid">
                <div>
                    <label class="pf-label" for="pf-name">Full Name</label>
                    <input id="pf-name" type="text" name="name" class="pf-input" value="{{ old('name', $user->name) }}" required>
                    @if ($profileErrors->has('name')) <div class="pf-error">{{ $profileErrors->first('name') }}</div> @endif
                </div>
                <div>
                    <label class="pf-label" for="pf-email">Email Address</label>
                    <input id="pf-email" type="email" name="email" class="pf-input" value="{{ old('email', $user->email) }}" required>
                    @if ($profileErrors->has('email')) <div class="pf-error">{{ $profileErrors->first('email') }}</div> @endif
                </div>
                <div>
                    <label class="pf-label" for="pf-phone">Phone</label>
                    <input id="pf-phone" type="text" name="phone" class="pf-input" value="{{ old('phone', $user->phone) }}" placeholder="+1 555 123 4567">
                    @if ($profileErrors->has('phone')) <div class="pf-error">{{ $profileErrors->first('phone') }}</div> @endif
                </div>
                <div></div>
            </div>

            {{-- Company-only fields --}}
            @if ($user->isCompany())
            <div style="margin-top:30px;padding-top:24px;border-top:1px dashed #ececec;">
                <h3 style="font-size:15px;font-weight:700;margin:0 0 4px;color:#0a0a0a;">Company Details</h3>
                <p style="font-size:13px;color:#6b7280;margin:0 0 18px;">Help job seekers learn more about your company.</p>

                <div class="pf-grid">
                    <div>
                        <label class="pf-label" for="pf-website">Website</label>
                        <input id="pf-website" type="url" name="website" class="pf-input" value="{{ old('website', $user->website) }}" placeholder="https://yourcompany.com">
                        @if ($profileErrors->has('website')) <div class="pf-error">{{ $profileErrors->first('website') }}</div> @endif
                    </div>
                    <div>
                        <label class="pf-label" for="pf-headline">Tagline / Headline</label>
                        <input id="pf-headline" type="text" name="headline" class="pf-input" value="{{ old('headline', $user->headline) }}" placeholder="e.g. The leading startup hiring platform">
                        @if ($profileErrors->has('headline')) <div class="pf-error">{{ $profileErrors->first('headline') }}</div> @endif
                    </div>
                    <div class="full">
                        <label class="pf-label" for="pf-bio">About the Company</label>
                        <textarea id="pf-bio" name="bio" class="pf-textarea" placeholder="Tell candidates about your company, culture and mission...">{{ old('bio', $user->bio) }}</textarea>
                        @if ($profileErrors->has('bio')) <div class="pf-error">{{ $profileErrors->first('bio') }}</div> @endif
                    </div>
                    <div>
                        <label class="pf-label" for="pf-address">Address</label>
                        <input id="pf-address" type="text" name="address" class="pf-input" value="{{ old('address', $user->address) }}" placeholder="City, State or full address">
                        @if ($profileErrors->has('address')) <div class="pf-error">{{ $profileErrors->first('address') }}</div> @endif
                    </div>
                    <div>
                        <label class="pf-label" for="pf-size">Company Size</label>
                        <select id="pf-size" name="company_size" class="pf-select">
                            @foreach ($sizes as $s)
                                <option value="{{ $s }}" @selected(old('company_size', $user->company_size) === $s)>{{ $s ?: '— Select —' }}</option>
                            @endforeach
                        </select>
                        @if ($profileErrors->has('company_size')) <div class="pf-error">{{ $profileErrors->first('company_size') }}</div> @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="pf-card-foot">
                @if (session('status') === 'profile-information-updated')
                    <span class="pf-saved"><i class="bi bi-check-circle-fill"></i> Saved</span>
                @endif
                <button type="submit" class="pf-btn pf-btn-primary">
                    <i class="bi bi-check2"></i> Save changes
                </button>
            </div>
        </form>
    </div>

    {{-- =========================================================
         UPDATE PASSWORD
       ========================================================= --}}
    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
    <div class="pf-card">
        <div class="pf-card-head">
            <span class="ico"><i class="bi bi-shield-lock"></i></span>
            <div>
                <h2>Update Password</h2>
                <p>Use a strong, unique password to keep your account secure.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('user-password.update') }}">
            @csrf
            @method('PUT')

            <div class="pf-grid">
                <div class="full">
                    <label class="pf-label" for="pf-cur">Current Password</label>
                    <input id="pf-cur" type="password" name="current_password" class="pf-input" autocomplete="current-password">
                    @if ($passwordErrors->has('current_password')) <div class="pf-error">{{ $passwordErrors->first('current_password') }}</div> @endif
                </div>
                <div>
                    <label class="pf-label" for="pf-new">New Password</label>
                    <input id="pf-new" type="password" name="password" class="pf-input" autocomplete="new-password">
                    @if ($passwordErrors->has('password')) <div class="pf-error">{{ $passwordErrors->first('password') }}</div> @endif
                </div>
                <div>
                    <label class="pf-label" for="pf-conf">Confirm Password</label>
                    <input id="pf-conf" type="password" name="password_confirmation" class="pf-input" autocomplete="new-password">
                </div>
            </div>

            <div class="pf-card-foot">
                @if (session('status') === 'password-updated')
                    <span class="pf-saved"><i class="bi bi-check-circle-fill"></i> Password updated</span>
                @endif
                <button type="submit" class="pf-btn pf-btn-primary">
                    <i class="bi bi-shield-check"></i> Update password
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- =========================================================
         TWO-FACTOR + SESSIONS + DELETE (delegated to Jetstream Livewire)
         These are kept Livewire-driven; styles inherited from layout.
       ========================================================= --}}
    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
    <div class="pf-card">
        <div class="pf-card-head">
            <span class="ico"><i class="bi bi-phone"></i></span>
            <div>
                <h2>Two-Factor Authentication</h2>
                <p>Add an extra layer of security to your account.</p>
            </div>
        </div>
        @livewire('profile.two-factor-authentication-form')
    </div>
    @endif

    <div class="pf-card">
        <div class="pf-card-head">
            <span class="ico"><i class="bi bi-box-arrow-right"></i></span>
            <div>
                <h2>Browser Sessions</h2>
                <p>Manage and log out your active sessions on other browsers and devices.</p>
            </div>
        </div>
        @livewire('profile.logout-other-browser-sessions-form')
    </div>

    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
    <div class="pf-card pf-danger-zone">
        <div class="pf-card-head">
            <span class="ico"><i class="bi bi-exclamation-triangle"></i></span>
            <div>
                <h2>Delete Account</h2>
                <p>Permanently delete your account. This action cannot be undone.</p>
            </div>
        </div>
        @livewire('profile.delete-user-form')
    </div>
    @endif

</div></div></main>

<script>
    // Auto-submit photo when picked (small UX win)
    document.getElementById('pf-photo-input')?.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            this.closest('form').submit();
        }
    });
</script>
@endsection
