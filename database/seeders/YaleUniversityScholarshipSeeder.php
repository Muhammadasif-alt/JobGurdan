<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * "Yale University Scholarship" — Yale College's need-based aid for
 * international undergraduates, plus Graduate School PhD funding.
 *
 * Checked on 15 September 2026 against finaid.yale.edu (cost of attendance,
 * affordability, types of aid), admissions.yale.edu (international
 * applicants, financial aid for international applicants, timelines,
 * standardized testing, requirements), Yale News (27 January 2026 aid
 * announcement, Class of 2030 admissions, 2 September 2026 welcome figures)
 * and gsas.yale.edu (PhD stipends, tuition and funding). Corrections to the
 * brief:
 *
 * 1. "If your family income is below $75,000" Yale covers everything, and
 *    elsewhere "less than $65,000". From students entering in 2026-27,
 *    families with typical assets and incomes below $100,000 pay nothing, and
 *    below $200,000 scholarships meet or exceed tuition.
 *
 * 2. "Up to $70,000/year" and an average grant "over $60,000" (or "over
 *    $50,000"). Aid meets full demonstrated need, which can exceed the whole
 *    cost of attendance; the average need-based scholarship is $75,220, and
 *    over $78,742 for new students in 2026-27.
 *
 * 3. "Living expenses $23,300" and "Total Cost of Attendance: $98,085". Housing
 *    is $12,080 and food $9,520; the listed 2026-27 items come to $97,985, and
 *    Yale publishes no total because travel varies.
 *
 * 4. "Early Action by Nov 1, 2025, Regular Decision by Jan 2, 2026" and "the
 *    financial aid deadline is the same as admission". Those dates have
 *    passed; Yale uses Single-Choice Early Action (1 November) and Regular
 *    Decision (2 January). International aid forms are due 1 December for
 *    early applicants and 15 February for Regular Decision.
 *
 * 5. "Results announced in April". Early decisions come in mid-December and
 *    Regular Decision in late March.
 *
 * 6. "Acceptance rate of approximately 4.6%". That was the Class of 2029; the
 *    Class of 2030 admitted 2,328 of 54,919 applicants, about 4.2%.
 *
 * 7. "Does not set a hard minimum" on English tests. Yale requires an English
 *    test from non-native speakers without two or more years in an
 *    English-medium school, and publishes typical competitive scores.
 *
 * 8. The brief lists "International financial aid forms". Yale uses the CSS
 *    Profile (code 3987) and IDOC; there is no separate international form.
 *
 * 9. PhD "Total Annual Value $104,000-$120,000+". Tuition is covered, not paid
 *    out; the cash is a stipend of at least $52,046 ($53,629 in the biological
 *    and biomedical sciences) and a one-time $1,500 relocation award.
 *
 * 10. The needs-analysis wording about "relative differences" between
 *     economies, the $80 fee (found only on a staging page) and the poster's
 *     "Airfare" and "Top 10" lines have no live official source and are left
 *     out.
 *
 * The posters are used as supplied, at the owner's instruction; the text
 * corrects them.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class YaleUniversityScholarshipSeeder extends Seeder
{
    public const SLUG = 'yale-university-scholarship';

    public const APPLY_URL = 'https://admissions.yale.edu/applying-yale-international-student';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Yale University Scholarship 2026: Need-Based Aid and PhD Funding',
                'provider' => 'Yale University',
                'country' => 'United States',
                'city' => 'New Haven, Connecticut',
                'study_level' => "Bachelor's, PhD",
                'funding_type' => 'Need-based (undergraduate), fully funded PhD',
                'award_value' => '100% of demonstrated need (undergraduate); PhD stipend from $52,046 a year',
                'deadline' => Carbon::parse('2026-11-01'),
                'deadline_note' => 'Regular Decision closes 2 Jan 2027 (fall 2027 entry)',
                'excerpt' => 'Yale has no merit scholarships. It admits international students need-blind and meets 100% of need: families with typical assets earning under $100,000 pay nothing. PhD students get tuition and a stipend from $52,046. Apply by 1 November or 2 January.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Yale University Scholarship 2026: Aid, Costs, PhD Funding',
                'meta_description' => 'Yale scholarship facts: no merit awards, need-blind for international students, $0 under $100,000 income, 2026-27 costs, deadlines and PhD stipends.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-15 06:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        return strtr(<<<'HTML'
<p>There is no single "Yale Scholarship" you apply for. What Yale offers is better, and stricter: <strong>Yale College admits every undergraduate without regard to ability to pay, including international students, and meets 100% of each admitted student's demonstrated financial need</strong> with scholarships that do not have to be repaid. PhD students are funded separately by the Graduate School. What Yale does not offer is a merit scholarship, and most master's students pay their own way.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Before you plan around the posters:</strong></p>
<ul>
<li><strong>There are no merit scholarships.</strong> Yale's financial aid office says merit-based scholarships are not offered by Yale. Grades get you admitted; need decides your aid.</li>
<li><strong>The income limits have changed.</strong> From 2026-27, families with typical assets earning below <strong>$100,000</strong> pay nothing, not the $65,000 or $75,000 still quoted online.</li>
<li><strong>The dates in circulation have passed.</strong> For fall 2027 entry, apply by <strong>1 November 2026</strong> (Single-Choice Early Action) or <strong>2 January 2027</strong> (Regular Decision).</li>
<li><strong>Admission is the hard part.</strong> The Class of 2030 admitted 2,328 of 54,919 applicants, about 4.2%.</li>
</ul>
</div>

<h2>Yale Funding at a Glance</h2>
<div class="scholar-table"><table>
<thead><tr><th>Level</th><th>What Yale pays</th><th>How you get it</th></tr></thead>
<tbody>
<tr><td>Undergraduate (Yale College)</td><td>100% of demonstrated need, as a scholarship; average need-based scholarship $75,220</td><td>Get admitted, then file the CSS Profile and IDOC documents</td></tr>
<tr><td>PhD (Graduate School of Arts and Sciences)</td><td>Full tuition ($52,400), a 12-month stipend of at least $52,046, health coverage, $1,500 relocation award</td><td>Automatic with a PhD offer, typically for at least five years</td></tr>
<tr><td>Master's (Graduate School)</td><td>Most students receive no Graduate School support</td><td>Some programs offer limited funding</td></tr>
<tr><td>Yale Young Global Scholars (summer, high school)</td><td>Need-based tuition discount of up to 100%</td><td>Apply for aid with the program application</td></tr>
</tbody>
</table></div>

<h2>Undergraduate Aid: Need-Blind for International Students</h2>
<p>Yale's international admissions page says it is one of only a handful of American universities that considers all applicants for admission without regard to their ability to pay and meets 100% of every family's demonstrated financial need. The financial aid page for international applicants puts it more directly: a family's ability to pay is not a factor in the admissions process, for any student, anywhere in the world.</p>
<p>In practice that means two things for a Pakistani applicant:</p>
<ul>
<li><strong>Asking for aid does not hurt your chances.</strong> Your application is read the same way whether you apply for aid or not.</li>
<li><strong>If you are admitted, Yale works out what your family can pay</strong> and covers the rest of the cost of attendance with a scholarship. There is no separate scholarship competition.</li>
</ul>

<h3>How much families pay from 2026-27</h3>
<p>On 27 January 2026 Yale announced new aid levels that took effect for new Yale College students entering in the 2026-2027 academic year:</p>
<div class="scholar-table"><table>
<thead><tr><th>Family income (typical assets)</th><th>What Yale says</th></tr></thead>
<tbody>
<tr><td>Below $100,000</td><td>The family pays nothing toward the cost of attendance, and the student gets a $2,000 start-up grant in the first year</td></tr>
<tr><td>Below $200,000</td><td>Scholarships meet or exceed the cost of tuition</td></tr>
<tr><td>Above $200,000</td><td>Aid is still based on demonstrated need</td></tr>
</tbody>
</table></div>
<p>Yale says its aid is given regardless of a student's citizenship or immigration status. The announcement does not set out separate income rules for international families, and assets matter as well as income, so treat the thresholds as a guide rather than a promise.</p>

<h3>How generous is it?</h3>
<ul>
<li>The average annual need-based scholarship is <strong>$75,220</strong>, and 55% of students receive need-based aid.</li>
<li>For students who started in fall 2026, Yale reported an average scholarship of over <strong>$78,742</strong>, and said more than a third of new students attend tuition free.</li>
</ul>
<p>That is why the brief's "up to $70,000 a year" is wrong: aid is not capped at a round figure. For a family that can pay nothing, the scholarship covers the full cost of attendance.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/yale-university-scholarship-students.jpg" alt="Yale University Scholarship 2026 in USA poster with students in Yale sweatshirts in front of Harkness Tower" width="1200" height="628" loading="lazy">
<figcaption>The posters say "Fully Funded Scholarships". At Yale College that means aid based on need, not an award for grades.</figcaption>
</figure>

<h2>What Yale Costs in 2026-27</h2>
<p>Yale's financial aid office lists these costs for a Yale College student in 2026-27:</p>
<div class="scholar-table"><table>
<thead><tr><th>Item</th><th>2026-27</th></tr></thead>
<tbody>
<tr><td>Tuition</td><td>$72,500</td></tr>
<tr><td>Student Activity Fee</td><td>$185</td></tr>
<tr><td>Housing</td><td>$12,080</td></tr>
<tr><td>Food</td><td>$9,520</td></tr>
<tr><td>Books and supplies</td><td>$1,000</td></tr>
<tr><td>Personal expenses</td><td>$2,700</td></tr>
<tr><td><strong>Listed items together</strong></td><td><strong>$97,985</strong>, plus travel</td></tr>
</tbody>
</table></div>
<p>Yale does not print a single total, because estimated travel expenses vary with the student's home address. The $98,085 total and $23,300 "living expenses" figures on other sites do not match Yale's own list: housing and food come to $21,600.</p>

<h2>Who Is Eligible</h2>
<h3>Undergraduates</h3>
<ul>
<li>Students of any nationality, including Pakistan, applying as first-year or transfer students to Yale College.</li>
<li>You must be admitted first. Aid is only offered to admitted students.</li>
<li>Your family must show financial need through the CSS Profile and supporting documents.</li>
</ul>
<h3>PhD students</h3>
<ul>
<li>Every student admitted to a PhD program at the Graduate School of Arts and Sciences receives the funding package, whatever their nationality.</li>
<li>You must meet the admission requirements of your department.</li>
</ul>

<h2>Testing and English Requirements</h2>
<p>Yale's testing page says all first-year and transfer applicants must include scores from the <strong>SAT or ACT</strong>. Scores from other exams, including international leaving exams or English proficiency exams, do not fulfill that requirement. AP and IB results are optional.</p>
<p>An English proficiency test is required if you are a non-native English speaker without two or more years of enrollment in an English-medium school. Yale accepts the TOEFL, IELTS, Cambridge English, the Duolingo English Test and InitialView. It sets no minimum, but lists typical scores for competitive applicants:</p>
<div class="scholar-table"><table>
<thead><tr><th>Test</th><th>Typical competitive score</th></tr></thead>
<tbody>
<tr><td>TOEFL iBT</td><td>5 or higher on the new scale (tests taken after 21 January 2026); at least 100 before</td></tr>
<tr><td>IELTS</td><td>7 or higher</td></tr>
<tr><td>Cambridge English</td><td>185 or higher</td></tr>
<tr><td>Duolingo English Test</td><td>At least 120</td></tr>
</tbody>
</table></div>
<p>PTE Academic is not on Yale's list.</p>

<h2>Deadlines for Fall 2027 Entry</h2>
<div class="scholar-table"><table>
<thead><tr><th>Round</th><th>Application due</th><th>Aid forms due (international)</th><th>Decision</th></tr></thead>
<tbody>
<tr><td>Single-Choice Early Action</td><td>1 November 2026</td><td>1 December 2026</td><td>Mid-December</td></tr>
<tr><td>Regular Decision</td><td>2 January 2027</td><td>15 February 2027</td><td>Late March</td></tr>
</tbody>
</table></div>
<p>US citizens and permanent residents applying early send aid forms by 1 November. Admitted students of both rounds reply by 1 May. Graduate program deadlines are set by each department; check the Graduate School's program pages.</p>

<h2>How to Apply</h2>
<ol>
<li><strong>Apply to Yale College</strong> through the Common App or the Coalition App on SCOIR. There is no separate scholarship application.</li>
<li><strong>Send your SAT or ACT scores</strong>, and an English test result if you need one.</li>
<li><strong>Complete the CSS Profile</strong> using Yale's code 3987.</li>
<li><strong>Upload tax and income documents</strong> through IDOC. Only US citizens and permanent residents also file the FAFSA.</li>
<li><strong>Ask about a fee waiver</strong> through your application platform. Yale does not issue its own waivers, but you can apply free if you meet the platform's criteria.</li>
</ol>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/yale-university-scholarship-campus.jpg" alt="Yale University Scholarship 2026 poster showing Harkness Tower and the Yale sign in New Haven, Connecticut" width="1200" height="628" loading="lazy">
<figcaption>Yale is in New Haven, Connecticut. PhD students there are funded by the Graduate School; most master's students pay their own tuition.</figcaption>
</figure>

<h2>PhD Funding at Yale</h2>
<p>The Graduate School of Arts and Sciences lists this package for 2026-27:</p>
<ul>
<li><strong>Tuition</strong> of $52,400 for full-time study, covered.</li>
<li>A <strong>12-month stipend of at least $52,046</strong> in the humanities, social sciences and physical sciences, and at least <strong>$53,629</strong> in the biological and biomedical sciences.</li>
<li>A <strong>one-time relocation award of $1,500</strong>, paid with the first stipend payment.</li>
<li>Yale Health Basic Coverage at no cost, and a Health Fellowship Award for hospitalization and specialty care.</li>
<li>A family support subsidy for eligible full-time PhD students with children.</li>
</ul>
<p>Funding is typically provided for a minimum of five years. Tuition is paid on your behalf, so the money you live on is the stipend, not the "$104,000 to $120,000" totals some sites quote.</p>

<h2>Master's Degrees: Usually Not Funded</h2>
<p>The Graduate School says most students pursuing master's degrees do not receive financial support from the Graduate School and are responsible for paying tuition, although some programs offer limited funding. Yale's professional schools run their own aid, so check the specific school before you count on help.</p>

<h2>For School Students: Yale Young Global Scholars</h2>
<p>Yale Young Global Scholars is a summer academic program for high school students, not a degree. It offers need-based aid equally to domestic and international students, as a tuition discount of up to 100%, and in 2026 it met 99% of aid requests in full.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does Yale give fully funded scholarships to international students?</h3>
<p>Yes, based on need. Yale admits international undergraduates without regard to ability to pay and meets 100% of demonstrated need. If your family can pay nothing, the scholarship covers the full cost of attendance.</p>

<h3>Does Yale offer merit scholarships?</h3>
<p>No. Yale's financial aid office says merit-based scholarships are not offered by Yale. All undergraduate scholarships are based on financial need.</p>

<h3>What family income gets free study at Yale?</h3>
<p>For students entering from 2026-27, families with typical assets and incomes below $100,000 pay nothing, and families below $200,000 get scholarships that meet or exceed tuition.</p>

<h3>How much does Yale cost for 2026-27?</h3>
<p>Tuition is $72,500. With the activity fee, housing, food, books and personal costs, the listed items come to $97,985, plus travel.</p>

<h3>What is the Yale application deadline for 2027?</h3>
<p>Single-Choice Early Action closes on 1 November 2026 and Regular Decision on 2 January 2027. International aid forms are due 1 December 2026 or 15 February 2027.</p>

<h3>Is IELTS required for Yale?</h3>
<p>An English test is required if English is not your first language and you have not spent two or more years in an English-medium school. Competitive applicants typically score IELTS 7 or higher, and Yale also accepts TOEFL, Cambridge English, Duolingo and InitialView.</p>

<h3>Do I need the SAT for Yale?</h3>
<p>Yes. All first-year and transfer applicants must send SAT or ACT scores. English tests and international leaving exams do not replace them.</p>

<h3>How much is the Yale PhD stipend?</h3>
<p>At least $52,046 for 12 months in 2026-27, or $53,629 in the biological and biomedical sciences, with tuition covered and a $1,500 relocation award.</p>

<h2>Related Scholarship Guides</h2>
<ul>
<li><a href="/scholarships/{yes}">Kennedy-Lugar YES Program Pakistan</a> &mdash; a US exchange year for Pakistani school students.</li>
<li><a href="/scholarships/{sydney}">University of Sydney RTP International Scholarship</a> &mdash; a funded PhD in Australia with tuition and stipend.</li>
<li><a href="/scholarships/{australia}">Australia scholarships without IELTS</a> &mdash; which English tests Australian awards accept.</li>
<li><a href="/scholarships/{france}">France scholarships without IELTS</a> &mdash; what Eiffel pays and who applies for you.</li>
<li><a href="/scholarships/{leeds}">University of Leeds Commonwealth Master's Scholarship</a> &mdash; a fully funded UK Master's for Commonwealth citizens.</li>
</ul>

<h2>Official Links</h2>
<ul>
<li><a href="{apply}" target="_blank" rel="noopener">Yale Admissions: applying as an international student</a></li>
<li><a href="https://admissions.yale.edu/financial-aid-international-applicants" target="_blank" rel="noopener">Yale Admissions: financial aid for international applicants</a></li>
<li><a href="https://finaid.yale.edu/coa" target="_blank" rel="noopener">Yale Financial Aid: cost of attendance</a></li>
<li><a href="https://admissions.yale.edu/standardized-testing" target="_blank" rel="noopener">Yale Admissions: standardized testing</a></li>
<li><a href="https://gsas.yale.edu/resources/graduate-financial-aid/phd-stipends" target="_blank" rel="noopener">Yale Graduate School: PhD stipends</a></li>
</ul>

<p><em>JobGader is not part of Yale University. This guide was checked against Yale Admissions, Yale Financial Aid, the Graduate School of Arts and Sciences and Yale News on 15 September 2026. Costs, aid levels and deadlines change each year, so confirm them on Yale's official pages before you apply.</em></p>
HTML, [
            '{apply}' => self::APPLY_URL,
            '{yes}' => YesProgramPakistanScholarshipSeeder::SLUG,
            '{sydney}' => SydneyRtpInternationalScholarshipSeeder::SLUG,
            '{australia}' => AustraliaScholarshipsWithoutIeltsScholarshipSeeder::SLUG,
            '{france}' => FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG,
            '{leeds}' => UniversityOfLeedsCommonwealthMastersScholarshipSeeder::SLUG,
        ]);
    }
}
