@extends('user.layouts.master')
@section('title', 'Customer Service Jobs | USA Jobs')
@section('meta_description', 'Search customer service jobs across the USA. Find roles in call centers, support teams, virtual support, and retail customer service.')
@section('og_title', 'Customer Service Jobs | USA Jobs')
@section('og_description', 'Search customer service jobs across the USA. Find roles in call centers, support teams, virtual support, and retail customer service.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Customer Service Jobs',
        'intro' => [
            'Customer service roles are essential for any business that interacts with clients and customers. These roles include call center agents, support specialists, virtual assistants, and retail team members.',
            'Search for customer service job listings and apply for positions that fit your communication strengths and preferred work environment.',
        ],
        'sections' => [
            [
                'title' => 'Customer Service Role Types',
                'paragraphs' => [
                    'Common roles include customer service representative, call center agent, chat support specialist, and help desk analyst. Many positions offer remote work flexibility.',
                    'Strong communication, problem-solving, and patience are key skills for success in customer-facing roles.',
                ],
            ],
            [
                'title' => 'How to Land a Customer Service Role',
                'paragraphs' => [
                    'Highlight your customer interaction experience, ability to manage multiple requests, and familiarity with support tools like Zendesk or Salesforce.',
                    'Include examples of using empathy and de-escalation techniques to resolve issues and improve customer satisfaction.',
                ],
            ],
        ],
        'jobRoles' => [
            'Customer Service Representative',
            'Call Center Agent',
            'Chat Support Specialist',
            'Help Desk Technician',
            'Retail Customer Associate',
            'Virtual Assistant',
        ],
        'ctaText' => 'Browse Customer Service Jobs',
        'filterType' => 'keyword',
        'filterValue' => ['customer service', 'customer support', 'call center', 'help desk'],
        'accentText' => 'Customer Service',
        'eyebrow' => 'Support &amp; Service',
    ])
@endsection
