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
 * "Warehouse Worker Jobs in the USA With Visa Sponsorship (2026)".
 *
 * What changed against the brief:
 *
 * 1. The brief says Pakistan is not on a DHS list of H-2B eligible countries.
 *    That is out of date: the DHS rule that took effect on 17 January 2025
 *    removed the eligible-country lists, so a nationality list no longer
 *    decides petition eligibility. The guide says that, and points to the
 *    embassy for the consular position without promising a visa. The same
 *    correction runs through the warehouse salary and Pakistan guides.
 *
 * 2. "Covers up to 3 years" is tightened: stays are granted in steps with a
 *    three-year maximum.
 *
 * 3. "Congress sometimes releases extra visas" is corrected: DHS and DOL issue
 *    the supplemental allocations, and the FY2026 one stopped taking petitions
 *    after 15 September 2026.
 *
 * 4. Pay is given from the BLS API (May 2025, occupation 53-7062) and the
 *    brief's "typically $17 to $19" is replaced with those figures. No salary
 *    goes on the job listing.
 *
 * 5. The Apply Now buttons are gone; the single official link sits on the job
 *    listing. The J-1 row is dropped; it fits trainees and students, not
 *    general warehouse hiring.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class WarehouseWorkerJobsUsaVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'warehouse-worker-jobs-usa-visa-sponsorship-2026';

    private const APPLY_URL = 'https://seasonaljobs.dol.gov/';

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
                'title' => 'Can a Foreigner Get a Warehouse Worker Job in the USA With Visa Sponsorship in 2026?',
                'excerpt' => 'Yes, but only through limited routes. The H-2B temporary visa covers seasonal or peak-load warehouse work; a permanent EB-3 green card is rare and slow. Here are the requirements, the cap position, pay and how to avoid scams.',
                'content' => $content,
                'featured_image' => 'blogs/warehouse-worker-jobs-usa-visa-sponsorship-poster.jpg',
                'tags' => 'warehouse worker jobs usa, warehouse jobs visa sponsorship, h-2b warehouse jobs, warehouse worker visa usa, material mover jobs usa, eb-3 other workers, warehouse jobs for foreigners, warehouse jobs from pakistan',
                'meta_title' => 'Warehouse Worker Jobs in USA With Visa Sponsorship (2026)',
                'meta_description' => 'Warehouse worker jobs in the USA with visa sponsorship: H-2B and EB-3 routes, requirements, the cap position, pay and how to avoid job scams.',
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
            ['name' => 'US Warehouse and Logistics Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'usa-warehouse-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            ['position' => 'Warehouse Worker — US Employers (H-2B Job Orders)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Seasonal or peak-load, set by each job order',
                'language' => 'English',
                // Pay is set per job order under the H-2B wage rules; this site
                // does not republish job-board rates.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Seasonal and peak-load warehouse job orders on the US Department of Labor portal. Sponsorship runs through the H-2B visa and the employer must file first.',
                'seo_keywords' => 'warehouse worker jobs usa, h-2b job orders, seasonal warehouse jobs usa, warehouse visa sponsorship',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>The US Department of Labor's seasonal jobs portal lists job orders from employers who plan to hire temporary workers, including warehouse and logistics employers hiring under the H-2B programme.</p>

<h3>Requirements</h3>
<ul>
    <li>Each job order states its own duties, dates, location, pay and DOL case number; read it before contacting the employer</li>
    <li>The employer must obtain labor certification and petition USCIS before you can apply for the visa, and H-2B has an annual numerical limit</li>
    <li>An H-2B worker must not be charged recruitment or visa fees by the employer or a recruiter, and the work is temporary</li>
</ul>

<p><strong>Note:</strong> visa and wage rules are set by the US Government, not by JobGader. This link opens the official listing portal; it is not an application form, and a visible order does not prove recruitment is still open.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Yes, but only through limited routes. The main one is the <strong>H-2B temporary visa</strong>, which covers seasonal or peak-load warehouse work. Permanent sponsorship through an EB-3 green card is possible but rare and slow. Legitimate employers never charge workers for the job or the visa. The visa rules come from the US Department of Labor's H-2B programme and the USCIS H-2B pages.</p>

<h2>Which Visa Can a Warehouse Worker Get for the USA?</h2>

<p>Warehouse jobs are classed as low-skilled work, so most skilled-worker visas, such as the H-1B, do not apply. Two routes exist:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Visa</th>
            <th style="padding:10px;text-align:left;">Type</th>
            <th style="padding:10px;text-align:left;">Best for</th>
            <th style="padding:10px;text-align:left;">Reality check</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>H-2B</strong></td><td style="padding:10px;">Temporary, non-farm</td><td style="padding:10px;">Seasonal or peak-load warehouse work</td><td style="padding:10px;">The most realistic route</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>EB-3 "Other Workers"</strong></td><td style="padding:10px;">Permanent (green card)</td><td style="padding:10px;">Long-term, year-round roles</td><td style="padding:10px;">Rare, with multi-year backlogs</td></tr>
    </tbody>
</table>
</div>

<p>For nearly everyone, H-2B is the real option.</p>

<h2>How Does the H-2B Visa Work for Warehouse Jobs?</h2>

<p>H-2B lets a US employer hire foreign workers for temporary non-agricultural jobs when it cannot find enough US workers. The need must be seasonal, a peak load, intermittent or a one-time occurrence.</p>

<ol>
    <li>The employer obtains Department of Labor certification, which shows that the job is temporary and that it tried to hire US workers first.</li>
    <li>The employer files Form I-129 with USCIS to petition for you.</li>
    <li>You apply for the visa at a US embassy or consulate, with the DS-160 form and an interview.</li>
    <li>You enter the US and work only for the sponsoring employer.</li>
</ol>

<p>The annual cap is 66,000 new H-2B workers, split into 33,000 for each half of the fiscal year. Extra supplemental visas are issued by DHS and the Department of Labor in some years, and for fiscal year 2026 USCIS stopped accepting petitions under the supplemental allocation after 15 September 2026, so check USCIS for the current year's position. Stays are granted in steps, up to a maximum of three years.</p>

<h2>Is Pakistan Eligible for the H-2B Visa?</h2>

<p>The old rule was that H-2B was open only to nationals of countries on a list that DHS published each year, and Pakistan was not on it. That has changed. A DHS rule that took effect on 17 January 2025 removed the eligible-country lists, so a nationality list no longer decides whether an employer can file a petition.</p>

<p>That does not guarantee a Pakistani applicant a job or a visa. You still need an employer that recruits from Pakistan, an approved petition and a visa decision, and the State Department's country pages can still carry older wording. Check the current instructions of the US Embassy in Pakistan, and any US entry restrictions in force, before you spend money. The full process for Pakistani applicants is in <a href="/blog/how-to-get-warehouse-worker-job-usa-from-pakistan-2026">How to Get a Warehouse Worker Job in USA From Pakistan</a>.</p>

<h2>What Do Warehouse Workers Earn in the USA?</h2>

<p>The Bureau of Labor Statistics (BLS) May 2025 estimates for "laborers and freight, stock and material movers, hand" give a median of $19.35 an hour and $40,240 a year, with a mean of $20.32 an hour. Pay is higher in states with higher minimum wages, and overtime may apply after 40 hours a week.</p>

<p>H-2B employers must pay at least the wage the Department of Labor requires for the job and location, and the rate is stated on the job order. The full breakdown, with the range from the lowest to the highest earners and PKR examples, is in <a href="/blog/warehouse-worker-salary-usa-2026">Warehouse Worker Salary in USA (2026)</a>.</p>

<h2>What Are the Requirements for a Warehouse Job With Visa Sponsorship?</h2>

<ul>
    <li>A job offer from a US employer willing to file the petition.</li>
    <li>A valid passport, ideally valid for your whole stay.</li>
    <li>Physical ability to lift (often 40 to 50 lbs), stand for long shifts and work in warehouse conditions.</li>
    <li>Basic English for safety instructions.</li>
    <li>A clean record for the visa interview and background checks.</li>
    <li>Forklift or inventory experience is a bonus, not usually a requirement.</li>
</ul>

<p>No degree is needed. Employers set the details, so read each job order carefully.</p>

<h2>How Do You Apply for a Warehouse Job With Visa Sponsorship?</h2>

<ol>
    <li>Search real job orders on the Department of Labor's seasonal jobs portal for "warehouse", "material mover" or "laborer".</li>
    <li>Note the employer's name, the DOL case number and the pay rate. A real H-2B job has a case number, often starting with H-400.</li>
    <li>Apply using the contact details in the listing, and never use a middleman who asks for money.</li>
    <li>Prepare your documents: passport, updated resume and any forklift or inventory certificates.</li>
    <li>Apply early, because employers file months before the start date and the cap often fills fast.</li>
</ol>

<h2>How Do You Avoid Visa Sponsorship Job Scams?</h2>

<p>Fake "warehouse job with visa" offers are common. Watch for these signs:</p>

<ul>
    <li>Any fee from you. Under the H-2B rules, employers and recruiters may not charge workers recruitment or visa fees.</li>
    <li>A job guaranteed with no interview.</li>
    <li>Contact only through WhatsApp, Telegram or a free email address.</li>
    <li>No named employer or no DOL case number.</li>
    <li>Pressure to pay quickly to "hold" the position.</li>
</ul>

<p>Verify an offer by finding the employer's real website, calling its published number and matching the case number with Department of Labor records. Report scams to the US Federal Trade Commission, or to the Federal Investigation Agency if you are in Pakistan.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a green card through a warehouse job?</h3>
<p>Possibly through EB-3 "Other Workers", but the employer must complete a PERM labor certification and the queue often runs many years. Few warehouse employers sponsor this way.</p>

<h3>Can I bring my family on an H-2B visa?</h3>
<p>Spouses and unmarried children under 21 may apply for H-4 visas, but they generally cannot work.</p>

<h3>Can I change employers on an H-2B visa?</h3>
<p>Only through a new employer's own petition for you. You cannot simply switch jobs, so check the current portability rules.</p>

<h3>Is it possible to apply from Pakistan?</h3>
<p>Yes, there is no longer a country list at the petition stage, but you need an employer that recruits from Pakistan and a visa decision. Check the embassy's current instructions.</p>

<h3>Do I need experience to work in a US warehouse?</h3>
<p>Usually not. Most hand-labour roles train new hires on the job.</p>

<h3>How long can I stay on an H-2B visa?</h3>
<p>Stays are granted in steps, up to a three-year maximum, and the work is temporary.</p>

<h3>Is there a limit on H-2B visas?</h3>
<p>Yes, 66,000 new workers a year, with supplemental allocations in some years. Check USCIS's current notices.</p>

<h3>Should I pay an agent to get a warehouse visa?</h3>
<p>No. H-2B workers must not be charged recruitment or visa fees, and nobody can guarantee a visa.</p>

<h2>People Also Search For</h2>

<h3>Warehouse worker jobs in USA with visa sponsorship</h3>
<p>Seasonal and peak-load work sponsored through the H-2B visa.</p>

<h3>H-2B warehouse jobs</h3>
<p>Temporary non-agricultural work, subject to an annual limit.</p>

<h3>Warehouse jobs for foreigners in USA</h3>
<p>Open to foreign workers only through an eligible visa route.</p>

<h3>EB-3 other workers</h3>
<p>A permanent route that is rare and slow for warehouse roles.</p>

<h3>Material mover jobs USA</h3>
<p>The BLS groups them with hand laborers and stock movers.</p>

<h3>Warehouse job from Pakistan</h3>
<p>Possible only where an employer recruits in Pakistan and the visa is approved.</p>

<h3>Warehouse worker salary USA</h3>
<p>$19.35 an hour at the median in the BLS May 2025 estimates.</p>

<h3>H-2B visa cap</h3>
<p>66,000 new workers a year, with supplemental allocations in some years.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/warehouse-worker-salary-usa-2026">Warehouse Worker Salary in USA (2026)</a> &mdash; the BLS pay breakdown.</li>
    <li><a href="/blog/how-to-get-warehouse-worker-job-usa-from-pakistan-2026">How to Get a Warehouse Worker Job in USA From Pakistan</a> &mdash; the step-by-step process.</li>
    <li><a href="/blog/landscaper-jobs-usa-visa-sponsorship-2026">Landscaper Jobs in USA With Visa Sponsorship (2026)</a> &mdash; the same H-2B route for outdoor work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Wage figures are from the US Bureau of Labor Statistics, May 2025 national estimates; visa rules are drawn from the US Department of Labor, USCIS and the Department of Homeland Security, reviewed on 8 October 2026. It does not confirm cap availability or that a visa will be issued to any individual applicant.</p>
HTML;
    }
}
