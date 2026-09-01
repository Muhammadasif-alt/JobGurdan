@php
    /**
     * Brand-themed SEO landing page partial.
     * Pages can pass:
     *   - $headline       : H1
     *   - $eyebrow        : small chip above H1 (defaults derived from $filterType)
     *   - $accentText     : optional fragment of headline to show in accent gradient
     *   - $intro          : array of intro paragraphs (under hero)
     *   - $sections       : array of [title, paragraphs[]] sub-sections
     *   - $jobRoles       : array of related role names
     *   - $ctaText        : CTA button label (defaults to "Browse Jobs")
     *   - $filterType     : 'state' | 'category' | 'keyword' | 'remote' | 'experience'
     *   - $filterValue    : string OR array (for keyword OR matching)
     *   - $faqs           : array of [q, a] pairs (else default set is used)
     */

    $headline       = $headline ?? 'Job Opportunities';
    $accentText     = $accentText ?? null;
    $eyebrow        = $eyebrow ?? null;
    $intro          = $intro ?? [];
    $sections       = $sections ?? [];
    $jobRoles       = $jobRoles ?? [];
    $ctaText        = $ctaText ?? 'Browse All Jobs';
    $filterType     = $filterType ?? null;
    $filterValue    = $filterValue ?? null;
    $faqs           = $faqs ?? null;

    // === Build filtered query of real jobs from DB ===
    // Cache only count + job IDs (tiny serialized footprint) to avoid hitting MySQL
    // max_allowed_packet on the database cache driver. Then fetch the 12 jobs by ID.
    // Version prefix is bumped by SiteCache::flush() so admin edits invalidate every combo at once.
    $cacheKey = 'seoLanding:v' . \App\Support\SiteCache::seoVersion()
              . ':' . md5(serialize([$filterType, $filterValue]));

    [$totalMatches, $jobIds] = \Illuminate\Support\Facades\Cache::remember($cacheKey, 600, function () use ($filterType, $filterValue) {
        $jobsQuery = \App\Models\Job::query()
            ->where(function ($q) { $q->where('status', 'active')->orWhereNull('status'); });

        if ($filterType === 'state' && $filterValue) {
            $jobsQuery->whereHas('location', fn ($q) => $q->where('name', $filterValue));
        } elseif ($filterType === 'category' && $filterValue) {
            $jobsQuery->whereHas('category', fn ($q) => $q->where('name', 'like', "%{$filterValue}%"));
        } elseif ($filterType === 'keyword' && $filterValue) {
            $keywords = is_array($filterValue) ? $filterValue : [$filterValue];
            $jobsQuery->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('position', 'like', "%{$kw}%");
                }
            });
        } elseif ($filterType === 'remote') {
            $jobsQuery->where(function ($q) {
                $q->where('position', 'like', '%remote%')
                  ->orWhere('position', 'like', '%work from home%')
                  ->orWhere('position', 'like', '%work-from-home%')
                  ->orWhere('position', 'like', '%telecommute%');
            });
        } elseif ($filterType === 'experience' && $filterValue) {
            $keywords = is_array($filterValue) ? $filterValue : [$filterValue];
            $jobsQuery->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('position', 'like', "%{$kw}%");
                }
            });
        }

        return [
            $jobsQuery->count(),
            $jobsQuery->latest()->take(12)->pluck('id')->all(),
        ];
    });

    $relatedJobs = empty($jobIds)
        ? collect()
        : \App\Models\Job::with(['advertiser', 'category', 'location'])
            ->whereIn('id', $jobIds)
            ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $jobIds)) . ')')
            ->get();

    // === Build "browse jobs" link with appropriate filter pre-applied ===
    $browseJobsUrl = route('jobs.index');
    if ($filterType === 'state' && $filterValue) {
        $browseJobsUrl = route('jobs.index', ['location' => $filterValue]);
    } elseif ($filterType === 'category' && $filterValue) {
        $browseJobsUrl = route('jobs.index', ['position' => $filterValue]);
    } elseif ($filterType === 'keyword' && $filterValue) {
        $kw = is_array($filterValue) ? $filterValue[0] : $filterValue;
        $browseJobsUrl = route('jobs.index', ['position' => $kw]);
    } elseif ($filterType === 'remote') {
        $browseJobsUrl = route('jobs.index', ['position' => 'remote']);
    } elseif ($filterType === 'experience' && $filterValue) {
        $kw = is_array($filterValue) ? $filterValue[0] : $filterValue;
        $browseJobsUrl = route('jobs.index', ['position' => $kw]);
    }

    // Default eyebrow if not provided
    if (!$eyebrow) {
        $eyebrow = match ($filterType) {
            'state'      => 'Local Jobs',
            'category'   => 'Industry Jobs',
            'remote'     => 'Remote Work',
            'experience' => 'Career Level',
            default      => 'Job Listings',
        };
    }
@endphp

<style>
    /* === Landing Hero (matches home/jobs/companies/categories/locations/about/blog) === */
    .seo-landing-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 70px 0 60px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .seo-landing-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: radial-gradient(circle at 12% 20%, rgba(10,10,10,.04) 0, transparent 40%),
                          radial-gradient(circle at 88% 80%, rgba(10,10,10,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .seo-landing-hero .container { position: relative; z-index: 2; text-align: center; }
    .seo-landing-hero .breadcrumbs-mini { color: #777; font-size: 13px; margin-bottom: 14px; }
    .seo-landing-hero .breadcrumbs-mini a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .seo-landing-hero .eyebrow {
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
    .seo-landing-hero h1 {
        color: #0a0a0a;
        font-size: clamp(30px, 4.4vw, 50px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 0 18px;
        max-width: 920px;
        margin-left: auto; margin-right: auto;
    }
    .seo-landing-hero h1 .accent {
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .seo-landing-hero p.lead {
        color: #555;
        font-size: clamp(15px, 1.5vw, 17px);
        line-height: 1.65;
        max-width: 720px;
        margin: 0 auto 26px;
    }
    .seo-landing-hero .hero-stats {
        display: inline-flex;
        gap: 32px;
        padding: 18px 30px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(15,23,42,.05);
        flex-wrap: wrap;
        justify-content: center;
    }
    .seo-landing-hero .hero-stats .stat strong {
        display: block;
        font-size: 22px;
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.1;
        letter-spacing: -.5px;
    }
    .seo-landing-hero .hero-stats .stat span {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #777;
        font-weight: 600;
    }

    /* === SEO Content Section === */
    .seo-content-section { padding: 70px 0 30px; background: #fff; }
    .seo-content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 50px;
    }
    @media (max-width: 991px) { .seo-content-grid { grid-template-columns: 1fr; } }

    .seo-content-prose p {
        font-size: 15.5px;
        line-height: 1.85;
        color: #4a4a4a;
        margin: 0 0 18px;
    }
    .seo-content-prose h2 {
        font-size: clamp(22px, 2.4vw, 28px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.25;
        letter-spacing: -.4px;
        margin: 32px 0 14px;
        position: relative;
        padding-left: 14px;
    }
    .seo-content-prose h2::before {
        content: "";
        position: absolute;
        left: 0; top: 6px;
        width: 4px; height: 24px;
        background: #0a0a0a;
        border-radius: 2px;
    }
    .seo-content-prose ul {
        list-style: none;
        padding: 0;
        margin: 0 0 18px;
    }
    .seo-content-prose ul li {
        position: relative;
        padding-left: 26px;
        font-size: 15px;
        line-height: 1.7;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    .seo-content-prose ul li::before {
        content: "";
        position: absolute;
        left: 0; top: 9px;
        width: 14px; height: 14px;
        border-radius: 50%;
        background: #0a0a0a;
        background-image: linear-gradient(135deg, #0a0a0a, #404040);
    }
    .seo-content-prose ul li::after {
        content: "✓";
        position: absolute;
        left: 3px; top: 4px;
        color: #fff;
        font-size: 9px;
        font-weight: 800;
        line-height: 1;
    }

    /* Sidebar promo card */
    .seo-side {
        position: sticky;
        top: 100px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .seo-side-promo {
        background: #0a0a0a;
        border-radius: 16px;
        padding: 26px 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .seo-side-promo::before, .seo-side-promo::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        filter: blur(50px);
        opacity: .3;
        pointer-events: none;
    }
    .seo-side-promo::before { width: 180px; height: 180px; background: #ff5722; top: -50px; right: -40px; }
    .seo-side-promo::after { width: 140px; height: 140px; background: #5e2bff; bottom: -40px; left: -30px; }
    .seo-side-promo > * { position: relative; z-index: 2; }
    .seo-side-promo .eyebrow {
        display: inline-block;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.20);
        color: rgba(255,255,255,.85);
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        padding: 5px 11px;
        border-radius: 999px;
        margin-bottom: 12px;
    }
    .seo-side-promo h4 {
        color: #fff;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.3;
        margin: 0 0 8px;
    }
    .seo-side-promo p {
        color: rgba(255,255,255,.78);
        font-size: 13px;
        line-height: 1.55;
        margin: 0 0 16px;
    }
    .seo-side-promo .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #0a0a0a !important;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        transition: all .15s ease;
    }
    .seo-side-promo .btn:hover { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(0,0,0,.25); }

    /* Common roles list */
    .seo-roles-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 24px;
    }
    .seo-roles-card h3 {
        font-size: 14px;
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 16px;
        padding: 0 0 12px;
        border-bottom: 2px solid #0a0a0a;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        display: inline-block;
    }
    .seo-roles-card ul { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 8px; }
    .seo-roles-card ul li a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 8px;
        background: #fafafa;
        color: #1a1a1a;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s ease;
    }
    .seo-roles-card ul li a:hover {
        background: #0a0a0a;
        color: #fff;
        transform: translateX(3px);
    }
    .seo-roles-card ul li a::before {
        content: "›";
        color: #0a0a0a;
        font-weight: 700;
        transition: color .15s ease;
    }
    .seo-roles-card ul li a:hover::before { color: #fff; }

    /* === Real Jobs Grid === */
    .seo-jobs-section {
        padding: 60px 0 80px;
        background: #fafafa;
        border-top: 1px solid #ececec;
    }
    .seo-jobs-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 30px;
    }
    .seo-jobs-head h2 {
        font-size: clamp(22px, 2.4vw, 30px);
        font-weight: 800;
        color: #0a0a0a;
        margin: 0;
        letter-spacing: -.4px;
    }
    .seo-jobs-head .view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #0a0a0a;
        font-size: 13.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        text-decoration: none;
        border-bottom: 1.5px solid #0a0a0a;
        padding-bottom: 2px;
        transition: gap .15s ease;
    }
    .seo-jobs-head .view-all:hover { gap: 12px; }

    .seo-jobs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }
    @media (max-width: 1199px) { .seo-jobs-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px)  { .seo-jobs-grid { grid-template-columns: 1fr; } }

    .seo-job-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 20px;
        text-decoration: none;
        color: inherit;
        height: 100%;
        transition: all .25s ease;
    }
    .seo-job-card:hover {
        border-color: #0a0a0a;
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(15,23,42,.10);
    }
    .seo-job-card-top {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 14px;
    }
    .seo-job-logo {
        width: 50px; height: 50px;
        border-radius: 12px;
        background: #f5f5f7;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .seo-job-logo img { max-width: 80%; max-height: 80%; object-fit: contain; }
    .seo-job-badge {
        font-size: 10.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        color: #fff;
        background: #047857;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .seo-job-title {
        font-size: 15px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 0 0 10px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
    }
    .seo-job-meta {
        list-style: none; padding: 0; margin: 0 0 14px;
        display: flex; flex-direction: column; gap: 5px;
    }
    .seo-job-meta li {
        font-size: 12.5px; color: #555;
        display: flex; align-items: center; gap: 6px;
    }
    .seo-job-meta li i { color: #0a0a0a; font-size: 13px; }
    .seo-job-button {
        display: inline-flex;
        align-items: center; justify-content: center;
        gap: 6px;
        background: #0a0a0a;
        color: #fff !important;
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        margin-top: auto;
        transition: background .2s ease;
    }
    .seo-job-card:hover .seo-job-button { background: #1a1a1a; }

    .seo-no-jobs {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px 20px;
        background: #fff;
        border: 1px dashed #ddd;
        border-radius: 14px;
    }
    .seo-no-jobs i { font-size: 42px; color: #c7c7cc; }
    .seo-no-jobs h4 { font-size: 16px; color: #0a0a0a; margin: 12px 0 6px; font-weight: 700; }
    .seo-no-jobs p { color: #777; margin: 0 0 14px; font-size: 14px; }
    .seo-no-jobs a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0a0a0a;
        color: #fff !important;
        font-size: 13.5px;
        font-weight: 600;
        padding: 11px 22px;
        border-radius: 8px;
        text-decoration: none;
    }

    .seo-bottom-cta {
        text-align: center;
        margin-top: 40px;
    }
    .seo-bottom-cta a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #0a0a0a;
        color: #fff !important;
        font-size: 14.5px;
        font-weight: 600;
        padding: 14px 28px;
        border-radius: 10px;
        text-decoration: none;
        border: 1.5px solid #0a0a0a;
        transition: all .15s ease;
    }
    .seo-bottom-cta a:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }

    /* === FAQ === */
    .seo-faq-section {
        padding: 70px 0;
        background: #fff;
        border-top: 1px solid #ececec;
    }
    .seo-faq-head { text-align: center; max-width: 760px; margin: 0 auto 40px; }
    .seo-faq-head .eyebrow {
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
        margin-bottom: 14px;
    }
    .seo-faq-head h2 {
        font-size: clamp(24px, 2.8vw, 32px);
        font-weight: 800;
        color: #0a0a0a;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin: 0;
    }
    .seo-faq-list { max-width: 880px; margin: 0 auto; }
    .seo-faq-item {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        transition: all .2s ease;
    }
    .seo-faq-item[open] {
        border-color: #0a0a0a;
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
    }
    .seo-faq-item summary {
        padding: 18px 22px;
        font-weight: 600;
        font-size: 15px;
        color: #0a0a0a;
        cursor: pointer;
        list-style: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }
    .seo-faq-item summary::-webkit-details-marker { display: none; }
    .seo-faq-item summary::after {
        content: '+';
        font-size: 22px;
        color: #0a0a0a;
        font-weight: 300;
    }
    .seo-faq-item[open] summary::after { content: '−'; }
    .seo-faq-item .faq-answer {
        padding: 0 22px 20px;
        color: #555;
        font-size: 14px;
        line-height: 1.75;
    }

    @media (max-width: 991px) {
        .seo-side { position: static; }
    }
</style>

<!-- Hero -->
<section class="seo-landing-hero">
    <div class="container">
        <div class="breadcrumbs-mini">
            <a href="{{ url('/') }}">Home</a> &nbsp;&rsaquo;&nbsp; {{ $headline }}
        </div>
        <span class="eyebrow">{{ $eyebrow }}</span>
        @if ($accentText)
            @php
                // Replace first occurrence of accentText in $headline with span
                $renderedHeadline = preg_replace('/' . preg_quote($accentText, '/') . '/', '<span class="accent">' . e($accentText) . '</span>', e($headline), 1);
            @endphp
            <h1>{!! $renderedHeadline !!}</h1>
        @else
            <h1>{{ $headline }}</h1>
        @endif
        @if (!empty($intro[0]))
            <p class="lead">{{ $intro[0] }}</p>
        @endif
        <div class="hero-stats">
            <div class="stat">
                <strong>{{ number_format($totalMatches) }}+</strong>
                <span>Matching Jobs</span>
            </div>
            <div class="stat">
                <strong>50</strong>
                <span>U.S. States</span>
            </div>
            <div class="stat">
                <strong>100%</strong>
                <span>Free to Apply</span>
            </div>
        </div>
    </div>
</section>

<!-- SEO Content -->
<section class="seo-content-section">
    <div class="container">
        <div class="seo-content-grid">
            <div class="seo-content-prose">
                @foreach (array_slice($intro, 1) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach

                @foreach ($sections as $section)
                    <h2>{{ $section['title'] }}</h2>
                    @foreach ($section['paragraphs'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                @endforeach

                @if (count($jobRoles))
                    <h2>Common Roles &amp; Job Titles</h2>
                    <ul>
                        @foreach ($jobRoles as $role)
                            <li>{{ $role }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <aside class="seo-side">
                <div class="seo-side-promo">
                    <span class="eyebrow">Get Started Free</span>
                    <h4>Find your next role faster with verified employers</h4>
                    <p>Sign up free, set your preferences, and let matching jobs come to you — no scams, no ghost listings.</p>
                    <a href="{{ route('register') }}" class="btn">
                        Create Free Account <i class="icon-feather-arrow-right"></i>
                    </a>
                </div>

                @if (count($jobRoles))
                    <div class="seo-roles-card">
                        <h3>Popular Roles</h3>
                        <ul>
                            @foreach (array_slice($jobRoles, 0, 8) as $role)
                                <li><a href="{{ route('jobs.index', ['position' => $role]) }}">{{ $role }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</section>

<!-- Real Jobs Grid -->
<section class="seo-jobs-section">
    <div class="container">
        <div class="seo-jobs-head">
            <h2>{{ $relatedJobs->count() ? 'Latest ' . $headline : 'Browse All Jobs' }}</h2>
            <a href="{{ $browseJobsUrl }}" class="view-all">
                View All <i class="icon-feather-arrow-right"></i>
            </a>
        </div>

        <div class="seo-jobs-grid">
            @forelse ($relatedJobs as $job)
                <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}"
                   class="seo-job-card">
                    <div class="seo-job-card-top">
                        <div class="seo-job-logo">
                            <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}"
                                 alt="{{ $job->advertiser->name ?? 'Company' }}" loading="lazy">
                        </div>
                        <span class="seo-job-badge">{{ $job->employment_type ?? 'Full Time' }}</span>
                    </div>
                    <h3 class="seo-job-title">{{ $job->position }}</h3>
                    <ul class="seo-job-meta">
                        <li><i class="icon-feather-briefcase"></i>
                            {{ $job->category?->name ?? ($job->advertiser->name ?? 'N/A') }}</li>
                        @if ($job->location)
                            <li><i class="icon-material-outline-location-on"></i>
                                {{ $job->location->name }}{{ $job->location->area ? ', ' . $job->location->area : '' }}</li>
                        @endif
                        <li><i class="icon-material-outline-access-time"></i>
                            {{ $job->created_at->diffForHumans() }}</li>
                    </ul>
                    <span class="seo-job-button">View Job <i class="icon-feather-arrow-right"></i></span>
                </a>
            @empty
                <div class="seo-no-jobs">
                    <i class="icon-feather-search"></i>
                    <h4>No matching jobs available right now</h4>
                    <p>New {{ strtolower($headline) }} are added every day. Browse all listings or check back soon.</p>
                    <a href="{{ route('jobs.index') }}">Browse All Jobs <i class="icon-feather-arrow-right"></i></a>
                </div>
            @endforelse
        </div>

        @if ($relatedJobs->count())
            <div class="seo-bottom-cta">
                <a href="{{ $browseJobsUrl }}">
                    {{ $ctaText }} <i class="icon-feather-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</section>

@php
    // Default FAQ set (used when page doesn't supply custom FAQs)
    $faqs = $faqs ?? [
        ['q' => 'Is it free to apply for ' . strtolower($headline) . '?',
         'a' => 'Yes — applying for any job on Jobs in USA is 100% free for job seekers. Just create a free account and start applying.'],
        ['q' => 'How are these listings verified?',
         'a' => 'Every employer profile on Jobs in USA is reviewed by our trust and safety team before going live. We verify business legitimacy and remove fraudulent or outdated listings.'],
        ['q' => 'Can I get email alerts for new ' . strtolower($headline) . '?',
         'a' => 'Yes — set up custom job alerts based on keywords, location, salary, and category. We will email you the moment a matching role goes live.'],
        ['q' => 'How quickly are new jobs added?',
         'a' => 'New jobs are posted every day across all 50 U.S. states and major industries. Subscribe to alerts so you never miss a fresh opening.'],
    ];
@endphp

<!-- FAQ -->
<section class="seo-faq-section" aria-labelledby="seo-faq-heading">
    <div class="container">
        <header class="seo-faq-head">
            <span class="eyebrow">Frequently Asked</span>
            <h2 id="seo-faq-heading">{{ $headline }} — Common Questions</h2>
        </header>
        <div class="seo-faq-list">
            @foreach ($faqs as $idx => $faq)
                <details class="seo-faq-item" {{ $idx === 0 ? 'open' : '' }}>
                    <summary>{{ $faq['q'] }}</summary>
                    <div class="faq-answer">{!! $faq['a'] !!}</div>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ JSON-LD --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach ($faqs as $idx => $faq)
        {
            "@@type": "Question",
            "name": {!! json_encode($faq['q']) !!},
            "acceptedAnswer": { "@@type": "Answer", "text": {!! json_encode(strip_tags($faq['a'])) !!} }
        }{{ $loop->last ? '' : ',' }}
        @endforeach
    ]
}
</script>
