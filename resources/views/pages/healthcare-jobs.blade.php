@extends('user.layouts.master')
@section('title', 'Healthcare Jobs — Roles, Pay and Openings | JobGader')
@section('meta_description', 'Healthcare jobs across the USA and UK: what nursing, care and support roles pay, which licences you need, and the visa routes that are open.')
@section('og_title', 'Healthcare Jobs — Roles, Pay and Openings | JobGader')
@section('og_description', 'Healthcare jobs across the USA and UK: what nursing, care and support roles pay, which licences you need, and the visa routes that are open.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Healthcare Jobs',
        'intro' => [
            'Healthcare covers an unusually wide range, from care assistant roles you can enter with no formal qualification to registered nursing positions that require licensing before you can work at all. The pay, the training and the visa position are completely different at each end.',
            'This page brings together healthcare and care openings from our listings, along with what each level requires and an honest account of which sponsorship routes are currently open.',
        ],
        'sections' => [
            [
                'title' => 'What Healthcare Roles Pay',
                'paragraphs' => [
                    'In the US, certified nursing assistants are commonly advertised around $16 to $22 an hour, licensed practical nurses in the $25 to $32 range, and registered nurses frequently $35 to $50 or more depending on state, specialism and shift pattern.',
                    'In the UK, care assistants are commonly £11 to £14 an hour, senior care workers slightly above, and NHS nursing runs on published Agenda for Change bands rather than negotiated salaries, which makes the pay unusually transparent before you apply.',
                ],
            ],
            [
                'title' => 'Licensing Is the Gate, Not the CV',
                'paragraphs' => [
                    'In the US, nursing is licensed state by state. Foreign-trained nurses generally need credential evaluation, an English language test and the NCLEX examination before they can practise, and that process takes months rather than weeks.',
                    'In the UK, registered nurses must be on the Nursing and Midwifery Council register, which for overseas applicants means the CBT and OSCE assessments. Care assistant roles need no register entry, which is why they are the common entry point.',
                ],
            ],
            [
                'title' => 'The Visa Position Has Changed',
                'paragraphs' => [
                    'This matters more than anything else on this page for overseas applicants. The UK closed its Health and Care Worker route to new overseas care worker and senior care worker applicants in July 2025. Care assistant sponsorship from abroad is not currently available, whatever an agency may tell you.',
                    'Registered nursing is a different matter and remains a genuine sponsored route in both the UK and the US, because it meets the skill and salary thresholds that care roles do not. Our caregiver guide sets out exactly what closed, what stayed open, and what the alternatives are.',
                ],
            ],
            [
                'title' => 'Getting Into Healthcare Without a Qualification',
                'paragraphs' => [
                    'Care assistant, healthcare assistant and support worker roles are the standard entry points and are usually filled on attitude and reliability, with training provided after hiring. A DBS check in the UK or a background check in the US is standard.',
                    'From there the progression is well worn: senior care worker, then either a nursing apprenticeship or a formal nursing qualification. Employers frequently part-fund this, and it is worth asking about at interview rather than after.',
                ],
            ],
        ],
        'jobRoles' => [
            'Care Assistant',
            'Healthcare Assistant',
            'Support Worker',
            'Certified Nursing Assistant',
            'Licensed Practical Nurse',
            'Registered Nurse',
            'Home Health Aide',
            'Senior Care Worker',
        ],
        'faqs' => [
            [
                'q' => 'Can I get a UK care worker visa from overseas?',
                'a' => 'Not currently. The Health and Care Worker route closed to new overseas care worker and senior care worker applicants in July 2025. Registered nursing remains sponsorable. Any agency charging you for a care worker visa is selling something that no longer exists.',
            ],
            [
                'q' => 'Do I need a qualification to work as a care assistant?',
                'a' => 'Generally no. Care assistant and support worker roles are usually filled on attitude and reliability with training provided, subject to a background check. Registered nursing is entirely different and requires licensing first.',
            ],
            [
                'q' => 'What does a foreign-trained nurse need to work in the US?',
                'a' => 'Credential evaluation, an English language test and the NCLEX examination, plus licensing in the specific state where you intend to work. Plan for months, not weeks.',
            ],
            [
                'q' => 'Is shift work standard in healthcare?',
                'a' => 'Yes. Nights, weekends and rotating shifts are normal across most of the sector, and shift premiums are a significant part of take-home pay. Check how unsocial hours are paid before accepting an offer.',
            ],
            [
                'q' => 'Should I pay a fee for a healthcare job abroad?',
                'a' => 'No. Charging a worker for sponsorship is illegal in both the UK and the US. Legitimate international recruitment is paid for by the employer.',
            ],
        ],
        'ctaText' => 'Browse Healthcare Jobs',
        'filterType' => 'category',
        'filterValue' => 'Healthcare',
        'accentText' => 'Healthcare',
        'eyebrow' => 'Healthcare &amp; Medical',
    ])
@endsection
