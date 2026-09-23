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
 * "How to Apply for Tesco Supermarket Jobs in UK" — rare among these employer
 * guides because Tesco publishes its hourly rate, negotiates it with a union
 * and announces it in a press release. The figures are checkable, so this
 * guide quotes them and benchmarks them against the legal minimum.
 *
 * Corrections to the draft (checked against tescoplc.com, a live Tesco
 * Colleague advert, apply.tesco-careers.com and gov.uk, 21 September 2026):
 *
 * 1. All three job links in the draft return 404 today, two days after the
 *    draft says it checked them. Job-ID links are never published here.
 *
 * 2. The draft sends store jobseekers to careers.tesco.com, whose default
 *    results are Hungarian and Slovak corporate roles. UK store vacancies
 *    are listed on apply.tesco-careers.com, which is what this guide links.
 *
 * 3. "More than 999 results" appears on no Tesco page. It is an aggregator
 *    result-cap artefact, not a Tesco figure, so it is not published here.
 *
 * 4. The draft calls the London premium an "M25 supplement". Tesco's own
 *    term is the London Location Allowance, and the press release gives its
 *    value: GBP 1.27 an hour, taking the rate to GBP 14.55 inside the M25.
 *
 * 5. The draft implies the Colleague Clubcard discount starts on day one.
 *    Tesco's live adverts place the second family card at 12 weeks, and its
 *    benefits page places the card itself at four weeks.
 *
 * 6. The draft presents 16 guaranteed hours as universal. It is the store
 *    colleague policy; a live Cafe advert guarantees eight.
 *
 * 7. The draft's claim that applicants must "create a new profile by
 *    uploading your CV" because the system was updated is taken from a FAQ
 *    about UK Office roles. It does not describe store applications.
 *
 * 8. The draft omits the online assessment, which Tesco names as a stage of
 *    its hiring process, and omits that Tesco publishes no rate at all for
 *    Customer Delivery Driver or Cafe roles.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TescoSupermarketJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://apply.tesco-careers.com/v2/job/search';

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
        $title = 'How to Apply for Tesco Supermarket Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Tesco pays store colleagues 13.28 GBP an hour from March 2026, and the same rate at every age, which the law does not require. Here is what that is worth, and where the UK store jobs actually are.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-tesco-supermarket-jobs-in-uk.jpg',
                'tags' => 'tesco jobs, tesco supermarket jobs uk, tesco colleague pay, tesco careers, supermarket jobs uk, retail jobs uk, national living wage, right to work uk',
                'meta_title' => 'Tesco Supermarket Jobs in UK: How to Apply',
                'meta_description' => 'Tesco store colleagues earn 13.28 GBP an hour from March 2026, one rate at every age. Where UK store jobs really are, and an apply link that will not expire.',
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
            ['name' => 'Tesco Stores, United Kingdom'],
            ['type' => 'Company', 'display_reference' => 'tesco-stores-uk']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Tesco Colleague, Tesco Stores, United Kingdom',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Minimum 16 guaranteed hours a week, with roles from 12 hours also available',
                'language' => 'English',
                // Tesco is one of the few employers in these guides that
                // publishes its rate, so the range is stored rather than
                // withheld: the floor and the inside-M25 rate, both from the
                // 18 March 2026 press release.
                'salary_currency' => 'GBP',
                'salary_period' => 'Hourly',
                'salary_minimum' => 13.28,
                'salary_maximum' => 14.55,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Tesco Colleague roles across UK stores, GBP 13.28 an hour and GBP 14.55 inside the M25. Right to work in the UK required.',
                'seo_keywords' => 'tesco jobs, tesco colleague, supermarket jobs uk, tesco careers, retail jobs uk, tesco pay per hour',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the hourly-paid store roles Tesco advertises across the United Kingdom, not a single vacancy and not a job advertised by JobGader. Applications are made on Tesco's own careers system. These are in-person roles in a physical store.</p>

<h3>What the work involves</h3>
<ul>
    <li>Serving customers on the shop floor and at the checkout.</li>
    <li>Shelf replenishment and general store routines.</li>
    <li>Picking online grocery orders, and occasional delivery assistance.</li>
    <li>Knowing the product range well enough to help when a customer asks.</li>
</ul>

<h3>Pay</h3>
<p>Tesco announced on 18 March 2026 that the hourly rate for colleagues in stores and online fulfilment centres rose to &pound;13.28 from 29 March 2026, a 5.1 per cent award agreed with the union Usdaw and worth more than &pound;200 million. The London Location Allowance rose at the same time from &pound;1.21 to &pound;1.27 an hour, taking the rate to &pound;14.55 for colleagues inside the M25. Some locations attract other supplements; live Isle of Man adverts show &pound;14.11.</p>

<h3>Hours</h3>
<p>Tesco's policy is to offer new store colleagues a minimum of 16 guaranteed hours a week, with roles from 12 hours also available. Contracted hours are scheduled inside the availability window printed on each advert, and Tesco gives at least three weeks' notice of exact shifts. Cafe roles are advertised on a different policy, with eight guaranteed hours.</p>

<h3>Age</h3>
<p>Tesco recruits from school leaving age. Many store adverts, however, require you to be over 18, and Tesco states it can only accept candidates over 18 where the role means working before 6:15am or after 9:45pm, or in areas such as the warehouse, beers, wines and spirits, counters, bakery and driving.</p>

<h3>Right to work</h3>
<p>Tesco checks your right to work in the UK before you start, as every UK employer is legally required to do. Tesco publishes no statement either way about sponsoring visas for hourly-paid store roles, so do not assume a sponsored route exists.</p>

<p>Pay, hours, benefits and age rules are set by Tesco, Usdaw and UK employment law &mdash; not by JobGader. Confirm the rate and the availability window on the live advert for the store you are applying to.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most articles about supermarket work quote a salary estimate from a jobs board and leave it there. Tesco makes that unnecessary. It negotiates its hourly rate with a trade union, announces it in a press release, and prints it on the adverts themselves.</p>

<p>So the pay in this guide is Tesco's own. What almost nobody points out is the more interesting part: <strong>Tesco pays the same hourly rate to an 18-year-old as it does to a 40-year-old.</strong> UK law does not require that, and most employers do not do it.</p>

<p>There is a catch attached to it, and we will get to that.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-tesco-supermarket-jobs-in-uk-store.jpg" alt="Tesco colleagues in navy uniform restocking fresh produce and scanning items on a UK supermarket shop floor" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Tesco Colleague roles cover shop floor service, checkouts, replenishment and picking online orders. Tesco guarantees new store colleagues a minimum of 16 hours a week.</figcaption>
</figure>

<h2>Where Do I Actually Apply?</h2>

<p>This is the first thing the internet gets wrong about Tesco, so it is worth a paragraph.</p>

<p>Tesco is running two recruitment systems at once. If you search for "Tesco careers" you will probably land on careers.tesco.com, and its default results today are corporate vacancies in Hungary and Slovakia. It is a real Tesco site. It is just not where UK store jobs are listed.</p>

<p><strong>UK hourly-paid store vacancies are on apply.tesco-careers.com.</strong> That is the search this guide links.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://apply.tesco-careers.com/v2/job/search" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Search Tesco UK Store Jobs &rarr;</a>
</p>

<p>A note on links. We do not publish links to individual Tesco vacancies, and here is the evidence for why. The draft this guide was built from listed three specific Tesco job links, checked on 19 September 2026. <strong>All three returned 404 two days later.</strong> Tesco vacancies expire on the closing date printed on them, and often earlier when a store gets enough applicants. A search page does not expire. A job ID does.</p>

<h2>What Does Tesco Pay?</h2>

<p>From Tesco's own announcement of 18 March 2026:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Where</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Hourly rate</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">From</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Stores and online fulfilment centres</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;13.28</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">29 March 2026</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Inside the M25 (London Location Allowance of &pound;1.27)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;14.55</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">29 March 2026</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Isle of Man (location supplement)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;14.11</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Live adverts, September 2026</td>
        </tr>
    </tbody>
</table>

<p>Tesco called it a 5.1 per cent award, an investment of more than &pound;200 million, agreed with the shopworkers' union Usdaw. It also said hourly-paid pay has risen 43 per cent over five years.</p>

<p>One thing the rate does not cover: Tesco publishes <strong>no hourly rate at all for Customer Delivery Driver or Cafe roles</strong> on its live adverts. If you read a figure for those jobs somewhere, it came from a salary-estimate site, not from Tesco.</p>

<h2>Is &pound;13.28 Good? Here Is the Honest Benchmark</h2>

<p>The UK minimum wage from April 2026 is set by age:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Your age</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Legal minimum</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Tesco pays</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Difference</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">21 and over</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;12.71</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;13.28</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">+&pound;0.57</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">18 to 20</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;10.85</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;13.28</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">+&pound;2.43</td>
        </tr>
    </tbody>
</table>

<p><strong>This is the finding worth carrying away.</strong> An employer is allowed to pay an 18-year-old &pound;10.85. Tesco pays &pound;13.28. On a 16-hour contract that gap is worth roughly &pound;2,020 a year, and it is the strongest argument for applying to a large supermarket rather than a smaller shop that pays the legal floor.</p>

<p>And now the catch. <strong>Many Tesco store adverts require you to be over 18.</strong> Tesco recruits from school leaving age in principle, and states it can only accept candidates over 18 where the role involves working before 6:15am or after 9:45pm, or in areas such as the warehouse, beers, wines and spirits, counters, bakery and driving roles. Note the words "areas such as" &mdash; that list is examples, not a complete set. So the age group that gains most from Tesco's flat rate is also the group most likely to find the advert closed to them. Read the age line on each advert before you spend time on it.</p>

<h2>What Are the Hours Really Like?</h2>

<p>Tesco's store colleague adverts state a clear policy: a minimum of <strong>16 guaranteed hours</strong> a week, with roles from 12 hours available if you want fewer. That is a genuine guarantee, not a zero-hours arrangement.</p>

<p>Two qualifications. First, this is the store colleague policy. A live Cafe Team Member advert guarantees <strong>eight</strong> hours, so do not carry the 16 across to every Tesco job. Second, every advert prints an availability window &mdash; the days and times you must be free &mdash; and your contracted hours are scheduled inside it. You get at least three weeks' notice of exact shifts. Tesco adds that if your availability is close to what the advert asks for, it still encourages you to apply.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-tesco-supermarket-jobs-in-uk-checkout.jpg" alt="A Tesco colleague scanning items at the checkout while customers shop in the background of a UK store" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Every Tesco advert prints an availability window. Your contracted hours are scheduled inside it, with at least three weeks' notice of exact shifts.</figcaption>
</figure>

<h2>What Do You Get Besides the Hourly Rate?</h2>

<p>From Tesco's live store colleague adverts:</p>

<ul>
    <li><strong>Holiday</strong> starting at 20 days plus a personal day, rising to 22 days after 12 months, plus bank holidays.</li>
    <li><strong>Colleague Clubcard</strong> giving 10 per cent off, rising to 15 per cent on pay day weekends.</li>
    <li><strong>Pension</strong> with matching contributions up to 7.5 per cent.</li>
    <li><strong>Life cover</strong> worth five times your pay.</li>
    <li><strong>Flexible working</strong> that you can request from day one.</li>
    <li><strong>Free wellbeing services</strong>, plus a benefits range including discounts, shares and a cycle to work scheme.</li>
    <li><strong>Uniform provided.</strong></li>
</ul>

<p>One correction worth making, because it is the benefit people join for. <strong>The discount is not available on day one.</strong> Tesco's benefits page says permanent colleagues receive a Colleague Clubcard after four weeks, and live adverts place the second card you can share with family at 12 weeks of service. Budget for the wait.</p>

<h2>How Do I Apply, Step by Step?</h2>

<ol>
    <li><strong>Search on apply.tesco-careers.com</strong> by postcode or town, and pick a store you can realistically travel to for early or late shifts.</li>
    <li><strong>Read the availability window first.</strong> It is the single most common reason a good application goes nowhere. If you cannot cover the window, the rest does not matter.</li>
    <li><strong>Check the age line.</strong> Many adverts say you must be over 18 for that role.</li>
    <li><strong>Create a profile and apply.</strong> You can speed this up by applying with a CV or a LinkedIn or Indeed profile, and you can save an unfinished application.</li>
    <li><strong>Expect an online assessment.</strong> Tesco names it as a stage: some roles include a scenario-based exercise or an online chat assessment designed around situations you would meet in the job.</li>
    <li><strong>Book your interview.</strong> For store roles Tesco sends a link to book a time, from the store or its recruitment hub. Interviews last about 30 minutes.</li>
    <li><strong>Pass the employment checks,</strong> including a right to work check. Tesco says a store or distribution application can take up to five weeks to review.</li>
</ol>

<p>If you need an adjustment at any stage, ask. Tesco is an accredited Level 3 Disability Confident Leader and lists examples including changes to equipment, premises and furniture, and extra time for tasks.</p>

<h2>Can I Apply From Outside the UK?</h2>

<p>You need the right to work in the UK before Tesco can employ you, and Tesco checks it as part of the process. Every UK employer must, by law.</p>

<p>On sponsorship, we will say exactly what we can support and no more: <strong>Tesco publishes no statement, for or against, about sponsoring visas for hourly-paid store roles.</strong> Any page telling you Tesco sponsors supermarket jobs is not quoting Tesco. Treat "visa sponsorship supermarket UK" adverts with suspicion, and never pay anyone for one.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does Tesco pay per hour in 2026?</h3>
<p>&pound;13.28 an hour for store and online fulfilment colleagues from 29 March 2026, rising to &pound;14.55 inside the M25 with the London Location Allowance.</p>

<h3>Do I need experience to work at Tesco?</h3>
<p>No. Live store colleague adverts do not ask for previous retail experience. Tesco says experience from a previous job, volunteering, school or even a hobby all count.</p>

<h3>How many hours will I get?</h3>
<p>Store colleague roles carry a minimum of 16 guaranteed hours a week, with roles from 12 hours available. Cafe roles are advertised at eight guaranteed hours.</p>

<h3>Can 16 and 17 year olds work at Tesco?</h3>
<p>Tesco recruits from school leaving age, but many store adverts require you to be over 18, including roles working before 6:15am or after 9:45pm. Check the age line on the advert.</p>

<h3>Does Tesco pay more in London?</h3>
<p>Yes. The London Location Allowance of &pound;1.27 an hour takes the rate to &pound;14.55 for colleagues inside the M25.</p>

<h3>When does the Tesco staff discount start?</h3>
<p>Not on day one. Tesco's benefits page places the Colleague Clubcard at four weeks for permanent colleagues, and adverts place the second family card at 12 weeks of service.</p>

<h3>Does Tesco sponsor visas for store jobs?</h3>
<p>Tesco publishes no statement either way. You need the right to work in the UK before you can be employed, and it is checked before you start.</p>

<h3>How long does a Tesco application take?</h3>
<p>Tesco says an application for a role in its stores or distribution centres can take up to five weeks to review.</p>

<h2>People Also Search For</h2>

<h3>Tesco jobs near me</h3>
<p>Search apply.tesco-careers.com by postcode or town. Store vacancies are listed there rather than on the corporate careers site.</p>

<h3>Tesco Colleague pay 2026</h3>
<p>&pound;13.28 an hour from 29 March 2026, a 5.1 per cent rise agreed with Usdaw and worth more than &pound;200 million.</p>

<h3>Tesco London Location Allowance</h3>
<p>&pound;1.27 an hour on top of the base rate for stores inside the M25, giving &pound;14.55 an hour.</p>

<h3>Tesco Colleague Clubcard discount</h3>
<p>10 per cent off, rising to 15 per cent on pay day weekends. Permanent colleagues receive the card after four weeks.</p>

<h3>Tesco online assessment</h3>
<p>A scenario-based exercise or online chat assessment used for some roles, designed around situations you would meet in the job.</p>

<h3>Tesco availability window</h3>
<p>The days and times printed on each advert that you must be free to work. Contracted hours are scheduled inside it.</p>

<h3>One Stop and Booker jobs</h3>
<p>Both are part of the Tesco family and advertise their own vacancies. Booker terms differ from Tesco store terms, including a three-month wait for a Tesco Colleague Clubcard.</p>

<h3>UK National Living Wage 2026</h3>
<p>&pound;12.71 an hour for workers aged 21 and over, &pound;10.85 for 18 to 20 year olds, from April 2026.</p>

<h2>More Job Guides</h2>

<p>Comparing UK retail employers, or working out whether you can take the job at all? These cover the ground:</p>

<ul>
    <li><a href="/blog/store-assistant-jobs-in-uk">Store Assistant Jobs in UK</a> &mdash; how to spot a shop advertising below the legal minimum.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the visa routes that exist, and the ones that do not.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; including the licence rules Tesco applies to its own drivers.</li>
    <li><a href="/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa">How to Apply for Walmart Store Associate Jobs in the USA</a> &mdash; the same job, a different country and a very different floor.</li>
    <li><a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">How to Apply for Amazon Fulfillment Center Jobs in USA</a> &mdash; another large employer that publishes what it pays.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; why Abu Dhabi ground jobs are advertised by a company called Velora.</li>
    <li><a href="/blog/how-to-apply-for-neom-construction-jobs-in-saudi-arabia">How to Apply for NEOM Construction Jobs in Saudi Arabia</a> &mdash; the famous employer whose job board is currently empty.</li>
    <li><a href="/blog/how-to-apply-for-woolworths-supermarket-jobs-in-australia">How to Apply for Woolworths Supermarket Jobs in Australia</a> &mdash; the Australian equivalent, where the rate comes from an enterprise agreement instead.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Tesco's own pay announcement of 18 March 2026, live Tesco Colleague and Cafe adverts, Tesco's careers FAQs and benefits pages, and GOV.UK minimum wage and right to work guidance, checked on 21 September 2026. Tesco's rates, supplements and availability windows vary by store and change over time. Always check the live advert and the official government source before acting.</p>
HTML;
    }
}
