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
 * "How to Apply for Royal Mail Delivery Jobs in the UK" - an employer guide
 * rebuilt on Royal Mail's own careers pages, the CWU and Royal Mail Group pay
 * agreement, Ofcom's universal service statement and the Home Office eligible
 * occupation list, rather than the job-board figures the draft relied on.
 *
 * Corrections to the draft (checked against careers.royalmailgroup.com,
 * internationaldistributionservices.com, cwu.org, ofcom.org.uk and gov.uk,
 * September 2026):
 *
 * 1. The draft names the parent "International Distributions Services plc".
 *    Both halves are wrong. The name is International Distribution Services,
 *    singular, and it is no longer a plc: EP Group's takeover completed at the
 *    end of April 2025 and IDS was delisted from the London Stock Exchange on
 *    2 June 2025. royalmailgroup.com now 301-redirects to
 *    internationaldistributionservices.com.
 *
 * 2. The draft offers Indeed as an apply route. Aggregator links are against
 *    site policy. Worse, Royal Mail's own individual vacancy URLs expire fast
 *    - several checked for this guide returned HTTP 410 Gone within weeks - so
 *    the only durable link is the Delivery and Sorting category page.
 *
 * 3. The draft's "GBP 12.54 to 16.24 per hour" and its "GBP 13.68-16.24" role
 *    table are job-board spreads across different contracts and regions.
 *    Replaced with the published CWU figures: a legacy delivery OPG national
 *    rate of GBP 15.04 an hour after the year-one 4.2% rise, and CWU's own
 *    statement that a new entrant earns GBP 1.98 an hour less, which puts the
 *    new entrant rate at GBP 13.06 before the April 2026 uplifts.
 *
 * 4. The draft gives one penalty-point rule, "a maximum of 6". Royal Mail does
 *    not use one rule. Postperson with Driving and Parcelforce adverts say "no
 *    more than 6 penalty points"; the Drivers page for Class 1 LGV and Class 2
 *    / MGV says "less than 6 penalty points", which makes five the practical
 *    ceiling for those roles.
 *
 * 5. The draft says the licence must be "registered to your current name and
 *    address". Royal Mail's wording is registered to your current address.
 *
 * 6. The draft treats a manual licence as a blanket requirement. The manual
 *    wording appears on postperson and Parcelforce adverts; Royal Mail's LGV
 *    and MGV pages specify only the licence category and say nothing about
 *    transmission. The guide says the requirement is role-specific rather than
 *    guessing in either direction.
 *
 * 7. The draft says "22.5 days holiday rising with service". Royal Mail's own
 *    wording is "at least 22.5* days of annual leave ... increasing the longer
 *    you're with us", footnoted "Some roles offer a higher starting
 *    entitlement." It is a floor, not the entitlement.
 *
 * 8. The draft's role table gives "Postperson with Driving 28-37 hours/week".
 *    That is not a Royal Mail figure. What is agreed is that from 1 June 2026
 *    Royal Mail employs new full-time staff only on 37-hour contracts, down
 *    from 40, and part-time new entrants' average hours rise from 31 to 35.
 *
 * 9. The draft's "paid overtime at 1.25x" does appear on Royal Mail adverts,
 *    but it was tied to a 40-hour full-time week that the 2026 agreement
 *    replaces for new starters, so the trigger point has to be read off the
 *    live advert.
 *
 * 10. The draft says "no CV is needed for most delivery roles, you simply
 *     complete an online test and then interview with a manager". Royal Mail
 *     publishes six steps: apply online, online tests if applicable,
 *     interview, offer and checks, onboarding, and a driving assessment. It
 *     never says a CV is unnecessary, and the tests are conditional.
 *
 * 11. The draft says "mailbags of 10kg or more". Royal Mail's postperson with
 *     driving adverts say a mailbag weighing up to 16kg; its seasonal mail
 *     sorter page says bags up to 11kg and trolleys up to 250kg.
 *
 * 12. "Free stamps at Christmas" and the "online GP" appear nowhere on Royal
 *     Mail's own benefits listing, so both are dropped. The discount scheme is
 *     My Bundle+, not MyBundle+.
 *
 * 13. The draft ignores right to work entirely, which is the question that
 *     matters most to this site's readers. On the Home Office eligible
 *     occupation list in force since 22 July 2025, SOC 9211 postal workers and
 *     mail sorters, 8214 delivery drivers and couriers, 8211 heavy and large
 *     goods vehicle drivers and 7123 van roundspersons are all Ineligible. No
 *     employer can sponsor these jobs, and the general Skilled Worker
 *     threshold is GBP 41,700 a year in any case. The guide says so plainly.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RoyalMailDeliveryJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.royalmailgroup.com/gb/en/Delivery_and_sorting';

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
        $title = 'How to Apply for Royal Mail Delivery Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Royal Mail is no longer a plc and no longer British-owned, and it cannot sponsor a postperson visa because the occupation code is ineligible. Here is the real CWU pay, the licence rules role by role, and an apply link that will not expire.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk.jpg',
                'tags' => 'royal mail jobs, postperson with driving, royal mail delivery driver, royal mail careers, cwu pay 2026, delivery jobs uk, postman jobs uk, right to work uk',
                'meta_title' => 'Royal Mail Delivery Jobs UK: Pay and How to Apply',
                'meta_description' => 'Royal Mail delivery jobs: CWU agreement pay of GBP 15.04 an hour, the driving licence rules, and why a postperson job cannot be visa sponsored.',
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
            ['name' => 'Royal Mail Group, United Kingdom'],
            ['type' => 'Company', 'display_reference' => 'royal-mail-group-uk']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Postperson and Delivery Driver, Royal Mail UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shifts vary by delivery office; new full-time contracts are 37 hours a week from 1 June 2026',
                'language' => 'English',
                // Both figures come from the CWU and Royal Mail Group pay
                // agreement, not from a job board. CWU puts the national rate
                // for a legacy delivery OPG at GBP 15.04 an hour after the
                // year-one 4.2% rise, and says a new entrant in a delivery
                // office earns GBP 1.98 an hour less, which is GBP 13.06. A 3%
                // rise, or 4.75% for new entrants, applies from 1 April 2026,
                // and London rates sit above both.
                'salary_currency' => 'GBP',
                'salary_period' => 'Hourly',
                'salary_minimum' => 13.06,
                'salary_maximum' => 15.04,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Postperson, postal delivery driver, collections driver and mail sorter roles at Royal Mail delivery offices across the UK, on CWU agreement pay. Right to work in the UK required.',
                'seo_keywords' => 'royal mail jobs, postperson with driving, royal mail delivery driver, postman jobs uk, delivery jobs uk, royal mail careers apply',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Royal Mail hires postpeople, postal delivery drivers, collections drivers and mail sorters at delivery offices and mail centres across the United Kingdom, on shifts that run seven days a week.</p>

<h3>What the work involves</h3>
<p>Sorting mail and parcels for a round, loading a van, delivering letters and parcels on foot and by vehicle, collecting from post boxes and business customers, and returning undelivered items to the office.</p>

<h3>Common requirements</h3>
<ul>
    <li>The existing right to work in the United Kingdom &mdash; these occupation codes cannot be sponsored</li>
    <li>A full UK driving licence registered to your current address for driving roles, within the penalty point limit stated on the advert</li>
    <li>Comfortable carrying a mailbag weighing up to 16kg and walking around 20,000 steps a day</li>
    <li>Flexibility across early, late, night and weekend shifts in a 24/7 operation</li>
    <li>No delivery experience needed for entry-level postperson roles</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay and conditions for these roles are set by Royal Mail and the CWU agreement, and visa rules are set by the Home Office &mdash; not by JobGader. Apply directly on Royal Mail's own careers site and never pay anyone for a Royal Mail job or a shift.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on Royal Mail's own Delivery and Sorting careers page, choose the delivery office you would actually report to, and work through the six steps Royal Mail publishes: apply online, online tests if applicable, interview, offer and checks, onboarding, and a driving assessment for driving roles.</strong> There is no shortcut, and no agent who can skip a step for you.</p>

<p>Two answers before you spend an hour on this. <strong>A Royal Mail delivery job cannot be sponsored for a UK work visa</strong> &mdash; the Home Office lists these occupation codes as ineligible, so you need a right to work you already hold. And the hourly rates circulating for these roles are job-board spreads; the figures that can actually be sourced sit in the CWU and Royal Mail pay agreement, and those are the ones used below.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.royalmailgroup.com/gb/en/Delivery_and_sorting" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#e4002b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9993; Royal Mail Delivery Jobs &rarr;
    </a>
</div>

<h2>Who You Are Actually Applying To</h2>

<p>It is still Royal Mail on the van, but the company behind it changed hands and most guides have not caught up. The parent is <strong>International Distribution Services</strong> &mdash; singular "Distribution" &mdash; and it is <strong>no longer a plc</strong>. EP Group, the Czech group led by Daniel K&#345;et&#237;nsk&#253;, completed its takeover at the end of April 2025, and <strong>IDS was delisted from the London Stock Exchange on 2 June 2025</strong>. The old corporate address, royalmailgroup.com, now redirects to internationaldistributionservices.com. A guide still calling the owner "International Distributions Services plc" has two errors in four words.</p>

<p>The UK government cleared the <strong>&pound;3.6 billion</strong> deal in December 2024 only against binding undertakings: the Royal Mail name and brand are kept, the headquarters and tax residency stay in the UK, the <strong>universal service obligation</strong> is protected, employee benefits are maintained, and dividends are barred unless financial and service targets are met. The government also holds a golden share over the headquarters and tax residency commitments. So the brief's instinct on the undertakings was right, even if its company name was not.</p>

<p>The work itself is being redrawn too. On <strong>10 July 2025 Ofcom reformed the universal service</strong>, and from 28 July Royal Mail has been allowed to deliver Second Class letters on alternate weekdays, Monday to Friday, still within three working days. First Class remains six days a week. Ofcom estimated annual savings of <strong>&pound;250m to &pound;425m</strong>. For anyone applying, that is why rounds, route structures and vacancy patterns keep moving.</p>

<h2>The Delivery Roles Royal Mail Lists</h2>

<p>Royal Mail's Delivery and Sorting page names five: <strong>Postal Delivery Driver (Postie)</strong>, <strong>Parcel delivery postie</strong>, <strong>Collections driver</strong>, <strong>Mail sorter</strong> and <strong>Specialist Delivery Drivers</strong>. Its separate Drivers page adds <strong>Class 1 LGV (C+E)</strong> and <strong>Class 2 and MGV (Category C1)</strong> work.</p>

<p>On hours, Royal Mail's own wording is worth quoting rather than paraphrasing: "Shifts will vary depending on the role you do. Some roles require flexibility to work earlies, lates and nights shifts and because we're a 24/7 operation there may also be some weekend working required." Drivers are told to expect "a variety of shifts on a rotational rota, including night driving, weekends, and bank holidays", with the reassurance that drivers never have to stay overnight.</p>

<p>Seasonal work is a separate door, and often the easiest one. The Christmas pages list <strong>Mail Sorter, Parcel Delivery Driver, LGV &amp; MGV Driver and Customer Service Assistant</strong>, and note that <strong>Northern Ireland vacancies are handled by Angard Staffing</strong> rather than the main careers site. Angard describes itself as the dedicated recruitment partner for Royal Mail and part of Royal Mail Group, so it is not a third-party agency that should ever be charging you a penny.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk-round.jpg" alt="Postal worker delivering mail on a round" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What It Pays, and Where Each Number Comes From</h2>

<p>Royal Mail does put an hourly rate on its own vacancy adverts. The problem is that those adverts do not last: several Postperson with Driving pages checked while writing this had already returned "the job you are trying to apply for has been filled", or an outright HTTP 410. So the durable, citable source is the <strong>CWU and Royal Mail Group pay agreement</strong>, which is published and dated.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#e4002b;color:#fff;">
            <th style="padding:10px;text-align:left;">Figure</th>
            <th style="padding:10px;text-align:left;">What it actually is</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>&pound;15.04 an hour</strong></td><td style="padding:10px;">CWU's stated national hourly rate for a legacy delivery OPG after the year-one 4.2% rise</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>&pound;1.98 an hour less</strong></td><td style="padding:10px;">What a new entrant in a delivery office earns compared with someone on the old contract, per CWU</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>3% from 1 April 2026</strong></td><td style="padding:10px;">Backdated, not dependent on any ballot, and flowing through to London pay ranges, Scottish Distant Island payments, pensionable allowances, scheduled attendance and overtime rates</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>4.75% from 1 April 2026</strong></td><td style="padding:10px;">New entrants only: the same 3% plus a 1.75% equalisation step, with a formal review no later than January 2027</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>37 hours</strong></td><td style="padding:10px;">From 1 June 2026 Royal Mail employs new full-time staff only on 37-hour contracts, down from 40; part-time new entrants' average rises from 31 to 35 hours</td></tr>
    </tbody>
</table>
</div>

<p>Put the first two rows together and the honest band is roughly <strong>&pound;13.06 an hour for a new entrant</strong> &mdash; the &pound;15.04 national rate less the &pound;1.98 gap the union itself quotes &mdash; up to <strong>&pound;15.04</strong> on a legacy contract, before the April 2026 uplifts and before London weighting. That is arithmetic on two published union figures, and the working is shown so you can check it rather than trust it.</p>

<p>What this guide will not do is repeat the "&pound;12.54 to &pound;16.24" spread you will see elsewhere. Those numbers are scraped from expired adverts and user submissions across different contracts, regions and London weightings, and they tell you nothing about the office you are applying to. The number that matters is the one printed on the live advert in front of you.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk-sorting.jpg" alt="Mail and parcels being sorted at a Royal Mail delivery office" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Licence Rules Are Not One Rule</h2>

<p>Most guides flatten this into "a full manual UK licence with a maximum of 6 penalty points". Royal Mail does not use one rule, and the difference can cost you an application.</p>

<ul>
    <li><strong>Postperson with Driving.</strong> Royal Mail's adverts ask for a full UK manual driving licence (Category B, up to 3.5t) registered to your current address, with <strong>no more than 6 penalty points</strong>.</li>
    <li><strong>Parcelforce collection and delivery drivers.</strong> The frontline careers page states a "full UK manual driving licence (Cat C1) with no more than 6 penalty points".</li>
    <li><strong>Class 1 LGV.</strong> The Drivers page asks for a valid UK Large Goods Vehicle Class 1 licence (C+E) "with <strong>less than</strong> 6 penalty points" &mdash; less than six, so five is the practical ceiling, not six.</li>
    <li><strong>Class 2 and MGV.</strong> A valid UK Category C1 licence "registered to your current address, with less than 6 penalty points".</li>
</ul>

<p>Two details worth pinning down. Royal Mail says <strong>registered to your current address</strong>, not to your current name and address, so update your DVLA record before you apply rather than after a rejection. And transmission is role-specific: the manual wording appears on postperson and Parcelforce adverts, while the LGV and MGV pages specify only the licence category and say nothing about transmission. If you hold an automatic-only licence, that is a question for the specific advert, not something to assume in either direction.</p>

<h2>Can Royal Mail Sponsor a Visa? No, and Here Is the Proof</h2>

<p>This is the question most Royal Mail guides leave out, and for readers outside the UK it is the only one that decides anything.</p>

<p>Under the Skilled Worker rules in force since <strong>22 July 2025</strong>, the Home Office publishes every occupation code and marks it eligible or not. On that list:</p>

<ul>
    <li><strong>9211 Postal workers, mail sorters and messengers</strong> &mdash; Ineligible</li>
    <li><strong>8214 Delivery drivers and couriers</strong> &mdash; Ineligible</li>
    <li><strong>8211 Heavy and large goods vehicle drivers</strong> &mdash; Ineligible</li>
    <li><strong>7123 Van salespersons and roundspersons</strong> &mdash; Ineligible</li>
</ul>

<p>"Ineligible" means no employer can sponsor that job, however short-staffed it is. The skill threshold for the route rose to degree level in July 2025, with narrow time-limited exceptions on the Temporary Shortage List that do not cover postal or delivery work, and the general salary floor is <strong>&pound;41,700 a year</strong> or the going rate for the occupation, whichever is higher &mdash; which a delivery round would not reach in any event.</p>

<p>So Royal Mail delivery work is open to you if you already hold the right to work: British or Irish citizenship, settled or pre-settled status, indefinite leave to remain, a partner or family visa, a graduate visa, or a dependant visa that permits work. It is not a route into the UK. Anyone offering you a sponsored Royal Mail postperson job is running a scam. If you are looking from abroad, <a href="/blog/jobs-in-uk-for-foreigners">our guide to jobs in UK for foreigners</a> sets out the routes that genuinely exist.</p>

<h2>Holiday, Overtime and the Benefits Royal Mail Actually Lists</h2>

<p>Royal Mail's own benefits wording is <strong>"at least 22.5* days of annual leave to relax and recharge, increasing the longer you're with us"</strong>, and the asterisk is the part that gets dropped: "Some roles offer a higher starting entitlement. Please refer to the job advert for details." So 22.5 days is a floor, not the entitlement, and a guide quoting it as a flat figure has read half the sentence.</p>

<p>The rest of the list on Royal Mail's own pages: 24/7 wellbeing support, company sick pay, a pension scheme it describes as market-leading, paid parental leave, development through the Royal Mail Academy, and <strong>My Bundle+</strong> with more than 800 retail discounts. Claims about free stamps at Christmas or an online GP do not appear anywhere on those pages, so treat them as unconfirmed unless an advert says otherwise.</p>

<p>On overtime, Royal Mail adverts have carried enhanced pay at <strong>1.25x</strong> above the full-time week. Read that next to the contract change: from <strong>1 June 2026 new full-time staff are employed only on 37-hour contracts</strong>, down from 40. Where your overtime trigger sits therefore depends on which contract you are hired onto, and that is on the advert, not in any guide.</p>

<p>On the physical side, Royal Mail's postperson-with-driving adverts describe carrying <strong>a mailbag weighing up to 16kg</strong> and walking around 20,000 steps a day, while its seasonal mail sorter page describes lifting bags <strong>up to 11kg</strong> and pushing wheeled trolleys weighing <strong>up to 250kg</strong>. "Mailbags of 10kg or more" understates the delivery round and misdescribes the sorting job.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Start on the Delivery and Sorting page</strong> rather than a job board. Individual Royal Mail vacancy links die quickly; the category page does not.</li>
    <li><strong>Filter by delivery office, not just by town.</strong> Rounds are run from a specific office and that is where you would report every morning.</li>
    <li><strong>Read the rate and the hours on the advert itself.</strong> They differ by contract type, region and London weighting, and no guide can tell you yours.</li>
    <li><strong>Expect Royal Mail's six steps:</strong> apply online, online tests if applicable, interview, offer and checks, onboarding, and a driving assessment for driving roles.</li>
    <li><strong>Fix your DVLA address first</strong> for a driving role, and check your penalty points against the exact wording on that advert rather than the general rule.</li>
    <li><strong>Confirm your right to work before you apply,</strong> because there is no sponsorship available for these occupation codes.</li>
    <li><strong>Never pay for a Royal Mail job or a shift.</strong> Angard Staffing is part of Royal Mail Group and is paid by the employer; anyone asking you for money is not recruiting for Royal Mail.</li>
</ol>

<p>If Royal Mail turns you down, or there is nothing at your office, the same licence and the same right-to-work position carry straight across to the wider market covered in our <a href="/blog/delivery-driver-jobs-in-uk">delivery driver jobs in UK</a> guide.</p>

<h2>Frequently Asked Questions</h2>

<h3>How do I apply for a Royal Mail delivery job?</h3>
<p>Through Royal Mail's own Delivery and Sorting careers page, filtering by the delivery office you want. Royal Mail then runs six steps: apply online, online tests if applicable, interview, offer and checks, onboarding, and a driving assessment for driving roles.</p>

<h3>What does a Royal Mail postperson earn an hour?</h3>
<p>CWU puts the national rate for a legacy delivery OPG at &pound;15.04 an hour after the year-one 4.2% rise, and says a new entrant earns &pound;1.98 an hour less, which is about &pound;13.06. A 3% rise applies from 1 April 2026, or 4.75% for new entrants.</p>

<h3>Does Royal Mail sponsor work visas for delivery jobs?</h3>
<p>No. SOC 9211 postal workers and mail sorters, 8214 delivery drivers and couriers and 8211 large goods vehicle drivers are all marked Ineligible on the Home Office list, so no employer can sponsor them. You need an existing right to work in the UK.</p>

<h3>How many penalty points can you have to drive for Royal Mail?</h3>
<p>It depends on the role. Postperson with Driving and Parcelforce adverts say no more than 6 penalty points. The Class 1 LGV and Class 2 and MGV pages say less than 6, which means five or fewer.</p>

<h3>Do I need a manual driving licence?</h3>
<p>For Postperson with Driving and Parcelforce collection and delivery roles, Royal Mail's adverts specify a full UK manual licence registered to your current address. The LGV and MGV pages specify only the licence category, so check the individual advert.</p>

<h3>How much holiday do Royal Mail delivery staff get?</h3>
<p>Royal Mail says at least 22.5 days of annual leave, increasing the longer you are with the company, and footnotes that some roles start higher. It is a minimum rather than a fixed figure, so read the advert.</p>

<h3>Who owns Royal Mail now?</h3>
<p>EP Group, led by Daniel K&#345;et&#237;nsk&#253;, through International Distribution Services. The &pound;3.6 billion takeover completed at the end of April 2025 and IDS was delisted from the London Stock Exchange on 2 June 2025, with binding undertakings protecting the universal service.</p>

<h3>Is there an online test, and do I need a CV?</h3>
<p>Royal Mail lists online tests as a step that applies to some roles rather than all, and it does not say a CV is unnecessary. Assume you will need your work history to hand, plus licence details for driving roles.</p>

<h2>People Also Search For</h2>

<h3>Royal Mail jobs near me</h3>
<p>Filter the Delivery and Sorting page by location; vacancies are tied to a specific delivery office rather than a general area.</p>

<h3>Postperson with Driving</h3>
<p>The most advertised delivery role, needing a full UK manual licence registered to your current address with no more than 6 penalty points.</p>

<h3>Royal Mail Christmas jobs</h3>
<p>Seasonal Mail Sorter, Parcel Delivery Driver, LGV and MGV Driver and Customer Service Assistant roles, with Northern Ireland handled by Angard Staffing.</p>

<h3>CWU pay deal 2026</h3>
<p>3% from 1 April 2026 for existing staff, backdated, and 4.75% for new entrants including a 1.75% equalisation step.</p>

<h3>Royal Mail 37 hour contract</h3>
<p>From 1 June 2026 Royal Mail employs new full-time staff only on 37-hour contracts, replacing the previous 40-hour recruitment practice.</p>

<h3>Angard Staffing</h3>
<p>Royal Mail Group's own dedicated recruitment partner, covering delivery driver, mail sorter and customer service roles, and all Northern Ireland vacancies.</p>

<h3>Royal Mail second class alternate days</h3>
<p>Ofcom allowed it from 28 July 2025, still within three working days, with estimated savings of &pound;250m to &pound;425m a year.</p>

<h3>Skilled Worker visa salary threshold</h3>
<p>&pound;41,700 a year or the occupation's going rate, whichever is higher, under the rules in force since 22 July 2025.</p>

<h2>More Job Guides</h2>

<p>If Royal Mail is one option rather than the only one, these cover the rest of the UK market:</p>

<ul>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; the wider delivery market, and what the same licence is worth elsewhere.</li>
    <li><a href="/blog/how-to-get-a-warehouse-driver-job-in-uk">How to Get a Warehouse Driver Job in UK</a> &mdash; the indoor alternative when the weather stops being romantic.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; what sponsorship really covers in logistics, and what it does not.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the routes that exist once the ineligible occupation codes are ruled out.</li>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a> &mdash; an employer that does publish its hourly rate, at every age.</li>
    <li><a href="/blog/store-assistant-jobs-in-uk">Store Assistant Jobs in UK</a> &mdash; similar entry requirements, indoors and on fixed hours.</li>
    <li><a href="/blog/how-to-apply-for-barclays-customer-service-jobs-in-the-uk">How to Apply for Barclays Customer Service Jobs in the UK</a> &mdash; the desk-based step up from a frontline role.</li>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a> &mdash; where the same reliability gets you off your feet.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; the opposite end of the same job market.</li>
    <li><a href="/blog/how-to-apply-for-ups-package-handler-jobs-in-the-usa">How to Apply for UPS Package Handler Jobs in the USA</a> &mdash; the same kind of work under a union contract, on the other side of the Atlantic.</li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; the hourly rate M and S announces itself, and why no store role can be sponsored.</li>
    <li><a href="/blog/how-to-apply-for-network-rail-maintenance-jobs-in-the-uk">How to Apply for Network Rail Maintenance Jobs in the UK</a> &mdash; the medical and drug screening standard, and why the trackside code cannot be sponsored.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Royal Mail Group's own careers pages for roles, hiring steps, licence requirements, benefits and annual leave wording; the CWU and Royal Mail Group pay agreement and CWU letters to branches for pay figures and contract hours; International Distribution Services and UK government announcements for ownership and undertakings; Ofcom's July 2025 universal service statement; and the Home Office eligible occupation list and Skilled Worker guidance for the visa position. Royal Mail vacancy adverts expire quickly, and pay, hours and immigration rules change. Always check the live advert and the official guidance before you commit.</p>
HTML;
    }
}
