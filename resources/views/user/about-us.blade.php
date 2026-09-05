@extends('user.layouts.master')
@section('title', 'About JobGader — Jobs and Honest Visa Guides')
@section('meta_description', 'Why JobGader exists: hand-checked listings across '.$coverage->shortList().', and visa guides that say which routes are open and which are closed.')
@section('meta_keywords', 'about jobgader, job board, visa sponsorship guides, jobs usa uk pakistan, free job search, jobs for foreigners, work abroad, hand checked job listings')
@section('og_title', 'About JobGader — Jobs and Honest Visa Guides')
@section('og_description', 'Hand-checked jobs across '.$coverage->shortList().', plus visa guides that tell you which sponsorship routes are actually open.')
@section('og_image', asset('public/user/images/single-company.jpg'))
@section('canonical', route('about.us'))

@push('meta')
    {{-- Twitter card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="About JobGader — Jobs and Honest Visa Guides">
    <meta name="twitter:description" content="Hand-checked jobs across {{ $coverage->shortList() }}, plus visa guides that say which routes are open.">
    <meta name="twitter:image" content="{{ asset('public/user/images/single-company.jpg') }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="JobGader">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="JobGader">
    <meta property="og:locale" content="en_US">

    {{-- JSON-LD: Organization schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "JobGader",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('public/user/images/favicon.png') }}",
        "description": "A job board and guide site covering openings in {{ $coverage->shortList() }}, with plain-English write-ups of which visa sponsorship routes are open to foreign workers.",
        "areaServed": {!! json_encode($coverage->areaServedNodes(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!},
        "sameAs": [
            "{{ url('/') }}"
        ],
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "Customer Support",
            "email": "{{ config('site.contact_email') }}",
            "availableLanguage": ["English"]
        }
    }
    </script>

    {{-- JSON-LD: WebPage schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "AboutPage",
        "name": "About JobGader",
        "url": "{{ route('about.us') }}",
        "description": "How JobGader works: hand-checked job listings across {{ $coverage->shortList() }}, and visa sponsorship guides that say which routes are open and which have closed.",
        "publisher": {
            "@@type": "Organization",
            "name": "JobGader",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('public/user/images/favicon.png') }}"
            }
        }
    }
    </script>

    {{-- JSON-LD: BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
            { "@@type": "ListItem", "position": 2, "name": "About Us", "item": "{{ route('about.us') }}" }
        ]
    }
    </script>

    {{-- JSON-LD: FAQPage --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "What is JobGader?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "JobGader is a job board and guide site covering openings in {{ $coverage->shortList() }}. Alongside the listings we publish guides explaining which visa sponsorship routes are genuinely open to foreign workers, which have closed, and what each role actually pays."
                }
            },
            {
                "@@type": "Question",
                "name": "Is JobGader free to use?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Yes, and you do not need an account. Every listing can be opened and applied to without signing up. We never charge job seekers, and no legitimate employer or recruiter should either."
                }
            },
            {
                "@@type": "Question",
                "name": "Do I need an account to apply for a job?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "No. An account is optional and only useful if you want to save roles for later. Applications go straight through to the employer or the original posting."
                }
            },
            {
                "@@type": "Question",
                "name": "Which countries and industries do you cover?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "The {{ $coverage->shortList() }}, across transport and logistics, hospitality, healthcare and care, construction and trades, cleaning and facilities, marketing, and IT and software."
                }
            },
            {
                "@@type": "Question",
                "name": "Are the visa sponsorship guides accurate?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "We write them from official sources such as gov.uk, USCIS and the US Department of Labor, and we say plainly when a route is closed. The UK care worker route shut to new overseas applicants in July 2025, and our guide leads with that rather than selling a visa that no longer exists."
                }
            },
            {
                "@@type": "Question",
                "name": "How are listings checked?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Every listing is added and reviewed by our team rather than scraped automatically, and each one links to the employer or original posting so you can verify it yourself before applying."
                }
            },
            {
                "@@type": "Question",
                "name": "Should I ever pay for a job or a visa?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "No. Charging a worker for visa sponsorship is illegal in both the US and the UK. If a recruiter or agency asks for an upfront fee to guarantee a job or a visa, treat it as a scam."
                }
            },
            {
                "@@type": "Question",
                "name": "How often is the site updated?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "New listings and guides go up every week, and the newest openings always appear at the top of the home page and the jobs board."
                }
            }
        ]
    }
    </script>
@endpush

@section('content')

<style>
    .about-page p { color: #555; line-height: 1.75; }
    .about-page h2, .about-page h3 { color: #1b3a6b; }

    /* === Hero — light gradient + dark text (matches home/jobs/companies/categories/locations) === */
    .about-hero {
        padding: 80px 0 70px;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .about-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: radial-gradient(circle at 12% 20%, rgba(27, 58, 107,.04) 0, transparent 40%),
                          radial-gradient(circle at 88% 80%, rgba(27, 58, 107,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .about-hero .container { position: relative; z-index: 2; }
    .about-hero-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    @media (max-width: 991px) {
        .about-hero-row { grid-template-columns: 1fr; gap: 40px; }
    }
    .about-hero-tag {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .about-hero h1 {
        font-size: clamp(32px, 4.4vw, 52px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.2px;
        color: #1b3a6b;
        margin-bottom: 22px;
    }
    .about-hero h1 span {
        background: linear-gradient(90deg, #1b3a6b, #4a90d9);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .about-hero .lead {
        font-size: clamp(15px, 1.5vw, 17px);
        line-height: 1.7;
        color: #555;
        margin-bottom: 28px;
        max-width: 540px;
    }
    .about-hero-cta a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #1b3a6b;
        color: #fff !important;
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        border: 1.5px solid #1b3a6b;
        transition: all .15s ease;
    }
    .about-hero-cta a:hover {
        background: #16305a;
        border-color: #16305a;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }

    /* Square hero image — animated like other pages */
    .about-hero-visual {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 6 / 5;
        max-width: 540px;
        max-height: 460px;
        margin-left: auto;
        box-shadow: 0 25px 60px rgba(15,23,42,.15);
        animation: aboutFloat 6s ease-in-out infinite;
    }
    @media (max-width: 991px) {
        .about-hero-visual { max-width: 100%; max-height: 380px; margin: 0 auto; aspect-ratio: 4 / 3; }
    }
    .about-hero-visual img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .8s ease;
    }
    .about-hero-visual:hover img { transform: scale(1.05); }
    .about-hero-visual::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 50%, rgba(27, 58, 107,0.10) 100%);
    }
    .about-hero-float {
        position: absolute;
        background: #fff;
        border-radius: 14px;
        padding: 14px 18px;
        box-shadow: 0 14px 32px rgba(15,23,42,.12);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 2;
        animation: aboutFloatBadge 5s ease-in-out infinite;
    }
    .about-hero-float.tl { top: 24px; left: 24px; }
    .about-hero-float.br { bottom: 24px; right: 24px; animation-delay: .8s; animation-direction: reverse; }
    .about-hero-float .ico {
        width: 42px; height: 42px;
        border-radius: 10px;
        background: #1b3a6b;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .about-hero-float .ico.green { background: #047857; }
    .about-hero-float strong { font-size: 14px; color: #1b3a6b; font-weight: 800; display: block; line-height: 1.2; }
    .about-hero-float span { font-size: 12px; color: #777; }

    @keyframes aboutFloat {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-10px); }
    }
    @keyframes aboutFloatBadge {
        0%, 100% { transform: translateY(0) translateX(0); }
        50%      { transform: translateY(-8px) translateX(3px); }
    }

    /* Stats */
    .about-stats {
        padding: 60px 0;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .about-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }
    @media (max-width: 991px) {
        .about-stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .about-stat-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 30px 24px;
        text-align: center;
        transition: all .25s ease;
    }
    .about-stat-card:hover {
        border-color: #1b3a6b;
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(15,23,42,.08);
    }
    .about-stat-card .stat-num {
        font-size: 38px;
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1;
        margin-bottom: 6px;
        letter-spacing: -1px;
    }
    .about-stat-card .stat-label {
        font-size: 13px;
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Sections */
    .about-section { padding: 80px 0; background: #fff; }
    .about-section.gray { background: #fafafa; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; }
    .about-section-head { text-align: center; margin-bottom: 50px; }
    .about-section-head .tag {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }
    .about-section.gray .about-section-head .tag { background: #fff; }
    .about-section-head h2 {
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin-bottom: 12px;
    }
    .about-section-head p {
        font-size: 16px;
        color: #555;
        line-height: 1.65;
        max-width: 640px;
        margin: 0 auto;
    }

    /* How It Works */
    .how-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    @media (max-width: 991px) { .how-grid { grid-template-columns: 1fr; } }
    .how-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 36px 28px;
        text-align: left;
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
    }
    .how-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: #1b3a6b;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s ease;
    }
    .how-card:hover {
        border-color: #1b3a6b;
        transform: translateY(-4px);
        box-shadow: 0 18px 36px rgba(15,23,42,.10);
    }
    .how-card:hover::before { transform: scaleX(1); }
    .how-card .step-num {
        position: absolute;
        top: 24px;
        right: 24px;
        font-size: 36px;
        font-weight: 800;
        color: #f3f4f6;
        line-height: 1;
    }
    .how-card .ico {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #1b3a6b;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        margin-bottom: 22px;
    }
    .how-card h3 { font-size: 19px; font-weight: 700; margin-bottom: 10px; color: #1b3a6b; }
    .how-card p { font-size: 14.5px; line-height: 1.7; color: #555; margin: 0; }

    /* Benefits split */
    .benefits-row {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 40px;
        align-items: stretch;
    }
    @media (max-width: 991px) { .benefits-row { grid-template-columns: 1fr; gap: 36px; } }
    .benefits-visual {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(15,23,42,.12);
        animation: aboutFloat 7s ease-in-out infinite;
        height: 100%;
        min-height: 100%;
    }
    .benefits-visual img {
        position: absolute;
        inset: 0;
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }
    @media (max-width: 991px) {
        .benefits-visual {
            position: static;
            aspect-ratio: 4 / 3;
            max-height: 360px;
            min-height: auto;
            height: auto;
        }
        .benefits-visual img { position: static; }
    }
    @media (max-width: 575px) {
        .benefits-visual { max-height: 280px; }
    }
    .benefits-visual img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform .8s ease;
    }
    .benefits-visual:hover img { transform: scale(1.05); }
    .benefits-head { margin-bottom: 30px; }
    .benefits-head .tag {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }
    .benefits-head h2 {
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.5px;
        color: #1b3a6b;
        margin: 0;
    }
    .benefits-head h2 span {
        background: linear-gradient(90deg, #1b3a6b, #4a90d9);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    /* 3-column card grid (icon top → heading → text) */
    .benefits-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-rows: min-content;
        align-items: start;
        gap: 14px;
        align-content: start;
    }
    @media (max-width: 1199px) { .benefits-list { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px)  { .benefits-list { grid-template-columns: 1fr; } }

    .benefit-item {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 18px 16px;
        text-align: left;
        height: auto;
        align-self: start;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .benefit-item:hover {
        transform: translateY(-3px);
        border-color: #1b3a6b;
        box-shadow: 0 14px 28px rgba(15,23,42,.08);
    }
    .benefit-item .ico {
        width: 42px; height: 42px;
        border-radius: 11px;
        background: #1b3a6b;
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 18px;
        margin-bottom: 12px;
        box-shadow: 0 6px 14px rgba(27, 58, 107,.18);
    }
    .benefit-item h4 { font-size: 15px; font-weight: 800; margin: 0 0 6px; color: #1b3a6b; line-height: 1.3; }
    .benefit-item p { font-size: 13px; line-height: 1.55; color: #555; margin: 0; }

    /* Testimonials */
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    @media (max-width: 991px) { .testimonial-grid { grid-template-columns: 1fr; } }
    .testimonial-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 32px 28px;
        position: relative;
        transition: all .3s ease;
    }
    .testimonial-card:hover {
        border-color: #1b3a6b;
        transform: translateY(-3px);
        box-shadow: 0 18px 36px rgba(15,23,42,.10);
    }
    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: 14px;
        right: 24px;
        font-size: 70px;
        color: #f3f4f6;
        font-family: Georgia, serif;
        line-height: 1;
    }
    .testimonial-stars { color: #1b3a6b; margin-bottom: 14px; font-size: 14px; letter-spacing: 1px; }
    .testimonial-text {
        font-size: 14.5px;
        line-height: 1.75;
        color: #16305a;
        margin-bottom: 22px;
        position: relative;
        z-index: 1;
    }
    .testimonial-author {
        display: flex; align-items: center; gap: 14px;
        border-top: 1px solid #f0f0f0;
        padding-top: 18px;
    }
    .testimonial-author img {
        width: 44px; height: 44px;
        border-radius: 50%;
        object-fit: cover;
    }
    .testimonial-author .name { font-size: 14px; font-weight: 700; color: #1b3a6b; line-height: 1.3; }
    .testimonial-author .role { font-size: 12px; color: #777; }

    /* Story */
    .story-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    @media (max-width: 991px) { .story-row { grid-template-columns: 1fr; gap: 40px; } }
    .story-content h2 {
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin-bottom: 18px;
        color: #1b3a6b;
    }
    .story-content h2 span {
        background: linear-gradient(90deg, #1b3a6b, #4a90d9);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .story-content p { font-size: 15.5px; line-height: 1.8; margin-bottom: 16px; color: #555; }
    .story-visual {
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 6 / 5;
        max-width: 520px;
        max-height: 460px;
        margin: 0 auto;
        box-shadow: 0 25px 50px rgba(15,23,42,.12);
        animation: aboutFloat 8s ease-in-out infinite reverse;
    }
    @media (max-width: 991px) {
        .story-visual { max-width: 460px; max-height: 400px; }
    }
    @media (max-width: 575px) {
        .story-visual { max-width: 100%; max-height: 320px; aspect-ratio: 4 / 3; }
    }
    .story-visual img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform .8s ease;
    }
    .story-visual:hover img { transform: scale(1.05); }

    /* Mission / Vision / Values */
    .mvv-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    @media (max-width: 991px) { .mvv-grid { grid-template-columns: 1fr; } }
    .mvv-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 36px 28px;
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
    }
    .mvv-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: #1b3a6b;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s ease;
    }
    .mvv-card:hover {
        border-color: #1b3a6b;
        transform: translateY(-4px);
        box-shadow: 0 18px 36px rgba(15,23,42,.10);
    }
    .mvv-card:hover::before { transform: scaleX(1); }
    .mvv-card .ico {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #1b3a6b;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        margin-bottom: 22px;
    }
    .mvv-card h3 { font-size: 19px; font-weight: 700; margin-bottom: 10px; color: #1b3a6b; }
    .mvv-card p { font-size: 14.5px; line-height: 1.7; color: #555; margin: 0; }

    /* Industries grid */
    .industries-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 991px) { .industries-grid { grid-template-columns: repeat(2, 1fr); } }
    .industry-card {
        display: flex; align-items: center; gap: 12px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 18px 20px;
        text-decoration: none;
        color: inherit;
        transition: all .2s ease;
    }
    .industry-card:hover {
        border-color: #1b3a6b;
        background: #fff;
        color: inherit;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15,23,42,.06);
    }
    .industry-card .ico {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #1b3a6b;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        transition: background .2s ease;
    }
    .industry-card:hover .ico { background: #16305a; }
    .industry-card .name { font-size: 14px; font-weight: 600; color: #1b3a6b; line-height: 1.3; }

    /* States chips */
    .states-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        max-width: 900px;
        margin: 30px auto 0;
    }
    .states-chips a {
        background: #fff;
        border: 1px solid #ececec;
        color: #16305a;
        padding: 9px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s ease;
    }
    .states-chips a:hover {
        background: #1b3a6b;
        border-color: #1b3a6b;
        color: #fff;
        transform: translateY(-1px);
    }

    /* Visible FAQ */
    .about-faq-list { max-width: 880px; margin: 0 auto; }
    .about-faq-item {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all .2s ease;
    }
    .about-faq-item[open] {
        border-color: #1b3a6b;
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
    }
    .about-faq-item summary {
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
    .about-faq-item summary::-webkit-details-marker { display: none; }
    .about-faq-item summary::after {
        content: '+';
        font-size: 24px;
        color: #1b3a6b;
        font-weight: 300;
        line-height: 1;
        flex-shrink: 0;
    }
    .about-faq-item[open] summary::after { content: '−'; }
    .about-faq-item .faq-answer {
        padding: 0 24px 22px;
        color: #555;
        font-size: 14.5px;
        line-height: 1.75;
    }
    .about-faq-item .faq-answer a {
        color: #1b3a6b;
        font-weight: 600;
        border-bottom: 1.5px solid #1b3a6b;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .about-hero { padding: 50px 0 40px; }
        .about-hero-float { display: none; }
        .about-section { padding: 50px 0; }
        .about-stat-card .stat-num { font-size: 28px; }
    }

    /* =========================================
       DARK MODE — About Us
       ========================================= */
    html.dark-mode .about-page p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .about-page h2,
    html.dark-mode .about-page h3,
    html.dark-mode .about-page h4 { color: #ffffff !important; }

    /* Hero */
    html.dark-mode .about-hero {
        background: linear-gradient(180deg, #111418 0%, #161a20 50%, #1c2128 100%) !important;
        border-bottom-color: rgba(255,255,255,.08) !important;
    }
    html.dark-mode .about-hero-tag {
        background: rgba(27, 58, 107,.12) !important;
        color: #1b3a6b !important;
        border-color: rgba(27, 58, 107,.30) !important;
    }
    html.dark-mode .about-hero h1 { color: #fff !important; }
    html.dark-mode .about-hero-float {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
        color: #fff !important;
    }
    html.dark-mode .about-hero-float strong { color: #fff !important; }
    html.dark-mode .about-hero-float span { color: var(--site-muted, #b8c0cc) !important; }

    /* Stats */
    html.dark-mode .about-stats { background: transparent !important; }
    html.dark-mode .about-stat-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .about-stat-card:hover {
        border-color: #1b3a6b !important;
        background: var(--site-card-bg, #1c2128) !important;
    }
    html.dark-mode .about-stat-card .stat-num { color: #1b3a6b !important; }
    html.dark-mode .about-stat-card .stat-label { color: var(--site-muted, #b8c0cc) !important; }

    /* Sections */
    html.dark-mode .about-section { background: var(--site-bg, #0f1216) !important; }
    html.dark-mode .about-section.gray {
        background: #161a20 !important;
        border-top-color: rgba(255,255,255,.06) !important;
        border-bottom-color: rgba(255,255,255,.06) !important;
    }
    html.dark-mode .about-section-head .tag {
        background: rgba(27, 58, 107,.12) !important;
        color: #1b3a6b !important;
        border-color: rgba(27, 58, 107,.30) !important;
    }
    html.dark-mode .about-section.gray .about-section-head .tag {
        background: rgba(27, 58, 107,.12) !important;
    }
    html.dark-mode .about-section-head p { color: var(--site-muted, #b8c0cc) !important; }

    /* "Three simple steps" cards */
    html.dark-mode .how-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .how-card:hover { border-color: #1b3a6b !important; }
    html.dark-mode .how-card h3 { color: #fff !important; }
    html.dark-mode .how-card p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .how-card .step-num {
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
        color: #fff !important;
    }
    html.dark-mode .how-card .icon-wrap {
        background: rgba(27, 58, 107,.12) !important;
        color: #1b3a6b !important;
    }

    /* "Built for job seekers who care about quality" — benefit items */
    html.dark-mode .benefit-item {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .benefit-item:hover { border-color: #1b3a6b !important; }
    html.dark-mode .benefit-item h4 { color: #fff !important; }
    html.dark-mode .benefit-item p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .benefit-item .icon-wrap,
    html.dark-mode .benefit-item .b-icon {
        background: rgba(27, 58, 107,.12) !important;
        color: #1b3a6b !important;
    }

    /* Testimonials */
    html.dark-mode .testimonial-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .testimonial-card:hover { border-color: #1b3a6b !important; }
    html.dark-mode .testimonial-card p,
    html.dark-mode .testimonial-card .testimonial-text { color: var(--site-muted, #d0d6df) !important; }
    html.dark-mode .testimonial-stars { color: #ffb800 !important; }
    html.dark-mode .testimonial-author .name { color: #fff !important; }
    html.dark-mode .testimonial-author .role { color: var(--site-muted, #b8c0cc) !important; }

    /* Story section ("Why JobGader exists") */
    html.dark-mode .story-row p,
    html.dark-mode .story-row .story-text p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .story-row h2 { color: #fff !important; }

    /* Mission / Vision / Values cards */
    html.dark-mode .mvv-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .mvv-card:hover { border-color: #1b3a6b !important; }
    html.dark-mode .mvv-card h3 { color: #fff !important; }
    html.dark-mode .mvv-card p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .mvv-card .icon-wrap,
    html.dark-mode .mvv-card .mvv-ico {
        background: rgba(27, 58, 107,.12) !important;
        color: #1b3a6b !important;
    }

    /* Industry pills/cards */
    html.dark-mode .industry-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .industry-card:hover {
        border-color: #1b3a6b !important;
        background: var(--site-card-bg, #1c2128) !important;
    }
    html.dark-mode .industry-card .name { color: #fff !important; }
    html.dark-mode .industry-card .count,
    html.dark-mode .industry-card .meta { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .industry-card .ind-ico,
    html.dark-mode .industry-card .icon-wrap {
        background: rgba(27, 58, 107,.12) !important;
        color: #1b3a6b !important;
    }

    /* "Trusted name" trust cards */
    html.dark-mode .trust-card,
    html.dark-mode .about-trust-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .trust-card:hover,
    html.dark-mode .about-trust-card:hover { border-color: #1b3a6b !important; }
    html.dark-mode .trust-card h4,
    html.dark-mode .trust-card h3,
    html.dark-mode .about-trust-card h4,
    html.dark-mode .about-trust-card h3 { color: #fff !important; }
    html.dark-mode .trust-card p,
    html.dark-mode .about-trust-card p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .trust-card .icon-wrap,
    html.dark-mode .about-trust-card .icon-wrap {
        background: rgba(27, 58, 107,.12) !important;
        color: #1b3a6b !important;
    }

    /* FAQ */
    html.dark-mode .about-faq-item {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .about-faq-item:hover { border-color: #1b3a6b !important; }
    html.dark-mode .about-faq-item summary,
    html.dark-mode .about-faq-item .faq-q { color: #fff !important; }
    html.dark-mode .about-faq-item p,
    html.dark-mode .about-faq-item .faq-a { color: var(--site-muted, #b8c0cc) !important; }

    /* Generic helper: any white card on this page */
    html.dark-mode .about-page [style*="background:#fff"],
    html.dark-mode .about-page [style*="background: #fff"] {
        background: var(--site-card-bg, #1c2128) !important;
    }

    /* ===== Dark mode: gradient-text spans (were black-on-black → invisible) ===== */
    html.dark-mode .benefits-head h2 span,
    html.dark-mode .story-content h2 span,
    html.dark-mode .about-hero h1 span {
        background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #ffab40) !important;
        -webkit-background-clip: text !important;
        background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        color: transparent !important;
    }

    /* ===== Dark mode: "Find Your Next Role" hero button → orange ===== */
    html.dark-mode .about-hero-cta a {
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
        border-color: #1b3a6b !important;
        color: #fff !important;
        box-shadow: 0 8px 18px rgba(27, 58, 107,.30) !important;
    }
    html.dark-mode .about-hero-cta a:hover {
        background: linear-gradient(135deg, #16305a, #ff4722) !important;
        box-shadow: 0 14px 28px rgba(27, 58, 107,.45) !important;
    }

    /* ===== Dark mode: FAQ +/− icons (were black-on-black → invisible) ===== */
    html.dark-mode .about-faq-item summary::after { color: #1b3a6b !important; }
    html.dark-mode .about-faq-item[open] summary::after { color: #1b3a6b !important; }
</style>

<div class="about-page">

    {{-- Hero --}}
    <section class="about-hero">
        <div class="container">
            <div class="about-hero-row">
                <div>
                    <span class="about-hero-tag" data-aos="fade-down" data-aos-duration="600">About JobGader</span>
                    <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Real jobs, and <span>straight answers</span> about the visa routes behind them</h1>
                    <p class="lead" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">JobGader lists hand-checked openings across {{ $coverage->shortList() }} &mdash; and publishes guides that say which sponsorship routes are genuinely open, which have closed, and what each role actually pays. Free to apply, and you never need an account.</p>
                    <div class="about-hero-cta" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
                        <a href="{{ route('jobs.index') }}">Browse Open Jobs <i class="icon-feather-arrow-right"></i></a>
                    </div>
                </div>
                <div class="about-hero-visual">
                    <img src="{{ asset('public/user/images/single-company.webp') }}"
                         alt="About JobGader — job listings and visa sponsorship guides for {{ $coverage->shortList() }}"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('public/user/images/single-company.jpg') }}'">
                    <div class="about-hero-float tl">
                        <div class="ico"><i class="icon-feather-globe"></i></div>
                        <div>
                            <strong>{{ $coverage->count() }} Countries</strong>
                            <span>{{ $coverage->shortList() }}</span>
                        </div>
                    </div>
                    <div class="about-hero-float br">
                        <div class="ico green"><i class="icon-feather-check-circle"></i></div>
                        <div>
                            <strong>Hand-checked</strong>
                            <span>Every listing, before it goes live</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="about-stats">
        <div class="container">
            <div class="about-stats-grid">
                <div class="about-stat-card">
                    <div class="stat-num">{{ $coverage->count() }}</div>
                    <div class="stat-label">Countries Covered</div>
                </div>
                <div class="about-stat-card">
                    <div class="stat-num">Weekly</div>
                    <div class="stat-label">New Jobs &amp; Guides</div>
                </div>
                <div class="about-stat-card">
                    <div class="stat-num">No</div>
                    <div class="stat-label">Sign-Up Required</div>
                </div>
                <div class="about-stat-card">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Free for Job Seekers</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission / Vision / Values --}}
    <section class="about-section">
        <div class="container">
            <div class="about-section-head">
                <span class="tag">What Drives Us</span>
                <h2>Our Mission, Vision &amp; Values</h2>
                <p>Why we publish what we publish, and the line we will not cross to get traffic.</p>
            </div>
            <div class="mvv-grid">
                <div class="mvv-card">
                    <div class="ico"><i class="icon-feather-target"></i></div>
                    <h3>Our Mission</h3>
                    <p>To make working abroad less of a guessing game &mdash; by pairing real openings with an honest account of the visa route behind each one, so nobody wastes months chasing a job they were never eligible for.</p>
                </div>
                <div class="mvv-card">
                    <div class="ico"><i class="icon-feather-eye"></i></div>
                    <h3>Our Vision</h3>
                    <p>A job search where the hard information is free and public: which routes are open, what the pay really is, and which recruiters are charging for something that should never cost a worker a penny.</p>
                </div>
                <div class="mvv-card">
                    <div class="ico"><i class="icon-feather-heart"></i></div>
                    <h3>Our Values</h3>
                    <p><strong>Say it plainly</strong>, even when the answer is no. <strong>Cite the source</strong>, not a rumour. <strong>Never charge a job seeker.</strong> <strong>Publish nothing we cannot verify</strong> &mdash; including our own numbers.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="about-section gray">
        <div class="container">
            <div class="about-section-head">
                <span class="tag">How It Works</span>
                <h2>Find your next role in three simple steps</h2>
                <p>No sign-up wall and no fees. Pick a country, check what the visa route allows, then apply straight through.</p>
            </div>
            <div class="how-grid">
                <div class="how-card">
                    <div class="step-num">01</div>
                    <div class="ico"><i class="icon-line-awesome-user-plus"></i></div>
                    <h3>Pick Your Country</h3>
                    <p>Openings across {{ $coverage->shortList() }} &mdash; general labour and hospitality through to skilled trades and senior engineering.</p>
                </div>
                <div class="how-card">
                    <div class="step-num">02</div>
                    <div class="ico"><i class="icon-line-awesome-search"></i></div>
                    <h3>Check the Visa Route</h3>
                    <p>Read the guide for that sector first. It sets out which routes are open, the real pay range, and the scam patterns to avoid.</p>
                </div>
                <div class="how-card">
                    <div class="step-num">03</div>
                    <div class="ico"><i class="icon-line-awesome-paper-plane"></i></div>
                    <h3>Apply Direct, Free</h3>
                    <p>Applications go straight to the employer or the original posting. No account, no fee, no middleman taking a cut.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits Split --}}
    <section class="about-section">
        <div class="container">
            <div class="benefits-head" style="text-align: center; margin-bottom: 44px;">
                <span class="tag">Why JobGader</span>
                <h2>What you actually get from <span>JobGader</span></h2>
            </div>
            <div class="benefits-row">
                <div class="benefits-visual">
                    <img src="{{ asset('public/user/images/partir-usa.webp') }}" alt="Job seeker reading a JobGader visa sponsorship guide" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('public/user/images/partir-usa.jpg') }}'">
                </div>
                <div class="benefits-list">
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-shield"></i></div>
                        <h4>Hand-Checked Listings</h4>
                        <p>Added and reviewed by us, not scraped. Each one links to the employer or original posting so you can verify it.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-alert-circle"></i></div>
                        <h4>Closed Routes Named</h4>
                        <p>When a visa route shuts, we say so up front instead of leaving an old guide up to collect clicks.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-dollar-sign"></i></div>
                        <h4>Researched Pay Ranges</h4>
                        <p>Hourly rates and annual bands with the source explained &mdash; not a scraped estimate presented as fact.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-user-x"></i></div>
                        <h4>No Account Needed</h4>
                        <p>Open any listing and apply. No sign-up wall, no resume paywall, nothing to unsubscribe from later.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-slash"></i></div>
                        <h4>Never a Fee</h4>
                        <p>Charging a worker for sponsorship is illegal in the US and UK. We will never ask you for money, at any stage.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-globe"></i></div>
                        <h4>{{ $coverage->countWord() }} Countries</h4>
                        <p>{{ $coverage->shortList() }} &mdash; trucking, hospitality, care, construction, cleaning, marketing and software.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-file-text"></i></div>
                        <h4>Official Sources</h4>
                        <p>Guides written from gov.uk, USCIS and Department of Labor material, with the rule and the date it changed.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-eye"></i></div>
                        <h4>Scam Patterns Flagged</h4>
                        <p>Upfront "processing fees", fake sponsorship tags and guaranteed-visa promises, explained sector by sector.</p>
                    </div>
                    <div class="benefit-item">
                        <div class="ico"><i class="icon-feather-refresh-cw"></i></div>
                        <h4>Updated Weekly</h4>
                        <p>New listings and guides every week, with the newest openings first on the home page and jobs board.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="about-section gray">
        <div class="container">
            <div class="about-section-head">
                <span class="tag">How We Work</span>
                <h2>The rules we hold ourselves to</h2>
                <p>We are a young site, so instead of testimonials, here is exactly how the listings and guides on JobGader are put together.</p>
            </div>
            <div class="mvv-grid">
                <div class="mvv-card">
                    <div class="ico"><i class="icon-feather-file-text"></i></div>
                    <h3>Sourced, Not Guessed</h3>
                    <p>Visa rules come from gov.uk, USCIS and the US Department of Labor, with the rule and the date it changed stated in the text. Pay ranges cite where the figure comes from rather than presenting an estimate as fact.</p>
                </div>
                <div class="mvv-card">
                    <div class="ico"><i class="icon-feather-alert-circle"></i></div>
                    <h3>Bad News Goes First</h3>
                    <p>When a route is closed, the guide opens with that. UK care worker sponsorship shut to new overseas applicants in July 2025, and standard warehouse and cleaning roles have never met the Skilled Worker thresholds &mdash; so those guides say so in the first paragraph.</p>
                </div>
                <div class="mvv-card">
                    <div class="ico"><i class="icon-feather-shield"></i></div>
                    <h3>Nothing Behind a Wall</h3>
                    <p>No sign-up to apply, no fee at any stage, and no data sold on. Charging a worker for visa sponsorship is illegal in both the US and the UK, and we flag the recruiters who try it.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Story --}}
    <section class="about-section">
        <div class="container">
            <div class="story-row">
                <div class="story-content">
                    <span class="about-hero-tag">Our Story</span>
                    <h2>Why JobGader <span>exists</span></h2>
                    <p>JobGader started from a specific frustration: search "warehouse jobs UK visa sponsorship" or "caregiver jobs UK" and you get page after page of sites promising sponsorship that the rules no longer allow. Some are years out of date. Some are agencies charging a fee for a visa that does not exist.</p>
                    <p>So we built the opposite. Every guide leads with the current rule and where it comes from &mdash; gov.uk, USCIS, the Department of Labor &mdash; even when the honest answer is that a route closed and is not coming back. The UK care worker visa shut to new overseas applicants in July 2025; our guide says that in the first paragraph rather than burying it.</p>
                    <p>We are a small team and this is a young site. We would rather publish eight guides we can stand behind than eight thousand listings we have never looked at, and we would rather tell you a route is closed than take the click.</p>
                </div>
                <div class="story-visual">
                    <img src="{{ asset('public/user/images/about-founders.jpg') }}" alt="The JobGader team reviewing job listings and visa guidance" loading="lazy" decoding="async">
                </div>
            </div>
        </div>
    </section>

    {{-- Industries We Serve --}}
    <section class="about-section gray">
        <div class="container">
            <div class="about-section-head">
                <span class="tag">Industries We Serve</span>
                <h2>The sectors we cover</h2>
                <p>From entry-level labour to senior engineering &mdash; the industries where we list openings and publish sponsorship guidance.</p>
            </div>
            <div class="industries-grid">
                <a href="{{ route('pages.healthcare-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-heartbeat"></i></div>
                    <span class="name">Healthcare &amp; Medical Jobs</span>
                </a>
                <a href="{{ route('pages.it-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-laptop"></i></div>
                    <span class="name">IT &amp; Technology Jobs</span>
                </a>
                <a href="{{ route('pages.software-developer-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-code"></i></div>
                    <span class="name">Software Developer Jobs</span>
                </a>
                <a href="{{ route('pages.construction-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-wrench"></i></div>
                    <span class="name">Construction Jobs</span>
                </a>
                <a href="{{ route('pages.warehouse-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-archive"></i></div>
                    <span class="name">Warehouse Jobs</span>
                </a>
                <a href="{{ route('pages.truck-driver-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-truck"></i></div>
                    <span class="name">Truck Driver Jobs</span>
                </a>
                <a href="{{ route('pages.retail-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-feather-shopping-bag"></i></div>
                    <span class="name">Retail Jobs</span>
                </a>
                <a href="{{ route('pages.customer-service-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-headphones"></i></div>
                    <span class="name">Customer Service Jobs</span>
                </a>
                <a href="{{ route('pages.marketing-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-bullhorn"></i></div>
                    <span class="name">Marketing Jobs</span>
                </a>
                <a href="{{ route('pages.accounting-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-calculator"></i></div>
                    <span class="name">Accounting Jobs</span>
                </a>
                <a href="{{ route('pages.data-entry-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-keyboard-o"></i></div>
                    <span class="name">Data Entry Jobs</span>
                </a>
                <a href="{{ route('pages.security-guard-jobs') }}" class="industry-card">
                    <div class="ico"><i class="icon-line-awesome-shield"></i></div>
                    <span class="name">Security Guard Jobs</span>
                </a>
            </div>

            <div style="text-align:center; margin-top:50px;">
                <h3 style="font-size:22px; font-weight:700; color:#16305a; margin-bottom:8px;">Hiring across {{ $coverage->countWordLower() }} countries</h3>
                <p style="font-size:14px; color:#5a5a5a; margin:0;">Browse top-paying jobs by state — from coast to coast.</p>
                <div class="states-chips">
                    <a href="{{ route('pages.jobs-in-texas') }}">Texas</a>
                    <a href="{{ route('pages.jobs-in-california') }}">California</a>
                    <a href="{{ route('pages.jobs-in-new-york') }}">New York</a>
                    <a href="{{ route('pages.jobs-in-florida') }}">Florida</a>
                    <a href="{{ route('pages.jobs-in-illinois') }}">Illinois</a>
                    <a href="{{ route('pages.jobs-in-pennsylvania') }}">Pennsylvania</a>
                    <a href="{{ route('pages.jobs-in-ohio') }}">Ohio</a>
                    <a href="{{ route('pages.jobs-in-georgia') }}">Georgia</a>
                    <a href="{{ route('pages.jobs-in-north-carolina') }}">North Carolina</a>
                    <a href="{{ route('pages.jobs-in-michigan') }}">Michigan</a>
                    <a href="{{ route('pages.jobs-in-new-jersey') }}">New Jersey</a>
                    <a href="{{ route('pages.jobs-in-virginia') }}">Virginia</a>
                    <a href="{{ route('pages.jobs-in-washington') }}">Washington</a>
                    <a href="{{ route('pages.jobs-in-arizona') }}">Arizona</a>
                    <a href="{{ route('pages.jobs-in-massachusetts') }}">Massachusetts</a>
                    <a href="{{ route('pages.remote-jobs-usa') }}">Remote Jobs</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Recognition / In the Press / Trust section --}}
    <section class="about-section gray about-press" aria-labelledby="press-heading">
        <div class="container">
            <div class="about-section-head">
                <span class="tag">How We Operate</span>
                <h2 id="press-heading">What we can actually stand behind</h2>
                <p>No awards, no press quotes, no invented user counts &mdash; just the commitments that govern what goes on this site.</p>
            </div>

            <div class="press-grid">
                <article class="press-card">
                    <div class="press-ico"><i class="icon-feather-check-circle"></i></div>
                    <h3>Reviewed Before Publishing</h3>
                    <p>Every listing is added by hand and links through to the employer or the original posting, so you can confirm it exists before spending time on an application.</p>
                    <span class="press-badge">Hand-Checked</span>
                </article>
                <article class="press-card">
                    <div class="press-ico"><i class="icon-feather-book-open"></i></div>
                    <h3>Guides Cite Their Sources</h3>
                    <p>Each visa guide names the rule it relies on and where it came from — gov.uk, USCIS or the Department of Labor — along with the date that rule took effect.</p>
                    <span class="press-badge">Sourced</span>
                </article>
                <article class="press-card">
                    <div class="press-ico"><i class="icon-feather-lock"></i></div>
                    <h3>Privacy &amp; Data Protection</h3>
                    <p>The site runs over HTTPS end to end. You can browse and apply without an account, so in most cases we hold no data about you at all.</p>
                    <span class="press-badge">HTTPS Secured</span>
                </article>
                <article class="press-card">
                    <div class="press-ico"><i class="icon-feather-dollar-sign"></i></div>
                    <h3>Free for Job Seekers</h3>
                    <p>No subscription, no resume paywall, no placement fee. We have never charged a job seeker and have no plans to.</p>
                    <span class="press-badge">Always Free</span>
                </article>
                <article class="press-card">
                    <div class="press-ico"><i class="icon-feather-globe"></i></div>
                    <h3>{{ $coverage->countWord() }} Countries Covered</h3>
                    <p>The {{ $coverage->shortList() }} — spanning transport, hospitality, care, construction, cleaning, marketing and software roles.</p>
                    <span class="press-badge">USA · UK · PK</span>
                </article>
                <article class="press-card">
                    <div class="press-ico"><i class="icon-feather-zap"></i></div>
                    <h3>Built for Speed</h3>
                    <p>Light pages that load quickly on a phone and a slow connection, because a lot of people searching for this work are not reading on fast broadband.</p>
                    <span class="press-badge">Fast on Mobile</span>
                </article>
            </div>

            {{-- Standards strip --}}
            <div class="press-strip" role="list" aria-label="Standards">
                <div class="press-strip-item" role="listitem">
                    <i class="icon-feather-shield"></i>
                    <div>
                        <strong>HTTPS Secured</strong>
                        <span>Encrypted end to end</span>
                    </div>
                </div>
                <div class="press-strip-item" role="listitem">
                    <i class="icon-feather-check-circle"></i>
                    <div>
                        <strong>Hand-Checked</strong>
                        <span>Every listing reviewed</span>
                    </div>
                </div>
                <div class="press-strip-item" role="listitem">
                    <i class="icon-feather-globe"></i>
                    <div>
                        <strong>{{ $coverage->count() }} Countries</strong>
                        <span>{{ $coverage->shortList() }}</span>
                    </div>
                </div>
                <div class="press-strip-item" role="listitem">
                    <i class="icon-feather-refresh-cw"></i>
                    <div>
                        <strong>Updated Weekly</strong>
                        <span>New jobs and guides</span>
                    </div>
                </div>
                <div class="press-strip-item" role="listitem">
                    <i class="icon-feather-dollar-sign"></i>
                    <div>
                        <strong>No Fees</strong>
                        <span>Free for job seekers</span>
                    </div>
                </div>
            </div>

            <div class="press-cta">
                <a href="{{ route('jobs.index') }}" class="press-btn">Browse open jobs <i class="icon-material-outline-arrow-right-alt"></i></a>
                <span class="press-cta-note">Free &middot; No account needed &middot; Apply direct</span>
            </div>
        </div>
    </section>

    <style>
        .about-press .press-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            margin-bottom: 40px;
        }
        @media (max-width: 991px) { .about-press .press-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 575px) { .about-press .press-grid { grid-template-columns: 1fr; } }
        .press-card {
            position: relative;
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 16px;
            padding: 26px 24px;
            transition: all .25s ease;
            overflow: hidden;
        }
        .press-card::before {
            content: ""; position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px; background: #1b3a6b;
            transform: scaleX(0); transform-origin: left;
            transition: transform .25s ease;
        }
        .press-card:hover {
            transform: translateY(-4px);
            border-color: #1b3a6b;
            box-shadow: 0 20px 40px rgba(15,23,42,.10);
        }
        .press-card:hover::before { transform: scaleX(1); }
        .press-card .press-ico {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: #1b3a6b;
            color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
            box-shadow: 0 6px 14px rgba(27, 58, 107,.18);
        }
        .press-card h3 {
            font-size: 17px;
            font-weight: 700;
            color: #1b3a6b;
            margin: 0 0 10px;
            letter-spacing: -.2px;
        }
        .press-card p {
            font-size: 14px;
            line-height: 1.65;
            color: #555;
            margin: 0 0 14px;
        }
        .press-card .press-badge {
            display: inline-block;
            background: #f3f4f6;
            color: #1b3a6b;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 11px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .press-strip {
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 16px;
            padding: 22px 26px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 22px;
            margin-bottom: 32px;
        }
        @media (max-width: 991px) { .press-strip { grid-template-columns: repeat(2, 1fr); gap: 18px; } }
        @media (max-width: 480px) { .press-strip { grid-template-columns: 1fr; } }
        .press-strip-item {
            display: flex; align-items: center; gap: 12px;
        }
        .press-strip-item i {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: #f3f4f6;
            color: #1b3a6b;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .press-strip-item strong {
            display: block;
            font-size: 14px;
            color: #1b3a6b;
            font-weight: 700;
            line-height: 1.2;
        }
        .press-strip-item span {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .press-cta { text-align: center; }
        .press-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: #1b3a6b; color: #fff !important;
            padding: 14px 28px; border-radius: 12px;
            font-weight: 700; font-size: 15px;
            text-decoration: none !important;
            box-shadow: 0 8px 18px rgba(27, 58, 107,.20);
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .press-btn:hover { transform: translateY(-1px); background: #16305a; box-shadow: 0 14px 28px rgba(27, 58, 107,.30); color: #fff !important; }
        .press-btn i { font-size: 22px; transition: transform .2s ease; }
        .press-btn:hover i { transform: translateX(4px); }
        .press-cta-note {
            display: block;
            margin-top: 12px;
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        /* === Dark mode — press / trust strip / states chips / inline heading === */
        html.dark-mode .press-card {
            background: var(--site-card-bg, #1c2128) !important;
            border-color: rgba(255,255,255,.10) !important;
        }
        html.dark-mode .press-card:hover { border-color: #1b3a6b !important; }
        html.dark-mode .press-card::before { background: linear-gradient(90deg, #1b3a6b, #2f7fc9) !important; }
        html.dark-mode .press-card h3 { color: #fff !important; }
        html.dark-mode .press-card p { color: var(--site-muted, #b8c0cc) !important; }
        html.dark-mode .press-card .press-ico {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            color: #fff !important;
        }
        html.dark-mode .press-card .press-badge {
            background: rgba(27, 58, 107,.14) !important;
            color: #1b3a6b !important;
        }

        html.dark-mode .press-strip {
            background: var(--site-card-bg, #1c2128) !important;
            border-color: rgba(255,255,255,.10) !important;
        }
        html.dark-mode .press-strip-item i {
            background: rgba(27, 58, 107,.14) !important;
            color: #1b3a6b !important;
        }
        html.dark-mode .press-strip-item strong { color: #fff !important; }
        html.dark-mode .press-strip-item span { color: var(--site-muted, #b8c0cc) !important; }

        html.dark-mode .press-btn {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            box-shadow: 0 8px 18px rgba(27, 58, 107,.30) !important;
        }
        html.dark-mode .press-btn:hover { background: linear-gradient(135deg, #16305a, #ff4722) !important; }
        html.dark-mode .press-cta-note { color: var(--site-muted, #b8c0cc) !important; }

        html.dark-mode .states-chips a {
            background: var(--site-card-bg, #1c2128) !important;
            border-color: rgba(255,255,255,.10) !important;
            color: var(--site-muted, #d0d6df) !important;
        }
        html.dark-mode .states-chips a:hover {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            color: #fff !important;
            border-color: #1b3a6b !important;
        }

        /* Hiring across 50 states heading (inline style override) */
        html.dark-mode .about-press + section [style*="color:#16305a"],
        html.dark-mode .about-industry [style*="color:#16305a"] { color: #fff !important; }
        html.dark-mode .about-industry [style*="color:#5a5a5a"],
        html.dark-mode .about-press + section [style*="color:#5a5a5a"] { color: var(--site-muted, #b8c0cc) !important; }

        /* =================================================================
           DARK MODE — comprehensive catch-all (loaded LAST, highest priority)
           ================================================================= */

        /* Gradient text spans — were dark gradient (invisible on dark bg) → orange */
        html.dark-mode .about-page .benefits-head h2 span,
        html.dark-mode .about-page .story-content h2 span,
        html.dark-mode .about-page .about-hero h1 span,
        html.dark-mode .about-page h1 .accent,
        html.dark-mode .about-page h2 .accent {
            background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #ffab40) !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            color: transparent !important;
        }

        /* "Find Your Next Role" hero CTA → orange gradient */
        html.dark-mode .about-page .about-hero-cta a {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            border-color: #1b3a6b !important;
            color: #fff !important;
            box-shadow: 0 8px 18px rgba(27, 58, 107,.30) !important;
        }
        html.dark-mode .about-page .about-hero-cta a:hover {
            background: linear-gradient(135deg, #16305a, #ff4722) !important;
            box-shadow: 0 14px 28px rgba(27, 58, 107,.45) !important;
        }

        /* FAQ +/− toggles → orange (were #1b3a6b black) */
        html.dark-mode .about-page .about-faq-item summary::after,
        html.dark-mode .about-page .about-faq-item[open] summary::after {
            color: #1b3a6b !important;
        }
        html.dark-mode .about-page .about-faq-item summary { color: #fff !important; }

        /* Catch-all for benefits-head / story-content headings themselves */
        html.dark-mode .about-page .benefits-head h2,
        html.dark-mode .about-page .story-content h2 { color: #fff !important; }
        html.dark-mode .about-page .benefits-head .tag {
            background: rgba(27, 58, 107,.12) !important;
            border-color: rgba(27, 58, 107,.30) !important;
            color: #1b3a6b !important;
        }

        /* Story paragraphs */
        html.dark-mode .about-page .story-content p { color: var(--site-muted, #b8c0cc) !important; }
        html.dark-mode .about-page .benefits-list .benefit-item h4 { color: #fff !important; }
        html.dark-mode .about-page .benefits-list .benefit-item p { color: var(--site-muted, #b8c0cc) !important; }

        /* Testimonial stars stay amber */
        html.dark-mode .about-page .testimonial-stars { color: #ffb800 !important; }

        /* Industry card icons (.ico) → orange gradient (matches site theme) */
        html.dark-mode .about-page .industry-card .ico {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(27, 58, 107,.30) !important;
        }
        html.dark-mode .about-page .industry-card:hover .ico {
            background: linear-gradient(135deg, #16305a, #ff4722) !important;
        }

        /* "Three simple steps" how-card icons (.ico) → orange gradient */
        html.dark-mode .about-page .how-card .ico {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            color: #fff !important;
            box-shadow: 0 6px 14px rgba(27, 58, 107,.30) !important;
        }

        /* Mission / Vision / Values icons (.ico) → orange gradient */
        html.dark-mode .about-page .mvv-card .ico {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            color: #fff !important;
            box-shadow: 0 6px 14px rgba(27, 58, 107,.30) !important;
        }

        /* Benefit list ("Built for job seekers...") icons → orange gradient */
        html.dark-mode .about-page .benefit-item .ico {
            background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(27, 58, 107,.30) !important;
        }
    </style>

    {{-- Visible FAQ --}}
    <section class="about-section">
        <div class="container">
            <div class="about-section-head">
                <span class="tag">Frequently Asked Questions</span>
                <h2>Common questions about JobGader</h2>
                <p>Everything you need to know about how our platform works, who it's for, and what makes it different.</p>
            </div>
            <div class="about-faq-list">
                <details class="about-faq-item" open>
                    <summary>What is JobGader and how does it work?</summary>
                    <div class="faq-answer">JobGader is a job board and guide site covering openings in {{ $coverage->shortList() }}. You can browse and apply to every <a href="{{ route('jobs.index') }}">listing</a> without an account, and alongside them we publish guides explaining which visa sponsorship routes are open to foreign workers and which have closed.</div>
                </details>
                <details class="about-faq-item">
                    <summary>Is JobGader free for job seekers?</summary>
                    <div class="faq-answer">Yes — 100% free. Creating an account, building your profile, browsing listings, applying for jobs, and setting up job alerts are all completely free for job seekers. We make money from employers who pay to post jobs and access advanced hiring features.</div>
                </details>
                <details class="about-faq-item">
                    <summary>How are job listings verified?</summary>
                    <div class="faq-answer">Listings are added and checked by hand rather than scraped, and each one links to the employer or the original posting so you can confirm it yourself before applying. If you see something suspicious, report it through our <a href="{{ route('contact.us') }}">Contact page</a> and we will look at it.</div>
                </details>
                <details class="about-faq-item">
                    <summary>What industries and job types are available?</summary>
                    <div class="faq-answer">We feature verified jobs across every major U.S. industry — including healthcare, IT, software development, construction, warehouse, transportation, retail, customer service, marketing, accounting, hospitality, education, finance, and more. Roles range from entry-level and part-time to executive and remote positions.</div>
                </details>
                <details class="about-faq-item">
                    <summary>Can I find remote and work-from-home jobs?</summary>
                    <div class="faq-answer">Absolutely. JobGader features a dedicated <a href="{{ route('pages.remote-jobs-usa') }}">remote jobs section</a> with thousands of fully remote, hybrid, and work-from-home opportunities across the country. Use the location filter to view only remote roles.</div>
                </details>
                <details class="about-faq-item">
                    <summary>How do I get notified when matching jobs are posted?</summary>
                    <div class="faq-answer">After creating your free account, set up custom job alerts based on keywords, location, salary, industry, and experience level. We'll email you the moment a matching role goes live — no daily searching required.</div>
                </details>
                <details class="about-faq-item">
                    <summary>Are my personal details kept private?</summary>
                    <div class="faq-answer">Yes. Privacy is built in by default. Your profile is only visible to verified employers when you choose to apply. Your current employer cannot see your profile, and you can hide or delete your information anytime from your dashboard.</div>
                </details>
                <details class="about-faq-item">
                    <summary>How can employers post jobs on JobGader?</summary>
                    <div class="faq-answer">Employers can register an account, choose a posting plan that matches their hiring needs, and submit listings via the dashboard. After our team reviews and verifies the company, the job goes live and reaches qualified candidates nationwide. Visit our <a href="{{ route('contact.us') }}">Contact page</a> for custom enterprise plans.</div>
                </details>
            </div>
        </div>
    </section>


</div>

@endsection