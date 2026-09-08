@extends('user.layouts.master')

@section('title', 'Resume & CV Writing Services — Written by a Person')
@section('meta_description', 'Professional resume and CV writing, written by a person and formatted so applicant tracking systems can actually read it. Send your details and we reply within one business day.')
@section('meta_keywords', 'resume writing services, cv writing services, professional cv writer, ats resume writing, cv writing pakistan, resume writing usa, cover letter writing, linkedin profile writing')
@section('og_title', 'Resume & CV Writing Services | JobGader')
@section('og_description', 'A resume written by a person, targeted at the job you actually want, and formatted to pass the software that reads it first.')
@section('canonical', route('resume-writing'))

@php
    $waNumber = preg_replace('/\D+/', '', (string) config('site.whatsapp'));
    $waLink = $waNumber !== ''
        ? 'https://wa.me/'.$waNumber.'?text='.rawurlencode('Hi, I would like a resume written.')
        : null;
    $contactEmail = config('site.contact_email');

    $rwFaqs = [
        ['Who actually writes my resume?', 'A person does, from your details and your existing CV. We do not generate it with AI and we do not drop your history into a template. You deal with the writer directly, so you can explain the parts of your career that a form would never capture.'],
        ['What is ATS, and why does the formatting matter so much?', 'Most medium and large employers run applications through an applicant tracking system before a human opens them. Multi-column layouts, text inside images, headers and footers, and unusual section names are the common reasons a good candidate is filtered out. We write in a structure these systems parse cleanly, without making the document ugly.'],
        ['Do you write CVs as well as resumes?', 'Yes, and they are not the same document. A US resume is short and achievement-led. A CV for the UK, Gulf or Pakistan carries more detail and different conventions around personal information. Tell us which country you are applying in and we write for that market.'],
        ['How long does it take?', 'We agree a delivery date with you before any money changes hands, based on how much work the role needs. We would rather commit to a date we can keep than quote a number that sounds impressive.'],
        ['What do you need from me?', 'Your current CV if you have one, the kind of role you are targeting, and a short conversation about what you actually did in your last two jobs. That conversation is where most of the value comes from.'],
        ['Do you write cover letters and LinkedIn profiles too?', 'Yes. A cover letter is written per role rather than reused, and a LinkedIn rewrite covers the headline, About section and experience so recruiters searching for your skills can find you.'],
        ['What if I am not happy with the draft?', 'You tell us what is wrong and we rewrite it. The draft is a starting point for a conversation, not a delivery.'],
        ['Can you guarantee I will get a job?', 'No, and be careful with anyone who does. A resume gets you read; the interview and the market decide the rest. What we can do is make sure you are not being filtered out before a person sees your name.'],
    ];
@endphp

@push('meta')
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    {{-- JSON-LD: the service itself --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Service",
        "name": "Resume and CV Writing Services",
        "serviceType": "Resume writing",
        "url": "{{ route('resume-writing') }}",
        "description": "Professional resume and CV writing by a human writer, formatted so applicant tracking systems can read it, and targeted at the specific role the candidate is applying for.",
        "provider": {
            "@@type": "Organization",
            "name": "JobGader",
            "url": "{{ url('/') }}",
            "email": "{{ $contactEmail }}"
        },
        "areaServed": {!! json_encode($coverage->areaServedNodes(), JSON_UNESCAPED_SLASHES) !!},
        "audience": { "@@type": "Audience", "audienceType": "Job seekers" }
    }
    </script>

    {{-- JSON-LD: BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Resume Writing", "item": "{{ route('resume-writing') }}" }
        ]
    }
    </script>

    {{-- JSON-LD: FAQ --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($rwFaqs as $i => $faq)
            {
                "@@type": "Question",
                "name": {!! json_encode($faq[0], JSON_UNESCAPED_SLASHES) !!},
                "acceptedAnswer": { "@@type": "Answer", "text": {!! json_encode($faq[1], JSON_UNESCAPED_SLASHES) !!} }
            }@if(! $loop->last),@endif
            @endforeach
        ]
    }
    </script>
@endpush

@section('content')

<style>
    .rw-page { background: #f6f9fd; }
    .rw-wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .rw-eyebrow {
        display: inline-block; background: #1b3a6b; color: #fff;
        font-size: 12.5px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase;
        padding: 9px 20px; border-radius: 999px; margin-bottom: 20px;
    }
    .rw-page h2 {
        font-size: clamp(27px, 3.4vw, 42px); font-weight: 800; color: #1b3a6b;
        line-height: 1.18; letter-spacing: -.6px; margin: 0 0 14px;
    }
    .rw-page h2 .accent { color: #2f7fc9; }
    .rw-lede { color: #55657a; font-size: 16.5px; line-height: 1.7; margin: 0 auto 40px; max-width: 720px; }

    /* ===== Hero ===== */
    .rw-hero { background: linear-gradient(160deg, #eef5fd 0%, #f6f9fd 60%, #fff 100%); padding: 64px 0 76px; }
    .rw-hero-grid { display: grid; grid-template-columns: 1.05fr .95fr; gap: 54px; align-items: center; }
    .rw-hero h1 {
        font-size: clamp(32px, 4.4vw, 54px); font-weight: 800; color: #1b3a6b;
        line-height: 1.12; letter-spacing: -1.2px; margin: 0 0 20px;
    }
    .rw-hero h1 .accent { color: #2f7fc9; }
    .rw-hero p.sub { color: #4c5b6e; font-size: 17px; line-height: 1.72; margin: 0 0 26px; max-width: 560px; }
    .rw-ticks { list-style: none; margin: 0 0 32px; padding: 0; display: grid; gap: 13px; }
    .rw-ticks li { display: flex; align-items: flex-start; gap: 11px; color: #22354d; font-size: 16px; font-weight: 600; }
    .rw-ticks i { color: #2f7fc9; font-size: 19px; line-height: 1.35; }
    .rw-cta-row { display: flex; flex-wrap: wrap; gap: 14px; }
    .rw-btn {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 15px 30px; border-radius: 999px;
        font-size: 15px; font-weight: 700; text-decoration: none;
        transition: transform .12s ease, box-shadow .15s ease, background .15s ease;
    }
    .rw-btn-primary { background: #1b3a6b; color: #fff !important; box-shadow: 0 8px 20px rgba(27,58,107,.26); }
    .rw-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(27,58,107,.34); }
    .rw-btn-wa { background: #25d366; color: #fff !important; box-shadow: 0 8px 20px rgba(37,211,102,.28); }
    .rw-btn-wa:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(37,211,102,.36); }
    .rw-btn-ghost { background: #fff; color: #1b3a6b !important; border: 1.5px solid #cfe0f3; }
    .rw-btn-ghost:hover { border-color: #1b3a6b; transform: translateY(-2px); }
    .rw-hero-media img {
        width: 100%; height: auto; border-radius: 22px;
        box-shadow: 0 26px 60px rgba(27,58,107,.20);
    }

    /* ===== Problem cards ===== */
    .rw-section { padding: 76px 0; }
    .rw-section.alt { background: #fff; }
    .rw-head-center { text-align: center; }
    .rw-problems { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    .rw-problem {
        background: #fff; border: 1px solid #e4edf8; border-radius: 18px;
        padding: 30px 26px; box-shadow: 0 2px 10px rgba(27,58,107,.05);
    }
    .rw-section.alt .rw-problem { background: #f6f9fd; }
    .rw-problem-ico {
        width: 50px; height: 50px; border-radius: 14px; display: grid; place-items: center;
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9); color: #fff; font-size: 21px; margin-bottom: 18px;
    }
    .rw-problem h3 { font-size: 19px; font-weight: 800; color: #1b3a6b; margin: 0 0 10px; }
    .rw-problem p { color: #55657a; font-size: 15.2px; line-height: 1.68; margin: 0; }

    /* ===== Services ===== */
    .rw-services { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .rw-service {
        background: #fff; border: 1px solid #e4edf8; border-radius: 18px; padding: 28px 24px;
        border-bottom: 4px solid #1b3a6b; transition: transform .15s ease, box-shadow .15s ease;
    }
    .rw-service:hover { transform: translateY(-4px); box-shadow: 0 16px 34px rgba(27,58,107,.13); }
    .rw-service h3 { font-size: 18px; font-weight: 800; color: #1b3a6b; margin: 16px 0 10px; }
    .rw-service p { color: #55657a; font-size: 14.8px; line-height: 1.66; margin: 0; }

    /* ===== Steps ===== */
    .rw-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; text-align: center; }
    .rw-step-num {
        width: 58px; height: 58px; margin: 0 auto 18px; border-radius: 50%;
        display: grid; place-items: center; font-size: 21px; font-weight: 800;
        background: #fff; color: #1b3a6b; border: 2.5px solid #1b3a6b;
    }
    .rw-step h3 { font-size: 19px; font-weight: 800; color: #1b3a6b; margin: 0 0 9px; }
    .rw-step p { color: #55657a; font-size: 15.2px; line-height: 1.68; margin: 0; }

    /* ===== Before / after band ===== */
    .rw-band { background: linear-gradient(135deg, #2f7fc9 0%, #1b3a6b 100%); padding: 76px 0; }
    .rw-band h2, .rw-band .rw-lede { color: #fff; }
    .rw-band h2 .accent { color: #cfe4fa; }
    .rw-band .rw-lede { color: #d6e6f8; }
    .rw-band .rw-eyebrow { background: rgba(255,255,255,.18); }
    .rw-compare { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .rw-compare-card { background: #fff; border-radius: 18px; padding: 30px 28px; }
    .rw-compare-tag {
        display: inline-flex; align-items: center; gap: 8px; border-radius: 999px;
        padding: 7px 18px; font-size: 13px; font-weight: 800; letter-spacing: .4px;
        color: #fff; background: #1b3a6b; margin-bottom: 20px;
    }
    .rw-compare ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 13px; }
    .rw-compare li { display: flex; align-items: flex-start; gap: 11px; color: #3d4d61; font-size: 15.2px; line-height: 1.6; }
    .rw-compare li i { font-size: 17px; line-height: 1.35; }
    .rw-x i { color: #d64545; }
    .rw-tick i { color: #2f9e5f; }

    /* ===== Industries ===== */
    .rw-industries { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .rw-industry {
        background: #fff; border: 1px solid #e4edf8; border-radius: 16px;
        padding: 22px 20px; border-bottom: 3px solid #1b3a6b;
    }
    .rw-industry strong { display: block; font-size: 16.5px; color: #1b3a6b; font-weight: 800; margin-bottom: 5px; }
    .rw-industry span { color: #6b7d92; font-size: 14px; }

    /* ===== Trust ===== */
    .rw-trust-grid { display: grid; grid-template-columns: .95fr 1.05fr; gap: 46px; align-items: center; }
    .rw-trust-grid img { width: 100%; height: auto; border-radius: 20px; box-shadow: 0 20px 46px rgba(27,58,107,.18); }
    .rw-trust-points { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .rw-trust-card { background: #fff; border: 1px solid #e4edf8; border-radius: 16px; padding: 24px 22px; }
    .rw-trust-card h3 { font-size: 17px; font-weight: 800; color: #1b3a6b; margin: 0 0 8px; }
    .rw-trust-card p { color: #55657a; font-size: 14.6px; line-height: 1.65; margin: 0; }

    /* ===== FAQ ===== */
    .rw-faq-grid { display: grid; grid-template-columns: .82fr 1.18fr; gap: 46px; align-items: start; }
    .rw-faq { border: 1px solid #e4edf8; border-radius: 14px; margin-bottom: 12px; background: #fff; overflow: hidden; }
    .rw-faq summary {
        cursor: pointer; list-style: none; padding: 19px 24px;
        font-size: 16px; font-weight: 700; color: #1b3a6b;
        display: flex; justify-content: space-between; align-items: center; gap: 16px;
    }
    .rw-faq summary::-webkit-details-marker { display: none; }
    .rw-faq summary::after {
        content: "+"; font-size: 22px; font-weight: 700; color: #2f7fc9; flex: 0 0 auto;
    }
    .rw-faq[open] summary { background: #1b3a6b; color: #fff; }
    .rw-faq[open] summary::after { content: "\2212"; color: #fff; }
    .rw-faq-body { padding: 18px 24px 22px; color: #55657a; font-size: 15.2px; line-height: 1.72; }

    /* ===== Enquiry form ===== */
    .rw-form-section { background: linear-gradient(135deg, #14294a 0%, #1b3a6b 100%); padding: 78px 0; }
    .rw-form-grid { display: grid; grid-template-columns: .92fr 1.08fr; gap: 46px; align-items: start; }
    .rw-form-copy h2 { color: #fff; }
    .rw-form-copy h2 .accent { color: #8fc4f0; }
    .rw-form-copy > p { color: #cfe0f3; font-size: 16px; line-height: 1.72; margin: 0 0 26px; }
    .rw-form-copy .rw-eyebrow { background: rgba(255,255,255,.18); }
    .rw-contact-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 16px; }
    .rw-contact-list li { display: flex; align-items: center; gap: 14px; }
    .rw-contact-ico {
        width: 44px; height: 44px; border-radius: 12px; flex: 0 0 auto;
        display: grid; place-items: center; background: rgba(255,255,255,.12); color: #fff; font-size: 18px;
    }
    .rw-contact-list strong { display: block; color: #fff; font-size: 15px; margin-bottom: 2px; }
    .rw-contact-list a, .rw-contact-list span { color: #cfe0f3; font-size: 14.6px; text-decoration: none; }
    .rw-contact-list a:hover { color: #fff; text-decoration: underline; }
    .rw-form-card { background: #fff; border-radius: 20px; padding: 34px 32px; box-shadow: 0 24px 60px rgba(0,0,0,.24); }
    .rw-form-card h3 { font-size: 22px; font-weight: 800; color: #1b3a6b; margin: 0 0 6px; }
    .rw-form-card > p { color: #6b7d92; font-size: 14.6px; line-height: 1.6; margin: 0 0 24px; }
    .rw-field { margin-bottom: 17px; }
    .rw-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .rw-field label { display: block; font-size: 13.5px; font-weight: 700; color: #3d4d61; margin-bottom: 7px; }
    .rw-field input, .rw-field textarea {
        width: 100%; border: 1.5px solid #dbe6f4; border-radius: 10px;
        padding: 12px 14px; font-size: 15px; color: #22354d; background: #fbfdff;
        font-family: inherit; transition: border-color .15s ease, box-shadow .15s ease;
    }
    .rw-field input:focus, .rw-field textarea:focus {
        outline: none; border-color: #2f7fc9; box-shadow: 0 0 0 3px rgba(47,127,201,.14); background: #fff;
    }
    .rw-field textarea { min-height: 118px; resize: vertical; }
    .rw-hp { position: absolute; left: -9999px; opacity: 0; height: 0; overflow: hidden; }
    .rw-submit {
        width: 100%; border: none; cursor: pointer; margin-top: 6px;
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9); color: #fff;
        padding: 15px 28px; border-radius: 999px; font-size: 15.5px; font-weight: 700;
        transition: filter .15s ease, transform .12s ease;
    }
    .rw-submit:hover { filter: brightness(1.12); transform: translateY(-1px); }
    .rw-form-note { color: #8494a8; font-size: 13px; line-height: 1.6; margin: 15px 0 0; text-align: center; }
    .rw-flash {
        background: #e8f6ed; border: 1px solid #a9dcbe; color: #1c6b3f;
        border-radius: 12px; padding: 15px 18px; font-size: 15px; margin-bottom: 22px;
    }
    .rw-errors { background: #fdecec; border: 1px solid #f2b8b8; color: #9b2c2c; border-radius: 12px; padding: 15px 18px; margin-bottom: 22px; }
    .rw-errors ul { margin: 0; padding-left: 18px; font-size: 14.4px; }

    /* ===== Closing links ===== */
    .rw-readmore { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    .rw-readmore a {
        display: block; background: #fff; border: 1px solid #e4edf8; border-radius: 16px;
        padding: 22px 22px; text-decoration: none; transition: transform .15s ease, box-shadow .15s ease;
    }
    .rw-readmore a:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(27,58,107,.12); }
    .rw-readmore strong { display: block; color: #1b3a6b; font-size: 16.5px; font-weight: 800; margin-bottom: 6px; }
    .rw-readmore span { color: #6b7d92; font-size: 14.4px; line-height: 1.6; }

    @media (max-width: 1024px) {
        .rw-hero-grid, .rw-trust-grid, .rw-faq-grid, .rw-form-grid { grid-template-columns: 1fr; gap: 36px; }
        .rw-services, .rw-industries { grid-template-columns: repeat(2, 1fr); }
        .rw-problems, .rw-steps, .rw-readmore { grid-template-columns: 1fr; }
        .rw-hero-media { order: -1; }
    }
    @media (max-width: 620px) {
        .rw-services, .rw-industries, .rw-compare, .rw-trust-points, .rw-row-2 { grid-template-columns: 1fr; }
        .rw-section, .rw-band, .rw-form-section { padding: 54px 0; }
        .rw-form-card { padding: 26px 22px; }
    }

    /* ===== Dark theme ===== */
    html.dark-mode .rw-page { background: var(--site-bg); }
    html.dark-mode .rw-hero { background: linear-gradient(160deg, #12203a 0%, #0e1726 70%, var(--site-bg) 100%); }
    html.dark-mode .rw-section.alt { background: rgba(255,255,255,.02); }
    html.dark-mode .rw-page h2,
    html.dark-mode .rw-hero h1,
    html.dark-mode .rw-problem h3,
    html.dark-mode .rw-service h3,
    html.dark-mode .rw-step h3,
    html.dark-mode .rw-trust-card h3,
    html.dark-mode .rw-industry strong { color: #f2f7fd; }
    html.dark-mode .rw-page h2 .accent,
    html.dark-mode .rw-hero h1 .accent { color: #8fc4f0; }
    html.dark-mode .rw-lede,
    html.dark-mode .rw-hero p.sub,
    html.dark-mode .rw-problem p,
    html.dark-mode .rw-service p,
    html.dark-mode .rw-step p,
    html.dark-mode .rw-trust-card p,
    html.dark-mode .rw-industry span,
    html.dark-mode .rw-readmore span { color: #b9c9dc; }
    html.dark-mode .rw-ticks li { color: #e4eefb; }
    html.dark-mode .rw-problem,
    html.dark-mode .rw-service,
    html.dark-mode .rw-industry,
    html.dark-mode .rw-trust-card,
    html.dark-mode .rw-faq,
    html.dark-mode .rw-readmore a,
    html.dark-mode .rw-step-num {
        background: rgba(255,255,255,.045) !important;
        border-color: rgba(255,255,255,.12);
    }
    html.dark-mode .rw-step-num { color: #8fc4f0; border-color: #8fc4f0; }
    html.dark-mode .rw-faq summary { color: #f2f7fd; }
    html.dark-mode .rw-faq-body { color: #b9c9dc; }
    html.dark-mode .rw-readmore strong { color: #8fc4f0; }
    html.dark-mode .rw-btn-ghost { background: rgba(255,255,255,.06); color: #e4eefb !important; border-color: rgba(255,255,255,.22); }
    html.dark-mode .rw-compare-card { background: #101a2b; }
    html.dark-mode .rw-compare li { color: #c7d6e6; }
    html.dark-mode .rw-form-card { background: #101a2b; }
    html.dark-mode .rw-form-card h3 { color: #f2f7fd; }
    html.dark-mode .rw-field label { color: #c7d6e6; }
    html.dark-mode .rw-field input,
    html.dark-mode .rw-field textarea { background: rgba(255,255,255,.05); border-color: rgba(255,255,255,.16); color: #f2f7fd; }
</style>

<div class="rw-page">

    {{-- ============ HERO ============ --}}
    <section class="rw-hero">
        <div class="rw-wrap rw-hero-grid">
            <div>
                <span class="rw-eyebrow">Human-Written Resume &amp; CV Service</span>
                <h1>Get More Interviews With a Resume <span class="accent">Written by a Person</span></h1>
                <p class="sub">
                    You have done the work. Your resume should show it. We write it by hand,
                    target it at the job you actually want, and format it so the software that
                    reads applications first does not throw it away.
                </p>
                <ul class="rw-ticks">
                    <li><i class="icon-feather-check-circle"></i> No AI drafts and no recycled templates</li>
                    <li><i class="icon-feather-check-circle"></i> Structured so applicant tracking systems can read it</li>
                    <li><i class="icon-feather-check-circle"></i> Rewritten until you are happy with it</li>
                </ul>
                <div class="rw-cta-row">
                    <a href="#resume-enquiry" class="rw-btn rw-btn-primary">
                        <i class="icon-feather-send"></i> Send Your Details
                    </a>
                    @if($waLink)
                        <a href="{{ $waLink }}" target="_blank" rel="noopener" class="rw-btn rw-btn-wa">
                            <i class="icon-feather-message-circle"></i> Message on WhatsApp
                        </a>
                    @else
                        <a href="mailto:{{ $contactEmail }}?subject={{ rawurlencode('Resume writing enquiry') }}" class="rw-btn rw-btn-ghost">
                            <i class="icon-feather-mail"></i> Email Us
                        </a>
                    @endif
                </div>
            </div>
            <div class="rw-hero-media">
                <img src="{{ asset('public/user/images/hero-diverse-professionals.jpg') }}"
                     alt="Professionals from different industries preparing job applications"
                     width="1200" height="800" fetchpriority="high" decoding="async">
            </div>
        </div>
    </section>

    {{-- ============ THE PROBLEM ============ --}}
    <section class="rw-section alt">
        <div class="rw-wrap">
            <div class="rw-head-center">
                <span class="rw-eyebrow">The Difference</span>
                <h2>Why Good Candidates <span class="accent">Never Get a Reply</span></h2>
                <p class="rw-lede">
                    If you have applied for dozens of roles and heard nothing back, it is usually
                    not your experience that is the problem. Three things quietly stop a good
                    application before anyone reads it.
                </p>
            </div>
            <div class="rw-problems">
                <div class="rw-problem">
                    <div class="rw-problem-ico"><i class="icon-feather-filter"></i></div>
                    <h3>The software reads it first</h3>
                    <p>
                        Most medium and large employers screen applications through an applicant
                        tracking system before a person sees them. Two-column layouts, text inside
                        images, details buried in headers and unusual section names are the common
                        reasons a strong CV never reaches a human.
                    </p>
                </div>
                <div class="rw-problem">
                    <div class="rw-problem-ico"><i class="icon-feather-copy"></i></div>
                    <h3>Everyone is sending the same document</h3>
                    <p>
                        Template phrasing and AI-generated summaries read identically across
                        hundreds of applications. When a recruiter has seen the same opening line
                        forty times that morning, being technically correct is not enough to be
                        remembered.
                    </p>
                </div>
                <div class="rw-problem">
                    <div class="rw-problem-ico"><i class="icon-feather-crosshair"></i></div>
                    <h3>It is written for no one in particular</h3>
                    <p>
                        A CV that lists duties describes a job. A CV that leads with results
                        describes a person worth interviewing. Most rejected applications are not
                        under-qualified, they are just aimed at nobody.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ WHAT YOU GET ============ --}}
    <section class="rw-section">
        <div class="rw-wrap">
            <div class="rw-head-center">
                <span class="rw-eyebrow">What You Get</span>
                <h2>Everything You Need to <span class="accent">Apply With Confidence</span></h2>
                <p class="rw-lede">
                    Take the resume on its own, or the full set so your application and your online
                    profile tell the same story.
                </p>
            </div>
            <div class="rw-services">
                <div class="rw-service">
                    <div class="rw-problem-ico"><i class="icon-feather-file-text"></i></div>
                    <h3>Resume &amp; CV Writing</h3>
                    <p>Written from scratch by a person, in the format your target country expects, and structured so applicant tracking systems parse it cleanly.</p>
                </div>
                <div class="rw-service">
                    <div class="rw-problem-ico"><i class="icon-feather-mail"></i></div>
                    <h3>Cover Letters</h3>
                    <p>Written for the specific role rather than reused, connecting what you have done to what that employer is asking for.</p>
                </div>
                <div class="rw-service">
                    <div class="rw-problem-ico"><i class="icon-feather-linkedin"></i></div>
                    <h3>LinkedIn Profiles</h3>
                    <p>Headline, About section and experience rewritten so recruiters searching your skills actually find you and have a reason to message.</p>
                </div>
                <div class="rw-service">
                    <div class="rw-problem-ico"><i class="icon-feather-search"></i></div>
                    <h3>Free CV Review</h3>
                    <p>Send what you have and we will tell you what is holding it back. If it only needs small fixes, we will say so rather than sell you a rewrite.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PROCESS ============ --}}
    <section class="rw-section alt">
        <div class="rw-wrap">
            <div class="rw-head-center">
                <span class="rw-eyebrow">Simple Process</span>
                <h2>Three Steps, <span class="accent">No Endless Forms</span></h2>
                <p class="rw-lede">You talk to the person writing your resume. That is the whole process.</p>
            </div>
            <div class="rw-steps">
                <div class="rw-step">
                    <div class="rw-step-num">1</div>
                    <h3>Send your details</h3>
                    <p>Your current CV if you have one, and the kind of role and country you are targeting. Use the form below, WhatsApp or email &mdash; whichever suits you.</p>
                </div>
                <div class="rw-step">
                    <div class="rw-step-num">2</div>
                    <h3>We write and agree a date</h3>
                    <p>We confirm the scope and a delivery date before anything is paid, then a writer builds the document by hand and checks it against the roles you are applying for.</p>
                </div>
                <div class="rw-step">
                    <div class="rw-step-num">3</div>
                    <h3>You review it</h3>
                    <p>Read the draft and tell us what to change. We rewrite until it sounds like you and reads the way you want it to.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ BEFORE / AFTER ============ --}}
    <section class="rw-band">
        <div class="rw-wrap">
            <div class="rw-head-center">
                <span class="rw-eyebrow">The Transformation</span>
                <h2>What Changes When a <span class="accent">Real Writer</span> Tells Your Story</h2>
                <p class="rw-lede">The same career, described so that both the software and the person reading it can see what you are worth.</p>
            </div>
            <div class="rw-compare">
                <div class="rw-compare-card">
                    <span class="rw-compare-tag">&#10005; Before</span>
                    <ul>
                        <li class="rw-x"><i class="icon-feather-x"></i> Layouts the screening software cannot read properly</li>
                        <li class="rw-x"><i class="icon-feather-x"></i> Duties listed instead of results anyone can measure</li>
                        <li class="rw-x"><i class="icon-feather-x"></i> Opening lines lifted straight from a template</li>
                        <li class="rw-x"><i class="icon-feather-x"></i> None of the words the job advert actually uses</li>
                        <li class="rw-x"><i class="icon-feather-x"></i> A tone that reads like every other AI draft</li>
                    </ul>
                </div>
                <div class="rw-compare-card">
                    <span class="rw-compare-tag">&#10003; After</span>
                    <ul>
                        <li class="rw-tick"><i class="icon-feather-check"></i> A clean structure that parses correctly every time</li>
                        <li class="rw-tick"><i class="icon-feather-check"></i> Achievements with real numbers behind them</li>
                        <li class="rw-tick"><i class="icon-feather-check"></i> Positioning built around the role you want next</li>
                        <li class="rw-tick"><i class="icon-feather-check"></i> The language of the jobs you are applying for</li>
                        <li class="rw-tick"><i class="icon-feather-check"></i> A voice that sounds credible and unmistakably yours</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ INDUSTRIES ============ --}}
    <section class="rw-section">
        <div class="rw-wrap">
            <div class="rw-head-center">
                <span class="rw-eyebrow">Industries We Write For</span>
                <h2>Writers Who Understand <span class="accent">Your Field</span></h2>
                <p class="rw-lede">
                    A resume only sounds like an insider wrote it if the writer knows the work.
                    These are the fields we cover most often.
                </p>
            </div>
            <div class="rw-industries">
                <div class="rw-industry"><strong>IT &amp; Software</strong><span>Developers, DevOps, data, QA</span></div>
                <div class="rw-industry"><strong>Digital Marketing</strong><span>SEO, paid media, content, social</span></div>
                <div class="rw-industry"><strong>Healthcare</strong><span>Nursing, care, clinical admin</span></div>
                <div class="rw-industry"><strong>Engineering</strong><span>Civil, mechanical, electrical</span></div>
                <div class="rw-industry"><strong>Finance &amp; Accounting</strong><span>Bookkeeping, audit, analysis</span></div>
                <div class="rw-industry"><strong>Sales &amp; Customer Service</strong><span>B2B, retail, support, call centre</span></div>
                <div class="rw-industry"><strong>Skilled Trades</strong><span>Construction, drivers, warehouse</span></div>
                <div class="rw-industry"><strong>Admin &amp; Remote Work</strong><span>Virtual assistance, data entry</span></div>
            </div>
        </div>
    </section>

    {{-- ============ TRUST ============ --}}
    <section class="rw-section alt">
        <div class="rw-wrap rw-trust-grid">
            <img src="{{ asset('public/user/images/about-founders.jpg') }}"
                 alt="The JobGader team working on candidate applications"
                 width="1000" height="700" loading="lazy" decoding="async">
            <div>
                <span class="rw-eyebrow">Why Work With Us</span>
                <h2>Straight Terms, <span class="accent">No Small Print</span></h2>
                <p class="rw-lede" style="margin: 0 0 26px; max-width: none;">
                    We would rather tell you what we can honestly do than promise a number that
                    sounds impressive.
                </p>
                <div class="rw-trust-points">
                    <div class="rw-trust-card">
                        <h3>A person, start to finish</h3>
                        <p>No AI generation and no template fill. One writer works with you from the first message to the final file.</p>
                    </div>
                    <div class="rw-trust-card">
                        <h3>Built for the screening software</h3>
                        <p>Clean structure, standard section names, no text trapped in images or headers &mdash; so it survives the first automated pass.</p>
                    </div>
                    <div class="rw-trust-card">
                        <h3>Written for your market</h3>
                        <p>A US resume and a Gulf or UK CV are different documents. Tell us where you are applying and we write to that convention.</p>
                    </div>
                    <div class="rw-trust-card">
                        <h3>No guarantees we cannot keep</h3>
                        <p>Nobody can promise you a job. We commit to a delivery date and to rewriting until the document is right.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section class="rw-section">
        <div class="rw-wrap rw-faq-grid">
            <div>
                <span class="rw-eyebrow">FAQ</span>
                <h2>The Questions <span class="accent">People Ask Most</span></h2>
                <p class="rw-lede" style="margin: 0 0 24px; max-width: none;">
                    Everything worth knowing before you get started. Anything else, just ask.
                </p>
                <a href="#resume-enquiry" class="rw-btn rw-btn-primary">
                    <i class="icon-feather-help-circle"></i> Ask a Question
                </a>
            </div>
            <div>
                @foreach($rwFaqs as $i => $faq)
                    <details class="rw-faq" @if($i === 0) open @endif>
                        <summary>{{ $faq[0] }}</summary>
                        <div class="rw-faq-body">{{ $faq[1] }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ ENQUIRY FORM ============ --}}
    <section class="rw-form-section" id="resume-enquiry">
        <div class="rw-wrap rw-form-grid">
            <div class="rw-form-copy">
                <span class="rw-eyebrow">Get Started</span>
                <h2>Tell Us About the Job <span class="accent">You Want Next</span></h2>
                <p>
                    Send your details and we will come back to you on WhatsApp or by email within
                    one business day &mdash; with an honest read on what your CV needs and what it
                    would cost. No payment until the scope and the date are agreed.
                </p>
                <ul class="rw-contact-list">
                    @if($waLink)
                        <li>
                            <div class="rw-contact-ico"><i class="icon-feather-message-circle"></i></div>
                            <div>
                                <strong>WhatsApp</strong>
                                <a href="{{ $waLink }}" target="_blank" rel="noopener">Message us directly</a>
                            </div>
                        </li>
                    @endif
                    <li>
                        <div class="rw-contact-ico"><i class="icon-feather-mail"></i></div>
                        <div>
                            <strong>Email</strong>
                            <a href="mailto:{{ $contactEmail }}?subject={{ rawurlencode('Resume writing enquiry') }}">{{ $contactEmail }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="rw-contact-ico"><i class="icon-feather-clock"></i></div>
                        <div>
                            <strong>Reply time</strong>
                            <span>Within one business day</span>
                        </div>
                    </li>
                    <li>
                        <div class="rw-contact-ico"><i class="icon-feather-globe"></i></div>
                        <div>
                            <strong>Where we write for</strong>
                            <span>{{ $coverage->shortList() }} and beyond</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="rw-form-card">
                <h3>Request a Free CV Review</h3>
                <p>Four fields. Tell us the role you are targeting and we will do the rest.</p>

                @if(session('success'))
                    <div class="rw-flash">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="rw-errors">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <input type="hidden" name="source" value="resume">
                    <input type="hidden" name="subject" value="Resume writing enquiry">
                    <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">

                    {{-- Honeypots: the controller drops anything that fills these. --}}
                    <div class="rw-hp" aria-hidden="true">
                        <label>Website (leave blank)</label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                        <label>Phone (leave blank)</label>
                        <input type="text" name="hp_phone" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="rw-row-2">
                        <div class="rw-field">
                            <label for="rw-first">First Name</label>
                            <input id="rw-first" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Your first name" required>
                        </div>
                        <div class="rw-field">
                            <label for="rw-last">Last Name</label>
                            <input id="rw-last" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Your last name" required>
                        </div>
                    </div>

                    <div class="rw-row-2">
                        <div class="rw-field">
                            <label for="rw-email">Email Address</label>
                            <input id="rw-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                        </div>
                        <div class="rw-field">
                            <label for="rw-phone">WhatsApp Number <span style="font-weight:500;color:#8494a8;">(optional)</span></label>
                            <input id="rw-phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+92 300 0000000">
                        </div>
                    </div>

                    <div class="rw-field">
                        <label for="rw-role">Role &amp; Country You Are Targeting</label>
                        <input id="rw-role" type="text" name="target_role" value="{{ old('target_role') }}" placeholder="e.g. Full Stack Developer, remote US roles">
                    </div>

                    <div class="rw-field">
                        <label for="rw-message">Tell Us About Your Experience</label>
                        <textarea id="rw-message" name="message" placeholder="Your current role, how many years you have, and what you want the new CV to do." required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="rw-submit">
                        <i class="icon-feather-send"></i> Send My Details
                    </button>
                    <p class="rw-form-note">
                        We reply within one business day. Your details are only used to answer your
                        enquiry &mdash; see our <a href="{{ url('/privacy-policy') }}" style="color:#2f7fc9;">privacy policy</a>.
                    </p>
                </form>
            </div>
        </div>
    </section>

    {{-- ============ RELATED READING ============ --}}
    <section class="rw-section">
        <div class="rw-wrap">
            <div class="rw-head-center">
                <span class="rw-eyebrow">Before You Apply</span>
                <h2>Free Guides Worth <span class="accent">Reading First</span></h2>
                <p class="rw-lede">Written by the same people. No sign-up, no paywall.</p>
            </div>
            <div class="rw-readmore">
                <a href="{{ url('/blog/ats-resume-writer-jobs-in-usa') }}">
                    <strong>How ATS Screening Actually Works</strong>
                    <span>What applicant tracking systems do with your file, and the formatting choices that get a good CV filtered out.</span>
                </a>
                <a href="{{ url('/blog/full-stack-developer-jobs-in-usa') }}">
                    <strong>Which Pay Band Is Your Job Title In?</strong>
                    <span>One title can span two occupations $43,000 apart. Read the advert properly before you set your expectations.</span>
                </a>
                <a href="{{ route('blog.index') }}">
                    <strong>All Career Guides</strong>
                    <span>Salary data, visa routes and country-by-country hiring guides across every field we cover.</span>
                </a>
            </div>
        </div>
    </section>

</div>

@endsection
