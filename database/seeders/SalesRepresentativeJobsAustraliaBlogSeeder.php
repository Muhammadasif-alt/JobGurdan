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
 * "How to Become a Sales Representative in Australia" — a guide for someone
 * aiming at a field, territory or B2B sales representative role and asking
 * what they need to be hired. The Sales Jobs in Australia guide owns the wider
 * market, retail award pay and the state-by-state real estate registrations,
 * so this one stays on the entry path, the training, the pay data and the
 * pay-structure traps specific to sales representatives.
 *
 * Corrections and clarifications to the draft (checked against Jobs and Skills
 * Australia, training.gov.au, the Fair Work Ombudsman, the Commercial Sales
 * Award, NSW Government and Home Affairs, September 2026):
 *
 * 1. The draft's pay comes from SEEK ($75,000-$95,000), BeBee ($79,500) and
 *    Glassdoor city figures. Jobs and Skills Australia puts median full-time
 *    earnings for Sales Representatives (ANZSCO 6113) at $1,692 a week, about
 *    $87,984 a year (ABS, May 2025), with 71,800 employed. No official source
 *    gives city figures, so they are dropped.
 *
 * 2. The draft says Year 12 is the baseline. It is the most common single
 *    level (23.5 per cent), but 20.2 per cent hold a bachelor degree and
 *    18.0 per cent a Certificate III or IV (2021 Census).
 *
 * 3. The draft recommends a "Certificate III or IV in Business, Business Sales
 *    or Customer Engagement". The sales-specific qualification is SIR30316
 *    Certificate III in Business to Business Sales; BSB30120 Certificate III in
 *    Business is current; the Certificate III in Customer Engagement (BSB30215)
 *    was superseded on 18 October 2020.
 *
 * 4. The draft says a guaranteed base "can be clawed back if you leave early"
 *    and treats commission-only pay as a normal option. Under the Commercial
 *    Sales Award a commercial traveller cannot be paid, by commission or
 *    otherwise, less than $1,122.80 a week from 1 July 2026. Fair Work says
 *    commission-only is only allowed where an award or agreement permits it,
 *    and a deduction from pay needs the employee's written agreement and must
 *    be mainly for the employee's benefit.
 *
 * 5. The draft says real estate roles may need an "Agent's Representative
 *    Certificate". In NSW the entry-level credential has been the Assistant
 *    Agent certificate of registration since the March 2020 reforms; agent's
 *    representative is Victoria's term, and each state differs.
 *
 * 6. The draft says sponsorship is "uncommon". For general sales
 *    representatives (ANZSCO 6113) it is not available through the Core
 *    Skills Occupation List at all; only technical sales roles such as
 *    225411, 225412, 225213 and 225499 are listed.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SalesRepresentativeJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-sales-representative-jobs.html';

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
        $title = 'How to Become a Sales Representative in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Sales representatives earn a median $1,692 a week full-time, about $88,000 a year, by official data. Most roles need no formal qualification, field reps keep an award floor even on commission, and general sales reps cannot be sponsored.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-become-a-sales-representative-in-australia.jpg',
                'tags' => 'how to become a sales representative, sales representative jobs australia, sales rep salary australia, certificate iii in business to business sales, sir30316, commercial sales award, commission only pay australia, field sales jobs, territory sales representative',
                'meta_title' => 'How to Become a Sales Representative in Australia (2026)',
                'meta_description' => 'How to become a sales representative in Australia in 2026: what employers ask for, the B2B sales certificate, official pay data and the award floor.',
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
            ['name' => 'Australian Wholesalers, Manufacturers & B2B Sales Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-sales-representative-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'sales'],
            ['name' => 'Sales']
        );

        Job::updateOrCreate(
            [
                'position' => 'Sales Representative — Field, Territory and B2B Sales, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Business hours, with travel across a territory for field roles',
                'language' => 'English',
                // Base, commission and contractor arrangements vary employer by
                // employer, so no single band is quoted on the listing.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Field, territory and B2B sales representative roles with Australian wholesalers, manufacturers and distributors. Check whether commission sits on top of an award-level wage.',
                'seo_keywords' => 'sales representative jobs australia, field sales jobs, territory sales representative, b2b sales jobs australia, sales rep salary australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Wholesalers, manufacturers, distributors and service businesses across Australia hire sales representatives to win and look after business customers, often across a territory that means time on the road.</p>

<h3>What the work involves</h3>
<p>Prospecting new accounts, visiting and calling existing customers, presenting products, negotiating orders and pricing, keeping a CRM up to date and meeting sales targets.</p>

<h3>Requirements</h3>
<ul>
    <li>No formal qualification for most general roles; technical, medical and industrial sales often want a degree or trade background</li>
    <li>A driver's licence for field and territory roles</li>
    <li>Full working rights in Australia; general sales representatives are not on the Core Skills Occupation List</li>
    <li>Strong spoken and written English, negotiation skills and resilience with rejection</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Official data.</strong> Median full-time earnings for Sales Representatives are $1,692 a week, about $87,984 a year (ABS, May 2025)</li>
    <li><strong>Award floor.</strong> Commercial travellers covered by the Commercial Sales Award cannot be paid less than $1,122.80 a week from 1 July 2026, however much of the pay is commission</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, commission terms and eligibility are set by each employer, the relevant award and the Fair Work Act &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>You do not need a formal qualification to become a sales representative in Australia.</strong> Most employers hire on communication skills, sales or customer-service experience and, for field roles, a driver's licence, then train you on the job. Official data puts median full-time earnings at <strong>$1,692 a week, about $88,000 a year</strong>. Technical, medical and industrial sales are the exception: they usually want a degree or subject knowledge.</p>

<p>This guide covers what employers actually ask for, the training that helps, what the pay data says, and how to read a commission or contractor offer before you sign. For retail sales, telesales and state-by-state real estate registration, see our <a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> guide.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-sales-representative-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127462;&#127482; Browse Sales Representative Jobs in Australia &rarr;
    </a>
</div>

<h2>Do You Actually Need a Qualification?</h2>

<p><strong>For most general sales representative roles, no.</strong> Jobs and Skills Australia classes the job at a lower vocational skill level, around a Certificate II or III, and notes that many people get there through on-the-job training and experience instead.</p>

<p>But the people doing the job are better qualified than the "Year 12 is enough" line suggests. Among Sales Representatives (ANZSCO 6113), the highest qualification held was:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Highest qualification</th>
            <th style="padding:10px;text-align:left;">Share of sales representatives</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Year 12</td><td style="padding:10px;"><strong>23.5%</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Bachelor degree</td><td style="padding:10px;">20.2%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Certificate III or IV</td><td style="padding:10px;">18.0%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Advanced diploma or diploma</td><td style="padding:10px;">13.3%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Year 10 and below</td><td style="padding:10px;">9.5%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Postgraduate</td><td style="padding:10px;">5.8%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Year 11</td><td style="padding:10px;">5.1%</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Jobs and Skills Australia, Sales Representatives (ANZSCO 6113), 2021 Census.</p>
</div>

<p><strong>Where a qualification does matter:</strong></p>

<ul>
    <li><strong>Technical sales.</strong> Pharmaceutical, medical, chemical, industrial and ICT sales usually expect a degree or trade background, because buyers expect you to explain the product in depth.</li>
    <li><strong>Competing without experience.</strong> A sales-specific certificate can move an application ahead of others with nothing to show.</li>
</ul>

<p><strong>The right course names.</strong> The sales-specific qualification is <strong>SIR30316 Certificate III in Business to Business Sales</strong>, which some employers offer as a traineeship alongside the job. <strong>BSB30120 Certificate III in Business</strong> and <strong>BSB40120 Certificate IV in Business</strong> are the general alternatives. The "Certificate III in Customer Engagement" many guides still recommend (BSB30215) was <strong>superseded on 18 October 2020</strong> and replaced by BSB30120.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-become-a-sales-representative-in-australia-client-meeting.jpg"
         alt="A sales representative with a tablet shaking hands with a client across an office desk, the Sydney Opera House and Harbour Bridge through the window behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Almost Every Sales Representative Ad Asks For</h2>

<ul>
    <li><strong>A driver's licence.</strong> Field and territory roles mean visiting customers across a region, so postings routinely ask for a current licence, often a full or unrestricted one. Inside sales roles are the alternative if you do not drive.</li>
    <li><strong>Working rights in Australia.</strong> Most postings ask for full working rights up front. For general sales representatives that is not a formality: the occupation is <strong>not on the Core Skills Occupation List</strong>, so employer sponsorship through that route is not available.</li>
    <li><strong>Communication and negotiation.</strong> Written and spoken communication, negotiation and problem-solving are listed far more often than any credential.</li>
    <li><strong>Evidence of results.</strong> Targets met, accounts grown or conversion rates &mdash; even from retail or hospitality &mdash; are what sales managers look for.</li>
    <li><strong>Industry licences where they apply.</strong> Real estate is the main one, and it differs by state. In NSW the entry-level credential is the <strong>Assistant Agent certificate of registration</strong>, issued for four years and not renewable, since the March 2020 reforms &mdash; not an "Agent's Representative Certificate", which is Victoria's term.</li>
</ul>

<h2>What Sales Representatives Earn</h2>

<p>The pay figures in most guides come from job boards and salary sites, which report advertised salaries rather than measured earnings. The official figure is measured from employer payroll data instead:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Figure</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median full-time earnings</strong></td><td style="padding:10px;"><strong>$1,692 a week, about $87,984 a year</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Employed</td><td style="padding:10px;">71,800</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Working full-time hours</td><td style="padding:10px;">83%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Award floor, commercial travellers</td><td style="padding:10px;">$1,122.80 a week from 1 July 2026</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Jobs and Skills Australia, Sales Representatives (ANZSCO 6113), from the ABS Survey of Employee Earnings and Hours, May 2025; Commercial Sales Award 2020.</p>
</div>

<p>A median describes the middle of all full-time sales representatives, not a starting salary. Entry-level and inside sales roles usually start lower, and experienced technical reps earn more. We could not find an official source for the city figures many guides quote for Sydney and Melbourne, so we have left them out. New South Wales and Victoria do account for the most sales representatives, at 32.5 and 29.4 per cent of the workforce.</p>

<p>Always check whether an advertised figure includes <strong>superannuation</strong> and whether it assumes you hit your commission targets.</p>

<h2>Read the Pay Structure Before You Sign</h2>

<p>The base number alone does not tell you what you will take home. There are three common structures, and the rules differ for each.</p>

<p><strong>Base plus commission.</strong> The most common arrangement for employees. If you are a <strong>commercial traveller</strong> covered by the <strong>Commercial Sales Award 2020</strong>, the award says no one "will be remunerated solely by commission payment, salary or retainer, that is lower than the minimum rate". From 1 July 2026 that minimum is <strong>$1,122.80 a week</strong>, or $29.55 an hour. Commission can make up your pay, but the total cannot fall below it. The award also requires your written terms to state any deductions that may be made from commission.</p>

<p><strong>Commission-only.</strong> Fair Work says an employee can only be paid commission-only when an <strong>award or enterprise agreement allows it</strong>. Employees who are not covered by an award or agreement still have to be paid at least the National Minimum Wage. A job advertised to employees as "commission-only" deserves a close look.</p>

<p><strong>Advances and clawbacks.</strong> Many guides say a guaranteed base can be treated as an advance and "clawed back if you leave early". An employer cannot simply take that money out of your pay. Under Fair Work rules, a deduction needs your <strong>written agreement</strong> and must be <strong>mainly for your benefit</strong>, and a term that benefits the employer and is unreasonable has no effect. Get any advance or clawback arrangement explained in writing before you accept.</p>

<p><strong>Subcontractor arrangements.</strong> Some businesses engage sales reps as contractors, with no sick pay, annual leave or superannuation from the business. That is lawful for genuine contractors. But it is illegal to represent a worker as a contractor when the business does not reasonably believe that &mdash; <strong>sham contracting</strong>. A role with set hours, a manager and targets set by someone else looks like employment, whatever the contract is called.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-become-a-sales-representative-in-australia-prospecting.jpg"
         alt="A sales representative on a phone call taking notes beside a laptop and an Australian flag, the Sydney Harbour Bridge, Opera House and skyline behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Sponsorship: Which Sales Roles Qualify</h2>

<p>The draft most guides copy says sponsorship is "uncommon" for sales representatives. For <strong>general sales representatives (ANZSCO 6113)</strong> it is not available through the Core Skills Occupation List at all. Only technical sales roles are listed:</p>

<ul>
    <li>Sales Representative (Industrial Products) &mdash; 225411</li>
    <li>Sales Representative (Medical and Pharmaceutical Products) &mdash; 225412</li>
    <li>ICT Sales Representative &mdash; 225213</li>
    <li>Technical Sales Representatives nec &mdash; 225499</li>
</ul>

<p>If you are not an Australian citizen or permanent resident, plan around working rights you already hold. Our <a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> guide covers how employer sponsorship works.</p>

<h2>How to Get Hired, Step by Step</h2>

<ol>
    <li><strong>Finish Year 12 or equivalent.</strong> It is the most common baseline, and the entry point for most traineeships.</li>
    <li><strong>Get a driver's licence</strong> if you want field or territory roles.</li>
    <li><strong>Build customer-facing experience</strong> in retail, hospitality or customer service, and keep the numbers: targets, sales and repeat customers.</li>
    <li><strong>Consider SIR30316 Certificate III in Business to Business Sales</strong>, ideally as a traineeship with an employer, if you are changing careers or have no sales history.</li>
    <li><strong>Pick an industry.</strong> A technical or medical sales role expects subject knowledge a consumer goods or wholesale role will not.</li>
    <li><strong>Ask about the pay structure in the interview</strong>: the base, the commission plan, whether super is included, any clawbacks, and whether you would be an employee or a contractor.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a degree to become a sales representative in Australia?</h3>
<p>No, not for most roles. Year 12 is the most common highest qualification among sales representatives, though 20.2 per cent hold a bachelor degree. Degrees matter mainly in pharmaceutical, medical, industrial and ICT sales.</p>

<h3>How much does a sales representative earn in Australia?</h3>
<p>Jobs and Skills Australia puts median full-time earnings at $1,692 a week, about $87,984 a year, based on ABS data from May 2025. Commercial travellers under the Commercial Sales Award cannot be paid less than $1,122.80 a week from 1 July 2026.</p>

<h3>Is a driver's licence really necessary?</h3>
<p>For field and territory roles, usually yes, because you travel to customers across a region, and many ads ask for a full or unrestricted licence. Inside sales roles based in an office or call centre often do not need one.</p>

<h3>What course should I do to become a sales representative?</h3>
<p>The sales-specific qualification is SIR30316 Certificate III in Business to Business Sales. BSB30120 Certificate III in Business is the general alternative. The Certificate III in Customer Engagement was superseded in October 2020.</p>

<h3>Is commission-only pay legal in Australia?</h3>
<p>For employees, only where an award or enterprise agreement allows it. Commercial travellers under the Commercial Sales Award must receive at least the award minimum, however much of their pay is commission, and award-free employees must receive at least the National Minimum Wage.</p>

<h3>Can my employer claw back commission or an advance if I leave?</h3>
<p>They cannot simply deduct it from your pay. A deduction needs your written agreement and must be mainly for your benefit. Get any clawback arrangement in writing and ask how it works before you sign.</p>

<h3>Can I get visa sponsorship as a sales representative?</h3>
<p>Not as a general sales representative through the Core Skills Occupation List. Technical sales roles such as Sales Representative (Industrial Products) 225411 and (Medical and Pharmaceutical Products) 225412 are on the list.</p>

<h3>Do I need a licence to sell real estate?</h3>
<p>Yes, and it depends on the state. In NSW you need an Assistant Agent certificate of registration, which lasts four years and cannot be renewed. Other states use different names and training requirements.</p>

<h2>People Also Search For</h2>

<h3>Sales representative salary Australia</h3>
<p>A median of $1,692 a week full-time, about $87,984 a year, by Jobs and Skills Australia.</p>

<h3>Certificate III in Business to Business Sales</h3>
<p>SIR30316, the current sales-specific qualification, often done as a traineeship.</p>

<h3>Commercial Sales Award pay rates 2026</h3>
<p>$1,122.80 a week, or $29.55 an hour, for commercial travellers from 1 July 2026.</p>

<h3>Commission only jobs Australia</h3>
<p>Only lawful for employees where an award or agreement allows it; otherwise the minimum wage applies.</p>

<h3>Field sales representative jobs</h3>
<p>Territory-based roles visiting business customers, which usually require a driver's licence.</p>

<h3>Medical sales representative Australia</h3>
<p>A technical sales role, often needing a science degree, and on the Core Skills Occupation List as 225412.</p>

<h3>Sales representative no experience</h3>
<p>Start in retail or customer service, or take a B2B sales traineeship, and record your results.</p>

<h3>Sham contracting sales</h3>
<p>Calling a worker a contractor when the business does not reasonably believe it is illegal under the Fair Work Act.</p>

<h2>More Job Guides</h2>

<p>Looking at other ways into work in Australia? These cover the neighbouring routes:</p>

<ul>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> &mdash; retail award pay, when commission-only is legal and each state's real estate registration.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; entry-level roles that build the customer-facing record sales managers look for.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; how employer sponsorship actually works, and which occupation list applies.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; another entry route into Australian business, and its award pay.</li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a> &mdash; the customer-facing route in the American market.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or financial advice. Pay rates, award terms and visa lists change over time. Confirm the current position with the Fair Work Ombudsman, training.gov.au and the Department of Home Affairs before relying on it.</p>
HTML;
    }
}
