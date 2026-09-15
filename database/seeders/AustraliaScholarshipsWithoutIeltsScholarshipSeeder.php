<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * "Australia scholarships without IELTS" — a roundup of the seven awards in
 * the owner's brief, rewritten around what each one actually asks for.
 *
 * Checked on 14 September 2026 against the Australia Awards Scholarships
 * Policy Handbook (November 2025), Study Australia's Australia Awards 2027
 * notice and its language testing page, RMIT's Research Stipend Scholarships
 * page, Monash's International Merit Scholarship page, and the University of
 * Melbourne and Deakin scholarship pages, plus JobGader's own verified RTP
 * guides. Corrections to the brief:
 *
 * 1. The brief's headline is "No IELTS needed" and "No language test
 *    required". Australia Awards requires Academic IELTS 6.5 with no band
 *    below 6.0, TOEFL iBT 84 or PTE Academic 58, and says scores are "not
 *    negotiable"; the only exemption is for applicants whose first language
 *    is English and who were educated in English. Universities set their own
 *    English requirement and RMIT wants it met by the closing date.
 *
 * 2. The brief and posters list Duolingo as an alternative. Home Affairs does
 *    not accept at-home or online tests for the Student visa.
 *
 * 3. Australia Awards living allowance "AUD $26,000". From 1 January 2026 the
 *    Contribution to Living Expenses is A$99.26 a day, about A$36,230 a year.
 *    The A$5,000 establishment allowance is correct.
 *
 * 4. Australia Awards "usually April 30". The 2027 round ran from 1 February
 *    to 30 April 2026 and has closed. Pre-course English is not a general
 *    fallback: it is up to six months, at the country program's election, for
 *    awardees within half an IELTS point of 6.5 (10 TOEFL, 7 PTE points).
 *
 * 5. RTP "AUD $32,500-$42,000" for "all international students" with no
 *    IELTS. The 2026 rates we have verified run from AUD $36,245 (RMIT) to
 *    AUD $42,754 (Sydney), and every university sets an English requirement.
 *
 * 6. RMIT "$35,886" with a "September 30, 2025" deadline. The 2026 stipend is
 *    $36,245; that deadline passed a year ago, and the 2027 round is open.
 *
 * 7. Monash "International Study Grants, AUD $5,000-$10,000". The Monash
 *    International Merit Scholarship pays $15,000 a year for awards from 2026
 *    ($10,000 before), up to $75,000, for undergraduates.
 *
 * 8. Deakin "STEM Scholarships, 20% discount". The Deakin International 20%
 *    Merit Scholarship is not limited to STEM; it is assessed automatically
 *    and excludes some limited-place Health programs.
 *
 * 9. Melbourne "25%-100% tuition reduction". The Melbourne International
 *    Undergraduate Scholarship is a 25 per cent fee sponsorship for awards
 *    before 2027 and 20 per cent from 2027, for citizens of countries with GDP
 *    per capita of US$10,000 or less.
 *
 * 10. Destination Australia "up to AUD $15,000/year" for international
 *     students. There have been no further funding rounds since 1 July 2024.
 *
 * 11. The brief's claims that international fees are "50-80% cheaper" than
 *     domestic ones, its "+61 2 (Australian Govt)" phone number and its Deakin
 *     "January 20" deadline have no source and are left out.
 *
 * The posters are used as supplied, at the owner's instruction; the text
 * corrects them.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class AustraliaScholarshipsWithoutIeltsScholarshipSeeder extends Seeder
{
    public const SLUG = 'australia-scholarships-without-ielts';

    public const APPLY_URL = 'https://australiaawardspakistan.org/';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Australia Scholarships Without IELTS 2026: What Is Really Accepted',
                'provider' => 'Australian Government and Australian universities',
                'country' => 'Australia',
                'city' => 'Multiple cities',
                'study_level' => "Bachelor's, Master's, PhD",
                'funding_type' => 'Full and partial scholarships',
                'award_value' => 'Up to full tuition plus about AUD $36,230 a year (Australia Awards)',
                'deadline' => null,
                'deadline_note' => 'Varies by scholarship: see the deadlines table',
                'excerpt' => 'Most Australian scholarships do not name IELTS, but nearly all need an English test: Australia Awards asks for IELTS 6.5, TOEFL iBT 84 or PTE 58. Duolingo does not count for the Student visa, and Destination Australia has closed.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Australia Scholarships Without IELTS: What Is True in 2026',
                'meta_description' => 'Australia scholarships without IELTS: the tests Australia Awards and universities accept, why Duolingo fails the Student visa, and 2026 rates.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-13 18:30:00'),
            ]
        );
    }

    private function guide(): string
    {
        return strtr(<<<'HTML'
<p>Searches for "Australia scholarships without IELTS" usually lead to the same promise: fully funded study, and no English test. The first half is real &mdash; Australia has generous government and university scholarships, and Pakistani students win them. The second half is mostly not. What is true is narrower: <strong>IELTS is rarely the only test accepted</strong>, so you can often use TOEFL iBT or PTE Academic instead. Almost every applicant still needs <em>an</em> English test.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Before you plan around this list:</strong></p>
<ul>
<li><strong>Australia Awards requires an English test score</strong> unless your first language is English and you were educated in English, and says its scores are not negotiable.</li>
<li><strong>Duolingo does not count for the Student visa.</strong> Home Affairs does not accept at-home or online tests, even where a university accepts them for admission.</li>
<li><strong>Destination Australia is closed to new students.</strong> There have been no further funding rounds since 1 July 2024.</li>
<li><strong>The Australia Awards 2027 round has closed.</strong> It ran from 1 February to 30 April 2026.</li>
</ul>
</div>

<h2>The Seven Scholarships at a Glance</h2>
<div class="scholar-table"><table>
<thead><tr><th>Scholarship</th><th>What it pays</th><th>English</th><th>Status</th></tr></thead>
<tbody>
<tr><td>Australia Awards</td><td>Full tuition, A$5,000 establishment allowance, about AUD $36,230 a year living allowance, airfare, OSHC</td><td>IELTS 6.5 (no band below 6.0), TOEFL iBT 84 or PTE Academic 58</td><td>2027 round closed 30 April 2026</td></tr>
<tr><td>Research Training Program (RTP)</td><td>Stipend of AUD $36,245 to AUD $42,754 a year (2026, by university) and tuition for international winners</td><td>Set by each university</td><td>Rounds vary by university</td></tr>
<tr><td>RMIT Research Stipend Scholarships</td><td>AUD $36,245 a year (2026), international tuition scholarship, relocation up to $1,540</td><td>Must be met by the closing date</td><td>2027 round open</td></tr>
<tr><td>Monash International Merit Scholarship</td><td>$15,000 a year, up to $75,000 (undergraduate)</td><td>Your course offer's requirement</td><td>Considered with an undergraduate offer</td></tr>
<tr><td>University of Melbourne International Undergraduate Scholarship</td><td>25 per cent fee sponsorship (20 per cent from 2027)</td><td>Your course offer's requirement</td><td>For citizens of lower-income countries</td></tr>
<tr><td>Deakin International 20% Merit Scholarship</td><td>20% off the indicative tuition fee</td><td>Your course offer's requirement</td><td>Assessed automatically</td></tr>
<tr><td>Destination Australia</td><td>&mdash;</td><td>&mdash;</td><td>No funding rounds since 1 July 2024</td></tr>
</tbody>
</table></div>

<h2>What "Without IELTS" Really Means</h2>
<p>There are two separate English checks, and a scholarship page only tells you about the first.</p>

<h3>1. The scholarship or university requirement</h3>
<p>Each scholarship or university decides which tests it accepts and the score it needs. Australia Awards accepts IELTS, TOEFL and PTE Academic. Universities commonly accept several tests, and some accept evidence of earlier study in English. That is the part that makes "without IELTS" partly true: you may be able to use a different test. It is not the same as needing no test.</p>

<h3>2. The Student visa requirement</h3>
<p>Separately, the Department of Home Affairs sets the English tests it accepts for the Student visa (subclass 500). According to Study Australia, Home Affairs accepts these tests, taken at a secure test centre:</p>
<ul>
<li>Cambridge C1 Advanced</li>
<li>CELPIP General</li>
<li>IELTS Academic and IELTS General Training</li>
<li>LANGUAGECERT Academic</li>
<li>Michigan English Test (MET)</li>
<li>Occupational English Test (OET)</li>
<li>PTE Academic</li>
<li>TOEFL iBT &mdash; choosing the "TOEFL iBT for Australia" option, scored 0 to 120</li>
</ul>
<p>At-home and online versions such as TOEFL iBT Special Home Edition, OET@Home, IELTS Indicator and IELTS Online are not on that list: Study Australia says Home Affairs does not accept scores from these tests or other at-home or online tests. The <strong>Duolingo English Test</strong> is taken online, so a Duolingo score that gets you a university offer will not carry your visa application.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/australia-scholarships-without-ielts-universities.jpg" alt="Australia fully funded scholarships poster listing Australia Awards, RTP, Monash, RMIT, Melbourne, Deakin and Destination Australia" width="1200" height="628" loading="lazy">
<figcaption>Posters for this search promise "No IELTS". Most of these awards accept other tests, but still need one.</figcaption>
</figure>

<h2>1. Australia Awards Scholarships</h2>
<p>Funded by the Department of Foreign Affairs and Trade (DFAT), Australia Awards is the most generous scholarship on the list and the one with the firmest English rule.</p>

<h3>What it covers</h3>
<p>The Australia Awards Scholarships Policy Handbook (November 2025) lists these standard entitlements:</p>
<ul>
<li>Full tuition fees</li>
<li>Return air travel</li>
<li>An establishment allowance of A$5,000, for costs such as rental bonds, textbooks and study materials</li>
<li>A Contribution to Living Expenses: from 1 January 2026 it is AUD $99.26 a day, or about AUD $36,230 a year, paid fortnightly</li>
<li>A compulsory 4 to 6 week Introductory Academic Program on arrival</li>
<li>Overseas Student Health Cover</li>
<li>Initial visa expenses</li>
</ul>
<p>The posters for this search put the living allowance at AUD $26,000. That is well below the 2026 rate. No official page gives a total "value" of $75,000 to $95,000 a year, so treat that figure as an estimate.</p>

<h3>The English requirement</h3>
<p>The handbook says English language scores are not negotiable. Every awardee must have an Academic IELTS result with an overall score of at least 6.5, with no band less than 6.0, or an internet based TOEFL score of at least 84, with a minimum of 21 in all subtests, or a PTE Academic overall score of 58 with no communicative skill score less than 50. Results must be valid at 1 January of the year you start.</p>
<p>The one exemption: you meet DFAT's requirement if your <strong>first language is English and you were educated in English</strong>, shown by your transcripts. For most Pakistani applicants that does not apply. And where your university asks for a higher score than DFAT, you must meet the university's score.</p>

<h3>Pre-course English is not a way around the test</h3>
<p>Country programs may choose to fund English training in your home country or region for up to six months, but only for awardees who are close: within half a point of IELTS 6.5, within 10 points of the TOEFL iBT score, or within 7 points of PTE Academic. It helps someone who is nearly there. It does not replace the test.</p>

<h3>Dates</h3>
<p>Applications for the 2027 intake were open from <strong>1 February 2026</strong> to <strong>30 April 2026, 14.00 AEST</strong>, and that round has closed. The study levels on offer and eligibility conditions are set for each country, so read the Pakistan country profile on the Australia Awards Pakistan site before the next round opens.</p>

<h2>2. Research Training Program (RTP) Scholarships</h2>
<p>RTP scholarships are Australian Government funded and awarded by each university to PhD and research master's students. They are competitive, and each university sets its own English requirement, rounds and stipend. These are the 2026 full-time stipend rates we have checked on each university's own pages:</p>
<div class="scholar-table"><table>
<thead><tr><th>University</th><th>2026 stipend</th><th>Our full guide</th></tr></thead>
<tbody>
<tr><td>RMIT University</td><td>AUD $36,245 a year</td><td>See the RMIT section below</td></tr>
<tr><td>Adelaide University (formerly UniSA)</td><td>AUD $36,500 a year</td><td><a href="/scholarships/{adelaide}">Adelaide University guide</a></td></tr>
<tr><td>Monash University</td><td>AUD $37,145 a year</td><td><a href="/scholarships/{monash}">Monash RTP guide</a></td></tr>
<tr><td>Australian National University</td><td>AUD $39,069 a year</td><td><a href="/scholarships/{anu}">ANU RTP guide</a></td></tr>
<tr><td>University of Melbourne</td><td>AUD $39,500 a year</td><td><a href="/scholarships/{melbourne}">Melbourne guide</a></td></tr>
<tr><td>University of Sydney</td><td>AUD $42,754 a year</td><td><a href="/scholarships/{sydney}">Sydney RTP International guide</a></td></tr>
</tbody>
</table></div>
<p>None of these is "IELTS-free". The University of Sydney's standard level, for example, is IELTS Academic 6.5 with no band below 6.0, and some faculties ask for more. The individual guides set out each university's accepted tests and scores.</p>

<h2>3. RMIT University Research Stipend Scholarships</h2>
<p>RMIT's page lists the benefits for the 2026 round, and says information for the 2027 round will be released shortly:</p>
<ul>
<li>A stipend of <strong>$36,245 a year</strong> (full-time), indexed annually. The $35,886 figure on the posters is out of date.</li>
<li>For international candidates, an RTP International Tuition Fee Offset Scholarship (RIFOS) or an RMIT Research International Tuition Fee Scholarship (RRITFS)</li>
<li>A relocation allowance of up to $515 per adult and $255 per child, to a maximum of $1,540</li>
<li>Paid sick, maternity and parenting leave</li>
</ul>
<p>Applications for scholarships starting in 2027 are open. Two conditions matter: international applicants must meet English language requirements by the application closing date, and every applicant must provide evidence from an academic who agrees to supervise the research when submitting an expression of interest. The "30 September 2025" deadline in the brief belongs to a round that closed a year ago.</p>

<h2>4. Monash International Merit Scholarship</h2>
<p>This is an undergraduate award, not a research grant. Monash says:</p>
<ul>
<li>Recipients receive <strong>$15,000 a year</strong> for scholarships awarded from 2026, paid until the minimum points for the degree are completed. Scholarships awarded before 2026 paid $10,000 a year.</li>
<li>The total value is up to $75,000, and 20 are offered a year.</li>
<li>International students who receive an undergraduate course offer from Monash are considered automatically.</li>
</ul>
<p>It is a partial scholarship, not full funding, and your English requirement is the one attached to your course offer. Monash research students are funded differently &mdash; see our <a href="/scholarships/{monash}">Monash RTP guide</a>.</p>

<h2>5. University of Melbourne International Undergraduate Scholarship</h2>
<ul>
<li>Scholarships awarded before 2027 give a <strong>25 per cent fee sponsorship</strong> for the course. From 2027 it becomes a <strong>20 per cent fee sponsorship</strong>, and the number of scholarships rises to 225.</li>
<li>You must be a citizen of a country with a Gross Domestic Product per capita of US$10,000 or less, based on World Bank data.</li>
<li>You need an offer of an Overseas Fee place in a bachelor's degree, and must not have undertaken tertiary studies before.</li>
<li>It cannot be combined with another fee sponsorship or discount.</li>
</ul>
<p>The brief's "25% to 100% tuition reduction" mixes this award with others. For fully funded research study at Melbourne, see our <a href="/scholarships/{melbourne}">Melbourne Graduate Research Scholarships guide</a>.</p>

<h2>6. Deakin University</h2>
<p>The brief calls this a STEM scholarship. Deakin's own description is broader:</p>
<ul>
<li>The <strong>Deakin International 20% Merit Scholarship</strong> gives a 20% reduction to the total indicative tuition fee in your offer letter. It is not limited to STEM courses.</li>
<li>You are assessed automatically on your academic results, with no separate application. It does not apply to some Health programs with limited places.</li>
<li>Deakin also offers the <strong>Vice-Chancellor's International Scholarship</strong>, covering 100% or 50% of tuition fees for high-achieving international coursework students.</li>
</ul>

<h2>7. Destination Australia: Closed</h2>
<p>Destination Australia paid up to AUD $15,000 a year to students at regional campuses. As part of the 2024&ndash;25 Budget, the Australian Government announced that there would be no further funding rounds of the program from 1 July 2024. Existing recipients continue to be supported, but it is not a scholarship you can apply for now, whatever a poster says.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/australia-scholarships-without-ielts-sydney.jpg" alt="Study in Australia scholarships poster with the Sydney Opera House and Harbour Bridge" width="1200" height="628" loading="lazy">
<figcaption>Check each scholarship's own page: rates, English rules and rounds change every year.</figcaption>
</figure>

<h2>Deadlines</h2>
<div class="scholar-table"><table>
<thead><tr><th>Scholarship</th><th>Next date we have verified</th></tr></thead>
<tbody>
<tr><td>Australia Awards</td><td>2027 round closed on 30 April 2026; next round not yet announced</td></tr>
<tr><td>University of Sydney RTP International</td><td>18 December 2026</td></tr>
<tr><td>University of Melbourne Graduate Research Scholarships</td><td>Round 1 closing dates run from 18 September to 31 October 2026, by faculty</td></tr>
<tr><td>ANU RTP (international)</td><td>15 April 2027</td></tr>
<tr><td>Monash RTP</td><td>Round 1 closing date not yet confirmed</td></tr>
<tr><td>RMIT Research Stipend Scholarships</td><td>2027 round open; see RMIT's page for the closing date</td></tr>
<tr><td>Monash, Melbourne and Deakin undergraduate awards</td><td>Considered with your course offer</td></tr>
<tr><td>Destination Australia</td><td>No funding rounds since 1 July 2024</td></tr>
</tbody>
</table></div>

<h2>How to Plan a Realistic Application</h2>
<ol>
<li><strong>Pick the level first.</strong> Undergraduate awards here are partial (20% to 25%, or $15,000 a year at Monash). Full funding is mainly Australia Awards and research scholarships.</li>
<li><strong>Book a test Home Affairs accepts.</strong> If you need a visa anyway, a centre-based IELTS, PTE Academic or TOEFL iBT for Australia score can serve both the university and the visa. A Duolingo score cannot.</li>
<li><strong>Check the exact score for your course,</strong> not just the scholarship minimum. The higher of the two applies.</li>
<li><strong>For research degrees, find a supervisor early.</strong> RMIT asks for evidence of one with your expression of interest.</li>
<li><strong>Watch the rounds.</strong> Australia Awards opens once a year; RTP rounds differ by university.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a scholarship in Australia without IELTS?</h3>
<p>Often without IELTS specifically, rarely without any English test. Australia Awards accepts IELTS, TOEFL iBT or PTE Academic, and universities set their own accepted tests. The only Australia Awards exemption is for applicants whose first language is English and who were educated in English.</p>

<h3>What English score does Australia Awards require?</h3>
<p>Academic IELTS 6.5 overall with no band below 6.0, internet-based TOEFL 84 with at least 21 in each subtest, or PTE Academic 58 with no communicative skill below 50. The handbook says the scores are not negotiable.</p>

<h3>Is the Duolingo English Test accepted for an Australian Student visa?</h3>
<p>No. Home Affairs accepts centre-based tests including IELTS, PTE Academic, TOEFL iBT, Cambridge C1 Advanced, CELPIP General, LANGUAGECERT Academic, MET and OET, and does not accept at-home or online tests.</p>

<h3>How much is the Australia Awards living allowance in 2026?</h3>
<p>The Contribution to Living Expenses is AUD $99.26 a day from 1 January 2026, about AUD $36,230 a year, plus a one-off establishment allowance of A$5,000.</p>

<h3>Is the Destination Australia scholarship still open?</h3>
<p>No. The Australian Government announced there would be no further funding rounds from 1 July 2024. Existing recipients continue to be supported.</p>

<h3>How much does the RMIT research scholarship pay?</h3>
<p>For the 2026 round, a stipend of $36,245 a year, a tuition scholarship for international candidates and a relocation allowance of up to $1,540. RMIT says 2027 details will be released shortly.</p>

<h3>How much is the Monash International Merit Scholarship?</h3>
<p>$15,000 a year for scholarships awarded from 2026, up to $75,000 in total, for international undergraduates who receive a Monash course offer.</p>

<h3>Does Deakin offer a 20% scholarship for international students?</h3>
<p>Yes. The Deakin International 20% Merit Scholarship takes 20% off the indicative tuition fee and is assessed automatically. It is not limited to STEM, and it excludes some limited-place Health programs.</p>

<h2>Related Scholarship Guides</h2>
<ul>
<li><a href="/scholarships/{sydney}">University of Sydney RTP International Scholarship</a> &mdash; AUD $42,754 a year and the next deadline.</li>
<li><a href="/scholarships/{monash}">Monash University RTP Scholarship</a> &mdash; stipend, IELTS and PTE scores, and rounds.</li>
<li><a href="/scholarships/{anu}">ANU RTP Scholarship</a> &mdash; the international round and 2027 rate.</li>
<li><a href="/scholarships/{melbourne}">University of Melbourne Graduate Research Scholarships</a> &mdash; faculty deadlines and entry marks.</li>
<li><a href="/scholarships/{adelaide}">Adelaide University (formerly UniSA) scholarships</a> &mdash; the AUD $36,500 stipend and the research round open now.</li>
<li><a href="/scholarships/{france}">France scholarships without IELTS</a> &mdash; what Eiffel pays and who applies for you.</li>
<li><a href="/scholarships/{yale}">Yale University Scholarship</a> &mdash; need-based aid in the United States, and what PhD funding covers.</li>
</ul>

<p><em>JobGader is not part of the Australian Government or any university. This guide was checked against the Australia Awards Scholarships Policy Handbook, Study Australia and each university's scholarship pages on 14 September 2026. Rates, English requirements and rounds change, so confirm them on the official page before you apply.</em></p>
HTML, [
            '{monash}' => MonashRtpScholarshipSeeder::SLUG,
            '{anu}' => AnuRtpScholarshipSeeder::SLUG,
            '{melbourne}' => MelbourneRtpScholarshipSeeder::SLUG,
            '{adelaide}' => UniversityOfSouthAustraliaScholarshipSeeder::SLUG,
            '{sydney}' => SydneyRtpInternationalScholarshipSeeder::SLUG,
            '{france}' => FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG,
            '{yale}' => YaleUniversityScholarshipSeeder::SLUG,
        ]);
    }
}
