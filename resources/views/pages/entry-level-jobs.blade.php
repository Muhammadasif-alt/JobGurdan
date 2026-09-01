@extends('user.layouts.master')
@section('title', 'Entry Level Jobs | USA Jobs')
@section('meta_description', 'Explore entry level jobs across the USA. Find entry-level roles in customer service, administration, technology, and more.')
@section('og_title', 'Entry Level Jobs | USA Jobs')
@section('og_description', 'Explore entry level jobs across the USA. Find entry-level roles in customer service, administration, technology, and more.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Entry Level Jobs',
        'intro' => [
            'Entry level jobs are perfect for new graduates, career changers, or those returning to the workforce. These roles often provide training and skill development to help you grow in your career.',
            'Search entry-level job listings across industries and apply for positions that match your interests and strengths.',
        ],
        'sections' => [
            [
                'title' => 'Common Entry Level Roles',
                'paragraphs' => [
                    'Many entry-level positions are available in customer support, administrative assistance, sales, marketing, and IT support. Employers are often willing to train employees who demonstrate a strong work ethic and willingness to learn.',
                    'Be sure to highlight any internships, volunteer work, coursework, or transferable skills that show your readiness to work.',
                ],
            ],
            [
                'title' => 'How to Apply for Entry Level Jobs',
                'paragraphs' => [
                    'Create a resume that emphasizes your skills, education, and achievements. Use a clear format and include any relevant experiences that demonstrate your ability to succeed in the role.',
                    'When applying, focus on roles that offer training and development, and include a cover letter that explains your motivation and goals.',
                ],
            ],
        ],
        'jobRoles' => [
            'Administrative Assistant',
            'Customer Service Representative',
            'Sales Associate',
            'Junior Software Developer',
            'Marketing Assistant',
            'Data Entry Clerk',
        ],
        'ctaText' => 'Browse Entry Level Jobs',
        'filterType' => 'experience',
        'filterValue' => ['entry level', 'entry-level', 'junior', 'associate', 'trainee'],
        'accentText' => 'Entry Level',
        'eyebrow' => 'Career Starter',
    ])
@endsection
