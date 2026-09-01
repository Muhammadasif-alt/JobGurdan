@extends('user.layouts.master')
@section('title', 'Internship Jobs | USA Jobs')
@section('meta_description', 'Browse internship job openings across the USA. Find paid and unpaid internships that offer professional experience and skill development.')
@section('og_title', 'Internship Jobs | USA Jobs')
@section('og_description', 'Browse internship job openings across the USA. Find paid and unpaid internships that offer professional experience and skill development.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Internship Jobs',
        'intro' => [
            'Internships are a great way to gain real-world experience, build your network, and explore career options. Whether you are in college or recently graduated, internships can help you qualify for full-time roles.',
            'Search for internships in marketing, engineering, finance, design, and more. Many internships provide mentorship and hands-on projects that help you develop valuable skills.',
        ],
        'sections' => [
            [
                'title' => 'Types of Internships',
                'paragraphs' => [
                    'Internships can be part-time, full-time, paid, or unpaid. Many employers offer seasonal internships during the summer, while others offer year-round opportunities.',
                    'Some internships are remote, while others require working on-site. Choose internships that align with your interests and availability.',
                ],
            ],
            [
                'title' => 'How to Make the Most of an Internship',
                'paragraphs' => [
                    'Set clear goals for what you want to learn, ask for feedback regularly, and take initiative on projects to build your skills.',
                    'Keep track of accomplishments and build a portfolio of your work to showcase in future job applications.',
                ],
            ],
        ],
        'jobRoles' => [
            'Marketing Intern',
            'Software Engineering Intern',
            'Design Intern',
            'Finance Intern',
            'HR Intern',
            'Operations Intern',
        ],
        'ctaText' => 'Search Internship Jobs',
        'filterType' => 'experience',
        'filterValue' => ['intern', 'internship'],
        'accentText' => 'Internship',
        'eyebrow' => 'Internship Opportunities',
    ])
@endsection
