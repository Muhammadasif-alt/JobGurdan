<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * "Assistant Professor Jobs in the US" — the entry rank and the search that
 * leads to it. The faculty hub owns the workforce overview, the professor
 * employment guide owns the contract terms, and the colleges guide owns the
 * institution types. This page owns the hiring cycle, the application packet
 * and the offer.
 *
 * Corrections to the draft:
 *
 * 1. The draft gives no calendar. US tenure-track searches advertise from
 *    late summer through autumn for the following autumn start, with campus
 *    visits in the new year. Someone who starts looking in spring has
 *    already missed the cycle, and the draft would not tell them.
 *
 * 2. It lists application materials without saying which one decides the
 *    outcome. The job talk and the teaching demonstration decide most
 *    campus visits; the cover letter decides whether you get one.
 *
 * 3. It never mentions negotiation. Startup funds, course release, the
 *    tenure clock and moving costs are settled once, at offer stage, and
 *    are effectively fixed afterwards.
 *
 * 4. It says "some assistant professor positions are not part of a tenure
 *    track" without naming the titles that signal it: research assistant
 *    professor, clinical assistant professor, teaching assistant professor.
 *
 * 5. It gives no pay figure. The AAUP 2025-26 average for an assistant
 *    professor is $97,232.
 *
 * 6. It does not mention the third-year review or external letters, which
 *    are the two parts of the tenure process that begin to matter in the
 *    first week of the appointment.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class AssistantProfessorJobsUsBlogSeeder extends Seeder
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
        $title = 'Assistant Professor Jobs in the US';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'US assistant professor searches run on a calendar that starts a year ahead, are decided by a job talk and a teaching demonstration, and settle everything negotiable in one conversation. The average salary is $97,232.',
                'content' => $content,
                'featured_image' => 'blogs/assistant-professor-jobs-in-the-us.jpg',
                'tags' => 'assistant professor jobs us, tenure track jobs usa, academic job market usa, job talk campus visit, assistant professor salary, startup funds negotiation, third year review tenure, research assistant professor',
                'meta_title' => 'Assistant Professor Jobs in the US: Hiring, Pay, Tenure',
                'meta_description' => 'Assistant professor jobs in the US: the hiring calendar, the job talk, what to negotiate at offer stage, and the $97,232 AAUP average salary.',
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
<p>Assistant professor is the entry rank of the US professorial ladder, and the search that fills it is unlike any other hiring process you will meet. It runs on a calendar set a year in advance, it is judged by faculty in your own subject rather than by recruiters, and it is decided in a two-day campus visit built around a research talk and a teaching demonstration. This guide is about that process, and about the offer at the end of it, where everything negotiable is settled once and then fixed for seven years.</p>

<h2>Not Every Assistant Professor Post Is Tenure-Track</h2>

<p>The modifier in front of the title is the contract. Read it before anything else:</p>

<ul>
    <li><strong>Assistant Professor</strong> with no modifier &mdash; normally tenure-track, with a probationary period and a tenure review at the end of it.</li>
    <li><strong>Research Assistant Professor</strong> &mdash; grant-funded. The appointment lasts as long as the award does, and there is usually no tenure route.</li>
    <li><strong>Clinical Assistant Professor</strong> &mdash; professional schools. Current licensure and practice usually outweigh a publication record.</li>
    <li><strong>Teaching Assistant Professor</strong> or <strong>Assistant Teaching Professor</strong> &mdash; a teaching-focused appointment, often renewable and promotable within its own ladder, outside the tenure system.</li>
    <li><strong>Visiting Assistant Professor</strong> &mdash; a fixed term, often one year, usually covering a sabbatical or a failed search.</li>
</ul>

<p>All five are advertised as assistant professor jobs. Only the first carries a route to tenure, and the advert will not always say so in the headline.</p>

<h2>The Hiring Calendar</h2>

<p>Most US tenure-track searches for an autumn start run on roughly this sequence. Applying outside it is the commonest wasted effort in the academic job market.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">When</th>
            <th style="padding:10px;text-align:left;">What happens</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Late summer to autumn</td><td style="padding:10px;">Adverts appear on institutions' own HR systems. Deadlines cluster from October to December.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Late autumn</td><td style="padding:10px;">The search committee longlists. Some fields run conference or video interviews at this stage.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Winter into spring</td><td style="padding:10px;">Three or four finalists are invited for campus visits, usually one at a time over several weeks.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Spring</td><td style="padding:10px;">The offer, then negotiation, then the contract. Start date is usually August.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Late spring and summer</td><td style="padding:10px;">Failed searches, late resignations and visiting posts fill, sometimes weeks before term.</td></tr>
    </tbody>
</table>
</div>

<p>Two practical consequences. First, if you want a post for next autumn, you are applying this autumn. Second, the late window is real but it is mostly visiting and non-tenure-track work, so treat it as a different job market rather than a second chance at the same one.</p>

<h2>The Packet, and Which Part Does the Work</h2>

<ol>
    <li><strong>Cover letter.</strong> Addressed to the search committee chair and written for subject specialists. It is the document that decides whether anyone reads the rest, so it must say what your research is, why this department, and what you would teach here.</li>
    <li><strong>Academic CV.</strong> Education, appointments, publications, grants, conference papers, teaching, supervision, service. No page limit and no design.</li>
    <li><strong>Research statement.</strong> What you have done, what you are doing, and what the next five years fund and produce. Tenure committees read this later as a promise.</li>
    <li><strong>Teaching statement.</strong> Philosophy, methods, assessment, and evidence that students learned. At teaching-focused institutions this outranks the research statement.</li>
    <li><strong>Evidence of teaching effectiveness.</strong> Course evaluations, sample syllabi, peer observations.</li>
    <li><strong>References.</strong> Three, asked in advance, warned before each deadline. Some systems contact referees automatically at application, so a referee who is slow can cost you the longlist.</li>
</ol>

<h2>The Campus Visit</h2>

<p>One or two days, and effectively the whole interview. The usual components:</p>

<ul>
    <li><strong>The job talk.</strong> Forty-five minutes on your research to a mixed audience of specialists and non-specialists from the department, followed by questions that are the real test.</li>
    <li><strong>The teaching demonstration.</strong> A real class or a mock one. At master's, baccalaureate and community colleges this is usually the deciding event.</li>
    <li><strong>Meetings with faculty</strong>, one after another, where everyone forms an opinion that goes back to the committee.</li>
    <li><strong>A meeting with the dean</strong>, where budget and fit are discussed and where you may first hear what the offer could look like.</li>
    <li><strong>Students.</strong> Graduate students in particular are often asked for a view, and are often the most candid source of information about the department for you.</li>
    <li><strong>Meals.</strong> Still part of the interview, in both directions.</li>
</ul>

<p>Go with your own questions: the written workload assignment, the tenure criteria in writing, how many assistant professors the department has tenured in the last five years, and what the startup package normally covers.</p>

<h2>The Offer Is Negotiated Once</h2>

<p>The AAUP 2025&ndash;26 average salary for an assistant professor is <strong>$97,232</strong>. The BLS medians by institution type are a better guide to the range you are actually in: $96,120 at state colleges and universities, $89,660 at private ones, $81,640 at local junior colleges and $68,160 at state junior colleges.</p>

<p>Salary is only part of what is on the table, and the rest is harder to change later:</p>

<ul>
    <li><strong>Startup funds</strong> &mdash; equipment, laboratory set-up, research assistance, conference travel, summer salary in the first years.</li>
    <li><strong>Course release</strong> in the first year or two, which is the most valuable thing a new assistant professor can be given.</li>
    <li><strong>The tenure clock itself</strong> &mdash; credit for prior service, or a longer clock, agreed in writing at the start.</li>
    <li><strong>Moving costs</strong>, which are normal and are often simply not offered unless asked for.</li>
    <li><strong>Partner hiring</strong>, where the institution has a programme. Raise it after the offer, not during the visit.</li>
    <li><strong>Equipment, space and graduate student funding</strong>, named specifically rather than promised generally.</li>
</ul>

<p>Get all of it in the offer letter. A verbal assurance from a department chair does not survive that chair's successor.</p>

<h2>The Tenure Clock Starts in Week One</h2>

<p>The AAUP standard, written into most institutional handbooks, is a probationary period not exceeding <strong>seven years</strong>, with notice at least a year before it ends if tenure is not to be granted. Two checkpoints matter from the beginning:</p>

<ul>
    <li><strong>The third-year review.</strong> A formal mid-probation assessment. It is advisory, but a weak one is the clearest warning you will get, and it is the point at which a correction is still possible.</li>
    <li><strong>External letters.</strong> In the final year your dossier goes to senior scholars at other institutions who assess your record against the field. You generally do not choose all of them. This is why conference presence and collaboration matter years before the decision.</li>
</ul>

<p>Ask for the tenure criteria in writing on day one. Departments change expectations, and the version you were hired under is the one you can hold them to.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is an assistant professor in the US?</h3>
<p>The entry rank of the professorial ladder, usually a probationary tenure-track appointment combining teaching, research and service. Some assistant professor posts are research, clinical, teaching-focused or visiting appointments with no tenure route.</p>

<h3>How much does an assistant professor earn in the US?</h3>
<p>$97,232 on the AAUP 2025&ndash;26 average. The BLS medians by institution type run from $96,120 at state universities to $68,160 at state junior colleges.</p>

<h3>When should I apply for assistant professor jobs?</h3>
<p>From late summer through autumn for the following autumn start. Deadlines cluster between October and December, with campus visits in the new year and offers in spring.</p>

<h3>Do you need a PhD to be an assistant professor?</h3>
<p>For tenure-track posts at four-year institutions, almost always, and most adverts require it in hand by the start date rather than in progress. Professional fields may accept another terminal qualification with relevant practice.</p>

<h3>What happens at a campus visit?</h3>
<p>A job talk on your research, a teaching demonstration, meetings with faculty, students and the dean, usually over one or two days. The talk and the teaching demonstration decide most searches.</p>

<h3>What can you negotiate in an assistant professor offer?</h3>
<p>Salary, startup funds, course release, moving costs, equipment and space, graduate student funding, partner hiring and the tenure clock itself. It is settled once, so get it in the offer letter.</p>

<h3>How long is the tenure clock?</h3>
<p>Normally no more than seven years, following the AAUP standard, with a third-year review along the way and notice at least a year before the end if tenure is refused.</p>

<h3>Is a research assistant professor a tenure-track job?</h3>
<p>Usually not. Research, clinical, teaching and visiting assistant professor titles sit outside the tenure system, and the research ones last only as long as the grant funding them.</p>

<h2>People Also Search For</h2>

<h3>Tenure-track jobs USA</h3>
<p>Probationary appointments with a tenure review, normally within seven years.</p>

<h3>Assistant professor salary</h3>
<p>$97,232 on AAUP 2025&ndash;26 averages, and about $16,000 below the associate professor average.</p>

<h3>Academic job market calendar</h3>
<p>Adverts in autumn, campus visits in winter and spring, offers in spring, start in August.</p>

<h3>Job talk and teaching demonstration</h3>
<p>The two events that decide most campus visits, and the two worth rehearsing properly.</p>

<h3>Startup funds negotiation</h3>
<p>Equipment, laboratory set-up, course release and summer salary, agreed once at offer stage.</p>

<h3>Third year review tenure track</h3>
<p>The mid-probation assessment, and the last point at which a weak record can be corrected.</p>

<h3>Research assistant professor</h3>
<p>A grant-funded appointment that ends with the award and carries no tenure route.</p>

<h3>External letters tenure dossier</h3>
<p>Assessments from senior scholars elsewhere, which is why conference presence matters early.</p>

<h2>More Job Guides</h2>

<p>These cover what sits either side of the entry rank:</p>

<ul>
    <li><a href="/blog/faculty-careers-in-the-us">Faculty Careers in the US</a> &mdash; every appointment type compared, with the BLS data behind each.</li>
    <li><a href="/blog/professor-employment-in-the-us">Professor Employment in the US</a> &mdash; the nine-month contract, teaching loads and what promotion is worth.</li>
    <li><a href="/blog/colleges-hiring-professors-in-the-us">Colleges Hiring Professors in the US</a> &mdash; which kinds of institution hire on which credential.</li>
    <li><a href="/blog/adjunct-teaching-opportunities">Adjunct Teaching Opportunities</a> &mdash; the per-section market many candidates enter first.</li>
    <li><a href="/blog/how-to-become-an-adjunct-lecturer-in-the-uk">How to Become an Adjunct Lecturer in the UK</a> &mdash; the British equivalent of the entry route.</li>
    <li><a href="/blog/adjunct-faculty-vacancies-in-the-uk">Adjunct Faculty Vacancies in the UK</a> &mdash; where UK hourly-paid academic work is advertised.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the school-level route and state certification.</li>
    <li><a href="/blog/higher-education-degree-jobs-in-the-us">Higher Education Degree Jobs in the US</a> &mdash; the non-faculty careers inside the same institutions.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or employment advice. Pay data is from the American Association of University Professors and the US Bureau of Labor Statistics; hiring calendars, tenure criteria and startup packages are set by each institution and department. Confirm the terms in writing before accepting an offer.</p>
HTML;
    }
}
