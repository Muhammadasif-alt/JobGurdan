@extends('user.layouts.master')
@section('title', 'Jobs in California | USA Jobs')
@section('meta_description', 'Discover California jobs in tech, entertainment, healthcare, and more. Explore roles in Los Angeles, San Francisco, San Diego, and Silicon Valley today.')
@section('og_title', 'Jobs in California | USA Jobs')
@section('og_description', 'Discover California jobs in tech, entertainment, healthcare, and more. Explore roles in Los Angeles, San Francisco, San Diego, and Silicon Valley today.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in California',
        'intro' => [
            'California is the nation’s largest economy and a global hub for technology, entertainment, healthcare, and clean energy. From Bay Area startups to Hollywood studios, California offers a huge range of job opportunities.',
            'Use our platform to narrow your search by city, industry, job type, and experience level. Apply directly to employers and track your applications in one place.',
        ],
        'sections' => [
            [
                'title' => 'Key Industries Driving California Jobs',
                'paragraphs' => [
                    'California has strong demand for software engineering, digital marketing, healthcare services, creative media, and renewable energy positions.',
                    'Especially in coastal metros like San Francisco, Los Angeles, and San Diego, smart job hunters can find opportunities in high-growth sectors.',
                ],
            ],
            [
                'title' => 'Tips for Landing a California Job',
                'paragraphs' => [
                    'Customize your resume to highlight skills that match the job requirements and consider building a strong online presence on LinkedIn.',
                    'Apply early and follow up professionally; many employers fill roles quickly in fast-paced California markets.',
                ],
            ],
        ],
        'jobRoles' => [
            'Front-End Developer',
            'Graphic Designer',
            'Registered Nurse',
            'Sales Manager',
            'Social Media Specialist',
            'Project Engineer',
        ],
        'ctaText' => 'Browse California Jobs',
        'filterType' => 'state',
        'filterValue' => 'California',
        'accentText' => 'California',
        'eyebrow' => 'Jobs in California',
    ])
@endsection
