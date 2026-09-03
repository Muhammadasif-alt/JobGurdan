@extends('user.layouts.master')
@section('title', 'Jobs in Florida — Tourism, Health and Logistics | JobGader')
@section('meta_description', 'Jobs in Florida: hospitality, healthcare, aerospace and logistics hiring across Miami, Orlando and Tampa, what they pay, and the seasonal cycle.')
@section('og_title', 'Jobs in Florida — Tourism, Health and Logistics | JobGader')
@section('og_description', 'Jobs in Florida: hospitality, healthcare, aerospace and logistics hiring across Miami, Orlando and Tampa, what they pay, and the seasonal cycle.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Florida',
        'intro' => [
            'Florida runs on tourism, healthcare and trade, and its hiring is more seasonal than most states. Knowing when each sector recruits is worth as much as knowing which employers are hiring.',
            'This page covers what is actually hiring across Florida, what the main sectors pay, and how the seasonal cycle shapes when applications land best.',
        ],
        'sections' => [
            [
                'title' => 'Tourism Sets the Calendar',
                'paragraphs' => [
                    'Hospitality is the state largest visible employer, concentrated in Orlando theme parks, Miami hotels and the coastal resort markets. Recruitment ramps from late autumn into the winter high season, and again before summer.',
                    'These roles are the most accessible entry point in the state, need no qualification, and are genuinely competitive on hours if not on hourly rate. Seasonal contracts convert to permanent at a meaningful rate in the larger operators.',
                ],
            ],
            [
                'title' => 'Healthcare Is the Bigger Employer',
                'paragraphs' => [
                    'Florida has one of the oldest populations in the country, which makes healthcare and elderly care the deepest and most stable job market in the state — nursing, home health aides, care assistants and clinical support are all in continuous demand.',
                    'This is also the sector where credentials pay off fastest. A certified nursing assistant qualification takes weeks and moves you meaningfully above hospitality pay.',
                ],
            ],
            [
                'title' => 'Aerospace, Ports and Logistics',
                'paragraphs' => [
                    'The Space Coast around Cape Canaveral supports a substantial aerospace and engineering sector, and it has grown rather than shrunk with commercial launch activity.',
                    'Miami and Jacksonville are major trade gateways, particularly for Latin American commerce, which sustains warehousing, freight and customs roles. Spanish is a genuine advantage across much of South Florida rather than a bonus.',
                ],
            ],
        ],
        'jobRoles' => [
            'Hotel Housekeeper',
            'Front Desk Agent',
            'Certified Nursing Assistant',
            'Home Health Aide',
            'Warehouse Associate',
            'Aerospace Technician',
            'Customer Service Representative',
            'Delivery Driver',
        ],
        'faqs' => [
            [
                'q' => 'When is the best time to apply for jobs in Florida?',
                'a' => 'For hospitality, from early autumn ahead of the winter high season, and again in spring before summer. Healthcare and logistics hire year-round.',
            ],
            [
                'q' => 'Which sector has the most stable work?',
                'a' => 'Healthcare. Florida has one of the oldest populations in the country, which keeps nursing, home health and care support in continuous demand regardless of the tourist season.',
            ],
            [
                'q' => 'Does Florida have a state income tax?',
                'a' => 'No personal state income tax, which raises take-home pay relative to states that levy one. Insurance costs, particularly for housing, are high by national standards.',
            ],
            [
                'q' => 'Is Spanish useful for jobs in Florida?',
                'a' => 'In South Florida it is a real advantage rather than a nice-to-have, particularly in customer-facing, healthcare and logistics roles serving Latin American trade.',
            ],
            [
                'q' => 'Is applying free?',
                'a' => 'Yes, with no account needed. Listings link through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse Florida Jobs',
        'filterType' => 'state',
        'filterValue' => 'Florida',
        'accentText' => 'Florida',
        'eyebrow' => 'Jobs in Florida',
    ])
@endsection
