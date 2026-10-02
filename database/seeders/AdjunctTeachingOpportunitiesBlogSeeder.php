<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * "Adjunct Teaching Opportunities" — the hub for the generic search term.
 *
 * The four UK pages in this cluster already own their own country queries:
 * adjunct teaching jobs, adjunct faculty vacancies, adjunct professor jobs
 * and how to become an adjunct lecturer, all "in the UK". This page owns the
 * bare term, which is American, and the thing none of the others cover: what
 * an adjunct appointment actually pays, the two federal rules that turn
 * course loads into hours, and what the job is called outside the US.
 *
 * Corrections to the draft:
 *
 * 1. It says "compensation varies by institution, subject, location,
 *    experience, and course" and names no figure at all. The AAUP Faculty
 *    Compensation Survey puts the average at $4,093 per three-credit course
 *    section in 2023-24.
 *
 * 2. It implies the BLS postsecondary teacher figure describes this job. The
 *    $85,330 May 2025 median covers all postsecondary teachers including
 *    full-time tenured professors, and is not what an adjunct earns.
 *
 * 3. It says "benefits eligibility can also differ" without the rule that
 *    decides it. The IRS method for adjunct faculty credits 2.25 hours of
 *    service for each hour of classroom teaching, which is what the 30 hour
 *    health coverage threshold is measured against.
 *
 * 4. It never mentions Public Service Loan Forgiveness, where the Department
 *    of Education requires employers to credit adjuncts at least 3.35 hours
 *    per credit hour taught, so nine credit hours a week counts as full time.
 *
 * 5. It treats "adjunct professor" as a job title everywhere. In the UK it is
 *    normally an honorary, unpaid title; the paid work is advertised as
 *    hourly-paid, associate or visiting lecturer.
 *
 * 6. It tells readers to check "established employment websites". This site
 *    does not send readers to aggregators, so the guide points at official
 *    institution careers pages instead.
 *
 * The record uses updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to this row.
 */
class AdjunctTeachingOpportunitiesBlogSeeder extends Seeder
{
    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on applying, interviewing and building a career.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Adjunct Teaching Opportunities';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The BLS median of $85,330 is for all postsecondary teachers, not adjuncts: AAUP puts the average at $4,093 per three-credit section. Nine credit hours a week makes you full time for PSLF, and outside the US the job is called something else entirely.',
                'content' => $content,
                'featured_image' => 'blogs/adjunct-teaching-opportunities.jpg',
                'tags' => 'adjunct teaching opportunities, adjunct faculty jobs, adjunct professor pay per course, part time faculty positions, online adjunct faculty jobs, adjunct teaching without a phd, pslf adjunct faculty full time, hourly paid lecturer uk',
                'meta_title' => 'Adjunct Teaching Opportunities 2026: Pay, Hours and Routes',
                'meta_description' => 'Adjunct teaching opportunities: what a course section really pays, the 3.35 rule that makes you full time for PSLF, and what the job is called outside the US.',
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
<p>"Adjunct" is an American job title. In the United States it means a real, paid, per-course academic appointment that about half the teaching workforce holds. Search the same word in Britain and you will mostly find unpaid honorary titles. Guides written for this term rarely say so, and they almost never put a number on the pay. This one does both.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://jobgader.com/categories/career-advice" rel="noopener" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127891; Browse Teaching and Academic Guides &rarr;
    </a>
</div>

<h2>What the Word Means Where You Are</h2>

<p>Searching the wrong word is the most common reason people find nothing. The appointment exists almost everywhere; the label does not travel.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">What to search</th>
            <th style="padding:10px;text-align:left;">What "adjunct" means there</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>United States</strong></td><td style="padding:10px;">Adjunct professor, adjunct faculty, adjunct instructor, part-time faculty</td><td style="padding:10px;">A paid, per-course teaching appointment</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>United Kingdom</strong></td><td style="padding:10px;">Hourly-paid lecturer, associate lecturer, visiting lecturer, graduate teaching assistant</td><td style="padding:10px;">Usually an honorary, unpaid title conferred by nomination</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">Sessional lecturer, sessional instructor, contract academic staff</td><td style="padding:10px;">Rarely used for paid teaching work</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australia</strong></td><td style="padding:10px;">Casual academic, sessional academic, casual tutor</td><td style="padding:10px;">Normally an honorary or affiliate title</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/adjunct-teaching-opportunities-lecture-hall.jpg"
         alt="A lecturer teaching students in a tiered lecture theatre, alongside students walking through a historic university campus"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Adjunct Teaching Actually Pays</h2>

<p>Two numbers get confused constantly, and only one of them describes this job.</p>

<ul>
    <li><strong>The BLS figure is not your figure.</strong> The Bureau of Labor Statistics puts the median pay for postsecondary teachers at <strong>$85,330 a year</strong> as of May 2025, across 1,378,200 jobs. That median includes full-time tenured professors. The same handbook notes that "part-time work is common" in the occupation.</li>
    <li><strong>The adjunct figure is per course.</strong> The AAUP Faculty Compensation Survey puts the average at <strong>$4,093 per three-credit course section</strong> in 2023-24, about 3.9 percent below the 2019-20 average once inflation is accounted for.</li>
</ul>

<p>That second number is the one to plan with. Teaching three sections in the autumn and three in the spring is six sections, which at the survey average is roughly <strong>$24,600 for the academic year</strong> before tax, with no pay over the summer unless you teach then too. That is arithmetic on the average, not a salary anyone is offering, and individual institutions pay well above and well below it.</p>

<p>For context on how large the group is: the AAUP found that part-time faculty made up <strong>48.6 percent</strong> of the academic workforce in autumn 2023, and that <strong>68.2 percent</strong> of all faculty were part-time or on full-time appointments ineligible for tenure. About 76 percent of part-time contingent appointments were on non-renewable short-term contracts, which is why renewal terms matter more here than in most jobs.</p>

<h2>The Two Rules That Turn Courses Into Hours</h2>

<p>Adjuncts are paid per course but regulated per hour, and two federal rules convert between the two. Neither appears in most guides, and both are worth money.</p>

<h3>Health coverage: the IRS method</h3>

<p>Employer health coverage obligations under the Affordable Care Act turn on whether you work 30 hours a week. Because adjunct hours are hard to track, the IRS says employers must use a reasonable method, and sets out one: credit <strong>2.25 hours of service for each hour of classroom teaching</strong>, which allows an extra 1.25 hours for preparation and grading, plus one hour per week for each additional required hour outside the classroom such as office hours or faculty meetings.</p>

<p>Ask how your institution counts. The method it chooses is what decides whether a teaching load reaches the coverage threshold.</p>

<h3>Student loans: the 3.35 rule</h3>

<p>For Public Service Loan Forgiveness, the Department of Education requires employers to credit adjunct and contingent faculty with <strong>at least 3.35 hours of work for every credit hour taught</strong>. Thirty hours a week is full time, so <strong>nine credit hours a week clears it</strong> at 30.15 hours. Most non-profit and public colleges are qualifying employers, which means a three-course load can make an adjunct full time for forgiveness purposes even though the institution calls the post part-time.</p>

<p>If an employer certifies you as part-time on the PSLF form using a lower multiplier, point them at the rule. The multiplier cannot be less than 3.35.</p>

<h2>Do You Need a PhD?</h2>

<p>Not always, and the official position is clearer than most guides suggest. The BLS Occupational Outlook Handbook states that postsecondary teachers "typically must have a Ph.D.", but that "a master's degree may be enough for some postsecondary teachers at community colleges".</p>

<p>In practice the pattern is:</p>

<ul>
    <li><strong>Community and technical colleges.</strong> A master's in the discipline, or a master's plus 18 graduate credit hours in the subject, is the usual accreditation benchmark.</li>
    <li><strong>Four-year institutions, general education courses.</strong> A master's is often accepted for introductory sections, a doctorate preferred.</li>
    <li><strong>Upper-division and graduate courses.</strong> A doctorate is normally required.</li>
    <li><strong>Applied and professional programmes.</strong> Nursing, accounting, law, IT and the trades weigh licences, certifications and years in the field heavily, sometimes above the degree.</li>
</ul>

<h2>Online Adjunct Work and Where You Can Live</h2>

<p>Online sections are genuinely common, and the duties are what the draft describes: delivering modules, running discussion boards, grading, virtual office hours and feedback inside a learning management system.</p>

<p>The constraint people miss is location. Online adjunct postings routinely restrict applicants to a list of states or exclude particular ones. That is usually nothing to do with your skills: employing someone in a state obliges the institution to register for payroll and unemployment tax there, so it limits hiring to states where it already does. Read the eligibility line before investing in the application.</p>

<h2>What to Check Before You Accept</h2>

<ol>
    <li><strong>Pay per section, and whether preparation is paid.</strong> First-time preparation of a new course is the hidden cost; some institutions pay a development fee and some do not.</li>
    <li><strong>Cancellation terms.</strong> Sections with low enrolment get cut late, sometimes days before term. Ask what you are paid if that happens.</li>
    <li><strong>How hours are counted</strong> for health coverage, and the multiplier used on PSLF forms.</li>
    <li><strong>Renewal.</strong> Most part-time contingent appointments are non-renewable by default and re-offered term by term.</li>
    <li><strong>Who owns the course materials</strong> you build, particularly for online sections.</li>
    <li><strong>Whether the load crosses a benefits or union threshold</strong> at that institution. Many cap adjunct loads deliberately just below one.</li>
</ol>

<h2>How to Find and Apply</h2>

<ol>
    <li><strong>Go to the institution's own careers page.</strong> Colleges post adjunct pools continuously, often as an open "adjunct pool" requisition per department rather than a dated vacancy.</li>
    <li><strong>Apply to the pool even with no live section.</strong> Pools are how most adjunct hiring actually happens; departments draw from them when enrolment moves.</li>
    <li><strong>Search every variant of the title.</strong> Adjunct professor, adjunct faculty, adjunct instructor, part-time faculty, lecturer, and the local word if you are outside the US.</li>
    <li><strong>Name the courses you can teach</strong> by catalogue number in your covering letter. Chairs are filling specific sections, not hiring a general academic.</li>
    <li><strong>Prepare the pack.</strong> Academic CV, covering letter, transcripts, teaching statement, references, teaching evaluations, any professional licence, and a sample syllabus.</li>
    <li><strong>Expect a teaching demonstration</strong> and questions on your teaching philosophy, assessment design and online delivery.</li>
</ol>

<h2>The UK Situation, Briefly</h2>

<p>If you are searching from Britain, the term is a trap. An adjunct or honorary professorship there is a title conferred by nomination, carries no salary, and creates no contract of employment. The paid equivalent is advertised as hourly-paid lecturer, associate lecturer, visiting lecturer or graduate teaching assistant.</p>

<p>The scale is also smaller than the US picture suggests. HESA's Statistical Bulletin SB274, published on 19 February 2026, records 3,440 academic staff on zero hours contracts across UK higher education in 2024/25, 92 percent of them paid by the hour. Our four UK guides below work through the titles, the contracts and the pay in detail.</p>

<h2>Frequently Asked Questions</h2>

<h3>What are adjunct teaching opportunities?</h3>
<p>Part-time, term-by-term academic appointments, usually paid per course section rather than by salary, at colleges and universities. In the US they are the single largest category of teaching appointment.</p>

<h3>How much does an adjunct get paid per course?</h3>
<p>The AAUP Faculty Compensation Survey puts the average at $4,093 per three-credit course section in 2023-24. Six sections across an academic year comes to roughly $24,600 at that average, with nothing over the summer.</p>

<h3>Is the $85,330 median what adjuncts earn?</h3>
<p>No. That is the BLS median for all postsecondary teachers as of May 2025, including full-time tenured professors. It does not describe a per-course appointment.</p>

<h3>Do adjunct teachers need a PhD?</h3>
<p>Not always. The BLS handbook says a master's degree may be enough at community colleges, while four-year institutions typically require a doctorate for upper-division and graduate courses.</p>

<h3>Can adjunct faculty qualify for Public Service Loan Forgiveness?</h3>
<p>Yes. The Department of Education requires employers to credit at least 3.35 hours per credit hour taught, so nine credit hours a week reaches the 30 hour full-time standard at a qualifying employer.</p>

<h3>Do adjuncts get health insurance?</h3>
<p>It depends on how the employer counts hours. The IRS sets out a method crediting 2.25 hours of service per hour of classroom teaching, plus one hour a week for each required hour outside it, measured against a 30 hour threshold.</p>

<h3>Are adjunct positions available online?</h3>
<p>Yes, and they are common. Postings often restrict applicants to particular states because employing someone there obliges the institution to register for payroll tax in that state.</p>

<h3>What is an adjunct called in the UK?</h3>
<p>The paid work is advertised as hourly-paid lecturer, associate lecturer, visiting lecturer or graduate teaching assistant. An adjunct or honorary professorship in the UK is normally an unpaid title.</p>

<h2>People Also Search For</h2>

<h3>Adjunct faculty jobs</h3>
<p>Per-course appointments, usually filled from standing departmental pools.</p>

<h3>Adjunct professor salary per course</h3>
<p>$4,093 on average for a three-credit section, AAUP 2023-24.</p>

<h3>Part time faculty positions</h3>
<p>48.6 percent of the US academic workforce in autumn 2023.</p>

<h3>Online adjunct faculty jobs</h3>
<p>Common, but usually limited to states where the institution already runs payroll.</p>

<h3>Adjunct teaching without a PhD</h3>
<p>A master's in the discipline is often enough at community colleges.</p>

<h3>PSLF adjunct faculty full time</h3>
<p>Nine credit hours a week clears 30 hours at the 3.35 multiplier.</p>

<h3>Adjunct faculty health insurance hours</h3>
<p>The IRS method credits 2.25 hours per classroom hour against a 30 hour threshold.</p>

<h3>Hourly paid lecturer UK</h3>
<p>The British equivalent of the paid adjunct appointment.</p>

<h2>More Job Guides</h2>

<p>The country detail sits in these:</p>

<ul>
    <li><a href="/blog/adjunct-teaching-jobs-in-the-uk">Adjunct Teaching Jobs in the UK</a> &mdash; what the paid work is really called, and the contracts it comes on.</li>
    <li><a href="/blog/adjunct-professor-jobs-in-the-uk">Adjunct Professor Jobs in the UK</a> &mdash; the honorary title that is not a job and carries no salary.</li>
    <li><a href="/blog/adjunct-faculty-vacancies-in-the-uk">Adjunct Faculty Vacancies in the UK</a> &mdash; where the hourly-paid vacancies are actually advertised.</li>
    <li><a href="/blog/how-to-become-an-adjunct-lecturer-in-the-uk">How to Become an Adjunct Lecturer in the UK</a> &mdash; the route in, and why the pay floor rules out sponsorship.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; qualified teacher status and the school pay scales.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the school-level route in the country where "adjunct" is a real job title.</li>
    <li><a href="/blog/tutor-jobs-in-usa">Tutor Jobs in USA</a> &mdash; teaching work that needs no faculty appointment at all.</li>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; remote teaching outside the university system.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or financial advice. Pay surveys, federal rules and institutional policies change. Confirm current figures with BLS, AAUP, the IRS and your own institution before relying on them.</p>
HTML;
    }
}
