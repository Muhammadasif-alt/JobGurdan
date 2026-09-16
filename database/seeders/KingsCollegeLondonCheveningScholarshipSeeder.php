<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * King's College London Chevening Scholarship 2027/28 - the fully funded UK
 * government (FCDO) award for a one-year taught Master's, written A-to-Z with
 * KCL as the study destination, but making clear Chevening is UK-wide.
 *
 * Checked on 16 September 2026 against Chevening's official pages (eligibility,
 * what it covers, work experience, application timeline), King's College London
 * (Chevening, fees, living-expenses, English and how-to-apply pages) and
 * GOV.UK. The owner's brief was well sourced; the fixes here are about keeping
 * Chevening eligibility separate from KCL admission and pinning the dates and
 * amounts to the official pages.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class KingsCollegeLondonCheveningScholarshipSeeder extends Seeder
{
    public const SLUG = 'kings-college-london-chevening-scholarship';

    public const APPLY_URL = 'https://www.kcl.ac.uk/study/funding/funding-opportunities/chevening-scholarships';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => "King's College London Chevening Scholarship 2027/28 (Fully Funded UK Master's)",
                'provider' => "King's College London / UK FCDO (Chevening)",
                'country' => 'United Kingdom',
                'city' => 'London',
                'study_level' => "Master's",
                'funding_type' => 'Fully Funded',
                'award_value' => 'Tuition, living allowance, travel + allowances',
                'deadline' => '2026-10-06',
                'deadline_note' => 'Chevening 2027/28 closed 6 October 2026, 11:00 UTC; the next cycle usually opens in August.',
                'excerpt' => "The King's College London Chevening Scholarship 2027/28 is a fully funded UK government award - tuition, a monthly living allowance, travel and more - for a one-year taught Master's. Eligible citizens apply to Chevening by 6 October 2026.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'KCL Chevening Scholarship 2027/28: Fully Funded UK',
                'meta_description' => "King's College London Chevening Scholarship 2027/28: fully funded by the UK FCDO. Eligibility, work experience, fees and how to apply by 6 October 2026.",
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-16 09:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>King's College London Chevening Scholarship 2027/28</strong> is a fully funded route to a <strong>one-year taught Master's</strong> in the UK. <strong>Chevening</strong> is the UK government's international scholarship programme, run by the <strong>Foreign, Commonwealth &amp; Development Office (FCDO)</strong>, and King's College London (KCL) is one of the universities where a Chevening scholar can study. This guide covers it A to Z - what Chevening pays, who can apply, the all-important work-experience rule, English and admission at KCL, real fees and London living costs, the visa, documents, the two-part application and the deadlines - using only official Chevening, KCL and GOV.UK sources.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Read this first:</strong></p>
<ul>
<li><strong>The Chevening deadline is 6 October 2026 at 11:00 UTC</strong>; the 2027/28 cycle opened on <strong>4 August 2026</strong>.</li>
<li><strong>Chevening is not a KCL-only scholarship.</strong> You choose <strong>three</strong> eligible UK Master's courses (they need not all be at KCL) and must hold an unconditional offer from one by <strong>8 July 2027</strong>.</li>
<li><strong>Two separate processes.</strong> You must win the Chevening award <em>and</em> be admitted to your Master's - one does not grant the other.</li>
<li><strong>Work experience is required:</strong> at least <strong>2,800 hours</strong> after your undergraduate degree. And Chevening does not fund PhDs or distance learning.</li>
</ul>
</div>

<h2>KCL Chevening Scholarship 2027/28 at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>King's College London (one of the eligible UK universities)</td></tr>
<tr><th>Scholarship</th><td>Chevening Scholarship 2027/28 (UK government)</td></tr>
<tr><th>Funder</th><td>UK Foreign, Commonwealth &amp; Development Office (FCDO)</td></tr>
<tr><th>Level / mode</th><td>One-year taught Master's, full-time (2027/28)</td></tr>
<tr><th>Funding</th><td><strong>Fully funded:</strong> tuition, monthly living allowance, return economy travel and several allowances</td></tr>
<tr><th>Who</th><td>Citizens of Chevening-eligible countries/territories (160+)</td></tr>
<tr><th>Applications open</th><td>4 August 2026</td></tr>
<tr><th>Deadline</th><td><strong>6 October 2026, 11:00 UTC</strong></td></tr>
<tr><th>Interviews</th><td>March&ndash;April 2027</td></tr>
<tr><th>Unconditional offer by</th><td>8 July 2027, 17:00 BST</td></tr>
<tr><th>Study begins</th><td>September/October 2027</td></tr>
</tbody>
</table></div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/kings-college-london-chevening-scholarship-campus.jpg" alt="King's College London Chevening Scholarship 2027/28, fully funded UK Master's, with the KCL London campus" width="1200" height="628" loading="lazy">
<figcaption>Chevening is the UK government's scholarship - fully funded for a one-year Master's - with King's College London as one destination among many UK universities.</figcaption>
</figure>

<h2>What Is the Chevening Scholarship?</h2>
<p>Chevening is a UK government programme for applicants from eligible countries and territories who want a qualifying <strong>one-year taught Master's</strong> in the UK. It is administered by the FCDO and its global network of embassies and high commissions, and is open to applicants from <strong>more than 160 countries and territories</strong>. It targets people with leadership potential and a clear career plan who will return home and make a difference.</p>
<p>Crucially, <strong>Chevening is not exclusively a King's scholarship</strong>. You name <strong>three eligible UK Master's courses</strong> in your Chevening application, and you must eventually secure an <strong>unconditional offer</strong> from at least one. KCL can be one, two or all three of those choices.</p>

<h2>What Does the Chevening Scholarship Cover?</h2>
<p>Chevening describes its standard award as covering the major costs of studying in the UK, normally including:</p>
<ul>
<li>Tuition fees</li>
<li>Return economy travel to and from the UK</li>
<li>An arrival allowance</li>
<li>The UK visa/entry-clearance application cost</li>
<li>A departure allowance</li>
<li>A contribution toward required TB testing (up to &pound;75), where applicable</li>
<li>A travel top-up allowance</li>
<li>A monthly personal living (stipend) allowance</li>
</ul>
<p>The exact components are confirmed in your award documentation, and stipend rates are reviewed each year. Approved tuition is normally paid through the scholarship rather than by you.</p>

<div class="scholar-note">
<p><strong>The King's 20% top-up.</strong> KCL's Chevening partnership includes a <strong>20% contribution toward tuition fees from King's</strong>, on top of the Chevening award. Because KCL fees differ sharply by programme, always check your specific course's fee page.</p>
</div>

<h3>KCL tuition-fee examples</h3>
<p>These are <strong>2026/27</strong> international fees, shown to illustrate the range - not guaranteed 2027/28 figures:</p>
<div class="scholar-table"><table>
<thead><tr><th>KCL Master's (2026/27)</th><th>International fee</th></tr></thead>
<tbody>
<tr><td>International Management MSc</td><td>&pound;40,450</td></tr>
<tr><td>Intelligence &amp; International Security MA</td><td>&pound;40,450</td></tr>
<tr><td>Global Health, Social Justice and Public Policy MSc</td><td>&pound;33,850</td></tr>
<tr><td>Applied Statistical Modelling &amp; Health Informatics MSc</td><td>&pound;42,800</td></tr>
</tbody>
</table></div>
<p>KCL says postgraduate fees vary by course and can rise year on year, so confirm the 2027/28 fee on your course page.</p>

<h2>Monthly Living Stipend and London Costs</h2>
<p>Chevening pays a monthly living allowance, at a <strong>higher rate for the London area</strong> because of the cost of living; the exact rate is set in your award letter. KCL's own guidance suggests budgeting about <strong>&pound;1,770 a month</strong> for a reasonable standard of living in London, on top of tuition, with an indicative range of <strong>&pound;1,331 (lower) to &pound;2,342 (higher)</strong>. Its average monthly breakdown is roughly:</p>
<div class="scholar-table"><table>
<tbody>
<tr><th>Accommodation</th><td>About &pound;1,164</td></tr>
<tr><th>Food</th><td>About &pound;167</td></tr>
<tr><th>Travel</th><td>About &pound;173</td></tr>
<tr><th>Personal / leisure</th><td>About &pound;266</td></tr>
</tbody>
</table></div>
<p>These are guides, not guaranteed costs, and vary with accommodation and lifestyle.</p>

<h2>Who Can Apply for Chevening 2027/28?</h2>
<ul>
<li><strong>Citizenship:</strong> you must be a citizen of a Chevening-eligible country or territory - check your official country page, as eligibility is country-specific.</li>
<li><strong>Undergraduate degree:</strong> you must hold a degree that qualifies you for a UK Master's. Your course may set additional academic requirements.</li>
<li><strong>Work experience:</strong> at least <strong>2,800 hours</strong> of eligible work experience, gained <strong>after</strong> your undergraduate degree - Chevening treats this as roughly two years of full-time work at 35 hours a week.</li>
<li><strong>Return home:</strong> you must commit to returning to your country of award for <strong>at least two years</strong> after the scholarship ends.</li>
</ul>

<h3>What counts as work experience?</h3>
<p>Eligible experience includes full-time and part-time employment, voluntary work, and paid or unpaid internships, all gained <strong>after</strong> your undergraduate degree - for the 2027/28 cycle, that means a degree completed by <strong>October 2024</strong>. You can enter up to 15 employment periods in the application, so report your <strong>actual</strong> hours and dates rather than rounding everything up to full-time. Your experience does <strong>not</strong> have to match your chosen Master's - but if you are changing direction, explain the rationale and your transferable skills.</p>

<h2>Does Chevening Require IELTS?</h2>
<p><strong>Chevening removed its own English-language requirement in 2020</strong>, so there is no separate Chevening English test. That does <strong>not</strong> mean IELTS is never needed: <strong>your KCL course sets its own English requirement</strong>. KCL uses bands, for example:</p>
<ul>
<li><strong>Band B:</strong> IELTS 7.0 overall, at least 6.5 in each skill.</li>
<li><strong>Band C:</strong> IELTS 7.0 overall, 6.5 in reading and writing, 6.0 in listening and speaking.</li>
<li><strong>Band D:</strong> IELTS 6.5 overall, at least 6.0 in each skill.</li>
</ul>
<p>Requirements vary by programme, so check your exact KCL course page before booking a test.</p>

<h2>Eligible Master's Courses at King's</h2>
<p>Chevening funds <strong>eligible one-year taught Master's</strong> programmes, not undergraduate degrees or PhDs. KCL offers Master's across business, economics, law, international affairs, politics, social sciences, informatics, engineering, natural sciences, health and medicine, psychology, and arts and humanities. But not every KCL programme is Chevening-eligible. Before you choose, confirm the course is a taught Master's, is one year where required, appears in the Chevening course finder, and that you meet KCL's academic and English requirements.</p>

<h2>KCL Admission Requirements</h2>
<p>Chevening eligibility and KCL admission are <strong>separate</strong>: even a strong Chevening candidate must still be academically admissible. KCL sets entry requirements <strong>by course</strong>, which may include a particular degree classification and subject, English evidence, a personal statement, references, a CV, a portfolio, or professional/ATAS requirements. For example, KCL's TESOL MA normally asks for a relevant 2:1 (or an equivalent qualification/experience) plus its own English requirement. So never assume a single entry standard across KCL.</p>

<h2>Documents You May Need</h2>
<p>The exact list depends on both Chevening and your course. For the <strong>Chevening application</strong>, prepare your passport and citizenship details, education history, work-experience history, three course choices, leadership and influencing examples, a career plan and references. For <strong>KCL admission</strong>, expect a passport, bachelor's certificate, transcripts, an English test result where required, a personal statement, references, a CV and any portfolio or professional documents. Submit as much as possible up front - missing documents delay assessment.</p>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Check Chevening eligibility</strong> - citizenship, undergraduate degree, the 2,800-hour work-experience rule and the return-home commitment.</li>
<li><strong>Choose three eligible UK Master's courses</strong> that fit your background and career plan; they need not all be at KCL.</li>
<li><strong>Submit the Chevening application</strong> before 6 October 2026 at 11:00 UTC - the online system closes at the deadline.</li>
<li><strong>Apply to KCL</strong> for your chosen Master's through King's Apply; do not wait for the Chevening result, as courses have their own deadlines and can fill.</li>
<li><strong>Interview</strong> if shortlisted, at a British embassy or high commission, in March&ndash;April 2027.</li>
<li><strong>Secure an unconditional offer</strong> from one of your three courses by 8 July 2027, 17:00 BST, then begin study in September/October 2027.</li>
</ol>

<h2>Chevening 2027/28 Timeline</h2>
<div class="scholar-table"><table>
<thead><tr><th>Date</th><th>Stage</th></tr></thead>
<tbody>
<tr><td>4 August 2026</td><td>Applications open</td></tr>
<tr><td>6 October 2026, 11:00 UTC</td><td>Applications close</td></tr>
<tr><td>Mid-February 2027</td><td>Interview shortlist</td></tr>
<tr><td>March&ndash;April 2027</td><td>Interviews</td></tr>
<tr><td>Mid-June 2027</td><td>Results begin</td></tr>
<tr><td>8 July 2027, 17:00 BST</td><td>Unconditional UK offer deadline</td></tr>
<tr><td>September/October 2027</td><td>Studies begin</td></tr>
</tbody>
</table></div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/kings-college-london-chevening-scholarship-london.jpg" alt="A Chevening scholar in London near King's College London, fully funded UK Master's scholarship 2027/28" width="1200" height="628" loading="lazy">
<figcaption>London stipend rates are higher because of the cost of living; KCL suggests budgeting about &pound;1,770 a month on top of tuition.</figcaption>
</figure>

<h2>KCL Application Fee and the UK Visa</h2>
<p>Most KCL taught Master's carry a <strong>non-refundable application fee</strong> through King's Apply - commonly around <strong>&pound;85</strong>, though it varies by course - and it is separate from the Chevening application. After you have an offer, you normally need a <strong>UK Student visa</strong> with a Confirmation of Acceptance for Studies (CAS). For study <strong>in London</strong>, GOV.UK's financial requirement is <strong>&pound;1,529 a month for up to nine months</strong> (up to &pound;13,761), on top of fees. As official financial sponsorship, your Chevening documentation can serve as evidence of funds under the visa rules, so you need not show the same personal bank balance as a self-funded student. Always check the current GOV.UK guidance for your circumstances.</p>

<h2>Return-Home Requirement</h2>
<p>A core Chevening condition is that you <strong>commit to returning to your country of award for at least two years</strong> after the scholarship ends. Understand and accept this before you apply.</p>

<h2>Common Mistakes to Avoid</h2>
<ul>
<li>Applying without the <strong>2,800 hours</strong> of post-undergraduate work experience.</li>
<li>Choosing three unrelated courses that do not fit your career plan.</li>
<li>Assuming Chevening eligibility guarantees a KCL place - it does not.</li>
<li>Waiting for the scholarship result before applying to universities.</li>
<li>Assuming IELTS is never required - your course may require it.</li>
<li>Entering inaccurate work-experience dates or hours.</li>
<li>Missing the unconditional-offer deadline of 8 July 2027, 17:00 BST.</li>
</ul>

<h2>Chevening, Leeds or Beyond?</h2>
<p>Chevening at King's is fully funded but competitive and mid-career - it needs the work experience. If you are earlier in your career, the fully funded <a href="/scholarships/university-of-leeds-commonwealth-masters-scholarship">University of Leeds Commonwealth Master's Scholarship</a> is another UK route with different rules. For a government stipend in Europe, see our <a href="/scholarships/france-scholarships-without-ielts">France scholarships without IELTS guide</a>, and for how US funding differs, our <a href="/scholarships/yale-university-scholarship">Yale University scholarship guide</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is the KCL Chevening Scholarship 2027/28 fully funded?</h3>
<p>Yes. Chevening's standard award is fully funded - tuition fees, return economy travel, a monthly living allowance and several allowances - and KCL adds a 20% tuition contribution.</p>

<h3>What is the deadline?</h3>
<p>The Chevening application deadline is 6 October 2026 at 11:00 UTC. Applications opened on 4 August 2026.</p>

<h3>How much work experience do I need?</h3>
<p>At least 2,800 hours of eligible work experience, gained after your undergraduate degree - roughly two years of full-time work. Part-time, voluntary and internship (paid or unpaid) experience can count.</p>

<h3>Do I need IELTS for Chevening?</h3>
<p>Chevening removed its own English requirement in 2020, but your KCL course sets its own - often IELTS 7.0 overall for Band B/C programmes, or 6.5 overall for Band D. Check your course page.</p>

<h3>Is Chevening only for King's College London?</h3>
<p>No. Chevening is a UK-wide programme. You choose three eligible UK Master's courses, which can be at different universities, and KCL is one option among them.</p>

<h3>Does Chevening fund PhDs or online study?</h3>
<p>No. The standard Chevening Scholarship funds one-year taught Master's degrees, not PhDs or distance-learning programmes.</p>

<h3>Can fresh graduates apply?</h3>
<p>Only if they meet the 2,800-hour work-experience requirement. A bachelor's degree alone is not enough.</p>

<h3>Do I apply to Chevening or to King's?</h3>
<p>Both. You submit the Chevening application to the FCDO by 6 October 2026, and separately apply to KCL for your Master's through King's Apply. Do not wait for the scholarship result to start your course applications.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">King's College London: Chevening Scholarships</a></li>
<li><a href="https://www.chevening.org/apply/" target="_blank" rel="noopener">Chevening: apply</a></li>
<li><a href="https://www.chevening.org/resource-hub/guidance/eligibility/" target="_blank" rel="noopener">Chevening: eligibility criteria</a></li>
<li><a href="https://www.chevening.org/scholarships/application-timeline/" target="_blank" rel="noopener">Chevening: 2027/28 application timeline</a></li>
<li><a href="https://www.kcl.ac.uk/study/postgraduate-taught" target="_blank" rel="noopener">King's College London: postgraduate taught study</a></li>
<li><a href="https://www.gov.uk/student-visa/money" target="_blank" rel="noopener">GOV.UK: Student visa money requirement</a></li>
</ul>

<p><em>JobGader is not part of King's College London, Chevening or the UK government. This guide was checked against the official Chevening, KCL and GOV.UK pages on 16 September 2026. Award terms, fees, dates and visa rules change, so confirm them on the official pages before you apply.</em></p>
HTML;
    }
}
