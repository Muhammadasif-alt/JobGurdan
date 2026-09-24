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
 * "How to Apply for Amazon Fulfillment Center Jobs in USA" — unusual among
 * these employer guides because Amazon does publish its pay. The honest
 * complication is who can take the job: these are in-person US roles that
 * require existing US work authorisation, and Amazon shows no sponsorship
 * route for hourly warehouse work.
 *
 * Corrections to the draft (checked against aboutamazon.com,
 * hiring.amazon.com, amazon.jobs, bls.gov and uscis.gov, 21 September 2026):
 *
 * 1. The draft quotes ZipRecruiter and Gridwise pay figures. Aggregator
 *    estimates are never published here. Amazon's own announcement of
 *    16 September 2026 carries the only Amazon pay figures in this guide,
 *    and the BLS occupational median is used as the independent benchmark.
 *
 * 2. The draft presents $20/hour as the fulfillment centre rate. Amazon's
 *    announcement scopes it to "full-time core operations roles". The words
 *    "part-time" do not appear in that announcement at all, so the guide
 *    does not extend the figure to part-time or flex work.
 *
 * 3. The draft's four hours bands are wrong. Amazon publishes three: Full
 *    and Reduced Time at 30 to 40 hours, Part Time at 20 to 29, Flex Time
 *    at 0 to 19.
 *
 * 4. The draft says benefits "begin on day one". Amazon's own benefits page
 *    gates Career Choice, dental and vision behind 90 days of employment.
 *
 * 5. The 49-pound lifting figure, the minimum age of 18 and the English
 *    requirement could not be found on any stable Amazon page. They are not
 *    published here as fact.
 *
 * 6. The draft links a hiring.amazon.com landing page that refuses automated
 *    requests, and an amazon.jobs team URL that already redirects. The guide
 *    links the amazon.jobs category page, which is stable and does not
 *    redirect. Job-ID links are never published; they expire.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AmazonFulfillmentCenterJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://amazon.jobs/content/en/job-categories/fulfillment-center-warehouse-associate';

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
        $title = 'How to Apply for Amazon Fulfillment Center Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Amazon raised minimum starting pay to $20 an hour in September 2026, but only for full-time core operations roles, and only for people who can already work in the US. Here is what that means for you.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa.jpg',
                'tags' => 'amazon fulfillment center jobs, amazon warehouse jobs, amazon hiring usa, amazon pay per hour, warehouse associate jobs, amazon career choice, us work authorization, eb-3 other workers',
                'meta_title' => 'Amazon Fulfillment Center Jobs: How to Apply',
                'meta_description' => 'Amazon fulfillment center jobs in the USA: the real pay Amazon publishes, which roles the $20 rate covers, and the work authorization rule that decides it.',
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
            ['name' => 'Amazon, US Fulfillment Centers'],
            ['type' => 'Company', 'display_reference' => 'amazon-us-fulfillment']
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
                'position' => 'Fulfillment Center Associate, Amazon, US Sites',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full and Reduced Time 30 to 40 hours, Part Time 20 to 29, Flex Time 0 to 19',
                'language' => 'English',
                // Amazon's 16 September 2026 announcement scopes the $20 rate to
                // full-time core operations roles only, so it is quoted in the
                // guide rather than stored as this aggregated listing's pay.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the fulfillment centre and warehouse associate roles Amazon advertises across its US sites, not a single vacancy and not a job advertised by JobGader. Applications are made on Amazon's own hiring site. These are in-person roles at a physical US fulfillment centre.</p>

<h3>What the work involves</h3>
<ul>
    <li>Stowing, picking, packing, sorting and shipping customer orders.</li>
    <li>Some roles operate Power Industrial Trucks. Amazon lists walkies, reach trucks, stand-ups, clamp trucks and order pickers, and states that training is provided.</li>
    <li>Amazon states that PIT work can reach extended heights of up to 35 feet.</li>
</ul>

<h3>Pay</h3>
<p>Amazon announced on 16 September 2026 that minimum starting pay for US full-time core operations roles is increasing to $20 an hour, with average pay reaching nearly $24 an hour, and average total compensation exceeding $32 an hour once the value of benefits is included. That announcement does not mention part-time or flex roles, so confirm the rate on the live posting for the schedule you want.</p>

<h3>Hours</h3>
<p>Amazon publishes three bands: Full and Reduced Time at 30 to 40 hours a week, Part Time at 20 to 29 hours, and Flex Time at 0 to 19 hours. Early morning, day, evening and overnight shifts are offered.</p>

<h3>Applying</h3>
<p>Amazon states that most hourly roles need no resume and no interview, and that you can receive a job offer the same day. Before you start you attend a pre-hire appointment to provide proof of identity and employment eligibility.</p>

<h3>Work authorisation</h3>
<p>That eligibility check is the decisive requirement. You must already hold the right to work in the United States. Amazon publishes no visa sponsorship route for hourly fulfillment centre roles, and these jobs cannot be done from outside the US.</p>

<h3>Fraud warning</h3>
<p>Amazon states that all genuine Amazon job opportunities are posted on its official job board, and that Amazon will never ask you to provide payment information for products or services.</p>

<p>Pay, shift bands, benefits terms and work-authorisation rules are set by Amazon, the US Department of Labor and USCIS &mdash; not by JobGader. Confirm the rate and the requirements on the live posting for the site you are applying to.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Amazon is one of the few large employers in these guides that actually publishes what it pays. On 16 September 2026 it announced a raise, and the numbers are real and checkable. But two things get lost in almost every article written about it, and both of them decide whether this job is available to you.</p>

<p><strong>The $20 an hour figure applies to full-time core operations roles.</strong> And <strong>every one of these jobs requires that you can already legally work in the United States.</strong></p>

<p>Here is the whole picture, with Amazon's own words and nothing borrowed from a salary-estimate site.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa-associate.jpg" alt="Amazon fulfillment centre associates in high visibility vests handling parcels on a conveyor line" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Fulfillment centre associates stow, pick, pack, sort and ship customer orders. Amazon says most hourly roles need no resume and no interview.</figcaption>
</figure>

<h2>Where Do I Apply?</h2>

<p>Amazon's own job board. Nothing else is a real Amazon vacancy.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://amazon.jobs/content/en/job-categories/fulfillment-center-warehouse-associate" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Amazon Warehouse Associate Jobs &rarr;</a>
</p>

<p>A note on links, because this matters more than it sounds. We deliberately do not publish links to individual Amazon job postings. Those carry a job ID and expire, often within weeks, and a dead link is the single most common complaint about pages like this one. The address above is a category page that does not expire.</p>

<h2>What Does Amazon Actually Pay?</h2>

<p>From Amazon's announcement of 16 September 2026, in its own words:</p>

<blockquote style="border-left:4px solid #b3151a;padding:12px 18px;margin:20px 0;background:#fafafa;color:#374151;">
    "Minimum starting pay for U.S. full-time core operations roles&mdash;many of which require no prior experience&mdash;is increasing to $20/hour, with average pay reaching nearly $24/hour. Average total compensation exceeds $32/hour when you include the value of Amazon's industry-leading benefits package."
</blockquote>

<p>Read the first four words carefully. <strong>Full-time core operations.</strong> We searched that entire announcement for the words "part-time" and "flex". They do not appear once. Amazon is not saying the $20 rate covers a 19-hour flex schedule, and neither will we.</p>

<h3>The independent benchmark</h3>

<p>For context that does not come from Amazon or from a salary-guess website, the US Bureau of Labor Statistics publishes a median for this kind of work. In its occupational profile for hand laborers and material movers, covering warehouse labourers, stockers and order fillers, BLS put the median annual wage at <strong>$38,220 in May 2025</strong>, which works out at about <strong>$18.38 an hour</strong>.</p>

<p>That makes Amazon's published starting rate meaningfully above the occupational median. It is a genuinely competitive number, which is exactly why it does not need any help from invented figures.</p>

<h2>How Many Hours Will I Get?</h2>

<p>Amazon publishes three bands, not four. A lot of guides split these wrongly, so here is what Amazon's own benefits page says:</p>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:12px;text-align:left;border:1px solid #ddd;">Band</th>
            <th style="padding:12px;text-align:left;border:1px solid #ddd;">Hours per week</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:12px;border:1px solid #ddd;">Full and Reduced Time</td>
            <td style="padding:12px;border:1px solid #ddd;"><strong>30 to 40 hours</strong></td>
        </tr>
        <tr style="background:#fafafa;">
            <td style="padding:12px;border:1px solid #ddd;">Part Time</td>
            <td style="padding:12px;border:1px solid #ddd;">20 to 29 hours</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #ddd;">Flex Time</td>
            <td style="padding:12px;border:1px solid #ddd;">0 to 19 hours</td>
        </tr>
    </tbody>
</table>

<p>Shifts run early morning, daytime, evening and overnight. You pick your shift during the online application.</p>

<h2>The Requirement That Decides Everything</h2>

<p>This is the section most pages on this subject leave out, and it is the one that matters most to readers outside the United States.</p>

<p>Amazon's own description of its hiring process includes a pre-hire appointment where, in Amazon's words, you provide <strong>proof of your identity and employment eligibility</strong>. That is the I-9 check, it happens in person in the United States, and it happens before you start. <strong>You must already hold US work authorisation.</strong></p>

<p>Amazon publishes no visa sponsorship pathway for hourly fulfillment centre roles. There is no page on its hiring site offering one, and the same-day hiring process it advertises is not compatible with one.</p>

<h3>Is there any route at all?</h3>

<p>On paper, yes. US Citizenship and Immigration Services runs an employment-based third preference category that includes what it calls <em>other workers</em>, described as people "capable of performing unskilled labor whose job requires less than 2 years training or experience". In practice that route needs the employer to start a permanent labour certification and offer a permanent full-time job, it is capped at 10,000 visas a year worldwide, and the backlogs for high-demand countries run for years.</p>

<p>What it is not is something you obtain by filling in an online application and picking a shift. If someone is offering you an Amazon warehouse job with a visa attached, that is not how any of this works.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa-scan.jpg" alt="An Amazon warehouse associate scanning a parcel at a workstation inside a US fulfillment centre" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">These are in-person roles at a physical US site. The pre-hire appointment checks identity and employment eligibility before you start.</figcaption>
</figure>

<h2>What Are the Benefits, Really?</h2>

<p>Amazon's benefits are genuinely strong, but the phrase "from day one" is doing a lot of work in most articles. Amazon's own benefits page gates several of them behind 90 days:</p>

<ul>
    <li><strong>Healthcare</strong> &mdash; Amazon describes an Essential Plan at $5 a week with $5 copays for regular full-time employees.</li>
    <li><strong>Medical Advice Line</strong> &mdash; access to nurses, which Amazon states is available starting on day 1 at no cost.</li>
    <li><strong>Career Choice</strong> &mdash; prepaid tuition of up to $5,250 a year through more than 475 education partners. Amazon's benefits page states this is <strong>after 90 days of employment</strong>.</li>
    <li><strong>Dental and vision</strong> &mdash; also <strong>after 90 days</strong>, and covering the employee only.</li>
    <li><strong>401(k)</strong> with company match, paid parental leave, flexible time off and 24/7 mental health support.</li>
</ul>

<h3>The two new ones announced in September 2026</h3>

<p><strong>Employee grocery discount.</strong> Amazon says that starting <strong>1 October 2026</strong>, all US Amazon employees get an uncapped 10% off eligible grocery and everyday essentials on Amazon.com and Whole Foods Market online, and an uncapped 20% off in store at Whole Foods Market, including the hot bar and salad bar.</p>

<p><strong>Day 1 Financial.</strong> A membership in First Tech Federal Credit Union, federally insured by the NCUA. Amazon says access "will begin rolling out in late 2026 and become broadly available in 2027", so it is not available to a new starter today.</p>

<h2>What We Could Not Confirm</h2>

<p>Three things appear in nearly every article on this subject, and we could not find any of them on a stable Amazon page:</p>

<ul>
    <li><strong>A 49-pound lifting requirement.</strong> Widely quoted, not on Amazon's hiring pages. It likely comes from individual job postings, which expire.</li>
    <li><strong>A minimum age of 18.</strong> Almost certainly true, but Amazon's hiring site and FAQ do not state it.</li>
    <li><strong>An English proficiency requirement.</strong> Same position.</li>
</ul>

<p>They may well all be accurate. We simply do not publish requirements we cannot show you on the employer's own page. Check the live posting for the site you are applying to.</p>

<h2>How Do I Apply, Step by Step?</h2>

<ol>
    <li><strong>Open Amazon's hiring site</strong> and let it find roles near you, or enter a postcode.</li>
    <li><strong>Compare shift, band and rate</strong> on the listing itself. The pay shown there is the pay for that role, whatever any article says.</li>
    <li><strong>Apply.</strong> Amazon states most hourly roles need no resume and no interview. Delivery driver roles do require an interview.</li>
    <li><strong>Book your pre-hire appointment</strong> and bring identity and work-eligibility documents.</li>
    <li><strong>Attend orientation</strong> and start.</li>
    <li><strong>Never pay anyone.</strong> Amazon does not charge to apply and does not use paid recruiters for these roles.</li>
</ol>

<h2>The Scam Warning, in Amazon's Own Words</h2>

<blockquote style="border-left:4px solid #b3151a;padding:12px 18px;margin:20px 0;background:#fafafa;color:#374151;">
    "Scammers contact people unexpectedly pretending to be recruiters and offering resume review services for a fee. This isn't how legitimate hiring works." &mdash; and &mdash; "All genuine Amazon job opportunities are posted on our official job board at www.amazon.jobs."
</blockquote>

<p>Amazon also states it will never ask you to provide payment information, including gift cards, for products or services. A recruiter asking for a fee to place you in an Amazon warehouse is not connected to Amazon.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does an Amazon fulfillment center job pay?</h3>
<p>Amazon announced on 16 September 2026 that minimum starting pay for US full-time core operations roles rises to $20 an hour, with average pay near $24 an hour. Confirm the rate on the live posting, as it varies by site and shift.</p>

<h3>Does the $20 rate apply to part-time work?</h3>
<p>Amazon's announcement refers only to full-time core operations roles and does not mention part-time or flex schedules. Check the posting for the band you want.</p>

<h3>Do I need experience or a resume?</h3>
<p>Amazon states most hourly roles need no resume and no interview, and that you can get a job offer the same day.</p>

<h3>Can I apply from outside the USA?</h3>
<p>No. These are in-person US roles and Amazon checks proof of identity and employment eligibility before you start. You need existing US work authorisation, and Amazon publishes no sponsorship route for hourly warehouse roles.</p>

<h3>Does Amazon sponsor visas for warehouse jobs?</h3>
<p>Not for hourly fulfillment centre roles. The only government route for unskilled work, the employment-based third preference "other workers" category, is employer-driven, capped at 10,000 visas a year worldwide and heavily backlogged.</p>

<h3>When do Amazon benefits start?</h3>
<p>Not all on day one. The Medical Advice Line is day-one, while Amazon's benefits page places Career Choice tuition, dental and vision after 90 days of employment.</p>

<h3>Can I learn to drive a forklift?</h3>
<p>Some roles operate Power Industrial Trucks including reach trucks and order pickers, and Amazon states training is provided. This work can reach heights of up to 35 feet.</p>

<h3>How heavy is the lifting?</h3>
<p>A 49-pound limit is widely quoted but does not appear on Amazon's hiring pages. Check the physical requirements on the specific posting.</p>

<h2>People Also Search For</h2>

<h3>Amazon warehouse jobs near me</h3>
<p>Amazon's hiring site locates roles by postcode. Every genuine vacancy is posted there or on amazon.jobs.</p>

<h3>Amazon pay per hour 2026</h3>
<p>$20 an hour minimum starting pay for US full-time core operations roles, with average pay near $24, announced 16 September 2026.</p>

<h3>Amazon Career Choice</h3>
<p>Prepaid tuition of up to $5,250 a year through more than 475 education partners, available after 90 days of employment.</p>

<h3>Amazon employee grocery discount</h3>
<p>From 1 October 2026, 10% off eligible groceries on Amazon.com and Whole Foods online, and 20% off in store at Whole Foods.</p>

<h3>Amazon Day 1 Financial</h3>
<p>A credit union membership through First Tech Federal Credit Union, rolling out in late 2026 and broadly available in 2027.</p>

<h3>Amazon PIT certification</h3>
<p>Power Industrial Truck work covering walkies, reach trucks, stand-ups, clamp trucks and order pickers, with training provided by Amazon.</p>

<h3>EB-3 other workers visa</h3>
<p>The US employment-based category for unskilled labour requiring less than two years of training. Employer-driven, capped at 10,000 a year, and not obtainable through an hourly job application.</p>

<h3>Amazon job scam</h3>
<p>Amazon says genuine roles appear only on its official job board and that it never asks for payment information. Paid placement offers are not Amazon.</p>

<h2>More Job Guides</h2>

<p>Comparing large US employers, or looking for the route that fits your situation? These cover the ground:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa">How to Apply for Walmart Store Associate Jobs in the USA</a> &mdash; the other giant US hourly employer, compared honestly.</li>
    <li><a href="/blog/forklift-operator-jobs-in-usa">Forklift Operator Jobs in USA</a> &mdash; where PIT certification actually takes you.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; the route that does require an interview.</li>
    <li><a href="/blog/how-to-get-an-entry-level-office-job-with-no-experience">How to Get an Entry-Level Office Job With No Experience</a> &mdash; if you want off the warehouse floor.</li>
    <li><a href="/blog/how-to-apply-for-unilever-factory-jobs-in-indonesia">How to Apply for Unilever Factory Jobs in Indonesia</a> &mdash; an employer that publishes no pay at all.</li>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a> &mdash; a supermarket that publishes its rate and pays it at every age.</li>
    <li><a href="/blog/how-to-apply-for-tesla-production-jobs-in-the-usa">How to Apply for Tesla Production Jobs in the USA</a> &mdash; factory work with a published hourly range and stock awards.</li>
    <li><a href="/blog/how-to-apply-for-ups-package-handler-jobs-in-the-usa">How to Apply for UPS Package Handler Jobs in the USA</a> &mdash; warehouse work where a union contract publishes the wage scale.</li>
    <li><a href="/blog/how-to-apply-for-fedex-delivery-jobs-in-the-usa">How to Apply for FedEx Delivery Jobs in the USA</a> &mdash; and how to tell a real FedEx job from a contractor advert.</li>
    <li><a href="/blog/how-to-apply-for-home-depot-retail-jobs-in-the-usa">How to Apply for Home Depot Retail Jobs in the USA</a> &mdash; store pay Home Depot prints itself, and the route from hourly to store leader.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Amazon's own newsroom announcement of 16 September 2026, its hiring and benefits pages, US Bureau of Labor Statistics occupational data and US Citizenship and Immigration Services guidance, checked on 21 September 2026. Amazon's pay varies by site and shift, its benefits terms change, and immigration rules change. Always check the live posting and the official government source before acting.</p>
HTML;
    }
}
