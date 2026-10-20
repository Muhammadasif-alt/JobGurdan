@extends('user.layouts.master')
@section('title', 'Disclaimer | JobGader')
@section('meta_description', 'JobGader is an information site, not an employer, recruiter or visa agent. Read what our guides and listings do and do not promise.')
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
            <h1>Disclaimer</h1>
            <span class="updated">Last updated: October 20, 2026</span>

            <h2>What JobGader is</h2>
            <p>JobGader is an independent information site. We are not an employer, a recruiter, a staffing agency or a visa or migration agent. We do not hire anyone, we do not recruit on behalf of employers, and we do not process visa or work permit applications.</p>

            <h2>Not legal, immigration or financial advice</h2>
            <p>Our guides give general information based on official sources, which we name in each guide. Rules about visas, work permits, wages and licensing change, and they differ from one person's situation to another. Always check the official source before you act, and speak to a licensed professional about your own case.</p>

            <h2>Listings and job portals</h2>
            <p>Each listing links to the employer or to the original posting. Some entries are links to official job portals, apprenticeship services or career resources rather than single vacancies, and we label those as <a href="{{ route('jobs.portals') }}">Job Portals &amp; Resources</a>. A listing, a portal or a guide does not mean an employer is hiring, will sponsor a visa or will offer you a job. We cannot guarantee that a listing is still open or that its details are correct.</p>

            <h2>External links</h2>
            <p>We link to government sites, employers and other third parties for your convenience. We do not control those sites, we do not endorse their content, and we are not responsible for their privacy practices or for what happens when you use them.</p>

            <h2>Never pay to apply</h2>
            <p>JobGader never asks job seekers for money to apply, and no genuine employer or agent should. If anyone asks you to pay for an interview, a guaranteed job or a visa, treat it as a scam. Our guide on <a href="{{ url('/blog/how-to-spot-a-fake-overseas-job-offer-2026') }}">how to spot a fake overseas job offer</a> explains the warning signs.</p>

            <h2>Advertising and paid services</h2>
            <p>JobGader earns money from display advertising and from an optional paid CV writing service. Ads and paid services do not decide what our guides say about a route or an employer. See our <a href="{{ route('pages.editorial-policy') }}">Editorial Policy</a> for how we work.</p>

            <h2>Errors and corrections</h2>
            <p>We try to be accurate, but mistakes happen. If you find one, tell us through the <a href="{{ route('contact.us') }}">Contact page</a> and we will check it against the official source.</p>
        </div>
    </div>
</section>
@endsection
