<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * University of South Australia scholarships, written up for readers who still
 * search for UniSA: the university merged with the University of Adelaide
 * into Adelaide University, so the guide covers what Adelaide University
 * offers now, for research and for international coursework students.
 *
 * Checked on 14 September 2026 against Adelaide University's research
 * scholarships page, the RTPS/AURS Conditions of Award 2026 (revised May
 * 2026), the international students scholarships page and each award page,
 * the apply for a research degree page, the Doctor of Philosophy page, the
 * English language proficiency page and the contact page. Corrections to the
 * brief and the posters:
 *
 * 1. The stipend is AUD $36,500 a year (2026), not "$32,500+". The second
 *    poster's "AUD $37,145" is Monash University's rate, not Adelaide's.
 * 2. The brief sends readers to adelaideuni.edu.au/scholarships. The site is
 *    adelaide.edu.au; unisa.edu.au and adelaideuni.edu.au redirect there.
 * 3. The International Postgraduate Research Scholarship (IPRS) no longer
 *    exists; the Research Training Program replaced it in 2017. "Adelaide
 *    Scholarships International" is not on the current list either: the
 *    research awards are the AURS and RTPS.
 * 4. The brief says "up to 3 years" with a thesis allowance. The PhD stipend
 *    runs up to 3.5 years with no extension, fees are waived up to 4 years,
 *    and there is an early submission allowance, not a thesis allowance.
 *    OSHC is a single policy, and relocation is up to AUD $1,500.
 * 5. The brief promises full funding for coursework students through "ASI".
 *    International coursework awards cut tuition by 10% to 50%; none pays
 *    living costs in full.
 * 6. Australia Awards "~AUD $26,000" with a "January 31, 2026" deadline. The
 *    allowance is about AUD $36,230 a year from 1 January 2026, and the 2027
 *    round ran from 1 February to 30 April 2026. Adelaide's coursework
 *    scholarships cannot be held by Australia Awards recipients.
 * 7. "Your cost AUD $0" and "4-8 weeks" have no source. Scholars pay their
 *    own visa and medical examination fees; outcome timing is set per round.
 * 8. The brief's scholarships@adelaide.edu.au, study@adelaide.edu.au and
 *    +61 8 8313 7000 are not on the contact page and are left out; the fee
 *    ranges it quotes vary by degree and are left out too.
 *
 * The posters are used as supplied, at the owner's instruction; the text
 * corrects them.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class UniversityOfSouthAustraliaScholarshipSeeder extends Seeder
{
    public const SLUG = 'university-of-south-australia-scholarships';

    public const APPLY_URL = 'https://adelaide.edu.au/research/research-degrees/research-scholarships/';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'University of South Australia Scholarships 2026: Now Adelaide University',
                'provider' => 'Adelaide University (formerly the University of South Australia)',
                'country' => 'Australia',
                'city' => 'Adelaide',
                'study_level' => "PhD, MPhil, Bachelor's, Master's",
                'funding_type' => 'Fully Funded + Fee Cuts',
                'award_value' => 'AUD $36,500 a year plus tuition fee waiver (research, 2026)',
                'deadline' => '2026-09-30',
                'deadline_note' => 'Next round: Talent Scheme Round 1, early Oct to early Nov 2026',
                'excerpt' => 'The University of South Australia is now part of Adelaide University. Its research scholarships pay AUD $36,500 a year (2026) with a tuition fee waiver, and international coursework students can get 10% to 50% off tuition.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'University of South Australia Scholarship 2026: Adelaide Uni',
                'meta_description' => 'UniSA is now Adelaide University. Research scholarships pay AUD $36,500 a year (2026) plus fee waiver and OSHC; coursework awards cut fees 10-50%.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-14 06:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>If you are looking for <strong>University of South Australia (UniSA) scholarships</strong>, you now apply to <strong>Adelaide University</strong>. UniSA and the University of Adelaide combined into one university, which opened in January 2026, and neither old university has taken applications from new students since 4 August 2025. The scholarships moved with them: Adelaide University's research scholarships pay a living allowance of <strong>AUD \$36,500 a year at the 2026 rate</strong> plus a full tuition fee waiver, and international coursework students can get <strong>10% to 50% off tuition</strong>.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>The research round open now closes on 30 September 2026.</strong> Expressions of interest for the Signature Research Theme (SRT) Scholarship Round, a project-based round open to international applicants, opened on 31 August 2026. The next round, the Graduate Research Talent Scheme, is expected to open in early October 2026, but it only takes international applicants who hold an Australian or New Zealand qualification.</p>
</div>

<h2>Adelaide University Scholarships at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>Adelaide University, formed from the University of South Australia and the University of Adelaide</td></tr>
<tr><th>Research scholarships</th><td>Adelaide University Research Scholarship (AURS), funded by the university, and Research Training Program Scholarship (RTPS), funded by the Australian Government</td></tr>
<tr><th>Research degrees</th><td>Doctor of Philosophy (PhD) and Master of Philosophy (MPhil)</td></tr>
<tr><th>Living allowance</th><td>AUD \$36,500 a year (2026 full-time rate), paid fortnightly</td></tr>
<tr><th>Tuition fees</th><td>Waived for up to 4 years (PhD) or 2 years (MPhil)</td></tr>
<tr><th>Health cover (OSHC)</th><td>Single policy for international students on a Student visa</td></tr>
<tr><th>Relocation allowance</th><td>Up to AUD \$1,500 for international students, AUD \$1,000 for domestic students</td></tr>
<tr><th>Coursework scholarships</th><td>10%, 15%, 25% or 50% off tuition for international bachelor's and master's students</td></tr>
<tr><th>Research round open now</th><td>SRT Scholarship Round: expressions of interest close 30 September 2026</td></tr>
<tr><th>Official site</th><td>adelaide.edu.au (the old unisa.edu.au address now redirects there)</td></tr>
</tbody>
</table></div>

<h2>What Happened to UniSA</h2>
<p>Adelaide University began operating in January 2026. From <strong>4 August 2025</strong>, the University of South Australia and the University of Adelaide stopped accepting applications for new students, and all 2026 applications went to Adelaide University.</p>
<ul>
<li><strong>Current UniSA and University of Adelaide students</strong> moved to Adelaide University, and scholarships they already held continue there.</li>
<li><strong>New applicants</strong> apply to Adelaide University and are considered for its scholarships, not UniSA's old list.</li>
<li><strong>The UniSA posters and pages you may find</strong> still describe the old awards. Use them for history only.</li>
</ul>

<h2>Research Scholarships: What They Pay</h2>
<p>The AURS and RTPS are available in every discipline for PhD and MPhil candidates, domestic and international. The Conditions of Award 2026 set out the benefits.</p>

<h3>1. A living allowance</h3>
<p>Full-time scholars receive a stipend of <strong>AUD \$36,500 a year at the 2026 rate</strong>, about AUD \$3,042 a month, paid fortnightly in arrears into an Australian bank account in your own name. The rate is indexed each year; no 2027 rate has been published. The Conditions describe the full-time stipend as tax-exempt, but advise scholars to get independent advice if their total income goes above the tax-free threshold.</p>
<p>Aboriginal and Torres Strait Islander scholars receive <strong>AUD \$53,608 a year</strong> (2026), and can apply at any time rather than in a round.</p>

<h3>2. Tuition fees</h3>
<ul>
<li><strong>Domestic students</strong> (Australian and New Zealand citizens and permanent residents) get an RTP Fees Offset, so they pay no tuition for the standard length of the degree.</li>
<li><strong>International students</strong> with an AURS have their fees waived. With an RTPS, the fee offset covers all or part of the fees and the university waives any gap.</li>
<li>Fees are covered for up to <strong>4 years for a PhD</strong> and <strong>2 years for an MPhil</strong>.</li>
</ul>

<h3>3. Overseas Student Health Cover</h3>
<p>International scholars on a Student visa (subclass 500) get a <strong>single</strong> BUPA Comprehensive OSHC policy: up to 54 months for a PhD and 30 months for an MPhil, starting two weeks before you arrive. Family members are not covered, so budget for family cover if you bring a partner or children.</p>

<h3>4. Relocation and early submission allowances</h3>
<ul>
<li><strong>Relocation:</strong> up to AUD \$1,500 for an international student, or AUD \$1,000 for a domestic student, towards one-way travel and removal costs when you move to Adelaide.</li>
<li><strong>Early submission:</strong> if you submit your thesis before your stipend ends, you may receive up to two months of stipend as a lump sum.</li>
</ul>
<p>There is no separate thesis allowance in the Conditions of Award.</p>

<h3>5. How long the money lasts</h3>
<ul>
<li><strong>PhD:</strong> stipend for up to <strong>3.5 years</strong> (3 years 9 months with an eligible research internship), with <strong>no extension</strong></li>
<li><strong>MPhil:</strong> stipend and fee waiver for up to 2 years</li>
<li><strong>Paid leave:</strong> up to 20 working days of recreation leave and 10 days of personal leave a year, up to 60 extra days of personal leave, and at least 20 weeks of parental leave per child</li>
</ul>
<p>International research students cannot study half-time, because of Student visa rules.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-south-australia-scholarships-overview.jpg" alt="University of South Australia Scholarship 2026 poster with a student outside a campus building" width="1200" height="628" loading="lazy">
<figcaption>The poster's AUD \$37,145 is Monash University's rate. Adelaide University pays AUD \$36,500 a year (2026).</figcaption>
</figure>

<h2>Research Scholarship Rounds for 2027</h2>
<p>Adelaide University runs separate rounds, and you apply to each one you want to be considered for.</p>
<div class="scholar-table"><table>
<thead><tr><th>Round</th><th>Who can apply</th><th>Dates</th><th>Status on 14 September 2026</th></tr></thead>
<tbody>
<tr><td>Signature Research Theme (SRT) Scholarship Round</td><td>Domestic and international applicants, unless a project says otherwise</td><td>Expressions of interest 31 August to 30 September 2026</td><td>Open</td></tr>
<tr><td>Graduate Research Talent Scheme, Round 1</td><td>Domestic applicants, and international applicants with a relevant Australian or New Zealand qualification</td><td>Expected early October to early November 2026</td><td>Not open yet</td></tr>
<tr><td>Graduate Research Talent Scheme, Round 2</td><td>As Round 1</td><td>Expected mid-February to late March 2027</td><td>Not open yet</td></tr>
<tr><td>Graduate Research Talent Scheme, Round 3</td><td>As Round 1</td><td>Expected mid-July to mid-August 2027</td><td>Not open yet</td></tr>
<tr><td>Out-of-round awards</td><td>Aboriginal and Torres Strait Islander candidates; external, top-up and travel scholarships</td><td>Any time, or each award's own dates</td><td>Varies</td></tr>
</tbody>
</table></div>

<h3>The SRT round in detail</h3>
<p>The SRT round funds specific projects in five themes: Creative and Cultural; Defence and National Security; Food, Agriculture and Wine; Personal and Societal Health; and Sustainable Green Transition. You find a funded project on the university's research projects page and submit an expression of interest (EOI) for it, one EOI per project.</p>
<ul>
<li>You must have <strong>completed your qualification by 28 February 2027</strong> if you are an international applicant outside Australia, or by 31 March 2027 if you are in Australia.</li>
<li>The project's supervisor reviews EOIs and invites suitable applicants to an interview. Only those who succeed are asked to make a formal application.</li>
<li>Incomplete EOIs, or answers over the form's character limits (spaces count), are not accepted, and the university says it cannot answer individual questions about the EOI process.</li>
</ul>

<h3>Who is eligible in any round</h3>
<ul>
<li>Your qualification must be <strong>complete</strong>, with evidence, by the scholarship closing date. The Conditions of Award make limited exceptions, mainly for students finishing a degree at Adelaide University, UniSA or the University of Adelaide.</li>
<li>International applicants must show they meet the <strong>English requirement by the closing date</strong>.</li>
<li>You cannot already hold an RTP Scholarship, an overseas sponsored scholarship or a research degree at the same level or higher.</li>
<li>Scholarships go to the highest-ranked applicants, judged on academic merit, research experience and, for some awards, fit with the university's research priorities.</li>
</ul>

<h2>Scholarships for International Coursework Students</h2>
<p>Bachelor's and master's students do not get a stipend. They can get a percentage off tuition for the standard length of the degree:</p>
<div class="scholar-table"><table>
<thead><tr><th>Scholarship</th><th>Fee reduction</th><th>Who qualifies</th><th>How to apply</th></tr></thead>
<tbody>
<tr><td>Adelaide Academic Excellence Scholarship</td><td>50%</td><td>GPA of at least 6.7 on a 7.0 scale or comparable (ATAR 99 for undergraduates); a bachelor's of at least 2 years or a master's of at least 1.5 years</td><td>Application form. Applications opened on 24 August 2026 and are reviewed on a rolling basis until places run out</td></tr>
<tr><td>Adelaide Emerging Leaders Award</td><td>25%</td><td>GPA of at least 6.0 on a 7.0 scale or comparable</td><td>Assessed automatically with your study application</td></tr>
<tr><td>Adelaide Merit Scholarship</td><td>15%</td><td>GPA of at least 5.0 on a 7.0 scale or comparable</td><td>Assessed automatically</td></tr>
<tr><td>Adelaide Global Alumni Scholarship</td><td>10%</td><td>A previous degree, or a study abroad semester, at Adelaide University, UniSA or the University of Adelaide</td><td>Assessed automatically</td></tr>
<tr><td>Adelaide Partner Award</td><td>10%</td><td>Admission based on a qualification from an approved partner institution; cannot be combined with the merit awards</td><td>Assessed automatically</td></tr>
</tbody>
</table></div>
<ul>
<li><strong>You must keep your grades up.</strong> The 50% award is suspended if your term GPA falls below 5.5, the 25% award below 5.0 and the 15% award below 4.5.</li>
<li><strong>Some degrees are excluded</strong>, including the Doctor of Medicine, dentistry, veterinary medicine, several allied health degrees and the MBA programs.</li>
<li><strong>Australia Awards recipients cannot hold them</strong>, and a sponsor's fee waiver cannot be combined with one.</li>
</ul>

<h2>Australia Awards at Adelaide University</h2>
<p>Australia Awards Scholarships are funded by the Australian Government's Department of Foreign Affairs and Trade, not by the university, and Adelaide University receives Australia Awards scholars. They cover full tuition, return airfare, OSHC, an establishment allowance of A\$5,000 and a living allowance of about <strong>AUD \$36,230 a year</strong> from 1 January 2026. The round for 2027 study ran from 1 February to 30 April 2026 and has closed. Pakistani applicants apply through Australia Awards Pakistan, not through the university. Our <a href="/scholarships/australia-scholarships-without-ielts">Australia scholarships without IELTS guide</a> sets out the English scores Australia Awards requires.</p>

<h2>Entry and English Requirements for Research Degrees</h2>

<h3>Academic entry</h3>
<p>For a PhD, Adelaide University asks for a master's degree by research, or an honours or coursework master's degree with an overall weighted average mark (WAM) of at least 65 (GPA 5.0 of 7) that includes at least half a year of research training marked at a WAM of 75 or more (GPA 6.0). If you do not meet this, the university suggests its Graduate Certificate in Research Skills or Research Design as a pathway.</p>

<h3>English</h3>
<ul>
<li><strong>Most research degrees:</strong> IELTS Academic 6.5 overall with at least 6.0 in listening, reading, writing and speaking</li>
<li><strong>Language-rich disciplines</strong> such as Education, Language and Literature, Communication and Media Studies, and Philosophy and Religious Studies: IELTS 7.0 overall with at least 6.5 in each band</li>
<li><strong>Accepted tests:</strong> IELTS Academic, PTE Academic and TOEFL iBT, taken at an official test centre within two years before you apply</li>
</ul>
<p>You do not need a test if you are a citizen of Australia, Canada (outside Quebec), New Zealand, Ireland, South Africa, the United Kingdom or the United States, or if you studied at bachelor's level or above in one of the countries on the university's exemption list. <strong>Pakistan is not on that list</strong>, so most Pakistani applicants need a test score.</p>

<h2>How to Apply for a Research Scholarship</h2>
<ol>
<li><strong>Check the entry requirements</strong> for the PhD or MPhil and the eligibility rules for your round.</li>
<li><strong>Find a project or a supervisor.</strong> In the SRT round, submit an EOI for a listed project by 30 September 2026. Otherwise, contact potential supervisors: you need <strong>written confirmation of support from an eligible principal supervisor</strong> before you apply.</li>
<li><strong>Have an interview in English</strong> with your proposed supervisor, unless you have already worked with them.</li>
<li><strong>Prepare your documents</strong> (see the list below), using the university's structured CV and research proposal templates.</li>
<li><strong>Apply online</strong> through the international applicants portal by the round's closing date. Incomplete applications are not assessed.</li>
<li><strong>Accept your offer.</strong> International students then receive a Confirmation of Enrolment (CoE) to apply for a Student visa.</li>
</ol>

<div class="scholar-note">
<p><strong>Admission and scholarship together.</strong> In the Graduate Research Talent Scheme, applications are assessed centrally and competitive applicants are offered admission and a scholarship together; in the SRT round, the expression of interest and interview come first. Visa application and medical examination fees are yours to pay: the scholarship does not cover them.</p>
</div>

<h2>Documents to Prepare</h2>
<ul>
<li>Official academic transcripts and degree certificates, with the grading scheme</li>
<li>Evidence of meeting the English requirement, where it applies</li>
<li>Confirmation of supervision from your proposed supervisor (an informal email is enough)</li>
<li>Confirmation of your interview with the supervisor</li>
<li>A CV on Adelaide University's structured CV template</li>
<li>A research proposal on the university's template</li>
<li>Any other relevant documents, such as references or name change documents</li>
</ul>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/university-of-south-australia-scholarships.jpg" alt="University of South Australia Scholarship 2026 banner with a student by the river in Adelaide" width="1200" height="628" loading="lazy">
<figcaption>UniSA is now part of Adelaide University: apply through adelaide.edu.au.</figcaption>
</figure>

<h2>Claims About UniSA Scholarships to Ignore</h2>
<ul>
<li><strong>"AUD \$32,500+ a year."</strong> The 2026 rate is AUD \$36,500. The AUD \$37,145 on one poster is Monash University's rate.</li>
<li><strong>"International Postgraduate Research Scholarships (IPRS)."</strong> IPRS ended when the Research Training Program replaced it in 2017.</li>
<li><strong>"Adelaide Scholarships International: full funding."</strong> This is not on Adelaide University's current list. Coursework awards take 10% to 50% off tuition, and full funding is for research students.</li>
<li><strong>"Up to 3 years, with a thesis allowance."</strong> The PhD stipend lasts up to 3.5 years and fees are waived up to 4 years. There is an early submission allowance instead of a thesis allowance.</li>
<li><strong>"Australia Awards: AUD \$26,000, deadline 31 January 2026."</strong> The allowance is about AUD \$36,230 a year, and the 2027 round closed on 30 April 2026.</li>
<li><strong>"Your cost: AUD \$0."</strong> You pay your own visa and medical examination fees, and OSHC covers you alone.</li>
<li><strong>"Results in 4 to 8 weeks."</strong> Outcome dates are set for each round.</li>
</ul>

<h2>Adelaide vs Monash vs Melbourne vs ANU vs Sydney: 2026 Stipends</h2>
<div class="scholar-table"><table>
<thead><tr><th>University</th><th>2026 full-time stipend</th><th>International health cover</th></tr></thead>
<tbody>
<tr><td>University of Sydney</td><td>AUD \$42,754</td><td>Included</td></tr>
<tr><td>University of Melbourne</td><td>AUD \$39,500</td><td>Single cover included</td></tr>
<tr><td>Australian National University</td><td>AUD \$39,069</td><td>Included for you and your family</td></tr>
<tr><td>Monash University</td><td>AUD \$37,145</td><td>See our Monash guide</td></tr>
<tr><td>Adelaide University</td><td>AUD \$36,500</td><td>Single cover included</td></tr>
</tbody>
</table></div>
<p>At 2026 rates Adelaide pays AUD \$645 a year less than Monash and AUD \$6,254 less than Sydney. Compare the details in our <a href="/scholarships/monash-university-rtp-scholarship">Monash RTP</a>, <a href="/scholarships/university-of-melbourne-rtp-scholarship">University of Melbourne RTP</a>, <a href="/scholarships/australian-national-university-rtp-scholarship">ANU RTP</a> and <a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a> guides.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does the University of South Australia still offer scholarships?</h3>
<p>Not to new students. UniSA combined with the University of Adelaide to form Adelaide University, which opened in January 2026. New applicants apply to Adelaide University and are considered for its scholarships; students who already held a UniSA scholarship keep it.</p>

<h3>How much is the Adelaide University research scholarship stipend?</h3>
<p>AUD \$36,500 a year at the 2026 full-time rate, paid fortnightly, with a tuition fee waiver, single OSHC for international students and up to AUD \$1,500 in relocation costs. No 2027 rate has been published.</p>

<h3>When is the next deadline?</h3>
<p>Expressions of interest for the Signature Research Theme Scholarship Round close on 30 September 2026. The Graduate Research Talent Scheme Round 1 is expected to run from early October to early November 2026, for domestic applicants and international applicants with an Australian or New Zealand qualification.</p>

<h3>Can Pakistani students apply?</h3>
<p>Yes. The SRT round is open to international applicants unless a project says otherwise. Pakistani students with a Pakistani degree are not eligible for the Graduate Research Talent Scheme, which needs an Australian or New Zealand qualification, and most need an English test.</p>

<h3>What IELTS score does Adelaide University need for a PhD?</h3>
<p>IELTS Academic 6.5 overall with at least 6.0 in each band for most research degrees, or 7.0 overall with 6.5 in each band for language-rich disciplines such as Education. PTE Academic and TOEFL iBT are also accepted.</p>

<h3>Do I need a supervisor before I apply?</h3>
<p>Yes. You need written confirmation of support from an eligible principal supervisor, and usually an interview in English with them. In the SRT round, the project supervisor reviews your expression of interest and runs the interview.</p>

<h3>Is there a full scholarship for a master's by coursework?</h3>
<p>Not from the university. The largest international coursework award is the Adelaide Academic Excellence Scholarship, which takes 50% off tuition for applicants with a GPA of 6.7 out of 7 or comparable. Full funding for coursework study comes from outside awards such as Australia Awards.</p>

<h3>Can the research scholarship be extended?</h3>
<p>No. The PhD stipend lasts up to 3.5 years full-time and the MPhil stipend up to 2 years, with no extension. Paid recreation and personal leave do not add time.</p>

<h2>Contact and Official Links</h2>
<ul>
<li>Future student enquiries: the enquiry form on the contact page, or +61 8 7420 5115</li>
<li>Research scholarships: research.scholarships@adelaide.edu.au</li>
<li>Current students (Student Assist): 1300 877 903, or +61 8 7420 5101 from outside Australia</li>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official research scholarships page</a></li>
<li><a href="https://adelaide.edu.au/content/dam/adelaideuniversity/documents/research/pdfs/rtps-aurs-conditions-of-award.pdf" target="_blank" rel="noopener">RTPS/AURS Conditions of Award 2026</a></li>
<li><a href="https://adelaide.edu.au/study/scholarships/international-students-scholarships/" target="_blank" rel="noopener">Scholarships for international students</a></li>
<li><a href="https://adelaide.edu.au/study/how-to-apply/research/" target="_blank" rel="noopener">Apply for a research degree</a></li>
<li><a href="https://adelaide.edu.au/contact/" target="_blank" rel="noopener">Contact Adelaide University</a></li>
</ul>

<p><em>JobGader is not part of Adelaide University or the University of South Australia. This guide was checked against Adelaide University's official pages and Conditions of Award on 14 September 2026. Amounts, rounds and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
