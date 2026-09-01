@extends('user.layouts.master')
@section('title', 'Jobs in Illinois | USA Jobs')
@section('meta_description', 'Explore Illinois jobs in Chicago, Springfield, Peoria, and beyond. Find openings in finance, manufacturing, healthcare, and education.')
@section('og_title', 'Jobs in Illinois | USA Jobs')
@section('og_description', 'Explore Illinois jobs in Chicago, Springfield, Peoria, and beyond. Find openings in finance, manufacturing, healthcare, and education.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Illinois',
        'intro' => [
            'Illinois is a top Midwest job market with strong demand for talent in finance, manufacturing, healthcare, and education. Chicago anchors the state’s employment opportunities, while smaller cities offer cost-effective options.',
            'Use our job search tools to filter by location and industry. Find positions that match your skills and career goals in Illinois.',
        ],
        'sections' => [
            [
                'title' => 'Industries Hiring in Illinois',
                'paragraphs' => [
                    'Chicago is a major finance and tech hub, while the broader state supports manufacturing, logistics, and healthcare roles. The state is also known for educational and government positions.',
                    'Illinois employers value strong communication skills, reliability, and experience in regulated industries.',
                ],
            ],
            [
                'title' => 'Getting Noticed by Illinois Employers',
                'paragraphs' => [
                    'Tailor your resume to highlight relevant experience and highlight any certifications or licenses needed for regulated roles.',
                    'Don’t forget to network locally—attend local meetups or join industry groups to increase your visibility.',
                ],
            ],
        ],
        'jobRoles' => [
            'Financial Analyst',
            'Mechanical Engineer',
            'Registered Nurse',
            'Operations Manager',
            'IT Support Specialist',
            'Teacher/Instructor',
        ],
        'ctaText' => 'Browse Illinois Jobs',
        'filterType' => 'state',
        'filterValue' => 'Illinois',
        'accentText' => 'Illinois',
        'eyebrow' => 'Jobs in Illinois',
    ])
@endsection
