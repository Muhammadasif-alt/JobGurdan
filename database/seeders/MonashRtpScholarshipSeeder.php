<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Monash University's Research Training Program (RTP) Stipend, written up as
 * a plain-English guide for international applicants.
 *
 * Checked against Monash's own pages on 10 September 2026: the graduate
 * research apply page, the 2026 stipend rates table, the English language
 * requirements page and the fees and scholarships page. Where the brief or
 * the posters disagree with those pages, the pages win:
 *
 * 1. Round 3 (international) closed on 31 July 2026, though the posters say
 *    "coming soon". Round 1 for 2027 opened on 1 September 2026 and Monash
 *    has not confirmed its closing date.
 * 2. IELTS 6.5 is the minimum for engineering, IT, science, health and art
 *    and design only. Arts, Business and Economics, Education and Law ask 7.0.
 * 3. The RTP Stipend is a living allowance. International tuition is paid by
 *    a separate award, the Monash International Tuition Scholarship, which
 *    Monash says it generally awards together with a stipend.
 * 4. Consideration is not automatic at any time of year: the course
 *    application has to go in during an open scholarship round.
 * 5. The brief estimates the 2027 rate at $37,500 to $38,000. Monash has not
 *    published one, so the guide gives the 2025 and 2026 rates instead.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class MonashRtpScholarshipSeeder extends Seeder
{
    public const SLUG = 'monash-university-rtp-scholarship';

    public const APPLY_URL = 'https://www.monash.edu/study/fees-scholarships/scholarships/find-a-scholarship/research-training-program-scholarship';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Monash University RTP Scholarship 2026–27',
                'provider' => 'Monash University',
                'country' => 'Australia',
                'city' => 'Melbourne',
                'study_level' => "PhD, Master's by Research",
                'funding_type' => 'Stipend + Allowance',
                'award_value' => 'AUD $37,145 per year',
                'deadline' => null,
                'deadline_note' => 'Round 1 (international) open, closing date TBC',
                'excerpt' => "Monash's RTP Scholarship pays PhD and research master's students AUD $37,145 a year in 2026. Who can apply, the IELTS scores, round dates and how to apply, step by step.",
                'content' => $this->guide(),
                'featured_image' => 'scholarships/monash-university-rtp-scholarship.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Monash RTP Scholarship 2026: Rounds, IELTS & How to Apply',
                'meta_description' => "Monash's RTP Scholarship pays AUD $37,145 a year (2026) for a PhD or research master's. Who can apply, IELTS scores, round dates and how to apply.",
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-10 00:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>Research Training Program (RTP) Scholarship</strong> at Monash University pays you a living allowance while you complete a PhD or a master's by research in Melbourne, Australia. It is funded by the Australian Government, and international students can apply as well as Australians. On its own website Monash calls it the <em>RTP Stipend</em>, so look for that name when you search.</p>

<p>Monash placed equal 36th in the world in the QS World University Rankings 2026, its best result so far. This guide explains, in plain English, what the scholarship pays, who can get it, the English scores you need, the round dates and exactly how to apply.</p>

<div class="scholar-note">
<p><strong>Where things stand on 10 September 2026:</strong> Round 1 for international students, for a start in 2027, opened on 1 September 2026. Monash has not confirmed its closing date yet. Round 3 closed on 31 July 2026, and its results are due in the week starting 11 October 2026.</p>
</div>

<h2>Monash RTP Scholarship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>Monash University, Melbourne (Australian campuses)</td></tr>
<tr><th>Official name</th><td>Research Training Program (RTP) Stipend</td></tr>
<tr><th>Funded by</th><td>The Australian Government</td></tr>
<tr><th>Study level</th><td>PhD and master's by research</td></tr>
<tr><th>Subjects</th><td>All ten Monash faculties</td></tr>
<tr><th>Who can apply</th><td>International students, plus Australian and New Zealand citizens, Australian permanent residents and humanitarian visa holders</td></tr>
<tr><th>Living allowance</th><td>AUD \$37,145 a year in 2026 (AUD \$1,423.18 every two weeks)</td></tr>
<tr><th>Relocation allowance</th><td>AUD \$2,000 if you move from overseas, if eligible</td></tr>
<tr><th>Tuition fees</th><td>Not part of the stipend &mdash; see tuition fees below</td></tr>
<tr><th>How long it lasts</th><td>Up to 3 years 6 months for a PhD, 2 years for a master's by research (full-time)</td></tr>
<tr><th>How to apply</th><td>In your online course application, sent during an open scholarship round</td></tr>
</tbody>
</table></div>

<h2>What the Scholarship Pays</h2>

<h3>1. A living allowance (stipend)</h3>
<p>In 2026 the stipend is <strong>AUD \$37,145 a year</strong>. Monash pays it every two weeks, at <strong>AUD \$1,423.18 a fortnight</strong>. It is money for rent, food and everyday costs while you research, and you do not pay it back.</p>
<p>The rate usually rises each year. It was AUD \$36,063 in 2025, so the 2026 rate is AUD \$1,082 higher, about 3%. Monash has not published a 2027 rate yet, so check the <a href="https://www.monash.edu/graduate-research/study/scholarships/fees-scholarships/rates" target="_blank" rel="noopener">official stipend rates page</a> before you plan a budget. If you study part-time, you receive half the full-time rate.</p>

<h3>2. A relocation allowance</h3>
<p>If you are eligible, Monash adds <strong>AUD \$2,000</strong> to help you move to Melbourne from overseas, or <strong>AUD \$1,000</strong> if you move from another Australian state.</p>

<h3>3. Tuition fees: read this carefully</h3>
<p>The RTP Stipend is a living allowance. It does <strong>not</strong> pay tuition fees by itself.</p>
<ul>
<li><strong>Australian and New Zealand citizens and Australian permanent residents</strong> have their course fees covered by the Australian Government's RTP Fees Offset.</li>
<li><strong>International students</strong> have their tuition covered by a separate award, the <strong>Monash International Tuition Scholarship (MITS)</strong>. MITS pays your course tuition fees and single Overseas Student Health Cover, and Monash says it is generally awarded together with a stipend scholarship.</li>
</ul>
<p>So when an offer arrives, check that it names MITS as well as the stipend. Without a tuition scholarship or a sponsor, international students pay the fees themselves. For scale, Monash lists the 2026 RTP Fees Offset at AUD \$41,000 to \$59,600 a year, depending on the course.</p>

<h3>4. Health cover (OSHC)</h3>
<p>Overseas Student Health Cover is compulsory for every international student for the whole length of the student visa. MITS covers single OSHC. If you bring a partner or children and need family cover, you pay the difference.</p>

<h3>5. How long the money lasts</h3>
<p>Under Monash's scholarship rules, the stipend runs for up to <strong>3 years and 6 months</strong> for a PhD and up to <strong>2 years</strong> for a master's by research, counted as full-time study. Part-time study stretches this out in proportion.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/monash-university-rtp-scholarship-overview.jpg" alt="Monash University RTP Scholarship poster showing AUD 37,145 per year, the eligible degrees and who can apply" width="1200" height="628" loading="lazy">
<figcaption>The key facts at a glance. The poster shows the 2026 dates: Round 3 has now closed, so international students apply in Round 1 for 2027.</figcaption>
</figure>

<h2>Who Can Apply</h2>

<h3>Citizenship and residency</h3>
<p>You can apply if you are any of these:</p>
<ul>
<li>An international student, from any country</li>
<li>An Australian citizen</li>
<li>A New Zealand citizen</li>
<li>An Australian permanent resident</li>
<li>An Australian humanitarian visa holder</li>
</ul>

<h3>Academic background</h3>
<p>To enter a PhD or master's by research at Monash, you need at least one of these, with the minimum grades on Monash's admissions criteria page:</p>
<ul>
<li>A bachelor's degree of <strong>at least four years</strong> in a relevant subject that included a research thesis or project</li>
<li>A master's degree in a relevant subject that included a research thesis or project</li>
<li>A qualification, or a mix of qualifications and relevant professional experience, that Monash accepts instead</li>
</ul>
<p>You also need to show <strong>research experience</strong>: for example an honours thesis, a research master's, a coursework master's with a thesis, or professional experience related to your research area.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Studied in Pakistan or India?</strong> A four-year BS with a final-year research project or thesis matches the first option. A two- or three-year BA or BSc is not a four-year degree on its own, so you would normally also need a master's with a research thesis, such as an MPhil or MS.</p>
</div>

<p>Meeting these minimums makes you eligible for admission, but Monash is clear that <strong>the bar for a scholarship is higher</strong> and the competition is strong. Your grades, your research experience and the quality of your proposal all count.</p>

<h2>Degrees and Subjects You Can Study</h2>
<p>The scholarship is for <strong>research degrees only</strong>:</p>
<ul>
<li><strong>Doctor of Philosophy (PhD)</strong></li>
<li><strong>Master's by research</strong>, such as the Master of Philosophy (MPhil)</li>
</ul>
<p>It does not cover coursework master's degrees. You can research in any of Monash's ten faculties:</p>
<ul>
<li>Art, Design and Architecture</li>
<li>Arts</li>
<li>Business and Economics</li>
<li>Education</li>
<li>Engineering</li>
<li>Information Technology</li>
<li>Law</li>
<li>Medicine, Nursing and Health Sciences</li>
<li>Pharmacy and Pharmaceutical Sciences</li>
<li>Science</li>
</ul>
<p>These rounds and rules are for Monash's Australian campuses. Monash Malaysia and Monash Indonesia run their own scholarships and dates.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/monash-university-rtp-scholarship-students.jpg" alt="International students on the Monash University campus with the RTP Scholarship details" width="1200" height="628" loading="lazy">
<figcaption>Open to international students of every nationality, for a PhD or a master's by research.</figcaption>
</figure>

<h2>English Language Requirements</h2>
<p>If English is not your first language, you have to prove your English before your application can go ahead. There are two ways:</p>
<ol>
<li><strong>An approved English test</strong>, taken at an official test centre, with results no more than two years old. Home or online versions of tests are not accepted.</li>
<li><strong>At least two years of full-time university study taught and assessed entirely in English</strong>, in a country or institution Monash approves.</li>
</ol>
<p>Pakistan and India are not on Monash's list of approved English-speaking countries, so most applicants from South Asia should plan to take a test. The minimum score depends on your faculty:</p>

<div class="scholar-table"><table>
<thead><tr><th>Test</th><th>Arts, Business and Economics, Education, Law</th><th>Art, Design and Architecture, Engineering, IT, Medicine, Nursing and Health Sciences, Pharmacy, Science</th></tr></thead>
<tbody>
<tr><td>IELTS Academic</td><td>7.0 overall, at least 6.5 in each part</td><td>6.5 overall, at least 6.0 in each part</td></tr>
<tr><td>PTE Academic</td><td>65 overall, at least 58 in each part</td><td>58 overall, at least 50 in each part</td></tr>
<tr><td>TOEFL iBT (from 21 January 2026)</td><td>4.5 overall (Reading 4, Listening 4.5, Speaking 4, Writing 5)</td><td>4 overall (Reading 3.5, Listening 3, Speaking 3.5, Writing 4.5)</td></tr>
<tr><td>Cambridge C1 Advanced</td><td>185 overall (169 in Reading, Listening and Speaking, 185 in Writing)</td><td>176 overall, at least 169 in each part</td></tr>
</tbody>
</table></div>

<p>The posters show IELTS 6.5. That is right for engineering, IT, science and health, but Arts, Business and Economics, Education and Law ask for <strong>7.0</strong>. If you plan to study in Australia, also check that your test is accepted for the student visa: not every test Monash accepts is accepted by the Department of Home Affairs.</p>

<h2>Application Rounds and Deadlines</h2>
<p>Monash awards these scholarships in rounds. <strong>You are only considered for a scholarship if you apply during an open round.</strong> International and Australian applicants have different rounds.</p>

<h3>Rounds for international students</h3>
<div class="scholar-table"><table>
<thead><tr><th>Round</th><th>Applications open and close</th><th>If successful, you start</th></tr></thead>
<tbody>
<tr><td>Round 1 (2027)</td><td>Opened 1 September 2026. Closing date to be confirmed (in 2026, Round 1 closed on 31 March)</td><td>July to December 2027</td></tr>
<tr><td>Round 3 (2026)</td><td>4 May to 31 July 2026 &mdash; now closed</td><td>January to June 2027</td></tr>
</tbody>
</table></div>

<h3>Rounds for Australian and New Zealand applicants</h3>
<div class="scholar-table"><table>
<thead><tr><th>Round</th><th>Applications open and close</th><th>If successful, you start</th></tr></thead>
<tbody>
<tr><td>Round 2</td><td>1 November to 31 May</td><td>July to December of the same year</td></tr>
<tr><td>Round 4 (2026)</td><td>1 June to 31 October 2026</td><td>January to June 2027</td></tr>
</tbody>
</table></div>

<ul>
<li><strong>One application per round.</strong> You can only send one application per scholarship round or admission period, so settle your faculty, course and supervisor before you submit.</li>
<li><strong>Results take six to ten weeks.</strong> Scholarship outcomes come out about six to ten weeks after a round closes. Round 3 (2026) results are due in the week starting 11 October 2026.</li>
<li><strong>Admission-only applications</strong> can be sent at any time of year, but they are not considered for a scholarship.</li>
</ul>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/monash-university-rtp-scholarship-deadlines.jpg" alt="Monash University RTP Scholarship poster with the 2026 application deadlines" width="1200" height="628" loading="lazy">
<figcaption>The 2026 deadlines as printed on the poster. Round 3 closed on 31 July 2026.</figcaption>
</figure>

<h2>How to Apply, Step by Step</h2>
<ol>
<li><strong>Check you are eligible.</strong> Compare your degree, research experience and English scores with the sections above, and read your course page for any extra entry requirements.</li>
<li><strong>Choose your faculty and read its guidance.</strong> Each faculty explains what it wants to see before you apply.</li>
<li><strong>Find a supervisor and contact them.</strong> Most faculties expect you to have contacted a potential supervisor before you apply, and the application form will not let you continue without naming a preferred supervisor. Monash's <em>Find a supervisor</em> search is the place to start.</li>
<li><strong>Write your research proposal.</strong> Explain your research question, how you plan to answer it and how it connects to research already happening at Monash.</li>
<li><strong>Book your English test early</strong> if you need one, so the results arrive in time.</li>
<li><strong>Collect your documents.</strong> Use the checklist below.</li>
<li><strong>Apply online during an open round.</strong> Your course application in Monash's online portal is also your scholarship application, so there is no separate scholarship form &mdash; but it only counts if you submit during an open round.</li>
<li><strong>Wait for the outcome.</strong> Scholarship results arrive six to ten weeks after the round closes. For new students, the admission assessment usually starts after the scholarship results are out.</li>
<li><strong>If you receive an offer</strong>, accept it, check that it names a tuition scholarship (MITS) as well as the stipend, arrange your OSHC and apply for an Australian student visa.</li>
</ol>

<h2>Documents Checklist</h2>
<p>Every applicant needs:</p>
<ul>
<li>Your passport (a copy of the personal details page) or proof of citizenship</li>
<li>Certified copies of transcripts for every completed qualification, showing all subjects and grades</li>
<li>Your degree certificates</li>
<li>An explanation of your university's grading system, often printed on the back of the transcript</li>
<li>Proof of English: your test report, or official confirmation that your earlier study was taught in English</li>
<li>Your research proposal</li>
<li>An academic CV of <strong>no more than two pages</strong>, covering your qualifications, relevant work, research experience, publications and awards. Do not include a photo or your date of birth.</li>
<li>Contact details for <strong>at least two referees</strong> who can speak about your academic ability and research potential</li>
</ul>
<p>You may also need certified English translations of documents that are not in English (Monash prefers NAATI-qualified translators), proof of any awards you list, evidence of work or research experience, and a portfolio for creative subjects.</p>

<h2>How to Email a Potential Supervisor</h2>
<p>A short, specific email works better than a long one. Keep it to a few paragraphs:</p>
<ol>
<li><strong>Subject line:</strong> "Prospective PhD applicant: [your research topic]".</li>
<li><strong>Who you are:</strong> your degree, university, final grade and any research you have done.</li>
<li><strong>Why them:</strong> name one or two of their recent papers or projects and say how your idea connects to them.</li>
<li><strong>Your idea:</strong> two or three sentences on the question you want to research.</li>
<li><strong>The ask:</strong> whether they are taking new students and would consider supervising you. Attach your CV and a one-page summary of your proposal.</li>
</ol>
<p>Do not send the same email to dozens of academics at once. Staff can tell, and a supervisor who says yes is the start of a strong application.</p>

<h2>Tips for a Stronger Application</h2>
<ul>
<li><strong>Match your proposal to your supervisor.</strong> A proposal that fits a supervisor's current work is easier for the faculty to support.</li>
<li><strong>Put research first on your CV.</strong> Theses, publications, conference papers and research jobs matter more than unrelated work.</li>
<li><strong>Ask your referees early.</strong> Choose people who have seen your research, such as your thesis supervisor, and give them weeks rather than days.</li>
<li><strong>Do not leave it to the last week.</strong> Certified copies, translations and test results all take time.</li>
<li><strong>Use your one application well.</strong> You get one application per round, so check the faculty, course and supervisor before you submit.</li>
</ul>
<p>Comparing universities? Our <a href="/scholarships/university-of-sydney-rtp-international-scholarship">University of Sydney RTP International Scholarship guide</a> covers a scholarship with a higher stipend and different deadlines, our <a href="/scholarships/australian-national-university-rtp-scholarship">ANU RTP Scholarship guide</a> covers Canberra, and our <a href="/scholarships/university-of-melbourne-rtp-scholarship">University of Melbourne RTP Scholarship guide</a> covers the other large research university in Melbourne.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can international students apply for the Monash RTP Scholarship?</h3>
<p>Yes. International students from any country can apply, alongside Australian and New Zealand citizens, permanent residents and humanitarian visa holders. You apply through your PhD or master's by research application, during an open international round.</p>

<h3>Does the Monash RTP Scholarship pay tuition fees for international students?</h3>
<p>Not by itself. The RTP Stipend is a living allowance. International tuition is covered by the Monash International Tuition Scholarship (MITS), which also pays single health cover, and Monash says it generally awards MITS together with a stipend scholarship.</p>

<h3>How much is the Monash RTP Scholarship worth in 2026?</h3>
<p>AUD \$37,145 a year, paid every two weeks at AUD \$1,423.18. Eligible students moving from overseas also receive a AUD \$2,000 relocation allowance.</p>

<h3>Do I need a separate scholarship application?</h3>
<p>No. You apply for the scholarship in the same online application as your course. But you are only considered if you submit it during an open scholarship round.</p>

<h3>What IELTS score do I need for Monash?</h3>
<p>IELTS Academic 6.5 overall with at least 6.0 in each part for Engineering, IT, Science, Medicine, Nursing and Health Sciences, Pharmacy, and Art, Design and Architecture. Arts, Business and Economics, Education and Law ask for 7.0 overall with at least 6.5 in each part.</p>

<h3>When is the next deadline for international students?</h3>
<p>Round 1 for a 2027 start opened on 1 September 2026. Monash has not confirmed its closing date yet; in 2026, Round 1 closed on 31 March. Round 3 for 2026 closed on 31 July 2026.</p>

<h3>Do I need a supervisor before I apply?</h3>
<p>Most faculties expect you to have contacted a potential supervisor first, and the application form asks you to name a preferred supervisor before you can continue.</p>

<h3>Can I use the RTP Scholarship for a coursework master's degree?</h3>
<p>No. It is for research degrees only: a PhD, or a master's by research such as the MPhil.</p>

<h3>Is there a Monash scholarship just for Pakistani students?</h3>
<p>Monash's joint scholarship with Pakistan's Higher Education Commission is not accepting applications, because the agreement has expired. Pakistani students can still apply for the RTP Scholarship in the international rounds.</p>

<h2>Contact and Official Links</h2>
<ul>
<li>Monash Graduate Research email: <a href="mailto:mgro-apply@monash.edu">mgro-apply@monash.edu</a></li>
<li>Phone: +61 3 9905 1538</li>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official RTP Scholarship page</a></li>
<li><a href="https://www.monash.edu/graduate-research/study/apply" target="_blank" rel="noopener">How to apply for graduate research at Monash</a></li>
<li><a href="https://www.monash.edu/graduate-research/study/scholarships/fees-scholarships/rates" target="_blank" rel="noopener">2026 stipend and allowance rates</a></li>
<li><a href="https://www.monash.edu/graduate-research/study/apply/english-language-proficiency-requirements-for-admission" target="_blank" rel="noopener">English language requirements</a></li>
</ul>

<p><em>JobGader is not part of Monash University. This guide was checked against Monash's official pages on 10 September 2026. Amounts and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
