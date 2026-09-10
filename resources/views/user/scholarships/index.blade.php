@extends('user.layouts.master')
@php
    $isFiltered = $keyword !== '' || $country !== '' || $level !== '';
    $onFirstPage = $scholarships->currentPage() === 1;
@endphp
@section('title', $onFirstPage ? 'Scholarships for International Students | JobGader' : 'Scholarships for International Students, Page '.$scholarships->currentPage().' | JobGader')
@section('meta_description', 'Scholarships to study abroad, explained in plain English: what each one pays, who can apply, the deadlines and how to apply on the official page.')
@section('meta_keywords', 'scholarships for international students, study abroad scholarships, funded scholarships, PhD scholarships, masters scholarships, RTP scholarship Australia')
@section('canonical', $onFirstPage ? route('scholarships.index') : $scholarships->url($scholarships->currentPage()))
@if ($isFiltered)
    @section('meta_robots', 'noindex, follow')
@endif

@push('meta')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Scholarships', 'item' => route('scholarships.index')],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
<style>
    .scholar-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%);
        padding: 70px 0 50px;
        border-bottom: 1px solid #f0f0f3;
        text-align: center;
    }
    .scholar-eyebrow {
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
    }
    .scholar-hero h1 {
        font-size: clamp(30px, 4.4vw, 52px);
        font-weight: 800;
        color: #1b3a6b;
        line-height: 1.1;
        letter-spacing: -1.2px;
        margin: 0 auto 18px;
        max-width: 920px;
    }
    .scholar-hero h1 .accent {
        background: linear-gradient(90deg, #1b3a6b, #4a90d9);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .scholar-hero p { color: #555; font-size: clamp(15px, 1.5vw, 17px); line-height: 1.65; max-width: 720px; margin: 0 auto 30px; }
    .scholar-hero-stats {
        display: inline-flex;
        gap: 32px;
        padding: 18px 34px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, .05);
        flex-wrap: wrap;
        justify-content: center;
    }
    .scholar-hero-stats strong { display: block; font-size: 24px; font-weight: 800; color: #1b3a6b; line-height: 1.1; }
    .scholar-hero-stats span { font-size: 11.5px; text-transform: uppercase; letter-spacing: 1.2px; color: #777; font-weight: 600; }

    .scholar-search-section { padding: 28px 0 0; background: #fff; }
    .scholar-search {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 10px;
        background: #1b3a6b;
        border-radius: 14px;
        padding: 20px 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
    }
    @media (max-width: 991px) {
        .scholar-search { grid-template-columns: 1fr 1fr; }
        .scholar-field-wide, .scholar-search-btn { grid-column: 1 / -1; }
    }
    @media (max-width: 575px) { .scholar-search { grid-template-columns: 1fr; } }
    .scholar-field { position: relative; }
    .scholar-field i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 17px; pointer-events: none; z-index: 2; }
    .scholar-field input,
    .scholar-field select {
        width: 100%;
        height: 48px;
        border: 1px solid #e5e5e7;
        border-radius: 10px;
        background: #fff;
        color: #16305a;
        font-size: 14.5px;
        padding: 0 14px 0 40px;
        margin: 0;
        box-shadow: none;
    }
    .scholar-search-btn {
        height: 48px;
        border: 0;
        border-radius: 10px;
        background: #fff;
        color: #1b3a6b;
        font-weight: 700;
        font-size: 14.5px;
        padding: 0 22px;
        cursor: pointer;
        white-space: nowrap;
        transition: background .15s ease;
    }
    .scholar-search-btn:hover { background: #f5f5f7; }
    .scholar-sr { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0; }

    .scholar-list-section { padding: 10px 0 70px; background: #fff; }
    .scholar-results-head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 24px 0 18px; }
    .scholar-results-count { margin: 0; color: #555; font-size: 14.5px; }
    .scholar-results-count strong { color: #1b3a6b; }
    .scholar-clear { color: #1b3a6b; font-weight: 700; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .scholar-pagination { margin-top: 36px; }
    .scholar-empty { text-align: center; padding: 56px 24px; border: 1px dashed #d8dee8; border-radius: 16px; background: #fafcff; }
    .scholar-empty i { font-size: 46px; color: #c7c7cc; }
    .scholar-empty h2 { font-size: 20px; color: #1b3a6b; margin: 14px 0 6px; font-weight: 800; }
    .scholar-empty p { color: #6b7280; margin: 0 0 18px; }
    .scholar-empty-btn { display: inline-block; background: #1b3a6b; color: #fff !important; padding: 12px 22px; border-radius: 10px; font-weight: 700; text-decoration: none !important; }
</style>

<section class="scholar-hero">
    <div class="container">
        <span class="scholar-eyebrow" data-aos="fade-down" data-aos-duration="600">{{ $isFiltered ? 'Search Results' : 'Scholarships' }}</span>
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">Scholarships to <span class="accent">Study Abroad</span></h1>
        <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">Each scholarship explained in plain English &mdash; what it pays, who can apply, the deadlines and the exact steps &mdash; with a link to apply on the official page.</p>
        <div class="scholar-hero-stats">
            <div>
                <strong>{{ number_format($totalScholarships) }}</strong>
                <span>{{ \Illuminate\Support\Str::plural('Scholarship', $totalScholarships) }}</span>
            </div>
            <div>
                <strong>{{ $countries->count() }}</strong>
                <span>{{ \Illuminate\Support\Str::plural('Country', $countries->count()) }}</span>
            </div>
            <div>
                <strong>100%</strong>
                <span>Free to Use</span>
            </div>
        </div>
    </div>
</section>

<section class="scholar-search-section">
    <div class="container">
        <form method="GET" action="{{ route('scholarships.index') }}" class="scholar-search" role="search">
            <div class="scholar-field scholar-field-wide">
                <i class="icon-feather-search" aria-hidden="true"></i>
                <label for="scholar-q" class="scholar-sr">Keyword</label>
                <input type="text" id="scholar-q" name="q" value="{{ $keyword }}" placeholder="University, country or scholarship name">
            </div>
            <div class="scholar-field">
                <i class="icon-material-outline-location-on" aria-hidden="true"></i>
                <label for="scholar-country" class="scholar-sr">Country</label>
                <select id="scholar-country" name="country">
                    <option value="">All Countries</option>
                    @foreach ($countries as $countryOption)
                        <option value="{{ $countryOption }}" @selected($country === $countryOption)>{{ $countryOption }}</option>
                    @endforeach
                </select>
            </div>
            <div class="scholar-field">
                <i class="icon-line-awesome-graduation-cap" aria-hidden="true"></i>
                <label for="scholar-level" class="scholar-sr">Study level</label>
                <select id="scholar-level" name="level">
                    <option value="">All Study Levels</option>
                    @foreach (\App\Models\Scholarship::STUDY_LEVELS as $levelKey => $levelOption)
                        <option value="{{ $levelKey }}" @selected($level === $levelKey)>{{ $levelOption['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="scholar-search-btn">Search Scholarships</button>
        </form>
    </div>
</section>

<section class="scholar-list-section" aria-label="Scholarship results">
    <div class="container">
        <div class="scholar-results-head">
            <p class="scholar-results-count">
                @if ($scholarships->total() > 0)
                    Showing <strong>{{ $scholarships->firstItem() }}&ndash;{{ $scholarships->lastItem() }}</strong> of <strong>{{ $scholarships->total() }}</strong> {{ \Illuminate\Support\Str::plural('scholarship', $scholarships->total()) }}
                @endif
            </p>
            @if ($isFiltered)
                <a href="{{ route('scholarships.index') }}" class="scholar-clear"><i class="icon-feather-x" aria-hidden="true"></i> Clear search</a>
            @endif
        </div>

        @if ($scholarships->isNotEmpty())
            <div class="scholar-grid">
                @foreach ($scholarships as $scholarship)
                    @include('user.scholarships.partials.card', ['scholarship' => $scholarship])
                @endforeach
            </div>

            @if ($scholarships->hasPages())
                @push('meta')
                    @if (! $scholarships->onFirstPage())
                        <link rel="prev" href="{{ $scholarships->previousPageUrl() }}">
                    @endif
                    @if ($scholarships->hasMorePages())
                        <link rel="next" href="{{ $scholarships->nextPageUrl() }}">
                    @endif
                @endpush
                <div class="scholar-pagination">
                    {{ $scholarships->onEachSide(1)->links() }}
                </div>
            @endif
        @else
            <div class="scholar-empty">
                <i class="icon-feather-search" aria-hidden="true"></i>
                <h2>No scholarships match your search</h2>
                <p>Try another keyword, or clear the filters to see every scholarship.</p>
                @if ($isFiltered)
                    <a href="{{ route('scholarships.index') }}" class="scholar-empty-btn">Show All Scholarships</a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection
