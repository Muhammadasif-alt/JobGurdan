@extends('user.layouts.master')
@section('title', 'Jobs in Washington — Tech, Aerospace and Trade | JobGader')
@section('meta_description', 'Jobs in Washington State: Seattle technology, aerospace manufacturing, agriculture and port logistics, and how no state income tax affects pay.')
@section('og_title', 'Jobs in Washington — Tech, Aerospace and Trade | JobGader')
@section('og_description', 'Jobs in Washington State: Seattle technology, aerospace manufacturing, agriculture and port logistics, and how no state income tax affects pay.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Washington',
        'intro' => [
            'Washington splits sharply down the middle. West of the Cascades is technology, aerospace and international trade at high wages and high costs; east of them is agriculture and food processing at a fraction of both.',
            'This page covers what is hiring across Washington State, what the main sectors pay, and what the absence of a state income tax means in practice.',
        ],
        'sections' => [
            [
                'title' => 'Seattle and the Technology Sector',
                'paragraphs' => [
                    'The Seattle and Bellevue-Redmond corridor is one of the largest technology employment centres in the world, spanning cloud infrastructure, software engineering, data and the enormous support functions attached to them.',
                    'Alongside the engineering roles sit substantial operations, logistics, customer support and warehouse workforces, which is where most of the accessible entry-level hiring in the region actually is.',
                ],
            ],
            [
                'title' => 'Aerospace and Manufacturing',
                'paragraphs' => [
                    'Commercial aircraft manufacturing and its supply chain remain a major employer in the Puget Sound region, sustaining assembly, machining, composites and quality roles.',
                    'These are skilled manufacturing jobs that pay well without requiring a degree, and the certifications involved transfer to other aerospace employers nationally.',
                ],
            ],
            [
                'title' => 'Agriculture, Ports and Taxes',
                'paragraphs' => [
                    'Eastern Washington is one of the most productive agricultural regions in the country — apples, wine, hops and potatoes — with substantial seasonal hiring and food processing work year-round.',
                    'Washington levies no personal state income tax, which lifts take-home pay relative to states that do. The offset is a high sales tax and, in the Seattle area, housing costs among the highest in the country.',
                ],
            ],
        ],
        'jobRoles' => [
            'Software Engineer',
            'Warehouse Associate',
            'Aerospace Assembler',
            'CNC Machinist',
            'Agricultural Worker',
            'Customer Service Representative',
            'Registered Nurse',
            'Logistics Coordinator',
        ],
        'faqs' => [
            [
                'q' => 'Is Seattle only good for technology jobs?',
                'a' => 'No. The technology employers also run very large operations, logistics, support and warehouse workforces, and those are where most accessible entry-level hiring in the region sits.',
            ],
            [
                'q' => 'Does Washington have a state income tax?',
                'a' => 'No personal state income tax, which raises take-home pay compared with states that levy one. Sales tax is high and Seattle housing costs are among the steepest in the country.',
            ],
            [
                'q' => 'What work is there in eastern Washington?',
                'a' => 'Agriculture and food processing, one of the most productive farming regions in the US, with substantial seasonal hiring and year-round processing work at much lower living costs.',
            ],
            [
                'q' => 'Do aerospace jobs need a degree?',
                'a' => 'Many do not. Assembly, machining and composites roles are skilled manufacturing positions with certifications that transfer to aerospace employers elsewhere.',
            ],
            [
                'q' => 'Is applying free?',
                'a' => 'Yes, and no account is needed. Each listing links through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse Washington Jobs',
        'filterType' => 'state',
        'filterValue' => 'Washington',
        'accentText' => 'Washington',
        'eyebrow' => 'Jobs in Washington',
    ])
@endsection
