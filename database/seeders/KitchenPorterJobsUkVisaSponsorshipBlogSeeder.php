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
 * "Can a Foreigner Get a Kitchen Porter Job in the UK With Visa Sponsorship in
 * 2026?"
 *
 * Checked on 9 October 2026 against the GOV.UK Skilled Worker pages (22 July
 * 2025 changes, GBP 41,700 general threshold, CEFR B2 from 8 January 2026),
 * the Student visa work rules and the 2026 National Living Wage. What changed
 * against the brief:
 *
 * 1. The "GBP 12.71 to GBP 14.50" advert range is not republished; the guide
 *    gives the legal minimum and points to the pay guide for labelled examples.
 * 2. The brief says restaurant and catering managers are "Medium Skilled and
 *    need a shortage-list listing"; that could not be confirmed against the
 *    live list, so the guide only says some management roles may qualify and
 *    tells the reader to check the code.
 * 3. The pointer to the cleaner-from-Pakistan guide is kept; that page exists.
 * 4. The Apply Now buttons are gone; the one official link sits on the job
 *    listing.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class KitchenPorterJobsUkVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'kitchen-porter-jobs-uk-visa-sponsorship-2026';

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
                'title' => 'Can a Foreigner Get a Kitchen Porter Job in the UK With Visa Sponsorship in 2026?',
                'excerpt' => 'Generally no. Since 22 July 2025 the Skilled Worker visa covers mainly degree-level roles and a few shortage roles, so UK employers cannot normally sponsor a kitchen porter. See the rules, the routes that allow work, why "sponsorship" adverts mislead and the scams to avoid.',
                'content' => $content,
                'featured_image' => 'blogs/kitchen-porter-jobs-uk-visa-sponsorship.jpg',
                'tags' => 'kitchen porter jobs uk, uk kitchen porter visa sponsorship, skilled worker visa kitchen porter, kitchen porter jobs for foreigners, kitchen porter from pakistan, uk visa sponsorship scams, national living wage, uk hospitality jobs',
                'meta_title' => 'Kitchen Porter Jobs in the UK With Visa Sponsorship (2026)',
                'meta_description' => 'Can foreigners get a UK kitchen porter job with visa sponsorship in 2026? The Skilled Worker rules, routes that allow work, pay and scams.',
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
            ['position' => 'Kitchen Porter — UK Employers (Right to Work Needed)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Find a job is the UK Government's official job search. It lists kitchen and hospitality vacancies from UK employers, with the pay and hours set out in each advert.</p>

<h3>Requirements</h3>
<ul>
    <li>The right to work in the UK: kitchen porter is not normally eligible for the Skilled Worker route, so a sponsored visa is not available</li>
    <li>Pay is at least the National Living Wage for the worker's age, and each advert states the actual rate</li>
    <li>No UK employer or agency may charge you a fee for finding work</li>
</ul>

<p><strong>Note:</strong> visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>
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
                'meta_description' => 'Kitchen and hospitality vacancies from UK employers on the UK Government Find a job service. The right to work is needed; the role is not sponsorable.',
                'seo_keywords' => 'kitchen porter jobs uk, uk kitchen porter visa sponsorship, kitchen porter vacancies, find a job uk',
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Generally no.</strong> Kitchen porter is a lower-skilled job, and since 22 July 2025 the Skilled Worker visa covers only roles at degree level (RQF Level 6) or a few listed shortage roles. Kitchen porters are not normally eligible, so any agent selling a "sponsored kitchen porter visa" is very likely a scam.</p>

<h2>Why Can't Kitchen Porters Get a Skilled Worker Visa?</h2>

<p>GOV.UK lists every job code and says whether it can be sponsored. Kitchen porter work is coded under kitchen and catering assistants. To be sponsored, a job needs to meet skill, salary and English rules:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Requirement</th>
            <th style="padding:10px;text-align:left;">Current rule (2026)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Job skill level</strong></td><td style="padding:10px;">RQF Level 6 (degree level), unless on a shortage list</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>General salary threshold</strong></td><td style="padding:10px;">£41,700 a year (or the going rate, if higher)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>English</strong></td><td style="padding:10px;">CEFR B2 from 8 January 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sponsor</strong></td><td style="padding:10px;">The employer must hold a Home Office sponsor licence and issue a Certificate of Sponsorship</td></tr>
    </tbody>
</table>
</div>

<p>A kitchen porter's pay is far below £41,700 and the job is not at degree level. Some hospitality management roles may qualify, but each depends on its code and the live shortage lists, so check the code on GOV.UK before believing any offer.</p>

<h2>Why Do I See "Kitchen Porter Visa Sponsorship" Jobs Online?</h2>

<p>Some job aggregator websites repost the same template, with wording like "UK visa sponsorship available", across many kitchen porter listings. The same role can be marked ineligible for sponsorship on sites that check the official codes, and the listings often tell applicants to confirm sponsorship with the employer.</p>

<p>Do not trust a "sponsorship" label alone. Check the employer on the official Register of Licensed Sponsors, and check the job's code on GOV.UK. If the role is ineligible, no employer can legally sponsor you for it.</p>

<h2>How Do People Legally Work as Kitchen Porters in the UK?</h2>

<p>Most hold UK permission that already includes the right to work:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">Work rights</th>
            <th style="padding:10px;text-align:left;">Reality check</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Graduate visa</strong></td><td style="padding:10px;">Can work in most jobs, including full time</td><td style="padding:10px;">For graduates of UK degrees</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Family visa</strong></td><td style="padding:10px;">Generally allowed to work</td><td style="padding:10px;">For partners and family of British or settled people</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Student visa</strong></td><td style="padding:10px;">Usually 20 hours a week in term time (degree level), full time in vacations</td><td style="padding:10px;">No permanent full-time job or self-employment</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Youth Mobility Scheme</strong></td><td style="padding:10px;">Can work</td><td style="padding:10px;">Only for listed countries, and Pakistan is not one</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Seasonal Worker visa</strong></td><td style="padding:10px;">Farm work only</td><td style="padding:10px;">Not for kitchens</td></tr>
    </tbody>
</table>
</div>

<p><strong>Pakistan note:</strong> from Pakistan, there is no straightforward visa to a UK kitchen porter job, and a visitor visa does not allow work. The same legal routes are explained step by step in <a href="/blog/how-to-get-cleaner-job-uk-from-pakistan-2026">How to Get a Cleaner Job in the UK From Pakistan</a>.</p>

<h2>What Does a Kitchen Porter Earn in the UK?</h2>

<p>The National Living Wage is £12.71 an hour for workers aged 21 and over from April 2026. At 40 hours a week that is about £26,437 a year, or roughly £2,203 a month before tax. Many hospitality jobs have part-time or irregular hours, so check the contract. Worked examples and take-home pay are in <a href="/blog/kitchen-porter-salary-uk-2026">Kitchen Porter Salary in the UK (2026)</a>.</p>

<h2>What Are the Requirements for a Kitchen Porter Job?</h2>

<ul>
    <li>The legal right to work in the UK, which the employer must check.</li>
    <li>Basic English for safety rules and kitchen instructions.</li>
    <li>Stamina for standing, lifting and washing for long shifts.</li>
    <li>Willingness to work evenings and weekends.</li>
    <li>Food hygiene awareness, which employers usually train.</li>
</ul>

<p>No degree is needed, and kitchen experience is usually a bonus rather than required.</p>

<h2>How Do You Avoid Kitchen Porter Visa Scams?</h2>

<ul>
    <li>Any fee for a "Certificate of Sponsorship" or job offer. Sponsors should not charge workers.</li>
    <li>Employers that are not on the Register of Licensed Sponsors.</li>
    <li>Offers made only through WhatsApp, Telegram or social media.</li>
    <li>A job guaranteed with no interview.</li>
    <li>Template adverts with no real company name, address or contact.</li>
</ul>

<p>Report UK fraud through the UK police fraud reporting service. In Pakistan, you can report suspicious agents to the Federal Investigation Agency (FIA). Never pay for a job offer.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/hospitality-tourism" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Hospitality &amp; Tourism Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a Skilled Worker visa as a kitchen porter?</h3>
<p>Generally no. Kitchen porter roles are not at degree level and are not normally eligible, so legitimate UK employers cannot normally sponsor them.</p>

<h3>Do UK restaurants sponsor kitchen porters?</h3>
<p>Rarely, and only if the role qualifies under the current list. Most kitchen porter jobs are filled by people who already have the right to work.</p>

<h3>How much does a kitchen porter earn in the UK?</h3>
<p>At least the National Living Wage of £12.71 an hour for those aged 21 and over. Each advert states the actual rate.</p>

<h3>Can I apply for a UK kitchen porter job from Pakistan?</h3>
<p>You can apply, but without the right to work you cannot take the job. There is no standard visa for this role, so be cautious of agents who say otherwise.</p>

<h3>Is there a UK work visa for low-skilled jobs?</h3>
<p>No general one. The care worker route also closed to new overseas applicants on 22 July 2025.</p>

<h3>Can a student visa holder work as a kitchen porter?</h3>
<p>Yes, as an employee within the visa's hour limits, usually 20 hours a week in term time on a degree-level course.</p>

<h3>Is the Youth Mobility Scheme open to Pakistanis?</h3>
<p>No. Pakistan is not on the list of eligible countries.</p>

<h3>Should I pay an agent for a UK kitchen porter visa?</h3>
<p>No. A job that cannot be sponsored cannot be sold to you legitimately. Treat any paid offer as a scam.</p>

<h2>People Also Search For</h2>

<h3>Kitchen porter jobs UK visa sponsorship</h3>
<p>Generally not available.</p>

<h3>Kitchen porter Skilled Worker visa</h3>
<p>The role is below the degree-level requirement.</p>

<h3>Kitchen porter salary UK</h3>
<p>At least £12.71 an hour at age 21 and over.</p>

<h3>Kitchen porter job from Pakistan</h3>
<p>Only with an existing right to work.</p>

<h3>UK low skilled work visa</h3>
<p>There is no general route.</p>

<h3>Register of Licensed Sponsors</h3>
<p>The official GOV.UK list of employers that can sponsor.</p>

<h3>UK visa sponsorship scams</h3>
<p>Any fee for sponsorship is a warning sign.</p>

<h3>Hotel housekeeper jobs in USA</h3>
<p>The US has a different, seasonal route covered in our guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/kitchen-porter-salary-uk-2026">Kitchen Porter Salary in the UK (2026)</a> &mdash; pay, tax and PKR examples.</li>
    <li><a href="/blog/how-to-get-cleaner-job-uk-from-pakistan-2026">How to Get a Cleaner Job in the UK From Pakistan (2026)</a> &mdash; the same legal routes step by step.</li>
    <li><a href="/blog/hotel-housekeeper-jobs-usa-visa-sponsorship-2026">Hotel Housekeeper Jobs in the USA With Visa Sponsorship (2026)</a> &mdash; the US route for comparison.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Rules were checked against GOV.UK on 9 October 2026 and change often, so confirm them on GOV.UK before you act or pay anyone.</p>
HTML;
    }
}
