@extends('user.layouts.master')
@section('title', 'Part-Time Remote Jobs | USA Jobs')
@section('meta_description', 'Find part-time remote jobs in the USA. Discover flexible work-from-home positions that fit your schedule.')
@section('og_title', 'Part-Time Remote Jobs | USA Jobs')
@section('og_description', 'Find part-time remote jobs in the USA. Discover flexible work-from-home positions that fit your schedule.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Part-Time Remote Jobs',
        'intro' => [
            'Part-time remote jobs are ideal if you need flexibility or are balancing other commitments. These positions allow you to work from home while still earning an income.',
            'Search for part-time remote job listings in customer service, marketing, data entry, and more.',
        ],
        'sections' => [
            [
                'title' => 'Benefits of Part-Time Remote Work',
                'paragraphs' => [
                    'Part-time remote roles offer flexibility and the ability to control your schedule. They can also be a great way to gain experience while studying or caring for family.',
                    'Many employers offer compensation that is competitive for part-time hours and may include opportunities to transition to full-time.',
                ],
            ],
            [
                'title' => 'How to Find Part-Time Remote Jobs',
                'paragraphs' => [
                    'Use our filters to search for part-time roles and set alerts for new postings. Highlight your time management and communication skills in your application.',
                    'Ensure you have a reliable workspace and internet connection to support remote work.',
                ],
            ],
        ],
        'jobRoles' => [
            'Remote Customer Support',
            'Virtual Assistant',
            'Part-Time Writer',
            'Online Tutor',
            'Remote Sales Representative',
            'Data Entry Specialist',
        ],
        'ctaText' => 'Browse Part-Time Remote Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['part-time remote', 'part time remote', 'remote part-time', 'remote part time'],
        'accentText' => 'Part-Time Remote',
        'eyebrow' => 'Flexible Remote',
    ])
@endsection
