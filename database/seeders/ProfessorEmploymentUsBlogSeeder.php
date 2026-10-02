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
 * "Professor Employment in the US" — the employment relationship itself:
 * what the contract covers, how the week divides, what promotion is worth
 * and why a US professorship is not the same office as a British one. The
 * faculty hub owns the workforce overview and the rank averages table; the
 * assistant professor guide owns the search. This page owns the terms.
 *
 * Corrections to the draft:
 *
 * 1. The draft lists responsibilities without saying how the time divides.
 *    A standard research-university appointment is weighted roughly 40 per
 *    cent teaching, 40 per cent research and 20 per cent service, and the
 *    teaching load changes from two courses a semester to five across
 *    institution types. That is the difference between two jobs.
 *
 * 2. It never mentions that most US faculty contracts are nine-month. The
 *    summer is unpaid unless you teach it or buy it out of a grant, which
 *    changes what an advertised salary actually means.
 *
 * 3. It gives no pay figures. The AAUP 2025-26 averages are $163,836 for a
 *    professor, $113,427 for an associate and $97,232 for an assistant.
 *
 * 4. It says promotion "is not automatic" without saying what it is worth,
 *    or that promotion to full professor usually has no fixed clock at all.
 *
 * 5. It treats "professor" as one word with one meaning. In the United
 *    States it is a rank held by many people in a department; in Britain it
 *    is a senior title held by few, which is why the two systems' job
 *    adverts are not comparable.
 *
 * 6. It omits the single biggest structural fact: on AAUP figures only 50.7
 *    per cent of full-time faculty hold tenure and 31.4 per cent are in
 *    non-tenure-track appointments.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class ProfessorEmploymentUsBlogSeeder extends Seeder
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
        $title = 'Professor Employment in the US';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A US professorship is a nine-month contract, a teaching load that doubles between institution types, and a promotion ladder worth about $50,000 at the top step. What the appointment actually commits you to, rank by rank.',
                'content' => $content,
                'featured_image' => 'blogs/professor-employment-in-the-us.jpg',
                'tags' => 'professor employment us, professor jobs usa, university professor salary, associate professor promotion, full professor requirements, teaching load university, nine month faculty contract, non tenure track professor',
                'meta_title' => 'Professor Employment in the US: Pay, Load and Promotion',
                'meta_description' => 'Professor employment in the US: the nine-month contract, teaching loads by institution, AAUP pay by rank and what promotion is actually worth.',
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
            ['name' => 'US Universities & Four-Year Colleges — Professorial Ranks (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-professor-ranks-aggregated']
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
                'position' => 'Professor Posts at US Universities — Assistant, Associate and Full Professor Appointments',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Nine-month contract over two semesters for most appointments; summer pay comes from teaching or grant funding',
                'language' => 'English',
                // Rank, institution type and discipline each move the figure,
                // so the AAUP averages are quoted in the description rather
                // than presented as this listing's salary band.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Assistant, associate and full professor appointments at US universities and four-year colleges, with teaching, research and service responsibilities.',
                'seo_keywords' => 'professor jobs usa, university professor positions, associate professor jobs, full professor jobs, professor employment us',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US universities and four-year colleges appoint faculty at three professorial ranks. In the United States these are rungs of an ordinary career rather than senior honours, which is why a department can hold twenty professors at once.</p>

<h3>The ranks</h3>
<ul>
    <li><strong>Assistant professor.</strong> The entry rank, normally probationary with a tenure review</li>
    <li><strong>Associate professor.</strong> Usually granted with tenure, at the end of a probationary period of no more than seven years</li>
    <li><strong>Professor.</strong> The senior rank, promoted on the record with no fixed clock</li>
</ul>

<h3>The contract</h3>
<ul>
    <li>Most appointments are nine-month contracts covering the autumn and spring semesters</li>
    <li>Summer salary comes from teaching a summer course or from external grant funding</li>
    <li>Workload is conventionally 40 per cent teaching, 40 per cent research and 20 per cent service at a research university</li>
    <li>Teaching load runs from two courses a semester at doctoral institutions to five at community colleges</li>
</ul>

<h3>Pay, on AAUP 2025&ndash;26 averages</h3>
<ul>
    <li>Professor $163,836, associate professor $113,427, assistant professor $97,232</li>
    <li>Lecturer $84,292, instructor $74,087, all ranks combined $119,836</li>
    <li>Collected from 768 institutions reporting on 359,234 full-time faculty</li>
</ul>

<h3>Before you accept</h3>
<p><strong>Get the written workload assignment and the tenure criteria before signing.</strong> Among full-time faculty, 50.7 per cent hold tenure and 31.4 per cent are in non-tenure-track appointments. Pay data is from the AAUP and the US Bureau of Labor Statistics, not by JobGader.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>In the United States "professor" is a rank, not an honour. A mid-sized department can hold twenty of them. The word says almost nothing about what the job involves, because the same title covers a research appointment where you teach two courses a semester and a community college appointment where you teach five. This guide is about the employment terms underneath the title: what the contract buys, how the week divides, what each rank averages, and what promotion is actually worth.</p>

<h2>The Contract Is Usually Nine Months</h2>

<p>This is the detail that changes how every advertised salary should be read. Most US tenure-track and tenured appointments are <strong>nine-month contracts</strong>, covering the autumn and spring semesters. The salary is quoted for those nine months, even when payroll spreads it across twelve by election.</p>

<ul>
    <li><strong>Summer is not included.</strong> You earn it by teaching a summer course, by buying your time out of an external grant, or not at all.</li>
    <li><strong>Grant buy-out works in reverse.</strong> A funded project pays the institution to release you from part of your teaching, which is how research-heavy appointments protect research time.</li>
    <li><strong>Twelve-month appointments exist</strong> but are mostly administrative, clinical, or in professional schools that run year-round.</li>
    <li><strong>Course release is negotiable</strong> at offer stage and almost never afterwards.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/professor-employment-in-the-us-campus.jpg"
         alt="A professor with books and a shoulder bag outside a US university building, with a US flag and a collage of a lecture, a laboratory and a student group behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How the Week Divides, by Institution Type</h2>

<p>US appointments are usually described by a workload split. A research university assignment is conventionally written as 40 per cent teaching, 40 per cent research and 20 per cent service; a teaching institution moves most of that research share into the classroom. The teaching load follows:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Institution</th>
            <th style="padding:10px;text-align:left;">Typical load</th>
            <th style="padding:10px;text-align:left;">What decides your review</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Doctoral, research-intensive</td><td style="padding:10px;">2 courses a semester, often fewer</td><td style="padding:10px;">Publications, external grant income, doctoral supervision</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Master's institutions</td><td style="padding:10px;">3 courses a semester</td><td style="padding:10px;">Teaching plus a steady publication record</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Baccalaureate and liberal arts</td><td style="padding:10px;">3 courses a semester</td><td style="padding:10px;">Teaching, undergraduate research supervision, advising</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Community and junior colleges</td><td style="padding:10px;">5 courses a semester</td><td style="padding:10px;">Teaching, student outcomes, service. Research is usually not expected</td></tr>
    </tbody>
</table>
</div>

<p>Ask for the written workload assignment before accepting. "Teaching, research and service" in an advert is not a number, and the number is the job.</p>

<h2>What Each Rank Averages</h2>

<p>AAUP averages for full-time faculty, 2025&ndash;26, all institution types combined:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Rank</th>
            <th style="padding:10px;text-align:left;">Average salary</th>
            <th style="padding:10px;text-align:left;">Step up from the rank below</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Professor</td><td style="padding:10px;">$163,836</td><td style="padding:10px;">about $50,400</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Associate professor</td><td style="padding:10px;">$113,427</td><td style="padding:10px;">about $16,200</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Assistant professor</td><td style="padding:10px;">$97,232</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lecturer</td><td style="padding:10px;">$84,292</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Instructor</td><td style="padding:10px;">$74,087</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>All ranks</strong></td><td style="padding:10px;"><strong>$119,836</strong></td><td style="padding:10px;">&mdash;</td></tr>
    </tbody>
</table>
</div>

<p>These come from 768 institutions reporting on 359,234 full-time faculty. In the same year average salaries rose 2.3 per cent while the CPI-U rose 2.7 per cent, so in real terms pay fell about 0.4 per cent. For context, the BLS median across all postsecondary teachers, including part-time staff, was $85,330 in May 2025.</p>

<h2>Promotion: Two Very Different Steps</h2>

<p>The two promotions in a US academic career work nothing alike.</p>

<ul>
    <li><strong>Assistant to associate</strong> is normally decided together with tenure, on a fixed clock. The AAUP standard, which most institutions write into their handbooks, is a probationary period not exceeding <strong>seven years</strong>, with notice at least a year before it ends. It is worth roughly $16,000 on the averages above, and failing it ends the appointment rather than freezing it.</li>
    <li><strong>Associate to full professor</strong> usually has <strong>no clock at all</strong>. You apply when your department believes the record supports it, which for many people is six to ten years later and for some is never. It is worth around $50,000 &mdash; the largest single jump in the structure, and the one nobody schedules for you.</li>
</ul>

<p>Because the second step is unscheduled, associate professor is where US academic careers most often stall. If that rank matters to you, ask the department how many associates it has promoted in the last five years before you accept the first job.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/professor-employment-in-the-us-lecture.jpg"
         alt="A professor presenting a world map on a classroom screen to attentive students, with a US flag and a campus building in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Titles That Are Not Tenure-Track</h2>

<p>Many US appointments carry "professor" in the title and no tenure anywhere near them. Read the modifier, because it is doing all the work:</p>

<ul>
    <li><strong>Teaching professor</strong> or <strong>professor of practice</strong> &mdash; full-time, salaried, usually promotable within its own ladder, no tenure, heavier teaching.</li>
    <li><strong>Clinical professor</strong> &mdash; professional schools. A licence and current practice usually matter more than publications.</li>
    <li><strong>Research professor</strong> &mdash; funded by grants. The appointment lasts as long as the award.</li>
    <li><strong>Visiting professor</strong> &mdash; a defined term, often one year, frequently covering a sabbatical.</li>
    <li><strong>Adjunct professor</strong> &mdash; paid per course section, averaging $4,093 for a standard three-credit section, usually without benefits.</li>
</ul>

<p>Across full-time faculty, 50.7 per cent hold tenure, 18.0 per cent are on the tenure track and 31.4 per cent are in non-tenure-track appointments. Include part-time staff and the non-tenured share is far larger again.</p>

<h2>Why a US Professor Is Not a British Professor</h2>

<p>This trips up applicants moving in either direction. In the United States, assistant, associate and full professor are three rungs of one ordinary career, and most tenured faculty reach the top rung eventually. In the United Kingdom, "Professor" is a senior title conferred on a minority; the equivalent of a US assistant or associate professor is usually Lecturer or Senior Lecturer.</p>

<p>So a British advert for a Lecturer is not a junior teaching post, and a US advert for an Assistant Professor is not an assistant to a professor. Translate the rank before you judge the salary.</p>

<h2>Benefits and the Two Federal Multipliers</h2>

<ul>
    <li><strong>Health coverage.</strong> For the employer mandate, the IRS credits <strong>2.25 hours of service for each hour of classroom teaching</strong>, plus required duties, which is how part-time teaching can still reach a full-time threshold.</li>
    <li><strong>Loan forgiveness.</strong> The Department of Education counts <strong>3.35 hours of work for every credit hour taught</strong> when a public or non-profit institution certifies employment for Public Service Loan Forgiveness, so nine credit hours a week clears the full-time test.</li>
    <li><strong>Retirement.</strong> Public institutions usually offer a state system or an optional retirement plan; the employer contribution is a real part of the package and is rarely in the advert.</li>
    <li><strong>Sabbatical.</strong> Commonly one semester at full pay or a year at part pay after six years of service, but it is institution policy, not a right.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What does professor employment in the US actually involve?</h3>
<p>Teaching, research and institutional service in a proportion set by the institution. A research university assignment is conventionally 40 per cent teaching, 40 per cent research and 20 per cent service; a community college appointment is overwhelmingly teaching.</p>

<h3>How much does a US professor earn?</h3>
<p>On AAUP 2025&ndash;26 averages, $163,836 for a full professor, $113,427 for an associate professor and $97,232 for an assistant professor, with all ranks averaging $119,836.</p>

<h3>Is a US professor's salary for the whole year?</h3>
<p>Usually not. Most appointments are nine-month contracts covering two semesters. Summer pay comes from teaching a summer course or from grant funding, and is separate from the advertised salary.</p>

<h3>How many courses does a US professor teach?</h3>
<p>Roughly two a semester at research-intensive universities, three at master's and baccalaureate institutions, and five at community colleges. Ask for the written workload assignment before accepting.</p>

<h3>How long does it take to become a full professor?</h3>
<p>Tenure and promotion to associate normally come within a probationary period of no more than seven years. Promotion to full professor has no fixed clock and commonly takes another six to ten years, if it happens at all.</p>

<h3>What is the difference between a professor and a lecturer in the US?</h3>
<p>Professor ranks are usually tenure-track or tenured. Lecturer and instructor titles are normally teaching-focused appointments outside the tenure system, averaging $84,292 and $74,087 respectively.</p>

<h3>Are most US professors tenured?</h3>
<p>Among full-time faculty, 50.7 per cent hold tenure and 18.0 per cent are on the tenure track, leaving 31.4 per cent in non-tenure-track appointments. Counting part-time staff, the non-tenured majority is much larger.</p>

<h3>Is a US professor the same as a UK professor?</h3>
<p>No. In the US it is an ordinary career rank held by many faculty. In the UK it is a senior title held by few, and the usual equivalent of a US assistant or associate professor is Lecturer or Senior Lecturer.</p>

<h2>People Also Search For</h2>

<h3>University professor salary USA</h3>
<p>$163,836 average for a full professor on AAUP 2025&ndash;26 figures; $119,836 across all ranks.</p>

<h3>Associate professor promotion requirements</h3>
<p>Normally decided with tenure inside a probationary period of no more than seven years.</p>

<h3>Full professor requirements</h3>
<p>No fixed clock. Departments promote on the record, which is why many careers stop at associate.</p>

<h3>Nine month faculty contract</h3>
<p>The US standard. Summer salary comes from teaching or grants, not from the contract.</p>

<h3>Teaching load at US universities</h3>
<p>Two courses a semester at research universities, up to five at community colleges.</p>

<h3>Non-tenure-track professor titles</h3>
<p>Teaching professor, professor of practice, clinical, research, visiting and adjunct.</p>

<h3>Professor vs lecturer USA</h3>
<p>Professor ranks are tenure-system; lecturer and instructor usually are not.</p>

<h3>Adjunct professor pay per course</h3>
<p>$4,093 for a standard three-credit section on AAUP figures for 2024&ndash;25.</p>

<h2>More Job Guides</h2>

<p>These cover the parts of the academic career this page assumes:</p>

<ul>
    <li><a href="/blog/faculty-careers-in-the-us">Faculty Careers in the US</a> &mdash; every appointment type compared, with the BLS data behind each.</li>
    <li><a href="/blog/assistant-professor-jobs-in-the-us">Assistant Professor Jobs in the US</a> &mdash; the entry rank, the hiring calendar and the job packet.</li>
    <li><a href="/blog/colleges-hiring-professors-in-the-us">Colleges Hiring Professors in the US</a> &mdash; which kinds of institution hire on which credential.</li>
    <li><a href="/blog/adjunct-teaching-opportunities">Adjunct Teaching Opportunities</a> &mdash; per-section pay and benefits eligibility in full.</li>
    <li><a href="/blog/adjunct-professor-jobs-in-the-uk">Adjunct Professor Jobs in the UK</a> &mdash; the British title that carries no salary at all.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; qualified teacher status and the school pay scales.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the school-level route and state certification.</li>
    <li><a href="/blog/higher-education-degree-jobs-in-the-us">Higher Education Degree Jobs in the US</a> &mdash; the administrative careers inside the same institutions, and what they pay.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, employment or tax advice. Pay data is from the American Association of University Professors and the US Bureau of Labor Statistics; workload, promotion and benefits policies are set by each institution. Confirm the terms with the hiring department before relying on them.</p>
HTML;
    }
}
