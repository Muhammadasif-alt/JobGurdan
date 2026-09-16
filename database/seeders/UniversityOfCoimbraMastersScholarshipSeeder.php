<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * University of Coimbra "Incentive Scholarship for Master's Degree Research and
 * Scientific Production" - a EUR 2,000 award for international-status students
 * on a UC Master's - written as an A-to-Z guide for international applicants.
 *
 * Checked on 16 September 2026 against the University of Coimbra's own pages:
 * the scholarship page, the 2026/27 Master's course list, the international
 * tuition page, the Inforestudante admission notices and the ISR "Living in
 * Coimbra" figures. The owner's brief was accurate; the corrections here are
 * about framing, not facts:
 *
 * 1. This is a University of Coimbra award, NOT a nationwide "Portugal
 *    Government Scholarship". DGES has warned that no such blanket government
 *    scholarship exists; UC lists this EUR 2,000 incentive among its own
 *    scholarships for international students.
 * 2. There is no single Master's tuition figure. UC says to read each
 *    programme's own opening notice; one 2026/27 notice lists EUR 7,000 a year
 *    for international students, so the exact course must be named first.
 * 3. The ISR living figures (about EUR 648 a month single-person, EUR 300-500
 *    for a T1) are indicative city costs, not a university fee or a scholarship
 *    allowance.
 * 4. "No IELTS" is programme-dependent; UC sets no single English rule.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class UniversityOfCoimbraMastersScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-coimbra-masters-scholarship';

    public const APPLY_URL = 'https://www.uc.pt/en/academic-services/awards-scholarships-uc/incentive-masters-research/';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Coimbra €2,000 Master\'s Scholarship for International Students',
                'provider' => 'University of Coimbra',
                'country' => 'Portugal',
                'city' => 'Coimbra',
                'study_level' => "Master's",
                'funding_type' => 'Up to EUR 2,000 fee exemption',
                'award_value' => 'Up to €2,000/yr tuition-fee exemption',
                'deadline' => null,
                'deadline_note' => "Tied to UC Master's admission and enrolment; check the programme's 2026/27 opening notice for its dates.",
                'excerpt' => 'The University of Coimbra gives international Master\'s students a tuition-fee exemption of up to EUR 2,000, scaled by admission merit - a UC award, not a nationwide "Portugal Government Scholarship". Here is who qualifies.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => "University of Coimbra Master's Scholarship 2026: EUR 2,000",
                'meta_description' => 'The University of Coimbra gives international Master\'s students a tuition-fee exemption of up to EUR 2,000. Eligibility, fees and how to apply.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-16 07:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>University of Coimbra</strong> (Universidade de Coimbra, UC) is one of Europe's oldest universities and a UNESCO World Heritage site. For international master's students it runs a specific award, the <strong>Incentive Scholarship for Master's Degree Research and Scientific Production</strong>, a tuition-fee exemption worth <strong>up to &euro;2,000</strong> a year. This guide covers the scholarship A to Z - who qualifies, the eligible programmes, admission and language rules, real tuition and living costs, the visa money proof, documents, the application steps and the deadlines - using only UC's official pages.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Read this first:</strong></p>
<ul>
<li><strong>This is a University of Coimbra award, not a "Portugal Government Scholarship".</strong> DGES has warned that no blanket government scholarship exists for all nationalities; UC lists this &euro;2,000 incentive among <em>its own</em> scholarships.</li>
<li><strong>It is a tuition-fee exemption of up to &euro;2,000 a year, not a cash stipend.</strong> The amount is scaled by your admission score, and you still pay the rest of your fee and your living costs.</li>
<li><strong>UC's standard international tuition is typically about &euro;7,000 a year</strong>, but it varies by course - always check your programme's opening notice for the exact figure.</li>
<li><strong>"No IELTS" depends on the programme.</strong> UC sets no single English rule; each Master's states its own language requirement.</li>
</ul>
</div>

<h2>Coimbra €2,000 Master's Scholarship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>University of Coimbra (Universidade de Coimbra), Portugal</td></tr>
<tr><th>Scholarship</th><td>Incentive Scholarship for Master's Degree Research and Scientific Production</td></tr>
<tr><th>Level</th><td>Master's</td></tr>
<tr><th>Who</th><td>Students with international-student status admitted to a UC Master's and enrolled full-time on an annual basis</td></tr>
<tr><th>Amount</th><td><strong>Up to &euro;2,000</strong> a year off tuition (fee exemption), scaled by admission score; +&euro;1,000 top-ups possible</td></tr>
<tr><th>Duration</th><td>Each year of the programme's normal full-time duration</td></tr>
<tr><th>Application fee</th><td>Programme-specific; many UC applications show <strong>&euro;50</strong></td></tr>
<tr><th>Tuition</th><td>Set per Master's programme; check the opening notice (example: &euro;7,000 a year)</td></tr>
<tr><th>Living cost</th><td>About &euro;648 a month for one person (UC ISR indicative figure)</td></tr>
<tr><th>Language</th><td>Depends on the programme; no single UC-wide IELTS rule</td></tr>
<tr><th>Apply through</th><td>UC's Inforestudante application system</td></tr>
</tbody>
</table></div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-coimbra-masters-scholarship-coimbra.jpg" alt="University of Coimbra 2,000 euro Master's scholarship for international students, with the historic Coimbra campus" width="1200" height="628" loading="lazy">
<figcaption>The University of Coimbra's up-to-&euro;2,000 tuition-fee exemption is a UC scholarship for international master's students - not a nationwide "Portugal Government Scholarship".</figcaption>
</figure>

<h2>What the Scholarship Is</h2>
<p>The award is UC's own <strong>Incentive Scholarship for Master's Degree Research and Scientific Production</strong> (UC also labels it the Scholarship for the Promotion of Scientific Research and Output for Master's programmes). Students who hold <strong>international-student status</strong>, gain admission to a UC <strong>Master's programme</strong> and <strong>enrol full-time on an annual basis</strong> fall into the eligible category. The "research and scientific production" in the name is the scheme's <em>purpose</em> - encouraging qualified research and internationalisation - <strong>not a portfolio of publications you must already have</strong> to qualify.</p>
<p>It works as a <strong>tuition-fee exemption of up to &euro;2,000 a year</strong>, not a cash grant. The exact amount is scaled by your <strong>admission score</strong>: where the score is <strong>160 points or more</strong>, the exemption is between <strong>&euro;1,000 and &euro;2,000</strong>. Two further &euro;1,000 top-ups can apply - one for students from <strong>Portuguese-speaking (CPLP) countries</strong> and a <strong>continuity</strong> component - subject to the scholarship's rules. It is not full funding and not a living stipend; it reduces what you pay in tuition.</p>

<h2>Eligible Programmes</h2>
<p>The scholarship is tied to UC <strong>Master's programmes</strong>, which run across the university's faculties and schools. The 2026/27 Master's admissions cover, among others:</p>
<ul>
<li>Arts and Humanities</li>
<li>Law</li>
<li>Medicine</li>
<li>Sciences and Technology</li>
<li>Pharmacy</li>
<li>Economics</li>
<li>Psychology and Educational Sciences</li>
<li>Sport Sciences and Physical Education</li>
<li>College of Arts</li>
<li>Nursing (Coimbra Nursing School)</li>
</ul>
<p>Each programme sets its own admission requirements, selection criteria, language of instruction, tuition and calendar, so choose the exact Master's first, then read its opening notice.</p>

<h2>Admission Requirements</h2>
<p>Requirements vary by programme, but a UC Master's application typically needs:</p>
<ul>
<li>A completed <strong>bachelor's degree</strong> (or recognised equivalent) in a relevant field.</li>
<li>Your <strong>academic transcripts</strong> and grades, which feed the programme's ranking and selection.</li>
<li>A <strong>motivation letter</strong> - a 2026/27 UC admission notice explicitly requires one from foreign applicants, along with an identification document.</li>
<li>Proof of <strong>language ability</strong> where the programme requires it.</li>
</ul>

<h2>Tuition Fees</h2>
<p>UC sets a <strong>standard international-student tuition fee, typically about &euro;7,000 a year</strong> for most Master's programmes, but it <strong>varies by course</strong> - the integrated Master's in Medicine, for example, is far higher (around &euro;18,000). UC advises checking the <strong>opening notice, course page or payment plan</strong> of your specific Master's for the exact figure, so confirm it there rather than assuming &euro;7,000. (The &euro;7,000 rate is documented for 2025/26.)</p>
<p>On top of tuition, UC Master's applications usually carry an <strong>application fee, commonly &euro;50</strong>, charged per application through Inforestudante and payable before the deadline.</p>

<h2>Scholarship Amount and Duration</h2>
<ul>
<li><strong>Amount:</strong> up to &euro;2,000 a year off tuition, scaled by your admission score (&euro;1,000 to &euro;2,000 where the score is 160 points or more), with possible &euro;1,000 top-ups for CPLP students and for continuity.</li>
<li><strong>Duration:</strong> applied each academic year or semester until you finish the programme, over its normal full-time duration - not a one-off payment.</li>
<li><strong>What it does not cover:</strong> the rest of your tuition beyond the exemption, plus living costs, accommodation, insurance and travel.</li>
</ul>

<h2>Living and Accommodation Costs in Coimbra</h2>
<p>Coimbra is one of Portugal's more affordable university cities. The University of Coimbra's Institute for Systems and Robotics gives these <strong>indicative</strong> figures on its "Living in Coimbra" page:</p>
<div class="scholar-table"><table>
<thead><tr><th>Item</th><th>Indicative monthly cost</th></tr></thead>
<tbody>
<tr><td>Single-person living cost</td><td>About &euro;648 a month</td></tr>
<tr><td>T1 apartment (one-bedroom)</td><td>About &euro;300&ndash;&euro;500 a month</td></tr>
<tr><td>3-bedroom apartment</td><td>About &euro;600&ndash;&euro;850 a month</td></tr>
</tbody>
</table></div>
<p>These are indicative city costs, <strong>not a guaranteed university fee or a scholarship allowance</strong>. UC also runs student residences and a Welcome Centre that helps international students find housing.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-coimbra-masters-scholarship-city.jpg" alt="International student in Coimbra by the Mondego river, University of Coimbra 2,000 euro Master's scholarship" width="1200" height="628" loading="lazy">
<figcaption>Coimbra is one of Portugal's more affordable university cities - about &euro;648 a month for one person on UC's own indicative figures.</figcaption>
</figure>

<h2>Visa and Financial Requirements</h2>
<p>Non-EU students who will study in Portugal for more than a few months need a <strong>national study visa (residence visa for study)</strong> from the Portuguese consulate, then a residence permit after arrival. Consulates typically ask for:</p>
<ul>
<li><strong>Proof of admission/enrolment</strong> at the University of Coimbra.</li>
<li><strong>Proof of sufficient means of subsistence</strong> for the stay - broadly the Portuguese guaranteed minimum monthly income for each month of the year - so a scholarship of &euro;2,000 will not by itself satisfy the money proof.</li>
<li><strong>Proof of accommodation</strong> in Coimbra.</li>
<li><strong>Valid health insurance</strong> and a clean <strong>criminal-record</strong> certificate.</li>
</ul>
<p>Confirm the exact amounts and document list with the Portuguese consulate for your country before you apply; they change year to year.</p>

<h2>IELTS and Language Requirements</h2>
<p>There is <strong>no single IELTS rule</strong> at Coimbra. It depends on the programme and the language it is taught in:</p>
<ul>
<li><strong>English-taught Master's</strong> set their own English requirement, typically around IELTS 6.0-6.5 or equivalent, and often accept alternatives (TOEFL, Cambridge, a medium-of-instruction certificate).</li>
<li><strong>Portuguese-taught Master's</strong> require Portuguese instead, usually a CAPLE level of about B1-B2. Much of UC's Master's offer is taught in Portuguese, so English-taught options are more limited.</li>
</ul>
<p>So "study at Coimbra without IELTS" is true for some programmes and not others. Check the language line on your chosen Master's before you assume you can skip a test.</p>

<h2>Documents You May Need</h2>
<ul>
<li>Passport or national identification document</li>
<li>Bachelor's degree certificate (recognised/legalised where required)</li>
<li>Academic transcripts</li>
<li>Motivation letter</li>
<li>CV / r&eacute;sum&eacute;</li>
<li>Language certificate, where the programme requires it</li>
<li>Any programme-specific documents named in the opening notice</li>
</ul>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Choose a UC Master's programme</strong> for 2026/27 and open its official course page and opening notice.</li>
<li><strong>Check eligibility and language rules</strong> for that programme, and confirm the tuition figure in its notice.</li>
<li><strong>Apply through Inforestudante</strong>, UC's official application system, and upload your documents.</li>
<li><strong>Pay the application fee</strong> (commonly &euro;50) before the deadline - an unpaid fee means an unassessed application.</li>
<li><strong>Accept your place and enrol full-time</strong> on an annual basis; this is what puts you in the eligible category for the &euro;2,000 incentive.</li>
<li><strong>Follow the scholarship rules</strong> on the UC scholarship page for how the incentive is applied to your tuition.</li>
</ol>

<h2>Deadlines</h2>
<p>Deadlines are <strong>set per programme</strong>, not once for the whole university. UC runs its 2026/27 Master's admissions in phases across its faculties, and several faculties keep their own calendars. Read the opening notice for your chosen Master's for the exact application window, and apply early - selection is competitive and the application fee must clear before the window closes.</p>

<h2>One Correction Worth Repeating</h2>
<p>Do not search for, or trust, a "Portugal Government Scholarship &euro;2,000". Write it and think of it as the <strong>University of Coimbra &euro;2,000 Master's Scholarship for international students</strong>, because that is what it is - a UC award listed on the university's own scholarships page, not a nationwide government grant.</p>

<h2>Coimbra, Porto or the Rest of Europe?</h2>
<p>Coimbra's up-to-&euro;2,000 exemption trims tuition on a specific Master's; it is not full funding. For the wider Portuguese picture - including the FCT PhD studentships that are the country's only real government funding - see our <a href="/scholarships/portugal-scholarships">Portugal scholarships guide</a>. If you want a government stipend elsewhere, France's Eiffel programme pays a monthly allowance; read our <a href="/scholarships/france-scholarships-without-ielts">France scholarships without IELTS guide</a>. And for low-income tuition waivers in Italy, see our <a href="/scholarships/university-of-pavia-scholarships">University of Pavia scholarships guide</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much is the University of Coimbra Master's scholarship?</h3>
<p>Up to &euro;2,000 a year, applied as a tuition-fee exemption rather than a cash grant. The amount is scaled by your admission score (&euro;1,000 to &euro;2,000 where it is 160 points or more), with possible &euro;1,000 top-ups for CPLP students and for continuity, and it recurs over the programme's normal duration. It is a University of Coimbra award, not a national government scholarship.</p>

<h3>Who is eligible for the Coimbra €2,000 scholarship?</h3>
<p>Students who hold international-student status, are admitted to a UC Master's programme and enrol full-time on an annual basis fall into the eligible category. You do not need existing research or publications - the "scientific production" in the name is the scheme's purpose, and the concrete merit factor is your admission score.</p>

<h3>Is this a Portugal Government Scholarship?</h3>
<p>No. It is a University of Coimbra scholarship. DGES has warned that a blanket "Portugal Government Scholarship" open to all nationalities does not exist; UC lists this &euro;2,000 incentive among its own scholarships for international students.</p>

<h3>How much is tuition for a Master's at Coimbra?</h3>
<p>UC's standard international fee is typically about &euro;7,000 a year, but it varies by course (the integrated Master's in Medicine is far higher), so check your programme's opening notice for the exact figure. Applications also usually carry a fee of about &euro;50.</p>

<h3>What are living costs in Coimbra?</h3>
<p>The University of Coimbra's ISR gives indicative figures of about &euro;648 a month for one person, &euro;300&ndash;&euro;500 for a T1 apartment and &euro;600&ndash;&euro;850 for a three-bedroom flat. These are city costs, not a university charge or a scholarship allowance.</p>

<h3>Do I need IELTS to study at Coimbra?</h3>
<p>Not always. UC sets no single English rule; each Master's states its own language requirement, and English-taught programmes often accept alternatives. Portuguese-taught programmes ask for Portuguese instead.</p>

<h3>How do I apply for the scholarship?</h3>
<p>Apply for a UC Master's through Inforestudante, pay the application fee, accept your place and enrol full-time on an annual basis. Enrolling in that way is what puts you in the eligible category for the &euro;2,000 incentive; follow the scholarship rules on the UC page.</p>

<h3>What is the deadline?</h3>
<p>There is no single university-wide deadline. Each Master's programme sets its own application window in its opening notice, and some faculties run their own calendars, so check the notice for your chosen course and apply early.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">University of Coimbra: the up-to-&euro;2,000 Master's incentive scholarship</a></li>
<li><a href="https://www.uc.pt/international-applicants/oportunidades/funding/ei" target="_blank" rel="noopener">University of Coimbra: scholarship conditions (funding page)</a></li>
<li><a href="https://www.uc.pt/en/applications/masters-degree-courses/" target="_blank" rel="noopener">University of Coimbra: 2026/27 Master's degree courses</a></li>
<li><a href="https://www.uc.pt/en/academic-services/tuition-fee/international/25-26-tuition-fee/" target="_blank" rel="noopener">University of Coimbra: international tuition information</a></li>
<li><a href="https://www.isr.uc.pt/living-in-coimbra" target="_blank" rel="noopener">University of Coimbra ISR: living in Coimbra</a></li>
<li><a href="https://www.uc.pt/en/academic-services/awards-scholarships-uc/" target="_blank" rel="noopener">University of Coimbra: awards and scholarships</a></li>
</ul>

<p><em>JobGader is not part of the University of Coimbra or the Portuguese government. This guide was checked against UC's official scholarship, admissions, tuition and living-cost pages on 16 September 2026. Amounts, fees and deadlines change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
