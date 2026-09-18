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
 * "Account Manager Jobs in USA" — the client-facing commercial career. The
 * title is not a BLS occupation, so the guide prices it from the sales
 * representative occupations that actually contain these jobs and shows the
 * real titles three large employers use.
 *
 * Corrections to the draft (checked against the BLS Occupational Outlook
 * Handbook and the OEWS API, salesforce.com, hubspot.com and
 * careers.adobe.com, September 2026):
 *
 * 1. The draft gives $164,350 as the BLS median for sales managers. That is
 *    the mean. The OOH median is $148,270 a year, and the lowest 10% earn
 *    under $73,170.
 *
 * 2. The draft says sales managers have about 29,000 openings a year. 29,000
 *    is the ten-year employment change; the OOH projects about 47,300
 *    openings a year.
 *
 * 3. The draft prices account managers off sales managers, who are
 *    supervisors. The OEWS occupations that hold most account managers have
 *    May 2025 medians of $69,990 (sales reps of services), $72,080 and
 *    $104,920 (wholesale and manufacturing) and $64,820 (advertising sales).
 *
 * 4. The draft implies HubSpot advertises account manager roles. HubSpot's
 *    US openings use Account Executive; Salesforce uses Account Executive and
 *    Account Partner; Adobe is the one of the three using Account Manager and
 *    Account Director.
 *
 * 5. The draft says a bachelor's degree is the BLS entry level. That applies
 *    to sales managers; for wholesale and manufacturing sales reps the OOH
 *    says education varies by product type, and advertising sales agents
 *    typically need a high school diploma.
 *
 * 6. The draft has no outlook figures for the analyst-level jobs. Wholesale
 *    and manufacturing sales reps are projected at 0% growth with about
 *    123,400 openings a year, and advertising sales agents at -7%.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AccountManagerJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-account-manager-jobs.html';

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
        $title = 'Account Manager Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Account manager is not a BLS occupation, so the guides quote the wrong number. The sales jobs that hold these roles had May 2025 medians of $69,990 to $104,920, and Salesforce, HubSpot and Adobe each use a different title.',
                'content' => $content,
                'featured_image' => 'blogs/account-manager-jobs-in-usa.jpg',
                'tags' => 'account manager jobs usa, account manager salary, account executive jobs, key account manager, customer success manager jobs, crm skills, sales manager salary bls, remote account manager jobs',
                'meta_title' => 'Account Manager Jobs in USA: Pay, Titles and Skills',
                'meta_description' => 'Account manager jobs in the USA: what the BLS really measures, $69,990 to $104,920 medians, remote roles, and the titles Salesforce, HubSpot and Adobe use.',
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
            ['name' => 'US Technology, Agency and B2B Employers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'us-account-manager-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'sales'],
            ['name' => 'Sales']
        );

        Job::updateOrCreate(
            [
                'position' => 'Account Manager — Client, Key and Strategic Account Roles at US Technology, Agency and B2B Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time, with remote and hybrid arrangements at many employers',
                'language' => 'English',
                // Base pay plus commission varies by employer and territory,
                // so no single band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Account manager, account executive and customer success roles at US technology, agency and B2B employers. Pay is usually base salary plus commission.',
                'seo_keywords' => 'account manager jobs, account executive jobs, key account manager jobs, customer success manager jobs, client services jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Technology companies, agencies, manufacturers and B2B service providers across the United States hire account managers to look after existing customers, keep them renewing and grow the accounts.</p>

<h3>What the work involves</h3>
<p>Managing a book of customer accounts, running account reviews, understanding each customer's goals, coordinating internal teams, handling questions and escalations, tracking account performance in a CRM and supporting renewals and expansion.</p>

<h3>Common requirements</h3>
<ul>
    <li>Customer-facing experience in sales, customer service, client services or account coordination</li>
    <li>Clear written and spoken communication, and comfort presenting to customers</li>
    <li>CRM experience, such as Salesforce or HubSpot</li>
    <li>Comfort with targets: many roles carry renewal or expansion quotas</li>
    <li>A bachelor's degree for many corporate roles, though employers often accept equivalent experience</li>
    <li>Authorization to work in the United States</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, quotas, remote policy and sponsorship rules are set by each employer &mdash; not by JobGader. Read the current job posting before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Account manager jobs in the USA are about keeping and growing existing customers: running the relationship, coordinating delivery, handling renewals and finding room to expand the account.</strong> The title spans software, agencies, manufacturing, insurance and logistics, and it is <strong>not a Bureau of Labor Statistics occupation</strong>, which is why most salary guides quote a number that does not describe the job at all.</p>

<p>This guide prices the role from the occupations that actually contain it, shows the titles three big employers really advertise, and covers skills, remote work and how to apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-account-manager-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; Browse Account Manager Jobs in USA &rarr;
    </a>
</div>

<h2>What Does an Account Manager Do?</h2>

<p>An account manager owns the relationship with customers the company already has. Day to day that usually means:</p>

<ul>
    <li>managing a portfolio of assigned accounts;</li>
    <li>running account reviews and regular check-ins;</li>
    <li>learning each customer's business goals and how they use the product or service;</li>
    <li>coordinating with sales, support, product, finance and delivery teams;</li>
    <li>answering questions and owning escalations;</li>
    <li>tracking usage, satisfaction and account performance;</li>
    <li>preparing reports and presentations;</li>
    <li>driving renewals and spotting expansion opportunities.</li>
</ul>

<p>Account management overlaps with sales and customer success, but the centre of gravity is <strong>existing customers</strong> rather than new logos.</p>

<h2>The Pay Number Most Guides Quote Is Wrong</h2>

<p>Career guides usually price account managers off the BLS <strong>sales managers</strong> page, and they usually quote <strong>$164,350</strong>. Two problems with that:</p>

<ol>
    <li><strong>$164,350 is the mean, not the median.</strong> The BLS median for sales managers in May 2025 was <strong>$148,270</strong> a year ($71.28 an hour), and the lowest 10% earned under $73,170 while the highest 10% earned over $290,540.</li>
    <li><strong>Sales managers supervise sales teams.</strong> Most account managers are individual contributors, so the occupation does not describe them.</li>
</ol>

<p>The occupations that do hold most account manager jobs are the sales representative groups. Their May 2025 national figures:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">BLS occupation</th>
            <th style="padding:10px;text-align:left;">Jobs</th>
            <th style="padding:10px;text-align:left;">Median pay</th>
            <th style="padding:10px;text-align:left;">Typical account manager</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sales reps of services</strong> (41-3091)</td><td style="padding:10px;">1,256,010</td><td style="padding:10px;"><strong>$69,990</strong></td><td style="padding:10px;">SaaS, media, staffing, logistics</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Wholesale and manufacturing</strong> (41-4012)</td><td style="padding:10px;">1,238,190</td><td style="padding:10px;">$72,080</td><td style="padding:10px;">Consumer goods, distribution</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Technical and scientific</strong> (41-4011)</td><td style="padding:10px;">284,800</td><td style="padding:10px;">$104,920</td><td style="padding:10px;">Industrial, medical, software</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Advertising sales agents</strong> (41-3011)</td><td style="padding:10px;">91,700</td><td style="padding:10px;">$64,820</td><td style="padding:10px;">Agency and media accounts</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sales managers</strong> (11-2022)</td><td style="padding:10px;">650,100</td><td style="padding:10px;">$148,270</td><td style="padding:10px;">The team lead above you</td></tr>
    </tbody>
</table>
</div>

<p>The spread inside one occupation shows how much the title varies. For sales representatives of services, the 10th percentile earned <strong>$37,980</strong>, the median $69,990, the 75th percentile $100,480 and the 90th percentile <strong>$148,840</strong>. Commission structure, territory and industry move you along that line far more than the job title does.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/account-manager-jobs-in-usa-client-meeting.jpg" alt="An account manager presenting to colleagues around a boardroom table with a city skyline behind her" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Account managers spend much of the week in reviews with customers and internal teams.</figcaption>
</figure>

<h2>Account Manager, Account Executive or Customer Success?</h2>

<p>The same work carries different titles at different companies, so search for all of them. What the three employers below actually advertised in September 2026:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Employer</th>
            <th style="padding:10px;text-align:left;">Titles used</th>
            <th style="padding:10px;text-align:left;">Example roles</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Salesforce</strong></td><td style="padding:10px;">Account Executive, Account Partner, Customer Success Manager</td><td style="padding:10px;">Small, Medium and Growth Business Account Executive (San Francisco, New York, Irvine, Atlanta); Account Partner Director, CPG (Atlanta)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>HubSpot</strong></td><td style="padding:10px;">Account Executive, BDR &mdash; <strong>no "Account Manager"</strong></td><td style="padding:10px;">Account Executive, Enterprise and Small Business (Remote - USA); Senior Account Executive, Mid-Market (Remote - USA)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Adobe</strong></td><td style="padding:10px;">Account Manager, Account Director</td><td style="padding:10px;">Enterprise Sales Account Manager, State &amp; Local Government (Remote, Minnesota); Enterprise Sales Account Director (Seattle)</td></tr>
    </tbody>
</table>
</div>

<p>In plain terms: an <strong>account executive</strong> is usually measured on new business, an <strong>account manager</strong> on retaining and growing existing customers, and a <strong>customer success manager</strong> on adoption and outcomes. Titles blur, so read the responsibilities and the target before the title.</p>

<p>Pay transparency laws mean some postings publish the band. Adobe's Seattle Enterprise Sales Account Director listing, for example, showed a US range of <strong>$229,000 to $369,600</strong> a year, with a Washington-specific range of $250,300 to $362,375 &mdash; a reminder that senior enterprise account roles sit far above the occupation median.</p>

<h2>What Qualifications Do You Need?</h2>

<p>There is no single rule, and the BLS entry-level education differs by occupation:</p>

<ul>
    <li><strong>Wholesale and manufacturing sales reps:</strong> education "varies by product type", with moderate-term on-the-job training and no prior experience required.</li>
    <li><strong>Advertising sales agents:</strong> typically a high school diploma or equivalent.</li>
    <li><strong>Sales managers:</strong> a bachelor's degree plus less than five years of related work experience.</li>
</ul>

<p>Most corporate account manager postings ask for a bachelor's degree in business, marketing or communications <em>or</em> equivalent experience, plus one to five years in a customer-facing role. Senior, key and strategic account jobs add experience of large customers, contracts and quotas.</p>

<h2>What Skills Do Account Managers Need?</h2>

<p>The BLS lists these qualities for sales managers, and they map directly onto account management:</p>

<ul>
    <li><strong>Analytical skills</strong> &mdash; tracking and interpreting data to evaluate trends and set goals;</li>
    <li><strong>Communication</strong> and <strong>interpersonal skills</strong>;</li>
    <li><strong>Customer-service skills</strong>;</li>
    <li><strong>Computer skills</strong>;</li>
    <li><strong>Leadership</strong> and <strong>organizational skills</strong>.</li>
</ul>

<p>Employers add the commercial layer: CRM fluency (Salesforce, HubSpot, Microsoft Dynamics or Zoho), negotiation and renewals, forecasting, presentation skills and enough project coordination to keep internal teams moving. You do not need every CRM &mdash; showing that you kept clean records, tracked opportunities and reported accurately matters more.</p>

<h2>Are Account Manager Jobs Remote?</h2>

<p>Many are, especially in software. <strong>HubSpot</strong> lists <strong>Remote - USA</strong> as a location on most of its US sales openings and publishes its split: <strong>72% @home</strong>, <strong>21% @flex</strong> (home plus regular office visits) and <strong>7% @office</strong> (in the office at least three days most weeks). <strong>Salesforce</strong> says of its US roles: "While some roles are tied to specific locations, many offer location flexibility." <strong>Adobe</strong> advertises remote state-level account roles alongside office-based ones.</p>

<p>Outside software, account managers who cover a territory usually travel to customers, so "remote" often means "home-based with visits" rather than fully remote.</p>

<h2>Is There Demand for Account Managers?</h2>

<p>Demand depends on which occupation your job sits in:</p>

<ul>
    <li><strong>Wholesale and manufacturing sales reps:</strong> 1,571,400 jobs, projected <strong>0% growth</strong> from 2025 to 2035, but about <strong>123,400 openings a year</strong> as people move on.</li>
    <li><strong>Sales managers:</strong> 4% growth, about <strong>47,300 openings a year</strong>.</li>
    <li><strong>Advertising sales agents:</strong> a projected <strong>7% decline</strong>, with about 7,800 openings a year.</li>
</ul>

<p>So the hiring is in replacement, not expansion. Industry matters more than the national number: software and B2B services keep hiring account teams while print and traditional media shrink.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/account-manager-jobs-in-usa-account-review.jpg" alt="An account manager showing a performance dashboard on a tablet to two clients in a modern office lounge" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Account reviews are where renewals and expansion are won.</figcaption>
</figure>

<h2>Can You Become an Account Manager Without Experience?</h2>

<p>Rarely straight away, but the feeder roles are well worn: customer service representative, sales development or business development representative, account coordinator, customer success associate, client services assistant and marketing coordinator. Each gives you the three things postings screen for &mdash; customer conversations, CRM records and reporting.</p>

<p>Our <a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a> guide covers the most common entry point and what it pays.</p>

<h2>Which Industries Hire Account Managers?</h2>

<p>Software and SaaS, advertising and marketing agencies, media, financial services, insurance, healthcare, telecommunications, manufacturing, logistics, professional services, e-commerce and B2B services all use the title. An agency account manager coordinates campaigns and creative teams; a software account manager owns renewals, usage and expansion; a distribution account manager handles orders, pricing and supply.</p>

<h2>Can Foreign Workers Apply?</h2>

<p>Private employers set their own work-authorization rules, and we could not find a general sponsorship statement on the Salesforce, HubSpot or Adobe careers pages &mdash; which means <strong>the individual posting is the only reliable source</strong>. Look for the work authorization question in the application form before you invest time in it.</p>

<p>If you are weighing up routes into the US, see our <a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> guide for the visa realities.</p>

<h2>How to Find Account Manager Jobs in the USA</h2>

<ol>
    <li><strong>Pick an industry</strong> where your background already means something: software, agency, healthcare, logistics, manufacturing.</li>
    <li><strong>Search every title:</strong> Account Manager, Senior/Strategic/Key/National/Enterprise Account Manager, Client Account Manager, Account Director, Client Services Manager, Customer Success Manager and Account Executive.</li>
    <li><strong>Quantify your resume:</strong> accounts managed, revenue or renewal targets, retention and renewal rates, expansion sold, satisfaction scores, contract values.</li>
    <li><strong>Name the CRM</strong> you have used and what you did in it &mdash; records, pipeline, reporting, account reviews.</li>
    <li><strong>Apply on the employer's own careers site,</strong> such as <a href="https://www.salesforce.com/company/careers/" target="_blank" rel="noopener">Salesforce</a>, <a href="https://www.hubspot.com/careers/jobs" target="_blank" rel="noopener">HubSpot</a> or <a href="https://careers.adobe.com/us/en" target="_blank" rel="noopener">Adobe</a>, where the posting and the pay range are current.</li>
</ol>

<h2>What Does the Career Path Look Like?</h2>

<p>A common progression runs <strong>Account Coordinator &rarr; Account Manager &rarr; Senior Account Manager &rarr; Strategic or Enterprise Account Manager &rarr; Account Director or sales leadership</strong>. Others move sideways into customer success, partnerships or industry specialisation. Moving up usually means bigger accounts and a bigger number, not more people to manage.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the average salary for an account manager in the USA?</h3>
<p>There is no BLS account manager category. The occupations that hold these jobs had May 2025 medians of $69,990 (sales reps of services), $72,080 and $104,920 (wholesale and manufacturing) and $64,820 (advertising sales agents).</p>

<h3>Is $164,350 the account manager salary?</h3>
<p>No. That figure is the BLS mean for sales managers, who supervise sales teams. Their median is $148,270, and most account managers are individual contributors.</p>

<h3>What is the difference between an account manager and an account executive?</h3>
<p>Account managers usually own existing customers, renewals and growth; account executives usually chase new business. Companies differ: HubSpot advertises US sales roles as Account Executive, while Adobe uses Account Manager and Account Director.</p>

<h3>Do account managers need a degree?</h3>
<p>Often preferred, not always required. The BLS gives sales managers a bachelor's degree, but for wholesale and manufacturing sales reps education varies by product type, and advertising sales agents typically need a high school diploma.</p>

<h3>Are account manager jobs remote in the USA?</h3>
<p>Many software roles are. HubSpot lists Remote - USA on its US sales jobs and says 72% of staff work @home, while Salesforce says many roles offer location flexibility.</p>

<h3>Are account manager jobs in demand?</h3>
<p>Openings come mostly from turnover. Wholesale and manufacturing sales reps are projected at 0% growth with about 123,400 openings a year; sales managers grow 4% with about 47,300 openings a year.</p>

<h3>What CRM should I learn?</h3>
<p>Salesforce and HubSpot cover most postings, with Microsoft Dynamics and Zoho common in smaller companies. Employers care more that you can keep accurate records and report from them.</p>

<h3>Can I move into account management from customer service?</h3>
<p>Yes, that is one of the most common routes, along with sales development, account coordination and client services.</p>

<h2>People Also Search For</h2>

<h3>Account manager salary</h3>
<p>$69,990 median for sales reps of services, the occupation that holds most of these jobs.</p>

<h3>Key account manager jobs</h3>
<p>Senior roles covering the largest customers, usually with contract and quota responsibility.</p>

<h3>Account executive jobs</h3>
<p>Usually new-business sales; HubSpot and Salesforce advertise US roles under this title.</p>

<h3>Customer success manager jobs</h3>
<p>Focused on adoption and outcomes; Salesforce lists CSM roles across California and Virginia.</p>

<h3>Remote account manager jobs</h3>
<p>Common in software: HubSpot lists Remote - USA on most US sales openings.</p>

<h3>Entry level account management jobs</h3>
<p>Look for account coordinator, BDR and customer success associate roles.</p>

<h3>Account manager skills</h3>
<p>Communication, analytical skills, CRM fluency, negotiation and organisation.</p>

<h3>Sales manager salary BLS</h3>
<p>$148,270 median in May 2025, with a $73,170 tenth percentile.</p>

<h2>More Job Guides</h2>

<p>Looking at client-facing and commercial careers? These cover them:</p>

<ul>
    <li><a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a> &mdash; the most common route into account management, priced from BLS.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; the marketing side of the same accounts.</li>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> &mdash; the same career in a different market.</li>
    <li><a href="/blog/finance-analyst-jobs-in-canada">Finance Analyst Jobs in Canada</a> &mdash; the numbers career, with Job Bank pay and the CFA rule.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; remote client work and what it really pays.</li>
    <li><a href="/blog/data-scientist-jobs-in-usa">Data Scientist Jobs in USA</a> &mdash; the analytics route for people who prefer the data to the meetings.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using BLS Occupational Outlook Handbook and OEWS data and the official career sites of Salesforce, HubSpot and Adobe. Pay, openings and remote policies change. Always read the current official job posting before applying.</p>
HTML;
    }
}
