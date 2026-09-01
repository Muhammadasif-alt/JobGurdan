@extends('user.layouts.master')
@section('title', 'Healthcare Jobs | USA Jobs')
@section('meta_description', 'Find healthcare jobs nationwide, including nursing, medical assistants, therapists, and administrative roles in hospitals, clinics, and telehealth.')
@section('og_title', 'Healthcare Jobs | USA Jobs')
@section('og_description', 'Find healthcare jobs nationwide, including nursing, medical assistants, therapists, and administrative roles in hospitals, clinics, and telehealth.')
@section('canonical', url()->current())

@section('content')
    @include('pages._seo-landing', [
        'headline' => 'Healthcare Jobs',
        'intro' => [
            'Healthcare is one of the most stable and rewarding industries, with ongoing demand for skilled workers across the country. From hospitals to home health care, there are many pathways to start or grow your career.',
            'Browse listings for nurses, medical assistants, therapists, administrators, and telehealth specialists. Apply to roles that match your experience and career goals.',
        ],
        'sections' => [
            [
                'title' => 'Types of Healthcare Jobs',
                'paragraphs' => [
                    'Entry-level roles often include medical assisting, patient care, and administrative support. Specialized roles include registered nurses, licensed practical nurses, and respiratory therapists.',
                    'Many providers offer tuition assistance or certification programs for new employees who want to advance.',
                ],
            ],
            [
                'title' => 'What Employers Look For',
                'paragraphs' => [
                    'Healthcare employers value certifications, clinical experience, and strong communication skills. Highlight any patient care experience and your ability to work in fast-paced environments.',
                    'A clear background check and willingness to follow safety protocols are often required.',
                ],
            ],
        ],
        'jobRoles' => [
            'Registered Nurse',
            'Medical Assistant',
            'Certified Nursing Assistant (CNA)',
            'Physical Therapist',
            'Healthcare Administrator',
            'Medical Receptionist',
        ],
        'ctaText' => 'Browse Healthcare Jobs',
        'filterType' => 'category',
        'filterValue' => 'Healthcare',
        'accentText' => 'Healthcare',
        'eyebrow' => 'Healthcare &amp; Medical',
    ])
@endsection
