<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The Australian National University's Research Training Program (RTP)
 * scholarships for PhD and MPhil students, domestic and international,
 * written up as a plain-English guide.
 *
 * Checked on 11 September 2026 against ANU's RTP Stipend, RTP International
 * and Domestic Fee Offset and University Research Scholarship pages, the RTP
 * Conditions of Award, the HDR scholarships and admissions procedures, the
 * English language policy and the ATO's scholarship payments page.
 * Corrections to the brief and the posters:
 *
 * 1. International Round 1 closed on 31 August 2026; the next international
 *    deadline is 15 April 2027. Domestic Round 1 (31 October 2026) is right.
 *    Applications are open all year, not from "about May or July".
 * 2. The brief guesses the 2027 stipend at about $40,000. ANU has published
 *    $40,475.
 * 3. The brief says the international award has no OSHC. The international
 *    fee offset includes OSHC for the student and their immediate family.
 * 4. The brief asks for a GPA of 3.0 and a bachelor's degree. PhD entry needs
 *    first or upper second class honours or equivalent, and the scholarship
 *    needs H1 or equivalent. IELTS is 6.5 overall with no band below 6.0.
 * 5. The brief gives 3-4 years for a PhD and 2 for an MPhil. The stipend runs
 *    3.5 and 1.5 years; tuition is covered for 4 and 2 years.
 * 6. The brief's scholarships@anu.edu.au, +61 2 6125 8000 and
 *    anu.edu.au/students/programs (404) are not ANU's; the posters'
 *    anu.rtp@anu.edu.au is not listed either, so the published posters show
 *    study.anu.edu.au in that block. The RTP page lists grs@anu.edu.au.
 * 7. The brief calls ANU Australia's number one research university and adds
 *    student numbers, a 50-60% success rate and HECS-HELP. ANU says it is
 *    fourth in Australia in the QS 2027 rankings; the rest is unsupported.
 * 8. The brief says the stipend is tax-free with no declaration. Only a
 *    full-time stipend meeting the ATO's conditions is exempt; part-time is
 *    taxable. It lists conference and publication support as benefits;
 *    travel money is a separate Vice-Chancellor's grant.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class AnuRtpScholarshipSeeder extends Seeder
{
    public const SLUG = 'australian-national-university-rtp-scholarship';

    public const APPLY_URL = 'https://study.anu.edu.au/scholarships/find-scholarship/australian-government-research-training-program-rtp-stipend';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Australian National University RTP Scholarship 2026–27',
                'provider' => 'The Australian National University',
                'country' => 'Australia',
                'city' => 'Canberra',
                'study_level' => "PhD, Master's by Research",
                'funding_type' => 'Fully Funded',
                'award_value' => 'AUD $39,069 per year',
                'deadline' => '2026-10-31',
                'deadline_note' => 'Next round closes 15 Apr 2027',
                'excerpt' => "ANU's RTP Scholarship pays AUD $39,069 a year (AUD $40,475 in 2027) plus tuition for a PhD or MPhil. Domestic Round 1 closes 31 October 2026; international applicants now apply by 15 April 2027.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/australian-national-university-rtp-scholarship.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'ANU RTP Scholarship 2026: Stipend, Deadlines & How to Apply',
                'meta_description' => "ANU's RTP Scholarship pays AUD $39,069 a year (2026) plus tuition for a PhD or MPhil. Domestic Round 1 closes 31 October 2026. Eligibility and how to apply.",
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-11 00:05:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>Australian National University (ANU)</strong> in Canberra funds PhD and Master of Philosophy (MPhil) students through the Australian Government's <strong>Research Training Program (RTP)</strong>. A full-time RTP Stipend Scholarship pays a living allowance of <strong>AUD \$39,069 a year at the 2026 rate</strong>, rising to <strong>AUD \$40,475 in 2027</strong>. It comes with a fee offset that covers tuition and, for international students, health cover. Domestic and international students both apply, in the same rounds but with different deadlines.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Deadlines:</strong> the international Round 1 deadline, <strong>31 August 2026</strong>, has passed. International applicants now apply by <strong>15 April 2027</strong>. Domestic applicants still have until <strong>31 October 2026</strong> to start in early 2027.</p>
</div>

<h2>ANU RTP Scholarship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>The Australian National University, Canberra</td></tr>
<tr><th>Scholarships</th><td>RTP Stipend Scholarship, plus an RTP International or Domestic Fee Offset Scholarship. ANU's own University Research Scholarships are awarded in the same rounds.</td></tr>
<tr><th>Who can apply</th><td>International students, and domestic students: Australian and New Zealand citizens, permanent residents and humanitarian visa holders</td></tr>
<tr><th>Study level</th><td>Doctor of Philosophy (PhD) and Master of Philosophy (MPhil)</td></tr>
<tr><th>Living allowance</th><td>AUD \$39,069 a year (2026 rate), AUD \$40,475 a year (2027 rate), paid fortnightly</td></tr>
<tr><th>Tuition fees</th><td>Covered for 4 years for a PhD and 2 years for an MPhil</td></tr>
<tr><th>Health cover (OSHC)</th><td>Included for international students and their immediate family</td></tr>
<tr><th>Relocation</th><td>Receipts refunded up to AUD \$1,000 (domestic) or AUD \$2,500 (international)</td></tr>
<tr><th>How long the stipend lasts</th><td>3.5 years for a PhD, 1.5 years for an MPhil</td></tr>
<tr><th>How to apply</th><td>Apply for admission and select the scholarship option; there is no separate form</td></tr>
<tr><th>Next deadlines</th><td>31 October 2026 (domestic), then 15 April 2027 (everyone)</td></tr>
</tbody>
</table></div>

<h2>What the Scholarship Pays</h2>

<h3>1. A living allowance</h3>
<p>The full-time stipend is <strong>AUD \$39,069 a year in 2026</strong>, about AUD \$3,256 a month. ANU has already published the <strong>2027 rate of AUD \$40,475 a year</strong>, about AUD \$3,373 a month, and the award is indexed every 1 January. It is paid fortnightly and you do not pay it back. Part-time scholars receive half the rate.</p>
<p>Aboriginal and Torres Strait Islander candidates are awarded the maximum RTP stipend rate, which is <strong>AUD \$55,537 a year in 2027</strong>.</p>

<h3>2. Tuition fees</h3>
<ul>
<li><strong>Domestic students</strong> receive an RTP Domestic Fee Offset Scholarship <strong>automatically when they are admitted</strong>. It covers four years for a PhD and two years for an MPhil, and domestic students are not charged tuition on a program extension.</li>
<li><strong>International students</strong> receive an RTP International Fee Offset Scholarship, or an ANU HDR Fee Merit Scholarship, <strong>together with a stipend award</strong>. It is competitive, not automatic, and covers tuition for four years (PhD) or two years (MPhil). Beyond that, international students pay an extension fee.</li>
</ul>

<h3>3. Overseas Student Health Cover</h3>
<p>The international fee offset includes <strong>Overseas Student Health Cover (OSHC) for you and your immediate family</strong>. It has to be claimed when the scholarship starts.</p>

<h3>4. A relocation allowance</h3>
<p>If you live outside the ACT, or outside Australia, when you apply, ANU refunds travel and removal costs against original receipts, up to <strong>AUD \$1,000 for domestic students</strong> or <strong>AUD \$2,500 for international students</strong>. Claim within 12 months. Insurance, accommodation and meals are not covered.</p>

<h3>5. Thesis and child allowances</h3>
<ul>
<li>A <strong>thesis allowance of up to AUD \$500</strong></li>
<li>For <strong>international students only</strong>, a dependent child allowance of up to AUD \$3,000 a year per child, to a maximum of AUD \$9,000 a year</li>
</ul>
<p>Conference travel is not part of the scholarship. After your first year you can apply once for a <strong>Vice-Chancellor's HDR Travel Grant</strong> of up to AUD \$5,000 to present your research overseas.</p>

<h3>6. Paid leave</h3>
<ul>
<li>20 days of paid recreation leave and 10 days of paid medical leave a year</li>
<li>60 days of paid parental leave once you have held the award for 12 months</li>
</ul>

<h3>7. How long the money lasts</h3>
<p>The stipend runs for <strong>3.5 years for a PhD</strong> and <strong>1.5 years for an MPhil</strong>. ANU can extend it by up to 3 months for delays beyond your control, or up to 6 months for an industry internship or for Indigenous candidates, but never past a total of four years for a PhD or two years for an MPhil.</p>

<h3>8. Tax</h3>
<p>ANU says living allowance stipends paid at the <strong>full-time rate are not considered taxable income</strong>, while <strong>part-time stipends are taxable</strong>. The Australian Taxation Office adds conditions: the payment must be made principally for your education and you must be a full-time student. If your stipend is exempt, you leave it out of your tax return and should tell ANU.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/australian-national-university-rtp-scholarship-campus.jpg" alt="Students by the lake on the ANU campus in Canberra with the RTP Scholarship stipend and benefits" width="1200" height="628" loading="lazy">
<figcaption>The poster shows the 2026 rate. Students starting in 2027 are paid the 2027 rate of AUD \$40,475 a year.</figcaption>
</figure>

<h2>Who Can Apply</h2>

<h3>Domestic or international</h3>
<p>You count as a <strong>domestic student</strong> if you are an Australian citizen, a New Zealand citizen, or hold an Australian permanent resident visa or a permanent humanitarian visa. Everyone else applies as an <strong>international student</strong>, from any country.</p>

<h3>Academic results</h3>
<ul>
<li><strong>PhD admission:</strong> a bachelor's degree with first class honours or upper second class honours, or an equivalent qualification</li>
<li><strong>MPhil admission:</strong> an overall grade of distinction or higher</li>
<li><strong>The scholarship:</strong> ANU's conditions of award ask for <strong>first class honours (H1) or an H1 equivalent</strong>, so admission alone is not enough to win funding</li>
</ul>
<p>ANU does not set a GPA cut-off such as 3.0. What counts is how your honours or equivalent result compares, and your research experience.</p>

<h3>English language</h3>
<p>For PhD and MPhil programs ANU asks for <strong>IELTS Academic 6.5 overall, with no band below 6.0</strong>, or an accepted equivalent. The PhD in Clinical Psychology is higher, at 7.0 in every band.</p>

<h2>Degrees You Can Study</h2>
<ul>
<li><strong>Doctor of Philosophy (PhD)</strong></li>
<li><strong>Master of Philosophy (MPhil)</strong>, ANU's master's by research</li>
</ul>
<p>Research degrees are offered across ANU's colleges, from science, engineering and computing to medicine, business, law, and the arts, humanities and social sciences. Coursework master's degrees are not covered. Browse programs on <a href="https://programsandcourses.anu.edu.au/" target="_blank" rel="noopener">ANU Programs and Courses</a>.</p>

<h2>Deadlines and Scholarship Rounds</h2>
<p>You can submit an application at any time of year, but you are considered for a scholarship in the round your complete application falls into.</p>
<div class="scholar-table"><table>
<thead><tr><th>Round</th><th>Who</th><th>Apply by</th><th>Start your degree by</th><th>Status</th></tr></thead>
<tbody>
<tr><td>Round 1, 2027</td><td>International</td><td>31 August 2026</td><td>31 March 2027</td><td>Closed</td></tr>
<tr><td>Round 1, 2027</td><td>Domestic</td><td><strong>31 October 2026</strong></td><td>31 March 2027</td><td>Open</td></tr>
<tr><td>Round 2, 2027</td><td>International and domestic</td><td><strong>15 April 2027</strong></td><td>31 August 2027</td><td>Upcoming</td></tr>
</tbody>
</table></div>
<ul>
<li><strong>Complete means complete.</strong> Your application, including your referees' reports, has to be in by the deadline. ANU suggests submitting two to three weeks early.</li>
<li><strong>Round 1 starts</strong> run from January to the end of March.</li>
<li><strong>Results:</strong> ANU does not publish a central results date. One college says international applicants hear towards the end of October and domestic applicants in December.</li>
</ul>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/australian-national-university-rtp-scholarship-students.jpg" alt="Four students at ANU with the RTP Scholarship 2026 deadlines for international and domestic applicants" width="1200" height="628" loading="lazy">
<figcaption>The international date on the poster, 31 August 2026, has passed. The next international deadline is 15 April 2027.</figcaption>
</figure>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Choose your program.</strong> Find the PhD or MPhil in your field on ANU Programs and Courses and check its entry requirements.</li>
<li><strong>Get a supervisor's support.</strong> Most ANU colleges want you to find a potential supervisor and secure their support before you apply. Without that endorsement ANU says it may not be able to assess your application, and some programs require a supervisor's written approval.</li>
<li><strong>Write your research proposal.</strong> ANU sets no fixed length, so follow your program's guidance and agree the scope with your potential supervisor.</li>
<li><strong>Line up your referees.</strong> You need at least two. If one of them is your proposed supervisor, add a third.</li>
<li><strong>Apply for admission online</strong> through <a href="https://study.anu.edu.au/apply/postgraduate-research" target="_blank" rel="noopener">ANU's postgraduate research application</a>. There is no application fee.</li>
<li><strong>Select the scholarship option.</strong> In the application, choose to be considered for a scholarship (listed as "Australia: ANU - Other Scholarship"). There is no separate scholarship form.</li>
<li><strong>Submit early.</strong> Aim for two to three weeks before the round closes, so your referees' reports arrive in time.</li>
</ol>

<div class="scholar-note">
<p><strong>No second form, but not automatic either.</strong> Domestic students get the tuition fee offset automatically when admitted, but the stipend is competitive for everyone. You are only considered if you select the scholarship option and your application is complete by the round's deadline.</p>
</div>

<h2>Documents to Prepare</h2>
<ul>
<li>Academic transcripts and degree certificates (ANU does not ask for certified copies)</li>
<li>A research proposal</li>
<li>A CV listing your research experience, publications and awards</li>
<li>Contact details for at least two referees</li>
<li>Proof of English language proficiency, if it applies to you</li>
</ul>

<h2>Rules While You Hold the Scholarship</h2>
<ul>
<li><strong>Study full-time.</strong> ANU expects 35 to 40 hours a week on your research for 48 weeks a year.</li>
<li><strong>Meet your milestones.</strong> A First Year Plan within 3 months, Confirmation of candidature at 9 to 12 months, and an Annual Progress Review each February. Missing them can end the scholarship.</li>
<li><strong>Part-time only for a reason.</strong> A part-time award is approved for caring commitments, a medical condition, a disability or similar circumstances. It pays half the rate, is taxable, and international students need a visa that allows it.</li>
<li><strong>Extensions are limited</strong> to the amounts above, and international students pay tuition during a program extension.</li>
</ul>

<h2>Claims About This Scholarship to Ignore</h2>
<ul>
<li><strong>"A GPA of 3.0 is enough."</strong> ANU asks for first or upper second class honours for PhD admission and H1 or equivalent for the scholarship.</li>
<li><strong>"No health cover for international students."</strong> The international fee offset includes OSHC for you and your immediate family.</li>
<li><strong>"The PhD stipend lasts 3 to 4 years."</strong> It lasts 3.5 years, with only short extensions up to a four-year total.</li>
<li><strong>"The 2027 rate will be about \$40,000."</strong> ANU has already published it: AUD \$40,475.</li>
<li><strong>"Australia's number one university."</strong> ANU says it placed fourth in Australia in the QS World University Rankings 2027, and lists itself as 29th in the world.</li>
<li><strong>"50 to 60% of eligible applicants win."</strong> ANU publishes no success rate; it says only that not everyone offered admission receives a scholarship.</li>
</ul>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/australian-national-university-rtp-scholarship-graduates.jpg" alt="ANU PhD graduates in gowns with the RTP Scholarship 2026 stipend and application rounds" width="1200" height="628" loading="lazy">
<figcaption>Open to domestic and international PhD and MPhil applicants. Round 2 closes 15 April 2027 for both.</figcaption>
</figure>

<h2>ANU vs Sydney vs Monash: RTP Stipends Compared</h2>
<div class="scholar-table"><table>
<thead><tr><th>University</th><th>2026 full-time stipend</th><th>Next international deadline</th></tr></thead>
<tbody>
<tr><td>University of Sydney</td><td>AUD \$42,754</td><td>18 December 2026, after the 11 September 2026 round</td></tr>
<tr><td>University of Melbourne</td><td>AUD \$39,500</td><td>Varies by faculty; see our guide</td></tr>
<tr><td>Australian National University</td><td>AUD \$39,069</td><td>15 April 2027</td></tr>
<tr><td>Monash University</td><td>AUD \$37,145</td><td>Round 1 for 2027 is open; see our guide</td></tr>
</tbody>
</table></div>
<p>At 2026 rates ANU pays AUD \$3,685 a year less than Sydney and AUD \$1,924 more than Monash. Living costs differ too: ANU's own estimate for Canberra is AUD \$33,000 to \$39,000 a year. Compare the full details in our <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a>, <a href="/scholarships/university-of-sydney-rtp-domestic-scholarship">Sydney RTP Domestic</a>, <a href="/scholarships/university-of-melbourne-rtp-scholarship">University of Melbourne RTP</a> and <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP</a> guides.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does the ANU RTP Scholarship pay?</h3>
<p>AUD \$39,069 a year at the 2026 full-time rate, about AUD \$3,256 a month, paid fortnightly. The 2027 rate is AUD \$40,475 a year.</p>

<h3>Is the 31 August 2026 international deadline still open?</h3>
<p>No. International Round 1 closed on 31 August 2026. International applicants now apply by 15 April 2027 to start by 31 August 2027.</p>

<h3>When is the deadline for domestic students?</h3>
<p>31 October 2026 for Round 1, to start by 31 March 2027. The next domestic deadline is 15 April 2027.</p>

<h3>Do I need a separate scholarship application?</h3>
<p>No. You apply for admission and select the scholarship option in the same application. It must be complete, including referee reports, by the round's deadline.</p>

<h3>Does the scholarship include OSHC for international students?</h3>
<p>Yes. The RTP International Fee Offset Scholarship includes Overseas Student Health Cover for you and your immediate family, claimed when the scholarship starts.</p>

<h3>What marks do I need?</h3>
<p>PhD admission needs first class or upper second class honours, or an equivalent. The scholarship itself asks for first class honours (H1) or an H1 equivalent. ANU sets no GPA cut-off such as 3.0.</p>

<h3>What IELTS score does ANU need for a PhD?</h3>
<p>IELTS Academic 6.5 overall with no band below 6.0, or an accepted equivalent. The PhD in Clinical Psychology asks for 7.0 in every band.</p>

<h3>Is the ANU stipend taxed?</h3>
<p>ANU says full-time stipends are not considered taxable income and part-time stipends are. The ATO's conditions are that the payment is principally for your education and you study full-time.</p>

<h3>How long does the scholarship last?</h3>
<p>The stipend lasts 3.5 years for a PhD and 1.5 years for an MPhil, with short extensions possible up to four and two years in total. Tuition is covered for four years (PhD) and two years (MPhil).</p>

<h3>Do I need a supervisor before I apply?</h3>
<p>Most ANU colleges want you to secure a potential supervisor's support first, and ANU says it may not be able to assess an application without that endorsement.</p>

<h2>Contact and Official Links</h2>
<ul>
<li>RTP scholarship enquiries: <a href="mailto:grs@anu.edu.au">grs@anu.edu.au</a>, +61 2 6125 5777</li>
<li>Research admissions: <a href="mailto:hdr.admissions@anu.edu.au">hdr.admissions@anu.edu.au</a></li>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official RTP Stipend Scholarship page</a></li>
<li><a href="https://study.anu.edu.au/scholarships/find-scholarship/research-training-program-rtp-international-fee-offset-scholarship" target="_blank" rel="noopener">RTP International Fee Offset Scholarship</a></li>
<li><a href="https://study.anu.edu.au/scholarships/find-scholarship/research-training-program-rtp-domestic-fee-offset-scholarship" target="_blank" rel="noopener">RTP Domestic Fee Offset Scholarship</a></li>
<li><a href="https://research.anu.edu.au/postgraduate-research-candidates/research-degree-scholarships" target="_blank" rel="noopener">Research degree scholarships and round dates</a></li>
<li><a href="https://study.anu.edu.au/apply/postgraduate-research" target="_blank" rel="noopener">How to apply for postgraduate research at ANU</a></li>
</ul>

<p><em>JobGader is not part of the Australian National University. This guide was checked against ANU's official pages on 11 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
