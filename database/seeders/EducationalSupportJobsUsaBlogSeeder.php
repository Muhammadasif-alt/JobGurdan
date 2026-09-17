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
 * "Educational Support Jobs in USA" — a guide to teacher assistant,
 * paraprofessional and classroom aide work. The Teacher Jobs in USA guide
 * covers licensed teaching and the Tutor Jobs in USA guide covers tutoring
 * companies, so this one stays on support roles in schools and early learning
 * centers: pay, the federal Title I rule, the ParaPro test and who hires.
 *
 * Corrections and clarifications to the draft (checked against the BLS
 * Occupational Outlook Handbook and OEWS May 2025, 34 CFR 200.58, ETS, KIPP's
 * job board and a live KIPP Jacksonville posting, KinderCare's SEC filing, and
 * the Primrose and Goddard careers pages, September 2026):
 *
 * 1. The draft says Title I requirements can be met through a 2-year degree,
 *    two years of college or an assessment. The federal rule also requires a
 *    high school diploma or equivalent, and it covers paraprofessionals with
 *    instructional duties in Title I-funded programs.
 *
 * 2. The draft says the BLS describes teacher assistants working "with a
 *    licensed teacher" and elsewhere implies supervision. The BLS wording is
 *    "work with or under the guidance of a licensed teacher", and it adds that
 *    most states require teacher assistants who work with special-needs
 *    students to pass a skills test, which the draft leaves out.
 *
 * 3. The draft calls KIPP the employer. KIPP says its regions and the KIPP
 *    Foundation are separate, and "your employer is the KIPP region". The
 *    Jacksonville paraprofessional posting's $42,000 salary is added.
 *
 * 4. The draft's KinderCare "Teacher & Staff Opportunities" and training
 *    wording, Goddard's job-title list and Kumon's "more than 3,000 centers"
 *    could not be checked on the official pages, which block automated
 *    access, so they are removed. KinderCare's size comes from its SEC filing.
 *
 * 5. The draft gives no size for Primrose or Goddard. Primrose lists more than
 *    560 schools and Goddard 665+ schools across 37 states and Washington, DC,
 *    and both say the franchise owner is the employer.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EducationalSupportJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-paraprofessional-jobs.html';

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
        $title = 'Educational Support Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Teacher assistants and paraprofessionals held 1.46 million US jobs in 2025, with 176,200 openings a year and a median wage of $36,780. Most public schools want 2 years of college, an associate degree or a passed assessment.',
                'content' => $content,
                'featured_image' => 'blogs/educational-support-jobs-in-usa.jpg',
                'tags' => 'educational support jobs usa, teacher assistant jobs, paraprofessional jobs, instructional aide jobs, special education paraprofessional, teacher assistant salary, parapro assessment, title i paraprofessional requirements',
                'meta_title' => 'Educational Support Jobs in USA: Pay and Requirements',
                'meta_description' => 'Educational support jobs in the USA: teacher assistant and paraprofessional pay of $36,780, Title I rules, the ParaPro test and who is hiring.',
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
            ['name' => 'US School Districts, Charter Schools & Early Learning Centers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-educational-support-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'Educational Support — Teacher Assistant, Paraprofessional and Instructional Aide Roles, US Schools and Early Learning Centers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Mostly full time on the school calendar; part-time roles are common',
                'language' => 'English',
                // Pay is set by each district, charter network or center and
                // varies by state, so no single band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Teacher assistant, paraprofessional and instructional aide roles in US public schools, charter schools and early learning centers.',
                'seo_keywords' => 'paraprofessional jobs, teacher assistant jobs, instructional aide jobs, special education aide jobs, educational support jobs usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US school districts, charter schools and early learning centers hire teacher assistants, paraprofessionals and instructional aides to work with teachers and give students extra attention and instruction, including students in special education.</p>

<h3>Common requirements</h3>
<ul>
    <li>A high school diploma or equivalent</li>
    <li>For instructional roles in Title I programs: 2 years of college, an associate degree or a passed state or local assessment such as the ParaPro</li>
    <li>A background check, and CPR and first aid certification for some roles</li>
    <li>Patience and good communication with students and teachers</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, hours and hiring decisions are set by each school district, charter network or center &mdash; not by JobGader. Check your state's paraprofessional requirements before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Educational support jobs in the USA are teacher assistant, paraprofessional, instructional aide and classroom aide roles that work with teachers to give students extra attention and instruction.</strong> Teacher assistants held <strong>1,463,000 jobs in 2025</strong>, and the Bureau of Labor Statistics (BLS) projects about <strong>176,200 openings a year</strong> to 2035, even though total employment is expected to stay flat. The median wage was <strong>$36,780 a year</strong> in May 2025. Public schools generally want at least <strong>2 years of college, an associate degree or a passed state or local assessment</strong>, on top of a high school diploma.</p>

<p>This guide covers what the job involves, pay, the federal and state requirements, the ParaPro test and the employers hiring now.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-paraprofessional-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127979; Browse Paraprofessional Jobs &rarr;
    </a>
</div>

<h2>What Are Educational Support Jobs?</h2>

<p>The BLS says <strong>"Teacher assistants also are called teacher aides, instructional aides, paraprofessionals, education assistants, and paraeducators."</strong> They work with or under the guidance of a licensed teacher. You will see the same work advertised under these titles:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Job title</th>
            <th style="padding:10px;text-align:left;">Where you will see it</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Paraprofessional / paraeducator</strong></td><td style="padding:10px;">Public school districts and charter schools</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Teacher assistant / teacher aide</strong></td><td style="padding:10px;">Elementary schools, preschools and child care centers</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Instructional assistant / instructional aide</strong></td><td style="padding:10px;">District job boards, often tied to reading or math support</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Special education paraprofessional</strong></td><td style="padding:10px;">Special education classrooms and inclusion support</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Assistant teacher</strong></td><td style="padding:10px;">Early learning centers such as KinderCare, Primrose and Goddard</td></tr>
    </tbody>
</table>
</div>

<h2>What Does a Teacher Assistant Do?</h2>

<p>According to the BLS, teacher assistants typically:</p>

<ul>
    <li>reinforce lessons with individual students or small groups;</li>
    <li>help teachers with recordkeeping, such as tracking attendance and grades;</li>
    <li>help teachers prepare materials and set up equipment;</li>
    <li>supervise students in class, between classes, at lunch and recess, and on field trips.</li>
</ul>

<p>In special education, <strong>"When special education students attend regular classes, these teacher assistants help them understand the material and adapt the information to their learning style."</strong> They may also help students with basic needs such as eating or personal hygiene.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/educational-support-jobs-in-usa-reading-group.jpg" alt="A teacher assistant helping a small group of elementary students read at a classroom table" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Reinforcing lessons with small groups is the core of the job.</figcaption>
</figure>

<h2>How Much Do Teacher Assistants Earn?</h2>

<p>The BLS reports a <strong>median annual wage of $36,780</strong> in May 2025. The lowest 10% earned less than $27,150, and the highest 10% more than $50,040. The mean was $38,290. Pay by the industries that employ the most teacher assistants:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Employer type</th>
            <th style="padding:10px;text-align:left;">Share of jobs</th>
            <th style="padding:10px;text-align:left;">Median annual wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Elementary and secondary schools; local</td><td style="padding:10px;">72%</td><td style="padding:10px;"><strong>$36,970</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Child day care services</td><td style="padding:10px;">10%</td><td style="padding:10px;"><strong>$34,920</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Elementary and secondary schools; private</td><td style="padding:10px;">9%</td><td style="padding:10px;"><strong>$37,090</strong></td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">BLS Occupational Outlook Handbook and OEWS, May 2025. BLS publishes annual wages only for this job, because many teacher assistants do not work year-round.</p>
</div>

<p>Individual postings can pay more. A KIPP Jacksonville paraprofessional posting in September 2026 offered a <strong>salary of $42,000</strong> on a 12-month schedule.</p>

<h2>Job Outlook</h2>

<p>The BLS projects employment of teacher assistants to show <strong>little or no change (0%)</strong> from 2025 to 2035, a loss of about 4,900 jobs. Openings stay high anyway: about <strong>176,200 a year</strong>, mostly to replace workers who move into other occupations or leave the labor force.</p>

<h2>What Qualifications Do You Need?</h2>

<p>The BLS lists the typical entry-level education as <strong>some college, no degree</strong>, and says <strong>"Teacher assistants in public schools need at least 2 years of college coursework or an associate's degree."</strong></p>

<h3>The federal Title I rule</h3>

<p>Paraprofessionals with instructional duties in programs funded by Title I, the federal program for schools with many low-income students, must meet 34 CFR 200.58. They need <strong>a high school diploma or its recognized equivalent</strong>, plus one of these:</p>

<ul>
    <li>at least <strong>two years of study</strong> at an institution of higher education;</li>
    <li>an <strong>associate's or higher degree</strong>;</li>
    <li>a pass on a <strong>formal state or local academic assessment</strong> showing knowledge of reading, writing and math and the ability to help teach them.</li>
</ul>

<h3>The ParaPro Assessment</h3>

<p>One common option for that state or local test is the ETS <strong>ParaPro Assessment</strong>. It has <strong>90 selected-response questions</strong> across reading, math and writing, takes <strong>150 minutes</strong>, and can be taken at a test center or remotely. Check with your state or district which test and passing score they accept.</p>

<h3>Special education and other requirements</h3>

<ul>
    <li><strong>"Most states require teacher assistants who work with special-needs students to pass a skills test."</strong></li>
    <li>Some jobs require <strong>CPR and first aid</strong> certification.</li>
    <li>Schools run background checks. The KIPP Jacksonville posting, for example, requires a background check under Florida law.</li>
    <li>Some employers accept a state paraprofessional certification instead of college credits. KIPP Jacksonville accepts a high school diploma with a valid paraprofessional certification, <strong>or</strong> an associate's degree, <strong>or</strong> at least 60 completed college credit hours.</li>
</ul>

<p>Early learning centers set requirements by state child care licensing rules and their own policies, so read each posting.</p>

<h2>Full-Time, Part-Time and Summers</h2>

<p>The BLS says <strong>"Most teacher assistants work full time, although part-time work is common."</strong> <strong>"Many teacher assistants do not work during the summer"</strong>, though some work in year-round schools or summer school. Many child care centers stay open year-round.</p>

<h2>Who Is Hiring for Educational Support Jobs?</h2>

<p>About 72% of teacher assistants work for local public schools, so your <strong>school district's job board</strong> is the first place to look. These employers also hire support staff across many states:</p>

<h3>1. KIPP Public Schools</h3>
<p>KIPP is a national network of public charter schools. Its job board, <a href="https://careers.smartrecruiters.com/KIPP" target="_blank" rel="noopener">careers.smartrecruiters.com/KIPP</a>, has a <strong>School-based Support</strong> department, and in September 2026 it listed paraprofessional roles in Jacksonville, New Orleans, Nashville, Austin, San Antonio, Houston, Dallas, Kansas City, Stockton and Redwood City. KIPP notes that its regions and the KIPP Foundation are separate, and <strong>"your employer is the KIPP region, not the KIPP Foundation."</strong></p>
<p>The Jacksonville paraprofessional posting covers instructional support, small-group interventions, literacy and math reinforcement, progress monitoring and testing support.</p>

<h3>2. KinderCare Learning Companies</h3>
<p>KinderCare runs about <strong>1,600 early childhood education centers</strong> and more than 1,100 before- and after-school sites in 41 states and Washington, DC, according to its 2026 SEC filing. Search its <a href="https://www.kindercare.com/about-us/connect-with-us/careers" target="_blank" rel="noopener">careers page</a> for assistant teacher and teacher roles.</p>

<h3>3. Primrose Schools</h3>
<p>Primrose has more than 560 schools and hires teachers, directors and support staff. It describes a path <strong>"From Teaching Assistant to Lead Teacher to School Leadership and beyond"</strong>. Note that <strong>"Each Primrose school is a privately owned and operated franchise, and the respective Franchise Owner is the employer at each school"</strong>, so wages and benefits differ by school. Apply through <a href="https://www.primroseschools.com/careers" target="_blank" rel="noopener">Primrose careers</a>.</p>

<h3>4. The Goddard School</h3>
<p>Goddard has 665+ schools across 37 states and Washington, DC. Each school is privately owned and operated by a franchisee, and <strong>the franchise owner is the employer</strong>. Search by state and school on <a href="https://www.goddardschool.com/careers" target="_blank" rel="noopener">Goddard careers</a> for assistant teacher roles.</p>

<h3>5. Kumon</h3>
<p>Kumon math and reading centers hire part-time center assistants who grade classwork and help students. Most centers are franchises, so your employer is usually the local center owner, not Kumon. Our <a href="/blog/tutor-jobs-in-usa">Tutor Jobs in USA</a> guide covers Kumon and other tutoring companies in detail.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/educational-support-jobs-in-usa-small-group.jpg" alt="A paraprofessional working with a small group of students writing in notebooks around a classroom table" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Public schools employ about 72% of teacher assistants, so start with your district's job board.</figcaption>
</figure>

<h2>How to Apply</h2>

<ol>
    <li><strong>Pick the setting:</strong> a public school district, a charter network, special education, or an early learning center.</li>
    <li><strong>Check your state's rules.</strong> If you lack 2 years of college or an associate degree, find out whether your district accepts the ParaPro or its own assessment.</li>
    <li><strong>Search every title:</strong> paraprofessional, paraeducator, teacher assistant, instructional aide, special education aide and assistant teacher.</li>
    <li><strong>Build your resume</strong> around work with children: tutoring, child care, coaching, camp, church or youth programs, and any special needs experience.</li>
    <li><strong>Get CPR and first aid certification</strong> if the postings near you ask for it.</li>
    <li><strong>Prepare for the interview.</strong> Expect questions on following a teacher's plan, handling behavior, working with students with disabilities and keeping student information private.</li>
</ol>

<h2>From Teacher Assistant to Teacher</h2>

<p>Many teacher assistants go on to teach. The BLS lists kindergarten and elementary, middle school, high school and special education teachers among similar occupations, but becoming one takes a bachelor's degree and a state teaching license. Working as a paraprofessional while you study gives you classroom experience that teacher preparation programs and principals value. Our <a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> guide explains the licensing route.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is another name for an educational support worker?</h3>
<p>The BLS says teacher assistants are also called teacher aides, instructional aides, paraprofessionals, education assistants and paraeducators.</p>

<h3>How much do teacher assistants make in the USA?</h3>
<p>The median annual wage was $36,780 in May 2025. The lowest 10% earned less than $27,150 and the highest 10% more than $50,040.</p>

<h3>Do I need a degree to be a paraprofessional?</h3>
<p>No bachelor's degree is needed. Public schools generally want 2 years of college or an associate degree, and Title I programs also accept a passed state or local assessment, plus a high school diploma.</p>

<h3>What is the ParaPro test?</h3>
<p>An ETS assessment with 90 selected-response questions in reading, math and writing, lasting 150 minutes. It is one way to meet the Title I assessment option where your state or district accepts it.</p>

<h3>Is there demand for teacher assistants?</h3>
<p>Yes. Employment is projected to stay flat from 2025 to 2035, but about 176,200 openings are expected each year as workers leave or change jobs.</p>

<h3>Are teacher assistant jobs part-time?</h3>
<p>Most are full time, but part-time work is common, and many teacher assistants do not work in the summer.</p>

<h3>Do special education paraprofessionals need extra qualifications?</h3>
<p>Often. The BLS says most states require teacher assistants who work with special-needs students to pass a skills test.</p>

<h3>Where can I apply for educational support jobs?</h3>
<p>Start with your local school district's job board, then check charter networks such as KIPP and early learning centers such as KinderCare, Primrose and The Goddard School.</p>

<h2>People Also Search For</h2>

<h3>Paraprofessional jobs near me</h3>
<p>Check your school district's job board first; local schools employ 72% of teacher assistants.</p>

<h3>Teacher assistant salary</h3>
<p>$36,780 median in May 2025.</p>

<h3>Paraprofessional requirements</h3>
<p>A high school diploma plus 2 years of college, an associate degree or a passed assessment.</p>

<h3>ParaPro assessment</h3>
<p>90 questions in reading, math and writing, 150 minutes.</p>

<h3>Special education aide jobs</h3>
<p>Most states require a skills test for special-needs teacher assistants.</p>

<h3>Instructional aide jobs</h3>
<p>The same work as a paraprofessional under a different title.</p>

<h3>Teacher assistant job outlook</h3>
<p>0% growth, but 176,200 openings a year to 2035.</p>

<h3>Assistant teacher jobs at daycare</h3>
<p>Child day care services pay a $34,920 median.</p>

<h2>More Job Guides</h2>

<p>Looking at other education jobs? These cover it:</p>

<ul>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the licensing route from assistant to teacher.</li>
    <li><a href="/blog/tutor-jobs-in-usa">Tutor Jobs in USA</a> &mdash; tutoring companies, pay and requirements.</li>
    <li><a href="/blog/preschool-teacher-jobs-in-canada">Preschool Teacher Jobs in Canada</a> &mdash; early childhood work in Canada.</li>
    <li><a href="/blog/education-assistant-jobs-in-australia">Education Assistant Jobs in Australia</a> &mdash; the same role in Australian schools.</li>
    <li><a href="/blog/school-administrator-jobs-in-uk">School Administrator Jobs in UK</a> &mdash; school office work in the UK.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Pay, job postings and paraprofessional requirements change and differ by state, district and employer. Confirm the current requirements with your state education agency and the employer before applying.</p>
HTML;
    }
}
