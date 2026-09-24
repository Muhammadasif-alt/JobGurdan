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
 * "How to Apply for UPS Package Handler Jobs in the USA" - an employer guide
 * rebuilt on the UPS careers site and the Teamsters National Master Agreement,
 * rather than the Indeed listings and review snippets the draft leaned on.
 *
 * Corrections to the draft (checked against jobs-ups.com, about.ups.com and
 * the 2023-2028 UPS Teamsters National Master Agreement, September 2026):
 *
 * 1. The draft made Indeed the primary apply link. Aggregator links are
 *    against site policy and Indeed blocks crawlers, so the UPS warehouse
 *    careers page is the only apply URL here.
 *
 * 2. The draft quoted no pay figure at all, only "excellent weekly pay". The
 *    National Master Agreement publishes the scale: new part-time hires start
 *    at $21 an hour and advance to $23, existing part-timers were raised to no
 *    less than $21 immediately, and part-time longevity increases run up to
 *    $1.50 an hour on top.
 *
 * 3. The draft's shift times are wrong. It gives one shift, "AM Preload
 *    roughly 3:30 AM to 9:30 AM, Tuesday through Saturday". UPS lists five:
 *    Preload about 3:00 AM to 9:00 AM, Sunrise about 3:00 AM to 8:00 AM, Day
 *    about 10:30 AM to 4:30 PM, Twilight about 5:00 PM to 10:00 PM and Night
 *    about 11:00 PM to 3:00 AM.
 *
 * 4. The draft says part-time roles run 3 to 6 hours a day. UPS says 3 to 5.
 *
 * 5. The draft gives the lifting limit as "up to 70 lbs" alone. UPS's own
 *    wording is that packages typically weigh 25 to 35 lbs and may weigh up
 *    to 70 lbs, which is a materially different day's work.
 *
 * 6. The draft omits the Earn and Learn tuition programme entirely, worth up
 *    to $25,000 towards tuition, books and fees. For students it is the single
 *    biggest reason to take the job.
 *
 * 7. The draft says pay lands "every Friday" and promises "generous paid time
 *    off that increases with tenure". Neither is on a UPS source; UPS says
 *    weekly pay, healthcare after a waiting period, 401(k) and money for
 *    college at some locations.
 *
 * 8. The draft's "five to six years" wait for a driver job is one reviewer's
 *    anecdote. The contract facts replace it: 7,500 new full-time jobs
 *    created, 22,500 open positions filled, the 22.4 two-tier driver
 *    classification abolished, and full-time openings filled by seniority.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class UpsPackageHandlerJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.jobs-ups.com/us/en/warehouse-workers';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on finding work, applying well and understanding what a job really pays.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Apply for UPS Package Handler Jobs in the USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'UPS package handler pay is not a guess: the Teamsters national contract publishes the scale. This guide covers the starting rate, all five shifts with real times, the weights you actually lift, the tuition programme most guides miss, and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-ups-package-handler-jobs-in-the-usa.jpg',
                'tags' => 'ups package handler jobs, ups warehouse jobs usa, ups careers apply, ups preload shift, ups package handler pay, teamsters ups contract, ups earn and learn, warehouse jobs no experience usa',
                'meta_title' => 'UPS Package Handler Jobs USA: Pay, Shifts, How to Apply',
                'meta_description' => 'UPS package handler jobs in the USA: the union contract pay scale, all five shift times, the real lifting weights and the $25,000 tuition programme.',
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
            ['name' => 'UPS, US Warehouses and Sort Facilities'],
            ['type' => 'Company', 'display_reference' => 'ups-us-warehouses']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Package Handler, UPS US Facilities',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Part-time shifts of about 3 to 5 hours: preload, sunrise, day, twilight or night sort',
                'language' => 'English',
                // Published in the 2023-2028 UPS Teamsters National Master
                // Agreement, which sets new part-time hires at $21 an hour
                // advancing to $23. That is a negotiated contract term, not a
                // job board estimate.
                'salary_currency' => 'USD',
                'salary_period' => 'Hourly',
                'salary_minimum' => 21,
                'salary_maximum' => 23,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Part-time package handler roles loading, unloading and sorting at UPS warehouses and sort facilities across the United States, with union contract pay.',
                'seo_keywords' => 'ups package handler jobs, ups warehouse jobs usa, ups preload shift, ups careers apply, warehouse jobs no experience usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>UPS hires part-time package handlers to load, unload and sort packages at warehouses and sort facilities across the United States, on fixed shifts that run around the clock.</p>

<h3>What the work involves</h3>
<p>Loading and unloading trailers and package cars, sorting packages by route and destination, and checking that labels are correct and packages undamaged before they move on.</p>

<h3>Common requirements</h3>
<ul>
    <li>At least 18 years old, with the legal right to work in the United States</li>
    <li>Able to lift packages that typically weigh 25 to 35 lbs and may weigh up to 70 lbs</li>
    <li>Comfortable standing and moving at pace for the whole shift</li>
    <li>Reliable attendance on an early morning, evening or overnight shift</li>
    <li>No warehouse or logistics experience needed</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay and conditions for these roles are set by UPS and the Teamsters national agreement &mdash; not by JobGader. Apply directly on the UPS careers site, and never pay anyone for a UPS job or a shift.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on the UPS careers site, pick a building near you, and choose a shift.</strong> No warehouse experience is required, you need to be 18, and the pay is not a mystery: a national union contract publishes the whole scale in writing.</p>

<p>That last point is what makes this job different from most warehouse work. With UPS you can read the negotiated rate before you apply, instead of guessing from a job board's estimate.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.jobs-ups.com/us/en/warehouse-workers" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#644117;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Search UPS Warehouse Jobs &rarr;
    </a>
</div>

<h2>What a UPS Package Handler Actually Does</h2>

<p>You work inside a UPS warehouse or sort facility, moving packages between trailers, conveyor belts and the brown package cars that run the delivery routes.</p>

<ul>
    <li>Loading and unloading UPS trailers and package cars</li>
    <li>Sorting packages by route, zone or destination</li>
    <li>Checking labels and spotting damaged packages before they move on</li>
    <li>Keeping pace with the belt through the whole shift</li>
</ul>

<p>It is a physical job with a simple task list. The difficulty is volume and pace, not complexity.</p>

<h2>The Weights You Actually Lift</h2>

<p>Most guides quote a single number here, and it misleads people. UPS's own wording is more useful: packages <strong>typically weigh 25 to 35 lbs and may weigh up to 70 lbs</strong>.</p>

<p>So the honest picture is a steady stream of 25 to 35 lb boxes with occasional heavy ones, not a shift of 70 lb lifts. That is still demanding work, especially in the first two weeks, but it is a different job from the one the "up to 70 lbs" headline suggests.</p>

<h2>All Five Shifts, With UPS's Own Times</h2>

<p>UPS buildings run around the clock, and the shift you pick shapes the job more than the building does. UPS lists five:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#644117;color:#fff;">
            <th style="padding:10px;text-align:left;">Shift</th>
            <th style="padding:10px;text-align:left;">Approximate hours</th>
            <th style="padding:10px;text-align:left;">What you are doing</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Preload</strong></td><td style="padding:10px;">3:00 AM to 9:00 AM</td><td style="padding:10px;">Loading package cars before the delivery routes leave</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sunrise</strong></td><td style="padding:10px;">3:00 AM to 8:00 AM</td><td style="padding:10px;">Loading tractor trailers</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Day</strong></td><td style="padding:10px;">10:30 AM to 4:30 PM</td><td style="padding:10px;">Daytime sorting and loading</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Twilight</strong></td><td style="padding:10px;">5:00 PM to 10:00 PM</td><td style="padding:10px;">Evening sort</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Night</strong></td><td style="padding:10px;">11:00 PM to 3:00 AM</td><td style="padding:10px;">Overnight sort</td></tr>
    </tbody>
</table>
</div>

<p>Part-time shifts usually run <strong>3 to 5 hours</strong>. Guides quoting a single "3:30 AM to 9:30 AM preload, Tuesday to Saturday" are describing one building's advert, not the pattern. Exact start times vary by facility, so read the posting for the building you want.</p>

<p>The practical point: if you are studying or holding a second job, twilight and night sort fit around a daytime timetable, while preload leaves your whole day free but costs you the early start every single morning.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-ups-package-handler-jobs-in-the-usa-sort.jpg" alt="Package handlers sorting boxes on a conveyor belt inside a UPS warehouse" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What It Pays, From the Contract Itself</h2>

<p>UPS package handlers in the United States are covered by the <strong>Teamsters National Master Agreement</strong>, which runs from 1 August 2023 to 31 July 2028. It publishes the wage terms, so nobody has to estimate them:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#644117;color:#fff;">
            <th style="padding:10px;text-align:left;">Contract term</th>
            <th style="padding:10px;text-align:left;">What it says</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New part-time hires</td><td style="padding:10px;"><strong>Start at $21 an hour and advance to $23</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Existing part-timers</td><td style="padding:10px;">Raised to <strong>no less than $21 an hour</strong> immediately on ratification</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">General wage increases</td><td style="padding:10px;">$2.75 an hour in the first year, <strong>$7.50 an hour in total</strong> across the contract</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Longevity</td><td style="padding:10px;">Part-timers with service earn <strong>up to $1.50 an hour more</strong> on top of the general rises</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Payroll errors</td><td style="padding:10px;">Part-timers are paid a penalty of 4 hours, rising to 5 hours per pay period from 1 January 2026</td></tr>
    </tbody>
</table>
</div>

<p>Two things follow from that table. First, your starting rate is a floor set nationally, not something a local manager decides. Second, <strong>the gap between a new hire and a long-serving part-timer is real money</strong>, because longevity stacks on top of the general increases.</p>

<h3>The benefits UPS itself lists</h3>

<ul>
    <li><strong>Weekly pay</strong></li>
    <li><strong>Full healthcare</strong> &mdash; medical, dental and vision &mdash; after a waiting period</li>
    <li><strong>401(k)</strong></li>
    <li>A relaxed dress code and an emphasis on safety</li>
    <li>Money for college at some locations</li>
</ul>

<h2>The Tuition Programme Most Guides Miss</h2>

<p>This is the part worth the most money and it rarely makes the list. Through <strong>Earn and Learn</strong>, students at an approved college, university, trade or technical school can receive <strong>up to $25,000 towards tuition, books and fees</strong>.</p>

<p>Weighed against a part-time wage, that benefit can be worth more than the hourly rate over a degree. It is not offered at every location, so confirm it for the building you are applying to before you plan around it.</p>

<h2>Do You Need Experience?</h2>

<p><strong>No.</strong> This is an entry-level physical role. The requirements are:</p>

<ul>
    <li>At least <strong>18 years old</strong></li>
    <li>Legally able to work in the United States, and able to pass a background check</li>
    <li>Able to lift repeatedly through the shift</li>
    <li>Comfortable standing and moving at pace in a fast environment</li>
    <li>Willing to work an early morning, evening or overnight shift</li>
</ul>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-ups-package-handler-jobs-in-the-usa-preload.jpg" alt="Warehouse worker scanning a package at a UPS loading dock at sunrise" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Route to a Driving Job, Honestly</h2>

<p>Package handling is the usual way into UPS driving, but the wait is decided by seniority at your building, not by a number a stranger read online. What the 2023 contract changed is worth knowing:</p>

<ul>
    <li><strong>7,500 new full-time Teamster jobs</strong> created, and 22,500 open positions filled</li>
    <li><strong>The 22.4 driver classification was abolished.</strong> Those drivers became Regular Package Car Drivers and were placed into seniority, ending the two-tier wage system</li>
    <li>Air conditioning required in new package cars and other listed vehicles from January 2024, with fans retrofitted to existing package cars</li>
    <li>Martin Luther King Day became a full holiday for all UPS Teamsters for the first time</li>
</ul>

<p>So treat driving as a real destination with an unpredictable timeline. Ask your building how many full-time openings it posted last year &mdash; that number tells you more than any general estimate.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Open the UPS warehouse jobs page</strong> and search by your city or postcode.</li>
    <li><strong>Pick the building, then the shift.</strong> The shift decides your daily life more than the job title does.</li>
    <li><strong>Check the posting for Earn and Learn</strong> if you are studying, because it is location-specific.</li>
    <li><strong>Apply online.</strong> The UPS application is short and designed to move quickly.</li>
    <li><strong>Expect a brief interview or facility tour</strong> covering safety and lifting expectations.</li>
    <li><strong>Complete safety orientation</strong> before your first shift.</li>
    <li><strong>Never pay anyone for a UPS job.</strong> UPS does not charge applicants, and no agent can move you up a seniority list.</li>
</ol>

<p>If the shift times do not work for you, our <a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">Amazon fulfillment center guide</a> and our <a href="/blog/how-to-apply-for-tesla-production-jobs-in-the-usa">Tesla production guide</a> cover the closest alternatives on the same no-experience basis.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does a UPS package handler get paid?</h3>
<p>The Teamsters national agreement sets new part-time hires at $21 an hour, advancing to $23. Longevity increases add up to $1.50 an hour for part-timers with service.</p>

<h3>How heavy are the packages?</h3>
<p>UPS says packages typically weigh 25 to 35 lbs and may weigh up to 70 lbs. The heavy ones are occasional rather than constant.</p>

<h3>What shifts does UPS offer package handlers?</h3>
<p>Five: preload about 3:00 AM to 9:00 AM, sunrise about 3:00 AM to 8:00 AM, day about 10:30 AM to 4:30 PM, twilight about 5:00 PM to 10:00 PM and night about 11:00 PM to 3:00 AM.</p>

<h3>How many hours is a part-time UPS shift?</h3>
<p>Usually 3 to 5 hours, though hours rise during peak shipping season between late November and the new year.</p>

<h3>Do I need experience to be a UPS package handler?</h3>
<p>No. You need to be 18, legally able to work in the US, able to pass a background check and physically able to keep pace through the shift.</p>

<h3>Does UPS help pay for college?</h3>
<p>Yes, at some locations. The Earn and Learn programme offers up to $25,000 towards tuition, books and fees for students at approved institutions.</p>

<h3>How long does it take to become a UPS driver?</h3>
<p>It depends on seniority and full-time openings at your building, so no national figure is meaningful. The 2023 contract created 7,500 new full-time jobs and filled 22,500 open positions.</p>

<h3>When does UPS hire the most package handlers?</h3>
<p>Ahead of peak shipping season. Seasonal hiring ramps up from the autumn into the new year, and some seasonal workers are kept on.</p>

<h2>People Also Search For</h2>

<h3>UPS package handler pay 2026</h3>
<p>$21 an hour for new part-time hires under the national agreement, advancing to $23.</p>

<h3>UPS preload shift hours</h3>
<p>About 3:00 AM to 9:00 AM, loading package cars before delivery routes leave.</p>

<h3>UPS twilight sort</h3>
<p>About 5:00 PM to 10:00 PM, the evening sort that suits daytime students.</p>

<h3>UPS Earn and Learn tuition</h3>
<p>Up to $25,000 towards tuition, books and fees, at participating locations.</p>

<h3>UPS Teamsters contract 2028</h3>
<p>The National Master Agreement runs 1 August 2023 to 31 July 2028 and sets the whole wage scale.</p>

<h3>UPS package weight limit</h3>
<p>Typically 25 to 35 lbs, and up to 70 lbs on heavier items.</p>

<h3>UPS seasonal jobs peak season</h3>
<p>Hiring ramps from autumn into the new year, with longer hours through the peak.</p>

<h3>UPS 22.4 driver classification</h3>
<p>Abolished by the 2023 contract; those drivers became Regular Package Car Drivers with seniority.</p>

<h2>More Job Guides</h2>

<p>Comparing entry-level work across the US? These cover the alternatives:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-tesla-production-jobs-in-the-usa">How to Apply for Tesla Production Jobs in the USA</a> &mdash; factory work with a published hourly range and stock awards.</li>
    <li><a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">How to Apply for Amazon Fulfillment Center Jobs in USA</a> &mdash; the closest warehouse comparison on pay and shifts.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; the next step if driving is where you are heading.</li>
    <li><a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> &mdash; the licence that opens the better-paid driving work.</li>
    <li><a href="/blog/forklift-operator-jobs-in-usa">Forklift Operator Jobs in USA</a> &mdash; a short certificate that moves you off the belt.</li>
    <li><a href="/blog/how-to-become-a-long-haul-truck-driver-in-usa">How to Become a Long-Haul Truck Driver in USA</a> &mdash; the long-distance route and what it really pays.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; what the work authorisation rules actually allow.</li>
    <li><a href="/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa">How to Apply for Walmart Store Associate Jobs in the USA</a> &mdash; retail hours instead of sort hours.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the UPS careers site, UPS corporate pages and the 2023-2028 UPS Teamsters National Master Agreement. Pay, shift times, tuition support and benefits vary by facility and by local supplement. Always read the live posting for the building you are applying to.</p>
HTML;
    }
}
