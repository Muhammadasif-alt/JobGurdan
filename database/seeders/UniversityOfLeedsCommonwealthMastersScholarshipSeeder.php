<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * University of Leeds Commonwealth Master's Scholarship 2027 - a fully funded,
 * FCDO/CSC-backed award for one year of full-time Master's study in 2027/28 -
 * written as an A-to-Z guide for eligible Commonwealth applicants.
 *
 * Checked on 16 September 2026 against the University of Leeds scholarship,
 * how-to-apply, entry-requirements and living-cost pages, the Commonwealth
 * Scholarship Commission (cscuk.fcdo.gov.uk) and GOV.UK. The owner's brief was
 * accurate; the fixes here are small:
 *
 * 1. The example Master's fees are full programme totals for a 12-month course,
 *    not per-year figures. International Business MSc and International
 *    Marketing Management MSc are GBP 33,500 total (both verified for 2027).
 * 2. There is no single university-wide IELTS score - requirements are
 *    course-specific, so the guide points to each course page.
 * 3. Leeds writes "Commonwealth Masters Scholarships"; the CSC scheme name is
 *    "Commonwealth Master's Scholarships". Both are used consistently here.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class UniversityOfLeedsCommonwealthMastersScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-leeds-commonwealth-masters-scholarship';

    public const APPLY_URL = 'https://www.leeds.ac.uk/scholarship/11/commonwealth-masters-scholarships-2027';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of Leeds Commonwealth Master\'s Scholarship 2027 (Fully Funded, UK)',
                'provider' => 'University of Leeds / UK FCDO (Commonwealth Scholarship Commission)',
                'country' => 'United Kingdom',
                'city' => 'Leeds',
                'study_level' => "Master's",
                'funding_type' => 'Fully Funded',
                'award_value' => 'Tuition, maintenance, return airfare + allowances',
                'deadline' => '2026-10-20',
                'deadline_note' => "Commonwealth Master's 2027 round closed; the next call usually opens around September.",
                'excerpt' => "The University of Leeds Commonwealth Master's Scholarship 2027 is fully funded by the UK FCDO - tuition, a living allowance and return airfare - for one year of Master's study in 2027/28. Eligible Commonwealth citizens apply via CSC by 20 October 2026.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => "Leeds Commonwealth Master's Scholarship 2027: Fully Funded",
                'meta_description' => "Leeds Commonwealth Master's Scholarship 2027: fully funded by the UK FCDO - fees, living costs, return airfare. Apply via CSC by 20 October 2026.",
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-16 08:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>University of Leeds Commonwealth Master's Scholarship 2027</strong> is a genuinely <strong>fully funded</strong> route to a one-year Master's in the UK. It is funded by the <strong>UK Foreign, Commonwealth &amp; Development Office (FCDO)</strong> through the <strong>Commonwealth Scholarship Commission (CSC)</strong>, and at Leeds it supports full-time Master's study in the <strong>2027/28</strong> academic year. This guide covers it A to Z - what "fully funded" really means, who can apply, the development themes, admission and English rules, real tuition and living costs, the UK visa money proof, documents, the two-part application process and the deadlines - using only official Leeds, CSC and GOV.UK sources.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Read this first:</strong></p>
<ul>
<li><strong>The Leeds deadline is 20 October 2026 at 4pm (16:00) BST</strong>, and the 2027 call opened on <strong>8 September 2026</strong>. Your nominating agency may close earlier.</li>
<li><strong>There are two separate processes.</strong> You must both win a place on a Leeds Master's <em>and</em> win the scholarship through the Commonwealth route - one does not grant the other.</li>
<li><strong>You do not apply to Leeds for the scholarship.</strong> You apply through your home-country Commonwealth Scholarship Agency on the CSC system, <strong>CSC Central</strong>, and to a nominating organisation.</li>
<li><strong>Not every Master's qualifies.</strong> Your study must fit one of the CSC's development themes, and English and tuition rules are set course by course.</li>
</ul>
</div>

<h2>Leeds Commonwealth Scholarship 2027 at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>University of Leeds, England (Russell Group)</td></tr>
<tr><th>Scholarship</th><td>Commonwealth Master's Scholarship 2027 (Leeds: "Commonwealth Masters Scholarships")</td></tr>
<tr><th>Funder</th><td>UK FCDO, through the Commonwealth Scholarship Commission</td></tr>
<tr><th>Level / mode</th><td>Master's, full-time, one year (2027/28)</td></tr>
<tr><th>Funding</th><td><strong>Fully funded:</strong> tuition, maintenance/living allowance, return economy airfare and other approved allowances</td></tr>
<tr><th>Who</th><td>Citizens of eligible Commonwealth countries (check the current list)</td></tr>
<tr><th>Applications open</th><td>8 September 2026</td></tr>
<tr><th>Final deadline</th><td><strong>20 October 2026, 4pm BST</strong> (Leeds)</td></tr>
<tr><th>Apply through</th><td>Your home Commonwealth Scholarship Agency, via CSC Central, plus a nominator</td></tr>
</tbody>
</table></div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-leeds-commonwealth-masters-scholarship-campus.jpg" alt="University of Leeds Commonwealth Master's Scholarship 2027, fully funded UK scholarship, with the Leeds campus" width="1200" height="628" loading="lazy">
<figcaption>The Leeds Commonwealth Master's Scholarship is fully funded by the UK FCDO - not a small tuition discount, but tuition, living costs and return airfare for one year.</figcaption>
</figure>

<h2>What Is the Commonwealth Master's Scholarship?</h2>
<p>The Commonwealth Master's Scholarship is a UK government programme for talented students from eligible Commonwealth countries who have the potential to contribute to <strong>sustainable development</strong> but could not otherwise afford postgraduate study in the UK. At Leeds, your proposed study must connect with one of the <strong>six CSC development themes</strong>:</p>
<ol>
<li>Science and technology for development</li>
<li>Improving population health, health systems and capacity</li>
<li>Promoting innovation and entrepreneurship</li>
<li>Strengthening peace, security and governance</li>
<li>Strengthening resilience and response to crises</li>
<li>Access, inclusion and opportunity</li>
</ol>
<p>So do not assume every Leeds Master's qualifies. Your course, and the case you make, must fit these development priorities.</p>

<h2>Is It Really Fully Funded?</h2>
<p>Yes. Leeds describes the 2027 award as a <strong>one-year fully funded scholarship</strong>, and lists the benefits as:</p>
<ul>
<li><strong>Full tuition fees</strong> for the approved Master's.</li>
<li><strong>Maintenance / living allowance</strong>, set under CSC rules.</li>
<li><strong>Return economy airfare</strong> to and from the UK.</li>
<li><strong>Other approved allowances</strong> under CSC rules (which can include arrival and thesis-related allowances).</li>
</ul>
<p>The exact maintenance rate and extra allowances are fixed by the Commonwealth Scholarship Commission, so confirm the current CSC figures rather than trusting a number on a third-party site. This is very different from the many university awards that give only &pound;3,000, &pound;5,000 or &pound;10,000 off tuition.</p>

<h2>Who Can Apply?</h2>
<p>The scholarship is for citizens of <strong>eligible Commonwealth countries</strong>; Leeds publishes the current country list on its scholarship page, and nationality is a hard requirement. But being from an eligible country is not a guarantee - you also must:</p>
<ul>
<li>Meet the <strong>scholarship's eligibility</strong> and development-theme requirements.</li>
<li>Meet the <strong>academic requirements</strong> of your chosen Leeds Master's.</li>
<li>Meet the <strong>English-language</strong> requirement where it applies.</li>
<li>Apply through the correct <strong>Commonwealth nomination route</strong> and a nominating agency.</li>
<li>Submit everything <strong>before the relevant deadline</strong>.</li>
</ul>

<h2>Which Degrees Are Covered?</h2>
<p>This is a <strong>Master's</strong> award - not undergraduate or PhD. Choose a full-time Master's that is relevant to the development themes. Broadly, strong fits include fields connected with science, technology, engineering, health and public health, development and international development, environment and sustainability, innovation and entrepreneurship, governance, social policy, education, inclusion, and crisis and resilience. Always check the exact eligibility of a course against the 2027 CSC conditions and the Leeds course information.</p>

<h2>University of Leeds Admission Requirements</h2>
<p>Scholarship eligibility and university admission are <strong>two separate things</strong>; first you must qualify for the Master's itself. Leeds sets entry requirements <strong>course by course</strong>, and applicants with international qualifications should check the equivalent international entry requirement for their chosen course. Many Leeds Master's ask for a bachelor's equivalent to a UK <strong>2:1</strong>, but this is not universal:</p>
<ul>
<li><strong>Social Research MA (2027):</strong> a 2:1 in a social science or related subject.</li>
<li><strong>International Business MSc (2027):</strong> a 2:1 in any subject.</li>
</ul>
<p>So never apply a single percentage or CGPA to every Leeds course - read the individual course page.</p>

<h2>IELTS and English Requirements</h2>
<p>There is <strong>no single IELTS score for all of Leeds</strong>. Requirements are set per course. Leeds' baseline for some courses is IELTS 6.0 overall with 5.5 in each component, while many programmes ask for more - Leeds specifically flags Law, Linguistics, English, Media and Communication, Fine Art/History of Art and the Business School as higher. Examples for 2027 entry:</p>
<ul>
<li><strong>International Business MSc:</strong> IELTS 6.5 overall, at least 6.0 in each component.</li>
<li><strong>International Marketing Management MSc:</strong> IELTS 6.5 overall, at least 6.0 in each component.</li>
<li><strong>Bioscience MSc:</strong> IELTS 6.5 overall, at least 6.0 in each component.</li>
</ul>
<p>Do not assume a Commonwealth Scholarship waives English - it does not. Check your specific course. Leeds also lets applicants for some Master's apply before taking an approved test, but you must still provide evidence meeting the requirement when asked.</p>

<h2>Tuition Fees for International Students</h2>
<p>International tuition differs by course; there is no single Leeds Master's fee. These 2027-entry examples are <strong>full programme totals</strong> (a taught Master's is a 12-month course), not per-year figures:</p>
<div class="scholar-table"><table>
<thead><tr><th>Programme (2027 entry)</th><th>International fee (full course)</th></tr></thead>
<tbody>
<tr><td>Social Research MA</td><td>&pound;27,300</td></tr>
<tr><td>International Business MSc</td><td>&pound;33,500</td></tr>
<tr><td>International Marketing Management MSc</td><td>&pound;33,500</td></tr>
<tr><td>Bioscience MSc</td><td>&pound;33,900</td></tr>
<tr><td>Criminal Justice and Criminal Law LLM</td><td>&pound;29,600</td></tr>
</tbody>
</table></div>
<p>These are examples; your programme may differ, so confirm the fee on its course page. <strong>The Commonwealth Scholarship covers the approved tuition</strong>, so a funded scholar does not pay these fees.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-leeds-commonwealth-masters-scholarship-city.jpg" alt="A Commonwealth scholar in the city of Leeds, fully funded University of Leeds Master's scholarship 2027" width="1200" height="628" loading="lazy">
<figcaption>Leeds is outside London, which matters for both living costs and the UK Student visa money proof.</figcaption>
</figure>

<h2>Cost of Living in Leeds</h2>
<p>Leeds is outside London and cheaper for it. The University's own budgeting guide gives these average estimates for a single student:</p>
<div class="scholar-table"><table>
<tbody>
<tr><th>University accommodation (self-catered)</th><td>About &pound;198 a week on average</td></tr>
<tr><th>Private accommodation</th><td>About &pound;210 a week on average</td></tr>
<tr><th>Groceries</th><td>About &pound;36 a week</td></tr>
<tr><th>Transport, socialising, phone, clothes</th><td>About &pound;59 a week</td></tr>
<tr><th>Combined estimate</th><td>About <strong>&pound;320 a week</strong> - roughly &pound;12,480 over 39 weeks or &pound;16,640 over a 52-week year</td></tr>
</tbody>
</table></div>
<p>These are budgeting estimates and exclude tuition. Because the scholarship includes a maintenance allowance, a funded scholar receives CSC support toward these costs.</p>

<h2>UK Student Visa Requirements</h2>
<p>Once you have your university and scholarship documents, you normally need a <strong>UK Student visa</strong>. GOV.UK sets a financial requirement: for study <strong>outside London</strong> you must show <strong>&pound;1,171 a month for up to nine months</strong> (about &pound;10,539), on top of course fees - versus &pound;1,529 a month inside London. Official scholarship or sponsorship documentation can serve as evidence of your funding, which is one of the practical advantages of a fully funded award. Always check the current GOV.UK Student visa rules before applying, as amounts and exemptions change.</p>

<h2>Documents You May Need</h2>
<p>The exact list varies by the scholarship, the nominating agency and the course. For the <strong>Leeds admission</strong> application, expect to provide:</p>
<ul>
<li>Passport or identification</li>
<li>Bachelor's degree certificate</li>
<li>Academic transcripts</li>
<li>Personal statement</li>
<li>CV</li>
<li>Academic references (Leeds' International Business MSc asks for two)</li>
<li>English-language evidence, where required</li>
<li>A sponsorship letter, where applicable</li>
</ul>
<p>The <strong>Commonwealth Scholarship</strong> application also asks about your academic background, proposed study, development impact and career plans, plus anything your nominating agency requires. Check the CSC application system and your nominating agency for the exact list.</p>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Choose a suitable full-time Leeds Master's</strong> for 2027/28 that fits your background, your goals and a CSC development theme.</li>
<li><strong>Check the course requirements</strong> - academic entry, English, documents, fee and any extras - on the course page.</li>
<li><strong>Apply for admission to Leeds</strong> through the University's application system. Leeds recommends submitting your course application <strong>at least one month before the scholarship deadline</strong> if you are also applying for funding, so do not wait for 20 October.</li>
<li><strong>Apply through the Commonwealth route</strong> - your home country's Commonwealth Scholarship Agency, using the CSC's online system, <strong>CSC Central</strong> (the 2027 call opened on 8 September 2026).</li>
<li><strong>Apply to a nominating organisation</strong>, which may have its own criteria and an earlier closing date.</li>
<li><strong>Submit before the final deadline</strong>, 20 October 2026 at 4pm BST - allowing for any earlier nominator deadline.</li>
</ol>

<h2>Application Timeline</h2>
<div class="scholar-table"><table>
<thead><tr><th>Date</th><th>What happens</th></tr></thead>
<tbody>
<tr><td>8 September 2026</td><td>Commonwealth application call opens</td></tr>
<tr><td>September 2026</td><td>Prepare and submit your Leeds Master's application</td></tr>
<tr><td>Before 20 October 2026</td><td>Complete your nominating-agency requirements</td></tr>
<tr><td>20 October 2026, 4pm BST</td><td>Final Leeds-listed scholarship deadline</td></tr>
<tr><td>2026&ndash;2027</td><td>Selection and admission decisions</td></tr>
<tr><td>September 2027</td><td>Master's study begins</td></tr>
</tbody>
</table></div>

<h2>Scholarship vs University Admission</h2>
<p>This is the point applicants miss most. <strong>Getting a Leeds offer does not give you the scholarship, and being from an eligible country does not give you a Leeds place.</strong> You must clear both:</p>
<ul>
<li><strong>University admission:</strong> academic entry, English, course-specific requirements and documents.</li>
<li><strong>Commonwealth Scholarship:</strong> eligible nationality, scholarship eligibility, a development theme, and the nomination and selection process.</li>
</ul>

<h2>Common Mistakes to Avoid</h2>
<ul>
<li>Applying after <strong>20 October 2026, 4pm BST</strong>.</li>
<li>Assuming every Master's is eligible - it must fit a development theme.</li>
<li>Assuming IELTS is waived - English rules are course-specific.</li>
<li>Using one generic tuition figure - fees vary by programme.</li>
<li>Waiting for a scholarship result before applying to Leeds.</li>
<li>Applying only to the university and skipping the CSC and nominating agency.</li>
<li>Trusting an unofficial site over Leeds, CSC and GOV.UK.</li>
</ul>

<h2>Leeds, France or the US?</h2>
<p>A Commonwealth Scholarship at Leeds is fully funded and for a taught Master's. If you want a government stipend in Europe, France's Eiffel programme pays a monthly allowance but leaves tuition to the institution - see our <a href="/scholarships/france-scholarships-without-ielts">France scholarships without IELTS guide</a>. In the United States, Yale meets need rather than offering merit awards, and funds PhD students through its Graduate School - read our <a href="/scholarships/yale-university-scholarship">Yale University scholarship guide</a>. And for funded research places in Australia with an English-test catch, see our <a href="/scholarships/australia-scholarships-without-ielts">Australia scholarships without IELTS guide</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the University of Leeds Commonwealth Scholarship 2027?</h3>
<p>A fully funded Commonwealth Master's scholarship for eligible students to do one year of full-time Master's study at Leeds in 2027/28, funded by the UK FCDO through the Commonwealth Scholarship Commission.</p>

<h3>Is it fully funded?</h3>
<p>Yes. Leeds lists tuition fees, a maintenance/living allowance, return economy airfare and other approved allowances as part of the award, set under CSC rules.</p>

<h3>What is the deadline?</h3>
<p>The University of Leeds final deadline is 20 October 2026 at 4pm BST. The 2027 call opened on 8 September 2026, and your nominating agency may close earlier.</p>

<h3>Who funds it?</h3>
<p>The UK Foreign, Commonwealth &amp; Development Office (FCDO), through the Commonwealth Scholarship Commission.</p>

<h3>Who can apply?</h3>
<p>Citizens of eligible Commonwealth countries, whose proposed study fits a CSC development theme and who meet the course's academic and English requirements. Leeds publishes the eligible-country list on its scholarship page.</p>

<h3>Do I need IELTS?</h3>
<p>It depends on the course. Leeds sets English requirements per programme - some at IELTS 6.0 overall, many at 6.5 overall with 6.0 in each component. The scholarship does not waive them.</p>

<h3>Do I apply to Leeds or to the Commonwealth Scholarship Commission?</h3>
<p>Both. You apply for admission through the University of Leeds, and for the scholarship through your home Commonwealth Scholarship Agency on CSC Central, plus a nominating organisation. Leeds recommends the course application at least a month before the scholarship deadline.</p>

<h3>Can fresh graduates apply?</h3>
<p>Potentially, if they meet the academic, nationality, scholarship and course requirements. A fresh graduate should make a strong case on academic record, relevant projects and development impact, and confirm eligibility against the 2027 CSC conditions.</p>

<h2>Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">University of Leeds: Commonwealth Master's Scholarships 2027</a></li>
<li><a href="https://cscuk.fcdo.gov.uk/scholarships/commonwealth-masters-scholarships/" target="_blank" rel="noopener">Commonwealth Scholarship Commission: Master's scholarships</a></li>
<li><a href="https://www.leeds.ac.uk/masters-applying/doc/apply-masters-courses" target="_blank" rel="noopener">University of Leeds: how to apply for Master's courses</a></li>
<li><a href="https://www.leeds.ac.uk/international-applying/doc/entry-requirements" target="_blank" rel="noopener">University of Leeds: entry and English-language requirements</a></li>
<li><a href="https://www.leeds.ac.uk/research-fees/doc/living-expenses" target="_blank" rel="noopener">University of Leeds: living costs and budgeting</a></li>
<li><a href="https://www.gov.uk/student-visa/money" target="_blank" rel="noopener">GOV.UK: Student visa money requirement</a></li>
</ul>

<p><em>JobGader is not part of the University of Leeds, the Commonwealth Scholarship Commission or the UK government. This guide was checked against the official Leeds, CSC and GOV.UK pages on 16 September 2026. Award terms, fees, deadlines and visa rules change, so confirm them on the official pages before you apply.</em></p>
HTML;
    }
}
