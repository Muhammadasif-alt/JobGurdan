@extends('user.layouts.master')
@section('title', 'Editorial Policy | JobGader')
@section('meta_description', 'How JobGader researches its guides, which sources it uses, how errors are corrected and how advertising is kept apart from what we write.')
@section('content')
<style>
    .doc-page { padding: 50px 0 70px; }
    .doc-page .doc-card { max-width: 860px; margin: 0 auto; background: #fff; border: 1px solid #ececec; border-radius: 16px; padding: 36px 40px; }
    .doc-page h1 { color: #1b3a6b; margin-bottom: 6px; }
    .doc-page h2 { font-size: 20px; color: #1b3a6b; margin: 28px 0 10px; }
    .doc-page p, .doc-page li { color: #374151; line-height: 1.7; }
    .doc-page .updated { display: block; color: #6b7280; font-size: 14px; margin-bottom: 20px; }
    html.dark-mode .doc-page .doc-card { background: #1f2937; border-color: #374151; }
    html.dark-mode .doc-page h1, html.dark-mode .doc-page h2 { color: #e5e7eb; }
    html.dark-mode .doc-page p, html.dark-mode .doc-page li { color: #d1d5db; }
</style>
<section class="doc-page">
    <div class="container">
        <div class="doc-card">
            <h1>Editorial Policy</h1>
            <span class="updated">Last updated: October 20, 2026</span>

            <h2>What we publish</h2>
            <p>JobGader publishes guides about jobs, apprenticeships, visas and work rules, and it links to job listings and official portals. Our guides are written to answer a specific question a job seeker has, such as what a route requires, what a role pays or how an application works.</p>

            <h2>How a guide is researched</h2>
            <p>Our standard is that every fact a guide depends on, such as a fee, a salary figure, a deadline or an eligibility rule, comes from an official or primary source and is named in the guide. Where we cannot check a claim, we leave it out or say that it could not be confirmed. We do not repeat numbers from job boards or from other blogs as if they were official.</p>

            <h2>Sources we use</h2>
            <ul>
                <li>Government and regulator websites, such as immigration, labour and statistics agencies.</li>
                <li>Public employers, public job portals and apprenticeship services.</li>
                <li>Professional bodies and exam or certification providers for their own requirements.</li>
                <li>Employer career pages, only for what an employer says about itself.</li>
            </ul>
            <p>Each guide has a Sources list, and links to commercial job boards are not used as evidence.</p>

            <h2>Reviews and updates</h2>
            <p>Rules change, so a guide is only as good as its date. Newer guides state the date they were reviewed. We update a guide when a rule it relies on changes or when a reader reports an error, and we are reviewing our older guides so that those that do not meet this policy are updated, merged or removed from search.</p>

            <h2>Corrections</h2>
            <p>If you find a mistake, tell us through the <a href="{{ route('contact.us') }}">Contact page</a>. Please say which guide and which statement, and send the official source if you have one. We check it against the source and correct the guide.</p>

            <h2>Independence and money</h2>
            <p>JobGader earns money from display advertising and from an optional paid CV writing service. We do not sell guides or job listings, and ads or paid services do not decide what a guide says about a route or an employer. We never ask job seekers to pay to apply.</p>

            <h2>Who is responsible</h2>
            <p>Guides are published by the JobGader editorial team. See the <a href="{{ route('about.us') }}">About page</a> for who runs the site, and the <a href="{{ route('pages.disclaimer') }}">Disclaimer</a> for what our content does and does not promise.</p>
        </div>
    </div>
</section>
@endsection
