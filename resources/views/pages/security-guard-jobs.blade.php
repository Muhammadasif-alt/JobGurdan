@extends('user.layouts.master')
@section('title', 'Security Guard Jobs — Licence, Pay and Shifts | JobGader')
@section('meta_description', 'Security guard jobs across the USA and UK: the licence you need first, what the shifts pay, and which sectors pay above the standard rate.')
@section('og_title', 'Security Guard Jobs — Licence, Pay and Shifts | JobGader')
@section('og_description', 'Security guard jobs across the USA and UK: the licence you need first, what the shifts pay, and which sectors pay above the standard rate.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Security Guard Jobs',
        'intro' => [
            'Security is one of the few fields where a short, inexpensive licence unlocks the whole job market. Get the badge first and the applications become straightforward; apply without it and most listings will not consider you at all.',
            'This page collects security and safety openings from our listings, with what the licence involves, what the different sectors pay, and where the work is genuinely better paid than the headline rate suggests.',
        ],
        'sections' => [
            [
                'title' => 'The Licence Comes First',
                'paragraphs' => [
                    'In the UK, you need an SIA licence before you can work in most security roles. Door supervision covers licensed premises and is the more flexible card; the security guarding licence covers static and patrol work. Both require a short training course and a criminal record check, and the licence is tied to you rather than to an employer.',
                    'In the US, licensing is set at state level and terminology varies — a guard card, a security officer licence or a registration, depending on where you are. Armed positions require a separate and considerably stricter permit. Check your state before paying for any training.',
                ],
            ],
            [
                'title' => 'What Security Work Pays',
                'paragraphs' => [
                    'In the US, unarmed security officers are commonly advertised at $15 to $22 an hour, with armed positions, corporate sites and specialist assignments paying meaningfully more. In the UK, security officers typically earn £11 to £15 an hour, with door supervisors on weekend nights above that.',
                    'The premium sectors are consistent in both countries: data centres, pharmaceutical and industrial sites, corporate headquarters and events with specialist requirements. Retail loss prevention and standard static guarding sit at the lower end.',
                ],
            ],
            [
                'title' => 'Shifts Are the Job',
                'paragraphs' => [
                    'Twelve-hour shifts are standard, and night work is a large share of the sector. That suits some people well and others not at all, and it is worth being honest with yourself before committing, because rota patterns are difficult to change once you are on a site.',
                    'Static site work is predictable and quiet. Door supervision, event security and retail loss prevention involve regular confrontation. These are genuinely different jobs sharing a licence, so choose deliberately.',
                ],
            ],
            [
                'title' => 'How to Move Up in Security',
                'paragraphs' => [
                    'Progression runs through supervisor and site manager roles into control room, corporate security and risk positions, and the pay difference at the top of that ladder is substantial. CCTV operation requires its own licence in the UK and is a common next step.',
                    'A clean record on incident reporting is what gets you promoted. Guards who write clear, accurate, timely reports are trusted with better sites, and better sites are where the money is.',
                ],
            ],
        ],
        'jobRoles' => [
            'Security Guard',
            'Security Officer',
            'Door Supervisor',
            'CCTV Operator',
            'Loss Prevention Officer',
            'Event Security Steward',
            'Mobile Patrol Officer',
            'Security Supervisor',
        ],
        'faqs' => [
            [
                'q' => 'Do I need a licence to work in security?',
                'a' => 'In the UK, yes — an SIA licence is required for most roles and must be in place before you start. In the US, requirements are set state by state, and armed work always needs a separate and stricter permit.',
            ],
            [
                'q' => 'How long does it take to get licensed?',
                'a' => 'The UK training course is a matter of days, with the licence application and background check adding a few weeks. US timelines vary by state but are broadly comparable for unarmed work.',
            ],
            [
                'q' => 'Which security jobs pay the most?',
                'a' => 'Armed positions where permitted, plus data centres, industrial and pharmaceutical sites, and corporate headquarters. Standard retail and static guarding sit at the lower end of the range.',
            ],
            [
                'q' => 'Is night work compulsory?',
                'a' => 'Not always, but it is a large part of the sector and night shifts usually pay a premium. Rota patterns are hard to change once assigned to a site, so agree them before accepting.',
            ],
            [
                'q' => 'Do I need experience to start?',
                'a' => 'No. Once licensed, entry-level static and retail roles hire without prior security experience and train on site procedures after hiring.',
            ],
        ],
        'ctaText' => 'Browse Security Guard Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['security guard', 'security officer', 'loss prevention'],
        'accentText' => 'Security Guard',
        'eyebrow' => 'Security &amp; Safety',
    ])
@endsection
