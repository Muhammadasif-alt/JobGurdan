@extends('user.layouts.master')
@section('title', 'Jobs in Pennsylvania — Health, Freight, Energy | JobGader')
@section('meta_description', 'Jobs in Pennsylvania: healthcare and education in Philadelphia and Pittsburgh, the I-78 freight corridor, and energy work in the north.')
@section('og_title', 'Jobs in Pennsylvania — Health, Freight, Energy | JobGader')
@section('og_description', 'Jobs in Pennsylvania: healthcare and education in Philadelphia and Pittsburgh, the I-78 freight corridor, and energy work in the north.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Pennsylvania',
        'intro' => [
            'Pennsylvania has rebuilt its economy around hospitals, universities and freight. The steel-town image is decades out of date; the largest employers in both major cities are now health systems and universities.',
            'This page covers what is actually hiring across Pennsylvania, what the main sectors pay, and where in the state each one concentrates.',
        ],
        'sections' => [
            [
                'title' => 'Hospitals and Universities Lead',
                'paragraphs' => [
                    'Philadelphia and Pittsburgh are both anchored by large health systems and universities, which together form the biggest employment base in the state and hire continuously across clinical, research, administrative and support roles.',
                    'This makes healthcare support work — nursing assistants, home health aides, patient services, medical records — one of the most reliable entry points anywhere in the state, with short certification routes into better pay.',
                ],
            ],
            [
                'title' => 'The Freight Corridor',
                'paragraphs' => [
                    'The corridor along Interstates 78 and 81 through the Lehigh Valley and central Pennsylvania has become one of the densest warehousing regions in the eastern United States, because it can reach a large share of the US population within a day drive.',
                    'Warehouse, distribution and CDL driving roles hire continuously across that corridor, with the usual premiums for night and weekend shifts and a well-worn route into supervision.',
                ],
            ],
            [
                'title' => 'Energy and Manufacturing',
                'paragraphs' => [
                    'Natural gas extraction across the northern and western parts of the state supports engineering, plant, driving and skilled trade roles, with pay well above the regional average for those willing to work rotational schedules.',
                    'Manufacturing persists in specialised form — food processing, pharmaceuticals, machinery and metals — rather than at the scale of the past, and skilled trades and maintenance technicians remain in steady demand within it.',
                ],
            ],
        ],
        'jobRoles' => [
            'Registered Nurse',
            'Certified Nursing Assistant',
            'Warehouse Operative',
            'CDL-A Truck Driver',
            'Maintenance Technician',
            'Manufacturing Operative',
            'Customer Service Representative',
            'Medical Records Clerk',
        ],
        'faqs' => [
            [
                'q' => 'What are the biggest employers in Pennsylvania?',
                'a' => 'Health systems and universities, particularly in Philadelphia and Pittsburgh. Together they form the largest employment base in the state.',
            ],
            [
                'q' => 'Where is warehouse work concentrated?',
                'a' => 'Along the Interstate 78 and 81 corridor through the Lehigh Valley and central Pennsylvania, one of the densest distribution regions in the eastern US.',
            ],
            [
                'q' => 'Is there still manufacturing work?',
                'a' => 'Yes, in specialised form — food processing, pharmaceuticals, machinery and metals — rather than at historic scale. Skilled trades and maintenance technicians are in steady demand.',
            ],
            [
                'q' => 'What pays best without a degree?',
                'a' => 'Energy sector work in the north and west, and skilled trades generally, particularly for those willing to work rotational or night schedules.',
            ],
            [
                'q' => 'Do I need an account to apply?',
                'a' => 'No. Applying is free and each listing links through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse Pennsylvania Jobs',
        'filterType' => 'state',
        'filterValue' => 'Pennsylvania',
        'accentText' => 'Pennsylvania',
        'eyebrow' => 'Jobs in Pennsylvania',
    ])
@endsection
