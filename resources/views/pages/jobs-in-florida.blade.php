@extends('user.layouts.master')
@section('title', 'Jobs in Florida | USA Jobs')
@section('meta_description', 'Find Florida jobs in tourism, healthcare, software, and logistics. Browse opportunities in Miami, Orlando, Tampa, Jacksonville, and more.')
@section('og_title', 'Jobs in Florida | USA Jobs')
@section('og_description', 'Find Florida jobs in tourism, healthcare, software, and logistics. Browse opportunities in Miami, Orlando, Tampa, Jacksonville, and more.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Florida',
        'intro' => [
            'Florida has a booming job market across tourism, healthcare, logistics, and technology. With year-round warm weather and no state income tax, it’s an attractive place for professionals and families alike.',
            'Search Florida jobs by city, industry, and experience level — and apply directly on our platform for fast response times.',
        ],
        'sections' => [
            [
                'title' => 'Top Florida Career Hubs',
                'paragraphs' => [
                    'Miami is a major center for finance, media, and hospitality roles, while Orlando is known for tourism and theme park jobs. Tampa and Jacksonville offer growing healthcare and logistics sectors.',
                    'Many Florida employers also support remote-friendly schedules, making it easy to work from anywhere within the state.',
                ],
            ],
            [
                'title' => 'Tips for Landing a Florida Job',
                'paragraphs' => [
                    'Optimize your resume with Florida-specific keywords and highlight experience in industries that are strong in the region.',
                    'Prepare for virtual interviews and highlight your flexibility, especially if you are open to remote or hybrid roles.',
                ],
            ],
        ],
        'jobRoles' => [
            'Tourism Manager',
            'Registered Nurse',
            'Logistics Coordinator',
            'Sales Representative',
            'Software Developer',
            'Hospitality Supervisor',
        ],
        'ctaText' => 'Browse Florida Jobs',
        'filterType' => 'state',
        'filterValue' => 'Florida',
        'accentText' => 'Florida',
        'eyebrow' => 'Jobs in Florida',
    ])
@endsection
