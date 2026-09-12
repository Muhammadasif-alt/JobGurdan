<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The University of Sydney's Research Training Program (RTP) scholarship for
 * international students, written up as a plain-English guide.
 *
 * Checked on 10 September 2026 against the official RTP International page
 * and the RTP Stipend (International) terms and conditions. Corrections to
 * the brief and the posters:
 *
 * 1. The brief says there is no separate scholarship form and consideration
 *    is automatic. The terms require both an admission application and the
 *    scholarship application form by the deadline.
 * 2. The brief estimates the 2027 stipend at about $44,000. The university
 *    has published it: $44,293.
 * 3. The brief lists "departmental ranking" as a selection criterion. The
 *    terms list academic merit, research experience, the research
 *    environment and supervision.
 * 4. Every poster carries Monash's email and phone number in its footer. The
 *    published copies have that block replaced with the university's own
 *    website, and the one poster claiming QS #18 (Sydney is equal 25th in the
 *    2026 table) is not used.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class SydneyRtpInternationalScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-sydney-rtp-international-scholarship';

    public const APPLY_URL = 'https://www.sydney.edu.au/scholarships/australian-government-research-training-program/rtp-international.html';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Sydney RTP International Scholarship 2026–27',
                'provider' => 'The University of Sydney',
                'country' => 'Australia',
                'city' => 'Sydney',
                'study_level' => "PhD, Master's by Research",
                'funding_type' => 'Fully Funded',
                'award_value' => 'AUD $42,754 per year',
                'deadline' => '2026-09-11',
                'deadline_note' => 'Next round closes 18 Dec 2026',
                'excerpt' => "Sydney's RTP International Scholarship pays AUD $42,754 a year plus full tuition and health cover for a PhD or research master's. Deadlines, eligibility and how to apply.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/university-of-sydney-rtp-international-scholarship.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Sydney RTP International Scholarship 2026: How to Apply',
                'meta_description' => "The University of Sydney's RTP International Scholarship pays AUD $42,754 a year plus full tuition and OSHC. Deadlines, eligibility and how to apply.",
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-10 00:05:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>Research Training Program (RTP) International Scholarship</strong> at the University of Sydney pays international students a living allowance of <strong>AUD \$42,754 a year</strong> and covers <strong>100% of tuition fees</strong> and health cover while they complete a PhD or a master's by research. It is funded by the Australian Government and awarded competitively by the university.</p>

<p>The University of Sydney placed equal 25th in the world in the QS World University Rankings 2026. This guide explains, in plain English, what the scholarship pays, who can get it, the deadlines and exactly how to apply.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Deadline alert:</strong> applications to start in Research Period 1 or 2 of 2027 close on <strong>11 September 2026</strong>. If you miss it, the next deadline is <strong>18 December 2026</strong>, for a start in Research Period 3 or 4 of 2027.</p>
</div>

<h2>Sydney RTP International Scholarship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>The University of Sydney, Australia</td></tr>
<tr><th>Funded by</th><td>The Australian Government, with an equivalent University of Sydney scholarship awarded in the same round</td></tr>
<tr><th>Study level</th><td>PhD and master's by research</td></tr>
<tr><th>Who can apply</th><td>International students starting or already enrolled in a research degree</td></tr>
<tr><th>Living allowance</th><td>AUD \$42,754 a year (2026 rate), AUD \$44,293 a year (2027 rate)</td></tr>
<tr><th>Tuition fees</th><td>100% covered for up to 14 research periods</td></tr>
<tr><th>Health cover</th><td>Overseas Student Health Cover (OSHC) included</td></tr>
<tr><th>Relocation allowance</th><td>Up to AUD \$1,485 to travel to Sydney, and up to AUD \$1,485 to travel home when you finish</td></tr>
<tr><th>Thesis allowance</th><td>Up to AUD \$840 for a PhD, AUD \$420 for a master's</td></tr>
<tr><th>How long it lasts</th><td>Up to 3.5 years for a PhD, with no extension</td></tr>
<tr><th>How to apply</th><td>Apply for admission <em>and</em> submit the scholarship application form by the deadline</td></tr>
<tr><th>Next deadlines</th><td>11 September 2026, then 18 December 2026</td></tr>
</tbody>
</table></div>

<h2>What the Scholarship Pays</h2>

<h3>1. A living allowance</h3>
<p>The stipend is <strong>AUD \$42,754 a year at the 2026 rate</strong>, and it is indexed every 1 January. The university has already published the <strong>2027 rate of AUD \$44,293 a year</strong>, which is AUD \$1,539 more. The stipend is for rent, food and everyday costs, and you do not pay it back.</p>

<h3>2. Full tuition fees and health cover</h3>
<p>If you are awarded the RTP Stipend, you are <strong>automatically awarded the RTP Fee Offset</strong> as well. It pays <strong>100% of your tuition fees for up to 14 research periods</strong> and includes Overseas Student Health Cover (OSHC).</p>
<p>The university also awards an equivalent scholarship, the University of Sydney International Stipend Scholarship (USYDIS), in the same process. It pays the same stipend, and its holders automatically receive the University of Sydney Tuition Fee Scholarship, which covers 100% of tuition fees for up to 14 research periods.</p>

<h3>3. A relocation allowance</h3>
<p>If you move to Sydney from outside the Sydney metropolitan area to start your degree, you can claim up to <strong>AUD \$1,485</strong> for eligible travel from your home country, and up to <strong>AUD \$1,485</strong> more to travel home after you successfully finish. It covers your own move only, not family members, and you cannot claim it if you had already started your degree before the scholarship was awarded.</p>

<h3>4. A thesis allowance</h3>
<p>You can be reimbursed up to <strong>AUD \$840</strong> as a PhD student, or up to <strong>AUD \$420</strong> as a master's student, for the direct costs of producing your thesis. Printing costs do not count, because a printed thesis is no longer required.</p>

<h3>5. Paid leave</h3>
<p>Scholarship holders get up to 20 working days of paid holiday every 12 months, taken with their supervisor's agreement, and up to 10 working days of sick leave every 12 months.</p>

<h3>6. How long the money lasts</h3>
<p>The scholarship runs for up to <strong>14 research periods for a PhD</strong> and up to <strong>7 research periods for a master's by research</strong>, studying full-time. The university splits each year into four research periods, so a PhD scholarship lasts up to three and a half years. <strong>No extension is possible</strong>, and any time you have already studied towards the degree is taken off.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-sydney-rtp-international-scholarship-overview.jpg" alt="University of Sydney RTP International Scholarship poster showing AUD 42,754 per year, full tuition, OSHC and relocation allowance" width="1200" height="628" loading="lazy">
<figcaption>The scholarship at a glance: stipend, full tuition, health cover and a relocation allowance.</figcaption>
</figure>

<h2>Who Can Apply</h2>
<ul>
<li>You must be an <strong>international student</strong> when the scholarship is awarded. Students of any nationality can apply.</li>
<li>You must be <strong>starting or already enrolled in a higher degree by research</strong> (a PhD or a master's by research) at the University of Sydney.</li>
<li>You are expected to study <strong>full-time</strong>. A part-time scholarship is only approved in special circumstances, and only if your visa allows part-time study.</li>
<li>You must apply for admission and submit the scholarship application form by the deadline.</li>
</ul>
<p>Australian and New Zealand citizens and Australian permanent residents apply for the domestic version instead, which has different deadlines. See our <a href="/scholarships/university-of-sydney-rtp-domestic-scholarship">University of Sydney RTP Domestic Scholarship guide</a>.</p>

<h3>Who gets preference</h3>
<p>The university may give preference to applicants who:</p>
<ul>
<li>Plan to study, or are already studying, a PhD</li>
<li>Have not already received an Australian Government or university scholarship for a research degree at the same level</li>
<li>Have not already completed a research degree at the same level</li>
<li>Identify as Aboriginal and/or Torres Strait Islander</li>
</ul>

<h3>How winners are chosen</h3>
<p>Your faculty recommends candidates, and the university's Higher Degrees by Research Scholarships Sub-Committee makes the award. You are judged on:</p>
<ol>
<li><strong>Academic merit</strong> &mdash; your grades and degrees</li>
<li><strong>Research experience</strong> &mdash; theses, publications and research work</li>
<li><strong>The research environment</strong> &mdash; whether the university has the resources your project needs</li>
<li><strong>Supervision</strong> &mdash; whether high-quality supervision is available for your project</li>
</ol>
<p>The last two points mean your choice of project and supervisor matters as well as your grades. The official scholarship page also has a <em>research experience evidence template</em> and a guide to filling it in, so use them to present your research experience.</p>

<h2>Degrees You Can Study</h2>
<ul>
<li><strong>Doctor of Philosophy (PhD)</strong></li>
<li><strong>Master's by research</strong>, such as the Master of Philosophy (MPhil)</li>
</ul>
<p>The scholarship does not cover coursework master's degrees. Research degrees are offered across the university, including engineering, science, medicine and health, business, law, and arts and social sciences. Planning a business PhD? Our <a href="/scholarships/university-of-sydney-business-school-phd-scholarships">University of Sydney Business School PhD Scholarships guide</a> explains the scholarships the Business School offers itself.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-sydney-rtp-international-scholarship-students.jpg" alt="International students in front of the University of Sydney quadrangle with the RTP International Scholarship details" width="1200" height="628" loading="lazy">
<figcaption>Open to international students of every nationality, for a PhD or a master's by research.</figcaption>
</figure>

<h2>English Language Requirements</h2>
<p>If English is not your first language, you need to meet the University of Sydney's English requirement. The standard level is <strong>IELTS Academic 6.5 overall, with no band below 6.0</strong>, but some faculties ask for more. Research degrees at the University of Sydney Business School, for example, need IELTS 7.0 overall with at least 6.5 in each band. Check your course page for the exact score before you book a test.</p>

<h2>Deadlines for International Students</h2>
<div class="scholar-table"><table>
<thead><tr><th>If you want to start in</th><th>Apply by</th><th>First outcomes from</th><th>All outcomes by</th></tr></thead>
<tbody>
<tr><td>Research Period 1 or 2, 2027</td><td><strong>11 September 2026</strong></td><td>10 November 2026</td><td>17 December 2026</td></tr>
<tr><td>Research Period 3 or 4, 2027</td><td><strong>18 December 2026</strong></td><td>2 March 2027</td><td>18 May 2027 if applying from overseas, 30 June 2027 if finishing a degree in Australia</td></tr>
<tr><td>Research Period 1 or 2, 2028</td><td>10 September 2027</td><td>16 November 2027</td><td>20 December 2027</td></tr>
</tbody>
</table></div>
<p>By each deadline, both your admission application with all its documents <em>and</em> your scholarship application form must be in. The university gives only estimated dates for Research Period 3 or 4 of 2028, with applications closing in mid-December 2027.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-sydney-rtp-international-scholarship-graduate.jpg" alt="University of Sydney RTP International Scholarship poster with the 11 September 2026 application deadline" width="1200" height="628" loading="lazy">
<figcaption>The 11 September 2026 deadline is for a start in Research Period 1 or 2 of 2027. The next one is 18 December 2026.</figcaption>
</figure>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Pick your degree and research area.</strong> Find the PhD or master's by research you want on the University of Sydney website, and check its entry and English requirements.</li>
<li><strong>Find a potential supervisor.</strong> Look for academics researching your topic and email them a short outline of your idea. Available supervision is part of how the scholarship is decided.</li>
<li><strong>Write your research proposal.</strong> Explain your research question, how you plan to answer it and why the University of Sydney is the right place to do it.</li>
<li><strong>Prepare evidence of your research experience</strong> with the template and guide on the official scholarship page.</li>
<li><strong>Apply for admission through Sydney Courses</strong>, the university's online application, and upload every document it asks for before the deadline.</li>
<li><strong>Submit the scholarship application form</strong> from the official RTP scholarship page. This is a separate step: an admission application on its own does not put you in the running.</li>
<li><strong>Watch your email.</strong> The outcome is sent to the email address in your admission application.</li>
<li><strong>If you receive an offer</strong>, accept your admission and scholarship offers, then apply for your Australian student visa.</li>
</ol>

<div class="scholar-note">
<p><strong>Not automatic.</strong> Some summaries say you are considered automatically when you apply for your course. For this scholarship the university asks new students to do two things by the deadline: apply for admission with all documents, <em>and</em> submit the scholarship application form.</p>
</div>

<h2>Documents to Prepare</h2>
<p>Your course application lists exactly what to upload. Most research applicants should have these ready:</p>
<ul>
<li>A copy of your passport</li>
<li>Academic transcripts and degree certificates, with certified English translations of anything not in English</li>
<li>Your English test results</li>
<li>A research proposal</li>
<li>An academic CV listing your research, publications and awards</li>
<li>Evidence of your research experience, set out with the university's template</li>
<li>Referees who know your research work</li>
</ul>

<h2>University of Sydney vs Monash: RTP Scholarships Compared</h2>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>University of Sydney</th><th>Monash University</th></tr></thead>
<tbody>
<tr><td>Living allowance (2026)</td><td>AUD \$42,754 a year</td><td>AUD \$37,145 a year</td></tr>
<tr><td>International tuition</td><td>100% covered by the RTP Fee Offset, awarded automatically with the stipend</td><td>Covered by a separate award (MITS), generally given with the stipend</td></tr>
<tr><td>Health cover</td><td>OSHC included with the RTP Fee Offset</td><td>Single OSHC included with MITS</td></tr>
<tr><td>Relocation</td><td>Up to AUD \$1,485 each way</td><td>AUD \$2,000 from overseas, if eligible</td></tr>
<tr><td>PhD length</td><td>Up to 3.5 years, no extension</td><td>Up to 3 years 6 months</td></tr>
<tr><td>How to apply</td><td>Admission application plus a separate scholarship form</td><td>One application, sent during an open round</td></tr>
<tr><td>Next international deadline</td><td>11 September 2026, then 18 December 2026</td><td>Round 1 for 2027, closing date to be confirmed</td></tr>
</tbody>
</table></div>
<p>At 2026 rates the Sydney stipend is AUD \$5,609 a year higher. The better choice still depends on where the right supervisor and project are for you. Read our full <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP Scholarship guide</a>, <a href="/scholarships/australian-national-university-rtp-scholarship">ANU RTP Scholarship guide</a> and <a href="/scholarships/university-of-melbourne-rtp-scholarship">University of Melbourne RTP Scholarship guide</a> to compare.</p>
<p>Weighing Europe instead? Italian state universities work the other way round: there is no research stipend, but tuition is set by family income and can fall to almost nothing. Our <a href="/scholarships/university-of-pavia-scholarships">University of Pavia scholarships guide</a>, <a href="/scholarships/university-of-insubria-scholarships">University of Insubria scholarships guide</a> and <a href="/scholarships/polytechnic-university-of-marche-scholarships">Polytechnic University of Marche scholarships guide</a> set out what an Italian degree really costs and which waivers cover it.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can international students get the University of Sydney RTP Scholarship?</h3>
<p>Yes. It is open to international students of any nationality who are starting or already enrolled in a PhD or master's by research at the University of Sydney. It is competitive, so a strong academic and research record matters.</p>

<h3>Does the Sydney RTP International Scholarship cover tuition fees?</h3>
<p>Yes. Students awarded the RTP Stipend automatically receive the RTP Fee Offset, which pays 100% of tuition fees for up to 14 research periods and includes Overseas Student Health Cover.</p>

<h3>How much is the University of Sydney RTP Scholarship worth?</h3>
<p>AUD \$42,754 a year at the 2026 rate, rising to AUD \$44,293 a year at the 2027 rate, plus relocation and thesis allowances.</p>

<h3>When is the deadline for international students?</h3>
<p>11 September 2026 to start in Research Period 1 or 2 of 2027. The next deadline is 18 December 2026, to start in Research Period 3 or 4 of 2027.</p>

<h3>Am I considered automatically when I apply for my course?</h3>
<p>No. You must apply for admission with all your documents and also submit the scholarship application form, both by the deadline.</p>

<h3>What IELTS score do I need for the University of Sydney?</h3>
<p>The standard requirement is IELTS Academic 6.5 overall with no band below 6.0. Some faculties ask for more, such as 7.0 overall with at least 6.5 in each band for research degrees at the Business School.</p>

<h3>Can the scholarship be extended?</h3>
<p>No. It lasts up to 14 research periods for a PhD and 7 for a master's by research, and the terms say no extension is possible.</p>

<h3>Can I earn other income while holding the scholarship?</h3>
<p>Another award, scholarship or salary for your research must be worth less than 75% of the scholarship, or the scholarship ends. Pay for work unrelated to your research does not count towards that limit, but your student visa has its own work rules.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official RTP International scholarship page and application form</a></li>
<li><a href="https://www.sydney.edu.au/study/applying/how-to-apply/postgraduate-research.html" target="_blank" rel="noopener">How to apply for postgraduate research</a></li>
<li><a href="https://www.sydney.edu.au/study/applying/how-to-apply/international-students/english-language-requirements.html" target="_blank" rel="noopener">English language requirements</a></li>
<li><a href="https://www.sydney.edu.au/content/dam/corporate/documents/scholarships/rtp-scholarships-/updated-tcs---rtp-international-stipend.pdf" target="_blank" rel="noopener">RTP Stipend (International) terms and conditions</a></li>
</ul>

<p><em>JobGader is not part of the University of Sydney. This guide was checked against the university's official pages on 10 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
