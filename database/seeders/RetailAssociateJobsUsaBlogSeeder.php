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
 * "Retail Associate Jobs in USA" — the hiring side of American retail, written
 * to sit beside the existing "Retail Jobs in USA" market guide rather than
 * repeat it. That page explains what the work pays and why state law decides
 * it; this one explains how you are actually hired into it.
 *
 * Corrections to the draft:
 *
 * 1. It gives "$12 to $18 per hour". Both ends are wrong. Against OEWS May
 *    2025 for retail salespersons, $12 is below the 10th percentile of
 *    $13.08, and $18 sits below the 75th percentile of $18.59. The measured
 *    median is $17.03 and the 90th percentile is $23.02.
 *
 * 2. It never mentions the minimum wage, which decides more of an American
 *    retail offer than any other single fact. The federal floor has been
 *    $7.25 since 24 July 2009, and five states — Alabama, Louisiana,
 *    Mississippi, South Carolina and Tennessee — have no state minimum wage
 *    law of their own at all.
 *
 * 3. It lists "a high school diploma or GED (preferred, not always
 *    required)". BLS records the entry requirement as no formal educational
 *    credential and no prior work experience, with a few days to a few months
 *    of on-the-job training.
 *
 * 4. It says "many store managers started as associates" without the numbers
 *    that make the claim useful. First-line retail supervisors earn a median
 *    of $23.33 an hour against $17.03, a $6.30 step, and there are 1,121,800
 *    of them against 3,897,860 associates — roughly one supervisor per three
 *    and a half associates.
 *
 * 5. It omits the outlook entirely. Employment is projected to change by 0
 *    per cent from 2025 to 2035, a fall of 5,700 jobs, while still producing
 *    about 550,600 openings a year. That combination is the whole shape of
 *    the job: very easy to enter, not a growth sector.
 *
 * 6. It treats register work as part of the associate role. BLS separates
 *    cashiers, and prices them $1.22 an hour lower.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RetailAssociateJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-retail-associate-jobs.html';

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
        $title = 'Retail Associate Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The $12 to $18 an hour every guide quotes starts below the tenth percentile. The measured median is $17.03, no diploma is required, five states have no minimum wage law at all, and the supervisor step is worth $6.30 an hour.',
                'content' => $content,
                'featured_image' => 'blogs/retail-associate-jobs-in-usa.jpg',
                'tags' => 'retail associate jobs usa, retail sales associate salary, entry level retail jobs, store associate jobs, part time retail jobs, no experience retail jobs, retail associate hiring, retail jobs near me',
                'meta_title' => 'Retail Associate Jobs in USA: Pay and Getting Hired',
                'meta_description' => 'Retail associate jobs in the USA: the $17.03 median BLS actually measures, why your state decides the offer, and what employers really require.',
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
            ['name' => 'US Retailers & Store Chains (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-retail-associate-aggregated']
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
                'position' => 'Retail Associate — Sales Floor, Fitting Room and Register, US Retailers',
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
                // The floor for this job is $7.25 in some states and $18.40 in
                // the District of Columbia, so a single national band would
                // misdescribe most of the country.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Sales floor and customer service roles with US retailers. No degree or prior experience required; check your state minimum wage first.',
                'seo_keywords' => 'retail associate jobs usa, retail sales associate jobs, entry level retail jobs, store associate jobs, part time retail jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>National chains, regional retailers, grocery stores and independent shops across the United States hire sales associates continuously, with a large additional intake before the holiday peak. It is the most accessible paid work in the country: the Bureau of Labor Statistics records the entry requirement as no formal educational credential and no prior work experience.</p>

<h3>What the work involves</h3>
<p>Greeting customers and helping them find and choose merchandise, operating a register or point-of-sale system, restocking shelves and keeping the floor presentable, handling returns and exchanges, and supporting promotions and seasonal resets. Most roles involve standing for a full shift and lifting stock.</p>

<h3>Requirements</h3>
<ul>
    <li>No degree and no prior experience for associate roles &mdash; a diploma is commonly listed as preferred rather than required</li>
    <li>Basic point-of-sale, handheld scanner and smartphone competence</li>
    <li>Availability for evenings, weekends and holiday peak periods</li>
    <li>Physical ability to stand, lift and move stock for a full shift</li>
    <li>Clear communication and reliability, which decide more retail hires than any qualification</li>
</ul>

<h3>What it pays, and what sets it</h3>
<ul>
    <li><strong>The measured median is $17.03 an hour</strong> for retail salespersons, with the middle half of the occupation between <strong>$14.38 and $18.59</strong> (BLS, May 2025)</li>
    <li><strong>Your state sets the floor.</strong> It is $7.25 in a dozen states and <strong>$18.40 in the District of Columbia</strong>, and five states have no minimum wage law of their own</li>
    <li><strong>First-line retail supervisors earn a median of $23.33 an hour</strong>, the genuine pay step inside the sector</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Look up your state and city minimum wage before you judge any advertised rate.</strong> The same hourly number is a strong offer in one state and the bare legal minimum in another.</p>

<p><strong>Note:</strong> pay, scheduling, benefits and eligibility are set by each employer and by state law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Retail associate is the easiest job in the United States to be hired into and the hardest to research honestly. Almost every guide quotes the same pay range, and that range starts below what nine out of ten retail associates actually earn. This page uses the measured figures, and spends most of its length on the part the guides skip: how the hiring actually works, and what decides whether the offer in front of you is a good one.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-retail-associate-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128717;&#65039; Browse Retail Associate Jobs in the USA &rarr;
    </a>
</div>

<h2>"$12 to $18 an Hour" Understates This Job at Both Ends</h2>

<p>It is the range you will see on nearly every retail careers page. Set it against what the Bureau of Labor Statistics measured for <strong>retail salespersons in May 2025</strong>, across <strong>3,897,860</strong> of them:</p>

<ul>
    <li><strong>10th percentile &mdash; $13.08 an hour.</strong> Nine out of ten earn more than this.</li>
    <li><strong>25th percentile &mdash; $14.38 an hour.</strong></li>
    <li><strong>Median &mdash; $17.03 an hour</strong>, or about $35,400 across a full-time year.</li>
    <li><strong>75th percentile &mdash; $18.59 an hour.</strong></li>
    <li><strong>90th percentile &mdash; $23.02 an hour.</strong></li>
</ul>

<p>So <strong>$12 sits below the bottom tenth of the occupation</strong>, and <strong>$18 sits below the top quarter</strong>. The published range describes neither the floor nor the ceiling of this job. The honest summary is that a typical retail associate earns <strong>$17.03 an hour</strong>, the middle half earn between <strong>$14.38 and $18.59</strong>, and the best-paid tenth clear <strong>$23.02</strong>.</p>

<p>One distinction matters before you use those numbers. <strong>Cashier is a separate occupation to BLS</strong>, with its own median of <strong>$15.81 an hour</strong>. Many postings titled "retail associate" are register roles. If the duties in the advertisement are checkout rather than the sales floor, you are looking at the lower of the two markets, about <strong>$1.22 an hour</strong> lower.</p>

<h2>Your State Decides the Offer More Than Your R&eacute;sum&eacute; Does</h2>

<p>No guide that quotes a national range for American retail can be much use, because there is no national wage floor for this work. There are dozens.</p>

<p>The <strong>federal minimum wage is $7.25 an hour</strong> and has not moved since <strong>24 July 2009</strong>. Above it sits a patchwork:</p>

<ul>
    <li><strong>The District of Columbia is highest at $18.40</strong>, with <strong>Washington at $17.13</strong>, <strong>New York at $17.00</strong> in the New York City area, <strong>Connecticut at $16.94</strong> and <strong>California at $16.90</strong>.</li>
    <li><strong>States including Texas, Pennsylvania, North Carolina, Wisconsin and Idaho</strong> still sit at exactly <strong>$7.25</strong>.</li>
    <li><strong>Five states &mdash; Alabama, Louisiana, Mississippi, South Carolina and Tennessee &mdash; have no state minimum wage law at all.</strong> The federal $7.25 applies by default.</li>
</ul>

<p>Now look at what that does to the same job. The <strong>median</strong> for a retail associate is <strong>$19.02 in Washington</strong>, <strong>$18.48 in California</strong>, <strong>$18.25 in Colorado</strong>, <strong>$18.17 in Hawaii</strong> and <strong>$18.13 in New York</strong>. At the other end it is <strong>$13.77 in Mississippi</strong>, <strong>$13.92 in West Virginia</strong> and <strong>$14.07 in Arkansas</strong>.</p>

<p><strong>The legal minimum in Washington, D.C. is higher than the national median for this occupation.</strong> Someone on the bare legal floor in one place out-earns more than half of everyone doing the same job nationally. Look up your state and city rate before you judge any advertisement; it will tell you more in ten seconds than any salary table.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/retail-associate-jobs-in-usa-service.jpg"
         alt="A retail associate in a blue apron helping a customer with folded clothing on the sales floor of a US store"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>One Thing You Cannot Be Paid</h2>

<p>A retail associate <strong>cannot be put on a tipped minimum wage</strong>. Under the Fair Labor Standards Act the tip credit applies only to employees who customarily and regularly receive <strong>more than $30 a month in tips</strong>, which does not describe general retail sales work. Your employer owes you the full applicable minimum wage in <strong>direct cash wages</strong>.</p>

<p>It is worth knowing because the same applicant pool moves between retail and restaurant work, where the rules are completely different, and because an offer quoting a sub-minimum "base plus tips" for shop-floor work is not a lawful one.</p>

<h2>What the Job Actually Requires</h2>

<p>The draft version of this list asks for "a high school diploma or GED (preferred, not always required)". The official position is plainer than that. For retail sales workers, BLS records:</p>

<ul>
    <li><strong>Typical entry-level education: no formal educational credential.</strong></li>
    <li><strong>Work experience required: none.</strong></li>
    <li><strong>On-the-job training: a few days to a few months.</strong></li>
</ul>

<p>So the diploma line on a posting is a preference, not a gate, and no part of this job requires you to have done it before. What employers screen on instead is narrower and much more practical:</p>

<ul>
    <li><strong>Availability.</strong> Evenings, weekends and the holiday peak. This decides more retail hires than anything on your r&eacute;sum&eacute;, and stating it plainly and early is the single strongest thing an applicant with no experience can do.</li>
    <li><strong>Physical reality of the shift.</strong> Standing for hours and lifting stock, commonly up to 50 pounds.</li>
    <li><strong>Clear, calm communication.</strong> Every interview in this sector asks about a difficult customer. Prepare one real example.</li>
    <li><strong>Basic systems confidence.</strong> A register, a handheld scanner, an app for your schedule.</li>
</ul>

<h2>The Promotion Ladder Is Real, and Narrower Than It Sounds</h2>

<p>"Many store managers started as associates" is true, and worth pricing rather than repeating. The first rung up is <strong>first-line supervisor of retail sales workers</strong>, and BLS measures it at a median of <strong>$23.33 an hour</strong>, or <strong>$48,520</strong> a year.</p>

<p>Against the associate median of $17.03, that is a step of <strong>$6.30 an hour</strong> &mdash; worth around <strong>$13,000</strong> across a full-time year, and a bigger raise than moving between states.</p>

<p>The limit is how many of those posts exist. There are <strong>1,121,800</strong> first-line retail supervisors against <strong>3,897,860</strong> associates, <strong>about one supervisor for every three and a half associates</strong>. The ladder is genuine, the rungs are real money, and there is nothing automatic about reaching them. Say early and often that you want key-holder and supervisor responsibility, and take the cross-training that leads there.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/retail-associate-jobs-in-usa-checkout.jpg"
         alt="A retail associate handing a folded sweater and a paper shopping bag to a customer at a US store checkout"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>A Flat Occupation With Half a Million Openings a Year</h2>

<p>Both halves of this matter, and guides normally print only the friendly one.</p>

<p>Employment of retail sales workers is projected to show <strong>no change at all from 2025 to 2035</strong> &mdash; a fall of <strong>5,700</strong> jobs across the decade, which on a base of <strong>4,271,400</strong> rounds to zero per cent. The retail trade sector as a whole is equally still: <strong>15.49 million jobs in August 2026</strong>, up <strong>0.31 per cent</strong> on the year.</p>

<p>And yet the occupation is expected to produce about <strong>550,600 openings every year</strong> across the decade. Every one of them comes from replacing people who move on, not from growth.</p>

<p>That is the honest shape of retail associate work: <strong>you can almost always get hired, and you should not plan to still be doing the same role in ten years.</strong> Treat the first job as the entry point it genuinely is, and aim at the supervisor step, at specialty and commission selling, or at using an employer's education benefit while you are there.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do retail associates earn in the USA?</h3>
<p>The measured median is $17.03 an hour for retail salespersons, about $35,400 a year full time. The middle half earn between $14.38 and $18.59, the bottom tenth below $13.08 and the top tenth above $23.02 (BLS, May 2025).</p>

<h3>Is $12 an hour a realistic retail associate wage?</h3>
<p>It is below the 10th percentile of $13.08, so more than nine in ten retail associates earn more. Even the lowest state median, Mississippi at $13.77, is above it.</p>

<h3>Do I need a high school diploma to work as a retail associate?</h3>
<p>No. BLS records the typical entry-level education for retail sales workers as no formal educational credential and no prior work experience, with a few days to a few months of on-the-job training. Postings often list a diploma as preferred rather than required.</p>

<h3>Which states pay retail associates the most?</h3>
<p>By median hourly wage: Washington at $19.02, the District of Columbia at $18.51, California at $18.48, Colorado at $18.25, Hawaii at $18.17 and New York at $18.13. The lowest are Mississippi at $13.77, West Virginia at $13.92 and Arkansas at $14.07.</p>

<h3>Can a retail associate be paid a tipped minimum wage?</h3>
<p>No. The tip credit applies only to employees who customarily and regularly receive more than $30 a month in tips, which does not cover general retail sales work. You are owed the full applicable minimum wage in direct cash wages.</p>

<h3>Is retail associate a growing job?</h3>
<p>No. Employment is projected to change by 0 per cent from 2025 to 2035, a fall of 5,700 jobs. It still produces about 550,600 openings a year, all from replacing people who leave, which is why it stays easy to enter.</p>

<h3>Is a retail associate the same as a cashier?</h3>
<p>Not to BLS, and the gap is worth about $1.22 an hour: a $17.03 median for retail salespersons against $15.81 for cashiers. Read the duties in the posting rather than the job title.</p>

<h3>How much more do retail supervisors earn?</h3>
<p>First-line supervisors of retail sales workers have a median of $23.33 an hour, or $48,520 a year &mdash; $6.30 an hour above the associate median. There are 1,121,800 of those posts against 3,897,860 associates.</p>

<h2>People Also Search For</h2>

<h3>Retail sales associate salary</h3>
<p>A measured median of $17.03 an hour, with the middle half of the occupation between $14.38 and $18.59.</p>

<h3>Entry level retail jobs no experience</h3>
<p>The official entry requirement is no formal educational credential and no prior work experience. Availability decides the hire.</p>

<h3>Part time retail jobs near me</h3>
<p>Widely available year round. Ask how far ahead the schedule is posted and whether shifts get cut on the day.</p>

<h3>Retail associate job description</h3>
<p>Sales floor service, register work, restocking, returns and seasonal resets, with standing and lifting for a full shift.</p>

<h3>Highest paying states for retail workers</h3>
<p>Washington, the District of Columbia, California, Colorado, Hawaii and New York, mostly because their wage floors are high.</p>

<h3>Minimum wage by state</h3>
<p>$7.25 federally since 2009, up to $18.40 in the District of Columbia, and five states with no minimum wage law of their own.</p>

<h3>Store manager career path</h3>
<p>The supervisor step is a median of $23.33 an hour, but there is roughly one supervisor post per three and a half associates.</p>

<h3>Seasonal retail jobs hiring</h3>
<p>The holiday intake is the largest of the year and a genuine route into a permanent role. Say you are available for the peak.</p>

<h2>More Job Guides</h2>

<p>Comparing entry-level routes? These cover them:</p>

<ul>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; the wider market, why published averages disagree by 57 per cent, and the scheduling question to ask at interview.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; the register side of the same store, and the till shortage rule that protects your pay.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; the other fast American entry route, and what your own car really costs per mile.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; an office route with no degree requirement, and the projection you should see first.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the same customer skills applied from home.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the honest answer on entry-level US sponsorship.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; the same first job under award wages instead of state minimums.</li>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> &mdash; shop-floor work where pay is set by job level rather than by state.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers, legal or immigration advice. Minimum wage rates, wage survey figures, employment projections and employer benefits change, and posting counts on any job board are a moving figure rather than a statistic. Confirm the current position with the Bureau of Labor Statistics, the US Department of Labor, your state labor department and the employer's own advertisement before applying.</p>
HTML;
    }
}
