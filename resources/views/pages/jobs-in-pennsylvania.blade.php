@extends('user.layouts.master')
@section('title', 'Jobs in Pennsylvania | USA Jobs')
@section('meta_description', 'Search jobs in Pennsylvania, including Pittsburgh, Philadelphia, Harrisburg and beyond. Find openings in healthcare, manufacturing, education, and technology.')
@section('og_title', 'Jobs in Pennsylvania | USA Jobs')
@section('og_description', 'Search jobs in Pennsylvania, including Pittsburgh, Philadelphia, Harrisburg and beyond. Find openings in healthcare, manufacturing, education, and technology.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Pennsylvania',
        'intro' => [
            'Pennsylvania is a diverse labor market spanning urban centers like Philadelphia and Pittsburgh, along with vibrant small towns across the state. You’ll find opportunities in healthcare, higher education, finance, and advanced manufacturing.',
            'Filter job listings by city, industry, schedule, and experience level to find roles that match your career goals. Apply directly through our platform.',
        ],
        'sections' => [
            [
                'title' => 'Major Pennsylvania Job Hubs',
                'paragraphs' => [
                    'Philadelphia is a leading market for healthcare, education, and finance. Pittsburgh is known for advanced manufacturing, robotics, and technology innovation.',
                    'The state also offers strong government and nonprofit hiring in Harrisburg and beyond.',
                ],
            ],
            [
                'title' => 'Tips for Landing a Job in Pennsylvania',
                'paragraphs' => [
                    'Highlight any local experience or certifications relevant to Pennsylvania industries, such as healthcare licensing or manufacturing safety credentials.',
                    'Be proactive — many employers value flexible candidates ready to start on short notice.',
                ],
            ],
        ],
        'jobRoles' => [
            'Clinical Nurse',
            'Software Developer',
            'Manufacturing Technician',
            'Accountant',
            'Customer Success Representative',
            'Operations Coordinator',
        ],
        'ctaText' => 'Browse Pennsylvania Jobs',
        'filterType' => 'state',
        'filterValue' => 'Pennsylvania',
        'accentText' => 'Pennsylvania',
        'eyebrow' => 'Jobs in Pennsylvania',
    ])
@endsection
