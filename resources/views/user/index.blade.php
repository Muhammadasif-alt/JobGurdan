@extends('user.layouts.master')
@section('title', 'JobGader | Jobs with Visa Sponsorship — USA, UK & Pakistan')
@section('meta_description', 'Verified jobs across the '.$coverage->shortList().', including roles that sponsor foreign workers. Plus honest guides on which visa routes are actually open. Free to apply, no account needed.')
@section('meta_keywords', 'jobs with visa sponsorship, jobs in usa, jobs in uk, jobs in pakistan, visa sponsorship jobs, H-2B visa jobs, EB-3 visa jobs, work abroad, job search, apply free, jobs for foreigners')
@section('og_title', 'JobGader | Jobs with Visa Sponsorship — USA, UK & Pakistan')
@section('og_description', 'Verified jobs across the '.$coverage->shortList().', including visa-sponsored roles — plus honest guides on which visa routes are open. Free to apply.')
@section('og_image', asset('public/user/images/home-background-03.jpg'))
@section('canonical', url('/'))

@push('meta')
    {{-- Twitter card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="JobGader — Find Verified Jobs Across All 50 States">
    <meta name="twitter:description" content="Verified jobs across the {{ $coverage->shortList() }}, including visa-sponsored roles. Free for job seekers.">
    <meta name="twitter:image" content="{{ asset('public/user/images/home-background-03.jpg') }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="JobGader">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="JobGader">
    <meta property="og:locale" content="en_US">

    {{-- JSON-LD: WebSite with SearchAction --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "JobGader",
        "url": "{{ url('/') }}",
        "description": "A job search platform connecting verified employers with job seekers across the {{ $coverage->shortList() }}, with a focus on roles open to foreign workers.",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": {
                "@@type": "EntryPoint",
                "urlTemplate": "{{ url('/jobs') }}?position={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    {{-- JSON-LD: Organization --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "JobGader",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('public/user/images/JobGader.png') }}",
        "description": "Verified online employment platform connecting job seekers with hiring employers across the {{ $coverage->shortList() }}.",
        "areaServed": {!! json_encode($coverage->areaServedNodes(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!},
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "Customer Support",
            "email": "{{ config('site.contact_email') }}",
            "availableLanguage": ["English"]
        }
    }
    </script>

    {{-- JSON-LD: FAQPage (matches the visible FAQ section on this page) --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "Is it free to search and apply for jobs?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Yes — searching and applying is completely free, and you do not even need an account. Open any listing and apply straight through. There are no fees, no paywalls and no charges for job seekers, ever." }
            },
            {
                "@@type": "Question",
                "name": "Do I need to create an account to apply?",
                "acceptedAnswer": { "@@type": "Answer", "text": "No. Every listing on JobGader can be opened and applied to without signing up. An account is optional and only useful if you want to save roles or come back to them later." }
            },
            {
                "@@type": "Question",
                "name": "Which countries and industries do you cover?",
                "acceptedAnswer": { "@@type": "Answer", "text": "We cover the {{ $coverage->shortList() }} across a wide range of industries — healthcare, IT, construction, retail, hospitality, transport, cleaning and more — including roles that sponsor foreign workers." }
            },
            {
                "@@type": "Question",
                "name": "Do you list jobs with visa sponsorship?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Yes, and we are honest about which routes are genuinely open. Some, like US truck driving and hospitality on H-2B and EB-3, still sponsor foreign workers. Others, like the UK care worker route, closed to new overseas applicants in July 2025 — our guides say so plainly instead of promising a visa that no longer exists." }
            },
            {
                "@@type": "Question",
                "name": "How do I know a listing is genuine?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Every listing is added and checked by our team rather than scraped automatically, and each one links through to the employer or the original posting so you can verify it yourself before applying." }
            },
            {
                "@@type": "Question",
                "name": "Do I ever have to pay a recruiter for sponsorship?",
                "acceptedAnswer": { "@@type": "Answer", "text": "No. Charging a worker for visa sponsorship is illegal in both the US and the UK. If a recruiter asks for an upfront fee to guarantee a job or a visa, treat it as a scam and walk away." }
            },
            {
                "@@type": "Question",
                "name": "How often are new jobs and guides added?",
                "acceptedAnswer": { "@@type": "Answer", "text": "New listings and visa guides go up every week. The newest openings always appear at the top of the home page and on the jobs board." }
            },
            {
                "@@type": "Question",
                "name": "What if I need help with an application?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Visit our Contact page and our team will get back to you. We can point you to the right guide, but we are not immigration advisers — always confirm visa rules on the official government site." }
            }
        ]
    }
    </script>
@endpush

@section('content')

    <!-- Intro Banner -->
    <style>
        /* === Hero with home.jpg background + light overlay === */
        .intro-banner.intro-hero-v2 {
            background-image: url('{{ asset('public/user/images/home.jpg') }}') !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            position: relative;
            overflow: hidden;
            padding: 140px 0 130px !important;
            min-height: 720px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f0f0f3;
        }
        @media (max-width: 991px) {
            .intro-banner.intro-hero-v2 { min-height: auto; padding: 80px 0 70px !important; display: block; }
        }
        /* Light overlay on top of image — keeps content readable */
        .intro-banner.intro-hero-v2::before,
        .intro-banner.intro-hero-v2::after {
            content: "";
            position: absolute;
            inset: 0;
            top: 0; left: 0;
            height: 100%; width: 100%;
            opacity: 1 !important;
            z-index: 1 !important;
            pointer-events: none;
        }
        .intro-banner.intro-hero-v2::before {
            background: linear-gradient(135deg,
                rgba(247,244,239,0.90) 0%,
                rgba(247,244,239,0.82) 40%,
                rgba(255,255,255,0.95) 100%) !important;
        }
        .intro-banner.intro-hero-v2::after { display: none !important; }
        .intro-banner.intro-hero-v2 .container { position: relative; z-index: 100 !important; }

        /* Floating decorative blobs — hidden (image background replaces them) */
        .hero-blob { display: none !important; position: absolute; border-radius: 50%; filter: blur(80px); opacity: .15; z-index: 1; }
        .hero-blob.b1 { width: 360px; height: 360px; background: #1b3a6b; top: -120px; right: -80px; animation: floaty 9s ease-in-out infinite; }
        .hero-blob.b2 { width: 280px; height: 280px; background: #5e2bff; bottom: -100px; left: -60px; animation: floaty 11s ease-in-out infinite reverse; }
        @keyframes floaty {
            0%, 100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-22px) scale(1.06); }
        }

        /* Eyebrow badge — clean white pill */
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1px solid #e5e5e7;
            color: #555;
            font-size: 13px;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 999px;
            box-shadow: 0 1px 2px rgba(15,23,42,.04);
            margin-bottom: 22px;
            letter-spacing: .3px;
        }
        .hero-eyebrow .pulse-dot {
            width: 7px; height: 7px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(34,197,94,.5);
            animation: heroPulse 1.6s infinite;
        }
        @keyframes heroPulse {
            0%   { box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
            70%  { box-shadow: 0 0 0 8px rgba(34,197,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        }

        /* Headline — dark navy ManageWP style */
        .intro-banner.intro-hero-v2 .utf-banner-headline-text-part { text-align: center; max-width: 980px; margin: 0 auto; }
        .intro-banner.intro-hero-v2 .utf-banner-headline-text-part h1 {
            color: #1b3a6b !important;
            font-size: clamp(26px, 5vw, 64px) !important;
            font-weight: 800 !important;
            line-height: 1.12 !important;
            letter-spacing: -1.2px !important;
            margin: 0 0 18px !important;
            text-shadow: none !important;
            overflow-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
        }
        /* Clean responsive — based on companies page approach which works flawlessly */
        @media (max-width: 991px) {
            .hero-visual { display: none !important; }
            .hero-2col {
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 30px !important;
                padding: 0 16px !important;
            }
            .hero-content { min-width: 0; }
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part {
                max-width: 100% !important;
                padding: 0 !important;
            }
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part h1 {
                font-size: clamp(22px, 5vw, 36px) !important;
                letter-spacing: -.4px !important;
                line-height: 1.2 !important;
            }
            .intro-banner.intro-hero-v2 .hero-eyebrow {
                font-size: 11.5px;
                line-height: 1.4;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part > span:not(.hero-eyebrow) {
                font-size: 14.5px !important;
                line-height: 1.6 !important;
            }
        }
        @media (max-width: 575px) {
            .intro-banner.intro-hero-v2 { padding: 50px 0 40px !important; }
            .hero-2col { padding: 0 14px !important; gap: 24px !important; }
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part h1 {
                font-size: 22px !important;
                letter-spacing: -.2px !important;
            }
            .intro-banner.intro-hero-v2 .hero-eyebrow {
                font-size: 10.5px;
                padding: 5px 10px;
                max-width: 100%;
                white-space: normal;
                text-align: center;
                display: flex !important;
                width: fit-content;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part > span:not(.hero-eyebrow) {
                font-size: 13.5px !important;
            }
            .hero-stats { flex-wrap: wrap !important; gap: 14px !important; }
            .hero-stats .divider { display: none !important; }
            .hero-stats .stat { min-width: calc(50% - 7px) !important; }
        }
        @media (max-width: 480px) {
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part h1 {
                font-size: 21px !important;
                letter-spacing: -.3px !important;
            }
            .intro-banner.intro-hero-v2 { padding: 50px 0 40px !important; }
            .intro-banner.intro-hero-v2 .container {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
            .intro-banner.intro-hero-v2 .hero-eyebrow { font-size: 10.5px !important; padding: 5px 9px !important; }
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part > span { font-size: 13px !important; }
        }
        .intro-banner.intro-hero-v2 .utf-banner-headline-text-part h1 .accent {
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            display: inline-block !important;
            margin: 0 !important;
            padding: 0 2px !important;
            background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #4a90d9) !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            color: transparent !important;
            overflow: visible !important;
        }
        @media (min-width: 768px) {
            .intro-banner.intro-hero-v2 .utf-banner-headline-text-part h1 .accent { white-space: nowrap; }
        }
        .intro-banner.intro-hero-v2 .utf-banner-headline-text-part > span {
            display: block !important;
            color: #555 !important;
            font-size: clamp(16px, 1.5vw, 18px) !important;
            line-height: 1.65 !important;
            font-weight: 400 !important;
            max-width: 720px;
            margin: 0 auto !important;
        }
        .intro-banner.intro-hero-v2 .hero-eyebrow {
            font-size: 13px !important;
            line-height: 1 !important;
            margin: 0 auto 22px !important;
            display: inline-flex !important;
            text-align: center !important;
            justify-content: center !important;
        }
        .intro-banner.intro-hero-v2 .hero-eyebrow .pulse-dot {
            font-size: 0 !important;
            line-height: 0 !important;
            margin: 0 !important;
            display: inline-block !important;
        }

        /* Search form — clean white card with subtle border */
        .intro-banner.intro-hero-v2 .utf-intro-banner-search-form-block {
            background: #fff !important;
            border: 1px solid #e5e5e7 !important;
            border-radius: 14px !important;
            padding: 8px !important;
            box-shadow: 0 8px 24px rgba(15,23,42,.06) !important;
            max-width: 880px;
            margin: 40px auto 0 !important;
            display: flex !important;
            gap: 6px;
            align-items: stretch;
            width: 100% !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item {
            border: none !important;
            background: transparent !important;
            flex: 1;
            position: relative;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item input,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn {
            height: 54px !important;
            border: none !important;
            background: transparent !important;
            font-size: 15px;
            color: #16305a !important;
            padding-left: 44px !important;
            box-shadow: none !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn { padding-top: 18px !important; }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 18px;
            z-index: 2;
            pointer-events: none;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item + .utf-intro-search-field-item {
            border-left: 1px solid #ececec !important;
        }
        /* Bootstrap-select trigger button — clean alignment with input */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select {
            width: 100% !important;
            position: static !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            text-align: left !important;
            padding: 0 36px 0 44px !important;
            outline: none !important;
            border-radius: 0 !important;
            color: #16305a !important;
            width: 100% !important;
            background: transparent !important;
            position: relative !important;
        }
        /* Force the filter-option chain into flex flow so the placeholder sits right after the pin icon */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn .filter-option {
            position: static !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            flex: 1 1 auto !important;
            width: auto !important;
            height: auto !important;
            top: auto !important;
            left: auto !important;
            right: auto !important;
            bottom: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            text-align: left !important;
            color: #16305a !important;
            line-height: 1 !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn .filter-option-inner {
            position: static !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            width: auto !important;
            text-align: left !important;
            padding: 0 !important;
            margin: 0 !important;
            line-height: 1 !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn .filter-option-inner-inner {
            color: #16305a !important;
            font-weight: 500 !important;
            font-size: 15px !important;
            line-height: 1 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            text-align: left !important;
            padding: 0 !important;
            margin: 0 !important;
            display: inline-block !important;
        }
        /* Hide the bootstrap-select count badge that appears as a small box */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-ok-default,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .filter-option-inner-inner > .badge,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .filter-option-inner > .badge {
            display: none !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select.show > .btn,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn:focus {
            box-shadow: none !important;
            outline: none !important;
        }
        /* Custom chevron using a CSS-drawn arrow — no font dependency */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn::after {
            content: "" !important;
            display: inline-block !important;
            width: 8px;
            height: 8px;
            border: solid #6b7280;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            margin-left: auto;
            margin-right: 4px;
            margin-top: -4px;
            transition: transform .15s ease;
            vertical-align: middle;
            background: transparent !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select.show > .btn::after {
            transform: rotate(-135deg);
            margin-top: 2px;
        }
        /* Hide every native bootstrap-select indicator/caret/checkmark — we render our own arrow */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-clearfix,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-caret,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .caret,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-ok-default,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .check-mark,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn > span.bs-caret,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select > .btn .badge {
            display: none !important;
        }
        /* Make sure the .filter-option doesn't render a visible box of its own */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .filter-option,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .filter-option-inner {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Bootstrap-select dropdown panel — drops DOWN (below the trigger) */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu {
            margin: 8px 0 0 0 !important;
            border: 1px solid #ececec !important;
            border-radius: 12px !important;
            box-shadow: 0 16px 40px rgba(15,23,42,.12) !important;
            padding: 6px !important;
            min-width: 280px !important;
            width: 100% !important;
            background: #fff !important;
            background-color: #fff !important;
            max-height: 340px !important;
            left: 0 !important;
            right: auto !important;
            transform: none !important;
            /* Anchor at the bottom edge of the trigger so the menu opens DOWNWARD */
            top: 100% !important;
            bottom: auto !important;
            /* Above hero stats / trending which have z-index 2 from global rule */
            z-index: 1050 !important;
        }
        /* When dropdown is open, raise the WHOLE bootstrap-select wrapper so its menu sits above siblings */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select.show,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select.open {
            z-index: 1050 !important;
            position: relative !important;
        }
        /* Same for the search form block so the entire search area can overflow above stats */
        .intro-banner.intro-hero-v2 .utf-intro-banner-search-form-block {
            position: relative;
            z-index: 100;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu.show {
            position: absolute !important;
            z-index: 1050 !important;
        }
        /* When menu is open below the trigger, flip chevron to point UP (collapse hint) */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select.show > .btn::after {
            transform: rotate(-135deg);
            margin-top: 2px;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox {
            padding: 8px 8px 10px !important;
            position: relative;
        }
        /* Magnifier icon inside the search input (left side) */
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox::before {
            content: "";
            position: absolute;
            left: 22px;
            top: 50%;
            margin-top: -1px;
            width: 14px;
            height: 14px;
            border: 2px solid #6b7280;
            border-radius: 50%;
            box-sizing: border-box;
            pointer-events: none;
            z-index: 2;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox::after {
            content: "";
            position: absolute;
            left: 32px;
            top: 50%;
            margin-top: 7px;
            width: 6px;
            height: 2px;
            background: #6b7280;
            transform: rotate(45deg);
            transform-origin: left center;
            pointer-events: none;
            z-index: 2;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox .form-control {
            height: 42px !important;
            border: 1px solid #ececec !important;
            border-radius: 8px !important;
            background: #f8faff !important;
            padding: 0 14px 0 38px !important;
            font-size: 14px !important;
            color: #16305a !important;
            box-shadow: none !important;
            text-align: left !important;
            width: 100% !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox .form-control::placeholder {
            color: #9ca3af !important;
            opacity: 1;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox .form-control:focus {
            border-color: #1b3a6b !important;
            background: #fff !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu .inner {
            padding: 4px !important;
            max-height: 240px !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > a,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > .dropdown-item {
            display: flex !important;
            align-items: center;
            padding: 9px 12px !important;
            border-radius: 8px !important;
            color: #16305a !important;
            font-size: 14.5px !important;
            font-weight: 500 !important;
            background: transparent !important;
            transition: background .12s ease, color .12s ease;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > a:hover,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > .dropdown-item:hover,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > a:focus,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > .dropdown-item:focus {
            background: #f3f4f6 !important;
            color: #1b3a6b !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li.selected > a,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li.active > a,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > .dropdown-item.active {
            background: #1b3a6b !important;
            color: #fff !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu .text { color: inherit !important; }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .no-results {
            padding: 12px !important;
            color: #6b7280 !important;
            background: transparent !important;
            font-size: 14px;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-button { flex: 0 0 auto; }
        .intro-banner.intro-hero-v2 .utf-intro-search-button .button {
            background: #1b3a6b !important;
            border: none !important;
            color: #fff !important;
            border-radius: 10px !important;
            height: 54px !important;
            padding: 0 28px !important;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: .2px;
            box-shadow: none;
            transition: all .15s ease;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-button .button:hover {
            background: #16305a !important;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0,0,0,.15);
        }

        /* CTA buttons (primary + outline) — ManageWP style */
        .hero-cta-row {
            display: inline-flex;
            gap: 12px;
            margin-top: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .hero-cta-row .btn-cta-dark,
        .hero-cta-row .btn-cta-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none !important;
            transition: all .15s ease;
        }
        .hero-cta-row .btn-cta-dark {
            background: #1b3a6b;
            color: #fff !important;
            border: 1.5px solid #1b3a6b;
        }
        .hero-cta-row .btn-cta-dark:hover {
            background: #16305a;
            border-color: #16305a;
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(0,0,0,.18);
        }
        .hero-cta-row .btn-cta-outline {
            background: #fff;
            color: #1b3a6b !important;
            border: 1.5px solid #1b3a6b;
        }
        .hero-cta-row .btn-cta-outline:hover {
            background: #1b3a6b;
            color: #fff !important;
            transform: translateY(-2px);
        }

        /* Trust list — light text */
        .intro-banner.intro-hero-v2 .hero-trust-list {
            list-style: none;
            padding: 0;
            margin: 26px 0 0 !important;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px 28px;
        }
        .intro-banner.intro-hero-v2 .hero-trust-list li {
            color: #555;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .intro-banner.intro-hero-v2 .hero-trust-list li i {
            color: #22c55e;
            font-size: 16px;
        }

        /* Stats strip — dark text */
        .hero-stats {
            display: flex;
            gap: 28px;
            justify-content: center;
            flex-wrap: wrap;
            margin: 44px auto 0;
            max-width: 880px;
            padding-top: 36px;
            border-top: 1px solid #ececec;
        }
        .hero-stats .stat {
            text-align: center;
            color: #1b3a6b;
            min-width: 140px;
        }
        .hero-stats .stat strong {
            display: block;
            font-size: 30px;
            font-weight: 800;
            line-height: 1.1;
            background: none;
            -webkit-background-clip: initial;
            background-clip: initial;
            color: #1b3a6b;
            -webkit-text-fill-color: initial;
            letter-spacing: -.5px;
        }
        .hero-stats .stat span {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #777;
            font-weight: 600;
            margin-top: 4px;
            display: inline-block;
        }
        .hero-stats .divider { width: 1px; background: #ececec; }

        /* Mobile */
        @media (max-width: 768px) {
            .intro-banner.intro-hero-v2 { padding: 56px 0 50px; }
            .intro-banner.intro-hero-v2 .utf-intro-banner-search-form-block { flex-direction: column; padding: 12px !important; }
            .intro-banner.intro-hero-v2 .utf-intro-search-field-item + .utf-intro-search-field-item { border-left: none !important; border-top: 1px solid #e5e7eb !important; }
            .intro-banner.intro-hero-v2 .utf-intro-search-button .button { width: 100%; }
            .hero-stats .divider { display: none; }
            .hero-stats { gap: 18px; }
            .hero-stats .stat { min-width: calc(50% - 18px); }
        }

        /* =================================================================
           HERO 2-COL LAYOUT — content left, MacBook mockup right
           ================================================================= */
        .hero-2col {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0;
            justify-items: center;
            align-items: center;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .hero-2col .hero-content { width: 100%; max-width: 880px; text-align: center; }
        .hero-2col .hero-trending,
        .hero-2col .hero-stats { justify-content: center; }
        .hero-2col form.utf-intro-banner-search-form-block { margin-left: auto; margin-right: auto; }

        /* Override centered alignment from base hero CSS */
        .intro-banner.intro-hero-v2.hero-split .utf-banner-headline-text-part {
            text-align: center !important;
            max-width: none !important;
            margin: 0 !important;
        }
        .intro-banner.intro-hero-v2.hero-split .utf-banner-headline-text-part > span:not(.hero-eyebrow):not(.accent) {
            margin: 0 0 28px !important;
        }
        .intro-banner.intro-hero-v2.hero-split .utf-intro-banner-search-form-block {
            margin: 24px 0 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        /* Trending + stats: stretch to same column width as search box for visual alignment */
        .intro-banner.intro-hero-v2.hero-split .hero-trending,
        .intro-banner.intro-hero-v2.hero-split .hero-stats {
            width: 100%;
            gap: 18px !important;
            flex-wrap: nowrap !important;
            justify-content: space-between !important;
        }
        .intro-banner.intro-hero-v2.hero-split .hero-stats .stat {
            min-width: 0 !important;
            flex: 1 1 0 !important;
            text-align: left;
        }
        .intro-banner.intro-hero-v2.hero-split .hero-stats .stat strong {
            font-size: 22px !important;
            letter-spacing: -.3px;
        }
        .intro-banner.intro-hero-v2.hero-split .hero-stats .stat span {
            font-size: 11px !important;
            letter-spacing: .8px;
        }
        .intro-banner.intro-hero-v2.hero-split .hero-stats .divider { display: none !important; }
        /* Tablet/mobile: allow stats to wrap (2x2) */
        @media (max-width: 768px) {
            .intro-banner.intro-hero-v2.hero-split .hero-stats {
                flex-wrap: wrap !important;
            }
            .intro-banner.intro-hero-v2.hero-split .hero-stats .stat {
                flex: 0 0 calc(50% - 12px) !important;
            }
        }
        @media (max-width: 991px) {
            .intro-banner.intro-hero-v2.hero-split .utf-banner-headline-text-part {
                text-align: center !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }

        /* Trending tags row */
        .hero-trending {
            margin-top: 22px;
            display: flex; align-items: center;
            gap: 8px; flex-wrap: wrap;
        }
        .hero-trending .trending-label {
            font-size: 13px; font-weight: 700;
            color: #555;
            text-transform: none;
            margin-right: 4px;
        }
        .hero-trending .trending-tag {
            display: inline-flex; align-items: center;
            background: #fff;
            border: 1px solid #e5e5e7;
            color: #1b3a6b;
            font-size: 13px; font-weight: 500;
            padding: 7px 14px;
            border-radius: 999px;
            text-decoration: none;
            transition: all .15s ease;
        }
        .hero-trending .trending-tag:hover {
            background: #1b3a6b; color: #fff; border-color: #1b3a6b;
            transform: translateY(-1px);
        }

        /* Compact stats row for the split hero */
        .intro-banner.intro-hero-v2.hero-split .hero-stats {
            border-top: 1px solid rgba(27, 58, 107,.10);
            margin-top: 36px;
            padding-top: 24px;
            justify-content: flex-start;
            gap: 36px;
        }
        @media (max-width: 991px) {
            .intro-banner.intro-hero-v2.hero-split .hero-stats { justify-content: center; }
        }

        /* === VISUAL: MacBook mockup + floating cards === */
        .hero-visual {
            position: relative;
            padding: 30px 20px 40px;
        }
        @media (max-width: 991px) { .hero-visual { padding: 10px 0 20px; max-width: 540px; margin: 0 auto; } }

        .macbook {
            position: relative;
            background: linear-gradient(180deg, #2c2c30 0%, #1f1f23 100%);
            border-radius: 16px 16px 6px 6px;
            padding: 12px 12px 14px;
            box-shadow:
                0 30px 60px rgba(15,23,42,.22),
                0 12px 24px rgba(15,23,42,.14),
                inset 0 1px 0 rgba(255,255,255,.06);
            z-index: 2;
        }
        /* MacBook "base" lip */
        .macbook::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: -3.5%;
            right: -3.5%;
            height: 12px;
            background: linear-gradient(180deg, #3a3a3e 0%, #1c1c1f 80%);
            border-radius: 0 0 16px 16px / 0 0 100% 100%;
            z-index: 1;
        }
        .macbook-screen {
            background: #fff;
            border-radius: 7px;
            overflow: hidden;
            position: relative;
        }
        .macbook-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 9px 12px;
            background: #ececec;
            border-bottom: 1px solid #e0e0e0;
        }
        .macbook-dot { width: 11px; height: 11px; border-radius: 50%; flex-shrink: 0; }
        .macbook-dot.red    { background: #ff5f57; }
        .macbook-dot.yellow { background: #febc2e; }
        .macbook-dot.green  { background: #28c840; }

        /* Inner UI — looks like the actual job board */
        .mb-page { padding: 14px 16px 18px; background: #fff; }
        .mb-header {
            display: flex; align-items: center; justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f3;
        }
        .mb-logo { font-size: 14px; font-weight: 800; letter-spacing: -.3px; }
        .mb-logo .a { color: #1b3a6b; }
        .mb-logo .b { color: #2a41e8; }
        .mb-nav { display: flex; gap: 14px; font-size: 10.5px; color: #555; font-weight: 500; }
        .mb-search {
            margin-top: 12px;
            background: #f5f5f7;
            border-radius: 7px;
            padding: 8px 12px;
            font-size: 10px; color: #999;
            display: flex; align-items: center; gap: 6px;
        }
        .mb-jobs {
            margin-top: 10px;
            display: flex; flex-direction: column;
            gap: 6px;
        }
        .mb-job {
            display: flex; align-items: center; gap: 10px;
            padding: 7px 9px;
            border: 1px solid #f0f0f3;
            border-radius: 7px;
            background: #fff;
        }
        .mb-job-logo {
            width: 24px; height: 24px;
            background: #e9f3fc;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }
        .mb-job-info { flex: 1; min-width: 0; }
        .mb-job-title {
            font-size: 10px; font-weight: 700; color: #1b3a6b;
            line-height: 1.3;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .mb-job-meta { font-size: 9px; color: #777; margin-top: 1px; }
        .mb-badge {
            font-size: 8.5px; font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            white-space: nowrap;
            flex-shrink: 0;
            letter-spacing: .2px;
        }
        .mb-badge.new    { background: #dcfce7; color: #15803d; }
        .mb-badge.hot    { background: #fee2e2; color: #b91c1c; }
        .mb-badge.remote { background: #dbeafe; color: #1d4ed8; }

        /* Floating cards over the MacBook */
        .hero-float {
            position: absolute;
            background: #fff;
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: 0 14px 30px rgba(15,23,42,.10), 0 4px 10px rgba(15,23,42,.05);
            display: flex; align-items: center; gap: 12px;
            z-index: 5;
            min-width: 200px;
        }
        .hero-float.tech   { top: 28px; right: -8px; animation: heroFloat 4s ease-in-out infinite; }
        .hero-float.health { top: 50%; left: -14px; transform: translateY(-50%); animation: heroFloat 4s ease-in-out 2s infinite; }
        @keyframes heroFloat {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-7px); }
        }
        .hero-float.health { animation: heroFloatMid 4s ease-in-out 2s infinite; }
        @keyframes heroFloatMid {
            0%, 100% { transform: translateY(-50%); }
            50%      { transform: translateY(calc(-50% - 7px)); }
        }
        @media (max-width: 575px) {
            .hero-float.tech   { top: 6px; right: 6px; min-width: 0; padding: 8px 10px; }
            .hero-float.health { display: none; }
        }
        .float-ico {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .float-ico.tech   { background: #dbeafe; color: #1d4ed8; }
        .float-ico.health { background: #dcfce7; color: #15803d; }
        .float-title { font-size: 13.5px; font-weight: 800; color: #1b3a6b; line-height: 1.1; }
        .float-meta  { font-size: 11.5px; color: #6b7280; margin-top: 3px; }
        .float-growth {
            margin-left: 8px;
            background: #dcfce7; color: #15803d;
            font-size: 11px; font-weight: 800;
            padding: 3px 8px; border-radius: 999px;
            white-space: nowrap;
        }
        @media (max-width: 575px) {
            .float-title { font-size: 11px; }
            .float-meta { font-size: 10px; }
            .float-ico { width: 30px; height: 30px; font-size: 14px; }
        }
    </style>

    <div class="intro-banner intro-hero-v2 hero-split">
        <div class="hero-blob b1"></div>
        <div class="hero-blob b2"></div>

        <div class="container">
            <div class="hero-2col">
                {{-- ============ LEFT: Content ============ --}}
                <div class="hero-content">
                    <div class="utf-banner-headline-text-part">
                        <span class="hero-eyebrow" data-aos="fade-down" data-aos-duration="600">
                            <span class="pulse-dot"></span>
                            New jobs and visa guides added every week
                        </span>
                        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                            Find Jobs Abroad
                            <span class="accent">With Visa Sponsorship</span>
                        </h1>
                        <span data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">
                            Verified openings across the {{ $coverage->shortList() }} &mdash; plus straight answers on which visa routes are actually open, and which ones aren't. Free to apply, no account needed.
                        </span>
                    </div>

                    <form method="GET" action="{{ route('jobs.index') }}" role="search"
                        data-aos="fade-up" data-aos-duration="700" data-aos-delay="400"
                        class="utf-intro-banner-search-form-block margin-top-40">
                        <div class="utf-intro-search-field-item with-autocomplete">
                            <input id="intro-keywords" name="position" type="text"
                                placeholder="Search jobs, skills…"
                                autocomplete="off"
                                data-autocomplete="jobs"
                                value="{{ request('position') }}">
                            <i class="icon-feather-search"></i>
                        </div>
                        <div class="utf-intro-search-field-item">
                            <select name="location" class="selectpicker default location-select" data-live-search="true"
                                data-live-search-placeholder="Search states…"
                                data-size="6" title="Select Location" data-width="100%"
                                data-dropup-auto="false">
                                <option value="">All Locations</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->name }}"
                                        {{ request('location') == $location->name ? 'selected' : '' }}>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                            <i class="icon-material-outline-location-on"></i>
                        </div>
                        <div class="utf-intro-search-button">
                            <button class="button ripple-effect" type="submit">
                                <i class="icon-material-outline-search"></i> Search Jobs
                            </button>
                        </div>
                    </form>

                    {{-- Trending tag chips --}}
                    <div class="hero-trending" data-aos="fade-up" data-aos-duration="600" data-aos-delay="550">
                        <span class="trending-label">Trending:</span>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Visa+Sponsorship">Visa Sponsorship</a>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Truck+Driver">Truck Driver</a>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Hotel">Hotel Jobs</a>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Cleaner">Cleaner</a>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Developer">Developer</a>
                    </div>

                    @php
                        // Raw counts read badly while the board is still filling up
                        // ("2+ Open Jobs"), so the live figures only appear once
                        // there are enough of them to be worth showing.
                        $showLiveCounts = ($stats['total_jobs'] ?? 0) >= 500;
                    @endphp
                    <div class="hero-stats">
                        @if ($showLiveCounts)
                            <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="700">
                                <strong>{{ number_format($stats['total_jobs']) }}+</strong>
                                <span>Open Jobs</span>
                            </div>
                            <div class="divider"></div>
                            <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="800">
                                <strong>{{ number_format($stats['total_companies'] ?? 0) }}+</strong>
                                <span>Employers</span>
                            </div>
                        @else
                            <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="700">
                                <strong>{{ $stats['total_countries'] ?? 3 }}</strong>
                                <span>Countries Covered</span>
                            </div>
                            <div class="divider"></div>
                            <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="800">
                                <strong>Weekly</strong>
                                <span>New Openings</span>
                            </div>
                        @endif
                        <div class="divider"></div>
                        <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="900">
                            <strong>Visa</strong>
                            <span>Sponsored Roles</span>
                        </div>
                        <div class="divider"></div>
                        <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="1000">
                            <strong>100%</strong>
                            <span>Free to Apply</span>
                        </div>
                    </div>
                </div>

                {{-- Hero visual mockup removed per client request --}}
                @if (false)
                <div class="hero-visual" data-aos="fade-left" data-aos-duration="900" data-aos-delay="200" aria-hidden="true">

                    {{-- Floating: Healthcare (mid-left of macbook) --}}
                    <div class="hero-float health">
                        <div class="float-ico health">🩺</div>
                        <div>
                            <div class="float-title">Healthcare</div>
                            <div class="float-meta">2,340 open roles</div>
                        </div>
                        <span class="float-growth">+12%</span>
                    </div>

                    {{-- MacBook frame --}}
                    <div class="macbook">
                        <div class="macbook-screen">
                            <div class="macbook-bar">
                                <span class="macbook-dot red"></span>
                                <span class="macbook-dot yellow"></span>
                                <span class="macbook-dot green"></span>
                            </div>
                            <div class="mb-page">
                                <div class="mb-header">
                                    <div class="mb-logo"><span class="a">jobs</span><span class="b">inusa</span></div>
                                    <div class="mb-nav">
                                        <span>Jobs</span>
                                        <span>Employers</span>
                                        <span>Sign In</span>
                                    </div>
                                </div>
                                <div class="mb-search">
                                    🔍 Search jobs, skills, titles…
                                </div>
                                <div class="mb-jobs">
                                    <div class="mb-job">
                                        <div class="mb-job-logo">🏥</div>
                                        <div class="mb-job-info">
                                            <div class="mb-job-title">Registered Nurse – ICU</div>
                                            <div class="mb-job-meta">NYU Langone · New York, NY</div>
                                        </div>
                                        <span class="mb-badge new">New</span>
                                    </div>
                                    <div class="mb-job">
                                        <div class="mb-job-logo">🚛</div>
                                        <div class="mb-job-info">
                                            <div class="mb-job-title">CDL Truck Driver</div>
                                            <div class="mb-job-meta">Amazon Logistics · Dallas, TX</div>
                                        </div>
                                        <span class="mb-badge hot">Hot</span>
                                    </div>
                                    <div class="mb-job">
                                        <div class="mb-job-logo">💻</div>
                                        <div class="mb-job-info">
                                            <div class="mb-job-title">Frontend Engineer</div>
                                            <div class="mb-job-meta">Stripe · Remote (US)</div>
                                        </div>
                                        <span class="mb-badge remote">Remote</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating: Tech / IT (top-right of macbook) --}}
                    <div class="hero-float tech">
                        <div class="float-ico tech">💻</div>
                        <div>
                            <div class="float-title">Tech / IT</div>
                            <div class="float-meta">1,820 open roles</div>
                        </div>
                    </div>

                </div>
                @endif
            </div>

        </div>
    </div>


    {{-- Latest Jobs — 4 per row x 2 rows (8 total), with a link through to the full board --}}
    @if ($featuredJobs->isNotEmpty())
        <style>
            .home-jobs-section {
                padding: 90px 0 80px;
                background: #ffffff;
                position: relative;
            }
            .home-jobs-section .section-head {
                text-align: center;
                max-width: 780px;
                margin: 0 auto 46px;
            }
            .home-jobs-section .section-tag {
                display: inline-block;
                background: #1b3a6b;
                border: none;
                color: #fff;
                font-weight: 700;
                font-size: 12.5px;
                padding: 10px 24px;
                border-radius: 999px;
                letter-spacing: 1.2px;
                text-transform: uppercase;
                margin-bottom: 20px;
            }
            .home-jobs-section h2 {
                font-size: 40px;
                line-height: 1.18;
                font-weight: 800;
                color: #0f2544;
                margin: 0 0 14px;
                letter-spacing: -.5px;
            }
            .home-jobs-section h2 .accent { color: #2f7fc9; }
            .home-jobs-section .section-head p {
                font-size: 16px;
                line-height: 1.7;
                color: #5b6b80;
                margin: 0;
            }
            .home-jobs-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 22px;
            }
            .home-job-card {
                display: flex;
                flex-direction: column;
                background: #fff;
                border: 1.5px solid #e3ecf6;
                border-radius: 16px;
                padding: 22px 20px 20px;
                text-decoration: none !important;
                transition: all .2s ease;
                height: 100%;
            }
            .home-job-card:hover {
                border-color: #2f7fc9;
                transform: translateY(-4px);
                box-shadow: 0 16px 32px rgba(27, 58, 107, .14);
            }
            .home-job-card .card-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 10px;
                margin-bottom: 16px;
            }
            .home-job-card .logo {
                width: 52px; height: 52px;
                border-radius: 12px;
                background: #f2f7fd;
                border: 1px solid #e3ecf6;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                flex-shrink: 0;
            }
            .home-job-card .logo img {
                max-width: 80%;
                max-height: 80%;
                object-fit: contain;
            }
            .home-job-card .type-badge {
                background: #eef5fc;
                color: #1b3a6b;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .4px;
                text-transform: uppercase;
                padding: 6px 11px;
                border-radius: 999px;
                white-space: nowrap;
            }
            .home-job-card h3 {
                font-size: 16.5px;
                line-height: 1.42;
                font-weight: 700;
                color: #0f2544;
                margin: 0 0 14px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .home-job-card:hover h3 { color: #1b3a6b; }
            .home-job-card .meta {
                list-style: none;
                padding: 0;
                margin: 0 0 18px;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .home-job-card .meta li {
                font-size: 13.5px;
                color: #5b6b80;
                display: flex;
                align-items: center;
                gap: 8px;
                line-height: 1.4;
            }
            .home-job-card .meta li i { color: #2f7fc9; font-size: 15px; flex-shrink: 0; }
            .home-job-card .meta li span {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .home-job-card .card-cta {
                margin-top: auto;
                display: inline-flex;
                align-items: center;
                gap: 7px;
                font-size: 13.5px;
                font-weight: 700;
                color: #1b3a6b;
                letter-spacing: .3px;
            }
            .home-job-card:hover .card-cta { color: #2f7fc9; }
            @media (max-width: 1199px) {
                .home-jobs-grid { grid-template-columns: repeat(3, 1fr); }
            }
            @media (max-width: 991px) {
                .home-jobs-grid { grid-template-columns: repeat(2, 1fr); gap: 18px; }
                .home-jobs-section h2 { font-size: 32px; }
            }
            @media (max-width: 575px) {
                .home-jobs-grid { grid-template-columns: 1fr; gap: 14px; }
                .home-jobs-section h2 { font-size: 27px; }
            }
            .home-jobs-section .view-all-row {
                text-align: center;
                margin-top: 42px;
            }
            .home-jobs-section .view-all-row a {
                display: inline-flex;
                align-items: center;
                gap: 14px;
                background: #1b3a6b;
                color: #fff !important;
                padding: 12px 14px 12px 30px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 14.5px;
                letter-spacing: .6px;
                text-transform: uppercase;
                text-decoration: none !important;
                border: 1.5px solid #1b3a6b;
                transition: all .18s ease;
            }
            .home-jobs-section .view-all-row a .arrow {
                width: 34px; height: 34px;
                border-radius: 50%;
                background: #fff;
                color: #1b3a6b;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                flex-shrink: 0;
            }
            .home-jobs-section .view-all-row a:hover {
                background: #16305a;
                border-color: #16305a;
                transform: translateY(-2px);
                box-shadow: 0 12px 24px rgba(27, 58, 107, .28);
            }
            @media (max-width: 768px) {
                .home-jobs-section { padding: 60px 0 54px; }
            }
        </style>

        <section class="home-jobs-section" aria-labelledby="latest-jobs-heading">
            <div class="container">
                <header class="section-head" data-aos="fade-up">
                    <span class="section-tag">Latest Openings</span>
                    <h2 id="latest-jobs-heading">Newest Jobs <span class="accent">Hiring Right Now</span></h2>
                    <p>Fresh openings added to JobGader — browse the newest roles across the USA and apply free, no account needed.</p>
                </header>

                <div class="home-jobs-grid">
                    @foreach ($featuredJobs as $idx => $job)
                        <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}"
                           class="home-job-card"
                           data-aos="fade-up" data-aos-delay="{{ ($idx % 4) * 80 }}" data-aos-duration="600"
                           title="{{ $job->position }}">
                            <div class="card-top">
                                <div class="logo">
                                    <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}"
                                         alt="{{ $job->advertiser->name ?? 'Company' }} logo" loading="lazy" width="52" height="52">
                                </div>
                                <span class="type-badge">{{ $job->employment_type ?: 'Full Time' }}</span>
                            </div>

                            <h3>{{ $job->position }}</h3>

                            <ul class="meta">
                                <li><i class="icon-feather-briefcase" aria-hidden="true"></i>
                                    <span>{{ $job->category?->name ?? ($job->advertiser->name ?? 'General') }}</span></li>
                                @if ($job->location)
                                    <li><i class="icon-material-outline-location-on" aria-hidden="true"></i>
                                        <span>{{ $job->location->name }}{{ $job->location->area ? ', ' . $job->location->area : '' }}</span></li>
                                @endif
                                <li><i class="icon-material-outline-access-time" aria-hidden="true"></i>
                                    <span>{{ $job->created_at?->diffForHumans() ?? 'Recently posted' }}</span></li>
                            </ul>

                            <span class="card-cta">View Job <i class="icon-feather-arrow-right" aria-hidden="true"></i></span>
                        </a>
                    @endforeach
                </div>

                <div class="view-all-row">
                    <a href="{{ route('jobs.index') }}">
                        View All Jobs
                        <span class="arrow" aria-hidden="true"><i class="icon-material-outline-arrow-right-alt"></i></span>
                    </a>
                </div>
            </div>
        </section>
    @endif
    @php
        // Icon picker — falls back to keyword match so DB names like "Education" or
        // "Sales & Marketing" pick the right icon even if the exact key isn't listed.
        $industryIconRules = [
            'health'        => 'icon-line-awesome-medkit',
            'medical'       => 'icon-line-awesome-medkit',
            'nurs'          => 'icon-line-awesome-medkit',
            'hospitality'   => 'icon-line-awesome-suitcase',
            'tourism'       => 'icon-line-awesome-suitcase',
            'travel'        => 'icon-line-awesome-suitcase',
            'hotel'         => 'icon-line-awesome-suitcase',
            'restaurant'    => 'icon-line-awesome-suitcase',
            'food'          => 'icon-line-awesome-suitcase',
            'trade'         => 'icon-line-awesome-wrench',
            'service'       => 'icon-line-awesome-wrench',
            'maintenance'   => 'icon-line-awesome-wrench',
            'transport'     => 'icon-line-awesome-truck',
            'logistic'      => 'icon-line-awesome-truck',
            'driver'        => 'icon-line-awesome-truck',
            'warehouse'     => 'icon-feather-package',
            'retail'        => 'icon-feather-shopping-bag',
            'consumer'      => 'icon-feather-shopping-bag',
            'shop'          => 'icon-feather-shopping-bag',
            'store'         => 'icon-feather-shopping-bag',
            'education'     => 'icon-line-awesome-graduation-cap',
            'training'      => 'icon-line-awesome-graduation-cap',
            'teach'         => 'icon-line-awesome-graduation-cap',
            'school'        => 'icon-line-awesome-graduation-cap',
            'sales'         => 'icon-feather-trending-up',
            'marketing'     => 'icon-feather-pie-chart',
            'business'      => 'icon-feather-briefcase',
            'finance'       => 'icon-line-awesome-bank',
            'bank'          => 'icon-line-awesome-bank',
            'account'       => 'icon-line-awesome-bank',
            'it '           => 'icon-line-awesome-laptop',
            'i.t.'          => 'icon-line-awesome-laptop',
            'tech'          => 'icon-line-awesome-laptop',
            'software'      => 'icon-line-awesome-laptop',
            'engineer'      => 'icon-line-awesome-laptop',
            'design'        => 'icon-feather-edit',
            'art'           => 'icon-feather-edit',
            'creative'      => 'icon-feather-edit',
            'construction'  => 'icon-line-awesome-cog',
            'manufactur'    => 'icon-line-awesome-cog',
            'industrial'    => 'icon-line-awesome-cog',
            'customer'      => 'icon-line-awesome-phone',
            'call'          => 'icon-line-awesome-phone',
            'support'       => 'icon-line-awesome-phone',
            'admin'         => 'icon-feather-file-text',
            'office'        => 'icon-feather-file-text',
            'clerical'      => 'icon-feather-file-text',
            'human'         => 'icon-feather-users',
            'hr'            => 'icon-feather-users',
            'recruit'       => 'icon-feather-users',
            'legal'         => 'icon-feather-shield',
            'security'      => 'icon-feather-shield',
            'media'         => 'icon-feather-monitor',
            'communicat'    => 'icon-feather-monitor',
            'agriculture'   => 'icon-feather-globe',
            'farm'          => 'icon-feather-globe',
            'energy'        => 'icon-feather-zap',
            'utility'       => 'icon-feather-zap',
        ];

        // Display name aliases — turns generic DB labels like "Other" into something
        // more meaningful for the homepage (DB row stays untouched).
        $categoryNameAliases = [
            'other'                     => 'General &amp; Other Roles',
            'misc'                      => 'General &amp; Other Roles',
            'miscellaneous'             => 'General &amp; Other Roles',
            'general'                   => 'General &amp; Other Roles',
            'untitled'                  => 'General &amp; Other Roles',
        ];

        $industryDescriptions = [
            'Healthcare & Medical'       => 'Nursing, physician, allied-health and clinical roles at top U.S. hospitals and care providers.',
            'Hospitality & Tourism'      => 'Front-of-house, kitchen, hotel and travel positions across America\'s leading brands.',
            'Trades & Services'          => 'Skilled-trades, maintenance and field-service jobs with competitive pay and benefits.',
            'Transport & Logistics'      => 'CDL drivers, warehouse, fleet and supply-chain openings nationwide.',
            'Retail & Consumer Products' => 'Store, e-commerce and merchandising roles with major U.S. retailers.',
            'I.T. & Communications'      => 'Software, networking, cybersecurity and helpdesk roles for every experience level.',
            'Call Centre / CustomerService' => 'Remote and on-site customer support, sales and service-rep positions.',
            'Education'                  => 'Teaching, instructional and training roles across schools and learning platforms.',
            'Education & Training'       => 'Teaching, instructional and training roles across schools and learning platforms.',
            'Construction'               => 'Project management, skilled labor and on-site construction opportunities.',
            'Sales'                      => 'Inside sales, account executive and business-development roles with uncapped commission.',
            'Sales & Marketing'          => 'Inside sales, account executive, brand and growth-marketing roles with uncapped commission.',
            'Other'                      => 'Diverse openings spanning admin, support, operations and specialised roles across the U.S.',
        ];

        // Resolver — picks the best icon for a given category name.
        $resolveIndustryIcon = function ($name) use ($industryIconRules) {
            $key = mb_strtolower((string) $name);
            foreach ($industryIconRules as $needle => $icon) {
                if (str_contains($key, $needle)) {
                    return $icon;
                }
            }
            return 'icon-feather-briefcase';
        };
        $resolveDisplayName = function ($name) use ($categoryNameAliases) {
            $key = mb_strtolower(trim((string) $name));
            return $categoryNameAliases[$key] ?? $name;
        };
    @endphp

    {{-- Industries / Categories Section (SEO-optimized) --}}
    @if ($categories->isNotEmpty())
        <style>
            .industry-section {
                padding: 90px 0 80px;
                background: linear-gradient(180deg, #f7fafd 0%, #eef5fc 100%);
                position: relative;
            }
            .industry-section .section-head {
                text-align: center;
                max-width: 780px;
                margin: 0 auto 46px;
            }
            .industry-section .section-tag {
                display: inline-block;
                background: #1b3a6b;
                border: none;
                color: #fff;
                font-weight: 700;
                font-size: 12.5px;
                padding: 10px 24px;
                border-radius: 999px;
                letter-spacing: 1.6px;
                text-transform: uppercase;
                margin-bottom: 20px;
            }
            .industry-section h2 {
                font-size: clamp(28px, 3.2vw, 42px);
                font-weight: 800;
                color: #1b3a6b;
                line-height: 1.18;
                letter-spacing: -.6px;
                margin: 0 0 16px;
            }
            .industry-section h2 .accent {
                color: #3182ce;
                background: none !important;
                -webkit-text-fill-color: currentColor !important;
            }
            .industry-section .section-head p {
                color: #5a6b7f;
                font-size: 16px;
                line-height: 1.7;
                margin: 0;
            }
            /* 4 cards per row × 2 rows = 8 total */
            .industry-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 22px;
            }
            .industry-card {
                position: relative;
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 16px;
                padding: 22px 20px;
                background: #fff;
                border: 1px solid #e4edf7;
                border-bottom: 4px solid #1b3a6b;
                border-radius: 14px;
                color: #1b3a6b !important;
                text-decoration: none !important;
                box-shadow: 0 2px 10px rgba(27, 58, 107, .06);
                transition: transform .25s ease, box-shadow .25s ease;
            }
            .industry-card::before { display: none; }
            .industry-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 18px 36px rgba(27, 58, 107, .14);
            }
            .industry-card .icon-wrap {
                width: 52px; height: 52px;
                flex-shrink: 0;
                border-radius: 50%;
                background: #3182ce;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 23px;
                transition: background .2s ease;
            }
            .industry-card:hover .icon-wrap { background: #1b3a6b; }
            .industry-card .card-text {
                min-width: 0;
            }
            .industry-card h3 {
                font-size: 17px;
                font-weight: 700;
                color: #1b3a6b;
                margin: 0 0 3px;
                line-height: 1.25;
            }
            .industry-card .count {
                font-size: 13.5px;
                font-weight: 500;
                color: #5a6b7f;
                margin: 0;
                line-height: 1.4;
            }

            /* Responsive: 2 cols on tablet, 1 on mobile */
            @media (max-width: 991px) {
                .industry-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 575px) {
                .industry-grid { grid-template-columns: 1fr; gap: 14px; }
            }
            .industry-section .view-all-row {
                text-align: center;
                margin-top: 42px;
            }
            .industry-section .view-all-row a {
                display: inline-flex;
                align-items: center;
                gap: 14px;
                background: #1b3a6b;
                color: #fff !important;
                padding: 12px 14px 12px 30px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 14.5px;
                letter-spacing: .6px;
                text-transform: uppercase;
                text-decoration: none !important;
                border: 1.5px solid #1b3a6b;
                transition: all .18s ease;
            }
            .industry-section .view-all-row a .arrow {
                width: 34px; height: 34px;
                border-radius: 50%;
                background: #fff;
                color: #1b3a6b;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                flex-shrink: 0;
            }
            .industry-section .view-all-row a:hover {
                background: #16305a;
                border-color: #16305a;
                transform: translateY(-2px);
                box-shadow: 0 12px 24px rgba(27, 58, 107, .28);
            }
            @media (max-width: 768px) {
                .industry-section { padding: 60px 0 54px; }
            }
        </style>

        <section class="industry-section" aria-labelledby="industry-heading">
            <div class="container">
                <header class="section-head" data-aos="fade-up">
                    <span class="section-tag">Industries We Serve</span>
                    <h2 id="industry-heading">Browse Jobs <span class="accent">By Your Industry</span></h2>
                    <p>Explore verified openings in America's most in-demand sectors. Pick an industry and jump straight to the roles hiring right now.</p>
                </header>

                <div class="industry-grid">
                    @foreach ($categories as $idx => $category)
                        @php
                            $iconClass   = $resolveIndustryIcon($category->name);
                            $displayName = $resolveDisplayName($category->name);
                            $jobsCount   = $category->jobs_count ?? 0;
                        @endphp
                        <a href="{{ route('jobs.category', $category->slug) }}"
                           class="industry-card"
                           data-aos="fade-up" data-aos-delay="{{ ($idx % 4) * 80 }}" data-aos-duration="600"
                           title="View {!! $displayName !!} jobs in the USA"
                           aria-label="Browse {{ $jobsCount }} {{ strip_tags($displayName) }} jobs">
                            <div class="icon-wrap" aria-hidden="true"><i class="{{ $iconClass }}"></i></div>
                            <div class="card-text">
                                <h3>{!! $displayName !!}</h3>
                                <p class="count">{{ number_format($jobsCount) }} active {{ $jobsCount === 1 ? 'job' : 'jobs' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="view-all-row">
                    <a href="{{ route('jobs.categories') }}">
                        View All Industries
                        <span class="arrow" aria-hidden="true"><i class="icon-material-outline-arrow-right-alt"></i></span>
                    </a>
                </div>
            </div>
        </section>

        {{-- Industry categories — CollectionPage (not Carousel ItemList — categories aren't Carousel-eligible per Google spec) --}}
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "CollectionPage",
            "@@id": {!! json_encode(url('/').'#industries') !!},
            "name": "Browse Jobs by Industry",
            "description": "Explore U.S. job opportunities by industry on JobGader.",
            "hasPart": [
                @foreach ($categories as $idx => $category)
                {
                    "@@type": "WebPage",
                    "name": {!! json_encode($category->name) !!},
                    "url": {!! json_encode(route('jobs.category', $category->slug)) !!}
                }{{ $loop->last ? '' : ',' }}
                @endforeach
            ]
        }
        </script>
    @endif



    {{-- How It Works — Step-by-Step Process (SEO-optimized) --}}
    <style>
        .process-section-v2 {
            position: relative;
            padding: 100px 0 90px;
            background: #ffffff;
            color: #1b3a6b;
            overflow: hidden;
        }
        .process-section-v2::before {
            content: "";
            position: absolute;
            top: -120px; right: -120px;
            width: 460px; height: 460px;
            background: radial-gradient(circle, rgba(15,23,42,.04) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .process-section-v2::after {
            content: "";
            position: absolute;
            bottom: -160px; left: -120px;
            width: 460px; height: 460px;
            background: radial-gradient(circle, rgba(15,23,42,.03) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .process-section-v2 .container { position: relative; z-index: 2; }

        .process-head {
            text-align: center;
            max-width: 920px;
            margin: 0 auto 70px;
        }
        .process-head .eyebrow {
            display: inline-block;
            color: #555;
            background: #fff;
            border: 1px solid #e5e5e7;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 22px;
        }
        .process-head h2 {
            font-size: clamp(30px, 4.2vw, 54px);
            font-weight: 800;
            color: #1b3a6b;
            line-height: 1.1;
            letter-spacing: -.6px;
            margin: 0 0 22px;
        }
        .process-head h2 .accent { color: #1b3a6b; }
        .process-head p {
            color: #555;
            font-size: 15.5px;
            line-height: 1.75;
            max-width: 780px;
            margin: 0 auto;
        }

        .process-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
        }
        .process-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #ececec;
            border-radius: 20px;
            padding: 32px 24px 30px;
            text-align: center;
            transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
        }
        .process-card:hover {
            transform: translateY(-6px);
            border-color: #1b3a6b;
            box-shadow: 0 22px 44px rgba(15,23,42,.10);
        }
        .process-card .step-badge {
            display: inline-block;
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
            color: #fff !important;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 3px;
            padding: 8px 20px;
            border-radius: 6px;
            box-shadow: 0 8px 18px rgba(27, 58, 107,.35);
            position: absolute;
            top: -16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            text-transform: uppercase;
        }
        .process-card .card-image {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            margin: 8px 0 24px;
            aspect-ratio: 4 / 3.4;
            background: #f3f4f6;
        }
        .process-card .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .5s ease;
        }
        .process-card:hover .card-image img { transform: scale(1.05); }
        .process-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1b3a6b;
            margin: 0 0 14px;
            letter-spacing: -.3px;
        }
        .process-card p {
            color: #555;
            font-size: 14.5px;
            line-height: 1.7;
            margin: 0 0 22px;
        }
        .process-card .card-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #1b3a6b !important;
            font-size: 13.5px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            text-decoration: none !important;
            border-bottom: 1.5px solid #1b3a6b;
            padding-bottom: 2px;
            transition: gap .15s ease, opacity .15s ease;
        }
        .process-card .card-cta:hover { gap: 12px; opacity: .7; }
        .process-card .card-cta i { font-size: 16px; }

        .process-cta-row {
            text-align: center;
            margin-top: 60px;
        }
        .process-cta-row a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #1b3a6b;
            color: #fff !important;
            font-size: 15px;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none !important;
            border: 1.5px solid #1b3a6b;
            transition: all .15s ease;
        }
        .process-cta-row a:hover {
            background: #16305a;
            border-color: #16305a;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(0,0,0,.18);
        }

        @media (max-width: 1199px) {
            .process-grid { grid-template-columns: repeat(2, 1fr); gap: 36px 24px; }
        }
        @media (max-width: 575px) {
            .process-section-v2 { padding: 70px 0 60px; }
            .process-grid { grid-template-columns: 1fr; gap: 36px; }
            .process-head { margin-bottom: 50px; }
        }
    </style>

    <section class="process-section-v2" aria-labelledby="process-heading">
        <div class="container">
            <header class="process-head" data-aos="fade-up">
                <span class="eyebrow">How It Works</span>
                <h2 id="process-heading">From Search to <span class="accent">Applied</span> in Four Steps</h2>
                <p>No sign-up wall, no fees, no guessing. Pick a country, read what the visa route actually allows, then apply straight through to the employer or the original posting.</p>
            </header>

            <div class="process-grid">
                <article class="process-card" data-aos="fade-up" data-aos-delay="0" data-aos-duration="700">
                    <span class="step-badge">Step 1</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/home-background-02.webp') }}"
                             alt="Choose jobs in the USA, UK or Pakistan on JobGader"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/user/images/home-background-02.jpg') }}'">
                    </div>
                    <h3>Pick Your Country</h3>
                    <p>We list openings across the {{ $coverage->shortList() }} &mdash; from general labour and hospitality to skilled trades and senior engineering. Start with where you want to work.</p>
                    <a href="{{ route('jobs.index') }}" class="card-cta" aria-label="Browse jobs by country">
                        Browse Jobs <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>

                <article class="process-card" data-aos="fade-up" data-aos-delay="120" data-aos-duration="700">
                    <span class="step-badge">Step 2</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/home-background-03.webp') }}"
                             alt="Read the visa sponsorship guide for your role before applying"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/user/images/home-background-03.jpg') }}'">
                    </div>
                    <h3>Check the Visa Route</h3>
                    <p>Every sector has its own rules. Our guides spell out which routes are open, which closed, what the pay really is, and how to spot a recruiter selling a visa that does not exist.</p>
                    <a href="{{ route('blog.index') }}" class="card-cta" aria-label="Read the visa sponsorship guides">
                        Read the Guides <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>

                <article class="process-card" data-aos="fade-up" data-aos-delay="240" data-aos-duration="700">
                    <span class="step-badge">Step 3</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/callout-1.jpg') }}"
                             alt="Open a listing to see pay, requirements and shift details"
                             loading="lazy">
                    </div>
                    <h3>Open the Listing</h3>
                    <p>Each listing sets out the pay range, requirements, shift pattern and sponsorship status up front &mdash; so you know whether you qualify before you spend time on an application.</p>
                    <a href="{{ route('jobs.index') }}" class="card-cta" aria-label="View the latest job listings">
                        See Latest Jobs <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>

                <article class="process-card" data-aos="fade-up" data-aos-delay="360" data-aos-duration="700">
                    <span class="step-badge">Step 4</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/callout-2.jpg') }}"
                             alt="Get hired by trusted employers across the {{ $coverage->shortList() }}"
                             loading="lazy">
                    </div>
                    <h3>Apply Direct, Free</h3>
                    <p>Apply straight through to the employer or the original posting. No account, no fee, no middleman &mdash; and never pay anyone who asks for money to guarantee a job or a visa.</p>
                    <a href="{{ route('jobs.companies') }}" class="card-cta" aria-label="Browse hiring employers">
                        Hiring Employers <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>
            </div>

            <div class="process-cta-row">
                <a href="{{ route('jobs.index') }}">
                    Browse All Jobs <i class="icon-feather-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Structured data: HowTo schema for "how to get hired" --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "HowTo",
        "name": "How to Find a Job in the USA on JobGader",
        "description": "Get hired in 4 simple steps on JobGader — America's trusted free job portal connecting candidates with verified employers nationwide.",
        "totalTime": "PT5M",
        "step": [
            {
                "@@type": "HowToStep",
                "position": 1,
                "name": "Create Your Account",
                "text": "Pick your country. We list openings across the {{ $coverage->shortList() }}, from general labour and hospitality to skilled trades and senior engineering.",
                "url": "{{ route('register') }}"
            },
            {
                "@@type": "HowToStep",
                "position": 2,
                "name": "Search Verified Jobs",
                "text": "Check the visa route. Our guides spell out which sponsorship routes are open, which are closed, and what the pay really is.",
                "url": "{{ route('jobs.index') }}"
            },
            {
                "@@type": "HowToStep",
                "position": 3,
                "name": "Apply with Confidence",
                "text": "Open the role that fits you best and submit your application instantly. Track every response from your dashboard.",
                "url": "{{ route('jobs.index') }}"
            },
            {
                "@@type": "HowToStep",
                "position": 4,
                "name": "Get Hired Faster",
                "text": "Apply direct and free. Applications go straight to the employer or the original posting, with no account and no fee.",
                "url": "{{ route('jobs.companies') }}"
            }
        ]
    }
    </script>


    {{-- ===== Why JobGader — 2-column "How we're different" ===== --}}
    <section class="why-section" aria-labelledby="why-heading" itemscope itemtype="https://schema.org/Service">
        <div class="container">
            <header class="why-head">
                <span class="eyebrow">Why JobGader</span>
                <h2 id="why-heading">How <span class="accent">JobGader</span> is Different</h2>
                <p>Most job sites tell you what you want to hear about visas. We tell you what the rules actually say &mdash; then get out of the way so you can apply.</p>
            </header>

            <div class="why-grid">
                <div class="why-points">
                    <article class="why-item" itemprop="hasOfferCatalog">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>Honest Visa Guidance</h3>
                            <p>When a route closes, we say so. The UK care worker visa shut to new overseas applicants in July 2025 &mdash; our guide leads with that instead of selling you a visa that no longer exists.</p>
                        </div>
                    </article>

                    <article class="why-item">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>{{ $coverage->countWord() }} Countries, One Board</h3>
                            <p>{{ $coverage->shortList() }} in one place &mdash; truck driving, hospitality, care, construction, cleaning and software. Filter by country, salary or category and apply in a click.</p>
                        </div>
                    </article>

                    <article class="why-item">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>No Account, No Fee, Ever</h3>
                            <p>You do not need to sign up to apply. No subscription, no resume paywall, no hidden charges &mdash; and we will never ask a job seeker for money.</p>
                        </div>
                    </article>

                    <article class="why-item">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>Real Pay Figures, Not Guesses</h3>
                            <p>Every listing carries a researched pay range with its source explained &mdash; hourly rates, annual bands and what the number actually means once tax and shift premiums are counted.</p>
                        </div>
                    </article>

                    <a href="{{ route('jobs.index') }}" class="why-cta">
                        <span>See Open Jobs</span>
                        <i class="icon-material-outline-arrow-right-alt"></i>
                    </a>
                </div>

                <aside class="why-visual" aria-hidden="true">
                    <div class="why-visual-blob blob-1"></div>
                    <div class="why-visual-blob blob-2"></div>
                    <div class="why-visual-stage">
                        <img src="{{ asset('public/user/images/hero-diverse-professionals.webp') }}"
                             alt="Job seeker browsing visa sponsorship job openings on JobGader"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/user/images/hero-diverse-professionals.jpg') }}'">
                        <div class="why-floating why-fl-1">
                            <div class="ico"><i class="icon-feather-shield"></i></div>
                            <div>
                                <strong>Hand-Checked</strong>
                                <span>Every listing, before it goes live</span>
                            </div>
                        </div>
                        <div class="why-floating why-fl-2">
                            <div class="ico"><i class="icon-feather-zap"></i></div>
                            <div>
                                <strong>No Sign-Up</strong>
                                <span>Apply without an account</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <style>
        .why-section { padding: 90px 0 80px; background: #fff; border-top: 1px solid #ececec; }
        .why-head { text-align: center; max-width: 760px; margin: 0 auto 56px; }
        .why-head .eyebrow {
            display: inline-block;
            background: #fff;
            border: 1px solid #e5e5e7;
            color: #555;
            font-weight: 700;
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 999px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .why-head h2 {
            font-size: clamp(28px, 3vw, 40px);
            font-weight: 800;
            color: #1b3a6b;
            line-height: 1.2;
            letter-spacing: -.5px;
            margin: 0 0 12px;
        }
        .why-head h2 .accent {
            background: linear-gradient(90deg, #1b3a6b, #4a90d9);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        .why-head p { color: #555; font-size: 16px; line-height: 1.7; margin: 0; }

        .why-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
        }
        @media (max-width: 991px) { .why-grid { grid-template-columns: 1fr; gap: 48px; } }

        .why-points { display: flex; flex-direction: column; gap: 28px; }
        .why-item { display: flex; gap: 18px; align-items: flex-start; }
        .why-check {
            flex-shrink: 0;
            width: 40px; height: 40px;
            border-radius: 50%;
            background: #1b3a6b;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 6px 14px rgba(27, 58, 107,.20);
        }
        .why-item h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1b3a6b;
            margin: 4px 0 8px;
            letter-spacing: -.2px;
        }
        .why-item p {
            font-size: 14.5px;
            line-height: 1.7;
            color: #555;
            margin: 0;
        }

        .why-cta {
            margin-top: 8px;
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #1b3a6b;
            color: #fff !important;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none !important;
            box-shadow: 0 8px 18px rgba(27, 58, 107,.20);
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .why-cta:hover { transform: translateY(-1px); background: #16305a; box-shadow: 0 14px 28px rgba(27, 58, 107,.30); }
        .why-cta i { font-size: 22px; transition: transform .2s ease; }
        .why-cta:hover i { transform: translateX(4px); }

        /* Visual side */
        .why-visual { position: relative; min-height: 420px; }
        .why-visual-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(50px);
            opacity: .45;
            pointer-events: none;
        }
        .why-visual-blob.blob-1 { width: 300px; height: 300px; background: #2f7fc9; top: -30px; right: 0; }
        .why-visual-blob.blob-2 { width: 240px; height: 240px; background: #5e2bff; bottom: -30px; left: 20px; }
        .why-visual-stage {
            position: relative;
            z-index: 2;
            border-radius: 22px;
            overflow: hidden;
        }
        .why-visual-stage img {
            width: 100%;
            height: 460px;
            object-fit: cover;
            border-radius: 22px;
            display: block;
            box-shadow: 0 30px 60px rgba(15,23,42,.18);
            animation: whyFloat 7s ease-in-out infinite;
        }
        @media (max-width: 991px) { .why-visual-stage img { height: 360px; } }
        @media (max-width: 575px) { .why-visual-stage img { height: 280px; } }
        @keyframes whyFloat {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-12px); }
        }
        .why-floating {
            position: absolute;
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 14px;
            padding: 12px 16px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 14px 32px rgba(15,23,42,.12);
            z-index: 3;
        }
        .why-floating .ico {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #1b3a6b;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .why-floating strong {
            display: block;
            font-size: 14px;
            color: #1b3a6b;
            font-weight: 700;
        }
        .why-floating span {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }
        .why-fl-1 {
            top: 22px; left: -34px;
            animation: whyBadgeA 6s ease-in-out infinite;
        }
        .why-fl-2 {
            bottom: 28px; right: -28px;
            animation: whyBadgeB 7s ease-in-out infinite;
        }
        @keyframes whyBadgeA {
            0%, 100% { transform: translate(0, 0); }
            50%      { transform: translate(0, -10px); }
        }
        @keyframes whyBadgeB {
            0%, 100% { transform: translate(0, 0); }
            50%      { transform: translate(0, 8px); }
        }
        @media (max-width: 991px) {
            .why-visual { min-height: auto; }
            .why-fl-1 { left: 14px; top: 14px; }
            .why-fl-2 { right: 14px; bottom: 14px; }
        }
        @media (max-width: 575px) {
            .why-fl-1, .why-fl-2 { display: none; }
        }
    </style>

    {{-- ===== Career Advice / Blog Section ===== --}}
    @if (isset($careerPosts) && $careerPosts->isNotEmpty())
    @php
        // Build category filter chip list from the posts on this page
        $careerCats = $careerPosts->map(fn($p) => $p->category)->filter()->unique('id')->values();
        // Image URL resolver — handles seeder paths ("public/user/images/...") and storage uploads
        $blogImg = function ($img) {
            if (! $img) return asset('public/user/images/blog-compact-post-01.jpg');
            if (str_starts_with($img, 'http')) return $img;
            if (str_starts_with($img, 'public/')) return asset($img);
            return asset('public/storage/' . ltrim($img, '/'));
        };
    @endphp
    <section class="career-section" aria-labelledby="career-heading">
        <div class="container">
            <header class="career-head">
                <h2 id="career-heading">Career Advice to Win Your Job Search</h2>
                <p>Resume tips, interview answers and salary insights from career experts &mdash; everything you need to land your next U.S. job.</p>

                @if ($careerCats->isNotEmpty())
                    <div class="career-chips" role="tablist" aria-label="Filter career advice by topic">
                        <button type="button" class="career-chip is-active" data-cat="all" role="tab" aria-selected="true">All Topics</button>
                        @foreach ($careerCats as $cat)
                            <button type="button" class="career-chip" data-cat="{{ $cat->id }}" role="tab" aria-selected="false">{{ $cat->name }}</button>
                        @endforeach
                    </div>
                @endif
            </header>

            <div class="career-grid">
                @foreach ($careerPosts as $post)
                    @php
                        $authorName = $post->author_name ?: ($post->author->name ?? 'Editorial Team');
                        $authorInit = mb_strtoupper(mb_substr($authorName, 0, 1));
                        $catId = $post->category->id ?? 'none';
                        $catName = $post->category->name ?? 'Career';
                    @endphp
                    <article class="career-card" data-cat="{{ $catId }}" itemscope itemtype="https://schema.org/Article">
                        <a href="{{ route('blog.show', $post->slug) }}" class="career-thumb" aria-label="Read: {{ $post->title }}">
                            <img src="{{ $blogImg($post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 loading="lazy"
                                 itemprop="image">
                        </a>
                        <div class="career-body">
                            @if ($post->category)
                                <a href="{{ route('blog.index', ['category' => $post->category->slug ?? '']) }}" class="career-cat" itemprop="articleSection">{{ $catName }}</a>
                            @else
                                <span class="career-cat">Career</span>
                            @endif
                            <h3 class="career-title" itemprop="headline">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <div class="career-meta">
                                <span class="career-author">
                                    <span class="career-avatar">{{ $authorInit }}</span>
                                    <span>By <strong itemprop="author">{{ $authorName }}</strong></span>
                                </span>
                                @if ($post->published_at || $post->created_at)
                                    <span class="career-date" itemprop="datePublished" content="{{ optional($post->published_at ?? $post->created_at)->toIso8601String() }}">
                                        {{ optional($post->published_at ?? $post->created_at)->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="career-foot">
                <a href="{{ route('blog.index') }}" class="career-all">
                    Read all career advice <i class="icon-material-outline-arrow-right-alt"></i>
                </a>
            </div>
        </div>
    </section>

    <style>
        .career-section { padding: 90px 0 80px; background: #fafafa; border-top: 1px solid #ececec; }
        .career-head { text-align: center; max-width: 760px; margin: 0 auto 40px; }
        .career-head h2 {
            font-size: clamp(26px, 3vw, 36px);
            font-weight: 800;
            color: #1b3a6b;
            letter-spacing: -.5px;
            margin: 0 0 12px;
        }
        .career-head p { color: #555; font-size: 16px; line-height: 1.7; margin: 0; }

        .career-chips {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 24px;
            justify-content: center;
        }
        .career-chip {
            background: #fff;
            border: 1px solid #ececec;
            color: #1b3a6b;
            font-size: 14px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 999px;
            cursor: pointer;
            transition: all .15s ease;
        }
        .career-chip:hover { background: #f3f4f6; border-color: #1b3a6b; }
        .career-chip.is-active { background: #1b3a6b; color: #fff; border-color: #1b3a6b; }

        .career-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }
        @media (max-width: 991px) { .career-grid { grid-template-columns: repeat(2, 1fr); gap: 22px; } }
        @media (max-width: 575px) { .career-grid { grid-template-columns: 1fr; } }

        .career-card {
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .career-card.hidden { display: none; }
        .career-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(15,23,42,.10);
            border-color: #1b3a6b;
        }
        .career-thumb {
            display: block;
            aspect-ratio: 16 / 9;
            background: #eef5fc;
            overflow: hidden;
        }
        .career-thumb img {
            width: 100%; height: 100%; object-fit: contain;
            transition: transform .35s ease;
        }
        .career-card:hover .career-thumb img { transform: none; }
        .career-body { padding: 22px 22px 24px; display: flex; flex-direction: column; gap: 12px; flex: 1; }
        .career-cat {
            display: inline-block;
            color: #1b3a6b;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            text-decoration: none;
        }
        .career-cat:hover { text-decoration: underline; }
        .career-title { margin: 0; font-size: 18px; line-height: 1.4; font-weight: 700; }
        .career-title a {
            color: #1b3a6b !important;
            text-decoration: none !important;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .career-card:hover .career-title a { text-decoration: underline; }
        .career-meta {
            margin-top: auto;
            padding-top: 14px;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #6b7280;
            flex-wrap: wrap;
        }
        .career-author { display: inline-flex; align-items: center; gap: 8px; }
        .career-author strong { color: #1b3a6b; font-weight: 600; }
        .career-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #1b3a6b;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
        }
        .career-date { font-size: 12.5px; }

        .career-foot { text-align: center; margin-top: 44px; }
        .career-all {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1b3a6b !important;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none !important;
            padding: 12px 24px;
            border: 1.5px solid #1b3a6b;
            border-radius: 10px;
            transition: all .15s ease;
        }
        .career-all:hover {
            background: #1b3a6b;
            color: #fff !important;
        }
        .career-all i { font-size: 22px; transition: transform .2s ease; }
        .career-all:hover i { transform: translateX(4px); }
    </style>

    <script>
        // Topic chip filter — purely client-side (the chips already filter the 6 posts on this page)
        (function () {
            const chips = document.querySelectorAll('.career-chip');
            const cards = document.querySelectorAll('.career-card');
            if (!chips.length || !cards.length) return;
            chips.forEach(chip => {
                chip.addEventListener('click', () => {
                    chips.forEach(c => { c.classList.remove('is-active'); c.setAttribute('aria-selected', 'false'); });
                    chip.classList.add('is-active');
                    chip.setAttribute('aria-selected', 'true');
                    const cat = chip.dataset.cat;
                    cards.forEach(card => {
                        if (cat === 'all' || card.dataset.cat === cat) card.classList.remove('hidden');
                        else card.classList.add('hidden');
                    });
                });
            });
        })();
    </script>

    {{-- Career-advice articles — Carousel ItemList; each item is a BlogPosting so Google can render the rich carousel. --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "ItemList",
        "@@id": {!! json_encode(url('/').'#career-articles') !!},
        "name": "Career Advice for U.S. Job Seekers",
        "itemListOrder": "https://schema.org/ItemListOrderAscending",
        "itemListElement": [
            @foreach ($careerPosts as $i => $post)
            {
                "@@type": "ListItem",
                "position": {{ $i + 1 }},
                "item": {
                    "@@type": "BlogPosting",
                    "@@id": @json(route('blog.show', $post->slug)),
                    "headline": @json($post->title),
                    "url": @json(route('blog.show', $post->slug)),
                    "datePublished": @json(optional($post->published_at)->toIso8601String() ?? optional($post->created_at)->toIso8601String()),
                    "author": { "@@type": "Organization", "name": "JobGader" },
                    "publisher": { "@@type": "Organization", "name": "JobGader" }
                }
            }@if (! $loop->last),@endif
            @endforeach
        ]
    }
    </script>
    @endif
    {{-- ===== End career advice ===== --}}




    <!-- FAQ Section — 2-column split layout -->
    <style>
        .home-faq-section {
            background: #fafafa;
            padding: 90px 0;
            border-top: 1px solid #ececec;
        }
        .home-faq-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 60px;
            align-items: start;
        }
        @media (max-width: 991px) {
            .home-faq-grid { grid-template-columns: 1fr; gap: 40px; }
        }

        /* Left: heading + contact CTA */
        .faq-left { position: sticky; top: 100px; }
        .faq-left .eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #555;
            background: #fff;
            border: 1px solid #e5e5e7;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 20px;
        }
        .faq-left h2 {
            font-size: clamp(28px, 3vw, 40px);
            font-weight: 800;
            color: #1b3a6b;
            line-height: 1.15;
            letter-spacing: -.6px;
            margin: 0 0 16px;
        }
        .faq-left p {
            color: #555;
            font-size: 16px;
            line-height: 1.7;
            margin: 0 0 28px;
            max-width: 380px;
        }
        .faq-left .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #1b3a6b;
            color: #fff !important;
            font-size: 15px;
            font-weight: 600;
            padding: 14px 26px;
            border-radius: 10px;
            text-decoration: none;
            transition: all .15s ease;
        }
        .faq-left .contact-btn:hover {
            background: #16305a;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(0,0,0,.18);
        }
        .faq-left .contact-btn i { font-size: 16px; }

        /* Right: FAQs in single column */
        .faq-list { display: flex; flex-direction: column; gap: 12px; }
        .home-faq-item {
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 12px;
            overflow: hidden;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .home-faq-item[open] {
            border-color: #1b3a6b;
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
        }
        .home-faq-item summary {
            padding: 20px 24px;
            font-weight: 600;
            font-size: 15.5px;
            color: #1b3a6b;
            cursor: pointer;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }
        .home-faq-item summary::-webkit-details-marker { display: none; }
        .home-faq-item summary::after {
            content: '+';
            font-size: 24px;
            color: #1b3a6b;
            font-weight: 300;
            line-height: 1;
            transition: transform .2s ease;
            flex-shrink: 0;
        }
        .home-faq-item[open] summary::after { content: '−'; }
        .home-faq-item .home-faq-answer {
            padding: 0 24px 22px;
            color: #555;
            font-size: 14.5px;
            line-height: 1.75;
        }
        .home-faq-item .home-faq-answer a {
            color: #1b3a6b;
            font-weight: 600;
            border-bottom: 1.5px solid #1b3a6b;
            text-decoration: none;
        }
    </style>
    <section class="home-faq-section">
        <div class="container">
            <div class="home-faq-grid">
                <div class="faq-left">
                    <span class="eyebrow">FAQ</span>
                    <h2>Got questions? We've got answers.</h2>
                    <p>The honest version — what applying costs, which visa routes are actually open, and how to spot a recruiter who is wasting your time. Still stuck? Our team is one click away.</p>
                    <a href="{{ route('pages.contact') }}" class="contact-btn">
                        Contact Support <i class="icon-feather-arrow-right"></i>
                    </a>
                </div>

                <div class="faq-list">
                    <details class="home-faq-item">
                        <summary>Is it free to search and apply for jobs?</summary>
                        <div class="home-faq-answer">Yes — searching and applying is completely free, and you do not even need an account. Open any listing and apply straight through. There are no fees, no paywalls and no charges for job seekers, ever.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>Do I need to create an account to apply?</summary>
                        <div class="home-faq-answer">No. Every listing on JobGader can be opened and applied to without signing up. An account is optional and only useful if you want to save roles or come back to them later.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>Which countries and industries do you cover?</summary>
                        <div class="home-faq-answer">We cover the {{ $coverage->shortList() }} across a wide range of industries — healthcare, IT, construction, retail, hospitality, transport, cleaning and more — including roles that sponsor foreign workers.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>Do you list jobs with visa sponsorship?</summary>
                        <div class="home-faq-answer">Yes, and we are honest about which routes are genuinely open. Some, like US truck driving and hospitality on H-2B and EB-3, still sponsor foreign workers. Others, like the UK care worker route, closed to new overseas applicants in July 2025 — our guides say so plainly instead of promising a visa that no longer exists.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>How do I know a listing is genuine?</summary>
                        <div class="home-faq-answer">Every listing is added and checked by our team rather than scraped automatically, and each one links through to the employer or the original posting so you can verify it yourself before applying.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>Do I ever have to pay a recruiter for sponsorship?</summary>
                        <div class="home-faq-answer">No. Charging a worker for visa sponsorship is illegal in both the US and the UK. If a recruiter asks for an upfront fee to guarantee a job or a visa, treat it as a scam and walk away.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>How often are new jobs and guides added?</summary>
                        <div class="home-faq-answer">New listings and visa guides go up every week. The newest openings always appear at the top of the home page and on the jobs board.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>What if I need help with an application?</summary>
                        <div class="home-faq-answer">Visit our Contact page and our team will get back to you. We can point you to the right guide, but we are not immigration advisers — always confirm visa rules on the official government site.</div>
                    </details>
                </div>
            </div>
        </div>
    </section>
@endsection
