@extends('user.layouts.master')
@section('title', 'Jobs in Georgia — Atlanta, Ports and Film | JobGader')
@section('meta_description', 'Jobs in Georgia: Atlanta corporate and aviation hiring, Savannah port logistics, the film industry, and what each sector pays.')
@section('og_title', 'Jobs in Georgia — Atlanta, Ports and Film | JobGader')
@section('og_description', 'Jobs in Georgia: Atlanta corporate and aviation hiring, Savannah port logistics, the film industry, and what each sector pays.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Georgia',
        'intro' => [
            'Georgia has built its economy on movement — the world busiest passenger airport, one of the fastest-growing container ports in the country, and a corporate base in Atlanta that keeps drawing headquarters south.',
            'This page covers what is hiring across Georgia, what the main sectors pay, and where the openings concentrate outside Atlanta.',
        ],
        'sections' => [
            [
                'title' => 'Atlanta Is the Centre of Gravity',
                'paragraphs' => [
                    'Atlanta hosts a substantial corporate headquarters base across logistics, telecommunications, media and financial services, and Hartsfield-Jackson is the busiest passenger airport in the world, which sustains an enormous aviation and ground services workforce.',
                    'That airport economy is one of the most accessible entry points in the state — ramp agents, baggage handlers, customer service and catering roles hire continuously without qualifications.',
                ],
            ],
            [
                'title' => 'The Port of Savannah',
                'paragraphs' => [
                    'Savannah is one of the largest and fastest-growing container ports in the United States, and the warehousing and distribution belt inland from it has expanded rapidly alongside.',
                    'Port operations, freight handling, warehouse work and CDL driving hire steadily across that corridor, and the driving roles in particular pay above the state average.',
                ],
            ],
            [
                'title' => 'Film, Manufacturing and Healthcare',
                'paragraphs' => [
                    'Georgia hosts a large film and television production industry supported by state tax incentives, which sustains crew, production support and logistics roles concentrated around Atlanta.',
                    'Manufacturing — automotive, aerospace and food processing — and healthcare across the state round out the picture, with healthcare providing the most consistent year-round hiring outside the metro.',
                ],
            ],
        ],
        'jobRoles' => [
            'Warehouse Associate',
            'Ramp Agent',
            'CDL-A Truck Driver',
            'Registered Nurse',
            'Customer Service Representative',
            'Production Assistant',
            'Manufacturing Operative',
            'Logistics Coordinator',
        ],
        'faqs' => [
            [
                'q' => 'What is the biggest employer sector in Georgia?',
                'a' => 'Logistics and transport broadly, driven by Hartsfield-Jackson airport and the Port of Savannah, alongside a large corporate headquarters base in Atlanta.',
            ],
            [
                'q' => 'Are there jobs at Atlanta airport without experience?',
                'a' => 'Yes. Ramp agent, baggage handling, customer service and catering roles hire continuously and train on site, subject to a background check and security clearance for airside work.',
            ],
            [
                'q' => 'Is the film industry a realistic career here?',
                'a' => 'It is a genuine employer supported by state incentives, though production work is project-based rather than steady. Crew and production support roles are the usual entry points.',
            ],
            [
                'q' => 'Where is warehouse work concentrated?',
                'a' => 'Along the corridor inland from the Port of Savannah, and around Atlanta. Both have grown quickly with port volumes.',
            ],
            [
                'q' => 'Do I need an account to apply?',
                'a' => 'No, and applying is free. Every listing links through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse Georgia Jobs',
        'filterType' => 'state',
        'filterValue' => 'Georgia',
        'accentText' => 'Georgia',
        'eyebrow' => 'Jobs in Georgia',
    ])
@endsection
