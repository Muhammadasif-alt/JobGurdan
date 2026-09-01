@extends('user.layouts.master')
@section('title', 'Graduate Jobs | USA Jobs')
@section('meta_description', 'Find graduate jobs and internships across the USA. Discover opportunities for recent college graduates and early career professionals.')
@section('og_title', 'Graduate Jobs | USA Jobs')
@section('og_description', 'Find graduate jobs and internships across the USA. Discover opportunities for recent college graduates and early career professionals.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Graduate Jobs',
        'intro' => [
            'Graduate jobs are tailored for recent college graduates and early career professionals. These positions are designed to help you transition from academic life to the professional workplace.',
            'You can find graduate roles in technology, finance, marketing, engineering, and more. Many employers also offer structured graduate programs that include training and mentorship.',
        ],
        'sections' => [
            [
                'title' => 'Types of Graduate Jobs',
                'paragraphs' => [
                    'Graduate jobs include trainee roles, rotational programs, and junior positions in areas like software development, accounting, project management, and human resources.',
                    'Look for companies that provide mentorship, training, and clear development paths for new graduates.',
                ],
            ],
            [
                'title' => 'How to Stand Out as a Graduate Candidate',
                'paragraphs' => [
                    'Highlight academic achievements, internships, and any relevant extracurricular activities that show leadership, communication, or technical skills.',
                    'Customize your resume and cover letter for each application, and practice interview questions related to your chosen field.',
                ],
            ],
        ],
        'jobRoles' => [
            'Graduate Analyst',
            'Junior Software Engineer',
            'Graduate Sales Representative',
            'Operations Coordinator',
            'Graduate Marketing Associate',
            'HR Assistant',
        ],
        'ctaText' => 'Explore Graduate Jobs',
        'filterType' => 'experience',
        'filterValue' => ['graduate', 'trainee', 'junior', 'entry level'],
        'accentText' => 'Graduate',
        'eyebrow' => 'New Graduates',
    ])
@endsection
