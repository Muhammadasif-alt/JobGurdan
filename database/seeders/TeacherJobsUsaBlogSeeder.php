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
 * "Teacher Jobs in USA" — public, charter and private school teaching. The
 * Pakistan teacher guide covers the home market, and this one owns American
 * teacher pay, state rankings, licensing and the visa routes for teachers.
 *
 * Corrections to the draft:
 *
 * 1. Its experienced teacher band of $50,000 to $65,000 sits below what
 *    teachers earn. The BLS put the May 2025 median at $63,970 for elementary
 *    and $72,040 for high school teachers, and the NEA average for 2024-25
 *    was $74,495.
 *
 * 2. Its top hiring states include Arizona, which ranks 23rd for elementary
 *    teacher jobs and 41st for their pay; Illinois is fifth. Its high-paying
 *    states name Massachusetts ahead of Washington, which the NEA and BLS both
 *    put in the top three.
 *
 * 3. It describes growing demand. The BLS projects little or no change in
 *    teacher employment from 2025 to 2035 as enrollment falls; the openings
 *    come from replacing teachers who leave.
 *
 * 4. It says certification means exams "like Praxis". California, Texas,
 *    Florida and New York use their own exams for most certificates. It also
 *    lists school counselors as a teaching job; they need a master's degree.
 *
 * 5. It says nothing about visas, and its poster promised visa support. The
 *    J-1 Teacher program needs two years of experience and lasts three years,
 *    and H-1B petitions face a weighted lottery and a $100,000 payment that
 *    is tied up in court.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TeacherJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-teacher-jobs.html';

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
        $title = 'Teacher Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'American teachers averaged $74,495 in 2024-25, Arizona is not a top hiring state, the BLS projects no growth in teacher jobs to 2035, and the J-1 and H-1B routes for foreign teachers are narrower than posters suggest.',
                'content' => $content,
                'featured_image' => 'blogs/teacher-jobs-in-usa.jpg',
                'tags' => 'teacher jobs in usa, teacher salary usa, teaching jobs in america for foreigners, j-1 teacher visa, h-1b teacher, teacher certification by state, highest paying states for teachers, special education teacher jobs, substitute teacher jobs',
                'meta_title' => 'Teacher Jobs in USA 2026: Pay by State, Licenses and Visas',
                'meta_description' => 'Teacher jobs in the USA: 2026 pay by state, why Arizona is not a top hiring state, how certification works, and the J-1 and H-1B routes for foreign teachers.',
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
            ['name' => 'US Public, Charter & Private Schools (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-teacher-aggregated']
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
                'position' => 'Teacher — Elementary, Middle and High Schools, US School Districts',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'School hours on the district calendar, with planning, grading and parent meetings outside them',
                'language' => 'English',
                // Teacher pay is set district by district on salary schedules
                // that differ by state, degree and years of service, so no
                // single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Elementary, middle and high school teaching roles across US public, charter and private schools. State certification required for most public school posts.',
                'seo_keywords' => 'teacher jobs usa, elementary teacher jobs, high school teacher jobs, special education teacher jobs, substitute teacher jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Public school districts, charter schools and private schools across the United States hire teachers for elementary, middle and high school classrooms, with most hiring for the school year that starts in August or September.</p>

<h3>What the work involves</h3>
<p>Planning and teaching lessons to state standards, assessing and grading students, managing a classroom, adapting work for students with disabilities or who are learning English, and meeting parents.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>A bachelor's degree</strong>, usually in education or the subject you teach</li>
    <li><strong>A state teaching certificate or license</strong> for public schools, which means passing the state's own tests and a background check</li>
    <li>For foreign-trained teachers, a credential evaluation and the right to work in the US, often through the J-1 Teacher program</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>National picture.</strong> The BLS put the May 2025 median at $63,970 for elementary teachers and $72,040 for high school teachers</li>
    <li><strong>Salary schedules.</strong> Districts pay by years of service and qualifications, so a master's degree usually moves you up the scale</li>
    <li><strong>By state.</strong> Average pay ranges from over $100,000 in California to under $57,000 in Mississippi, Florida and Louisiana</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the state before the school.</strong> Your certificate is issued by one state, and moving to another usually means applying for that state's license.</p>

<p><strong>Note:</strong> pay, certification rules and visa eligibility are set by school districts, state education departments and US immigration law &mdash; not by JobGader. Confirm the details with the district and the state before applying, and never pay a recruiter for a teaching job offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>American schools employ millions of teachers and hire every year, and the job comes with a pension, a salary schedule and summers structured around the school calendar. Before you apply, it helps to know four things most guides get wrong: what teachers really earn, which states hire the most, why demand is not growing, and how narrow the visa routes are for teachers trained abroad.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-teacher-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127822; Browse Teacher Jobs in USA &rarr;
    </a>
</div>

<h2>Teachers Earn More Than the Guides Say</h2>

<p>Guides put experienced teachers at <strong>$50,000 to $65,000</strong> and senior teachers at $65,000 to $80,000. The official figures are higher:</p>

<ul>
    <li><strong>Elementary school teachers:</strong> a median of <strong>$63,970</strong> in May 2025 and a mean of $72,650, according to the Bureau of Labor Statistics. The top 10% earned more than $104,340.</li>
    <li><strong>Middle school teachers:</strong> a median of $64,370.</li>
    <li><strong>High school teachers:</strong> a median of <strong>$72,040</strong> and a mean of $76,320.</li>
    <li><strong>Special education teachers:</strong> a median of $67,170, and $74,260 for secondary special education.</li>
    <li><strong>The national average</strong> across all public school teachers was <strong>$74,495</strong> in 2024-25, a 3.5% rise, according to the National Education Association, which estimates <strong>$76,552</strong> for 2025-26.</li>
</ul>

<p>A median is the middle of the whole workforce, so a typical teacher with several years in the job is already above the range guides give for experienced teachers.</p>

<p>Starting pay is where the guides are closest. The NEA found the average starting salary rose 3.4% to <strong>$48,112</strong> in 2024-25. Only <strong>35%</strong> of districts start teachers on $50,000 or more, while 91% start them on at least $40,000.</p>

<p><strong>Substitute teachers</strong> are paid far less. The BLS median for short-term substitutes was <strong>$41,670</strong> a year, and the bottom 10% earned under $28,560, because most are paid by the day and only for the days they work.</p>

<h2>Which States Pay the Most &mdash; and Which Hire the Most</h2>

<p>Guides name California, New York and Massachusetts as the best-paying states. The NEA's state averages for 2024-25 put <strong>California ($103,552)</strong>, <strong>New York ($98,655)</strong> and <strong>Washington ($96,589)</strong> at the top, and Mississippi ($54,975), Florida ($56,663) and Louisiana ($56,785) at the bottom. The BLS mean for elementary teachers is highest in <strong>Washington ($97,970)</strong>, ahead of California, the District of Columbia, New York and Massachusetts.</p>

<p>Guides also name California, Texas, New York, Florida and Arizona as the top hiring states. BLS employment counts for elementary teachers in May 2025 show a different fifth state:</p>

<ul>
    <li><strong>California:</strong> 155,160 elementary teachers</li>
    <li><strong>Texas:</strong> 116,260</li>
    <li><strong>New York:</strong> 101,410</li>
    <li><strong>Florida:</strong> 79,090</li>
    <li><strong>Illinois:</strong> 61,520</li>
</ul>

<p><strong>Arizona ranks 23rd</strong>, with 24,240. For high school teachers, Texas employs the most, followed by California, New York, Pennsylvania and Florida, with Arizona 16th.</p>

<p>Pay and headcount pull in different directions. Arizona's elementary teachers had the <strong>41st highest mean wage</strong> at $58,920, Florida's the 38th at $59,530, and Texas's the 32nd at $61,890. A shortage often means a state that pays poorly, so compare the salary schedule with local living costs before you count a signing bonus as a reason to move.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teacher-jobs-in-usa-students.jpg"
         alt="A teacher helping elementary students with their work at a classroom table"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Demand Is Steady, Not Growing</h2>

<p>Guides describe rising demand and growing student populations. The BLS projects <strong>little or no change</strong> in teacher employment from 2025 to 2035:</p>

<ul>
    <li><strong>Kindergarten and elementary teachers:</strong> 0%, a loss of about 6,100 jobs, but around <strong>99,400 openings a year</strong>.</li>
    <li><strong>High school teachers:</strong> 0%, with around 63,500 openings a year.</li>
    <li><strong>Special education teachers:</strong> 0%, with around 37,100 openings a year.</li>
</ul>

<p>The BLS gives the reasons plainly: public school enrollment is expected to fall, charter schools, private schools and homeschooling give families alternatives, and hiring depends on state and local budgets. Most openings come from <strong>replacing teachers who retire or leave</strong>. So jobs are there every year, but they are concentrated in particular subjects, schools and districts rather than spread evenly.</p>

<h2>Where the Shortages Really Are</h2>

<p>Shortages are real, but they sit in particular subjects. The US Department of Education publishes the teacher shortage areas each state reports, and for 2026-27 the most common are:</p>

<ul>
    <li><strong>Special education:</strong> reported by 40 states, counting the District of Columbia</li>
    <li><strong>Mathematics:</strong> 35</li>
    <li><strong>Science:</strong> 35</li>
    <li><strong>Language arts:</strong> 29</li>
    <li><strong>English as a second language:</strong> 27</li>
    <li><strong>Career and technical education:</strong> 25</li>
</ul>

<p>If you can teach special education, math, science or English learners, you are applying into the gaps rather than competing for the jobs every graduate wants.</p>

<h2>How to Become a Teacher in the US</h2>

<p>Every state sets its own rules, but the BLS summarises the common path:</p>

<ul>
    <li><strong>A bachelor's degree.</strong> Public school teachers usually need one, typically in education or in the subject they teach.</li>
    <li><strong>A state certificate or license.</strong> Public school teachers must hold one, which normally means an approved teacher preparation program, student teaching, a background check and the state's tests.</li>
    <li><strong>A master's degree later, in some states.</strong> Some states require teachers to complete a master's degree after they are certified and hired.</li>
    <li><strong>Alternative routes.</strong> Graduates without education coursework can often teach through an alternative route to certification while they train.</li>
    <li><strong>Private schools.</strong> Teachers typically do not need a state license, though schools still expect a degree.</li>
</ul>

<p><strong>Praxis is not the test everywhere.</strong> Guides say certification typically means passing exams like Praxis. ETS lists Praxis requirements for 44 states, but several of the largest hiring states use their own exams for most teaching certificates: <strong>California</strong> uses the CBEST and CSET, <strong>Texas</strong> the TExES, <strong>Florida</strong> the FTCE and <strong>New York</strong> the NYSTCE. Arizona has its own AEPA and NES exams. Check your state's exam before you book one.</p>

<p><strong>New York shows how the master's rule works.</strong> Its Initial Certificate is valid for five years, and within them you must complete three years of experience and a master's degree to move to the Professional Certificate.</p>

<p><strong>School counselors and instructional coordinators are not entry-level teaching jobs.</strong> Guides list them alongside classroom roles, but the BLS lists a <strong>master's degree</strong> as the typical entry requirement for school counselors, who also need a state credential. Counselors earned a median of $64,330 and instructional coordinators $77,440 in May 2025.</p>

<h2>Can a Foreign Teacher Get a US Visa?</h2>

<p>Guides say only that foreign credentials "often require additional steps". The larger question is the visa, and neither main route is easy.</p>

<h3>The J-1 Teacher program</h3>

<ul>
    <li><strong>Who qualifies:</strong> teachers with a degree equivalent to a US bachelor's in education or their subject, at least <strong>two years (24 months)</strong> of teaching or related experience, and sufficient English. You must also meet the standards of the state where you will teach.</li>
    <li><strong>How long:</strong> an exchange of <strong>three years</strong>, which the sponsor can extend by one or two years.</li>
    <li><strong>What it is not:</strong> an immigration route. Before you can take part again, you must have lived outside the United States for two years.</li>
</ul>

<h3>The H-1B visa</h3>

<ul>
    <li><strong>The lottery.</strong> School districts are not higher education institutions, so their H-1B petitions are normally subject to the annual cap. From the FY 2027 season, USCIS weights the lottery by wage level, giving a level IV salary four entries and a level I salary one &mdash; which works against new teachers on entry-level pay.</li>
    <li><strong>The $100,000 payment.</strong> A presidential proclamation required a $100,000 payment with new H-1B petitions filed from 21 September 2025. In June 2026 a federal court in Massachusetts vacated USCIS's guidance implementing it for certain petitions, and on 24 July 2026 the appeals court refused to pause that order. DHS says it will comply while it considers next steps, and still plans to collect the payment if the order is lifted. Check the current USCIS guidance before relying on either outcome.</li>
</ul>

<p><strong>Your degree is evaluated first.</strong> California requires everyone prepared outside the United States to have their transcripts evaluated by a Commission-approved agency, and New York accepts evaluations from members of NACES or the Association of International Credential Evaluators. Budget for the evaluation before you apply for a license or a J-1 placement.</p>

<p>A recruiter who promises a teaching job with "visa support" for a fee is a warning sign. Legitimate J-1 sponsors are designated by the State Department, and H-1B costs fall on the employer.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teacher-jobs-in-usa-classroom.jpg"
         alt="A smiling teacher working with a group of students in a classroom with a world map and a US flag"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Loan Forgiveness for Teachers</h2>

<ul>
    <li><strong>Teacher Loan Forgiveness</strong> cancels up to <strong>$17,500</strong> of federal student loans for highly qualified secondary math or science teachers and special education teachers, and up to $5,000 for other eligible teachers, after <strong>five consecutive complete academic years</strong> of full-time teaching at a school serving low-income families.</li>
    <li><strong>Public Service Loan Forgiveness</strong> cancels the remaining balance after the equivalent of <strong>120 monthly payments</strong> while working full time for a qualifying employer, which includes public schools. Since 1 July 2026, payments under the new Repayment Assistance Plan count towards it.</li>
</ul>

<p>Both apply to US federal student loans, so they help teachers who studied in the US rather than teachers arriving on a J-1 visa.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do teachers earn in the USA in 2026?</h3>
<p>The NEA put the average public school teacher salary at $74,495 in 2024-25, and the BLS put the May 2025 median at $63,970 for elementary and $72,040 for high school teachers. Average starting pay was $48,112.</p>

<h3>Which state pays teachers the most?</h3>
<p>California, with an NEA average of $103,552 in 2024-25, followed by New York at $98,655 and Washington at $96,589. Washington has the highest BLS mean for elementary teachers, at $97,970.</p>

<h3>Which states hire the most teachers?</h3>
<p>California, Texas, New York, Florida and Illinois employ the most elementary teachers. Arizona ranks 23rd, and Texas employs the most high school teachers.</p>

<h3>Is there a teacher shortage in the USA?</h3>
<p>In specific subjects rather than everywhere. For 2026-27, states most often report shortages in special education, mathematics and science, while the BLS projects little or no change in overall teacher employment to 2035.</p>

<h3>Do I need a teaching certificate to teach in the USA?</h3>
<p>For public schools, yes: every state requires a certificate or license, usually after a bachelor's degree, a preparation program and state tests. Private school teachers typically do not need one.</p>

<h3>Can a foreign teacher get a US visa?</h3>
<p>Through the J-1 Teacher program, with a bachelor's-equivalent degree, two years of experience and English proficiency, for three years plus an extension. The H-1B route needs a lottery win and faces a $100,000 payment that is tied up in court.</p>

<h3>How much do substitute teachers make?</h3>
<p>The BLS median for short-term substitute teachers was $41,670 a year in May 2025. Most are paid a daily rate for the days they work.</p>

<h3>Do school counselors need a master's degree?</h3>
<p>Usually, yes. The BLS lists a master's degree as the typical entry requirement, and public school counselors also need a state credential.</p>

<h2>People Also Search For</h2>

<h3>Teacher salary by state</h3>
<p>From $103,552 in California to $54,975 in Mississippi, on the NEA's 2024-25 averages.</p>

<h3>Starting teacher salary</h3>
<p>$48,112 on average in 2024-25, from $64,640 in the District of Columbia to $36,682 in Montana, with 35% of districts starting at $50,000 or more.</p>

<h3>J-1 teacher visa requirements</h3>
<p>A bachelor's-equivalent degree, two years of teaching experience, English proficiency and meeting the host state's standards.</p>

<h3>Teaching jobs in USA for foreigners</h3>
<p>Mostly through J-1 exchange sponsors and districts willing to file H-1B petitions, after a credential evaluation and a state license.</p>

<h3>Special education teacher salary</h3>
<p>A BLS median of $67,170 in May 2025, and $74,260 for secondary special education teachers.</p>

<h3>Substitute teacher jobs</h3>
<p>A median of $41,670 a year, paid by the day. Each state sets its own substitute requirements.</p>

<h3>Praxis test states</h3>
<p>ETS lists 44 states, but California, Texas, Florida and New York use their own exams for most teaching certificates.</p>

<h3>Arizona teacher jobs</h3>
<p>Arizona ranks 23rd for elementary teacher jobs and 41st for their pay, with a mean of $58,920 in May 2025.</p>

<h2>More Job Guides</h2>

<p>Comparing teaching with other licensed and public service work? These cover it:</p>

<ul>
    <li><a href="/blog/teacher-jobs-in-pakistan">Teacher Jobs in Pakistan</a> &mdash; what replaced PTC and CT, and who really employs government school teachers.</li>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; another state-licensed profession, and what it pays by state.</li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the green card route for a licensed profession.</li>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; the other public service career run by states and cities.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the visa routes that do not need a degree.</li>
    <li><a href="/blog/government-jobs-in-pakistan">Government Jobs in Pakistan</a> &mdash; how grades, tests and advertisements work across the public sector.</li>
    <li><a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> &mdash; another public service career, paid on the federal General Schedule.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, careers or financial advice. Teacher pay, certification rules and immigration policy change and differ by state and district, and the H-1B payment is subject to ongoing litigation. Confirm the current position with the state education department, the school district, the State Department and USCIS before applying.</p>
HTML;
    }
}
