@extends('user.layouts.master')
@section('title', 'Jobs in Virginia | USA Jobs')
@section('meta_description', 'Explore Virginia jobs in Richmond, Norfolk, Virginia Beach, and Northern Virginia. Browse opportunities in government, tech, defense, and healthcare.')
@section('og_title', 'Jobs in Virginia | USA Jobs')
@section('og_description', 'Explore Virginia jobs in Richmond, Norfolk, Virginia Beach, and Northern Virginia. Browse opportunities in government, tech, defense, and healthcare.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Virginia',
        'intro' => [
            'Virginia provides a robust job market with major hubs for government contracting, cybersecurity, healthcare, and education. Northern Virginia in particular is known for its high concentration of tech and defense employers.',
            'Use our job search tools to locate Virginia positions that align with your skillset and preferred work style, including hybrid and remote options.',
        ],
        'sections' => [
            [
                'title' => 'Growing Virginia Job Sectors',
                'paragraphs' => [
                    'Cybersecurity, IT, and defense contractors thrive in Northern Virginia, while Richmond and Norfolk have strong healthcare, logistics, and manufacturing job markets.',
                    'Virginia also supports apprenticeship and training programs that help job seekers transition to high-demand careers.',
                ],
            ],
            [
                'title' => 'Landing a Virginia Job',
                'paragraphs' => [
                    'Highlight security clearance experience, technical certifications, and any experience working with federal or state agencies. Many positions require strong communication and project management skills.',
                    'Network through local industry groups and attend hiring events hosted in Northern Virginia and Richmond.',
                ],
            ],
        ],
        'jobRoles' => [
            'Cybersecurity Analyst',
            'Software Engineer',
            'Project Manager',
            'Registered Nurse',
            'Logistics Coordinator',
            'System Administrator',
        ],
        'ctaText' => 'Browse Virginia Jobs',
        'filterType' => 'state',
        'filterValue' => 'Virginia',
        'accentText' => 'Virginia',
        'eyebrow' => 'Jobs in Virginia',
    ])
@endsection
