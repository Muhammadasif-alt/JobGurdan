@extends('user.layouts.master')
@section('title', 'No Experience Jobs | USA Jobs')
@section('meta_description', 'Browse no experience jobs across the USA. Find hiring employers that provide on-the-job training and entry-level opportunities.')
@section('og_title', 'No Experience Jobs | USA Jobs')
@section('og_description', 'Browse no experience jobs across the USA. Find hiring employers that provide on-the-job training and entry-level opportunities.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'No Experience Jobs',
        'intro' => [
            'No experience jobs are ideal if you are just starting your career or looking to switch fields. Employers are often open to hiring candidates who demonstrate a strong work ethic and eagerness to learn.',
            'These roles usually include on-the-job training, allowing you to build skills while you earn.',
        ],
        'sections' => [
            [
                'title' => 'Where to Find No Experience Jobs',
                'paragraphs' => [
                    'Many industries hire without formal experience requirements, including retail, hospitality, customer service, warehousing, and entry-level office roles.',
                    'Focus on companies known for strong training programs and supportive work cultures.',
                ],
            ],
            [
                'title' => 'Tips for Securing a Job Without Experience',
                'paragraphs' => [
                    'Emphasize transferable skills such as communication, teamwork, and problem solving. Include any volunteer work, internships, or relevant coursework that showcase your abilities.',
                    'Write a compelling cover letter that highlights your motivation to learn and grow in a new role.',
                ],
            ],
        ],
        'jobRoles' => [
            'Retail Associate',
            'Food Service Worker',
            'Warehouse Associate',
            'Customer Service Representative',
            'Receptionist',
            'Delivery Driver',
        ],
        'ctaText' => 'Search No Experience Jobs',
        'filterType' => 'experience',
        'filterValue' => ['no experience', 'entry level', 'trainee', 'training provided'],
        'accentText' => 'No Experience',
        'eyebrow' => 'No Experience Needed',
    ])
@endsection
