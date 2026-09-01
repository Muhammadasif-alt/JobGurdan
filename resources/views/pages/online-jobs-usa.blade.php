@extends('user.layouts.master')
@section('title', 'Online Jobs USA | USA Jobs')
@section('meta_description', 'Discover online jobs in the USA. Find remote, freelance, and work-from-anywhere positions across multiple industries.')
@section('og_title', 'Online Jobs USA | USA Jobs')
@section('og_description', 'Discover online jobs in the USA. Find remote, freelance, and work-from-anywhere positions across multiple industries.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Online Jobs USA',
        'intro' => [
            'Online jobs provide flexibility to work from virtually anywhere. These roles are ideal for people seeking freelance work, contract positions, or remote full-time roles.',
            'Explore online job listings and apply to roles that match your skills in digital marketing, customer support, content creation, and more.',
        ],
        'sections' => [
            [
                'title' => 'What Counts as an Online Job?',
                'paragraphs' => [
                    'Online jobs typically involve working from home or a remote location, communicating via email, chat, and video conferencing. Common online roles include customer support, writing, programming, and virtual assistance.',
                    'Many employers offer contract or part-time online positions that can be done from anywhere in the US.',
                ],
            ],
            [
                'title' => 'Tips for Online Job Seekers',
                'paragraphs' => [
                    'Build a strong online presence, including a LinkedIn profile and portfolio if applicable. Highlight remote work experience and your ability to work independently.',
                    'Be cautious of scams by researching the employer and focusing on reputable job listings.',
                ],
            ],
        ],
        'jobRoles' => [
            'Remote Customer Support',
            'Freelance Writer',
            'Virtual Assistant',
            'Remote Project Manager',
            'Online Tutor',
            'Remote Marketing Coordinator',
        ],
        'ctaText' => 'Browse Online Jobs',
        'filterType' => 'remote',
        'accentText' => 'Online',
        'eyebrow' => 'Online &amp; Remote',
    ])
@endsection
