<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | Jobs in USA</title>
    <meta name="robots" content="noindex">
    <link rel="icon" href="{{ asset('public/user/images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8faff;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        .auth-shell {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* === LEFT: Visual === */
        /* Matches homepage modern-cta-card: brand-black + orange/violet blur blobs */
        .auth-visual {
            position: relative;
            background: #0a0a0a;
            color: #fff;
            padding: 56px 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }
        .auth-visual::before, .auth-visual::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .35;
            pointer-events: none;
        }
        .auth-visual::before { width: 380px; height: 380px; background: #ff5722; top: -120px; right: -100px; }
        .auth-visual::after  { width: 340px; height: 340px; background: #5e2bff; bottom: -120px; left: -100px; }

        .auth-visual .auth-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 20px;
            position: relative;
            z-index: 2;
        }
        .auth-visual .auth-brand img { width: 36px; height: 36px; object-fit: contain; background: #fff; padding: 4px; border-radius: 8px; }

        .auth-hero {
            position: relative;
            z-index: 2;
            max-width: 460px;
        }
        .auth-hero .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.20);
            color: rgba(255,255,255,.92);
            font-size: 12px;
            font-weight: 700;
            padding: 7px 16px;
            border-radius: 999px;
            backdrop-filter: blur(8px);
            margin-bottom: 22px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }
        .auth-hero .eyebrow .dot {
            width: 7px; height: 7px;
            background: #34d399;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(52,211,153,.7);
            animation: dotPulse 1.6s infinite;
        }
        @keyframes dotPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(52,211,153,.7); }
            70%      { box-shadow: 0 0 0 8px rgba(52,211,153,0); }
        }
        .auth-hero h1 {
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -.5px;
            margin: 0 0 16px;
        }
        .auth-hero h1 .accent {
            background: linear-gradient(90deg, #ffd54f, #ff7043);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        .auth-hero p {
            color: rgba(255,255,255,.85);
            font-size: 15px;
            line-height: 1.65;
            margin: 0 0 26px;
        }
        .auth-trust {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }
        .auth-trust li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,.85);
            font-size: 14px;
            font-weight: 500;
        }
        .auth-trust li i { color: #ff7043; font-size: 18px; }

        .auth-stats {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 40px;
            border-top: 1px solid rgba(255,255,255,.15);
            padding-top: 22px;
            flex-wrap: wrap;
        }
        .auth-stats .stat strong {
            display: block;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }
        .auth-stats .stat span {
            font-size: 12px;
            color: rgba(255,255,255,.65);
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        /* === RIGHT: Form === */
        .auth-form-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: #f8faff;
        }
        .auth-form {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border: 1px solid #eef0f4;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 24px 56px rgba(15, 23, 42, .06);
        }
        .auth-form-head { text-align: center; margin-bottom: 28px; }
        .auth-form-head .head-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #5e2bff;
            background: #f3efff;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }
        .auth-form-head h2 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.4px;
            margin: 0 0 6px;
        }
        .auth-form-head p {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }

        .field-group { margin-bottom: 18px; }
        .field-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap input {
            width: 100%;
            height: 48px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0 44px 0 44px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .input-wrap input:focus {
            outline: none;
            border-color: #0a0a0a;
            box-shadow: 0 0 0 3px rgba(10, 10, 10, .10);
        }
        .input-wrap .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
            pointer-events: none;
        }
        .input-wrap .toggle-pass {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 18px;
            padding: 4px;
            border-radius: 6px;
            transition: color .15s ease, background .15s ease;
        }
        .input-wrap .toggle-pass:hover { color: #0a0a0a; background: #f3f4f6; }
        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
            display: block;
        }

        .row-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin: -4px 0 22px;
        }
        .row-between label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            cursor: pointer;
        }
        .row-between label input { accent-color: #0a0a0a; }
        .row-between a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
        .row-between a:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            height: 52px;
            background: #0a0a0a;
            border: 1px solid #0a0a0a;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit i { transition: transform .2s ease; }
        .btn-submit:hover {
            transform: translateY(-1px);
            background: #1a1a1a;
            box-shadow: 0 14px 28px rgba(10, 10, 10, .25);
        }
        .btn-submit:hover i { transform: translateX(3px); }

        .alt-link {
            text-align: center;
            margin-top: 22px;
            color: #6b7280;
            font-size: 14px;
        }
        .alt-link a { color: #0a0a0a; font-weight: 700; text-decoration: none; }
        .alt-link a:hover { text-decoration: underline; }

        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .alert-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

        @media (max-width: 991px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-visual { display: none; }
            .auth-form-wrap { padding: 24px 16px; min-height: 100vh; }
            .auth-form { padding: 28px 22px; }
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <!-- LEFT: Visual side -->
    <aside class="auth-visual">
        <a href="{{ url('/') }}" class="auth-brand">
            <img src="{{ asset('public/user/images/Jobs in USA.png') }}" alt="Jobs in USA" onerror="this.style.display='none'">
            Jobs in USA
        </a>

        <div class="auth-hero">
            <span class="eyebrow"><span class="dot"></span> Welcome back</span>
            <h1>Sign in to continue your <span class="accent">job search</span></h1>
            <p>Access your saved jobs, applications, and personalized recommendations across all 50 U.S. states &mdash; all in one place.</p>
            <ul class="auth-trust">
                <li><i class="bi bi-check-circle-fill"></i> 230,000+ verified job listings</li>
                <li><i class="bi bi-check-circle-fill"></i> Track applications in one dashboard</li>
                <li><i class="bi bi-check-circle-fill"></i> Personalized job recommendations</li>
                <li><i class="bi bi-check-circle-fill"></i> Trusted by top U.S. employers</li>
            </ul>
        </div>

        <div class="auth-stats">
            <div class="stat"><strong>230K+</strong><span>Open Jobs</span></div>
            <div class="stat"><strong>10K+</strong><span>Employers</span></div>
            <div class="stat"><strong>50+</strong><span>States</span></div>
        </div>
    </aside>

    <!-- RIGHT: Form side -->
    <main class="auth-form-wrap">
        <div class="auth-form">
            <div class="auth-form-head">
                <span class="head-badge"><i class="bi bi-shield-check"></i> Secure Login</span>
                <h2>Sign in to your account</h2>
                <p>Enter your email or username to continue</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    <label for="email">Email or Username</label>
                    <div class="input-wrap">
                        <i class="bi bi-person input-icon"></i>
                        <input id="email" name="email" type="text"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="field-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock input-icon"></i>
                        <input id="password" name="password" type="password"
                               placeholder="Enter your password"
                               required autocomplete="current-password">
                        <button type="button" class="toggle-pass" data-target="password" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="row-between">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">
                    Sign In <i class="bi bi-arrow-right"></i>
                </button>

                <p class="alt-link">
                    Don't have an account?
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Create one free</a>
                    @endif
                </p>
            </form>
        </div>
    </main>
</div>

<script>
    document.querySelectorAll('.toggle-pass').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            const icon  = btn.querySelector('i');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash');
                btn.setAttribute('aria-label', 'Hide password');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye');
                btn.setAttribute('aria-label', 'Show password');
            }
        });
    });
</script>
</body>
</html>
