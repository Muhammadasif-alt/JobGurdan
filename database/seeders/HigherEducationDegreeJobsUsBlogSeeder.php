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
 * "Higher Education Degree Jobs in the US" — the non-faculty side of a
 * university. The faculty hub and the three professor guides own teaching
 * and research; this page owns administration, advising, admissions,
 * student affairs and institutional research, which is where most people
 * with a higher education degree actually work.
 *
 * Corrections to the draft:
 *
 * 1. The draft lists job titles with no pay anywhere. BLS puts
 *    postsecondary education administrators at a $104,590 median across
 *    231,800 jobs, school and career counsellors and advisers at $64,330,
 *    instructional coordinators at $77,440 and librarians at $68,270. A
 *    reader cannot choose a path without those numbers.
 *
 * 2. It implies these are growth careers. They are not, uniformly:
 *    administrators are projected to grow 2 per cent to 2035 and
 *    instructional coordinators 2 per cent, both slower than average,
 *    against 7 per cent for postsecondary teaching.
 *
 * 3. It says degree requirements "vary by institution" throughout without
 *    naming the one that recurs: BLS lists a master's degree as the typical
 *    entry-level education for administrators, counsellors and advisers,
 *    instructional coordinators and librarians alike.
 *
 * 4. It treats "academic advisor" as a distinct occupation. There is no
 *    separate BLS profile; the data sits under school and career counsellors
 *    and advisers, which is why advertised pay for the title looks
 *    inconsistent.
 *
 * 5. It never says that university support pay is lower inside a college
 *    than the occupational median suggests for some of these roles, which
 *    is visible in the BLS industry breakdowns.
 *
 * 6. It offers no openings figures. Administrators turn over roughly 14,500
 *    posts a year even while net employment barely moves, which is the
 *    actual shape of the opportunity.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class HigherEducationDegreeJobsUsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.usa.gov/job-search';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Guides on qualifications, pay, progression and how to get hired.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Higher Education Degree Jobs in the US';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Most people with a higher education degree never teach. Administration pays a $104,590 median across 231,800 jobs, advising $64,330 and instructional design $77,440 - and the growth rates are nothing like the teaching side.',
                'content' => $content,
                'featured_image' => 'blogs/higher-education-degree-jobs-in-the-us.jpg',
                'tags' => 'higher education degree jobs, university administration jobs, student affairs jobs, academic advisor jobs, college admissions jobs, institutional research careers, instructional coordinator salary, higher education careers usa',
                'meta_title' => 'Higher Education Degree Jobs in the US: Roles and Pay',
                'meta_description' => 'Higher education degree jobs in the US: a $104,590 administrator median, advising at $64,330, what each role needs and how fast each is growing.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );

        $this->seedJob();
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Colleges & Universities — Administration, Advising and Student Services (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-higher-ed-admin-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'University Administration, Advising and Student Affairs Posts at US Colleges',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Twelve-month appointments for most administrative posts, with peak periods around admissions cycles and term start',
                'language' => 'English',
                // These are several BLS occupations with different medians,
                // so each one is quoted separately in the description rather
                // than collapsed into a single band here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Administration, academic advising, admissions, student affairs, institutional research and library posts at US colleges and universities.',
                'seo_keywords' => 'higher education jobs usa, university administration jobs, student affairs jobs, academic advisor jobs, college admissions jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Most staff at a US college never teach. Administration, advising, admissions, student affairs, institutional research and library services are all career tracks inside the institution, and several of them pay above the teaching median.</p>

<h3>The roles and what they pay</h3>
<ul>
    <li><strong>Postsecondary education administrators</strong> &mdash; deans, registrars, admissions and student affairs directors. BLS median $104,590 across 231,800 jobs, master's typical</li>
    <li><strong>Instructional coordinators</strong> &mdash; curriculum and instructional design. $77,440 across 248,700 jobs</li>
    <li><strong>Librarians and media collections specialists</strong> &mdash; $68,270; academic libraries pay $74,490 at state institutions</li>
    <li><strong>School and career counsellors and advisers</strong>, the category that holds academic advising &mdash; $64,330, but $58,870 inside public colleges</li>
    <li><strong>Archivists, curators and museum workers</strong> &mdash; $60,330 across 38,500 jobs</li>
</ul>

<h3>What the institution looks for</h3>
<ul>
    <li>A master's degree is the typical entry-level education for all four of the main categories above</li>
    <li>Named experience with student information systems, CRM and learning management systems</li>
    <li>Outcomes with numbers: caseload size, retention or yield movement, programmes delivered</li>
    <li>A resume, not an academic CV, for administrative and student services posts</li>
</ul>

<h3>How the market is shaped</h3>
<p>Administration is projected to grow just 2 per cent from 2025 to 2035, an increase of 4,100 jobs, against 7 per cent for postsecondary teaching. <strong>The opportunity is turnover rather than expansion</strong>: about 14,500 openings a year.</p>

<p>Vacancies are published on the institution's own HR system first, and public systems often run a single state-wide portal. Pay and projection data is from the US Bureau of Labor Statistics for May 2025 and 2025 to 2035, not by JobGader.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>A university is a large employer, and most of its staff never stand in front of a class. Admissions, advising, student affairs, institutional research, library services and academic administration are all careers inside higher education, they are all open to someone with a relevant degree, and almost none of the guidance written about them carries a single pay figure. This page does. Every number below is from the US Bureau of Labor Statistics for May 2025, with the projections running to 2035.</p>

<h2>What These Jobs Pay, and How Fast They Are Growing</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Occupation</th>
            <th style="padding:10px;text-align:left;">Median pay</th>
            <th style="padding:10px;text-align:left;">Jobs, 2025</th>
            <th style="padding:10px;text-align:left;">Growth to 2035</th>
            <th style="padding:10px;text-align:left;">Typical entry</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Postsecondary education administrators</strong></td><td style="padding:10px;">$104,590</td><td style="padding:10px;">231,800</td><td style="padding:10px;">2%, +4,100</td><td style="padding:10px;">Master's degree</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Instructional coordinators</strong></td><td style="padding:10px;">$77,440</td><td style="padding:10px;">248,700</td><td style="padding:10px;">2%, +4,100</td><td style="padding:10px;">Master's degree</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Librarians and media collections specialists</strong></td><td style="padding:10px;">$68,270</td><td style="padding:10px;">142,300</td><td style="padding:10px;">3%</td><td style="padding:10px;">Master's degree</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>School and career counsellors and advisers</strong></td><td style="padding:10px;">$64,330</td><td style="padding:10px;">389,500</td><td style="padding:10px;">3%, +11,400</td><td style="padding:10px;">Master's degree</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Archivists, curators and museum workers</strong></td><td style="padding:10px;">$60,330</td><td style="padding:10px;">38,500</td><td style="padding:10px;">4%</td><td style="padding:10px;">Varies</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Postsecondary teachers, for comparison</td><td style="padding:10px;">$85,330</td><td style="padding:10px;">1,378,200</td><td style="padding:10px;">7%, +98,200</td><td style="padding:10px;">Varies</td></tr>
    </tbody>
</table>
</div>

<p>Two things stand out. First, <strong>the administrative side out-earns the teaching median</strong>: $104,590 against $85,330. Second, it is growing far more slowly &mdash; 2 per cent against 7 per cent. The opportunity is in turnover rather than expansion: BLS projects about <strong>14,500 openings a year</strong> for postsecondary education administrators even while net employment barely moves.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/higher-education-degree-jobs-in-the-us-campus.jpg"
         alt="A student sitting with a laptop and books outside a US university building, with a US flag, a graduating student and a city skyline in an inset collage"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Administration: the Best-Paid Route In</h2>

<p>"Postsecondary education administrator" is the BLS category covering deans, registrars, admissions directors, student affairs directors, department administrators and provost-office staff. Within it, the institution again sets the pay:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Employer</th>
            <th style="padding:10px;text-align:left;">Median pay, May 2025</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Colleges, universities and professional schools, state</td><td style="padding:10px;">$107,540</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Junior colleges, local</td><td style="padding:10px;">$105,270</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Colleges, universities and professional schools, private</td><td style="padding:10px;">$102,790</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Junior colleges, state</td><td style="padding:10px;">$98,840</td></tr>
    </tbody>
</table>
</div>

<p>Notice how narrow that band is. On the teaching side the same four employer types span $68,160 to $96,120; on the administrative side they span $98,840 to $107,540. <strong>Administration pays more consistently across institution types than teaching does</strong>, which matters if you are tied to one region and cannot choose your employer freely.</p>

<p>BLS lists a master's degree as the typical entry-level education, with less than five years of related work experience and no on-the-job training. In practice the common route is three to six years in a specialist function &mdash; admissions, advising, financial aid, residence life &mdash; before a director-level post.</p>

<h2>Academic Advising: the Title That Confuses the Data</h2>

<p>There is no separate BLS occupation called "academic advisor". The data sits inside <strong>school and career counsellors and advisers</strong>, median $64,330 across 389,500 jobs, growing 3 per cent with about 11,400 jobs added by 2035. Typical entry-level education is a master's degree.</p>

<p>Inside higher education specifically the medians are lower than the headline: <strong>$58,870</strong> at state and local colleges, universities, junior colleges and professional schools, and <strong>$58,720</strong> at private ones. That gap is worth knowing before you accept a salary that looks below the occupational median &mdash; inside a university, it may not be.</p>

<p>Advising is also the most common entry point into higher education administration, because it builds exactly the record a director-level search is looking for: student caseloads, retention outcomes, policy knowledge and systems experience.</p>

<h2>Instructional Design and Academic Programme Work</h2>

<p><strong>Instructional coordinators</strong> &mdash; the BLS category covering curriculum specialists, instructional designers and academic programme developers &mdash; have a median of <strong>$77,440</strong> across 248,700 jobs, with 2 per cent growth and a master's degree as typical entry. Within higher education, private colleges and universities pay a median of $73,070 and state ones $61,810.</p>

<p>This is the function that grew fastest when online teaching expanded, and it is the one where skills transfer most easily out of higher education, into corporate learning and development or edtech, if the academic job market disappoints.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/higher-education-degree-jobs-in-the-us-advising.jpg"
         alt="A university staff member helping three students at a laptop on campus, with a graduation photograph, a college building and digital icons in a collage"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Library, Archives and Collections</h2>

<ul>
    <li><strong>Librarians and media collections specialists</strong> &mdash; median $68,270 across 142,300 jobs, 3 per cent growth, master's degree typical. Academic libraries pay above the occupational median: $74,490 at state colleges and universities and $73,700 at private ones.</li>
    <li><strong>Archivists</strong> &mdash; median $64,550. <strong>Curators</strong> &mdash; $63,420. <strong>Museum technicians and conservators</strong> &mdash; $51,440. The combined category sits at $60,330 across 38,500 jobs, growing 4 per cent.</li>
</ul>

<p>These are small occupations, so vacancies are infrequent and competition for each is heavy. If this is the target, geographic flexibility matters more than in any other route on this page.</p>

<h2>Institutional Research, Admissions and Student Affairs</h2>

<p>Three functions that BLS does not publish separately, but which sit inside the administrator category and recruit continuously:</p>

<ul>
    <li><strong>Institutional research.</strong> Enrolment modelling, federal and state reporting, accreditation evidence, survey work. The most quantitative role in a university that is not a faculty post, and the one where SQL and statistical software are genuinely decisive.</li>
    <li><strong>Admissions and enrolment management.</strong> Recruitment, application review, yield and financial aid strategy. Entry-level admissions counsellor roles are among the easiest ways into a university for a recent graduate, with heavy autumn travel.</li>
    <li><strong>Student affairs.</strong> Residence life, student conduct, orientation, activities, accessibility services, counselling support. Many of these posts prefer a master's in higher education or student affairs, and residence life roles often include accommodation as part of the package.</li>
</ul>

<h2>How to Get Hired Inside a University</h2>

<ol>
    <li><strong>Pick the function before the institution.</strong> Advising, admissions, institutional research and student affairs hire on very different evidence; a generic "higher education" application reads as unfocused to all four.</li>
    <li><strong>Apply through the institution's own HR system.</strong> Universities post there first, and public systems often run one state-wide portal covering every campus.</li>
    <li><strong>Use a resume, not an academic CV</strong>, for administrative and student services posts. The academic CV belongs to faculty applications.</li>
    <li><strong>Name the systems you have used.</strong> Student information systems, CRM, learning management systems and reporting tools are screened for explicitly.</li>
    <li><strong>Lead with outcomes.</strong> Caseload size, retention or yield movement, programmes launched, reports delivered. Higher education hiring committees read numbers.</li>
    <li><strong>Start one rung lower than you want.</strong> Coordinator and adviser posts are the normal entry, and internal moves are far easier than external ones once you are in.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What jobs can you get with a higher education degree?</h3>
<p>University and college administration, academic advising, admissions and enrolment management, student affairs, institutional research, instructional design, library and archive work, and teaching. Most of these are non-faculty roles.</p>

<h3>How much do university administrators earn in the US?</h3>
<p>A median of $104,590 in May 2025 across 231,800 jobs, from $98,840 at state junior colleges to $107,540 at state colleges and universities.</p>

<h3>Can you work at a university without being a professor?</h3>
<p>Yes, and most staff do. Administration, advising, admissions, student affairs, institutional research, library services, IT, finance and communications are all career tracks inside the institution.</p>

<h3>Do higher education jobs require a master's degree?</h3>
<p>BLS lists a master's as the typical entry-level education for postsecondary education administrators, instructional coordinators, librarians, and school and career counsellors and advisers. Entry-level coordinator and admissions posts often accept a bachelor's.</p>

<h3>How much does an academic advisor earn?</h3>
<p>There is no separate BLS category. The occupation is school and career counsellors and advisers, median $64,330; inside colleges and universities the medians are $58,870 at public institutions and $58,720 at private ones.</p>

<h3>Are higher education administration jobs growing?</h3>
<p>Slowly. Employment is projected to grow 2 per cent from 2025 to 2035, an increase of 4,100 jobs, against 7 per cent for postsecondary teachers. The opportunity is in the roughly 14,500 openings a year from turnover.</p>

<h3>What does an instructional coordinator earn in higher education?</h3>
<p>The occupational median is $77,440. Within higher education, private colleges and universities pay a median of $73,070 and state ones $61,810.</p>

<h3>Which higher education job pays best?</h3>
<p>Administration, at a $104,590 median, which is above the $85,330 median for postsecondary teachers and well above advising at $64,330.</p>

<h2>People Also Search For</h2>

<h3>University administration jobs</h3>
<p>$104,590 median, master's typical, about 14,500 openings a year from turnover.</p>

<h3>Student affairs jobs</h3>
<p>Residence life, conduct, orientation and accessibility; many prefer a student affairs master's.</p>

<h3>Academic advisor jobs</h3>
<p>Counted under school and career counsellors and advisers; $58,870 median inside public colleges.</p>

<h3>College admissions jobs</h3>
<p>Admissions counsellor is one of the easiest entry points, with heavy autumn recruitment travel.</p>

<h3>Institutional research careers</h3>
<p>Enrolment modelling, federal reporting and accreditation evidence; the most quantitative non-faculty role.</p>

<h3>Instructional coordinator salary</h3>
<p>$77,440 overall; $73,070 at private colleges and $61,810 at state ones.</p>

<h3>Academic librarian salary</h3>
<p>$74,490 at state colleges and universities, above the $68,270 occupational median.</p>

<h3>Higher education careers USA</h3>
<p>Administration out-earns teaching but grows at 2 per cent against 7 per cent.</p>

<h2>More Job Guides</h2>

<p>These cover the teaching side of the same institutions:</p>

<ul>
    <li><a href="/blog/faculty-careers-in-the-us">Faculty Careers in the US</a> &mdash; every academic appointment type compared, with the BLS data behind each.</li>
    <li><a href="/blog/professor-employment-in-the-us">Professor Employment in the US</a> &mdash; the nine-month contract, teaching loads and promotion.</li>
    <li><a href="/blog/assistant-professor-jobs-in-the-us">Assistant Professor Jobs in the US</a> &mdash; the hiring calendar, the job talk and the offer.</li>
    <li><a href="/blog/colleges-hiring-professors-in-the-us">Colleges Hiring Professors in the US</a> &mdash; which institutions hire on which credential.</li>
    <li><a href="/blog/adjunct-teaching-opportunities">Adjunct Teaching Opportunities</a> &mdash; per-section pay and benefits eligibility.</li>
    <li><a href="/blog/educational-support-jobs-in-usa">Educational Support Jobs in USA</a> &mdash; school-level support roles and what they pay.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the school route and state certification.</li>
    <li><a href="/blog/tutor-jobs-in-usa">Tutor Jobs in USA</a> &mdash; teaching work that needs no institutional appointment.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not career, legal or employment advice. Pay and projection data is from the US Bureau of Labor Statistics Occupational Outlook Handbook, with wages for May 2025 and projections for 2025 to 2035. Individual institutions set their own job titles, salary bands and qualification requirements.</p>
HTML;
    }
}
