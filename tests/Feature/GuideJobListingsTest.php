<?php

use App\Models\Job;
use Database\Seeders\GuideJobListingsSeeder;

it('creates the truck driver, caregiver and UK warehouse job listings', function () {
    $this->seed(GuideJobListingsSeeder::class);

    $jobs = Job::whereIn('position', [
        'Truck Driver — US Employers (CDL Roles)',
        'Caregiver — US Employers (EB-3 Sponsorship Route)',
        'Warehouse Operative — UK Employers',
    ])->get();

    expect($jobs)->toHaveCount(3);

    foreach ($jobs as $job) {
        expect($job->application_url)->toStartWith('https://')
            ->and($job->salary_minimum)->toBeNull()
            ->and($job->description)->not->toContain('Apply Now')
            ->and(strlen($job->meta_description))->toBeLessThanOrEqual(160);
    }
});

it('gives each salary and from-Pakistan guide its own job listing', function () {
    $this->seed(GuideJobListingsSeeder::class);

    $positions = [
        'Hotel Housekeeper — US Hotels (Wage Check on H-2B Job Orders)',
        'Hotel Housekeeper — US Hotels (Applying From Pakistan)',
        'Warehouse Worker — US Employers (Wage Check on H-2B Job Orders)',
        'Warehouse Worker — US Employers (Applying From Pakistan)',
        'Landscaper — US Employers (Wage Check on H-2B Job Orders)',
        'Warehouse Operative — UK Employers (Pay Check)',
        'Landscaper — US Employers (Applying From Pakistan)',
        'Farm Worker — US Farms (Wage Check on H-2A Job Orders)',
        'Farm Worker — US Farms (Applying From Pakistan)',
        'Skilled Worker — Canadian Employers (Check Who Can Apply)',
        'Skilled Trades Worker — Canadian Employers (Welders, Electricians, Truck Drivers)',
        'Entry-Level Worker — Canada (Working Holiday Route, No Experience)',
        'Overseas Job Offer Check — Verify a UK Employer Before You Pay',
        'Truck Driver — Canadian Employers (Class 1 Licence Needed)',
        'Skilled Worker — UK Employers (Check the Sponsor Route)',
        'Work Visa Applicant — Apply Without an Agent (UK Skilled Worker Route)',
        'Skilled Trades Worker — US Employers (Electrician, Plumber, HVAC, Welder)',
        'Overseas Worker — Documents for a UK Skilled Worker Application',
        'Overseas Worker — Canadian Employers Recruiting Internationally',
        'Job Seeker Abroad — European Vacancies (EURES)',
        'Overseas Worker — Apply Directly to Canadian Employers (No Recruiter)',
        'Worker Comparing Offers — European Vacancies With Stated Terms (EURES)',
        'Overseas Worker — Check UK Skilled Worker Costs Before You Budget',
        'Skilled Worker — Germany Professions in Demand (Make it in Germany)',
        'Remote Worker in Pakistan — Check US Employer Eligibility (CareerOneStop)',
        'Ironworker Apprentice — Ironworkers Apprenticeship Information (Iron Workers International)',
        'Elevator Constructor Apprentice — NEIEP Recruitment Opportunities',
        'Sheet Metal Apprentice — International Training Institute Apprenticeship Information',
        'Plumber and Pipefitter Apprentice — United Association Apprenticeship',
        'Boilermaker Apprentice — Boilermakers Recruiting Information',
        'Construction Laborer Apprentice — LiUNA Training Centers',
        'Operating Engineer Apprentice — IUOE Local Apprenticeship Information',
        'Apprenticeship Applicant — Apprenticeship.gov Program Information',
        'Maintenance Technician Apprentice — Apprenticeship.gov Program Finder',
        'Reapplying Apprentice — Apprenticeship.gov Programme Information',
        'Apprenticeship Applicant — Check Selection Criteria (Apprenticeship.gov)',
        'Apprenticeship Test Candidate — OSHA Training Information (OSHA)',
        'Apprenticeship Applicant — Apprenticeship.gov Job Finder (Check Entry Requirements)',
        'Elevator Constructor Applicant — NEIEP Ranked Hiring List Information',
        'Construction Laborer Applicant — Laborers Training School Waiting List Information',
        'Ironworker Apprentice — New York State Apprenticeship Announcements',
        'Elevator Mechanic Apprentice — NEIEP Recruitment (Chicago, Philadelphia, Boston)',
        'Plumber Apprentice — Seattle Area Pipe Trades Application Requirements',
        'Sheet Metal Apprentice — Local 12 Pittsburgh Apprenticeship Application',
        'Trades Apprentice — Upstate New York Apprenticeship Announcements (Buffalo, Rochester, Syracuse)',
        'Construction Laborer Apprentice — Local 66 Long Island Recruitment Notice',
        'Apprentice — California Apprenticeship Program Search (Southern California and Inland Empire)',
        'Job Seeker — City of Chicago Careers Portal (Public Sector Roles)',
        'Job Seeker — City of Houston Job Board (Public Sector Roles)',
        'Part-Time Job Seeker — University of Washington Jobs (Seattle)',
        'Healthcare Assistant — NHS Jobs UK (English Evidence Required)',
        'Seasonal Worker — US Job Orders (Plan Your Remittances)',
        'Work Permit Holder — Canada Job Bank (Employer-Specific vs Open Permit)',
        'Care Worker — UK Licensed Sponsor Register Check (GOV.UK)',
        'Skilled Worker — UK Overseas Application and Decision Time (GOV.UK)',
        'Skilled Worker in Canada — Spouse Open Work Permit Eligibility (IRCC)',
        'Worker Changing Employer — UK Skilled Worker Update Your Visa (GOV.UK)',
        'Overseas Applicant — Job Bank Foreign Candidates Search (Government of Canada)',
        'Overseas Offer Check — UK Companies House Company Search',
        'Skills Learner — National Careers Service Courses and Advice (UK)',
        'Interview Candidate — National Careers Service Interview Advice (UK)',
        'Qualified Professional — Germany EU Blue Card Route (Make it in Germany)',
        'Skilled Tradesperson — UAE Employment and Work Permit Information (UAE Government)',
        'Overseas Job Seeker — Check a Licensed Promoter (BEOE Pakistan)',
        'Worker — UK Employment Status and Rights (GOV.UK)',
        'Non-EU Worker — EURES Jobseekers Portal (European Union)',
        'Expat Worker — UAE Golden Visa Eligibility (UAE Government)',
        'Relocating Worker — EU Immigration Portal Route Check (European Commission)',
        'Farm Worker — US H-2A Job Orders (Housing and Meals Terms)',
        'Electrician — US Employers (CareerOneStop Job Search)',
    ];

    foreach ($positions as $position) {
        $job = Job::where('position', $position)->firstOrFail();

        expect($job->application_url)->toStartWith('https://')
            ->and($job->salary_minimum)->toBeNull()
            ->and($job->description)->not->toContain('Apply Now')
            ->and(strlen($job->meta_description))->toBeLessThanOrEqual(160);
    }
});

it('does not duplicate the listings when re-seeded', function () {
    $this->seed(GuideJobListingsSeeder::class);
    $this->seed(GuideJobListingsSeeder::class);

    expect(Job::where('position', 'Warehouse Operative — UK Employers')->count())->toBe(1);
});
