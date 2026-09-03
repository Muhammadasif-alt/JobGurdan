@extends('user.layouts.master')
@section('title', 'Jobs in Arizona — Semiconductors, Logistics, Care | JobGader')
@section('meta_description', 'Jobs in Arizona: semiconductor and construction growth around Phoenix, distribution, healthcare and tourism, and what each sector pays.')
@section('og_title', 'Jobs in Arizona — Semiconductors, Logistics, Care | JobGader')
@section('og_description', 'Jobs in Arizona: semiconductor and construction growth around Phoenix, distribution, healthcare and tourism, and what each sector pays.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Arizona',
        'intro' => [
            'Arizona has been one of the fastest-growing state economies in the country, and the growth is concentrated in three things: semiconductor manufacturing, distribution, and the construction needed to keep up with both.',
            'This page covers what is hiring across Arizona, what the main sectors pay, and how the climate shapes working patterns here.',
        ],
        'sections' => [
            [
                'title' => 'Semiconductors and Advanced Manufacturing',
                'paragraphs' => [
                    'Major semiconductor investment around Phoenix has created a hiring wave that runs well beyond engineering — construction trades to build the facilities, then technicians, maintenance and operations staff to run them.',
                    'Fabrication technician roles are the accessible entry point. They typically want a technical associate degree or relevant military or maintenance experience rather than a four-year degree, and pay well above general manufacturing.',
                ],
            ],
            [
                'title' => 'Distribution and Logistics',
                'paragraphs' => [
                    'Phoenix serves as a distribution point for the southwest and for southern California, which has driven continuous warehouse, fulfilment and driving hiring across the metro area.',
                    'Living costs, while rising, remain below coastal California, which is much of why the distribution capacity moved here in the first place.',
                ],
            ],
            [
                'title' => 'Healthcare, Tourism and the Heat',
                'paragraphs' => [
                    'A large retirement population makes healthcare and elderly care one of the most stable job markets in the state, with home health and nursing assistant roles hiring continuously.',
                    'Summer heat genuinely shapes outdoor work. Construction and other outdoor sectors shift to early starts through the hottest months, and heat safety rules are a real part of the job rather than a formality.',
                ],
            ],
        ],
        'jobRoles' => [
            'Warehouse Associate',
            'Fabrication Technician',
            'Construction Worker',
            'Certified Nursing Assistant',
            'Home Health Aide',
            'CDL-A Truck Driver',
            'Customer Service Representative',
            'Maintenance Technician',
        ],
        'faqs' => [
            [
                'q' => 'What is driving job growth in Arizona?',
                'a' => 'Semiconductor manufacturing investment around Phoenix, distribution and logistics serving the southwest, and the construction required to support both.',
            ],
            [
                'q' => 'Do semiconductor jobs need an engineering degree?',
                'a' => 'Not all of them. Fabrication technician roles typically want a technical associate degree or relevant maintenance or military experience, and pay well above general manufacturing.',
            ],
            [
                'q' => 'How does the heat affect work?',
                'a' => 'Outdoor sectors shift to early starts through the hottest months, and heat safety requirements are taken seriously on site. It changes schedules rather than stopping work.',
            ],
            [
                'q' => 'Is healthcare a good sector here?',
                'a' => 'It is one of the most stable in the state, because of the large retirement population. Home health and nursing assistant roles hire continuously and need only short certification.',
            ],
            [
                'q' => 'Is it free to apply through JobGader?',
                'a' => 'Yes, and no account is required. Every listing links through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse Arizona Jobs',
        'filterType' => 'state',
        'filterValue' => 'Arizona',
        'accentText' => 'Arizona',
        'eyebrow' => 'Jobs in Arizona',
    ])
@endsection
