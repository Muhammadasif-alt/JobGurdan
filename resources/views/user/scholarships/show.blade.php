@extends('user.layouts.master')
@php
    $pageUrl = route('scholarships.show', $scholarship->slug);
    $posterUrl = $scholarship->imageUrl();
    $summary = $scholarship->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $scholarship->content), 160);
    $location = $scholarship->city ? $scholarship->city.', '.$scholarship->country : $scholarship->country;
@endphp
@section('title', filled($scholarship->meta_title) ? $scholarship->meta_title : $scholarship->title.' | JobGader')
@section('meta_description', filled($scholarship->meta_description) ? $scholarship->meta_description : $summary)
@section('meta_keywords', $scholarship->provider.' scholarship, scholarships in '.$scholarship->country.', '.$scholarship->study_level.' scholarship')
@section('og_title', $scholarship->title)
@section('og_description', $summary)
@section('og_image', $posterUrl)
@section('canonical', $pageUrl)

@push('meta')
    @php
        $faqs = app(\App\Services\StructuredDataService::class)->faqsFromHtml($scholarship->content);
    @endphp
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $posterUrl }}">

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Scholarships', 'item' => route('scholarships.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $scholarship->title],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- FAQ markup is built from the guide's own FAQ section, so it always matches what a reader sees. --}}
    @if (count($faqs) >= 2)
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn (array $faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ], $faqs),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif
@endpush

@section('content')
<style>
    .scholar-detail-hero {
        position: relative;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 55%, #f5f5f7 100%);
        padding: 48px 0 44px;
        border-bottom: 1px solid #f0f0f3;
    }
    .scholar-crumbs { font-size: 13px; color: #777; margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 6px; }
    .scholar-crumbs a { color: #1b3a6b; font-weight: 600; text-decoration: none; }
    .scholar-crumbs a:hover { text-decoration: underline; }
    .scholar-crumbs .sep { color: #c7c7cc; }
    .scholar-detail-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .scholar-tag { display: inline-flex; align-items: center; gap: 5px; background: #fff; border: 1px solid #e5e7eb; color: #1b3a6b; font-size: 12.5px; font-weight: 700; padding: 6px 13px; border-radius: 999px; }
    .scholar-tag-gold { background: #fff7df; border-color: #f5d67a; color: #7a5500; }
    .scholar-detail-hero h1 { font-size: clamp(28px, 3.6vw, 44px); font-weight: 800; color: #1b3a6b; line-height: 1.15; letter-spacing: -.8px; margin: 0 0 12px; max-width: 900px; }
    .scholar-detail-provider { color: #555; font-size: 16px; margin: 0 0 24px; }
    .scholar-detail-provider strong { color: #1b3a6b; }
    .scholar-detail-actions { display: flex; flex-wrap: wrap; gap: 12px; }
    .scholar-apply-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #16a34a;
        color: #fff !important;
        font-weight: 700;
        font-size: 15.5px;
        padding: 14px 28px;
        border-radius: 12px;
        text-decoration: none !important;
        box-shadow: 0 8px 18px rgba(22, 163, 74, .25);
        transition: transform .15s ease, background .15s ease;
    }
    .scholar-apply-btn:hover { background: #15803d; transform: translateY(-1px); }
    .scholar-guide-btn { display: inline-flex; align-items: center; padding: 14px 24px; border-radius: 12px; border: 1.5px solid #1b3a6b; color: #1b3a6b !important; font-weight: 700; font-size: 15px; text-decoration: none !important; transition: background .15s ease; }
    .scholar-guide-btn:hover { background: #1b3a6b; color: #fff !important; }

    .scholar-detail-section { padding: 50px 0 60px; background: #fff; }
    .scholar-poster { margin: 0 0 30px; border-radius: 16px; overflow: hidden; box-shadow: 0 18px 40px rgba(15, 23, 42, .10); }
    .scholar-poster img { display: block; width: 100%; height: auto; }

    .scholar-content { font-size: 16px; line-height: 1.85; color: #2a2a2a; }
    .scholar-content p { margin: 0 0 18px; }
    .scholar-content h2 { font-size: clamp(22px, 2.6vw, 28px); font-weight: 800; color: #1b3a6b; line-height: 1.3; letter-spacing: -.4px; margin: 42px 0 16px; padding-left: 14px; position: relative; }
    .scholar-content h2::before { content: ""; position: absolute; left: 0; top: 8px; width: 4px; height: 22px; background: #1b3a6b; border-radius: 2px; }
    .scholar-content h3 { font-size: 19px; font-weight: 800; color: #1b3a6b; line-height: 1.35; margin: 26px 0 10px; }
    .scholar-content ul, .scholar-content ol { margin: 0 0 22px; padding-left: 22px; }
    .scholar-content li { margin-bottom: 8px; line-height: 1.75; }
    .scholar-content ul li::marker { color: #3182ce; }
    .scholar-content ol li::marker { color: #1b3a6b; font-weight: 800; }
    .scholar-content a { color: #1b3a6b; font-weight: 600; text-decoration: none; border-bottom: 1.5px solid #1b3a6b; }
    .scholar-content a:hover { opacity: .7; }
    .scholar-content strong { color: #1b3a6b; }
    .scholar-table { overflow-x: auto; margin: 0 0 24px; border: 1px solid #e5e7eb; border-radius: 12px; }
    .scholar-content table { width: 100%; border-collapse: collapse; font-size: 15px; min-width: 520px; margin: 0; }
    .scholar-content th, .scholar-content td { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; line-height: 1.55; }
    .scholar-content th { background: #f3f7fc; color: #1b3a6b; font-weight: 700; }
    .scholar-content tr:last-child td { border-bottom: 0; }
    .scholar-content tr:nth-child(even) td { background: #fafcff; }
    .scholar-note { background: #f3f7fc; border-left: 4px solid #3182ce; border-radius: 0 12px 12px 0; padding: 16px 20px; margin: 0 0 24px; }
    .scholar-note p:last-child { margin-bottom: 0; }
    .scholar-note-warn { background: #fff8e6; border-left-color: #f5b301; }
    .scholar-figure { margin: 30px 0; }
    .scholar-figure img { display: block; width: 100%; height: auto; border-radius: 14px; box-shadow: 0 12px 30px rgba(15, 23, 42, .10); }
    .scholar-figure figcaption { font-size: 13.5px; color: #6b7280; text-align: center; margin-top: 10px; line-height: 1.5; }

    .scholar-apply-box { margin-top: 40px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; background: #f3f7fc; border: 1px solid #dce8f5; border-radius: 16px; padding: 26px 28px; }
    .scholar-apply-box h2 { margin: 0 0 6px; font-size: 22px; font-weight: 800; color: #1b3a6b; }
    .scholar-apply-box p { margin: 0; color: #5a6b7f; font-size: 15px; max-width: 460px; }

    .scholar-side { position: sticky; top: 100px; display: flex; flex-direction: column; gap: 20px; }
    @media (max-width: 991px) { .scholar-side { position: static; margin-top: 36px; } }
    .scholar-side-card { background: #fff; border: 1px solid #ececec; border-radius: 16px; padding: 24px; box-shadow: 0 10px 26px rgba(15, 23, 42, .05); }
    .scholar-side-card h2 { font-size: 18px; font-weight: 800; color: #1b3a6b; margin: 0 0 12px; }
    .scholar-facts { margin: 0 0 18px; }
    .scholar-facts > div { display: flex; justify-content: space-between; gap: 14px; padding: 10px 0; border-bottom: 1px solid #f1f3f6; }
    .scholar-facts dt { color: #6b7280; font-size: 13.5px; font-weight: 600; flex-shrink: 0; }
    .scholar-facts dd { margin: 0; color: #1b3a6b; font-size: 14px; font-weight: 700; text-align: right; }
    .scholar-facts dd small { display: block; font-weight: 500; color: #6b7280; font-size: 12.5px; }
    .scholar-apply-block { width: 100%; }
    .scholar-side-note { margin: 10px 0 0; font-size: 12.5px; color: #6b7280; text-align: center; }
    .scholar-side-cv p { color: #5a6b7f; font-size: 14.5px; line-height: 1.6; margin: 0 0 12px; }
    .scholar-side-cv a { color: #1b3a6b; font-weight: 700; text-decoration: none; }

    .scholar-more-section { padding: 60px 0 70px; background: #f7fafd; border-top: 1px solid #ececec; }
    .scholar-more-title { font-size: clamp(24px, 2.8vw, 32px); font-weight: 800; color: #1b3a6b; margin: 0 0 28px; text-align: center; }
</style>

<section class="scholar-detail-hero">
    <div class="container">
        <nav class="scholar-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep" aria-hidden="true">&rsaquo;</span>
            <a href="{{ route('scholarships.index') }}">Scholarships</a>
            <span class="sep" aria-hidden="true">&rsaquo;</span>
            <span>{{ $scholarship->provider }}</span>
        </nav>
        <div class="scholar-detail-tags">
            <span class="scholar-tag"><i class="icon-material-outline-location-on" aria-hidden="true"></i>{{ $location }}</span>
            @if ($scholarship->funding_type)
                <span class="scholar-tag scholar-tag-gold">{{ $scholarship->funding_type }}</span>
            @endif
        </div>
        <h1>{{ $scholarship->title }}</h1>
        <p class="scholar-detail-provider">Offered by <strong>{{ $scholarship->provider }}</strong></p>
        <div class="scholar-detail-actions">
            <a href="{{ $scholarship->apply_url }}" class="scholar-apply-btn" target="_blank" rel="noopener">Apply Now <i class="icon-feather-external-link" aria-hidden="true"></i></a>
            <a href="#scholarship-guide" class="scholar-guide-btn">Read the Full Guide</a>
        </div>
    </div>
</section>

<section class="scholar-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="scholar-article" id="scholarship-guide">
                    <figure class="scholar-poster">
                        <img src="{{ $posterUrl }}" alt="{{ $scholarship->title }} poster" width="1200" height="628" fetchpriority="high">
                    </figure>

                    <div class="scholar-content">
                        {!! $scholarship->content !!}
                    </div>

                    <div class="scholar-apply-box">
                        <div>
                            <h2>Ready to apply?</h2>
                            <p>You apply on {{ $scholarship->provider }}&rsquo;s own website. Check the dates and requirements there before you submit, as they can change after this guide was written.</p>
                        </div>
                        <a href="{{ $scholarship->apply_url }}" class="scholar-apply-btn" target="_blank" rel="noopener">Apply Now <i class="icon-feather-external-link" aria-hidden="true"></i></a>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <aside class="scholar-side">
                    <div class="scholar-side-card">
                        <h2>At a Glance</h2>
                        <dl class="scholar-facts">
                            @if ($scholarship->award_value)
                                <div><dt>Value</dt><dd>{{ $scholarship->award_value }}</dd></div>
                            @endif
                            <div><dt>Study level</dt><dd>{{ $scholarship->study_level }}</dd></div>
                            <div><dt>University</dt><dd>{{ $scholarship->provider }}</dd></div>
                            <div><dt>Location</dt><dd>{{ $location }}</dd></div>
                            @if ($scholarship->funding_type)
                                <div><dt>Funding</dt><dd>{{ $scholarship->funding_type }}</dd></div>
                            @endif
                            <div>
                                <dt>Deadline</dt>
                                <dd>
                                    {{ $scholarship->deadlineLabel() }}
                                    @if ($scholarship->deadline && filled($scholarship->deadline_note) && ! $scholarship->hasClosed())
                                        <small>{{ $scholarship->deadline_note }}</small>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                        <a href="{{ $scholarship->apply_url }}" class="scholar-apply-btn scholar-apply-block" target="_blank" rel="noopener">Apply Now <i class="icon-feather-external-link" aria-hidden="true"></i></a>
                        <p class="scholar-side-note">Opens the official page in a new tab.</p>
                    </div>

                    <div class="scholar-side-card scholar-side-cv">
                        <h2>Need an Academic CV?</h2>
                        <p>Research scholarships ask for a short academic CV. Send us yours for a free review before you apply.</p>
                        <a href="{{ route('resume-writing') }}#resume-enquiry">Get a Free CV Review <i class="icon-feather-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

@if ($moreScholarships->isNotEmpty())
    <section class="scholar-more-section" aria-labelledby="more-scholarships-heading">
        <div class="container">
            <h2 class="scholar-more-title" id="more-scholarships-heading">More Scholarships</h2>
            <div class="scholar-grid">
                @foreach ($moreScholarships as $moreScholarship)
                    @include('user.scholarships.partials.card', ['scholarship' => $moreScholarship])
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
