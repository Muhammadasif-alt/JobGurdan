@extends('user.layouts.master')
@section('title', 'Jobs in Michigan | USA Jobs')
@section('meta_description', 'Explore Michigan jobs in Detroit, Grand Rapids, Lansing, and Ann Arbor. Find positions in manufacturing, automotive, healthcare, and technology.')
@section('og_title', 'Jobs in Michigan | USA Jobs')
@section('og_description', 'Explore Michigan jobs in Detroit, Grand Rapids, Lansing, and Ann Arbor. Find positions in manufacturing, automotive, healthcare, and technology.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Michigan',
        'intro' => [
            'Michigan is the epicenter of automotive innovation, and its cities like Detroit, Grand Rapids, and Ann Arbor are also growing hubs for healthcare, education, and technology jobs.',
            'Search Michigan jobs by city and industry to find roles that match your experience and career goals. Use our tools to filter by schedule, remote options, and salary.',
        ],
        'sections' => [
            [
                'title' => 'Key Michigan Employment Sectors',
                'paragraphs' => [
                    'Automotive manufacturing and mobility tech remain strong, but Michigan also has expanding healthcare and higher education job markets, especially around Ann Arbor and Lansing.',
                    'Many employers are hiring for both on-site and remote roles, offering flexibility across the state.',
                ],
            ],
            [
                'title' => 'How to Stand Out in Michigan Job Searches',
                'paragraphs' => [
                    'Showcase experience with manufacturing systems, automotive software, or healthcare workflows depending on the industry you’re targeting.',
                    'Networking with local professional organizations can help you locate hidden job opportunities and get referrals.',
                ],
            ],
        ],
        'jobRoles' => [
            'Manufacturing Engineer',
            'Automotive Technician',
            'Registered Nurse',
            'Software QA Tester',
            'Project Coordinator',
            'Operations Manager',
        ],
        'ctaText' => 'Browse Michigan Jobs',
        'filterType' => 'state',
        'filterValue' => 'Michigan',
        'accentText' => 'Michigan',
        'eyebrow' => 'Jobs in Michigan',
    ])
@endsection
