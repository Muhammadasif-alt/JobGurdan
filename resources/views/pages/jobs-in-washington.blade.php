@extends('user.layouts.master')
@section('title', 'Jobs in Washington | USA Jobs')
@section('meta_description', 'Search Washington jobs in Seattle, Tacoma, Spokane, and Bellevue. Find opportunities in tech, aerospace, healthcare, and retail.')
@section('og_title', 'Jobs in Washington | USA Jobs')
@section('og_description', 'Search Washington jobs in Seattle, Tacoma, Spokane, and Bellevue. Find opportunities in tech, aerospace, healthcare, and retail.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Washington',
        'intro' => [
            'Washington state features a thriving job market driven by technology, aerospace, healthcare, and retail. North of the border, Seattle and Bellevue host many of the world’s top tech companies.',
            'Explore job opportunities across Washington and apply to roles that match your experience and preferred lifestyle.',
        ],
        'sections' => [
            [
                'title' => 'Washington Industry Highlights',
                'paragraphs' => [
                    'Tech giants dominate the Seattle area, while aerospace, healthcare, and logistics also provide strong employment opportunities throughout the state.',
                    'Smaller cities like Spokane and Tacoma are growing hubs for manufacturing, distribution, and healthcare services.',
                ],
            ],
            [
                'title' => 'Tips for Washington Job Seekers',
                'paragraphs' => [
                    'Be prepared for a competitive job market in Seattle by showcasing specialized technical skills and team collaboration experience.',
                    'Consider remote-friendly roles or positions in suburban markets to expand your options.',
                ],
            ],
        ],
        'jobRoles' => [
            'Software Developer',
            'Aerospace Engineer',
            'Registered Nurse',
            'Project Manager',
            'Retail Store Manager',
            'Data Analyst',
        ],
        'ctaText' => 'Browse Washington Jobs',
        'filterType' => 'state',
        'filterValue' => 'Washington',
        'accentText' => 'Washington',
        'eyebrow' => 'Jobs in Washington',
    ])
@endsection
