@extends('user.layouts.master')
@section('title', 'Marketing Jobs | USA Jobs')
@section('meta_description', 'Search marketing jobs across the USA. Find roles in digital marketing, advertising, content, social media, and branding.')
@section('og_title', 'Marketing Jobs | USA Jobs')
@section('og_description', 'Search marketing jobs across the USA. Find roles in digital marketing, advertising, content, social media, and branding.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Marketing Jobs',
        'intro' => [
            'Marketing jobs span a wide range of specialties, including digital marketing, social media, content creation, branding, and advertising. Businesses of all sizes need marketing professionals to grow their audience and revenue.',
            'Use our search tools to find marketing roles that match your skills and interests, whether you are focused on digital channels or traditional marketing strategies.',
        ],
        'sections' => [
            [
                'title' => 'Types of Marketing Roles',
                'paragraphs' => [
                    'Common roles include marketing coordinator, social media manager, content writer, SEO specialist, and marketing analyst. Many companies also hire for product marketing and brand management positions.',
                    'Marketing jobs often require creativity, strong communication, and the ability to interpret data to drive results.',
                ],
            ],
            [
                'title' => 'How to Find the Right Marketing Job',
                'paragraphs' => [
                    'Build a portfolio of marketing work, such as campaigns, writing samples, or analytics reports, to demonstrate your impact.',
                    'Tailor your resume and cover letter to highlight relevant marketing tools and techniques that match the job description.',
                ],
            ],
        ],
        'jobRoles' => [
            'Digital Marketing Specialist',
            'Content Strategist',
            'Social Media Manager',
            'SEO Analyst',
            'Marketing Coordinator',
            'Brand Manager',
        ],
        'ctaText' => 'Browse Marketing Jobs',
        'filterType' => 'category',
        'filterValue' => 'Marketing',
        'accentText' => 'Marketing',
        'eyebrow' => 'Marketing &amp; Brand',
    ])
@endsection
