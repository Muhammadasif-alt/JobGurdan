@extends('user.layouts.master')
@section('title', 'Jobs in North Carolina | USA Jobs')
@section('meta_description', 'Find North Carolina jobs in Raleigh, Charlotte, Durham, and the Research Triangle. Browse careers in technology, healthcare, finance, and manufacturing.')
@section('og_title', 'Jobs in North Carolina | USA Jobs')
@section('og_description', 'Find North Carolina jobs in Raleigh, Charlotte, Durham, and the Research Triangle. Browse careers in technology, healthcare, finance, and manufacturing.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in North Carolina',
        'intro' => [
            'North Carolina’s Research Triangle and Charlotte metro area are hotspots for tech, finance, healthcare, and biotech careers. The state offers a combination of growing business centers and a lower cost of living than many coastal markets.',
            'Search job listings statewide and focus on positions that match your specialization and preferred city.',
        ],
        'sections' => [
            [
                'title' => 'High-Growth Sectors in North Carolina',
                'paragraphs' => [
                    'The Research Triangle (Raleigh, Durham, Chapel Hill) is known for IT and biotech, while Charlotte is a major banking and financial services hub. Healthcare and manufacturing also provide strong job pipelines.',
                    'Remote roles are common, especially in technology and digital marketing, offering flexibility for job seekers across the state.',
                ],
            ],
            [
                'title' => 'Tips for Finding North Carolina Jobs',
                'paragraphs' => [
                    'Use our site’s location filters to focus on specific cities, and apply early since many North Carolina employers move quickly. Include your willingness to work remotely or in hybrid roles.',
                    'Highlight experience with tools and technologies commonly used in the Triangle, such as cloud platforms, data analysis tools, and healthcare systems.',
                ],
            ],
        ],
        'jobRoles' => [
            'Data Analyst',
            'Software Developer',
            'Healthcare Nurse',
            'Financial Analyst',
            'Manufacturing Technician',
            'Marketing Specialist',
        ],
        'ctaText' => 'Browse North Carolina Jobs',
        'filterType' => 'state',
        'filterValue' => 'North Carolina',
        'accentText' => 'North Carolina',
        'eyebrow' => 'Jobs in North Carolina',
    ])
@endsection
