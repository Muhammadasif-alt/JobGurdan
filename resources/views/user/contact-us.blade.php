@extends('user.layouts.master')
@section('title', 'Contact Jobs in USA — Get Help, Partner With Us, Report a Listing')
@section('meta_description', 'Get in touch with Jobs in USA — call, email, or send a message. Our support team responds within 24 hours. Trusted by millions of job seekers across all 50 U.S. states.')
@section('meta_keywords', 'contact jobs in usa, jobs in usa support, jobs in usa email, customer service usa job board, partnership jobs in usa, report job listing')
@section('og_title', 'Contact Jobs in USA — We\'re Here to Help')
@section('og_description', 'Reach our support team within 24 hours. Phone, email, or message — we\'re ready to help job seekers, employers, and partners.')
@section('canonical', route('contact.us'))

@push('meta')
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    {{-- JSON-LD: ContactPage --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "ContactPage",
        "name": "Contact Jobs in USA",
        "url": "{{ route('contact.us') }}",
        "description": "Get in touch with the Jobs in USA support team for help with your account, job listings, partnerships, or report concerns."
    }
    </script>

    {{-- JSON-LD: Organization with contact point --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Jobs in USA",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('public/user/images/Jobs in USA.png') }}",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Building 800, N State College Blvd",
            "addressLocality": "Fullerton",
            "addressRegion": "CA",
            "addressCountry": "US"
        },
        "contactPoint": [{
            "@@type": "ContactPoint",
            "telephone": "+1-321-775-9823",
            "contactType": "customer support",
            "email": "info@jobsinusa.us",
            "availableLanguage": ["English"],
            "areaServed": "US"
        }]
    }
    </script>

    {{-- JSON-LD: BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Contact Us", "item": "{{ route('contact.us') }}" }
        ]
    }
    </script>

    {{-- JSON-LD: FAQ --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {"@@type":"Question","name":"How quickly will I receive a response from Jobs in USA?","acceptedAnswer":{"@@type":"Answer","text":"Our support team typically responds within 24 hours on business days. For urgent matters, please mention 'Urgent' in your subject line."}},
            {"@@type":"Question","name":"I'm having trouble logging in. What should I do?","acceptedAnswer":{"@@type":"Answer","text":"Try the Forgot Password link on the login page first. If you still can't access your account, contact us with your registered email and we'll help recover it."}},
            {"@@type":"Question","name":"How do I report a fake or suspicious job listing?","acceptedAnswer":{"@@type":"Answer","text":"Send the job link through our contact form with the subject 'Report Job Listing.' Our trust and safety team reviews every report and removes fraudulent posts quickly."}},
            {"@@type":"Question","name":"Can I delete my Jobs in USA account?","acceptedAnswer":{"@@type":"Answer","text":"Yes — message us with your registered email and we'll permanently delete your account and associated data within 7 business days, in line with our privacy policy."}},
            {"@@type":"Question","name":"Do you offer partnership or advertising opportunities?","acceptedAnswer":{"@@type":"Answer","text":"Yes. Use the form and select 'Partnership' or 'Advertising' as your subject. Our partnerships team will share details on plans, reach, and pricing."}},
            {"@@type":"Question","name":"How do employers post jobs on Jobs in USA?","acceptedAnswer":{"@@type":"Answer","text":"Register an employer account, choose a posting plan, and submit your job through the dashboard. Need a custom plan for bulk hiring? Contact our sales team."}}
        ]
    }
    </script>
@endpush

@section('content')

@php
    $blogImg = function ($path) {
        if (!$path) return asset('public/user/images/blog-compact-post-01.jpg');
        if (str_starts_with($path, 'public/') || str_starts_with($path, 'http')) return asset($path);
        return asset('public/storage/' . $path);
    };
@endphp

<style>
    /* === Hero (light gradient + dark text — matches home theme) === */
    .contact-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 70px 0 60px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .contact-hero::before {
        content: ""; position: absolute; inset: 0;
        background-image: radial-gradient(circle at 12% 20%, rgba(10,10,10,.04) 0, transparent 40%),
                          radial-gradient(circle at 88% 80%, rgba(10,10,10,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .contact-hero .container { position: relative; z-index: 2; text-align: center; }
    .contact-hero .breadcrumbs-mini { color: #777; font-size: 13px; margin-bottom: 14px; }
    .contact-hero .breadcrumbs-mini a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .contact-hero .eyebrow {
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
        margin-bottom: 18px;
        box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .contact-hero h1 {
        color: #0a0a0a;
        font-size: clamp(30px, 4.4vw, 50px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 0 18px;
        max-width: 880px;
        margin-left: auto; margin-right: auto;
    }
    .contact-hero h1 .accent {
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .contact-hero p.lead {
        color: #555;
        font-size: clamp(15px, 1.5vw, 17px);
        line-height: 1.65;
        max-width: 720px;
        margin: 0 auto;
    }

    /* === Quick Contact Cards === */
    .quick-contact-section { padding: 60px 0 30px; background: #fff; }
    .quick-contact-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    @media (max-width: 768px) { .quick-contact-grid { grid-template-columns: 1fr; } }

    .quick-contact-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 32px 26px;
        text-align: center;
        transition: all .25s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
        overflow: hidden;
    }
    .quick-contact-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: #0a0a0a;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s ease;
    }
    .quick-contact-card:hover {
        transform: translateY(-4px);
        border-color: #0a0a0a;
        box-shadow: 0 18px 36px rgba(15,23,42,.10);
        color: inherit;
    }
    .quick-contact-card:hover::before { transform: scaleX(1); }
    .quick-contact-icon {
        width: 60px; height: 60px;
        border-radius: 14px;
        background: #0a0a0a;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 18px;
        transition: background .25s ease;
    }
    .quick-contact-card:hover .quick-contact-icon { background: #1a1a1a; }
    .quick-contact-card h4 {
        font-size: 18px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 0 0 8px;
    }
    .quick-contact-card p {
        color: #555;
        font-size: 14px;
        margin: 0 0 14px;
        line-height: 1.6;
    }
    .quick-contact-card .qc-action {
        color: #0a0a0a;
        font-weight: 700;
        font-size: 13.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 1.5px solid #0a0a0a;
        padding-bottom: 2px;
        transition: gap .15s ease;
    }
    .quick-contact-card:hover .qc-action { gap: 10px; }

    /* === Form + Info section === */
    .contact-section { padding: 30px 0 70px; background: #fff; }

    .contact-form-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 36px 32px;
    }
    .contact-form-card h3 {
        font-size: 22px;
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 8px;
        letter-spacing: -.3px;
    }
    .contact-form-card .lead {
        color: #555;
        font-size: 14.5px;
        line-height: 1.65;
        margin: 0 0 24px;
    }
    .contact-form-card label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #0a0a0a;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .contact-form-card input,
    .contact-form-card textarea,
    .contact-form-card select {
        width: 100%;
        background: #fff;
        border: 1px solid #e5e5e7 !important;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 15px;
        color: #1a1a1a;
        transition: border-color .15s ease, box-shadow .15s ease;
        margin-bottom: 14px;
        box-shadow: none;
    }
    .contact-form-card input:focus,
    .contact-form-card textarea:focus,
    .contact-form-card select:focus {
        outline: none;
        border-color: #0a0a0a !important;
        box-shadow: 0 0 0 3px rgba(0,0,0,.08);
    }
    .contact-form-card textarea { resize: vertical; min-height: 130px; }
    .contact-form-card .submit-btn {
        background: #0a0a0a;
        color: #fff;
        border: 1.5px solid #0a0a0a;
        padding: 13px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14.5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .15s ease;
    }
    .contact-form-card .submit-btn:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }

    /* Contact info card — dark with brand glow */
    .contact-info-card {
        background: #0a0a0a;
        color: #fff;
        border-radius: 16px;
        padding: 36px 32px;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .contact-info-card::before, .contact-info-card::after {
        content: ""; position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: .25;
        pointer-events: none;
    }
    .contact-info-card::before { width: 220px; height: 220px; background: #ff5722; top: -60px; right: -50px; }
    .contact-info-card::after { width: 180px; height: 180px; background: #5e2bff; bottom: -50px; left: -40px; }
    .contact-info-card > * { position: relative; z-index: 2; }
    .contact-info-card .eyebrow {
        display: inline-block;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.20);
        color: rgba(255,255,255,.85);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
        margin-bottom: 14px;
    }
    .contact-info-card h3 {
        color: #fff;
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 24px;
        letter-spacing: -.3px;
    }
    .contact-info-list { list-style: none; padding: 0; margin: 0; }
    .contact-info-list li {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 22px;
        font-size: 14px;
        color: rgba(255,255,255,0.85);
        line-height: 1.55;
    }
    .contact-info-list li:last-child { margin-bottom: 0; }
    .contact-info-list li .ico {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.15);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #fff;
        flex-shrink: 0;
    }
    .contact-info-list li strong {
        color: #fff;
        display: block;
        margin-bottom: 3px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .contact-info-list li a {
        color: rgba(255,255,255,.92);
        text-decoration: none;
        transition: color .15s ease;
    }
    .contact-info-list li a:hover { color: #fff; }

    /* === Map placeholder section === */
    .contact-map-section { padding: 0 0 70px; background: #fff; }
    .contact-map-card {
        background: #fafafa;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 30px 32px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        align-items: center;
    }
    @media (max-width: 768px) { .contact-map-card { grid-template-columns: 1fr; } }
    .contact-map-card .info h3 {
        font-size: 22px;
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 10px;
        letter-spacing: -.3px;
    }
    .contact-map-card .info p {
        font-size: 14.5px;
        line-height: 1.7;
        color: #555;
        margin: 0 0 16px;
    }
    .contact-map-card .info .map-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #0a0a0a;
        font-weight: 700;
        font-size: 13.5px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 1.5px solid #0a0a0a;
        padding-bottom: 2px;
        transition: gap .15s ease;
    }
    .contact-map-card .info .map-link:hover { gap: 12px; }
    .contact-map-card .map-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .contact-map-card .map-stat {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 18px 20px;
    }
    .contact-map-card .map-stat strong {
        display: block;
        font-size: 22px;
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.1;
        letter-spacing: -.3px;
    }
    .contact-map-card .map-stat span {
        font-size: 11.5px;
        color: #777;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* === Blog teaser === */
    .contact-blog-section {
        background: #fafafa;
        padding: 70px 0;
        border-top: 1px solid #ececec;
    }
    .contact-blog-section .section-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 14px;
    }
    .contact-blog-section .section-head h2 {
        font-size: clamp(24px, 2.8vw, 32px);
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 6px;
        letter-spacing: -.4px;
    }
    .contact-blog-section .section-head p { color: #555; font-size: 14.5px; margin: 0; }
    .contact-blog-section .view-all-link {
        color: #0a0a0a;
        font-weight: 700;
        font-size: 13.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 1.5px solid #0a0a0a;
        padding-bottom: 2px;
        transition: gap .15s ease;
    }
    .contact-blog-section .view-all-link:hover { gap: 12px; }
    .contact-blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    @media (max-width: 991px) { .contact-blog-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .contact-blog-grid { grid-template-columns: 1fr; } }
    .contact-blog-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: all .25s ease;
    }
    .contact-blog-card:hover {
        transform: translateY(-3px);
        border-color: #0a0a0a;
        box-shadow: 0 14px 32px rgba(15,23,42,.10);
        color: inherit;
    }
    .contact-blog-thumb { height: 180px; overflow: hidden; background: #f5f5f7; }
    .contact-blog-thumb img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
    }
    .contact-blog-card:hover .contact-blog-thumb img { transform: scale(1.05); }
    .contact-blog-body { padding: 18px 20px 22px; flex: 1; display: flex; flex-direction: column; }
    .contact-blog-body .meta { font-size: 12px; color: #777; margin-bottom: 8px; }
    .contact-blog-body h4 {
        font-size: 16px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 0 0 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .contact-blog-body p {
        font-size: 13px;
        color: #555;
        line-height: 1.6;
        flex: 1;
        margin: 0 0 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .contact-blog-body .read-link {
        color: #0a0a0a;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* === FAQ === */
    .contact-faq-section {
        background: #fff;
        padding: 70px 0;
        border-top: 1px solid #ececec;
    }
    .contact-faq-grid {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 70px;
        align-items: start;
    }
    @media (max-width: 991px) {
        .contact-faq-grid { grid-template-columns: 1fr; gap: 30px; }
    }
    .contact-faq-head { text-align: left; position: sticky; top: 90px; }
    .contact-faq-head .eyebrow {
        display: inline-block;
        background: rgba(255,138,0,.10);
        color: #ff8a00;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 999px;
        margin-bottom: 14px;
    }
    .contact-faq-head h2 {
        font-size: clamp(26px, 3vw, 38px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.15;
        letter-spacing: -.5px;
        margin: 0 0 14px;
    }
    .contact-faq-head h2 .accent {
        background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
        -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
    }
    .contact-faq-head p { color: #555; font-size: 16px; line-height: 1.65; margin: 0 0 22px; }
    .contact-faq-cta {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #ff8a00, #ff5722); color: #fff !important;
        padding: 12px 22px; border-radius: 10px;
        font-weight: 700; font-size: 14px; text-decoration: none;
        box-shadow: 0 8px 20px rgba(255,138,0,.30);
        transition: all .2s ease;
    }
    .contact-faq-cta:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(255,138,0,.40); }
    .contact-faq-list { max-width: none; margin: 0; }
    .contact-faq-item {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all .2s ease;
    }
    .contact-faq-item[open] {
        border-color: #0a0a0a;
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
    }
    .contact-faq-item summary {
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
    .contact-faq-item summary::-webkit-details-marker { display: none; }
    .contact-faq-item summary::after {
        content: '+';
        font-size: 24px;
        color: #0a0a0a;
        font-weight: 300;
    }
    .contact-faq-item[open] summary::after { content: '−'; }
    .contact-faq-item .faq-answer {
        padding: 0 24px 22px;
        color: #555;
        font-size: 14.5px;
        line-height: 1.75;
    }

    /* Alert */
    .contact-alert {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .contact-alert i { color: #047857; font-size: 18px; }
</style>

<!-- Hero -->
<section class="contact-hero">
    <div class="container">
        <div class="breadcrumbs-mini">
            <a href="{{ url('/') }}">Home</a> &nbsp;&rsaquo;&nbsp; Contact Us
        </div>
        <span class="eyebrow" data-aos="fade-down" data-aos-duration="600">Contact Us</span>
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Get in Touch With <span class="accent">Jobs in USA</span></h1>
        <p class="lead" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">Have a question, need support, or want to partner with us? Our team responds within 24 hours on business days. Choose the contact method that works best for you below.</p>
    </div>
</section>

<!-- Quick Contact Cards -->
<section class="quick-contact-section">
    <div class="container">
        <div class="quick-contact-grid">
            <a href="tel:+13217759823" class="quick-contact-card">
                <div class="quick-contact-icon"><i class="icon-feather-phone"></i></div>
                <h4>Call Us</h4>
                <p>Speak directly with our support team for quick assistance and account help.</p>
                <span class="qc-action">(+1) 321 775 9823 <i class="icon-feather-arrow-right"></i></span>
            </a>
            <a href="mailto:info@jobsinusa.us" class="quick-contact-card">
                <div class="quick-contact-icon"><i class="icon-feather-mail"></i></div>
                <h4>Email Us</h4>
                <p>Drop us a line and we'll respond within 24 hours on business days.</p>
                <span class="qc-action">info@jobsinusa.us <i class="icon-feather-arrow-right"></i></span>
            </a>
            <a href="https://calendly.com/" target="_blank" rel="noopener" class="quick-contact-card">
                <div class="quick-contact-icon"><i class="icon-feather-calendar"></i></div>
                <h4>Book a Meeting</h4>
                <p>Schedule a one-on-one call with our team at a time that fits your schedule.</p>
                <span class="qc-action">Book Now <i class="icon-feather-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

<!-- Form + Info -->
<section class="contact-section">
    <div class="container">
        @if(session('success'))
            <div class="contact-alert">
                <i class="icon-feather-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="contact-form-card">
                    <h3>Send Us a Message</h3>
                    <p class="lead">Have a question, need support, or want to partner with us? Fill out the form and we'll respond within 24 hours.</p>

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        {{-- Honeypot fields — hidden from users, bots typically auto-fill these --}}
                        <div style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
                            <label>Website (leave blank)</label>
                            <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
                            <label>Phone (leave blank)</label>
                            <input type="text" name="hp_phone" tabindex="-1" autocomplete="off" value="">
                        </div>
                        {{-- Submission timestamp — bots usually submit in <2s, real users take longer --}}
                        <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">

                        <div class="row">
                            <div class="col-md-6">
                                <label for="first_name">First Name</label>
                                <input id="first_name" name="first_name" type="text" placeholder="Jane" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" name="last_name" type="text" placeholder="Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email Address</label>
                                <input id="email" name="email" type="email" placeholder="you@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="subject">Subject</label>
                                <input id="subject" name="subject" type="text" placeholder="How can we help?" required>
                            </div>
                            <div class="col-md-12">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" placeholder="Tell us a bit about what you need…" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">
                            Send Message <i class="icon-feather-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <span class="eyebrow">Contact Info</span>
                    <h3>We're here to help</h3>
                    <ul class="contact-info-list">
                        <li>
                            <div class="ico"><i class="icon-feather-phone"></i></div>
                            <div>
                                <strong>Phone</strong>
                                <a href="tel:+13217759823">(+1) 321 775 9823</a>
                            </div>
                        </li>
                        <li>
                            <div class="ico"><i class="icon-feather-mail"></i></div>
                            <div>
                                <strong>Email</strong>
                                <a href="mailto:info@jobsinusa.us">info@jobsinusa.us</a>
                            </div>
                        </li>
                        <li>
                            <div class="ico"><i class="icon-feather-map-pin"></i></div>
                            <div>
                                <strong>Address</strong>
                                Building 800, N State College Blvd<br>
                                Cal State Fullerton, Fullerton, CA
                            </div>
                        </li>
                        <li>
                            <div class="ico"><i class="icon-feather-clock"></i></div>
                            <div>
                                <strong>Working Hours</strong>
                                Mon — Fri: 9:00 AM – 6:00 PM PST
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust strip -->
<section class="contact-map-section">
    <div class="container">
        <div class="contact-map-card">
            <div class="info">
                <h3>Trusted by job seekers across America</h3>
                <p>Millions of professionals use Jobs in USA every month to find verified opportunities, connect with employers, and grow their careers across all 50 U.S. states.</p>
                <a href="{{ route('about.us') }}" class="map-link">
                    Learn About Us <i class="icon-feather-arrow-right"></i>
                </a>
            </div>
            <div class="map-stats">
                <div class="map-stat"><strong>10M+</strong><span>Job seekers</span></div>
                <div class="map-stat"><strong>230K+</strong><span>Active jobs</span></div>
                <div class="map-stat"><strong>50</strong><span>U.S. states</span></div>
                <div class="map-stat"><strong>24h</strong><span>Response time</span></div>
            </div>
        </div>
    </div>
</section>

@php
    $contactBlogs = \App\Models\Blog::with('author')
        ->whereNotNull('published_at')
        ->where(function($q){ $q->where('status', 'published')->orWhereNull('status'); })
        ->latest('published_at')
        ->take(3)
        ->get();
@endphp

@if($contactBlogs->count())
<!-- Blog Section -->
<section class="contact-blog-section">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>From Our Career Blog</h2>
                <p>Job search tips, salary guides, and industry insights to help you grow.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="view-all-link">View All <i class="icon-feather-arrow-right"></i></a>
        </div>
        <div class="contact-blog-grid">
            @foreach($contactBlogs as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="contact-blog-card">
                    <div class="contact-blog-thumb">
                        <img src="{{ $blogImg($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy">
                    </div>
                    <div class="contact-blog-body">
                        <div class="meta">{{ optional($post->published_at)->format('M d, Y') }} · By {{ $post->author_name ?? $post->author?->name ?? 'Jobs in USA Editorial' }}</div>
                        <h4>{{ $post->title }}</h4>
                        <p>{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 100) }}</p>
                        <span class="read-link">Read More →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FAQ Section -->
<section class="contact-faq-section" aria-labelledby="contact-faq-heading">
    <div class="container">
        <div class="contact-faq-grid">
            <header class="contact-faq-head">
                <span class="eyebrow">FAQ</span>
                <h2 id="contact-faq-heading">Frequently asked <span class="accent">questions</span></h2>
                <p>Quick answers to common questions. Can't find what you're looking for? Reach out using the form above and we'll respond within 24 hours.</p>
                <a href="#contact-form" class="contact-faq-cta">Contact Support <i class="icon-feather-arrow-right"></i></a>
            </header>
            <div class="contact-faq-list">
            <details class="contact-faq-item" open>
                <summary>How quickly will I receive a response?</summary>
                <div class="faq-answer">Our support team typically responds within 24 hours on business days. For urgent matters, please mention "Urgent" in the subject line and we'll prioritize your request.</div>
            </details>
            <details class="contact-faq-item">
                <summary>I'm having trouble logging in. What should I do?</summary>
                <div class="faq-answer">First, try the "Forgot Password" link on the login page. If you still can't access your account, contact us with your registered email and we'll help you recover it within one business day.</div>
            </details>
            <details class="contact-faq-item">
                <summary>How do I report a fake or suspicious job listing?</summary>
                <div class="faq-answer">If a listing looks suspicious, send us the job link through this form with the subject "Report Job Listing." Our trust and safety team reviews every report and removes fraudulent posts quickly.</div>
            </details>
            <details class="contact-faq-item">
                <summary>Can I delete my Jobs in USA account?</summary>
                <div class="faq-answer">Yes — message us with your registered email and we'll permanently delete your account and associated data within 7 business days, in line with our privacy policy.</div>
            </details>
            <details class="contact-faq-item">
                <summary>Do you offer partnership or advertising opportunities?</summary>
                <div class="faq-answer">Absolutely. Use the form and select "Partnership" or "Advertising" as your subject. Our partnerships team will share details on plans, reach, and pricing within 1–2 business days.</div>
            </details>
            <details class="contact-faq-item">
                <summary>I'm an employer — how do I post jobs?</summary>
                <div class="faq-answer">Register an employer account, choose a posting plan, and submit your job through the dashboard. If you need a custom plan for bulk hiring, contact our sales team via this form.</div>
            </details>
            <details class="contact-faq-item">
                <summary>How can I update my resume or profile?</summary>
                <div class="faq-answer">Log in, go to your dashboard, and edit your profile or upload a new resume any time. Updates take effect immediately for new applications.</div>
            </details>
            <details class="contact-faq-item">
                <summary>Are my personal details kept private when I contact you?</summary>
                <div class="faq-answer">Yes. Your information is handled in line with our privacy policy and only used to respond to your inquiry. We never share or sell contact form data with third parties.</div>
            </details>
            </div>
        </div>
    </div>
</section>

@endsection
