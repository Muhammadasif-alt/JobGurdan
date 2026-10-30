<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * "Portugal Scholarships 2026/27 for international students", rewritten around
 * the routes that actually exist - the FCT PhD studentships, the University of
 * Porto international admission and its merit and scientific scholarships - and
 * around the DGES warning that the "Portugal Government Scholarship 2026" doing
 * the rounds online is not real.
 *
 * Checked on 16 September 2026 against the DGES notice of 20 November 2025 on
 * false scholarship information, the DGES international-student and grants
 * pages, the FCT 2026 PhD studentships call and the myFCT portal, and the
 * University of Porto Special Call for international students (up.pt and
 * sigarra.up.pt). Corrections to the brief and the posters:
 *
 * 1. The posters and the "Portugal Government Scholarships 2026" pages promise
 *    a single fully funded government scholarship open to all nationalities.
 *    DGES says in writing that this does not correspond to any programme
 *    promoted by the Portuguese State.
 * 2. "Free tuition" is wrong for international students. Non-EU students under
 *    the international-student statute pay institution-set fees; at Porto these
 *    run from about EUR 3,500 to EUR 16,500 a year.
 * 3. International-statute students do not get Portugal's social-action grants.
 *    They have access only to indirect social action, unless they hold the
 *    humanitarian-emergency student statute.
 * 4. The DGES means-tested grant reaches a third-country national only through
 *    permanent residence, long-term-resident status or a cooperation or
 *    equal-treatment agreement - that is, when they are not enrolled under the
 *    international-student regime at all.
 * 5. "No IELTS" is programme-dependent, not a country rule. English-taught
 *    programmes set their own proof of English and often accept alternatives or
 *    a medium-of-instruction exemption; Portuguese-taught programmes ask for
 *    Portuguese instead.
 * 6. The unicafscholarship.com "apply" link on one poster is a private-provider
 *    pathway, not a Portuguese State or public-university scholarship.
 *
 * The three posters are used as supplied, at the owner's instruction; the text
 * corrects them.
 *
 * Updated on 30 October 2026 from a second brief, rather than publishing a rival page. Added: Erasmus Mundus
 * (official page and Programme Guide: students worldwide, no second EMJM scholarship, 10% nationality cap, October to
 * January), University of Aveiro's 2026/27 international fees (Deliberacao 16/CG/2025: EUR 5,000, 4,150, 3,500) and its
 * incentive regulation 950/2021, and the D4 grant exemption from the VFS Islamabad checklist. Not stated as fact because
 * no official text could be read: EUR 1,400 a month (third-party only), Aveiro's EUR 1,000 and grade 16, Coimbra's grade
 * 140 and EUR 7,000, Minho's grade 16 and 167 winners, the EUR 110 visa fee and the three-application limit. The brief's
 * FCT stipend of about EUR 1,260 is the stale figure; EUR 1,359.64 stands.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class PortugalScholarshipsSeeder extends Seeder
{
    public const SLUG = 'portugal-scholarships';

    public const APPLY_URL = 'https://www.dges.gov.pt/en/pagina/international-students';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Portugal Scholarships 2026/27 for International Students',
                'provider' => 'Portuguese universities, DGES and FCT',
                'country' => 'Portugal',
                'city' => 'Lisbon, Porto and Coimbra',
                'study_level' => "Bachelor's, Master's, PhD",
                'funding_type' => 'Erasmus Mundus, FCT PhD and university awards',
                'award_value' => 'FCT PhD: EUR 1,359.64/month; university merit awards',
                'deadline' => null,
                'deadline_note' => 'Erasmus Mundus rounds usually run October to January. FCT and Porto 2026 rounds closed; FCT normally reopens around March.',
                'excerpt' => 'No fully funded Portugal Government Scholarship exists for everyone: DGES has said so. The real money is Erasmus Mundus for a Master\'s, FCT for a PhD, and university fee discounts for everyone else. Here is which fits you.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/portugal-scholarships-study-in-europe.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Portugal Scholarships 2026/27 for International Students',
                'meta_description' => 'Portugal scholarships for Pakistani and international students: Erasmus Mundus, FCT PhD, university discounts, IELTS rules and the fake government scholarship.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-16 06:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>If you searched for a fully funded Portugal scholarship, you have probably already seen the posters: "Portugal Government Scholarship 2026, all nationalities, apply now". Start by ignoring them. <strong>That scholarship does not exist</strong>, and Portugal's own higher-education authority, DGES, has said so in writing.</p>

<p>Real money does exist, but it depends on what you want to study. Going for a Master's? Look at <strong>Erasmus Mundus</strong>. Going for a PhD? Look at <strong>FCT</strong>. Going for a Bachelor's? Be honest with yourself: you will almost certainly pay fees, and the best you can expect is a discount from the university. The rest of this page takes each of those in turn.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Before you plan around the posters:</strong></p>
<ul>
<li><strong>The "Portugal Government Scholarships 2026" pages are a warning, not an opportunity.</strong> DGES published a notice on <strong>20 November 2025</strong> saying this information "is false and does not correspond to any scholarship programme promoted by the Portuguese State".</li>
<li><strong>Portugal is not free for international students.</strong> Non-EU students under the international-student statute pay institution-set fees - at Porto, about <strong>&euro;3,500 to &euro;16,500 a year</strong>.</li>
<li><strong>Some rounds have closed, one is opening.</strong> The FCT PhD studentships closed on <strong>31 March 2026</strong> and Porto's 2026/27 international rounds are over. Erasmus Mundus programmes usually take applications between <strong>October and January</strong>, so that door is open about now.</li>
<li><strong>"No IELTS" depends on the programme,</strong> not on the country. The programme that admits you sets its own proof of English.</li>
</ul>
</div>

<h2>Portugal Scholarships at a Glance</h2>
<div class="scholar-table"><table>
<thead><tr><th>Route</th><th>Level</th><th>What it gives</th><th>Who applies</th><th>Status</th></tr></thead>
<tbody>
<tr><td>Erasmus Mundus Joint Masters</td><td>Master's</td><td>Full scholarship for the best-ranked students: participation costs, plus travel, visa and living contribution</td><td>Students worldwide, directly to each programme</td><td>Usually October to January</td></tr>
<tr><td>FCT PhD Studentships</td><td>PhD</td><td>Monthly allowance plus fees, insurance, training and conference support</td><td>All nationalities, through myFCT, via a host institution</td><td>2026 call closed 31 March 2026</td></tr>
<tr><td>University of Aveiro incentive scholarships</td><td>Bachelor's, Master's</td><td>A cut in the &euro;3,500 to &euro;5,000 international fee</td><td>International students, on the university's terms</td><td>Check with Aveiro</td></tr>
<tr><td>University of Coimbra award</td><td>Master's</td><td>Up to &euro;2,000 a year off the fee</td><td>International-status Master's students</td><td>See our Coimbra guide</td></tr>
<tr><td>University of Porto merit &amp; scientific scholarships</td><td>Bachelor's to PhD</td><td>Merit awards and research scholarships (not means-tested aid)</td><td>Enrolled U.Porto students</td><td>Programme-specific</td></tr>
<tr><td>University of Porto international admission</td><td>Bachelor's, Integrated Master's</td><td>A study place, not funding; fees &euro;3,500&ndash;&euro;16,500</td><td>Non-EU international applicants</td><td>2026/27 rounds closed</td></tr>
<tr><td>DGES social-action grant</td><td>Bachelor's, Master's</td><td>Means-tested living grant</td><td>Not international-statute students (see below)</td><td>Annual</td></tr>
<tr><td>"Portugal Government Scholarship 2026"</td><td>&mdash;</td><td>Nothing &mdash; DGES says it is not a State programme</td><td>No one</td><td>False information</td></tr>
</tbody>
</table></div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/portugal-scholarships-students.jpg" alt="Portugal scholarships 2026/27 poster with an international student and the Belem Tower in Lisbon" width="1200" height="628" loading="lazy">
<figcaption>The posters say "Scholarships 2026/27". The genuine funded route is the FCT PhD studentship; bachelor's and master's students mostly pay fees and chase university merit awards.</figcaption>
</figure>

<h2>Is There a "Portugal Government Scholarship 2026"?</h2>
<p>No. This is the single most important line in the guide. DGES - the <em>Direc&ccedil;&atilde;o-Geral do Ensino Superior</em>, Portugal's Directorate-General for Higher Education - published a notice on <strong>20 November 2025</strong> headed "false information about scholarship programmes". It says that information circulating on digital platforms, often titled "Portugal Government Scholarships 2026" and claiming fully funded scholarships for citizens of all nationalities, <strong>"is false and does not correspond to any scholarship programme promoted by the Portuguese State"</strong>. It asks candidates to rely only on official DGES and institution channels.</p>
<p>So treat any page, poster or "apply" button that promises a blanket Portuguese government scholarship - including the private-provider link on one of these posters - as marketing at best. The real money is in the specific routes below.</p>

<h2>Erasmus Mundus: The Fully Funded Master's Route</h2>
<p>This is the one to look at first if you want a Master's and cannot pay for it. An Erasmus Mundus Joint Master is run by a group of at least three universities in three different countries, so you study in more than one, and many include a Portuguese partner. You can filter for Portugal in the official catalogue.</p>
<p>The European Commission says the programmes offer <strong>full scholarships to the best-ranked students worldwide</strong>, covering your participation costs and contributing to travel, visa and a living allowance. Most scholarship sites quote <strong>&euro;1,400 a month</strong>. We could not find that figure on the official pages we read, so check it in the programme's own call.</p>
<p>Three things worth knowing before you apply:</p>
<ul>
<li><strong>You apply to each programme, not to the EU.</strong> Most deadlines fall between October and January for the following year's start.</li>
<li><strong>You only get one.</strong> Anyone who has already held an Erasmus Mundus scholarship cannot get another.</li>
<li><strong>Nationality is capped.</strong> No more than 10% of a programme's scholarships should go to one nationality, so strong applicants from Pakistan or India compete with each other for a limited share.</li>
</ul>
<p>Official pages: <a href="https://erasmus-plus.ec.europa.eu/opportunities/opportunities-for-individuals/students/erasmus-mundus-joint-masters" target="_blank" rel="noopener">Erasmus Mundus Joint Masters</a> and the <a href="https://www.eacea.ec.europa.eu/scholarships/erasmus-mundus-catalogue_en" target="_blank" rel="noopener">programme catalogue</a>.</p>

<h2>FCT PhD Studentships: Portugal's Genuine Government Funding</h2>
<p>The clearest state-funded opportunity is the PhD studentship from the <strong>Funda&ccedil;&atilde;o para a Ci&ecirc;ncia e a Tecnologia (FCT)</strong>, Portugal's national research funder. It supports research leading to a doctoral degree, across every scientific area, and it is open to international applicants.</p>

<div class="scholar-table"><table>
<tbody>
<tr><th>Programme</th><td>FCT PhD Studentships 2026 (general call)</td></tr>
<tr><th>Level</th><td>PhD / doctorate</td></tr>
<tr><th>Areas</th><td>All scientific areas</td></tr>
<tr><th>International applicants</th><td>Eligible, including third-country citizens, subject to the full call rules</td></tr>
<tr><th>Studentships in 2026</th><td>1,600 planned, including 600 in non-academic environments</td></tr>
<tr><th>Monthly allowance</th><td>&euro;1,359.64 (2026 FCT table, doctoral category in Portugal)</td></tr>
<tr><th>Apply through</th><td>The myFCT portal</td></tr>
<tr><th>2026 deadline</th><td>31 March 2026, 5:00 pm Lisbon time (call opened 2 March 2026)</td></tr>
<tr><th>Results</th><td>Provisional 31 July 2026; final expected early November 2026</td></tr>
<tr><th>Status</th><td>2026 application period closed</td></tr>
</tbody>
</table></div>

<h3>What the studentship pays</h3>
<p>An FCT PhD studentship is a funded research position, not a fee to be paid. It includes:</p>
<ul>
<li>A <strong>monthly maintenance allowance</strong> of <strong>&euro;1,359.64</strong> for a doctoral researcher based in Portugal, under FCT's 2026 allowance table (higher, &euro;2,168.65, for approved periods abroad).</li>
<li><strong>Tuition and registration fees</strong>, paid by FCT directly to the host institution.</li>
<li><strong>Personal accident insurance</strong> and, where the rules apply, voluntary social-security contributions borne by FCT under the Research Fellowship Holder Statute.</li>
<li>Support for <strong>training, and for presenting research</strong> at scientific meetings, with travel and installation help where applicable.</li>
</ul>
<p>Beware secondary sites still quoting the older <strong>&euro;1,259.64</strong> figure: the official 2026 table sets the doctoral allowance at &euro;1,359.64. Which extras apply depends on the terms of your specific studentship, so read the call and the regulation before you budget.</p>

<h3>The 2026 timeline</h3>
<p>The general 2026 call opened on <strong>2 March 2026</strong> and closed on <strong>31 March 2026 at 5:00 pm</strong> Lisbon time, with <strong>provisional results on 31 July 2026</strong> and final results expected in <strong>early November 2026</strong>. There was a separate line for research carried out partly in <strong>non-academic institutions</strong> - companies, public bodies, health, cultural or social organisations - which required at least 12 months of the studentship in the same non-academic host in Portugal, and drew a record <strong>833 applications</strong> for its 600 awards. If the pattern holds, the 2027 call should open in the first quarter of 2027; watch the FCT site and myFCT.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/portugal-scholarships-lisbon.jpg" alt="International students in Portugal with the FCT PhD studentship and University of Porto details" width="1200" height="628" loading="lazy">
<figcaption>FCT PhD studentships are the closest thing to a "fully funded Portugal scholarship" - and they are for doctoral research, not bachelor's or master's study.</figcaption>
</figure>

<h2>University of Porto for International Students</h2>
<p>The University of Porto (U.Porto) runs the clearest admission route for international bachelor's and integrated-master's applicants, through its <strong>Special Call for international students</strong>. For 2026/27 it opened <strong>712 places</strong> across most of its first-cycle and integrated-master's courses.</p>

<h3>The 2026/27 application phases</h3>
<div class="scholar-table"><table>
<thead><tr><th>Phase</th><th>Dates (2026)</th><th>Note</th></tr></thead>
<tbody>
<tr><td>Phase 1</td><td>2 January &ndash; 6 February 2026</td><td>Held most of the places (489 of 712)</td></tr>
<tr><td>Phase 2</td><td>9 February &ndash; 2 April 2026</td><td>&mdash;</td></tr>
<tr><td>Phase 3</td><td>2 June &ndash; 22 July 2026</td><td>Some faculties run their own calendars</td></tr>
</tbody>
</table></div>
<p>Faculties including Sciences (FCUP), Engineering (FEUP), Medicine (FMUP), Sport (FADEUP), Economics (FEP) and ICBAS set their own application calendars, so the general phases are a guide, not a rule. All three 2026/27 phases have now passed.</p>

<h3>What it costs</h3>
<ul>
<li>International-student tuition for bachelor's and integrated master's runs from about <strong>&euro;3,500 to &euro;16,500 a year</strong>, depending on the faculty and programme.</li>
<li>The <strong>Faculty of Sciences</strong> lists a full-time international bachelor's fee of <strong>&euro;3,810 a year</strong> for 2026/27, for example.</li>
<li>Citizens of <strong>CPLP</strong> (Portuguese-speaking) countries can qualify for a reduction of up to <strong>45%</strong> on the international fee.</li>
</ul>

<h3>Scholarships at U.Porto</h3>
<p>U.Porto does offer funding, but be clear about which kind:</p>
<ul>
<li><strong>Merit scholarships</strong>, awarded for the best academic results regardless of a student's financial situation.</li>
<li><strong>Scientific scholarships</strong>, for undergraduate research and other research work.</li>
<li>Some faculties add their own <strong>incentives by admission ranking</strong>.</li>
<li><strong>SASUP social-support scholarships exclude international-statute students.</strong> The university states plainly that "students with the International Student Status are not eligible for these scholarships"; SASUP aid is limited to EU nationals, stateless persons and nationals of countries with reciprocal cooperation protocols.</li>
</ul>
<h2>Aveiro, Coimbra and Minho</h2>
<p><strong>University of Aveiro.</strong> Its 2026/27 international fee is set by subject: <strong>&euro;5,000</strong> for arts, sciences, engineering and health, <strong>&euro;4,150</strong> for economics, management, maths and education, and <strong>&euro;3,500</strong> for humanities and social sciences. Those fees sit alongside an incentive scheme for international Bachelor's and Master's students under Regulation 950/2021. Many sites say it brings the fee down to &euro;1,000 for students with a grade of 16 out of 20, and that may well be right, but they disagree on the details and we could not read the regulation itself. Ask Aveiro in writing before you budget on it. Source: <a href="https://api-portal.ua.pt/api/v1/file/90553" target="_blank" rel="noopener">University of Aveiro: 2026/27 fees</a>.</p>
<p><strong>University of Coimbra.</strong> Coimbra's awards are fee discounts rather than living money. International Master's students can get up to &euro;2,000 a year off; our <a href="/scholarships/university-of-coimbra-masters-scholarship">University of Coimbra Master's scholarship guide</a> has the details.</p>
<p><strong>University of Minho.</strong> Minho lists academic merit and excellence scholarships on its student support pages. These reward results once you are already studying there, so treat them as a bonus rather than a way to pay for the degree.</p>

<h2>Can Pakistani Students Apply for Portugal Scholarships?</h2>
<p>Yes - Pakistani students can apply to Portuguese university programmes and to FCT PhD funding where they meet the rules. But being an international student does <strong>not</strong> unlock Portugal's means-tested government grant. DGES is explicit: students who enter higher education under the <strong>international-student regime do not have access to direct social support</strong>, only to indirect social action, unless they hold the humanitarian-emergency student statute.</p>
<p>The DGES social-action grant reaches a third-country national only through <strong>permanent residence, long-term-resident status, or a cooperation or equal-treatment agreement</strong> with Portugal - that is, when they are settled in Portugal and not enrolled under the international-student regime at all. So a student arriving directly from Pakistan should plan around university fees, merit awards and, for research, the FCT route - not around a general "government grant".</p>

<h2>Tuition, and the "Study in Portugal for Free" Myth</h2>
<p>EU/EEA students pay Portugal's state-regulated fee, which is modest - commonly a few hundred euros a year for a bachelor's or integrated master's. <strong>International-statute (non-EU) students pay a separate, institution-set fee</strong> that is not capped by the State and is usually much higher: at Porto, &euro;3,500 to &euro;16,500 a year, with specialised programmes higher still. Portugal is affordable by European standards, but it is not free for international students, and no scholarship changes that unless its own page says it covers tuition.</p>

<h2>Do You Need IELTS to Study in Portugal?</h2>
<p>There is <strong>no single IELTS rule</strong> for Portugal. It depends on the university, the programme and the language it is taught in:</p>
<ul>
<li><strong>English-taught programmes</strong> set their own English requirement - often around IELTS 5.5 to 6.5, higher for master's and PhD - and many accept <strong>alternatives</strong> (TOEFL, PTE, Cambridge, Duolingo) or a <strong>medium-of-instruction certificate</strong> from a previous English-taught degree.</li>
<li><strong>Portuguese-taught programmes</strong> ask for Portuguese instead, usually around B2.</li>
</ul>
<p>So "study in Portugal without IELTS" is sometimes true and sometimes not. Check the language rule on your own programme page before you assume you can skip a test.</p>

<h2>The Portugal Student Visa From Pakistan</h2>
<p>A degree needs a long-stay D4 study visa. Pakistani applicants go through VFS Global for the Embassy of Portugal in Islamabad. One detail is useful if you win funding: the VFS checklist says someone holding a study or research grant is exempt from the proof of admission and the proof of sufficient means of subsistence, provided they tell the consular post. Everyone else shows a bank statement for the last three months. That checklist still mentions SEF, the old border agency, so check the current version and fee with VFS before you book. Source: <a href="https://www.vfsglobal.com/one-pager/portugal/pakistan/english/pdf/checklist_PT_national_residency_study_research_internship.pdf" target="_blank" rel="noopener">VFS Global: Portugal study visa checklist, Pakistan</a>.</p>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Pick your level and a real programme</strong> at a Portuguese university, and open its official page - not a third-party "scholarship" site.</li>
<li><strong>Check the route.</strong> For a PhD with funding, look at the FCT studentship through myFCT and a host institution. For a bachelor's or master's, apply through the university's international admission, such as U.Porto's Special Call.</li>
<li><strong>Read the eligibility and language rules</strong> for that programme - nationality, qualifications, English or Portuguese proof.</li>
<li><strong>Budget for the real fee</strong>, and check whether any merit or scientific scholarship is open to you as an enrolled student.</li>
<li><strong>Prepare your documents:</strong> passport, certificates and transcripts, CV, motivation letter, references, a research proposal for PhD applications, and any language certificate required.</li>
<li><strong>Apply through the official portal</strong> - the university's system, or myFCT for FCT funding - and track your email and application account for decisions and document requests.</li>
</ol>

<div class="scholar-note">
<p><strong>On application fees.</strong> There is no single "Portugal scholarship" fee. FCT studentships and university scholarships each set their own rules, and university <em>admission</em> can carry its own separate charge. Check the fee on the official application page for your specific route before you pay anything to anyone else.</p>
</div>

<h2>Portugal, France or Italy?</h2>
<p>Portugal works best if you can fund your own fees and chase a merit award, or if you are aiming at a funded FCT PhD. If you want a government stipend, France's Eiffel programme pays a monthly allowance but leaves tuition to the institution - see our <a href="/scholarships/france-scholarships-without-ielts">France scholarships without IELTS guide</a>. Italy asks you to fund yourself, then removes tuition and adds a regional living grant if your family income is low; read our <a href="/scholarships/university-of-pavia-scholarships">University of Pavia scholarships guide</a>, <a href="/scholarships/university-of-insubria-scholarships">University of Insubria scholarships guide</a> and <a href="/scholarships/polytechnic-university-of-marche-scholarships">Polytechnic University of Marche scholarships guide</a> for how that works in practice.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is there a fully funded Portugal government scholarship for all international students in 2026?</h3>
<p>No. DGES issued a notice on 20 November 2025 that the "Portugal Government Scholarships 2026" circulating online is false and does not correspond to any programme promoted by the Portuguese State. The genuine funded route is the FCT PhD studentship; bachelor's and master's students mostly pay fees and compete for university merit awards.</p>

<h3>Can Pakistani students get a fully funded scholarship in Portugal?</h3>
<p>Yes, but through Erasmus Mundus for a Master's or FCT for a PhD, not through a Portuguese government grant. Both are competitive, and neither is automatic.</p>

<h3>Can international students get a Portuguese government grant?</h3>
<p>Not under the international-student regime. Those students have access only to indirect social action, unless they hold the humanitarian-emergency student statute. The direct means-tested grant reaches a third-country national only through permanent residence, long-term-resident status or a cooperation agreement.</p>

<h3>Is it free to study in Portugal?</h3>
<p>No. EU/EEA students pay a modest state-regulated fee, but international (non-EU) students pay institution-set fees, at the University of Porto about &euro;3,500 to &euro;16,500 a year, depending on the programme.</p>

<h3>Do I need IELTS to study in Portugal?</h3>
<p>Not always. English-taught programmes set their own English requirement and often accept alternatives or a medium-of-instruction exemption, while Portuguese-taught programmes ask for Portuguese, usually around B2. Check your own programme page.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">DGES: international students information</a></li>
<li><a href="https://www.dges.gov.pt/en/pagina/grants-general-information" target="_blank" rel="noopener">DGES: grants and social-action support</a></li>
<li><a href="https://www.dges.gov.pt/pt/noticia/disseminacao-de-informacao-falsa-sobre-programa-de-atribuicao-de-bolsas-spread-false" target="_blank" rel="noopener">DGES: notice on false scholarship information (20 November 2025)</a></li>
<li><a href="https://erasmus-plus.ec.europa.eu/opportunities/opportunities-for-individuals/students/erasmus-mundus-joint-masters" target="_blank" rel="noopener">European Commission: Erasmus Mundus Joint Masters</a></li>
<li><a href="https://www.fct.pt/en/" target="_blank" rel="noopener">FCT: PhD studentships</a></li>
<li><a href="https://myfct.fct.pt/" target="_blank" rel="noopener">myFCT: FCT application portal</a></li>
<li><a href="https://www.up.pt/portal/en/study/international-students/special-call-for-applications/" target="_blank" rel="noopener">University of Porto: Special Call for international students</a></li>
</ul>

<p><em>JobGader is not part of DGES, FCT, any university or the Portuguese government. Last checked against the official pages named here on 30 October 2026. Amounts, calls and deadlines change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
