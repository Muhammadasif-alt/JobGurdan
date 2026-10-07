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
 * "Medical Lab Technician and Pharmacy Assistant Jobs".
 *
 * The brief was already cautious and cited official pages, so it is kept
 * close. What changed:
 *
 * 1. Apply Now buttons were repeated for the same Mediclinic, NMC Healthcare,
 *    NHS and Boots pages, some of them an Oracle candidate-portal address and a
 *    Boots search address that only a vacancy-specific session can open. The
 *    guide links only to our own pages; the listings carry one official link
 *    each (Mediclinic careers, NHS Jobs).
 *
 * 2. The brief's "NHS primary care careers" source is a regional site. The
 *    guide cites the National Careers Service page for entry routes instead.
 *
 * 3. The brief said a pharmacy assistant "should not be treated as a
 *    pharmaceutical technician simply to claim eligibility". That is kept as
 *    plain advice to check the job against the GOV.UK eligible list; the guide
 *    does not say whether pharmacy assistants qualify, because the list
 *    changes.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class LabTechnicianPharmacyAssistantJobsBlogSeeder extends Seeder
{
    public const SLUG = 'medical-lab-technician-and-pharmacy-assistant-jobs';

    private const UAE_APPLY_URL = 'https://careers.mediclinic.com/MiddleEast/?locale=en_GB';

    private const UK_APPLY_URL = 'https://www.jobs.nhs.uk/candidate/search/results?keyword=pharmacy+assistant';

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
                'title' => 'Medical Lab Technician and Pharmacy Assistant Jobs',
                'excerpt' => 'A UAE lab technician needs a recognised qualification and a health-authority licence; a UK pharmacy assistant needs employer training and the right to work. Two different routes, what each asks for, and why neither guarantees a visa.',
                'content' => $content,
                'featured_image' => 'blogs/lab-technician-pharmacy-assistant-jobs.jpg',
                'tags' => 'lab technician jobs uae, medical laboratory technician dubai, dha licence lab technician, pharmacy assistant jobs uk, trainee pharmacy assistant, dispensary assistant jobs, healthcare jobs for foreigners, mediclinic careers',
                'meta_title' => 'Lab Technician Jobs UAE and Pharmacy Assistant Jobs UK',
                'meta_description' => 'Lab technician jobs in the UAE and pharmacy assistant jobs in the UK: qualifications, DHA licensing, training and why a visa is never guaranteed.',
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
                'advertiser' => ['UAE Laboratory Employers (Aggregated)', 'uae-laboratory-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Medical Laboratory Technician — UAE Hospitals and Clinics (Health Authority Licence Required)',
                'apply' => self::UAE_APPLY_URL,
                'description' => $this->uaeJobDescription(),
                'meta' => 'Medical laboratory technician roles with UAE hospitals and clinics. The health authority for the facility licenses you before you can practise.',
                'keywords' => 'lab technician jobs uae, medical laboratory technician dubai, dha licence, mediclinic careers',
            ],
            [
                'advertiser' => ['UK Pharmacy Employers (Aggregated)', 'uk-pharmacy-aggregated'],
                'location' => ['United Kingdom', 'United Kingdom'],
                'position' => 'Pharmacy Assistant — UK Pharmacies and NHS Employers (Right to Work Required)',
                'apply' => self::UK_APPLY_URL,
                'description' => $this->ukJobDescription(),
                'meta' => 'Pharmacy assistant and dispensary roles in the UK. Employers set their own entry criteria, and you need the right to work in the UK.',
                'keywords' => 'pharmacy assistant jobs uk, dispensary assistant, trainee pharmacy assistant, nhs pharmacy jobs',
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
                    'work_hours' => 'Shift-based, including some weekends',
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

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hospitals, clinics and diagnostic laboratories in the UAE recruit medical laboratory technicians for specimen handling, testing support, quality procedures and records within their authorised duties. The Mediclinic Middle East careers portal is linked as a starting point.</p>

<h3>Requirements</h3>
<ul>
    <li>A recognised qualification matching the exact laboratory title, checked against the Professional Qualification Requirements (PQR)</li>
    <li>A licence from the health authority that covers the facility: the Dubai Health Authority in Dubai, the Department of Health in Abu Dhabi, or the Ministry of Health and Prevention elsewhere</li>
    <li>A work permit and residence visa arranged by the employer. You cannot work on a visit or tourist visa</li>
</ul>

<p><strong>Note:</strong> licensing and work permit rules are set by the UAE health and labour authorities, not by JobGader. A portal may have no suitable vacancy when you visit.</p>
JOBHTML;
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Pharmacies, hospitals and primary care employers across the UK hire pharmacy assistants for stock, ordering, administration and customer support. NHS Jobs lists vacancies from participating employers.</p>

<h3>Requirements</h3>
<ul>
    <li>No single entry rule: employers commonly look for literacy, numeracy, IT and customer service skills, and some provide training or an apprenticeship</li>
    <li>The right to work in the UK. Do not assume a pharmacy assistant vacancy comes with sponsorship</li>
    <li>A pharmacy assistant is not a registered pharmacy technician or pharmacist, and an overseas pharmacy qualification does not give UK registration</li>
</ul>

<p><strong>Note:</strong> immigration and registration rules are set by the Home Office and the pharmacy regulator, not by JobGader. Check the person specification of each vacancy.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>You can apply for medical laboratory technician jobs in the UAE when your qualifications and professional eligibility match the relevant health authority's requirements. For pharmacy assistant jobs in the UK, employers commonly look for literacy, numeracy, communication and either suitable training or a willingness to train. They are different routes: clinical laboratory work can need a professional licence, while a pharmacy assistant supports a pharmacy team. Neither a vacancy nor a training certificate guarantees overseas employment or visa sponsorship.</p>

<h2>Which Role Matches Your Background?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">Typical work</th>
            <th style="padding:10px;text-align:left;">Main eligibility check</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UAE medical laboratory technician</strong></td><td style="padding:10px;">Specimen handling, testing support, quality procedures and records within authorised duties</td><td style="padding:10px;">Recognised qualification and the relevant licensing pathway</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UK pharmacy assistant</strong></td><td style="padding:10px;">Stock handling, administration, customer support and trained pharmacy tasks</td><td style="padding:10px;">Employer criteria, task-appropriate training and the right to work</td></tr>
    </tbody>
</table>
</div>

<p>Read the complete job description. A laboratory technician, technologist and laboratory assistant can have different requirements, and a pharmacy assistant, pharmacy technician and pharmacist are separate roles.</p>

<h2>Lab Technician Jobs in the UAE: Qualifications and Licensing</h2>

<p>Start with the Unified Healthcare Professional Qualification Requirements, known as PQR. The framework covers educational standards, experience and licensing eligibility. Match your qualification to the specific laboratory title instead of assuming any science degree qualifies you for clinical practice. Qualification level, programme content, clinical training, experience and professional history can all affect eligibility, and a clinical laboratory role is not the same as an industrial or school laboratory job.</p>

<p>Then find the regulator. For a Dubai Health Authority facility, use the DHA's Sheryan pathway. For Abu Dhabi, check the Department of Health's requirements, and other facilities can fall under the Ministry of Health and Prevention. Confirm the regulator with the employer, because the facility's jurisdiction decides it.</p>

<p>The DHA pathway includes self-assessment, primary source verification, assessment where required, registration and activation of the professional licence. The hiring facility completes the activation stage, and an eligibility assessment alone does not authorise you to practise.</p>

<p>You may need a passport, photograph, educational documents, employment certificates, professional registration evidence and a good standing certificate. Provide accurate dates and consistent names, ask previous employers to describe your laboratory department and responsibilities, and check whether translations or direct verification from the issuing institutions are required.</p>

<p>For the work itself, you cannot work on a visit or tourist visa, and the employer arranges the work permit. Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs, directly or indirectly. Licensing and immigration are separate requirements, so confirm both before you travel. Browse more roles in the <a href="/categories/healthcare">healthcare category</a>.</p>

<p>Searches for lab technician jobs in the UAE also return unrelated technical work. Use medical laboratory keywords and read each advert before applying, and do not assume an opening exists just because a hospital runs laboratories.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/lab-technician-pharmacy-assistant-jobs-inline.jpg"
         alt="A smiling lab technician in a white coat holding a tablet beside a colleague at a microscope, with pharmacy and laboratory scenes, a plane and a passport around her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Pharmacy Assistant Jobs in the UK: Entry and Training</h2>

<p>Pharmacy assistants support stock management, ordering, administration, customer communication and other duties that suit their training. They work within the pharmacy team under the relevant supervision, and refer clinical questions beyond their competence to the pharmacist. The role does not qualify you to practise independently as a pharmacist, and pharmacy technician registration is a separate route. An overseas pharmacy qualification does not automatically give you UK eligibility for either regulated profession.</p>

<p>There is no single entry requirement. The National Careers Service describes direct applications, workplace training and pharmacy services assistant apprenticeships, and some routes involve Level 2 learning. Individual employers can ask for specific qualifications or earlier pharmacy work, and apprenticeship eligibility and funding conditions need separate checks, especially if you are arriving from overseas.</p>

<p>Search for "trainee pharmacy assistant", "pharmacy support worker" or "dispensary assistant", and check what training is provided, which tasks you would perform and whether earlier certificates are accepted. NHS Jobs lists vacancies from participating employers, and high street pharmacy chains advertise on their own careers sites. Compare the person specification, location, hours, closing date and application instructions. Related UK guides: <a href="/blog/hospital-support-worker-jobs-in-the-uk">Hospital Support Worker Jobs in the UK</a> and <a href="/blog/nursing-assistant-jobs-in-the-uk">Nursing Assistant Jobs in the UK</a>.</p>

<h2>Can Overseas Applicants Get Sponsorship?</h2>

<p>Do not assume that a UK pharmacy assistant job qualifies for a sponsored visa. Eligibility depends on the actual occupation code, the duties, employer sponsorship, the salary and the current immigration rules, and you should check the job against the GOV.UK Health and Care Worker visa guidance. A pharmacy assistant should not be described as a pharmacy technician just to claim eligibility. Ask the employer whether sponsorship is available for the specific vacancy; generic sponsorship wording or an old advert is not enough.</p>

<h2>How Do I Build a Strong Application?</h2>

<p>For laboratory work, describe the specimen handling, equipment, quality control, information systems and safety procedures you have actually used. For pharmacy support, highlight stock accuracy, customer service, confidentiality, computer skills and careful record keeping. Tailor each application to the essential criteria, prepare references and explain employment gaps accurately.</p>

<p>Keep a tracker showing the employer, vacancy reference, application date, licensing stage and response, and upload only the documents the verified recruitment process asks for. Prepare interview examples about accuracy, teamwork, confidentiality and knowing when to escalate a concern, and avoid sharing identifiable patient details. If you are offered a job, ask for the full contract and ask who pays for required training, verification, licensing and relocation. Get any repayment conditions in writing.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners apply?</h3>
<p>Some pharmacy assistant vacancies provide training. Clinical laboratory eligibility depends on qualifications, licensing rules and employer requirements, so check the specific route.</p>

<h3>Is DHA eligibility enough to start working?</h3>
<p>No. An eligibility assessment does not authorise practice. You must complete registration and licence activation, which the hiring facility finishes.</p>

<h3>Is a pharmacy assistant a registered pharmacy technician?</h3>
<p>No. The roles have different duties and training pathways, and the titles are not interchangeable.</p>

<h3>Which health authority licenses a UAE lab technician?</h3>
<p>It depends on the facility: the DHA in Dubai, the Department of Health in Abu Dhabi, or the Ministry of Health and Prevention in other emirates. Ask the employer.</p>

<h3>Do I have to pay a recruitment fee for a UAE job?</h3>
<p>No. Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs.</p>

<h3>Can I work as a lab technician in the UAE on a visit visa?</h3>
<p>No. You need a work permit and the professional licence for the facility's regulator.</p>

<h3>Is overseas sponsorship guaranteed?</h3>
<p>No. Ask the employer and check current immigration eligibility before making relocation plans.</p>

<h3>Does an overseas pharmacy qualification work in the UK?</h3>
<p>Not automatically. It does not establish UK eligibility as a pharmacist or pharmacy technician.</p>

<h2>People Also Search For</h2>

<h3>Lab technician jobs UAE</h3>
<p>Match your qualification to the exact title and the facility's health authority.</p>

<h3>Medical laboratory technician Dubai</h3>
<p>Follows the DHA Sheryan pathway for Dubai facilities.</p>

<h3>DHA licence for lab technician</h3>
<p>Eligibility, verification, registration and licence activation by the hiring facility.</p>

<h3>Pharmacy assistant jobs UK</h3>
<p>Employer criteria vary; training is common.</p>

<h3>Trainee pharmacy assistant</h3>
<p>A search term for entry roles that include training.</p>

<h3>Dispensary assistant jobs</h3>
<p>Support roles in a pharmacy dispensary.</p>

<h3>Healthcare jobs for foreigners</h3>
<p>Licensed roles need registration; support roles need the right to work.</p>

<h3>Pharmacy technician versus pharmacy assistant</h3>
<p>Different duties, training and registration.</p>

<h2>More Job Guides</h2>

<p>Related healthcare guides:</p>

<ul>
    <li><a href="/blog/nursing-jobs-abroad-requirements-for-foreign-nurses">Nursing Jobs Abroad: Requirements for Foreign Nurses</a> &mdash; registration in Saudi Arabia, the UK and Australia.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; roles beyond registered clinical work.</li>
    <li><a href="/blog/hospital-support-worker-jobs-in-the-uk">Hospital Support Worker Jobs in the UK</a> &mdash; another UK support route.</li>
    <li><a href="/blog/nursing-assistant-jobs-in-the-uk">Nursing Assistant Jobs in the UK</a> &mdash; the care-support route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. UAE information is from the Department of Health Abu Dhabi, the Dubai Health Authority and u.ae; UK information from the National Careers Service and GOV.UK, reviewed on 6 October 2026. Requirements change, so confirm them with the official authority before applying.</p>
HTML;
    }
}
