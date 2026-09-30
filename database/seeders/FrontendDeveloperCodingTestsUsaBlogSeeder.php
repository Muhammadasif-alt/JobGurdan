<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Frontend developer coding tests and take-home assignments in the USA, checked
 * on 30 September 2026.
 *
 * A spoke off the front end developer guide, which keeps the head term. The
 * supplied brief was titled for that head term, and its only section the hub
 * does not already cover was step 5 of "How Do I Apply", the technical
 * interview. So that is what this page takes, at its own URL.
 *
 * The spine, and the thing no careers guide says: a take-home coding test is
 * not an informal favour asked between strangers. Federal law calls it a
 * selection procedure, and 29 CFR 1607.16(Q) names "performance tests" and
 * "informal or casual interviews" in the same breath. Once it is a selection
 * procedure it has to be job related, it can be measured for adverse impact,
 * and a disabled candidate has an enforceable right to have it adjusted.
 *
 * Corrections applied to the supplied brief:
 *  1. Every Indeed, LinkedIn and Glassdoor link removed, along with the 1,974
 *     vacancy count and the $84,912 to $195,946 posting range they supported.
 *  2. The brief's BLS paragraph was a projection round out of date: 7 per cent
 *     over 2024 to 2034, 15,500 jobs and a 214,900 base, described as "much
 *     faster than average". The current round is 5 per cent over 2025 to 2035,
 *     11,300 jobs on a 220,100 base, graded "faster than average". Corrected on
 *     the hub, which owns the outlook question, rather than restated here.
 *  3. The $90,930 median was May 2024. Web developers were at $92,650 in May
 *     2025. The hub carries the pay question.
 *
 * Sourcing note: the UGESP and ADA regulation quotes are byte-verified from the
 * 2025 annual edition of the CFR on govinfo, and the EEOC quotes from eeoc.gov.
 * The two Wage and Hour fact sheets were read through a reader because dol.gov
 * returns 403 to direct download; each was read twice, with consistent wording.
 *
 * Deliberately not claimed: that the Fact Sheet #71 seven-factor test governs a
 * take-home given to an experienced candidate. It was written for interns and
 * students, and three of its factors are about formal education. Nor is any
 * federal rule on unpaid work trials asserted, because there is none; the
 * absence is stated as an absence.
 */
class FrontendDeveloperCodingTestsUsaBlogSeeder extends Seeder
{
    public const SLUG = 'frontend-developer-coding-tests-in-usa';

    public const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=front%20end%20developer';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'US Front End Hiring Teams (Aggregated)'],
                ['type' => 'Private', 'display_reference' => 'us-front-end-assessment-aggregated']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United States'],
                ['area' => 'Nationwide', 'country' => 'United States']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Front End Developer Assessments, US Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site / Hybrid / Remote',
                    'work_hours' => 'Standard US business hours, with assessment stages scheduled by the employer',
                    'language' => 'English',
                    // No wage is asserted: this overview is about the hiring
                    // stage rather than one advertised post.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'front end developer coding test, take home assignment usa, frontend technical interview, coding test accommodation, front end developer jobs usa',
                    'meta_description' => 'Front end developer roles with US employers that assess by coding test or take-home assignment, and what federal law requires of that stage.',
                ]
            );
            $blogCategory = BlogCatgories::firstOrCreate(
                ['slug' => 'visa-sponsorship'],
                [
                    'name' => 'Visa Sponsorship',
                    'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
                ]
            );
            $author = User::where('role', 'admin')->first();
            $content = $this->postBody();
            Blog::updateOrCreate(
                ['slug' => self::SLUG],
                [
                    'blog_catgories_id' => $blogCategory->id,
                    'author_id' => $author?->id,
                    'author_name' => $author?->name ?? 'JobGader Editorial',
                    'title' => 'Frontend Developer Coding Tests in USA',
                    'excerpt' => 'A take-home coding test is not an informal favour. Federal law calls it a selection procedure, which means it has to relate to the job, it can be measured for adverse impact, and a timed test can be required to give you more time.',
                    'content' => $content,
                    'featured_image' => 'blogs/frontend-developer-coding-tests-usa.jpg',
                    'tags' => 'frontend developer coding tests, take home coding test usa, frontend technical interview usa, coding test accommodation, unpaid coding assignment, frontend developer assessment, four fifths rule hiring, frontend developer jobs usa',
                    'meta_title' => 'Frontend Developer Coding Tests in USA: Your Rights',
                    'meta_description' => 'What a coding test is in federal law, when a timed test must be adjusted, what the four-fifths rule measures, and whether unpaid work can be asked of you.',
                    'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                ]
            );
        });
    }

    private function jobDescription(): string
    {
        return <<<'HTML'
<p>This is an overview of how US employers assess front end developers, and of what the law requires at that stage. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Front end hiring in the United States is assessed in four common shapes: a timed online exercise, a take-home assignment built in your own time, a live or pair-programming session, and a portfolio review. Each of these is a selection procedure in federal law, which is the subject of the guide.</p>
<p>The federal search linked here covers the government side of this market, where the assessment stage is documented in the vacancy announcement itself. The commercial market is larger and rarely documents it, which is why the guide sets out what you are entitled to ask.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Somewhere between the first call and the offer, a US front end employer will ask you to build something. It might be a timed exercise on a testing platform, a take-home to finish over a weekend, or an hour of live coding with someone watching. Almost every guide to this treats it as etiquette: be gracious, ask clarifying questions, write clean code.</p>

<p>That advice is fine and it is also missing the point. The coding test is the most heavily regulated part of the whole hiring process, and almost nothing written for candidates says so. In federal law it is not a conversation. It is a <em>selection procedure</em>, and once something is a selection procedure, obligations attach to it and rights attach to you.</p>

<p>This page sets out what those are, from the regulations and the enforcement guidance rather than from opinion. If you want the pay and skills picture instead, that is on our <a href="/blog/front-end-developer-jobs-in-usa">front end developer jobs guide</a>, and the application process end to end is covered in <a href="/blog/how-to-apply-for-react-developer-jobs-in-usa">how to apply for developer jobs in the USA</a>.</p>

<h2 id="what-the-law-calls-a-coding-test">What the Law Calls a Coding Test</h2>

<p>The Uniform Guidelines on Employee Selection Procedures, at <strong>29 CFR Part 1607</strong>, are the rules federal enforcement agencies apply to hiring tests. Their definition, at section 1607.16(Q), is broader than most engineers assume:</p>

<blockquote><p>"Any measure, combination of measures, or procedure used as a basis for any employment decision. Selection procedures include the full range of assessment techniques from traditional paper and pencil tests, performance tests, training programs, or probationary periods and physical, educational, and work experience requirements through informal or casual interviews and unscored application forms."</p></blockquote>

<p>Read the second sentence twice. <strong>Performance tests</strong> are named, which is what a coding exercise is. So are <strong>probationary periods</strong>, <strong>work experience requirements</strong>, and &mdash; the part that surprises people &mdash; <strong>informal or casual interviews and unscored application forms</strong>.</p>

<p>That matters because of a widespread belief that a company reduces its legal exposure by keeping the process casual: no scoring, no rubric, just a chat and a gut call. The opposite is closer to the truth. An unstructured chat is a selection procedure exactly as a scored test is, and it is considerably harder to defend as job related, because there is no record of what was measured.</p>

<img src="/public/storage/blogs/frontend-developer-coding-tests-usa-take-home.jpg" alt="A front end developer working through a take-home coding assignment at a laptop" />

<h2 id="job-related-and-consistent-with-business-necessity">It Has to Be About the Job</h2>

<p>The standard a test has to meet is not "is it hard" or "is it a fair puzzle". Where a selection procedure disproportionately excludes people on the basis of race, colour, religion, sex or national origin, the employer has to show it is <strong>"job-related and consistent with business necessity"</strong>. The EEOC's guidance on employment tests puts the test of that plainly:</p>

<blockquote><p>"An employer can meet this standard by showing that it is necessary to the safe and efficient performance of the job. The challenged policy or practice should therefore be associated with the skills needed to perform the job successfully. In contrast to a general measurement of applicants' or employees' skills, the challenged policy or practice must evaluate an individual's skills as related to the particular job in question."</p></blockquote>

<p>"As related to the particular job in question" is the operative phrase, and it is worth holding on to when a front end interview turns into a whiteboard round on binary trees. A test of skills the job does not use is weaker ground for the employer, not stronger.</p>

<p>One more line from the same guidance is worth knowing if the exercise arrives from a third-party assessment platform:</p>

<blockquote><p>"While a test vendor's documentation supporting the validity of a test may be helpful, the employer is still responsible for ensuring that its tests are valid under UGESP."</p></blockquote>

<p>Buying the test in does not move the obligation. The employer still owns it.</p>

<p>A note on weight, because it cuts both ways: that EEOC document carries its own disclaimer that its "contents do not have the force and effect of law and are not meant to bind the public in any way". It explains existing law rather than creating new law. The regulation in Part 1607 and the statute behind it are what bind.</p>

<h2 id="adverse-impact-and-the-four-fifths-rule">Adverse Impact, and the Rule of Thumb Everyone Misquotes</h2>

<p>Part 1607 sets out the general principle at section 1607.3(A):</p>

<blockquote><p>"The use of any selection procedure which has an adverse impact on the hiring, promotion, or other employment or membership opportunities of members of any race, sex, or ethnic group will be considered to be discriminatory and inconsistent with these guidelines, unless the procedure has been validated in accordance with these guidelines, or the provisions of section 6 below are satisfied."</p></blockquote>

<p>How adverse impact gets measured is the well-known <strong>four-fifths rule</strong>, at section 1607.4(D):</p>

<blockquote><p>"A selection rate for any race, sex, or ethnic group which is less than four-fifths (4/5) (or eighty percent) of the rate for the group with the highest rate will generally be regarded by the Federal enforcement agencies as evidence of adverse impact, while a greater than four-fifths rate will generally not be regarded by Federal enforcement agencies as evidence of adverse impact."</p></blockquote>

<p>Here is where nearly every summary of this goes wrong. The four-fifths rule is repeatedly described as a legal threshold, so that passing it means passing the law. The EEOC's own interpretive questions and answers say otherwise:</p>

<blockquote><p>"This '4/5ths' or '80%' rule of thumb is not intended as a legal definition, but is a practical means of keeping the attention of the enforcement agencies on serious discrepancies in rates of hiring, promotion and other selection decisions."</p></blockquote>

<p>And, in the same document: <strong>"The 4/5ths rule of thumb speaks only to the question of adverse impact, and is not intended to resolve the ultimate question of unlawful discrimination."</strong> Section 1607.4(D) says as much itself &mdash; smaller differences can still constitute adverse impact where they are significant "in both statistical and practical terms".</p>

<p>There is also a preference in the regulation that hiring teams rarely know about, at section 1607.3(B): where two procedures are available which serve the employer's interest and are "substantially equally valid for a given purpose", the employer "should use the procedure which has been demonstrated to have the lesser adverse impact". A realistic take-home and a whiteboard puzzle that predicts the same thing are not equivalent under that sentence.</p>

<h2 id="timed-tests-and-accommodation">Timed Tests, and the Right to Have One Adjusted</h2>

<p>This is the most immediately useful part of this page, because it is an enforceable right that costs nothing to exercise and is very widely unknown.</p>

<p>The ADA regulations contain a section specifically about administering tests, at <strong>29 CFR 1630.11</strong>:</p>

<blockquote><p>"It is unlawful for a covered entity to fail to select and administer tests concerning employment in the most effective manner to ensure that, when a test is administered to a job applicant or employee who has a disability that impairs sensory, manual or speaking skills, the test results accurately reflect the skills, aptitude, or whatever other factor of the applicant or employee that the test purports to measure, rather than reflecting the impaired sensory, manual, or speaking skills of such employee or applicant (except where such skills are the factors that the test purports to measure)."</p></blockquote>

<p>The duty runs to applicants and not only to employees. Section 1630.9(a) makes it unlawful "not to make reasonable accommodation to the known physical or mental limitations of an otherwise qualified <strong>applicant</strong> or employee with a disability", unless the employer can show undue hardship.</p>

<p>What that means in practice, for a timed coding exercise, is spelled out in the interpretive appendix to section 1630.11:</p>

<blockquote><p>"An employer may also be required, as a reasonable accommodation, to allow more time to complete the test. In addition, the employer's obligation to make reasonable accommodation extends to ensuring that the test site is accessible."</p></blockquote>

<p>The appendix goes further, and names what happens when a timed test simply cannot be made accessible: alternative formats include "large print or braille, or via a reader or sign interpreter", and where testing in an alternative format is not possible, "the employer may be required, as a reasonable accommodation, to evaluate the skill to be tested in another manner (e.g., through an interview, or through education license, or work experience requirements)".</p>

<h3>How asking actually works</h3>

<p>Four points from the EEOC's enforcement guidance on reasonable accommodation, each of which removes a reason people talk themselves out of asking:</p>

<ul>
    <li><strong>You do have to ask.</strong> "Generally, the individual with a disability must inform the employer that an accommodation is needed." The employer is not required to guess.</li>
    <li><strong>Plain language is enough.</strong> An individual "may use 'plain English' and need not mention the ADA or use the phrase 'reasonable accommodation'".</li>
    <li><strong>It does not have to be in writing.</strong> "Requests for reasonable accommodation do not need to be in writing."</li>
    <li><strong>Timing is open.</strong> A request may be made "at any time during the application process or during the period of employment" &mdash; and the appendix to Part 1630 covers the case where you only realise mid-test, in which case you must tell them "upon becoming aware of the need".</li>
</ul>

<p>The guidance also states the employer's side, which is the sentence worth quoting back if a request is treated as an odd one: an employer "must provide a reasonable accommodation to a qualified applicant with a disability that will enable the individual to have an equal opportunity to participate in the application process and to be considered for a job", short of undue hardship. Employers are expressly permitted to ask about it up front, too &mdash; they "may tell applicants what the hiring process involves (e.g., an interview, timed written test, or job demonstration), and may ask applicants whether they will need a reasonable accommodation for this process".</p>

<p>The guidance's own illustration of getting this wrong is blunt: an employer that cancelled an interview rather than provide a sign language interpreter "has violated the ADA".</p>

<img src="/public/storage/blogs/frontend-developer-coding-tests-usa-assessment.jpg" alt="A live technical assessment session between a front end candidate and an interviewer" />

<h2 id="should-a-take-home-be-paid">Should a Take-Home Be Paid?</h2>

<p>This is the question candidates actually argue about, and it deserves an honest answer rather than a confident one.</p>

<p><strong>There is no federal regulation and no Department of Labor fact sheet specifically about unpaid work trials, job auditions or take-home assignments for applicants.</strong> Part 785 of the wage and hour regulations, which governs hours worked, does not address applicants at all. Anyone telling you there is a clear federal rule here is overstating it, in either direction.</p>

<p>What does exist is the statutory definition the whole of wage and hour law turns on. Under the Fair Labor Standards Act, as Wage and Hour Division Fact Sheet #22 puts it, the term <strong>"employ" includes "to suffer or permit to work"</strong>, and:</p>

<blockquote><p>"Work not requested but suffered or permitted to be performed is work time that must be paid for by the employer."</p></blockquote>

<p>That is a definition about employees, and an applicant is generally not yet one. But it is the reason the line moves when an assignment stops looking like an assessment and starts looking like delivery. A four-hour exercise against a synthetic brief is an assessment. Building a working feature against the company's real backlog, to be merged, is the thing that definition was written about.</p>

<p>The nearest official test for an unpaid work-like arrangement at a for-profit employer is the <strong>seven-factor "primary beneficiary" test</strong> in Fact Sheet #71 &mdash; but be careful with it, because it was written for interns and students, and three of its seven factors are about formal education programmes, academic credit and the academic calendar. Those do not map onto a take-home given to an experienced candidate. Its first factor does, though, and it is the useful one: "The extent to which the intern and the employer clearly understand that there is no expectation of compensation. Any promise of compensation, express or implied, suggests that the intern is an employee."</p>

<p>One historical note, since out-of-date versions circulate: the Department of Labor formally rescinded its previous six-factor internship test in <strong>January 2018</strong>, in Field Assistance Bulletin 2018-2, and adopted the courts' primary beneficiary test instead. If a guide quotes six factors, it is eight years stale.</p>

<h3>The practical position</h3>
<ul>
    <li><strong>Scope, not principle, is the argument to have.</strong> "Is this paid?" invites a no. "This looks like about twelve hours &mdash; would a four-hour version answer the same question?" is a scoping conversation, and a reasonable employer will take it.</li>
    <li><strong>Ask what happens to the work.</strong> If the output ships, you are not being assessed, you are delivering. That is a fair thing to say out loud.</li>
    <li><strong>Ask what is being measured and how.</strong> An employer who can answer has a job-related procedure. One who cannot has a vibe check, and you have learned something either way.</li>
    <li><strong>Get the time limit in writing before you start.</strong> It is the thing most often left vague and most often disputed afterwards.</li>
</ul>

<h2 id="what-to-do-with-all-this">What To Do With All This</h2>

<p>None of the above is a reason to arrive at an interview quoting regulations. Used that way it will cost you the job and deserve to. It is useful in four quieter ways:</p>

<ul>
    <li><strong>If you need an adjustment, ask for it.</strong> Plain language, no diagnosis required, no form, any time in the process. This is the single highest-value item on this page.</li>
    <li><strong>Judge the process, because it tells you about the team.</strong> An employer who can say what the exercise measures and why is describing an engineering culture. So is one who cannot.</li>
    <li><strong>Do not read a casual process as a safe one.</strong> For you, an unstructured interview is the stage where an unrelated judgement is hardest to see and hardest to challenge.</li>
    <li><strong>Prepare for what the job uses.</strong> Front end assessments that predict the work tend to look like the work: a component with states nobody designed, a layout that has to survive a narrow screen, something keyboard operable. Our <a href="/blog/front-end-developer-jobs-in-usa">front end guide</a> covers the two skills that are measurable on the job, which are also the two that are most convincing in an assessment.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Are take-home coding tests legal in the USA?</h3>
<p>Yes. Employers may test applicants. What the law regulates is how: a test is a selection procedure under 29 CFR Part 1607, so where it disproportionately excludes a protected group it has to be job related and consistent with business necessity, and it has to be administered so that a disabled applicant's results reflect the skill rather than the disability.</p>

<h3>Does a coding exercise really count as a selection procedure?</h3>
<p>Yes. Section 1607.16(Q) defines one as "any measure, combination of measures, or procedure used as a basis for any employment decision" and names performance tests explicitly, alongside probationary periods, work experience requirements and even "informal or casual interviews and unscored application forms".</p>

<h3>Can I ask for more time on a timed coding test?</h3>
<p>If you have a disability that the timing disadvantages, yes. The interpretive appendix to 29 CFR 1630.11 states that an employer "may also be required, as a reasonable accommodation, to allow more time to complete the test". The employer can refuse only by showing undue hardship.</p>

<h3>Do I have to disclose a diagnosis to get an adjustment?</h3>
<p>You have to let the employer know you need an adjustment for a reason related to a medical condition, but the EEOC's guidance says you may use "plain English", need not mention the ADA or the phrase "reasonable accommodation", and need not put the request in writing.</p>

<h3>When should I ask for an accommodation?</h3>
<p>A request may be made at any point in the application process. If you only realise partway through a test, the appendix to Part 1630 says you must tell the employer on becoming aware of the need, so say so then rather than afterwards.</p>

<h3>Should a take-home assignment be paid?</h3>
<p>There is no federal rule specifically on unpaid assignments for applicants, so the honest answer is that it depends on what is being asked. The distinction that matters is assessment against delivery: an exercise on a synthetic brief is one thing, work against the company's real backlog that will ship is another.</p>

<h3>What does the four-fifths rule actually mean?</h3>
<p>It is a rule of thumb for spotting adverse impact: a selection rate for a group below four-fifths of the highest group's rate is generally treated as evidence of it. The EEOC is explicit that it "is not intended as a legal definition" and "speaks only to the question of adverse impact".</p>

<h3>Is an informal chat safer for an employer than a scored test?</h3>
<p>No, and the assumption is common. Part 1607 covers informal or casual interviews and unscored application forms as selection procedures too, and an unstructured process is harder rather than easier to defend as job related, because nothing records what was measured.</p>

<h2>People Also Search For</h2>

<h3>Take home coding test</h3>
<p>A selection procedure in federal law, not an informal favour. Settle the scope and the time limit in writing before you start it.</p>

<h3>Frontend developer interview questions</h3>
<p>The ones that predict the job tend to look like the job: component states, narrow screens, keyboard operability. Puzzles the role never uses are weaker ground for the employer.</p>

<h3>Unpaid coding assignment</h3>
<p>No federal rule addresses applicant work trials. The useful line is assessment against delivery, and whether the output is going to ship.</p>

<h3>Coding test accommodation ADA</h3>
<p>More time is named in the appendix to 29 CFR 1630.11. So are large print, braille, a reader or an interpreter, and assessing the skill another way where the format cannot be adapted.</p>

<h3>Four fifths rule hiring</h3>
<p>A rule of thumb at 29 CFR 1607.4(D) for flagging adverse impact, which the EEOC says is not a legal definition and does not settle whether discrimination occurred.</p>

<h3>Frontend developer technical assessment</h3>
<p>Four common shapes: timed online exercise, take-home, live or pair session, and portfolio review. All four are selection procedures.</p>

<h3>Live coding interview</h3>
<p>Ask what is being measured. An employer who can answer has a job-related procedure; one who cannot is telling you something about the team.</p>

<h3>Frontend developer jobs USA</h3>
<p>Web developers were at a $92,650 median in May 2025, with the top tenth above $162,290. The pay and skills picture is on the main front end guide.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/front-end-developer-jobs-in-usa">Front End Developer Jobs in USA</a> &mdash; what the work pays, the outlook on the current projection round, and the two skills that are measurable.</li>
    <li><a href="/blog/how-to-apply-for-react-developer-jobs-in-usa">How to Apply for Developer Jobs in the USA</a> &mdash; the rest of the process, from the vacancy announcement to the background check and the I-9.</li>
    <li><a href="/blog/entry-level-react-developer-jobs-in-usa">Entry Level React Developer Jobs in USA</a> &mdash; what to build before you are assessed, and how to read a junior advert.</li>
    <li><a href="/blog/remote-frontend-developer-jobs-in-usa">Remote Frontend Developer Jobs in USA</a> &mdash; what the word remote hides on a US advert, including where you owe tax.</li>
    <li><a href="/blog/senior-react-developer-jobs-in-usa">Senior React Developer Jobs in USA</a> &mdash; senior processes add architecture and system design, and the grade changes your overtime status.</li>
    <li><a href="/blog/web-developer-jobs-in-usa">Web Developer Jobs in USA</a> &mdash; the BLS occupation front end work is counted in, in full.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li>Uniform Guidelines on Employee Selection Procedures, 29 CFR Part 1607 &mdash; sections 1607.3, 1607.4(D) and 1607.16(Q), 2025 edition of the Code of Federal Regulations.</li>
    <li>Equal Employment Opportunity Commission &mdash; "Employment Tests and Selection Procedures", and the questions and answers interpreting the Uniform Guidelines.</li>
    <li>Equal Employment Opportunity Commission &mdash; Enforcement Guidance on Reasonable Accommodation and Undue Hardship under the ADA.</li>
    <li>ADA regulations, 29 CFR 1630.9 and 1630.11 with the interpretive appendix to Part 1630.</li>
    <li>Department of Labor, Wage and Hour Division &mdash; Fact Sheet #22 (Hours Worked), Fact Sheet #71 (Internship Programs) and Field Assistance Bulletin 2018-2.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Regulations, enforcement guidance and wage data change, and an individual situation can turn on facts this page cannot know &mdash; confirm the current position with the EEOC, the Department of Labor and the employer's own process before relying on any of it.</p>
HTML;
    }
}
