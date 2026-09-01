@extends('user.layouts.master')
@section('title', 'Jobs in New York | USA Jobs')
@section('meta_description', 'Search New York jobs in finance, media, technology, healthcare, and hospitality. Find openings in NYC, Buffalo, Rochester, and the Hudson Valley.')
@section('og_title', 'Jobs in New York | USA Jobs')
@section('og_description', 'Search New York jobs in finance, media, technology, healthcare, and hospitality. Find openings in NYC, Buffalo, Rochester, and the Hudson Valley.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in New York',
        'intro' => [
            'New York state offers a wide range of job opportunities from corporate finance in Manhattan to advanced manufacturing upstate. Whether you are looking for a high-energy city role or a more relaxed suburban position, New York has options.',
            'Use our search filters to find roles by city, salary range, and experience level. Apply quickly and track your progress in one dashboard.',
        ],
        'sections' => [
            [
                'title' => 'Why New York is a Top Job Market',
                'paragraphs' => [
                    'New York ranks among the top states for finance, media, healthcare, and technology careers. Its diversity of industries makes it easy to pivot careers or find a specialized niche.',
                    'The state also has strong entry-level hiring for college graduates, along with plenty of remote-friendly roles.',
                ],
            ],
            [
                'title' => 'How to Stand Out in New York Job Searches',
                'paragraphs' => [
                    'Highlight your relevant experience and use local keywords such as “New York”, “NYC”, or specific boroughs to match employer searches.',
                    'Reach out to recruiters and attend local networking meetups to increase your chances of landing interviews.',
                ],
            ],
        ],
        'jobRoles' => [
            'Financial Analyst',
            'Content Producer',
            'Healthcare Administrator',
            'UX/UI Designer',
            'Business Development Representative',
            'Customer Support Specialist',
        ],
        'ctaText' => 'Browse New York Jobs',
        'filterType' => 'state',
        'filterValue' => 'New York',
        'accentText' => 'New York',
        'eyebrow' => 'Jobs in New York',
    ])
@endsection
