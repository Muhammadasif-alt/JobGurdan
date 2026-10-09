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
 * "Can a Foreigner Get an HGV Driver Job in the UK With Visa Sponsorship in
 * 2026?"
 *
 * Checked on 9 October 2026 against the GOV.UK Skilled Worker rules (22 July
 * 2025 changes, GBP 41,700 general threshold, CEFR B2 from 8 January 2026) and
 * the Temporary Shortage List, where HGV drivers (SOC 8211) do not appear.
 *
 * What changed against the brief:
 *
 * 1. "The Home Office said in 2021 that HGV driving could not be sponsored" and
 *    "only for EU, EEA or Swiss licence holders" are dropped; neither could be
 *    confirmed. The guide says only that the 2021 scheme was a one-off with
 *    4,700 places for food haulage drivers and has closed.
 * 2. The recruiter-ad pay ranges are not republished. Pay sits in the HGV
 *    salary guide, which uses labelled examples.
 * 3. Management code 1140 and the 1242 warehouse-manager row are dropped;
 *    only 1241, 1243 and 5231 are named, and the reader is told to check the
 *    live list.
 * 4. The brief's pointer to a warehouse-from-Pakistan guide is not used; that
 *    page does not exist.
 * 5. The Apply Now buttons are gone; the one official link sits on the job
 *    listing.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class HgvDriverJobsUkVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'hgv-driver-jobs-uk-visa-sponsorship-2026';

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
                'title' => 'Can a Foreigner Get an HGV Driver Job in the UK With Visa Sponsorship in 2026?',
                'excerpt' => 'Generally no. Since 22 July 2025 the Skilled Worker visa covers mainly degree-level and shortage roles, and HGV driver is not on the shortage list. See the rules, licences needed, sponsored transport roles and scams.',
                'content' => $content,
                'featured_image' => 'blogs/hgv-driver-jobs-uk-visa-sponsorship-poster.jpg',
                'tags' => 'hgv driver jobs uk, hgv visa sponsorship, uk lorry driver visa, skilled worker visa hgv, hgv driver from pakistan, hgv licence uk, uk visa sponsorship scams, class 1 driver jobs',
                'meta_title' => 'HGV Driver Jobs in the UK With Visa Sponsorship (2026)',
                'meta_description' => 'Can foreigners get a UK HGV driver job with visa sponsorship in 2026? The Skilled Worker rules, licences needed, sponsored transport roles and scams.',
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
            ['position' => 'HGV Driver — UK Employers (Right to Work Needed)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Find a job is the UK Government's official job search. It lists HGV and driving vacancies from UK employers, with the pay and location set out in each advert.</p>

<h3>Requirements</h3>
<ul>
    <li>The right to work in the UK: HGV driver is not on the Skilled Worker shortage list, so a sponsored visa is not normally available</li>
    <li>A UK HGV licence (Category C or C+E), Driver CPC and a digital tachograph card; a foreign licence alone does not let you start</li>
    <li>No UK employer or agency may charge you a fee for finding work</li>
</ul>

<p><strong>Note:</strong> visa and licensing rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>
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
                'meta_description' => 'HGV and driving vacancies from UK employers on the UK Government Find a job service. The right to work and a UK HGV licence are needed.',
                'seo_keywords' => 'hgv driver jobs uk, uk lorry driver jobs, hgv visa sponsorship, find a job uk',
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Generally no.</strong> Since 22 July 2025 the Skilled Worker visa covers only roles at degree level (RQF Level 6) or a few listed shortage roles, and HGV driver is not one of them. A legitimate UK employer cannot normally sponsor you as a lorry driver, so any agent selling a "sponsored HGV driver visa" is very likely a scam.</p>

<h2>Why Can't HGV Drivers Get a Skilled Worker Visa?</h2>

<p>The Skilled Worker visa is the UK's main route for sponsored jobs. GOV.UK sets out each occupation code and whether it can be sponsored. HGV drivers sit under occupation code 8211, and the rules changed on 22 July 2025:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Job skill level</th>
            <th style="padding:10px;text-align:left;">Can it be sponsored?</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>RQF Level 6 (degree level)</strong></td><td style="padding:10px;">Yes, if the salary, English and sponsor rules are met</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>RQF Levels 3 to 5</strong></td><td style="padding:10px;">Only if the job is on the Temporary Shortage List or the Immigration Salary List</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Below RQF Level 3</strong></td><td style="padding:10px;">No</td></tr>
    </tbody>
</table>
</div>

<p>The general salary requirement is £41,700 a year (or the going rate, if higher), with English at CEFR B2 from 8 January 2026. HGV driver (8211) does not appear on the Temporary Shortage List, so it cannot be used for a new sponsorship. Check the code on the live GOV.UK list before acting on any offer.</p>

<h2>What Happened With the Temporary HGV Visas?</h2>

<p>In autumn 2021 the government ran a one-off temporary scheme with 4,700 places for HGV drivers carrying food. It has closed, and nothing like it is open to Pakistani drivers today.</p>

<p><strong>Beware of old articles.</strong> Some blogs and job adverts still say "HGV drivers can get Skilled Worker sponsorship." The rules changed in 2025, and these claims are often used by scammers.</p>

<h2>Which Transport Jobs Can Be Sponsored?</h2>

<p>Driving itself is not sponsored, but a few roles in the same industry can be. Each still needs a licensed sponsor, the salary threshold and English at B2, and the live list may change:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Role (code)</th>
            <th style="padding:10px;text-align:left;">Note</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Managers in transport and distribution (1241)</strong></td><td style="padding:10px;">Depot, fleet and dispatch managers; needs management experience</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Managers in logistics (1243)</strong></td><td style="padding:10px;">Appears on the Temporary Shortage List</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Vehicle technicians, mechanics and electricians (5231)</strong></td><td style="padding:10px;">Appears on the Temporary Shortage List</td></tr>
    </tbody>
</table>
</div>

<h2>What Licence Do You Need to Drive an HGV in the UK?</h2>

<ul>
    <li>Category C (Class 2): rigid lorries over 3.5 tonnes.</li>
    <li>Category C+E (Class 1): articulated lorries and trailers.</li>
    <li>Driver CPC (Certificate of Professional Competence) and a digital tachograph card.</li>
</ul>

<p>The usual path is a full UK car licence, then provisional HGV entitlement, theory and practical tests, and Driver CPC training. You must be living in the UK with the right to work to do this, so a foreign licence alone does not let you start.</p>

<h2>How Much Do HGV Drivers Earn in the UK?</h2>

<p>Pay depends mainly on licence class, shift and employer, and every advert states its own rate. Class 1 drivers are generally paid more than Class 2, and nights and weekends often pay extra. The pay guide shows a worked example and the tax: <a href="/blog/hgv-driver-salary-uk-2026">HGV Driver Salary in the UK (2026)</a>.</p>

<p><strong>Pakistan note:</strong> there is no sponsorship route for Pakistani HGV drivers. Pakistani nationals who already hold UK permission to work, such as a Graduate or family visa, can train for a UK HGV licence and apply for driving jobs. The step-by-step is in <a href="/blog/how-to-get-hgv-driver-job-uk-from-pakistan-2026">How to Get an HGV Driver Job in the UK From Pakistan</a>.</p>

<h2>How Do You Avoid HGV Visa Sponsorship Scams?</h2>

<ul>
    <li>Any fee for a "Certificate of Sponsorship" or job placement. Sponsors should not charge workers.</li>
    <li>"Guaranteed HGV visa" promises from agents who ask for money first.</li>
    <li>Employers that are not on the Register of Licensed Sponsors.</li>
    <li>Offers made only through WhatsApp, Telegram or social media.</li>
    <li>Fake job adverts with no company address.</li>
</ul>

<p>Report UK fraud to the police fraud reporting service. In Pakistan, you can report suspicious agents to the Federal Investigation Agency (FIA). Never pay for a job offer.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/transport-logistics" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Transport &amp; Logistics Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is HGV driver on the UK shortage occupation list?</h3>
<p>No. HGV driver (8211) does not appear on the Temporary Shortage List for new Skilled Worker sponsorship. Check the code on the live GOV.UK list for the latest.</p>

<h3>Can I get a Skilled Worker visa as a lorry driver?</h3>
<p>Generally no. HGV driving is not currently eligible, so legitimate UK employers cannot normally sponsor it.</p>

<h3>Can I convert a Pakistani driving licence to drive an HGV in the UK?</h3>
<p>No. You need UK HGV entitlement, Driver CPC and the legal right to work. Start with the process on GOV.UK.</p>

<h3>Is there a visa scheme for HGV drivers?</h3>
<p>Not now. The 2021 scheme for food haulage drivers was a one-off and has closed.</p>

<h3>Which licence do I need for an articulated lorry?</h3>
<p>Category C+E (Class 1), together with Driver CPC and a digital tachograph card.</p>

<h3>Can I work as an HGV driver on a student visa?</h3>
<p>Only within the visa's hour limits, and you still need a UK HGV licence. A permanent full-time driving job is not allowed.</p>

<h3>Should I pay an agent for an HGV driver visa?</h3>
<p>No. A job that cannot be sponsored cannot be sold to you legitimately. Treat any paid offer as a scam.</p>

<h3>How much does an HGV driver earn in the UK?</h3>
<p>It depends on licence class, shift and employer, and each advert states the rate. See our <a href="/blog/hgv-driver-salary-uk-2026">HGV salary guide</a> for a worked example.</p>

<h2>People Also Search For</h2>

<h3>HGV driver jobs UK visa sponsorship</h3>
<p>Generally not available for lorry drivers.</p>

<h3>HGV driver Skilled Worker visa</h3>
<p>The role is not on the Temporary Shortage List.</p>

<h3>UK lorry driver visa</h3>
<p>There is no open visa scheme.</p>

<h3>HGV driver salary UK</h3>
<p>Set by each employer; see the pay guide.</p>

<h3>HGV driver job from Pakistan</h3>
<p>Only with an existing right to work.</p>

<h3>HGV licence UK</h3>
<p>Category C or C+E, plus Driver CPC.</p>

<h3>Register of Licensed Sponsors</h3>
<p>The official GOV.UK list of employers that can sponsor.</p>

<h3>Truck driver jobs in USA</h3>
<p>The US has different licence and visa rules, covered in our pay guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/hgv-driver-salary-uk-2026">HGV Driver Salary in the UK (2026)</a> &mdash; pay, tax and PKR examples.</li>
    <li><a href="/blog/how-to-get-hgv-driver-job-uk-from-pakistan-2026">How to Get an HGV Driver Job in the UK From Pakistan (2026)</a> &mdash; the legal route step by step.</li>
    <li><a href="/blog/truck-driver-salary-usa-2026">Truck Driver Salary in the USA (2026)</a> &mdash; the US picture for comparison.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Rules were checked against GOV.UK on 9 October 2026 and change often, so confirm them on GOV.UK before you act or pay anyone.</p>
HTML;
    }
}
