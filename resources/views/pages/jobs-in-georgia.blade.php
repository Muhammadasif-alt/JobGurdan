@extends('user.layouts.master')
@section('title', 'Jobs in Georgia | USA Jobs')
@section('meta_description', 'Explore Georgia jobs in Atlanta, Savannah, Augusta, and beyond. Browse opportunities in logistics, finance, film, and technology.')
@section('og_title', 'Jobs in Georgia | USA Jobs')
@section('og_description', 'Explore Georgia jobs in Atlanta, Savannah, Augusta, and beyond. Browse opportunities in logistics, finance, film, and technology.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Georgia',
        'intro' => [
            'Georgia has a rapidly growing job market, especially in Atlanta where logistics, film, film production, tech, and finance are booming. The state is also home to strong healthcare and manufacturing sectors.',
            'Search Georgia job listings and apply to positions that match your skills and lifestyle preferences, whether you prefer urban or suburban settings.',
        ],
        'sections' => [
            [
                'title' => 'Growing Industries in Georgia',
                'paragraphs' => [
                    'Georgia is known for its thriving film industry, logistical hubs around Atlanta, and many headquarters for tech and finance firms. Healthcare and education continue to add new positions as well.',
                    'There are also strong opportunities for remote work, especially in IT and creative roles.',
                ],
            ],
            [
                'title' => 'Job Search Tips for Georgia',
                'paragraphs' => [
                    'Highlight your experience with Georgia-specific markets and include keywords like “Atlanta”, “Georgia”, or “Southeast US” to help recruiters find you.',
                    'Networking through local meetups and online groups can open doors to jobs that are not publicly advertised.',
                ],
            ],
        ],
        'jobRoles' => [
            'Logistics Manager',
            'Film Production Assistant',
            'Sales Representative',
            'Healthcare Technician',
            'IT Support Specialist',
            'Marketing Coordinator',
        ],
        'ctaText' => 'Browse Georgia Jobs',
        'filterType' => 'state',
        'filterValue' => 'Georgia',
        'accentText' => 'Georgia',
        'eyebrow' => 'Jobs in Georgia',
    ])
@endsection
