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
        'Petrol Station Assistant - The Kay Group Irlam Gateway Vacancy Page (Manchester)',
        'Cleaner - DMAIDS Careers Page (Dubai)',
        'Driver - Dubai Taxi Company Careers Page (Dubai)',
        'Truck Driver - FMCSA Training Provider Registry for CDL Training (Dallas, Texas)',
        'Petrol Pump Attendant - PSO Careers and Dealer Hiring Information (Lahore)',
        'Sweeper - Sindh Solid Waste Management Board Careers (Karachi)',
        'Janitor - City of Philadelphia Jobs Board (Philadelphia, Pennsylvania)',
        'Delivery Driver - CalJOBS State Job Board (Los Angeles, California)',
        'Gas Station Cashier - WorkInTexas State Job Board (Houston, Texas)',
        'Commis Chef - France Travail Public Job Offers (Paris, France)',
        'Waiter - ILO Guide to Qatar Minimum Wage and Job Changes (Doha)',
        'Hotel Housekeeping - EmployNV State Job Board (Las Vegas, Nevada)',
        'Hotel Jobs for Foreigners - UAE Government Work Permit Information (Dubai)',
        'Hotel Receptionist - Employ Florida State Job Board (Orlando, Florida)',
        'Pharmacy Technician - OhioMeansJobs and Board of Pharmacy Registration (Columbus, Ohio)',
        'Home Health Aide - NY Department of Health Approved Training (Brooklyn, New York)',
        'Lab Technician - Punjab Job Portal and PMF Qualifications (Lahore, Punjab)',
        'Medical Assistant - Arizona Job Connection and Training Rules (Phoenix, Arizona)',
        'Caregiver / Home Support Worker - Job Bank Wages and LMIA Route (Calgary, Alberta)',
        'Registered Nurse - College of Nurses of Ontario International Applicant Route (Toronto, Ontario)',
        'Certified Nursing Assistant - Georgia Nurse Aide Registry and Training (Atlanta, Georgia)',
        'Registered Nurse - Harris Health Nursing Programs (Houston, Texas)',
        'Registered Nurse - NHS Jobs Nursing Vacancies (London, England)',
        'Nurse - SCFHS Classification Through Mumaris Plus (Riyadh, Saudi Arabia)',
        'Prison Officer - HMPPS Current Vacancies (London, England)',
        'Firefighter/EMT - Chicago Fire Department Entrance Examination (Chicago, Illinois)',
        'Security Guard - SIRA Security Cadre Card Requirements (Dubai)',
        'Security Officer - Texas DPS Private Security Licensing (Houston, Texas)',
        'Police Constable - Punjab Police Recruitment Notices (Rahim Yar Khan and Rajanpur)',
        'Police Constable - UPPRPB Recruitment Notices (Uttar Pradesh)',
        'Overseas Worker - BEOE Foreign Jobs and Promoter Check (Pakistan)',
        'Full-Time Worker - GOV.UK Find a Job Service (United Kingdom)',
        'Qualified Professional - Make it in Germany Job Listings (Germany)',
        'Office Assistant - myjobscotland Council Vacancies (Glasgow, Scotland)',
        'Back Office Executive - National Career Service Portal (Delhi)',
        'Receptionist - UAE Government Jobs Information Page (Dubai)',
        'Call Centre Agent - National Job Portal (Karachi, Sindh)',
        'BPO Associate - National Career Service Portal (Pune, Maharashtra)',
        'Data Entry Clerk - WorkInTexas Job Search (Houston, Texas)',
        'Remote Data Entry - PA CareerLink Job Search (Pennsylvania)',
        'Social Media Manager - Workforce Australia Job Search (Melbourne, Victoria)',
        'SEO Executive - National Career Service Portal (Mumbai, Maharashtra)',
        'Content Writer - DigiSkills.pk Free Training (Lahore, Punjab)',
        'Transcriptionist - OhioMeansJobs Job Seeker Page (Ohio)',
        'Remote Entry Level Support - Michigan Works! American Job Centers (Michigan)',
        'Remote Beginner - DigiSkills.pk Free Training (Islamabad)',
        'Copywriter - National Careers Service Job Profile (Birmingham, England)',
        'Email Marketing - Job Bank Wage Report (Vancouver, British Columbia)',
        'Digital Skills Learner - DigiSkills.pk Free Online Courses (Karachi, Sindh)',
        'SEO Job Seeker - NYC Workforce1 Career Centers (New York City)',
        'Remote SEO Learner - O*NET Search Marketing Strategists (Denver, Colorado)',
        'PPC Specialist Learner - Google Ads Certification Help (Atlanta, Georgia)',
        'Remote Customer Support - Employ Florida Marketplace (Florida)',
        'Bug Bounty Learner - HackerOne Getting Started Guide (Karachi, Sindh)',
        'Virtual Assistant - PhilJobNet (Manila, Philippines)',
        'Online Tutor - New Jersey Department of Labor Career Services (New Jersey)',
        'Freelance Writer - Ajira Digital Programme (Nairobi, Kenya)',
        'Software Developer — WorkSource Washington Job Search (Seattle)',
        'Front-End Developer — Find an Apprenticeship (Manchester, England)',
        'Data Analyst — Public Appointments Service (Dublin, Ireland)',
        'Web Developer — WorkInTexas Job Search (Texas Workforce Commission)',
        'Remote WordPress Developer — Government of Canada Job Bank (Toronto and Ontario)',
        'Junior Web Developer — IllinoisJobLink (Chicago, Illinois)',
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
