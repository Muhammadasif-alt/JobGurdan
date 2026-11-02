@extends('user.layouts.master')
@section('title', 'Sitemap | JobGader')
@section('meta_description', 'Every JobGader guide, scholarship and main page in one list, grouped by topic.')
@section('content')
<style>
    .doc-page { padding: 50px 0 70px; }
    .doc-page .doc-card { max-width: 1000px; margin: 0 auto; background: #fff; border: 1px solid #ececec; border-radius: 16px; padding: 36px 40px; }
    .doc-page h1 { color: #1b3a6b; margin-bottom: 6px; }
    .doc-page h2 { font-size: 20px; color: #1b3a6b; margin: 28px 0 10px; }
    .doc-page p, .doc-page li { color: #374151; line-height: 1.7; }
    .doc-page ul.sitemap-list { columns: 2; column-gap: 32px; padding-left: 18px; }
    .doc-page ul.sitemap-list li { break-inside: avoid; }
    @media (max-width: 767px) { .doc-page .doc-card { padding: 24px 18px; } .doc-page ul.sitemap-list { columns: 1; } }
    html.dark-mode .doc-page .doc-card { background: #1f2937; border-color: #374151; }
    html.dark-mode .doc-page h1, html.dark-mode .doc-page h2 { color: #e5e7eb; }
    html.dark-mode .doc-page p, html.dark-mode .doc-page li { color: #d1d5db; }
</style>
<section class="doc-page">
    <div class="container">
        <div class="doc-card">
            <h1>Sitemap</h1>
            <p>Every guide, scholarship and main page on JobGader, grouped by topic.</p>

            <h2>Main pages</h2>
            <ul class="sitemap-list">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('jobs.index') }}">Jobs</a></li>
                <li><a href="{{ route('jobs.portals') }}">Job Portals &amp; Resources</a></li>
                <li><a href="{{ route('jobs.categories') }}">Job Categories</a></li>
                <li><a href="{{ url('/locations') }}">Job Locations</a></li>
                <li><a href="{{ route('blog.index') }}">Career Guides</a></li>
                <li><a href="{{ route('scholarships.index') }}">Scholarships</a></li>
                <li><a href="{{ url('/about-us') }}">About Us</a></li>
                <li><a href="{{ route('contact.us') }}">Contact</a></li>
                <li><a href="{{ route('pages.editorial-policy') }}">Editorial Policy</a></li>
                <li><a href="{{ route('pages.disclaimer') }}">Disclaimer</a></li>
                <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                <li><a href="{{ url('/terms-of-service') }}">Terms of Service</a></li>
            </ul>

            @foreach ($categories as $category)
                <h2>{{ $category->name }} ({{ $category->blogs->count() }})</h2>
                <ul class="sitemap-list">
                    @foreach ($category->blogs as $post)
                        <li><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></li>
                    @endforeach
                </ul>
            @endforeach

            @if ($scholarships->isNotEmpty())
                <h2>Scholarships ({{ $scholarships->count() }})</h2>
                <ul class="sitemap-list">
                    @foreach ($scholarships as $scholarship)
                        <li><a href="{{ route('scholarships.show', $scholarship->slug) }}">{{ $scholarship->title }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>
@endsection
