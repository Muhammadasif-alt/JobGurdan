@extends('user.layouts.master')
@section('title', 'Jobs in North Carolina — Triangle, Banking, Trades | JobGader')
@section('meta_description', 'Jobs in North Carolina: Research Triangle technology and pharma, Charlotte banking, manufacturing and healthcare, and what each sector pays.')
@section('og_title', 'Jobs in North Carolina — Triangle, Banking, Trades | JobGader')
@section('og_description', 'Jobs in North Carolina: Research Triangle technology and pharma, Charlotte banking, manufacturing and healthcare, and what each sector pays.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in North Carolina',
        'intro' => [
            'North Carolina has two very different high-growth centres. The Research Triangle runs on universities, pharmaceuticals and technology; Charlotte is one of the largest banking centres in the United States. Between and around them sits a substantial manufacturing base.',
            'This page covers what is hiring across North Carolina, what the main sectors pay, and where the entry-level volume actually is.',
        ],
        'sections' => [
            [
                'title' => 'The Research Triangle',
                'paragraphs' => [
                    'Raleigh, Durham and Chapel Hill together host a dense cluster of universities, pharmaceutical and biotech companies and technology employers, which makes it one of the strongest markets in the south for research, clinical trial and software roles.',
                    'Around that sits a large support economy — laboratory technicians, clinical research coordinators, facilities and administrative roles — that is more accessible than the research positions themselves.',
                ],
            ],
            [
                'title' => 'Charlotte and Financial Services',
                'paragraphs' => [
                    'Charlotte is a major US banking centre, with large retail and commercial banking operations and the back-office, compliance, technology and customer service functions that come with them.',
                    'Call centre and customer service roles at these institutions are a genuine entry point into financial services, and internal progression from them into operations and compliance is common.',
                ],
            ],
            [
                'title' => 'Manufacturing, Logistics and Healthcare',
                'paragraphs' => [
                    'Furniture, textiles, food processing and increasingly automotive and battery manufacturing employ across the state outside the two metros, with production and maintenance roles hiring steadily.',
                    'Healthcare is, as in most states, the largest single employer overall, and nursing assistant and home health roles are the most consistently available entry-level positions statewide.',
                ],
            ],
        ],
        'jobRoles' => [
            'Registered Nurse',
            'Certified Nursing Assistant',
            'Customer Service Representative',
            'Laboratory Technician',
            'Production Operative',
            'Warehouse Associate',
            'Software Developer',
            'Clinical Research Coordinator',
        ],
        'faqs' => [
            [
                'q' => 'What is the Research Triangle known for?',
                'a' => 'Universities, pharmaceuticals, biotech and technology, concentrated across Raleigh, Durham and Chapel Hill. It is one of the strongest research job markets in the southern US.',
            ],
            [
                'q' => 'Is Charlotte good for finance careers?',
                'a' => 'Yes, it is one of the largest banking centres in the country. Customer service and call centre roles at those institutions are a realistic entry point with genuine internal progression.',
            ],
            [
                'q' => 'What manufacturing exists in North Carolina?',
                'a' => 'Furniture, textiles and food processing historically, with automotive and battery manufacturing growing. Production and maintenance roles hire steadily outside the metros.',
            ],
            [
                'q' => 'What is the easiest sector to enter?',
                'a' => 'Healthcare support and customer service. Nursing assistant and home health roles need short certification, and call centre roles train after hiring.',
            ],
            [
                'q' => 'Is applying free?',
                'a' => 'Yes, and no account is needed. Every listing links through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse North Carolina Jobs',
        'filterType' => 'state',
        'filterValue' => 'North Carolina',
        'accentText' => 'North Carolina',
        'eyebrow' => 'Jobs in North Carolina',
    ])
@endsection
