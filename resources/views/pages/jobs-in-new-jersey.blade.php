@extends('user.layouts.master')
@section('title', 'Jobs in New Jersey — Pharma, Ports and Finance | JobGader')
@section('meta_description', 'Jobs in New Jersey: pharmaceutical and life sciences, Port Newark logistics, finance and healthcare hiring, and what each sector pays.')
@section('og_title', 'Jobs in New Jersey — Pharma, Ports and Finance | JobGader')
@section('og_description', 'Jobs in New Jersey: pharmaceutical and life sciences, Port Newark logistics, finance and healthcare hiring, and what each sector pays.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in New Jersey',
        'intro' => [
            'New Jersey packs an unusual amount into a small state: one of the densest pharmaceutical clusters in the world, the busiest container port on the US east coast, and a finance sector spilling across the river from New York.',
            'This page covers what is hiring across New Jersey, what the main sectors pay, and how proximity to New York shapes the market.',
        ],
        'sections' => [
            [
                'title' => 'Pharmaceuticals and Life Sciences',
                'paragraphs' => [
                    'New Jersey hosts one of the highest concentrations of pharmaceutical and life sciences employers anywhere, spanning research, manufacturing, quality control and regulatory affairs.',
                    'The accessible roles here are in manufacturing and quality — production technicians, quality control analysts, warehouse and materials handling in regulated environments — which pay above general manufacturing because of the compliance requirements.',
                ],
            ],
            [
                'title' => 'The Port and the Warehouses',
                'paragraphs' => [
                    'Port Newark and Elizabeth form the busiest container complex on the east coast, and the warehousing corridor along the New Jersey Turnpike exists to serve it and the New York metropolitan market beyond.',
                    'Warehouse, freight and CDL driving roles hire continuously across that corridor, with night and weekend premiums and a steady internal route into supervision.',
                ],
            ],
            [
                'title' => 'Living Here, Working There',
                'paragraphs' => [
                    'A significant share of the state workforce commutes into New York, which pushes local salaries up in the northern counties and makes housing costs high relative to most of the country.',
                    'Working in New York while living in New Jersey has real tax consequences worth understanding before you accept an offer, since both states are involved and the treatment is not intuitive.',
                ],
            ],
        ],
        'jobRoles' => [
            'Warehouse Associate',
            'Quality Control Analyst',
            'Production Technician',
            'CDL-A Truck Driver',
            'Registered Nurse',
            'Financial Analyst',
            'Logistics Coordinator',
            'Regulatory Affairs Associate',
        ],
        'faqs' => [
            [
                'q' => 'What is New Jersey best known for employing?',
                'a' => 'Pharmaceuticals and life sciences, one of the densest clusters anywhere, alongside port logistics at Newark and Elizabeth and a finance sector linked to New York.',
            ],
            [
                'q' => 'How do I get into pharmaceutical work without a science degree?',
                'a' => 'Through manufacturing and materials roles — production technician, warehouse and quality support in regulated environments. These pay above general manufacturing and train on the compliance requirements.',
            ],
            [
                'q' => 'Is warehouse work plentiful in New Jersey?',
                'a' => 'Yes, along the Turnpike corridor serving Port Newark and the New York metropolitan market. Night and weekend shifts carry premiums.',
            ],
            [
                'q' => 'What should I know about working in New York and living in New Jersey?',
                'a' => 'Both states are involved in your tax position and the treatment is not intuitive. It is worth understanding before accepting an offer rather than after.',
            ],
            [
                'q' => 'Is applying free?',
                'a' => 'Yes, with no account required. Listings link to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse New Jersey Jobs',
        'filterType' => 'state',
        'filterValue' => 'New Jersey',
        'accentText' => 'New Jersey',
        'eyebrow' => 'Jobs in New Jersey',
    ])
@endsection
