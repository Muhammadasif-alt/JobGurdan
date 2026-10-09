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
 * "How Much Does a Kitchen Porter Earn in the UK in 2026?"
 *
 * What changed against the brief:
 *
 * 1. The "average GBP 13.17 an hour" came from Indeed salary data, which this
 *    site does not republish. The guide is built on the 2026 National Living
 *    Wage of GBP 12.71 and two clearly labelled example rates (GBP 13.50 and
 *    GBP 14.50), with no claim that they are averages.
 * 2. The "other websites show GBP 10 to GBP 11.50" paragraph and the care-home
 *    advert (GBP 12.25 rising to GBP 12.75) are dropped; neither could be
 *    confirmed.
 * 3. Take-home is recomputed for the 2026-27 England tax year: GBP 26,437 gives
 *    about GBP 1,880 a month and GBP 28,080 about GBP 1,978 a month.
 * 4. PKR uses an illustrative 375 to the pound, as in the other UK guides.
 * 5. The Apply Now buttons are gone; the one official link sits on the job
 *    listing.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class KitchenPorterSalaryUkBlogSeeder extends Seeder
{
    public const SLUG = 'kitchen-porter-salary-uk-2026';

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
                'title' => 'How Much Does a Kitchen Porter Earn in the UK in 2026?',
                'excerpt' => 'The legal minimum for a UK kitchen porter aged 21 or over is £12.71 an hour from April 2026, about £2,203 a month before tax. See example rates, take-home pay and PKR conversions.',
                'content' => $content,
                'featured_image' => 'blogs/kitchen-porter-salary-uk.jpg',
                'tags' => 'kitchen porter salary uk, kitchen porter hourly rate, national living wage 2026, kitchen porter pay uk, kitchen porter salary in pkr, kitchen porter take home pay, uk hospitality pay, kitchen porter job uk foreigners',
                'meta_title' => 'How Much Does a Kitchen Porter Earn in the UK (2026)?',
                'meta_description' => 'UK kitchen porter pay in 2026: the National Living Wage, what higher rates pay, take-home and PKR examples, and the right-to-work rules.',
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
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Hospitality and Catering Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-hospitality-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        Job::updateOrCreate(
            ['position' => 'Kitchen Porter — UK Employers (Pay Check)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Find a job is the UK Government's official job search. Every advert states its hourly rate, so check it against the National Living Wage for your age before you apply.</p>

<h3>Requirements</h3>
<ul>
    <li>The National Living Wage is the legal minimum for workers aged 21 and over; younger workers have lower legal minimums</li>
    <li>Hours, shifts and location are in each advert</li>
    <li>No UK employer or agency may charge you a fee for finding work</li>
</ul>

<p><strong>Note:</strong> pay and visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Set by each employer, often evenings and weekends',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => 'https://findajob.dwp.gov.uk/',
                'meta_description' => 'Compare kitchen porter pay on the UK Government Find a job service. Each advert states its rate, and the National Living Wage is the legal minimum.',
                'seo_keywords' => 'kitchen porter salary uk, kitchen porter pay, national living wage, find a job uk',
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>The legal minimum for a UK kitchen porter aged 21 or over is the <strong>National Living Wage of £12.71 an hour from April 2026</strong>. At 40 hours a week that is about £26,437 a year, or roughly £2,203 a month before tax, which is about PKR 826,125 at an illustrative PKR 375 to the pound. Many hospitality jobs pay more at weekends or in busy venues, and the guide below shows what a higher hourly rate adds up to.</p>

<h2>What Is the Hourly Rate for a Kitchen Porter in the UK?</h2>

<p>No employer can pay a worker aged 21 or over less than £12.71 an hour. Above that floor, pay depends on the employer, the venue and the shift, so the rows beyond the minimum below are <strong>examples of what a higher rate pays, not a statistic</strong>. Always check the exact rate in the job advert.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Hourly rate</th>
            <th style="padding:10px;text-align:left;">Yearly (40 hrs)</th>
            <th style="padding:10px;text-align:left;">Monthly (gross)</th>
            <th style="padding:10px;text-align:left;">Monthly in PKR (illustrative)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>£12.71</strong> (legal minimum, age 21+)</td><td style="padding:10px;">£26,437</td><td style="padding:10px;">£2,203</td><td style="padding:10px;">PKR 826,125</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>£13.50</strong> (example)</td><td style="padding:10px;">£28,080</td><td style="padding:10px;">£2,340</td><td style="padding:10px;">PKR 877,500</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>£14.50</strong> (example)</td><td style="padding:10px;">£30,160</td><td style="padding:10px;">£2,513</td><td style="padding:10px;">PKR 942,375</td></tr>
    </tbody>
</table>
</div>

<p>Yearly pay is the hourly rate times 40 hours times 52 weeks. Monthly figures are the yearly pay divided by 12, gross, before tax. The PKR column uses <strong>£1 = PKR 375</strong>, an illustrative rate rather than a verified exchange quote, so replace it with your bank's current rate. Many kitchen porter jobs are part time or irregular, so your real monthly pay may be lower.</p>

<h2>How Much Is a Kitchen Porter's Take-Home Pay?</h2>

<p>Income tax and National Insurance reduce your pay. As a rough guide for someone in England with a standard tax code and no pension:</p>

<ul>
    <li><strong>On £26,437 a year (minimum wage):</strong> income tax is about £2,773 and National Insurance about £1,109, which leaves roughly £22,555 a year, or about £1,880 a month.</li>
    <li><strong>On £28,080 a year (£13.50 an hour):</strong> income tax is about £3,102 and National Insurance about £1,241, which leaves roughly £23,737 a year, or about £1,978 a month.</li>
</ul>

<p>These are estimates. Scotland has different income tax bands, and pension contributions, student loans and your tax code change the result.</p>

<h2>How Much Is a Kitchen Porter's Salary in PKR?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Period (minimum wage)</th>
            <th style="padding:10px;text-align:left;">GBP</th>
            <th style="padding:10px;text-align:left;">Illustrative PKR</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per hour</td><td style="padding:10px;">£12.71</td><td style="padding:10px;">PKR 4,766</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per month (gross)</td><td style="padding:10px;">£2,203</td><td style="padding:10px;">PKR 826,125</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per month (after tax and NI)</td><td style="padding:10px;">£1,880</td><td style="padding:10px;">PKR 705,000</td></tr>
    </tbody>
</table>
</div>

<p>UK rent, transport and food are costly, so savings are much lower than the headline number. Check the current exchange rate before you plan.</p>

<h2>What Makes Kitchen Porter Pay Higher or Lower?</h2>

<ul>
    <li><strong>Location:</strong> London and some hotel or event venues tend to pay more.</li>
    <li><strong>Hours:</strong> many jobs are part time, split-shift or irregular. A full-time job can be 40 hours or more a week, sometimes with alternate weekends.</li>
    <li><strong>Weekends and nights:</strong> some employers pay a higher rate at weekends.</li>
    <li><strong>Employer type:</strong> contract caterers, hotels, restaurants and care homes all pay differently.</li>
    <li><strong>Progression:</strong> moving up to kitchen assistant or commis roles can raise pay over time.</li>
    <li><strong>Age:</strong> the £12.71 rate applies to workers aged 21 and over. Younger workers have lower legal minimums.</li>
</ul>

<h2>Do Foreign Workers Earn the Same as British Workers?</h2>

<p>Yes. UK minimum wage law applies to everyone working legally, whatever their nationality. A full-time worker is also entitled to statutory paid holiday of 5.6 weeks a year. A Student visa holder limited to 20 hours a week in term time earns about £254 a week before tax at the minimum wage.</p>

<p><strong>Important:</strong> you need the legal right to work in the UK first. Kitchen porter jobs are generally not eligible for visa sponsorship, so read <a href="/blog/kitchen-porter-jobs-uk-visa-sponsorship-2026">Kitchen Porter Jobs in the UK With Visa Sponsorship (2026)</a> before paying anyone. Any agent who charges for a "sponsored kitchen porter job" is likely a scam.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/hospitality-tourism" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Hospitality &amp; Tourism Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the monthly salary of a kitchen porter in the UK?</h3>
<p>About £2,203 a month before tax at the legal minimum and 40 hours a week, or roughly £1,880 after tax and National Insurance in England.</p>

<h3>What is the minimum wage for a kitchen porter in the UK?</h3>
<p>The National Living Wage is £12.71 an hour for workers aged 21 and over from April 2026. Lower minimum rates apply to younger workers and apprentices.</p>

<h3>Do kitchen porters get paid for overtime?</h3>
<p>It depends on the contract. Many employers pay extra for weekends or hours over the contracted amount, but not all do. Check the offer before you accept.</p>

<h3>Can I work as a kitchen porter in the UK from Pakistan?</h3>
<p>Only if you already have the legal right to work. There is no standard visa for this job, and the Skilled Worker visa generally does not cover it, so be wary of paid "sponsorship" offers.</p>

<h3>How much tax does a kitchen porter pay?</h3>
<p>In England on £26,437 with a standard tax code, about £2,773 income tax and £1,109 National Insurance a year.</p>

<h3>How much holiday does a kitchen porter get?</h3>
<p>A full-time worker is entitled to 5.6 weeks of statutory paid holiday a year.</p>

<h3>Can a student visa holder work as a kitchen porter?</h3>
<p>Yes, as an employee within the visa's hour limits, usually 20 hours a week in term time on a degree-level course.</p>

<h3>Do foreign workers earn the same as British workers?</h3>
<p>Yes. UK minimum wage law applies to everyone working legally.</p>

<h2>People Also Search For</h2>

<h3>Kitchen porter salary UK</h3>
<p>At least £12.71 an hour at age 21 and over.</p>

<h3>Kitchen porter hourly rate</h3>
<p>Set by each employer; never below the legal minimum.</p>

<h3>National Living Wage 2026</h3>
<p>£12.71 an hour from April 2026 for ages 21 and over.</p>

<h3>Kitchen porter take-home pay</h3>
<p>About £1,880 a month at the minimum wage.</p>

<h3>Kitchen porter salary in PKR</h3>
<p>About PKR 826,125 gross a month at the minimum wage.</p>

<h3>Kitchen porter visa sponsorship</h3>
<p>Generally not available.</p>

<h3>UK hospitality jobs</h3>
<p>Open to people who already have the right to work.</p>

<h3>Cleaner job UK from Pakistan</h3>
<p>Only with an existing right to work.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/kitchen-porter-jobs-uk-visa-sponsorship-2026">Kitchen Porter Jobs in the UK With Visa Sponsorship (2026)</a> &mdash; why it is generally not available.</li>
    <li><a href="/blog/how-to-get-cleaner-job-uk-from-pakistan-2026">How to Get a Cleaner Job in the UK From Pakistan (2026)</a> &mdash; the legal routes step by step.</li>
    <li><a href="/blog/warehouse-operative-salary-uk-2026">Warehouse Operative Salary in the UK (2026)</a> &mdash; another UK pay guide.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or financial advice. Tax and wage figures were checked on 9 October 2026 and change often, so confirm them on GOV.UK before you act or pay anyone.</p>
HTML;
    }
}
