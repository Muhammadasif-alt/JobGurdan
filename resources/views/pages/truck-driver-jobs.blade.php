@extends('user.layouts.master')
@section('title', 'Truck Driver Jobs | USA Jobs')
@section('meta_description', 'Search truck driver jobs across the USA. Find CDL driving positions, regional and long-haul routes, and opportunities with top carriers.')
@section('og_title', 'Truck Driver Jobs | USA Jobs')
@section('og_description', 'Search truck driver jobs across the USA. Find CDL driving positions, regional and long-haul routes, and opportunities with top carriers.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Truck Driver Jobs',
        'intro' => [
            'Truck driving remains one of the most in-demand careers in the U.S. With a CDL, you can access opportunities in local delivery, regional routes, and long-haul trucking.',
            'Browse job postings from top carriers and logistics companies. Filter by route type, pay, and benefits to find the right fit.',
        ],
        'sections' => [
            [
                'title' => 'Types of Truck Driver Jobs',
                'paragraphs' => [
                    'Choose from regional routes, long-haul freight, local delivery, and specialty hauling. Some positions offer home-time guarantees or premium pay for hazardous materials.',
                    'Many employers offer training programs for new CDL holders and incentives for experienced drivers.',
                ],
            ],
            [
                'title' => 'How to Apply for Truck Driver Roles',
                'paragraphs' => [
                    'Prepare a resume that highlights your CDL class, endorsements (such as HAZMAT or tanker), and driving experience. Include any safety awards or clean driving records.',
                    'Apply to multiple carriers and consider reaching out to recruiters who specialize in transportation jobs.',
                ],
            ],
        ],
        'jobRoles' => [
            'CDL A Driver',
            'Local Delivery Driver',
            'Regional Truck Driver',
            'Flatbed Driver',
            'Owner-Operator',
            'Logistics Coordinator',
        ],
        'ctaText' => 'Browse Truck Driver Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['truck driver', 'CDL', 'driver', 'trucking'],
        'accentText' => 'Truck Driver',
        'eyebrow' => 'Transport &amp; Driving',
    ])
@endsection
