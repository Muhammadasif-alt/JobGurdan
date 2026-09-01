@extends('user.layouts.master')
@php
    $srPos = trim((string) ($keywords ?? request('keywords') ?? request('position') ?? ''));
    $srLoc = trim((string) ($selectedLocation ?? request('location') ?? ''));
    $srPosLbl = $srPos !== '' ? ucwords($srPos) : '';
    $srLocLbl = $srLoc !== '' ? ucwords($srLoc) : '';
    if ($srPosLbl && $srLocLbl) {
        $srTitle = "{$srPosLbl} Jobs in {$srLocLbl} — Search Results | Jobs in USA";
        $srDesc = "Find {$srPosLbl} jobs in {$srLocLbl} — verified listings, apply free on Jobs in USA.";
    } elseif ($srPosLbl) {
        $srTitle = "{$srPosLbl} Jobs — Search Results | Jobs in USA";
        $srDesc = "Browse {$srPosLbl} job openings across the USA. Apply free with one click on Jobs in USA.";
    } elseif ($srLocLbl) {
        $srTitle = "Jobs in {$srLocLbl} — Search Results | Jobs in USA";
        $srDesc = "Discover verified jobs in {$srLocLbl} across every industry. Apply free on Jobs in USA.";
    } else {
        $srTitle = 'Job Search Results — Find Verified U.S. Jobs | Jobs in USA';
        $srDesc = 'Browse matched job results across the USA. Filter by category, location and salary — apply free with one click on Jobs in USA.';
    }
@endphp
@section('title', $srTitle)
@section('meta_description', $srDesc)

@section('content')

<style>
    /* === Search results page === */
    .search-results-wrap {
        background: #f7f8fa;
        padding: 40px 0 60px;
        min-height: 60vh;
    }
    .search-results-wrap .container { max-width: 1280px; }
    .search-results-title {
        font-size: 22px;
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 18px;
        letter-spacing: -.3px;
    }

    /* Search form (top) */
    .search-results-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 12px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 6px 18px rgba(15,23,42,.05);
        margin-bottom: 28px;
    }
    @media (max-width: 900px) { .search-results-form { grid-template-columns: 1fr; } }
    .search-results-form .input-wrap { position: relative; }
    .search-results-form .input-wrap > i {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 16px; pointer-events: none;
    }
    .search-results-form input[type="text"],
    .search-results-form .select2-selection,
    .search-results-form select {
        width: 100%;
        height: 48px !important;
        background: #f8f9fb;
        border: 1px solid #e5e7eb !important;
        border-radius: 10px !important;
        padding: 0 38px 0 14px;
        font-size: 14.5px;
        color: #0a0a0a;
        outline: none;
    }
    .search-results-form input[type="text"]:focus,
    .search-results-form select:focus { border-color: #0a0a0a !important; }
    .search-results-form button {
        height: 48px;
        padding: 0 32px;
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: filter .15s ease, transform .12s ease;
    }
    .search-results-form button:hover { filter: brightness(1.05); transform: translateY(-1px); }

    /* Results bar */
    .results-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 22px;
    }
    .results-bar .count { color: #555; font-size: 14px; }
    .results-bar .count strong { color: #0a0a0a; font-weight: 700; }
    .results-bar .sort-by { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: #555; }
    .results-bar .sort-by select {
        height: 38px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 8px;
        padding: 0 30px 0 12px;
        font-size: 14px;
        font-weight: 600;
        color: #0a0a0a;
        cursor: pointer;
    }

    /* === Job cards grid (3 per row) === */
    .search-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    @media (max-width: 1100px) { .search-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .search-grid { grid-template-columns: 1fr; } }

    .sr-card {
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
    .sr-card:hover {
        border-color: #0a0a0a;
        box-shadow: 0 14px 32px rgba(15,23,42,.10);
        transform: translateY(-3px);
        text-decoration: none;
        color: inherit;
    }
    .sr-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .sr-card-logo {
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
    .sr-card-logo img { max-width: 80%; max-height: 80%; object-fit: contain; }
    .sr-card-badge {
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
    .sr-card-badge.green  { background: #047857; }
    .sr-card-badge.yellow { background: #b45309; }
    .sr-card-title {
        font-size: 16.5px;
        font-weight: 700;
        color: #0a0a0a;
        margin: 0 0 12px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 46px;
    }
    .sr-card-meta { list-style: none; padding: 0; margin: 0 0 18px; flex-grow: 1; }
    .sr-card-meta li {
        font-size: 13px;
        color: #555;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sr-card-meta li i { color: #1b3a6b; font-size: 14px; }
    .sr-card-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #0a0a0a;
        color: #fff !important;
        padding: 11px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        margin-top: auto;
        transition: background .2s ease;
        border: 1.5px solid #0a0a0a;
    }
    .sr-card:hover .sr-card-button { background: #1b3a6b; border-color: #1b3a6b; }

    /* Empty state */
    .sr-empty {
        text-align: center;
        padding: 60px 24px;
        background: #fff;
        border: 1px dashed #e5e7eb;
        border-radius: 14px;
        color: #555;
    }
    .sr-empty i { font-size: 48px; color: #c7c7cc; margin-bottom: 12px; display: inline-block; }
    .sr-empty h4 { color: #0a0a0a; font-weight: 700; font-size: 18px; margin: 6px 0 6px; }
    .sr-empty p  { color: #555; font-size: 14.5px; margin: 0; }

    /* Pagination wrap */
    .sr-pagination { margin-top: 32px; }

    /* === Dark mode === */
    html.dark-mode .search-results-wrap { background: var(--site-bg) !important; }
    html.dark-mode .search-results-title { color: #fff !important; }
    html.dark-mode .search-results-form,
    html.dark-mode .results-bar {
        background: var(--site-card-bg) !important;
        border-color: var(--site-card-bd) !important;
        box-shadow: var(--site-shadow) !important;
    }
    html.dark-mode .search-results-form input[type="text"],
    html.dark-mode .search-results-form select,
    html.dark-mode .search-results-form .select2-selection {
        background: var(--site-input-bg) !important;
        border-color: var(--site-input-bd) !important;
        color: var(--site-text) !important;
    }
    html.dark-mode .search-results-form .input-wrap > i { color: var(--site-muted) !important; }
    html.dark-mode .results-bar .count { color: #cbd5e1 !important; }
    html.dark-mode .results-bar .count strong { color: #fff !important; }
    html.dark-mode .results-bar .sort-by,
    html.dark-mode .results-bar .sort-by span { color: #cbd5e1 !important; }
    html.dark-mode .results-bar .sort-by select {
        background: var(--site-input-bg) !important;
        border-color: var(--site-input-bd) !important;
        color: var(--site-text) !important;
    }
    html.dark-mode .sr-card {
        background: var(--site-card-bg) !important;
        border-color: var(--site-card-bd) !important;
        color: var(--site-text) !important;
        box-shadow: var(--site-shadow) !important;
    }
    html.dark-mode .sr-card:hover {
        border-color: #1b3a6b !important;
        box-shadow: 0 14px 32px rgba(0,0,0,.55) !important;
    }
    html.dark-mode .sr-card-logo { background: rgba(255,255,255,.06) !important; }
    html.dark-mode .sr-card-title { color: #fff !important; }
    html.dark-mode .sr-card-meta li { color: #cbd5e1 !important; }
    html.dark-mode .sr-card-meta li i { color: #1b3a6b !important; }
    html.dark-mode .sr-card-badge.green  { background: rgba(16,185,129,.20) !important; color: #6ee7b7 !important; }
    html.dark-mode .sr-card-badge.yellow { background: rgba(251,191,36,.20) !important; color: #fcd34d !important; }
    html.dark-mode .sr-card-button {
        background: linear-gradient(135deg, #1b3a6b, #2f7fc9) !important;
        border-color: #1b3a6b !important;
    }
    html.dark-mode .sr-empty {
        background: var(--site-card-bg) !important;
        border-color: var(--site-card-bd) !important;
        color: var(--site-text) !important;
    }
    html.dark-mode .sr-empty h4 { color: #fff !important; }
    html.dark-mode .sr-empty p  { color: #cbd5e1 !important; }
    html.dark-mode .sr-empty i  { color: var(--site-muted) !important; }
</style>

<div class="search-results-wrap">
    <div class="container">
        <h1 class="search-results-title">
            Search Results for "{{ $keywords ?? 'All Jobs' }}"
            @if($selectedLocation)
                @if(is_numeric($selectedLocation))
                    {{ ' in ' . optional(\App\Models\Location::find($selectedLocation))->name }}
                @else
                    {{ ' in ' . $selectedLocation }}
                @endif
            @endif
        </h1>

        <form method="GET" action="{{ route('jobs.search') }}" class="search-results-form">
            <div class="input-wrap">
                <input type="text" name="keywords" placeholder="Job Title, Company, or Keywords" value="{{ $keywords ?? '' }}">
                <i class="icon-material-outline-search"></i>
            </div>
            <div class="input-wrap">
                <select class="select2" name="location" data-placeholder="All Locations">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->name }}" {{ $selectedLocation == $location->name ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Search</button>
        </form>

        <div class="results-bar">
            <div class="count">
                @if($jobs->total() > 0)
                    Found <strong>{{ $jobs->total() }}</strong> {{ Str::plural('job', $jobs->total()) }}
                @else
                    No jobs found matching your search criteria
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

        @if($jobs->count())
            <div class="search-grid">
                @foreach($jobs as $job)
                    <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? '')) ) }}" class="sr-card">
                        <div class="sr-card-top">
                            <div class="sr-card-logo">
                                <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}" alt="{{ $job->advertiser->name ?? 'Company' }}" loading="lazy">
                            </div>
                            <span class="sr-card-badge {{ ($job->employment_type ?? '') == 'Part Time' ? 'yellow' : 'green' }}">
                                <i class="icon-material-outline-business-center"></i> {{ $job->employment_type ?? 'Full Time' }}
                            </span>
                        </div>
                        <h3 class="sr-card-title">{{ $job->position }}</h3>
                        <ul class="sr-card-meta">
                            <li><i class="icon-feather-briefcase"></i> {{ $job->category?->name ?? ($job->advertiser->name ?? 'N/A') }}</li>
                            @if($job->location)
                                <li><i class="icon-material-outline-location-on"></i>
                                    {{ $job->location->name }}{{ $job->location->area ? ', ' . $job->location->area : '' }}{{ $job->location->postal_code ? ' (' . $job->location->postal_code . ')' : '' }}
                                </li>
                            @endif
                            <li><i class="icon-material-outline-access-time"></i> {{ $job->created_at->diffForHumans() }}</li>
                        </ul>
                        <span class="sr-card-button">View Job <i class="icon-line-awesome-bullhorn"></i></span>
                    </a>
                @endforeach
            </div>

            @if($jobs->hasPages())
                <div class="sr-pagination">
                    @push('meta')
                        @if($jobs->onFirstPage() === false)
                            <link rel="prev" href="{{ $jobs->previousPageUrl() }}">
                        @endif
                        @if($jobs->hasMorePages())
                            <link rel="next" href="{{ $jobs->nextPageUrl() }}">
                        @endif
                    @endpush
                    {{ $jobs->appends(request()->query())->onEachSide(0)->links('vendor.pagination.custom') }}
                </div>
            @endif
        @else
            <div class="sr-empty">
                <i class="icon-feather-search"></i>
                <h4>No jobs found</h4>
                <p>Try adjusting your search keywords or selecting a different location.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: $(this).data('placeholder')
        });

        $('#sortSelect').on('change', function() {
            window.location.href = $(this).val();
        });
    });
</script>
@endpush
