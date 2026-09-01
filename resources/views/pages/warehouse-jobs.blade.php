@extends('user.layouts.master')
@section('title', 'Warehouse Jobs | USA Jobs')
@section('meta_description', 'Search warehouse jobs across the USA. Find roles in shipping, inventory, forklift operation, and warehouse management.')
@section('og_title', 'Warehouse Jobs | USA Jobs')
@section('og_description', 'Search warehouse jobs across the USA. Find roles in shipping, inventory, forklift operation, and warehouse management.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Warehouse Jobs',
        'intro' => [
            'Warehouse jobs are essential to the supply chain, and they are in high demand across the country. Positions range from entry-level pickers and packers to supervisors and managers.',
            'Search warehouse job listings near you, apply directly, and start a career in logistics and fulfillment.',
        ],
        'sections' => [
            [
                'title' => 'What to Expect in Warehouse Roles',
                'paragraphs' => [
                    'Most warehouse jobs involve physical tasks, teamwork, and attention to detail. Many employers offer training programs for new hires.',
                    'Some roles require certifications such as OSHA safety training or a forklift license, while others focus on general warehouse support.',
                ],
            ],
            [
                'title' => 'How to Apply for Warehouse Positions',
                'paragraphs' => [
                    'Prepare a resume that highlights your reliability, punctuality, and any prior experience in logistics or manual work.',
                    'Use our job filters to search by location, shift schedule, and experience level to find warehouse jobs that fit your needs.',
                ],
            ],
        ],
        'jobRoles' => [
            'Forklift Operator',
            'Warehouse Associate',
            'Shipping and Receiving Clerk',
            'Inventory Specialist',
            'Warehouse Supervisor',
            'Order Picker/Packer',
        ],
        'ctaText' => 'Browse Warehouse Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['warehouse', 'forklift', 'picker', 'packer'],
        'accentText' => 'Warehouse',
        'eyebrow' => 'Warehouse &amp; Logistics',
    ])
@endsection
