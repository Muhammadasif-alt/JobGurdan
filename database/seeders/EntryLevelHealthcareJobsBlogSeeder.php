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
use Illuminate\Support\Str;

/**
 * "Entry Level Healthcare Jobs" — a US guide for someone who wants to start
 * in healthcare without a degree. The Medical Assistant, Registered Nurse and
 * Nurse guides each cover one occupation in depth, and the Healthcare Jobs
 * landing page covers pay and licensing across the USA and UK, so this one
 * compares the short-training roles side by side: entry education, training,
 * May 2025 pay, 2025-35 growth and which employers train new starters.
 *
 * Corrections and clarifications to the draft (checked against the BLS
 * Occupational Outlook Handbook, BLS projections and Occupational Requirements
 * Survey, eCFR, HHS and the careers sites of Cleveland Clinic, Mayo Clinic and
 * HCA Healthcare, September 2026):
 *
 * 1. The draft's table uses 2024-34 growth figures. BLS now publishes 2025-35
 *    projections: home health and personal care aides 18%, medical assistants
 *    13%, medical records specialists 8%, phlebotomists 7%, medical equipment
 *    preparers 11% and dental assistants 7%.
 *
 * 2. The draft says nursing assistants "commonly" learn on the job. Nursing
 *    home nurse aides must complete at least 75 clock hours of training,
 *    including 16 hours of supervised practical training, and pass a
 *    competency evaluation (42 CFR 483.152), then be on the state registry.
 *
 * 3. The draft links a Cleveland Clinic medical assistant posting as an entry
 *    point. That posting requires graduation from an approved medical assisting
 *    program and a clinical externship.
 *
 * 4. The draft points to generic Mayo Clinic searches. Mayo Clinic advertises a
 *    Patient Care Assistant role with paid training at $21.96-$29.02 an hour,
 *    which is the genuine no-degree entry point, so that is described instead.
 *
 * 5. The draft says BLS's Occupational Requirements Survey covers people
 *    skills without any figures. The 2025 survey found on-the-job training was
 *    required for 86.5% of healthcare support workers and no minimum education
 *    for 30.7%.
 *
 * 6. The draft quotes no pay. BLS May 2025 medians are added for every role,
 *    and "patient care technician", which is not a BLS occupation, is placed
 *    with nursing assistants.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EntryLevelHealthcareJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-entry-level-healthcare-jobs.html';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
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
        $title = 'Entry Level Healthcare Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Seven US healthcare roles you can start without a degree, with BLS May 2025 pay and 2025-35 growth, the 75-hour federal rule for nurse aides, and the hospitals that pay you while they train you.',
                'content' => $content,
                'featured_image' => 'blogs/entry-level-healthcare-jobs.jpg',
                'tags' => 'entry level healthcare jobs, healthcare jobs no experience, healthcare jobs without a degree, patient care assistant paid training, nursing assistant training hours, phlebotomist jobs entry level, medical records specialist salary, home health aide pay, hospital jobs with paid training',
                'meta_title' => 'Entry Level Healthcare Jobs: Roles, Pay and Training (2026)',
                'meta_description' => 'Entry level healthcare jobs in the US: roles that need only a diploma or short course, BLS pay and growth to 2035, and hospitals that train new staff.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Hospitals, Clinics & Care Providers Hiring Entry-Level Staff (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-entry-level-healthcare-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Entry Level Healthcare Worker — Patient Care, Nursing Assistant, Phlebotomy and Medical Records Roles, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work in hospitals and care homes, including nights and weekends; clinic roles mostly daytime',
                'language' => 'English',
                // Pay differs widely by role, state and employer, so no single
                // band is quoted on the listing.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Entry level healthcare roles with US hospitals, clinics and care providers: patient care assistants, nursing assistants, phlebotomists and medical records staff.',
                'seo_keywords' => 'entry level healthcare jobs, healthcare jobs no experience, patient care assistant jobs, nursing assistant jobs, phlebotomist jobs, medical records jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hospitals, clinics, nursing homes and home care agencies across the United States hire entry-level staff to support patients and clinical teams, often with training on the job.</p>

<h3>Typical roles</h3>
<ul>
    <li>Patient care assistants and nursing assistants</li>
    <li>Home health and personal care aides</li>
    <li>Phlebotomists</li>
    <li>Medical records specialists and unit secretaries</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>Usually a high school diploma or equivalent; some roles need a certificate or short course</li>
    <li>Nursing home nurse aides: at least 75 hours of approved training, a competency evaluation and a place on the state registry</li>
    <li>A background check and, for many roles, Basic Life Support certification</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, training and certification requirements are set by each employer and by state law &mdash; not by JobGader. Confirm the details on the employer's own posting before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Entry level healthcare jobs are roles you can start with a high school diploma or a short training course, such as patient care assistant, nursing assistant, home health aide, phlebotomist and medical records specialist.</strong> The U.S. Bureau of Labor Statistics (BLS) projects healthcare occupations to grow "much faster than the average for all occupations from 2025 to 2035", with about <strong>1.9 million openings a year</strong>. Median pay for healthcare support occupations was <strong>$38,340</strong> in May 2025, and some hospitals pay you while they train you.</p>

<p>This guide compares the main no-degree roles on entry education, pay and growth, explains the training rules that apply, and shows where to apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-entry-level-healthcare-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127973; Browse Entry Level Healthcare Jobs &rarr;
    </a>
</div>

<h2>Entry Level Healthcare Jobs Compared</h2>

<p>Many guides still quote the older 2024&ndash;34 projections. These are the current BLS figures:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Job</th>
            <th style="padding:10px;text-align:left;">Typical entry education</th>
            <th style="padding:10px;text-align:left;">Median pay, May 2025</th>
            <th style="padding:10px;text-align:left;">Growth 2025&ndash;35</th>
            <th style="padding:10px;text-align:left;">Openings a year</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Home health and personal care aide</strong></td><td style="padding:10px;">High school diploma or equivalent</td><td style="padding:10px;">$35,800 ($17.21/hr)</td><td style="padding:10px;"><strong>18%</strong></td><td style="padding:10px;">760,500</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Nursing assistant and orderly</strong></td><td style="padding:10px;">State-approved training for nursing assistants</td><td style="padding:10px;">$41,870 ($20.13/hr)</td><td style="padding:10px;">3%</td><td style="padding:10px;">203,300</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Medical assistant</strong></td><td style="padding:10px;">Postsecondary nondegree award</td><td style="padding:10px;">$45,690 ($21.97/hr)</td><td style="padding:10px;">13%</td><td style="padding:10px;">109,700</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Phlebotomist</strong></td><td style="padding:10px;">Postsecondary nondegree award</td><td style="padding:10px;">$45,230 ($21.75/hr)</td><td style="padding:10px;">7%</td><td style="padding:10px;">18,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Medical equipment preparer</strong></td><td style="padding:10px;">High school diploma or equivalent</td><td style="padding:10px;">$47,700</td><td style="padding:10px;">11%</td><td style="padding:10px;">10,700</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Dental assistant</strong></td><td style="padding:10px;">Postsecondary nondegree award</td><td style="padding:10px;">$48,070 ($23.11/hr)</td><td style="padding:10px;">7%</td><td style="padding:10px;">53,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Medical records specialist</strong></td><td style="padding:10px;">Postsecondary nondegree award</td><td style="padding:10px;">$51,140 ($24.59/hr)</td><td style="padding:10px;">8%</td><td style="padding:10px;">14,000</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">U.S. Bureau of Labor Statistics, Occupational Outlook Handbook and Employment Projections, 2025&ndash;35, pay from May 2025, September 2026. Medical equipment preparers are listed among occupations not covered in detail.</p>
</div>

<p>Home health and personal care aides have by far the most openings, but they are also the lowest paid. Medical records specialists pay the most here, and the job is administrative rather than hands-on patient care.</p>

<h2>The Roles, One by One</h2>

<h3>1. Patient care assistant or nursing assistant</h3>
<p>Nursing assistants help patients eat, bathe, dress and move, and take basic measurements for nurses. Hospitals use titles such as <strong>patient care technician</strong> or <strong>patient care assistant</strong> for similar work; BLS counts it under nursing assistants and orderlies. Nursing assistants earned a median of <strong>$42,260</strong> in May 2025.</p>
<p>The training rule is stricter than most guides say. In nursing homes that take Medicare or Medicaid, a nurse aide must complete <strong>at least 75 clock hours of training, including at least 16 hours of supervised practical training</strong>, and pass a competency evaluation (42 CFR 483.152). BLS adds that nursing assistants "must be on the state registry to work in a nursing home". Orderlies, who mainly move patients and equipment, "do not need a license".</p>

<h3>2. Home health and personal care aide</h3>
<p>Aides help older adults and people with disabilities with daily activities at home or in group homes. BLS lists a high school diploma or equivalent and short-term on-the-job training as typical, and this is the fastest-growing role in the table at 18%. The trade-off is pay: a median of $35,800 a year.</p>

<h3>3. Medical assistant</h3>
<p>Medical assistants take patient histories and vital signs and handle scheduling in clinics and physician offices. BLS lists a <strong>postsecondary nondegree award</strong> as the typical entry education. That matters in practice: a Cleveland Clinic medical assistant posting checked in September 2026 required graduation from an approved medical assisting program and a clinical externship, paying $19.75 to $27.75 an hour. Our <a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> guide covers certification in detail.</p>

<h3>4. Phlebotomist</h3>
<p>Phlebotomists draw blood for tests, donations and transfusions. BLS lists a postsecondary nondegree award for entry and says: "States may require that phlebotomists complete an accredited training program, have a license or certification, or meet other requirements." Check with your state licensing agency before paying for a course.</p>

<h3>5. Medical records specialist</h3>
<p>Medical records specialists organise, code and maintain patient records in electronic health record systems. It is the best-paid role in the table at a median of $51,140, and usually needs a postsecondary certificate.</p>

<h3>6. Medical equipment preparer</h3>
<p>Preparers clean, sterilise and assemble medical instruments. BLS lists a high school diploma or equivalent with moderate-term on-the-job training, a May 2025 median of $47,700 and 11% projected growth.</p>

<h3>7. Dental assistant</h3>
<p>Dental assistants prepare patients and instruments and keep records in dental offices. BLS lists a postsecondary nondegree award for entry, with a median of $48,070.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/entry-level-healthcare-jobs-care-team.jpg"
         alt="A smiling healthcare worker in blue scrubs holding a clipboard in front of three colleagues in scrubs, with healthcare icons of a stethoscope, a medical cross, a checklist and a caring hand"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Will a Hospital Train You?</h2>

<p>Often, yes. In BLS's 2025 Occupational Requirements Survey, <strong>on-the-job training was required for 86.5 percent</strong> of healthcare support workers, and <strong>no minimum education was required for 30.7 percent</strong>.</p>

<ul>
    <li><strong>Mayo Clinic</strong> advertises a Patient Care Assistant role marked "Paid Training Provided" in Rochester, Minnesota, at <strong>$21.96 to $29.02 an hour</strong> based on its union contract. It asks for 12 college credits, or a high school diploma or GED, and prefers completed nursing assistant training. Basic Life Support certification is included in orientation.</li>
    <li><strong>Cleveland Clinic</strong> says many people who become patient care nursing assistants "have no previous medical or clinical experience", with an orientation period of about four weeks.</li>
    <li><strong>HCA Healthcare</strong> runs a careers section for people starting a healthcare career. Check each posting for its current training and experience requirements.</li>
</ul>

<p>Postings close and change, so confirm the pay and requirements on the live advertisement.</p>

<h2>Skills Healthcare Employers Look For</h2>

<ul>
    <li><strong>Communication:</strong> in the 2025 survey, 47.0 percent of healthcare support workers had to speak with people outside their team constantly, every few minutes.</li>
    <li><strong>Patient privacy:</strong> the HIPAA Privacy Rule "requires appropriate safeguards to protect the privacy of protected health information", and new staff are trained on it.</li>
    <li><strong>Attention to detail:</strong> patient identification, specimen labels and records have to be right every time.</li>
    <li><strong>Physical stamina:</strong> patient care roles involve long shifts on your feet, lifting and moving patients.</li>
    <li><strong>Reliability:</strong> hospitals and care homes run around the clock, so night and weekend shifts are common.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/entry-level-healthcare-jobs-clinical-team.jpg"
         alt="A smiling healthcare worker in light blue scrubs with a stethoscope writing on a clipboard, with three colleagues in scrubs behind her in a bright hospital ward"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Get an Entry Level Healthcare Job, Step by Step</h2>

<ol>
    <li><strong>Pick patient care or admin.</strong> Patient care means nursing assistant, aide or phlebotomy; admin means medical records or unit secretary roles.</li>
    <li><strong>Check the training rule before you pay for anything.</strong> Nursing home aides need a state-approved programme of at least 75 hours, and phlebotomy rules differ by state.</li>
    <li><strong>Look for paid training first.</strong> Search hospital careers sites for "paid training" or "patient care assistant" before paying for a course yourself.</li>
    <li><strong>Get Basic Life Support (CPR) certification.</strong> Many postings ask for it, and some employers include it in orientation.</li>
    <li><strong>Write your resume around transferable skills.</strong> Customer service, caring for relatives, volunteering and reliability all count.</li>
    <li><strong>Apply on the employer's own site</strong> and expect a background check and health screening before you start.</li>
</ol>

<h2>Where to Apply</h2>

<p>Apply through employers' official careers sites, where the job description and requirements are the employer's own:</p>

<ul>
    <li><strong>Mayo Clinic:</strong> jobs.mayoclinic.org</li>
    <li><strong>Cleveland Clinic:</strong> jobs.clevelandclinic.org</li>
    <li><strong>HCA Healthcare:</strong> careers.hcahealthcare.com</li>
</ul>

<p>Local hospital systems, nursing homes, home care agencies, blood donation centres and laboratories hire for the same roles. For a wider view of pay and licensing across the sector, see our <a href="/healthcare-jobs">healthcare jobs</a> page, and for other first jobs, the <a href="/entry-level-jobs">entry level jobs</a> page.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the easiest healthcare job to get with no experience?</h3>
<p>Home health and personal care aide roles typically need a high school diploma or equivalent and short-term on-the-job training, and they have about 760,500 openings a year. Hospital patient care assistant roles with paid training are another direct way in.</p>

<h3>Can I get a healthcare job with only a high school diploma?</h3>
<p>Yes. BLS lists a high school diploma or equivalent as typical for home health and personal care aides and medical equipment preparers, and the 2025 Occupational Requirements Survey found no minimum education was required for 30.7 percent of healthcare support workers.</p>

<h3>How much do entry level healthcare jobs pay?</h3>
<p>BLS May 2025 medians range from $35,800 a year for home health and personal care aides to $51,140 for medical records specialists. The median for all healthcare support occupations was $38,340.</p>

<h3>How long is nursing assistant training?</h3>
<p>For nurse aides in nursing homes, federal rules require at least 75 clock hours of training, including at least 16 hours of supervised practical training, followed by a competency evaluation. Some states require more hours.</p>

<h3>Do phlebotomists need a license?</h3>
<p>It depends on the state. BLS says states may require phlebotomists to complete an accredited training program, hold a license or certification, or meet other requirements, so check with your state licensing agency.</p>

<h3>Are healthcare jobs in demand in the US?</h3>
<p>Yes. BLS projects healthcare occupations to grow much faster than average from 2025 to 2035, with about 1.9 million openings a year. Home health and personal care aides are projected to grow 18 percent.</p>

<h3>Do hospitals offer paid training for entry level jobs?</h3>
<p>Some do. Mayo Clinic advertises a Patient Care Assistant role with paid training at $21.96 to $29.02 an hour, and Cleveland Clinic runs an orientation of about four weeks for new patient care nursing assistants.</p>

<h3>Is a medical assistant job entry level?</h3>
<p>Partly. BLS lists a postsecondary nondegree award as typical, and many employers, including a Cleveland Clinic posting checked in 2026, require completion of a medical assisting program before you start.</p>

<h2>People Also Search For</h2>

<h3>Healthcare jobs with no experience</h3>
<p>Home health aide, personal care aide and patient care assistant roles with paid training.</p>

<h3>Healthcare jobs without a degree</h3>
<p>Nursing assistant, phlebotomist, medical records specialist and medical equipment preparer.</p>

<h3>Patient care assistant paid training</h3>
<p>Offered by some hospital systems, including Mayo Clinic, with pay during training.</p>

<h3>CNA training hours</h3>
<p>At least 75 clock hours for nursing home nurse aides under federal rules, including 16 supervised.</p>

<h3>Home health aide salary</h3>
<p>A median of $35,800 a year, or $17.21 an hour, in May 2025.</p>

<h3>Phlebotomist salary</h3>
<p>A median of $45,230 a year, or $21.75 an hour, in May 2025.</p>

<h3>Medical records specialist salary</h3>
<p>A median of $51,140 a year, the highest of the no-degree roles compared here.</p>

<h3>Fastest growing entry level healthcare jobs</h3>
<p>Home health and personal care aides at 18 percent and medical assistants at 13 percent to 2035.</p>

<h2>More Job Guides</h2>

<p>Looking at a specific healthcare career? These go deeper:</p>

<ul>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; training programmes, certification and clinic pay.</li>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; the degree and NCLEX route once you are ready to move up.</li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; nursing roles and licensing across the country.</li>
    <li><a href="/blog/physical-therapist-jobs-in-usa">Physical Therapist Jobs in USA</a> &mdash; a longer route into patient care.</li>
    <li><a href="/blog/healthcare-support-jobs-in-uk">Healthcare Support Jobs in UK</a> &mdash; the same entry roles under NHS rules.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Pay, projections, training rules and job postings change over time. Confirm the current position with the U.S. Bureau of Labor Statistics, your state licensing agency and the employer's own posting before relying on it.</p>
HTML;
    }
}
