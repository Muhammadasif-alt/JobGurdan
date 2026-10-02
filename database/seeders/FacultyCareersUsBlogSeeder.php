<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * "Faculty Careers in the US" — the hub for the whole US faculty workforce:
 * what the appointment types are, what each one pays, and which of them a
 * reader can realistically reach. The adjunct hub owns part-time teaching
 * in detail and is cross-linked rather than repeated.
 *
 * Corrections to the draft:
 *
 * 1. The draft describes tenure-track work as one career path among several
 *    without saying how rare it now is. On AAUP figures, 68.2 per cent of
 *    US faculty appointments are contingent and 48.6 per cent are part-time.
 *    A guide that lists "assistant professor" beside "adjunct" as equal
 *    options misrepresents the market a reader is entering.
 *
 * 2. It carries no pay figures at all. BLS puts the median for postsecondary
 *    teachers at $85,330 in May 2025, with a tenfold spread between the
 *    lowest and highest tenths, and the median differs by $27,960 between a
 *    state university and a state junior college.
 *
 * 3. It says tenure follows "a formal review process" without the clock.
 *    The AAUP standard, which most US institutions follow, is a probationary
 *    period that does not exceed seven years.
 *
 * 4. It treats online faculty work as a straightforward option. State
 *    authorisation and the institution's own payroll registration decide
 *    where an online instructor may live, which is a harder constraint than
 *    the draft's "location restrictions can apply".
 *
 * 5. It never mentions benefits eligibility, which is the main financial
 *    difference between appointment types. The IRS counts 2.25 hours of
 *    service for each classroom hour when deciding health coverage, and the
 *    Department of Education counts 3.35 hours for each credit hour taught
 *    for Public Service Loan Forgiveness.
 *
 * 6. It tells readers to search "higher-education employment platforms"
 *    generically. US faculty vacancies are published on the institution's
 *    own system first and syndicate later.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class FacultyCareersUsBlogSeeder extends Seeder
{
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
        $title = 'Faculty Careers in the US';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'US postsecondary teaching is 1.38 million jobs with a $85,330 median, growing 7 per cent to 2035. It is also 68.2 per cent contingent. Which faculty appointments are real careers, what each pays, and how the hiring actually runs.',
                'content' => $content,
                'featured_image' => 'blogs/faculty-careers-in-the-us.jpg',
                'tags' => 'faculty careers us, faculty jobs usa, university faculty positions, tenure track faculty jobs, assistant professor jobs, academic jobs usa, non tenure track faculty, college faculty jobs',
                'meta_title' => 'Faculty Careers in the US 2026: Ranks, Pay and Tenure',
                'meta_description' => 'Faculty careers in the US: the $85,330 BLS median, 1.38 million jobs, 7 per cent growth to 2035, and why most appointments are off the tenure track.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>"Faculty" in the United States covers two different working lives that share a vocabulary. One is a salaried appointment with benefits, a research allocation and a route to tenure. The other is course-by-course teaching paid per section, with no benefits and no guarantee of a second semester. Both are advertised as faculty positions, often by the same department in the same month. This guide separates them: what each appointment type actually is, what the federal data says it pays, and how US academic hiring really runs.</p>

<h2>The Shape of the US Faculty Workforce</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Figure</th>
            <th style="padding:10px;text-align:left;">Source</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Postsecondary teaching jobs</td><td style="padding:10px;">1,378,200 (2025)</td><td style="padding:10px;">BLS</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Median annual wage</td><td style="padding:10px;">$85,330 (May 2025)</td><td style="padding:10px;">BLS</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lowest 10 per cent</td><td style="padding:10px;">less than $49,540</td><td style="padding:10px;">BLS</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Highest 10 per cent</td><td style="padding:10px;">more than $203,580</td><td style="padding:10px;">BLS</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Projected change, 2025 to 2035</td><td style="padding:10px;">+7 per cent, +98,200 jobs</td><td style="padding:10px;">BLS</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Openings each year, on average</td><td style="padding:10px;">about 103,300</td><td style="padding:10px;">BLS</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Faculty appointments that are part-time</td><td style="padding:10px;">48.6 per cent</td><td style="padding:10px;">AAUP</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Faculty appointments that are contingent</td><td style="padding:10px;">68.2 per cent</td><td style="padding:10px;">AAUP</td></tr>
    </tbody>
</table>
</div>

<p>Read the last two rows against the first six. The occupation is growing and pays a solid median, and roughly two thirds of the appointments inside it carry no tenure and no expectation of renewal. Both facts are true, and a career plan built on only the first one will not survive contact with a search committee.</p>

<p>The AAUP's 2025&ndash;26 Faculty Compensation Survey collected full-time salary data from 768 US colleges and universities covering 359,234 full-time faculty, plus 125,149 part-time faculty from 664 institutions. It found average salaries up 2.3 per cent in nominal terms from autumn 2024 to autumn 2025, against a 2.7 per cent rise in the CPI-U &mdash; a real-terms fall of about 0.4 per cent.</p>

<h2>What Each Rank Averages</h2>

<p>These are the AAUP's 2025&ndash;26 averages for full-time faculty, all institution types combined. They describe salaried appointments only, so read them alongside the per-section figure further down rather than instead of it.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Rank</th>
            <th style="padding:10px;text-align:left;">Average salary, 2025&ndash;26</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Professor</td><td style="padding:10px;">$163,836</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Associate professor</td><td style="padding:10px;">$113,427</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Assistant professor</td><td style="padding:10px;">$97,232</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lecturer</td><td style="padding:10px;">$84,292</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Instructor</td><td style="padding:10px;">$74,087</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>All ranks combined</strong></td><td style="padding:10px;"><strong>$119,836</strong></td></tr>
    </tbody>
</table>
</div>

<p>Promotion from assistant to associate professor is worth about $16,000 on these averages; the step from associate to full professor is worth roughly $50,000. That second gap is why the tenure decision matters financially long after it is made.</p>

<p>Among full-time faculty in the same survey, 50.7 per cent held tenure, 18.0 per cent were on the tenure track and 31.4 per cent were in non-tenure-track appointments. That is full-time staff only &mdash; add part-time appointments back in and the contingent share rises sharply.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/faculty-careers-in-the-us-campus.jpg"
         alt="A postgraduate sitting on a campus wall with a tablet and books, with a US flag, a university clock tower and students walking behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Appointment Types, and What Each One Gives You</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Appointment</th>
            <th style="padding:10px;text-align:left;">Basis</th>
            <th style="padding:10px;text-align:left;">What it really means</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Tenure-track assistant professor</strong></td><td style="padding:10px;">Salaried, full-time</td><td style="padding:10px;">A probationary appointment with a tenure review at the end of it. Teaching, research and service are all assessed.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Tenured associate professor or professor</strong></td><td style="padding:10px;">Salaried, continuing</td><td style="padding:10px;">The appointment the whole structure is built around, and the one that is shrinking as a share of the workforce.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Teaching professor or professor of practice</strong></td><td style="padding:10px;">Salaried, renewable</td><td style="padding:10px;">Full-time, benefits, often promotable through its own ranks, but no tenure. Heavier teaching load, little or no research expectation.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Lecturer or instructor</strong></td><td style="padding:10px;">Salaried or per-course</td><td style="padding:10px;">The most ambiguous title in US higher education. Read the contract, not the name: some are full-time with benefits, some are adjunct work relabelled.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Adjunct faculty</strong></td><td style="padding:10px;">Per course section</td><td style="padding:10px;">Paid by the section, usually no benefits, re-hired term by term. Nearly half of all US faculty appointments.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Clinical faculty</strong></td><td style="padding:10px;">Salaried, fixed-term</td><td style="padding:10px;">Nursing, medicine, law, social work and education. A professional licence usually matters more than a publication record.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Research faculty</strong></td><td style="padding:10px;">Grant-funded</td><td style="padding:10px;">Employment lasts as long as the award does. Little or no teaching, and renewal depends on funding rather than performance alone.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Visiting faculty</strong></td><td style="padding:10px;">One term or one year</td><td style="padding:10px;">A real salary and a hard end date. Often used to cover a sabbatical.</td></tr>
    </tbody>
</table>
</div>

<p><strong>The job title is not the contract.</strong> Two adverts reading "Lecturer, Department of Biology" at two institutions can mean a salaried post with a pension and a per-section appointment with neither. The question to ask before you apply is not the rank; it is whether the appointment is full-time, whether it carries benefits, and how it is renewed.</p>

<h2>What Faculty Work Pays</h2>

<p>The single median hides most of what matters, because the type of institution moves the number more than the discipline does.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Employer</th>
            <th style="padding:10px;text-align:left;">Median annual wage, May 2025</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Colleges and universities, state</td><td style="padding:10px;">$96,120</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Colleges and universities, private</td><td style="padding:10px;">$89,660</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Junior colleges, local</td><td style="padding:10px;">$81,640</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Junior colleges, state</td><td style="padding:10px;">$68,160</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>All postsecondary teachers</strong></td><td style="padding:10px;"><strong>$85,330</strong></td></tr>
    </tbody>
</table>
</div>

<p>None of these describe adjunct pay. <strong>The AAUP puts the average pay for teaching one standard three-credit course section at $4,093 in 2024&ndash;25, unchanged in cash terms from the year before.</strong> Four sections a semester, eight across an academic year, is around $32,700 before tax for what is in practice a full teaching load &mdash; which is why the BLS median and the adjunct reality are so far apart. If you are weighing part-time teaching, use the per-section figure and not the occupational median.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/faculty-careers-in-the-us-application.jpg"
         alt="An academic job applicant at a desk with a laptop, notebook and stacked textbooks, with a US flag, a campus building and a city skyline behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Do You Need a PhD?</h2>

<p>It depends on the institution type far more than on the discipline.</p>

<ul>
    <li><strong>Four-year colleges and universities</strong> generally expect a doctorate for tenure-track appointments, and most adverts require it to be in hand by the start date rather than merely in progress.</li>
    <li><strong>Community and junior colleges</strong> commonly hire with a master's degree in the discipline, or a master's with 18 graduate credit hours in the subject taught, which is the regional accreditation convention.</li>
    <li><strong>Professional and applied fields</strong> &mdash; nursing, accounting, engineering, computing, criminal justice, the trades &mdash; weight licensure and industry experience heavily, and a terminal professional qualification can substitute for a PhD.</li>
    <li><strong>Studio and performance disciplines</strong> treat the MFA as the terminal degree.</li>
</ul>

<h2>The Tenure Clock</h2>

<p>A tenure-track appointment is probationary. The AAUP's standard, which most US institutions follow in their own handbooks, is a probationary period that does not exceed <strong>seven years</strong>, with the tenure decision made in the final year. If tenure is refused, the appointment ends; there is no demotion to a lower rank.</p>

<p>What is assessed varies, but the three headings are near-universal: teaching, scholarship and service. At research-intensive universities the scholarship record &mdash; publications, external grant funding, doctoral supervision &mdash; usually decides the case. At teaching-focused institutions, course evaluations, curriculum work and student outcomes carry far more weight. <strong>Read the department's own tenure criteria before accepting the post</strong>, not in year five.</p>

<h2>Benefits, Hours and Two Federal Multipliers</h2>

<p>The gap between appointment types is mostly a gap in benefits, and two federal rules decide how teaching hours convert into entitlements.</p>

<ul>
    <li><strong>Health coverage.</strong> For the employer mandate, the IRS credits <strong>2.25 hours of service for each hour of classroom teaching</strong>, plus time for required duties such as office hours and faculty meetings. That is how a per-section appointment can still cross the full-time threshold.</li>
    <li><strong>Public Service Loan Forgiveness.</strong> The Department of Education counts <strong>3.35 hours of work for every credit hour taught</strong> when a non-profit or public institution certifies employment, so teaching nine credit hours a week clears the full-time test.</li>
    <li><strong>Retirement.</strong> Salaried faculty at public institutions are usually in a state system or an optional retirement plan; adjunct appointments frequently have no employer contribution at all.</li>
    <li><strong>Summer.</strong> A nine-month contract is paid over nine or twelve months by election, but it is still nine months of salary. Summer teaching and grant buy-out are separate payments.</li>
</ul>

<h2>How US Faculty Hiring Actually Runs</h2>

<ol>
    <li><strong>The search committee, not a recruiter.</strong> Faculty in the department read the applications. Write for subject specialists, not for an applicant tracking system.</li>
    <li><strong>The timetable is seasonal.</strong> Tenure-track searches for an autumn start are typically advertised from late summer through the autumn, with campus visits in the new year. Adjunct and visiting posts are filled far later, sometimes weeks before term.</li>
    <li><strong>The materials are standard.</strong> Academic CV, cover letter addressed to the department, teaching statement, research statement, evidence of teaching effectiveness, and three referees who have agreed in advance.</li>
    <li><strong>The campus visit is the interview.</strong> One or two days: a job talk, a teaching demonstration, meetings with the dean, with faculty and with students. The teaching demonstration is where teaching-focused posts are won or lost.</li>
    <li><strong>Negotiation happens once.</strong> Startup funds, teaching release, moving costs and the tenure clock itself are all negotiable at offer stage and effectively fixed afterwards.</li>
    <li><strong>Apply where the vacancy is published.</strong> US institutions post to their own HR system first; everything else is a copy, usually a later one.</li>
</ol>

<h2>Online and Remote Faculty Work</h2>

<p>Online teaching is real but the constraint is not the teaching. An institution must be authorised to operate in the state where <em>you</em> live, and must be registered to run payroll there. That is why an online faculty advert often lists eligible states, and why "remote" in US higher education rarely means anywhere. Check the eligible-state list before investing an application.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is a faculty career in the US?</h3>
<p>An academic appointment at a college, university or community college involving teaching, and sometimes research and institutional service. Appointments range from per-section adjunct work to tenured professorships, with very different pay and security.</p>

<h3>How much do US faculty earn?</h3>
<p>The BLS median for postsecondary teachers was $85,330 in May 2025, from less than $49,540 in the lowest tenth to more than $203,580 in the highest. State universities median $96,120 and state junior colleges $68,160.</p>

<h3>How many faculty jobs are there in the US?</h3>
<p>About 1,378,200 postsecondary teaching jobs in 2025, projected to grow 7 per cent by 2035, an increase of 98,200, with around 103,300 openings a year on average.</p>

<h3>Do you need a PhD for a faculty job?</h3>
<p>For tenure-track posts at four-year institutions, almost always, and usually in hand by the start date. Community colleges commonly hire with a master's in the discipline, and professional fields may accept a terminal professional qualification plus experience.</p>

<h3>What is the tenure clock?</h3>
<p>The probationary period before a tenure decision. The AAUP standard, followed by most US institutions, is that it should not exceed seven years, with the decision taken in the final year.</p>

<h3>What proportion of US faculty are tenured?</h3>
<p>A minority. On AAUP figures, 68.2 per cent of faculty appointments are contingent and 48.6 per cent are part-time, so most people teaching in US higher education are not on a tenure track at all.</p>

<h3>What does an adjunct get paid per course?</h3>
<p>An average of $4,093 a section on AAUP figures. Eight sections across an academic year comes to roughly $32,700 before tax, usually with no benefits.</p>

<h3>Can faculty members work fully remotely?</h3>
<p>Sometimes, but the limit is regulatory rather than academic. The institution must be authorised to operate and to run payroll in your state, which is why online faculty adverts list eligible states.</p>

<h2>People Also Search For</h2>

<h3>Faculty jobs USA</h3>
<p>1,378,200 postsecondary teaching jobs, growing 7 per cent to 2035 on BLS projections.</p>

<h3>Tenure-track faculty positions</h3>
<p>Probationary salaried appointments with a tenure review, normally within seven years.</p>

<h3>Assistant professor jobs</h3>
<p>The entry rank on the tenure track; not every assistant professor post is tenure-track.</p>

<h3>Non-tenure-track faculty</h3>
<p>Teaching professors, lecturers, clinical and research faculty; 68.2 per cent of all appointments are contingent.</p>

<h3>Community college faculty jobs</h3>
<p>Often open to a master's with 18 graduate credit hours in the subject. State junior college median $68,160.</p>

<h3>Online faculty jobs</h3>
<p>Limited by state authorisation and payroll registration, not by the teaching itself.</p>

<h3>Academic CV for US faculty jobs</h3>
<p>CV, cover letter, teaching statement, research statement, evidence of teaching and three referees.</p>

<h3>University professor salary USA</h3>
<p>$96,120 median at state universities, $89,660 at private ones, on BLS May 2025 data.</p>

<h2>More Job Guides</h2>

<p>These go deeper on the parts of academic work this page summarises:</p>

<ul>
    <li><a href="/blog/professor-employment-in-the-us">Professor Employment in the US</a> &mdash; the nine-month contract, teaching loads by institution and what promotion is worth.</li>
    <li><a href="/blog/assistant-professor-jobs-in-the-us">Assistant Professor Jobs in the US</a> &mdash; the hiring calendar, the job talk and what to negotiate at offer stage.</li>
    <li><a href="/blog/colleges-hiring-professors-in-the-us">Colleges Hiring Professors in the US</a> &mdash; which institutions hire on which credential, and where they publish.</li>
    <li><a href="/blog/adjunct-teaching-opportunities">Adjunct Teaching Opportunities</a> &mdash; per-section pay, benefits eligibility and the loan-forgiveness arithmetic in full.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the school-level route, certification by state.</li>
    <li><a href="/blog/tutor-jobs-in-usa">Tutor Jobs in USA</a> &mdash; teaching work that needs no faculty appointment.</li>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; remote teaching outside the university system.</li>
    <li><a href="/blog/adjunct-teaching-jobs-in-the-uk">Adjunct Teaching Jobs in the UK</a> &mdash; what the same work is called and paid in Britain.</li>
    <li><a href="/blog/adjunct-professor-jobs-in-the-uk">Adjunct Professor Jobs in the UK</a> &mdash; the honorary title that carries no salary.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; qualified teacher status and the school pay scales.</li>
    <li><a href="/blog/educational-support-jobs-in-usa">Educational Support Jobs in USA</a> &mdash; the non-teaching roles inside the same institutions.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, employment or tax advice. Pay data is from the US Bureau of Labor Statistics and the American Association of University Professors, and institutional policies on tenure, benefits and online teaching vary. Confirm details with the hiring institution before relying on them.</p>
HTML;
    }
}
