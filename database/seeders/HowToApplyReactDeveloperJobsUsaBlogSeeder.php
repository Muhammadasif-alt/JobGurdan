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
 * Applying for React developer work in the USA, checked on 29 September 2026.
 *
 * The third spoke off the React developer hub. The brief behind it was titled
 * for the head term the hub already ranks on, so this page takes the brief's
 * own distinguishing section, "How Do I Apply", and builds the guide there.
 * The hub keeps "React developer jobs"; this takes the application process.
 *
 * Corrections applied to the supplied brief:
 *  1. Every Indeed and LinkedIn link removed, with the 6,764 vacancy count and
 *     the $129,348 ZipRecruiter average they were quoted to support.
 *  2. The brief's location table was assembled from job board listings and is
 *     dropped rather than republished.
 *  3. The brief's five-step "how to apply" was generic advice with no source.
 *     Replaced with the two systems a US applicant actually meets, and the
 *     law that governs the employer's side of each.
 *  4. Four FAQs and no People Also Search For block. Now eight of each.
 *
 * Everything here is OPM, EEOC, FTC, FCRA guidance or USCIS. The useful
 * insight the briefs all missed: the application process is not a black box
 * with etiquette attached to it. Large parts of it are regulated, and the
 * regulation mostly creates rights for the applicant.
 *
 * Not claimed: USAJOBS federal resume formatting rules. The USAJOBS help
 * centre redirects and then returns 403, so the specifics could not be
 * confirmed and are described only where OPM's own hiring pages carry them.
 */
class HowToApplyReactDeveloperJobsUsaBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-apply-for-react-developer-jobs-in-usa';

    public const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=software%20developer';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'US React Employers, Commercial and Federal (Aggregated)'],
                ['type' => 'Private', 'display_reference' => 'us-react-apply-aggregated']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United States'],
                ['area' => 'Nationwide', 'country' => 'United States']
            );
            Job::updateOrCreate(
                [
                    'position' => 'React Developer Applications, US Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site / Hybrid / Remote',
                    'work_hours' => 'Set by the employer; federal appointments follow published schedules',
                    'language' => 'English',
                    // The guide is about process, and no pay is asserted on it.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'how to apply for react developer jobs, react developer application process, react developer interview usa, federal hiring process developer, job scam warning signs',
                    'meta_description' => 'Applying for React developer roles with US employers, covering the commercial pipeline and the federal competitive hiring process that runs alongside it.',
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
                    'title' => 'How to Apply for React Developer Jobs in USA',
                    'excerpt' => 'Most advice on applying treats the process as etiquette. Large parts of it are law. What an employer may ask, what it must hand you before rejecting you over a background check, and how a federal vacancy is actually decided are all written down.',
                    'content' => $content,
                    'featured_image' => 'blogs/how-to-apply-react-developer-jobs-usa.jpg',
                    'tags' => 'how to apply for react developer jobs, react developer application process, react developer interview usa, federal hiring process developer, background check rights usa, job scam warning signs, form i9 employment, eeoc hiring rules',
                    'meta_title' => 'How to Apply for React Developer Jobs in USA 2026',
                    'meta_description' => 'How to apply for React developer jobs in the USA: the federal hiring stages, what an employer may not ask you, your background check rights, and scam signs.',
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
<p>This is an overview of how React developer applications work with employers across the United States. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Two hiring systems run side by side. Commercial employers set their own process. Federal agencies run a competitive hiring procedure defined by the Office of Personnel Management, with published stages, rating categories and veterans' preference.</p>
<p>Both are constrained by the same federal law on what may be asked, how background checks are run, and how work authorisation is verified.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most advice about applying for developer jobs is etiquette: tailor the CV, follow up politely, send a thank you note. Useful enough, and it misses the more interesting thing.</p>

<p><strong>Large parts of the hiring process are written down in law</strong>, and almost all of what is written down creates rights for you rather than obligations. What an employer may ask, what it has to put in your hands before rejecting you over a background check, and how a federal vacancy is actually decided are not matters of style.</p>

<p>Our <a href="/blog/react-developer-jobs-in-usa">React developer guide</a> covers the market and the pay. This page covers the process.</p>

<h2 id="two-hiring-systems">You Are Applying Into One of Two Systems</h2>

<p>American React work is hired through two quite different machines, and candidates lose time by treating them as one:</p>

<ul>
    <li><strong>Commercial hiring.</strong> The employer designs its own process. Typically a CV and portfolio screen, a recruiter call, a take-home or live coding exercise, a system design conversation for senior roles, and an offer. Speed varies from days to months.</li>
    <li><strong>Federal competitive hiring.</strong> A published procedure with defined stages, run by or under the rules of the Office of Personnel Management. It is slower, far more structured, and it does not reward the things commercial applications reward.</li>
    </ul>

<p>A large slice of US React work sits on the federal side, through agencies and the contractors who build for them. Before you invest in it, check the eligibility line: much of it requires US citizenship or a clearance, which the <a href="/blog/react-developer-jobs-in-usa">main React guide</a> sets out in full.</p>

<h2 id="federal-hiring-process">The Federal Process, Stage by Stage</h2>

<p>OPM's competitive hiring process runs in six stages:</p>

<ul>
    <li><strong>Vacancy announcement</strong> &mdash; the public notice of the opening, with its own open period.</li>
    <li><strong>Application and assessment</strong> &mdash; candidates apply and are evaluated, which can mean written tests or a review of education and experience.</li>
    <li><strong>Rating and ranking</strong> &mdash; applicants are rated against job-related competencies.</li>
    <li><strong>Certification</strong> &mdash; eligible candidates are certified to the hiring manager.</li>
    <li><strong>Selection</strong> &mdash; the manager selects from the certified candidates.</li>
    <li><strong>Appointment</strong> &mdash; the successful candidate receives a career or career-conditional appointment.</li>
</ul>

<p>Two features of that process explain most of the frustration people report with it.</p>

<p><strong>Category rating replaced the old rule of three.</strong> The traditional approach let a manager pick only from the top three scorers. Under category rating, agencies "evaluate candidates and place them into two or more pre-determined quality categories", and the manager may select anyone in the highest category. So your goal is not to be ranked first. It is to land in the top category, which is a different target and a more achievable one.</p>

<p><strong>Veterans' preference is not a tiebreak.</strong> Preference eligibles "receive absolute preference within each category", and an agency "may not select a non-preference eligible unless the agency requests to pass over the preference eligible". If you are not a veteran, that is simply part of the landscape. If you are, it is worth far more than most applicants realise.</p>

<p>The practical consequence for a React developer: the federal application is assessed against stated competencies, not against how impressive your portfolio feels. Answer what the announcement asks, in the terms it asks it, and do it before the announcement closes.</p>

<img src="/public/storage/blogs/how-to-apply-react-developer-jobs-usa-application.jpg" alt="Completing a React developer job application in the United States" />

<h2 id="what-employers-cannot-ask">What an Employer Is Not Allowed to Ask</h2>

<p>The laws the EEOC enforces protect applicants, not only employees. The protected characteristics are <strong>"race, color, religion, sex (including transgender status, sexual orientation, and pregnancy), national origin, age (40 or older), disability or genetic information"</strong>, along with protection from retaliation for having complained about discrimination or taken part in an investigation.</p>

<p>What that rules out at application stage:</p>

<ul>
    <li><strong>Job adverts that signal a preference.</strong> An employer may not "publish a job advertisement that shows a preference for or discourages someone from applying for a job" on those grounds. The EEOC's own examples include adverts seeking "females" or <strong>"recent college graduates"</strong> &mdash; which is worth knowing in a field where that phrasing is common.</li>
    <li><strong>Recruitment that filters people out.</strong> Recruiting "in a way that discriminates" is unlawful, and the EEOC names word-of-mouth hiring that produces a homogeneous workforce as a possible violation.</li>
    <li><strong>Hiring on assumptions.</strong> Decisions may not be "based on stereotypes and assumptions" about protected characteristics.</li>
    <li><strong>Disability questions before an offer.</strong> Employers are "explicitly prohibited from making pre-offer inquiries about disability".</li>
</ul>

<p>The governing principle for the whole application stage: "the information obtained and requested through the pre-employment process should be limited to those essential for determining if a person is qualified for the job".</p>

<p>On salary history, be careful with what you read. <strong>There is no single federal ban.</strong> Restrictions exist, but they are state and local law and they differ, so check the rule where the role is based rather than assuming either way.</p>

<h2 id="background-check-rights">The Background Check Has Rules, and They Are Mostly Yours</h2>

<p>If an employer uses a consumer reporting agency to check you, the Fair Credit Reporting Act applies, and it is unusually specific.</p>

<p><strong>Before the check</strong>, the employer must tell you it "might use the information for decisions about his or her employment", and that notice "must be in writing and in a stand-alone format" &mdash; it cannot be buried in the application form. It must then "get the applicant's or employee's written permission to do the background check".</p>

<p><strong>Before rejecting you</strong> because of what the report says, the employer must give you a pre-adverse action notice that includes <strong>a copy of the consumer report it relied on</strong> and <strong>a copy of "A Summary of Your Rights Under the Fair Credit Reporting Act"</strong>. The point of that step is to let you see the report and challenge anything wrong in it <em>before</em> the decision is final.</p>

<p><strong>After rejecting you</strong>, it must tell you that the decision was based on the report, give you the reporting company's name, address and phone number, state that the reporting company did not make the decision, and tell you that you may dispute the report's accuracy and get a free copy within 60 days.</p>

<p>Inaccurate background reports are common. If you are rejected after a check and receive none of that, something has gone wrong in the employer's process, and the report itself is worth obtaining.</p>

<h2 id="work-authorisation">Work Authorisation and Form I-9</h2>

<p>Every US employer must verify that a new hire is authorised to work, using <strong>Form I-9</strong>. The timing is fixed: "within three business days of the date employment begins", the employer or an authorised representative must complete Section 2, and if someone is hired for fewer than three business days, Section 2 must be completed "no later than the first day of employment".</p>

<p>Employers who fail to complete the form properly risk violating section 274A of the Immigration and Nationality Act and civil penalties, and they may terminate an employee who does not present acceptable documentation inside the three-day window.</p>

<p>Two things follow. Have your documents ready before the start date, because the clock is short and it is not negotiable. And note that this is a verification step after hiring, not a screening question at application &mdash; an employer asking for immigration documents before an offer is not following the normal sequence.</p>

<img src="/public/storage/blogs/how-to-apply-react-developer-jobs-usa-interview.jpg" alt="React developer technical interview with a live coding exercise" />

<h2 id="commercial-pipeline">The Commercial Pipeline, and What Each Stage Is Testing</h2>

<p>Nothing regulates this one, so it rewards preparation instead:</p>

<ul>
    <li><strong>The screen.</strong> A human spends well under a minute. Deployed project links beat repository links, because a live URL gets clicked and a repository often does not.</li>
    <li><strong>The recruiter call.</strong> Ask which occupation the role is benchmarked against and whether there is a published band. Both answers change what the rest of the process is worth to you.</li>
    <li><strong>The take-home.</strong> Scope it before you start, and ask how long they expect it to take. Handle error and loading states; that is usually what separates submissions more than styling does.</li>
    <li><strong>The live exercise.</strong> Narrate your reasoning. Most interviewers are scoring how you think far more than whether you finish.</li>
    <li><strong>System design, for senior roles.</strong> Rendering strategy, where state lives, and what you would choose not to build.</li>
</ul>

<p>Applying from a standing start is a different problem, and our <a href="/blog/entry-level-react-developer-jobs-in-usa">entry level React guide</a> covers what to build and how to read a junior advert. If the role is remote, settle the employment shape before the offer, because it changes your take-home pay substantially &mdash; our <a href="/blog/remote-react-developer-jobs-in-usa">remote React guide</a> works through it.</p>

<h2 id="job-scams">Before You Send Anything, Check It Is Real</h2>

<p>Developer job scams are common, and the FTC gives one test that settles most of them: <strong>"Honest employers, including the federal government, will never ask you to pay to get a job. Anyone who does is a scammer."</strong></p>

<p>Alongside that:</p>

<ul>
    <li><strong>Money for nothing.</strong> "If someone offers you a job and claims that you can make a lot of money in a short period of time with little work, that's almost certainly a scam."</li>
    <li><strong>Cheques you are asked to forward.</strong> "No honest potential employer will ever send you a check to deposit and then tell you to send on part of the money." The cheque bounces later and you owe the bank the money.</li>
    <li><strong>Fees for equipment, training or certification.</strong> A real employer buys its own laptops.</li>
</ul>

<p>The FTC's own advice is to search the company independently and use official sources rather than responding to an unsolicited offer. For federal roles that means USAJOBS. For commercial ones, apply through the employer's own careers page wherever you can, and be wary of any process that moves to a messaging app and asks for bank details before an offer exists.</p>

<h2>Frequently Asked Questions</h2>

<h3>How do I apply for a React developer job in the USA?</h3>
<p>Through one of two systems. Commercial employers run their own process, usually a portfolio screen, a recruiter call, a take-home or live exercise and an offer. Federal agencies run OPM's competitive hiring process, which has six published stages and closes on a fixed date.</p>

<h3>What are the stages of federal hiring?</h3>
<p>Vacancy announcement, application and assessment, rating and ranking, certification of eligible candidates to the hiring manager, selection, and appointment to a career or career-conditional post.</p>

<h3>Do I have to be ranked first for a federal job?</h3>
<p>No. Under category rating, agencies place candidates into two or more quality categories and the manager may select anyone in the highest one. Veterans' preference operates as absolute preference within each category.</p>

<h3>What can an employer not ask me in a job application?</h3>
<p>Anything used to discriminate on race, colour, religion, sex including transgender status, sexual orientation and pregnancy, national origin, age 40 or over, disability or genetic information. Pre-offer questions about disability are explicitly prohibited.</p>

<h3>Can an employer ask my salary history?</h3>
<p>There is no single federal ban. Restrictions exist but are set by state and local law and vary, so check the rule where the role is based.</p>

<h3>What are my rights if an employer runs a background check?</h3>
<p>You must be told in writing, in a stand-alone notice, and give written permission. Before rejecting you over the report the employer must give you a copy of it and a Summary of Your Rights under the FCRA, and afterwards must tell you who produced it and that you may dispute it and get a free copy within 60 days.</p>

<h3>When do I complete Form I-9?</h3>
<p>The employer must complete Section 2 within three business days of the date employment begins, or by the first day if the job lasts fewer than three business days. It is a post-hire verification step, not an application screening question.</p>

<h3>How do I tell if a developer job advert is a scam?</h3>
<p>The clearest test is money. The FTC states that honest employers, including the federal government, will never ask you to pay to get a job. Treat forwarded cheques, equipment fees and quick-money promises as scams.</p>

<h2>People Also Search For</h2>

<h3>React developer application process</h3>
<p>Portfolio screen, recruiter call, take-home or live coding, system design for senior roles, then offer. Deployed links get opened; repositories often do not.</p>

<h3>Federal hiring process steps</h3>
<p>Announcement, application and assessment, rating and ranking, certification, selection, appointment, under OPM's competitive hiring rules.</p>

<h3>What is category rating in federal hiring</h3>
<p>Candidates are placed into two or more quality categories and the hiring manager may select anyone in the top category, replacing the old rule of three.</p>

<h3>Veterans preference federal jobs</h3>
<p>Absolute preference within each quality category, and an agency may not select a non-preference eligible without requesting to pass over the preference eligible.</p>

<h3>Background check rights before a job offer</h3>
<p>Stand-alone written notice, your written permission, and a copy of the report plus a Summary of Your Rights before any adverse decision is finalised.</p>

<h3>Illegal interview questions USA</h3>
<p>Anything tied to a protected characteristic, and any pre-offer question about disability. Pre-employment inquiries should be limited to what determines whether you are qualified.</p>

<h3>Form I-9 three day rule</h3>
<p>Section 2 must be completed within three business days of employment beginning, or by the first day for jobs shorter than that.</p>

<h3>Job scam warning signs</h3>
<p>Any request for payment to get the job, a cheque you are told to deposit and partly forward, fees for equipment or certification, and promises of high pay for little work.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/react-developer-jobs-in-usa">React Developer Jobs in USA</a> &mdash; the main guide: pay bands, the toolchain that dates you, and the clearance filter on federal work.</li>
    <li><a href="/blog/remote-react-developer-jobs-in-usa">Remote React Developer Jobs in USA</a> &mdash; W-2 against 1099, self-employment tax, and which state ends up taxing you.</li>
    <li><a href="/blog/entry-level-react-developer-jobs-in-usa">Entry Level React Developer Jobs in USA</a> &mdash; what BLS records about experience, and the paid apprenticeship route.</li>
    <li><a href="/blog/front-end-developer-jobs-in-usa">Front End Developer Jobs in USA</a> &mdash; accessibility law and Core Web Vitals, the two measurable skills that move an offer.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li><a href="https://www.opm.gov/policy-data-oversight/hiring-information/competitive-hiring/" target="_blank" rel="noopener nofollow">OPM, competitive hiring process</a></li>
    <li><a href="https://www.eeoc.gov/prohibited-employment-policiespractices" target="_blank" rel="noopener nofollow">EEOC, prohibited employment policies and practices</a></li>
    <li><a href="https://www.ftc.gov/business-guidance/resources/background-checks-what-employers-need-know" target="_blank" rel="noopener nofollow">FTC, background checks and the Fair Credit Reporting Act</a></li>
    <li><a href="https://consumer.ftc.gov/articles/job-scams" target="_blank" rel="noopener nofollow">FTC, job scams</a></li>
    <li><a href="https://www.uscis.gov/i-9-central" target="_blank" rel="noopener nofollow">USCIS, I-9 Central</a></li>
    <li><a href="https://www.usajobs.gov/" target="_blank" rel="noopener nofollow">USAJOBS, the official federal job site</a></li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Hiring rules, state and local restrictions and agency procedures change &mdash; confirm the current position with OPM, the EEOC, the FTC and USCIS, and take professional advice before relying on any of it.</p>
HTML;
    }
}
