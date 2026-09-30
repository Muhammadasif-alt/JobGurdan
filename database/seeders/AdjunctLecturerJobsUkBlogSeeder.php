<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * How to become an adjunct lecturer in the UK, checked on 30 September 2026
 * against universities' own published pay schedules, the JNCHES guidance, UCU's
 * survey data, Advance HE and the Immigration Rules.
 *
 * This is the paid half of a pair. Our "Adjunct Professor Jobs in the UK" guide
 * covers the honorary title, which is conferred by nomination and cannot be
 * paid. This page covers the job, which is applied for and is paid by the hour.
 * They share no material.
 *
 * The spine, and the reason this page is worth writing at all:
 *
 * 1. The multiplier. A UK teaching hour is not paid as one hour. The 2004
 *    JNCHES guidance set the convention at "1.5 additional hours for each
 *    hour's teaching", which is where the 2.5x comprehensive rate universities
 *    still quote comes from. Cambridge pays 5x for a lecture, Oxford Brookes
 *    2.5x, St Andrews pays preparation as separate hours but only for the first
 *    delivery of a session. Understanding the multiplier is the difference
 *    between reading an advertised rate correctly and misreading it badly.
 * 2. The gap between the multiplier and the work. UCU's 2019 survey of 1,568
 *    part-time teaching staff found a median of 50 per cent of their labour
 *    unpaid, and its own worked example turns a headline GBP 18.70 an hour into
 *    a real GBP 9.35. Oxford Brookes publishes the sharpest single number in
 *    the sector on this: its comprehensive rate includes 20 minutes of marking
 *    per scheduled teaching hour.
 * 3. The visa arithmetic almost every guide gets backwards. Going rates
 *    pro-rate to working pattern under SW 14.4 of Appendix Skilled Worker, but
 *    the GBP 41,700 general threshold does not. That is the structural reason
 *    hourly-paid teaching cannot normally be sponsored, and it is the single
 *    most important fact on this page for a reader outside the UK.
 *
 * Corrections applied to the supplied brief:
 *  1. The brief routed readers to jobs.ac.uk and LinkedIn. Aggregators are not
 *     linked. The academic board is named once because omitting it would
 *     mislead, and every link goes to a university, to Advance HE or to GOV.UK.
 *  2. The brief left every pay and visa figure as an "[ADD REAL SOURCE]"
 *     placeholder. All are now sourced to a named, dated document.
 *  3. The brief says hourly-paid roles "often don't meet sponsorship
 *     requirements". The reason is structural rather than incidental and is
 *     given, because a reader planning a move needs to know it is not bad luck.
 *
 * Sourcing note: ucu.org.uk serves a Cloudflare challenge to automated access,
 * so UCU's June 2019 casualisation report and its hourly-rate guidance were
 * read from archived captures of UCU's own published files. The figures are
 * UCU's; the pages are worth re-reading in a browser when they next change.
 *
 * Deliberately NOT claimed:
 *  - A sector-wide average hourly rate. There is no such published figure, and
 *    the three universities quoted use three different structures, which is the
 *    point rather than a gap.
 */
class AdjunctLecturerJobsUkBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-become-an-adjunct-lecturer-in-the-uk';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides to getting hired, written from official and employer sources.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Become an Adjunct Lecturer in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'JobGader Editorial',
                'title' => $title,
                'excerpt' => 'UK universities pay teaching by the hour, but a teaching hour is not one hour of pay. Here is what the multiplier covers, what three universities actually publish, and why hourly work cannot normally be visa sponsored.',
                'content' => $content,
                'featured_image' => 'blogs/adjunct-lecturer-jobs-uk.jpg',
                'tags' => 'adjunct lecturer uk, hourly paid lecturer uk, associate lecturer jobs, sessional lecturer uk, hourly paid lecturer rates, afhea advance he, uk university teaching jobs, skilled worker visa lecturer',
                'meta_title' => 'How to Become an Adjunct Lecturer in the UK in 2026',
                'meta_description' => 'Adjunct lecturer work in the UK means hourly-paid or associate lecturer posts. What a teaching hour actually pays, and why the work is rarely sponsored.',
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
<p>There is no job in the UK called "adjunct lecturer". The work exists, it is paid, and thousands of people do it, but universities advertise it as <strong>hourly-paid lecturer</strong>, <strong>associate lecturer</strong>, <strong>sessional lecturer</strong> or <strong>visiting lecturer</strong>. Search the right words and the vacancies appear.</p>

<p>The harder problem is not finding the work. It is reading the offer correctly. A UK university will quote you an hourly rate that looks remarkable, and it is remarkable, because it is not paying you for one hour. Whether that rate is generous or thin depends entirely on a number buried in the policy document, and most people never see it.</p>

<p>This page is about that number.</p>

<h2 id="the-multiplier">A Teaching Hour Is Not an Hour</h2>

<p>When you see an hourly-paid lecturer rate advertised at &pound;50, or &pound;92, your instinct is that it is extraordinarily well paid. It is not. That figure is a <strong>comprehensive rate</strong>: one hour of teaching plus the preparation, marking and administration that goes with it, rolled into a single number you claim once.</p>

<p>The convention goes back to guidance issued in March 2004 by the Joint Negotiating Committee for Higher Education Staff, which set out what the rate is meant to cover: "preparation for teaching, setting and marking of projects and assignments, setting and marking of examinations, supervision of examinations, completion of registers, provision of data and related course administration, and keeping up-to-date with knowledge of the subject". And it set the ratio:</p>

<blockquote><p>"Subject to job requirements, typically this will mean payment for 1.5 additional hours for each hour's teaching, for such associated duties."</p></blockquote>

<p>One teaching hour plus 1.5 hours of everything else is 2.5. That is where the "2.5x" you will see in university policies comes from, more than twenty years later.</p>

<p><strong>Three universities, three different multipliers.</strong> Compare how they publish it:</p>

<ul>
    <li><strong>Oxford Brookes University</strong> states it plainly: "Scheduled teaching is paid at a comprehensive rate (2.5 x the simple basic rate), which includes payment for time spent on preparation and marking, pastoral guidance, and dissertation/thesis supervision". Its policy adds the instruction that catches people out: associate lecturers "must not claim for these activities separately in addition to the comprehensive rate".</li>
    <li><strong>The University of Cambridge</strong> goes much further for lectures. Its substitute teaching schedule says its rates "incorporate preparation time, set at 4 hours per 1 hour lecture, and 1.5 hous per 1 hour seminar". That is a 5x multiplier on a lecture and 2.5x on a seminar.</li>
    <li><strong>The University of St Andrews</strong> takes a third approach entirely, paying preparation as additional claimable hours rather than baking it into a multiplier: "Lecturing &mdash; 3 hours preparation (minimum) per 1" hour of contact time. But it caps the repeat: "Preparation time would only be paid for the first session delivery. Subsequent repeat sessions would be covered by the initial preparation and only contact time should be claimed."</li>
</ul>

<p>So the same phrase, "hourly paid", describes three materially different deals. <strong>The question to ask before you accept anything is not "what is the rate" but "what does the rate include, and what is the multiplier".</strong> If the answer is that the rate is comprehensive, ask what ratio it is built on. If the answer is that preparation is claimed separately, ask whether repeat deliveries are paid.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-lecturer-jobs-uk-teaching.jpg" alt="A tutor leading a small seminar group in a UK university teaching room" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">The contact hour is the visible part. The multiplier decides how much of the invisible part you are paid for.</figcaption>
</figure>

<h2 id="real-rates">What Three Universities Actually Publish</h2>

<p>Very few guides put real numbers on this, because most universities do not publish them. These three do, on their own websites.</p>

<p><strong>University of Cambridge</strong>, substitute teaching schedule effective from 11 August 2025:</p>

<ul>
    <li>Standard lecture rate <strong>&pound;92.10 per hour</strong>, plus &pound;11.12 holiday pay</li>
    <li>Standard seminar rate <strong>&pound;46.05 per hour</strong>, plus &pound;5.56 holiday pay</li>
    <li>Basic hourly rate for a substitute teacher, where the comprehensive rate does not apply: <strong>&pound;18.42</strong></li>
</ul>

<p>Those numbers are worth pausing on, because they show the mechanism perfectly. The lecture rate is the basic rate multiplied by five: &pound;18.42 for the hour you are in the room, and four more hours for the work of getting there. Cambridge is not paying &pound;92 an hour for teaching. It is paying &pound;18.42 an hour for five hours of work, claimed as one.</p>

<p><strong>Oxford Brookes University</strong>, associate lecturer rates current as of 1 August 2025:</p>

<ul>
    <li>Scale point 30: simple basic rate &pound;20.10, comprehensive rate <strong>&pound;50.26</strong></li>
    <li>Scale point 31: simple basic rate &pound;20.68, comprehensive rate <strong>&pound;51.71</strong></li>
    <li>The comprehensive rate "is inclusive of holiday pay"</li>
</ul>

<p>And then the single most revealing line published by any UK university on this subject:</p>

<blockquote><p>"The comprehensive rate includes payment for 20 minutes (0.333 hours) of marking for every scheduled teaching hour worked."</p></blockquote>

<p>Twenty minutes. If you teach a two-hour seminar to twenty students, your marking allowance for that group is forty minutes in total. Brookes does allow more: "an additional allocation of hours for marking and assessments may be assigned to an AL if their line manager considers that the nature of the assessment is more complex than the norm." <strong>That allocation is the thing to negotiate, and it has to be agreed with the line manager before the assessment arrives, not after you are three hours into a stack of scripts.</strong></p>

<p>Rates are renegotiated annually, as Brookes notes: "please note that the rate has the potential to change every academic year depending on the pay negotiations." Check the current year's table rather than a figure you saw in a forum.</p>

<h2 id="the-gap">What the Union's Data Says About Whether the Multiplier Holds</h2>

<p>The multiplier is the theory. The University and College Union surveyed what actually happens, and published the results in June 2019 in a report on casualisation in higher education. After excluding full-time fixed-term staff it had usable data for <strong>1,568 part-time teaching staff</strong>.</p>

<p>Its finding on the multipliers is direct:</p>

<blockquote><p>"These 'multipliers' are almost invariably too low to cover the amount of work associated with preparing and delivering classes, marking work and giving students feedback on their work."</p></blockquote>

<p>And the measurement behind it: on average those part-time teachers were "delivering 45% of their work without being paid for it, while the median figure was 50%". The median respondent was contracted for 10 hours a week and working 20.</p>

<p>UCU's own worked example is the clearest illustration of what that does to a headline rate:</p>

<blockquote><p>"For example, a part-time lecturer contracted and paid for 10 hours and bringing in &pound;187 a week... will have an hourly rate of &pound;18.70. However, if she is in fact working 20 hours a week (which is the median from our survey) she will be paid a real hourly rate of &pound;9.35."</p></blockquote>

<p>The supporting numbers from the same survey: 78 per cent reported regularly working more hours than they are paid for; 67 per cent said they did not have enough paid time to prepare adequately; 73 per cent said they did not have enough paid time to complete their marking; 75 per cent said they did not have enough paid time to keep up with their subject.</p>

<p>None of that means you should not do the work. It means you should go in with the arithmetic in your hand. UCU's own guidance sets its benchmark at "a weighting of at least 2.5 for every teaching hour", to be "increased by local agreement where it can be demonstrated that the time taken to prepare, etc, is greater than 1.5 hours for every teaching hour". If your institution's multiplier is below 2.5, that is a conversation worth having with your department and your local union branch. UCU also notes that "all hourly-paid lecturers should be graded at academic 2 or above".</p>

<h2 id="getting-in">How You Actually Get the Work</h2>

<p>Most hourly-paid teaching is never advertised. Universities keep a pool of approved staff and draw from it when a module needs covering, which means the route in is usually registration rather than application.</p>

<ol>
    <li><strong>Target the department, not the university.</strong> Hiring is decided at module level by a programme leader who knows exactly which seminar group has no tutor. Write to that person.</li>
    <li><strong>Name the module.</strong> Find the programme's module list on the university site and say which specific modules you could teach, by title and code. A generic offer to "teach in your department" is the most common reason for no reply.</li>
    <li><strong>Ask to join the teaching pool</strong> in as many words. Many institutions run a formal register of approved hourly-paid staff, and joining it is a separate process from any vacancy.</li>
    <li><strong>Time it to the academic calendar.</strong> Cover needs surface in the weeks before each semester starts and again when someone goes on leave mid-year.</li>
    <li><strong>Expect a teaching demonstration</strong> or a conversation about how you would run a specific seminar, rather than a formal interview panel.</li>
    <li><strong>Complete right-to-work checks and onboarding before term,</strong> because the first payment cycle depends on it.</li>
</ol>

<p>The academic vacancies that are advertised nationally appear on the sector's academic job board, which you will find easily enough; we do not link job boards here. The higher-yield route for hourly-paid work is the university's own human resources pages and a direct approach to the programme leader.</p>

<h2 id="afhea">The Credential Worth Having: AFHEA</h2>

<p>Associate Fellowship of Advance HE is the recognised teaching credential for exactly the person reading this page. Advance HE's own description names the audience:</p>

<blockquote><p>"Individuals applying for Associate Fellowship may be fairly new to responsibilities in teaching and/or support for learning or may have a limited teaching portfolio. They may be new or experienced staff with specific responsibilities in supporting HE learning such as technicians, librarians, professional service staff, learning technologists, careers advisors, PhD students that teach, graduate teaching assistants, etc."</p></blockquote>

<p>Fellowship comes in four categories, each tied to a descriptor: Associate Fellowship (Descriptor 1), Fellowship (Descriptor 2), Senior Fellowship (Descriptor 3) and Principal Fellowship (Descriptor 4). For Associate Fellowship you evidence three criteria:</p>

<ul>
    <li>"D1.1 use of appropriate Professional Values, including at least V1 and V3"</li>
    <li>"D1.2 application of appropriate Core Knowledge, including at least K1, K2 and K3"</li>
    <li>"D1.3 effective and inclusive practice in at least two of the five Areas of Activity"</li>
</ul>

<p><strong>One thing to get right before you start writing.</strong> The framework was revised and the new version launched on 31 January 2023. Advance HE states that "from 1 January 2024 Advance HE will only accept new fellowship applications based on the PSF 2023". If you are working from a guide or a template built on the 2011 UKPSF, it is out of date for application purposes. Existing fellowships are unaffected: "your existing Fellowship status will not be affected by the PSF 2023."</p>

<h2 id="visa">The Visa Arithmetic, and Why It Is Not Bad Luck</h2>

<p>If you are outside the UK, this is the section that matters more than everything above it, and it is the one most guides get backwards.</p>

<p>A Skilled Worker visa needs an approved sponsor, a certificate of sponsorship, an eligible occupation and a qualifying salary. GOV.UK states the salary test: "The minimum salary for the type of work you'll be doing is whichever is the highest of: <strong>&pound;41,700 per year</strong> / the 'going rate' for the type of work you'll be doing." The current rules came into force on 22 July 2025.</p>

<p>The relevant occupation is <strong>SOC 2020 code 2311, higher education teaching professionals</strong>. Its published going rate is <strong>&pound;52,600 a year, or &pound;26.97 an hour</strong>, with reduced rates of &pound;47,300, &pound;42,100 and &pound;36,800 available at the 90, 80 and 70 per cent tradeable points. The code is eligible for PhD points.</p>

<p>Now the part that decides everything. Under the Immigration Rules, going rates "will be pro-rated to the applicant's working pattern", calculated as the full-time going rate multiplied by the sponsor's stated weekly hours divided by 37.5. <strong>The going rate scales down with your hours. The &pound;41,700 general threshold does not.</strong></p>

<p>That asymmetry is the whole answer. Part-time teaching can reduce the going-rate side of the test, but it cannot reduce the floor, and the floor is an absolute amount you must actually be paid. Hourly-paid and sessional teaching does not produce a salary anywhere near &pound;41,700, so it cannot be sponsored, no matter how good you are or how much the department wants you.</p>

<p>The practical conclusions are unwelcome but clear:</p>

<ul>
    <li><strong>If you need sponsorship, hourly-paid teaching is not your route.</strong> Target a full-time or substantial fractional lectureship where the actual salary clears &pound;41,700 and the pro-rated going rate.</li>
    <li><strong>If you already have the right to work in the UK</strong> &mdash; settled status, a dependant visa, a graduate route visa, a partner visa, British or Irish citizenship &mdash; then everything on this page is open to you, and the teaching pool is a genuinely accessible way in.</li>
    <li><strong>Check your own status on GOV.UK before applying,</strong> and tell the department your status early. Programme leaders will not pursue an applicant they cannot lawfully engage.</li>
</ul>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-lecturer-jobs-uk-rate.jpg" alt="A pay schedule and calculator showing hourly rate calculations" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">The going rate scales with your hours. The general threshold does not. That asymmetry is why hourly teaching cannot be sponsored.</figcaption>
</figure>

<h2 id="the-way-out">The Route Off the Hourly Contract</h2>

<p>Hourly-paid work is a door, not a destination, and at least one university publishes the way through it. Oxford Brookes commits to a review:</p>

<blockquote><p>"At the commencement of the third year following two successive academic years of working 110 or more hours (at a basic or comprehensive rate and within one faculty), considerations will be given to whether a permanent fractional contract can be offered to the AL."</p></blockquote>

<p>One hundred and ten hours, in one faculty, for two years running. That is a target you can plan against, and it is the reason to concentrate your teaching in a single faculty rather than spreading it thinly across three institutions for the same total income.</p>

<p>The union's position is that the hourly contract should not exist at all: "UCU policy is that all hourly paid staff should be employed on pro-rata or full time (as appropriate) contracts with equal conditions of employment to non-hourly paid staff." Its 2019 survey found that 80 per cent of hourly-paid respondents "would rather be on a contract that guaranteed them hours, even if it meant less flexibility".</p>

<p>So: take the hours, keep a precise record of every one you work, concentrate them in one faculty, join the union branch, and treat the fractional contract as the actual goal.</p>

<h2 id="faq">Frequently Asked Questions</h2>

<h3>What is an adjunct lecturer called in the UK?</h3>
<p>Hourly-paid lecturer, associate lecturer, sessional lecturer or visiting lecturer. "Adjunct" is a United States term and UK universities do not advertise with it, so search the UK titles instead.</p>

<h3>How much does an hourly-paid lecturer earn in the UK?</h3>
<p>It depends on the multiplier, not just the rate. Oxford Brookes publishes a comprehensive rate of GBP 50.26 at scale point 30, being 2.5 times its simple basic rate of GBP 20.10. Cambridge publishes GBP 92.10 an hour for a lecture, which is its GBP 18.42 basic rate times five, because its lecture rate includes four hours of preparation.</p>

<h3>Does the hourly rate include preparation and marking?</h3>
<p>Usually yes, and that is the point. A comprehensive rate bundles preparation, marking and administration into the figure you claim for each teaching hour, and Oxford Brookes states that associate lecturers "must not claim for these activities separately in addition to the comprehensive rate". Some universities, such as St Andrews, instead pay preparation as separate claimable hours, but only for the first delivery of a session.</p>

<h3>How much marking time am I actually paid for?</h3>
<p>Less than you will need. Oxford Brookes states that its comprehensive rate "includes payment for 20 minutes (0.333 hours) of marking for every scheduled teaching hour worked". Extra allocation is possible where an assessment is more complex than the norm, but it has to be agreed with your line manager in advance.</p>

<h3>Do I need a PhD to be an adjunct lecturer in the UK?</h3>
<p>Not always. Academic departments usually expect a PhD or one near completion, while vocational and professional subjects often accept a Master's with substantial industry experience. What every department wants is a match to a specific module.</p>

<h3>Can I get a Skilled Worker visa as an hourly-paid lecturer?</h3>
<p>Almost never, and for a structural reason. Going rates pro-rate down to your working pattern under the Immigration Rules, but the GBP 41,700 general threshold does not, so part-time hourly teaching cannot reach the floor. You would need a full-time or substantial fractional post paying at least GBP 41,700 and the pro-rated going rate for SOC code 2311, which is GBP 52,600 at full time.</p>

<h3>What is AFHEA and is it worth getting?</h3>
<p>Associate Fellowship of Advance HE, the entry category of a four-level fellowship scheme. Advance HE names PhD students who teach and graduate teaching assistants among the people it is designed for. Since 1 January 2024 Advance HE only accepts new applications under the PSF 2023, so do not work from a 2011 UKPSF template.</p>

<h3>How do I get onto a university teaching pool?</h3>
<p>Write directly to the programme leader for the modules you could teach, name those modules by title and code, and ask explicitly to be added to the department's register of approved hourly-paid staff. Most of this work is never advertised, so joining the pool matters more than watching vacancy pages.</p>

<h2 id="people-also-search-for">People Also Search For</h2>

<h3>Hourly paid lecturer rates UK</h3>
<p>Published by some universities on their own HR pages, usually as a comprehensive rate covering teaching plus preparation and marking.</p>

<h3>Associate lecturer meaning</h3>
<p>A part-time, teaching-focused academic role, often on a contract with a set allocation of teaching rather than purely casual hours.</p>

<h3>Comprehensive rate teaching hour</h3>
<p>The bundled rate paid per scheduled teaching hour, typically 2.5 times the basic hourly rate following the 2004 JNCHES convention.</p>

<h3>UCU casualisation report</h3>
<p>The union's June 2019 survey of 1,568 part-time teaching staff, which found a median of 50 per cent of their work going unpaid.</p>

<h3>AFHEA application requirements</h3>
<p>Evidence against the three Descriptor 1 criteria of the PSF 2023, covering Professional Values, Core Knowledge and at least two of the five Areas of Activity.</p>

<h3>SOC code 2311 going rate</h3>
<p>Higher education teaching professionals, with a full-time going rate of GBP 52,600 a year for Skilled Worker sponsorship purposes.</p>

<h3>Sessional teaching contract UK</h3>
<p>An engagement for a specific module or teaching block, usually without guaranteed hours beyond that block.</p>

<h3>Fractional contract university</h3>
<p>A permanent part-time academic contract expressed as a fraction of full time, and the usual destination for people moving off hourly-paid work.</p>

<h2 id="more-guides">More Job Guides</h2>

<ul>
    <li><a href="/blog/adjunct-professor-jobs-in-the-uk">Adjunct Professor Jobs in the UK</a> &mdash; the honorary title, why it is conferred rather than applied for, and why it pays nothing.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the sponsorship routes that clear the salary floor.</li>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; teaching income that does not depend on a UK visa.</li>
    <li><a href="/blog/how-to-get-an-esl-teaching-job-in-japan">How to Get an ESL Teaching Job in Japan</a> &mdash; a teaching route with a far more accessible visa.</li>
    <li><a href="/blog/educational-support-jobs-in-usa">Educational Support Jobs in USA</a> &mdash; the equivalent entry route into a different education system.</li>
</ul>

<h2 id="sources">Official Sources</h2>

<ul>
    <li><a href="https://www.hr.admin.cam.ac.uk/" rel="nofollow noopener" target="_blank">University of Cambridge &mdash; substitute teaching schedule of payment rates</a>, effective from 11 August 2025</li>
    <li><a href="https://www.brookes.ac.uk/staff/working-at-brookes/employment-policies/recruitment/temporary-and-casual/associate-lecturers/employment-of-associate-lecturers" rel="nofollow noopener" target="_blank">Oxford Brookes University &mdash; Employment of Associate Lecturers</a>, effective 28 May 2026, rates current as of 1 August 2025</li>
    <li><a href="https://www.st-andrews.ac.uk/policy/staff-pay-and-benefits-pay-rates-arrangements/pay-rates-and-arrangements-for-hourly-paid-teaching-staff-including-pgr-tutors.pdf" rel="nofollow noopener" target="_blank">University of St Andrews &mdash; pay rates and arrangements for hourly paid teaching staff</a>, July 2025</li>
    <li><a href="https://www.advance-he.ac.uk/fellowship/associate-fellowship" rel="nofollow noopener" target="_blank">Advance HE &mdash; Associate Fellowship</a> and the <a href="https://www.advance-he.ac.uk/teaching-and-learning/psf" rel="nofollow noopener" target="_blank">Professional Standards Framework 2023</a></li>
    <li><a href="https://www.ucu.org.uk/" rel="nofollow noopener" target="_blank">University and College Union</a> &mdash; <em>Counting the costs of casualisation in higher education</em>, June 2019, and its guidance on calculating hourly rates, which carries the 2004 JNCHES guidance for post-92 institutions</li>
    <li><a href="https://www.gov.uk/skilled-worker-visa" rel="nofollow noopener" target="_blank">GOV.UK &mdash; Skilled Worker visa</a>, and <a href="https://www.gov.uk/guidance/immigration-rules/immigration-rules-appendix-skilled-worker" rel="nofollow noopener" target="_blank">Immigration Rules Appendix Skilled Worker</a> for the pro-rating rule at SW 14.4</li>
</ul>

<p style="font-size:14px;color:#666;margin-top:26px;">Checked on 30 September 2026. University pay rates are renegotiated annually and immigration rules change; confirm the current figures on the university's own pages and on GOV.UK before acting. JobGader is not a recruiter and does not accept payment from applicants.</p>
HTML;
    }
}
