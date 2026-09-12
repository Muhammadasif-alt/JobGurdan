<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Scholarships, grants and tuition fees at the University of Insubria
 * (Varese, Como and Busto Arsizio), written up as a plain-English guide.
 *
 * Checked on 12 September 2026 against the university's press kit, the
 * 2026/27 student contribution regulation, the 2026/27 Ateneo scholarship
 * call, the 2026/27 economic benefits call (D.R. 1025/2026), Regione
 * Lombardia's right-to-study page, the CRUI IUPALS pages, the Erasmus and
 * double degree calls, and the official contacts page. Corrections to the
 * brief and the posters:
 *
 * 1. The brief lists Saronno as a campus. The university names three:
 *    Varese, Como and Busto Arsizio.
 * 2. The brief says 11,414 students. The press kit says more than 12,000.
 * 3. The brief says "top 600 world". The THE 2026 band is 601-800, and the
 *    university's own headline says top 800, 36th in Italy. There is no
 *    overall QS placement.
 * 4. The brief says a €156 registration fee made of €140 regional tax plus
 *    €16 stamp duty. The 2026/27 regulation sets €146 (€130 + €16) as the
 *    floor, and the regional tax rises to €160 or €190 in higher bands.
 * 5. The brief's €200-€3,960 tuition range could not be confirmed and is not
 *    published here. What is published is the flat rate by country of origin.
 * 6. The brief says the excellence scholarship pays €2,500 per semester. It
 *    pays four instalments of €2,500, gross, with about 18% withheld, and no
 *    call has been published since 2024/25.
 * 7. "CRUI IUPALS" is IUPALS, Italian Universities for Palestinian Students,
 *    open only to Palestinian students resident in the Palestinian
 *    Territories. The brief's ~€12,000 package is the national figure quoted
 *    by other universities; Insubria's own call carried two €3,000 awards.
 * 8. The brief's 400+ bilateral agreements is wrong: about 1,400 Erasmus
 *    places at more than 300 universities in 28 countries for 2026/27.
 * 9. The brief lists ServiceScape, IvyPanda and the ACES Education Fund as
 *    scholarships. They are essay-contest and copy-editing awards with no
 *    connection to Insubria or Italy, and are left out.
 * 10. The brief's IELTS 6.0/TOEFL 60 is not a university-wide rule, and the
 *    address and phone number it gives are not the international office's.
 *
 * The four posters are used as supplied. Their €10,000 total and "400+
 * Erasmus partnerships" lines are corrected in the captions and the text.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class InsubriaScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-insubria-scholarships';

    public const APPLY_URL = 'https://www.uninsubria.it/bandi-e-concorsi/bando-di-concorso-lassegnazione-dei-benefici-economici-aa-20262027';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Insubria Scholarships and Fees 2026–27',
                'provider' => 'University of Insubria (Università degli Studi dell\'Insubria)',
                'country' => 'Italy',
                'city' => 'Varese and Como',
                'study_level' => "Bachelor's, Master's, PhD",
                'funding_type' => 'Living Grants + Fee Waivers',
                'award_value' => '€500 to €7,171.11 a year',
                'deadline' => '2026-09-30',
                'deadline_note' => 'Ateneo scholarships close 5 Oct 2026, 12:00',
                'excerpt' => "The University of Insubria in Varese and Como awards 197 of its own scholarships worth €500 to €3,000, and Lombardy's regional grants pay up to €7,171.11 a year with free meals. The two calls close on 30 September and 5 October 2026.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/university-of-insubria-scholarships.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'University of Insubria Scholarships 2026: How to Apply',
                'meta_description' => 'University of Insubria scholarships 2026-27: 197 Ateneo grants close 5 October 2026, regional DSU grants close 30 September, plus tuition fee waivers.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-12 00:20:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>University of Insubria</strong> is a young Italian state university in the lakes region of Lombardy, with campuses in <strong>Varese, Como and Busto Arsizio</strong>. It does not pay a large research stipend the way Australian universities do. Instead it works the Italian way: <strong>tuition is set by your family's income</strong>, and separate calls hand out <strong>living grants, fee waivers, free meals and subsidised rooms</strong>. Put together, a student from a low-income family can study almost free and still receive up to <strong>&euro;7,171.11 a year</strong> to live on.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Two calls are open right now.</strong> The regional right-to-study benefits (the biggest money, up to &euro;7,171.11 plus free meals) close on <strong>30 September 2026 at 15:00 Italian time</strong>. The university's own Ateneo scholarships, 197 of them, close on <strong>5 October 2026 at 12:00</strong>. Both need you to be enrolling at Insubria for 2026/27.</p>
</div>

<h2>Insubria Scholarships at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>Universit&agrave; degli Studi dell'Insubria, founded 14 July 1998</td></tr>
<tr><th>Where</th><td>Varese, Como and Busto Arsizio, Lombardy, northern Italy</td></tr>
<tr><th>Size</th><td>More than 12,000 students, 8 departments, about 500 international students</td></tr>
<tr><th>Biggest award</th><td>Regional right-to-study grant: &euro;7,171.11 (living away), &euro;4,190.71 (commuter), &euro;2,890.16 (local), plus free meals</td></tr>
<tr><th>University's own awards</th><td>197 Ateneo scholarships for 2026/27: &euro;2,000, &euro;3,000, &euro;500 and two of &euro;1,000</td></tr>
<tr><th>For international students</th><td>Two-year excellence scholarship, &euro;5,000 a year gross, when a call is open</td></tr>
<tr><th>Tuition</th><td>Registration fee from &euro;146; nothing more to pay if your ISEE is &euro;22,000 or under</td></tr>
<tr><th>Study levels</th><td>25 bachelor's, 4 single-cycle master's, 16 master's (7 fully in English), 8 PhD programmes</td></tr>
<tr><th>Next deadlines</th><td>30 September 2026 (regional benefits), 5 October 2026 (Ateneo scholarships)</td></tr>
<tr><th>Apply through</th><td>The university's own <a href="{$applyUrl}" target="_blank" rel="noopener">bandi e concorsi</a> pages, after enrolling</td></tr>
</tbody>
</table></div>

<h2>The Three Ways Insubria Students Get Funded</h2>
<p>Italian universities split student money into three separate pots, each with its own form and closing date. Missing one does not stop you applying for the others, and most funded students hold more than one at a time.</p>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>Regional benefits (DSU)</th><th>Ateneo scholarships</th><th>Excellence scholarship</th></tr></thead>
<tbody>
<tr><td>Who pays</td><td>Regione Lombardia, run by the university</td><td>The University of Insubria</td><td>The University of Insubria</td></tr>
<tr><td>What you get</td><td>&euro;2,890.16 to &euro;7,171.11 a year, free meals, a subsidised room, tuition waived</td><td>&euro;500, &euro;2,000 or &euro;3,000 once</td><td>&euro;5,000 a year for two years, gross</td></tr>
<tr><td>Chosen on</td><td>Family income and assets, plus credits</td><td>Marks, plus income for some awards</td><td>Academic merit</td></tr>
<tr><td>How many</td><td>Everyone who qualifies and ranks high enough</td><td>197 for 2026/27</td><td>Up to 20 when a call runs</td></tr>
<tr><td>Closes</td><td>30 September 2026, 15:00</td><td>5 October 2026, 12:00</td><td>No call published since 2024/25</td></tr>
</tbody>
</table></div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-insubria-scholarships-lakes.jpg" alt="University of Insubria campus beside the lake with the tuition range and scholarship details" width="1200" height="628" loading="lazy">
<figcaption>Insubria's campuses sit in the Italian lakes region between Milan and the Swiss border. The poster's &euro;10,000 is the two-year total of the excellence scholarship, before tax, and only when a call is open.</figcaption>
</figure>

<h2>1. The Regional Right-to-Study Grant (DSU)</h2>
<p>This is where the real money is. Regione Lombardia sets the rules and the funding; there is no separate regional agency, so <strong>Insubria issues and runs its own call</strong> (D.R. 1025/2026, published 23 July 2026). It is open to Italian and international students alike, and it is decided on your family's finances, not your nationality.</p>

<h3>What it pays for 2026/27</h3>
<div class="scholar-table"><table>
<thead><tr><th>Your situation</th><th>National minimum</th><th>Lombardy average</th></tr></thead>
<tbody>
<tr><td><strong>Fuori sede</strong> &mdash; you rent near the university because home is too far</td><td>&euro;7,171.11</td><td>&euro;7,172</td></tr>
<tr><td><strong>Pendolare</strong> &mdash; you commute daily from another town</td><td>&euro;4,190.71</td><td>&euro;4,191</td></tr>
<tr><td><strong>In sede</strong> &mdash; you live in the university's own town</td><td>&euro;2,890.16</td><td>&euro;2,991</td></tr>
</tbody>
</table></div>
<ul>
<li>Part of the grant is paid as services rather than cash: <strong>free meals</strong>, and a subsidised room if you take one.</li>
<li><strong>Top-ups:</strong> 15% more for students in the most fragile situations, <strong>40% more for students with a disability</strong>, and <strong>20% more for women enrolled in STEM subjects</strong>.</li>
<li>Winners are <strong>exempt from tuition</strong> as well, so the grant and the fee waiver come together.</li>
</ul>

<h3>The income test</h3>
<p>You have to be under <strong>both</strong> limits, not one:</p>
<ul>
<li><strong>ISEE of &euro;26,887.93 or less</strong> (your family's means-tested income indicator), <em>and</em></li>
<li><strong>ISPE of &euro;58,452.06 or less</strong> (the assets side of the same calculation).</li>
</ul>
<p>Inside that, the ISEE brackets are <strong>up to &euro;13,443.97</strong>, <strong>&euro;13,443.98 to &euro;17,925.29</strong> and <strong>&euro;17,925.30 to &euro;26,887.93</strong>. A figure of &euro;28,339.88 circulates in the Italian press; it is <strong>not</strong> the Lombardy ceiling, so do not plan around it.</p>

<h3>Dates you cannot miss</h3>
<ul>
<li><strong>Grant and catering:</strong> opened 24 July 2026 at 12:00, closes <strong>30 September 2026 at 15:00</strong>.</li>
<li><strong>Subsidised accommodation:</strong> closed earlier, on <strong>24 August 2026 at 15:00</strong>.</li>
<li><strong>Your ISEE declaration (DSU)</strong> must be signed by <strong>16 October 2026</strong>, or by 24 August 2026 if you applied for housing.</li>
</ul>

<div class="scholar-note">
<p><strong>No Italian ISEE?</strong> Students whose family lives abroad use the <strong>equivalent ISEE (ISEE parificato)</strong>, worked out from the family's foreign income and assets, with documents translated and legalised. Start it early &mdash; a CAF office needs time, and the deadline does not move.</p>
</div>

<h2>2. The University's Own Ateneo Scholarships</h2>
<p>Insubria runs a separate call of its own each year for students enrolled on its degree courses. For <strong>2026/27 there are 197 scholarships</strong>, and applications opened on <strong>3 August 2026</strong> and close on <strong>5 October 2026 at 12:00</strong>.</p>
<div class="scholar-table"><table>
<thead><tr><th>Award</th><th>How many</th><th>Who it is for</th></tr></thead>
<tbody>
<tr><td>&euro;2,000</td><td>60</td><td>First-year students living in the university's town (in sede)</td></tr>
<tr><td>&euro;3,000</td><td>15</td><td>First-year students living away from home (fuori sede)</td></tr>
<tr><td>&euro;500</td><td>120</td><td>Students in later years of their degree</td></tr>
<tr><td>&euro;1,000</td><td>1</td><td>The TEDx Varese scholarship</td></tr>
<tr><td>&euro;1,000</td><td>1</td><td>The CUSMIBIO scholarship</td></tr>
</tbody>
</table></div>
<p>First-year applicants need a <strong>school-leaving mark of 98/100 or better</strong>. That is a high bar, and it is the single reason most first-year applications fail, so check your converted mark before you spend time on the form.</p>

<h2>3. The Two-Year International Excellence Scholarship</h2>
<p>This is the award the posters advertise, and it is worth understanding exactly, because most of what circulates about it online is wrong.</p>
<ul>
<li>Its official title is the <strong>"Call for two-year scholarships supporting excellent International Students enrolling in a Bachelor or a Master Degree programme"</strong>. The university's English pages also call it the <strong>International Excellence Scholarship</strong>.</li>
<li><strong>Up to 20</strong> are offered, worth <strong>&euro;5,000 a year for two years</strong>.</li>
<li>The money is <strong>gross</strong>. About <strong>18% is withheld</strong> in tax and duties, so the cash that reaches you is closer to &euro;4,100 a year.</li>
<li>It is paid in <strong>four instalments of &euro;2,500</strong> across the two years &mdash; not &euro;2,500 every semester.</li>
<li><strong>To keep it you must pass at least 40 ECTS credits</strong> by the end of the summer exam session, 30 September, each year.</li>
<li>It covers <strong>two years only</strong>. A three-year bachelor's is not funded in its final year, and the university tells holders to apply for the regional grant instead.</li>
<li><strong>Travel, health insurance and accommodation are not included.</strong></li>
</ul>

<div class="scholar-note scholar-note-warn">
<p><strong>Important:</strong> the last call for this scholarship that can be traced was for <strong>2024/25</strong>, with a deadline of 31 May at 12:00 Italian time. <strong>No call has been published for 2025/26, 2026/27 or 2027/28.</strong> Treat any article promising a "31 May deadline" as out of date, and watch the university's own scholarship page before you build a plan around it.</p>
</div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-insubria-scholarships-students.jpg" alt="International students on the University of Insubria campus with the 20 scholarships of 5,000 euro a year" width="1200" height="628" loading="lazy">
<figcaption>The 20 excellence scholarships pay &euro;5,000 a year before tax, in four instalments, and need 40 ECTS a year to renew.</figcaption>
</figure>

<h2>IUPALS: What It Really Is</h2>
<p>Summaries of Insubria funding often list a "CRUI IUPALS" scholarship worth about &euro;12,000 a year with free housing, meals and an Italian course. That description mixes up two different things.</p>
<ul>
<li><strong>IUPALS stands for Italian Universities for Palestinian Students</strong>, a scheme coordinated by CRUI, the conference of Italian university rectors, with the Italian foreign and university ministries.</li>
<li>It is open <strong>only to Palestinian students resident in the Palestinian Territories</strong>, and students already enrolled at an Italian university are excluded. It is not a general international scholarship.</li>
<li>The 2025/26 edition offered about <strong>97 scholarships across 35 universities</strong>. The roughly &euro;12,000 package, with tuition exemption, accommodation, canteen, insurance and airfare, is the figure quoted by large universities such as Padova &mdash; not by Insubria.</li>
<li><strong>Insubria does take part</strong> and hosts Palestinian students from Gaza. Its own scholarship call carried <strong>two awards of &euro;3,000</strong> reserved for IUPALS students in 2025/26.</li>
<li>The 2025 round has <strong>closed</strong>, and no 2026/27 edition is expected.</li>
</ul>
<p>Two other schemes are worth ruling out while you plan: Insubria is <strong>not</strong> one of the universities in the UNHCR's UNICORE corridors, and it is <strong>not</strong> in the Italian government's Invest Your Talent in Italy programme.</p>

<h2>What You Actually Pay in Tuition</h2>

<h3>The fixed part</h3>
<p>Every student pays a registration fee whatever their income. For 2026/27 the regulation sets it at <strong>&euro;146.00</strong>: a regional right-to-study tax starting at <strong>&euro;130.00</strong> plus <strong>&euro;16.00</strong> of stamp duty. The regional tax is then set in three bands by your ISEE:</p>
<div class="scholar-table"><table>
<thead><tr><th>Band</th><th>Regional tax</th><th>With stamp duty</th></tr></thead>
<tbody>
<tr><td>Band 1 &mdash; lowest incomes, and grant holders</td><td>&euro;130.00</td><td>&euro;146.00</td></tr>
<tr><td>Band 2</td><td>&euro;160.00</td><td>&euro;176.00</td></tr>
<tr><td>Band 3 &mdash; highest incomes, and anyone who files no ISEE</td><td>&euro;190.00</td><td>&euro;206.00</td></tr>
</tbody>
</table></div>
<p>Older pages still quote &euro;156 (&euro;140 plus &euro;16). That was an earlier year's figure. And note the sting in band 3: <strong>if you do not file an ISEE at all, you are treated as the highest earner</strong>, on the tax and on the tuition.</p>

<h3>The income-based part</h3>
<ul>
<li><strong>ISEE of &euro;22,000 or less: no tuition contribution at all.</strong> You pay the registration fee and nothing else.</li>
<li>Above that, the contribution rises with your ISEE up to the maximum in the regulation's annexed tables.</li>
<li>Figures of "&euro;200 to &euro;3,960 a year" circulate widely. That range could not be confirmed in the 2026/27 regulation, so check the tables on the official fees page rather than trusting it.</li>
</ul>

<h3>If you live abroad: the flat rate</h3>
<p>International students whose family lives outside Italy are not assessed on an Italian ISEE by default. The regulation sets a <strong>flat-rate contribution based on your country of origin</strong>, with countries sorted into <strong>three groups</strong> in annexed tables, then adjusted by a <strong>corrective coefficient K</strong> for the subject area of your degree. Students registered with AIRE, the register of Italians abroad, are placed by their country of residence. If your family's real income is low, filing the equivalent ISEE instead will usually beat the flat rate &mdash; it is worth doing the sum both ways.</p>

<h3>Discounts worth claiming</h3>
<ul>
<li><strong>A reduction equal to your CIMEA costs</strong> &mdash; the &euro;150 comparability statement and the &euro;65 verification statement that international applicants pay to have a foreign qualification recognised.</li>
<li><strong>Merit exemptions</strong> for students who are on track and have earned enough credits by 30 November 2026.</li>
<li><strong>A full waiver</strong> if you win the regional grant.</li>
</ul>

<h3>When the money is due</h3>
<ul>
<li><strong>Re-enrolment:</strong> 1 July to 30 September 2026.</li>
<li><strong>Exemption applications:</strong> 16 October to 21 December 2026.</li>
<li><strong>Deposit:</strong> 21 December 2026. <strong>Balance:</strong> 31 May 2027, and the balance itself can be split again.</li>
</ul>

<h2>Studying at Insubria in English</h2>
<p>Be clear about this before you apply: <strong>the bachelor's degrees are taught in Italian</strong>. English-taught study at Insubria means a master's. Of the 16 master's programmes, <strong>7 are taught entirely in English</strong>, including:</p>
<ul>
<li><strong>Biomedical Sciences</strong> (Busto Arsizio), with a double degree with Bonn-Rhein-Sieg</li>
<li><strong>Biotechnology for the Bio-based and Health Industry</strong></li>
<li><strong>Computer Science</strong></li>
<li><strong>Physics</strong></li>
<li><strong>Global Entrepreneurship Economics and Management (GEEM)</strong></li>
</ul>
<p>For Italian-taught bachelor's and single-cycle master's degrees you must pass an <strong>Italian language test at B2 level</strong>, unless you hold a recognised exemption such as a CLIQ-system certificate at B2 or above, or an Italian-taught qualification. For English-taught master's degrees, requirements are set programme by programme &mdash; there is <strong>no university-wide IELTS or TOEFL score</strong>, so ignore any article that quotes one.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-insubria-scholarships-campus.jpg" alt="University of Insubria sign and campus buildings with the scholarship and tuition information" width="1200" height="628" loading="lazy">
<figcaption>Insubria teaches 7 of its 16 master's programmes entirely in English; the bachelor's degrees are taught in Italian.</figcaption>
</figure>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Pick a programme</strong> from the course catalogue and read its own entry and language requirements. You need at least <strong>12 years of schooling</strong> to enrol.</li>
<li><strong>Get your qualification recognised.</strong> Order the CIMEA comparability statement (&euro;150) and verification statement (&euro;65) early; the fee comes back later as a tuition reduction.</li>
<li><strong>Pre-enrol on Universitaly</strong> if you are a non-EU applicant who needs a study visa. For 2026/27 the pre-application deadline was <strong>30 June 2026</strong>.</li>
<li><strong>Enrol in the official window.</strong> For 2026/27: bachelor's <strong>10 July to 30 September 2026</strong> without a surcharge, or 1 October to 30 November 2026 with a late fee that cannot be extended; master's <strong>1 July to 30 October 2026</strong>. Documents and the visa must be in by <strong>1 February 2027</strong>.</li>
<li><strong>File your ISEE or equivalent ISEE</strong> straight away. Almost every discount and grant depends on it, and band 3 is the default if you skip it.</li>
<li><strong>Apply for the regional benefits</strong> through the university's benefits call by <strong>30 September 2026, 15:00</strong>.</li>
<li><strong>Apply for the Ateneo scholarships</strong> by <strong>5 October 2026, 12:00</strong>, if your marks qualify.</li>
<li><strong>Watch for an excellence scholarship call</strong> on the university's English scholarship page if you want the &euro;5,000-a-year award.</li>
</ol>

<h2>Living in Varese, Como and Busto Arsizio</h2>
<ul>
<li><strong>University rooms.</strong> Insubria runs <strong>Collegio Carlo Cattaneo</strong> in Varese, with single rooms, private bathrooms and a kitchen shared between four, and <strong>Collegio Santa Teresa</strong> in Como, with 36 beds in double rooms.</li>
<li><strong>Recent rates</strong> ran from about &euro;250 a month for a double and &euro;270 for a single for grant holders, to &euro;300 and &euro;320 at the full rate, with a &euro;400 deposit. Check the current tariff sheet before you budget.</li>
<li><strong>Three ways in:</strong> a subsidised place through the benefits call, a call reserved for excellent students, and a full-rate call. There is also a "Cerco Alloggio" service for private rooms.</li>
<li><strong>Meals.</strong> Grant holders eat free: lunch Monday to Friday for local and commuting students, and every day plus an evening meal for students living away from home.</li>
<li><strong>Getting around.</strong> Varese is about 55 km from Milan and Como sits close to the Swiss border, both on direct train lines.</li>
</ul>

<h2>Erasmus and Double Degrees</h2>
<ul>
<li><strong>Erasmus+ study:</strong> about <strong>1,400 places at more than 300 partner universities in 28 countries</strong> for 2026/27. The widely repeated "400+ agreements" figure understates it.</li>
<li><strong>Grants</strong> run <strong>&euro;500 to &euro;650 a month</strong>, with a <strong>&euro;250 a month top-up</strong> either for students in defined categories (low ISEE, disability, working students, parents, orphans) or for travelling sustainably.</li>
<li><strong>Double degrees:</strong> <strong>8 programmes</strong> &mdash; one single-cycle and seven master's &mdash; with 48 places at 11 universities in 7 countries for 2026/27. You graduate with a degree from both universities.</li>
</ul>

<h2>Insubria Compared</h2>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>Insubria</th><th>Pavia</th><th>Australian RTP</th></tr></thead>
<tbody>
<tr><td>Founded</td><td>1998</td><td>1361</td><td>&mdash;</td></tr>
<tr><td>Tuition if your income is low</td><td>Registration fee only, from &euro;146</td><td>Registration fee only, from &euro;156</td><td>Covered by the scholarship</td></tr>
<tr><td>Living money</td><td>Up to &euro;7,171.11 a year, means-tested</td><td>Regional EDiSU grant, means-tested</td><td>AUD \$37,145 to \$42,754 a year, merit-based</td></tr>
<tr><td>Levels funded</td><td>Bachelor's, master's, PhD</td><td>Bachelor's, master's, PhD</td><td>PhD and research master's only</td></tr>
<tr><td>Taught in English</td><td>7 master's programmes</td><td>30 programmes</td><td>Everything</td></tr>
</tbody>
</table></div>
<p>The honest comparison is this: an Australian research scholarship pays you a salary but only for a PhD, and only if you beat a national field. Italy asks you to fund yourself, then removes tuition and adds a living grant if your family's income is genuinely low. Read our <a href="/scholarships/university-of-pavia-scholarships">University of Pavia scholarships guide</a> for the older Italian option nearby, our <a href="/scholarships/polytechnic-university-of-marche-scholarships">Polytechnic University of Marche scholarships guide</a> for the Adriatic coast alternative, and our <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP</a>, <a href="/scholarships/university-of-melbourne-rtp-scholarship">University of Melbourne RTP</a> and <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a> guides for the Australian route.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much are University of Insubria scholarships worth?</h3>
<p>The regional right-to-study grant is the largest: &euro;7,171.11 a year if you live away from home, &euro;4,190.71 if you commute and &euro;2,890.16 if you live locally, plus free meals and tuition exemption. The university's own Ateneo scholarships are one-off awards of &euro;500, &euro;2,000 or &euro;3,000, and the international excellence scholarship pays &euro;5,000 a year gross for two years.</p>

<h3>When do Insubria scholarships close for 2026/27?</h3>
<p>The regional benefits call closes on 30 September 2026 at 15:00 Italian time, and the Ateneo scholarship call closes on 5 October 2026 at 12:00. The subsidised accommodation deadline has already passed, on 24 August 2026.</p>

<h3>Is the 20-scholarship international award still running?</h3>
<p>No call has been published since 2024/25. The last edition offered up to 20 scholarships of &euro;5,000 a year with a 31 May deadline, but there is no 2025/26, 2026/27 or 2027/28 call, so do not plan around that date.</p>

<h3>How much is tuition at the University of Insubria?</h3>
<p>If your ISEE is &euro;22,000 or less you pay no tuition contribution at all, only the registration fee, which starts at &euro;146 for 2026/27 (&euro;130 regional tax plus &euro;16 stamp duty). Above that, the contribution rises with income, and international students living abroad can instead be charged a flat rate set by country of origin and adjusted by subject area.</p>

<h3>What happens if I do not file an ISEE?</h3>
<p>You are placed in the top band automatically: &euro;190 of regional tax instead of &euro;130, and the maximum tuition contribution. Filing the equivalent ISEE, worked out from your family's foreign income, is almost always worth the paperwork.</p>

<h3>Can I study at Insubria in English?</h3>
<p>At master's level, yes: 7 of the 16 master's programmes are taught entirely in English, including Computer Science, Physics, Biomedical Sciences and Global Entrepreneurship Economics and Management. The bachelor's degrees are taught in Italian and need a B2 Italian test.</p>

<h3>Where is the University of Insubria?</h3>
<p>In Lombardy, northern Italy, with campuses in Varese, Como and Busto Arsizio. Varese is about 55 km from Milan and Como sits near the Swiss border. Saronno, which some guides list, is not a current campus.</p>

<h3>What is IUPALS and can I apply?</h3>
<p>IUPALS is Italian Universities for Palestinian Students, run by CRUI with the Italian ministries. It is open only to Palestinian students resident in the Palestinian Territories. Insubria takes part and reserved two &euro;3,000 awards for IUPALS students in 2025/26, but the 2025 round has closed and no new edition is expected.</p>

<h3>Who do I contact at Insubria?</h3>
<p>The International Cooperation office at relint@uninsubria.it, or erasmusincoming@uninsubria.it for Erasmus. The International Relations Office is at Padiglione Rossi, via Ottorino Rossi 9, 21100 Varese, with a second office at via Valleggio 11, 22100 Como. The university's legal seat is via Ravasi 2, Varese.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">2026/27 economic benefits call (regional grant, meals, housing)</a></li>
<li><a href="https://www.uninsubria.it/bandi-e-concorsi/bando-di-concorso-borse-di-studio-di-ateneo-studenti-iscritti-ad-un-corso-di" target="_blank" rel="noopener">2026/27 Ateneo scholarship call</a></li>
<li><a href="https://www.uninsubria.it/formazione/opportunita-e-agevolazioni/diritto-allo-studio/tasse-e-contributi" target="_blank" rel="noopener">Tuition fees and instalment dates</a></li>
<li><a href="https://www.uninsubria.it/formazione/opportunita-e-agevolazioni/diritto-allo-studio/esonero-tasse-e-contributi" target="_blank" rel="noopener">Fee exemptions</a></li>
<li><a href="https://www.uninsubria.eu/course-catalogue/course-list/degree-programs" target="_blank" rel="noopener">Course catalogue</a></li>
<li><a href="https://www.uninsubria.eu/services/uninsubria-living/pre-enrollment-non-ue-students" target="_blank" rel="noopener">Pre-enrolment for non-EU students</a></li>
<li><a href="https://www.uninsubria.eu/our-contacts" target="_blank" rel="noopener">Official contacts</a></li>
<li><a href="https://www.regione.lombardia.it/istruzione-formazione-e-lavoro/universita-e-formazione-accademica/borse-di-studio-universitarie-2026-2027" target="_blank" rel="noopener">Regione Lombardia, university grants 2026/27</a></li>
</ul>

<p><em>JobGader is not part of the University of Insubria. This guide was checked against the university's official pages, its 2026/27 calls and Regione Lombardia on 12 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
