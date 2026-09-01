@extends('user.layouts.master')
@section('title', 'Jobs in New Jersey | USA Jobs')
@section('meta_description', 'Find New Jersey jobs near NYC, Newark, and Jersey City. Browse opportunities in finance, healthcare, technology, and manufacturing.')
@section('og_title', 'Jobs in New Jersey | USA Jobs')
@section('og_description', 'Find New Jersey jobs near NYC, Newark, and Jersey City. Browse opportunities in finance, healthcare, technology, and manufacturing.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in New Jersey',
        'intro' => [
            'New Jersey offers a strong job market with proximity to New York City while maintaining more affordable commuting options. The state has significant hiring in finance, pharmaceuticals, healthcare, and transportation.',
            'Search New Jersey job listings and use our filters to narrow results by city, industry, or schedule. Apply to roles directly and track your submissions in one place.',
        ],
        'sections' => [
            [
                'title' => 'Why Work in New Jersey?',
                'paragraphs' => [
                    'With major hubs like Newark and Jersey City, New Jersey provides access to top-tier employers in banking, logistics, and healthcare. Many companies also offer hybrid and remote positions.',
                    'For those willing to commute, a New Jersey job can provide competitive salaries with a lower cost of living than NYC.',
                ],
            ],
            [
                'title' => 'Tips for Job Seekers',
                'paragraphs' => [
                    'Include keywords such as “NJ”, “New Jersey”, and relevant city names in your resume. Highlight your ability to commute and work in fast-paced environments.',
                    'Consider joining local networking groups and online professional communities to find hidden job leads.',
                ],
            ],
        ],
        'jobRoles' => [
            'Financial Analyst',
            'Clinical Research Coordinator',
            'Logistics Specialist',
            'Software Developer',
            'Sales Manager',
            'Customer Service Representative',
        ],
        'ctaText' => 'Browse New Jersey Jobs',
        'filterType' => 'state',
        'filterValue' => 'New Jersey',
        'accentText' => 'New Jersey',
        'eyebrow' => 'Jobs in New Jersey',
    ])
@endsection
