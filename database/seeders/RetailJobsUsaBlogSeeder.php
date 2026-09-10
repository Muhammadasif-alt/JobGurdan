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
 * "Retail Jobs in USA" — the largest entry-level American job market, written
 * around the fact that makes a national average almost meaningless here: the
 * legal wage floor for the same job ranges from $7.25 to $18.40 depending on
 * the state.
 *
 * Corrections to the draft:
 *
 * 1. It publishes five "averages" for one job title spanning $12.72 to
 *    $20.00 an hour, a 57 per cent spread, and presents them as a table to
 *    compare rather than as evidence that none of them is a measurement.
 *
 * 2. It merges retail salespersons and cashiers under one heading. BLS
 *    separates them, and the difference is material: a $17.03 median against
 *    $14.99, and a flat outlook against a projected 10 per cent decline.
 *
 * 3. It never mentions the federal minimum wage, which is the single most
 *    important number in American retail pay. It has been $7.25 since 24
 *    July 2009 — the longest period without an increase since the Fair
 *    Labor Standards Act was enacted in 1938 — while 30 states and the
 *    District of Columbia set higher floors, up to $18.40.
 *
 * 4. It lists the highest paying metros without noting that those cities
 *    mostly have high local minimum wages, so the ranking is largely a map
 *    of wage floors rather than of employer generosity.
 *
 * 5. It presents "flexible scheduling" as a benefit. In retail the same
 *    property is the industry's most common complaint, and the reader should
 *    be told which one they are being offered.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RetailJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-retail-jobs-jobs.html';

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
        $title = 'Retail Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Five published averages for one job span 57 per cent, the federal floor has not moved since 2009 while some states sit at more than double it, and cashier and sales associate are not the same job or the same outlook.',
                'content' => $content,
                'featured_image' => 'blogs/retail-jobs-in-usa.jpg',
                'tags' => 'retail jobs usa, retail sales associate jobs, cashier jobs, retail sales associate salary, seasonal retail jobs, store manager jobs, part time retail jobs, entry level jobs no degree',
                'meta_title' => 'Retail Jobs in USA',
                'meta_description' => 'Retail jobs in the USA: the measured BLS medians, why the state minimum wage matters more than any national average, and cashier versus sales associate.',
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
            ['name' => 'US Retail Chains & Independent Stores (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-retail-aggregated']
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
                'position' => 'Retail Sales Associate — Store Floor, Register and Stockroom, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift-based, including evenings, weekends and holiday peak periods',
                'language' => 'English',
                // The legal floor for this job ranges from $7.25 to $18.40 by
                // state, so a single national band would misdescribe most of
                // the country.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Sales floor, register and stockroom roles with US retailers. Check your state minimum wage before judging any advertised rate.',
                'seo_keywords' => 'retail jobs usa, retail sales associate jobs, cashier jobs, seasonal retail jobs, part time retail jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>National chains, regional retailers, grocery stores and independent shops across the United States hire sales associates, cashiers, stock associates and merchandisers year round, with large additional hiring around the holiday and back-to-school peaks. It is the most accessible paid work in the country, and the one where where you live changes the offer more than anything you do.</p>

<h3>What the work involves</h3>
<p>Greeting customers and helping them find and choose merchandise, operating a register or point-of-sale system, restocking shelves and keeping the floor presentable, answering product and returns questions, and cross-training between register, floor and stockroom. Most roles involve standing for long periods and lifting stock, commonly up to 50 pounds.</p>

<h3>Requirements</h3>
<ul>
    <li>No degree, and for most associate roles no prior experience &mdash; many listings say so outright</li>
    <li>Basic point-of-sale, handheld scanner and smartphone competence</li>
    <li>Availability for evenings, weekends and holiday peak periods</li>
    <li>Physical ability to stand, lift and move stock for a full shift</li>
    <li>Three or more years of experience for supervisory and store management roles</li>
    <li>Reliability and clear communication, listed at every level of seniority</li>
</ul>

<h3>What it pays, and what sets it</h3>
<ul>
    <li><strong>The measured medians</strong> are <strong>$17.03 an hour for retail salespersons</strong> and <strong>$14.99 an hour for cashiers</strong> &mdash; two different occupations often advertised under one heading</li>
    <li><strong>The federal minimum wage is $7.25</strong> and has not changed since 2009, but <strong>30 states and DC set higher floors</strong>, reaching <strong>$18.40</strong> in the District of Columbia</li>
    <li><strong>Full-time roles at national chains</strong> commonly add merchandise discounts, retirement plan matching and education benefits, which are worth pricing rather than skimming</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Look up your state and city minimum wage before you judge any advertised rate.</strong> For this occupation the legal floor is doing most of the work, and the same hourly number is a good offer in one state and the bare minimum in another.</p>

<p><strong>Note:</strong> pay, scheduling, benefits and eligibility are set by each employer and by state law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Retail is the largest open door into paid work in the United States. It is also the job category where published salary data is least useful, and for a reason nobody states plainly: the United States does not have one wage floor for this work. It has dozens, and they differ by more than two and a half times.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-retail-jobs-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128717;&#65039; Browse Retail Jobs in the USA &rarr;
    </a>
</div>

<h2>Five Averages, 57 Per Cent Apart</h2>

<p>Here is what the major salary platforms say a retail sales associate earns, all at once, all describing the same job title:</p>

<ul>
    <li>Indeed &mdash; <strong>$16.31</strong> an hour</li>
    <li>Glassdoor &mdash; about <strong>$20.00</strong> an hour, or $40,632 a year</li>
    <li>Salary.com &mdash; about <strong>$19.00</strong> an hour, or $39,500 a year</li>
    <li>ZipRecruiter &mdash; about <strong>$15.67</strong> an hour, or $32,589 a year</li>
    <li>PayScale, entry level &mdash; <strong>$12.72 to $14.52</strong> an hour</li>
</ul>

<p>The lowest and the highest of those are <strong>57 per cent apart</strong>. Presented as a table, they invite you to average them. Do not. A set of estimates that far apart is not five measurements of one thing; it is five different samples of a job title that covers a commission-earning specialty salesperson and a big-box weekend cashier equally.</p>

<p>The measured figures come from the Bureau of Labor Statistics, and the first thing they do is separate two jobs the table above merges:</p>

<ul>
    <li><strong>Retail salespersons &mdash; a median of $17.03 an hour</strong> (May 2025).</li>
    <li><strong>Cashiers &mdash; a median of $14.99 an hour</strong> (May 2024).</li>
</ul>

<p>That is a <strong>$2.04 an hour</strong> difference between two roles routinely advertised in the same posting and applied for interchangeably. Over a full-time year it is worth more than $4,000. If a listing is titled "Retail Sales Associate" but the description is register work, you are looking at the lower of those two markets.</p>

<h2>The Number Every Retail Guide Leaves Out</h2>

<p>The federal minimum wage in the United States is <strong>$7.25 an hour</strong>. It has not changed since <strong>24 July 2009</strong> &mdash; the longest stretch without an increase since the Fair Labor Standards Act was enacted in 1938.</p>

<p>That number does not describe what most people earn, because <strong>30 states and the District of Columbia set higher floors of their own</strong>, and dozens of cities set higher ones again. The District of Columbia is the highest at <strong>$18.40</strong> an hour, with Washington state at <strong>$17.13</strong> and Connecticut at <strong>$16.94</strong>.</p>

<p>Stop and look at what that means. <strong>The legal minimum in Washington, D.C. is higher than the national median wage for retail salespersons.</strong> Someone earning the bare legal minimum in one part of the country is earning more than half of everyone doing the same job nationwide.</p>

<p>So a "national average" for American retail pay is averaging across a country where the floor itself ranges from $7.25 to $18.40. It is not a useful number for any individual, and it is why the five platform estimates above disagree so violently &mdash; they are weighting different states.</p>

<p><strong>The only figure that matters to you is your state's, and then your city's.</strong> Look it up before you read another salary table. It will tell you more in ten seconds than the rest of this section.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/retail-jobs-in-usa-pay.jpg"
         alt="A retail sales associate assisting a customer at a checkout counter in a US store"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The "Highest Paying Cities" List Is a Map of Wage Floors</h2>

<p>Guides list the top metros for retail pay as New York at about <strong>$19.47</strong> an hour, Chicago at <strong>$18.67</strong> and Minneapolis at <strong>$18.28</strong>, and describe them as reflecting "local wage levels and cost of living".</p>

<p>Partly. But look at what those three cities have in common: all sit in states or cities with minimum wages far above the federal floor. The ranking is substantially a ranking of <strong>legal minimums</strong>, not of employer generosity, and the premium above the local floor in those cities is much smaller than the gap to the national average suggests.</p>

<p>Which changes the practical advice. Moving to a high-minimum city for a retail job raises your nominal hourly rate and raises your rent by considerably more. The genuine reason to work retail in an expensive metro is the density of employers and the speed of finding a second job or a better one &mdash; not the headline rate.</p>

<h2>Cashier Is Declining. Sales Associate Is Not.</h2>

<p>The two occupations separate again on outlook, and it is worth knowing which one you are joining.</p>

<p><strong>Retail sales workers</strong> overall are projected to show <strong>little or no change</strong> in employment over the coming decade. Flat, not shrinking.</p>

<p><strong>Cashiers</strong> are projected to <strong>decline about 10 per cent from 2024 to 2034</strong>, as self-checkout and automated payment absorb the work.</p>

<p>And yet cashiers still show around <strong>542,600 projected openings every year</strong> &mdash; every one of them from replacing people who leave the occupation or the workforce entirely. That is the shape of a shrinking job that is nevertheless extremely easy to get: enormous churn, no growth.</p>

<p>Both facts are useful together. You can get hired quickly, and you should not plan to still be doing register work in a decade. The durable direction inside retail is towards the parts that need judgement &mdash; specialty and commission sales, inventory and merchandising, and supervision &mdash; which is also where the pay is. Store management roles are the visible ladder, and they screen on three or more years of experience and point-of-sale system knowledge.</p>

<h2>"Flexible Scheduling" Is the Industry's Best Feature and Worst Problem</h2>

<p>Every guide lists flexible scheduling among the benefits. In retail, the exact same property is the most common complaint people have about the work, and which one you get depends entirely on the employer.</p>

<p><strong>Flexible for you</strong> means you can state your availability and the schedule respects it &mdash; the reason retail suits students, carers and people holding two jobs.</p>

<p><strong>Flexible for them</strong> means variable hours week to week, shifts posted with little notice, and being sent home early when the store is quiet. That version makes it impossible to hold a second job, budget a month, or arrange childcare.</p>

<p>The advertisement will not distinguish them. So ask directly, at interview: <strong>how far in advance is the schedule posted, how much do weekly hours vary, and are shifts ever cut on the day?</strong> Some cities have enacted scheduling laws that require advance notice, so the answer also varies with where you are. Those three questions separate the two versions of the job more reliably than the hourly rate does.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/retail-jobs-in-usa-store.jpg"
         alt="Retail staff restocking shelves and organising the sales floor in an American store"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Retail sales associate.</strong> The core title, at the $17.03 median, with a flat employment outlook.</li>
    <li><strong>Cashier.</strong> Register-focused, at the $14.99 median, declining but with over half a million openings a year.</li>
    <li><strong>Stock associate.</strong> Receiving, backroom and floor replenishment. Less customer contact, often earlier or later shifts.</li>
    <li><strong>Retail merchandiser.</strong> Displays, planograms and resets, frequently travelling between stores.</li>
    <li><strong>Seasonal retail associate.</strong> Holiday and back-to-school hiring, usually flagged in the job title. A genuine route to a permanent role.</li>
    <li><strong>Assistant store manager and store manager.</strong> Three or more years of experience, point-of-sale knowledge, and the real pay step in this sector.</li>
</ul>

<h2>Benefits Worth Actually Pricing</h2>

<p>At full-time national chains the non-wage package can be a meaningful share of what the job is worth, and it is the part applicants skim:</p>

<ul>
    <li><strong>Retirement plan matching.</strong> Free money with a vesting schedule attached &mdash; ask what the match is and when it vests.</li>
    <li><strong>Employee discount.</strong> Worth real money if you shop there anyway and nothing at all if you do not.</li>
    <li><strong>Tuition and education programmes.</strong> Several large chains fund study. On a $17 an hour job this is frequently the single most valuable line in the offer.</li>
    <li><strong>Sales incentives.</strong> Genuine in specialty and commission-driven roles. Ask what the average associate actually earns from it, not the top performer.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do retail jobs pay in the USA?</h3>
<p>The measured medians are $17.03 an hour for retail salespersons and $14.99 for cashiers. Published platform averages range from $12.72 to $20.00, a 57 per cent spread, because they weight states and job types differently.</p>

<h3>What is the federal minimum wage for retail workers?</h3>
<p>$7.25 an hour, unchanged since 24 July 2009 &mdash; the longest period without an increase since 1938. But 30 states and the District of Columbia set higher floors, up to $18.40 in DC.</p>

<h3>Why do salary sites disagree so much about retail pay?</h3>
<p>Because they are averaging across a country where the legal floor ranges from $7.25 to $18.40, and across a job title covering both commission specialty sales and part-time register work.</p>

<h3>Is a cashier job the same as a retail sales associate job?</h3>
<p>No, and the difference is worth about $2.04 an hour. BLS treats them as separate occupations, and their outlooks differ too. Read the duties in the posting rather than the title.</p>

<h3>Are cashier jobs disappearing?</h3>
<p>Employment is projected to decline about 10 per cent from 2024 to 2034. Even so, roughly 542,600 openings appear each year, all from replacing people who leave, so the job stays easy to get.</p>

<h3>Which US cities pay retail workers best?</h3>
<p>New York, Chicago and Minneapolis lead published lists, largely because their local minimum wages are high. The premium above the local floor is smaller than the gap to the national average implies.</p>

<h3>Do I need experience for a retail job?</h3>
<p>Usually not for associate roles &mdash; many listings state no experience required. Store management is the exception, typically asking for three or more years plus point-of-sale system experience.</p>

<h3>Is retail scheduling actually flexible?</h3>
<p>It depends whether it is flexible for you or for the employer. Ask how far ahead the schedule is posted, how much weekly hours vary, and whether shifts get cut on the day.</p>

<h2>People Also Search For</h2>

<h3>Retail sales associate salary</h3>
<p>A measured median of $17.03 an hour, against platform estimates ranging from $12.72 to $20.00.</p>

<h3>Cashier jobs near me</h3>
<p>A $14.99 median and over half a million openings a year, almost entirely from turnover.</p>

<h3>Seasonal retail jobs</h3>
<p>Flagged in the job title around the holiday and back-to-school peaks, and a common route into a permanent role.</p>

<h3>Part time retail jobs</h3>
<p>Widely available. Confirm how far in advance the schedule is posted before relying on it around other commitments.</p>

<h3>Retail jobs no experience</h3>
<p>The most accessible paid work in the country. State minimum wage matters more to the offer than your r&eacute;sum&eacute; does.</p>

<h3>Store manager salary USA</h3>
<p>The real pay step in retail, screened on three or more years of experience and point-of-sale knowledge.</p>

<h3>Minimum wage by state</h3>
<p>$7.25 federally since 2009, with 30 states and DC higher, up to $18.40 &mdash; more than double the federal floor.</p>

<h3>Walmart and Target jobs hiring</h3>
<p>Large chains post across many states at once. Price the retirement match and any tuition programme, not just the hourly rate.</p>

<h2>More Job Guides</h2>

<p>Comparing entry-level routes? These cover them:</p>

<ul>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; the other big American entry point, and the projection you should see first.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; the same customer-facing work under provincial wage floors.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the work-from-home version of retail's customer skills.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the honest answer on entry-level US sponsorship.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; hospitality, and the seasonal visa routes into it.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; manual work and the visa categories that reach it.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; the same seniority level in a legally very different labour market.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; British entry-level work and its employment-status trap.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers, legal or immigration advice. Minimum wages, wage survey figures and employment projections change, and posting counts on any job board are a moving figure rather than a statistic. Confirm the current position with the Bureau of Labor Statistics, your state labor department and the employer's own advertisement before applying.</p>
HTML;
    }
}
