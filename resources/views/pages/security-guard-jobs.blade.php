@extends('user.layouts.master')
@section('title', 'Security Guard Jobs | USA Jobs')
@section('meta_description', 'Search security guard jobs across the USA. Find positions in corporate security, retail security, event security, and facility protection.')
@section('og_title', 'Security Guard Jobs | USA Jobs')
@section('og_description', 'Search security guard jobs across the USA. Find positions in corporate security, retail security, event security, and facility protection.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Security Guard Jobs',
        'intro' => [
            'Security guard jobs are in demand across industries, from corporate campuses to retail stores and event venues. If you have strong situational awareness and customer service skills, this could be a great career path.',
            'Browse listings for full-time, part-time, and contract security positions across the United States.',
        ],
        'sections' => [
            [
                'title' => 'Types of Security Roles',
                'paragraphs' => [
                    'Security positions range from entry-level on-site guards to supervisory roles and loss prevention specialists. Some roles require licensing or certifications depending on state regulations.',
                    'Many employers offer training programs for new hires and opportunities to advance into management or investigative positions.',
                ],
            ],
            [
                'title' => 'How to Apply for Security Jobs',
                'paragraphs' => [
                    'Highlight any security or military experience, your attention to detail, and your communication skills. Be prepared to pass background checks and sometimes drug screenings.',
                    'Use filters to find roles near you and specify any preferences for shift type or location.',
                ],
            ],
        ],
        'jobRoles' => [
            'Security Guard',
            'Loss Prevention Officer',
            'Event Security',
            'Corporate Security Associate',
            'Security Supervisor',
            'Access Control Specialist',
        ],
        'ctaText' => 'Browse Security Guard Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['security guard', 'security officer', 'loss prevention'],
        'accentText' => 'Security Guard',
        'eyebrow' => 'Security &amp; Safety',
    ])
@endsection
