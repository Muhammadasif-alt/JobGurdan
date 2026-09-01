@extends('user.layouts.master')
@section('title', 'Retail Jobs | USA Jobs')
@section('meta_description', 'Explore retail jobs nationwide. Find positions in sales, store management, inventory, merchandising, and customer service.')
@section('og_title', 'Retail Jobs | USA Jobs')
@section('og_description', 'Explore retail jobs nationwide. Find positions in sales, store management, inventory, merchandising, and customer service.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Retail Jobs',
        'intro' => [
            'Retail jobs are a core part of the US economy, with opportunities ranging from entry-level sales to store management and merchandising. These roles are available in malls, big box stores, and specialty shops.',
            'Browse retail job listings to find positions that match your strengths in customer interaction, organization, and sales.',
        ],
        'sections' => [
            [
                'title' => 'Types of Retail Positions',
                'paragraphs' => [
                    'Common roles include sales associate, cashier, store manager, visual merchandiser, and inventory specialist. Many retailers offer part-time and seasonal positions, as well as full-time management tracks.',
                    'Retail employers often value strong customer service skills, reliability, and the ability to work flexible schedules.',
                ],
            ],
            [
                'title' => 'How to Succeed in Retail Hiring',
                'paragraphs' => [
                    'Highlight your experience with customer interaction, point-of-sale systems, and inventory management. Include any sales achievements or leadership experience.',
                    'Apply early for seasonal positions and be prepared to discuss your availability and scheduling preferences.',
                ],
            ],
        ],
        'jobRoles' => [
            'Sales Associate',
            'Cashier',
            'Store Manager',
            'Visual Merchandiser',
            'Inventory Specialist',
            'Customer Service Representative',
        ],
        'ctaText' => 'Browse Retail Jobs',
        'filterType' => 'category',
        'filterValue' => 'Retail',
        'accentText' => 'Retail',
        'eyebrow' => 'Retail &amp; Sales',
    ])
@endsection
