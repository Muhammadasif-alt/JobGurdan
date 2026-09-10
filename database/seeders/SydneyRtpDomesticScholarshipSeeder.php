<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The University of Sydney's Research Training Program (RTP) scholarship for
 * domestic students, written up as a plain-English guide.
 *
 * Checked on 10 September 2026 against the official RTP Domestic page, the RTP
 * Stipend (Domestic) terms and conditions and the HDR scholarship selection
 * process document. Corrections to the brief and the posters:
 *
 * 1. The posters say "Australian citizens & NZ residents". The terms say
 *    Australian citizens, New Zealand citizens and Australian permanent
 *    residents; the published posters have that badge corrected.
 * 2. The posters say "No tuition reduction". The terms say the Australian
 *    Government pays tuition for up to 16 research periods (PhD) or 8
 *    (master's); the heading is corrected, and the one poster with a
 *    crossed-out graduation cap beside it is not used.
 * 3. The brief says there is no separate scholarship form and consideration
 *    is automatic. The terms require both an admission application and the
 *    scholarship application form by the deadline.
 * 4. The brief calls the stipend tax-free. The terms only warn that a
 *    part-time scholarship may have tax implications.
 * 5. The brief lists a departmental ranking as a selection criterion. The
 *    selection process ranks marks, Honours 1 or equivalent, research
 *    master's, the world ranking of the awarding institution and research
 *    experience.
 * 6. The brief estimates the 2027 stipend at about $44,000; the university
 *    has published $44,293. It gives no relocation amounts; the terms do.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class SydneyRtpDomesticScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-sydney-rtp-domestic-scholarship';

    public const APPLY_URL = 'https://www.sydney.edu.au/scholarships/australian-government-research-training-program/rtp-domestic.html';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Sydney RTP Domestic Scholarship 2026–27',
                'provider' => 'The University of Sydney',
                'country' => 'Australia',
                'city' => 'Sydney',
                'study_level' => "PhD, Master's by Research",
                'funding_type' => 'Fully Funded',
                'award_value' => 'AUD $42,754 per year',
                'deadline' => '2026-10-12',
                'deadline_note' => 'Next round closes 19 Mar 2027',
                'excerpt' => "Sydney's RTP Domestic Scholarship pays Australian and NZ citizens and permanent residents AUD $42,754 a year for a PhD or research master's. Deadlines, ranking and how to apply.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/university-of-sydney-rtp-domestic-scholarship.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Sydney RTP Domestic Scholarship 2026: How to Apply',
                'meta_description' => "The University of Sydney's RTP Domestic Scholarship pays AUD $42,754 a year with tuition covered. Eligibility, the 12 October 2026 deadline and how to apply.",
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-10 00:10:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>Research Training Program (RTP) Domestic Scholarship</strong> at the University of Sydney pays Australian and New Zealand citizens and Australian permanent residents a living allowance of <strong>AUD \$42,754 a year</strong> while they complete a PhD or a master's by research. The Australian Government also pays their tuition fees. Places are awarded competitively, so how you present your marks and research experience really matters.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Deadline:</strong> applications to start in Research Period 1 or 2 of 2027 close on <strong>12 October 2026</strong>. The next deadline after that is <strong>19 March 2027</strong>, for a start in Research Period 3 or 4 of 2027.</p>
</div>

<div class="scholar-note">
<p><strong>International student?</strong> This scholarship is not for you. Read our <a href="/scholarships/university-of-sydney-rtp-international-scholarship">University of Sydney RTP International Scholarship guide</a> instead &mdash; it has earlier deadlines and also covers tuition and health cover.</p>
</div>

<h2>Sydney RTP Domestic Scholarship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>The University of Sydney, Australia</td></tr>
<tr><th>Funded by</th><td>The Australian Government, with an equivalent University of Sydney Postgraduate Award (UPA) given in the same process</td></tr>
<tr><th>Who can apply</th><td>Australian citizens, New Zealand citizens and Australian permanent residents</td></tr>
<tr><th>Study level</th><td>PhD and master's by research</td></tr>
<tr><th>Living allowance</th><td>AUD \$42,754 a year (2026 rate), AUD \$44,293 a year (2027 rate)</td></tr>
<tr><th>Tuition fees</th><td>Paid by the Australian Government for up to 16 research periods for a PhD, or 8 for a master's</td></tr>
<tr><th>Relocation allowance</th><td>Up to AUD \$515 per adult and AUD \$255 per child, AUD \$1,485 in total</td></tr>
<tr><th>Thesis allowance</th><td>Up to AUD \$840 for a PhD, AUD \$420 for a master's</td></tr>
<tr><th>How long the stipend lasts</th><td>Up to 3.5 years for a PhD, with no extension</td></tr>
<tr><th>How to apply</th><td>Apply for admission <em>and</em> submit the scholarship application form by the deadline</td></tr>
<tr><th>Next deadlines</th><td>12 October 2026, then 19 March 2027</td></tr>
</tbody>
</table></div>

<h2>What the Scholarship Pays</h2>

<h3>1. A living allowance</h3>
<p>The stipend is <strong>AUD \$42,754 a year at the 2026 rate</strong>, and it is indexed every 1 January. The university has already published the <strong>2027 rate of AUD \$44,293 a year</strong>, which is AUD \$1,539 more. Spread over 12 months, the 2026 rate works out at about <strong>AUD \$3,563 a month</strong>. It is for rent, food and everyday costs, and you do not pay it back.</p>

<h3>2. Your tuition fees</h3>
<p>As an eligible research student, your tuition fees are paid by the Australian Government's Research Training Program for up to <strong>16 research periods</strong> for a PhD and up to <strong>8 research periods</strong> for a master's by research. That is longer than the stipend, which runs for up to 14 and 7 research periods.</p>
<p>So despite what some summaries say, domestic research students do not pay their own tuition while they are within those limits.</p>

<h3>3. A relocation allowance</h3>
<p>If you move from outside the Sydney metropolitan area to start your degree, you can claim eligible moving costs of up to <strong>AUD \$515 for each eligible adult</strong> and <strong>AUD \$255 for each eligible child</strong>. The most you can receive in total is <strong>AUD \$1,485</strong>. You cannot claim it if you had already started your degree before the scholarship was awarded.</p>

<h3>4. A thesis allowance</h3>
<p>You can be reimbursed up to <strong>AUD \$840</strong> as a PhD student, or up to <strong>AUD \$420</strong> as a master's student, for the direct costs of producing your thesis. Printing costs do not count, because a printed thesis is no longer required.</p>

<h3>5. Paid leave</h3>
<ul>
<li>Up to <strong>20 working days of paid holiday</strong> every 12 months, taken with your supervisor's agreement</li>
<li>Up to <strong>10 working days of sick leave</strong> every 12 months, plus up to 12 extra weeks of paid sick leave for a medically supported illness</li>
<li>Up to <strong>12 weeks of paid parental leave</strong> once you have held the scholarship for 12 months</li>
</ul>
<p>Extra paid sick leave and paid parental leave are added on to the length of your scholarship.</p>

<h3>6. How long the money lasts</h3>
<p>The stipend runs for up to <strong>14 research periods for a PhD</strong> and up to <strong>7 research periods for a master's by research</strong>, studying full-time. The university splits each year into four research periods, so a PhD stipend lasts up to three and a half years. <strong>No extension is possible</strong>, and any time you have already studied towards the degree is taken off.</p>

<h3>7. Tax</h3>
<p>The university's terms do not set out the stipend's tax position, apart from a warning that a <strong>part-time</strong> scholarship may have tax implications and that you should get advice from a registered tax agent. If you plan to study part-time, get that advice before you accept.</p>

<h2>Who Can Apply</h2>
<ul>
<li>You must be an <strong>Australian citizen</strong>, a <strong>New Zealand citizen</strong> or an <strong>Australian permanent resident</strong> when the scholarship is awarded.</li>
<li>You must be <strong>starting or already enrolled in a higher degree by research</strong> (a PhD or a master's by research) at the University of Sydney.</li>
<li>You are expected to study <strong>full-time</strong>. A part-time scholarship is only approved in special circumstances beyond your control, such as a medical condition, financial hardship or caring responsibilities.</li>
<li>New students must apply for admission, and everyone must submit the scholarship application form, by the deadline.</li>
</ul>

<h3>Who gets preference</h3>
<p>The university may give preference to applicants who:</p>
<ul>
<li>Identify as Aboriginal and/or Torres Strait Islander</li>
<li>Plan to study, or are already studying, a PhD</li>
<li>Have not already received an Australian Government or university scholarship for a research degree at the same level</li>
<li>Have not already completed a research degree at the same level</li>
</ul>

<h2>How Applicants Are Ranked</h2>
<p>The Scholarships Office assesses applications first, faculties review them, and the university's Higher Degree by Research Scholarship Sub-Committee decides how many offers to make. Applicants doing their <strong>first PhD</strong> are the first priority.</p>
<p>You are assessed on a combination of these, where they apply to you:</p>
<ul>
<li>Your undergraduate weighted average mark (WAM)</li>
<li>Your First Class Honours (H1) result, or a degree accepted as equivalent to it</li>
<li>A completed master's by research</li>
<li>The <strong>world ranking of the university</strong> that awarded your qualification</li>
<li>Your research experience</li>
</ul>

<h3>How to make your marks count</h3>
<ul>
<li><strong>Give numbers, not just grades.</strong> The university asks for numerical marks wherever possible. When only a grade is available, it enters a set mark instead: 57 for a Pass, 69.5 for a Credit, 78.33 for a Distinction and 85 for a High Distinction. If your real marks are higher, show them.</li>
<li><strong>Include your grading scale.</strong> Show the range of marks for each grade and honours level, usually printed on the back of your transcript, so your marks can be scaled properly.</li>
<li><strong>No Honours 1?</strong> A completed Master of Philosophy by thesis counts as Honours 1 equivalent, at a mark of 85 unless your thesis mark was higher. A coursework master's with an average of at least 80 and a substantial research component can also count.</li>
<li><strong>Document your research experience</strong> with the evidence template and guide on the official scholarship page.</li>
</ul>

<h2>Degrees You Can Study</h2>
<ul>
<li><strong>Doctor of Philosophy (PhD)</strong></li>
<li><strong>Master's by research</strong>, such as the Master of Philosophy (MPhil)</li>
</ul>
<p>The scholarship does not cover coursework master's degrees. Research degrees are offered across the university, including engineering, science, medicine and health, business, law, and arts and social sciences. Planning a business PhD? Our <a href="/scholarships/university-of-sydney-business-school-phd-scholarships">University of Sydney Business School PhD Scholarships guide</a> explains the scholarships the Business School offers itself.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-sydney-rtp-domestic-scholarship-graduates.jpg" alt="Graduates at the University of Sydney with the RTP Domestic Scholarship details" width="1200" height="628" loading="lazy">
<figcaption>Open to Australian and New Zealand citizens and Australian permanent residents, for a PhD or a master's by research.</figcaption>
</figure>

<h2>Deadlines for Domestic Students</h2>
<div class="scholar-table"><table>
<thead><tr><th>If you want to start in</th><th>Apply by</th><th>First outcomes from</th><th>All outcomes by</th></tr></thead>
<tbody>
<tr><td>Research Period 1 or 2, 2027</td><td><strong>12 October 2026</strong></td><td>17 December 2026</td><td>2 March 2027</td></tr>
<tr><td>Research Period 3 or 4, 2027</td><td><strong>19 March 2027</strong></td><td>18 May 2027</td><td>30 June 2027</td></tr>
<tr><td>Research Period 1 or 2, 2028</td><td>11 October 2027</td><td>20 December 2027</td><td>Mid-February 2028 (estimate)</td></tr>
<tr><td>Research Period 3 or 4, 2028</td><td>Mid-March 2028 (estimate)</td><td>Mid-May 2028 (estimate)</td><td>30 June 2028 (estimate)</td></tr>
</tbody>
</table></div>
<p>By each deadline, both your admission application with all its documents <em>and</em> your scholarship application form must be in. International students have earlier deadlines: 11 September 2026 and 18 December 2026.</p>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Pick your degree and research area.</strong> Find the PhD or master's by research you want on the University of Sydney website, and check its entry requirements.</li>
<li><strong>Find a potential supervisor.</strong> Look for academics researching your topic and email them a short outline of your idea. Good supervision being available is part of how scholarships are decided.</li>
<li><strong>Write your research proposal.</strong> Explain your research question, how you plan to answer it and why the University of Sydney is the right place to do it.</li>
<li><strong>Gather your marks.</strong> Get transcripts with numerical marks and the grading scale for every qualification.</li>
<li><strong>Prepare evidence of your research experience</strong> with the template and guide on the official scholarship page.</li>
<li><strong>Apply for admission through Sydney Courses</strong>, the university's online application, and upload every document it asks for before the deadline.</li>
<li><strong>Submit the scholarship application form</strong> from the official RTP Domestic page. This is a separate step: an admission application on its own does not put you in the running.</li>
<li><strong>Watch your email.</strong> The outcome is sent to the email address in your admission application, or to your university email if you are already enrolled.</li>
</ol>

<div class="scholar-note">
<p><strong>Not automatic.</strong> Some summaries say you are considered automatically when you apply for your course. For this scholarship the university asks you to do two things by the deadline: apply for admission with all your documents, <em>and</em> submit the scholarship application form.</p>
</div>

<h2>Documents to Prepare</h2>
<p>Your course application lists exactly what to upload. Most research applicants should have these ready:</p>
<ul>
<li>Proof that you are an Australian or New Zealand citizen or an Australian permanent resident</li>
<li>Academic transcripts with numerical marks, plus the grading scale for each qualification</li>
<li>Your degree certificates</li>
<li>A research proposal</li>
<li>An academic CV listing your research, publications and awards</li>
<li>Evidence of your research experience, set out with the university's template</li>
<li>Referees who know your research work</li>
</ul>

<h2>Rules While You Hold the Scholarship</h2>
<ul>
<li><strong>Keep up your progress.</strong> The scholarship can be ended if the university finds you have not studied with competence and diligence, have not made satisfactory progress, or have committed serious misconduct.</li>
<li><strong>Other income is limited.</strong> Another award, scholarship or salary for your research must be worth less than 75% of the scholarship. Pay for work unrelated to your research does not count towards that limit.</li>
<li><strong>Research overseas</strong> is not normally allowed in your first six months. After that you can spend up to 12 months researching outside Australia, with approval.</li>
<li><strong>Suspension</strong> is not normally allowed in your first six months. After that you can suspend for up to 12 months for any reason.</li>
<li><strong>Tell the university about changes</strong> to your enrolment, such as moving to part-time or taking leave, or you may have to repay stipend you were overpaid.</li>
<li><strong>It ends when your thesis goes in</strong>, or when the award reaches its maximum length, whichever comes first. It cannot be moved to another university.</li>
<li><strong>Acknowledge the funding</strong> in your publications with the line: "This research is supported by an Australian Government Research Training Program (RTP) Scholarship."</li>
</ul>

<h2>Domestic vs International: Sydney RTP Scholarships Compared</h2>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>RTP Domestic</th><th>RTP International</th></tr></thead>
<tbody>
<tr><td>Who can apply</td><td>Australian and New Zealand citizens, Australian permanent residents</td><td>International students</td></tr>
<tr><td>Living allowance (2026)</td><td>AUD \$42,754 a year</td><td>AUD \$42,754 a year</td></tr>
<tr><td>Tuition fees</td><td>Paid by the Australian Government, up to 16 research periods for a PhD</td><td>100% covered by the RTP Fee Offset, up to 14 research periods</td></tr>
<tr><td>Health cover (OSHC)</td><td>Not included</td><td>Included</td></tr>
<tr><td>Relocation</td><td>Up to AUD \$515 per adult and \$255 per child, AUD \$1,485 in total</td><td>Up to AUD \$1,485 to Sydney and up to AUD \$1,485 home</td></tr>
<tr><td>Next deadlines</td><td>12 October 2026, then 19 March 2027</td><td>11 September 2026, then 18 December 2026</td></tr>
<tr><td>How to apply</td><td>Admission application plus the scholarship form</td><td>Admission application plus the scholarship form</td></tr>
</tbody>
</table></div>
<p>Comparing other universities too? Read our <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP Scholarship guide</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Who can apply for the University of Sydney RTP Domestic Scholarship?</h3>
<p>Australian citizens, New Zealand citizens and Australian permanent residents who are starting or already enrolled in a PhD or master's by research at the University of Sydney.</p>

<h3>Can international students apply for the RTP Domestic Scholarship?</h3>
<p>No. International students apply for the University of Sydney RTP International Scholarship, which has earlier deadlines and also covers tuition fees and health cover.</p>

<h3>Does the Sydney RTP Domestic Scholarship cover tuition fees?</h3>
<p>Yes. For eligible domestic research students, the Australian Government's Research Training Program pays tuition fees for up to 16 research periods for a PhD and up to 8 for a master's by research.</p>

<h3>How much does the scholarship pay each month?</h3>
<p>AUD \$42,754 a year at the 2026 rate, which is about AUD \$3,563 a month spread over 12 months. The 2027 rate is AUD \$44,293 a year.</p>

<h3>When is the deadline for domestic students?</h3>
<p>12 October 2026 to start in Research Period 1 or 2 of 2027. The next deadline is 19 March 2027, to start in Research Period 3 or 4 of 2027.</p>

<h3>Am I considered automatically when I apply for my course?</h3>
<p>No. You must apply for admission with all your documents and also submit the scholarship application form, both by the deadline.</p>

<h3>Is the RTP stipend taxed?</h3>
<p>The university's terms only warn that a part-time scholarship may have tax implications and advise getting help from a registered tax agent. If your tax position matters to your decision, check before you accept.</p>

<h3>Can the scholarship be extended?</h3>
<p>No. The stipend lasts up to 14 research periods for a PhD and 7 for a master's by research. Approved extra paid sick leave and paid parental leave are added on to its length, though.</p>

<h3>How are applicants ranked?</h3>
<p>On your marks (your undergraduate average, Honours 1 or an equivalent, and any research master's), the world ranking of the university that awarded your qualification, and your research experience. Applicants doing their first PhD are the first priority.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official RTP Domestic scholarship page and application form</a></li>
<li><a href="https://www.sydney.edu.au/content/dam/corporate/documents/scholarships/rtp-scholarships-/tcs---rtp-domestic.pdf" target="_blank" rel="noopener">RTP Stipend (Domestic) terms and conditions</a></li>
<li><a href="https://sydney.edu.au/content/dam/corporate/documents/scholarships/rtp-scholarships-/hdr-selection-process.pdf" target="_blank" rel="noopener">How research scholarship applicants are selected</a></li>
<li><a href="https://www.sydney.edu.au/study/applying/how-to-apply/postgraduate-research.html" target="_blank" rel="noopener">How to apply for postgraduate research</a></li>
</ul>

<p><em>JobGader is not part of the University of Sydney. This guide was checked against the university's official pages on 10 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
