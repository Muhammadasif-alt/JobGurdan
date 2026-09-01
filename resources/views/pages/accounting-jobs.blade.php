@extends('user.layouts.master')
@section('title', 'Accounting Jobs | USA Jobs')
@section('meta_description', 'Find accounting jobs across the United States. Browse openings in auditing, bookkeeping, financial analysis, and corporate accounting.')
@section('og_title', 'Accounting Jobs | USA Jobs')
@section('og_description', 'Find accounting jobs across the United States. Browse openings in auditing, bookkeeping, financial analysis, and corporate accounting.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Accounting Jobs',
        'intro' => [
            'Accounting roles are essential for businesses of all sizes, ensuring accurate financial reporting and compliance. Jobs range from bookkeeping and accounts payable to financial analysis and audit management.',
            'Search accounting job listings by specialization, experience level, and location. Apply directly to firms and corporate finance teams.',
        ],
        'sections' => [
            [
                'title' => 'Common Accounting Positions',
                'paragraphs' => [
                    'Roles include staff accountant, senior accountant, auditor, controller, and financial analyst. Many employers also seek accountants with CPA certifications.',
                    'Accounting jobs are available in public accounting firms, corporations, non-profits, and government agencies.',
                ],
            ],
            [
                'title' => 'Qualifications Employers Seek',
                'paragraphs' => [
                    'Highlight your experience with accounting software like QuickBooks, Oracle, or SAP, as well as your knowledge of GAAP and financial reporting standards.',
                    'If you have certifications like CPA, CMA, or ACCA, be sure to include them prominently in your application.',
                ],
            ],
        ],
        'jobRoles' => [
            'Staff Accountant',
            'Audit Associate',
            'Financial Analyst',
            'Accounts Payable Specialist',
            'Controller',
            'Tax Preparer',
        ],
        'ctaText' => 'Browse Accounting Jobs',
        'filterType' => 'category',
        'filterValue' => 'Accounting',
        'accentText' => 'Accounting',
        'eyebrow' => 'Accounting &amp; Finance',
    ])
@endsection
