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

/**
 * "How Much Does an HGV Driver Earn in the UK in 2026?"
 *
 * What changed against the brief:
 *
 * 1. The pay ranges came from recruiter adverts and industry guides, which this
 *    site does not republish as statistics. The guide uses worked examples at
 *    GBP 32,000, 37,500 and 42,000 a year, labelled as examples, and the
 *    2026-27 England tax and National Insurance maths is recomputed (GBP 37,500
 *    gives about GBP 4,986 tax and GBP 1,994 NI, so about GBP 2,543 a month).
 * 2. The single February 2026 job-ad rate card, the "GBP 1.50 to GBP 3 night
 *    premium" and the "GBP 2,000 to GBP 8,000" add-on figures are dropped; none
 *    could be confirmed.
 * 3. PKR uses an illustrative 375 to the pound, as in the other UK guides.
 * 4. The Apply Now buttons are gone; the one official link sits on the job
 *    listing.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class HgvDriverSalaryUkBlogSeeder extends Seeder
{
    public const SLUG = 'hgv-driver-salary-uk-2026';

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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'How Much Does an HGV Driver Earn in the UK in 2026?',
                'excerpt' => 'Pay depends on licence class, shift and employer, and every advert states its own rate. See worked examples at £32,000, £37,500 and £42,000 a year, the take-home pay, PKR conversions and the right-to-work rules.',
                'content' => $content,
                'featured_image' => 'blogs/hgv-driver-salary-uk.jpg',
                'tags' => 'hgv driver salary uk, class 1 driver pay, class 2 driver pay, hgv driver hourly rate, hgv driver monthly pay, hgv salary in pkr, lorry driver take home pay, hgv driver from pakistan',
                'meta_title' => 'How Much Does an HGV Driver Earn in the UK (2026)?',
                'meta_description' => 'UK HGV driver pay in 2026: worked examples by year, take-home pay, PKR conversions, what moves pay up or down and the right-to-work rules.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Haulage and Logistics Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-haulage-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        Job::updateOrCreate(
            ['position' => 'HGV Driver — UK Employers (Pay Check)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Find a job is the UK Government's official job search. Every advert states its hourly rate or yearly salary, so you can compare Class 1 and Class 2 roles before you apply.</p>

<h3>Requirements</h3>
<ul>
    <li>The right to work in the UK and a UK HGV licence (Category C or C+E) with Driver CPC</li>
    <li>Pay must be at least the National Living Wage for the worker's age, and each advert states the actual rate</li>
    <li>No UK employer or agency may charge you a fee for finding work</li>
</ul>

<p><strong>Note:</strong> pay and visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Set by each employer, often shifts',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => 'https://findajob.dwp.gov.uk/',
                'meta_description' => 'Compare HGV driver pay on the UK Government Find a job service. Each advert states its rate. The right to work and a UK HGV licence are needed.',
                'seo_keywords' => 'hgv driver salary uk, hgv driver pay, class 1 driver jobs, find a job uk',
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>There is no single HGV driver pay rate in the UK. It depends on licence class, shift, employer and the type of work, and every advert states its own rate. As a worked example, a Class 1 driver on <strong>£37,500 a year</strong> earns about £3,125 a month before tax, or roughly £2,543 a month after tax and National Insurance. That is about PKR 1.17 million gross at an illustrative PKR 375 to the pound.</p>

<h2>What Is the Salary of an HGV Driver in the UK?</h2>

<p>Pay depends mostly on your licence class. Class 1 (Category C+E) covers articulated lorries and usually pays more. Class 2 (Category C) covers rigid lorries. The rows below are <strong>examples of what a given yearly salary pays, not a statistic</strong>, so always check the exact figure in the job advert.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Yearly salary (example)</th>
            <th style="padding:10px;text-align:left;">Equivalent hourly (40 hrs)</th>
            <th style="padding:10px;text-align:left;">Monthly (gross)</th>
            <th style="padding:10px;text-align:left;">Monthly in PKR (illustrative)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>£32,000</strong></td><td style="padding:10px;">£15.38</td><td style="padding:10px;">£2,667</td><td style="padding:10px;">PKR 1,000,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>£37,500</strong></td><td style="padding:10px;">£18.03</td><td style="padding:10px;">£3,125</td><td style="padding:10px;">PKR 1,171,875</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>£42,000</strong></td><td style="padding:10px;">£20.19</td><td style="padding:10px;">£3,500</td><td style="padding:10px;">PKR 1,312,500</td></tr>
    </tbody>
</table>
</div>

<p>Monthly figures are the yearly pay divided by 12, gross, before tax. The hourly column divides the yearly pay by 2,080 hours (40 hours a week for 52 weeks); many HGV drivers work longer weeks, so the real hourly rate can be lower or higher. The PKR column uses <strong>£1 = PKR 375</strong>, an illustrative rate rather than a verified exchange quote, so replace it with your bank's current rate.</p>

<h2>What Is the Hourly Rate for an HGV Driver in the UK?</h2>

<p>Many drivers, especially agency workers, are paid by the hour, and the rate depends on the shift and employer. Whatever the advert says, it can never be lower than the legal minimum: the National Living Wage is £12.71 an hour for workers aged 21 and over from April 2026. Many adverts also set a higher rate for early or late starts, Saturdays, Sundays and bank holidays, so read the rate card in each advert.</p>

<h2>How Much Is an HGV Driver's Take-Home Pay?</h2>

<p>Income tax and National Insurance reduce your pay. As a rough guide for someone in England with a standard tax code and no pension:</p>

<ul>
    <li><strong>On £32,000 a year:</strong> income tax is about £3,886 and National Insurance about £1,554, which leaves roughly £26,560 a year, or about £2,213 a month.</li>
    <li><strong>On £37,500 a year:</strong> income tax is about £4,986 and National Insurance about £1,994, which leaves roughly £30,520 a year, or about £2,543 a month.</li>
    <li><strong>On £42,000 a year:</strong> income tax is about £5,886 and National Insurance about £2,354, which leaves roughly £33,760 a year, or about £2,813 a month.</li>
</ul>

<p>These are estimates. Scotland has different income tax bands, and pension contributions, student loans and your tax code change the result.</p>

<h2>How Much Is an HGV Driver's Salary in PKR?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Period (£37,500 example)</th>
            <th style="padding:10px;text-align:left;">GBP</th>
            <th style="padding:10px;text-align:left;">Illustrative PKR</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per month (gross)</td><td style="padding:10px;">£3,125</td><td style="padding:10px;">PKR 1,171,875</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per year (gross)</td><td style="padding:10px;">£37,500</td><td style="padding:10px;">PKR 14,062,500</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per month (after tax and NI)</td><td style="padding:10px;">£2,543</td><td style="padding:10px;">PKR 953,625</td></tr>
    </tbody>
</table>
</div>

<p>UK rent, fuel for your own travel and food are costly, so savings are much lower than the headline number. Check the current exchange rate before you plan.</p>

<h2>What Makes HGV Pay Higher or Lower?</h2>

<ul>
    <li><strong>Licence class:</strong> Class 1 usually pays more than Class 2.</li>
    <li><strong>Shift:</strong> nights and weekends often carry a premium; check each advert.</li>
    <li><strong>Type of work:</strong> long-haul work with nights away generally pays more than local day work, but takes you away from home.</li>
    <li><strong>Specialist work:</strong> hazardous goods (ADR), tanker and crane work can add to pay.</li>
    <li><strong>Agency or permanent:</strong> agency work is often paid by the hour with no guaranteed hours, while permanent jobs add holiday pay, sick pay and a pension.</li>
    <li><strong>Experience:</strong> some adverts ask for two years of experience and a clean licence.</li>
</ul>

<p>Working time rules also matter: the average working week is limited to 48 hours unless you opt out, and daily driving is capped at 9 hours (10 hours twice a week).</p>

<h2>Do Foreign HGV Drivers Earn the Same as British Drivers?</h2>

<p>Yes. UK pay and minimum wage laws apply to everyone working legally. But you need the legal right to work, a UK HGV licence (a full UK car licence, then provisional HGV entitlement, tests and Driver CPC) and often a digital tachograph card.</p>

<p><strong>Important:</strong> HGV driving is generally not eligible for UK visa sponsorship. Read <a href="/blog/hgv-driver-jobs-uk-visa-sponsorship-2026">HGV Driver Jobs in the UK With Visa Sponsorship (2026)</a> before paying anyone. A "guaranteed HGV visa" offer that asks for money is very likely a scam. The legal route from Pakistan is in <a href="/blog/how-to-get-hgv-driver-job-uk-from-pakistan-2026">How to Get an HGV Driver Job in the UK From Pakistan</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/transport-logistics" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Transport &amp; Logistics Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the monthly salary of an HGV driver in the UK?</h3>
<p>It depends on the job. A worked example of £37,500 a year is about £3,125 a month before tax and £2,543 after tax and National Insurance.</p>

<h3>Do Class 1 drivers earn more than Class 2?</h3>
<p>Usually yes, because articulated lorries need extra training and licence entitlement. Check the rate in each advert.</p>

<h3>Do HGV drivers get paid more for nights and weekends?</h3>
<p>Often, yes. Many adverts pay a higher rate on nights, Saturdays, Sundays and bank holidays.</p>

<h3>What is the minimum an HGV driver can be paid?</h3>
<p>No less than the National Living Wage, which is £12.71 an hour for workers aged 21 and over from April 2026.</p>

<h3>How much tax does an HGV driver pay?</h3>
<p>In England on £37,500 with a standard tax code, about £4,986 income tax and £1,994 National Insurance a year.</p>

<h3>Can I work as an HGV driver in the UK from Pakistan?</h3>
<p>Only if you already have the legal right to work in the UK and hold, or can gain, a UK HGV licence. There is no standard sponsorship route.</p>

<h3>How many hours can an HGV driver work?</h3>
<p>The average working week is limited to 48 hours unless you opt out, and daily driving is capped at 9 hours, or 10 hours twice a week.</p>

<h3>Do foreign drivers earn the same as British drivers?</h3>
<p>Yes. UK pay and minimum wage laws apply to everyone working legally.</p>

<h2>People Also Search For</h2>

<h3>HGV driver salary UK</h3>
<p>Set by each employer; see the worked examples.</p>

<h3>Class 1 driver pay</h3>
<p>Usually higher than Class 2.</p>

<h3>Class 2 driver pay</h3>
<p>Varies by employer and experience.</p>

<h3>HGV driver hourly rate</h3>
<p>At least £12.71 an hour at age 21 and over.</p>

<h3>HGV driver take-home pay</h3>
<p>About £2,543 a month on £37,500.</p>

<h3>HGV driver salary in PKR</h3>
<p>About PKR 1.17 million gross a month on £37,500.</p>

<h3>HGV visa sponsorship</h3>
<p>Generally not available for lorry drivers.</p>

<h3>Truck driver salary USA</h3>
<p>The US pay picture is in our truck driver guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/hgv-driver-jobs-uk-visa-sponsorship-2026">HGV Driver Jobs in the UK With Visa Sponsorship (2026)</a> &mdash; why it is generally not available.</li>
    <li><a href="/blog/how-to-get-hgv-driver-job-uk-from-pakistan-2026">How to Get an HGV Driver Job in the UK From Pakistan (2026)</a> &mdash; the legal route step by step.</li>
    <li><a href="/blog/truck-driver-salary-usa-2026">Truck Driver Salary in the USA (2026)</a> &mdash; the US picture for comparison.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or financial advice. Tax and wage figures were checked on 9 October 2026 and change often, so confirm them on GOV.UK before you act or pay anyone.</p>
HTML;
    }
}
