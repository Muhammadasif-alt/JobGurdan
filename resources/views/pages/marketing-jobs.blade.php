@extends('user.layouts.master')
@section('title', 'Marketing Jobs — SEO, Content, Paid and Social | JobGader')
@section('meta_description', 'Marketing jobs across '.$coverage->shortList().': SEO, content, paid media and social roles, what they pay, and what a hiring portfolio needs.')
@section('og_title', 'Marketing Jobs — SEO, Content, Paid and Social | JobGader')
@section('og_description', 'Marketing jobs across '.$coverage->shortList().': SEO, content, paid media and social roles, what they pay, and what a hiring portfolio needs.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Marketing Jobs',
        'intro' => [
            'Marketing is one of the few fields where a portfolio outranks a degree. Employers want to see campaigns you ran, numbers that moved and what you did to move them — which makes it unusually open to self-taught candidates who can prove results.',
            'This page pulls together marketing, SEO and digital openings from our listings, with realistic pay bands and a clear account of what a hiring portfolio needs to contain.',
        ],
        'sections' => [
            [
                'title' => 'What Marketing Roles Pay',
                'paragraphs' => [
                    'In the US, marketing coordinators are commonly advertised around $50,000 to $65,000, SEO and paid media specialists $60,000 to $90,000, and marketing managers well above that depending on team size and budget responsibility.',
                    'In the UK, marketing executives commonly sit at £26,000 to £35,000, specialists £35,000 to £50,000, and managers above. In Pakistan, digital marketing roles commonly run PKR 60,000 to 200,000 a month, with the upper end concentrated in agencies serving overseas clients.',
                ],
            ],
            [
                'title' => 'The Disciplines Are Separating',
                'paragraphs' => [
                    'A generalist marketing role at a small company still exists, but most hiring now targets a specific discipline. SEO, paid media, content, lifecycle and email, social, and marketing operations each have their own tool stacks and their own interview questions.',
                    'SEO roles expect fluency across technical, on-page and off-page work, and Search Console, Analytics and one of Ahrefs, Semrush or Moz. Paid roles expect Google Ads and Meta Ads Manager plus a defensible view on attribution. Say which discipline you are, then prove it.',
                ],
            ],
            [
                'title' => 'What a Hiring Portfolio Contains',
                'paragraphs' => [
                    'Three or four case studies beat a long list of employers. Each one needs the starting position, what you changed, and the measured outcome, with the timeframe stated. Organic traffic up by a percentage over a stated period, cost per acquisition down from one figure to another, conversion rate improved on a named page.',
                    'Include the campaigns that did not work and what you learned, at least in interview. Marketing hiring managers have all run failed campaigns and are wary of candidates who claim they never have.',
                ],
            ],
            [
                'title' => 'AI Has Changed the Baseline',
                'paragraphs' => [
                    'Content and design tooling has moved fast, and job ads increasingly assume working knowledge of AI writing and image tools alongside the traditional stack. Familiarity with them is now closer to a baseline expectation than a differentiator.',
                    'What has not changed is that employers hire for judgement — knowing which keyword is worth targeting, which channel deserves the budget, and when a campaign should be stopped. Lead with the decisions you made, not the tools you used to execute them.',
                ],
            ],
        ],
        'jobRoles' => [
            'Marketing Executive',
            'SEO Specialist',
            'Content Writer',
            'Social Media Manager',
            'PPC / Paid Media Specialist',
            'Email Marketing Specialist',
            'Digital Marketing Manager',
            'Brand Manager',
        ],
        'faqs' => [
            [
                'q' => 'Do I need a marketing degree?',
                'a' => 'Rarely, in digital marketing specifically. A portfolio with measured results is the primary filter. Degrees carry more weight for brand and strategy roles at large consumer companies.',
            ],
            [
                'q' => 'Which marketing discipline pays best?',
                'a' => 'Paid media and marketing operations tend to top the ranges because both sit close to revenue and are directly measurable. SEO is comparable at senior level, particularly technical SEO.',
            ],
            [
                'q' => 'What should be in my portfolio if I have no clients yet?',
                'a' => 'Your own project. A site you ranked, a social account you grew, or a campaign you ran for a local business, with the numbers stated honestly. Employers accept small results with real evidence over large claims with none.',
            ],
            [
                'q' => 'Are remote marketing jobs common?',
                'a' => 'Yes, particularly in SEO, content and paid media where the work and its results are both measurable remotely. Roles serving another timezone may set overlapping hours.',
            ],
            [
                'q' => 'Is applying free?',
                'a' => 'Yes, with no account needed. Listings link straight to the employer or original posting.',
            ],
        ],
        'ctaText' => 'Browse Marketing Jobs',
        'filterType' => 'category',
        'filterValue' => 'Marketing',
        'accentText' => 'Marketing',
        'eyebrow' => 'Marketing &amp; Brand',
    ])
@endsection
