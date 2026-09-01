@extends('user.layouts.master')
@section('title', 'Jobs in Texas | USA Jobs')
@section('meta_description', 'Explore the best Texas jobs across industries, including oil & gas, healthcare, tech, and hospitality. Find opportunities in Dallas, Houston, Austin, San Antonio and beyond.')
@section('og_title', 'Jobs in Texas | USA Jobs')
@section('og_description', 'Explore the best Texas jobs across industries, including oil & gas, healthcare, tech, and hospitality. Find opportunities in Dallas, Houston, Austin, San Antonio and beyond.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Texas',
        'intro' => [
            'Texas is home to a diverse job market that includes energy, healthcare, tech, manufacturing, hospitality, and transportation. Whether you are searching for entry-level work or senior management, the Lone Star State offers opportunities in cities large and small.',
            'Browse thousands of Texas job listings and apply directly through our platform. Our search tools help you filter by city, industry, and salary range so you can find the best match.',
        ],
        'sections' => [
            [
                'title' => 'Why Choose a Job in Texas?',
                'paragraphs' => [
                    'Texas has one of the strongest economies in the US, with consistently high job growth in manufacturing, logistics, healthcare, and technology. The state also has no personal income tax, making it an attractive option for job seekers.',
                    'Major metro areas like Houston, Dallas-Fort Worth, Austin, and San Antonio offer an abundance of career opportunities along with a vibrant cultural scene and affordable living.',
                ],
            ],
            [
                'title' => 'How to Find the Right Texas Job',
                'paragraphs' => [
                    'Use our search filters to narrow down jobs by location, industry, and job type. Bookmark your favorite listings and apply directly to employers with a few clicks.',
                    'Sign up for job alerts to receive the latest Texas job postings delivered to your inbox daily.',
                ],
            ],
        ],
        'jobRoles' => [
            'Registered Nurse',
            'Software Engineer',
            'Truck Driver',
            'Construction Superintendent',
            'Customer Service Representative',
            'Project Manager',
        ],
        'ctaText' => 'Browse Texas Jobs',
        'filterType' => 'state',
        'filterValue' => 'Texas',
        'accentText' => 'Texas',
        'eyebrow' => 'Jobs in Texas',
    ])
@endsection
