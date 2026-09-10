<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The University of Sydney Business School's PhD scholarships, both the
 * project-specific awards and the Business School Research Scholarship,
 * written up as a plain-English guide.
 *
 * Checked on 10 September 2026 against the Business School's research
 * scholarship lists, each scholarship's terms, the Doctor of Philosophy
 * (Business) course page, the admission requirements table and the
 * university's English language proficiency tables. Corrections to the brief:
 *
 * 1. The brief says projects pay $42,754 plus project funding, with sample
 *    packages of $50,000 or more. Each project pays a base stipend ($28,870
 *    to $39,500) that the Business School Supplementary Research Scholarship
 *    tops up to the RTP rate, so the total is $42,754. The only extra money
 *    listed is a $5,000 research allowance on the wage theft project.
 * 2. The brief says deadlines run year-round. Admission does, but every 2026
 *    project scholarship was open for two to four weeks and all have closed.
 *    The Business School Research Scholarship needs a PhD application by
 *    30 September or 1 February.
 * 3. The brief gives an email, a phone number and a
 *    /business/postgraduate-scholarships/ page that the school does not use
 *    (the URL returns 404). The Business School Research Unit's address is
 *    business.pgresearch@sydney.edu.au.
 * 4. The brief asks for IELTS 6.5. Business School research degrees need 7.0
 *    overall with 6.5 in each section.
 * 5. The brief says to get a supervisor's approval before applying. The
 *    course page says applicants do not need a supervisor first; project
 *    scholarships are awarded on the project supervisor's nomination.
 * 6. The brief says you cannot propose your own topic and that 15 to 25
 *    projects run each year. The Business School Research Scholarship funds
 *    your own topic, and seven project scholarships were advertised in
 *    2025-26.
 * 7. The brief lists Monash's deadline as 31 July; that round has closed.
 *
 * The three posters are used as supplied. Their amount, research areas and
 * "deadlines vary by project" line hold up, though no 2026 project was
 * advertised under sustainability alone.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class SydneyBusinessSchoolPhdScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-sydney-business-school-phd-scholarships';

    public const APPLY_URL = 'https://www.sydney.edu.au/scholarships/international/postgraduate-research/faculty/business.html';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Sydney Business School PhD Scholarships 2026–27',
                'provider' => 'The University of Sydney Business School',
                'country' => 'Australia',
                'city' => 'Sydney',
                'study_level' => "PhD, Master's by Research",
                'funding_type' => 'Stipend + Tuition Support',
                'award_value' => 'AUD $42,754 per year',
                'deadline' => '2026-09-30',
                'deadline_note' => 'Next PhD round closes 1 Feb 2027',
                'excerpt' => 'The University of Sydney Business School funds PhDs through its Business School Research Scholarship and project-specific scholarships, both worth AUD $42,754 a year. Deadlines, projects and how to apply.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/university-of-sydney-business-school-phd-scholarships.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Sydney Business School PhD Scholarships 2026: How to Apply',
                'meta_description' => 'University of Sydney Business School PhD scholarships pay AUD $42,754 a year. Project-specific awards, the 30 September 2026 deadline and how to apply.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-10 00:15:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>University of Sydney Business School</strong> funds PhD students in two main ways. The <strong>Business School Research Scholarship (BSRS)</strong> goes to top applicants who apply for the PhD by the deadline, whatever they plan to research. <strong>Project-specific PhD scholarships</strong> pay a student to work on one research project that already has its funding and supervisors, on topics such as bushfire planning, wage theft and road pricing. Both pay a living allowance at the university's RTP rate: <strong>AUD \$42,754 a year in 2026</strong>.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>What is open now:</strong> every project-specific scholarship the Business School advertised in 2026 has closed. The route still open is the Business School Research Scholarship: apply for the Doctor of Philosophy (Business) by <strong>30 September 2026</strong> to start on 1 March 2027. The next deadline is <strong>1 February 2027</strong>, for a 1 July 2027 start.</p>
</div>

<div class="scholar-note">
<p><strong>Want another chance at funding?</strong> Business School PhD applicants can also apply for the university-wide RTP scholarships, which have their own forms and deadlines. See our <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International guide</a> and our <a href="/scholarships/university-of-sydney-rtp-domestic-scholarship">Sydney RTP Domestic guide</a>. You cannot hold two full stipends at once.</p>
</div>

<h2>Sydney Business School PhD Scholarships at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>School</th><td>The University of Sydney Business School, Sydney, Australia</td></tr>
<tr><th>Who can apply</th><td>Domestic and international students starting a full-time PhD at the Business School</td></tr>
<tr><th>Two types</th><td>The Business School Research Scholarship (your own topic) and project-specific PhD scholarships (a set project and supervisors)</td></tr>
<tr><th>Living allowance</th><td>AUD \$42,754 a year in total (2026 RTP rate), rising to AUD \$44,293 on 1 January 2027</td></tr>
<tr><th>Tuition fees</th><td>Covered for international BSRS holders for up to 14 research periods; for project scholarships, confirmed in your offer letter</td></tr>
<tr><th>Also paid</th><td>The Student Services and Amenities Fee (SSAF)</td></tr>
<tr><th>How long</th><td>Up to 14 research periods (3.5 years) for a PhD</td></tr>
<tr><th>English</th><td>IELTS 7.0 overall, with at least 6.5 in each section</td></tr>
<tr><th>Next deadlines</th><td>30 September 2026, then 1 February 2027 (BSRS)</td></tr>
<tr><th>How to apply</th><td>BSRS: apply for the PhD and you are considered automatically. Projects: submit the form on each project's scholarship page</td></tr>
</tbody>
</table></div>

<h2>Two Ways to Get a Funded PhD at the Business School</h2>

<h3>1. The Business School Research Scholarship</h3>
<p>The BSRS supports outstanding PhD students, chosen on academic merit and their potential for research excellence and impact. You research your own topic. There is no separate scholarship form: if you apply for a full-time PhD at the Business School by the deadline, you are considered automatically.</p>

<h3>2. Project-specific PhD scholarships</h3>
<p>These are attached to a research project that already has funding, usually a grant from the Australian Research Council (ARC) or an industry partner. The topic is set by the project, and the scholarship goes to the applicant the project's supervisors nominate, based on academic merit and fit with the research. Each has its own page, its own form and a short application window.</p>

<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>Business School Research Scholarship</th><th>Project-specific scholarship</th></tr></thead>
<tbody>
<tr><td>Your research topic</td><td>Your own, matched with academics in the school</td><td>Set by the funded project</td></tr>
<tr><td>Who chooses</td><td>A Business School selection committee</td><td>The project's supervisors nominate the winner</td></tr>
<tr><td>How to apply</td><td>Apply for the PhD by the deadline; considered automatically</td><td>Submit the project's scholarship form and apply for the PhD</td></tr>
<tr><td>When</td><td>30 September or 1 February each year</td><td>A window of two to four weeks when the project is advertised</td></tr>
<tr><td>Stipend</td><td>The RTP rate, AUD \$42,754 a year (2026)</td><td>A base stipend topped up to the RTP rate</td></tr>
<tr><td>International tuition</td><td>Covered</td><td>Confirmed in the offer letter</td></tr>
<tr><td>Best for</td><td>Applicants with their own research plan</td><td>Applicants whose skills match a project</td></tr>
</tbody>
</table></div>

<h2>How Project Scholarships Are Paid</h2>
<p>Some summaries say project scholarships pay AUD \$42,754 plus extra project money. That is not how the terms work. Each project scholarship pays a <strong>base stipend</strong> from its research grant, and the <strong>Business School Supplementary Research Scholarship (BSSRS)</strong> pays the difference up to the RTP rate. The two together come to AUD \$42,754 a year at the 2026 rate.</p>
<div class="scholar-table"><table>
<thead><tr><th>Project scholarship</th><th>Base stipend</th><th>BSSRS top-up</th><th>Total a year</th></tr></thead>
<tbody>
<tr><td>Optimisation of Bushfire Fuel Reduction</td><td>AUD \$28,870</td><td>AUD \$13,884</td><td>AUD \$42,754</td></tr>
<tr><td>Tackling Wage Theft</td><td>AUD \$29,452</td><td>AUD \$13,302</td><td>AUD \$42,754</td></tr>
<tr><td>Road User Charging Reform (two roles)</td><td>AUD \$33,511</td><td>AUD \$9,243</td><td>AUD \$42,754</td></tr>
<tr><td>Strategic Travel Models</td><td>AUD \$33,533</td><td>AUD \$9,221</td><td>AUD \$42,754</td></tr>
<tr><td>Investec Women in Finance and Leadership</td><td>AUD \$39,500</td><td>AUD \$3,254</td><td>AUD \$42,754</td></tr>
</tbody>
</table></div>
<ul>
<li>The <strong>base stipend usually lasts up to 3 years</strong> and cannot be extended. The BSSRS runs for up to 14 research periods (3.5 years) and pays the full RTP rate once the base stipend ends.</li>
<li>The BSSRS also pays the <strong>SSAF</strong>, and it <strong>may cover tuition fees</strong>; your offer letter says whether it does. The bushfire project listed tuition fees for international recipients.</li>
<li>Among the 2026 project scholarships, only the wage theft project listed extra money: a <strong>AUD \$5,000 research allowance in Years 2 and 3</strong> for the costs of producing and sharing the research.</li>
</ul>

<h2>Project-Specific PhD Scholarships Advertised in 2025–26</h2>
<p>These are the project scholarships on the Business School's research scholarship lists, with the dates they were open. All of them have closed, but they show the kind of projects the school funds and when they tend to appear.</p>
<div class="scholar-table"><table>
<thead><tr><th>Scholarship</th><th>What you would research</th><th>Was open</th></tr></thead>
<tbody>
<tr><td>The PhD Scholarship in the Optimisation of Bushfire Fuel Reduction</td><td>A scalable method for planning fuel treatment so severe bushfires are less likely. Funded by the ARC.</td><td>20 May to 2 June 2026</td></tr>
<tr><td>The PhD Scholarship in Tripartite Approaches to Tackling Wage Theft</td><td>How a labour regulator, worker representatives and businesses can work together to get employers paying correct wages. Funded by the ARC.</td><td>24 March to 20 April 2026</td></tr>
<tr><td>Technical and System-Level Modelling of Road User Charging Reform</td><td>Part of the ARC project "An Agreeable Price": mapping Australian road use and charges, driving simulator experiments, a six-month field trial and network simulation of distance- and time-based road pricing.</td><td>19 March to 1 April 2026</td></tr>
<tr><td>Acceptability and Behavioural Pathways in Road User Charging Reform</td><td>The same road pricing project, focused on how drivers and policymakers respond to new charges and where resistance comes from.</td><td>19 March to 1 April 2026</td></tr>
<tr><td>Strategic Travel Models</td><td>A new open-access, activity-based travel model for the Greater Sydney area with dynamic traffic assignment. Funded by the ARC.</td><td>1 to 20 April 2026</td></tr>
<tr><td>Improved Trip Generation Forecasting</td><td>A spatial interaction model built on TRIPS, a national database of nearly 1,000 trip generation surveys, to improve traffic forecasts for new developments. Funded by the ARC.</td><td>25 February to 15 March 2026</td></tr>
<tr><td>The Investec Women in Finance and Leadership PhD Scholarship</td><td>The experiences of women building careers as leaders in finance and investment banking, supervised by Professor Rae Cooper. Funded by Investec Australia and the Business School.</td><td>17 October to 13 November 2025</td></tr>
</tbody>
</table></div>

<h3>What these projects tell you</h3>
<ul>
<li><strong>Windows are short.</strong> Most stayed open for two to four weeks, so have your documents ready before a project appears.</li>
<li><strong>Most appear early in the year.</strong> Six of the seven opened between late February and late May 2026.</li>
<li><strong>Transport leads.</strong> Four of the seven were transport projects.</li>
<li><strong>Skills matter as much as interest.</strong> The road pricing project asked for strong quantitative skills, large datasets, statistical modelling and R or Python, with transport modelling, simulation, GIS or behavioural data analysis an advantage.</li>
<li><strong>No 2026 project was advertised under climate or sustainability alone.</strong> The road pricing project does study emissions, and you can propose a sustainability topic under the BSRS.</li>
</ul>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-sydney-business-school-phd-scholarships-research.jpg" alt="University of Sydney Business School project-specific PhD scholarships and their research areas" width="1200" height="628" loading="lazy">
<figcaption>Recent project scholarships covered transport and mobility, bushfire management and wage theft.</figcaption>
</figure>

<h2>The Business School Research Scholarship in Detail</h2>

<h3>What it pays</h3>
<ul>
<li>A <strong>stipend of AUD \$42,754 a year</strong> at the 2026 rate, indexed every 1 January to the university's RTP rate, for up to <strong>14 research periods (3.5 years)</strong> for a PhD, or up to 7 research periods for a Master of Philosophy (MPhil)</li>
<li><strong>Tuition fees for international students</strong> for the same period</li>
<li>The <strong>Student Services and Amenities Fee (SSAF)</strong></li>
<li>Up to <strong>20 working days of paid holiday</strong> and <strong>10 working days of sick leave</strong> a year; up to five sick days a year can be used as carer's leave</li>
</ul>

<h3>Who is eligible</h3>
<ul>
<li>Domestic and international students</li>
<li>Applying to study a research degree full-time at the Business School</li>
<li>Not already started the PhD at the University of Sydney</li>
</ul>

<h3>How winners are chosen</h3>
<p>On academic merit and potential for research excellence and impact. A selection committee of the Associate Dean (Research Education), or their nominee, and at least two other Business School academics nominates the recipients. Offers depend on the funding available.</p>

<h3>Keeping it</h3>
<p>You must pass your annual progress evaluation, keep a <strong>Distinction average (a weighted average mark of 75 or more)</strong> in your research training coursework and complete the school's research milestones.</p>

<div class="scholar-note">
<p><strong>Read about an "Enhanced" scholarship worth \$50,207?</strong> The Enhanced Business School Research Scholarship was replaced by the Business School Research Scholarship from 2025. Older articles that still list it are out of date.</p>
</div>

<h2>Entry Requirements for the PhD (Business)</h2>
<p>Both types of scholarship need you to meet the PhD admission requirements. You need one of these:</p>
<ul>
<li>A bachelor's degree with <strong>first class honours</strong></li>
<li>A <strong>coursework master's</strong> with a substantial independent research component, such as a thesis or research project equal to 25% of a year's full-time study</li>
<li>A <strong>master's by research</strong></li>
</ul>
<p>You also need an <strong>overall weighted average of at least 80%</strong> and a <strong>completed honours or master's thesis of about 20,000 words</strong>. Meeting these minimums does not guarantee a place. The school says admission is highly competitive, with a small number of offers made from a large pool of applicants every intake.</p>

<h3>English language</h3>
<p>All research degrees at the Business School need <strong>IELTS 7.0 overall, with at least 6.5 in each section</strong>. That is higher than the university's standard of 6.5 overall with no band below 6.0. You do not have to send proof of English when you apply, but you need it before you can receive an unconditional offer.</p>

<h3>Documents by discipline</h3>
<p>Every discipline asks for a statement of purpose (1 to 2 pages on why you want to do a PhD), transcripts and degree certificates, proof of English, referee reports, your CV and your honours or master's thesis. Whether you need the GRE or GMAT, and what kind of research proposal to send, depends on the discipline:</p>
<div class="scholar-table"><table>
<thead><tr><th>Discipline</th><th>GRE or GMAT</th><th>Research proposal</th></tr></thead>
<tbody>
<tr><td>Accounting</td><td>Recommended, not compulsory</td><td>Indicative research proposal</td></tr>
<tr><td>Business Analytics</td><td>GRE or GMAT</td><td>Statement of research interests</td></tr>
<tr><td>Business Information Systems</td><td>No</td><td>Statement of research interests</td></tr>
<tr><td>Business Law</td><td>No</td><td>Indicative research proposal</td></tr>
<tr><td>Finance</td><td>Recommended, not compulsory</td><td>Indicative research proposal</td></tr>
<tr><td>International Business</td><td>GMAT</td><td>Statement of research interests</td></tr>
<tr><td>Marketing</td><td>No</td><td>Indicative research proposal</td></tr>
<tr><td>Strategy, Innovation and Entrepreneurship</td><td>No</td><td>Indicative research proposal</td></tr>
<tr><td>Transport and Logistics Studies</td><td>No</td><td>Indicative research proposal</td></tr>
<tr><td>Work and Organisational Studies</td><td>No</td><td>Developed research proposal</td></tr>
</tbody>
</table></div>

<h2>How to Apply, Step by Step</h2>

<h3>For the Business School Research Scholarship</h3>
<ol>
<li><strong>Check the entry requirements</strong>, including the 80% average and the thesis.</li>
<li><strong>Pick your discipline</strong> and look up the academics researching your area on the University of Sydney website. You do not need to find a supervisor before applying: the Business School finds one for you during the application process. If you have not picked one, choose "Business HOD" (Head of Discipline) when the application asks for a supervisor. Your interests still need to match the school's research.</li>
<li><strong>Prepare the documents for your discipline</strong>: statement of purpose, research proposal or statement of research interests, CV, thesis, transcripts and referees. Book the GRE or GMAT early if your discipline asks for it.</li>
<li><strong>Apply for the Doctor of Philosophy (Business)</strong> through Sydney Student, the university's online application, by <strong>30 September 2026</strong> to start on 1 March 2027, or by <strong>1 February 2027</strong> to start on 1 July 2027. You are considered for the scholarship automatically.</li>
<li><strong>Prepare for an interview.</strong> Shortlisted applicants are interviewed, online or in person.</li>
<li><strong>Take your English test</strong> in time to send the results before an unconditional offer.</li>
</ol>

<h3>For a project-specific scholarship</h3>
<ol>
<li><strong>Watch the Business School's research scholarship lists</strong> for <a href="{$applyUrl}" target="_blank" rel="noopener">international students</a> and <a href="https://www.sydney.edu.au/scholarships/domestic/postgraduate-research/faculty/business.html" target="_blank" rel="noopener">domestic students</a>, especially from February to June.</li>
<li><strong>Read the project page closely</strong>: the research aims, the skills it asks for and any supervisor or contact it names.</li>
<li><strong>Get in touch early</strong> when a supervisor or project contact is named. Show how your skills fit the project's methods, not only that the topic interests you.</li>
<li><strong>Submit the scholarship form</strong> from the "Apply here" link on the project page before it closes.</li>
<li><strong>Apply for the PhD (Business) as well.</strong> The terms give these scholarships to applicants with an unconditional offer of admission, or who are already enrolled full-time.</li>
<li><strong>Wait for the nomination.</strong> Project scholarships are awarded on academic merit and research fit, on the nomination of the project's supervisors.</li>
</ol>

<h3>A short email to a project supervisor</h3>
<div class="scholar-note">
<p>Dear Professor [Name],<br>I am applying for the PhD scholarship on [project title]. I hold a [degree] from [university] with an average of [mark], and my thesis used [a method, such as discrete choice modelling in R]. The project's work on [one specific part] builds on that experience. I have attached my CV and transcript. Could you let me know whether my background suits the project before the scholarship closes on [date]?<br>Kind regards,<br>[Your name]</p>
</div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-sydney-business-school-phd-scholarships-student.jpg" alt="Student at the University of Sydney with the Business School PhD scholarship details" width="1200" height="628" loading="lazy">
<figcaption>Apply for the PhD by the deadline to be considered for the Business School Research Scholarship.</figcaption>
</figure>

<h2>Deadlines</h2>
<div class="scholar-table"><table>
<thead><tr><th>If you want to</th><th>Apply by</th><th>Good to know</th></tr></thead>
<tbody>
<tr><td>Start on 1 March 2027 (Research Period 2) with the BSRS</td><td><strong>30 September 2026</strong></td><td>Considered for the BSRS automatically</td></tr>
<tr><td>Start on 1 July 2027 (Research Period 3) with the BSRS</td><td><strong>1 February 2027</strong></td><td>Considered for the BSRS automatically</td></tr>
<tr><td>Win a project-specific scholarship</td><td>The closing date on the project page</td><td>Usually open for two to four weeks</td></tr>
<tr><td>Apply for Sydney's RTP International Scholarship</td><td>11 September 2026, then 18 December 2026</td><td>Separate form and deadlines</td></tr>
<tr><td>Apply for Sydney's RTP Domestic Scholarship</td><td>12 October 2026, then 19 March 2027</td><td>Separate form and deadlines</td></tr>
</tbody>
</table></div>
<p>PhD applications are accepted all year, but scholarships have their own deadlines. If your application cannot be processed in time for the start date you asked for, it is considered for the next research period.</p>

<h3>Information session</h3>
<p>The Business School's postgraduate research page lists a session on PhD pathways, scholarships and research opportunities on <strong>22 September 2026, 12pm to 1pm Sydney time</strong>. That is 7am to 8am in Pakistan and 7:30am to 8:30am in India. Details are on the <a href="https://www.sydney.edu.au/business/study/postgraduate-research.html" target="_blank" rel="noopener">postgraduate research page</a>.</p>

<h2>Rules While You Hold a Scholarship</h2>
<ul>
<li><strong>Study full-time.</strong> The award ends if you move to part-time study without approval.</li>
<li><strong>Keep making progress.</strong> Pass your annual progress evaluation. BSRS holders also need a Distinction average in research training coursework.</li>
<li><strong>Leave.</strong> Up to 20 working days of paid holiday a year, taken with your supervisor's agreement, and 10 working days of sick leave.</li>
<li><strong>Research overseas</strong> is not normally allowed in your first six months. After that you can spend up to 12 months researching abroad, with approval, when it is essential to your degree.</li>
<li><strong>Suspension</strong> is not allowed in your first six months unless the law requires it. After that you can suspend for up to 12 months in total.</li>
<li><strong>No deferral or change of topic.</strong> Neither type of scholarship can be deferred or moved to another research area without approval.</li>
<li><strong>One main stipend.</strong> If you receive another main stipend scholarship worth more, the Business School award ends in its favour.</li>
<li><strong>Project conditions.</strong> Project holders may have to sign a Student Deed Poll, keep project partners' information confidential and acknowledge the Australian Research Council's funding in their publications.</li>
<li><strong>It ends</strong> when you submit your thesis or reach the maximum length of the award.</li>
</ul>

<h2>Other Funding at the Business School</h2>
<ul>
<li><strong>Industry Partnered Scholarships</strong>: research in a real business setting with an industry partner, with a stipend and tuition fees covered. Contact details are on the Business School's postgraduate research page.</li>
<li><strong>University of Sydney and University of Glasgow Joint PhD</strong>: the RTP stipend rate, a tuition fee offset and a one-off AUD \$5,000 travel scholarship, with time spent at both universities. You apply through Glasgow's portal.</li>
<li><strong>Transport and Logistics Studies supplementary scholarship</strong>: a one-off AUD \$2,000 for PhD students with a master's degree who already hold a main stipend scholarship and research transport and logistics.</li>
<li><strong>Paid internships</strong>: 3 to 6 month placements with industry partners through APR.Intern, open to domestic and international research students.</li>
</ul>

<h2>Sydney Business School vs Other Research Scholarships</h2>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>Sydney Business School</th><th>Sydney RTP International</th><th>Sydney RTP Domestic</th><th>Monash RTP</th></tr></thead>
<tbody>
<tr><td>Living allowance (2026)</td><td>AUD \$42,754 a year</td><td>AUD \$42,754 a year</td><td>AUD \$42,754 a year</td><td>AUD \$37,145 a year</td></tr>
<tr><td>Tuition fees</td><td>BSRS covers international tuition; projects vary</td><td>Covered</td><td>Paid by the Australian Government</td><td>Through the separate Monash International Tuition Scholarship</td></tr>
<tr><td>Research topic</td><td>Your own (BSRS) or a set project</td><td>Your own</td><td>Your own</td><td>Your own</td></tr>
<tr><td>How to apply</td><td>PhD application (BSRS) or the project's form</td><td>Admission plus scholarship form</td><td>Admission plus scholarship form</td><td>Apply during an open round</td></tr>
<tr><td>Next deadline</td><td>30 September 2026</td><td>11 September 2026, then 18 December 2026</td><td>12 October 2026</td><td>Round 1 closing date not yet confirmed</td></tr>
</tbody>
</table></div>
<p>Read the full guides: <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a>, <a href="/scholarships/university-of-sydney-rtp-domestic-scholarship">Sydney RTP Domestic</a> and <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP</a>.</p>

<h2>Tips to Strengthen Your Application</h2>
<ul>
<li><strong>Apply by 30 September 2026</strong> if you want a March 2027 start and a chance at the BSRS in that round.</li>
<li><strong>Show exact marks.</strong> With 80% as the minimum average, give your precise marks and the grading scale from your transcript.</li>
<li><strong>Make your thesis count.</strong> The school asks for the thesis itself, so send a clean final version and be ready to discuss its methods at interview.</li>
<li><strong>Match the school's research.</strong> Name academics whose work fits yours in your statement of purpose.</li>
<li><strong>Build the skills projects ask for.</strong> The road pricing project, for example, wanted R or Python, statistical modelling and experience with large datasets.</li>
<li><strong>Be ready before projects open.</strong> Keep an up-to-date CV, transcripts and a one-page research summary so you can apply inside a two-week window.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What are project-specific PhD scholarships at the University of Sydney Business School?</h3>
<p>Scholarships attached to a funded research project, usually an Australian Research Council grant, with a set topic and supervisors. The student's PhD thesis comes from the project, and the project's supervisors nominate the winner.</p>

<h3>How much do Sydney Business School PhD scholarships pay?</h3>
<p>A total stipend at the University of Sydney RTP rate: AUD \$42,754 a year in 2026 and AUD \$44,293 from 1 January 2027. The Business School Research Scholarship also covers tuition fees for international students.</p>

<h3>Do project scholarships pay extra on top of AUD \$42,754?</h3>
<p>No. Each project pays a base stipend, such as AUD \$28,870 or AUD \$33,511, and the Business School Supplementary Research Scholarship tops it up to AUD \$42,754. The wage theft project also listed a AUD \$5,000 research allowance in Years 2 and 3.</p>

<h3>Are any project-specific scholarships open now?</h3>
<p>Not as of 10 September 2026. All the project scholarships advertised in 2026 have closed. Watch the Business School's research scholarship lists, as most projects opened between February and May.</p>

<h3>Is the Business School Research Scholarship automatic?</h3>
<p>Yes. If you apply for a full-time PhD at the Business School by the deadline, 30 September or 1 February, you are considered automatically. You cannot receive it once you have started your PhD.</p>

<h3>Do I need a supervisor before I apply?</h3>
<p>No. The Business School finds a supervisor for you during the application process. For a project scholarship, contact the named supervisor or project contact early, because the project's supervisors nominate the winner.</p>

<h3>What IELTS score do I need for a PhD at Sydney Business School?</h3>
<p>IELTS 7.0 overall with at least 6.5 in each section, for all Business School research degrees. You can send proof of English after you apply, but you need it before an unconditional offer.</p>

<h3>Do I need the GRE or GMAT?</h3>
<p>Only in some disciplines. Business Analytics asks for the GRE or GMAT and International Business for the GMAT, while Accounting and Finance recommend one. The other disciplines do not ask for either.</p>

<h3>Can I propose my own research topic?</h3>
<p>Yes, under the Business School Research Scholarship. Project scholarships fund a set topic, so your thesis has to fit that project.</p>

<h3>Who do I contact with questions?</h3>
<p>The Business School Research Unit at business.pgresearch@sydney.edu.au, or the university on 1800 SYD UNI (1800 793 864) or +61 2 8627 1444.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Business School research scholarships for international students</a></li>
<li><a href="https://www.sydney.edu.au/scholarships/domestic/postgraduate-research/faculty/business.html" target="_blank" rel="noopener">Business School research scholarships for domestic students</a></li>
<li><a href="https://www.sydney.edu.au/scholarships/a/business-school-research-scholarship.html" target="_blank" rel="noopener">Business School Research Scholarship terms</a></li>
<li><a href="https://www.sydney.edu.au/scholarships/a/business-school-supplementary-research-scholarship.html" target="_blank" rel="noopener">Business School Supplementary Research Scholarship terms</a></li>
<li><a href="https://www.sydney.edu.au/courses/courses/pr/doctor-of-philosophy-business0.html" target="_blank" rel="noopener">Doctor of Philosophy (Business) course page</a></li>
<li><a href="https://www.sydney.edu.au/business/study/postgraduate-research.html" target="_blank" rel="noopener">Business School postgraduate research page</a></li>
<li><a href="https://www.sydney.edu.au/content/dam/corporate/documents/business-school/study/business-mphil-and-phd-admission-requirements-table.pdf" target="_blank" rel="noopener">Admission requirements by discipline (PDF)</a></li>
</ul>

<p><em>JobGader is not part of the University of Sydney. This guide was checked against the university's official pages on 10 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
