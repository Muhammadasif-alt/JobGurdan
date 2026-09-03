@extends('user.layouts.master')
@section('title', 'Truck Driver Jobs — CDL, Pay and Visa Routes | JobGader')
@section('meta_description', 'Truck driver jobs in the USA and UK: what CDL and HGV drivers earn, how licensing works for foreign drivers, and which visa routes are real.')
@section('og_title', 'Truck Driver Jobs — CDL, Pay and Visa Routes | JobGader')
@section('og_description', 'Truck driver jobs in the USA and UK: what CDL and HGV drivers earn, how licensing works for foreign drivers, and which visa routes are real.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Truck Driver Jobs',
        'intro' => [
            'Trucking is one of the few well-paid trades with a genuine and persistent shortage of workers on both sides of the Atlantic, and one of the few where visa sponsorship for foreign drivers is real rather than imaginary.',
            'This page collects driving and transport openings from our listings, with what the different types of haulage pay, how licensing works if you trained abroad, and which sponsorship routes actually exist.',
        ],
        'sections' => [
            [
                'title' => 'What Drivers Earn',
                'paragraphs' => [
                    'In the US, company CDL-A drivers are commonly advertised between $60,000 and $85,000 a year, with specialised freight — tanker, flatbed, hazmat, oversize — paying above that. Pay is often quoted per mile rather than per year, so check the expected weekly mileage before comparing offers.',
                    'In the UK, HGV Class 1 drivers commonly earn £35,000 to £45,000, with night trunking and specialist work higher. In Saudi Arabia and the Gulf, heavy truck drivers are commonly advertised around SAR 1,500 to 2,500 a month plus allowances, usually with accommodation provided.',
                ],
            ],
            [
                'title' => 'Licensing If You Trained Abroad',
                'paragraphs' => [
                    'A foreign licence rarely transfers directly. In the US, a CDL is issued state by state, requires residency and a medical certificate, and for most foreign drivers means retaking testing locally. In the UK, an HGV licence requires the Driver CPC alongside the vocational entitlement.',
                    'Budget time and money for this and confirm the exact requirement before you travel, not after. It is the single most common thing overseas drivers underestimate.',
                ],
            ],
            [
                'title' => 'Where Sponsorship Is Genuine',
                'paragraphs' => [
                    'US trucking does sponsor foreign drivers, principally through EB-3 for permanent positions and H-2B for temporary seasonal work, and some carriers run structured international recruitment programmes. This is one of the few blue-collar categories where the route is real.',
                    'The UK position is tighter but HGV driving has at times been treated differently from other transport work under shortage arrangements, and the rules have moved more than once. Check the current position on gov.uk rather than relying on an agency description of it.',
                ],
            ],
            [
                'title' => 'Types of Haulage Are Different Jobs',
                'paragraphs' => [
                    'Over-the-road work means weeks away from home and pays accordingly. Regional runs get you home most weekends. Local and day-cab work gets you home nightly and pays the least of the three. None is better than the others in the abstract — they are different lives.',
                    'Endorsements are where the money is. Hazmat, tanker and doubles or triples endorsements in the US, and ADR in the UK, each lift the rate meaningfully and cost far less to obtain than the increase they unlock.',
                ],
            ],
        ],
        'jobRoles' => [
            'CDL-A Truck Driver',
            'HGV Class 1 Driver',
            'Delivery Driver',
            'Tanker Driver',
            'Flatbed Driver',
            'Local Route Driver',
            'Owner Operator',
            'Heavy Truck Driver',
        ],
        'faqs' => [
            [
                'q' => 'Can foreign drivers get sponsored to drive in the USA?',
                'a' => 'Yes, and it is one of the few genuine blue-collar sponsorship categories. EB-3 covers permanent positions and H-2B covers temporary seasonal work, both employer-driven. Our truck driver guide explains how each runs.',
            ],
            [
                'q' => 'Does my home country licence transfer?',
                'a' => 'Rarely directly. A US CDL is issued by the state and generally requires residency, a medical certificate and local testing. A UK HGV licence requires the Driver CPC alongside the vocational entitlement.',
            ],
            [
                'q' => 'Which trucking jobs pay the most?',
                'a' => 'Specialised freight — tanker, hazmat, flatbed and oversize — along with over-the-road work that keeps you away from home for extended periods. Endorsements typically pay for themselves quickly.',
            ],
            [
                'q' => 'What is the difference between OTR, regional and local driving?',
                'a' => 'Time at home, essentially. Over-the-road means weeks away and the highest pay, regional gets you home most weekends, and local day-cab work gets you home nightly at the lowest rate.',
            ],
            [
                'q' => 'Should I pay an agency for a trucking visa?',
                'a' => 'No. Legitimate international driver recruitment is funded by the carrier. An upfront fee for a visa or a job offer is the standard pattern of a scam in this sector.',
            ],
        ],
        'ctaText' => 'Browse Truck Driver Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['truck driver', 'CDL', 'driver', 'trucking'],
        'accentText' => 'Truck Driver',
        'eyebrow' => 'Transport &amp; Driving',
    ])
@endsection
