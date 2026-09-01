@extends('user.layouts.master')
@section('title', 'Jobs in Ohio | USA Jobs')
@section('meta_description', 'Find Ohio jobs in Columbus, Cleveland, Cincinnati, and Toledo. Browse opportunities in manufacturing, healthcare, logistics, and IT.')
@section('og_title', 'Jobs in Ohio | USA Jobs')
@section('og_description', 'Find Ohio jobs in Columbus, Cleveland, Cincinnati, and Toledo. Browse opportunities in manufacturing, healthcare, logistics, and IT.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Ohio',
        'intro' => [
            'Ohio offers a balanced job market with strong manufacturing, healthcare, logistics, and service sectors. Cities like Columbus, Cleveland, and Cincinnati host many growing companies and startups.',
            'Search Ohio job postings and filter by experience level, schedule, and industry to find the right role for you.',
        ],
        'sections' => [
            [
                'title' => 'Top Sectors Hiring in Ohio',
                'paragraphs' => [
                    'Manufacturing and logistics are major employment drivers in Ohio, along with healthcare and education. Tech roles are growing, especially in cities like Columbus.',
                    'Many employers offer hybrid or remote options, even in traditional industries.',
                ],
            ],
            [
                'title' => 'How to Apply for Ohio Jobs',
                'paragraphs' => [
                    'Use our advanced search to filter by city, job type, and experience level. Apply early and tailor your resume to the company’s requirements.',
                    'Track your applications and follow up professionally to increase your chances of receiving an interview.',
                ],
            ],
        ],
        'jobRoles' => [
            'Production Supervisor',
            'Registered Nurse',
            'Logistics Coordinator',
            'Software Engineer',
            'Sales Associate',
            'Administrative Assistant',
        ],
        'ctaText' => 'Browse Ohio Jobs',
        'filterType' => 'state',
        'filterValue' => 'Ohio',
        'accentText' => 'Ohio',
        'eyebrow' => 'Jobs in Ohio',
    ])
@endsection
