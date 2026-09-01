@extends('user.layouts.master')
@section('title', 'Jobs in Arizona | USA Jobs')
@section('meta_description', 'Discover Arizona jobs in Phoenix, Tucson, Scottsdale, and beyond. Browse positions in tech, healthcare, tourism, and construction.')
@section('og_title', 'Jobs in Arizona | USA Jobs')
@section('og_description', 'Discover Arizona jobs in Phoenix, Tucson, Scottsdale, and beyond. Browse positions in tech, healthcare, tourism, and construction.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Arizona',
        'intro' => [
            'Arizona offers growing career opportunities across tech, healthcare, tourism, and construction. Phoenix and Tucson are home to fast-growing tech hubs and expanding medical centers.',
            'Whether you are seeking a remote role or an on-site position, our platform makes it easy to search Arizona job listings and apply quickly.',
        ],
        'sections' => [
            [
                'title' => 'Industries Hiring in Arizona',
                'paragraphs' => [
                    'Technology and software development are expanding rapidly in the Phoenix metro area, while healthcare continues to grow across the state.',
                    'Tourism and hospitality also drive seasonal hiring, with resorts and restaurants looking for reliable staff year-round.',
                ],
            ],
            [
                'title' => 'How to Find Arizona Jobs Fast',
                'paragraphs' => [
                    'Use location filters to narrow results to cities and suburbs. Highlight your flexibility for shift work or weekend schedules when applying.',
                    'Apply early, as many Arizona employers fill positions quickly during busy seasons.',
                ],
            ],
        ],
        'jobRoles' => [
            'Software Engineer',
            'Registered Nurse',
            'Hotel Manager',
            'Construction Superintendent',
            'IT Support Specialist',
            'Tourism Coordinator',
        ],
        'ctaText' => 'Browse Arizona Jobs',
        'filterType' => 'state',
        'filterValue' => 'Arizona',
        'accentText' => 'Arizona',
        'eyebrow' => 'Jobs in Arizona',
    ])
@endsection
