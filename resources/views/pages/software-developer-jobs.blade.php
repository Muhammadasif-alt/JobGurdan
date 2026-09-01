@extends('user.layouts.master')
@section('title', 'Software Developer Jobs | USA Jobs')
@section('meta_description', 'Search software developer jobs across the USA. Find roles in front-end, back-end, full stack, mobile, and DevOps development.')
@section('og_title', 'Software Developer Jobs | USA Jobs')
@section('og_description', 'Search software developer jobs across the USA. Find roles in front-end, back-end, full stack, mobile, and DevOps development.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Software Developer Jobs',
        'intro' => [
            'Software developers are in high demand across industries, from startups to enterprise organizations. Opportunities range from front-end design to back-end infrastructure and full-stack development.',
            'Browse software developer job listings and find positions that match your preferred languages, frameworks, and work style.',
        ],
        'sections' => [
            [
                'title' => 'Types of Software Development Roles',
                'paragraphs' => [
                    'Common roles include front-end developer, back-end developer, full stack developer, mobile app developer, and DevOps engineer. Each role focuses on different parts of the software lifecycle.',
                    'Many companies also hire for specialized positions like UI/UX engineer, machine learning engineer, and software architect.',
                ],
            ],
            [
                'title' => 'Tips for Getting Hired as a Developer',
                'paragraphs' => [
                    'Showcase your coding skills with a portfolio and GitHub projects. Include relevant frameworks and tools in your resume and tailor them to the job description.',
                    'Prepare for technical interviews by practicing algorithms, system design, and language-specific questions.',
                ],
            ],
        ],
        'jobRoles' => [
            'Front-End Developer',
            'Back-End Developer',
            'Full Stack Developer',
            'Mobile App Developer',
            'DevOps Engineer',
            'Quality Assurance Engineer',
        ],
        'ctaText' => 'Browse Software Developer Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['software', 'developer', 'engineer', 'programmer'],
        'accentText' => 'Software Developer',
        'eyebrow' => 'Software &amp; Engineering',
    ])
@endsection
