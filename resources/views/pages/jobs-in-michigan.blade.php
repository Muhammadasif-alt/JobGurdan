@extends('user.layouts.master')
@section('title', 'Jobs in Michigan — Automotive, Health, Engineering | JobGader')
@section('meta_description', 'Jobs in Michigan: automotive and EV manufacturing, engineering, healthcare and skilled trades hiring, plus what each sector pays.')
@section('og_title', 'Jobs in Michigan — Automotive, Health, Engineering | JobGader')
@section('og_description', 'Jobs in Michigan: automotive and EV manufacturing, engineering, healthcare and skilled trades hiring, plus what each sector pays.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Jobs in Michigan',
        'intro' => [
            'Michigan remains the centre of the American automotive industry, and the shift towards electric vehicles has changed what that means rather than ending it — battery plants, software and new engineering disciplines now sit alongside traditional assembly.',
            'This page covers what is hiring across Michigan, what the main sectors pay, and where the openings are outside automotive.',
        ],
        'sections' => [
            [
                'title' => 'Automotive and What It Became',
                'paragraphs' => [
                    'Vehicle assembly, parts manufacturing and the engineering base around Detroit and Ann Arbor still employ heavily, and the transition to electric vehicles has brought battery production and vehicle software into the same region.',
                    'Production operative roles hire without prior experience and train on site. Skilled trades — tool and die, industrial maintenance, controls technicians — are consistently short-supplied and among the best-paid work in the state without a degree.',
                ],
            ],
            [
                'title' => 'Engineering and Research',
                'paragraphs' => [
                    'Michigan has an unusually high concentration of automotive and mechanical engineers, supported by strong university programmes and the research operations attached to the manufacturers.',
                    'Software and controls engineering has grown substantially as vehicles have become more software-defined, and those roles now compete with traditional technology employers on pay.',
                ],
            ],
            [
                'title' => 'Healthcare and the Rest of the State',
                'paragraphs' => [
                    'Large hospital systems across Detroit, Grand Rapids and Ann Arbor make healthcare the largest employer statewide, with the usual accessible entry points through nursing assistant and home health roles.',
                    'Agriculture and food processing are significant across the west and north of the state, and tourism sustains seasonal hospitality work around the lakes through the summer.',
                ],
            ],
        ],
        'jobRoles' => [
            'Production Operative',
            'Industrial Maintenance Technician',
            'Automotive Engineer',
            'Registered Nurse',
            'Certified Nursing Assistant',
            'CNC Machinist',
            'Warehouse Associate',
            'Controls Engineer',
        ],
        'faqs' => [
            [
                'q' => 'Is the automotive industry still hiring in Michigan?',
                'a' => 'Yes. Assembly and parts manufacturing continue to employ heavily, and the electric vehicle transition has added battery production and vehicle software work in the same region.',
            ],
            [
                'q' => 'What pays best without a degree in Michigan?',
                'a' => 'Skilled trades — tool and die, industrial maintenance, controls and CNC machining. All are consistently short-supplied and paid accordingly.',
            ],
            [
                'q' => 'Do production jobs require experience?',
                'a' => 'Usually not. Production operative roles hire without experience and train on site. Trades roles are different and need a recognised background.',
            ],
            [
                'q' => 'What else employs at scale here?',
                'a' => 'Healthcare, which is the largest employer statewide, plus agriculture and food processing in the west and north, and seasonal tourism around the lakes.',
            ],
            [
                'q' => 'Do I need to register to apply?',
                'a' => 'No. Applying is free and each listing links through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse Michigan Jobs',
        'filterType' => 'state',
        'filterValue' => 'Michigan',
        'accentText' => 'Michigan',
        'eyebrow' => 'Jobs in Michigan',
    ])
@endsection
