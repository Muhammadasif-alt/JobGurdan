@extends('user.layouts.master')
@php
    $jobPos = trim((string) (request('keywords') ?: request('position') ?: ''));
    $jobLoc = trim((string) (request('location') ?: ''));
    $posLabel = $jobPos !== '' ? ucwords($jobPos) : '';
    $locLabel = $jobLoc !== '' ? ucwords($jobLoc) : '';
    if ($posLabel && $locLabel) {
        $pageTitle = "{$posLabel} Jobs in {$locLabel} — Apply Free | JobGader";
        $pageDesc = "Browse {$posLabel} jobs in {$locLabel}. Apply on the employer site. Free to read on JobGader.";
    } elseif ($posLabel) {
        $pageTitle = "{$posLabel} Jobs — Hiring Now, Apply Free | JobGader";
        $pageDesc = "Search {$posLabel} jobs across {$coverage->shortList()}. Free to read, no account needed.";
    } elseif ($locLabel) {
        $pageTitle = "Jobs in {$locLabel} — Browse Listings | JobGader";
        $pageDesc = "Find jobs in {$locLabel} across every industry. Free to read on JobGader, with links to the employer or source.";
    } else {
        $pageTitle = 'Browse Jobs | Find Listings, Free to Read | JobGader';
        $pageDesc = 'Search ' . number_format($heroStats['total_jobs'] ?? 0) . ' jobs across ' . $coverage->shortList() . '. Filter by location or category. Free to read, no account needed.';
    }

    /*
     * url()->current() drops the query string, so every paginated page was
     * canonicalising to page 1 and telling Google the deeper pages were
     * duplicates — the listings past page 1 were not being crawled. The page
     * number is the one parameter that belongs in the canonical; the search
     * filters stay out of it deliberately, so filter permutations still fold
     * back into /jobs instead of bloating the index.
     */
    $jobsPage = $jobs->currentPage();
    $jobsFirst = $jobsPage === 1;
    if (! $jobsFirst) {
        // Page 1's titles carry a marketing tail that pushes past 60 characters
        // once ", Page N" is added, so deeper pages get the plain stem instead.
        $stem = match (true) {
            $posLabel !== '' && $locLabel !== '' => "{$posLabel} Jobs in {$locLabel}",
            $posLabel !== '' => "{$posLabel} Jobs",
            $locLabel !== '' => "Jobs in {$locLabel}",
            default => 'Browse Jobs',
        };
        $pageTitle = $stem.', Page '.$jobsPage.' | JobGader';
    }
@endphp
@section('title', $pageTitle)
@section('og_title', $pageTitle)
@section('meta_description', $pageDesc)
@section('canonical', $jobsFirst ? route('jobs.index') : route('jobs.index').'?page='.$jobsPage)

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Jobs', 'item' => route('jobs.index')],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'Jobs on JobGader',
    'description' => $pageDesc,
    'url' => url()->current(),
    'isPartOf' => ['@type' => 'WebSite', 'name' => 'JobGader', 'url' => url('/')],
    'mainEntity' => [
        '@type' => 'ItemList',
        'numberOfItems' => $jobs->total(),
        'itemListElement' => collect($jobs->items())->values()->map(fn ($job, $i) => [
            '@type' => 'ListItem',
            'position' => $jobs->firstItem() + $i,
            'url' => route('jobs.show', \Illuminate\Support\Str::slug($job->position.'-'.($job->location->name ?? ''))),
            'name' => $job->position,
        ])->all(),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@section('meta_keywords', 'browse jobs, job listings, search jobs, apply jobs free, hiring now, jobs by location, jobs by category, visa sponsorship jobs')
@section('content')

<style>
    /* === Jobs Hero (matches home — light gradient, dark text) === */
    .jobs-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 70px 0 50px;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f3;
    }
    .jobs-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 12% 20%, rgba(27, 58, 107,.04) 0, transparent 40%),
            radial-gradient(circle at 88% 80%, rgba(27, 58, 107,.03) 0, transparent 45%);
        pointer-events: none;
    }
    .jobs-hero .container { position: relative; z-index: 2; text-align: center; }
    .jobs-hero .eyebrow {
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
    .jobs-hero h1 {
        font-size: clamp(30px, 4.4vw, 52px);
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 0 18px;
        max-width: 920px;
        margin-left: auto;
        margin-right: auto;
    }
    .jobs-hero h1 .accent {
        background: linear-gradient(90deg, #1b3a6b, #4a90d9);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .jobs-hero p {
        color: #555;
        font-size: clamp(15px, 1.5vw, 17px);
        line-height: 1.65;
        max-width: 720px;
        margin: 0 auto 30px;
    }
    .jobs-hero .hero-stats {
        display: inline-flex;
        gap: 32px;
        padding: 20px 36px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(15,23,42,.05);
        flex-wrap: wrap;
        justify-content: center;
    }
    .jobs-hero .hero-stats .stat strong {
        display: block;
        font-size: 24px;
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.1;
        letter-spacing: -.5px;
    }
    .jobs-hero .hero-stats .stat span {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #777;
        font-weight: 600;
    }

    /* === Filter Bar (dark) === */
    .jobs-filter-section {
        padding: 28px 0 0;
        background: #fff;
    }
    .jobs-filter-bar {
        background: #1b3a6b;
        color: #fff;
        border-radius: 14px;
        padding: 22px 26px;
        box-shadow: 0 8px 24px rgba(15,23,42,.06);
    }
    .jobs-filter-bar .filter-head {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 16px;
        color: #fff;
    }
    .jobs-filter-bar form .row { row-gap: 10px; }
    .jobs-filter-bar .input-wrap { position: relative; }
    .jobs-filter-bar input,
    .jobs-filter-bar select.form-control,
    .jobs-filter-bar .select2-container--bootstrap .select2-selection--single {
        background: #fff !important;
        border: 1px solid #e5e5e7 !important;
        height: 48px !important;
        line-height: 46px !important;
        border-radius: 10px !important;
        font-size: 14.5px !important;
        color: #16305a !important;
        padding-left: 40px !important;
        box-shadow: none !important;
        width: 100% !important;
    }
    .jobs-filter-bar .select2-container--bootstrap .select2-selection--single .select2-selection__rendered {
        line-height: 46px !important;
        padding-left: 0 !important;
        color: #16305a !important;
    }
    .jobs-filter-bar .select2-container--bootstrap .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 8px !important;
    }
    .jobs-filter-bar .input-wrap > i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 17px;
        z-index: 5;
        pointer-events: none;
    }
    .jobs-filter-bar .btn-search {
        background: #fff !important;
        color: #1b3a6b !important;
        border: 1.5px solid #fff !important;
        font-weight: 700 !important;
        font-size: 14.5px !important;
        height: 48px !important;
        border-radius: 10px !important;
        width: 100% !important;
        cursor: pointer;
        transition: all .15s ease;
    }
    .jobs-filter-bar .btn-search:hover {
        background: #f5f5f7 !important;
        transform: translateY(-1px);
    }
    /* Make disabled selects look clearly inactive */
    .jobs-filter-bar select:disabled,
    .jobs-filter-bar .select2-container--disabled .select2-selection {
        opacity: .55;
        cursor: not-allowed;
    }

    /* === Results header === */
    .jobs-results-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 26px 0 18px;
        margin-top: 8px;
    }
    .jobs-results-header .count { color: #555; font-size: 14.5px; }
    .jobs-results-header .count strong { color: #1b3a6b; font-weight: 700; }
    .jobs-results-header .sort-by {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #555;
    }
    .jobs-results-header .sort-by select {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        padding: 8px 30px 8px 12px;
        font-size: 14px;
        font-weight: 600;
        color: #1b3a6b;
        cursor: pointer;
    }

    /* === Job Cards === */
    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    @media (max-width: 1199px) { .jobs-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 900px)  { .jobs-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px)  { .jobs-grid { grid-template-columns: 1fr; } }

    .job-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        padding: 22px 20px;
        text-decoration: none;
        color: inherit;
        height: 100%;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .job-card:hover {
        border-color: #1b3a6b;
        box-shadow: 0 14px 32px rgba(15,23,42,.08);
        transform: translateY(-3px);
    }
    .job-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .job-card-logo {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #f5f5f7;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .job-card-logo img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }
    .job-card-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 11px;
        border-radius: 6px;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .job-card-badge.green  { background: #047857; }
    .job-card-badge.yellow { background: #b45309; }
    .job-card-title {
        font-size: 16.5px;
        font-weight: 700;
        color: #1b3a6b;
        margin: 0 0 12px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 46px;
    }
    .job-card-meta {
        list-style: none;
        padding: 0;
        margin: 0 0 18px;
        flex-grow: 1;
    }
    .job-card-meta li {
        font-size: 13px;
        color: #555;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .job-card-meta li i {
        color: #1b3a6b;
        font-size: 14px;
    }
    .job-card-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #1b3a6b;
        color: #fff !important;
        padding: 11px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        margin-top: auto;
        transition: background .2s ease;
        border: 1.5px solid #1b3a6b;
    }
    .job-card:hover .job-card-button { background: #16305a; border-color: #16305a; }

    /* No results card */
    .no-jobs {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: #fafafa;
        border: 1px dashed #ddd;
        border-radius: 14px;
    }
    .no-jobs i { font-size: 50px; color: #c7c7cc; }
    .no-jobs h4 { font-size: 18px; color: #1b3a6b; margin: 14px 0 6px; font-weight: 700; }
    .no-jobs p { color: #777; margin: 0; }

    /* Pagination — dark */
    .jobs-pagination { padding: 30px 0 20px; display: flex; justify-content: center; }
    .jobs-pagination ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .jobs-pagination li a, .jobs-pagination li span {
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        color: #1b3a6b;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all .15s ease;
    }
    .jobs-pagination li a:hover { background: #f5f5f7; border-color: #1b3a6b; }
    .jobs-pagination li a.current-page,
    .jobs-pagination li.active a {
        background: #1b3a6b;
        color: #fff;
        border-color: #1b3a6b;
    }
    .jobs-pagination li a.disabled { opacity: .35; cursor: not-allowed; pointer-events: none; }

    /* === Related Section: Browse by Category & State === */
    .jobs-explore-section {
        padding: 70px 0 60px;
        background: #fafafa;
        border-top: 1px solid #ececec;
        margin-top: 30px;
    }
    .jobs-explore-section .section-head {
        text-align: center;
        max-width: 760px;
        margin: 0 auto 40px;
    }
    .jobs-explore-section .section-head .eyebrow {
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
    .jobs-explore-section h2 {
        font-size: clamp(24px, 3vw, 34px);
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.2;
        letter-spacing: -.5px;
        margin: 0 0 12px;
    }
    .jobs-explore-section .section-head p {
        color: #555;
        font-size: 15px;
        line-height: 1.6;
        margin: 0;
    }
    .explore-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 991px) { .explore-grid { grid-template-columns: 1fr; } }
    .explore-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        padding: 28px 26px;
    }
    .explore-card h3 {
        font-size: 17px;
        font-weight: 700;
        color: #1b3a6b;
        margin: 0 0 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .explore-card h3::before {
        content: "";
        width: 4px;
        height: 18px;
        background: #1b3a6b;
        border-radius: 2px;
    }
    .explore-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .explore-pills a {
        background: #fff;
        border: 1px solid #ececec;
        color: #16305a;
        padding: 9px 16px;
        border-radius: 999px;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .explore-pills a:hover {
        background: #1b3a6b;
        border-color: #1b3a6b;
        color: #fff;
        transform: translateY(-1px);
    }
    .explore-pills a .num {
        background: #f5f5f7;
        color: #555;
        font-size: 11.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        transition: all .15s ease;
    }
    .explore-pills a:hover .num {
        background: rgba(255,255,255,.18);
        color: #fff;
    }

    /* === Trust strip below === */
    .jobs-trust-strip {
        margin-top: 32px;
        padding: 24px 28px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
    @media (max-width: 768px) { .jobs-trust-strip { grid-template-columns: repeat(2, 1fr); } }
    .jobs-trust-strip .trust-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .jobs-trust-strip .trust-item .ico {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #1b3a6b;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .jobs-trust-strip .trust-item h5 {
        font-size: 14px;
        font-weight: 700;
        color: #1b3a6b;
        margin: 0 0 3px;
    }
    .jobs-trust-strip .trust-item p {
        font-size: 12.5px;
        color: #777;
        line-height: 1.5;
        margin: 0;
    }

    @media (max-width: 768px) {
        .jobs-hero { padding: 50px 0 36px; }
        .jobs-hero .hero-stats { gap: 18px; padding: 14px 22px; }
        .jobs-hero .hero-stats .stat strong { font-size: 20px; }
    }
</style>

<style>
    .jb-top { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 18px 0 14px; }
    .jb-top form { display: flex !important; flex-wrap: wrap; gap: 12px; justify-content: center; align-items: center; margin: 0; }
    .jb-top .jb-search { flex: 0 1 460px !important; width: 460px !important; max-width: 100% !important; margin: 0 !important; height: 46px; border: 1px solid #cfd4dc; border-radius: 6px; padding: 0 14px; font-size: 15px; }
    .jb-top .jb-search:focus, .jb-top .jb-loc:focus { outline: none; border-color: #2f7fc9; box-shadow: 0 0 0 3px rgba(47,127,201,.15); }
    .jb-top .jb-loc { flex: 0 0 210px !important; width: 210px !important; max-width: 100% !important; margin: 0 !important; height: 46px; min-width: 190px; border: 1px solid #cfd4dc; border-radius: 6px; padding: 0 12px; font-size: 15px; background: #fff; }
    .jb-top .jb-find { flex: 0 0 auto !important; width: auto !important; margin: 0 !important; height: 46px; padding: 0 26px; border: 0; border-radius: 999px; background: #2f7fc9; color: #fff; font-weight: 700; font-size: 15px; cursor: pointer; }
    .jb-top .jb-find:hover { background: #1b3a6b; }
    .jb-note { text-align: center; font-size: 13.5px; color: #5b6472; margin: 12px 0 0; }
    .jb-note a { color: #1b3a6b; font-weight: 600; }
    .jb-stats { display: flex; justify-content: center; flex-wrap: wrap; gap: 6px 26px; margin-top: 8px; font-size: 13px; color: #5b6472; }
    .jb-stats .stat { display: inline-flex; gap: 6px; align-items: baseline; }
    .jb-stats strong { color: #1b3a6b; font-size: 15px; }

    .jb-board { display: grid; grid-template-columns: 290px minmax(0, 1fr) 330px; gap: 0; background: #fff; border-top: 0; }
    .jb-col { min-width: 0; }
    .jb-col-head { background: #f7f7f8; padding: 26px 24px 20px; margin: 0; font-size: 24px; font-weight: 700; color: #1f2937; }
    .jb-filters { border-right: 1px solid #eceef1; }
    .jb-side { border-left: 1px solid #eceef1; }
    .jb-filters-body, .jb-side-body { padding: 20px 20px 28px; }
    .jb-toggle { display: grid; grid-template-columns: repeat(3, 1fr); border-radius: 5px; overflow: hidden; border: 1px solid #2f7fc9; margin-bottom: 8px; }
    .jb-toggle a { text-align: center; padding: 9px 4px; font-size: 13px; font-weight: 600; color: #2f7fc9; background: #fff; text-decoration: none; }
    .jb-toggle a.on { background: #2f7fc9; color: #fff; }
    .jb-toggle a + a { border-left: 1px solid #2f7fc9; }
    .jb-fgroup { border-top: 1px solid #eceef1; margin-top: 22px; padding-top: 18px; }
    .jb-fgroup h3 { font-size: 19px; font-weight: 700; margin: 0 0 14px; color: #1f2937; display: flex; justify-content: space-between; align-items: center; }
    .jb-fgroup h3 a { font-size: 12px; font-weight: 600; color: #2f7fc9; }
    .jb-fitem { display: flex; align-items: center; gap: 10px; padding: 8px 0; text-decoration: none !important; color: #374151; font-size: 14.5px; }
    .jb-fitem .box { width: 18px; height: 18px; flex: 0 0 18px; border: 1px solid #c4c9d1; border-radius: 3px; background: #fafafa; position: relative; }
    .jb-fitem.on .box { background: #2f7fc9; border-color: #2f7fc9; }
    .jb-fitem.on .box::after { content: ''; position: absolute; left: 5px; top: 1px; width: 5px; height: 10px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
    .jb-fitem .name { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .jb-fitem .num { color: #6b7280; font-size: 13.5px; }
    .jb-fitem:hover .name { color: #1b3a6b; }
    .jb-more summary { cursor: pointer; color: #2f7fc9; font-weight: 600; font-size: 13.5px; padding: 6px 0 4px 28px; list-style: none; }
    .jb-more summary::-webkit-details-marker { display: none; }

    .jb-list-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 14px 24px; border-bottom: 1px solid #eceef1; font-size: 14px; color: #5b6472; }
    .jb-list-head select { border: 1px solid #cfd4dc; border-radius: 5px; padding: 5px 8px; font-size: 13.5px; background: #fff; }
    .jb-rows { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; padding: 22px 24px 8px; }
    .jb-row { display: flex; flex-direction: column; position: relative; padding: 20px 20px 18px; border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; text-decoration: none !important; color: inherit; transition: box-shadow .15s ease, border-color .15s ease, transform .15s ease; }
    .jb-row:hover { border-color: #2f7fc9; box-shadow: 0 8px 22px rgba(27,58,107,.12); transform: translateY(-2px); }
    .jb-row h2 { font-size: 17px; font-weight: 700; margin: 0 0 6px; color: #111827; line-height: 1.35; }
    .jb-row .who { font-size: 14px; color: #4b5563; margin: 0 0 10px; }
    .jb-row .desc { font-size: 13.5px; color: #6b7280; line-height: 1.55; margin: 0 0 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .jb-row .facts { display: flex; flex-direction: column; gap: 4px; font-size: 13px; color: #6b7280; margin: auto 0 12px; padding-top: 4px; }
    .jb-row .facts span { display: inline-flex; gap: 6px; align-items: center; }
    .jb-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .jb-chip { background: #f1f3f5; border-radius: 999px; padding: 5px 12px; font-size: 12px; color: #374151; }
    .jb-chip.res { background: #e6f4ea; color: #166534; font-weight: 700; }
    .jb-empty { grid-column: 1 / -1; padding: 60px 24px; text-align: center; color: #6b7280; }

    .jb-guide { display: block; padding: 14px 0; border-bottom: 1px solid #eceef1; text-decoration: none !important; }
    .jb-guide strong { display: block; color: #111827; font-size: 15px; line-height: 1.35; margin-bottom: 4px; }
    .jb-guide span { display: block; color: #6b7280; font-size: 13px; line-height: 1.45; }
    .jb-guide:hover strong { color: #1b3a6b; }
    .jb-side-link { display: inline-block; margin-top: 14px; color: #2f7fc9; font-weight: 700; font-size: 14px; }
    .jb-side-card { margin-top: 26px; padding: 16px; background: #f7f8fa; border-radius: 8px; font-size: 13.5px; color: #4b5563; line-height: 1.55; }
    .jb-side-card a { color: #1b3a6b; font-weight: 700; }

    @media (max-width: 1599px) { .jb-rows { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 1199px) { .jb-board { grid-template-columns: 260px minmax(0, 1fr); } .jb-side { grid-column: 1 / -1; border-left: 0; border-top: 1px solid #eceef1; } }
    @media (max-width: 767px) { .jb-board { grid-template-columns: 1fr; } .jb-filters { border-right: 0; border-bottom: 1px solid #eceef1; } .jb-col-head { padding: 18px 18px 14px; } .jb-rows { grid-template-columns: 1fr; padding: 16px 14px 4px; } .jb-top .jb-find { flex: 1 1 140px; justify-content: center; } }
</style>

@php
    $jbHasFilter = request()->anyFilled(['position', 'location', 'area', 'postal_code', 'category', 'employment', 'type']);
    $jbType = request('type');
    $jbUrl = fn (array $query) => route('jobs.index', array_filter(array_merge(request()->only(['position', 'location', 'category', 'employment', 'type', 'sort']), $query), fn ($v) => $v !== null && $v !== ''));
@endphp

<!-- Hero Section -->
<section class="jobs-hero">
    <div class="container">
        <span class="eyebrow" data-aos="fade-down" data-aos-duration="600">
            @if(request()->anyFilled(['position','location','area','postal_code']))
                Search Results
            @else
                Job Search
            @endif
        </span>
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Find Your Next <span class="accent">Career Move</span></h1>
        <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">Current job openings across {{ $coverage->shortList() }}. Filter by location or category, then apply on the employer's own website. Free to read, no account needed. Looking for official job portals instead? See <a href="{{ route('jobs.portals') }}">Job Portals &amp; Resources</a>.</p>
        <div class="hero-stats">
            <div class="stat">
                <strong>{{ number_format($heroStats['total_jobs'] ?? 0) }}+</strong>
                <span>Active Jobs</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_companies'] ?? 0) }}+</strong>
                <span>Employers</span>
            </div>
            <div class="stat">
                <strong>{{ number_format($heroStats['total_locations'] ?? 50) }}+</strong>
                <span>Locations</span>
            </div>
            <div class="stat">
                <strong>100%</strong>
                <span>Free to Apply</span>
            </div>
        </div>
    </div>
</section>

<section class="jb-top">
    <div class="container">
        <form method="GET" action="{{ route('jobs.index') }}" id="jobSearchForm">
            <input type="text" name="position" class="jb-search" placeholder="Job title, skill or company"
                autocomplete="off" data-autocomplete="jobs" value="{{ request('position') }}" aria-label="Job title, skill or company">
            <select class="jb-loc" id="location" name="location" aria-label="Location">
                <option value="">All locations</option>
                @foreach ($uniqueLocations as $loc)
                    <option value="{{ $loc->name }}" {{ request('location') == $loc->name ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
            @if (request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if ($jbType)
                <input type="hidden" name="type" value="{{ $jbType }}">
            @endif
            <button type="submit" class="jb-find">Find Jobs</button>
        </form>
    </div>
</section>

<div class="container-fluid px-0">
    <div class="jb-board">
        <aside class="jb-col jb-filters" aria-label="Filters">
            <h2 class="jb-col-head">Filters</h2>
            <div class="jb-filters-body">
                <div class="jb-toggle" role="group" aria-label="Listing type">
                    <a href="{{ $jbUrl(['type' => null]) }}" class="{{ $jbType ? '' : 'on' }}">All</a>
                    <a href="{{ $jbUrl(['type' => 'vacancy']) }}" class="{{ $jbType === 'vacancy' ? 'on' : '' }}">Vacancies</a>
                    <a href="{{ $jbUrl(['type' => 'resource']) }}" class="{{ $jbType === 'resource' ? 'on' : '' }}">Resources</a>
                </div>
                <p style="font-size:12.5px;color:#6b7280;margin:6px 0 0;">{{ number_format($typeCounts['vacancy'] ?? 0) }} vacancies, {{ number_format($typeCounts['resource'] ?? 0) }} official resources.</p>

                <div class="jb-fgroup">
                    <h3>Job Category @if (request('category'))<a href="{{ $jbUrl(['category' => null]) }}">Clear</a>@endif</h3>
                    @foreach ($filterCategories as $i => $row)
                        @if ($i === 8)
                            <details class="jb-more" {{ request()->filled('category') && $filterCategories->slice(8)->contains('slug', request('category')) ? 'open' : '' }}><summary>Show More</summary>
                        @endif
                        <a href="{{ $jbUrl(['category' => request('category') === $row->slug ? null : $row->slug]) }}" class="jb-fitem {{ request('category') === $row->slug ? 'on' : '' }}">
                            <span class="box" aria-hidden="true"></span>
                            <span class="name">{{ $row->name }}</span>
                            <span class="num">{{ number_format($row->job_count) }}</span>
                        </a>
                        @if ($loop->last && $i >= 8)
                            </details>
                        @endif
                    @endforeach
                </div>

                <div class="jb-fgroup">
                    <h3>Location @if (request('location'))<a href="{{ $jbUrl(['location' => null]) }}">Clear</a>@endif</h3>
                    @foreach ($filterLocations as $i => $row)
                        @if ($i === 8)
                            <details class="jb-more" {{ request()->filled('location') && $filterLocations->slice(8)->contains('name', request('location')) ? 'open' : '' }}><summary>Show More</summary>
                        @endif
                        <a href="{{ $jbUrl(['location' => request('location') === $row->name ? null : $row->name]) }}" class="jb-fitem {{ request('location') === $row->name ? 'on' : '' }}">
                            <span class="box" aria-hidden="true"></span>
                            <span class="name">{{ $row->name }}</span>
                            <span class="num">{{ number_format($row->job_count) }}</span>
                        </a>
                        @if ($loop->last && $i >= 8)
                            </details>
                        @endif
                    @endforeach
                </div>

                @if ($employmentCounts->isNotEmpty())
                    <div class="jb-fgroup">
                        <h3>Job Type @if (request('employment'))<a href="{{ $jbUrl(['employment' => null]) }}">Clear</a>@endif</h3>
                        @foreach ($employmentCounts as $label => $count)
                            <a href="{{ $jbUrl(['employment' => request('employment') === $label ? null : $label]) }}" class="jb-fitem {{ request('employment') === $label ? 'on' : '' }}">
                                <span class="box" aria-hidden="true"></span>
                                <span class="name">{{ $label }}</span>
                                <span class="num">{{ number_format($count) }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </aside>

        <main class="jb-col jb-jobs">
            <h2 class="jb-col-head">Jobs</h2>
            <div class="jb-list-head">
                <div class="count">
                    @if ($jobs->total() > 0)
                        Showing <strong>{{ $jobs->firstItem() }}-{{ $jobs->lastItem() }}</strong> of <strong>{{ number_format($jobs->total()) }}</strong> jobs
                    @else
                        <strong>No jobs found</strong>
                    @endif
                </div>
                <div class="sort-by">
                    <span>Sort By:</span>
                    <select id="sortSelect">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'position_asc']) }}" {{ request('sort') == 'position_asc' ? 'selected' : '' }}>Position (A-Z)</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'position_desc']) }}" {{ request('sort') == 'position_desc' ? 'selected' : '' }}>Position (Z-A)</option>
                    </select>
                </div>
            </div>

            <div class="jb-rows">
                @forelse ($jobs as $job)
                    <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}" class="jb-row">
                        <h2>{{ $job->position }}</h2>
                        <p class="who">{{ $job->advertiser->name ?? 'Employer' }}@if ($job->location), {{ $job->location->name }}@endif</p>
                        @php($jbDesc = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(preg_replace('/<[^>]+>/', ' ', (string) $job->description))))), 190))
                        @if ($jbDesc !== '')
                            <p class="desc">{{ $jbDesc }}</p>
                        @endif
                        <div class="facts">
                            <span><i class="icon-material-outline-access-time" aria-hidden="true"></i> {{ $job->created_at->format('M d, Y') }} ({{ $job->created_at->diffForHumans() }})</span>
                            @if ($job->location)
                                <span><i class="icon-material-outline-location-on" aria-hidden="true"></i> {{ $job->location->name }}{{ $job->location->area ? ', ' . $job->location->area : '' }}</span>
                            @endif
                        </div>
                        <div class="jb-chips">
                            @if ($job->isResource())
                                <span class="jb-chip res">Official resource</span>
                            @elseif ($job->employment_type)
                                <span class="jb-chip">{{ $job->employment_type }}</span>
                            @endif
                            @if ($job->category)
                                <span class="jb-chip">{{ $job->category->name }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="jb-empty">
                        <h3>No jobs match your search</h3>
                        <p>Try changing your filters or removing some criteria to see more results.</p>
                        <a href="{{ route('jobs.index') }}" class="jb-side-link">Clear all filters</a>
                    </div>
                @endforelse
            </div>

            @if ($jobs->hasPages())
                @push('meta')
                    @if ($jobs->onFirstPage() === false)
                        <link rel="prev" href="{{ $jobs->previousPageUrl() }}">
                    @endif
                    @if ($jobs->hasMorePages())
                        <link rel="next" href="{{ $jobs->nextPageUrl() }}">
                    @endif
                @endpush
                {{ $jobs->onEachSide(2)->links() }}
            @endif
        </main>

        <aside class="jb-col jb-side" aria-label="Career guides">
            <h2 class="jb-col-head">Career Guides</h2>
            <div class="jb-side-body">
                @foreach ($latestGuides as $guide)
                    <a href="{{ route('blog.show', $guide->slug) }}" class="jb-guide">
                        <strong>{{ $guide->title }}</strong>
                        <span>{{ \Illuminate\Support\Str::limit((string) $guide->excerpt, 110) }}</span>
                    </a>
                @endforeach
                <a href="{{ route('blog.index') }}" class="jb-side-link">Read more guides</a>

                <div class="jb-side-card">
                    Resource listings link to official public portals, not to one vacancy. Read each portal's own rules before you apply. See <a href="{{ route('jobs.portals') }}">Job Portals &amp; Resources</a>.
                </div>
            </div>
        </aside>
    </div>
</div>

<!-- Related Section: Explore by Category & State + Trust Strip -->
<section class="jobs-explore-section" aria-labelledby="explore-heading">
    <div class="container">
        <header class="section-head">
            <span class="eyebrow">Explore More</span>
            <h2 id="explore-heading">Browse Jobs by Industry &amp; State</h2>
            <p>Narrow your search to the role and location that fits you best — explore the top hiring industries and most active U.S. job markets right now.</p>
        </header>

        <div class="explore-grid">
            <div class="explore-card">
                <h3>Top Industries</h3>
                <div class="explore-pills">
                    @foreach($topCategories as $cat)
                        <a href="{{ route('jobs.category', $cat->slug) }}" title="Browse {{ $cat->name }} jobs">
                            {{ $cat->name }}
                            <span class="num">{{ number_format($cat->jobs_count) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="explore-card">
                <h3>Top Hiring States</h3>
                <div class="explore-pills">
                    @foreach($topStates as $state)
                        <a href="{{ route('jobs.index', ['location' => $state->name]) }}" title="Browse jobs in {{ $state->name }}">
                            {{ $state->name }}
                            <span class="num">{{ number_format($state->job_count) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Trust strip -->
        <div class="jobs-trust-strip">
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-shield"></i></div>
                <div>
                    <h5>Source Links</h5>
                    <p>Each listing links to the employer's own site or an official portal.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-external-link"></i></div>
                <div>
                    <h5>Apply on the Source Site</h5>
                    <p>You apply on the employer's or portal's own website, not on JobGader.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-bell"></i></div>
                <div>
                    <h5>Job Alerts</h5>
                    <p>Registered job seekers can get email alerts for new matching listings.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="icon-feather-users"></i></div>
                <div>
                    <h5>Free to Read</h5>
                    <p>Openings across {{ $coverage->shortList() }}. No account is needed to read a listing.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize select2
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap'
            });

            const uniqueAreas = @json($uniqueAreas->values());
            const uniquePostalCodes = @json($uniquePostalCodes->values());
            const $location = $('#location');
            const $area = $('#area');
            const $postal = $('#postal_code');

            function dedupe(arr) { return Array.from(new Set(arr)); }

            function populateAreas(selectedLocation, selectedArea = null) {
                $area.prop('disabled', !selectedLocation);
                let options = ['<option value="">All Areas</option>'];
                if (!selectedLocation) {
                    $area.html(options.join('')).trigger('change');
                    return;
                }
                const areas = uniqueAreas
                    .filter(a => a.name === selectedLocation)
                    .map(a => a.area)
                    .filter(Boolean);
                dedupe(areas).forEach(ar => {
                    const selected = (ar === selectedArea) ? ' selected' : '';
                    options.push(`<option value="${ar}"${selected}>${ar}</option>`);
                });
                $area.html(options.join('')).trigger('change');
            }

            function populatePostalCodes(selectedLocation, selectedArea, selectedPostal = null) {
                $postal.prop('disabled', !(selectedLocation && selectedArea));
                let options = ['<option value="">All ZIPs</option>'];
                if (!(selectedLocation && selectedArea)) {
                    $postal.html(options.join('')).trigger('change');
                    return;
                }
                const zips = uniquePostalCodes
                    .filter(p => p.name === selectedLocation && p.area === selectedArea)
                    .map(p => p.postal_code)
                    .filter(Boolean);
                dedupe(zips).forEach(z => {
                    const selected = (z === selectedPostal) ? ' selected' : '';
                    options.push(`<option value="${z}"${selected}>${z}</option>`);
                });
                $postal.html(options.join('')).trigger('change');
            }

            $location.on('change', function() {
                populateAreas($(this).val());
                populatePostalCodes('', '', null);
            });

            $area.on('change', function() {
                populatePostalCodes($location.val(), $(this).val());
            });

            // Initialize with existing values
            const initialLocation = "{{ request('location') }}";
            const initialArea     = "{{ request('area') }}";
            const initialPostal   = "{{ request('postal_code') }}";

            if (initialLocation) {
                populateAreas(initialLocation, initialArea);
                if (initialArea) {
                    populatePostalCodes(initialLocation, initialArea, initialPostal);
                }
            }

            // Sort change → reload
            $('#sortSelect').on('change', function() {
                window.location.href = this.value;
            });
        });
    </script>
@endpush
