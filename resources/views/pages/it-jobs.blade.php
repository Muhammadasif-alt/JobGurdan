@extends('user.layouts.master')
@section('title', 'IT Jobs | USA Jobs')
@section('meta_description', 'Search IT jobs across the United States. Find roles in software development, system administration, cybersecurity, and network engineering.')
@section('og_title', 'IT Jobs | USA Jobs')
@section('og_description', 'Search IT jobs across the United States. Find roles in software development, system administration, cybersecurity, and network engineering.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'IT Jobs',
        'intro' => [
            'The IT job market is booming nationwide, with demand for developers, systems administrators, cybersecurity experts, and network engineers. Whether you’re just starting your tech career or you’re a seasoned pro, there are opportunities across industries.',
            'Use our search tools to filter IT jobs by skillset, location, and experience level. Apply directly to companies looking for your expertise.',
        ],
        'sections' => [
            [
                'title' => 'Popular IT Career Paths',
                'paragraphs' => [
                    'Software development, DevOps, and cloud engineering are among the fastest-growing fields. Cybersecurity continues to be a priority as businesses protect their digital assets.',
                    'IT roles are available in every industry, including finance, healthcare, retail, and government.',
                ],
            ],
            [
                'title' => 'How to Stand Out in IT Hiring',
                'paragraphs' => [
                    'Showcase your technical skills and certifications, such as AWS, Azure, CompTIA, or CISSP. Provide examples of projects and contributions to open-source or enterprise systems.',
                    'Tailor your resume to the job description and include key technologies used by the employer.',
                ],
            ],
        ],
        'jobRoles' => [
            'Software Engineer',
            'DevOps Engineer',
            'Network Administrator',
            'Cybersecurity Analyst',
            'Cloud Architect',
            'IT Project Manager',
        ],
        'ctaText' => 'Browse IT Jobs',
        'filterType' => 'category',
        'filterValue' => 'I.T',
        'accentText' => 'IT',
        'eyebrow' => 'Tech &amp; IT',
    ])
@endsection
