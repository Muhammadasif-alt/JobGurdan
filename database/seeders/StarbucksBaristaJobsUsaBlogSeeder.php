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
 * "How to Apply for Starbucks Barista Jobs in the USA" — an employer guide
 * built on what Starbucks publishes about itself, rather than the Indeed
 * listings the draft was assembled from.
 *
 * Corrections to the draft (checked against careers.starbucks.com,
 * starbucksbenefits.com, investor.starbucks.com and about.starbucks.com,
 * September 2026):
 *
 * 1. The draft tells readers to apply "via Starbucks Careers or Indeed", and
 *    almost every source behind it was an Indeed listing rather than a
 *    Starbucks page. Every aggregator is dropped; the guide links only the
 *    Starbucks coffeehouse careers page, which has no job ID in it and does
 *    not rotate.
 *
 * 2. The draft's "virtual job tryout" does not exist under that name.
 *    Starbucks' own hiring pages call it Applicant Insights, an online
 *    questionnaire of about 20 minutes with no time limit, not pass/fail.
 *
 * 3. The draft's "most applications take about 15 minutes" and "around two
 *    weeks from application to offer" are job-board folklore. Starbucks
 *    publishes no application time and no hiring timeline, so both are gone.
 *    It says only that a recruiter or coffeehouse leader will reach out to
 *    schedule next steps, so the draft's "interview with the assistant store
 *    manager up to an hour, possible second interview" is softened to that.
 *
 * 4. The draft lists medical, dental, vision, paid parental leave and family
 *    expansion reimbursement with no eligibility threshold, which is the most
 *    repeated wrong claim about Starbucks. All of those require benefits
 *    eligible status: on the US mainland that is 240 total hours over three
 *    full consecutive months to start, then 520 hours in each six-month
 *    measurement period to keep, which works out at an average of 20 hours a
 *    week. Sick time and the mental health support start on hire.
 *
 * 5. The draft's "flexible hours" hides the gap that matters. Starbucks asks
 *    barista candidates for a minimum availability of 18 hours a week, which
 *    is below the 20-hour average the benefits run on.
 *
 * 6. The draft says sick time accrues "by hours" without a rate. It is one
 *    hour of sick time for every 25 hours worked, usable as soon as it is
 *    accrued, with up to 520 hours carried year to year.
 *
 * 7. The draft treats paid parental leave as a single figure. It is up to 12
 *    weeks at 100% of average pay for benefits eligible parents by birth,
 *    foster placement or adoption, plus 6 further weeks for birth parents.
 *
 * 8. The draft's "signing bonuses at some locations paid after 90 days" has
 *    no Starbucks source at all and is dropped.
 *
 * 9. The draft's "401(k) with employer match and a discounted stock purchase
 *    program" understates the conditions. Future Roast matches 100% of the
 *    first 5% of eligible pay and is immediately vested, but needs age 18 and
 *    90 days of service. The Stock Investment Plan is a 5% discount, open
 *    after 90 days, with enrolment only in the first 15 days of March, June,
 *    September and December.
 *
 * 10. The draft calls Bean Stock an equity programme and stops there. It is
 *     an annual November RSU grant for store partners hired by 1 May with no
 *     break in service, and it vests half at one year and half at two.
 *
 * 11. The draft omits the Starbucks College Achievement Plan entirely, which
 *     is the largest benefit on offer: 100% upfront tuition for a first-time
 *     bachelor's degree through Arizona State University online, 180+
 *     programmes, open from day one to benefits eligible partners, with no
 *     obligation to stay after graduating.
 *
 * 12. No aggregator pay figure is republished. Starbucks itself states an
 *     average of more than $19 an hour for US hourly retail partners and
 *     values total pay and benefits at more than $30 an hour at 20 or more
 *     hours a week, and from July 2026 added a quarterly Best of Starbucks
 *     Reward of up to $300 a quarter, expanded tipping and weekly pay.
 *
 * 13. Two draft claims survived checking and are kept: retail hourly postings
 *     are refreshed every 90 days, which is on Starbucks' own FAQ, and the
 *     duty about recognising changes in partner morale, which is genuinely in
 *     the Starbucks barista job description.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class StarbucksBaristaJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.starbucks.com/discover-opportunities/coffeehouses/';

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
        $title = 'How to Apply for Starbucks Barista Jobs in the USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Starbucks states an average above $19 an hour for US hourly partners and values pay plus benefits above $30. But barista availability starts at 18 hours a week and benefits start at an average of 20, and that gap decides the free ASU degree.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-starbucks-barista-jobs-in-the-usa.jpg',
                'tags' => 'starbucks barista jobs, starbucks careers usa, starbucks partner benefits, starbucks college achievement plan, bean stock, barista jobs usa, starbucks hiring process, coffee shop jobs usa',
                'meta_title' => 'Starbucks Barista Jobs USA: Pay, Benefits and How to Apply',
                'meta_description' => 'Starbucks barista jobs in the USA: the pay Starbucks publishes itself, the 20-hour benefits line, the free ASU degree, and how the hiring really works.',
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
            ['name' => 'Starbucks Coffee Company, US Coffeehouses'],
            ['type' => 'Company', 'display_reference' => 'starbucks-us-coffeehouses']
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
                'position' => 'Barista, Starbucks US Stores',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Minimum availability of 18 hours a week, including early mornings, evenings and weekends',
                'language' => 'English',
                // Starbucks publishes a company-wide average of more than $19
                // an hour across all US hourly retail partners, not a barista
                // pay band, and store rates are set against each state and
                // city minimum wage. An average is neither a floor nor a
                // ceiling, so no band is recorded here and the published
                // figures are quoted with their context in the guide instead.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Barista roles in Starbucks company-operated coffeehouses across the United States, covering drink preparation, register work and store cleaning.',
                'seo_keywords' => 'starbucks barista jobs, starbucks careers, barista jobs usa, coffee shop jobs usa, starbucks hiring',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Starbucks hires baristas for its company-operated coffeehouses across the United States. No previous coffee experience is required, because the drink recipes, the equipment and the food safety routines are all taught on the job.</p>

<h3>What the work involves</h3>
<p>Handcrafting beverages to recipe card standards, taking orders and handling the register, keeping food and the bar area within safety and cleaning standards, and contributing to the store team &mdash; including noticing and reporting changes in partner morale and performance to the store manager, which is written into the role itself.</p>

<h3>Common requirements</h3>
<ul>
    <li>A minimum availability of 18 hours a week, including early mornings, evenings and weekends</li>
    <li>No prior coffee or barista experience, and no specific qualification</li>
    <li>The ability to keep pace and stay composed through peak trading periods</li>
    <li>The legal right to work in the United States, and the minimum working age for your state</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> hourly rates, shift patterns and benefits eligibility are set by Starbucks and by state and city wage law &mdash; not by JobGader. Apply directly on the Starbucks careers site, and never pay anyone for a Starbucks application.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on the Starbucks careers site, pick a coffeehouse near you, and expect an online questionnaire called Applicant Insights before anyone calls.</strong> No coffee experience is needed and Starbucks trains you from scratch. The real decision is how many hours you can commit to, because that single number controls everything worth having in the offer.</p>

<p>Two things worth knowing before you spend the evening on an application. <strong>Starbucks does publish pay figures about itself</strong>, which most employers do not, so you can check the numbers rather than trust a job board estimate. And the benefits everyone talks about &mdash; the healthcare, the stock, the free degree &mdash; are not automatic for part-time staff. There is a threshold, and the barista advert quietly sits just below it.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.starbucks.com/discover-opportunities/coffeehouses/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#00704a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9749; Starbucks Coffeehouse Jobs &rarr;
    </a>
</div>

<h2>What Starbucks Says It Pays</h2>

<p>Starbucks does not publish a barista pay band, and any single hourly figure you see quoted for a Starbucks barista is a job board's estimate built from user submissions. What Starbucks does publish, in its own press material and investor releases, is this:</p>

<ul>
    <li><strong>An average of more than $19 an hour</strong> for US hourly retail partners.</li>
    <li><strong>More than $30 an hour</strong> as the average value of total pay <em>and</em> benefits, which Starbucks says is available from 20 hours a week.</li>
</ul>

<p>Read that second figure carefully, because it is doing a lot of work. The roughly $11 an hour difference between the two numbers is not cash. It is Starbucks' own valuation of healthcare, stock, tuition and leave &mdash; and it only counts if you clear the hours threshold in the next section.</p>

<h3>What changed in July 2026</h3>

<p>Starbucks added three things for hourly coffeehouse partners from July 2026: a quarterly <strong>Best of Starbucks Reward</strong> worth <strong>up to $300 a quarter, or $1,200 a year</strong>, expanded tipping across more ordering and payment channels, and <strong>weekly pay</strong> for all US partners. Starbucks says the combination is worth roughly <strong>5 to 8% more on average</strong> on top of current earnings.</p>

<p>An average national figure is also not what you will be offered. Starbucks rates are set store by store against state and city minimum wage law, so the same job in Seattle and in a low-wage state are not the same job financially. Compare it against what the wider market pays before you decide &mdash; our <a href="/blog/retail-jobs-in-usa">retail jobs in USA</a> and <a href="/blog/cashier-jobs-in-usa">cashier jobs in USA</a> guides cover the going rates for equivalent work.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-starbucks-barista-jobs-in-the-usa-counter.jpg" alt="Service counter in a coffee shop with cups and equipment ready for customers" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The 20-Hour Line That Decides Everything</h2>

<p>This is the part most guides get wrong. "Starbucks gives part-timers full benefits" is repeated everywhere, and it is only half true. Starbucks benefits require <strong>benefits eligible</strong> status, and Starbucks sets that out precisely on its own partner benefits site:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#00704a;color:#fff;">
            <th style="padding:10px;text-align:left;">Stage</th>
            <th style="padding:10px;text-align:left;">What Starbucks requires (US mainland)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Becoming eligible</td><td style="padding:10px;"><strong>240 total hours over three full, consecutive months</strong>. Coverage starts the first day of the second month after that.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Staying eligible</td><td style="padding:10px;"><strong>520 total hours</strong> on paychecks in each six-month measurement period, audited twice a year</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">In plain terms</td><td style="padding:10px;">An average of <strong>20 hours a week</strong>, sustained</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Hawaii</td><td style="padding:10px;">A different rule: 80 hours in a consecutive four-week period to start</td></tr>
    </tbody>
</table>
</div>

<p>Now the detail that makes this matter. <strong>Starbucks asks barista candidates for a minimum availability of 18 hours a week.</strong> Eighteen is what gets you hired. Twenty is what gets you covered. A barista scheduled at the advertised minimum, every week, never becomes benefits eligible at all.</p>

<p>What needs benefits eligible status: medical, dental and vision, short and long term disability, life insurance and AD&amp;D, spending accounts, paid parental leave, and family expansion reimbursement assistance. What does not: <strong>sick time and the mental health support start from hire</strong>, with no waiting period.</p>

<p>So the single most useful question at interview is not about pay. It is "realistically, how many hours a week will I be scheduled?" Ask it, and ask it plainly.</p>

<h2>The Free Degree Almost Nobody Mentions</h2>

<p>The biggest thing Starbucks offers is not on most lists of Starbucks benefits, which is strange, because it is worth more than everything else combined. The <strong>Starbucks College Achievement Plan</strong> gives benefits eligible US partners <strong>100% upfront tuition coverage</strong> for a <strong>first-time bachelor's degree</strong> through <strong>Arizona State University online</strong>, across <strong>180+ undergraduate programmes</strong>.</p>

<p>The terms are unusually generous, and these are Starbucks' own words: you can <strong>apply on day one</strong>, and there are <strong>no strings attached after you graduate</strong> &mdash; no repayment, no obligation to stay. Partners who do not initially meet ASU's admission requirements can work towards it through <strong>Pathway to Admission</strong>, with credit conversion costs covered. Starbucks says nearly 90% of its US stores have at least one partner enrolled.</p>

<p>"Upfront" is the word doing the work there. This is not a reimbursement you have to fund first and claim back, which is how most retail tuition schemes are built. But it runs on the same 20-hour line as everything else, which is the real reason to care about your schedule.</p>

<h2>Stock, Retirement and the Rest of the Offer</h2>

<ul>
    <li><strong>Bean Stock.</strong> An annual restricted stock unit grant made in November to store partners hired by 1 May with no break in service before the grant date. It vests in halves: the first after one year from grant, the second after two. It is not an instant share handout.</li>
    <li><strong>Future Roast 401(k).</strong> Starbucks matches <strong>100% of the first 5%</strong> of eligible pay you contribute each pay period, immediately vested. You need to be <strong>18 or over with 90 days of service</strong>.</li>
    <li><strong>Stock Investment Plan (SIP).</strong> Buy Starbucks stock at a <strong>5% discount</strong> through payroll, contributing 1% to 10% of base pay, after 90 days of service. Enrolment and changes only happen in the <strong>first 15 days of March, June, September and December</strong>, so missing a window costs you a quarter.</li>
    <li><strong>Sick time.</strong> <strong>One hour for every 25 hours worked</strong>, usable as soon as it accrues, with up to 520 hours carried from year to year.</li>
    <li><strong>Paid parental leave.</strong> Up to <strong>12 weeks at 100% of average pay</strong> for benefits eligible parents by birth, foster placement or adoption, plus <strong>6 further weeks for birth parents</strong> immediately after the birth.</li>
    <li><strong>Everyday perks.</strong> 30% off drinks, food and merchandise, a free pound of coffee or box of tea each week, and Spotify Premium.</li>
</ul>

<p>Claims you may have read that do not come from Starbucks: signing bonuses paid out at 90 days, and a fixed two-week hiring timeline. Neither appears anywhere in Starbucks' own material, and both trace back to job board listings rather than the company.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-starbucks-barista-jobs-in-the-usa-barista.jpg" alt="Barista preparing a coffee drink behind the bar during a shift" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>How the Application Actually Works</h2>

<ol>
    <li><strong>Search the Career Hub</strong> on the Starbucks careers site and filter to coffeehouse roles near you. Starbucks says it is always accepting barista applications.</li>
    <li><strong>Apply and upload a current resume.</strong> You get an email confirmation, and you can track the application status in the Career Hub.</li>
    <li><strong>Complete Applicant Insights.</strong> This is the step the job boards mislabel as a "virtual job tryout". Starbucks describes it as an online questionnaire taking <strong>about 20 minutes</strong>, with <strong>no time limit</strong>, <strong>no right or wrong answers</strong> and <strong>no pass or fail</strong>. Your answers carry over to other applications for the same job for <strong>90 days</strong>.</li>
    <li><strong>Wait for contact.</strong> Starbucks says a recruiter or coffeehouse leader will reach out to schedule next steps. It does not publish an interview format, a length, or a number of rounds, so treat any guide quoting "45 minutes with the assistant store manager" as guesswork.</li>
    <li><strong>Prepare the way Starbucks tells you to.</strong> Its own advice is to read the Starbucks mission and values, prepare for <strong>behavioural interviewing</strong> with specific examples, and visit a store first to watch how it runs.</li>
    <li><strong>Reapply if nothing happens.</strong> Starbucks refreshes retail hourly postings <strong>every 90 days</strong>, so a stale application is normal rather than a rejection.</li>
</ol>

<p>On age: Starbucks' own customer service answer sets the minimum at <strong>16 in most states</strong>, with state child labour law setting the exceptions. Individual postings can require 18 where the location or the hours demand it, so read the advert rather than assuming.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need barista or coffee experience to work at Starbucks?</h3>
<p>No. Starbucks trains baristas on the drinks, the equipment and the food safety standards on the job, and sets no qualification requirement for the role.</p>

<h3>How many hours a week do I need for Starbucks benefits?</h3>
<p>An average of 20. On the US mainland you become benefits eligible after 240 total hours over three full consecutive months, and stay eligible with 520 hours in each six-month measurement period.</p>

<h3>What does Starbucks pay baristas per hour?</h3>
<p>Starbucks publishes no barista pay band. It states an average of more than $19 an hour across US hourly retail partners, and values total pay plus benefits at more than $30 an hour at 20 or more hours a week.</p>

<h3>Is the Starbucks free degree real?</h3>
<p>Yes. The Starbucks College Achievement Plan covers 100% of tuition upfront for a first-time bachelor's degree through Arizona State University online, across 180+ programmes, with no obligation to stay after graduating.</p>

<h3>What is Applicant Insights?</h3>
<p>An online questionnaire of about 20 minutes with no time limit, no right or wrong answers and no pass or fail. Your responses carry over to applications for the same job for 90 days.</p>

<h3>How old do you have to be to work at Starbucks?</h3>
<p>Starbucks' own customer service answer sets the minimum at 16 in most states, with state child labour law creating the exceptions. Some individual postings require 18.</p>

<h3>How long does Starbucks take to hire?</h3>
<p>Starbucks publishes no hiring timeline. It says only that a recruiter or coffeehouse leader will contact you to schedule next steps, and that retail hourly postings refresh every 90 days.</p>

<h3>What is Bean Stock and when do I get it?</h3>
<p>An annual November restricted stock unit grant for store partners hired by 1 May with no break in service. Half vests one year after the grant date and half after two years.</p>

<h2>People Also Search For</h2>

<h3>Starbucks partner benefits eligibility hours</h3>
<p>240 total hours over three full consecutive months to become eligible, then 520 hours per six-month measurement period, an average of 20 hours a week.</p>

<h3>Starbucks average hourly pay</h3>
<p>More than $19 an hour for US hourly retail partners, per Starbucks, with total pay and benefits valued above $30 an hour.</p>

<h3>Starbucks College Achievement Plan ASU</h3>
<p>100% upfront tuition for a first-time bachelor's degree online at Arizona State University, 180+ programmes, available from day one to benefits eligible partners.</p>

<h3>Starbucks 401k match</h3>
<p>Future Roast matches 100% of the first 5% of eligible pay contributed each pay period, immediately vested, from age 18 with 90 days of service.</p>

<h3>Starbucks Stock Investment Plan discount</h3>
<p>A 5% discount on Starbucks stock, contributing 1% to 10% of base pay, with enrolment only in the first 15 days of March, June, September and December.</p>

<h3>Starbucks barista minimum availability</h3>
<p>Starbucks asks barista candidates for a minimum availability of 18 hours a week, which sits below the 20-hour average the benefits run on.</p>

<h3>Starbucks quarterly bonus 2026</h3>
<p>The Best of Starbucks Reward, up to $300 a quarter or $1,200 a year for hourly coffeehouse partners, introduced alongside weekly pay from July 2026.</p>

<h3>Starbucks sick time accrual</h3>
<p>One hour of sick time for every 25 hours worked, usable as soon as it accrues, with up to 520 hours carried year to year.</p>

<h2>More Job Guides</h2>

<p>Weighing Starbucks against the other big US hourly employers? These cover the same ground:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa">How to Apply for Walmart Store Associate Jobs in the USA</a> &mdash; a published hourly range, and the age rule most guides get wrong.</li>
    <li><a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">How to Apply for Amazon Fulfillment Center Jobs in USA</a> &mdash; warehouse pay, shift patterns and the tuition scheme.</li>
    <li><a href="/blog/how-to-apply-for-ups-package-handler-jobs-in-the-usa">How to Apply for UPS Package Handler Jobs in the USA</a> &mdash; part-time hours with union terms behind them.</li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a> &mdash; what the same skills earn across the sector.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; register work on its own, and what it pays.</li>
    <li><a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a> &mdash; where counter experience transfers next.</li>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; the wider market and which employers publish their pay.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; the hospitality route and its visa realities.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; an honest look at what is actually open without a qualification.</li>
    <li><a href="/blog/how-to-get-an-entry-level-office-job-with-no-experience">How to Get an Entry Level Office Job With No Experience</a> &mdash; the step off the shop floor.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the Starbucks careers site, the Starbucks partner benefits site, Starbucks press releases and Starbucks investor relations material. Starbucks publishes no pay band for the barista role, and hourly rates are set store by store against state and city wage law. Benefits rules, eligibility thresholds and reward programmes change. Always check the live posting and Starbucks' own benefits pages before you commit.</p>
HTML;
    }
}
