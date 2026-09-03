@extends('user.layouts.master')
@section('title', 'Jobs in California — Regions, Pay and Costs | JobGader')
@section('meta_description', 'Jobs in California: how the Bay Area, Los Angeles, San Diego and the Central Valley differ, what they pay, and how far that pay actually goes.')
@section('og_title', 'Jobs in California — Regions, Pay and Costs | JobGader')
@section('og_description', 'Jobs in California: how the Bay Area, Los Angeles, San Diego and the Central Valley differ, what they pay, and how far that pay actually goes.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in California',
        'intro' => [
            'California has the largest state economy in the US and the widest internal variation. A wage that is comfortable in Fresno is tight in San Francisco, and the industries driving each region have almost nothing in common.',
            'This page covers what is hiring across California region by region, what the main sectors pay, and how to read a California salary against the cost of living where the job actually is.',
        ],
        'sections' => [
            [
                'title' => 'Five Regions, Five Job Markets',
                'paragraphs' => [
                    'The Bay Area is technology, biotech and venture-backed startups, with the highest salaries in the state and the highest housing costs in the country. Los Angeles is entertainment, media, logistics through the ports of LA and Long Beach, and a large healthcare sector.',
                    'San Diego is biotech, defence and tourism. The Central Valley is agriculture, food processing and distribution, with far lower pay and far lower costs. Sacramento is state government and healthcare.',
                ],
            ],
            [
                'title' => 'High Wages, High Costs',
                'paragraphs' => [
                    'California salaries lead national averages in most sectors, but the state also levies among the highest personal income tax rates in the country, and housing in the coastal metros absorbs a large share of take-home pay.',
                    'The state minimum wage is well above the federal floor, and several cities set higher local minimums, which lifts the bottom of the market noticeably compared with most other states.',
                ],
            ],
            [
                'title' => 'Where Entry-Level Hiring Concentrates',
                'paragraphs' => [
                    'Warehousing and logistics around the Inland Empire and the ports, agriculture and food processing in the Central Valley, healthcare support statewide, and hospitality in the tourist regions account for most continuous entry-level hiring.',
                    'Agricultural work in the Central Valley is also one of the few US sectors with a genuine seasonal visa route, through the H-2A programme, which is separate from the H-2B route used by other industries.',
                ],
            ],
        ],
        'jobRoles' => [
            'Software Engineer',
            'Registered Nurse',
            'Warehouse Associate',
            'Agricultural Worker',
            'Customer Service Representative',
            'Biotech Research Associate',
            'Delivery Driver',
            'Hospitality Assistant',
        ],
        'faqs' => [
            [
                'q' => 'Which part of California has the best job market?',
                'a' => 'It depends entirely on your field. The Bay Area leads for technology and biotech, Los Angeles for media, logistics and healthcare, San Diego for biotech and defence, and the Central Valley for agriculture and food processing.',
            ],
            [
                'q' => 'Are California salaries actually higher?',
                'a' => 'In nominal terms yes, across most sectors. Whether they go further depends on where in the state — coastal housing costs and state income tax absorb much of the difference, while inland regions are far cheaper.',
            ],
            [
                'q' => 'What is the minimum wage in California?',
                'a' => 'The state minimum is well above the federal floor and several cities set higher local minimums, so check the specific city as well as the state figure.',
            ],
            [
                'q' => 'Is there a visa route for agricultural work in California?',
                'a' => 'Yes. H-2A covers seasonal agricultural work and is separate from the H-2B route used by other industries. It is employer-sponsored, and no legitimate employer charges the worker a fee for it.',
            ],
            [
                'q' => 'Do I need an account to apply?',
                'a' => 'No. Applying is free and every listing links through to the employer or original posting.',
            ],
        ],
        'ctaText' => 'Browse California Jobs',
        'filterType' => 'state',
        'filterValue' => 'California',
        'accentText' => 'California',
        'eyebrow' => 'Jobs in California',
    ])
@endsection
