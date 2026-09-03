@php
    /** Cache-busting stamp so asset changes reach browsers without a hard refresh. */
    $assetVersion = function (string $path): string {
        $file = public_path($path);

        return file_exists($file) ? (string) filemtime($file) : (string) config('app.asset_version', '1');
    };
@endphp
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from jobword.utouchdesign.com/jobword_ltr/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 06 Apr 2025 10:25:57 GMT -->

<!-- Mirrored from www.jobword.flarza.com/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Oct 2025 12:15:52 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="JobGader">
    <meta name="theme-color" content="#1b3a6b">
    <meta name="google-site-verification" content="OXrNNeaVvS-g-hiCo0sSs2paH_CgPJ_8DB04L46sKWk" />
    @php
        $metaDescription =
            trim($__env->yieldContent('meta_description')) ?:
            'Find thousands of job opportunities across the USA. Search for jobs by location, category, and experience level on JobGader - your premier job search platform.';
        $metaKeywords =
            trim($__env->yieldContent('meta_keywords')) ?:
            'jobs, job search, visa sponsorship jobs, jobs for foreigners, employment, careers, job listings, hiring, job board';
    @endphp

    <meta name="description" content="@yield('meta_description', $metaDescription)">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <meta name="google-site-verification" content="NEZhtXbrZZkQYcz5kQO1hT17Vs27bb3VYUgrjUTUeQ0">
    <meta name="keywords" content="@yield('meta_keywords', $metaKeywords)">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    @stack('meta')
    <meta property="og:title" content="@yield('og_title', 'JobGader - Find Your Dream Job Today')">
    <meta property="og:description" content="@yield('og_description', $metaDescription)">
    <meta property="og:image" content="@yield('og_image', asset('public/user/images/home-background-03.jpg'))">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jobs Portal')</title>


    <!-- Favicon. The root .ico is listed too because Google fetches
         /favicon.ico directly, not only the tagged icons. -->
    <link rel="icon" href="{{ url('favicon.ico') }}" sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('public/user/images/favicon.png') }}?v={{ $assetVersion('user/images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('public/user/images/favicon.png') }}?v={{ $assetVersion('user/images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/user/images/apple-touch-icon.png') }}?v={{ $assetVersion('user/images/apple-touch-icon.png') }}">

    <!-- DNS prefetch + preconnect for faster third-party loads -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="//www.googletagmanager.com">

    <!-- Google Fonts (single request, swap so text shows immediately) -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('public/user/css/bootstrap-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('public/user/css/icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/user/css/style.css') }}?v={{ $assetVersion('user/css/style.css') }}">
    <style>
        @media (min-width: 1200px) {
            .container { max-width: 1800px !important; }
        }
        /* Prevent horizontal scroll on mobile */
        html, body { overflow-x: hidden; max-width: 100%; }

        /* === Unified hero layout (size, position, overlay) — image is per-page below === */
        body .intro-banner.intro-hero-v2,
        body .about-hero,
        body .contact-hero,
        body .jobs-hero,
        body .cat-hero,
        body .loc-hero,
        body .companies-hero,
        body .blog-hero,
        body .blog-detail-hero,
        body .jd-hero,
        body .js-hero,
        body .seeker-hero,
        body .utf-page-heading-area {
            background-color: transparent !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            position: relative !important;
            isolation: isolate;
        }

        /* === Per-page hero background images ===
           WebP with PNG/JPG fallback via image-set() — ~83% smaller on supporting browsers (97% of users).
           Pages without a dedicated upload fall back to hero-diverse-professionals. */
        body .intro-banner.intro-hero-v2 {
            background-image: url('{{ asset('public/user/images/home.jpg') }}') !important;
            background-image: image-set(
                url('{{ asset('public/user/images/home.webp') }}') type('image/webp'),
                url('{{ asset('public/user/images/home.jpg') }}') type('image/jpeg')
            ) !important;
        }
        body .blog-hero {
            background-image: url('{{ asset('public/user/images/blog.jpg') }}') !important;
            background-image: image-set(
                url('{{ asset('public/user/images/blog.webp') }}') type('image/webp'),
                url('{{ asset('public/user/images/blog.jpg') }}') type('image/jpeg')
            ) !important;
        }
        body .contact-hero {
            background-image: url('{{ asset('public/user/images/contact.jpg') }}') !important;
            background-image: image-set(
                url('{{ asset('public/user/images/contact.webp') }}') type('image/webp'),
                url('{{ asset('public/user/images/contact.jpg') }}') type('image/jpeg')
            ) !important;
        }
        body .jobs-hero {
            background-image: url('{{ asset('public/user/images/job.jpg') }}') !important;
            background-image: image-set(
                url('{{ asset('public/user/images/job.webp') }}') type('image/webp'),
                url('{{ asset('public/user/images/job.jpg') }}') type('image/jpeg')
            ) !important;
        }
        body .companies-hero {
            background-image: url('{{ asset('public/user/images/companies.jpg') }}') !important;
            background-image: image-set(
                url('{{ asset('public/user/images/companies.webp') }}') type('image/webp'),
                url('{{ asset('public/user/images/companies.jpg') }}') type('image/jpeg')
            ) !important;
        }
        /* Pages without a custom upload yet — keep the diverse-professionals image */
        body .about-hero,
        body .cat-hero,
        body .loc-hero,
        body .js-hero,
        body .seeker-hero,
        body .blog-detail-hero,
        body .jd-hero,
        body .utf-page-heading-area {
            background-image: url('{{ asset('public/user/images/hero-diverse-professionals.jpg') }}') !important;
            background-image: image-set(
                url('{{ asset('public/user/images/hero-diverse-professionals.webp') }}') type('image/webp'),
                url('{{ asset('public/user/images/hero-diverse-professionals.jpg') }}') type('image/jpeg')
            ) !important;
        }
        body .intro-banner.intro-hero-v2::before,
        body .about-hero::before,
        body .contact-hero::before,
        body .jobs-hero::before,
        body .cat-hero::before,
        body .loc-hero::before,
        body .companies-hero::before,
        body .blog-hero::before,
        body .blog-detail-hero::before,
        body .jd-hero::before,
        body .js-hero::before,
        body .seeker-hero::before,
        body .utf-page-heading-area::before {
            content: "" !important;
            position: absolute !important;
            inset: 0 !important;
            top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important;
            width: auto !important; height: auto !important;
            background-image: linear-gradient(135deg,
                rgba(247,244,239,0.90) 0%,
                rgba(247,244,239,0.82) 40%,
                rgba(255,255,255,0.95) 100%) !important;
            background-color: transparent !important;
            border-radius: 0 !important;
            filter: none !important;
            transform: none !important;
            z-index: 1 !important;
            pointer-events: none !important;
            opacity: 1 !important;
            display: block !important;
        }
        body .intro-banner.intro-hero-v2 > *,
        body .about-hero > *,
        body .contact-hero > *,
        body .jobs-hero > *,
        body .cat-hero > *,
        body .loc-hero > *,
        body .companies-hero > *,
        body .blog-hero > *,
        body .blog-detail-hero > *,
        body .jd-hero > *,
        body .utf-page-heading-area > * {
            position: relative;
            z-index: 2;
        }

        /* Hide page-local decorative blob ::after on heroes (now use unified image bg) */
        body .intro-banner.intro-hero-v2::after,
        body .about-hero::after,
        body .contact-hero::after,
        body .jobs-hero::after,
        body .cat-hero::after,
        body .loc-hero::after,
        body .companies-hero::after,
        body .blog-hero::after,
        body .utf-page-heading-area::after {
            display: none !important;
            content: none !important;
        }

        /* === Global 2-color H1 accent (orange gradient) across ALL hero variations === */
        body .intro-banner.intro-hero-v2 h1 .accent,
        body .about-hero h1 .accent,
        body .about-hero h1 > span,
        body .contact-hero h1 .accent,
        body .jobs-hero h1 .accent,
        body .cat-hero h1 .accent,
        body .cat-hero h1 span,
        body .loc-hero h1 .accent,
        body .companies-hero h1 .accent,
        body .blog-hero h1 .accent,
        body .js-hero h1 .accent,
        body .seeker-hero h1 .accent,
        body .utf-page-heading-area h1 .accent {
            background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #4a90d9) !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            color: transparent !important;
            font-style: inherit;
            font-weight: inherit;
        }

        /* Hide decorative orange/purple blobs on job-seekers heroes (had ::after blobs) */
        body .js-hero::after,
        body .seeker-hero::after { display: none !important; }
        body .js-hero > *,
        body .seeker-hero > * { position: relative; z-index: 2; }

        /* Override white text → dark text on job-seekers heroes (now use light overlay) */
        body .js-hero,
        body .seeker-hero { color: #1b3a6b !important; }
        body .js-hero h1,
        body .seeker-hero h1 { color: #1b3a6b !important; }
        body .js-hero p,
        body .seeker-hero p { color: #555 !important; }
        body .js-hero .eyebrow,
        body .seeker-hero .eyebrow {
            background: #fff !important;
            border: 1px solid #e5e5e7 !important;
            color: #555 !important;
            backdrop-filter: none !important;
        }
        body .js-hero-stats { border-top-color: rgba(27, 58, 107,.10) !important; }
        body .js-hero-stats .stat strong { color: #1b3a6b !important; }
        body .js-hero-stats .stat span { color: #555 !important; }
        /* Accent gradient text — re-tune to brand orange (against light bg) */
        body .js-hero h1 .accent,
        body .seeker-hero h1 .accent {
            background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #4a90d9) !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            color: transparent !important;
        }
        /* Global brand override — back-to-top, pagination active */
        #backtotop a {
            background-color: #1b3a6b !important;
            color: #fff !important;
            border-color: #1b3a6b !important;
        }
        #backtotop a:hover { background-color: #16305a !important; }
        .pagination ul li a.current-page {
            background-color: #1b3a6b !important;
            color: #fff !important;
            border-color: #1b3a6b !important;
        }
        /* Hide preloader fast — fallback if JS hides it later */
        .preloader { transition: opacity .25s ease; }
    </style>

    <!-- Select2 CSS — non-blocking; only the jobs filter / a few selects use it -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"></noscript>

    {{-- Google Analytics 4 and Tag Manager. Both IDs come from config, so
         nothing is emitted until they are set — the previous hardcoded IDs
         belonged to a different property and sent this site's traffic there.
         Loaded on window load so they never block first paint. --}}
    @php
        $analyticsId = config('services.google.analytics_id');
        $tagManagerId = config('services.google.tag_manager_id');
    @endphp
    @if ($analyticsId || $tagManagerId)
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      window.addEventListener('load', function () {
        @if ($analyticsId)
        var s1 = document.createElement('script');
        s1.async = true;
        s1.src = 'https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}';
        document.head.appendChild(s1);
        gtag('js', new Date());
        gtag('config', @json($analyticsId));
        @endif

        @if ($tagManagerId)
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer',@json($tagManagerId));
        @endif
      });
    </script>
    @endif

    {{-- AOS (Animate On Scroll) — lightweight animation library. Lazy-loaded so it doesn't block first paint. --}}
    <link rel="preload" href="https://unpkg.com/aos@2.3.4/dist/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css"></noscript>
    {{-- Bootstrap Icons — used across auth, seeker, admin. Lazy-loaded; icons appear when CSS resolves. --}}
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"></noscript>
    <link rel="stylesheet" href="{{ asset('public/user/css/site-dark.css') }}?v={{ $assetVersion('user/css/site-dark.css') }}">
    {{-- Pre-apply theme BEFORE body paints (no flash) --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                if (t === 'dark') document.documentElement.classList.add('dark-mode');
            } catch (_) {}
        })();
    </script>

    {{-- Per-page <head> appendables (JSON-LD schemas, canonical overrides, page-specific meta). --}}
    @stack('head')
</head>

<body>
    @if ($tagManagerId)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $tagManagerId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <!-- Preloader Start -->
    <div class="preloader">
        <div class="utf-preloader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- Preloader End -->

    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Header Container -->
        <header id="utf-header-container-block">
            <div id="header">
                <div class="container">
                    <div class="utf-left-side">
                        <div id="logo">
                            <a href="/">
                                <img class="logo-light" src="{{ asset('public/user/images/jobgader-navbar.svg') }}?v={{ $assetVersion('user/images/jobgader-navbar.svg') }}" alt="JobGader" fetchpriority="high" decoding="async" width="120" height="40">
                                <img class="logo-dark"  src="{{ asset('public/user/images/jobgader-navbar-dark.svg') }}?v={{ $assetVersion('user/images/jobgader-navbar-dark.svg') }}" alt="JobGader" fetchpriority="high" decoding="async" width="120" height="40">
                            </a>
                        </div>
                        <nav id="navigation">
                            <ul id="responsive">

                                <li>
                                    <a href="{{ route('home') }}"
                                        class="{{ request()->routeIs('home') ? 'current' : '' }}">
                                        Home
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('jobs.companies') }}"
                                        class="{{ request()->routeIs('jobs.companies', 'companies.show') ? 'current' : '' }}">
                                        Employers
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('job-seekers.index') }}"
                                        class="{{ request()->routeIs('job-seekers.*') ? 'current' : '' }}">
                                        Job Seekers
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('jobs.index') }}"
                                        class="{{ request()->routeIs('jobs.index', 'jobs.show', 'jobs.search', 'jobs.location', 'jobs.locations', 'jobs.categories', 'jobs.category') ? 'current' : '' }}">
                                        Jobs
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('blog.index') }}"
                                        class="{{ request()->routeIs('blog.*') ? 'current' : '' }}">
                                        Career Advice
                                    </a>
                                </li>

                                @guest
                                    <li class="mobile-only-auth">
                                        <a href="{{ route('login') }}">Sign In</a>
                                    </li>
                                @endguest

                            </ul>
                        </nav>

                        <div class="clearfix"></div>
                    </div>

                    <div class="utf-right-side">
                        {{-- Theme toggle (light / dark) — works for guests + authed --}}
                        <button type="button" class="theme-toggle" id="themeToggle"
                                aria-label="Toggle light/dark mode" title="Toggle light/dark mode">
                            <span class="tt-thumb">
                                <i class="bi bi-sun-fill ic-sun"></i>
                                <i class="bi bi-moon-stars-fill ic-moon"></i>
                            </span>
                        </button>
                        @auth
                            @php
                                $authUser = auth()->user();
                                $roleLabel = match ($authUser->role) {
                                    'admin'      => 'Administrator',
                                    'company'    => 'Company',
                                    'job_seeker' => 'Job Seeker',
                                    default      => 'Member',
                                };
                                $dashUrl = match ($authUser->role) {
                                    'admin'      => route('admin.dashboard'),
                                    'company'    => route('company.dashboard'),
                                    'job_seeker' => route('seeker.dashboard'),
                                    default      => url('/'),
                                };
                            @endphp
                            <div class="utf-header-widget-item">
                                <a href="{{ $dashUrl }}" class="utf-button-header-bg" title="Go to your dashboard">
                                    <i class="icon-feather-grid"></i> <span>Dashboard</span>
                                </a>
                            </div>
                            <div class="utf-header-widget-item">
                                <form method="POST" action="{{ route('logout') }}" class="utf-user-logout-form">
                                    @csrf
                                    <button type="submit" class="log-in-button" title="Sign out">
                                        <i class="icon-feather-log-out"></i> <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="utf-header-widget-item">
                                <a href="{{ route('login') }}" class="register-cv-btn">
                                    <i class="icon-feather-file-text"></i> <span>Register CV</span>
                                </a>
                            </div>
                            <div class="utf-header-widget-item">
                                <a href="{{ route('login') }}" class="log-in-button">
                                    <i class="icon-feather-log-in"></i> <span>Sign In</span>
                                </a>
                            </div>
                        @endauth
                        <span class="mmenu-trigger">
                            <button class="hamburger utf-hamburger-collapse-item" type="button">
                                <span class="utf-hamburger-box-item">
                                    <span class="utf-hamburger-inner-item"></span>
                                </span>
                            </button>
                        </span>
                    </div>
                    <style>
                        /* === Register CV button (guest navbar — brand navy in light mode, gold in dark mode) === */
                        .utf-header-widget-item .register-cv-btn {
                            display: inline-flex; align-items: center; gap: 8px;
                            height: 44px; padding: 0 18px;
                            background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
                            color: #fff !important;
                            border: 1.5px solid #1b3a6b;
                            border-radius: 10px;
                            font: 700 14px/1 'Nunito', system-ui, sans-serif;
                            text-decoration: none;
                            transition: filter .15s ease, transform .12s ease, box-shadow .15s ease;
                            box-shadow: 0 4px 12px rgba(27,58,107,.25);
                        }
                        /* Match the Sign In button shape (theme toggle is now a sliding pill) */
                        #header .utf-right-side .log-in-button { border-radius: 10px !important; }
                        .utf-header-widget-item .register-cv-btn:hover {
                            filter: brightness(1.15);
                            transform: translateY(-1px);
                            box-shadow: 0 8px 18px rgba(27, 58, 107,.35);
                        }
                        .utf-header-widget-item .register-cv-btn i,
                        .utf-header-widget-item .register-cv-btn span { color: #fff !important; }
                        html.dark-mode .utf-header-widget-item .register-cv-btn {
                            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
                            border-color: #1b3a6b !important;
                            color: #fff !important;
                            box-shadow: 0 4px 12px rgba(27, 58, 107,.45) !important;
                        }
                        html.dark-mode .utf-header-widget-item .register-cv-btn i,
                        html.dark-mode .utf-header-widget-item .register-cv-btn span { color: #fff !important; }
                        html.dark-mode .utf-header-widget-item .register-cv-btn:hover {
                            box-shadow: 0 8px 18px rgba(27, 58, 107,.55) !important;
                        }
                        @media (max-width: 991px) {
                            .utf-header-widget-item .register-cv-btn { display: none; }
                        }

                        /* === User dropdown chip (logged-in navbar) === */
                        .utf-user-dropdown { position: relative; }
                        .utf-user-chip {
                            display: inline-flex; align-items: center; gap: 10px;
                            background: #1b3a6b; color: #fff;
                            border: 1px solid #1b3a6b; border-radius: 999px;
                            padding: 5px 14px 5px 5px; height: 46px;
                            font: 600 14px/1 'Nunito', system-ui, sans-serif;
                            cursor: pointer;
                            transition: background .15s ease, box-shadow .15s ease;
                        }
                        .utf-user-chip:hover { background: #16305a; box-shadow: 0 6px 14px rgba(27, 58, 107,.20); }
                        .utf-user-chip .user-avatar {
                            width: 36px; height: 36px; border-radius: 50%;
                            overflow: hidden; flex-shrink: 0; padding: 0; margin: 0;
                            border: 2px solid rgba(255,255,255,.18);
                        }
                        .utf-user-chip .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
                        .utf-user-chip .user-name { color: #fff; white-space: nowrap; }
                        .utf-user-chip .chev { font-size: 14px; opacity: .8; transition: transform .2s ease; }
                        .utf-user-dropdown.open .utf-user-chip .chev { transform: rotate(180deg); }

                        .utf-user-menu {
                            position: absolute; top: calc(100% + 10px); right: 0;
                            min-width: 250px;
                            background: #fff;
                            border: 1px solid #ececec;
                            border-radius: 14px;
                            box-shadow: 0 24px 48px rgba(15,23,42,.12);
                            padding: 8px;
                            z-index: 1000;
                            opacity: 0; pointer-events: none;
                            transform: translateY(-6px);
                            transition: opacity .15s ease, transform .15s ease;
                        }
                        .utf-user-dropdown.open .utf-user-menu {
                            opacity: 1; pointer-events: auto; transform: translateY(0);
                        }
                        .utf-user-menu-head {
                            padding: 12px 14px 14px;
                            border-bottom: 1px solid #f3f4f6;
                            margin-bottom: 6px;
                        }
                        .utf-user-menu-head .nm { font-weight: 700; color: #1b3a6b; font-size: 14.5px; line-height: 1.3; }
                        .utf-user-menu-head .rl {
                            display: inline-block; margin-top: 4px;
                            font-size: 11px; font-weight: 700; color: #5e2bff;
                            background: #f3efff; padding: 3px 10px; border-radius: 999px;
                            letter-spacing: 1px; text-transform: uppercase;
                        }
                        .utf-user-menu-item {
                            display: flex; align-items: center; gap: 12px;
                            width: 100%;
                            padding: 10px 14px;
                            border-radius: 8px;
                            font: 600 14px/1.3 'Nunito', system-ui, sans-serif;
                            color: #374151 !important;
                            text-decoration: none !important;
                            background: transparent;
                            border: none; text-align: left;
                            cursor: pointer;
                            transition: background .12s ease, color .12s ease;
                        }
                        .utf-user-menu-item:hover { background: #f3f4f6; color: #1b3a6b !important; }
                        .utf-user-menu-item i {
                            width: 32px; height: 32px; border-radius: 8px;
                            background: #f3f4f6; color: #1b3a6b;
                            display: inline-flex; align-items: center; justify-content: center;
                            font-size: 15px; flex-shrink: 0;
                        }
                        .utf-user-menu-item:hover i { background: #1b3a6b; color: #fff; }
                        .utf-user-menu-item.logout { color: #b91c1c !important; }
                        .utf-user-menu-item.logout i { color: #b91c1c; }
                        .utf-user-menu-item.logout:hover { background: #fef2f2; color: #b91c1c !important; }
                        .utf-user-menu-item.logout:hover i { background: #b91c1c; color: #fff; }
                        .utf-user-menu-sep { height: 1px; background: #f3f4f6; margin: 6px 4px; }
                        .utf-user-logout-form { margin: 0; padding: 0; }

                        @media (max-width: 575px) {
                            .utf-user-chip .user-name { display: none; }
                            .utf-user-menu { right: -10px; min-width: 230px; }
                        }

                        /* === ManageWP-inspired clean navbar === */
                        #utf-header-container-block { background: #fff !important; }
                        #header {
                            background: #fff !important;
                            border-bottom: 1px solid #f0f0f3;
                            box-shadow: 0 1px 2px rgba(15,23,42,.02);
                        }
                        #header .container {
                            display: flex !important;
                            align-items: center;
                            justify-content: space-between;
                            gap: 24px;
                            height: 76px;
                        }
                        #header .utf-left-side {
                            display: flex !important;
                            align-items: center;
                            gap: 50px;
                            flex: 1 1 auto !important;
                            width: auto !important;
                            float: none !important;
                            position: relative !important;
                            justify-content: flex-start;
                        }
                        #header .utf-right-side {
                            display: flex !important;
                            align-items: center !important;
                            gap: 12px;
                            flex-shrink: 0;
                            flex-wrap: nowrap !important;
                            position: relative !important;
                            right: auto !important;
                            float: none !important;
                            background: transparent !important;
                            width: auto !important;
                            height: 76px !important;
                        }

                        /* Logo — light + dark variants swap via dark-mode class on <html> */
                        #header #logo {
                            flex: 0 0 auto;
                            display: inline-flex !important;
                            align-items: center;
                            min-width: 150px;
                            height: 76px;
                        }
                        #header #logo a {
                            display: inline-flex !important;
                            align-items: center;
                            text-decoration: none;
                            height: 100%;
                            line-height: 1;
                        }
                        #header #logo img.logo-light,
                        #header #logo img.logo-dark {
                            width: 200px !important;
                            height: 40px !important;
                            max-width: none;
                            max-height: none;
                            visibility: visible;
                            object-fit: contain;
                            vertical-align: middle;
                            margin: 0 !important;
                        }
                        /* Light mode: show light, hide dark */
                        html:not(.dark-mode) #header #logo img.logo-light { display: inline-block !important; }
                        html:not(.dark-mode) #header #logo img.logo-dark  { display: none !important; }
                        /* Dark mode: show dark, hide light */
                        html.dark-mode #header #logo img.logo-light { display: none !important; }
                        html.dark-mode #header #logo img.logo-dark  { display: inline-block !important; }

                        /* Nav menu — centered between logo and right-side via flex */
                        #header #navigation {
                            flex: 1 1 auto;
                            display: flex;
                            justify-content: center;
                        }
                        #header #navigation > ul {
                            display: flex;
                            align-items: center;
                            gap: 4px;
                            list-style: none;
                            margin: 0;
                            padding: 0;
                        }
                        #header #navigation > ul > li > a {
                            display: inline-flex;
                            align-items: center;
                            color: #16305a !important;
                            font-size: 15px !important;
                            font-weight: 500 !important;
                            text-decoration: none;
                            padding: 10px 16px !important;
                            border-radius: 8px;
                            background: transparent !important;
                            border: none !important;
                            line-height: 1 !important;
                            height: auto !important;
                            transition: background .15s ease, color .15s ease;
                            text-transform: none !important;
                            letter-spacing: 0 !important;
                            white-space: nowrap !important;
                        }
                        #header #navigation > ul > li > a:hover {
                            background: #f5f5f7 !important;
                            color: #000 !important;
                        }
                        #header #navigation > ul > li > a.current {
                            color: #000 !important;
                            font-weight: 600 !important;
                            background: #f5f5f7 !important;
                        }
                        #header #navigation > ul > li > a::after,
                        #header #navigation > ul > li > a::before { display: none !important; }

                        /* Right-side widget container — every direct wrapper aligned identically */
                        #header .utf-right-side .utf-header-widget-item {
                            display: inline-flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                            align-self: center !important;
                            height: 46px !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            float: none !important;
                            border: none !important;
                            background: transparent !important;
                            line-height: 1 !important;
                            vertical-align: middle !important;
                            top: auto !important;
                            right: auto !important;
                            bottom: auto !important;
                            left: auto !important;
                            transform: none !important;
                            inset: auto !important;
                            position: relative !important;
                        }

                        /* === Authenticated user chip — kill ALL inherited positioning from template === */
                        #header .utf-right-side .utf-header-notifications,
                        #header .utf-right-side .user-menu,
                        #header .utf-right-side .utf-header-notifications-trigger,
                        #header .utf-right-side .user-profile-title {
                            position: static !important;
                            top: auto !important;
                            right: auto !important;
                            bottom: auto !important;
                            left: auto !important;
                            transform: none !important;
                            float: none !important;
                            display: inline-flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            border: none !important;
                            background: transparent !important;
                            box-shadow: none !important;
                            height: 46px !important;
                            line-height: 1 !important;
                            vertical-align: middle !important;
                            width: auto !important;
                            min-width: 0 !important;
                            max-width: none !important;
                            inset: auto !important;
                        }
                        #header .utf-right-side .utf-header-notifications::before,
                        #header .utf-right-side .utf-header-notifications::after,
                        #header .utf-right-side .user-menu::before,
                        #header .utf-right-side .user-menu::after,
                        #header .utf-right-side .utf-header-notifications-trigger::before,
                        #header .utf-right-side .utf-header-notifications-trigger::after,
                        #header .utf-right-side .user-profile-title::before,
                        #header .utf-right-side .user-profile-title::after {
                            display: none !important;
                            content: none !important;
                        }
                        #header .utf-right-side .user-profile-title > a {
                            position: static !important;
                            top: auto !important;
                            right: auto !important;
                            bottom: auto !important;
                            left: auto !important;
                            transform: none !important;
                            float: none !important;
                            display: inline-flex !important;
                            align-items: center !important;
                            justify-content: center;
                            gap: 10px;
                            padding: 6px 18px 6px 6px !important;
                            background: #fff !important;
                            border: 1.5px solid #16305a !important;
                            border-radius: 8px !important;
                            color: #16305a !important;
                            text-decoration: none !important;
                            font-weight: 600 !important;
                            font-size: 14.5px !important;
                            line-height: 1.4 !important;
                            min-width: 200px !important;
                            height: 46px !important;
                            box-sizing: border-box;
                            margin: 0 !important;
                            inset: auto !important;
                            vertical-align: middle !important;
                            transition: background .15s ease, color .15s ease;
                        }
                        #header .utf-right-side .user-profile-title > a:hover {
                            background: #16305a !important;
                            color: #fff !important;
                        }
                        #header .utf-right-side .user-profile-title > a:hover .user-name { color: #fff !important; }
                        #header .utf-right-side .user-profile-title .user-avatar {
                            width: 32px !important;
                            height: 32px !important;
                            min-width: 32px;
                            border-radius: 50% !important;
                            overflow: hidden;
                            border: none !important;
                            background: #1b3a6b;
                            margin: 0 !important;
                            padding: 0 !important;
                            position: static !important;
                            display: inline-flex !important;
                            align-items: center;
                            justify-content: center;
                            top: auto !important;
                            float: none !important;
                            flex-shrink: 0;
                        }
                        #header .utf-right-side .user-profile-title .user-avatar img {
                            width: 100% !important;
                            height: 100% !important;
                            object-fit: cover;
                            border-radius: 50% !important;
                            display: block !important;
                            border: none !important;
                            margin: 0 !important;
                            padding: 0 !important;
                        }
                        #header .utf-right-side .user-profile-title .user-avatar::after,
                        #header .utf-right-side .user-profile-title .user-avatar.status-online::after {
                            display: none !important;
                        }
                        #header .utf-right-side .user-profile-title .user-name {
                            color: #16305a !important;
                            font-size: 14.5px !important;
                            font-weight: 600 !important;
                            line-height: 1 !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            white-space: nowrap !important;
                            transition: color .15s ease;
                        }
                        #header .utf-right-side .user-profile-title .user-name::after { display: none !important; }

                        /* Login / Logout button — outlined dark, fixed 46px height to match user chip */
                        #header .utf-right-side .log-in-button {
                            display: inline-flex !important;
                            align-items: center;
                            justify-content: center;
                            gap: 8px;
                            color: #16305a !important;
                            background: #fff !important;
                            border: 1.5px solid #16305a !important;
                            font-size: 14.5px !important;
                            font-weight: 600 !important;
                            text-decoration: none;
                            padding: 0 28px !important;
                            border-radius: 8px !important;
                            min-width: 120px !important;
                            height: 46px !important;
                            box-sizing: border-box;
                            line-height: 1 !important;
                            margin: 0 !important;
                            top: auto !important;
                            transform: none !important;
                            white-space: nowrap !important;
                            transition: all .15s ease;
                        }
                        #header .utf-right-side .log-in-button span,
                        #header .utf-right-side .utf-button-header-bg span {
                            white-space: nowrap !important;
                        }
                        #header .utf-right-side .log-in-button i {
                            color: #16305a !important;
                            background: transparent !important;
                            font-size: 15px !important;
                            margin: 0 !important;
                            top: 0 !important;
                            line-height: 1 !important;
                        }
                        #header .utf-right-side .log-in-button:hover {
                            background: #16305a !important;
                            color: #fff !important;
                            border-color: #16305a !important;
                        }
                        #header .utf-right-side .log-in-button:hover i { color: #fff !important; }

                        /* Logout form — make it inline & remove default button chrome */
                        #header .utf-right-side .logout-form {
                            display: inline-flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            border: none !important;
                            background: transparent !important;
                            height: 46px !important;
                            line-height: 1 !important;
                            vertical-align: middle !important;
                        }
                        #header .utf-right-side button.log-in-button {
                            cursor: pointer;
                            font-family: inherit !important;
                            vertical-align: middle !important;
                        }

                        /* Sign Up button — filled black, same 46px height */
                        #header .utf-right-side .utf-button-header-bg {
                            display: inline-flex !important;
                            align-items: center;
                            justify-content: center;
                            gap: 8px;
                            background: #16305a !important;
                            color: #fff !important;
                            font-size: 14.5px !important;
                            font-weight: 600 !important;
                            text-decoration: none;
                            padding: 0 28px !important;
                            border-radius: 8px !important;
                            border: 1.5px solid #16305a !important;
                            min-width: 120px !important;
                            height: 46px !important;
                            box-sizing: border-box;
                            line-height: 1 !important;
                            margin: 0 !important;
                            white-space: nowrap !important;
                            transition: all .15s ease;
                        }
                        #header .utf-right-side .utf-button-header-bg i {
                            color: #fff !important;
                            font-size: 15px !important;
                            margin: 0 !important;
                            line-height: 1 !important;
                        }
                        #header .utf-right-side .utf-button-header-bg:hover {
                            background: #000 !important;
                            border-color: #000 !important;
                            color: #fff !important;
                            transform: translateY(-1px);
                            box-shadow: 0 4px 10px rgba(0,0,0,.18);
                        }

                        /* Mobile toggle */
                        #header .mmenu-trigger .hamburger { padding: 8px; }
                        #header .mmenu-trigger .utf-hamburger-inner-item,
                        #header .mmenu-trigger .utf-hamburger-inner-item::before,
                        #header .mmenu-trigger .utf-hamburger-inner-item::after {
                            background: #16305a !important;
                        }
                        @media (min-width: 1100px) {
                            #header .mmenu-trigger { display: none !important; }
                        }

                        /* Hide mobile-only auth links on desktop */
                        @media (min-width: 1100px) {
                            #header #navigation .mobile-only-auth { display: none !important; }
                        }

                        /* Mobile/tablet — only Logo + Hamburger in header; Sign In/Up moved into menu */
                        @media (max-width: 1099px) {
                            #header .container { gap: 8px !important; padding: 0 14px !important; }
                            #header .utf-right-side { gap: 8px !important; }
                            #header .utf-right-side .log-in-button,
                            #header .utf-right-side .utf-button-header-bg {
                                display: none !important;
                            }
                            #header .mmenu-trigger {
                                display: inline-flex !important;
                                align-items: center;
                                justify-content: center;
                                width: 44px !important;
                                height: 44px !important;
                                background: #16305a !important;
                                border-radius: 8px !important;
                                margin: 0 !important;
                                top: auto !important;
                                float: none !important;
                                position: relative !important;
                            }
                            #header .mmenu-trigger .hamburger {
                                padding: 0 !important;
                                top: auto !important;
                                left: auto !important;
                                height: 22px !important;
                                width: 26px !important;
                                transform: none !important;
                                -moz-transform: none !important;
                                position: relative !important;
                                display: inline-flex !important;
                                align-items: center;
                                justify-content: center;
                            }
                            #header .mmenu-trigger .utf-hamburger-box-item {
                                position: relative !important;
                                width: 22px !important;
                                height: 18px !important;
                                display: inline-block !important;
                            }
                            #header .mmenu-trigger .utf-hamburger-inner-item,
                            #header .mmenu-trigger .utf-hamburger-inner-item::before,
                            #header .mmenu-trigger .utf-hamburger-inner-item::after {
                                background-color: #fff !important;
                                background: #fff !important;
                                width: 22px !important;
                                height: 2.5px !important;
                                border-radius: 2px !important;
                                left: 0 !important;
                                position: absolute !important;
                            }
                            #header .mmenu-trigger .utf-hamburger-inner-item { top: 50% !important; margin-top: -1.25px !important; }
                            #header .mmenu-trigger .utf-hamburger-inner-item::before { top: -8px !important; content: "" !important; display: block !important; }
                            #header .mmenu-trigger .utf-hamburger-inner-item::after { bottom: -8px !important; top: auto !important; content: "" !important; display: block !important; }
                            #header #logo { min-width: 170px; }
                            #header #logo img.logo-light,
                            #header #logo img.logo-dark {
                                width: 170px !important;
                                height: 34px !important;
                            }
                            #header .utf-left-side { min-width: 0; flex: 1 1 auto; }
                        }
                        @media (max-width: 480px) {
                            #header #logo { min-width: 150px; }
                            #header #logo img.logo-light,
                            #header #logo img.logo-dark {
                                width: 150px !important;
                                height: 30px !important;
                            }
                            #header .container { padding: 0 10px !important; gap: 6px !important; }
                        }

                        /* === Mobile menu (mmenu) — visual feedback on items === */
                        .mm-menu { --mm-color-background: #16305a; }
                        .mm-menu .mm-navbar { color: #fff !important; font-weight: 700; }
                        .mm-menu .mm-listitem { position: relative; }
                        .mm-menu .mm-listitem:after {
                            border-color: rgba(255,255,255,.08) !important;
                            left: 20px !important;
                        }
                        .mm-menu .mm-listitem__text,
                        .mm-menu .mm-listitem > a {
                            color: #fff !important;
                            padding: 18px 44px 18px 22px !important;
                            font-size: 15px !important;
                            font-weight: 500 !important;
                            transition: background .15s ease, color .15s ease;
                            display: block;
                            position: relative;
                        }
                        /* Chevron arrow on the right */
                        .mm-menu .mm-listitem > a::after,
                        .mm-menu .mm-listitem__text::after {
                            content: "";
                            position: absolute;
                            right: 22px;
                            top: 50%;
                            width: 8px;
                            height: 8px;
                            border-right: 2px solid rgba(255,255,255,.5);
                            border-top: 2px solid rgba(255,255,255,.5);
                            transform: translateY(-50%) rotate(45deg);
                            transition: border-color .15s ease, right .15s ease;
                            pointer-events: none;
                        }
                        /* Tap/active feedback */
                        .mm-menu .mm-listitem > a:hover,
                        .mm-menu .mm-listitem > a:focus,
                        .mm-menu .mm-listitem > a:active,
                        .mm-menu .mm-listitem__text:hover,
                        .mm-menu .mm-listitem__text:active {
                            background: rgba(255,255,255,.06) !important;
                            color: #1b3a6b !important;
                        }
                        .mm-menu .mm-listitem > a:hover::after,
                        .mm-menu .mm-listitem > a:active::after,
                        .mm-menu .mm-listitem__text:hover::after,
                        .mm-menu .mm-listitem__text:active::after {
                            border-color: #1b3a6b;
                            right: 18px;
                        }
                        /* Sign Up — accent pill style to stand out */
                        .mm-menu .mm-listitem.mobile-only-auth:last-child > a,
                        .mm-menu .mm-listitem.mobile-only-auth:last-child .mm-listitem__text {
                            background: #1b3a6b !important;
                            color: #fff !important;
                            margin: 14px 18px 0 !important;
                            padding: 14px 22px !important;
                            border-radius: 10px !important;
                            text-align: center !important;
                            font-weight: 700 !important;
                        }
                        .mm-menu .mm-listitem.mobile-only-auth:last-child:after { display: none !important; }
                        .mm-menu .mm-listitem.mobile-only-auth:last-child > a::after,
                        .mm-menu .mm-listitem.mobile-only-auth:last-child .mm-listitem__text::after { display: none !important; }
                        /* Sign In — outlined style above Sign Up */
                        .mm-menu .mm-listitem.mobile-only-auth:nth-last-child(2) > a,
                        .mm-menu .mm-listitem.mobile-only-auth:nth-last-child(2) .mm-listitem__text {
                            border: 1.5px solid rgba(255,255,255,.25) !important;
                            margin: 18px 18px 0 !important;
                            padding: 14px 22px !important;
                            border-radius: 10px !important;
                            text-align: center !important;
                            font-weight: 600 !important;
                        }
                        .mm-menu .mm-listitem.mobile-only-auth:nth-last-child(2):after { display: none !important; }
                        .mm-menu .mm-listitem.mobile-only-auth:nth-last-child(2) > a::after,
                        .mm-menu .mm-listitem.mobile-only-auth:nth-last-child(2) .mm-listitem__text::after { display: none !important; }

                        /* Auth user state */
                        #header .utf-right-side .utf-header-notifications,
                        #header .utf-right-side .utf-header-notifications-trigger,
                        #header .utf-right-side .utf-header-notifications-trigger.user-profile-title {
                            display: inline-flex !important;
                            align-items: center !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            background: transparent !important;
                            box-shadow: none !important;
                            border: none !important;
                            float: none !important;
                            position: relative !important;
                            top: auto !important;
                            transform: none !important;
                            height: auto !important;
                            width: auto !important;
                        }
                        #header .utf-right-side .utf-header-notifications-trigger a {
                            display: inline-flex !important;
                            align-items: center !important;
                            gap: 10px;
                            color: #16305a !important;
                            text-decoration: none;
                            background: #f5f5f7;
                            padding: 6px 14px 6px 6px;
                            border-radius: 999px;
                            transition: background .15s ease;
                            line-height: 1 !important;
                        }
                        #header .utf-right-side .utf-header-notifications-trigger a:hover { background: #ebebef; }
                        #header .utf-right-side .user-avatar {
                            width: 32px !important; height: 32px !important;
                            border-radius: 50% !important;
                            overflow: hidden;
                            display: inline-flex !important;
                            align-items: center; justify-content: center;
                            margin: 0 !important; padding: 0 !important;
                            position: relative;
                            background: linear-gradient(135deg, #16305a, #4a90d9);
                            flex-shrink: 0;
                        }
                        #header .utf-right-side .user-avatar img {
                            width: 100% !important; height: 100% !important;
                            object-fit: cover; display: block;
                            border-radius: 50%;
                        }
                        #header .utf-right-side .user-avatar.status-online::after {
                            display: none;
                        }
                        #header .utf-right-side .user-name {
                            font-weight: 600 !important;
                            font-size: 13.5px !important;
                            color: #16305a !important;
                            white-space: nowrap;
                            line-height: 1 !important;
                            margin: 0 !important;
                        }

                        @media (max-width: 991px) {
                            #header .container { height: 64px; }
                            #header #navigation { display: none; }
                        }
                    </style>
                </div>
            </div>
        </header>
        <div class="clearfix"></div>
        <!-- Header Container / End -->


        @yield('content')

        <!-- CTA Block — Modern, oversized, no privacy button -->
        <style>
            .modern-cta-section {
                padding: 90px 0;
                background: #f5f5f7;
            }
            .modern-cta-card {
                position: relative;
                background: #1b3a6b;
                color: #fff;
                border-radius: 24px;
                padding: 70px 60px;
                overflow: hidden;
                text-align: center;
            }
            .modern-cta-card::before, .modern-cta-card::after {
                content: "";
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: .35;
                pointer-events: none;
            }
            .modern-cta-card::before {
                width: 360px; height: 360px;
                background: #2f7fc9;
                top: -120px; right: -100px;
            }
            .modern-cta-card::after {
                width: 320px; height: 320px;
                background: #5e2bff;
                bottom: -120px; left: -100px;
            }
            .modern-cta-card .cta-inner { position: relative; z-index: 2; max-width: 760px; margin: 0 auto; }
            .modern-cta-card .eyebrow {
                display: inline-block;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 1.4px;
                text-transform: uppercase;
                color: rgba(255,255,255,.85);
                background: rgba(255,255,255,.10);
                border: 1px solid rgba(255,255,255,.20);
                padding: 7px 16px;
                border-radius: 999px;
                margin-bottom: 22px;
                backdrop-filter: blur(8px);
            }
            .modern-cta-card h2 {
                font-size: clamp(32px, 4vw, 52px);
                font-weight: 800;
                line-height: 1.1;
                letter-spacing: -.8px;
                color: #fff !important;
                margin: 0 0 18px;
            }
            .modern-cta-card h2 .accent {
                background: linear-gradient(90deg, #8fc4f0, #4a90d9);
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                color: transparent;
            }
            .modern-cta-card p {
                color: rgba(255,255,255,.85);
                font-size: 17px;
                line-height: 1.65;
                max-width: 580px;
                margin: 0 auto 32px;
            }
            .modern-cta-card .cta-actions {
                display: inline-flex;
                gap: 12px;
                flex-wrap: wrap;
                justify-content: center;
            }
            .modern-cta-card .btn-cta-primary {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: #fff;
                color: #1b3a6b !important;
                font-size: 16px;
                font-weight: 700;
                padding: 16px 32px;
                border-radius: 12px;
                text-decoration: none;
                transition: all .15s ease;
            }
            .modern-cta-card .btn-cta-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(0,0,0,.25);
            }
            .modern-cta-card .btn-cta-secondary {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: transparent;
                color: #fff !important;
                font-size: 16px;
                font-weight: 600;
                padding: 16px 30px;
                border-radius: 12px;
                border: 1.5px solid rgba(255,255,255,.30);
                text-decoration: none;
                transition: all .15s ease;
            }
            .modern-cta-card .btn-cta-secondary:hover {
                background: rgba(255,255,255,.10);
                border-color: rgba(255,255,255,.50);
            }
            .modern-cta-card .cta-stats {
                display: flex;
                justify-content: center;
                gap: 40px;
                margin-top: 40px;
                padding-top: 30px;
                border-top: 1px solid rgba(255,255,255,.15);
                flex-wrap: wrap;
            }
            .modern-cta-card .cta-stats .stat strong {
                display: block;
                font-size: 22px;
                font-weight: 800;
                color: #fff;
                line-height: 1.1;
            }
            .modern-cta-card .cta-stats .stat span {
                font-size: 12px;
                color: rgba(255,255,255,.65);
                text-transform: uppercase;
                letter-spacing: 1.2px;
            }
            @media (max-width: 768px) {
                .modern-cta-section { padding: 56px 0; }
                .modern-cta-card { padding: 50px 26px; border-radius: 18px; }
                .modern-cta-card .cta-stats { gap: 24px; }
            }
        </style>
        <section class="modern-cta-section">
            <div class="container">
                <div class="modern-cta-card">
                    <div class="cta-inner">
                        <span class="eyebrow">Start your search</span>
                        <h2>Your next job is <span class="accent">one click away</span></h2>
                        <p>Real openings across the USA, UK and Pakistan &mdash; with straight answers on which visa routes are actually open. Free to apply, and you don't need an account.</p>
                        <div class="cta-actions">
                            <a href="{{ route('jobs.index') }}" class="btn-cta-primary">
                                Browse All Jobs
                                <i class="icon-feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('blog.index') }}" class="btn-cta-secondary">
                                Read Visa Guides
                            </a>
                        </div>
                        <div class="cta-stats">
                            <div class="stat"><strong>3</strong><span>Countries</span></div>
                            <div class="stat"><strong>Weekly</strong><span>New Jobs</span></div>
                            <div class="stat"><strong>No</strong><span>Sign-Up</span></div>
                            <div class="stat"><strong>100%</strong><span>Free</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Block End -->

        <!-- Footer — ManageWP-inspired dark navy with blue glow -->
        <style>
            /* === Footer: dark navy gradient with subtle blue radial glow === */
            #footer {
                background: linear-gradient(135deg, #0a1828 0%, #061224 55%, #050b18 100%) !important;
                color: #b0c0d0 !important;
                padding: 0 !important;
                margin: 0 !important;
                position: relative !important;
                overflow: hidden;
            }
            #footer::before {
                content: "";
                position: absolute;
                top: -100px; left: -100px;
                width: 720px; height: 480px;
                background: radial-gradient(ellipse at top left, rgba(64,145,255,.22) 0%, rgba(64,145,255,.08) 30%, transparent 65%);
                pointer-events: none;
                z-index: 0;
            }
            #footer::after { display: none !important; }
            #footer > * { position: relative; z-index: 1; }
            #footer .utf-footer-section-item-block {
                background: transparent !important;
                padding: 90px 0 0 !important;
                border: none !important;
            }
            #footer .utf-footer-section-item-block::before,
            #footer .utf-footer-section-item-block::after { display: none !important; }
            #footer .container-fluid { padding-left: 60px !important; padding-right: 60px !important; }

            /* Brand column */
            #footer .footer-logo {
                max-width: 220px;
                height: auto;
                margin-bottom: 22px;
                opacity: 1;
            }
            #footer .utf-footer-item-links p {
                color: #8a9bb0 !important;
                font-size: 14.5px !important;
                line-height: 1.75 !important;
                margin: 0 0 18px !important;
                max-width: 400px;
            }
            #footer .utf-footer-item-links p a {
                color: #c8d6e8 !important;
                text-decoration: none;
                transition: color .15s ease;
            }
            #footer .utf-footer-item-links p a:hover { color: #4d9eff !important; }

            /* Section headings — bright blue ManageWP style */
            #footer .utf-footer-item-links h3 {
                color: #4d9eff !important;
                font-size: 14px !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 1.6px !important;
                margin: 0 0 24px !important;
                padding: 0 !important;
                border: none !important;
                background: none !important;
            }
            #footer .utf-footer-item-links h3::before,
            #footer .utf-footer-item-links h3::after { display: none !important; }

            /* Link lists */
            #footer .utf-footer-item-links ul {
                list-style: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            #footer .utf-footer-item-links ul li {
                margin: 0 !important;
                padding: 0 !important;
                background: none !important;
            }
            #footer .utf-footer-item-links ul li a {
                color: #c8d6e8 !important;
                font-size: 14.5px !important;
                font-weight: 400 !important;
                line-height: 1 !important;
                padding: 8px 0 !important;
                display: inline-flex !important;
                align-items: center;
                text-decoration: none !important;
                transition: color .15s ease, transform .15s ease;
            }
            #footer .utf-footer-item-links ul li a i { display: none !important; }
            #footer .utf-footer-item-links ul li a span {
                color: inherit !important;
                font: inherit !important;
                background: none !important;
            }
            #footer .utf-footer-item-links ul li a:hover {
                color: #4d9eff !important;
                transform: translateX(2px);
            }
            #footer .utf-footer-item-links ul li::before { display: none !important; }

            /* Hub link — promotes the "All Categories" / "All Locations" landing pages */
            #footer .utf-footer-item-links ul li a.footer-hub-link {
                color: #fff !important;
                background: rgba(77,158,255,.12) !important;
                border: 1px solid rgba(77,158,255,.30) !important;
                border-radius: 8px;
                padding: 9px 12px !important;
                margin-bottom: 10px;
                font-weight: 600 !important;
                gap: 6px;
                transition: background .15s ease, border-color .15s ease, transform .15s ease;
            }
            #footer .utf-footer-item-links ul li a.footer-hub-link i {
                display: inline-block !important;
                font-size: 14px !important;
                color: #4d9eff !important;
                margin-right: 4px;
            }
            #footer .utf-footer-item-links ul li a.footer-hub-link span strong { color: #fff !important; font-weight: 600 !important; }
            #footer .utf-footer-item-links ul li a.footer-hub-link:hover {
                background: rgba(77,158,255,.22) !important;
                border-color: rgba(77,158,255,.55) !important;
                transform: translateX(2px);
                color: #fff !important;
            }
            #footer .utf-footer-item-links ul li a.footer-hub-link:hover i { color: #fff !important; }

            /* Copyright bar */
            #footer .utf-footer-copyright-item {
                background: transparent !important;
                border-top: 1px solid rgba(64,145,255,.12) !important;
                margin-top: 70px !important;
                padding: 26px 0 !important;
                color: #6b7d92 !important;
                font-size: 13.5px !important;
                text-align: center;
            }
            #footer .utf-footer-copyright-item .container-fluid { padding: 0 60px !important; }
            #footer .utf-footer-copyright-item .row,
            #footer .utf-footer-copyright-item .col-xl-12 {
                color: #6b7d92 !important;
                text-align: center !important;
            }

            @media (max-width: 991px) {
                #footer .container-fluid { padding-left: 24px !important; padding-right: 24px !important; }
                #footer .utf-footer-section-item-block { padding-top: 60px !important; }
                #footer::before { width: 100%; height: 320px; }
            }

            /* === Dark mode — match site bg + brand orange (replaces navy blue look) === */
            html.dark-mode #footer {
                background: var(--site-bg) !important;
                color: var(--site-text) !important;
                border-top: 1px solid var(--site-card-bd);
            }
            html.dark-mode #footer::before {
                background: radial-gradient(ellipse at top left, rgba(27, 58, 107,.14) 0%, rgba(27, 58, 107,.05) 30%, transparent 65%) !important;
            }
            html.dark-mode #footer .utf-footer-item-links h3 { color: #1b3a6b !important; }
            html.dark-mode #footer .utf-footer-item-links p { color: #cbd5e1 !important; }
            html.dark-mode #footer .utf-footer-item-links p a { color: #e5e7eb !important; }
            html.dark-mode #footer .utf-footer-item-links p a:hover { color: #1b3a6b !important; }
            html.dark-mode #footer .utf-footer-item-links ul li a { color: #cbd5e1 !important; }
            html.dark-mode #footer .utf-footer-item-links ul li a:hover { color: #1b3a6b !important; }
            html.dark-mode #footer .utf-footer-item-links ul li a i { color: var(--site-muted) !important; }
            html.dark-mode #footer .utf-footer-item-links ul li a:hover i { color: #1b3a6b !important; }
            html.dark-mode #footer .footer-hub-link {
                background: rgba(27, 58, 107,.10) !important;
                border-color: rgba(27, 58, 107,.30) !important;
                color: #1b3a6b !important;
            }
            html.dark-mode #footer .footer-hub-link:hover {
                background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
                color: #fff !important;
                border-color: #1b3a6b !important;
            }
            html.dark-mode #footer .footer-hub-link strong,
            html.dark-mode #footer .footer-hub-link span,
            html.dark-mode #footer .footer-hub-link i { color: inherit !important; }
            html.dark-mode #footer .utf-footer-copyright-item {
                background: transparent !important;
                border-top-color: var(--site-card-bd) !important;
                color: var(--site-muted) !important;
            }
            html.dark-mode #footer .utf-footer-copyright-item .row,
            html.dark-mode #footer .utf-footer-copyright-item .col-xl-12 { color: var(--site-muted) !important; }
        </style>

        <!-- Footer -->
        <div id="footer">
            <div class="utf-footer-section-item-block">
                <div class="container-fluid px-5">
                    <div class="row">
                        <div class="col-xl-4 col-md-12">
                            <div class="utf-footer-item-links">
                                <a href="/"><img class="footer-logo" loading="lazy" decoding="async"
                                        src="{{ asset('public/user/images/jobgader-dark-logo.svg') }}" alt="JobGader"></a>
                                <p>JobGader is your premier destination for finding job opportunities across the
                                    United States. We connect job seekers with employers in various industries,
                                    locations, and experience levels. Discover your next career opportunity with our
                                    comprehensive job search platform.</p>
                                <p>
                                    <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> &nbsp;·&nbsp;
                                    <a href="{{ url('/terms-of-service') }}">Terms of Service</a> &nbsp;·&nbsp;
                                    <a href="{{ url('/about-us') }}">About Us</a> &nbsp;·&nbsp;
                                    <a href="{{ url('/contact') }}">Contact</a>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <div class="utf-footer-item-links">
                                <h3>Job Categories</h3>
                                <ul>
                                    <li><a href="{{ route('jobs.categories') }}" class="footer-hub-link"><i
                                                class="icon-feather-grid"></i> <span><strong>Browse All Categories</strong></span></a>
                                    </li>
                                    @foreach(($footerCategories ?? collect()) as $cat)
                                        <li><a href="{{ route('jobs.category', $cat->slug) }}"><i
                                                    class="icon-feather-chevron-right"></i> <span>{{ $cat->name }}</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <div class="utf-footer-item-links">
                                <h3>States</h3>
                                <ul>
                                    <li><a href="{{ route('jobs.locations') }}" class="footer-hub-link"><i
                                                class="icon-feather-map-pin"></i> <span><strong>Browse All Locations</strong></span></a>
                                    </li>
                                    @foreach(($footerStates ?? collect()) as $state)
                                        <li><a href="{{ route('jobs.search', ['location' => $state->name]) }}"><i
                                                    class="icon-feather-chevron-right"></i> <span>Jobs in {{ $state->name }}</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <div class="utf-footer-item-links">
                                <h3>Remote & Work Styles</h3>
                                <ul>
                                    <li><a href="{{ route('pages.remote-jobs-usa') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Remote Jobs
                                                USA</span></a></li>
                                    <li><a href="{{ route('pages.work-from-home-jobs') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Work From Home
                                                Jobs</span></a></li>
                                    <li><a href="{{ route('pages.online-jobs-usa') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Online Jobs
                                                USA</span></a></li>
                                    <li><a href="{{ route('pages.part-time-remote-jobs') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Part-Time Remote
                                                Jobs</span></a></li>
                                    <li><a href="{{ route('pages.entry-level-remote-jobs') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Entry Level Remote
                                                Jobs</span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <div class="utf-footer-item-links">
                                <h3>Experience Levels</h3>
                                <ul>
                                    <li><a href="{{ route('pages.entry-level-jobs') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Entry Level
                                                Jobs</span></a></li>
                                    <li><a href="{{ route('pages.no-experience-jobs') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>No Experience
                                                Jobs</span></a></li>
                                    <li><a href="{{ route('pages.graduate-jobs') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Graduate Jobs</span></a>
                                    </li>
                                    <li><a href="{{ route('pages.internship-jobs') }}"><i
                                                class="icon-feather-chevron-right"></i> <span>Internship
                                                Jobs</span></a></li>
                                </ul>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Footer Copyrights -->
                <div class="utf-footer-copyright-item">
                    <div class="container-fluid px-5">
                        <div class="row">
                            <div class="col-xl-12">Copyright &copy; 2026 JobGader. All Rights Reserved.</div>
                        </div>
                    </div>
                </div>
                <!-- Footer Copyrights / End -->
            </div>
            <!-- Footer / End -->
        </div>
        <!-- Wrapper / End -->

        <!-- Scripts -->
        <script src="{{ asset('public/user/js/jquery-3.3.1.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/jquery-migrate-3.0.0.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/mmenu.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/tippy.all.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/simplebar.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/bootstrap-slider.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/bootstrap-select.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/snackbar.js') }}" defer></script>
        <script src="{{ asset('public/user/js/clipboard.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/counterup.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/magnific-popup.min.js') }}" defer></script>
        <script src="{{ asset('public/user/js/slick.min.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
        <script src="{{ asset('public/user/js/custom_jquery.js') }}" defer></script>

        <script>
            window.addEventListener('load', function () {
                // Hide preloader as soon as everything is ready
                var pre = document.querySelector('.preloader');
                if (pre) { pre.style.opacity = '0'; setTimeout(function () { pre.style.display = 'none'; }, 250); }
                // Move any Google Places autocomplete container into the search field block
                if (window.jQuery && jQuery('.utf-intro-banner-search-form-block')[0]) {
                    jQuery(".pac-container").prependTo(".utf-intro-search-field-item.with-autocomplete");
                }
            });

            // === User dropdown chip toggle ===
            (function () {
                var btn = document.getElementById('utfUserChipBtn');
                if (!btn) return;
                var dd = btn.closest('.utf-user-dropdown');
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var open = dd.classList.toggle('open');
                    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
                document.addEventListener('click', function (e) {
                    if (!dd.contains(e.target)) {
                        dd.classList.remove('open');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        dd.classList.remove('open');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });
            })();
        </script>

        {{-- AOS init — scroll animations --}}
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js" defer></script>
        <script>
            window.addEventListener('load', function () {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 700,
                        easing: 'ease-out-cubic',
                        once: true,
                        offset: 80,
                        delay: 0,
                        disable: function() { return window.innerWidth < 480; }
                    });
                }
            });
        </script>

        {{-- ===== Light / dark theme toggle ===== --}}
        <script>
        (function () {
            const btn = document.getElementById('themeToggle');
            if (!btn) return;
            btn.addEventListener('click', function () {
                const isDark = document.documentElement.classList.toggle('dark-mode');
                try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch (_) {}
            });
        })();
        </script>

        {{-- ===== Global autocomplete for search inputs ===== --}}
        <style>
            .ac-dropdown {
                position: absolute; z-index: 1100;
                top: 100%; left: 0; right: 0;
                background: #fff; border: 1px solid #e5e7eb;
                border-radius: 14px; box-shadow: 0 18px 40px rgba(15,23,42,.14);
                max-height: 440px; overflow-y: auto;
                width: 100%;
                margin-top: 6px;
                display: none;
                text-align: left;
            }
            .ac-dropdown.show { display: block; }
            .ac-section-title {
                padding: 10px 14px 6px; font-size: 10.5px; font-weight: 700;
                letter-spacing: .6px; text-transform: uppercase; color: #9ca3af;
            }
            .ac-item {
                display: flex; align-items: center; gap: 10px;
                padding: 10px 14px; cursor: pointer;
                font-size: 14px; color: #1b3a6b;
                border-bottom: 1px solid #f3f4f6;
            }
            .ac-item:last-child { border-bottom: none; }
            .ac-item:hover, .ac-item.is-active { background: #f9fafb; }
            .ac-item .ac-icon {
                width: 30px; height: 30px; border-radius: 8px;
                background: #f3f4f6; color: #374151;
                display: inline-flex; align-items: center; justify-content: center;
                font-size: 14px; flex-shrink: 0;
            }
            .ac-item .ac-icon.briefcase { background: #fef3c7; color: #92400e; }
            .ac-item .ac-icon.tag      { background: #f3efff; color: #5e2bff; }
            .ac-item .ac-icon.geo-alt  { background: #ecfdf5; color: #047857; }
            .ac-item .ac-text { flex: 1; min-width: 0; }
            .ac-item .ac-label { font-weight: 600; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .ac-item .ac-type  { font-size: 11.5px; color: #9ca3af; text-transform: uppercase; letter-spacing: .4px; }
            .ac-item .ac-key   { font-size: 11px; color: #9ca3af; background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }
            .ac-empty { padding: 16px; text-align: center; font-size: 13px; color: #9ca3af; }

            /* Rich job card row */
            .ac-job { align-items: flex-start; padding: 12px 14px; }
            .ac-job .ac-icon { width: 36px; height: 36px; border-radius: 10px; font-size: 16px; }
            .ac-job .ac-job-title { font-weight: 700; font-size: 14px; color: #1b3a6b; line-height: 1.3;
                white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .ac-job .ac-job-meta { display: flex; flex-wrap: wrap; gap: 6px 12px; margin-top: 4px;
                font-size: 12.5px; color: #6b7280; }
            .ac-job .ac-job-meta span { display: inline-flex; align-items: center; gap: 4px; }
            .ac-job .ac-job-salary { color: #047857; font-weight: 600; }
            .ac-view-all {
                display: block; padding: 12px 14px; text-align: center;
                font-size: 13px; font-weight: 600; color: #5e2bff;
                background: #fafafe; border-top: 1px solid #f3f4f6;
                cursor: pointer; text-decoration: none;
            }
            .ac-view-all:hover { background: #f3efff; color: #5e2bff; }
        </style>
        <script>
        (function () {
            const inputs = document.querySelectorAll('input[data-autocomplete="jobs"]');
            if (!inputs.length) return;

            const url = "{{ route('jobs.autocomplete') }}";

            inputs.forEach(input => {
                // Wrap input in a relative wrapper for absolute-positioned dropdown
                if (!input.parentElement.style.position) {
                    input.parentElement.style.position = 'relative';
                }
                const dropdown = document.createElement('div');
                dropdown.className = 'ac-dropdown';
                input.parentElement.appendChild(dropdown);

                let debounce, abortCtrl, items = [], activeIdx = -1;

                const close = () => { dropdown.classList.remove('show'); activeIdx = -1; };
                const iconEmoji = { briefcase: '💼', tag: '🏷️', 'geo-alt': '📍' };
                const renderJobCard = (s, i) => `
                    <div class="ac-item ac-job" data-idx="${i}">
                        <span class="ac-icon briefcase">💼</span>
                        <div class="ac-text">
                            <div class="ac-job-title">${escapeHtml(s.label)}</div>
                            <div class="ac-job-meta">
                                ${s.company ? `<span>🏢 ${escapeHtml(s.company)}</span>` : ''}
                                ${s.location ? `<span>📍 ${escapeHtml(s.location)}</span>` : ''}
                                ${s.salary ? `<span class="ac-job-salary">💵 ${escapeHtml(s.salary)}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `;
                const renderRow = (s, i) => `
                    <div class="ac-item" data-idx="${i}">
                        <span class="ac-icon ${s.icon || 'briefcase'}">${iconEmoji[s.icon] || '💼'}</span>
                        <div class="ac-text">
                            <div class="ac-label">${escapeHtml(s.label)}</div>
                            <div class="ac-type">${s.type}</div>
                        </div>
                        <span class="ac-key">↵</span>
                    </div>
                `;
                const render = (suggestions, query) => {
                    items = suggestions || [];
                    if (items.length === 0) {
                        dropdown.innerHTML = '<div class="ac-empty">No matches — try a different keyword</div>';
                        dropdown.classList.add('show');
                        return;
                    }
                    const jobs = items.filter(x => x.kind === 'job_card');
                    const other = items.filter(x => x.kind !== 'job_card');
                    let html = '';
                    if (jobs.length) {
                        html += '<div class="ac-section-title">Matching jobs</div>';
                        html += jobs.map((s, i) => renderJobCard(s, items.indexOf(s))).join('');
                    }
                    if (other.length) {
                        html += '<div class="ac-section-title">Categories &amp; locations</div>';
                        html += other.map((s) => renderRow(s, items.indexOf(s))).join('');
                    }
                    const viewAllUrl = "{{ route('jobs.index') }}" + '?position=' + encodeURIComponent(query);
                    html += `<a class="ac-view-all" href="${viewAllUrl}">View all results for &ldquo;${escapeHtml(query)}&rdquo; →</a>`;
                    dropdown.innerHTML = html;
                    dropdown.classList.add('show');
                    dropdown.querySelectorAll('.ac-item').forEach(el => {
                        el.addEventListener('mousedown', e => {
                            e.preventDefault();
                            const idx = parseInt(el.dataset.idx, 10);
                            if (items[idx] && items[idx].url) window.location.href = items[idx].url;
                        });
                    });
                };
                const escapeHtml = (t) => String(t).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

                input.addEventListener('input', () => {
                    const q = input.value.trim();
                    if (q.length < 2) { close(); return; }
                    if (debounce) clearTimeout(debounce);
                    if (abortCtrl) abortCtrl.abort();
                    debounce = setTimeout(() => {
                        abortCtrl = new AbortController();
                        fetch(url + '?q=' + encodeURIComponent(q), { signal: abortCtrl.signal, headers: { 'Accept': 'application/json' } })
                            .then(r => r.json())
                            .then(d => render(d.suggestions || [], q))
                            .catch(() => {});
                    }, 180);
                });

                input.addEventListener('keydown', (e) => {
                    if (!dropdown.classList.contains('show')) return;
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        activeIdx = Math.min(activeIdx + 1, items.length - 1);
                        updateActive();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        activeIdx = Math.max(activeIdx - 1, -1);
                        updateActive();
                    } else if (e.key === 'Enter' && activeIdx >= 0) {
                        e.preventDefault();
                        if (items[activeIdx]?.url) window.location.href = items[activeIdx].url;
                    } else if (e.key === 'Escape') {
                        close();
                    }
                });

                function updateActive() {
                    dropdown.querySelectorAll('.ac-item').forEach((el, i) => {
                        el.classList.toggle('is-active', i === activeIdx);
                    });
                }

                document.addEventListener('click', (e) => {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) close();
                });
            });
        })();
        </script>

        @guest
            {{-- Login-prompt popup — appears 10s after page load, once per browser session --}}
            <div id="loginPromptModal" class="lp-overlay" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="lpTitle">
                <div class="lp-modal" tabindex="-1">
                    <button type="button" class="lp-close" id="lpClose" aria-label="Close">&times;</button>
                    <div class="lp-icon"><i class="icon-feather-briefcase"></i></div>
                    <h2 id="lpTitle">Find Your Next Job — Free</h2>
                    <p>Sign in to apply for jobs in one click, save listings, and get personalized job alerts straight to your inbox.</p>
                    <a href="{{ route('login') }}" class="lp-cta">Sign In to Apply</a>
                    <a href="{{ route('register') }}" class="lp-secondary">Don't have an account? Register free</a>
                    <button type="button" class="lp-dismiss" id="lpDismiss">No thanks, I'll keep browsing</button>
                </div>
            </div>
            <style>
                .lp-overlay {
                    position: fixed; inset: 0; z-index: 99999;
                    background: rgba(27, 58, 107, .65);
                    backdrop-filter: blur(4px);
                    display: none;
                    align-items: center; justify-content: center;
                    padding: 20px;
                    opacity: 0;
                    transition: opacity .25s ease;
                }
                .lp-overlay.is-open { display: flex; opacity: 1; }
                .lp-modal {
                    position: relative;
                    width: 100%;
                    max-width: 460px;
                    background: #16305a;
                    color: #fff;
                    border-radius: 14px;
                    padding: 36px 32px 28px;
                    text-align: center;
                    box-shadow: 0 24px 48px rgba(0, 0, 0, .45);
                    transform: scale(.96);
                    transition: transform .25s ease;
                }
                .lp-overlay.is-open .lp-modal { transform: scale(1); }
                .lp-close {
                    position: absolute; top: 12px; right: 14px;
                    width: 36px; height: 36px;
                    background: transparent;
                    border: 1.5px solid rgba(255, 255, 255, .35);
                    color: #fff;
                    border-radius: 50%;
                    font-size: 22px; line-height: 1;
                    cursor: pointer;
                    transition: all .15s ease;
                    display: inline-flex; align-items: center; justify-content: center;
                }
                .lp-close:hover { background: rgba(255, 255, 255, .12); border-color: #fff; }
                .lp-icon {
                    width: 64px; height: 64px;
                    margin: 0 auto 16px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
                    display: inline-flex; align-items: center; justify-content: center;
                    box-shadow: 0 8px 18px rgba(27, 58, 107, .35);
                }
                .lp-icon i { color: #fff; font-size: 26px; }
                .lp-modal h2 {
                    color: #fff;
                    font-size: 26px; font-weight: 800;
                    margin: 0 0 10px;
                    letter-spacing: -.4px;
                }
                .lp-modal p {
                    color: #cbd5e1;
                    font-size: 14.5px; line-height: 1.55;
                    margin: 0 0 22px;
                    max-width: 360px; margin-left: auto; margin-right: auto;
                }
                .lp-cta {
                    display: block;
                    background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
                    color: #fff !important;
                    padding: 14px 18px;
                    border-radius: 10px;
                    font-weight: 800; font-size: 15px;
                    text-decoration: none !important;
                    letter-spacing: .2px;
                    transition: filter .15s ease, transform .12s ease;
                    box-shadow: 0 6px 16px rgba(27, 58, 107, .35);
                }
                .lp-cta:hover { filter: brightness(1.05); transform: translateY(-1px); }
                .lp-secondary {
                    display: block;
                    color: #cbd5e1 !important;
                    font-size: 13px;
                    text-decoration: none !important;
                    margin-top: 14px;
                }
                .lp-secondary:hover { color: #1b3a6b !important; }
                .lp-dismiss {
                    display: block;
                    width: 100%;
                    background: transparent;
                    border: none;
                    color: rgba(255, 255, 255, .55);
                    font-size: 12.5px; font-weight: 600;
                    margin-top: 18px;
                    padding: 6px;
                    cursor: pointer;
                    text-decoration: underline;
                    transition: color .15s ease;
                }
                .lp-dismiss:hover { color: #fff; }
                @media (max-width: 480px) {
                    .lp-modal { padding: 30px 22px 22px; }
                    .lp-modal h2 { font-size: 22px; }
                }
            </style>
            <script>
                (function () {
                    var STORAGE_KEY = 'lpShownThisSession';
                    if (sessionStorage.getItem(STORAGE_KEY) === '1') return;

                    var overlay = document.getElementById('loginPromptModal');
                    if (!overlay) return;

                    function open() {
                        overlay.classList.add('is-open');
                        overlay.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                        sessionStorage.setItem(STORAGE_KEY, '1');
                    }
                    function close() {
                        overlay.classList.remove('is-open');
                        overlay.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }

                    document.getElementById('lpClose').addEventListener('click', close);
                    document.getElementById('lpDismiss').addEventListener('click', close);
                    overlay.addEventListener('click', function (e) {
                        if (e.target === overlay) close();
                    });
                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape' && overlay.classList.contains('is-open')) close();
                    });

                    setTimeout(open, 10000);
                })();
            </script>
        @endguest

</body>

<!-- Mirrored from jobword.utouchdesign.com/jobword_ltr/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 06 Apr 2025 10:26:10 GMT -->

<!-- Mirrored from www.jobword.flarza.com/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Oct 2025 12:16:19 GMT -->

</html>
