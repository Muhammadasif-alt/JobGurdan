@extends('user.layouts.master')
@section('title', 'Entry-Level Remote Jobs | USA Jobs')
@section('meta_description', 'Find entry-level remote jobs across the USA. Start a new career path with remote opportunities in customer support, marketing, and administration.')
@section('og_title', 'Entry-Level Remote Jobs | USA Jobs')
@section('og_description', 'Find entry-level remote jobs across the USA. Start a new career path with remote opportunities in customer support, marketing, and administration.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Entry-Level Remote Jobs',
        'intro' => [
            'Entry-level remote jobs are a great way to begin a remote career. These roles typically require minimal experience and include training to help you grow your skills.',
            'Search our curated list of entry-level remote opportunities and apply to positions that align with your interests.',
        ],
        'sections' => [
            [
                'title' => 'Types of Entry-Level Remote Jobs',
                'paragraphs' => [
                    'Common entry-level remote positions include customer service, virtual assistance, social media support, and data entry. Many companies provide onboarding and training for new hires.',
                    'These roles often emphasize communication skills, reliability, and a willingness to learn new tools and processes.',
                ],
            ],
            [
                'title' => 'How to Get Started',
                'paragraphs' => [
                    'Create a basic resume that highlights your communication skills, any relevant coursework, and your ability to learn quickly.',
                    'Include a short cover letter or intro message demonstrating your enthusiasm for remote work and your willingness to develop new skills.',
                ],
            ],
        ],
        'jobRoles' => [
            'Remote Customer Service Representative',
            'Virtual Assistant',
            'Data Entry Clerk',
            'Social Media Assistant',
            'Remote Administrative Assistant',
            'Remote Sales Support',
        ],
        'ctaText' => 'Browse Entry-Level Remote Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['entry level remote', 'entry-level remote', 'remote entry level', 'junior remote'],
        'accentText' => 'Entry-Level Remote',
        'eyebrow' => 'Entry-Level Remote',
    ])
@endsection
