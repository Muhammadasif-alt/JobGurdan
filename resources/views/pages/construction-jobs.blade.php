@extends('user.layouts.master')
@section('title', 'Construction Jobs — Trades, Pay and Openings | JobGader')
@section('meta_description', 'Construction jobs across the USA and UK: hourly rates by trade, the cards and licences you need on site, and how to apply free with no account.')
@section('og_title', 'Construction Jobs — Trades, Pay and Openings | JobGader')
@section('og_description', 'Construction jobs across the USA and UK: hourly rates by trade, the cards and licences you need on site, and how to apply free with no account.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Construction Jobs',
        'intro' => [
            'Construction hires steadily and it hires by trade. General labouring gets you on site; a ticket, a card or a licence is what moves you up the pay scale, and most of them take weeks rather than years to obtain.',
            'This page collects construction and skilled trade openings from our listings, with what each trade pays and what documentation site managers will ask to see before you start.',
        ],
        'sections' => [
            [
                'title' => 'What the Trades Pay',
                'paragraphs' => [
                    'In the US, general construction labourers are commonly advertised around $17 to $24 an hour, with skilled trades — electricians, plumbers, welders, heavy equipment operators — frequently in the $25 to $40 range and higher on union and specialist industrial work. Overtime is a real part of the package on most sites, not a bonus.',
                    'In the UK, labourers with a CSCS card are typically £12 to £16 an hour, while qualified electricians, plumbers and groundworkers commonly reach £18 to £28 depending on region and whether the work is day rate or price work.',
                ],
            ],
            [
                'title' => 'The Cards and Tickets That Get You On Site',
                'paragraphs' => [
                    'In the UK, a CSCS card is effectively mandatory on commercial sites, and the level of card follows your qualification. CPCS or NPORS tickets cover plant and machinery, and specific tickets exist for forklift, telehandler, scaffolding and working at height.',
                    'In the US, OSHA 10 and OSHA 30 certifications are widely requested and cheap to obtain. Trades are licensed at state level rather than nationally, so an electrician licensed in one state usually needs to check reciprocity before working in another.',
                ],
            ],
            [
                'title' => 'Visa Sponsorship in Construction — the Honest Position',
                'paragraphs' => [
                    'US construction does have genuine routes for foreign workers, mainly the H-2B seasonal programme for temporary non-agricultural work and EB-3 for permanent positions, though both are capped and employer-driven. Our construction guide sets out how each one actually runs.',
                    'The UK is far more restrictive. General labouring does not meet Skilled Worker thresholds, and sponsorship in this sector is concentrated in site management, quantity surveying and engineering rather than on the tools. Anyone offering you a UK labouring visa for a fee is selling something that does not exist.',
                ],
            ],
            [
                'title' => 'What Site Managers Look For',
                'paragraphs' => [
                    'Reliability and turning up on time are stated more often than any technical skill in construction job ads, because both are genuinely the main problem employers face. A clean record of finishing contracts matters.',
                    'List your tickets, cards and their expiry dates at the top of your CV, along with whether you have your own tools and transport. Those four things decide most shortlists before anyone reads your experience.',
                ],
            ],
        ],
        'jobRoles' => [
            'Construction Labourer',
            'Carpenter',
            'Electrician',
            'Plumber',
            'Welder',
            'Heavy Equipment Operator',
            'Site Supervisor',
            'Groundworker',
        ],
        'faqs' => [
            [
                'q' => 'Do I need experience to start in construction?',
                'a' => 'Not for general labouring, which is the standard entry point and where most people start. Skilled trade roles need either an apprenticeship, a recognised qualification or a demonstrable track record on similar work.',
            ],
            [
                'q' => 'What is a CSCS card and do I need one?',
                'a' => 'It is the UK construction skills certification card, and on most commercial sites you will not be allowed to work without one. The card level follows your qualification, and the test and application are straightforward to arrange.',
            ],
            [
                'q' => 'Can I get construction work in the USA on a visa?',
                'a' => 'There are real routes, principally H-2B for temporary seasonal work and EB-3 for permanent roles, but both are employer-sponsored and capped. Our construction guide explains what each requires and what it does not cover.',
            ],
            [
                'q' => 'Is overtime normal in construction?',
                'a' => 'Yes, on most sites, and it is often where a meaningful part of the earnings comes from. Ask how overtime is paid before accepting, since day rate and hourly contracts treat it very differently.',
            ],
            [
                'q' => 'Should I pay an agency to find me construction work?',
                'a' => 'No. Legitimate construction agencies are paid by the employer, not by you. An upfront fee to secure work or a visa is the clearest warning sign in this sector.',
            ],
        ],
        'ctaText' => 'Browse Construction Jobs',
        'filterType' => 'category',
        'filterValue' => 'Construction',
        'accentText' => 'Construction',
        'eyebrow' => 'Construction &amp; Trades',
    ])
@endsection
