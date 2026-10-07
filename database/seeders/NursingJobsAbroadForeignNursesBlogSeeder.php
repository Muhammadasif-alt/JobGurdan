<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Nursing Jobs Abroad: Requirements for Foreign Nurses".
 *
 * The brief was already built on official sources, so it is kept close. What
 * changed:
 *
 * 1. The brief repeated the same NHS search link three times under different
 *    labels and sent readers off site with "Apply Now" buttons. The guide links
 *    only to our own pages; the job listings carry one official link each.
 *
 * 2. Added the figures the brief left out and GOV.UK confirms: the Health and
 *    Care Worker visa needs a salary of at least GBP 25,000 or the going rate,
 *    whichever is higher.
 *
 * 3. The NSW Health line is kept as the brief states it, with the wording
 *    NSW itself uses ("usually need at least 24 months"), and is labelled as
 *    NSW employer guidance, not a registration rule.
 *
 * The Australian streamlined pathway is described without a start date, since
 * the brief's "April 2025" was not checked against the NMBA page.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class NursingJobsAbroadForeignNursesBlogSeeder extends Seeder
{
    public const SLUG = 'nursing-jobs-abroad-requirements-for-foreign-nurses';

    private const SAUDI_APPLY_URL = 'https://www.kfshrc.edu.sa/en/home/careers';

    private const UK_APPLY_URL = 'https://www.jobs.nhs.uk/candidate/search/results?keyword=nurse';

    private const AUSTRALIA_APPLY_URL = 'https://www.health.nsw.gov.au/nursing/careers/Pages/overseas-recruitment.aspx';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Nursing Jobs Abroad: Requirements for Foreign Nurses',
                'excerpt' => 'A nursing degree alone does not let you practise abroad. What SCFHS in Saudi Arabia, the NMC for NHS jobs and the NMBA in Australia each require, which documents to prepare, and why registration, a job and a visa are three separate steps.',
                'content' => $content,
                'featured_image' => 'blogs/nursing-jobs-abroad-foreign-nurses.jpg',
                'tags' => 'nursing jobs in saudi arabia, nhs nurse jobs, nurse jobs australia, scfhs classification, nmc registration overseas nurses, nmba registration nurses, health and care worker visa nurses, foreign nurse requirements',
                'meta_title' => 'Nursing Jobs Abroad: Requirements for Foreign Nurses',
                'meta_description' => 'Nursing jobs in Saudi Arabia, NHS nurse jobs and nurse jobs in Australia: registration, documents, English, and how to apply from abroad.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        $listings = [
            [
                'advertiser' => ['Saudi Hospital Nursing Employers (Aggregated)', 'saudi-nursing-aggregated'],
                'location' => ['Saudi Arabia', 'Saudi Arabia'],
                'position' => 'Registered Nurse — Saudi Hospitals (SCFHS Classification Required)',
                'apply' => self::SAUDI_APPLY_URL,
                'description' => $this->saudiJobDescription(),
                'meta' => 'Registered nurse roles in Saudi hospitals. Foreign nurses are classified and registered by the Saudi Commission for Health Specialties first.',
                'keywords' => 'nursing jobs in saudi arabia, registered nurse saudi, scfhs classification, king faisal specialist hospital careers',
            ],
            [
                'advertiser' => ['NHS Nursing Employers (Aggregated)', 'nhs-nursing-aggregated'],
                'location' => ['United Kingdom', 'United Kingdom'],
                'position' => 'Registered Nurse — NHS Employers (NMC Registration Required)',
                'apply' => self::UK_APPLY_URL,
                'description' => $this->ukJobDescription(),
                'meta' => 'Registered nurse roles with NHS employers. Overseas nurses join the NMC register before practising, and a sponsored visa needs an approved sponsor.',
                'keywords' => 'nhs nurse jobs, nmc registration, overseas nurse uk, health and care worker visa',
            ],
            [
                'advertiser' => ['Australian Health Service Nursing Employers (Aggregated)', 'australia-nursing-aggregated'],
                'location' => ['Australia', 'Australia'],
                'position' => 'Registered Nurse — Australian Health Services (NMBA Registration Required)',
                'apply' => self::AUSTRALIA_APPLY_URL,
                'description' => $this->australiaJobDescription(),
                'meta' => 'Registered nurse roles with Australian health services. Registration with the NMBA, administered by Ahpra, comes before practice.',
                'keywords' => 'nurse jobs australia, nmba registration, ahpra overseas nurse, nsw health nursing',
            ],
        ];

        foreach ($listings as $listing) {
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location'][0]],
                ['area' => 'Nationwide', 'country' => $listing['location'][1]]
            );

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Shift-based, including nights and weekends',
                    'language' => 'English',
                    // Pay depends on the employer, grade and contract; this site
                    // does not republish job-board rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['apply'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function saudiJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Saudi hospitals recruit registered nurses from abroad across general wards, intensive care, emergency and theatre. The official careers portal of King Faisal Specialist Hospital and Research Centre is linked here as a starting point.</p>

<h3>Requirements</h3>
<ul>
    <li>Classification and registration with the Saudi Commission for Health Specialties (SCFHS) through Mumaris+</li>
    <li>Verification of overseas qualifications through an SCFHS-approved provider where required</li>
    <li>Hospitals can ask for specialty experience, English ability and clinical credentials beyond the regulator's minimum</li>
</ul>

<p><strong>Note:</strong> registration and visa rules are set by SCFHS and the Saudi authorities, not by JobGader. Check the current requirements for your category before paying for any examination.</p>
JOBHTML;
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>NHS employers advertise registered nurse vacancies on NHS Jobs. Read the person specification for each one, because some employers support international recruits through their remaining registration steps and others need you fully registered when you apply.</p>

<h3>Requirements</h3>
<ul>
    <li>Registration with the Nursing and Midwifery Council (NMC), including English language evidence and, where required, the Test of Competence (a computer-based test and an OSCE)</li>
    <li>For a Health and Care Worker visa: an eligible job, an approved sponsor, a Certificate of Sponsorship and a salary of at least GBP 25,000 or the going rate, whichever is higher</li>
    <li>NMC registration does not satisfy the immigration requirements by itself</li>
</ul>

<p><strong>Note:</strong> registration and visa rules are set by the NMC and the Home Office, not by JobGader.</p>
JOBHTML;
    }

    private function australiaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australian health services recruit internationally qualified nurses, and NSW Health publishes guidance for them. The link goes to its overseas recruitment page.</p>

<h3>Requirements</h3>
<ul>
    <li>Registration with the Nursing and Midwifery Board of Australia (NMBA), administered by Ahpra, through the pathway that matches your qualification</li>
    <li>English language, recency of practice, criminal history and professional indemnity requirements</li>
    <li>NSW Health says you will usually need at least 24 months of nursing or midwifery experience to work in a NSW public health facility and get visa sponsorship. That is NSW employer guidance, not a rule for all of Australia</li>
</ul>

<p><strong>Note:</strong> registration and visa rules are set by the NMBA and the Australian Government, not by JobGader.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Foreign nurses can work abroad when they meet the destination's qualification, professional registration, language, employer and immigration requirements. For nursing jobs in Saudi Arabia, start with the Saudi Commission for Health Specialties (SCFHS). For NHS nurse jobs, follow the registration route of the UK Nursing and Midwifery Council (NMC). For nurse jobs in Australia, check the eligibility rules of the Nursing and Midwifery Board of Australia (NMBA). A nursing degree alone does not authorise overseas practice, and registration does not automatically give you a job or a visa.</p>

<h2>Requirements at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Destination</th>
            <th style="padding:10px;text-align:left;">Professional regulator</th>
            <th style="padding:10px;text-align:left;">First practical step</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Saudi Arabia</strong></td><td style="padding:10px;">SCFHS</td><td style="padding:10px;">Check classification requirements and Mumaris+ services</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>United Kingdom</strong></td><td style="padding:10px;">NMC</td><td style="padding:10px;">Review the internationally trained applicant checklist</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australia</strong></td><td style="padding:10px;">NMBA, with Ahpra administering applications</td><td style="padding:10px;">Check the assessment route matching your qualifications</td></tr>
    </tbody>
</table>
</div>

<p>Keep licensing, recruitment and immigration separate in your planning. You may prepare the applications together, but each authority makes its own decision.</p>

<h2>Documents Foreign Nurses Should Prepare</h2>

<p>Requirements vary, but a useful document folder includes:</p>

<ul>
    <li>Passport, and evidence explaining any name differences.</li>
    <li>Nursing qualification, academic transcript and clinical training records.</li>
    <li>Professional registration evidence and good standing documentation where requested.</li>
    <li>Employment letters showing dates, department, responsibilities and working hours.</li>
    <li>Language evidence accepted by the destination regulator.</li>
    <li>An updated CV, references and relevant clinical training certificates.</li>
    <li>Health, character and identity evidence required for your application.</li>
</ul>

<p>Check translation and certification instructions before paying for documents. Regulators may require information directly from universities, employers or licensing authorities.</p>

<h2>Nursing Jobs in Saudi Arabia</h2>

<p>SCFHS assesses qualifications and professional standing through its classification and registration processes. The published requirements cover qualification documents, verification of overseas qualifications, professional registration evidence and experience documentation. Follow the instructions for your category through Mumaris+. Separate services publish their own conditions, so confirm the rule for your qualification and application type rather than assuming every foreign nurse follows the same route.</p>

<p>Overseas documents may need verification through an SCFHS-approved provider. Do not book an examination only because a recruiter says every nurse takes the same one. Hospitals can also ask for specialty experience, English ability or clinical credentials beyond the regulator's minimum, and intensive care, emergency, theatre and general ward roles may be selected differently. King Faisal Specialist Hospital and Research Centre runs an official careers portal; read each vacancy's requirements and follow its recruitment process. The wider picture is in <a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a>.</p>

<h2>NHS Nurse Jobs: Requirements for Overseas Applicants</h2>

<p>Internationally trained nurses must meet NMC requirements before practising as registered nurses in the UK. The process covers qualification assessment, English language evidence, health and character, identity checks and other registration conditions. Where required, the Test of Competence has two parts: a computer-based test and an Objective Structured Clinical Examination (OSCE). Your qualification can change the route, so follow your own NMC checklist.</p>

<p>The NMC requires evidence that you can communicate safely and effectively in English. IELTS or OET may be suitable, but the accepted routes have detailed conditions, so check them with the NMC. An employer's interview is not a replacement for the regulator's requirement.</p>

<p>Read the person specification of each NHS vacancy. Some employers support international recruits through their remaining registration steps, while others need you fully registered when you apply. Describe your registration status accurately.</p>

<p>If you need a Health and Care Worker visa, you need an eligible job, an approved sponsor, a Certificate of Sponsorship and a salary of at least GBP 25,000 or the going rate for the job, whichever is higher. NMC registration alone does not meet the immigration requirements. For support roles rather than registered nursing, see <a href="/blog/nursing-assistant-jobs-in-the-uk">Nursing Assistant Jobs in the UK</a> and <a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a>.</p>

<h2>Nurse Jobs in Australia: Registration and Recruitment</h2>

<p>To practise as a nurse in Australia you need the relevant NMBA registration. International applicants must meet the qualification criteria and registration standards, so follow the current pathway guidance, including self-assessment where directed. Registration, employment and immigration are distinct processes.</p>

<p>The NMBA has a streamlined pathway for eligible internationally qualified registered nurses. Eligibility depends on prescribed criteria, including relevant registration and practice history in approved comparable jurisdictions, and it does not apply automatically to every overseas nurse. Other applicants may need further assessment. Check English language, recency of practice, criminal history and professional indemnity requirements, and do not assume that registration elsewhere guarantees Australian registration.</p>

<p>NSW Health publishes guidance for internationally qualified nurses. It says you will usually need at least 24 months of nursing or midwifery experience to work in a NSW public health facility and get visa sponsorship. That is NSW employer guidance, not a universal Australian registration rule.</p>

<h2>How Do I Improve My Nursing Application?</h2>

<p>Tailor your CV to the department. Describe patient groups, clinical responsibilities, equipment, teamwork and relevant training, using accurate examples and no confidential patient information. Match your supporting statement to the vacancy's essential criteria.</p>

<p>Ask about supervision, orientation, registration support, examination costs, relocation, accommodation and any repayment clauses. Compare written salary, hours, allowances, deductions and living costs before accepting. Neither high advertised pay nor sponsorship wording guarantees suitability.</p>

<p>Keep a simple tracker with the employer name, vacancy reference, registration stage, submission date and response. Prepare interview examples about recognising deterioration, escalation, infection prevention, medication safety and communication. If documents are delayed, contact the issuing institution early. Avoid resigning or making irreversible travel arrangements while essential approvals are outstanding, and ask the employer to confirm your start date against the registration and immigration steps still required.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/healthcare" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Healthcare Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can newly qualified nurses work abroad?</h3>
<p>Possibly, but regulator eligibility and employer experience requirements differ. Graduate routes and experienced international recruitment are separate options.</p>

<h3>Is IELTS compulsory for every destination?</h3>
<p>No single language rule applies everywhere. Check the regulator's accepted evidence and the employer's requirements before booking a test.</p>

<h3>Does registration guarantee sponsorship?</h3>
<p>No. Registration establishes professional eligibility. Employer selection and immigration approval remain separate decisions.</p>

<h3>What salary does the UK Health and Care Worker visa need?</h3>
<p>At least GBP 25,000 or the going rate for the job, whichever is higher, plus an approved sponsor and a Certificate of Sponsorship.</p>

<h3>Do I need 24 months of experience to work in Australia?</h3>
<p>Not as a registration rule. NSW Health says you will usually need at least that for its public facilities and visa sponsorship, so check the employer you are applying to.</p>

<h3>What is the OSCE?</h3>
<p>The Objective Structured Clinical Examination, the practical part of the NMC Test of Competence, taken after the computer-based test where your route requires it.</p>

<h3>Which destination should I choose?</h3>
<p>Compare qualification eligibility, experience, language evidence, assessment costs, employer support and living expenses before deciding.</p>

<h3>Should I pay a recruiter for registration?</h3>
<p>No. Apply to the regulator yourself and check anything a recruiter claims against the regulator's own page.</p>

<h2>People Also Search For</h2>

<h3>Nursing jobs in Saudi Arabia</h3>
<p>Classification through SCFHS comes first, then hospital recruitment.</p>

<h3>NHS nurse jobs for overseas nurses</h3>
<p>NMC registration, English evidence and, where required, the Test of Competence.</p>

<h3>Nurse jobs in Australia</h3>
<p>NMBA registration through the pathway that matches your qualification.</p>

<h3>SCFHS classification</h3>
<p>The Saudi process that assesses your qualifications and professional standing.</p>

<h3>NMC OSCE</h3>
<p>The practical examination in the Test of Competence.</p>

<h3>Health and Care Worker visa for nurses</h3>
<p>Needs an eligible job, an approved sponsor and a Certificate of Sponsorship.</p>

<h3>Ahpra overseas nurse registration</h3>
<p>Applications are administered by Ahpra for the NMBA.</p>

<h3>Foreign nurse requirements</h3>
<p>Qualification, registration, English, an employer and immigration permission, each separately.</p>

<h2>More Job Guides</h2>

<p>Related healthcare guides:</p>

<ul>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; the Saudi route in detail.</li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the American route.</li>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; licensing and visas.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; roles beyond registered nursing.</li>
    <li><a href="/blog/nursing-assistant-jobs-in-the-uk">Nursing Assistant Jobs in the UK</a> &mdash; the support-worker route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. Information is drawn from SCFHS, the NMC, GOV.UK, the NMBA and NSW Health, checked on 6 October 2026. Registration and visa rules change, so confirm them with the official authority before applying or paying for any examination.</p>
HTML;
    }
}
