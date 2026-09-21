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
 * "How to Apply for BHP Mining Jobs in Australia" — an employer guide built
 * on BHP's own careers pages, with the Mining Industry Award and ABS earnings
 * data standing in for the pay BHP does not publish.
 *
 * Corrections to the draft (checked against bhp.com, careers.bhp.com,
 * fairwork.gov.au, abs.gov.au and jobsandskills.gov.au, September 2026):
 *
 * 1. The draft says more than 2,500 people have graduated from the FutureFit
 *    Academy. 2,500 is BHP's five-year target for new traineeships and
 *    apprenticeships nationally, not a graduate count. BHP's own published
 *    figures are more than 1,100 joined and more than 500 graduated.
 *
 * 2. The draft's pay figures are Glassdoor and SEEK estimates. BHP publishes
 *    no pay scale, so the guide uses the Mining Industry Award floor of
 *    $27.53 an hour and the ABS mining average of $3,224.20 a week instead.
 *
 * 3. The draft links a specific Olympic Dam vacancy. That posting now reads
 *    "Sorry, this position has been filled", so the guide links BHP's stable
 *    hubs instead.
 *
 * 4. The draft implies one BHP roster pattern. Rosters are role and site
 *    specific; the confirmed 7/7 pattern is the FutureFit training roster,
 *    not a company-wide standard.
 *
 * 5. The draft omits BHP's own fraudulent email scams warning, which is the
 *    most useful thing on BHP's careers site for an overseas reader.
 *
 * 6. Apprenticeship and traineeship lengths corrected: traineeships run
 *    12 months to a Certificate II, apprenticeships two years to a
 *    Certificate III, not the four years the draft states.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class BhpMiningJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.bhp.com/';

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
        $title = 'How to Apply for BHP Mining Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'BHP hires people with no mining experience through its FutureFit Academy and new-to-industry roles, paid and permanent from day one. It publishes no pay scale, so this guide prices the work from the Mining Award and ABS earnings data.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-bhp-mining-jobs-in-australia.jpg',
                'tags' => 'bhp jobs, mining jobs australia, bhp futurefit academy, entry level mining jobs, fifo jobs australia, olympic dam jobs, mining apprenticeships, mining industry award',
                'meta_title' => 'BHP Mining Jobs in Australia: Entry Routes and Real Pay',
                'meta_description' => 'BHP mining jobs: the no-experience pathways, what the FutureFit Academy actually offers, official pay benchmarks and how the application works.',
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
            ['name' => 'BHP Group, Australian Operations'],
            ['type' => 'Company', 'display_reference' => 'bhp-australia-operations']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Entry-Level Miner and Trade Apprentice, BHP Australian Operations',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Roster based and role specific, including FIFO swings and residential options',
                'language' => 'English',
                // BHP publishes no pay scale, so the guide uses the Mining
                // Industry Award floor and ABS earnings rather than a band.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Entry-level mining, traineeship and apprenticeship roles with BHP across Western Australia, South Australia and Queensland.',
                'seo_keywords' => 'bhp jobs, mining jobs australia, entry level mining jobs, fifo jobs, bhp futurefit academy',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>BHP recruits for its Australian iron ore, copper and coal operations, including entry routes for people with no mining background through its FutureFit Academy and site-based new-to-industry roles.</p>

<h3>What the work involves</h3>
<p>Operating haul trucks and production equipment, underground production and services work, fixed and mobile plant maintenance, and the trade apprenticeships that feed those teams.</p>

<h3>Common requirements</h3>
<ul>
    <li>No prior mining experience for the new-to-industry pathways</li>
    <li>A valid Australian driver licence, and for some roles the ability to drive a manual vehicle</li>
    <li>Physical fitness for lifting, pushing and pulling, and for working safely underground or at heights</li>
    <li>Willingness to work a roster, including fly-in fly-out swings</li>
    <li>The right to work in Australia</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, rosters and eligibility are set by BHP, and minimum rates are set by the Fair Work Commission &mdash; not by JobGader. BHP states it never seeks any funds from job applicants at any stage of recruitment.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>BHP runs real entry routes for people with no mining experience, and on all of them you are a paid, permanent employee from day one.</strong> Search and apply on BHP's own careers portal, choose between the FutureFit Academy, a site-based apprenticeship or traineeship, and a new-to-industry role.</p>

<p>One thing to fix before anything else: BHP does not publish a pay scale for any of these jobs, so every salary figure you have read about BHP comes from a job board's estimate. This guide prices the work from the sources that do publish.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.bhp.com/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9935; Search BHP Jobs &rarr;
    </a>
</div>

<h2>The Three Routes In With No Experience</h2>

<p>BHP publishes exactly three new-to-industry pathways, and they are genuinely different from one another:</p>

<ul>
    <li><strong>The FutureFit Academy.</strong> Accelerated classroom and workshop learning at a dedicated academy, then deployment to a site. Best if you want a recognised qualification.</li>
    <li><strong>Site-based apprenticeships and traineeships.</strong> A traditional trade qualification learned on the job at an operating site.</li>
    <li><strong>New-to-industry roles.</strong> Site-based from the start, with all training on the job. Best if you want to be earning at full site rates soonest.</li>
</ul>

<h2>What the FutureFit Academy Actually Is</h2>

<p>The academies are in <strong>Perth, Western Australia and Mackay, Queensland</strong>, and BHP committed <strong>$300 million</strong> to them. The programmes on offer:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Programme</th>
            <th style="padding:10px;text-align:left;">Length</th>
            <th style="padding:10px;text-align:left;">Qualification</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Traineeships (heavy diesel, mechanical fitting and fixed plant maintenance)</td><td style="padding:10px;"><strong>12 months</strong></td><td style="padding:10px;">Certificate II</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Apprenticeships (auto electrical, boilermaking and fabrication, polymer processing)</td><td style="padding:10px;"><strong>2 years</strong></td><td style="padding:10px;">Certificate III</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New to Industry Production Operator (Mackay only)</td><td style="padding:10px;">2 months immersive, then 10 months in the field</td><td style="padding:10px;">On-the-job</td></tr>
    </tbody>
</table>
</div>

<p><strong>Correcting the figure everyone repeats:</strong> guides say more than 2,500 people have graduated from the academies. That is not right. <strong>2,500 is BHP's five-year target</strong> for creating new traineeships and apprenticeships nationally. BHP's own published numbers are <strong>more than 1,100 joined and more than 500 graduated</strong>. It is still a serious programme; it is just smaller than the number in circulation.</p>

<h3>The eligibility condition that catches people out</h3>

<p>You must <strong>live within travelling distance of the academy</strong> in Mackay or Perth for the whole training period, because <strong>BHP does not offer relocation and does not provide accommodation during training</strong>. Camp accommodation is provided later, once you are deployed to a site on a swing.</p>

<p>So the academy is realistically for people already in or able to move themselves to those two cities at their own cost. If that is not you, the site-based new-to-industry roles are the better target.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-bhp-mining-jobs-in-australia-site.jpg" alt="Mine site crew reviewing operations in Australia" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What It Pays, From Sources That Publish</h2>

<p>BHP discloses pay at offer stage, not before, and nothing in its annual reporting gives a median employee figure. Here is what can actually be checked:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Benchmark</th>
            <th style="padding:10px;text-align:left;">Figure</th>
            <th style="padding:10px;text-align:left;">What it means</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mining Industry Award floor</td><td style="padding:10px;"><strong>$27.53 an hour</strong> ($1,046.31 a week)</td><td style="padding:10px;">The legal minimum for entry level introductory work from 1 July 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Award top rate</td><td style="padding:10px;">$37.62 an hour ($1,429.51 a week)</td><td style="padding:10px;">Dual-trade instrumentation technician, the highest award classification</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Mining industry average earnings</strong></td><td style="padding:10px;"><strong>$3,224.20 a week</strong></td><td style="padding:10px;">ABS full-time adult ordinary time earnings, May 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">All industries average, for comparison</td><td style="padding:10px;">$2,083.70 a week</td><td style="padding:10px;">The same ABS series across the whole economy</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Drillers, miners and shot firers</td><td style="padding:10px;">$2,824 a week median</td><td style="padding:10px;">Jobs and Skills Australia, 62,800 employed</td></tr>
    </tbody>
</table>
</div>

<p>The number that matters is the comparison: <strong>mining pays roughly 55% above the all-industry average</strong>. That gap, not any particular job board estimate, is the honest reason people move into this work. The award floor is only the legal minimum &mdash; BHP and every other major miner pay above it, and ordinary time earnings exclude the overtime and allowances that make up a large part of a roster worker's income.</p>

<p>One caution on the direction of travel: Jobs and Skills Australia records employment in the drillers, miners and shot firers occupation <strong>falling by about 1,200 a year</strong>. Mining pays well; it is not a growing headcount.</p>

<h2>Rosters and FIFO: There Is No Single BHP Pattern</h2>

<p>Guides that quote one roster for BHP are describing one job. Rosters are set by role and site, and live listings show fly-in fly-out patterns such as 9/5, 5/2 and 4/3 alongside residential roles with subsidised accommodation.</p>

<p>Two things are confirmed. The <strong>FutureFit Academy trains on a 7/7 roster</strong>, seven days on then a week off, alternating day and afternoon shifts. And <strong>academy graduates commit to FIFO deployment</strong> to sites in Western Australia, South Australia or Queensland, with camp accommodation provided during the swing.</p>

<p>Read the roster line in the posting before anything else. It decides more about your life than the pay does.</p>

<h2>Olympic Dam, and a Number to Get Right</h2>

<p>Olympic Dam in South Australia, about 560km north of Adelaide, is one of the world's most significant deposits of <strong>copper, gold and uranium</strong>, with underground and surface operations and full processing on site through a smelter and refinery. It is where most of BHP's entry-level underground roles are advertised.</p>

<p>You will see "8,000 people work at Olympic Dam" repeated online. BHP's figure of about 8,000 covers its whole <strong>Copper South Australia</strong> business, which includes Prominent Hill and Carrapateena as well as Olympic Dam. BHP does not publish an Olympic Dam headcount on its own.</p>

<h2>How the Application Works</h2>

<ol>
    <li><strong>Online application</strong> through BHP's careers portal.</li>
    <li><strong>Assessment</strong>, delivered virtually, using video and game-based exercises rather than a written test.</li>
    <li><strong>Interview or engagement centre</strong>, depending on the role.</li>
    <li><strong>Pre-employment checks</strong>: references, a medical assessment and a criminal history check.</li>
    <li><strong>Offer.</strong></li>
</ol>

<p>If nothing suits today, set a job alert rather than applying to something that does not fit. Academy intakes run in cycles and the alert is how you catch the next one.</p>

<h3>Do not trust a deep link to a vacancy</h3>

<p>Guides that link a specific BHP job ID go stale quickly. The Olympic Dam posting linked by several current guides now returns the line "Sorry, this position has been filled" while still loading as a normal page, which is how a stale link fools you into thinking the role is open. Start from the careers portal and search.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-bhp-mining-jobs-in-australia-training.jpg" alt="Apprentices in training at a mining workshop" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>BHP's Own Scam Warning</h2>

<p>Mining recruitment is heavily impersonated, particularly toward overseas applicants who are told a job is waiting if they pay a processing fee. BHP publishes a direct warning: <strong>BHP will never request funds, require the transfer of money, or seek advancement of fees at any stage of its recruitment process, and never seeks any funds from job applicants.</strong></p>

<p>So there is no legitimate BHP visa fee, medical deposit, training payment or agent commission. Apply on BHP's own portal, and treat any offer arriving from a free email account or a messaging app as fake.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does BHP hire people with no mining experience?</h3>
<p>Yes. BHP publishes three new-to-industry pathways: the FutureFit Academy, site-based apprenticeships and traineeships, and new-to-industry roles where all training happens on the job.</p>

<h3>Do you get paid at the BHP FutureFit Academy?</h3>
<p>Yes. Academy participants are paid, permanent BHP employees from day one, not students.</p>

<h3>How long is a BHP traineeship or apprenticeship?</h3>
<p>Traineeships run 12 months to a Certificate II. Apprenticeships run two years to a Certificate III.</p>

<h3>Does BHP pay to relocate you for the academy?</h3>
<p>No. You must live within travelling distance of the Perth or Mackay academy, and BHP does not provide accommodation during training. Camp accommodation comes later, on site.</p>

<h3>What do BHP mining jobs pay?</h3>
<p>BHP does not publish a pay scale. The Mining Industry Award floor is $27.53 an hour, and ABS puts average full-time mining earnings at $3,224.20 a week against $2,083.70 across all industries.</p>

<h3>Are all BHP jobs fly-in fly-out?</h3>
<p>No. Rosters are role and site specific, and listings include FIFO patterns such as 9/5, 5/2 and 4/3 as well as residential roles with subsidised accommodation.</p>

<h3>What is the BHP assessment like?</h3>
<p>It is delivered virtually, using video and game-based exercises, before an interview or engagement centre stage and then pre-employment checks.</p>

<h3>Does BHP charge applicants any fee?</h3>
<p>No. BHP states it will never request funds, require a transfer of money or seek advance fees at any stage of recruitment.</p>

<h2>People Also Search For</h2>

<h3>BHP FutureFit Academy intake</h3>
<p>Academies in Perth and Mackay, with paid permanent roles from day one and intakes running in cycles.</p>

<h3>Mining salary Australia average</h3>
<p>$3,224.20 a week in full-time ordinary time earnings, against $2,083.70 across all industries.</p>

<h3>Mining Industry Award rates 2026</h3>
<p>An entry level introductory floor of $27.53 an hour from 1 July 2026, rising to $37.62 at the top classification.</p>

<h3>Entry level mining jobs no experience</h3>
<p>BHP's three published pathways all accept people with no mining background.</p>

<h3>Olympic Dam mine jobs</h3>
<p>Copper, gold and uranium operations 560km north of Adelaide, with underground and surface roles.</p>

<h3>FIFO roster meaning</h3>
<p>Fly-in fly-out, with patterns such as 9/5, 5/2 and 4/3 depending on the role and site.</p>

<h3>BHP recruitment scam email</h3>
<p>BHP never seeks funds from applicants at any stage, so any fee request is fraudulent.</p>

<h3>Mining jobs outlook Australia</h3>
<p>Employment in drillers, miners and shot firers is falling by roughly 1,200 a year despite high pay.</p>

<h2>More Job Guides</h2>

<p>Looking at Australian work more widely? These cover it:</p>

<ul>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the closest trade market and its award rates.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; which Australian jobs can actually be sponsored.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; other entry-level routes and what they pay.</li>
    <li><a href="/blog/how-to-get-a-delivery-job-in-australia">How to Get a Delivery Job in Australia</a> &mdash; award pay and the new gig minimum standards.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; another licensed trade route.</li>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> &mdash; the visa side for overseas applicants.</li>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a> &mdash; another resources giant that publishes no pay figure.</li>
    <li><a href="/blog/how-to-apply-for-qantas-ground-staff-jobs-in-australia">How to Apply for Qantas Ground Staff Jobs in Australia</a> &mdash; the pay Qantas will not publish, from the agreement that does.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using BHP's own careers, FutureFit Academy, recruitment process and fraud warning pages, the Fair Work Mining Industry Award pay guide, ABS average weekly earnings and Jobs and Skills Australia occupation data. BHP does not publish pay for individual roles, and rosters and intakes change. Always check the live posting before you apply.</p>
HTML;
    }
}
