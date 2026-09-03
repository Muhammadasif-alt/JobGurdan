@extends('user.layouts.master')
@section('title', 'Work From Home Jobs — Real Options | JobGader')
@section('meta_description', 'Work from home jobs across the '.$coverage->shortList().': which roles are genuine, what they pay, and how to filter out the fee-charging scams.')
@section('og_title', 'Work From Home Jobs — Real Options | JobGader')
@section('og_description', 'Work from home jobs across the '.$coverage->shortList().': which roles are genuine, what they pay, and how to filter out the fee-charging scams.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Work From Home Jobs',
        'intro' => [
            'Work from home is a real and growing category and also the phrase that attracts the highest concentration of fraudulent listings anywhere online. Both facts have to be held at once.',
            'This page collects genuine home-based openings from our listings, with what they actually pay, what employers require of your setup, and the specific patterns that identify a scam before it costs you anything.',
        ],
        'sections' => [
            [
                'title' => 'The Roles That Are Genuinely Home-Based',
                'paragraphs' => [
                    'Customer support, virtual assistance, bookkeeping, transcription, content writing, SEO, tutoring and technical support all hire home-based staff routinely, and all pay ordinary market wages for the skill involved.',
                    'That last point is the useful filter. A genuine home-based customer support job pays roughly what an office-based one pays, minus commuting. Anything promising several times the market rate for simple work is not what it claims to be.',
                ],
            ],
            [
                'title' => 'What Home-Based Work Pays',
                'paragraphs' => [
                    'In the US, home-based customer support commonly runs $16 to $23 an hour, virtual assistants $18 to $28, and bookkeepers $22 to $35 depending on qualification and client size.',
                    'In the UK, home-based support roles commonly sit at £11 to £15 an hour and virtual assistant work at £13 to £20. In Pakistan and similar markets, remote work for overseas clients commonly ranges from PKR 60,000 to 250,000 a month depending on skill and client.',
                ],
            ],
            [
                'title' => 'What Employers Require of Your Setup',
                'paragraphs' => [
                    'Home-based roles carry conditions that office roles do not. A wired internet connection with a stated minimum speed, a quiet room without background noise, and a fixed schedule are common requirements, particularly for voice work.',
                    'Some employers supply a laptop and require you to use only that device for security reasons. Ask who provides equipment and whether there is a stipend, and confirm before you commit to buying anything yourself.',
                ],
            ],
            [
                'title' => 'The Scam Patterns, Precisely',
                'paragraphs' => [
                    'Three patterns account for almost all of it. The advance fee scam asks you to pay for training, software, a starter kit or a background check. The overpayment scam sends you a cheque or transfer and asks you to forward part of it on, leaving you liable when the original payment reverses. The reshipping scam has you receive and forward parcels bought with stolen cards.',
                    'The rule that catches all three is simple: money never moves from you to an employer, and a job that involves handling other people money or goods on your own account is not a job.',
                ],
            ],
        ],
        'jobRoles' => [
            'Virtual Assistant',
            'Remote Customer Support Agent',
            'Transcriptionist',
            'Remote Bookkeeper',
            'Online Tutor',
            'Content Writer',
            'Data Entry Clerk',
            'Technical Support Agent',
        ],
        'faqs' => [
            [
                'q' => 'Are work from home jobs legitimate?',
                'a' => 'Many are. Customer support, virtual assistance, bookkeeping, transcription, tutoring and writing all hire home-based staff at ordinary market wages. The high-paying no-skill offers circulating on messaging apps are not among them.',
            ],
            [
                'q' => 'How do I spot a work from home scam?',
                'a' => 'Money moving towards the employer is the giveaway — fees for training, software, equipment or background checks. Also refuse any role that asks you to forward payments or reship parcels.',
            ],
            [
                'q' => 'What equipment do I need?',
                'a' => 'Usually a reliable wired internet connection and a quiet space, and for voice work a headset. Ask whether the employer provides a laptop, since many require you to work only on their device.',
            ],
            [
                'q' => 'Do work from home jobs pay less?',
                'a' => 'Broadly they pay the market rate for the skill involved. Anything advertising far above that for simple work is signalling that it is not a genuine job.',
            ],
            [
                'q' => 'Do I need to sign up to apply here?',
                'a' => 'No. Applying is free and every listing links through to the employer or the original posting.',
            ],
        ],
        'ctaText' => 'Browse Work From Home Jobs',
        'filterType' => 'remote',
        'accentText' => 'Work From Home',
        'eyebrow' => 'Work From Home',
    ])
@endsection
