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
 * "How to Apply for Walmart Store Associate Jobs in the USA" — an employer
 * guide built on what Walmart publishes about itself, with BLS wages for
 * comparison and the Department of Labor for the youth employment rules.
 *
 * Corrections to the draft (checked against corporate.walmart.com,
 * careers.walmart.com, one.walmart.com, bls.gov and dol.gov,
 * September 2026):
 *
 * 1. The draft's pay table came from a third-party guide and is out of date
 *    in both directions. Walmart publishes an average US frontline hourly
 *    wage of more than $18.50 and a Team Associate range of $14 to $37 an
 *    hour, not a flat $14 to $19.
 *
 * 2. The draft says the Sam's Club minimum is $15. Walmart's published Sam's
 *    Club range starts at $16.
 *
 * 3. The draft states a minimum hiring age of 16, sourced from a third-party
 *    site. Walmart's own live job postings carry the line "Must be at least
 *    18 years old", and no Walmart page states 16, so the guide gives the
 *    federal rules and tells readers to check the posting instead.
 *
 * 4. The draft links two specific job IDs. Both have already rotated: one now
 *    shows an unrelated filler listing and the other redirects to a different
 *    live vacancy in another state. The guide links Walmart's stable careers
 *    hub instead.
 *
 * 5. The draft omits the annual bonus, the 401(k) match, the stock purchase
 *    match and the store manager pay range, which are the parts of Walmart's
 *    offer that are actually published and verifiable.
 *
 * 6. The draft has no occupational context. BLS medians for the equivalent
 *    work are $17.03 for retail salespersons, $15.81 for cashiers and $17.95
 *    for stockers and order fillers.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class WalmartStoreAssociateJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.walmart.com/us/en/home/careers-areas/stores-and-clubs';

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
        $title = 'How to Apply for Walmart Store Associate Jobs in the USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Walmart publishes a Team Associate range of $14 to $37 an hour and an average frontline wage above $18.50, well beyond the $14 to $19 most guides quote. Here is what Walmart actually says about pay, age, benefits and the application.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-walmart-store-associate-jobs-in-the-usa.jpg',
                'tags' => 'walmart jobs, walmart store associate, walmart careers, walmart pay per hour, sams club jobs, live better u, walmart application, retail jobs usa',
                'meta_title' => 'Walmart Store Associate Jobs: Real Pay and How to Apply',
                'meta_description' => 'Walmart store associate jobs: the pay Walmart actually publishes, the age and youth work rules, Live Better U and the discount, and where to apply.',
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
            ['name' => 'Walmart Inc. and Sam\'s Club, US Stores and Clubs'],
            ['type' => 'Company', 'display_reference' => 'walmart-stores-clubs-us']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Store Associate, Walmart and Sam\'s Club Hourly Store Roles, USA',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time and part-time, including overnight, evening and weekend shifts',
                'language' => 'English',
                // Walmart publishes a $14 to $37 range that spans the whole
                // Team Associate band and varies by store and state, so the
                // guide quotes Walmart's own figures rather than a band here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Hourly store and club roles with Walmart across the United States, covering sales floor, checkout, stocking and online pickup work.',
                'seo_keywords' => 'walmart jobs, walmart store associate jobs, sams club jobs, walmart careers apply, retail jobs usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Walmart and Sam's Club hire hourly store associates across the United States for sales floor, checkout, stocking and online order pickup work, with part-time, full-time and overnight shifts.</p>

<h3>What the work involves</h3>
<p>Stocking and zoning shelves, serving customers at the register or self-checkout, picking and staging online grocery orders, unloading trucks and keeping the sales floor clean and shoppable.</p>

<h3>Common requirements</h3>
<ul>
    <li>No prior experience for most entry-level store roles</li>
    <li>Ability to lift and move stock, including boxes over 25 pounds for stocking roles</li>
    <li>Availability for the shift pattern in the posting, including nights and weekends</li>
    <li>Authorization to work in the United States</li>
    <li>Age requirements vary by role and state; some roles state 18 or over</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay ranges, benefits and eligibility are set and published by Walmart, and the youth employment rules are set by the US Department of Labor and the states &mdash; not by JobGader. Applying to Walmart is free; any request for payment is a scam.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on Walmart's own careers site, pick a store near you, and expect an application of about 20 to 25 minutes plus an assessment for some roles.</strong> Walmart says it aims to respond within a week of submission.</p>

<p>The part worth reading first is the pay. Walmart publishes its own figures, and they are considerably better than the "$14 to $19 an hour" that circulates in most guides &mdash; which appears to be copied from a Walmart fact sheet that has not been updated since 2023.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.walmart.com/us/en/home/careers-areas/stores-and-clubs" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128722; Browse Walmart Store Jobs &rarr;
    </a>
</div>

<h2>What Walmart Actually Pays</h2>

<p>These are Walmart's own published figures, not estimates from a salary aggregator:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">Published hourly range</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Team Associate</strong> (Walmart stores)</td><td style="padding:10px;"><strong>$14 to $37</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Team Lead</strong></td><td style="padding:10px;">$19 to $40</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sam's Club associate</strong></td><td style="padding:10px;"><strong>$16 to $37</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Average, US frontline hourly associates</td><td style="padding:10px;"><strong>More than $18.50</strong></td></tr>
    </tbody>
</table>
</div>

<p>Three things follow from that. The <strong>$14 floor is real</strong>, so the bottom of the range in the old guides is right. The <strong>top is not $19</strong>, it is $37, because the same Team Associate band covers specialised and higher-responsibility store roles. And the <strong>Sam's Club floor is $16</strong>, not the $15 usually quoted.</p>

<p>Where you land in that range depends far more on your store's location and market than on which department you join. Two stores an hour apart can start at different numbers.</p>

<h3>How that compares to the rest of retail</h3>

<p>Useful context, from the Bureau of Labor Statistics, for the occupations this work falls under:</p>

<ul>
    <li><strong>Retail salespersons:</strong> median $17.03 an hour, $35,560 a year. Employment projected flat.</li>
    <li><strong>Cashiers:</strong> median $15.81 an hour, $32,880 a year. Employment projected to fall 6%.</li>
    <li><strong>Stockers and order fillers:</strong> median $17.95 an hour, $37,330 a year, and the one that is <strong>growing, by 9%</strong>.</li>
</ul>

<p>That last line is a practical hint. If you are choosing between departments and thinking about the next five years, stocking and fulfilment work sits in the part of retail that is expanding, while checkout is the part shrinking fastest.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-walmart-store-associate-jobs-in-the-usa-floor.jpg" alt="Store associate scanning stock on the sales floor" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Bonus and the Benefits Most Guides Skip</h2>

<p>Walmart publishes several things that materially change what the job is worth, and almost no guide includes them:</p>

<ul>
    <li><strong>Annual cash bonus, up to $1,000 a year.</strong> Introduced for hourly store associates and scaled by tenure and store performance, starting around $350 a year at one year of service and reaching the $1,000 maximum at twenty years.</li>
    <li><strong>401(k) match.</strong> Walmart matches each dollar you contribute up to 6% of eligible pay.</li>
    <li><strong>Associate Stock Purchase Plan.</strong> A 15% match on the first $1,800 you contribute per plan year, so up to $270 a year.</li>
    <li><strong>Live Better U.</strong> Walmart pays 100% of tuition, fees and books at partner schools, with no cap, and you are eligible from day one with no waiting period. It requires a high school diploma and excludes people who already hold a bachelor's degree, and seasonal and temporary associates.</li>
    <li><strong>Associate discount.</strong> 10% on general merchandise and, since the 2025 expansion, most food. It starts after <strong>90 days</strong>, covers a spouse or domestic partner, and associates with twenty years of service keep it for life.</li>
    <li><strong>Paid parental leave.</strong> Birth mothers can receive up to nine weeks of paid maternity leave at full pay after a seven-day waiting period, plus parental leave, to a combined maximum of sixteen weeks. Other new, adoptive and foster parents receive up to six weeks paid. Generally requires twelve months of service.</li>
</ul>

<p><strong>Live Better U is the single most valuable item on that list</strong> if you intend to study, precisely because there is no waiting period. A degree paid for while you earn is worth more than a dollar an hour, and most people applying for these jobs do not know it starts on day one.</p>

<h2>Age: What Walmart Says, and What the Law Says</h2>

<p>Most guides state that Walmart hires from 16. <strong>We could not find that figure anywhere on Walmart's own site.</strong> What Walmart's live job postings do carry is a standard line reading "Must be at least 18 years old", and role and state requirements vary. So read the age line on the specific posting rather than trusting a blog.</p>

<p>What is settled is the federal law, set by the Department of Labor:</p>

<ul>
    <li><strong>14 and 15 year olds</strong> may work in retail, and the rules explicitly permit bagging groceries, office work, stocking shelves and cashiering. Hours are capped: 3 hours on a school day, 8 on a non-school day, 18 in a school week, 40 in a non-school week, between 7am and 7pm (extended to 9pm from 1 June to Labor Day). They may not use power-driven machinery or work from ladders or scaffolds.</li>
    <li><strong>16 and 17 year olds</strong> have no federal limit on hours, but seventeen Hazardous Occupations Orders still apply. The one that bites in a store is the order covering balers and compactors: under-18s may load them but may not operate or unload them.</li>
    <li><strong>State law can be stricter</strong>, and where it is, the stricter rule wins. Some states also require a work permit.</li>
</ul>

<p>The federal minimum wage remains <strong>$7.25 an hour</strong>, unchanged since 2009, which is why Walmart's $14 floor sits so far above it and why state minimums matter more than the federal one in most of the country.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Go to Walmart's careers site directly.</strong> Use the stores and clubs hub and search by your location. Do not apply through a link someone sends you.</li>
    <li><strong>Set your availability honestly.</strong> Availability is one of the biggest factors in whether a store can use you, and overstating it to get hired creates a problem in week two rather than solving one.</li>
    <li><strong>Allow 20 to 25 minutes</strong> for the application, which is Walmart's own estimate.</li>
    <li><strong>Complete the assessment if the role has one.</strong> Answer consistently rather than trying to guess a preferred answer.</li>
    <li><strong>Expect a response within about a week.</strong> That is the target Walmart publishes.</li>
    <li><strong>Apply to more than one store.</strong> Hiring is driven by each store's staffing needs, so two locations in the same city can be in completely different positions.</li>
</ol>

<h3>A note on job links that expire</h3>

<p>Guides that link to a specific Walmart job ID go stale fast. We checked the two postings linked by one widely copied guide: one now shows an unrelated filler listing in a different state, and the other redirects to a completely different live vacancy that reuses the number. Always start from the careers hub and search, rather than trusting a deep link.</p>

<h2>Never Pay to Apply</h2>

<p>Walmart's own fraud alerts warn that requests for payment are a scam signal. Applying to Walmart is free at every stage. Nobody legitimate will ask you for a fee for an application, a uniform, a background check or a "training package", and no real recruiter needs your bank details before you are hired and on payroll.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-walmart-store-associate-jobs-in-the-usa-team.jpg" alt="Walmart store team working together in the aisles" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Where the Job Can Lead</h2>

<p>Walmart states that <strong>approximately 75% of its salaried store, club and supply chain leaders started as hourly associates</strong>. That is an unusually strong internal promotion record for retail, and it is the honest argument for taking an entry-level role here over an identical one elsewhere.</p>

<p>The published destination is worth knowing. Walmart gives a store manager base salary range of <strong>$95,000 to $170,000</strong> a year before bonus and stock, having raised the average base to $128,000 in 2024, with total compensation for a Supercenter store manager reported in the <strong>$218,000 to $530,000</strong> range. Very few people get there, but the ladder is real and it starts on the sales floor.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does Walmart pay store associates?</h3>
<p>Walmart publishes a Team Associate range of $14 to $37 an hour and an average of more than $18.50 an hour for US frontline associates. Sam's Club associates start at $16.</p>

<h3>How old do you have to be to work at Walmart?</h3>
<p>Walmart does not publish a single minimum age, and its live postings carry a line stating you must be at least 18. Requirements vary by role and state, so check the individual posting.</p>

<h3>Does Walmart pay for your degree?</h3>
<p>Yes. Live Better U covers 100% of tuition, fees and books at partner schools with no cap, and you are eligible from your first day. It excludes people who already have a bachelor's degree.</p>

<h3>When does the Walmart associate discount start?</h3>
<p>After 90 days. It is 10% on general merchandise and most food, extends to a spouse or domestic partner, and becomes a lifetime benefit after twenty years of service.</p>

<h3>Does Walmart give bonuses to hourly workers?</h3>
<p>Yes. Hourly store associates can receive an annual cash bonus of up to $1,000, scaled by length of service and store performance.</p>

<h3>How long does Walmart take to hire?</h3>
<p>Walmart says it aims to respond to applicants within a week of submission. The full process through interview and orientation depends on the store's staffing needs.</p>

<h3>Do I need experience to work at Walmart?</h3>
<p>No, not for most entry-level store roles. Stocking roles do expect you to lift boxes over 25 pounds, and front-end roles benefit from any customer service background.</p>

<h3>Can Walmart associates become managers?</h3>
<p>Walmart says approximately 75% of its salaried store, club and supply chain leaders began as hourly associates, with a published store manager base range of $95,000 to $170,000.</p>

<h2>People Also Search For</h2>

<h3>Walmart starting pay 2026</h3>
<p>A $14 an hour floor in Walmart stores and $16 at Sam's Club, with an average above $18.50.</p>

<h3>Walmart 401k match</h3>
<p>Dollar for dollar on your contributions, up to 6% of eligible pay.</p>

<h3>Live Better U eligibility</h3>
<p>Available from day one, covering tuition, fees and books, excluding those who already hold a bachelor's degree.</p>

<h3>Walmart overnight stocker pay</h3>
<p>Within the Team Associate range, with stockers and order fillers earning a BLS median of $17.95 an hour across the industry.</p>

<h3>Walmart store manager salary</h3>
<p>A published base range of $95,000 to $170,000 a year, before bonus and stock.</p>

<h3>Sam's Club jobs pay</h3>
<p>A published range of $16 to $37 an hour.</p>

<h3>Working at Walmart at 16</h3>
<p>Walmart does not publish a 16 minimum and its postings state 18; federal law permits retail work from 14 with strict hour limits.</p>

<h3>Walmart application assessment</h3>
<p>Part of the online application for some roles, after a form that takes about 20 to 25 minutes.</p>

<h2>More Job Guides</h2>

<p>Comparing retail employers and roles? These cover them:</p>

<ul>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a> &mdash; the wider market, pay and what the work involves.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; front-end work and the outlook for it.</li>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; the sector overview and where the openings are.</li>
    <li><a href="/blog/how-to-get-an-entry-level-office-job-with-no-experience">Entry-Level Office Jobs With No Experience</a> &mdash; the office alternative and what it pays.</li>
    <li><a href="/blog/forklift-operator-jobs-in-usa">Forklift Operator Jobs in USA</a> &mdash; a certificated step up from stocking work.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; which US routes actually sponsor a visa.</li>
    <li><a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">How to Apply for Amazon Fulfillment Center Jobs in USA</a> &mdash; a large employer that does publish its starting pay.</li>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a> &mdash; a supermarket that publishes its rate and pays it at every age.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Walmart's own corporate and careers pages for pay, benefits and hiring information, Bureau of Labor Statistics wage and projection data for occupational context, and US Department of Labor guidance for the youth employment and minimum wage rules. Pay ranges vary by store and state and change over time. Always check the live posting for the role and location you want.</p>
HTML;
    }
}
