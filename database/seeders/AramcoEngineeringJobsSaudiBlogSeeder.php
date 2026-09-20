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
use Illuminate\Support\Str;

/**
 * "How to Apply for Aramco Engineering Jobs in Saudi Arabia" — an employer
 * guide built on what Aramco itself publishes, which on the two questions
 * readers care about most is less than the drafts circulating online claim.
 *
 * Corrections to the draft (checked against aramco.com, aramco.jobs and
 * zatca.gov.sa, September 2026):
 *
 * 1. The draft quotes salary figures. Aramco publishes no base-salary number,
 *    range or grade scale anywhere in its careers material. Its compensation
 *    page describes structure only, so this guide publishes the allowance
 *    formulas and says plainly that no pay figure exists.
 *
 * 2. The draft's apply link is careers.aramco.com/expat_us/go/For-US-
 *    Applicants/7717823/. That page is real but sits on the US-scoped
 *    /expat_us/ branch of the applicant tracking system, so it is the wrong
 *    gateway for a South Asian reader. The guide links the global
 *    for-international-applicants page instead.
 *
 * 3. The draft treats the roles as open to any engineering graduate. Aramco's
 *    own line is that it "generally" seeks five to 10 years of applicable
 *    experience for international hires, which the guide leads with.
 *
 * 4. The draft describes the offer as an offer. Aramco issues a written
 *    conditional offer with five named conditions, and the guide quotes them.
 *
 * 5. The retirement plan cash-payment percentage that circulates on third
 *    party sites could not be confirmed on an Aramco page, so no percentage
 *    is published here.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AramcoEngineeringJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.aramco.com/en/careers/for-international-applicants';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Apply for Aramco Engineering Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Aramco publishes no salary figure for any engineering role, and asks international hires for five to 10 years of experience. Here is what the company actually documents about pay, benefits, the conditional offer and the route to apply.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia.jpg',
                'tags' => 'aramco engineering jobs, saudi aramco careers, aramco jobs for foreigners, dhahran engineering jobs, aramco application process, saudi arabia engineering jobs, aramco benefits, aramco recruitment',
                'meta_title' => 'Aramco Engineering Jobs in Saudi Arabia: How to Apply',
                'meta_description' => 'Aramco engineering jobs: the experience bar, the five-condition offer, the benefits Aramco documents, and why it publishes no salary figure at all.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Saudi Aramco, Dhahran'],
            ['type' => 'Company', 'display_reference' => 'saudi-aramco-dhahran']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Engineer, Saudi Aramco, Dhahran and Other Saudi Locations',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Company schedule, with some roles on rotation at remote sites',
                'language' => 'English',
                // Aramco publishes no base salary figure, range or grade scale
                // on any of its careers pages, so nothing is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Engineering, geosciences and drilling roles with Saudi Aramco, recruited internationally through the company careers portal.',
                'seo_keywords' => 'aramco engineering jobs, saudi aramco careers, dhahran engineering jobs, aramco jobs for foreigners',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Saudi Aramco recruits internationally for engineering, geosciences, drilling and research roles, alongside finance, law, education and other administrative functions, with Dhahran as its main base.</p>

<h3>What the work involves</h3>
<p>Discipline engineering across upstream and downstream operations, project and plant work at Saudi sites, and technical collaboration with global teams.</p>

<h3>What Aramco documents</h3>
<ul>
    <li>A stated preference for five to 10 years of applicable experience on international hires</li>
    <li>Assessment that can include technical, analytical aptitude and behavioural evaluations</li>
    <li>Interviews held at its London, Houston or Singapore offices, or by video</li>
    <li>A written conditional offer naming five conditions that must be met before you travel</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> Aramco publishes no salary figure for any role, and any number you see elsewhere is a third-party estimate &mdash; not by JobGader. Aramco states it will never ask applicants for payment at any point in recruitment.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply through Aramco's international applicants page, and expect the company to look for five to 10 years of applicable experience.</strong> That experience line is Aramco's own wording, and it is the single biggest filter between an engineering graduate and a job at this company.</p>

<p>The second thing to know before you read another guide on this subject: <strong>Aramco does not publish a salary figure for any role, anywhere.</strong> Every number you have seen is an estimate from a third-party site.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.aramco.com/en/careers/for-international-applicants" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9881; Aramco Careers for International Applicants &rarr;
    </a>
</div>

<h2>The Experience Bar Most Guides Leave Out</h2>

<p>Aramco's page for international applicants states it directly: <strong>"We generally seek candidates who possess a minimum of five to 10 years of applicable experience."</strong></p>

<p>The word doing the work there is "generally". It is not an absolute rule, and Aramco runs separate graduate tracks. But those graduate tracks sit under its <em>Saudi applicants</em> pages, not the international one. For someone applying from Pakistan, India or the Philippines with a fresh engineering degree and no site experience, the honest reading is that the international route is not aimed at you yet.</p>

<p>That is worth knowing before you spend months on applications. The realistic sequence is to build five or more years on projects at home or in the wider Gulf, then apply. Our guide on <a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">how to get a job in Saudi Arabia as a foreigner</a> covers the sponsorship system you will be entering either way.</p>

<h3>The three routes Aramco publishes</h3>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">Route</th>
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">Who it is for</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">International applicants</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">Non-Saudi professionals, generally with five to 10 years of applicable experience</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;">Saudi applicants &mdash; graduates</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">Saudi nationals entering from university</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">Saudi applicants &mdash; experienced professionals</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">Saudi nationals already in the workforce</td>
        </tr>
    </tbody>
</table>

<h2>What Aramco Actually Recruits For</h2>

<p>The company describes its hiring across <strong>"engineering, geosciences, drilling, R&amp;D, as well as education, finance, law, and other administrative areas."</strong></p>

<p>So "Aramco engineering jobs" is narrower than the company's real intake. If your background is mechanical, chemical, electrical, civil or petroleum engineering, you are in the core group. If it is finance, law or education, Aramco hires there too and those postings get far less attention.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia-dhahran.jpg" alt="Aramco engineer at a Saudi Arabian plant site" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Hiring Process, In Aramco's Own Words</h2>

<p>This part is documented properly, across the company's <a href="https://www.aramco.com/en/careers/for-international-applicants/hiring-process" target="_blank" rel="noopener nofollow">hiring process page</a> and its recruitment overview:</p>

<ol>
    <li><strong>Application.</strong> If your CV shows the skills Aramco is looking for, the hiring organisation asks you to complete a full Application for Employment.</li>
    <li><strong>Assessment.</strong> <strong>"In some cases, an initial assessment may be required for some roles, which can include technical, analytical aptitude, and/or behavioral evaluations."</strong></li>
    <li><strong>Interview.</strong> A preliminary phone interview with Talent Acquisition, then technical interviews. Aramco states these <strong>take place at its London, Houston or Singapore offices</strong>, with video interviews as an alternative containing similar technical assessment.</li>
    <li><strong>The offer.</strong> A regional office issues a <strong>written conditional offer of employment</strong> specifying the conditions that must be met.</li>
    <li><strong>Relocation.</strong> An advisor handles medical examination and background checks, clearance for dependents attending Aramco Schools, visa and travel arrangements.</li>
    <li><strong>Pre-departure orientation,</strong> then arrival, where a company representative meets you and takes you to your accommodation.</li>
</ol>

<p>Note what that sequence means in practice: the interview is not in Saudi Arabia. If you are shortlisted from South Asia, the technical stage is most likely to be a video interview or a trip to Aramco's Singapore or London office.</p>

<h2>The Offer Is Conditional, And Aramco Names the Conditions</h2>

<p>This is the step that catches people out, because a conditional offer reads like a job. Aramco's recruitment overview lists what must still be cleared:</p>

<ul>
    <li><strong>Medical examinations</strong></li>
    <li><strong>Background investigations</strong></li>
    <li><strong>Housing confirmation from Saudi Aramco</strong></li>
    <li><strong>The enrollment of dependent children in Saudi Aramco Expatriate Schools</strong>, where applicable</li>
    <li><strong>Permission to secure a visa</strong> to work and reside in-country, from the Saudi Arabian government</li>
</ul>

<p><strong>Do not resign your current job on a conditional offer.</strong> The visa permission in that last line is a government decision, not Aramco's, and the housing and schooling confirmations are real gates rather than paperwork.</p>

<h2>Pay: Aramco Publishes No Figure, And That Is the Finding</h2>

<p><strong>There is no Aramco salary number on any Aramco page.</strong> Not a range, not a grade scale, not a starting figure for engineers. Its own compensation page describes the <em>structure</em> of pay entirely in formulas and multiples of a base salary it never states.</p>

<p>Here is everything the company does put in writing, from the compensation page on its Saudi applicants section:</p>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">Component</th>
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">What Aramco states</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">Base salary</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">Fixed, paid monthly or hourly, set "based on an industry standard". No figure given.</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;">Housing allowance</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">Three months' base salary, with a <strong>minimum of SAR 30,000</strong></td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">Ramadan bonus</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">Equivalent to one month's base salary</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;">Settling-in allowance</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">60% of one month's base salary if single, 100% if married</td>
        </tr>
    </tbody>
</table>

<p>That SAR 30,000 housing floor is the only absolute currency amount Aramco publishes in the whole compensation section, and it is an allowance minimum rather than a salary.</p>

<p><strong>So treat every Aramco salary figure you find online as a third-party estimate.</strong> Glassdoor, PayScale and the salary blogs disagree with each other, none of them is sourced from Aramco, and averaging them does not make them true. Ask for the number in writing when you receive an offer &mdash; that is the first time a real figure exists.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia-team.jpg" alt="Aramco engineering team at work in Saudi Arabia" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Benefits Are Documented, Unlike the Pay</h2>

<p>This is where Aramco is specific, and it is the part worth weighing against a higher headline salary somewhere else:</p>

<ul>
    <li><strong>38 days of vacation leave per year, plus travel days</strong> for expatriate employees. The company's corporate page phrases it as up to 38 calendar days of annual paid leave.</li>
    <li><strong>9 to 11 paid company holidays</strong> each year on top of that.</li>
    <li><strong>Annual paid round-trip travel</strong> to your home country for you and eligible dependents residing in-country. This is a yearly benefit, not a one-off on joining or leaving.</li>
    <li><strong>Education assistance for dependent children</strong>, which may include Aramco's own private schools, international schools in the local community, or international boarding schools anywhere in the world.</li>
    <li><strong>Healthcare through Johns Hopkins Aramco Healthcare</strong>, the company's subsidiary medical network, or contracted hospitals and clinics.</li>
    <li>Retirement, education and healthcare programmes are named as benefit categories. Aramco does not publish the retirement contribution rate on these pages, so any percentage you see quoted elsewhere is unverified.</li>
</ul>

<h2>Tax: What You Keep</h2>

<p><strong>Saudi Arabia levies no personal income tax on employment income</strong>, for Saudi nationals or expatriates. The Income Tax Law administered by ZATCA applies to corporate profits, non-resident business income and shares held by non-Saudi partners &mdash; ordinary salary does not fall within its scope.</p>

<p>What you do pay is <strong>VAT at 15%</strong> on most goods and services, raised from 5% with effect from 1 July 2020. That rate matters more than people expect once you are running a household and paying school fees, rent and utilities locally.</p>

<p>There is no automatic pension or social-insurance deduction comparable to income tax for expatriates, but your own home country may still tax worldwide income. Check that before you assume a tax-free salary is entirely tax-free.</p>

<h2>The Link Most Guides Give You Is the Wrong One</h2>

<p>You will repeatedly see <code>careers.aramco.com/expat_us/go/For-US-Applicants/</code> given as the Aramco application link. <strong>That page is genuine, but it sits on the US-scoped branch of Aramco's applicant tracking system</strong> &mdash; the "expat_us" in the path is not decoration. There is a matching "/saudi/" branch for the Saudi-applicant track.</p>

<p>For anyone applying from South Asia, the correct starting point is the global <a href="https://www.aramco.com/en/careers/for-international-applicants" target="_blank" rel="noopener nofollow">for-international-applicants</a> page, which is not region-gated and links onward to the recruitment portal. Aramco also states that it <strong>only accepts resumes uploaded on its Recruitment Portal or Talent Community</strong>, so a CV emailed to a recruiter is not an application.</p>

<h2>Recruitment Fraud: Aramco's Own Warning</h2>

<p>Aramco publishes a recruitment disclaimer, and it is unusually clear:</p>

<p><strong>"Neither Saudi Aramco nor any of the organizations that recruit on our behalf will ever ask for any payments from applicants at any point in the recruitment process."</strong></p>

<p>It adds that <strong>you will always meet an Aramco representative in person or virtually for an interview before any formal offer is made.</strong> An offer that arrives without an interview is not an Aramco offer.</p>

<p>The warning signs it lists:</p>

<ul>
    <li>Any request for money &mdash; visa fees, taxes, travel expenses, processing charges</li>
    <li>Requests for passport or bank details early in the process</li>
    <li>A recruiter writing from a free email account such as Yahoo, Gmail, Hotmail or Live.com</li>
    <li>Hidden caller ID on phone contact</li>
    <li>Poorly formatted documents and offer letters</li>
</ul>

<p>Suspicious approaches can be reported to <strong>antiabuse@aramco.com</strong>. If an agent in your city offers an Aramco job for a fee, that is the fraud Aramco is describing, and paying it buys nothing.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Be honest about the experience bar.</strong> Five to 10 years of applicable experience is the stated preference for international hires.</li>
    <li><strong>Start at the international applicants page,</strong> not a region-scoped ATS link someone posted in a group.</li>
    <li><strong>Upload your CV to the recruitment portal or Talent Community.</strong> That is the only channel Aramco says it accepts.</li>
    <li><strong>Prepare for technical and aptitude assessment,</strong> and for a phone screen before any technical interview.</li>
    <li><strong>Read the conditional offer carefully,</strong> confirm the salary figure in writing, and do not resign until medical, background, housing, schooling and visa permission are all cleared.</li>
    <li><strong>Pay nobody.</strong> Not an agent, not a recruiter, not for a visa.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can a fresh engineering graduate get an Aramco job?</h3>
<p>Not through the international route as it is written. Aramco states it generally seeks five to 10 years of applicable experience from international applicants. Its graduate tracks sit under the Saudi applicants section.</p>

<h3>How much does Aramco pay engineers?</h3>
<p>Aramco publishes no salary figure, range or grade scale for any role. Every number circulating online is a third-party estimate. The only currency amount the company publishes is a housing allowance floor of SAR 30,000.</p>

<h3>Where are Aramco interviews held?</h3>
<p>Aramco states technical interviews for international applicants take place at its London, Houston or Singapore offices, with video interviews available as an alternative.</p>

<h3>What conditions are attached to an Aramco offer?</h3>
<p>Five: medical examinations, background investigations, housing confirmation, enrollment of dependent children in Aramco Expatriate Schools where applicable, and government permission to secure a work and residence visa.</p>

<h3>How much annual leave do Aramco expatriate employees get?</h3>
<p>38 days of vacation leave per year plus travel days, with a further 9 to 11 paid company holidays.</p>

<h3>Does Aramco pay for flights home?</h3>
<p>Yes. Employees and eligible dependents residing in-country are paid annually for round-trip travel costs from Saudi Arabia to their home country.</p>

<h3>Is salary in Saudi Arabia tax free?</h3>
<p>There is no personal income tax on employment income in Saudi Arabia. VAT is charged at 15% on most goods and services, and your home country may still tax worldwide income.</p>

<h3>Does Aramco ever charge applicants a fee?</h3>
<p>No. Aramco states that neither the company nor any organisation recruiting on its behalf will ever ask for payments from applicants at any point in the recruitment process.</p>

<h2>People Also Search For</h2>

<h3>Aramco careers login</h3>
<p>Accounts are created on the Aramco recruitment portal reached from the careers pages. Aramco only accepts resumes uploaded there or through its Talent Community.</p>

<h3>Saudi Aramco jobs for Pakistani citizens</h3>
<p>Aramco recruits internationally without publishing nationality-specific pages. The same five to 10 years experience preference applies, and the visa is a Saudi government decision.</p>

<h3>Aramco salary for mechanical engineer</h3>
<p>Not published by Aramco. Any figure attached to a discipline comes from third-party estimate sites, not from the company.</p>

<h3>Aramco Dhahran jobs</h3>
<p>Dhahran is Aramco's main base and appears in its work-location material alongside the Eastern Province and other Saudi sites. Vacancies are advertised through the recruitment portal rather than by location pages.</p>

<h3>Aramco graduate programme</h3>
<p>Graduate opportunities are published under Aramco's Saudi applicants section rather than its international applicants route.</p>

<h3>Saudi Aramco recruitment agency</h3>
<p>Aramco says organisations recruiting on its behalf will never ask applicants for payment. Any agency charging you a fee for an Aramco job is not operating on Aramco's terms.</p>

<h3>Aramco expatriate schools</h3>
<p>Aramco operates its own private schools and offers an education assistance plan that may cover international schools locally or boarding schools abroad. Enrollment clearance for dependents is one of the five offer conditions.</p>

<h3>Johns Hopkins Aramco Healthcare</h3>
<p>Aramco's subsidiary medical network, through which employees and dependents receive care, alongside contracted hospitals and clinics.</p>

<h2>More Job Guides</h2>

<p>Working in Saudi Arabia or comparing Gulf employers? These cover the ground:</p>

<ul>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; the sponsorship system, Saudization and what your contract has to say.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the site-based route into the same country.</li>
    <li><a href="/blog/mechanic-jobs-in-saudi-arabia">Mechanic Jobs in Saudi Arabia</a> &mdash; skilled trade work where experience counts more than a degree.</li>
    <li><a href="/blog/how-to-apply-for-bhp-mining-jobs-in-australia">How to Apply for BHP Mining Jobs in Australia</a> &mdash; another resources employer that does not publish pay.</li>
    <li><a href="/blog/how-to-apply-for-oman-air-cabin-crew-jobs">How to Apply for Oman Air Cabin Crew Jobs</a> &mdash; a Gulf employer with the same missing-salary problem.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Aramco's own careers, hiring process, compensation, benefits and recruitment disclaimer pages and ZATCA's published tax material. Aramco does not publish salary figures for any role, and recruitment criteria and benefits change. Always follow the current official Aramco announcement and the terms of your own written offer.</p>
HTML;
    }
}
