<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The University of Melbourne's Graduate Research Scholarships (the Melbourne
 * Research Scholarship and the Research Training Program Scholarship) for
 * PhD and research master's students, written up as a plain-English guide.
 *
 * Checked on 11 September 2026 against the Graduate Research Scholarships,
 * RTP and MRS award pages, the scholarship terms and conditions, the RTP
 * scholarship policy, each PhD course's How to apply tab, the graduate
 * research application pages and the facts and figures page. Corrections to
 * the brief and the posters:
 *
 * 1. The stipend is $39,500 (2026), not "~$38,500"; the published posters
 *    have the amount corrected. No 2027 rate is published.
 * 2. The brief says deadlines are rolling with no fixed date. Each faculty
 *    sets its own rounds, and several 2027 rounds have closed (Arts
 *    international 15 August, Engineering and IT Round 1 19 July 2026).
 * 3. The brief says tuition is 100% remitted for everyone and paid by the
 *    government. Domestic students get an RTP Fee Offset automatically;
 *    international fee remission comes only with a competitive award, and
 *    the Melbourne Research Scholarship is university-funded.
 * 4. OSHC is single cover. The relocation grant is $2,000 or $3,000.
 * 5. The brief asks for a GPA of 3.0, IELTS 6.5 and a 1,000-word proposal.
 *    Entry is a research honours or master's degree with a WAM of 75% (80% in
 *    some faculties), Arts, Education and Architecture need IELTS 7.0, and
 *    proposal lengths run from 500 to 5,000 words by faculty.
 * 6. The brief's gradresearch@unimelb.edu.au, +61 3 9035 3500 and
 *    study.unimelb.edu.au/degrees (404) are not Melbourne's; the posters'
 *    rto@unimelb.edu.au is not listed either and is removed from them.
 * 7. The brief calls Melbourne "#1 or #2" with 45,000 students and HECS-HELP.
 *    It is #1 in Australia in THE and ARWU and 2nd (22nd in the world) in QS
 *    2027, has 58,000+ students, and domestic research fees are already
 *    covered by the fee offset.
 * 8. The brief says the stipend is tax-free with no declaration. Part-time
 *    stipends have tax withheld and need a TFN declaration.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class MelbourneRtpScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-melbourne-rtp-scholarship';

    public const APPLY_URL = 'https://scholarships.unimelb.edu.au/awards/graduate-research-scholarships';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Melbourne RTP Scholarship 2026–27',
                'provider' => 'The University of Melbourne',
                'country' => 'Australia',
                'city' => 'Melbourne',
                'study_level' => "PhD, Master's by Research",
                'funding_type' => 'Fully Funded',
                'award_value' => 'AUD $39,500 per year',
                'deadline' => null,
                'deadline_note' => 'Varies by course: Round 1 closes 18 Sep–31 Oct 2026',
                'excerpt' => "The University of Melbourne's Graduate Research Scholarships pay AUD $39,500 a year plus 100% fee remission for a PhD or research master's. Round 1 closing dates run from 18 September to 31 October 2026 by faculty.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/university-of-melbourne-rtp-scholarship.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'University of Melbourne RTP Scholarship 2026: How to Apply',
                'meta_description' => "Melbourne's Graduate Research Scholarships pay AUD $39,500 a year (2026) plus fee remission and OSHC. Deadlines by faculty, entry marks and how to apply.",
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-11 00:10:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>University of Melbourne</strong> funds PhD and research master's students through its <strong>Graduate Research Scholarships</strong>: the university's own <strong>Melbourne Research Scholarship (MRS)</strong> and the Australian Government's <strong>Research Training Program (RTP) Scholarship</strong>. A full scholarship pays a living allowance of <strong>AUD \$39,500 a year at the 2026 rate</strong>, plus 100% fee remission and, for international students, health cover. Domestic and international students are both eligible, and you are considered automatically when you apply for your course on time.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Deadlines depend on your faculty.</strong> For PhDs starting in 2027, the next closing date is <strong>18 September 2026</strong> (Education), and most Round 1 dates fall between <strong>30 September and 31 October 2026</strong>. Some have already closed: Arts for international applicants on 15 August 2026, and Engineering and IT Round 1 on 19 July 2026.</p>
</div>

<h2>Melbourne Graduate Research Scholarship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>The University of Melbourne, Australia</td></tr>
<tr><th>Scholarships</th><td>Melbourne Research Scholarship (funded by the university) and Research Training Program Scholarship (funded by the Australian Government)</td></tr>
<tr><th>Who can apply</th><td>International students, and domestic students: Australian and New Zealand citizens, permanent residents and permanent humanitarian visa holders</td></tr>
<tr><th>Study level</th><td>PhD, doctorate by research, MPhil and master's by research</td></tr>
<tr><th>Living allowance</th><td>AUD \$39,500 a year (2026 full-time rate), paid fortnightly</td></tr>
<tr><th>Tuition fees</th><td>100% fee remission for up to 4 years (doctorate) or 2 years (master's)</td></tr>
<tr><th>Health cover (OSHC)</th><td>Single cover for international students who need a student visa</td></tr>
<tr><th>Relocation grant</th><td>AUD \$2,000 from another state, AUD \$3,000 from overseas</td></tr>
<tr><th>How long the stipend lasts</th><td>3.5 years for a doctorate, 2 years for a master's, with no extension</td></tr>
<tr><th>How to apply</th><td>Apply for your course by its closing date; consideration is automatic</td></tr>
<tr><th>Places</th><td>The scholarship page lists 600</td></tr>
</tbody>
</table></div>

<h2>What the Scholarship Pays</h2>

<h3>1. A living allowance</h3>
<p>The stipend is <strong>AUD \$39,500 a year at the 2026 full-time rate</strong>, about AUD \$3,292 a month, paid fortnightly. The university says rates may be indexed each year, but it <strong>has not published a 2027 rate</strong>, so treat any 2027 figure you see elsewhere as a guess. Part-time scholars receive half the full-time rate.</p>
<p>Aboriginal and Torres Strait Islander domestic students can receive the <strong>RTP Scholarship (Indigenous)</strong>, which pays <strong>AUD \$53,600 a year</strong> (2026) with the same fee offset and relocation grant. Melbourne says every eligible applicant receives it.</p>

<h3>2. Tuition fees</h3>
<ul>
<li><strong>Domestic students</strong> offered a place receive a <strong>Research Training Program Fee Offset Scholarship automatically</strong>. It covers fees but pays no living allowance; the stipend is competitive.</li>
<li><strong>International students</strong> have their fees covered only if they win a competitive MRS or RTP award.</li>
<li>Fees are covered for up to <strong>four years for a doctorate</strong> and <strong>two years for a master's by research</strong>. The Student Services and Amenities Fee (SSAF) is not covered.</li>
</ul>

<h3>3. Overseas Student Health Cover</h3>
<p>International scholarship holders who need a student visa get OSHC, but it is <strong>single cover only</strong>. If you bring a partner or children, budget for family cover yourself.</p>

<h3>4. A relocation grant</h3>
<p>You receive <strong>AUD \$2,000</strong> if you move from another Australian state, or <strong>AUD \$3,000</strong> if you move from overseas, usually with your first stipend payment. You must have lived outside Victoria when you applied, not have studied at a Victorian institution in the previous 12 months, and have moved to Melbourne to start your degree.</p>

<h3>5. Paid leave</h3>
<ul>
<li>20 working days of recreation leave and 10 days of personal leave each year</li>
<li>Up to 60 extra days of personal leave</li>
<li>100 working days of parental leave after 12 months of study, and 5 days of partner leave</li>
<li>10 days of family violence leave (5 if you study part-time)</li>
</ul>
<p>The terms list no thesis, conference or dependant allowances as part of the scholarship.</p>

<h3>6. How long the money lasts</h3>
<p>The stipend runs for up to <strong>3.5 years for a doctorate</strong> and <strong>2 years for a master's by research</strong>, full-time. Scholarships are awarded for the maximum length and <strong>cannot be extended</strong>. Approved unpaid leave moves the end date back, and study you have already done towards the same course is taken off.</p>

<h3>7. Tax</h3>
<p>Melbourne says scholarship payments are <strong>normally exempt from income tax</strong> when you are enrolled full-time and the scholarship is for educational purposes. If you study <strong>part-time</strong>, the university withholds tax and asks you for a Tax File Number declaration.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-melbourne-rtp-scholarship-campus.jpg" alt="PhD graduates on the University of Melbourne campus with the Graduate Research Scholarship stipend and benefits" width="1200" height="628" loading="lazy">
<figcaption>AUD \$39,500 a year at the 2026 rate, with fee remission, OSHC and a relocation grant.</figcaption>
</figure>

<h2>Who Can Apply</h2>

<h3>Domestic or international</h3>
<p>You apply as a <strong>domestic student</strong> if you are an Australian citizen, a New Zealand citizen, an Australian permanent resident or the holder of an Australian permanent humanitarian visa. If your permanent residency application is still being processed, you must apply as an international student.</p>

<h3>Academic results</h3>
<ul>
<li><strong>Entry:</strong> a four-year honours degree or a master's degree that includes research worth at least a quarter of a year, typically with a <strong>weighted average mark (WAM) of 75%</strong>. Medicine, Dentistry and Health Sciences asks for 80%, and Arts asks for 80% on the research component.</li>
<li><strong>The scholarship</strong> is ranked on your academic results and your research potential, so entry marks are rarely enough. Engineering and IT says successful applicants are typically in the top 5% of their class, that 80% is not usually competitive, and that past winners averaged about 85%.</li>
</ul>

<h3>English language</h3>
<ul>
<li><strong>Engineering and IT, Science, and Medicine, Dentistry and Health Sciences PhDs:</strong> IELTS 6.5 overall, with at least 6.0 in writing, speaking, reading and listening</li>
<li><strong>Arts, Education, and Architecture, Building and Planning PhDs:</strong> IELTS <strong>7.0 overall</strong>, with 7.0 in writing and 6.5 in speaking, reading and listening</li>
</ul>

<h2>Degrees You Can Study</h2>
<ul>
<li><strong>Doctor of Philosophy (PhD)</strong> and doctorates by research: fees for up to 4 years, stipend for up to 3.5 years</li>
<li><strong>Master of Philosophy (MPhil)</strong> and masters by research: fees and stipend for up to 2 years</li>
</ul>
<p>The Master of Research in Medicine, Dentistry and Health Sciences is by invitation only and open only to eligible international students. Coursework master's degrees are not covered.</p>

<h2>Deadlines by Faculty for a 2027 PhD Start</h2>
<p>Melbourne accepts graduate research applications all year, but each course has closing dates for its intakes and scholarship rounds. These are the PhD dates each faculty lists on its course page:</p>
<div class="scholar-table"><table>
<thead><tr><th>Faculty</th><th>Closing dates</th><th>Status on 11 September 2026</th></tr></thead>
<tbody>
<tr><td>Education</td><td>18 September 2026, for a February 2027 start</td><td>Open</td></tr>
<tr><td>Fine Arts and Music</td><td>30 September 2026, Semester 1 only</td><td>Open</td></tr>
<tr><td>Architecture, Building and Planning</td><td>International 30 September 2026; domestic 31 October 2026; Round 2 on 31 March 2027 only if scholarships remain</td><td>Open</td></tr>
<tr><td>Science</td><td>Round 1: 1 October 2026; Round 2: 1 February 2027</td><td>Open</td></tr>
<tr><td>Engineering and IT</td><td>Round 1: 19 July 2026; Round 2: 11 October 2026</td><td>Round 1 closed, Round 2 open</td></tr>
<tr><td>Arts</td><td>International 15 August 2026; domestic 15 October 2026; no mid-year intake</td><td>Closed for international, open for domestic</td></tr>
<tr><td>Medicine, Dentistry and Health Sciences</td><td>Round 1: 31 October 2026; Round 2: 31 January 2027; Round 3: 15 May 2027</td><td>Open (most scholarships go in Round 1)</td></tr>
<tr><td>Business and Economics, Law, Veterinary and Agricultural Sciences</td><td>Check the How to apply tab on the course page</td><td>&mdash;</td></tr>
</tbody>
</table></div>
<ul>
<li><strong>Your application must be complete</strong> by the closing date, including referee reports and, for Arts international applicants, English test results.</li>
<li><strong>Medicine, Dentistry and Health Sciences</strong> also needs your final results at least two weeks before the outcome date.</li>
<li><strong>Already enrolled or deferred?</strong> Submit the online scholarship form by 31 October.</li>
</ul>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-melbourne-rtp-scholarship-research.jpg" alt="Research laboratory with the University of Melbourne RTP Scholarship details for PhD applicants" width="1200" height="628" loading="lazy">
<figcaption>The poster says "rolling deadlines". In practice each faculty sets its own rounds, so check your course page.</figcaption>
</figure>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Choose your course</strong> from Melbourne's graduate research degrees and note its closing dates on the How to apply tab.</li>
<li><strong>Find a supervisor.</strong> For most courses you need to show evidence of a supervisor's support in your application. Arts requires written support from a principal supervisor and a co-supervisor before you apply, followed by an interview.</li>
<li><strong>Write your research proposal</strong> to your faculty's length: up to 500 words for Engineering and IT, Science, and Medicine, Dentistry and Health Sciences; up to 2,500 for Architecture; 4,000 to 5,000 for Education; and up to 5,000 for Arts, which also wants a 3,000 to 5,000 word writing sample.</li>
<li><strong>Line up two referees.</strong> If you finished your last degree less than five years ago, they should be academic referees.</li>
<li><strong>Apply online</strong> through <a href="https://study.unimelb.edu.au/how-to-apply/graduate-research" target="_blank" rel="noopener">Melbourne's graduate research application</a>. There is no application fee for research degrees.</li>
<li><strong>Apply by your course's closing date.</strong> A complete application by then puts you in the running for a scholarship automatically.</li>
</ol>

<div class="scholar-note">
<p><strong>Automatic, with a catch.</strong> There is no separate scholarship form for new applicants, but you are only considered if your complete application is in by your course's closing date. Domestic students get the fee offset automatically; the living allowance is competitive for everyone.</p>
</div>

<h2>Documents to Prepare</h2>
<ul>
<li>Academic transcripts</li>
<li>A CV, including any publications</li>
<li>A research proposal of the length your faculty sets</li>
<li>Evidence of a supervisor's support, for most courses</li>
<li>Details of two referees</li>
<li>Evidence of English language proficiency, if it applies to you</li>
</ul>

<h2>Rules While You Hold the Scholarship</h2>
<ul>
<li><strong>Hours:</strong> Melbourne expects about 40 hours a week on your research full-time, or 20 hours part-time.</li>
<li><strong>Paid work:</strong> the university sets no limit on paid work for RTP scholars. International students must still follow their visa's work conditions.</li>
<li><strong>Progress reviews:</strong> for a PhD, pre-confirmation at 6 months, confirmation at 1 year, then reviews at 2, 3 and 3.5 years. For a master's, confirmation at 6 months and reviews at 1 and 1.5 years.</li>
<li><strong>Time on campus:</strong> you must spend at least 12 months full-time equivalent (doctorate) or 6 months (master's) at the university. Studying away needs approval in advance.</li>
<li><strong>No extensions:</strong> the scholarship ends at its maximum length.</li>
</ul>

<h2>Claims About This Scholarship to Ignore</h2>
<ul>
<li><strong>"About \$38,500 a year."</strong> The 2026 rate is AUD \$39,500.</li>
<li><strong>"About \$40,000 in 2027."</strong> Melbourne has not published a 2027 rate.</li>
<li><strong>"Rolling deadlines, no fixed date."</strong> Each faculty sets closing dates, and some 2027 rounds have closed.</li>
<li><strong>"100% tuition covered by the government for everyone."</strong> Domestic students get an automatic fee offset; international students need a competitive award, and the MRS is paid for by the university.</li>
<li><strong>"IELTS 6.5 and a 1,000-word proposal."</strong> Arts, Education and Architecture need IELTS 7.0, and proposal lengths run from 500 to 5,000 words.</li>
<li><strong>"45,000 students."</strong> Melbourne reports more than 58,000.</li>
<li><strong>"HECS-HELP is available."</strong> Domestic research students do not need it: the RTP Fee Offset already covers their fees.</li>
</ul>
<p>On rankings, Melbourne is <strong>#1 in Australia</strong> in the Times Higher Education and ARWU 2026 rankings (37th in the world in both) and <strong>22nd in the world</strong>, second in Australia, in the QS World University Rankings 2027.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-melbourne-rtp-scholarship-building.jpg" alt="University of Melbourne sandstone building with the RTP Fee Offset and Graduate Research Scholarship details" width="1200" height="628" loading="lazy">
<figcaption>Open to domestic and international PhD and research master's applicants.</figcaption>
</figure>

<h2>Melbourne vs Sydney vs ANU vs Monash: RTP Stipends Compared</h2>
<div class="scholar-table"><table>
<thead><tr><th>University</th><th>2026 full-time stipend</th><th>International health cover</th></tr></thead>
<tbody>
<tr><td>University of Sydney</td><td>AUD \$42,754</td><td>Included</td></tr>
<tr><td>University of Melbourne</td><td>AUD \$39,500</td><td>Single cover included</td></tr>
<tr><td>Australian National University</td><td>AUD \$39,069</td><td>Included for you and your family</td></tr>
<tr><td>Monash University</td><td>AUD \$37,145</td><td>See our Monash guide</td></tr>
</tbody>
</table></div>
<p>At 2026 rates Melbourne pays AUD \$3,254 a year less than Sydney, AUD \$431 more than ANU and AUD \$2,355 more than Monash, the other large research university in the same city. Compare the details in our <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a>, <a href="/scholarships/australian-national-university-rtp-scholarship">ANU RTP</a> and <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP</a> guides.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does the University of Melbourne RTP Scholarship pay?</h3>
<p>AUD \$39,500 a year at the 2026 full-time rate, about AUD \$3,292 a month, paid fortnightly. No 2027 rate has been published yet.</p>

<h3>When is the deadline?</h3>
<p>It depends on your faculty. For a 2027 PhD start, Education closes on 18 September 2026 and most other Round 1 dates fall between 30 September and 31 October 2026. Arts closed for international applicants on 15 August 2026.</p>

<h3>Do I need a separate scholarship application?</h3>
<p>No. New applicants are considered automatically if their complete application, including referee reports, is in by the course's closing date. Students already enrolled or deferred use an online form by 31 October.</p>

<h3>Can international students get the scholarship?</h3>
<p>Yes. International students compete for the Melbourne Research Scholarship and the RTP Scholarship, which cover fees, the living allowance and single OSHC.</p>

<h3>What marks do I need?</h3>
<p>Entry typically needs a weighted average mark (WAM) of 75% in a research honours or master's degree, or 80% in some faculties. Scholarships are ranked on results and research potential; in Engineering and IT, past winners averaged about 85%.</p>

<h3>What IELTS score does Melbourne need for a PhD?</h3>
<p>6.5 overall with no band below 6.0 for Engineering and IT, Science, and Medicine, Dentistry and Health Sciences. Arts, Education, and Architecture, Building and Planning need 7.0 overall with 7.0 in writing and 6.5 in the other bands.</p>

<h3>Do I need a supervisor before I apply?</h3>
<p>For most courses, yes: your application must include evidence of a supervisor's support. Arts needs written support from two supervisors before you apply.</p>

<h3>Is the stipend taxed?</h3>
<p>Melbourne says it is normally exempt from income tax when you study full-time and the scholarship is for educational purposes. Part-time stipends have tax withheld, and you give the university a Tax File Number declaration.</p>

<h3>Can the scholarship be extended?</h3>
<p>No. It lasts up to 3.5 years for a doctorate and 2 years for a master's by research. Approved unpaid leave moves the end date back without adding money.</p>

<h2>Contact and Official Links</h2>
<ul>
<li>Scholarships, fees and visas: contact <strong>Stop 1</strong>, the university's student services team</li>
<li>Graduate research applications: your faculty's admissions contact on the <a href="https://gradresearch.unimelb.edu.au/key-contacts" target="_blank" rel="noopener">key contacts page</a>, for example sciencegr-admissions@unimelb.edu.au or arts-gr@unimelb.edu.au</li>
<li>Phone: 13 MELB (13 6352), or +61 3 9035 5511 from overseas</li>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official Graduate Research Scholarships page</a></li>
<li><a href="https://scholarships.unimelb.edu.au/awards/research-training-program-scholarship/" target="_blank" rel="noopener">Research Training Program Scholarship</a></li>
<li><a href="https://scholarships.unimelb.edu.au/awards/melbourne-research-scholarship/" target="_blank" rel="noopener">Melbourne Research Scholarship</a></li>
<li><a href="https://gradresearch.unimelb.edu.au/scholarships/graduate-research-scholarship-terms-and-conditions" target="_blank" rel="noopener">Scholarship terms and conditions</a></li>
<li><a href="https://study.unimelb.edu.au/how-to-apply/graduate-research" target="_blank" rel="noopener">How to apply for graduate research</a></li>
</ul>

<p><em>JobGader is not part of the University of Melbourne. This guide was checked against the university's official pages on 11 September 2026. Amounts and dates change, so confirm them on your course page before you apply.</em></p>
HTML;
    }
}
