@extends('user.layouts.master')

@section('title', 'Browse Job Seekers — Hire Verified Talent | Jobs in USA')
@section('meta_description', 'Browse thousands of active U.S. job seekers actively looking for work. Filter by skill, location and experience to find your next great hire on Jobs in USA.')

@section('content')

@php
    use App\Http\Controllers\Public\JobSeekerPublicController;
@endphp

<style>
    .js-page { background: #f5f5f7; padding: 0; }

    /* === Hero === */
    .js-hero {
        position: relative;
        background: #0a0a0a;
        color: #fff;
        padding: 80px 0 90px;
        overflow: hidden;
        text-align: center;
    }
    .js-hero::before, .js-hero::after {
        content: ""; position: absolute;
        border-radius: 50%; filter: blur(80px);
        opacity: .35; pointer-events: none;
    }
    .js-hero::before { width: 380px; height: 380px; background: #ff5722; top: -120px; right: -100px; }
    .js-hero::after  { width: 340px; height: 340px; background: #5e2bff; bottom: -120px; left: -100px; }
    .js-hero .container { position: relative; z-index: 2; }
    .js-hero .eyebrow {
        display: inline-block;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.20);
        font-size: 12px; font-weight: 700;
        letter-spacing: 1.4px; text-transform: uppercase;
        padding: 7px 16px; border-radius: 999px;
        margin-bottom: 22px; backdrop-filter: blur(8px);
    }
    .js-hero h1 {
        font-size: clamp(32px, 4vw, 52px);
        font-weight: 800; line-height: 1.1; letter-spacing: -.8px;
        margin: 0 0 16px;
    }
    .js-hero h1 .accent {
        background: linear-gradient(90deg, #ffd54f, #ff7043);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .js-hero p { font-size: 17px; color: rgba(255,255,255,.85); line-height: 1.65; max-width: 660px; margin: 0 auto 30px; }

    .js-search-box {
        max-width: 560px; margin: 0 auto;
        background: #fff; border-radius: 14px;
        display: flex; gap: 8px;
        padding: 8px;
        box-shadow: 0 24px 48px rgba(0,0,0,.30);
    }
    .js-search-box input {
        flex: 1; height: 50px; border: none; outline: none;
        padding: 0 16px; font-size: 15px; color: #0f172a;
        font-family: inherit; background: transparent;
    }
    .js-search-box button {
        height: 50px; padding: 0 26px;
        background: #0a0a0a; color: #fff; border: none;
        border-radius: 10px; font-weight: 700; font-size: 14.5px;
        cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        transition: background .15s ease;
    }
    .js-search-box button:hover { background: #1a1a1a; }

    .js-hero-stats {
        display: flex; justify-content: center; gap: 40px;
        margin-top: 30px; padding-top: 24px;
        border-top: 1px solid rgba(255,255,255,.15);
        flex-wrap: wrap;
    }
    .js-hero-stats .stat strong { display: block; font-size: 22px; font-weight: 800; color: #fff; line-height: 1.1; }
    .js-hero-stats .stat span { font-size: 12px; color: rgba(255,255,255,.65); text-transform: uppercase; letter-spacing: 1.2px; }

    /* === Cards section === */
    .js-section { padding: 70px 0 30px; }
    .js-section-head { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 28px; }
    .js-section-head h2 { font-size: clamp(22px, 2.5vw, 30px); font-weight: 800; color: #0a0a0a; letter-spacing: -.5px; margin: 0; }
    .js-section-head p  { color: #555; font-size: 15px; margin: 6px 0 0; }
    .js-section-head .count-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid #e5e5e7;
        padding: 6px 14px; border-radius: 999px;
        font-size: 13px; font-weight: 600; color: #0a0a0a;
    }

    .js-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    @media (max-width: 991px) { .js-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .js-grid { grid-template-columns: 1fr; } }

    .js-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 22px 22px 18px;
        position: relative;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        text-decoration: none !important;
        color: inherit !important;
        display: flex; flex-direction: column;
    }
    .js-card:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(15,23,42,.08); border-color: #0a0a0a; }
    .js-card-head { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
    .js-avatar {
        width: 54px; height: 54px; border-radius: 14px;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 18px; letter-spacing: -.5px;
        flex-shrink: 0; box-shadow: 0 6px 14px rgba(10,10,10,.20);
        overflow: hidden;
    }
    .js-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .js-card-name { font-weight: 700; font-size: 16px; color: #0a0a0a; line-height: 1.2; margin: 0 0 3px; }
    .js-card-loc { font-size: 12.5px; color: #6b7280; display: inline-flex; align-items: center; gap: 5px; }
    .js-card-loc i { font-size: 13px; color: #ff5722; }

    .js-card-headline {
        font-size: 14px; color: #374151; line-height: 1.55;
        margin: 0 0 14px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        min-height: 44px;
    }
    .js-skills { display: flex; flex-wrap: wrap; gap: 6px; margin: 0 0 16px; }
    .js-skill {
        font-size: 11.5px; font-weight: 600; color: #0a0a0a;
        background: #f3f4f6; border: 1px solid #e5e7eb;
        padding: 4px 10px; border-radius: 999px;
    }

    .js-card-meta {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 14px; border-top: 1px solid #f3f4f6;
        margin-top: auto;
    }
    .js-card-exp { font-size: 12px; color: #6b7280; display: inline-flex; align-items: center; gap: 5px; }
    .js-card-exp i { color: #5e2bff; font-size: 13px; }
    .js-view-link {
        font-size: 13px; font-weight: 700; color: #0a0a0a;
        display: inline-flex; align-items: center; gap: 4px;
        transition: gap .15s ease;
    }
    .js-card:hover .js-view-link { gap: 8px; }

    /* Empty state */
    .js-empty { text-align: center; padding: 80px 20px; background: #fff; border-radius: 16px; border: 1px solid #ececec; }
    .js-empty i { font-size: 56px; color: #d1d5db; }
    .js-empty h4 { color: #0a0a0a; font-weight: 700; margin: 14px 0 6px; font-size: 19px; }
    .js-empty p { color: #6b7280; margin: 0; font-size: 14.5px; }

    /* Pagination */
    .js-pagination { margin-top: 36px; }

    /* === FAQs — 2-column layout (heading+CTA left, accordion right) === */
    .js-faq-section { background: #fff; padding: 70px 0; border-top: 1px solid #ececec; border-bottom: 1px solid #ececec; }
    .js-faq-grid {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 70px;
        align-items: start;
    }
    @media (max-width: 991px) {
        .js-faq-grid { grid-template-columns: 1fr; gap: 30px; }
    }
    .js-faq-head { text-align: left; position: sticky; top: 90px; }
    .js-faq-head .eyebrow {
        display: inline-block; background: rgba(255,138,0,.10);
        color: #ff8a00; font-weight: 800; font-size: 11px;
        padding: 5px 14px; border-radius: 999px;
        letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 14px;
    }
    .js-faq-head h2 {
        font-size: clamp(26px, 3vw, 38px); font-weight: 800;
        color: #0a0a0a; line-height: 1.15; letter-spacing: -.5px;
        margin: 0 0 14px;
    }
    .js-faq-head h2 .accent {
        background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
        -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
    }
    .js-faq-head p { color: #555; font-size: 16px; line-height: 1.65; margin: 0 0 22px; }
    .js-faq-cta {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #ff8a00, #ff5722); color: #fff !important;
        padding: 12px 22px; border-radius: 10px;
        font-weight: 700; font-size: 14px; text-decoration: none;
        box-shadow: 0 8px 20px rgba(255,138,0,.30);
        transition: all .2s ease;
    }
    .js-faq-cta:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(255,138,0,.40); }
    .js-faqs { max-width: none; margin: 0; }
    .js-faq {
        background: #fff; border: 1px solid #ececec;
        border-radius: 14px; padding: 0;
        margin-bottom: 12px;
        overflow: hidden;
        transition: border-color .15s ease;
    }
    .js-faq[open] { border-color: #0a0a0a; box-shadow: 0 8px 18px rgba(10,10,10,.06); }
    .js-faq summary {
        list-style: none; cursor: pointer;
        padding: 18px 22px;
        font-weight: 700; font-size: 15.5px; color: #0a0a0a;
        display: flex; justify-content: space-between; align-items: center;
        gap: 12px;
    }
    .js-faq summary::-webkit-details-marker { display: none; }
    .js-faq summary::after {
        content: "+"; font-size: 22px; font-weight: 400; color: #0a0a0a;
        transition: transform .2s ease;
    }
    .js-faq[open] summary::after { content: "×"; transform: rotate(0deg); }
    .js-faq-body { padding: 0 22px 18px; color: #555; font-size: 14.5px; line-height: 1.7; }

    /* === CTA === */
    .js-cta-section { padding: 70px 0; background: #f5f5f7; }
    .js-cta-card {
        position: relative;
        background: #0a0a0a; color: #fff;
        border-radius: 24px; padding: 60px 40px;
        overflow: hidden; text-align: center;
    }
    .js-cta-card::before, .js-cta-card::after {
        content: ""; position: absolute;
        border-radius: 50%; filter: blur(80px); opacity: .35;
        pointer-events: none;
    }
    .js-cta-card::before { width: 360px; height: 360px; background: #ff5722; top: -120px; right: -100px; }
    .js-cta-card::after  { width: 320px; height: 320px; background: #5e2bff; bottom: -120px; left: -100px; }
    .js-cta-card > * { position: relative; z-index: 2; }
    .js-cta-card h2 {
        font-size: clamp(26px, 3.4vw, 40px);
        font-weight: 800; letter-spacing: -.6px; margin: 0 0 14px;
    }
    .js-cta-card h2 .accent {
        background: linear-gradient(90deg, #ffd54f, #ff7043);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .js-cta-card p { font-size: 16px; color: rgba(255,255,255,.85); max-width: 580px; margin: 0 auto 26px; line-height: 1.65; }
    .js-cta-actions { display: inline-flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
    .js-cta-btn-primary, .js-cta-btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 14px 28px; border-radius: 12px;
        font-weight: 700; font-size: 15px;
        text-decoration: none !important;
        transition: transform .15s ease;
    }
    .js-cta-btn-primary { background: #fff; color: #0a0a0a !important; }
    .js-cta-btn-primary:hover { transform: translateY(-2px); }
    .js-cta-btn-outline { background: transparent; color: #fff !important; border: 1.5px solid rgba(255,255,255,.30); }
    .js-cta-btn-outline:hover { background: rgba(255,255,255,.10); border-color: rgba(255,255,255,.50); }

    /* ===== Job-Seeker Spotlight (replaces old employer CTA) ===== */
    .js-spotlight-section { padding: 60px 0; background: #fff; border-top: 1px solid #ececec; }
    .js-spotlight-grid {
        display: grid; grid-template-columns: 1fr 1.1fr;
        gap: 60px; align-items: center;
    }
    @media (max-width: 991px) { .js-spotlight-grid { grid-template-columns: 1fr; gap: 40px; } }

    .js-spotlight-visual {
        position: relative; border-radius: 22px; overflow: hidden;
        aspect-ratio: 5 / 6; max-width: 440px; max-height: 480px;
        margin: 0 auto;
        box-shadow: 0 30px 60px rgba(15,23,42,.15);
    }
    @media (max-width: 991px) { .js-spotlight-visual { max-width: 100%; max-height: 380px; aspect-ratio: 4 / 3; } }
    @media (max-width: 575px) { .js-spotlight-visual { max-height: 320px; } }
    .js-spotlight-visual img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .js-spotlight-pill {
        position: absolute; bottom: 22px; left: 22px;
        background: #fff; color: #0a0a0a;
        padding: 8px 14px; border-radius: 999px;
        font-size: 12.5px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
        box-shadow: 0 8px 20px rgba(15,23,42,.18);
    }
    .js-spotlight-pill i { color: #10b981; font-size: 14px; }

    .js-spotlight-content .eyebrow {
        display: inline-block;
        background: rgba(255,138,0,.10); color: #ff8a00;
        font-size: 11px; font-weight: 800; letter-spacing: 1.5px;
        text-transform: uppercase; padding: 6px 14px; border-radius: 999px;
        margin-bottom: 16px;
    }
    .js-spotlight-content h2 {
        font-size: clamp(28px, 3.2vw, 42px); font-weight: 800;
        color: #0a0a0a; line-height: 1.15; letter-spacing: -.6px;
        margin: 0 0 16px;
    }
    .js-spotlight-content h2 .accent {
        background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .js-spotlight-content > p {
        color: #555; font-size: 16px; line-height: 1.7;
        margin: 0 0 22px; max-width: 580px;
    }
    .js-spotlight-list { list-style: none; padding: 0; margin: 0 0 28px; display: grid; gap: 10px; }
    .js-spotlight-list li {
        display: flex; align-items: flex-start; gap: 10px;
        font-size: 14.5px; color: #1f2937; line-height: 1.55;
    }
    .js-spotlight-list li i {
        flex-shrink: 0; margin-top: 2px;
        width: 22px; height: 22px; border-radius: 50%;
        background: rgba(255,138,0,.12); color: #ff8a00;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 800;
    }

    .js-spotlight-actions { display: flex; flex-wrap: wrap; gap: 12px; }
    .js-spotlight-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 24px; border-radius: 12px;
        font-weight: 700; font-size: 14.5px;
        text-decoration: none !important;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .js-spotlight-btn.primary {
        background: linear-gradient(135deg, #ff8a00, #ff5722);
        color: #fff !important;
        box-shadow: 0 8px 18px rgba(255,138,0,.30);
    }
    .js-spotlight-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(255,138,0,.45); }
    .js-spotlight-btn.outline {
        background: #fff;
        color: #0a0a0a !important;
        border: 1.5px solid #0a0a0a;
    }
    .js-spotlight-btn.outline:hover { background: #0a0a0a; color: #fff !important; }
</style>

<div class="js-page">
    {{-- ============ HERO ============ --}}
    <section class="js-hero">
        <div class="container">
            <span class="eyebrow" data-aos="fade-down" data-aos-duration="600"><i class="icon-feather-users"></i> Active U.S. Talent Pool</span>
            <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Meet professionals <span class="accent">ready to work</span></h1>
            <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">Browse thousands of verified job seekers across all 50 states. Filter by skill, location, and experience — and connect with your next great hire today.</p>

            <form action="{{ route('job-seekers.index') }}" method="GET" class="js-search-box"
                  data-aos="fade-up" data-aos-duration="700" data-aos-delay="400">
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Search by name, username, or skill…"
                       autocomplete="off">
                <button type="submit"><i class="icon-feather-search"></i> Search</button>
            </form>

            <div class="js-hero-stats">
                <div class="stat"><strong>{{ number_format($stats['total_seekers'] ?? 0) }}+</strong><span>Active Job Seekers</span></div>
                <div class="stat"><strong>{{ number_format($stats['open_jobs'] ?? 0) }}+</strong><span>Open Jobs</span></div>
                <div class="stat"><strong>{{ number_format($stats['companies'] ?? 0) }}+</strong><span>Hiring Companies</span></div>
            </div>
        </div>
    </section>

    {{-- ============ CARDS GRID ============ --}}
    <section class="js-section">
        <div class="container">
            <div class="js-section-head">
                <div>
                    <h2>{{ $search ? 'Results for "'.$search.'"' : 'Featured Job Seekers' }}</h2>
                    <p>Real candidates actively looking for opportunities right now.</p>
                </div>
                <span class="count-pill"><i class="icon-feather-user-check"></i> {{ number_format($seekers->total()) }} talent profiles</span>
            </div>

            @if($seekers->count())
                <div class="js-grid">
                    @foreach($seekers as $seeker)
                        @php
                            $profile = JobSeekerPublicController::profileFor($seeker);
                            $initials = collect(preg_split('/\s+/', trim($seeker->name)))
                                ->filter()->take(2)
                                ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                ->implode('');
                        @endphp
                        <a href="{{ route('job-seekers.show', $seeker->username) }}" class="js-card">
                            <div class="js-card-head">
                                <div class="js-avatar">
                                    @if($seeker->profile_photo_path)
                                        <img src="{{ asset('public/storage/' . $seeker->profile_photo_path) }}" alt="{{ $seeker->name }}" loading="lazy">
                                    @else
                                        {{ $initials ?: 'U' }}
                                    @endif
                                </div>
                                <div>
                                    <h3 class="js-card-name">{{ $seeker->name }}</h3>
                                    <span class="js-card-loc"><i class="icon-feather-map-pin"></i> {{ $profile['city'] }}</span>
                                </div>
                            </div>
                            <p class="js-card-headline">{{ $profile['headline'] }}</p>
                            <div class="js-skills">
                                @foreach(array_slice($profile['skills'], 0, 4) as $skill)
                                    <span class="js-skill">{{ $skill }}</span>
                                @endforeach
                            </div>
                            <div class="js-card-meta">
                                <span class="js-card-exp"><i class="icon-feather-briefcase"></i> {{ $profile['experience_years'] }} yr exp · {{ $profile['open_to'] }}</span>
                                <span class="js-view-link">View Profile <i class="icon-feather-arrow-right"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="js-pagination">
                    {{ $seekers->onEachSide(1)->links() }}
                </div>
            @else
                <div class="js-empty">
                    <i class="icon-feather-users"></i>
                    <h4>No job seekers match your search</h4>
                    <p>Try a different keyword, or <a href="{{ route('job-seekers.index') }}" style="color:#0a0a0a;font-weight:600;">browse all profiles</a>.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ============ FAQs ============ --}}
    <section class="js-faq-section">
        <div class="container">
            <div class="js-faq-grid">
                <div class="js-faq-head">
                    <span class="eyebrow">FAQ</span>
                    <h2>Common questions from <span class="accent">employers</span></h2>
                    <p>Everything you need to know about hiring through Jobs in USA — from contact options to candidate verification and pricing.</p>
                    <a href="{{ route('register') }}" class="js-faq-cta">Get Started Free <i class="icon-feather-arrow-right"></i></a>
                </div>

                <div class="js-faqs">
                <details class="js-faq">
                    <summary>How do I contact a job seeker?</summary>
                    <div class="js-faq-body">Click any profile card to open the full profile page. From there, you can review their skills and preferences, then either post a matching job or contact them directly through your company dashboard.</div>
                </details>
                <details class="js-faq">
                    <summary>Are these candidates verified?</summary>
                    <div class="js-faq-body">Every account on Jobs in USA goes through email verification before profiles are listed publicly. Our team also flags suspicious accounts to keep the talent pool clean and trustworthy.</div>
                </details>
                <details class="js-faq">
                    <summary>Is there a cost to browse job seekers?</summary>
                    <div class="js-faq-body">Browsing the talent directory is completely free. To unlock direct messaging, post unlimited jobs, and access advanced filters, you can upgrade your company account at any time.</div>
                </details>
                <details class="js-faq">
                    <summary>Can I filter candidates by skill or location?</summary>
                    <div class="js-faq-body">Yes — use the search bar at the top to find candidates by name, username, or specific skills. Each card also shows the candidate's preferred U.S. city so you can spot regional matches at a glance.</div>
                </details>
                <details class="js-faq">
                    <summary>How fresh are these profiles?</summary>
                    <div class="js-faq-body">Profiles are sorted by most recently active. New candidates join the platform every day, and inactive profiles are filtered out automatically so you only see talent that's currently available.</div>
                </details>
                <details class="js-faq">
                    <summary>Can job seekers reach out to me first?</summary>
                    <div class="js-faq-body">Absolutely. When you post a job, candidates can apply directly through the listing — those applications appear in your company dashboard alongside the candidate's profile and contact info.</div>
                </details>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         Why Job Seekers Choose Us — 4-card horizontal row
         ============================================================ --}}
    <section class="jsk-why-section">
        <div class="container">
            <header class="jsk-section-head">
                <span class="eyebrow">Why Job Seekers Choose Us</span>
                <h2>Trusted by <span class="accent">millions of Americans</span></h2>
                <p>From first-time applicants to career changers, Jobs in USA helps you find the right role faster — with verified employers and tools built for U.S. talent.</p>
            </header>
            <div class="jsk-why-grid">
                <div class="jsk-why-card">
                    <div class="jsk-why-ico"><i class="icon-feather-shield"></i></div>
                    <h3>Verified Employers</h3>
                    <p>Every company on our platform is screened by our trust team — no scams, no ghost listings, no fake recruiters.</p>
                </div>
                <div class="jsk-why-card">
                    <div class="jsk-why-ico"><i class="icon-feather-zap"></i></div>
                    <h3>Fast Matching</h3>
                    <p>Our AI matches your profile to roles in under 60 seconds. The average user gets their first interview within 14 days.</p>
                </div>
                <div class="jsk-why-card">
                    <div class="jsk-why-ico"><i class="icon-feather-dollar-sign"></i></div>
                    <h3>Always Free</h3>
                    <p>100% free for job seekers — no premium tiers, no hidden charges, no upsells. Your data is never sold.</p>
                </div>
                <div class="jsk-why-card">
                    <div class="jsk-why-ico"><i class="icon-feather-bell"></i></div>
                    <h3>Instant Job Alerts</h3>
                    <p>Set your preferences once and receive real-time alerts when a matching role goes live. Be first to apply.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         Success Rate / Why Job Search With Us — SEO row
         ============================================================ --}}
    <section class="jsk-seo-section">
        <div class="container">
            <div class="jsk-seo-grid">
                <div class="jsk-seo-content">
                    <span class="eyebrow">Real Results</span>
                    <h2>The job search platform that <span class="accent">actually works</span></h2>
                    <p class="jsk-seo-lead">
                        Our verified-employer model and AI matching have helped thousands of Americans land interviews faster than traditional job boards.
                    </p>
                    <p>
                        Most job boards flood you with stale postings, recycled recruiter listings, and roles that are already filled. We do the opposite — we verify every employer, deduplicate listings across feeds, and surface the freshest opportunities first.
                        Whether you're a recent graduate, a career changer, or a senior professional looking for your next move, our platform is built to help you skip the noise and apply to roles that hire.
                    </p>
                    <ul class="jsk-seo-list">
                        <li><i class="icon-feather-check-circle"></i> <strong>14 days</strong> — average time to first interview</li>
                        <li><i class="icon-feather-check-circle"></i> <strong>68,000+</strong> verified U.S. jobs updated daily</li>
                        <li><i class="icon-feather-check-circle"></i> <strong>3X higher</strong> response rate than legacy job boards</li>
                        <li><i class="icon-feather-check-circle"></i> <strong>One profile</strong> — apply to unlimited roles</li>
                    </ul>
                </div>
                <div class="jsk-seo-visual">
                    <img src="{{ asset('public/user/images/hero-diverse-professionals.webp') }}"
                         onerror="this.onerror=null;this.src='{{ asset('public/user/images/hero-diverse-professionals.jpg') }}'"
                         alt="U.S. job seekers find verified roles on Jobs in USA" loading="lazy" decoding="async">
                    <div class="jsk-float-badge tl">
                        <div class="ico green"><i class="icon-feather-check-circle"></i></div>
                        <div class="text">
                            <strong>92%</strong>
                            <span>Apply success rate</span>
                        </div>
                    </div>
                    <div class="jsk-float-badge br">
                        <div class="ico"><i class="icon-feather-trending-up"></i></div>
                        <div class="text">
                            <strong>14 days</strong>
                            <span>To first interview</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         Trusted Companies / Logos / Stats trust bar
         ============================================================ --}}
    <section class="jsk-trust-section">
        <div class="container">
            <header class="jsk-section-head">
                <span class="eyebrow">Trusted Companies Hire Here</span>
                <h2>From local startups to <span class="accent">Fortune 500</span></h2>
                <p>Verified employers across all U.S. industries actively recruit on Jobs in USA — your next role is just one application away.</p>
            </header>
            <div class="jsk-trust-grid">
                <div class="jsk-trust-stat"><strong>10,000+</strong><span>Hiring Employers</span></div>
                <div class="jsk-trust-stat"><strong>68,000+</strong><span>Open Roles</span></div>
                <div class="jsk-trust-stat"><strong>50</strong><span>U.S. States</span></div>
                <div class="jsk-trust-stat"><strong>2M+</strong><span>Active Job Seekers</span></div>
                <div class="jsk-trust-stat"><strong>92%</strong><span>Success Rate</span></div>
            </div>
        </div>
    </section>

    {{-- ============ Job Seeker Spotlight (replaced employer CTA) ============ --}}
    <section class="js-spotlight-section">
        <div class="container">
            <div class="js-spotlight-grid">
                <div class="js-spotlight-visual">
                    <img src="{{ asset('public/user/images/seo-jobseekers.webp') }}"
                         alt="Confident job seeker walking out of a successful interview"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('public/user/images/seo-jobseekers.jpg') }}'">
                    <span class="js-spotlight-pill"><i class="icon-feather-check-circle"></i> Verified placements</span>
                </div>
                <div class="js-spotlight-content">
                    <span class="eyebrow">Your Career, Your Pace</span>
                    <h2>Land your <span class="accent">dream U.S. role</span> — faster</h2>
                    <p>Create a free profile in under two minutes, upload your resume, and let verified U.S. employers find you. Most candidates get their first interview invitation within 14 days.</p>
                    <ul class="js-spotlight-list">
                        <li><i class="icon-feather-check"></i> 100% free for job seekers — no hidden fees, ever</li>
                        <li><i class="icon-feather-check"></i> AI-matched roles based on your skills &amp; goals</li>
                        <li><i class="icon-feather-check"></i> Real-time alerts the moment a matching job goes live</li>
                        <li><i class="icon-feather-check"></i> Your data stays yours — never sold to third parties</li>
                    </ul>
                    <div class="js-spotlight-actions">
                        <a href="{{ route('register') }}" class="js-spotlight-btn primary">
                            Create Free Profile <i class="icon-feather-arrow-right"></i>
                        </a>
                        <a href="{{ route('jobs.index') }}" class="js-spotlight-btn outline">
                            Browse Open Jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* ===== Section base ===== */
.jsk-why-section, .jsk-seo-section, .jsk-trust-section {
    padding: 70px 0;
    position: relative;
}
.jsk-why-section { background: #fafafa; border-top: 1px solid #ececec; }
.jsk-seo-section { background: #fff; }
.jsk-trust-section {
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    color: #fff;
    padding: 70px 0;
}

.jsk-section-head { text-align: center; max-width: 720px; margin: 0 auto 44px; }
.jsk-section-head .eyebrow {
    display: inline-block; background: rgba(255,138,0,.10); color: #ff8a00;
    font-size: 11px; font-weight: 800; padding: 5px 14px; border-radius: 999px;
    letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 14px;
}
.jsk-section-head h2 {
    font-size: clamp(26px, 3.2vw, 38px); font-weight: 800;
    color: #0a0a0a; line-height: 1.15; letter-spacing: -.6px; margin: 0 0 12px;
}
.jsk-section-head h2 .accent {
    background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
    -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
}
.jsk-section-head p { color: #555; font-size: 16px; line-height: 1.65; margin: 0; }
.jsk-trust-section .jsk-section-head h2 { color: #fff; }
.jsk-trust-section .jsk-section-head p { color: #cbd5e1; }
.jsk-trust-section .jsk-section-head .eyebrow {
    background: rgba(255,255,255,.08); color: #ffab40; border: 1px solid rgba(255,255,255,.15);
}

/* ===== Why grid ===== */
.jsk-why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
@media (max-width: 991px) { .jsk-why-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px) { .jsk-why-grid { grid-template-columns: 1fr; } }
.jsk-why-card {
    background: #fff; border: 1px solid #ececec; border-radius: 16px;
    padding: 28px 24px;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}
.jsk-why-card:hover { transform: translateY(-4px); border-color: #ff8a00; box-shadow: 0 20px 40px rgba(15,23,42,.10); }
.jsk-why-ico {
    width: 52px; height: 52px; border-radius: 14px;
    background: linear-gradient(135deg, #0a0a0a, #1f1f1f); color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 22px; margin-bottom: 16px;
    box-shadow: 0 6px 14px rgba(10,10,10,.18);
}
.jsk-why-card h3 { font-size: 17px; font-weight: 800; color: #0a0a0a; margin: 0 0 8px; }
.jsk-why-card p { color: #555; font-size: 13.5px; line-height: 1.55; margin: 0; }

/* ===== SEO section ===== */
.jsk-seo-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 60px; align-items: center; }
@media (max-width: 991px) { .jsk-seo-grid { grid-template-columns: 1fr; gap: 40px; } }
.jsk-seo-content .eyebrow {
    display: inline-block; background: rgba(16,185,129,.10); color: #047857;
    font-size: 11px; font-weight: 800; padding: 5px 14px; border-radius: 999px;
    letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 14px;
}
.jsk-seo-content h2 {
    font-size: clamp(26px, 3vw, 36px); font-weight: 800; color: #0a0a0a;
    line-height: 1.15; letter-spacing: -.6px; margin: 0 0 16px;
}
.jsk-seo-content h2 .accent {
    background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
    -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
}
.jsk-seo-lead { color: #1a1a1a; font-size: 16px; line-height: 1.65; margin: 0 0 14px; font-weight: 500; }
.jsk-seo-content p { color: #555; font-size: 15px; line-height: 1.7; margin: 0 0 18px; }
.jsk-seo-list { list-style: none; padding: 0; margin: 18px 0 0; }
.jsk-seo-list li {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0; color: #1a1a1a; font-size: 14.5px;
}
.jsk-seo-list li i { color: #10b981; font-size: 18px; }
.jsk-seo-list li strong { color: #0a0a0a; font-weight: 700; margin-right: 4px; }
.jsk-seo-visual { position: relative; }
.jsk-seo-visual img {
    width: 100%;
    height: 460px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 30px 60px rgba(15,23,42,.15);
}
@media (max-width: 991px) { .jsk-seo-visual img { height: 360px; } }
@media (max-width: 575px) { .jsk-seo-visual img { height: 280px; } }
.jsk-float-badge {
    position: absolute; background: #fff; border-radius: 14px;
    padding: 14px 18px; box-shadow: 0 14px 32px rgba(15,23,42,.15);
    display: flex; align-items: center; gap: 12px; min-width: 180px;
}
.jsk-float-badge.tl { top: 20px; left: -20px; }
.jsk-float-badge.br { bottom: 20px; right: -20px; }
.jsk-float-badge .ico {
    width: 36px; height: 36px; border-radius: 10px;
    background: #0a0a0a; color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.jsk-float-badge .ico.green { background: #10b981; }
.jsk-float-badge .text strong { display: block; color: #0a0a0a; font-size: 14.5px; font-weight: 800; }
.jsk-float-badge .text span { color: #777; font-size: 12px; }
@media (max-width: 575px) {
    .jsk-float-badge.tl { top: 10px; left: 10px; }
    .jsk-float-badge.br { bottom: 10px; right: 10px; }
}

/* ===== Trust stats bar ===== */
.jsk-trust-grid {
    display: grid; grid-template-columns: repeat(5, 1fr); gap: 30px;
}
@media (max-width: 991px) { .jsk-trust-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 575px) { .jsk-trust-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; } }
.jsk-trust-stat { text-align: center; }
.jsk-trust-stat strong {
    display: block; font-size: clamp(28px, 3vw, 40px); font-weight: 800;
    letter-spacing: -.5px; margin-bottom: 6px;
    background: linear-gradient(135deg, #ff8a00, #ff5722);
    -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
}
.jsk-trust-stat span {
    color: #cbd5e1; font-size: 11.5px;
    text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;
}

/* ===== Dark mode ===== */
html.dark-mode .jsk-why-section,
html.dark-mode .jsk-seo-section { background: var(--site-bg) !important; border-top-color: var(--site-card-bd) !important; }
html.dark-mode .jsk-section-head h2,
html.dark-mode .jsk-seo-content h2,
html.dark-mode .jsk-why-card h3 { color: #fff !important; }
html.dark-mode .jsk-section-head p,
html.dark-mode .jsk-seo-content p,
html.dark-mode .jsk-why-card p { color: #cbd5e1 !important; }
html.dark-mode .jsk-seo-lead { color: #fff !important; }
html.dark-mode .jsk-seo-list li { color: #e5e7eb !important; }
html.dark-mode .jsk-seo-list li strong { color: #fff !important; }
html.dark-mode .jsk-why-card {
    background: var(--site-card-bg) !important;
    border-color: var(--site-card-bd) !important;
}
html.dark-mode .jsk-float-badge { background: var(--site-card-bg) !important; border: 1px solid var(--site-card-bd); }
html.dark-mode .jsk-float-badge .text strong { color: #fff !important; }
html.dark-mode .jsk-float-badge .text span { color: var(--site-muted) !important; }

/* ==== Job Seekers — full dark-mode coverage (hero stats, cards, FAQ, CTA) ==== */
html.dark-mode .js-page { background: var(--site-bg, #0f1216) !important; }

/* Hero stats — brighten so they read well over the photo + overlay */
html.dark-mode .js-hero-stats { border-top-color: rgba(255,255,255,.20) !important; }
html.dark-mode .js-hero-stats .stat strong { color: #ffffff !important; text-shadow: 0 1px 4px rgba(0,0,0,.6); }
html.dark-mode .js-hero-stats .stat span  { color: rgba(255,255,255,.88) !important; text-shadow: 0 1px 3px rgba(0,0,0,.5); }

/* Hero search box — switch button to brand orange so it pops on dark hero */
html.dark-mode .js-search-box {
    background: rgba(255,255,255,.96) !important;
    box-shadow: 0 24px 48px rgba(0,0,0,.55) !important;
}
html.dark-mode .js-search-box button {
    background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
    box-shadow: 0 6px 14px rgba(255,138,0,.35) !important;
}
html.dark-mode .js-search-box button:hover { background: linear-gradient(135deg, #ff7a00, #ff4722) !important; }

/* Section heads (above the seeker grid) */
html.dark-mode .js-section-head h2 { color: #fff !important; }
html.dark-mode .js-section-head p  { color: var(--site-muted, #b8c0cc) !important; }
html.dark-mode .js-section-head .count-pill {
    background: var(--site-card-bg, #1c2128) !important;
    border-color: rgba(255,255,255,.12) !important;
    color: #fff !important;
}

/* Seeker cards (.js-card) */
html.dark-mode .js-card {
    background: var(--site-card-bg, #1c2128) !important;
    border-color: rgba(255,255,255,.10) !important;
}
html.dark-mode .js-card:hover {
    border-color: #ff8a00 !important;
    box-shadow: 0 20px 40px rgba(0,0,0,.55) !important;
}
html.dark-mode .js-card-name { color: #fff !important; }
html.dark-mode .js-card-loc  { color: var(--site-muted, #b8c0cc) !important; }
html.dark-mode .js-card-headline { color: #d0d6df !important; }
html.dark-mode .js-skill {
    background: rgba(255,138,0,.10) !important;
    border-color: rgba(255,138,0,.25) !important;
    color: #ff8a00 !important;
}
html.dark-mode .js-card-meta { border-top-color: rgba(255,255,255,.08) !important; }
html.dark-mode .js-card-exp { color: var(--site-muted, #b8c0cc) !important; }
html.dark-mode .js-view-link { color: #ff8a00 !important; }
html.dark-mode .js-avatar {
    background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
    box-shadow: 0 6px 14px rgba(255,138,0,.30) !important;
}

/* Empty state */
html.dark-mode .js-empty {
    background: var(--site-card-bg, #1c2128) !important;
    border-color: rgba(255,255,255,.10) !important;
}
html.dark-mode .js-empty i { color: #4b5563 !important; }
html.dark-mode .js-empty h4 { color: #fff !important; }
html.dark-mode .js-empty p  { color: var(--site-muted, #b8c0cc) !important; }

/* FAQ section */
html.dark-mode .js-faq-section {
    background: #161a20 !important;
    border-top-color: rgba(255,255,255,.06) !important;
    border-bottom-color: rgba(255,255,255,.06) !important;
}
html.dark-mode .js-faq-head h2 { color: #fff !important; }
html.dark-mode .js-faq-head h2 .accent {
    background: linear-gradient(90deg, #ff8a00, #ff5722) !important;
    -webkit-background-clip: text !important; background-clip: text !important;
    -webkit-text-fill-color: transparent !important; color: transparent !important;
}
html.dark-mode .js-faq-head p { color: var(--site-muted, #b8c0cc) !important; }
html.dark-mode .js-faq-cta {
    background: var(--site-card-bg, #1c2128) !important;
    border: 1px solid rgba(255,255,255,.10) !important;
}
html.dark-mode .js-faq-cta a,
html.dark-mode .js-faq-cta strong { color: #fff !important; }
html.dark-mode .js-faq {
    background: var(--site-card-bg, #1c2128) !important;
    border-color: rgba(255,255,255,.10) !important;
}
html.dark-mode .js-faq[open] { border-color: #ff8a00 !important; }
html.dark-mode .js-faq summary,
html.dark-mode .js-faq-q { color: #fff !important; }
html.dark-mode .js-faq summary::after { color: #ff8a00 !important; }
html.dark-mode .js-faq[open] summary::after { color: #ff8a00 !important; }
html.dark-mode .js-faq-body { color: var(--site-muted, #b8c0cc) !important; }

/* Light mode FAQ +/× clearer accent (was plain black) */
.js-faq summary::after { color: #ff8a00 !important; }
.js-faq[open] summary::after { color: #ff8a00 !important; }

/* Dark mode for Job-Seeker Spotlight section */
html.dark-mode .js-spotlight-section {
    background: var(--site-bg, #0f1216) !important;
    border-top-color: rgba(255,255,255,.06) !important;
}
html.dark-mode .js-spotlight-content h2 { color: #fff !important; }
html.dark-mode .js-spotlight-content > p { color: var(--site-muted, #b8c0cc) !important; }
html.dark-mode .js-spotlight-list li { color: #d0d6df !important; }
html.dark-mode .js-spotlight-pill {
    background: var(--site-card-bg, #1c2128) !important;
    color: #fff !important;
    box-shadow: 0 8px 20px rgba(0,0,0,.55) !important;
}
html.dark-mode .js-spotlight-btn.outline {
    background: transparent !important;
    color: #fff !important;
    border-color: #ff8a00 !important;
}
html.dark-mode .js-spotlight-btn.outline:hover {
    background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
    color: #fff !important;
}

/* Why-job-seeker icon containers — orange in dark mode (was black) */
html.dark-mode .jsk-why-ico {
    background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
    box-shadow: 0 6px 14px rgba(255,138,0,.30) !important;
}

/* CTA section */
html.dark-mode .js-cta-section { background: var(--site-bg, #0f1216) !important; }
html.dark-mode .js-cta-card {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%) !important;
    border: 1px solid rgba(255,138,0,.20) !important;
}
html.dark-mode .js-cta-btn-primary {
    background: linear-gradient(135deg, #ff8a00, #ff5722) !important;
    color: #fff !important;
}
html.dark-mode .js-cta-btn-outline {
    border-color: rgba(255,255,255,.30) !important;
    color: #fff !important;
}
</style>

@endsection
