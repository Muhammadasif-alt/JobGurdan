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
 * "Store Assistant Jobs in UK" — the most common first job in the country, and
 * the one where the pay figures repeated across careers advice are no longer
 * legal to offer.
 *
 * Corrections to the draft:
 *
 * 1. Its hourly range is below the law. "Store Assistants earn between GBP
 *    10.50 and GBP 12.50 per hour, in line with or slightly above the National
 *    Living Wage" is wrong on both halves: from 1 April 2026 the rate for
 *    workers aged 21 and over is GBP 12.71, so the entire quoted range sits
 *    under the legal minimum rather than above it.
 *
 * 2. Its annual range is unlawful for full-time work. At GBP 12.71 an hour a
 *    35-hour week must pay at least GBP 23,132.20, a 37.5-hour week
 *    GBP 24,784.50 and a 40-hour week GBP 26,436.80. The quoted GBP 21,000 to
 *    GBP 24,000 describes full-time pay no employer may lawfully offer an
 *    adult.
 *
 * 3. It never states the age-related rates, in an article aimed squarely at
 *    first-time workers. From 1 April 2026 the rates are GBP 10.85 for 18 to
 *    20 year olds and GBP 8.00 for under-18s and apprentices.
 *
 * 4. It lists staff discounts and bonus schemes as compensation without noting
 *    that they cannot be used to bring pay up to the minimum wage, nor can
 *    deductions for uniform or shortfalls take pay below it.
 *
 * 5. It sells "flexible hours" as a feature of the job. Flexibility in retail
 *    usually means the employer's flexibility. The employee's version is the
 *    statutory right to request flexible working, available from day one since
 *    6 April 2024, twice in any 12-month period, answerable within two months.
 *
 * 6. It says "no formal qualifications required" and stops there, omitting the
 *    one legal requirement that does apply: the employer must complete a right
 *    to work check before the first shift.
 *
 * 7. It frames retail as simply "evolving alongside online shopping" while
 *    quoting pay that would only make sense in a shrinking wage market. The
 *    honest framing is that the wage floor has risen faster than the sector's
 *    reputation for low pay has caught up.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class StoreAssistantJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-store-assistant-jobs.html';

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
        $title = 'Store Assistant Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The pay usually quoted for UK store assistants is below the law. From 1 April 2026 the National Living Wage is 12.71 GBP an hour for workers aged 21 and over, so a full-time 37.5-hour week must pay at least 24,784 GBP a year.',
                'content' => $content,
                'featured_image' => 'blogs/store-assistant-jobs-in-uk.jpg',
                'tags' => 'store assistant jobs uk, retail jobs uk, national living wage 2026, supermarket jobs uk, shop assistant pay, part time retail jobs, first job uk, flexible working request',
                'meta_title' => 'Store Assistant Jobs in UK: Legal Pay and Real Hours',
                'meta_description' => 'Store assistant jobs in the UK: why 10.50 GBP an hour is below the legal minimum, what the National Living Wage sets by age, and your rights on hours and pay.',
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
            ['name' => 'UK Retailers Advertising Store Assistant Roles (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-store-assistant-aggregated']
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
                'position' => 'Store Assistant — Supermarkets, High Street and Independent Retailers, UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work including evenings, weekends and bank holidays; many contracts are part-time',
                'language' => 'English',
                // Retail pay is set per hour against a statutory floor that
                // varies by age, and most contracts are part-time, so an annual
                // band would misdescribe the majority of these roles.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Shop floor and checkout roles with UK retailers. Check the advertised hourly rate against the National Living Wage for your age before applying.',
                'seo_keywords' => 'store assistant jobs uk, retail assistant jobs, supermarket jobs uk, shop assistant vacancies, part time retail jobs uk',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Supermarkets, high street chains, department stores and independent shops across the United Kingdom recruit store assistants continuously, and heavily before Christmas and other peak trading periods. Most roles are advertised as part-time or on shift patterns covering evenings and weekends, with training given on the job.</p>

<h3>What the work involves</h3>
<p>Serving customers on the shop floor; operating the till and handling card, cash and contactless payments; replenishing shelves and facing up stock; checking and putting away deliveries; processing returns and exchanges; supporting promotions and stocktakes; and keeping the store clean, tidy and safe.</p>

<h3>Requirements</h3>
<ul>
    <li>No formal qualifications for most roles; GCSE maths and English are useful, not usually essential</li>
    <li>Clear, friendly communication and the patience to handle complaints</li>
    <li>Availability across evenings, weekends and bank holidays, which is what most employers are really recruiting for</li>
    <li>Physical stamina for standing, lifting and moving stock throughout a shift</li>
    <li>The right to work in the UK, which the employer must check before your first shift</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the advertised hourly rate against the National Living Wage for your age.</strong> From 1 April 2026 that is &pound;12.71 for workers aged 21 and over, &pound;10.85 for 18 to 20 year olds, and &pound;8.00 for under-18s and apprentices. A staff discount is not pay and cannot make up a shortfall.</p>

<p><strong>Note:</strong> pay, hours, contract type and benefits are set by each retailer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Store assistant is the most common first job in Britain, and the advice written about it has not kept up with the law. Nearly every guide quotes the same hourly range and the same annual salary. Both now describe pay that would be unlawful to offer an adult for full-time work. This page gives the rates the law actually sets, what your employer may and may not deduct from them, and what "flexible hours" means when the flexibility belongs to the rota.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/store-assistant-jobs-in-uk-shop-floor.jpg" alt="Store assistant working the shop floor in a UK retail store" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Pay Figures Everyone Quotes Are Below the Law</h2>

<p>The standard line is that store assistants earn between &pound;10.50 and &pound;12.50 an hour, "in line with or slightly above the National Living Wage". Check it against the rate itself.</p>

<p><strong>From 1 April 2026 the National Living Wage for workers aged 21 and over is &pound;12.71 an hour.</strong> The entire quoted range sits below it. Not slightly above it &mdash; below it, top to bottom. An employer advertising &pound;12.50 an hour to a 22-year-old is advertising an unlawful rate, and the shortfall is recoverable.</p>

<p>The annual figures fail the same test. Multiply &pound;12.71 by a full-time week and by 52 weeks:</p>

<ul>
    <li><strong>35 hours a week</strong> &mdash; at least &pound;23,132.20 a year</li>
    <li><strong>37.5 hours a week</strong> &mdash; at least &pound;24,784.50 a year</li>
    <li><strong>40 hours a week</strong> &mdash; at least &pound;26,436.80 a year</li>
</ul>

<p>So the commonly published band of &pound;21,000 to &pound;24,000 for a full-time store assistant is not a modest salary. At 37.5 hours it is an illegal one, and at 40 hours even the top of the band falls more than &pound;2,400 short. For context, the Office for National Statistics put the median full-time employee across all occupations at <strong>&pound;39,039 a year, or &pound;19.67 an hour</strong>, in April 2025.</p>

<h2>The Rates by Age, Because This Is a First Job</h2>

<p>An article about store assistants is an article about people taking their first job, and the minimum wage is not one number. From 1 April 2026:</p>

<ul>
    <li><strong>21 and over</strong> &mdash; &pound;12.71 an hour (the National Living Wage)</li>
    <li><strong>18 to 20</strong> &mdash; &pound;10.85 an hour</li>
    <li><strong>Under 18</strong> &mdash; &pound;8.00 an hour</li>
    <li><strong>Apprentice</strong> &mdash; &pound;8.00 an hour</li>
</ul>

<p>Two practical consequences. First, a rate that is perfectly legal for a 19-year-old becomes unlawful on their 21st birthday, and the employer must move them up &mdash; it does not happen automatically in every payroll, so check your payslip the month after. Second, if you are offered an "apprenticeship" at &pound;8.00 an hour to do exactly the work of a store assistant, ask what training it actually carries; the apprentice rate exists to fund training, not to discount shelf-filling.</p>

<h2>What Cannot Be Taken Off Your Pay</h2>

<p>This is where retail specifically goes wrong, because the perks in the job advert are not pay.</p>

<p><strong>A staff discount cannot count towards the minimum wage.</strong> Neither can a bonus scheme, free parking, or a Christmas hamper. The minimum wage is a cash rate per hour worked, and benefits in kind do not fill a gap in it.</p>

<p>Deductions get their own rule. If your employer takes money off your wages for something connected to the job &mdash; a uniform you must buy, a till shortfall, breakages &mdash; that deduction cannot reduce your pay below the minimum wage for the hours you worked. A required uniform charged to a worker on exactly the minimum rate is the classic way a lawful headline rate becomes an unlawful actual one.</p>

<p>Unpaid working time is the other leak. Time spent on a required security check at the end of a shift, or cashing up after the doors close, is working time. If your contract pays to closing time but the routine keeps you twenty minutes longer every night, your real hourly rate is not the one on the advert.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/store-assistant-jobs-in-uk-checkout.jpg" alt="Checkout and till work in a UK supermarket, a core store assistant duty" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>"Flexible Hours" Usually Means the Employer's Flexibility</h2>

<p>Retail job adverts sell flexibility, and it is real &mdash; but it generally runs in the employer's direction. Shifts move with trading patterns, hours rise before Christmas and fall in January, and a contract for a small number of guaranteed hours can be topped up or not depending on the rota.</p>

<p>Your side of it is a specific legal right, and it is worth knowing precisely. <strong>Since 6 April 2024 the right to request flexible working is a day-one right.</strong> You can make <strong>two statutory requests in any 12-month period</strong>, your employer must <strong>consult you before rejecting</strong> one, and must respond <strong>within two months</strong>. It can still refuse on any of <strong>eight business reasons</strong>. It is a right to ask, not a right to have &mdash; but a written request that names the pattern you need is far more effective than asking a duty manager on the shop floor.</p>

<p>Before you accept a role, get three things straight: how many hours are <em>guaranteed</em> in the contract, how much notice you get of the rota, and whether evenings, weekends and bank holidays are paid at the same rate. Premium pay for unsocial hours is a contractual matter in the UK, not a statutory one, so it exists only if the contract says so.</p>

<h2>What the Job Is Actually Worth Beyond the Wage</h2>

<p>The honest case for store assistant work is not the pay. It is that it is one of the few jobs available in every town, it trains you in public, and it is the standard entry point into retail management. The progression that guides describe &mdash; assistant, supervisor, assistant manager, store manager &mdash; is real, and retail is unusual in promoting from the shop floor rather than recruiting graduates into it.</p>

<p>It is also transferable. Handling complaints, working a till accurately, managing stock and keeping to a rota are exactly the competencies that customer service, warehousing, hospitality and administrative employers screen for. A year on a shop floor answers the "have you dealt with difficult customers" question in every future interview.</p>

<h2>How to Get Hired</h2>

<ul>
    <li><strong>Divide before you apply.</strong> Convert any annual figure to an hourly rate and compare it against &pound;12.71, or the rate for your age. This one habit filters out the worst listings immediately</li>
    <li><strong>Lead with availability.</strong> Retailers recruit for the hours they struggle to cover. Stating clearly that you can work evenings, weekends and the Christmas period puts you ahead of candidates with better experience</li>
    <li><strong>Apply before the peak, not during it.</strong> Seasonal recruitment for Christmas typically runs from early autumn, and temporary contracts are the most common route into a permanent one</li>
    <li><strong>Name the store.</strong> A line about why that retailer and that branch is the cheapest way to stand out in a pile of identical applications</li>
    <li><strong>Expect a trial shift, and treat it as paid work.</strong> If you are doing productive work under supervision, that is working time</li>
    <li><strong>Have your right to work documents ready.</strong> The employer must check them before your first shift, and delays here lose people the job</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-store-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; See Current UK Store Assistant Listings &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>How much should a store assistant be paid in the UK?</h3>
<p>At least the minimum wage for your age. From 1 April 2026 that is &pound;12.71 an hour at 21 and over, &pound;10.85 at 18 to 20, and &pound;8.00 under 18 or on the apprentice rate. Widely quoted ranges of &pound;10.50 to &pound;12.50 are below the adult rate, not above it.</p>

<h3>Is 21,000 GBP a year legal for a full-time store assistant?</h3>
<p>No, not for an employee aged 21 or over. A 35-hour week must pay at least &pound;23,132.20 a year, a 37.5-hour week &pound;24,784.50, and a 40-hour week &pound;26,436.80. Anything below that for those hours is an underpayment you can recover.</p>

<h3>Does a staff discount count towards the minimum wage?</h3>
<p>No. The minimum wage is a cash rate for each hour worked. Discounts, bonuses and other benefits in kind sit on top of it and cannot be used to make up a shortfall.</p>

<h3>Can my employer deduct the cost of a uniform from my wages?</h3>
<p>Not if the deduction takes your pay below the minimum wage for the hours you worked. The same applies to deductions for till shortfalls or breakages. A lawful headline rate can become an unlawful actual rate this way.</p>

<h3>Do I need qualifications to be a store assistant?</h3>
<p>Generally no. Most retailers train on the job and treat GCSE maths and English as useful rather than essential. The one legal requirement is that your employer completes a right to work check before your first shift.</p>

<h3>Can I ask for set shifts instead of a changing rota?</h3>
<p>You can make a statutory flexible working request from your first day, twice in any 12-month period. Your employer must consult you before refusing and must respond within two months, but it can still refuse for any of eight business reasons.</p>

<h3>Do store assistants get paid more for evenings, weekends and bank holidays?</h3>
<p>Only if the contract says so. Premium rates for unsocial hours are contractual in the UK, not statutory, so check whether they exist before accepting a role built around those shifts.</p>

<h3>What can a store assistant job lead to?</h3>
<p>Retail promotes from the shop floor, so the route to supervisor, assistant manager and store manager is a normal one. The skills also transfer directly into customer service, warehousing, hospitality and administrative work.</p>

<h2>People Also Search For</h2>

<ul>
    <li><strong>Store assistant jobs near me no experience</strong> &mdash; most retailers train on the job; availability for evenings and weekends matters more than experience</li>
    <li><strong>Store assistant salary UK per hour</strong> &mdash; at least &pound;12.71 for workers aged 21 and over from 1 April 2026</li>
    <li><strong>Part time store assistant jobs</strong> &mdash; the minimum wage applies per hour, so lawful part-time pay can total well under &pound;24,784 a year</li>
    <li><strong>Christmas temporary retail jobs UK</strong> &mdash; recruitment usually opens in early autumn and is the most common route into a permanent contract</li>
    <li><strong>What is the minimum wage for a 17 year old UK</strong> &mdash; &pound;8.00 an hour from 1 April 2026, the same as the apprentice rate</li>
    <li><strong>Flexible working request retail</strong> &mdash; a day-one right since 6 April 2024, twice a year, answerable within two months</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London (No Experience Needed)</a></li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a></li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a></li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a></li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a></li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a></li>
</ul>
HTML;
    }
}
