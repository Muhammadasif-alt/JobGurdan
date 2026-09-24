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
 * "How to Apply for Marks and Spencer Retail Jobs in the UK" - an employer
 * guide rebuilt on M&S's own careers pages and corporate newsroom, the Home
 * Office eligible occupation list and the statutory minimum wage rates, rather
 * than on the third-party careers-advice site the draft cited.
 *
 * The draft's own stated source for pay and for the hiring process was
 * "ResumeGeni", which is not M&S. Every claim below was re-checked against
 * jobs.marksandspencer.com, corporate.marksandspencer.com and gov.uk in
 * September 2026, and the corrections are listed in full.
 *
 * 1. The draft offers Indeed as an apply route. Aggregator links are against
 *    site policy. It is also the wrong advice in practice: M&S individual
 *    vacancy URLs expire fast, and two live-looking Customer Assistant vacancy
 *    URLs checked while writing this both returned HTTP 404. The only durable
 *    link is the In Store team page on M&S's own careers site.
 *
 * 2. The draft's "typically pay around GBP 13.41 per hour" is the right number
 *    presented as a rumour. M&S publishes it itself: a 6.4% increase taking the
 *    minimum hourly rate to GBP 13.41 nationally and GBP 14.74 in London from
 *    1 April 2026, for around 55,000 retail colleagues, worth about GBP 132 a
 *    month or GBP 1,587 a year, with the total package valued at up to
 *    GBP 16.33 an hour. The draft omitted the London rate, the effective date,
 *    the percentage and the source.
 *
 * 3. The draft's "GBP 3.00 per hour unsocial hours premium where applicable"
 *    is real but uselessly vague. M&S's own adverts attach the GBP 3.00 to
 *    hours worked between 10pm and 6am. "Where applicable" is replaced with
 *    the actual window.
 *
 * 4. The draft's role table is a mix of advert variants, not M&S's published
 *    structure. The In Store page names three families: Customer Assistants,
 *    Retail Management and Visual Merchandising. Visual Merchandising is
 *    missing from the draft entirely.
 *
 * 5. "Retail Assistant via catering/facilities partners like Compass Group" is
 *    misleading enough to cost a reader a job. Compass runs franchised M&S
 *    Simply Food outlets in hospitals and similar sites and advertises M&S Cafe
 *    roles of its own. Those are Compass jobs: Compass is the employer, and M&S
 *    pay rates and the colleague discount do not apply. M&S's own seasonal
 *    announcement lists "M&S Cafe assistants" as M&S roles.
 *
 * 6. "M&S is the UK's fastest growing retailer" is not what M&S claims. Its own
 *    wording is "M&S is the fastest growing major retailer YoY within FMCG
 *    amongst families", sourced to the Worldpanel by Numerator FMCG panel for
 *    the 52 weeks ending 28 December 2025, published 14 January 2026. The
 *    unqualified version is dropped.
 *
 * 7. The draft's "18 or older for roles involving specialist equipment, late
 *    nights, or mechanical equipment" invents a list. M&S's advert wording ties
 *    the age rule to health and safety legislation and to shift timing - early
 *    mornings and overnight working - not to an equipment category.
 *
 * 8. The draft says the 20% discount is "available from your first day for
 *    seasonal roles or after probation for others". M&S publishes no first-day
 *    seasonal exception. Its wording is "After completing your probation
 *    period, you'll receive 20% colleague discount across all M&S products and
 *    many of our third-party brands for you and a member of your household."
 *
 * 9. The draft lists life assurance without a figure, and third-party sites put
 *    it at four times salary. M&S's own wording is "Get cover for twice your
 *    salary up to age 70 (double with our Pension Plan)."
 *
 * 10. The draft's "roughly 45-minute Oracle form followed by a face-to-face
 *     interview" is half right, and the half that is right is not the half the
 *     draft thought. The 45 minutes is M&S's own published figure - "Allow
 *     yourself around 45 minutes to complete the application" - so it is not
 *     the third-party site's invention. "Oracle" is not M&S's word anywhere on
 *     its careers site and is dropped. And it is not form-then-interview: M&S
 *     publishes five stages including a separate online assessment, which for
 *     Customer Assistant roles it describes as "an interactive assessment built
 *     around real-life store scenarios" taking "around 20 minutes, and you'll
 *     need to complete it in one go", before a one-to-one interview or group
 *     assessment held in store.
 *
 * 11. "Christmas hiring brings hundreds of temporary roles per city that often
 *     convert to permanent" has no M&S source. M&S's figures are national:
 *     20,000 seasonal jobs across UK and Republic of Ireland stores in over 500
 *     stores, 5,000 more than the year when "more than 1 in 5 of the 15,000
 *     recruits were made permanent employees"; a later drive was "over 11,000
 *     new Customer Assistants". Just over one in five is not "often".
 *
 * 12. The AI policy claim is real, and is the one draft claim that survives
 *     intact. M&S publishes "Using AI in your job application": "Use AI to
 *     improve, not to define, your job application for us", with no copied
 *     generic answers, no made-up examples, no live help during interviews or
 *     assessments, and a warning not to treat AI as the sole source of research
 *     about the business. Quoted from M&S rather than from the third party.
 *
 * 13. The draft ignores right to work and sponsorship entirely, which is the
 *     question that decides everything for this site's readers. M&S's own In
 *     Store page requires "Your right to work documentation". On the Home
 *     Office eligible occupation list updated 22 July 2025, SOC 7111 sales and
 *     retail assistants is Ineligible, so no employer can sponsor a Customer
 *     Assistant. M&S holding a sponsor licence does not change that:
 *     eligibility is per occupation code. The general threshold is
 *     GBP 41,700 a year or the going rate, whichever is higher, at RQF level 6.
 *
 * 14. The draft's Sparks App and in-store devices detail is advert copy
 *     circulated by third parties; M&S's own careers pages do not describe the
 *     tools. Kept only as a general statement with the caveat attached.
 *
 * 15. Statutory context the draft omits: the National Living Wage for workers
 *     aged 21 and over is GBP 12.71 an hour from 1 April 2026, so the M&S rate
 *     sits GBP 0.70 above the legal floor. That is the only other number on
 *     this page that is not M&S's own.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MarksAndSpencerRetailJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://jobs.marksandspencer.com/our-teams/in-store';

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
        $title = 'How to Apply for Marks and Spencer Retail Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'M and S publishes its own rate: GBP 13.41 an hour nationally and GBP 14.74 in London from 1 April 2026, plus a GBP 3.00 unsocial hours premium. Here is the real application process, the discount rule, and why the job cannot be sponsored.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk.jpg',
                'tags' => 'marks and spencer jobs, m and s customer assistant, marks and spencer careers, retail jobs uk, m and s pay 2026, store assistant jobs uk, customer assistant jobs, right to work uk',
                'meta_title' => 'M and S Customer Assistant Jobs UK: Pay and Apply',
                'meta_description' => 'M and S pays customer assistants GBP 13.41 an hour from April 2026, GBP 14.74 in London. Apply steps, benefits, and why retail jobs cannot be sponsored.',
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
            ['name' => 'Marks and Spencer, United Kingdom'],
            ['type' => 'Company', 'display_reference' => 'marks-and-spencer-uk']
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
                'position' => 'Customer Assistant, Marks and Spencer UK Stores',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift patterns are stated at the top of each advert; early morning and overnight shifts are restricted to applicants aged 18 and over',
                'language' => 'English',
                // Both figures are Marks and Spencer's own published rates, from
                // its corporate announcement of a 6.4% increase effective
                // 1 April 2026: GBP 13.41 an hour as the national minimum rate
                // for customer assistants and GBP 14.74 an hour in London. A
                // GBP 3.00 an hour premium applies to hours worked between 10pm
                // and 6am on M&S's own adverts, and is described in the guide
                // rather than folded into these figures.
                'salary_currency' => 'GBP',
                'salary_period' => 'Hourly',
                'salary_minimum' => 13.41,
                'salary_maximum' => 14.74,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Customer Assistant roles in Marks and Spencer food, clothing and home stores across the UK, on the published M&S hourly rate. Right to work in the UK required.',
                'seo_keywords' => 'marks and spencer jobs, m and s customer assistant, marks and spencer careers, retail jobs uk, store assistant jobs uk, m and s apply online',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Marks and Spencer hires Customer Assistants across its food halls, Simply Food stores and clothing, home and beauty floors throughout the United Kingdom, on shift patterns that run from early mornings through to overnight replenishment.</p>

<h3>What the work involves</h3>
<p>Serving customers on the shop floor and at the tills, replenishing and rotating stock, keeping counters and displays to standard, handling deliveries, and working the digital tools and till systems the store uses.</p>

<h3>Common requirements</h3>
<ul>
    <li>The existing right to work in the United Kingdom &mdash; M&amp;S asks for your right to work documentation at the application stage, and this occupation code cannot be sponsored</li>
    <li>Your National Insurance number to hand when you apply</li>
    <li>Availability across the shift pattern printed at the top of the advert, including weekends</li>
    <li>Age 18 or over for early morning and overnight shifts, under health and safety rules</li>
    <li>No retail experience needed for entry-level Customer Assistant roles</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, premiums, benefits and age rules are set and published by Marks and Spencer, and visa eligibility is set by the UK Home Office &mdash; not by JobGader. Sales and retail assistant occupation codes are currently ineligible for the Skilled Worker visa. Apply directly on the M&amp;S careers site, and never pay anyone for an M&amp;S job or a shift.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on the M&amp;S careers site's own In Store page, allow around 45 minutes for the application, and expect an online assessment of about 20 minutes before an interview held inside the store you applied to.</strong> M&amp;S asks for your National Insurance number and your right to work documentation up front, so have both open before you start.</p>

<p>Two answers before you spend that 45 minutes. <strong>An M&amp;S Customer Assistant job cannot be sponsored for a UK work visa</strong> &mdash; the Home Office marks the occupation code ineligible, and M&amp;S holding a sponsor licence does not change that, because eligibility is decided per occupation, not per employer. And the pay figure circulating for these roles is correct but usually undated: M&amp;S publishes it itself, and the current rate has a start date and a London variant that most guides leave out.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://jobs.marksandspencer.com/our-teams/in-store" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#00543c;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128722; M&amp;S In Store Jobs &rarr;
    </a>
</div>

<h2>What M&amp;S Pays, From M&amp;S's Own Announcement</h2>

<p>This is the part most M&amp;S guides take from a job board or a careers-advice blog. There is no need. M&amp;S announces its shop-floor hourly rate in its own newsroom, with a percentage, a date and a headcount attached, and those are the figures used here.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#00543c;color:#fff;">
            <th style="padding:10px;text-align:left;">Figure</th>
            <th style="padding:10px;text-align:left;">What it actually is</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>&pound;13.41 an hour</strong></td><td style="padding:10px;">The M&amp;S minimum hourly rate for customer assistants nationally, from 1 April 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>&pound;14.74 an hour</strong></td><td style="padding:10px;">The same role in London, from the same date</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>6.4%</strong></td><td style="padding:10px;">The size of the increase, covering around 55,000 UK retail colleagues, which M&amp;S puts at about &pound;132 a month or &pound;1,587 a year</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>&pound;3.00 an hour extra</strong></td><td style="padding:10px;">The unsocial hours premium on M&amp;S adverts, for hours worked between 10pm and 6am &mdash; not a blanket top-up</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Up to &pound;16.33 an hour</strong></td><td style="padding:10px;">M&amp;S's own valuation of base pay plus benefits combined, which is a package figure and not what lands in your bank</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>&pound;12.71 an hour</strong></td><td style="padding:10px;">The statutory National Living Wage for workers aged 21 and over from 1 April 2026, for comparison &mdash; M&amp;S sits 70p above the legal floor</td></tr>
    </tbody>
</table>
</div>

<p>Two things follow. First, if you work nights the premium is the difference between an ordinary retail wage and a good one, but it only attaches to the hours inside that 10pm to 6am window &mdash; a shift that ends at 9pm earns none of it. Second, the "up to &pound;16.33" figure is M&amp;S valuing pension, discount and the rest alongside the wage. That is a fair way to describe a package and a misleading way to describe an hourly rate, so read it as the former.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk-store.jpg" alt="Retail store floor with stocked shelves and shoppers" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The In-Store Roles M&amp;S Actually Lists</h2>

<p>M&amp;S's own In Store page names three families of role, not the five-row table you will see repeated elsewhere:</p>

<ul>
    <li><strong>Customer Assistants</strong> &mdash; described by M&amp;S as being "the face of M&amp;S and every customer's first port of call". In practice the adverts split by department, so you will see food, and clothing, home and beauty variants, plus operations, service and safety, and cafe roles.</li>
    <li><strong>Retail Management</strong> &mdash; the roles that, in M&amp;S's wording, "run our teams and stores". This is where team manager and store leadership vacancies sit.</li>
    <li><strong>Visual Merchandising</strong> &mdash; the specialists who make sure "customers have a seamless experience in-store". Guides that list only assistant and manager roles miss this one entirely, and it is a genuine route in for anyone with a display or styling background.</li>
</ul>

<p>One warning that matters more than it looks. You will find "M&amp;S" roles advertised by <strong>Compass Group</strong>, including M&amp;S Cafe and M&amp;S Simply Food positions at hospitals and similar sites run under franchise. Those are real jobs, but <strong>Compass is the employer, not M&amp;S</strong>. The M&amp;S hourly rate, the colleague discount and the M&amp;S benefits package in this guide do not apply to them. Check the name at the top of the contract, not the name above the door.</p>

<h2>The Application: 45 Minutes, and What Is Actually In It</h2>

<p>M&amp;S tells you the length itself: "Allow yourself around 45 minutes to complete the application." That figure is M&amp;S's own, not a guess, and it is worth taking literally &mdash; the form asks for full personal details including your National Insurance number and your right to work documentation.</p>

<p>The stages M&amp;S publishes are these:</p>

<ol>
    <li><strong>Find the role</strong> on the M&amp;S careers site, filtered by store.</li>
    <li><strong>Make your application.</strong> Create an account, answer the questions, and upload a CV and cover letter where the role asks for them.</li>
    <li><strong>Online assessment.</strong> M&amp;S says "for some roles, we may ask you to complete an online assessment". For Customer Assistant roles it describes "an interactive assessment built around real-life store scenarios", taking "around 20 minutes", and warns "you'll need to complete it in one go". Do not start it on a phone with 4% battery.</li>
    <li><strong>Interview or group assessment.</strong> You are invited to "either a one-to-one interview or a group assessment", and M&amp;S says interviews take place in store. You book the slot yourself from the availability you give.</li>
    <li><strong>Offer.</strong> Communicated by email, sometimes with a call.</li>
</ol>

<p>So the shorthand "a 45-minute form and then an interview" understates it by one whole stage. The 20-minute scenario assessment is a separate sitting, it is the step most people fail, and it cannot be paused.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk-checkout.jpg" alt="Checkout and till area in a retail store" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What M&amp;S Says About Using AI in Your Application</h2>

<p>M&amp;S has published its own page on this, which very few UK retailers have, and it is worth reading before you let a chatbot near your application. Its summary line is <strong>"Use AI to improve, not to define, your job application for us."</strong></p>

<p>M&amp;S says AI "can be a brilliant way to help you prepare, organise and refine your application" &mdash; organising your ideas, tidying grammar and flow, practising interview questions &mdash; provided the final version is genuinely yours. What it rules out is specific:</p>

<ul>
    <li><strong>"No copying and pasting generic answers from AI into your application."</strong></li>
    <li><strong>"No made-up examples"</strong> &mdash; M&amp;S says real-life stories are what let it understand your strengths.</li>
    <li><strong>"No live help during interviews or assessments"</strong>, which M&amp;S calls "your moment to shine".</li>
    <li>Do not rely on AI as your only research about the business, because the tools "are often inaccurate or out of date". M&amp;S's suggestion is to visit the stores, the website and the social channels.</li>
</ul>

<p>That last point is the practical one. The 20-minute assessment is built on store scenarios, and the interview happens on the shop floor. Spending twenty minutes inside the store you are applying to will do more for you than any amount of prompting.</p>

<h2>Can M&amp;S Sponsor a Visa? Not for a Customer Assistant</h2>

<p>This is the question the third-party guides skip, and for anyone reading from outside the UK it is the only one that decides anything.</p>

<p>M&amp;S does hold a sponsor licence. That fact, on its own, tells you nothing. Under the rules in force since <strong>22 July 2025</strong>, sponsorship is decided per occupation code, and the Home Office publishes which codes are eligible. On that list:</p>

<ul>
    <li><strong>7111 Sales and retail assistants</strong> &mdash; <strong>Ineligible</strong>. No employer can sponsor a Customer Assistant, however short-staffed the store is.</li>
    <li><strong>7132 Sales supervisors, retail and wholesale</strong> &mdash; listed, but as a medium-skilled occupation under the restricted arrangements, not an open door to a shop-floor job.</li>
</ul>

<p>The skill threshold for the route rose to degree level, RQF 6, in July 2025, and the general salary floor is <strong>&pound;41,700 a year</strong> or the going rate for the occupation, whichever is higher. A part-time Customer Assistant contract would not come close on either test even if the code were eligible.</p>

<p>So M&amp;S store work is open to you if you already hold the right to work: British or Irish citizenship, settled or pre-settled status, indefinite leave to remain, a partner or family visa, a graduate visa, or a dependant visa that permits work. M&amp;S asks for that documentation at the application stage, so there is no point starting without it. Anyone offering you a sponsored M&amp;S shop-floor job is running a scam. If you are applying from abroad, <a href="/blog/jobs-in-uk-for-foreigners">our guide to jobs in UK for foreigners</a> sets out the routes that genuinely exist.</p>

<h2>Benefits M&amp;S Names Itself</h2>

<p>Taken from M&amp;S's own Life at M&amp;S pages and its pay announcement, with the wording kept close to the original because the conditions are where guides go wrong:</p>

<ul>
    <li><strong>Colleague discount.</strong> "After completing your probation period, you'll receive 20% colleague discount across all M&amp;S products and many of our third-party brands for you and a member of your household." Note the condition: M&amp;S publishes no first-day exception for seasonal colleagues, so assume probation applies to you too unless your own offer says otherwise. M&amp;S describes the discount elsewhere as uncapped.</li>
    <li><strong>Bonus.</strong> "We have discretionary bonus schemes depending on your role and our business performance." Discretionary is doing real work in that sentence.</li>
    <li><strong>Pension.</strong> Contributions of up to 12%, per M&amp;S's pay announcement.</li>
    <li><strong>Workplace savings.</strong> "We'll help you save through our Pension Savings Plan, Share Buy and Sharesave schemes."</li>
    <li><strong>Life assurance.</strong> "Get cover for twice your salary up to age 70 (double with our Pension Plan)." Twice, not the four times you will see quoted elsewhere &mdash; four is what you reach only with the Pension Plan.</li>
    <li><strong>Wellbeing.</strong> Access to the Wellbeing Hub, "including a free virtual GP service".</li>
    <li><strong>Family friendly.</strong> "Industry-leading maternity, paternity, adoption and neo-natal policies."</li>
</ul>

<p>On the tools you will actually use, M&amp;S adverts refer to digital tools and in-store devices, and Sparks is the customer loyalty programme you will be asked about at the till. M&amp;S's own careers pages do not set out which systems a Customer Assistant is trained on, so treat any detailed claim about that as coming from an advert rather than from M&amp;S's published material.</p>

<h2>Christmas and Seasonal Hiring</h2>

<p>Seasonal recruitment is the widest door into M&amp;S, and the numbers are national rather than per city. In one year M&amp;S announced <strong>20,000 seasonal jobs</strong> across its UK and Republic of Ireland stores, 5,000 more than the previous year, spanning <strong>over 500 stores</strong>, covering customer assistants, M&amp;S Cafe assistants and store operations roles. A later festive drive was for <strong>over 11,000 new Customer Assistants</strong>, with vacancies in every store.</p>

<p>On conversion, M&amp;S's own figure is that <strong>more than 1 in 5</strong> of that 15,000-strong intake were made permanent. That is a genuinely good rate for seasonal retail, and it is worth planning around &mdash; but it is just over one in five, not "often", and four in five went home in January. M&amp;S also notes that colleagues who started at Christmas have gone on to its Stepping Into Team Manager programme, which is the realistic path upward from a seasonal contract.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Start on the M&amp;S In Store page</strong>, not a job board. Individual M&amp;S vacancy links expire quickly &mdash; two checked for this guide had already gone &mdash; while the category page does not.</li>
    <li><strong>Filter by the specific store</strong> you would actually travel to. The shift pattern is printed at the top of each advert, and M&amp;S notes it is indicative and can change with the store's needs.</li>
    <li><strong>Check the age rule on the advert.</strong> Early morning and overnight Customer Assistant roles require you to be 18 or over, which M&amp;S ties to health and safety legislation rather than to the equipment involved.</li>
    <li><strong>Have your National Insurance number and right to work documents ready</strong> before you open the form, and set aside the full 45 minutes.</li>
    <li><strong>Sit the 20-minute assessment properly.</strong> One sitting, no pausing, real store scenarios. Do it somewhere quiet.</li>
    <li><strong>Book your interview slot</strong> from the availability you give, and expect it to be held in store.</li>
    <li><strong>Use AI to tidy your writing, not to write it.</strong> M&amp;S says so on its own site, and pasted generic answers are the thing it names first.</li>
    <li><strong>Never pay for an M&amp;S job, a shift or an interview.</strong> Applying is free.</li>
</ol>

<p>If M&amp;S has nothing near you, the same application profile transfers straight across &mdash; <a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">Tesco publishes its hourly rate too</a>, and the wider market is covered in our <a href="/blog/store-assistant-jobs-in-uk">store assistant jobs in UK</a> guide.</p>

<h2>Frequently Asked Questions</h2>

<h3>How do I apply for a job at Marks and Spencer?</h3>
<p>Through the In Store section of the M&amp;S careers site, filtered by the store you want. Allow around 45 minutes for the application, have your National Insurance number and right to work documents ready, then expect an online assessment and an interview held in store.</p>

<h3>How much does M&amp;S pay customer assistants an hour?</h3>
<p>M&amp;S publishes a minimum rate of &pound;13.41 an hour nationally and &pound;14.74 in London, effective 1 April 2026 following a 6.4% increase covering around 55,000 retail colleagues. A &pound;3.00 an hour premium applies to hours worked between 10pm and 6am.</p>

<h3>Does M&amp;S pay extra for night shifts?</h3>
<p>Yes. M&amp;S adverts carry a &pound;3.00 an hour unsocial hours premium, and it applies specifically to hours worked between 10pm and 6am. A late shift that finishes before 10pm does not attract it.</p>

<h3>Can Marks and Spencer sponsor a work visa?</h3>
<p>Not for a Customer Assistant. The Home Office lists occupation code 7111, sales and retail assistants, as ineligible for the Skilled Worker route, so no employer can sponsor it. M&amp;S holding a sponsor licence does not change that, because eligibility is set per occupation code.</p>

<h3>When does the 20% M&amp;S colleague discount start?</h3>
<p>M&amp;S's own wording is that you receive it after completing your probation period, covering all M&amp;S products and many third-party brands, for you and a member of your household. M&amp;S publishes no first-day exception for seasonal contracts.</p>

<h3>Do you have to be 18 to work at M&amp;S?</h3>
<p>Not for every role, but early morning and overnight Customer Assistant shifts are restricted to applicants aged 18 and over, which M&amp;S ties to health and safety legislation. The requirement is printed on the adverts it applies to, so read each one.</p>

<h3>What is the M&amp;S online assessment like?</h3>
<p>For Customer Assistant roles M&amp;S describes it as an interactive assessment built around real-life store scenarios, taking around 20 minutes, and it says you need to complete it in one go. It is separate from the 45-minute application form.</p>

<h3>Can I use AI to write my M&amp;S application?</h3>
<p>To a point. M&amp;S publishes guidance saying to use AI to improve rather than define your application: no pasted generic answers, no invented examples, no live help during interviews or assessments, and do not treat AI as your only research about the business.</p>

<h2>People Also Search For</h2>

<h3>M&amp;S jobs near me</h3>
<p>Filter the In Store section of the M&amp;S careers site by location; vacancies are tied to an individual store with its own shift pattern.</p>

<h3>M&amp;S customer assistant pay</h3>
<p>&pound;13.41 an hour nationally and &pound;14.74 in London from 1 April 2026, plus &pound;3.00 an hour for time worked between 10pm and 6am.</p>

<h3>M&amp;S Christmas jobs</h3>
<p>Seasonal customer assistant, M&amp;S Cafe and store operations roles across more than 500 UK and Irish stores, with more than one in five of one recent intake made permanent.</p>

<h3>M&amp;S colleague discount</h3>
<p>20% across all M&amp;S products and many third-party brands, for you and a household member, after you complete your probation period.</p>

<h3>M&amp;S online assessment</h3>
<p>A roughly 20-minute interactive assessment built on real store scenarios, completed in a single sitting, sent after the application form.</p>

<h3>M&amp;S visual merchandiser jobs</h3>
<p>The third in-store family M&amp;S lists alongside customer assistants and retail management, and the one most guides forget to mention.</p>

<h3>Retail jobs visa sponsorship UK</h3>
<p>Sales and retail assistant roles are ineligible under the Skilled Worker rules in force since 22 July 2025, so no UK retailer can sponsor them.</p>

<h3>Skilled Worker visa salary threshold</h3>
<p>&pound;41,700 a year or the occupation's going rate, whichever is higher, at RQF level 6, under the rules in force since 22 July 2025.</p>

<h2>More Job Guides</h2>

<p>If M&amp;S is one option rather than the only one, these cover the rest of the UK market:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a> &mdash; the closest direct comparison, and another employer that publishes its own rate.</li>
    <li><a href="/blog/store-assistant-jobs-in-uk">Store Assistant Jobs in UK</a> &mdash; the same work across the wider retail market, with the same entry requirements.</li>
    <li><a href="/blog/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk">How to Apply for Royal Mail Delivery Jobs in the UK</a> &mdash; shift work on a union pay agreement, if you would rather be outdoors.</li>
    <li><a href="/blog/how-to-apply-for-barclays-customer-service-jobs-in-the-uk">How to Apply for Barclays Customer Service Jobs in the UK</a> &mdash; the desk-based step up from a shop floor role.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; what sponsorship really covers behind the shop, and what it does not.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the routes that remain once the ineligible occupation codes are ruled out.</li>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a> &mdash; where the same reliability gets you off your feet.</li>
    <li><a href="/blog/cook-jobs-in-uk">Cook Jobs in UK</a> &mdash; the kitchen side of the same hospitality and retail market.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; the opposite end of the same job market.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; what the same availability is worth with a licence attached.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Marks and Spencer's own careers site for the in-store roles, application length, hiring stages, assessment description, AI guidance and benefits wording; the M&amp;S corporate newsroom for the hourly rates, the 6.4% increase and its effective date, the seasonal recruitment figures and the market-share claim and its qualifier; and GOV.UK for the Skilled Worker eligible occupation list, the salary threshold and the National Minimum Wage and National Living Wage rates. M&amp;S vacancy adverts expire quickly, and pay, benefits and immigration rules change. Always check the live advert and the official guidance before you commit.</p>
HTML;
    }
}
