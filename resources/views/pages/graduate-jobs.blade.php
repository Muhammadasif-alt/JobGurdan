@extends('user.layouts.master')
@section('title', 'Graduate Jobs — Schemes, Salaries and Timing | JobGader')
@section('meta_description', 'Graduate jobs and schemes across '.$coverage->shortList().': typical starting salaries, when applications open, and how the selection process works.')
@section('og_title', 'Graduate Jobs — Schemes, Salaries and Timing | JobGader')
@section('og_description', 'Graduate jobs and schemes across '.$coverage->shortList().': typical starting salaries, when applications open, and how the selection process works.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Graduate Jobs',
        'intro' => [
            'Graduate hiring runs on a calendar, and missing it costs more than a weak application does. Large employers open their schemes long before the roles begin, recruit on a rolling basis, and close early when they fill.',
            'This page collects graduate and trainee openings from our listings, with the timing you need to plan around, realistic starting salaries and what each stage of a graduate selection process is actually testing.',
        ],
        'sections' => [
            [
                'title' => 'The Timing Is the Whole Game',
                'paragraphs' => [
                    'Major graduate schemes typically open applications in the autumn for roles starting the following summer, and many operate rolling selection — meaning places go as strong candidates apply rather than after a deadline. Applying in month one and month five of the same window are not equivalent.',
                    'Smaller employers hire much closer to the start date and advertise graduate roles year-round. If you have missed the scheme cycle, that is where to look rather than waiting a full year for the next round.',
                ],
            ],
            [
                'title' => 'What Graduate Roles Pay',
                'paragraphs' => [
                    'In the US, graduate and entry-level professional salaries commonly start between $50,000 and $70,000, with engineering, finance and technology at the top of that range and non-profit and public sector below it.',
                    'In the UK, graduate schemes commonly start at £25,000 to £35,000, with investment banking, law and consulting well above. In Pakistan, management trainee programmes at large employers commonly start around PKR 60,000 to 120,000 a month.',
                ],
            ],
            [
                'title' => 'What Each Selection Stage Tests',
                'paragraphs' => [
                    'The online application and situational judgement test screen for basic fit and are usually automated. Numerical and verbal reasoning tests follow, and these are practisable — candidates who have done twenty practice tests score visibly better than those who have done none, regardless of ability.',
                    'The video interview stage tests structure more than content: answer in a clear beginning, middle and end. The assessment centre is testing how you behave in a group, and the common failure is either dominating the discussion or disappearing from it.',
                ],
            ],
            [
                'title' => 'When Your Degree Is Not in the Field',
                'paragraphs' => [
                    'Most graduate schemes outside engineering, medicine and law accept any discipline, and say so. What they screen for instead is evidence of commercial awareness — some understanding of what the employer does, who its competitors are, and what pressure its industry is under.',
                    'An internship, a placement year or a part-time job during study carries more weight than the class of degree at many employers. If you have one, build the application around it.',
                ],
            ],
        ],
        'jobRoles' => [
            'Graduate Analyst',
            'Management Trainee',
            'Graduate Engineer',
            'Junior Developer',
            'Graduate Marketing Executive',
            'Trainee Accountant',
            'Graduate Consultant',
            'Research Assistant',
        ],
        'faqs' => [
            [
                'q' => 'When should I apply for graduate schemes?',
                'a' => 'As early in the window as you can. Many large employers recruit on a rolling basis in the autumn for roles starting the following summer, and close once they are full rather than at the stated deadline.',
            ],
            [
                'q' => 'Do I need a first-class degree?',
                'a' => 'Fewer employers set that bar than candidates assume. A 2:1 or equivalent is a common minimum, and many have dropped fixed academic requirements entirely in favour of testing and assessment.',
            ],
            [
                'q' => 'Can I apply with a degree in an unrelated subject?',
                'a' => 'For most schemes outside engineering, medicine and law, yes, and many state that explicitly. What they want instead is evidence you understand the business you are applying to.',
            ],
            [
                'q' => 'How do I prepare for aptitude tests?',
                'a' => 'By practising them. Numerical and verbal reasoning tests reward familiarity with the format and the time pressure, and the difference between no practice and twenty practice tests is large and measurable.',
            ],
            [
                'q' => 'What if I missed the graduate scheme window?',
                'a' => 'Look at smaller employers, who hire graduates year-round and much closer to the start date. A first role there is worth more than waiting twelve months for the next scheme cycle.',
            ],
        ],
        'ctaText' => 'Explore Graduate Jobs',
        'filterType' => 'experience',
        'filterValue' => ['graduate', 'trainee', 'junior', 'entry level'],
        'accentText' => 'Graduate',
        'eyebrow' => 'New Graduates',
    ])
@endsection
