@extends('user.layouts.master')
@section('title', 'Remote Jobs USA | USA Jobs')
@section('meta_description', 'Search remote jobs in the USA across industries. Find work-from-home opportunities in tech, marketing, customer service, and more.')
@section('og_title', 'Remote Jobs USA | USA Jobs')
@section('og_description', 'Search remote jobs in the USA across industries. Find work-from-home opportunities in tech, marketing, customer service, and more.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Remote Jobs USA',
        'intro' => [
            'Remote jobs allow you to work from anywhere in the USA while staying connected with employers via video, chat, and collaboration tools. Remote work spans industries including technology, marketing, customer support, and more.',
            'Use our platform to find remote-friendly roles and apply for positions that let you work from home, a co‑working space, or anywhere with an internet connection.',
        ],
        'sections' => [
            [
                'title' => 'Benefits of Remote Work',
                'paragraphs' => [
                    'Remote jobs offer flexibility, reduced commute times, and the ability to balance personal and work life. Many remote employers also offer competitive pay and global teams.',
                    'Remote roles can include full-time, part-time, and contract positions, giving you options to match your schedule.',
                ],
            ],
            [
                'title' => 'Tips for Landing Remote Positions',
                'paragraphs' => [
                    'Highlight your ability to communicate clearly, manage time effectively, and work independently. Remote employers value self-motivated candidates who can stay organized.',
                    'Showcase your experience with remote tools like Slack, Zoom, and project management software.',
                ],
            ],
        ],
        'jobRoles' => [
            'Remote Customer Support',
            'Virtual Assistant',
            'Remote Software Developer',
            'Remote Content Writer',
            'Remote Project Manager',
            'Remote Marketing Specialist',
        ],
        'ctaText' => 'Browse Remote Jobs',
        'filterType' => 'remote',
        'accentText' => 'Remote',
        'eyebrow' => 'Remote Work',
    ])
@endsection
