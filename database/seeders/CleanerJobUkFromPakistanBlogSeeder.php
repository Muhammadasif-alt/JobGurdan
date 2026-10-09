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
 * "How Do I Get a Cleaner Job in the UK From Pakistan?"
 *
 * Checked on 9 October 2026 against the GOV.UK Skilled Worker pages, the
 * Student visa work rules (20 hours a week in term time at degree level, 10
 * below; no self-employment) and the 2026 National Living Wage. What changed
 * against the brief:
 *
 * 1. The "GBP 12.71 to GBP 14.50" and London "GBP 13 to GBP 14.50" ranges came
 *    from job adverts and are not republished. The guide gives the legal
 *    minimum and a labelled example for a student's weekly pay.
 * 2. The pointer to a warehouse-from-Pakistan guide is replaced with the
 *    warehouse sponsorship guide, which exists.
 * 3. The Apply Now buttons are gone; the one official link sits on the job
 *    listing.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class CleanerJobUkFromPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-get-cleaner-job-uk-from-pakistan-2026';

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
                'title' => 'How Do I Get a Cleaner Job in the UK From Pakistan?',
                'excerpt' => 'You cannot get a UK cleaner job directly from Pakistan on a work visa. See which visas already allow cleaning work, the steps to apply, pay rules and the scams to avoid.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-cleaner-job-uk-from-pakistan.jpg',
                'tags' => 'cleaner job uk from pakistan, uk cleaner visa, cleaning jobs uk for foreigners, student visa cleaner job, uk cleaner salary, graduate visa work, uk visa sponsorship scams, janitor job uk',
                'meta_title' => 'How to Get a Cleaner Job in the UK From Pakistan',
                'meta_description' => 'Can Pakistanis get a UK cleaner job in 2026? The visas that already allow cleaning work, the steps to apply, pay rules and scams to avoid.',
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
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Cleaning and Facilities Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-cleaning-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        Job::updateOrCreate(
            ['position' => 'Cleaner — UK Employers (From Pakistan: Existing Right to Work)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Find a job is the UK Government's official job search. If you already live in the UK with permission to work, it lists cleaner vacancies from UK employers, with the pay and hours set out in each advert.</p>

<h3>Requirements</h3>
<ul>
    <li>The right to work in the UK, which the employer must check; a visitor visa does not allow work</li>
    <li>Student visa holders may work only as employees and within their weekly hour limit</li>
    <li>No UK employer or agency may charge you a fee for finding work</li>
</ul>

<p><strong>Note:</strong> visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>
JOBHTML,
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Set by each employer, often early mornings or evenings',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => 'https://findajob.dwp.gov.uk/',
                'meta_description' => 'Cleaner vacancies from UK employers on the UK Government Find a job service, for people who already have the right to work.',
                'seo_keywords' => 'cleaner job uk from pakistan, uk cleaning jobs, cleaner vacancies uk, find a job uk',
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>You cannot get a UK cleaner job directly from Pakistan on a work visa.</strong> Cleaning is a lower-skilled job that UK employers cannot normally sponsor, and a visitor visa does not allow you to work. People from Pakistan who work as cleaners in the UK already have permission to work, for example on a Graduate, family or limited Student visa.</p>

<h2>Is There a UK Work Visa for Cleaners?</h2>

<p>No. Since 22 July 2025 the Skilled Worker visa needs a degree-level job (RQF Level 6) or a role on a short shortage list, plus a salary of at least £41,700 a year and English at CEFR B2. Cleaning jobs fall well below that standard. The other routes do not help either:</p>

<ul>
    <li><strong>Visitor visa:</strong> you cannot work on it.</li>
    <li><strong>Seasonal Worker visa:</strong> limited to farm and horticulture work.</li>
    <li><strong>Youth Mobility Scheme:</strong> only for listed countries, and Pakistan is not one of them.</li>
</ul>

<p>The same rules apply to similar jobs. For the full explanation see <a href="/blog/warehouse-operative-jobs-uk-visa-sponsorship-2026">Warehouse Operative Jobs in the UK With Visa Sponsorship (2026)</a>.</p>

<h2>How Do Pakistanis Legally Work as Cleaners in the UK?</h2>

<p>They hold UK permission that already includes the right to work. The common cases:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Status</th>
            <th style="padding:10px;text-align:left;">Can you work as a cleaner?</th>
            <th style="padding:10px;text-align:left;">Things to know</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Graduate visa</strong></td><td style="padding:10px;">Yes, in most jobs including full time</td><td style="padding:10px;">For graduates of UK degrees</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Family visa</strong></td><td style="padding:10px;">Generally yes</td><td style="padding:10px;">For partners and family of British or settled people</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Student visa</strong></td><td style="padding:10px;">Yes, within limits</td><td style="padding:10px;">Usually 20 hours a week in term time on degree-level courses (10 below degree level), full time in vacations. You cannot take a permanent full-time job or be self-employed.</td></tr>
    </tbody>
</table>
</div>

<p><strong>Warning:</strong> Student visa holders cannot work as self-employed cleaners, such as taking private cleaning jobs through apps. Work only as an employee within your hours limit, and never take a student course just to work.</p>

<h2>What Are the Steps to Get a Cleaner Job in the UK?</h2>

<ol>
    <li>Confirm your right to work. Most visa holders prove it with a share code from their online GOV.UK immigration account. Check your weekly hour limits.</li>
    <li>Prepare a short CV. Include any cleaning, hospitality or housekeeping experience and your availability.</li>
    <li>Search real vacancies on the government's Find a job service, recruitment agency websites and the career pages of cleaning and facilities companies.</li>
    <li>Apply and attend the interview or induction. Employers must check your right to work before you start.</li>
    <li>Complete training and checks. Some jobs, such as work in schools or care settings, may need a background check.</li>
</ol>

<h2>Can You Get a Cleaner Job in the UK Without Experience?</h2>

<p>Yes. Entry-level cleaning jobs usually train new starters. What helps most:</p>

<ul>
    <li>Reliability and punctuality, since many shifts are early mornings or evenings.</li>
    <li>Basic English to follow safety instructions and talk to supervisors.</li>
    <li>Physical fitness for being on your feet and lifting equipment.</li>
    <li>Flexibility with shifts, including part-time and weekend work.</li>
</ul>

<h2>How Much Does a Cleaner Earn in the UK?</h2>

<p>The legal minimum is the National Living Wage of £12.71 an hour for workers aged 21 and over from April 2026. Each advert states its own rate, and some sites pay more for overtime and weekends. As a worked example, a student working the 20-hour limit at the legal minimum earns about £254 a week before tax; at an example rate of £13 an hour that is £260 a week. At an illustrative PKR 375 to the pound, £254 is about PKR 95,300 a week. Check the exact rate in every advert.</p>

<h2>How Do You Avoid UK Cleaner Job and Visa Scams?</h2>

<ul>
    <li>Any fee for a job, "work permit" or "sponsorship". Cleaner jobs cannot be sponsored in the usual way.</li>
    <li>"Guaranteed visa" cleaner jobs from agents who ask for money first.</li>
    <li>Offers made only through WhatsApp, Telegram or social media.</li>
    <li>Jobs with no named company, address or interview.</li>
    <li>Requests to send money to a personal account.</li>
</ul>

<p>Verify the employer's real website, address and phone number. Report UK fraud through the UK police fraud reporting service, and in Pakistan report suspicious agents to the Federal Investigation Agency (FIA).</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can Pakistanis get a UK work visa as cleaners?</h3>
<p>No standard work visa covers cleaning jobs. Pakistanis who do this work usually hold another visa that already allows work, such as a Graduate or family visa.</p>

<h3>Can I get a cleaner job in the UK without experience?</h3>
<p>Yes, many employers train new cleaners. The challenge for people abroad is the legal right to work, not experience.</p>

<h3>Can I work as a cleaner on a student visa?</h3>
<p>Yes, as an employee within your visa's hour limits, usually 20 hours a week in term time for degree-level courses. You cannot be self-employed or take a permanent full-time role.</p>

<h3>Should I pay an agent for a UK cleaner visa?</h3>
<p>No. A job that cannot be sponsored cannot be sold to you legitimately. Treat any paid offer as a scam.</p>

<h3>Can I work on a visitor visa?</h3>
<p>No. A visitor visa does not allow you to work, and working on it can lead to a ban.</p>

<h3>What is the minimum pay for a cleaner in the UK?</h3>
<p>The National Living Wage, £12.71 an hour for workers aged 21 and over from April 2026.</p>

<h3>Is the Youth Mobility Scheme open to Pakistanis?</h3>
<p>No. Pakistan is not on the list of eligible countries.</p>

<h3>How do I prove my right to work to an employer?</h3>
<p>Most visa holders generate a share code from their online GOV.UK immigration account, and the employer checks it.</p>

<h2>People Also Search For</h2>

<h3>Cleaner job UK from Pakistan</h3>
<p>Only with an existing right to work.</p>

<h3>UK cleaner visa sponsorship</h3>
<p>Generally not available.</p>

<h3>Cleaning jobs UK for foreigners</h3>
<p>Open to people who already have the right to work.</p>

<h3>Student visa work hours</h3>
<p>Usually 20 hours a week at degree level, 10 below.</p>

<h3>UK cleaner salary</h3>
<p>At least £12.71 an hour at age 21 and over.</p>

<h3>Graduate visa work rights</h3>
<p>Allows most jobs, including full time.</p>

<h3>Self-employed cleaner on a student visa</h3>
<p>Not allowed.</p>

<h3>UK visa sponsorship scams</h3>
<p>Any fee for sponsorship is a warning sign.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/warehouse-operative-jobs-uk-visa-sponsorship-2026">Warehouse Operative Jobs in the UK With Visa Sponsorship (2026)</a> &mdash; the same rules for warehouse work.</li>
    <li><a href="/blog/kitchen-porter-jobs-uk-visa-sponsorship-2026">Kitchen Porter Jobs in the UK With Visa Sponsorship (2026)</a> &mdash; another lower-skilled role.</li>
    <li><a href="/blog/hotel-housekeeper-jobs-usa-visa-sponsorship-2026">Hotel Housekeeper Jobs in the USA With Visa Sponsorship (2026)</a> &mdash; the US route for comparison.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Rules were checked against GOV.UK on 9 October 2026 and change often, so confirm them on GOV.UK before you act or pay anyone.</p>
HTML;
    }
}
