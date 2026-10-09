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
 * "How Do I Get an HGV Driver Job in the UK From Pakistan?"
 *
 * Checked on 9 October 2026 against the GOV.UK Skilled Worker pages and the
 * Student visa work rules (20 hours a week in term time at degree level, 10
 * below). What changed against the brief:
 *
 * 1. "No more than six penalty points" and the "GBP 35,000 to GBP 40,000"
 *    typical pay line are dropped; neither could be confirmed. Pay goes to the
 *    HGV salary guide.
 * 2. The Skilled Worker row now says the right to work is tied to the sponsor,
 *    with no promise about exceptions.
 * 3. The Apply Now buttons are gone; the one official link sits on the job
 *    listing.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class HgvDriverJobUkFromPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-get-hgv-driver-job-uk-from-pakistan-2026';

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
                'title' => 'How Do I Get an HGV Driver Job in the UK From Pakistan?',
                'excerpt' => 'You cannot get a UK HGV driver job directly from Pakistan. No standard work visa sponsors the role and you need a UK HGV licence. See the statuses that allow driving work, the licence steps and scams.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-hgv-driver-job-uk-from-pakistan-poster.jpg',
                'tags' => 'hgv driver job uk from pakistan, pakistani hgv driver uk, hgv licence uk, driver cpc, uk lorry driver from pakistan, hgv visa pakistan, uk visa sponsorship scams, class 1 driver jobs',
                'meta_title' => 'How to Get an HGV Driver Job in the UK From Pakistan',
                'meta_description' => 'Can Pakistanis get a UK HGV driver job in 2026? The statuses that allow driving work, the licence steps, what employers want and scams to avoid.',
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
            ['position' => 'HGV Driver — UK Employers (From Pakistan: Existing Right to Work)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Find a job is the UK Government's official job search. If you already live in the UK with permission to work, it lists HGV vacancies from UK employers.</p>

<h3>Requirements</h3>
<ul>
    <li>The right to work in the UK, which the employer must check; a visitor visa does not allow work</li>
    <li>A UK HGV licence (Category C or C+E) and Driver CPC; a Pakistani licence cannot be used to drive an HGV in the UK</li>
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
                'meta_description' => 'HGV vacancies from UK employers on the UK Government Find a job service, for people who already have the right to work and a UK HGV licence.',
                'seo_keywords' => 'hgv driver job uk from pakistan, uk lorry driver jobs, hgv licence uk, find a job uk',
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>You cannot get a UK HGV driver job directly from Pakistan.</strong> No standard work visa sponsors the role, and you need a UK HGV licence, which means living in the UK with the legal right to work first. The realistic path is to gain UK work permission for another reason, then qualify for an HGV licence and Driver CPC and apply for jobs.</p>

<h2>Is There a UK Work Visa for HGV Drivers?</h2>

<p>Generally no. Since 22 July 2025 the Skilled Worker visa mainly covers degree-level jobs (RQF Level 6) and a few shortage roles, and HGV driver (occupation code 8211) is not on the Temporary Shortage List. A one-off 2021 scheme for food haulage drivers has closed. Read the full explanation in <a href="/blog/hgv-driver-jobs-uk-visa-sponsorship-2026">HGV Driver Jobs in the UK With Visa Sponsorship (2026)</a>, and check the code on GOV.UK before acting on any offer.</p>

<h2>How Do Pakistanis Legally Work as HGV Drivers in the UK?</h2>

<p>They first hold UK permission that already includes the right to work. The common cases:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Status</th>
            <th style="padding:10px;text-align:left;">Can you drive an HGV for work?</th>
            <th style="padding:10px;text-align:left;">Things to know</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Graduate visa</strong></td><td style="padding:10px;">Yes, in most jobs including full time</td><td style="padding:10px;">For graduates of UK degrees</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Family visa</strong></td><td style="padding:10px;">Generally yes</td><td style="padding:10px;">For partners and family of British or settled people</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Student visa</strong></td><td style="padding:10px;">Only within work limits</td><td style="padding:10px;">Usually 20 hours a week in term time at degree level; you cannot take a permanent full-time job</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skilled Worker (another job)</strong></td><td style="padding:10px;">Generally only for your sponsor</td><td style="padding:10px;">You cannot freely switch to a driving job</td></tr>
    </tbody>
</table>
</div>

<p><strong>Warning:</strong> do not buy a visa or a course only to become a lorry driver. Breaking work conditions can lead to cancelled permission and refused future applications.</p>

<h2>What Are the Steps to Become an HGV Driver Once You Can Work in the UK?</h2>

<ol>
    <li>Get a full UK car licence. You need it before you can apply for provisional HGV entitlement.</li>
    <li>Apply for provisional HGV entitlement through the DVLA, including a medical exam.</li>
    <li>Pass the theory tests, which cover the HGV theory and Driver CPC modules.</li>
    <li>Pass the practical driving test for Category C (Class 2, rigid) or C+E (Class 1, articulated).</li>
    <li>Complete the Driver CPC (Certificate of Professional Competence), which is usually part of training.</li>
    <li>Get a digital tachograph card.</li>
    <li>Apply for jobs on the government's Find a job service, agency websites and employer pages.</li>
</ol>

<p>Many people train through an approved training provider or an employer academy. Check each step on GOV.UK, since rules and forms change.</p>

<h2>Can You Get an HGV Driver Job Without UK Experience?</h2>

<p>Sometimes. Some adverts say newly qualified drivers are considered, while others ask for experience. Newly qualified drivers are often paid less than experienced ones; the pay guide has worked examples: <a href="/blog/hgv-driver-salary-uk-2026">HGV Driver Salary in the UK (2026)</a>.</p>

<p>What employers look for:</p>

<ul>
    <li>A UK HGV licence (Category C or C+E) and Driver CPC.</li>
    <li>The legal right to work, checked by the employer.</li>
    <li>A clean driving record.</li>
    <li>Good English for safety, delivery paperwork and customers.</li>
    <li>Flexibility with shifts, including nights and weekends.</li>
</ul>

<h2>How Do You Avoid UK HGV Job and Visa Scams?</h2>

<ul>
    <li>Any fee for a "Certificate of Sponsorship", job offer or placement. Sponsors should not charge workers.</li>
    <li>"Visa, licence and job package" deals that promise everything for one payment.</li>
    <li>A guaranteed visa or job with no interview.</li>
    <li>Offers made only through WhatsApp, Telegram or social media.</li>
    <li>Employers not on the Register of Licensed Sponsors, when a job claims to offer sponsorship.</li>
</ul>

<p>Verify the employer's real website, address and phone number. Report UK fraud through the UK police fraud reporting service, and in Pakistan report suspicious agents to the Federal Investigation Agency (FIA).</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/transport-logistics" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Transport &amp; Logistics Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can Pakistanis get a UK work visa as HGV drivers?</h3>
<p>No standard visa covers HGV driving. Pakistanis who do this work usually have another status that already allows it, such as a Graduate or family visa.</p>

<h3>Can I use my Pakistani driving licence to drive a lorry in the UK?</h3>
<p>No. You need UK HGV entitlement and Driver CPC, and you must have the legal right to work. Start with the process on GOV.UK.</p>

<h3>Can I get an HGV driver job in the UK without experience?</h3>
<p>Some employers hire newly qualified drivers, but many prefer experience. The main barrier for people abroad is the legal right to work and the UK licence, not experience.</p>

<h3>Should I pay an agent for an HGV driver visa?</h3>
<p>No. A job that cannot be sold to you legitimately as sponsored should not be bought. Treat any paid offer as a scam.</p>

<h3>Can I drive an HGV on a visitor visa?</h3>
<p>No. A visitor visa does not allow you to work.</p>

<h3>Can a student visa holder drive an HGV for work?</h3>
<p>Only within the visa's hour limits, usually 20 hours a week in term time at degree level. A permanent full-time driving job is not allowed.</p>

<h3>What licence do I need for an articulated lorry?</h3>
<p>Category C+E (Class 1), together with Driver CPC and a digital tachograph card.</p>

<h3>How much does an HGV driver earn in the UK?</h3>
<p>It depends on licence class, shift and employer. See the <a href="/blog/hgv-driver-salary-uk-2026">HGV salary guide</a> for worked examples.</p>

<h2>People Also Search For</h2>

<h3>HGV driver job UK from Pakistan</h3>
<p>Only with an existing right to work.</p>

<h3>UK lorry driver visa</h3>
<p>There is no open visa scheme.</p>

<h3>HGV licence UK</h3>
<p>Category C or C+E, plus Driver CPC.</p>

<h3>Driver CPC</h3>
<p>The Certificate of Professional Competence needed for professional drivers.</p>

<h3>HGV driver visa sponsorship</h3>
<p>Generally not available.</p>

<h3>HGV driver salary UK</h3>
<p>Set by each employer; see the pay guide.</p>

<h3>Graduate visa work rights</h3>
<p>Allows most jobs, including full time.</p>

<h3>UK visa sponsorship scams</h3>
<p>Any fee for sponsorship is a warning sign.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/hgv-driver-jobs-uk-visa-sponsorship-2026">HGV Driver Jobs in the UK With Visa Sponsorship (2026)</a> &mdash; why it is generally not available.</li>
    <li><a href="/blog/hgv-driver-salary-uk-2026">HGV Driver Salary in the UK (2026)</a> &mdash; pay, tax and PKR examples.</li>
    <li><a href="/blog/warehouse-operative-jobs-uk-visa-sponsorship-2026">Warehouse Operative Jobs in the UK With Visa Sponsorship (2026)</a> &mdash; the same rules for warehouse work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Rules were checked against GOV.UK on 9 October 2026 and change often, so confirm them on GOV.UK before you act or pay anyone.</p>
HTML;
    }
}
