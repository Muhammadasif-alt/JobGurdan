@extends('user.layouts.master')
@section('title', 'Work From Home Jobs | USA Jobs')
@section('meta_description', 'Find work from home jobs across the USA. Discover remote-friendly roles in customer service, tech, marketing, and more.')
@section('og_title', 'Work From Home Jobs | USA Jobs')
@section('og_description', 'Find work from home jobs across the USA. Discover remote-friendly roles in customer service, tech, marketing, and more.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Work From Home Jobs',
        'intro' => [
            'Work from home jobs offer flexibility and the ability to shape your own schedule. Many employers are hiring remote workers in customer service, marketing, writing, and more.',
            'Explore listings for remote-friendly roles and apply to positions that fit your availability and experience.',
        ],
        'sections' => [
            [
                'title' => 'What Employers Look For',
                'paragraphs' => [
                    'Employers hiring remote workers value strong communication, reliability, and a comfortable home workspace with a stable internet connection.',
                    'Focus on your ability to manage time, stay organized, and collaborate virtually as you apply for work-from-home jobs.',
                ],
            ],
            [
                'title' => 'How to Find Remote Work',
                'paragraphs' => [
                    'Use filters to search for remote roles and specify your desired schedule (full-time, part-time, or contract).',
                    'Watch out for scam postings by researching the company and avoiding roles that require upfront fees.',
                ],
            ],
        ],
        'jobRoles' => [
            'Remote Customer Service Rep',
            'Virtual Assistant',
            'Remote Sales Representative',
            'Remote Content Writer',
            'Remote Data Entry',
            'Remote Social Media Manager',
        ],
        'ctaText' => 'Browse Work From Home Jobs',
        'filterType' => 'remote',
        'accentText' => 'Work From Home',
        'eyebrow' => 'Work From Home',
    ])
@endsection
