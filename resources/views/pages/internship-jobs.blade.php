@extends('user.layouts.master')
@section('title', 'Internships — Paid, Unpaid and Worth It | JobGader')
@section('meta_description', 'Internships across the '.$coverage->shortList().': which are paid, what the law says about unpaid work, and how to turn a placement into a job offer.')
@section('og_title', 'Internships — Paid, Unpaid and Worth It | JobGader')
@section('og_description', 'Internships across the '.$coverage->shortList().': which are paid, what the law says about unpaid work, and how to turn a placement into a job offer.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Internship Jobs',
        'intro' => [
            'An internship is the most reliable way into a competitive field and also the easiest arrangement to get exploited by. The difference usually comes down to two questions: is it paid, and is there structured work at the end of it.',
            'This page collects internship and placement openings from our listings, along with what the law actually says about unpaid internships and how to convert a placement into a permanent offer.',
        ],
        'sections' => [
            [
                'title' => 'Paid, Unpaid and What the Law Says',
                'paragraphs' => [
                    'In the UK, if you are doing set hours and real work, you are generally a worker and entitled to the National Minimum Wage regardless of what the arrangement is called. Genuine work shadowing and student placements that form part of a course are the main exceptions. Calling a job an internship does not remove the entitlement.',
                    'In the US, unpaid internships at for-profit employers are only lawful where the intern is the primary beneficiary, judged against a specific set of factors. Many unpaid internships advertised in practice would not survive that test.',
                ],
            ],
            [
                'title' => 'What Internships Pay When They Pay',
                'paragraphs' => [
                    'In the US, paid internships in technology, finance and engineering commonly run $20 to $40 an hour, with the largest technology and banking employers substantially higher. General business internships commonly sit around $15 to $22.',
                    'In the UK, paid internships typically pay the National Minimum Wage up to around £22,000 pro rata, with finance and law well above. In Pakistan, internships commonly carry a stipend in the PKR 20,000 to 50,000 range, with international firms paying more.',
                ],
            ],
            [
                'title' => 'Converting a Placement Into an Offer',
                'paragraphs' => [
                    'Conversion is normal in structured programmes and rare in unstructured ones, so ask about conversion rates at interview. It is a fair question and the answer tells you what kind of internship it is.',
                    'The behaviour that converts is consistent: finish a piece of work end to end rather than assisting on several, document what you did, and make sure someone senior knows about it. Interns who leave behind a working thing get remembered; interns who were helpful do not.',
                ],
            ],
            [
                'title' => 'Spotting an Internship That Wastes Your Time',
                'paragraphs' => [
                    'Warning signs are an unpaid full-time commitment at a profitable company, no named supervisor, no defined project, and a description consisting mainly of general support duties. That combination is unpaid labour rather than training.',
                    'Anything asking you to pay for a placement, a certificate or a visa is a different problem entirely. Legitimate internships never charge the intern, and international placement schemes charging large fees deserve very close scrutiny.',
                ],
            ],
        ],
        'jobRoles' => [
            'Marketing Intern',
            'Software Engineering Intern',
            'Finance Intern',
            'HR Intern',
            'Data Analyst Intern',
            'Design Intern',
            'Research Intern',
            'Operations Intern',
        ],
        'faqs' => [
            [
                'q' => 'Should an internship be paid?',
                'a' => 'In the UK, if you are doing set hours and real work you are generally entitled to at least the National Minimum Wage, whatever the role is called. In the US, unpaid internships at for-profit employers are lawful only in narrow circumstances.',
            ],
            [
                'q' => 'Do internships lead to permanent jobs?',
                'a' => 'Structured programmes at larger employers convert at a meaningful rate. Ask about the conversion rate at interview — a programme that tracks it usually has a good answer, and one that has never considered it tells you something too.',
            ],
            [
                'q' => 'Can I do an internship after graduating?',
                'a' => 'Yes. Many internships are open to recent graduates as well as students, and some employers run graduate internships specifically as a route into permanent roles.',
            ],
            [
                'q' => 'Should I pay for an internship placement?',
                'a' => 'No. Legitimate internships do not charge the intern. Placement schemes that charge large fees for an overseas internship warrant very close scrutiny before any money changes hands.',
            ],
            [
                'q' => 'How do I get the most from a placement?',
                'a' => 'Own one piece of work from start to finish rather than assisting across several, write down what you delivered, and make sure a senior person sees it. That is what gets remembered when a permanent role opens.',
            ],
        ],
        'ctaText' => 'Search Internship Jobs',
        'filterType' => 'experience',
        'filterValue' => ['intern', 'internship'],
        'accentText' => 'Internship',
        'eyebrow' => 'Internship Opportunities',
    ])
@endsection
