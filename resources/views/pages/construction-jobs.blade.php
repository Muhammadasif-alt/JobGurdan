@extends('user.layouts.master')
@section('title', 'Construction Jobs | USA Jobs')
@section('meta_description', 'Find construction jobs nationwide. Browse opportunities in carpentry, project management, site supervision, and skilled trades.')
@section('og_title', 'Construction Jobs | USA Jobs')
@section('og_description', 'Find construction jobs nationwide. Browse opportunities in carpentry, project management, site supervision, and skilled trades.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Construction Jobs',
        'intro' => [
            'Construction remains a leading source of employment across the U.S. Roles range from field laborers to site supervisors, estimators, and project managers.',
            'Search construction job listings to find positions that match your experience level, whether you are new to the trade or an experienced professional.',
        ],
        'sections' => [
            [
                'title' => 'Construction Career Paths',
                'paragraphs' => [
                    'Many construction workers begin as laborers and advance into specialized trades like carpentry, plumbing, or electrical. Leadership roles include foreman and project manager.',
                    'Certifications like OSHA 10/30, NCCER, or union apprenticeship credentials can boost your competitiveness.',
                ],
            ],
            [
                'title' => 'How to Apply for Construction Jobs',
                'paragraphs' => [
                    'Build a resume that lists your trade skills, certifications, and project experience. Include specific tools and equipment you are trained to use.',
                    'Use job filters to search by location, union affiliation, and project type to find the right fit.',
                ],
            ],
        ],
        'jobRoles' => [
            'Carpenter',
            'Electrician',
            'Construction Project Manager',
            'Heavy Equipment Operator',
            'Site Supervisor',
            'Estimator',
        ],
        'ctaText' => 'Browse Construction Jobs',
        'filterType' => 'category',
        'filterValue' => 'Construction',
        'accentText' => 'Construction',
        'eyebrow' => 'Construction &amp; Trades',
    ])
@endsection
