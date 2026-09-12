<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Scholarships, grants and tuition fees at the Polytechnic University of
 * Marche (UNIVPM) in Ancona, written up as a plain-English guide.
 *
 * Checked on 12 September 2026 against UNIVPM's own fee and scholarship
 * pages, its international admissions portal, the ERDIS Marche 2026/27
 * bando, the UNIVPM PhD and part-time work calls, the MUR circular on
 * international students and the Italian consulates' visa pages.
 * Corrections to the brief and the posters:
 *
 * 1. The &euro;156 a year on the posters is right, which is unusual: UNIVPM
 *    publishes a flat &euro;140 regional tax plus &euro;16 stamp duty with no
 *    ISEE banding, unlike Pavia and Insubria.
 * 2. The brief's "~$2,649 USD/year minimum for Italian residents" matches
 *    nothing UNIVPM publishes and is left out.
 * 3. The posters say ERDIS Marche covers "Tuition + Accommodation + Meals".
 *    A bed and board are taken out of the grant's value, not added to it:
 *    &euro;2,821 for a room and &euro;2,011 for meals come off the cash.
 * 4. Marche's income ceilings are stricter than the national ones every
 *    other Italian guide quotes: ISEE &euro;24,000 and ISPE &euro;50,000,
 *    not &euro;26,887.93 and &euro;58,452.06.
 * 5. The ERDIS deadline for 2026/27 was 28 August 2026 and has passed.
 * 6. The brief lists Fulbright, K.C. Mahindra, the J.N. Tata Endowment,
 *    Robert S. McNamara Fellowships and a "Dr. Martin Blank - SYB"
 *    scholarship as UNIVPM aid. None is run, funded or awarded by UNIVPM.
 *    The university's own award is 120 scholarships of &euro;2,000.
 * 7. "69+ scholarship listings tracked for international students" has no
 *    official source and is left out.
 * 8. The brief has no Universitaly pre-enrolment step, which is mandatory
 *    for every non-EU applicant who needs a visa.
 * 9. The brief omits the visa money proof, which rose to &euro;10,179.85 for
 *    2026/27, about 46% above the previous year.
 * 10. The brief says the post-study permit runs "6 months-1 year". The law
 *    sets it at not less than one year, and it can be renewed.
 * 11. The brief's &euro;25-&euro;40 a month for transport overstates it: the
 *    UNIVPM-Conerobus student pass works out at about &euro;16-&euro;18.
 * 12. Non-EU students from developing countries pay the &euro;156 and nothing
 *    more, which the brief never mentions and which matters most of all.
 * 13. "Founded 1969" is half the story. 1969 was the private Libera
 *    Universit&agrave; di Ancona; it became a state university on 18 January
 *    1971 and took its present name on 18 January 2003.
 * 14. "Top-730 world (QS)" matches no published edition. QS 2026 puts
 *    UNIVPM at 801-850 and THE 2026 at 401-500.
 * 15. The "~90% placement rate" is sound but needs its source: AlmaLaurea
 *    2026 found 90.6% of master's graduates in work a year after
 *    graduating, against a national 80.8%.
 * 16. The brief's address, Via Oberdan 8, 60122 Ancona, has the wrong
 *    postcode and is the admissions office, not the university's seat,
 *    which is Piazza Roma 22, 60121 Ancona.
 * 17. Of the partnerships listed, CRUI, APRE, UNIADRION and CUIA are
 *    confirmed; EUA, EUCEN and CUM could not be and are dropped.
 *
 * The maximum annual contribution is deliberately not quoted: UNIVPM
 * publishes it only inside a scanned PDF that could not be read, so no
 * figure is given rather than a guessed one.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class PolytechnicMarcheScholarshipSeeder extends Seeder
{
    public const SLUG = 'polytechnic-university-of-marche-scholarships';

    public const APPLY_URL = 'https://www.univpm.it/Entra/Engine/RAServePG.php/P/2729910010400';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Polytechnic University of Marche Scholarships 2026–27',
                'provider' => 'Polytechnic University of Marche (Università Politecnica delle Marche)',
                'country' => 'Italy',
                'city' => 'Ancona',
                'study_level' => "Bachelor's, Master's, PhD",
                'funding_type' => 'Fee Waivers + Living Grants',
                'award_value' => '€2,000 awards, grants to €7,171.11',
                'deadline' => '2026-11-17',
                'deadline_note' => 'ERDIS Marche regional grants closed 28 Aug 2026',
                'excerpt' => 'The Polytechnic University of Marche in Ancona charges a flat €156 a year, waives tuition completely below an ISEE of €28,000, and is giving out 120 scholarships of €2,000 that close on 17 November 2026.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/polytechnic-university-of-marche-scholarships.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'UNIVPM Scholarships 2026: Fees, Grants and Deadlines',
                'meta_description' => 'UNIVPM scholarships 2026-27: 120 awards of EUR 2,000 close 17 November, fees start at EUR 156, and ERDIS Marche grants pay up to EUR 7,171.11 a year.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-12 00:30:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>Polytechnic University of Marche</strong>, UNIVPM, is a state university on Italy's Adriatic coast at <strong>Ancona</strong>. It is one of the cheapest serious universities in Europe, and the reason is worth stating plainly: the fixed cost of enrolling is <strong>&euro;156 a year</strong>, and if your family's assessed income is <strong>&euro;28,000 or less</strong> there is nothing further to pay. On top of that sit two separate pots of scholarship money &mdash; the university's own, and the Marche region's.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>What is open and what has closed.</strong> The university's own scholarships &mdash; <strong>120 awards of &euro;2,000</strong> &mdash; are open and close on <strong>17 November</strong>. The big regional grant from ERDIS Marche, worth up to <strong>&euro;7,171.11</strong>, <strong>closed on 28 August 2026</strong>; the next round opens around July 2027. If you are applying for medicine, the <strong>IMAT sat on 29 September 2026</strong> and its registration closed on 9 September.</p>
</div>

<h2>UNIVPM Scholarships at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>Universit&agrave; Politecnica delle Marche (UNIVPM)</td></tr>
<tr><th>Where</th><td>Ancona, Marche region, on the Adriatic coast of central Italy</td></tr>
<tr><th>Founded</th><td>1969 as a private university, a state university from 1971, renamed in 2003</td></tr>
<tr><th>Ranking</th><td>QS 2026: 801 to 850. THE 2026: 401 to 500. Medical and health subjects 301 to 400</td></tr>
<tr><th>Graduate jobs</th><td>90.6% of master's graduates in work a year on, against 80.8% nationally</td></tr>
<tr><th>Fixed cost</th><td>&euro;156 a year: &euro;140 regional tax plus &euro;16 stamp duty, the same for everyone</td></tr>
<tr><th>No-tax area</th><td>ISEE of &euro;28,000 or less means no tuition contribution at all</td></tr>
<tr><th>University's own awards</th><td>120 scholarships of &euro;2,000, closing 17 November</td></tr>
<tr><th>Regional grant</th><td>ERDIS Marche: &euro;7,171.11 (living away), &euro;4,190.71 (commuter), &euro;2,890.16 (local)</td></tr>
<tr><th>PhD</th><td>Salaried: &euro;16,243 a year gross, plus 50% more for approved periods abroad</td></tr>
<tr><th>Study levels</th><td>Bachelor's, master's, single-cycle medicine, PhD</td></tr>
<tr><th>Next deadlines</th><td>17 November (UNIVPM scholarships); ERDIS reopens around July 2027</td></tr>
<tr><th>Apply through</th><td>The university's own <a href="{$applyUrl}" target="_blank" rel="noopener">scholarships page</a>, after you have a place</td></tr>
</tbody>
</table></div>

<h2>Is UNIVPM Actually Any Good?</h2>
<p>Worth answering plainly, because two of the poster's claims need adjusting. UNIVPM is a solid mid-table European research university rather than a world top-500 one, and its real strength is what happens to its graduates afterwards.</p>
<ul>
<li><strong>Rankings:</strong> QS places UNIVPM in the <strong>801 to 850</strong> band for 2026, and Times Higher Education at <strong>401 to 500</strong>. The "top 730" figure in circulation matches no published edition.</li>
<li><strong>By subject it does better.</strong> THE 2026 ranks its medical and health subjects <strong>301 to 400</strong>, and business, economics and life sciences <strong>401 to 500</strong>. ShanghaiRanking's subject table puts <strong>food science and technology at 76 to 100</strong> worldwide, its strongest result anywhere.</li>
<li><strong>Employment is the real story.</strong> AlmaLaurea's 2026 report found <strong>90.6% of UNIVPM master's graduates in work a year after finishing</strong>, against a national average of 80.8%, on about <strong>&euro;1,621 net a month</strong>. Five years out it is <strong>96.9%</strong>. That is where the poster's "90% placement" comes from, and it holds up.</li>
<li><strong>Shape:</strong> five faculties &mdash; agriculture, economics, engineering, medicine and surgery, and sciences &mdash; spread across <strong>12 departments</strong>.</li>
</ul>

<h3>How old is it really?</h3>
<p>The posters say "EST. 1969", which is half right. In <strong>1969</strong> the city, province and chamber of commerce founded the <strong>Libera Universit&agrave; di Ancona</strong>, a private institution, with faculties of engineering and of medicine and surgery. It was recognised as a <strong>state university on 18 January 1971</strong> under the name Universit&agrave; degli Studi di Ancona, added agriculture in 1988 and sciences in 1991, and became the <strong>Universit&agrave; Politecnica delle Marche on 18 January 2003</strong>. Ancona did have a papal <em>studium generale</em> from 1562, but it closed in 1739 and today's university does not descend from it.</p>

<h3>Studying in English</h3>
<p>More is taught in English here than at most Italian universities this size. The university's own course list names ten fully English-taught degrees, although its homepage still says seven, so check the current catalogue for your subject:</p>
<ul>
<li><strong>Bachelor's:</strong> Digital Economics and Business; Environmental Sciences and Civil Protection.</li>
<li><strong>Master's:</strong> Biomedical Engineering; Environmental Engineering; Green Industrial Engineering; International Economics and Commerce; Food and Beverage Innovation and Management; Marine Biology; Environmental Hazard and Disaster Risk Management.</li>
<li><strong>Single-cycle:</strong> Medicine and Surgery (Med-Tech), six years, entered through the IMAT.</li>
</ul>
<p>UNIVPM belongs to the <strong>SUNRISE</strong> European Universities alliance and to CRUI, APRE, UNIADRION and CUIA. EUA, EUCEN and CUM appear in some summaries of the university but could not be confirmed on its own lists.</p>

<h2>The Three Ways UNIVPM Students Get Funded</h2>
<p>Italian student money comes in separate pots with separate forms and separate closing dates. Applying for one does not enter you for the others, and most funded students hold more than one.</p>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>UNIVPM scholarships</th><th>ERDIS Marche grant</th><th>Part-time work</th></tr></thead>
<tbody>
<tr><td>Who pays</td><td>The university</td><td>The Marche region, through ERDIS</td><td>The university</td></tr>
<tr><td>What you get</td><td>&euro;2,000 once, renewable for STEM women</td><td>&euro;2,890.16 to &euro;7,171.11 a year, plus a room and meals</td><td>Up to &euro;1,200 a year, tax-free</td></tr>
<tr><td>Chosen on</td><td>Your entry marks, with an ISEE on file</td><td>Family income and assets, then credits</td><td>Credits earned, from your second year</td></tr>
<tr><td>How many</td><td>120 for 2026/27</td><td>Everyone who qualifies and ranks high enough</td><td>Set each year by call</td></tr>
<tr><td>Closes</td><td>17 November</td><td>Closed 28 August 2026</td><td>Usually January</td></tr>
</tbody>
</table></div>

<h2>1. The University's Own 120 Scholarships</h2>
<p>This is the call that is actually open, and it is the one most guides to UNIVPM never mention. UNIVPM offers <strong>120 scholarships of &euro;2,000 each</strong> to students enrolling for the first time in 2026/27, split three ways:</p>
<div class="scholar-table"><table>
<thead><tr><th>Award</th><th>How many</th><th>Who it is for</th></tr></thead>
<tbody>
<tr><td>&euro;2,000</td><td>40</td><td>First-year bachelor's students on open-access programmes</td></tr>
<tr><td>&euro;2,000</td><td>40</td><td>First-year master's students</td></tr>
<tr><td>&euro;2,000</td><td>40</td><td><strong>Women starting a STEM bachelor's degree</strong>, renewable for two further years</td></tr>
</tbody>
</table></div>
<ul>
<li><strong>Marks needed:</strong> a school-leaving mark of <strong>80/100 up to 100/100 with honours</strong> for the bachelor's and STEM awards, or a bachelor's degree classed <strong>90/110 up to 110/110 with honours</strong> for the master's award.</li>
<li><strong>You need a valid ISEE on file</strong>, so start that paperwork before you start the form.</li>
<li><strong>The window runs 15 July to 17 November.</strong> Unlike the regional grant, this one is still open as this guide is published.</li>
</ul>
<p>There is a second, much larger university award that is easy to miss: the <strong>MA.R.I. programme</strong> attaches <strong>30 scholarships of &euro;8,000 a year</strong> to the degree in Management for the Sustainable Development of Aquatic Resources. If marine and fisheries science is your field, that single programme is worth more than everything else on this page.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/polytechnic-university-of-marche-scholarships-students.jpg" alt="Students at the Polytechnic University of Marche in Ancona with the fees from 156 euro a year and ERDIS Marche scholarships" width="1200" height="628" loading="lazy">
<figcaption>The &euro;156 on the poster is real and is the whole fixed cost. What it does not show is that the ERDIS grant beside it closed on 28 August 2026.</figcaption>
</figure>

<h2>2. The ERDIS Marche Regional Grant</h2>
<p>ERDIS is the Marche region's right-to-study agency, and its grant is where the real money is. It is open to Italian and international students alike and is decided on your family's finances, not your nationality.</p>

<h3>What it pays for 2026/27</h3>
<div class="scholar-table"><table>
<thead><tr><th>Your situation</th><th>Full annual amount</th></tr></thead>
<tbody>
<tr><td><strong>Fuori sede</strong> &mdash; you rent near the university because home is too far</td><td>&euro;7,171.11</td></tr>
<tr><td><strong>Pendolare</strong> &mdash; you commute daily from another town</td><td>&euro;4,190.71</td></tr>
<tr><td><strong>In sede</strong> &mdash; you live in Ancona already</td><td>&euro;2,890.16</td></tr>
</tbody>
</table></div>
<p>PhD students are paid at the <strong>fuori sede</strong> rate whatever their address. The amount then moves with your ISEE: below <strong>&euro;12,000</strong> it is <strong>increased by 15%</strong>, taking the fuori sede figure to <strong>&euro;8,246.78</strong>; from &euro;16,000 upwards it is cut in steps of 12.5% until, in the top band, it is <strong>halved</strong>.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Read this before you budget.</strong> Accommodation and meals are <strong>not added on top</strong> of the grant, which is how the posters read. They are taken out of it. A fuori sede winner living in an ERDIS hall has <strong>&euro;2,821 deducted for the room</strong> and <strong>&euro;2,011 for two meals a day</strong>, leaving about <strong>&euro;2,339 in cash</strong> plus board and lodging. That is still an excellent deal &mdash; it is simply not &euro;7,171 in your pocket <em>and</em> a free room.</p>
</div>

<h3>The income test, and why Marche is stricter</h3>
<p>You must be under <strong>both</strong> ceilings:</p>
<ul>
<li><strong>ISEE of &euro;24,000 or less</strong>, and</li>
<li><strong>ISPE of &euro;50,000 or less</strong>.</li>
</ul>
<p>Almost every article about studying in Italy quotes <strong>&euro;26,887.93 and &euro;58,452.06</strong>. Those are the national reference figures, and <strong>Marche sets its own limits below them</strong>. A family that qualifies in Lombardy or Lazio can be refused here. Check your number against &euro;24,000, not against the figure in the general guides.</p>

<h3>Merit, and the trap for first-years</h3>
<ul>
<li><strong>Continuing students</strong> need credits banked by 10 August: <strong>25 CFU</strong> entering the second year of a bachelor's, <strong>30 CFU</strong> for a master's, then 80, 135, 190 and 245 CFU for later years.</li>
<li><strong>First-years are judged after the fact.</strong> You receive the grant, then you must earn <strong>20 CFU by 10 August</strong> of the following year to get the second half in cash, and <strong>20 CFU by 30 November</strong> to keep it at all.</li>
<li><strong>Miss that and the scholarship is revoked and has to be repaid.</strong> This is the single most common way international students lose the money.</li>
<li>A one-off bonus of 5, 12 or 15 credits can lift you over the threshold in later years, but it <strong>cannot</strong> be used for the first-year test.</li>
</ul>
<p>Winners are also <strong>exempt from the regional tax and from enrolment fees</strong>, so the grant and the fee waiver arrive together.</p>

<h2>What You Actually Pay</h2>

<h3>The fixed part</h3>
<p>Every student pays <strong>&euro;156 a year</strong>: a regional right-to-study tax of <strong>&euro;140</strong> plus <strong>&euro;16</strong> of stamp duty. UNIVPM publishes this as a single flat figure with <strong>no income banding</strong> &mdash; worth knowing, because most Italian universities, Pavia and Insubria included, charge &euro;140, &euro;160 or &euro;190 depending on your ISEE. At UNIVPM the poster's number is simply the number.</p>

<h3>The income-based part</h3>
<ul>
<li><strong>ISEE of &euro;28,000 or less: no tuition contribution at all.</strong> You pay the &euro;156 and nothing more, provided you keep to the credit requirements.</li>
<li>Between <strong>&euro;28,000 and &euro;30,000</strong> there are partial reductions.</li>
<li>Above that the contribution rises with income and varies by programme. UNIVPM publishes the ceiling only inside a scanned table, so no maximum is quoted here &mdash; use the university's own fee simulator for your exact course.</li>
</ul>

<h3>If your family lives abroad</h3>
<p>UNIVPM does not use the country-banded flat rate that Pavia and Insubria apply. It runs on the <strong>equivalent ISEE (ISEE parificato)</strong>, and the branch you fall into matters enormously:</p>
<ul>
<li><strong>Citizens of developing countries</strong> on the Italian foreign ministry's list pay the <strong>&euro;156 and nothing else in the first year</strong>, and the same in later years as long as they earn the required credits by 10 August &mdash; 10 CFU for the second year, 25 CFU after that.</li>
<li><strong>Citizens of OECD countries</strong> should file an ISEE parificato through a CAF office in Ancona before the enrolment deadline. <strong>If you file nothing, you are charged the maximum for your course.</strong></li>
<li>Where an equivalent ISEE genuinely cannot be obtained, a conventional figure is applied instead &mdash; <strong>&euro;13,001</strong> for citizens of a developing country, <strong>&euro;24,000</strong> for other non-EU students.</li>
</ul>

<h3>When the money is due</h3>
<ul>
<li><strong>Three instalments:</strong> the &euro;156 by <strong>5 November 2026</strong>, the second by <strong>14 December 2026</strong>, the third by <strong>31 May 2027</strong>.</li>
<li>Students arriving on a visa pay their first instalment by <strong>31 January 2027</strong>.</li>
<li><strong>Late fees:</strong> &euro;25 within 60 days of a deadline, &euro;50 after that.</li>
<li>Applying itself costs <strong>&euro;10 per application</strong>, and you may apply to two programmes.</li>
</ul>

<h2>PhDs and Paid Student Work</h2>
<ul>
<li><strong>A UNIVPM PhD is a salaried position</strong>, not a fee to be paid. The scholarship is <strong>&euro;16,243 a year gross</strong>, paid monthly, set by national decree.</li>
<li>Approved research periods abroad are paid at <strong>50% more</strong>, for up to twelve months in total, and every PhD student gets a research budget worth <strong>at least 10%</strong> of the scholarship each year.</li>
<li><strong>Part-time student work:</strong> up to <strong>150 hours a year at &euro;8 an hour</strong>, so up to &euro;1,200, and it is <strong>tax-free</strong> by law rather than taxable income. Open from your second year, once you hold two-fifths of your credits. PhD and master's students are excluded.</li>
</ul>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/polytechnic-university-of-marche-scholarships-campus.jpg" alt="Polytechnic University of Marche campus buildings above the Adriatic coast at Ancona with the scholarship and tuition details" width="1200" height="628" loading="lazy">
<figcaption>UNIVPM sits above the Adriatic at Ancona. ERDIS runs eight halls of residence in the city, free to out-of-town grant winners.</figcaption>
</figure>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Pick a programme</strong> and check its language requirement. English-taught degrees ask for <strong>B2</strong>; the Economics master's accept <strong>B1</strong>. An expired certificate, or one the awarding body cannot verify online, will be refused.</li>
<li><strong>Apply through the international admissions portal</strong> in one of the year's calls. For 2026/27 they ran from 16 December 2025 to <strong>5 November 2026</strong>, the last of them limited to medicine for non-EU visa applicants. It costs <strong>&euro;10</strong> per application.</li>
<li><strong>Get your qualification recognised.</strong> UNIVPM has a CIMEA agreement, so a <strong>CIMEA Statement of Comparability (&euro;150)</strong> is accepted <em>instead of</em> the Declaration of Value, and a Statement of Verification (&euro;65) instead of legalisation. Neither route is compulsory &mdash; pick whichever is faster where you are.</li>
<li><strong>Pre-enrol on Universitaly.</strong> Mandatory for every non-EU applicant who needs a visa, and missing from most checklists. For restricted-access courses you must apply on <strong>both</strong> the UNIVPM portal and Universitaly at the same time.</li>
<li><strong>For medicine, sit the IMAT.</strong> UNIVPM's English-taught Medicine and Surgery (MedTech) degree is <strong>exempt from Italy's new filter semester</strong> and keeps the IMAT. For 2026 it was sat on <strong>29 September</strong>, with registration closing 9 September.</li>
<li><strong>Apply for the visa by 30 November 2026</strong> for 2026/27, or by <strong>31 October 2027</strong> for 2027/28.</li>
<li><strong>Complete enrolment by 31 January</strong>. Miss it and the visa is revoked.</li>
<li><strong>Then chase the money:</strong> the university's scholarships by <strong>17 November</strong>, and the ERDIS grant when the next call opens around July 2027.</li>
</ol>

<div class="scholar-note scholar-note-warn">
<p><strong>The visa money proof has jumped.</strong> For 2026/27 you must show <strong>&euro;10,179.85</strong> for the year, up about <strong>46%</strong> from &euro;6,947.33. The consulate examines where the money came from and whether it is traceable, not just the balance on the day. A scholarship counts only once it has actually been awarded. Anyone planning from an older guide will be short.</p>
</div>

<h2>Living in Ancona</h2>
<ul>
<li><strong>ERDIS halls:</strong> eight residences in Ancona &mdash; Matteotti, Scosciacavalli, Buon Pastore, Brecce Bianche, Bartolo, Miglioli, Montemarino and Leopardi. A bed is <strong>free for out-of-town grant winners</strong> and charged at the call's tariff otherwise, for ten months of the year, allocated by open competition.</li>
<li><strong>Private rooms</strong> in Ancona advertise at roughly <strong>&euro;290 to &euro;420 a month</strong>, some excluding bills. A whole flat runs far higher, with a median around &euro;800.</li>
<li><strong>Transport is cheap.</strong> Under the UNIVPM agreement with Conerobus a student pays <strong>&euro;166 for a nine-month urban pass</strong> or <strong>&euro;190 for twelve months</strong> &mdash; about &euro;16 to &euro;18 a month, not the &euro;25 to &euro;40 the brief suggests. The university contributes &euro;50 towards it.</li>
<li><strong>Afterwards:</strong> Italy's job-seeking permit for graduates runs for <strong>not less than one year</strong>, and can be renewed on proof of income. Register with the employment centre within twelve months of your study permit expiring.</li>
</ul>

<h2>UNIVPM Compared With the Other Italian Options</h2>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>UNIVPM (Ancona)</th><th>Pavia</th><th>Insubria</th></tr></thead>
<tbody>
<tr><td>Fixed yearly cost</td><td>&euro;156 flat, no banding</td><td>From &euro;156, banded</td><td>From &euro;146, banded</td></tr>
<tr><td>No-tax threshold</td><td>ISEE &euro;28,000</td><td>ISEE &euro;32,000</td><td>ISEE &euro;22,000</td></tr>
<tr><td>Grant ceiling</td><td>ISEE &euro;24,000 / ISPE &euro;50,000</td><td>ISEE &euro;26,887.93</td><td>ISEE &euro;26,887.93</td></tr>
<tr><td>Living grant</td><td>Up to &euro;8,246.78 with the low-income uplift</td><td>Up to &euro;7,171</td><td>Up to &euro;7,171.11</td></tr>
<tr><td>University's own award</td><td>120 of &euro;2,000, open now</td><td>120 fee waivers</td><td>197 awards, &euro;500 to &euro;3,000</td></tr>
<tr><td>If you are from a developing country</td><td>&euro;156 and nothing more</td><td>Flat rate &euro;390 to &euro;4,550</td><td>Flat rate by country group</td></tr>
</tbody>
</table></div>
<p>For a student from a lower-income country, that last row is the whole argument: UNIVPM charges the &euro;156 and stops. Read our <a href="/scholarships/university-of-pavia-scholarships">University of Pavia scholarships guide</a> and <a href="/scholarships/university-of-insubria-scholarships">University of Insubria scholarships guide</a> for the two northern alternatives, and our <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP</a>, <a href="/scholarships/university-of-melbourne-rtp-scholarship">University of Melbourne RTP</a> and <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a> guides if a salaried PhD abroad is what you are really after.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does UNIVPM cost for international students?</h3>
<p>The fixed cost is &euro;156 a year, made of a &euro;140 regional tax and &euro;16 of stamp duty, and it is the same for everyone. If your assessed income is &euro;28,000 or less there is no tuition contribution on top. Students who are citizens of developing countries pay only the &euro;156, provided they earn the required credits each year.</p>

<h3>What scholarships can I apply for at UNIVPM right now?</h3>
<p>The university's own call is open: 120 scholarships of &euro;2,000, split between first-year bachelor's students, master's students and women starting a STEM degree, closing on 17 November. The ERDIS Marche regional grant closed on 28 August 2026 and reopens around July 2027.</p>

<h3>How much is the ERDIS Marche scholarship?</h3>
<p>&euro;7,171.11 a year if you live away from home, &euro;4,190.71 if you commute and &euro;2,890.16 if you already live in Ancona. Below an ISEE of &euro;12,000 those amounts rise by 15%, so the top figure becomes &euro;8,246.78. If you take an ERDIS room and meals, their value is deducted from the grant rather than added to it.</p>

<h3>What income limits apply in the Marche region?</h3>
<p>An ISEE of &euro;24,000 or less and an ISPE of &euro;50,000 or less, and you must be under both. These are stricter than the national figures of &euro;26,887.93 and &euro;58,452.06 that most guides to Italy quote, so check against the Marche numbers.</p>

<h3>Is a UNIVPM PhD funded?</h3>
<p>Yes. A PhD place at UNIVPM is a salaried position paying &euro;16,243 a year gross, with 50% more for approved research periods abroad and a research budget worth at least 10% of the scholarship. There is no separate merit scholarship for PhD students because the position itself is the funding.</p>

<h3>Are Fulbright, K.C. Mahindra or the J.N. Tata Endowment UNIVPM scholarships?</h3>
<p>No. Those are external awards run by other organisations that can be used at many universities. They are not funded, awarded or administered by UNIVPM, and applying to the university does not enter you for them. The university's own awards are the 120 scholarships of &euro;2,000 and the MA.R.I. scholarships of &euro;8,000 a year.</p>

<h3>How much money do I need to show for the student visa?</h3>
<p>&euro;10,179.85 for the 2026/27 academic year, about 46% more than the &euro;6,947.33 required the year before. The consulate checks that the funds are lawful and traceable, not simply present, and a scholarship counts only after it has been formally awarded.</p>

<h3>Can I work while studying at UNIVPM?</h3>
<p>Yes. The university runs a part-time scheme of up to 150 hours a year at &euro;8 an hour, worth up to &euro;1,200, and by law that pay is tax-free rather than taxable income. It opens from your second year once you hold two-fifths of your credits.</p>

<h3>Where does UNIVPM rank?</h3>
<p>QS places it in the 801 to 850 band for 2026 and Times Higher Education at 401 to 500. By subject it does better: medical and health sciences rank 301 to 400 with THE, and ShanghaiRanking puts food science and technology at 76 to 100 in the world. The "top 730" figure that circulates matches no published edition.</p>

<h3>Who do I contact at UNIVPM?</h3>
<p>The International Students Admission Office is at Via Oberdan 8, 60121 Ancona, on +39 071 220 2450. Its own page gives the email as student.admission@sm.univpm.it while the international FAQ gives student.admission@univpm.it, so try both if one bounces. General enquiries go to info@univpm.it on +39 071 220 1, and fee questions to dirittoallostudio@univpm.it. The university's registered seat is Piazza Roma 22, 60121 Ancona.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">UNIVPM scholarships</a></li>
<li><a href="https://www.univpm.it/Entra/Universita_Politecnica_delle_Marche_Home/Tasse_e_agevolazioni/Tuition_and_Fees" target="_blank" rel="noopener">Tuition and fees, with instalment dates</a></li>
<li><a href="https://www.international.univpm.it/international-admissions/" target="_blank" rel="noopener">International admissions and application calls</a></li>
<li><a href="https://www.international.univpm.it/become-a-student/useful-information/tuition-fees/" target="_blank" rel="noopener">Fees for international students</a></li>
<li><a href="https://erdis.it/documenti/3615115/bando-borsa-studio-2026-2027" target="_blank" rel="noopener">ERDIS Marche 2026/27 scholarship call</a></li>
<li><a href="https://erdis.it/luoghi/3188088/alloggi-erdis" target="_blank" rel="noopener">ERDIS student residences</a></li>
<li><a href="https://www.univpm.it/Entra/Accordo_Univpm_Cimea" target="_blank" rel="noopener">UNIVPM and CIMEA agreement</a></li>
<li><a href="https://www.conerobus.it/tariffe/abbonamenti/abbonamenti-studenti-univpm/" target="_blank" rel="noopener">Conerobus student travel passes</a></li>
<li><a href="https://www.international.univpm.it/the-university/our-study-courses/english-taught-courses/" target="_blank" rel="noopener">English-taught degree programmes</a></li>
<li><a href="https://www.univpm.it/Entra/Ateneo/Storia_dellAteneo" target="_blank" rel="noopener">The university's own history</a></li>
<li><a href="https://www.international.univpm.it/first-week-at-univpm/useful-contacts/" target="_blank" rel="noopener">Useful contacts for new students</a></li>
</ul>

<p><em>JobGader is not part of the Polytechnic University of Marche. This guide was checked against the university's own pages, the ERDIS Marche 2026/27 call and the Italian ministry and consulate rules on 12 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
