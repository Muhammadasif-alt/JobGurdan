@extends('user.layouts.master')

@section('title', 'Partners — Institutions We Work With')
@section('meta_description', 'The institutions Sajjad Digital Services works with under signed memoranda of understanding, what each agreement covers, and what it does not promise.')
@section('meta_keywords', 'sajjad digital services partners, memorandum of understanding, mou partners, institutional partners, education partners, recruitment partners')
@section('og_title', 'Partners — Institutions We Work With')
@section('og_description', 'Who we have signed memoranda of understanding with, what those agreements cover, and what they do not promise.')
@section('og_image', asset('public/user/images/partners-banner.jpg'))
@section('canonical', route('partners'))

@php
    /**
     * The institutions we hold a signed memorandum of understanding with.
     *
     * @var array<int, array{name: string, kind: string, country: string, summary: string, url: ?string, signed: ?string}>
     */
    $partners = [];

    $contactEmail = config('site.contact_email');
    $waNumber = preg_replace('/\D+/', '', (string) config('site.whatsapp'));
    $waLink = $waNumber !== ''
        ? 'https://wa.me/'.$waNumber.'?text='.rawurlencode('Hi, I would like to discuss a partnership.')
        : null;

    $partnerFaqs = [
        ['What is a memorandum of understanding?', 'It is a signed statement that two organisations intend to work together, and it sets out what each side will do. It is not a contract of employment, and on its own it does not move money or oblige either side to place a single person.'],
        ['Does a partnership mean you can get me a job?', 'No. We are not an employer, a recruiter or a visa agent, and none of these agreements changes that. They let us share verified information and route enquiries to the right desk faster. The hiring or admission decision stays entirely with the institution.'],
        ['Do partners pay you to be listed here?', 'No. This page names the institutions we have an agreement with. It is not advertising space and nobody buys a place on it.'],
        ['Does a partner see my application or my data?', 'Only what you send them yourself. We do not pass your details to any partner without you asking us to, and we do not sell or share applicant data.'],
        ['Can my organisation sign one?', 'Yes, if there is something real to cooperate on. Write to us with what your organisation does and what you would want out of it, and we will tell you honestly whether it is worth either side signing anything.'],
        ['Is a partner listing an endorsement of everything they do?', 'No. It means we have an agreement with them on a defined piece of work. Check any institution against its own official channels before you pay anyone anything.'],
        ['How long does an agreement last?', 'Each one carries its own term and either side can end it. If an agreement lapses, the institution comes off this page.'],
        ['Who do I contact about a partnership?', 'Write to '.$contactEmail.' with the details. A person reads it and replies; there is no form that files your message into a queue nobody opens.'],
    ];
@endphp

@push('meta')
    <link rel="preload" as="image" href="{{ asset('public/user/images/partners-banner.jpg') }}" fetchpriority="high">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Partners — Institutions We Work With">
    <meta name="twitter:description" content="Who we have signed memoranda of understanding with, and what those agreements actually cover.">
    <meta name="twitter:image" content="{{ asset('public/user/images/partners-banner.jpg') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Sajjad Digital Services">

    {{-- JSON-LD: BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Partners", "item": "{{ route('partners') }}" }
        ]
    }
    </script>

    @if(count($partners) > 0)
        {{-- JSON-LD: the partner list itself. Built in PHP rather than with
             inline directives, because a conditional comma inside a JSON literal
             is exactly the kind of thing Blade compiles into a parse error. --}}
        @php
            $partnerListSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => 'Partner institutions',
                'url' => route('partners'),
                'numberOfItems' => count($partners),
                'itemListElement' => array_map(
                    static fn (array $partner, int $i): array => [
                        '@type' => 'ListItem',
                        'position' => $i + 1,
                        'item' => array_filter([
                            '@type' => 'Organization',
                            'name' => $partner['name'],
                            'url' => $partner['url'] ?? null,
                        ]),
                    ],
                    $partners,
                    array_keys($partners)
                ),
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($partnerListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    {{-- JSON-LD: FAQ --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($partnerFaqs as $faq)
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
    .pt-page { background: #f5f5f7; }
    .pt-wrap { max-width: 1440px; margin: 0 auto; padding: 0 30px; }
    .pt-eyebrow {
        display: inline-block; width: fit-content;
        background: #1b3a6b; color: #fff;
        font-size: 12.5px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase;
        padding: 9px 20px; border-radius: 999px; margin-bottom: 20px;
    }
    .pt-page h2 {
        font-size: clamp(27px, 3.4vw, 42px); font-weight: 800; color: #1b3a6b;
        line-height: 1.18; letter-spacing: -.6px; margin: 0 0 14px;
    }
    .pt-page h2 .accent { color: #2f7fc9; }
    .pt-lede { color: #55657a; font-size: 16.5px; line-height: 1.7; margin: 0 auto 40px; max-width: 760px; }

    /* ===== Hero. The photograph is the background of the whole band, with a
       scrim over it, so the copy sits in the middle of one block rather than
       beside a panel. ===== */
    .pt-hero {
        position: relative; padding: 104px 0 112px; text-align: center;
        background: #14294a url('{{ asset('public/user/images/partners-banner.jpg') }}') center 42% / cover no-repeat;
    }
    .pt-hero::before {
        content: ""; position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(10,24,46,.90) 0%, rgba(20,41,74,.82) 48%, rgba(10,24,46,.93) 100%);
    }
    .pt-hero > .pt-wrap { position: relative; z-index: 1; }
    .pt-hero-inner { max-width: 880px; margin: 0 auto; }
    .pt-hero .pt-eyebrow { background: rgba(255,255,255,.16); backdrop-filter: blur(2px); }
    .pt-hero h1 {
        font-size: clamp(32px, 4.4vw, 54px); font-weight: 800; color: #fff;
        line-height: 1.12; letter-spacing: -1.2px; margin: 0 0 20px;
        text-shadow: 0 2px 18px rgba(6,16,32,.45);
    }
    .pt-hero p {
        color: rgba(255,255,255,.90); font-size: 17px; line-height: 1.7;
        max-width: 720px; margin: 0 auto 30px;
    }
    .pt-hero-count {
        display: inline-flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.28);
        color: #fff; font-weight: 700; font-size: 14.5px;
        padding: 11px 22px; border-radius: 999px;
    }

    /* ===== Sections ===== */
    .pt-section { padding: 78px 0; }
    .pt-section.alt { background: #fff; }
    .pt-section-head { text-align: center; margin-bottom: 44px; }

    /* ===== Partner cards ===== */
    .pt-grid {
        display: grid; gap: 22px;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
    .pt-card {
        background: #fff; border: 1px solid #e5e5e7; border-radius: 16px;
        padding: 26px 24px 24px; display: flex; flex-direction: column; gap: 12px;
        box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .pt-section.alt .pt-card { background: #f8f9fb; }
    .pt-card-top { display: flex; align-items: center; gap: 13px; }
    .pt-card-mark {
        width: 46px; height: 46px; flex: 0 0 46px; border-radius: 12px;
        background: linear-gradient(135deg, #2f7fc9, #1b3a6b);
        color: #fff; font-weight: 800; font-size: 17px;
        display: flex; align-items: center; justify-content: center;
    }
    .pt-card h3 { font-size: 18px; font-weight: 800; color: #1b3a6b; margin: 0; line-height: 1.3; }
    .pt-card-meta { font-size: 12.5px; color: #7b8698; font-weight: 600; margin: 2px 0 0; }
    .pt-card p { color: #55657a; font-size: 14.8px; line-height: 1.65; margin: 0; }
    .pt-card-foot { margin-top: auto; padding-top: 6px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .pt-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(34,197,94,.12); color: #15803d;
        font-size: 12px; font-weight: 700; letter-spacing: .3px;
        padding: 5px 11px; border-radius: 999px;
    }
    .pt-card-foot a { color: #2f7fc9; font-weight: 700; font-size: 13.5px; text-decoration: none; }
    .pt-card-foot a:hover { text-decoration: underline; }

    /* ===== Explainer cards. Same treatment as the resume page's: an icon tile
       over the heading, four across on a wide screen, and a rule that draws in
       across the top on hover. ===== */
    .pt-points { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
    .pt-point {
        background: #fff; border: 1px solid #e4edf8; border-radius: 18px;
        padding: 30px 26px; box-shadow: 0 2px 10px rgba(27,58,107,.05);
    }
    .pt-section.alt .pt-point { background: #f5f5f7; }
    .pt-point-ico {
        width: 50px; height: 50px; border-radius: 14px; display: grid; place-items: center;
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9); color: #fff; font-size: 21px;
        margin-bottom: 18px;
    }
    .pt-point h3 { font-size: 19px; font-weight: 800; color: #1b3a6b; margin: 0 0 10px; }
    .pt-point p { color: #55657a; font-size: 15.2px; line-height: 1.68; margin: 0; }

    /* Shared card hover, as on the resume and About pages. */
    .pt-point, .pt-card {
        position: relative; overflow: hidden;
        transition: transform .2s ease, box-shadow .22s ease, border-color .22s ease;
    }
    .pt-point::before, .pt-card::before {
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: #1b3a6b; transform: scaleX(0); transform-origin: left;
        transition: transform .3s ease;
    }
    .pt-point:hover, .pt-card:hover {
        border-color: #1b3a6b;
        transform: translateY(-4px);
        box-shadow: 0 18px 36px rgba(15,23,42,.10);
    }
    .pt-point:hover::before, .pt-card:hover::before { transform: scaleX(1); }
    @media (prefers-reduced-motion: reduce) {
        .pt-point, .pt-card { transition: border-color .22s ease; }
        .pt-point:hover, .pt-card:hover { transform: none; }
    }

    /* ===== The honest limits panel ===== */
    .pt-limits {
        background: #16305a; border-radius: 18px; padding: 40px 38px; color: #fff;
    }
    .pt-limits h2 { color: #fff; margin-bottom: 10px; }
    .pt-limits > p { color: rgba(255,255,255,.82); font-size: 16px; line-height: 1.7; max-width: 720px; margin: 0 0 26px; }
    .pt-limits ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 13px; }
    .pt-limits li {
        display: flex; gap: 12px; align-items: flex-start;
        color: rgba(255,255,255,.90); font-size: 15px; line-height: 1.6;
    }
    .pt-limits li i { color: #7fb4e8; font-size: 17px; line-height: 1.4; flex: 0 0 auto; }

    /* ===== FAQ ===== */
    .pt-faq { max-width: 860px; margin: 0 auto; display: grid; gap: 12px; }
    .pt-faq details {
        background: #fff; border: 1px solid #e5e5e7; border-radius: 12px; padding: 0 20px;
    }
    .pt-section.alt .pt-faq details { background: #f8f9fb; }
    .pt-faq summary {
        cursor: pointer; list-style: none; padding: 17px 0;
        font-weight: 700; color: #1b3a6b; font-size: 16px; line-height: 1.45;
    }
    .pt-faq summary::-webkit-details-marker { display: none; }
    .pt-faq summary::after {
        content: "+"; float: right; font-weight: 700; color: #2f7fc9; font-size: 20px; line-height: 1.2;
    }
    .pt-faq details[open] summary::after { content: "\2212"; }
    .pt-faq-answer { color: #55657a; font-size: 15px; line-height: 1.7; padding: 0 0 18px; }

    /* ===== CTA ===== */
    .pt-cta { text-align: center; }
    .pt-cta-buttons { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-top: 26px; }
    .pt-btn {
        display: inline-flex; align-items: center; gap: 9px;
        height: 50px; padding: 0 26px; border-radius: 10px;
        font-weight: 700; font-size: 15px; text-decoration: none;
    }
    .pt-btn-primary { background: #1b3a6b; color: #fff; }
    .pt-btn-primary:hover { background: #2f7fc9; color: #fff; }
    .pt-btn-ghost { background: #fff; color: #1b3a6b; border: 1px solid #d7dbe3; }
    .pt-btn-ghost:hover { border-color: #2f7fc9; color: #2f7fc9; }

    @media (max-width: 1200px) {
        .pt-points { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 991px) {
        .pt-hero { padding: 74px 0 78px; }
        .pt-section { padding: 56px 0; }
        .pt-limits { padding: 30px 24px; }
    }
    @media (max-width: 575px) {
        .pt-wrap { padding: 0 16px; }
        .pt-hero { padding: 58px 0 62px; }
        .pt-hero p { font-size: 15px; }
        .pt-section { padding: 44px 0; }
        .pt-grid, .pt-points { grid-template-columns: 1fr; }
        .pt-point { padding: 24px 22px; }
        .pt-btn { width: 100%; justify-content: center; }
    }

    /* Dark mode follows the rest of the site: the page background and the cards
       take the shared tokens so nothing stays white against a dark page. */
    html.dark-mode .pt-page { background: var(--site-bg) !important; }
    html.dark-mode .pt-section.alt { background: var(--site-card) !important; }
    html.dark-mode .pt-page h2,
    html.dark-mode .pt-card h3,
    html.dark-mode .pt-point h3,
    html.dark-mode .pt-faq summary { color: var(--site-text) !important; }
    html.dark-mode .pt-lede,
    html.dark-mode .pt-card p,
    html.dark-mode .pt-point p,
    html.dark-mode .pt-faq-answer { color: var(--site-muted) !important; }
    html.dark-mode .pt-card,
    html.dark-mode .pt-point,
    html.dark-mode .pt-faq details,
    html.dark-mode .pt-section.alt .pt-card,
    html.dark-mode .pt-section.alt .pt-point,
    html.dark-mode .pt-section.alt .pt-faq details {
        background: var(--site-card) !important;
        border-color: var(--site-card-bd) !important;
    }
    html.dark-mode .pt-point::before,
    html.dark-mode .pt-card::before { background: #4da3e8 !important; }
    html.dark-mode .pt-point:hover,
    html.dark-mode .pt-card:hover { border-color: #4da3e8 !important; }
    html.dark-mode .pt-btn-ghost {
        background: var(--site-card) !important;
        color: var(--site-text) !important;
        border-color: var(--site-card-bd) !important;
    }
</style>

<div class="pt-page">

    <section class="pt-hero">
        <div class="pt-wrap">
            <div class="pt-hero-inner">
                <span class="pt-eyebrow">Partners</span>
                <h1>The Institutions We Work With</h1>
                <p>We sign a memorandum of understanding when there is something concrete to cooperate on:
                    verified openings, admissions information, or a clear route for enquiries to reach the
                    right desk. Each agreement is written down, and each one has limits. Both are on this page.</p>
                @if(count($partners) > 0)
                    <span class="pt-hero-count">
                        <i class="icon-feather-file-text"></i>
                        {{ count($partners) }} signed memoranda of understanding
                    </span>
                @endif
            </div>
        </div>
    </section>

    @if(count($partners) > 0)
        <section class="pt-section">
            <div class="pt-wrap">
                <div class="pt-section-head">
                    <h2>Our <span class="accent">Partners</span></h2>
                    <p class="pt-lede">Every organisation below has a signed agreement with us. If one lapses,
                        it comes off this page rather than staying here for decoration.</p>
                </div>

                <div class="pt-grid">
                    @foreach($partners as $partner)
                        <article class="pt-card">
                            <div class="pt-card-top">
                                <span class="pt-card-mark" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($partner['name'], 0, 2)) }}</span>
                                <div>
                                    <h3>{{ $partner['name'] }}</h3>
                                    <p class="pt-card-meta">{{ $partner['kind'] }} &middot; {{ $partner['country'] }}</p>
                                </div>
                            </div>
                            <p>{{ $partner['summary'] }}</p>
                            <div class="pt-card-foot">
                                <span class="pt-badge"><i class="icon-feather-check"></i> {{ trim('MOU signed '.($partner['signed'] ?? '')) }}</span>
                                @if(! empty($partner['url']))
                                    <a href="{{ $partner['url'] }}" target="_blank" rel="noopener nofollow">Official site</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="pt-section alt">
        <div class="pt-wrap">
            <div class="pt-section-head">
                <span class="pt-eyebrow">The Agreement</span>
                <h2>What a Memorandum of Understanding <span class="accent">Actually Is</span></h2>
                <p class="pt-lede">The phrase gets used loosely enough that it is worth being plain about it,
                    because a lot of people are asked to pay money on the strength of one.</p>
            </div>

            <div class="pt-points">
                <div class="pt-point">
                    <div class="pt-point-ico"><i class="icon-feather-file-text"></i></div>
                    <h3>A written intention to cooperate</h3>
                    <p>It records that two organisations mean to work together and sets out who does what.
                        It is signed by both sides, and it names the work rather than gesturing at it.</p>
                </div>
                <div class="pt-point">
                    <div class="pt-point-ico"><i class="icon-feather-briefcase"></i></div>
                    <h3>Not a job offer</h3>
                    <p>No memorandum hires anybody. Employers and universities decide who they take, on their
                        own criteria, through their own process. Nothing we sign changes that.</p>
                </div>
                <div class="pt-point">
                    <div class="pt-point-ico"><i class="icon-feather-credit-card"></i></div>
                    <h3>Not a payment route</h3>
                    <p>We never collect fees on a partner's behalf, and a partner never collects on ours.
                        If anyone asks you to pay us for a place, a visa or an admission, it is not us.</p>
                </div>
                <div class="pt-point">
                    <div class="pt-point-ico"><i class="icon-feather-clock"></i></div>
                    <h3>Time-limited, and revocable</h3>
                    <p>Each agreement carries its own term, and either side can end it. That is normal and it
                        is the reason this page can go down as well as up.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-section">
        <div class="pt-wrap">
            <div class="pt-limits">
                <h2>What These Agreements Do Not Promise You</h2>
                <p>We are a third-party information site. We are not an employer, a recruiter or a visa agent,
                    and a signed partnership does not quietly make us one.</p>
                <ul>
                    <li><i class="icon-feather-x-circle"></i><span>No partner guarantees you a job, a seat or a visa, and neither do we.</span></li>
                    <li><i class="icon-feather-x-circle"></i><span>We do not collect applications, and we do not decide who is hired or admitted.</span></li>
                    <li><i class="icon-feather-x-circle"></i><span>We do not pass your details to a partner unless you ask us to.</span></li>
                    <li><i class="icon-feather-x-circle"></i><span>We are not paid to list anyone here, and a place on this page cannot be bought.</span></li>
                    <li><i class="icon-feather-x-circle"></i><span>Check every institution against its own official channels before paying anyone anything.</span></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="pt-section alt">
        <div class="pt-wrap">
            <div class="pt-section-head">
                <h2>Common <span class="accent">Questions</span></h2>
            </div>
            <div class="pt-faq">
                @foreach($partnerFaqs as $faq)
                    <details>
                        <summary>{{ $faq[0] }}</summary>
                        <div class="pt-faq-answer">{{ $faq[1] }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pt-section">
        <div class="pt-wrap pt-cta">
            <h2>Want to Work With Us?</h2>
            <p class="pt-lede">Tell us what your organisation does and what you would want out of an agreement.
                If there is nothing real to cooperate on we will say so rather than sign something for the logo.</p>
            <div class="pt-cta-buttons">
                <a class="pt-btn pt-btn-primary" href="{{ route('contact.us') }}">
                    <i class="icon-feather-mail"></i> Contact Us
                </a>
                @if($waLink)
                    <a class="pt-btn pt-btn-ghost" href="{{ $waLink }}" target="_blank" rel="noopener">
                        <i class="icon-feather-message-circle"></i> Message on WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </section>

</div>

@endsection
