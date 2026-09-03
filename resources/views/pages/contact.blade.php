@extends('user.layouts.master')
@section('title', 'Contact Us — Get Support & Inquiries | JobGader')
@section('meta_description', 'Contact JobGader for support, inquiries, or partnership opportunities.')
@section('content')

<style>
    /* === Contact page hero (replaces legacy thin titlebar) === */
    .contact-page-hero {
        position: relative;
        padding: 110px 0 90px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    @media (max-width: 991px) { .contact-page-hero { padding: 80px 0 60px; } }
    @media (max-width: 575px) { .contact-page-hero { padding: 60px 0 50px; } }
    .contact-page-hero .container { position: relative; z-index: 2; text-align: center; }
    .contact-page-hero .breadcrumbs-mini {
        color: #777; font-size: 13px; margin-bottom: 14px;
    }
    .contact-page-hero .breadcrumbs-mini a {
        color: #1b3a6b; text-decoration: none; font-weight: 600;
    }
    .contact-page-hero .breadcrumbs-mini a:hover { color: #1b3a6b; }
    .contact-page-hero .eyebrow {
        display: inline-block;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        padding: 7px 16px;
        border-radius: 999px;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .contact-page-hero h1 {
        color: #1b3a6b;
        font-size: clamp(34px, 4.8vw, 56px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 0 20px;
        max-width: 880px;
        margin-left: auto; margin-right: auto;
    }
    .contact-page-hero h1 .accent {
        background: linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #ffab40);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .contact-page-hero p.lead {
        color: #555;
        font-size: clamp(15px, 1.5vw, 18px);
        line-height: 1.65;
        max-width: 760px;
        margin: 0 auto;
    }

    /* Dark mode for contact hero */
    html.dark-mode .contact-page-hero {
        border-bottom-color: rgba(255,255,255,.08) !important;
    }
    html.dark-mode .contact-page-hero .breadcrumbs-mini { color: #b8c0cc !important; }
    html.dark-mode .contact-page-hero .breadcrumbs-mini a { color: #1b3a6b !important; }
    html.dark-mode .contact-page-hero .eyebrow {
        background: rgba(27, 58, 107,.12) !important;
        border-color: rgba(27, 58, 107,.30) !important;
        color: #1b3a6b !important;
    }
    html.dark-mode .contact-page-hero h1 { color: #fff !important; }
    html.dark-mode .contact-page-hero p.lead { color: #b8c0cc !important; }

    .contact-section { padding: 60px 0; }
    .contact-section h1 {
        font-size: 34px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #2a2a2a;
    }
    .contact-section .lead {
        color: #666;
        font-size: 15px;
        margin-bottom: 30px;
    }
    .contact-form-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        padding: 36px 32px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
    }
    .contact-form-card .form-group { margin-bottom: 16px; }
    .contact-form-card label {
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
        display: block;
    }
    .contact-form-card .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color .2s ease;
    }
    .contact-form-card .form-control:focus {
        border-color: #1b3a6b;
        outline: none;
    }
    .contact-form-card .submit-btn {
        background: #1b3a6b;
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background .2s ease;
    }
    .contact-form-card .submit-btn:hover { background: #e57b00; }

    .contact-info-card {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #fff;
        border-radius: 8px;
        padding: 36px 32px;
        height: 100%;
    }
    .contact-info-card h3 {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .contact-info-list { list-style: none; padding: 0; margin: 0; }
    .contact-info-list li {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 18px;
        font-size: 14px;
        color: rgba(255,255,255,0.9);
    }
    .contact-info-list li i {
        color: #1b3a6b;
        font-size: 18px;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .contact-info-list li strong { color: #fff; display: block; margin-bottom: 2px; }

    .contact-faq-section {
        background: #fafafa;
        padding: 70px 0;
        border-top: 1px solid #ececec;
    }
    .contact-faq-section .faq-head {
        text-align: center;
        margin-bottom: 40px;
    }
    .contact-faq-section .faq-head h2 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #2a2a2a;
    }
    .contact-faq-section .faq-head p { color: #666; font-size: 15px; }
    .contact-faq-list { max-width: 900px; margin: 0 auto; }
    .contact-faq-item {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 6px;
        margin-bottom: 14px;
        overflow: hidden;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .contact-faq-item[open] {
        border-color: #1b3a6b;
        box-shadow: 0 4px 14px rgba(27, 58, 107,0.08);
    }
    .contact-faq-item summary {
        padding: 18px 22px;
        font-weight: 600;
        font-size: 15px;
        color: #2a2a2a;
        cursor: pointer;
        list-style: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .contact-faq-item summary::-webkit-details-marker { display: none; }
    .contact-faq-item summary::after {
        content: '+';
        font-size: 22px;
        color: #1b3a6b;
        font-weight: 400;
    }
    .contact-faq-item[open] summary::after { content: '−'; }
    .contact-faq-item .faq-answer {
        padding: 0 22px 20px;
        color: #666;
        font-size: 14px;
        line-height: 1.7;
    }

    /* Quick contact cards */
    .quick-contact-section { padding: 50px 0 20px; }
    .quick-contact-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    @media (max-width: 768px) {
        .quick-contact-grid { grid-template-columns: 1fr; }
    }
    .quick-contact-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 10px;
        padding: 36px 28px;
        text-align: center;
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .quick-contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 32px rgba(0,0,0,0.08);
        border-color: #1b3a6b;
        color: inherit;
    }
    .quick-contact-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(27, 58, 107,0.12);
        color: #1b3a6b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin: 0 auto 18px;
        transition: background .25s ease, color .25s ease;
    }
    .quick-contact-card:hover .quick-contact-icon {
        background: #1b3a6b;
        color: #fff;
    }
    .quick-contact-card h4 {
        font-size: 18px;
        font-weight: 700;
        color: #2a2a2a;
        margin-bottom: 8px;
    }
    .quick-contact-card p {
        color: #777;
        font-size: 13px;
        margin-bottom: 14px;
        line-height: 1.6;
    }
    .quick-contact-card .qc-action {
        color: #1b3a6b;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Blog teaser on contact */
    .contact-blog-section {
        background: #fff;
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
        font-size: 28px;
        font-weight: 700;
        color: #2a2a2a;
        margin-bottom: 4px;
    }
    .contact-blog-section .section-head p { color: #777; font-size: 14px; margin: 0; }
    .contact-blog-section .view-all-link {
        color: #1b3a6b;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .contact-blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    @media (max-width: 991px) {
        .contact-blog-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .contact-blog-grid { grid-template-columns: 1fr; }
    }
    .contact-blog-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .contact-blog-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.07);
        border-color: #1b3a6b;
        color: inherit;
    }
    .contact-blog-thumb { height: 150px; overflow: hidden; background: #f5f5f5; }
    @media (max-width: 991px) { .contact-blog-thumb { height: 140px; } }
    @media (max-width: 575px) { .contact-blog-thumb { height: 180px; } }
    .contact-blog-thumb img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .35s ease;
    }
    .contact-blog-card:hover .contact-blog-thumb img { transform: scale(1.05); }
    .contact-blog-body { padding: 18px 20px 22px; flex: 1; display: flex; flex-direction: column; }
    .contact-blog-body .meta {
        font-size: 11px;
        color: #888;
        margin-bottom: 8px;
    }
    .contact-blog-body h4 {
        font-size: 15px;
        font-weight: 600;
        color: #2a2a2a;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .contact-blog-body p {
        font-size: 13px;
        color: #666;
        line-height: 1.6;
        flex: 1;
        margin-bottom: 12px;
    }
    .contact-blog-body .read-link {
        color: #1b3a6b;
        font-weight: 600;
        font-size: 12px;
    }

    /* =========================================
       DARK MODE — Contact page sections
       ========================================= */
    html.dark-mode .contact-section {
        background: var(--site-bg, #0f1216) !important;
    }
    html.dark-mode .contact-section .form-control,
    html.dark-mode .contact-form-card .form-control {
        background: #1c2128 !important;
        border-color: rgba(255,255,255,.12) !important;
        color: #fff !important;
    }
    html.dark-mode .contact-form-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .contact-form-card h1,
    html.dark-mode .contact-form-card h2 { color: #fff !important; }
    html.dark-mode .contact-form-card label { color: #d0d6df !important; }
    html.dark-mode .contact-form-card .lead { color: #b8c0cc !important; }
    html.dark-mode .contact-form-card .form-control:focus { border-color: #1b3a6b !important; }

    /* Quick contact cards */
    html.dark-mode .quick-contact-section { background: transparent !important; }
    html.dark-mode .quick-contact-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .quick-contact-card:hover { border-color: #1b3a6b !important; }
    html.dark-mode .quick-contact-card h4 { color: #fff !important; }
    html.dark-mode .quick-contact-card p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .quick-contact-icon {
        background: rgba(27, 58, 107,.15) !important;
        color: #1b3a6b !important;
    }
    html.dark-mode .quick-contact-card:hover .quick-contact-icon {
        background: #1b3a6b !important; color: #fff !important;
    }

    /* "From Our Blog" teaser section */
    html.dark-mode .contact-blog-section {
        background: var(--site-bg, #0f1216) !important;
        border-top-color: rgba(255,255,255,.06) !important;
    }
    html.dark-mode .contact-blog-section .section-head h2 { color: #fff !important; }
    html.dark-mode .contact-blog-section .section-head p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .contact-blog-card {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .contact-blog-card:hover { border-color: #1b3a6b !important; }
    html.dark-mode .contact-blog-thumb { background: #15181d !important; }
    html.dark-mode .contact-blog-body .meta { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .contact-blog-body h4 { color: #fff !important; }
    html.dark-mode .contact-blog-body p { color: var(--site-muted, #b8c0cc) !important; }

    /* FAQ section */
    html.dark-mode .contact-faq-section {
        background: #161a20 !important;
        border-top-color: rgba(255,255,255,.06) !important;
    }
    html.dark-mode .contact-faq-section .faq-head h2 { color: #fff !important; }
    html.dark-mode .contact-faq-section .faq-head p { color: var(--site-muted, #b8c0cc) !important; }
    html.dark-mode .contact-faq-item {
        background: var(--site-card-bg, #1c2128) !important;
        border-color: rgba(255,255,255,.10) !important;
    }
    html.dark-mode .contact-faq-item:hover,
    html.dark-mode .contact-faq-item[open] { border-color: #1b3a6b !important; }
    html.dark-mode .contact-faq-item summary { color: #fff !important; }
    html.dark-mode .contact-faq-item .faq-answer { color: var(--site-muted, #b8c0cc) !important; }

    /* Contact info card (dark gradient — keep but tighten on dark theme) */
    html.dark-mode .contact-info-card {
        background: linear-gradient(135deg, #1c2128 0%, #15181d 100%) !important;
        border: 1px solid rgba(255,255,255,.08) !important;
    }
</style>

<section class="contact-hero contact-page-hero">
    <div class="container">
        <div class="breadcrumbs-mini">
            <a href="{{ route('home') }}">Home</a> &nbsp;&rsaquo;&nbsp; Contact Us
        </div>
        <span class="eyebrow" data-aos="fade-down" data-aos-duration="600">We're Here to Help</span>
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            Get in Touch With <span class="accent">JobGader</span>
        </h1>
        <p class="lead" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">
            Have a question, need support, or want to partner with us? Our team responds within 24 hours on business days. Pick the contact channel that works best for you below.
        </p>
    </div>
</section>

<!-- Quick Contact Cards -->
<div class="quick-contact-section">
    <div class="container">
        <div class="quick-contact-grid">
            <a href="{{ route('jobs.index') }}" class="quick-contact-card">
                <div class="quick-contact-icon"><i class="icon-feather-search"></i></div>
                <h4>Looking for work?</h4>
                <p>Browse every opening across the {{ $coverage->shortList() }} &mdash; free, and no account needed.</p>
                <span class="qc-action">Browse all jobs <i class="icon-feather-arrow-right"></i></span>
            </a>
            <a href="mailto:{{ config('site.contact_email') }}" class="quick-contact-card">
                <div class="quick-contact-icon"><i class="icon-feather-mail"></i></div>
                <h4>Email Us</h4>
                <p>Drop us a line and we'll respond within 24 hours.</p>
                <span class="qc-action">{{ config('site.contact_email') }} <i class="icon-feather-arrow-right"></i></span>
            </a>
            <a href="https://calendly.com/" target="_blank" rel="noopener" class="quick-contact-card">
                <div class="quick-contact-icon"><i class="icon-feather-calendar"></i></div>
                <h4>Book a Meeting</h4>
                <p>Schedule a one-on-one call with our team at a time that works for you.</p>
                <span class="qc-action">Book Now <i class="icon-feather-arrow-right"></i></span>
            </a>
        </div>
    </div>
</div>

<div class="contact-section">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <h2>Get in Touch</h2>
                    <p class="lead">Have a question, need support, or want to partner with us? Fill out the form and we'll respond within 24 hours.</p>

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button class="submit-btn" type="submit">Send Message</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <h3>Contact Information</h3>
                    <ul class="contact-info-list">
                        <li>
                            <i class="icon-feather-mail"></i>
                            <div>
                                <strong>Email</strong>
                                {{ config('site.contact_email') }}
                            </div>
                        </li>
                        <li>
                            <i class="icon-feather-globe"></i>
                            <div>
                                <strong>Coverage</strong>
                                {{ $coverage->fullList() }}
                            </div>
                        </li>
                        <li>
                            <i class="icon-feather-dollar-sign"></i>
                            <div>
                                <strong>Our Fee</strong>
                                None &mdash; free for job seekers
                            </div>
                        </li>
                        <li>
                            <i class="icon-feather-clock"></i>
                            <div>
                                <strong>Working Hours</strong>
                                Mon - Fri: 9:00 AM - 6:00 PM
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $contactBlogs = \App\Models\Blog::with('author')
        ->whereNotNull('published_at')
        ->where(function($q){ $q->where('status', 'published')->orWhereNull('status'); })
        ->latest('published_at')
        ->take(3)
        ->get();
@endphp

@if($contactBlogs->count())
<div class="contact-blog-section">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>From Our Blog</h2>
                <p>Career tips and insights to help you grow.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="view-all-link">View All <i class="icon-feather-arrow-right"></i></a>
        </div>
        <div class="contact-blog-grid">
            @foreach($contactBlogs as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="contact-blog-card">
                    <div class="contact-blog-thumb">
                        @if($post->featured_image)
                            <img src="{{ asset('public/storage/' . $post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='{{ asset('public/user/images/blog-compact-post-01.jpg') }}'">
                        @else
                            <img src="{{ asset('public/user/images/blog-compact-post-01.jpg') }}"
                                 alt="{{ $post->title }}" loading="lazy">
                        @endif
                    </div>
                    <div class="contact-blog-body">
                        <div class="meta">{{ optional($post->published_at)->format('d M, Y') }} · By {{ $post->author?->name ?? 'Unknown' }}</div>
                        <h4>{{ $post->title }}</h4>
                        <p>{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 90) }}</p>
                        <span class="read-link">Read More →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="contact-faq-section">
    <div class="container">
        <div class="faq-head">
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers to questions you may have. Can't find what you're looking for? Reach out using the form above.</p>
        </div>
        <div class="contact-faq-list">
            <details class="contact-faq-item">
                <summary>How quickly will I receive a response?</summary>
                <div class="faq-answer">Our support team typically responds within 24 hours on business days. For urgent matters, please mention "Urgent" in the subject line.</div>
            </details>
            <details class="contact-faq-item">
                <summary>I'm having trouble logging in. What should I do?</summary>
                <div class="faq-answer">First try the "Forgot Password" link on the login page. If you still can't access your account, contact us with your registered email and we'll help you recover it.</div>
            </details>
            <details class="contact-faq-item">
                <summary>How do I report a fake or suspicious job listing?</summary>
                <div class="faq-answer">If a listing looks suspicious, send us the job link through this form with the subject "Report Job Listing." Our team reviews every report and removes fraudulent posts quickly.</div>
            </details>
            <details class="contact-faq-item">
                <summary>Can I delete my account?</summary>
                <div class="faq-answer">Yes — message us with your registered email and we'll permanently delete your account and associated data within 7 business days, in line with our privacy policy.</div>
            </details>
            <details class="contact-faq-item">
                <summary>Do you offer partnership or advertising opportunities?</summary>
                <div class="faq-answer">Absolutely. Use the form and select "Partnership" or "Advertising" as your subject. Our partnerships team will share details on plans, reach, and pricing.</div>
            </details>
            <details class="contact-faq-item">
                <summary>I'm an employer — how do I post jobs?</summary>
                <div class="faq-answer">Register an employer/advertiser account, choose a posting plan, and submit your job through the dashboard. If you need a custom plan for bulk hiring, contact our sales team via this form.</div>
            </details>
            <details class="contact-faq-item">
                <summary>How can I update my resume or profile?</summary>
                <div class="faq-answer">Log in, go to your dashboard, and edit your profile or upload a new resume any time. Updates take effect immediately for new applications.</div>
            </details>
        </div>
    </div>
</div>

@endsection
