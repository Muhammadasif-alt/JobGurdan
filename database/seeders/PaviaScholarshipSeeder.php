<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Scholarships, fee waivers and tuition at the University of Pavia, written
 * up as a plain-English guide.
 *
 * Checked on 12 September 2026 against the university's history and facts
 * pages, the 2026/27 student contribution notice and fee simulator, the
 * exemptions and incentives page, the non-EU fees page, the apply.unipv.eu
 * application calendar and pre-enrolment notice, the CICOPS pages, the
 * CIVRISK scholarship page, EDiSU Pavia's 2026/27 bando and services pages,
 * the Invest Your Talent in Italy call and the official contacts page.
 * Corrections to the brief and the posters:
 *
 * 1. The posters call Pavia the "Oldest University in Italy". Bologna (1088)
 *    is older. Pavia's own history page dates a school of rhetoric to Lothair
 *    I's capitulary of 825 and the university itself to 1361.
 * 2. The brief says 50 tuition fee waivers. The figure on the official bando
 *    is 120, they are first-year only and non-renewable.
 * 3. The brief says CICOPS offers 6 scholarships a year. It offers 10, and
 *    the 2027 call carried 8.
 * 4. The brief says EDiSU pays at most €3,967. The 2026/27 national floors
 *    are €7,171 fuori sede, €4,190 pendolare and €2,890 in sede.
 * 5. The brief calls €156 a flat enrolment tax. The regional tax is banded
 *    at €140, €160 or €190 by ISEE, so €156 is the floor, not a fixed price.
 * 6. The brief says €1,000 a year applies to "specific partnerships". The
 *    €1,000 in the fee rules is a cap on the PA 110 e lode contribution.
 * 7. The brief leaves out the flat rate that most non-EU students actually
 *    pay: three brackets from €390 to €4,550 by citizenship and subject.
 * 8. The brief says 9% international students and 700+ Erasmus and 400+
 *    international partnerships. QS says 11% and THE 12%; the official facts
 *    page gives 500 international partnerships and 900 exchange scholarships.
 * 9. The brief says top 400 world and top 200 Europe. QS World 2026 places
 *    Pavia =423, THE 2026 at 351-400 and ARWU 2026 at 401-500. No Europe
 *    ranking position could be confirmed, so none is published.
 * 10. The brief says Pavia is 100 km from Milan; it is about 35 km.
 * 11. The brief's international@unipv.it and +39 0382 985878 appear nowhere
 *    official. The Welcome Point uses welcomeoffice@unipv.it, admissions use
 *    admission@unipv.it, and the phone number is +39 0382 984020.
 * 12. The brief says extraordinary aid is granted in order of submission.
 *    EDiSU describes it as need-based.
 * 13. The brief's €2,953, €3,758 and €4,619 fee figures and its blanket
 *    IELTS 6.0 could not be confirmed and are not published here.
 *
 * The four posters are used as supplied; their "Oldest University in Italy",
 * "50 Fee Waivers" and "400+ Partnerships" lines are corrected in the text
 * and captions.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class PaviaScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-pavia-scholarships';

    public const APPLY_URL = 'https://en.unipv.it/en/education/bachelors-and-masters-degree-programs/fees-and-funding/fees/exemptions-and-incentives';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Pavia Scholarships and Fees 2026–27',
                'provider' => 'University of Pavia (Università di Pavia)',
                'country' => 'Italy',
                'city' => 'Pavia',
                'study_level' => "Bachelor's, Master's, PhD",
                'funding_type' => 'Fee Waivers + Living Grants',
                'award_value' => '120 fee waivers + living grants',
                'deadline' => '2026-09-15',
                'deadline_note' => 'Next admission and EDiSU rounds not yet published',
                'excerpt' => "The University of Pavia offers 120 tuition fee waivers a year, and Italy's highest no-tax threshold means an ISEE up to €32,000 pays no tuition at all. The EDiSU living grant, worth up to €7,171, closes on 15 September 2026.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/university-of-pavia-scholarships.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'University of Pavia Scholarships 2026: Fees and Waivers',
                'meta_description' => 'University of Pavia scholarships 2026-27: 120 tuition fee waivers, EDiSU grants closing 15 September, CICOPS awards and what non-EU students really pay.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-12 00:10:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>University of Pavia</strong> is one of Italy's oldest and best-known state universities, in a small river city about <strong>35 km south of Milan</strong>. For international students its appeal is simple: Italian state universities charge tuition by <strong>family income rather than nationality</strong>, Pavia operates the <strong>highest no-tax threshold in Italy</strong>, and it hands out <strong>120 tuition fee waivers</strong> a year on top. A student from a low-income family can end up paying only the <strong>fixed tax of about &euro;156</strong> for a full degree.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Closing in days:</strong> the <strong>EDiSU living grant</strong> for 2026/27, worth up to <strong>&euro;7,171 a year</strong> plus a free daily meal, closes on <strong>15 September 2026 at 15:00</strong> Italian time. Every 2026/27 admission window at Pavia has already closed, and the 2027/28 calendar is not published yet, so if you are planning for next year, start with the fee rules below.</p>
</div>

<h2>Pavia Scholarships and Fees at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>Universit&agrave; di Pavia, a studium generale since 1361</td></tr>
<tr><th>Where</th><td>Pavia, Lombardy, about 35 km south of Milan</td></tr>
<tr><th>Size</th><td>More than 26,000 students, 18 departments, 2 faculties, 85 degree courses, 21 colleges</td></tr>
<tr><th>Taught in English</th><td>30 programmes</td></tr>
<tr><th>Fixed tax everyone pays</th><td>From about &euro;156 a year (regional right-to-study tax plus &euro;16 stamp duty)</td></tr>
<tr><th>No-tax area</th><td>ISEE up to <strong>&euro;32,000</strong> pays no tuition contribution &mdash; the highest threshold in Italy</td></tr>
<tr><th>If you live abroad</th><td>A flat rate of <strong>&euro;390 to &euro;4,550</strong> a year by citizenship and subject area, or the equivalent ISEE instead</td></tr>
<tr><th>Fee waivers</th><td><strong>120</strong>, first come first served, first year only</td></tr>
<tr><th>Living grant</th><td>EDiSU: up to <strong>&euro;7,171</strong> (living away), &euro;4,190 (commuter), &euro;2,890 (local)</td></tr>
<tr><th>Application fee</th><td>&euro;35, mandatory and non-refundable</td></tr>
<tr><th>Apply through</th><td>apply.unipv.eu, then Universitaly pre-enrolment for a study visa</td></tr>
</tbody>
</table></div>

<h2>How Much a Degree at Pavia Really Costs</h2>
<p>This is the part most guides get wrong, so it is worth going slowly. Your bill has <strong>two parts</strong>: a fixed tax that everyone pays, and a tuition contribution that depends on your family's finances.</p>

<h3>Part one: the fixed tax</h3>
<p>Every enrolled student pays the <strong>regional tax for the right to study</strong> plus <strong>&euro;16 of stamp duty</strong>. The regional tax is not a flat &euro;140 as many articles claim &mdash; it is set in bands by your ISEE:</p>
<div class="scholar-table"><table>
<thead><tr><th>ISEE band</th><th>Regional tax</th><th>With stamp duty</th></tr></thead>
<tbody>
<tr><td>Lowest band</td><td>&euro;140</td><td><strong>&euro;156</strong></td></tr>
<tr><td>About &euro;28,339.89 to &euro;56,679.76</td><td>&euro;160</td><td>&euro;176</td></tr>
<tr><td>Above that</td><td>&euro;190</td><td>&euro;206</td></tr>
</tbody>
</table></div>
<p>So <strong>&euro;156 is the floor, not a fixed price</strong>. It is what a fee-waiver holder or a low-income student ends up paying for the whole year. The university's own notice adds that the amount is revised annually and can vary slightly.</p>

<h3>Part two: the tuition contribution</h3>
<ul>
<li><strong>ISEE of &euro;32,000 or less: you pay no tuition contribution at all.</strong> Pavia has raised its no-tax area from &euro;23,000 to &euro;32,000, the highest in Italy. It applies while you are within the normal duration of your degree plus one year.</li>
<li>Above that threshold, the contribution rises with income, and the maximum differs by subject area &mdash; humanities, law and economics sit in a cheaper band than sciences, engineering and medicine.</li>
<li>Figures of "&euro;2,953", "&euro;3,758" and "an average master's of &euro;4,619" circulate widely. Those specific numbers could not be confirmed in the 2026/27 annexes, so treat them as unofficial and check the fee simulator before you budget.</li>
</ul>

<div class="scholar-note">
<p><strong>The &euro;1,000 figure explained.</strong> Some articles say international students on "specific partnerships" pay &euro;1,000 a year. That is a misreading: the &euro;1,000 in Pavia's fee rules is a cap on the contribution for <em>PA 110 e lode</em> courses, a scheme for Italian public-sector employees. It is not an international tuition rate.</p>
</div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-pavia-scholarships-students.jpg" alt="Students at the University of Pavia with the tuition range and fee waiver details" width="1200" height="628" loading="lazy">
<figcaption>Pavia offers 120 tuition fee waivers a year, not 50 as the poster says, and they cover the first year only.</figcaption>
</figure>

<h2>If Your Family Lives Abroad: Two Routes</h2>
<p>You cannot normally get an Italian ISEE from outside Italy, so the university gives international students a choice. Working out both is worth an afternoon, because the gap can run to thousands of euros.</p>

<h3>Route 1: the flat rate</h3>
<p>Non-EU students who file no income documents are placed automatically in a <strong>flat-rate bracket set by citizenship and area of study</strong>. There are <strong>three brackets, running from &euro;390 to &euro;4,550 a year</strong>, plus the fixed tax. The bracket list is an annex to the fee rules and is updated each year on the World Bank's country classification. Once set, your bracket is fixed for the normal duration of your degree plus one year.</p>

<h3>Route 2: the equivalent ISEE</h3>
<p>If your family's income is genuinely low, declaring it usually beats the flat rate. The <strong>equivalent ISEE (ISEE parificato)</strong> is worked out from your family's foreign income for 2025 plus <strong>20% of its assets</strong>, converted at Bank of Italy exchange rates, with documents translated and legalised. It takes weeks, so start before you arrive. Get under &euro;32,000 and your tuition contribution is zero.</p>

<h2>The 120 Tuition Fee Waivers</h2>
<ul>
<li><strong>How many:</strong> the figure on the official call is <strong>120</strong>. The "50" repeated in many articles is not supported by the university's own bando, though the count for 2026/27 has not been separately confirmed.</li>
<li><strong>How they are given:</strong> <strong>first come, first served</strong>, so applying in the earliest admission window matters far more than a perfect transcript.</li>
<li><strong>What is left to pay:</strong> the waiver covers the tuition contribution. You still pay the regional tax and stamp duty, about <strong>&euro;156</strong>.</li>
<li><strong>How long:</strong> <strong>first year only, and not renewable.</strong> Plan how you will pay from year two &mdash; usually through the equivalent ISEE or an EDiSU grant.</li>
<li><strong>How to apply:</strong> inside your programme application on apply.unipv.eu, not as a separate form.</li>
</ul>

<h2>EDiSU: The Grant That Pays You to Live</h2>
<p>EDiSU Pavia is the regional right-to-study body. Its ordinary scholarship is the largest single sum a Pavia student can receive, and it is open to international students on the same terms as Italians.</p>
<div class="scholar-table"><table>
<thead><tr><th>Your situation</th><th>2026/27 national floor</th></tr></thead>
<tbody>
<tr><td><strong>Fuori sede</strong> &mdash; you rent near the university</td><td>&euro;7,171</td></tr>
<tr><td><strong>Pendolare</strong> &mdash; you commute daily</td><td>&euro;4,190</td></tr>
<tr><td><strong>In sede</strong> &mdash; you live in Pavia already</td><td>&euro;2,890</td></tr>
</tbody>
</table></div>
<ul>
<li><strong>Income limits:</strong> <strong>ISEE UNI 2026 of &euro;26,887.93 or less</strong> and <strong>ISPE UNI 2026 of &euro;58,452.06 or less</strong>. A figure of &euro;26,306.25 circulates online; that is Piedmont's limit, not Pavia's.</li>
<li><strong>Deadline:</strong> <strong>15 September 2026 at 15:00</strong>.</li>
<li><strong>Meals:</strong> grant holders get <strong>one free meal a day</strong>. Everyone else pays &euro;4.00, &euro;4.50, &euro;5.00 or &euro;6.50 depending on their bracket.</li>
<li><strong>Rooms:</strong> EDiSU runs <strong>12 colleges with 1,643 beds</strong>. Fees are annual and set each year by economic bracket, so the "&euro;0 to &euro;360 a month" often quoted is not an official range. The 2026/27 college placement call closed on <strong>4 August 2026 at 15:00</strong>.</li>
<li><strong>Hardship money:</strong> EDiSU's extraordinary contributions are <strong>need-based</strong>, for students facing particularly difficult economic or health circumstances &mdash; not first-come-first-served as some summaries say. The university separately ran a <strong>&euro;1 million</strong> support package, mostly rent help, open up to an ISEE of &euro;40,000.</li>
</ul>

<h2>Named Scholarships Worth Knowing</h2>

<h3>CICOPS, for visiting researchers</h3>
<p>Often listed as a student scholarship; it is not. CICOPS funds <strong>academics</strong> from developing countries to spend time researching at Pavia.</p>
<ul>
<li><strong>10 scholarships a year</strong>, with <strong>8</strong> in the 2027 call &mdash; not 6.</li>
<li>Stays of <strong>4 to 12 weeks</strong>, paying <strong>&euro;150 gross a week</strong>, plus travel, meals, accommodation and health insurance.</li>
<li>You need a <strong>PhD by the deadline</strong> and <strong>at least two years' employment</strong> at a university or public or non-profit research centre in a World Bank low, lower-middle or upper-middle income economy.</li>
<li>An <strong>invitation letter from a Pavia professor is mandatory</strong>. PhD and postgraduate students at Italian universities are excluded.</li>
<li>The <strong>2027 call has closed</strong>; its stays run 11 January to 24 July 2027 and 6 September to 18 December 2027.</li>
</ul>

<h3>CIVRISK, for civil engineers</h3>
<p><strong>Two scholarships of &euro;4,000 a year</strong> for the MSc in Civil Engineering for Risk Mitigation. The &euro;3,000 figure still online is the old 2024/25 amount. The 2026/27 deadline was <strong>17 June 2026</strong>, with interviews from 24 June and the ranking by 26 July 2026.</p>

<h3>Engineering master's scholarships</h3>
<p>Pavia does advertise scholarships for newly enrolled students on two MSc engineering programmes, described as covering full tuition plus a monthly stipend, and the Environmental Engineering <strong>REACH</strong> curriculum has its own 2026/27 award. The exact names, amounts and closing dates are not published in a form that could be confirmed, so check the engineering notice board directly rather than trusting a figure from an article.</p>

<h3>Invest Your Talent in Italy</h3>
<p>An Italian government scheme, and <strong>Pavia takes part</strong>. It pays <strong>&euro;900 a month for 9 months</strong>, waives tuition and adds a traineeship with an Italian company. Applications close on <strong>11 May at 18:00</strong> Italian time each year; the 2026/27 round has closed.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-pavia-scholarships-graduates.jpg" alt="Graduates at the University of Pavia with the scholarship and no-tax area details" width="1200" height="628" loading="lazy">
<figcaption>An ISEE up to &euro;32,000 means no tuition contribution &mdash; the highest no-tax threshold of any Italian university.</figcaption>
</figure>

<h2>Pavia's Colleges: Cheap Rooms and Merit Money</h2>
<p>Pavia is unusual in Italy for its <strong>college system</strong>, a little like Oxford or Cambridge. Alongside EDiSU's public colleges there are historic merit colleges that select by competition and can be far cheaper than they look.</p>
<ul>
<li><strong>The unified competition</strong> is run with the IUSS school: applications closed <strong>28 August 2026 at 12:00</strong>, written papers were on <strong>3 September 2026</strong> and orals ran online from <strong>11 September 2026</strong>.</li>
<li><strong>Collegio Ghislieri</strong> &mdash; 16 places for bachelor's and single-cycle students plus up to 10 for master's. The full price is <strong>&euro;11,500 a year</strong>, but it is <strong>free if your ISEE is &euro;30,000 or less</strong>, and around 70% of students attend effectively free.</li>
<li><strong>Collegio Nuovo</strong> &mdash; <strong>&euro;5,000 a year if your ISEE is under &euro;30,000</strong>, and winners of the IUSS unified competition receive a &euro;5,000 a year study prize.</li>
<li><strong>Collegio Santa Caterina</strong> &mdash; at least 12 places plus at least 8 in its Biomedical Residence, with free renewable places offered; its own deadline was 28 August 2026 at 16:00.</li>
<li><strong>EDiSU's colleges</strong>, such as Volta and Cairoli, are the straightforward public option if you would rather not sit an entrance competition.</li>
</ul>

<h2>Applying: Dates, Documents and the Visa</h2>

<h3>The 2026/27 admission calendar</h3>
<p>Pavia runs several windows a year through <strong>apply.unipv.eu</strong>, its official application portal. All five 2026/27 windows have closed, but the pattern is the best guide to what 2027/28 will look like:</p>
<div class="scholar-table"><table>
<thead><tr><th>Window</th><th>Open</th><th>Results</th><th>Who</th></tr></thead>
<tbody>
<tr><td>1st</td><td>12&ndash;20 November 2025</td><td>19 December 2025</td><td>Everyone</td></tr>
<tr><td>2nd</td><td>12&ndash;20 January 2026</td><td>25 February 2026</td><td>Everyone</td></tr>
<tr><td>3rd</td><td>10&ndash;18 March 2026</td><td>27 April 2026</td><td>EU, and non-EU already in Italy</td></tr>
<tr><td>4th</td><td>5&ndash;11 June 2026</td><td>6 July 2026</td><td>Everyone</td></tr>
<tr><td>5th</td><td>1&ndash;8 September 2026</td><td>17 September 2026</td><td>EU, and non-EU already in Italy</td></tr>
</tbody>
</table></div>
<p>All deadlines fall at <strong>23:59 CET</strong>. Note the pattern: if you need a visa, the <strong>first, second and fourth windows are your only chances</strong>. Applying in the first window also gives the best shot at a fee waiver, because they go first come first served.</p>

<h3>The application fee</h3>
<p>There is a <strong>&euro;35 application fee</strong>, mandatory and non-refundable, payable before the window closes. An unpaid fee means an unassessed application.</p>

<h3>The visa: Universitaly pre-enrolment</h3>
<p>Non-EU students must pre-enrol on <strong>Universitaly</strong> before applying for a type D study visa &mdash; without it, enrolment cannot be confirmed. The 2026/27 deadlines were <strong>25 May 2026</strong> for the first three intakes, <strong>20 July 2026</strong> for the fourth and <strong>31 August 2026</strong> for restricted-access programmes. You upload your passport, photo, diploma or degree and your admission letter. Pre-enrolment guarantees neither a consular appointment nor a visa.</p>

<h3>Language</h3>
<p>There is <strong>no single English requirement</strong> at Pavia. B2 is typical for English-taught master's degrees and B1 for many Italian-taught entries, but several programmes ask for considerably more &mdash; IELTS Academic 7.0, TOEFL iBT 92 or a Cambridge CAE. Read your own programme page and ignore any article quoting one universal score.</p>

<h3>Medicine</h3>
<p>Pavia's English-taught medicine degree, the <strong>Harvey course</strong>, is entered through the national <strong>IMAT</strong> exam, booked on Universitaly for a fee of about &euro;130. The 2026 sitting was set for <strong>29 September 2026</strong> after a ministry correction to an original 30 September date, with registration from 26 August to 9 September 2026. The long-standing official intake is <strong>100 places a year</strong>; larger numbers circulating online are unofficial.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-pavia-scholarships-campus.jpg" alt="University of Pavia campus by the river with the application windows and fees" width="1200" height="628" loading="lazy">
<figcaption>If you need a study visa, only the November, January and June windows work &mdash; the March and September rounds are for EU applicants and non-EU students already in Italy.</figcaption>
</figure>

<h2>Where Pavia Actually Ranks</h2>
<p>Pavia is a strong mid-table international university, and it is worth being accurate about that rather than repeating marketing lines:</p>
<ul>
<li><strong>QS World University Rankings 2026: =423</strong> (and =395 in the 2027 edition). "Top 400" was not true for 2026.</li>
<li><strong>THE World University Rankings 2026: 351&ndash;400.</strong></li>
<li><strong>ARWU 2026: 401&ndash;500.</strong></li>
<li><strong>International students: 11% (QS) to 12% (THE)</strong>, not 9%.</li>
<li><strong>500 international partnerships</strong>, 50 projects and <strong>900 exchange scholarships</strong> &mdash; the "700+ Erasmus" and "400+ partnerships" figures on the posters are not the university's own.</li>
<li>No confirmed "top 200 in Europe" position exists, so treat that claim as marketing.</li>
</ul>

<div class="scholar-note">
<p><strong>About "the oldest university in Italy".</strong> Pavia's posters say it, but <strong>Bologna (1088) is older</strong>. Pavia's own history page traces a <em>school of rhetoric</em> to Emperor Lothair I's capitulary of <strong>825</strong> and dates the university itself to <strong>1361</strong>, when Emperor Charles IV founded the studium generale at Galeazzo II Visconti's request. It is genuinely one of Europe's oldest universities &mdash; just not Italy's oldest.</p>
</div>

<h2>Pavia Compared</h2>
<div class="scholar-table"><table>
<thead><tr><th>&nbsp;</th><th>Pavia</th><th>Insubria</th><th>Australian RTP</th></tr></thead>
<tbody>
<tr><td>Founded</td><td>1361</td><td>1998</td><td>&mdash;</td></tr>
<tr><td>No-tax threshold</td><td>ISEE &euro;32,000</td><td>ISEE &euro;22,000</td><td>&mdash;</td></tr>
<tr><td>Fixed tax</td><td>From &euro;156</td><td>From &euro;146</td><td>None</td></tr>
<tr><td>Living grant</td><td>EDiSU, up to &euro;7,171</td><td>Regional grant, up to &euro;7,171.11</td><td>AUD \$37,145 to \$42,754</td></tr>
<tr><td>Taught in English</td><td>30 programmes</td><td>7 master's programmes</td><td>Everything</td></tr>
<tr><td>Levels funded</td><td>Bachelor's, master's, PhD</td><td>Bachelor's, master's, PhD</td><td>PhD and research master's only</td></tr>
</tbody>
</table></div>
<p>Pavia is the better-known name with far more English-taught choice; Insubria is smaller, newer and cheaper on the fixed tax. Read our <a href="/scholarships/university-of-insubria-scholarships">University of Insubria scholarships guide</a> for the comparison, and our <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP</a>, <a href="/scholarships/australian-national-university-rtp-scholarship">ANU RTP</a> and <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a> guides if a funded PhD abroad is what you are really after.</p>

<h2>Frequently Asked Questions</h2>

<h3>How many tuition fee waivers does the University of Pavia offer?</h3>
<p>The figure on the official call is 120, given first come first served to first-year students. They are not renewable, and holders still pay the regional tax and stamp duty, about &euro;156. Many articles say 50; that number is not supported by the university's own bando.</p>

<h3>How much does the University of Pavia cost for international students?</h3>
<p>Everyone pays a fixed tax from about &euro;156 a year. On top of that, non-EU students living abroad who file no income documents pay a flat rate of &euro;390 to &euro;4,550 a year, set by citizenship and subject area. If you file an equivalent ISEE instead and it comes to &euro;32,000 or less, you pay no tuition contribution at all.</p>

<h3>What is Pavia's no-tax area?</h3>
<p>An ISEE of &euro;32,000 or less, raised from &euro;23,000 and the highest threshold of any Italian university. It applies while you are within the normal duration of your degree plus one year.</p>

<h3>How much is the EDiSU scholarship and when does it close?</h3>
<p>Up to &euro;7,171 a year if you live away from home, &euro;4,190 if you commute and &euro;2,890 if you live in Pavia, plus one free meal a day. The 2026/27 deadline is 15 September 2026 at 15:00. You need an ISEE of &euro;26,887.93 or less and an ISPE of &euro;58,452.06 or less.</p>

<h3>Can I apply for CICOPS as a student?</h3>
<p>No. CICOPS funds visiting academics, not degree students. You need a PhD and at least two years' employment at a university or public or non-profit research centre in a low, lower-middle or upper-middle income economy, plus an invitation from a Pavia professor. It offers 10 scholarships a year, with 8 in the 2027 call.</p>

<h3>When can I apply to the University of Pavia for 2027/28?</h3>
<p>The 2027/28 calendar has not been published. In 2026/27 there were five windows, from 12 November 2025 to 8 September 2026, and only the November, January and June rounds were open to non-EU applicants who needed a visa. Watch apply.unipv.eu from around October.</p>

<h3>What English score do I need for the University of Pavia?</h3>
<p>It depends on the programme. B2 is typical for English-taught master's degrees and B1 for many Italian-taught entries, but some programmes ask for IELTS Academic 7.0, TOEFL iBT 92 or a Cambridge CAE. There is no single university-wide score.</p>

<h3>Is the University of Pavia the oldest university in Italy?</h3>
<p>No. Bologna, founded in 1088, is older. Pavia's history page traces a school of rhetoric to 825 and the university itself to 1361, which still makes it one of Europe's oldest.</p>

<h3>Who do I contact at the University of Pavia?</h3>
<p>The Welcome Point at welcomeoffice@unipv.it or +39 0382 984020, and admissions at admission@unipv.it. The university's address is Corso Strada Nuova 65, 27100 Pavia. The international@unipv.it address and the number +39 0382 985878 that circulate online do not appear on any official page.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Exemptions and incentives, including the fee waivers</a></li>
<li><a href="https://apply.unipv.eu/" target="_blank" rel="noopener">apply.unipv.eu, the official application portal</a></li>
<li><a href="https://en.unipv.it/en/education/bachelors-and-masters-degree-programs/fees-and-funding/fees/prospective-students/non-eu-students" target="_blank" rel="noopener">Fees for non-EU students, including the flat rate</a></li>
<li><a href="https://simulatasse.unipv.it/" target="_blank" rel="noopener">The official tuition fee simulator</a></li>
<li><a href="https://www.edisu.pv.it/en/services/call-for-ordinary-scholarship/" target="_blank" rel="noopener">EDiSU ordinary scholarship call</a></li>
<li><a href="http://cicops.unipv.it/" target="_blank" rel="noopener">CICOPS scholarships for visiting scholars</a></li>
<li><a href="https://civrisk.unipv.it/scholarships/" target="_blank" rel="noopener">CIVRISK scholarships</a></li>
<li><a href="https://en.unipv.it/en/contacts" target="_blank" rel="noopener">Official contacts</a></li>
</ul>

<p><em>JobGader is not part of the University of Pavia. This guide was checked against the university's official pages, EDiSU Pavia and the Italian ministries on 12 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
