@extends('user.layouts.master')
@section('title', 'Data Entry Jobs | USA Jobs')
@section('meta_description', 'Find data entry jobs across the country. Search roles in data processing, transcription, clerical support, and remote data entry.')
@section('og_title', 'Data Entry Jobs | USA Jobs')
@section('og_description', 'Find data entry jobs across the country. Search roles in data processing, transcription, clerical support, and remote data entry.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Data Entry Jobs',
        'intro' => [
            'Data entry jobs are great for detail-oriented professionals and those seeking flexible, remote-friendly work. Positions often involve inputting, updating, and maintaining data for companies in many industries.',
            'Search data entry job listings to find roles that match your experience, including transcription, clerical, and database support positions.',
        ],
        'sections' => [
            [
                'title' => 'Common Data Entry Responsibilities',
                'paragraphs' => [
                    'Responsibilities typically include entering data into systems, verifying accuracy, and maintaining records. Strong typing skills and attention to detail are essential.',
                    'Many data entry roles require basic spreadsheet and database knowledge, while advanced positions may involve CRM systems or billing software.',
                ],
            ],
            [
                'title' => 'Finding the Right Data Entry Job',
                'paragraphs' => [
                    'Use search filters to locate remote or on-site roles, and look for positions that match your speed and accuracy strengths.',
                    'Highlight any specialized tools you’ve used, such as Microsoft Excel, QuickBooks, Salesforce, or other database systems.',
                ],
            ],
        ],
        'jobRoles' => [
            'Data Entry Clerk',
            'Transcription Specialist',
            'Administrative Assistant',
            'Billing Clerk',
            'Database Coordinator',
            'CRM Data Specialist',
        ],
        'ctaText' => 'Browse Data Entry Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['data entry', 'data clerk', 'data specialist', 'transcription'],
        'accentText' => 'Data Entry',
        'eyebrow' => 'Data &amp; Admin',
    ])
@endsection
