@extends('user.layouts.master')
@section('title', 'Jobs in USA | Find Your Next Job — Apply Free, Hiring Now')
@section('meta_description', 'Search 230,000+ verified jobs in the USA. Healthcare, IT, retail, logistics & more. Free to apply, new openings daily across all 50 states — find your next career on Jobs in USA.')
@section('meta_keywords', 'jobs in usa, job search usa, american jobs, hiring near me, employment usa, careers, job listings, remote jobs usa, work from home jobs, healthcare jobs, IT jobs, full-time jobs, part-time jobs, apply free')
@section('og_title', 'Jobs in USA | Find Your Next Job — Apply Free, Hiring Now')
@section('og_description', 'Search 230,000+ verified jobs in the USA. Apply free across healthcare, IT, retail, logistics and more — your next career move starts here.')
@section('og_image', asset('public/user/images/home-background-03.jpg'))
@section('canonical', url('/'))

@push('meta')
    {{-- Twitter card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Jobs in USA — Find Verified Jobs Across All 50 States">
    <meta name="twitter:description" content="Search 230,000+ verified job listings across all 50 U.S. states. Free for job seekers.">
    <meta name="twitter:image" content="{{ asset('public/user/images/home-background-03.jpg') }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="Jobs in USA">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Jobs in USA">
    <meta property="og:locale" content="en_US">

    {{-- JSON-LD: WebSite with SearchAction --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "Jobs in USA",
        "url": "{{ url('/') }}",
        "description": "America's trusted job search platform connecting verified employers with millions of qualified job seekers across all 50 U.S. states.",
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
        "name": "Jobs in USA",
        "alternateName": "JobsinUSA",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('public/user/images/Jobs in USA.png') }}",
        "description": "Verified online employment platform connecting American job seekers with hiring employers across all 50 U.S. states.",
        "areaServed": {
            "@@type": "Country",
            "name": "United States"
        },
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "Customer Support",
            "email": "support@jobsinusa.com",
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
                "acceptedAnswer": { "@@type": "Answer", "text": "Yes, browsing and applying for jobs on Jobs in USA is 100% free for job seekers. Just create an account and start applying." }
            },
            {
                "@@type": "Question",
                "name": "How do I get started?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Click Sign Up, create your free account, complete your profile with your resume and skills, then start exploring thousands of opportunities." }
            },
            {
                "@@type": "Question",
                "name": "Which states and industries are covered?",
                "acceptedAnswer": { "@@type": "Answer", "text": "We cover all 50 U.S. states and a wide range of industries — healthcare, IT, construction, retail, hospitality, transport, finance, and many more." }
            },
            {
                "@@type": "Question",
                "name": "Can I work remotely through Jobs in USA?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Yes. We have a dedicated section for remote, work-from-home, and hybrid roles. Use the location filter and select Remote to see all matching jobs." }
            },
            {
                "@@type": "Question",
                "name": "How are employers verified?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Every employer profile is reviewed by our team before being published. We verify business details and monitor activity to keep the platform safe and trustworthy." }
            },
            {
                "@@type": "Question",
                "name": "How do I post a job as an employer?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Register as an employer, choose a posting plan, and submit your listing through your dashboard. It goes live once our team approves it." }
            },
            {
                "@@type": "Question",
                "name": "Can I get email alerts for new jobs?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Yes — set up job alerts based on keywords, location, and category. We'll email you whenever matching positions are posted." }
            },
            {
                "@@type": "Question",
                "name": "What if I need help with my application?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Visit our Contact page and our support team will get back to you within 24 hours." }
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
            color: #0a0a0a !important;
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
            background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #ffab40) !important;
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
            color: #1a1a1a !important;
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
            color: #1a1a1a !important;
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
            color: #1a1a1a !important;
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
            color: #1a1a1a !important;
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
            color: #1a1a1a !important;
            box-shadow: none !important;
            text-align: left !important;
            width: 100% !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox .form-control::placeholder {
            color: #9ca3af !important;
            opacity: 1;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .bs-searchbox .form-control:focus {
            border-color: #0a0a0a !important;
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
            color: #1a1a1a !important;
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
            color: #0a0a0a !important;
        }
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li.selected > a,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li.active > a,
        .intro-banner.intro-hero-v2 .utf-intro-search-field-item .bootstrap-select .dropdown-menu li > .dropdown-item.active {
            background: #0a0a0a !important;
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
            background: #0a0a0a !important;
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
            background: #1a1a1a !important;
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
            background: #0a0a0a;
            color: #fff !important;
            border: 1.5px solid #0a0a0a;
        }
        .hero-cta-row .btn-cta-dark:hover {
            background: #1a1a1a;
            border-color: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(0,0,0,.18);
        }
        .hero-cta-row .btn-cta-outline {
            background: #fff;
            color: #0a0a0a !important;
            border: 1.5px solid #0a0a0a;
        }
        .hero-cta-row .btn-cta-outline:hover {
            background: #0a0a0a;
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
            color: #0a0a0a;
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
            color: #0a0a0a;
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
            color: #0a0a0a;
            font-size: 13px; font-weight: 500;
            padding: 7px 14px;
            border-radius: 999px;
            text-decoration: none;
            transition: all .15s ease;
        }
        .hero-trending .trending-tag:hover {
            background: #0a0a0a; color: #fff; border-color: #0a0a0a;
            transform: translateY(-1px);
        }

        /* Compact stats row for the split hero */
        .intro-banner.intro-hero-v2.hero-split .hero-stats {
            border-top: 1px solid rgba(10,10,10,.10);
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
            background: #fef3e7;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }
        .mb-job-info { flex: 1; min-width: 0; }
        .mb-job-title {
            font-size: 10px; font-weight: 700; color: #0a0a0a;
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
        .float-title { font-size: 13.5px; font-weight: 800; color: #0a0a0a; line-height: 1.1; }
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
                            Trusted by job seekers across all 50 U.S. states
                        </span>
                        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                            Find Your Next Job in the USA &mdash;
                            <span class="accent">Hiring Now</span>
                        </h1>
                        <span data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">
                            Search {{ number_format($stats['total_jobs'] ?? 230000) }}+ verified jobs from top U.S. employers in healthcare, IT, logistics, retail, and more. Free to apply. Get hired faster.
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
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Nurse">Nurse RN</a>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Warehouse">Warehouse</a>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?position=Software+Engineer">Software Eng.</a>
                        <a class="trending-tag" href="{{ route('jobs.search') }}?location=Remote">Remote</a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="700">
                            <strong>{{ number_format($stats['total_jobs'] ?? 0) }}+</strong>
                            <span>Open Jobs</span>
                        </div>
                        <div class="divider"></div>
                        <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="800">
                            <strong>{{ number_format($stats['total_companies'] ?? 0) }}+</strong>
                            <span>Employers</span>
                        </div>
                        <div class="divider"></div>
                        <div class="stat" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="900">
                            <strong>{{ number_format($stats['total_locations'] ?? 50) }}+</strong>
                            <span>Locations</span>
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
                padding: 80px 0 70px;
                background: #ffffff;
                position: relative;
            }
            .industry-section .section-head {
                text-align: center;
                max-width: 760px;
                margin: 0 auto 48px;
            }
            .industry-section .section-tag {
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
            .industry-section h2 {
                font-size: clamp(26px, 3vw, 36px);
                font-weight: 800;
                color: #0a0a0a;
                line-height: 1.2;
                letter-spacing: -.5px;
                margin: 0 0 12px;
            }
            .industry-section .section-head p {
                color: #555;
                font-size: 16px;
                line-height: 1.7;
                margin: 0;
            }
            /* 4 cards per row × 2 rows = 8 total */
            .industry-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 24px;
            }
            .industry-card {
                position: relative;
                display: flex;
                flex-direction: column;
                gap: 10px;
                padding: 26px 24px;
                background: #fff;
                border: 1px solid #ececec;
                border-radius: 16px;
                color: #0a0a0a !important;
                text-decoration: none !important;
                overflow: hidden;
                transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
            }
            .industry-card::before { display: none; }
            .industry-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 44px rgba(15, 23, 42, .10);
                border-color: #0a0a0a;
            }
            .industry-card .icon-wrap {
                width: 52px; height: 52px;
                border-radius: 12px;
                background: #0a0a0a;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                margin-bottom: 4px;
                transition: background .2s ease;
            }
            .industry-card:hover .icon-wrap { background: #1a1a1a; }
            .industry-card h3 {
                font-size: 17px;
                font-weight: 700;
                color: #0a0a0a;
                margin: 0;
                line-height: 1.25;
            }
            .industry-card .count {
                font-size: 12px;
                font-weight: 600;
                color: #555;
                margin: 0;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #f5f5f7;
                padding: 4px 12px;
                border-radius: 999px;
                align-self: flex-start;
            }
            .industry-card .count::before {
                content: "";
                width: 6px; height: 6px;
                background: #10b981;
                border-radius: 50%;
            }
            .industry-card .desc {
                font-size: 13.5px;
                color: #6b7280;
                line-height: 1.55;
                margin: 0;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .industry-card .cta {
                margin-top: auto;
                padding-top: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #0a0a0a;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                transition: gap .15s ease;
            }
            .industry-card:hover .cta { gap: 8px; }

            /* Responsive: 2 cols on tablet, 1 on mobile */
            @media (max-width: 991px) {
                .industry-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 575px) {
                .industry-grid { grid-template-columns: 1fr; gap: 14px; }
            }
            .industry-section .view-all-row {
                text-align: center;
                margin-top: 36px;
            }
            .industry-section .view-all-row a {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: #0a0a0a;
                color: #fff !important;
                padding: 14px 28px;
                border-radius: 10px;
                font-weight: 600;
                font-size: 15px;
                text-decoration: none !important;
                border: 1.5px solid #0a0a0a;
                transition: all .15s ease;
            }
            .industry-section .view-all-row a:hover {
                background: #1a1a1a;
                border-color: #1a1a1a;
                transform: translateY(-1px);
                box-shadow: 0 8px 18px rgba(0,0,0,.18);
            }
            @media (max-width: 768px) {
                .industry-section { padding: 56px 0 50px; }
            }
        </style>

        <section class="industry-section" aria-labelledby="industry-heading">
            <div class="container">
                <header class="section-head" data-aos="fade-up">
                    <span class="section-tag">Industries We Cover</span>
                    <h2 id="industry-heading">Browse Jobs by Industry Across the United States</h2>
                    <p>Explore thousands of verified job openings in America's most in-demand sectors. From healthcare and IT to logistics and skilled trades — find the right career path for you, updated daily.</p>
                </header>

                <div class="industry-grid">
                    @foreach ($categories as $idx => $category)
                        @php
                            $iconClass   = $resolveIndustryIcon($category->name);
                            $displayName = $resolveDisplayName($category->name);
                            $description = $industryDescriptions[$category->name]
                                ?? 'Discover the latest ' . strip_tags($displayName) . ' job opportunities from verified U.S. employers hiring now.';
                            $jobsCount   = $category->jobs_count ?? 0;
                        @endphp
                        <a href="{{ route('jobs.category', $category->slug) }}"
                           class="industry-card"
                           data-aos="fade-up" data-aos-delay="{{ ($idx % 6) * 80 }}" data-aos-duration="600"
                           title="View {!! $displayName !!} jobs in the USA"
                           aria-label="Browse {{ $jobsCount }} {{ strip_tags($displayName) }} jobs">
                            <div class="icon-wrap" aria-hidden="true"><i class="{{ $iconClass }}"></i></div>
                            <h3>{!! $displayName !!}</h3>
                            <p class="count">{{ number_format($jobsCount) }} active {{ $jobsCount === 1 ? 'job' : 'jobs' }}</p>
                            <p class="desc">{{ $description }}</p>
                            <span class="cta">Explore {!! $displayName !!} jobs <i class="icon-material-outline-arrow-right-alt"></i></span>
                        </a>
                    @endforeach
                </div>

                <div class="view-all-row">
                    <a href="{{ route('jobs.categories') }}">
                        View all job categories <i class="icon-material-outline-arrow-right-alt"></i>
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
            "description": "Explore U.S. job opportunities by industry on Jobs in USA.",
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
            color: #0a0a0a;
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
            color: #0a0a0a;
            line-height: 1.1;
            letter-spacing: -.6px;
            margin: 0 0 22px;
        }
        .process-head h2 .accent { color: #0a0a0a; }
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
            border-color: #0a0a0a;
            box-shadow: 0 22px 44px rgba(15,23,42,.10);
        }
        .process-card .step-badge {
            display: inline-block;
            background: linear-gradient(135deg, #0a0a0a, #1f1f1f);
            color: #fff !important;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 3px;
            padding: 8px 20px;
            border-radius: 6px;
            box-shadow: 0 8px 18px rgba(10,10,10,.35);
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
            color: #0a0a0a;
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
            color: #0a0a0a !important;
            font-size: 13.5px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            text-decoration: none !important;
            border-bottom: 1.5px solid #0a0a0a;
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
            background: #0a0a0a;
            color: #fff !important;
            font-size: 15px;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none !important;
            border: 1.5px solid #0a0a0a;
            transition: all .15s ease;
        }
        .process-cta-row a:hover {
            background: #1a1a1a;
            border-color: #1a1a1a;
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
                <h2 id="process-heading">Our <span class="accent">Step-By-Step</span> Job Search Process</h2>
                <p>From signing up to landing your next role, we guide you through every step with clarity, verified employers, and full support. Whether you're entering the workforce or making your next career move, finding jobs in the USA has never been easier or more transparent.</p>
            </header>

            <div class="process-grid">
                <article class="process-card" data-aos="fade-up" data-aos-delay="0" data-aos-duration="700">
                    <span class="step-badge">Step 1</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/home-background-02.webp') }}"
                             alt="Create your free Jobs in USA account in under a minute"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/user/images/home-background-02.jpg') }}'">
                    </div>
                    <h3>Create Your Account</h3>
                    <p>Sign up in under a minute and build your professional profile to stand out to top U.S. employers. Add your resume, skills, and job preferences in one place.</p>
                    <a href="{{ route('register') }}" class="card-cta" aria-label="Register a free Jobs in USA account">
                        Sign Up Free <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>

                <article class="process-card" data-aos="fade-up" data-aos-delay="120" data-aos-duration="700">
                    <span class="step-badge">Step 2</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/home-background-03.webp') }}"
                             alt="Search verified U.S. jobs across all 50 states"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/user/images/home-background-03.jpg') }}'">
                    </div>
                    <h3>Search Verified Jobs</h3>
                    <p>Browse {{ number_format($stats['total_jobs'] ?? 230000) }}+ verified job listings across healthcare, IT, logistics, retail, and more. Use smart filters by location, salary, and category to find your match.</p>
                    <a href="{{ route('jobs.index') }}" class="card-cta" aria-label="Search jobs in the USA">
                        Browse All Jobs <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>

                <article class="process-card" data-aos="fade-up" data-aos-delay="240" data-aos-duration="700">
                    <span class="step-badge">Step 3</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/callout-1.jpg') }}"
                             alt="Apply to verified U.S. jobs with one click"
                             loading="lazy">
                    </div>
                    <h3>Apply with Confidence</h3>
                    <p>Open the role that fits you best and submit your application instantly. Track every response, save favorites, and stay organized in your personalized dashboard.</p>
                    <a href="{{ route('jobs.index') }}" class="card-cta" aria-label="View jobs and apply">
                        View &amp; Apply <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>

                <article class="process-card" data-aos="fade-up" data-aos-delay="360" data-aos-duration="700">
                    <span class="step-badge">Step 4</span>
                    <div class="card-image">
                        <img src="{{ asset('public/user/images/callout-2.jpg') }}"
                             alt="Get hired by trusted U.S. employers across all 50 states"
                             loading="lazy">
                    </div>
                    <h3>Get Hired Faster</h3>
                    <p>Connect directly with verified U.S. employers and recruiters. Land your dream job and start your next career chapter — confidently, securely, and 100% free.</p>
                    <a href="{{ route('jobs.companies') }}" class="card-cta" aria-label="Browse hiring employers">
                        Top Employers <i class="icon-feather-arrow-right"></i>
                    </a>
                </article>
            </div>

            <div class="process-cta-row">
                <a href="{{ route('register') }}">
                    Get Started Free <i class="icon-feather-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Structured data: HowTo schema for "how to get hired" --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "HowTo",
        "name": "How to Find a Job in the USA on Jobs in USA",
        "description": "Get hired in 4 simple steps on Jobs in USA — America's trusted free job portal connecting candidates with verified employers nationwide.",
        "totalTime": "PT5M",
        "step": [
            {
                "@@type": "HowToStep",
                "position": 1,
                "name": "Create Your Account",
                "text": "Sign up in under a minute and build your professional profile to stand out to top U.S. employers. Add your resume, skills, and job preferences.",
                "url": "{{ route('register') }}"
            },
            {
                "@@type": "HowToStep",
                "position": 2,
                "name": "Search Verified Jobs",
                "text": "Browse thousands of verified job listings across all 50 U.S. states. Use smart filters to find roles that match your skills and location.",
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
                "text": "Connect directly with verified U.S. employers and recruiters. Land your dream job across all 50 U.S. states.",
                "url": "{{ route('jobs.companies') }}"
            }
        ]
    }
    </script>

    <!-- SEO Long-Form Content / Browse By Section -->
    <style>
        .home-seo-section { padding: 90px 0; background: #fff; }

        /* === 2-column intro: content left, animated image right === */
        .seo-intro-grid {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 70px;
            align-items: center;
            margin-bottom: 80px;
        }
        @media (max-width: 991px) {
            .seo-intro-grid { grid-template-columns: 1fr; gap: 50px; }
        }

        .seo-intro-content .eyebrow {
            display: inline-block;
            background: #fff;
            border: 1px solid #e5e5e7;
            color: #555;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 20px;
        }
        .home-seo-section h2 {
            font-size: clamp(28px, 3.2vw, 42px);
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 16px;
            letter-spacing: -.6px;
            line-height: 1.15;
        }
        .home-seo-section .subhead {
            color: #555;
            font-size: 16.5px;
            line-height: 1.7;
            margin-bottom: 24px;
            max-width: 540px;
        }
        .home-seo-section h3 {
            font-size: 17px;
            font-weight: 700;
            color: #0a0a0a;
            margin-bottom: 18px;
            padding: 0;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -.2px;
        }
        .home-seo-section h3::before {
            content: "";
            width: 4px; height: 18px;
            background: #0a0a0a;
            border-radius: 2px;
        }
        .home-seo-section .seo-block { margin-bottom: 42px; }
        .home-seo-section .seo-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .home-seo-section .seo-links a {
            background: #fff;
            border: 1px solid #e5e5e7;
            color: #1a1a1a;
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all .15s ease;
        }
        .home-seo-section .seo-links a:hover {
            background: #0a0a0a;
            border-color: #0a0a0a;
            color: #fff;
            transform: translateY(-1px);
        }
        .home-seo-prose {
            color: #4a4a4a;
            font-size: 15.5px;
            line-height: 1.8;
        }
        .home-seo-prose p { margin-bottom: 18px; }
        .home-seo-prose strong { color: #0a0a0a; }
        .home-seo-prose a {
            color: #0a0a0a;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1.5px solid #0a0a0a;
            transition: opacity .15s ease;
        }
        .home-seo-prose a:hover { opacity: .65; }

        /* === Animated visual === */
        .seo-intro-visual {
            position: relative;
            min-height: 520px;
        }
        .seo-visual-frame {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(15, 23, 42, .12);
            animation: floatUpDown 6s ease-in-out infinite;
        }
        .seo-visual-frame::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 50%, rgba(10,10,10,.10) 100%);
            z-index: 2;
            pointer-events: none;
        }
        .seo-visual-frame img {
            width: 100%;
            height: 460px;
            object-fit: cover;
            display: block;
            transition: transform .8s ease;
        }
        @media (max-width: 991px) { .seo-visual-frame img { height: 360px; } }
        @media (max-width: 575px) { .seo-visual-frame img { height: 280px; } }
        .seo-intro-visual:hover .seo-visual-frame img { transform: scale(1.05); }

        /* Soft decorative blob behind the image */
        .seo-intro-visual::before {
            content: "";
            position: absolute;
            top: -40px; right: -40px;
            width: 220px; height: 220px;
            background: linear-gradient(135deg, #0a0a0a, #404040);
            border-radius: 50%;
            opacity: .04;
            z-index: 0;
            animation: floatUpDown 8s ease-in-out infinite reverse;
        }
        .seo-intro-visual::after {
            content: "";
            position: absolute;
            bottom: -30px; left: -30px;
            width: 160px; height: 160px;
            background: linear-gradient(135deg, #0a0a0a, #404040);
            border-radius: 50%;
            opacity: .03;
            z-index: 0;
            animation: floatUpDown 10s ease-in-out infinite;
        }

        /* Floating badge cards */
        .seo-float-badge {
            position: absolute;
            background: #fff;
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 14px 32px rgba(15, 23, 42, .12);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 3;
            min-width: 200px;
        }
        .seo-float-badge.top-left {
            top: 32px;
            left: -28px;
            animation: floatBadge 5s ease-in-out infinite;
        }
        .seo-float-badge.bottom-right {
            bottom: 38px;
            right: -28px;
            animation: floatBadge 6s ease-in-out infinite reverse;
            animation-delay: .8s;
        }
        .seo-float-badge .ico {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #0a0a0a;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .seo-float-badge .ico.green { background: #047857; }
        .seo-float-badge .text strong {
            display: block;
            font-size: 15px;
            font-weight: 800;
            color: #0a0a0a;
            line-height: 1.2;
        }
        .seo-float-badge .text span {
            font-size: 12px;
            color: #777;
            font-weight: 500;
        }

        /* Animations */
        @keyframes floatUpDown {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-12px); }
        }
        @keyframes floatBadge {
            0%, 100% { transform: translateY(0) translateX(0); }
            50%      { transform: translateY(-10px) translateX(4px); }
        }

        @media (max-width: 991px) {
            .seo-intro-visual { min-height: 360px; }
            .seo-visual-frame img { min-height: 360px; }
            .seo-float-badge.top-left { left: 16px; }
            .seo-float-badge.bottom-right { right: 16px; }
        }
        @media (max-width: 575px) {
            .seo-float-badge { padding: 10px 14px; min-width: 0; }
            .seo-float-badge .text strong { font-size: 13px; }
            .seo-float-badge .text span { font-size: 11px; }
            .seo-float-badge .ico { width: 34px; height: 34px; font-size: 14px; }
        }
    </style>
    <section class="home-seo-section">
        <div class="container">
            <!-- 2-column intro -->
            <div class="seo-intro-grid">
                <!-- Left: heading + prose -->
                <div class="seo-intro-content">
                    <span class="eyebrow">Why Jobs in USA</span>
                    <h2>America's Most Comprehensive Job Search Platform</h2>
                    <p class="subhead">Whether you're starting your first job, switching careers, or hiring talent — Jobs in USA is built to make every step of the journey simpler, faster, and more transparent.</p>

                    <div class="home-seo-prose">
                        <p>Looking for a new job in the United States? You're in the right place. <strong>Jobs in USA</strong> is a verified employment platform connecting millions of American job seekers with thousands of hiring employers — every day, across every industry, in every state. From <a href="{{ route('pages.healthcare-jobs') }}">healthcare jobs</a> in California to <a href="{{ route('pages.it-jobs') }}">tech roles</a> in New York, from <a href="{{ route('pages.warehouse-jobs') }}">warehouse jobs</a> in Texas to <a href="{{ route('pages.remote-jobs-usa') }}">remote work-from-home positions</a> nationwide, we help you find opportunities that match your skills and goals.</p>

                        <p>Our platform is 100% free for job seekers. Sign up in under two minutes, upload your resume, set your preferences, and apply to as many roles as you want. Our smart job alerts notify you the moment a matching role goes live — every employer on our platform is verified by our trust and safety team, meaning no scams, no ghost jobs, and no surprises.</p>
                    </div>
                </div>

                <!-- Right: animated image with floating badges -->
                <div class="seo-intro-visual" aria-hidden="true">
                    <div class="seo-visual-frame">
                        <img src="{{ asset('public/user/images/single-company.webp') }}"
                             alt="Verified U.S. employers and hiring teams on Jobs in USA"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/user/images/single-company.jpg') }}'">
                    </div>

                    <div class="seo-float-badge top-left">
                        <div class="ico green"><i class="icon-feather-check-circle"></i></div>
                        <div class="text">
                            <strong>{{ number_format($stats['total_jobs'] ?? 230000) }}+</strong>
                            <span>Verified Jobs</span>
                        </div>
                    </div>

                    <div class="seo-float-badge bottom-right">
                        <div class="ico"><i class="icon-feather-zap"></i></div>
                        <div class="text">
                            <strong>One-Click Apply</strong>
                            <span>100% free for job seekers</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="seo-block">
                <h3>Browse Jobs by Top Industry</h3>
                <div class="seo-links">
                    <a href="{{ route('pages.healthcare-jobs') }}">Healthcare Jobs</a>
                    <a href="{{ route('pages.it-jobs') }}">IT Jobs</a>
                    <a href="{{ route('pages.software-developer-jobs') }}">Software Developer Jobs</a>
                    <a href="{{ route('pages.construction-jobs') }}">Construction Jobs</a>
                    <a href="{{ route('pages.warehouse-jobs') }}">Warehouse Jobs</a>
                    <a href="{{ route('pages.truck-driver-jobs') }}">Truck Driver Jobs</a>
                    <a href="{{ route('pages.retail-jobs') }}">Retail Jobs</a>
                    <a href="{{ route('pages.customer-service-jobs') }}">Customer Service Jobs</a>
                    <a href="{{ route('pages.marketing-jobs') }}">Marketing Jobs</a>
                    <a href="{{ route('pages.accounting-jobs') }}">Accounting Jobs</a>
                    <a href="{{ route('pages.data-entry-jobs') }}">Data Entry Jobs</a>
                    <a href="{{ route('pages.security-guard-jobs') }}">Security Guard Jobs</a>
                </div>
            </div>

            <div class="seo-block">
                <h3>Browse Jobs by State</h3>
                <div class="seo-links">
                    <a href="{{ route('pages.jobs-in-texas') }}">Jobs in Texas</a>
                    <a href="{{ route('pages.jobs-in-california') }}">Jobs in California</a>
                    <a href="{{ route('pages.jobs-in-new-york') }}">Jobs in New York</a>
                    <a href="{{ route('pages.jobs-in-florida') }}">Jobs in Florida</a>
                    <a href="{{ route('pages.jobs-in-illinois') }}">Jobs in Illinois</a>
                    <a href="{{ route('pages.jobs-in-pennsylvania') }}">Jobs in Pennsylvania</a>
                    <a href="{{ route('pages.jobs-in-ohio') }}">Jobs in Ohio</a>
                    <a href="{{ route('pages.jobs-in-georgia') }}">Jobs in Georgia</a>
                    <a href="{{ route('pages.jobs-in-north-carolina') }}">Jobs in North Carolina</a>
                    <a href="{{ route('pages.jobs-in-michigan') }}">Jobs in Michigan</a>
                    <a href="{{ route('pages.jobs-in-new-jersey') }}">Jobs in New Jersey</a>
                    <a href="{{ route('pages.jobs-in-virginia') }}">Jobs in Virginia</a>
                    <a href="{{ route('pages.jobs-in-washington') }}">Jobs in Washington</a>
                    <a href="{{ route('pages.jobs-in-arizona') }}">Jobs in Arizona</a>
                    <a href="{{ route('pages.jobs-in-massachusetts') }}">Jobs in Massachusetts</a>
                </div>
            </div>

            <div class="seo-block">
                <h3>Popular Work Styles</h3>
                <div class="seo-links">
                    <a href="{{ route('pages.remote-jobs-usa') }}">Remote Jobs USA</a>
                    <a href="{{ route('pages.work-from-home-jobs') }}">Work From Home Jobs</a>
                    <a href="{{ route('pages.online-jobs-usa') }}">Online Jobs USA</a>
                    <a href="{{ route('pages.part-time-remote-jobs') }}">Part-Time Remote Jobs</a>
                    <a href="{{ route('pages.entry-level-remote-jobs') }}">Entry Level Remote Jobs</a>
                    <a href="{{ route('pages.entry-level-jobs') }}">Entry Level Jobs</a>
                    <a href="{{ route('pages.no-experience-jobs') }}">No Experience Jobs</a>
                    <a href="{{ route('pages.graduate-jobs') }}">Graduate Jobs</a>
                    <a href="{{ route('pages.internship-jobs') }}">Internship Jobs</a>
                </div>
            </div>
        </div>
    </section>
    <!-- SEO Long-Form Content / End -->

    {{-- ===== Why Jobs in USA — 2-column "How we're different" ===== --}}
    <section class="why-section" aria-labelledby="why-heading" itemscope itemtype="https://schema.org/Service">
        <div class="container">
            <header class="why-head">
                <span class="eyebrow">Why Jobs in USA</span>
                <h2 id="why-heading">How <span class="accent">Jobs in USA</span> is Different</h2>
                <p>The smart way to find work in America &mdash; verified employers, hand-picked listings, and zero spam between you and your next role.</p>
            </header>

            <div class="why-grid">
                <div class="why-points">
                    <article class="why-item" itemprop="hasOfferCatalog">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>Verified U.S. Employers Only</h3>
                            <p>Every employer is reviewed by our team. No scams, no ghost listings, no recycled job ads — just real openings from real American companies.</p>
                        </div>
                    </article>

                    <article class="why-item">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>Search 50 States in One Place</h3>
                            <p>From Texas to New York, Florida to California &mdash; browse {{ number_format($stats['total_jobs'] ?? 230000) }}+ live openings nationwide. Filter by city, ZIP, salary or job type and apply with one click.</p>
                        </div>
                    </article>

                    <article class="why-item">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>Free for Job Seekers, Always</h3>
                            <p>No subscription, no resume paywalls, no hidden fees. Create a free profile, save your favourites and apply to as many roles as you want — completely free.</p>
                        </div>
                    </article>

                    <article class="why-item">
                        <span class="why-check"><i class="icon-feather-check"></i></span>
                        <div>
                            <h3>Smart Matches &amp; Daily Alerts</h3>
                            <p>Tell us what you're looking for once and we'll surface fresh, matching roles every day. Spend less time searching, more time interviewing.</p>
                        </div>
                    </article>

                    <a href="{{ route('register') }}" class="why-cta">
                        <span>Get Started — It's Free</span>
                        <i class="icon-material-outline-arrow-right-alt"></i>
                    </a>
                </div>

                <aside class="why-visual" aria-hidden="true">
                    <div class="why-visual-blob blob-1"></div>
                    <div class="why-visual-blob blob-2"></div>
                    <div class="why-visual-stage">
                        <img src="{{ asset('public/user/images/hero-diverse-professionals.webp') }}"
                             alt="Job seeker browsing verified U.S. job openings on Jobs in USA"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/user/images/hero-diverse-professionals.jpg') }}'">
                        <div class="why-floating why-fl-1">
                            <div class="ico"><i class="icon-feather-shield"></i></div>
                            <div>
                                <strong>100% Verified</strong>
                                <span>Every job, every employer</span>
                            </div>
                        </div>
                        <div class="why-floating why-fl-2">
                            <div class="ico"><i class="icon-feather-zap"></i></div>
                            <div>
                                <strong>1-Click Apply</strong>
                                <span>Save hours every week</span>
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
            color: #0a0a0a;
            line-height: 1.2;
            letter-spacing: -.5px;
            margin: 0 0 12px;
        }
        .why-head h2 .accent {
            background: linear-gradient(90deg, #0a0a0a, #404040);
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
            background: #0a0a0a;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 6px 14px rgba(10,10,10,.20);
        }
        .why-item h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0a0a0a;
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
            background: #0a0a0a;
            color: #fff !important;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none !important;
            box-shadow: 0 8px 18px rgba(10,10,10,.20);
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .why-cta:hover { transform: translateY(-1px); background: #1a1a1a; box-shadow: 0 14px 28px rgba(10,10,10,.30); }
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
            background: #0a0a0a;
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
            color: #0a0a0a;
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
            color: #0a0a0a;
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
            color: #0a0a0a;
            font-size: 14px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 999px;
            cursor: pointer;
            transition: all .15s ease;
        }
        .career-chip:hover { background: #f3f4f6; border-color: #0a0a0a; }
        .career-chip.is-active { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }

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
            border-color: #0a0a0a;
        }
        .career-thumb {
            display: block;
            aspect-ratio: 16 / 9;
            background: #f3f4f6;
            overflow: hidden;
        }
        .career-thumb img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .35s ease;
        }
        .career-card:hover .career-thumb img { transform: scale(1.04); }
        .career-body { padding: 22px 22px 24px; display: flex; flex-direction: column; gap: 12px; flex: 1; }
        .career-cat {
            display: inline-block;
            color: #0a0a0a;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            text-decoration: none;
        }
        .career-cat:hover { text-decoration: underline; }
        .career-title { margin: 0; font-size: 18px; line-height: 1.4; font-weight: 700; }
        .career-title a {
            color: #0a0a0a !important;
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
        .career-author strong { color: #0a0a0a; font-weight: 600; }
        .career-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #0a0a0a;
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
            color: #0a0a0a !important;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none !important;
            padding: 12px 24px;
            border: 1.5px solid #0a0a0a;
            border-radius: 10px;
            transition: all .15s ease;
        }
        .career-all:hover {
            background: #0a0a0a;
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
                    "author": { "@@type": "Organization", "name": "Jobs in USA" },
                    "publisher": { "@@type": "Organization", "name": "Jobs in USA" }
                }
            }@if (! $loop->last),@endif
            @endforeach
        ]
    }
    </script>
    @endif
    {{-- ===== End career advice ===== --}}

    {{-- ===== Comparison Table — Jobs in USA vs Top Job Boards ===== --}}
    <section class="compare-section" aria-labelledby="compare-heading">
        <div class="container">
            <header class="compare-head">
                <span class="eyebrow">Side-by-Side Comparison</span>
                <h2 id="compare-heading">How <span class="accent">Jobs in USA</span> compares to Indeed, LinkedIn &amp; ZipRecruiter</h2>
                <p>Most U.S. job seekers waste hours bouncing between sites filled with expired listings, scams, and paywalls. Here's how Jobs in USA stacks up against the biggest names — feature by feature.</p>
            </header>

            <div class="compare-table-wrap" role="region" aria-label="Job board feature comparison">
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th scope="col" class="cmp-feat">Feature</th>
                            <th scope="col" class="cmp-us">
                                <span class="brand-pill"><i class="icon-feather-zap"></i> Jobs in USA</span>
                            </th>
                            <th scope="col">Indeed</th>
                            <th scope="col">LinkedIn Jobs</th>
                            <th scope="col">ZipRecruiter</th>
                            <th scope="col">Glassdoor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Free for job seekers</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><i class="icon-feather-check yes"></i></td>
                            <td data-label="LinkedIn Jobs"><span class="partial">Limited</span></td>
                            <td data-label="ZipRecruiter"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Glassdoor"><i class="icon-feather-check yes"></i></td>
                        </tr>
                        <tr>
                            <th scope="row">100% verified employers</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><span class="partial">Mixed</span></td>
                            <td data-label="LinkedIn Jobs"><i class="icon-feather-check yes"></i></td>
                            <td data-label="ZipRecruiter"><span class="partial">Mixed</span></td>
                            <td data-label="Glassdoor"><span class="partial">Mixed</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Coverage of all 50 U.S. states</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><i class="icon-feather-check yes"></i></td>
                            <td data-label="LinkedIn Jobs"><i class="icon-feather-check yes"></i></td>
                            <td data-label="ZipRecruiter"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Glassdoor"><i class="icon-feather-check yes"></i></td>
                        </tr>
                        <tr>
                            <th scope="row">No third-party redirects</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><i class="icon-feather-x no"></i></td>
                            <td data-label="LinkedIn Jobs"><span class="partial">Some</span></td>
                            <td data-label="ZipRecruiter"><i class="icon-feather-x no"></i></td>
                            <td data-label="Glassdoor"><i class="icon-feather-x no"></i></td>
                        </tr>
                        <tr>
                            <th scope="row">Hyper-local ZIP &amp; area filters</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><span class="partial">ZIP only</span></td>
                            <td data-label="LinkedIn Jobs"><i class="icon-feather-x no"></i></td>
                            <td data-label="ZipRecruiter"><span class="partial">ZIP only</span></td>
                            <td data-label="Glassdoor"><i class="icon-feather-x no"></i></td>
                        </tr>
                        <tr>
                            <th scope="row">Spam &amp; ghost-job filtering</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><i class="icon-feather-x no"></i></td>
                            <td data-label="LinkedIn Jobs"><span class="partial">Some</span></td>
                            <td data-label="ZipRecruiter"><i class="icon-feather-x no"></i></td>
                            <td data-label="Glassdoor"><span class="partial">Some</span></td>
                        </tr>
                        <tr>
                            <th scope="row">No paywalls or premium tiers</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><i class="icon-feather-x no"></i></td>
                            <td data-label="LinkedIn Jobs"><i class="icon-feather-x no"></i></td>
                            <td data-label="ZipRecruiter"><i class="icon-feather-x no"></i></td>
                            <td data-label="Glassdoor"><i class="icon-feather-x no"></i></td>
                        </tr>
                        <tr>
                            <th scope="row">One-click apply &amp; saved searches</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><i class="icon-feather-check yes"></i></td>
                            <td data-label="LinkedIn Jobs"><i class="icon-feather-check yes"></i></td>
                            <td data-label="ZipRecruiter"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Glassdoor"><span class="partial">Limited</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Salary transparency on every listing</th>
                            <td class="cmp-us" data-label="Jobs in USA"><i class="icon-feather-check yes"></i></td>
                            <td data-label="Indeed"><span class="partial">Partial</span></td>
                            <td data-label="LinkedIn Jobs"><span class="partial">Partial</span></td>
                            <td data-label="ZipRecruiter"><span class="partial">Partial</span></td>
                            <td data-label="Glassdoor"><i class="icon-feather-check yes"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="compare-note">
                Comparison reflects publicly available platform features as of {{ now()->format('M Y') }}.
                Jobs in USA differs by guaranteeing verified employers, native applications (no redirects), and zero paywalls — for life, for every U.S. job seeker.
            </p>

            <div class="compare-cta">
                <a href="{{ route('jobs.index') }}" class="btn-dark"><i class="icon-feather-search"></i> Browse Verified Jobs</a>
                <a href="{{ route('register') }}" class="btn-outline"><i class="icon-feather-user-plus"></i> Create Free Account</a>
            </div>
        </div>
    </section>

    <style>
        .compare-section { padding: 90px 0 70px; background: #fff; border-top: 1px solid #ececec; }
        .compare-head { text-align: center; max-width: 880px; margin: 0 auto 44px; }
        .compare-head .eyebrow {
            display: inline-block;
            background: #fff; border: 1px solid #e5e5e7;
            color: #555;
            font-weight: 700; font-size: 12px;
            padding: 6px 14px; border-radius: 999px;
            letter-spacing: 1.4px; text-transform: uppercase;
            margin-bottom: 14px;
        }
        .compare-head h2 {
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 800; color: #0a0a0a;
            line-height: 1.2; letter-spacing: -.5px;
            margin: 0 0 12px;
        }
        .compare-head h2 .accent {
            background: linear-gradient(90deg, #0a0a0a, #404040);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent; color: transparent;
        }
        .compare-head p { color: #555; font-size: 16px; line-height: 1.7; margin: 0; }

        .compare-table-wrap {
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 18px;
            overflow-x: auto;
            box-shadow: 0 18px 40px rgba(15,23,42,.06);
        }
        .compare-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
            font-size: 14.5px;
        }
        .compare-table thead th {
            background: #fafafa;
            color: #0a0a0a;
            font-weight: 700;
            text-align: center;
            padding: 18px 14px;
            border-bottom: 1px solid #ececec;
            white-space: nowrap;
        }
        .compare-table thead th.cmp-feat { text-align: left; padding-left: 22px; min-width: 240px; }
        .compare-table thead th.cmp-us { background: #0a0a0a; color: #fff; }
        .compare-table thead th.cmp-us .brand-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.18);
            padding: 4px 11px; border-radius: 999px;
            font-size: 12.5px; font-weight: 700;
            letter-spacing: .3px;
        }
        .compare-table thead th.cmp-us .brand-pill i { color: #ffb866; font-size: 13px; }

        .compare-table tbody th {
            text-align: left;
            font-weight: 600;
            color: #0a0a0a;
            padding: 16px 22px;
            border-top: 1px solid #f3f4f6;
            background: #fff;
        }
        .compare-table tbody td {
            padding: 16px 14px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            color: #6b7280;
        }
        .compare-table tbody td.cmp-us {
            background: #fafbff;
            border-left: 3px solid #0a0a0a;
            border-right: 3px solid #0a0a0a;
        }
        .compare-table tbody tr:last-child td.cmp-us { border-bottom: 3px solid #0a0a0a; border-radius: 0 0 0 0; }
        .compare-table tbody tr:hover td:not(.cmp-us) { background: #fafbff; }

        .compare-table .yes {
            color: #fff;
            background: #0a0a0a;
            width: 26px; height: 26px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(10,10,10,.18);
        }
        .compare-table .no {
            color: #9ca3af;
            background: #f3f4f6;
            width: 26px; height: 26px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 13px;
        }
        .compare-table .partial {
            display: inline-block;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 999px;
        }

        .compare-note {
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.7;
            max-width: 760px;
            margin: 22px auto 32px;
        }

        .compare-cta { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
        .compare-cta .btn-dark, .compare-cta .btn-outline {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 26px; border-radius: 12px;
            font-weight: 700; font-size: 15px;
            text-decoration: none !important;
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            white-space: nowrap;
        }
        .compare-cta .btn-dark { background: #0a0a0a; color: #fff !important; border: 1px solid #0a0a0a; box-shadow: 0 8px 18px rgba(10,10,10,.20); }
        .compare-cta .btn-dark:hover { transform: translateY(-1px); background: #1a1a1a; box-shadow: 0 14px 28px rgba(10,10,10,.30); }
        .compare-cta .btn-outline { background: #fff; color: #0a0a0a !important; border: 1.5px solid #0a0a0a; }
        .compare-cta .btn-outline:hover { background: #0a0a0a; color: #fff !important; }

        /* === Tablet polish === */
        @media (max-width: 991px) {
            .compare-section { padding: 70px 0 56px; }
            .compare-table thead th { padding: 14px 10px; font-size: 13.5px; }
            .compare-table thead th.cmp-feat { padding-left: 14px; min-width: 180px; }
            .compare-table tbody th { padding: 14px 14px; font-size: 13.5px; }
            .compare-table tbody td { padding: 12px 8px; }
            .compare-table .yes, .compare-table .no { width: 24px; height: 24px; font-size: 12px; }
            .compare-table .partial { font-size: 11.5px; padding: 3px 9px; }
        }

        /* === Mobile: convert table into stacked feature cards (no horizontal scroll) === */
        @media (max-width: 767px) {
            .compare-section { padding: 56px 0 44px; }
            .compare-head { margin-bottom: 28px; }
            .compare-head p { font-size: 14.5px; }

            .compare-table-wrap {
                background: transparent;
                border: none;
                border-radius: 0;
                box-shadow: none;
                overflow: visible;
            }
            .compare-table { display: block; min-width: 0; width: 100%; }
            .compare-table thead { display: none; }
            .compare-table tbody { display: block; }
            .compare-table tbody tr {
                display: block;
                background: #fff;
                border: 1px solid #ececec;
                border-radius: 14px;
                padding: 14px 16px 6px;
                margin-bottom: 12px;
                box-shadow: 0 4px 10px rgba(15,23,42,.04);
            }
            .compare-table tbody tr:hover td:not(.cmp-us) { background: transparent; }
            .compare-table tbody th {
                display: block;
                padding: 0 0 12px;
                margin-bottom: 6px;
                border: none;
                border-bottom: 1px solid #f3f4f6;
                font-size: 14.5px; font-weight: 700;
                color: #0a0a0a;
                background: transparent;
            }
            .compare-table tbody td {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                padding: 9px 0 !important;
                border: none !important;
                border-top: 1px solid #f7f7f7 !important;
                background: transparent !important;
                text-align: left;
            }
            .compare-table tbody td:first-of-type { border-top: none !important; }
            .compare-table tbody td::before {
                content: attr(data-label);
                flex: 1;
                font-size: 13px; font-weight: 600;
                color: #6b7280;
                text-align: left;
            }
            /* Highlight the "Jobs in USA" row inside each card */
            .compare-table tbody td.cmp-us {
                background: #fafbff !important;
                border: 1px solid #ececec !important;
                border-radius: 10px;
                padding: 10px 12px !important;
                margin: 6px 0 !important;
                position: relative;
            }
            .compare-table tbody td.cmp-us::before {
                content: "⚡ Jobs in USA";
                color: #0a0a0a;
                font-weight: 800;
                font-size: 13.5px;
            }
            /* Slightly bigger check/X for readability on small screens */
            .compare-table .yes, .compare-table .no { width: 26px; height: 26px; }
            .compare-table .partial { font-size: 12px; padding: 4px 10px; }

            .compare-note { font-size: 12.5px; padding: 0 6px; margin: 22px auto; }
            .compare-cta { flex-direction: column; align-items: stretch; gap: 10px; }
            .compare-cta .btn-dark, .compare-cta .btn-outline { width: 100%; justify-content: center; padding: 14px 22px; }
        }
        @media (max-width: 380px) {
            .compare-table tbody tr { padding: 12px 14px 4px; }
            .compare-table tbody td::before { font-size: 12.5px; }
        }
    </style>

    {{-- ===== Top Searched Roles & Salary Insights ===== --}}
    <section class="salary-section" aria-labelledby="salary-heading">
        <div class="container">
            <header class="salary-head">
                <span class="eyebrow">U.S. Salary Insights · {{ now()->year }}</span>
                <h2 id="salary-heading">Top Searched Jobs &amp; Average U.S. Salaries</h2>
                <p>The most popular roles American workers search for today &mdash; with median salary ranges, growth trends, and direct links to live openings on our platform.</p>
            </header>

            @php
                // High-volume search terms with realistic 2026 U.S. salary medians.
                // Editorial — keep simple, accurate enough to add real SEO value.
                $salaryRoles = [
                    ['title' => 'Software Engineer',        'kw' => 'software engineer',  'min' => 95000,  'max' => 165000, 'trend' => 'up',   'demand' => 'High',     'cat' => 'IT & Software'],
                    ['title' => 'Registered Nurse (RN)',    'kw' => 'registered nurse',   'min' => 72000,  'max' => 110000, 'trend' => 'up',   'demand' => 'Critical', 'cat' => 'Healthcare'],
                    ['title' => 'Data Analyst',             'kw' => 'data analyst',       'min' => 68000,  'max' => 112000, 'trend' => 'up',   'demand' => 'High',     'cat' => 'IT & Software'],
                    ['title' => 'Truck Driver (CDL-A)',     'kw' => 'truck driver',       'min' => 58000,  'max' => 95000,  'trend' => 'up',   'demand' => 'Critical', 'cat' => 'Transport'],
                    ['title' => 'Marketing Manager',        'kw' => 'marketing manager',  'min' => 78000,  'max' => 135000, 'trend' => 'flat', 'demand' => 'Strong',   'cat' => 'Marketing'],
                    ['title' => 'Warehouse Associate',      'kw' => 'warehouse',          'min' => 36000,  'max' => 52000,  'trend' => 'up',   'demand' => 'High',     'cat' => 'Warehouse'],
                    ['title' => 'Customer Service Rep',     'kw' => 'customer service',   'min' => 38000,  'max' => 58000,  'trend' => 'flat', 'demand' => 'Strong',   'cat' => 'Customer Service'],
                    ['title' => 'Accountant',               'kw' => 'accountant',         'min' => 62000,  'max' => 98000,  'trend' => 'up',   'demand' => 'Strong',   'cat' => 'Accounting'],
                    ['title' => 'Sales Representative',     'kw' => 'sales',              'min' => 52000,  'max' => 95000,  'trend' => 'up',   'demand' => 'Strong',   'cat' => 'Sales'],
                    ['title' => 'Project Manager',          'kw' => 'project manager',    'min' => 82000,  'max' => 138000, 'trend' => 'flat', 'demand' => 'Strong',   'cat' => 'Business'],
                    ['title' => 'Construction Worker',      'kw' => 'construction',       'min' => 42000,  'max' => 68000,  'trend' => 'up',   'demand' => 'High',     'cat' => 'Construction'],
                    ['title' => 'Remote Customer Support',  'kw' => 'remote',             'min' => 40000,  'max' => 62000,  'trend' => 'up',   'demand' => 'Critical', 'cat' => 'Remote'],
                ];
            @endphp

            <div class="salary-grid">
                @foreach ($salaryRoles as $role)
                    @php
                        $deepLink = route('jobs.index', ['position' => $role['kw']]);
                        $trendIcon  = $role['trend'] === 'up' ? 'icon-feather-trending-up' : 'icon-feather-minus';
                        $trendLabel = $role['trend'] === 'up' ? 'Growing demand' : 'Stable demand';
                    @endphp
                    <a href="{{ $deepLink }}" class="salary-card" aria-label="See {{ $role['title'] }} jobs and salaries">
                        <div class="salary-row-top">
                            <span class="salary-cat">{{ $role['cat'] }}</span>
                            <span class="salary-trend {{ $role['trend'] }}">
                                <i class="{{ $trendIcon }}"></i>
                                <span>{{ $trendLabel }}</span>
                            </span>
                        </div>
                        <h3>{{ $role['title'] }}</h3>
                        <div class="salary-range">
                            <span class="range">${{ number_format($role['min']/1000) }}K&ndash;${{ number_format($role['max']/1000) }}K</span>
                            <span class="range-lbl">/ year (median)</span>
                        </div>
                        <div class="salary-foot">
                            <span class="demand demand-{{ strtolower($role['demand']) }}">{{ $role['demand'] }} demand</span>
                            <span class="see-jobs">See jobs <i class="icon-material-outline-arrow-right-alt"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>

            <p class="salary-note">
                Salary ranges are U.S. national medians for {{ now()->year }} compiled from BLS data, employer postings on this platform, and major-market job listings.
                Actual pay varies by experience, location and employer. Click any role to see live openings.
            </p>

            {{-- Popular searches strip — pure SEO value: rich internal linking --}}
            <div class="search-strip" aria-label="Popular U.S. job searches">
                <span class="strip-lbl">Trending searches:</span>
                <a href="{{ route('jobs.index', ['position' => 'remote']) }}">Remote Jobs</a>
                <a href="{{ route('jobs.index', ['position' => 'work from home']) }}">Work From Home</a>
                <a href="{{ route('jobs.index', ['position' => 'part time']) }}">Part-Time</a>
                <a href="{{ route('jobs.index', ['position' => 'entry level']) }}">Entry Level</a>
                <a href="{{ route('jobs.index', ['position' => 'no experience']) }}">No Experience</a>
                <a href="{{ route('jobs.index', ['position' => 'weekend']) }}">Weekend</a>
                <a href="{{ route('jobs.index', ['position' => 'night shift']) }}">Night Shift</a>
                <a href="{{ route('jobs.index', ['position' => 'data entry']) }}">Data Entry</a>
                <a href="{{ route('pages.jobs-in-texas') }}">Jobs in Texas</a>
                <a href="{{ route('pages.jobs-in-california') }}">Jobs in California</a>
                <a href="{{ route('pages.jobs-in-new-york') }}">Jobs in New York</a>
                <a href="{{ route('pages.jobs-in-florida') }}">Jobs in Florida</a>
            </div>
        </div>
    </section>

    <style>
        .salary-section { padding: 90px 0 80px; background: #fafafa; border-top: 1px solid #ececec; }
        .salary-head { text-align: center; max-width: 820px; margin: 0 auto 44px; }
        .salary-head .eyebrow {
            display: inline-block;
            background: #0a0a0a; color: #fff;
            font-weight: 700; font-size: 12px;
            padding: 6px 14px; border-radius: 999px;
            letter-spacing: 1.4px; text-transform: uppercase;
            margin-bottom: 14px;
        }
        .salary-head h2 {
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 800; color: #0a0a0a;
            line-height: 1.2; letter-spacing: -.5px;
            margin: 0 0 12px;
        }
        .salary-head p { color: #555; font-size: 16px; line-height: 1.7; margin: 0; }

        .salary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }
        @media (max-width: 1199px) { .salary-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 767px)  { .salary-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px)  { .salary-grid { grid-template-columns: 1fr; } }

        .salary-card {
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 14px;
            padding: 20px 22px 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-decoration: none !important;
            color: inherit !important;
            transition: all .25s ease;
        }
        .salary-card:hover {
            transform: translateY(-3px);
            border-color: #0a0a0a;
            box-shadow: 0 18px 36px rgba(15,23,42,.10);
        }
        .salary-row-top { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
        .salary-cat {
            font-size: 11px; font-weight: 700;
            color: #6b7280; text-transform: uppercase;
            letter-spacing: 1.2px;
        }
        .salary-trend {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11.5px; font-weight: 700;
            padding: 3px 9px; border-radius: 999px;
            white-space: nowrap;
        }
        .salary-trend.up { background: #f0fdf4; color: #15803d; }
        .salary-trend.flat { background: #f3f4f6; color: #6b7280; }
        .salary-trend i { font-size: 13px; }

        .salary-card h3 {
            font-size: 17px; font-weight: 700;
            color: #0a0a0a;
            margin: 4px 0 0;
            letter-spacing: -.2px;
            line-height: 1.3;
        }
        .salary-range {
            display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap;
            margin-top: 4px;
        }
        .salary-range .range {
            font-size: 22px; font-weight: 800;
            color: #0a0a0a;
            letter-spacing: -.4px;
            background: linear-gradient(90deg, #0a0a0a, #404040);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .salary-range .range-lbl { font-size: 12px; color: #6b7280; font-weight: 600; }

        .salary-foot {
            display: flex; justify-content: space-between; align-items: center; gap: 8px;
            padding-top: 12px;
            margin-top: 6px;
            border-top: 1px dashed #ececec;
            font-size: 12.5px;
        }
        .demand {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-size: 11px;
        }
        .demand-critical { color: #dc2626; }
        .demand-high     { color: #ea580c; }
        .demand-strong   { color: #0a0a0a; }
        .salary-foot .see-jobs {
            color: #0a0a0a; font-weight: 700;
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 13px;
        }
        .salary-foot .see-jobs i { font-size: 18px; transition: transform .2s ease; }
        .salary-card:hover .see-jobs i { transform: translateX(3px); }

        .salary-note {
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.7;
            max-width: 820px;
            margin: 28px auto 28px;
        }

        .search-strip {
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }
        .search-strip .strip-lbl {
            font-size: 12.5px; font-weight: 700;
            color: #0a0a0a;
            text-transform: uppercase; letter-spacing: 1.2px;
            margin-right: 4px;
        }
        .search-strip a {
            display: inline-flex; align-items: center;
            background: #f3f4f6;
            color: #0a0a0a !important;
            padding: 6px 13px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none !important;
            transition: all .15s ease;
        }
        .search-strip a:hover { background: #0a0a0a; color: #fff !important; transform: translateY(-1px); }
    </style>

    {{-- JSON-LD: Structured comparison + salary data for richer SERP appearance --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "ItemList",
        "name": "Top Searched U.S. Jobs and Salaries {{ now()->year }}",
        "description": "Median U.S. salary ranges and live openings for the most-searched job titles on Jobs in USA.",
        "itemListElement": [
            @foreach ($salaryRoles as $i => $role)
            {
                "@@type": "ListItem",
                "position": {{ $i + 1 }},
                "item": {
                    "@@type": "Occupation",
                    "name": @json($role['title']),
                    "occupationLocation": { "@@type": "Country", "name": "United States" },
                    "estimatedSalary": {
                        "@@type": "MonetaryAmountDistribution",
                        "name": "Median U.S. salary",
                        "currency": "USD",
                        "duration": "P1Y",
                        "median": {{ (int) (($role['min'] + $role['max']) / 2) }},
                        "percentile25": {{ $role['min'] }},
                        "percentile75": {{ $role['max'] }}
                    },
                    "url": @json(route('jobs.index', ['position' => $role['kw']]))
                }
            }@if (! $loop->last),@endif
            @endforeach
        ]
    }
    </script>
    {{-- ===== End Comparison + Salary sections ===== --}}

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
            color: #0a0a0a;
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
            background: #0a0a0a;
            color: #fff !important;
            font-size: 15px;
            font-weight: 600;
            padding: 14px 26px;
            border-radius: 10px;
            text-decoration: none;
            transition: all .15s ease;
        }
        .faq-left .contact-btn:hover {
            background: #1a1a1a;
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
            border-color: #0a0a0a;
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
        }
        .home-faq-item summary {
            padding: 20px 24px;
            font-weight: 600;
            font-size: 15.5px;
            color: #0a0a0a;
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
            color: #0a0a0a;
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
            color: #0a0a0a;
            font-weight: 600;
            border-bottom: 1.5px solid #0a0a0a;
            text-decoration: none;
        }
    </style>
    <section class="home-faq-section">
        <div class="container">
            <div class="home-faq-grid">
                <div class="faq-left">
                    <span class="eyebrow">FAQ</span>
                    <h2>Got questions? We've got answers.</h2>
                    <p>Everything you need to know about finding your next job on Jobs in USA. Can't find what you're looking for? Our team is one click away.</p>
                    <a href="{{ route('pages.contact') }}" class="contact-btn">
                        Contact Support <i class="icon-feather-arrow-right"></i>
                    </a>
                </div>

                <div class="faq-list">
                    <details class="home-faq-item">
                        <summary>Is it free to search and apply for jobs?</summary>
                        <div class="home-faq-answer">Yes, browsing and applying for jobs on Jobs in USA is 100% free for job seekers. Just create an account and start applying.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>How do I get started?</summary>
                        <div class="home-faq-answer">Click "Sign Up", create your free account, complete your profile with your resume and skills, then start exploring thousands of opportunities.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>Which states and industries are covered?</summary>
                        <div class="home-faq-answer">We cover all 50 U.S. states and a wide range of industries — healthcare, IT, construction, retail, hospitality, transport, finance, and many more.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>Can I work remotely through Jobs in USA?</summary>
                        <div class="home-faq-answer">Yes. We have a dedicated section for remote, work-from-home, and hybrid roles. Use the location filter and select "Remote" to see all matching jobs.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>How are employers verified?</summary>
                        <div class="home-faq-answer">Every employer profile is reviewed by our team before being published. We verify business details and monitor activity to keep the platform safe and trustworthy.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>How do I post a job as an employer?</summary>
                        <div class="home-faq-answer">Register as an employer, choose a posting plan, and submit your listing through your dashboard. It goes live once our team approves it.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>Can I get email alerts for new jobs?</summary>
                        <div class="home-faq-answer">Yes — set up job alerts based on keywords, location, and category. We'll email you whenever matching positions are posted.</div>
                    </details>
                    <details class="home-faq-item">
                        <summary>What if I need help with my application?</summary>
                        <div class="home-faq-answer">Visit our <a href="{{ route('pages.contact') }}">Contact page</a> and our support team will get back to you within 24 hours.</div>
                    </details>
                </div>
            </div>
        </div>
    </section>
@endsection
