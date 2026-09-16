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
 * "School Nurse Jobs in USA" — registered nurses in public, charter and
 * private K-12 schools. The registered nurse guide owns national RN pay,
 * licensure and the compact; this one owns what schools pay, the state
 * education credentials on top of the RN license, national certification,
 * and how many schools actually have a nurse.
 *
 * Corrections to the draft:
 *
 * 1. Its pay bands are low. The BLS May 2025 figures for registered nurses in
 *    elementary and secondary schools are a $69,340 median and a $73,960
 *    mean, with a tenth percentile of $48,010 and a ninetieth of $103,310.
 *
 * 2. It says an ADN is sometimes accepted. That depends on the state: the
 *    California School Nurse Services Credential, the New Jersey School Nurse
 *    certificate and the Pennsylvania School Nurse certificate all need a
 *    bachelor's degree on top of the RN license.
 *
 * 3. It presents NBCSN certification as something a candidate can bring to an
 *    application. The NCSN exam requires a bachelor's degree in nursing (or a
 *    qualifying master's) and at least 1,000 hours of school nursing in the
 *    three years before the test, so it is a mid-career credential.
 *
 * 4. It says demand continues to rise with no source. The National School
 *    Nurse Workforce Study 2.0 found 65.7 per cent of schools had a full-time
 *    nurse and 18.1 per cent had no paid nurse.
 *
 * 5. Its apply link searches the Indeed site with a query string rather than
 *    the site's own school nurse search page.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SchoolNurseJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-school-nurse-jobs.html';

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
        $title = 'School Nurse Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'School nurses earn a BLS median of $69,340, well below the $97,550 RN median, several states require a bachelor\'s degree and an education credential on top of the RN license, and 18.1 per cent of schools have no paid nurse.',
                'content' => $content,
                'featured_image' => 'blogs/school-nurse-jobs-in-usa.jpg',
                'tags' => 'school nurse jobs usa, school nurse salary, how to become a school nurse, school nurse certification by state, ncsn certification, nbcsn exam, california school nurse services credential, new jersey school nurse certificate, school nurse requirements, rn school jobs',
                'meta_title' => 'School Nurse Jobs in USA 2026: Salary and Credentials',
                'meta_description' => 'School nurse jobs in the USA: the BLS school pay median of $69,340, state credentials that need a bachelor\'s degree, NCSN certification rules and staffing.',
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
            ['name' => 'U.S. Public School Districts, Charter & Private Schools (Aggregated)'],
            ['type' => 'Public', 'display_reference' => 'us-school-nurse-aggregated']
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
                'position' => 'School Nurse — Public School Districts, Charter and Private K-12 Schools, U.S. Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'School-day hours on a school-year contract; some districts add summer programs',
                'language' => 'English',
                // District salary schedules differ widely, so no single range
                // is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'School nurse roles in U.S. public school districts, charter and private schools. RN license required; some states need a credential.',
                'seo_keywords' => 'school nurse jobs, school nurse jobs near me, district school nurse, school rn jobs, school health services jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Public school districts, charter schools and private schools across the United States hire registered nurses to run school health offices, often covering more than one campus.</p>

<h3>What the work involves</h3>
<p>First aid and emergency response, medication administration, care plans for students with asthma, diabetes, seizures and severe allergies, state-required screenings, immunization compliance, health records, and working with families, teachers and doctors.</p>

<h3>Requirements</h3>
<ul>
    <li>A current RN license in the state where the school is</li>
    <li>In states such as California, New Jersey and Pennsylvania, a bachelor's degree and a school nurse credential from the state education agency</li>
    <li>CPR certification and the fingerprint-based background check required for school staff</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured pay.</strong> The BLS May 2025 median for registered nurses in elementary and secondary schools is $69,340 a year</li>
    <li><strong>Salary schedules.</strong> Many districts place school nurses on a certificated or classified salary schedule tied to a school-year contract</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check your state education agency's school nurse credential rules</strong> before applying, because a license alone is not enough in every state.</p>

<p><strong>Note:</strong> pay, credentials and hiring rules are set by school districts, state boards of nursing and state education agencies &mdash; not by JobGader. Confirm the details with the district before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>School nurses look after the health and safety of students through the school day, from first aid and daily medication to care plans for diabetes, asthma and severe allergies. The role appeals to registered nurses who want school-day hours and a school-year calendar instead of hospital shifts. Before you apply, it helps to know what schools really pay compared with hospitals, which states want more than an RN license, what national certification involves, and how many schools actually employ a nurse.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-school-nurse-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129658; Browse School Nurse Jobs in the USA &rarr;
    </a>
</div>

<h2>How Many Schools Have a Nurse</h2>

<p>Guides say demand for school nurses continues to rise, without a figure. The best national picture is the <strong>National School Nurse Workforce Study 2.0</strong>, published in 2024:</p>

<ul>
    <li><strong>65.7 per cent of schools</strong> had a full-time school nurse.</li>
    <li><strong>18.1 per cent of schools</strong> had no paid school nurse at all.</li>
    <li>Most of the rest share a part-time nurse with other schools.</li>
</ul>

<p>So the gap is real, but it shows up as shared and part-time posts as much as new full-time jobs. Districts that cover several schools with one nurse are the ones where the guides' point about prioritization matters most.</p>

<h2>What a School Nurse Does</h2>

<ul>
    <li>First aid and emergency response, including seizures, anaphylaxis and injuries</li>
    <li>Giving and supervising medication, and training staff where state law allows delegation</li>
    <li>Individualized health plans for students with diabetes, asthma, allergies and other conditions</li>
    <li>Vision, hearing and other screenings, following the state's own screening rules</li>
    <li>Immunization compliance and student health records</li>
    <li>Health services for students with disabilities under IDEA and Section 504 plans</li>
    <li>Working with parents, doctors, counselors and teachers, including on mental health referrals</li>
</ul>

<p>Screening rules differ by state. Vision and hearing screening is common, but not every state requires scoliosis screening, so check the state's school health rules rather than assuming a national list.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/school-nurse-jobs-in-usa-office.jpg"
         alt="A smiling school nurse in navy scrubs with a stethoscope writing notes at her desk in a school health office, with an American flag and a school bus outside the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>School Nurse Salary: What the BLS Measures</h2>

<p>Guides quote <strong>$45,000 to $55,000</strong> for entry-level school nurses and $55,000 to $65,000 for experienced ones. The Bureau of Labor Statistics measures registered nurses working in <strong>elementary and secondary schools</strong> directly. May 2025 figures:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">RNs in elementary and secondary schools</th>
            <th style="padding:10px;text-align:left;">All registered nurses</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Employment</td><td style="padding:10px;">67,120</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lowest 10 per cent earn less than</td><td style="padding:10px;">$48,010</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Median</td><td style="padding:10px;">$69,340</td><td style="padding:10px;">$97,550</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mean</td><td style="padding:10px;">$73,960</td><td style="padding:10px;">$101,420</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Top 10 per cent earn more than</td><td style="padding:10px;">$103,310</td><td style="padding:10px;">&mdash;</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>The guides' bands are low.</strong> Their experienced band tops out below the school median of $69,340, and most of their entry band sits near the lowest tenth.</li>
    <li><strong>Schools still pay less than hospitals.</strong> The school median is about $28,000 below the median for all registered nurses.</li>
    <li><strong>The school calendar explains part of it.</strong> Many districts pay nurses on a school-year contract of fewer working days, often on the same certificated or classified salary schedule as other school staff, with steps for years of service and degrees.</li>
</ul>

<h2>Licenses and State Credentials</h2>

<p>Every school nurse needs a current <strong>RN license</strong> in the state where they work, either a state license or a multistate license where the state is in the Nurse Licensure Compact. Guides say an Associate Degree in Nursing is sometimes accepted. Whether it is depends on the state, because several state education agencies issue a <strong>school nurse credential</strong> on top of the license:</p>

<ul>
    <li><strong>California.</strong> The preliminary <strong>School Nurse Services Credential</strong> requires a bachelor's degree or higher, a California RN license and fingerprint clearance. It is issued for five years and <strong>is not renewable</strong>. The clear credential needs two years of school nurse experience and a Commission-approved school nurse program.</li>
    <li><strong>New Jersey.</strong> The <strong>School Nurse certificate</strong> requires a bachelor's degree, a New Jersey RN license, CPR and AED certification, and coursework in areas such as school nursing, health assessment and special education.</li>
    <li><strong>Pennsylvania.</strong> The <strong>School Nurse certificate</strong> requires a bachelor's degree and RN licensure, with an approved certification program.</li>
</ul>

<p>In states without a separate credential, an RN license and district requirements are enough, and an ADN can qualify. Check the state department of education, not only the board of nursing, before you apply.</p>

<h2>National Certification: NCSN</h2>

<p>The <strong>National Board for Certification of School Nurses (NBCSN)</strong> awards the <strong>Nationally Certified School Nurse (NCSN)</strong> credential. Guides present it as something that strengthens an application, but it is <strong>not an entry credential</strong>:</p>

<ul>
    <li>A current <strong>RN license</strong>.</li>
    <li>A <strong>bachelor's degree or higher in nursing</strong>, or a master's in education with a concentration in school nursing or school health from an approved institution.</li>
    <li>At least <strong>1,000 hours of school nursing practice in the three years</strong> before the exam, confirmed by a supervisor or district official.</li>
    <li>A 2026 application fee of <strong>$380 early bird</strong> or $400 regular, with a $50 late fee.</li>
</ul>

<p>Most nurses sit the NCSN after a year or more in a school, and some districts reward it on their salary schedule.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/school-nurse-jobs-in-usa-student.jpg"
         alt="A school nurse in light blue scrubs listening to a smiling student's chest with a stethoscope in a bright school health office with a first aid kit on the desk"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where to Find School Nurse Jobs</h2>

<ol>
    <li><strong>District HR pages.</strong> Most public districts post nursing vacancies directly, and many use their state's education job board.</li>
    <li><strong>Job boards.</strong> Indeed lists public, charter and private school postings; set alerts before spring.</li>
    <li><strong>State school nurse associations.</strong> NASN's state affiliates share postings and networking.</li>
    <li><strong>Staffing agencies.</strong> Some place contract nurses in schools, often for one-to-one care of a student with complex needs.</li>
</ol>

<p>Most districts hire on a school-year cycle, with the heaviest hiring in spring and summer for an August or September start.</p>

<h2>Tips for Landing a School Nurse Job</h2>

<ul>
    <li><strong>Sort the credential first.</strong> In credential states, apply for it early or confirm the district will hire you while it is processed.</li>
    <li><strong>Show pediatric or community health experience.</strong> Pediatrics, emergency and public health backgrounds transfer well.</li>
    <li><strong>Prepare for scenario questions.</strong> Expect a severe allergic reaction, a diabetic low or a seizure with no doctor on site.</li>
    <li><strong>Know the plans.</strong> Be ready to explain an individualized health plan, an emergency care plan and your role in a Section 504 plan.</li>
    <li><strong>Ask about the salary schedule.</strong> Confirm the contract length, the step you start on and whether degrees or NCSN move you up.</li>
</ul>

<h2>Career Progression</h2>

<p>With experience, school nurses move into lead or district nurse roles, health services coordination, public health nursing, or nurse education. A <strong>pediatric or family nurse practitioner</strong> role needs a graduate degree and national NP certification, and some states also license school nurse practitioners in school-based health centers.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do school nurses make in the USA?</h3>
<p>The BLS May 2025 median for registered nurses in elementary and secondary schools is $69,340 a year, with a mean of $73,960. That is well below the $97,550 median for all registered nurses.</p>

<h3>Do school nurses need a bachelor's degree?</h3>
<p>In some states. California, New Jersey and Pennsylvania require a bachelor's degree for the school nurse credential, while other states only require an RN license.</p>

<h3>What is the California School Nurse Services Credential?</h3>
<p>A credential from the Commission on Teacher Credentialing. The preliminary version needs a bachelor's degree, a California RN license and fingerprint clearance, and lasts five years without renewal.</p>

<h3>How do I become a Nationally Certified School Nurse?</h3>
<p>Through NBCSN. You need an RN license, a bachelor's degree in nursing or a qualifying master's, and at least 1,000 hours of school nursing in the three years before the exam.</p>

<h3>How much does the NCSN exam cost?</h3>
<p>The 2026 application fee is $380 early bird or $400 regular, with a $50 late fee.</p>

<h3>Do all schools have a nurse?</h3>
<p>No. The National School Nurse Workforce Study 2.0 found 65.7 per cent of schools had a full-time nurse and 18.1 per cent had no paid nurse.</p>

<h3>Do school nurses get summers off?</h3>
<p>Many work a school-year contract with summers off, but that contract usually pays for fewer working days than a hospital job.</p>

<h3>Can an LPN or ADN nurse work as a school nurse?</h3>
<p>An ADN-prepared RN can in states without a bachelor's requirement. Some districts also hire LPNs as health aides under an RN's supervision, but the school nurse title usually requires an RN.</p>

<h2>People Also Search For</h2>

<h3>School nurse salary by state</h3>
<p>District salary schedules decide it; the national school median is $69,340.</p>

<h3>How to become a school nurse</h3>
<p>An RN license, plus a bachelor's degree and state credential in some states.</p>

<h3>NCSN certification requirements</h3>
<p>RN license, bachelor's in nursing and 1,000 hours of school nursing in three years.</p>

<h3>California school nurse credential</h3>
<p>Preliminary for five years, then a clear credential after two years and an approved program.</p>

<h3>New Jersey school nurse certificate</h3>
<p>Bachelor's degree, New Jersey RN license, CPR and AED, and school nursing coursework.</p>

<h3>School nurse jobs near me</h3>
<p>Start with district HR pages and state education job boards.</p>

<h3>School nurse vs hospital nurse pay</h3>
<p>About $28,000 lower at the median, on a shorter school-year contract.</p>

<h3>Part-time school nurse jobs</h3>
<p>Common where one nurse is shared between several schools.</p>

<h2>More Job Guides</h2>

<p>Comparing nursing and school jobs? These cover them:</p>

<ul>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; national RN pay, the multistate license and the NCLEX.</li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the nursing roles and pathways across American health care.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; state teaching licenses and the salary schedules school nurses often share.</li>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; the no-degree clinical role, and what it pays by setting.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; an entry-level health care job with NHS pay bands.</li>
    <li><a href="/blog/physical-therapist-jobs-in-usa">Physical Therapist Jobs in the USA</a> &mdash; a high-paying allied-health role, its licensing and the visa route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, licensing or careers advice. Wage data, state credential rules, certification fees and school health requirements change and differ by state and district. Confirm the current position with your state board of nursing, your state education agency, NBCSN and the district before applying.</p>
HTML;
    }
}
