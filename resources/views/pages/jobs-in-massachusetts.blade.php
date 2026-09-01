@extends('user.layouts.master')
@section('title', 'Jobs in Massachusetts | USA Jobs')
@section('meta_description', 'Find Massachusetts jobs in Boston, Cambridge, Worcester, and beyond. Explore opportunities in biotech, education, healthcare, and technology.')
@section('og_title', 'Jobs in Massachusetts | USA Jobs')
@section('og_description', 'Find Massachusetts jobs in Boston, Cambridge, Worcester, and beyond. Explore opportunities in biotech, education, healthcare, and technology.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Massachusetts',
        'intro' => [
            'Massachusetts is a top destination for biotech, healthcare, higher education, and technology careers. Boston and Cambridge lead with world-class research institutions and startups.',
            'Browse job opportunities throughout the state and apply to positions that match your skillset and career goals.',
        ],
        'sections' => [
            [
                'title' => 'Why Work in Massachusetts?',
                'paragraphs' => [
                    'The state excels in life sciences, higher education, and tech innovation. Many employers support hybrid and remote schedules to attract top talent.',
                    'Massachusetts also offers a strong professional network and plenty of roles for those seeking career advancement.',
                ],
            ],
            [
                'title' => 'Tips for Finding Jobs in Massachusetts',
                'paragraphs' => [
                    'Networking at local industry events and leveraging connections can help you land interviews faster.',
                    'Highlight any experience in research, biotech, or education when applying for roles in the greater Boston area.',
                ],
            ],
        ],
        'jobRoles' => [
            'Biotech Research Associate',
            'Software Developer',
            'Clinical Research Coordinator',
            'University Administrator',
            'Product Manager',
            'Healthcare Technician',
        ],
        'ctaText' => 'Browse Massachusetts Jobs',
        'filterType' => 'state',
        'filterValue' => 'Massachusetts',
        'accentText' => 'Massachusetts',
        'eyebrow' => 'Jobs in Massachusetts',
    ])
@endsection
