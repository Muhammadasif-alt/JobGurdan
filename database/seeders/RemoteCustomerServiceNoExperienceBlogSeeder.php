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
 * "How to Get a Remote Customer Service Job With No Experience" — a US-focused
 * guide for a beginner asking how to land a first work-from-home support job.
 * The Remote Customer Service Jobs guide covers the Pakistani market and the
 * Customer Service Jobs in USA guide covers on-site American roles, so this one
 * stays on the no-experience route into remote US roles, what large employers
 * actually ask for, and the scams aimed at beginners.
 *
 * Corrections and clarifications to the draft (checked against the Bureau of
 * Labor Statistics, the FTC and the employers' own careers sites, September
 * 2026):
 *
 * 1. The draft says the field has "projected double-digit growth". The BLS
 *    projects customer service representative employment to decline 5 percent
 *    from 2025 to 2035, though about 289,500 openings are still expected each
 *    year.
 *
 * 2. The draft says employers posted "over 1.35 million" support positions in
 *    2025. No official source gives that figure; the BLS counts 2,666,000
 *    customer service representative jobs in 2025.
 *
 * 3. The draft lists TTEC at $14-$18 an hour. The TTEC remote posting checked
 *    states $13.00 to $16.35, and Concentrix postings state $15 to $18.31.
 *    Amazon and Apple pay could not be confirmed on their own sites, so those
 *    figures are dropped; Apple had no At Home Advisor posting open.
 *
 * 4. The draft frames these employers as hiring with no experience. The TTEC
 *    and Concentrix remote postings checked ask for six months to a year of
 *    customer service experience, and TTEC excludes applicants in five states.
 *
 * 5. The draft says hiring can take under a week, that 40-45 words per minute
 *    is expected, and that most applications are filtered out by ATS software.
 *    None of these is supported by an official or employer source, so they
 *    are dropped.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RemoteCustomerServiceNoExperienceBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-remote-customer-service-no-experience-jobs.html';

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
        $title = 'How to Get a Remote Customer Service Job With No Experience';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Customer service needs no degree and typically no prior experience, with a BLS median of $21.53 an hour. But many big remote employers ask for six to twelve months of experience, and BLS projects the field to shrink 5 percent by 2035.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-a-remote-customer-service-job-with-no-experience.jpg',
                'tags' => 'remote customer service jobs no experience, work from home customer service, entry level remote jobs, customer service representative pay, ttec at home, concentrix work from home, amazon remote customer service, work from home job scams',
                'meta_title' => 'How to Get a Remote Customer Service Job With No Experience',
                'meta_description' => 'How to get a remote customer service job with no experience in the US: what employers really ask for, official BLS pay, paid training and scam red flags.',
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
            ['name' => 'US Remote Customer Support Employers & Outsourcers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-remote-customer-service-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Remote Customer Service Representative — Entry-Level Work-From-Home Roles, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Scheduled shifts, often including evenings and weekends',
                'language' => 'English',
                // Hourly rates differ by employer, client account and state,
                // so no single band is quoted on the listing.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Entry-level work-from-home customer service roles with US employers and outsourcers. Paid training is common; check state eligibility and equipment rules.',
                'seo_keywords' => 'remote customer service jobs no experience, work from home customer service jobs, entry level remote customer service, call center work from home',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US companies and customer-service outsourcers hire remote representatives to answer customer calls, chats and emails from home, usually on scheduled shifts.</p>

<h3>What the work involves</h3>
<p>Answering questions about orders, accounts and billing, solving problems or escalating them, recording every contact in the company's system and meeting quality and handle-time targets.</p>

<h3>Requirements</h3>
<ul>
    <li>A high school diploma or equivalent is the typical entry level</li>
    <li>A quiet workspace, a reliable high-speed internet connection and, for some employers, your own computer and headset</li>
    <li>US residence, and in some cases residence in a state the employer hires from</li>
    <li>Some remote postings ask for six months to a year of customer service experience</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Official data.</strong> The BLS median for customer service representatives was $21.53 an hour in May 2025, with the lowest 10 percent under $15.27</li>
    <li><strong>Training.</strong> Large employers such as TTEC and Concentrix pay for training</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, equipment and eligibility are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own careers site, and never pay for a job, training or equipment.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>You can get a remote customer service job without experience, but it is harder than most guides admit.</strong> The job itself needs no degree: the Bureau of Labor Statistics (BLS) says the typical entry level is a <strong>high school diploma</strong> with no related work experience, followed by <strong>two to four weeks of on-the-job training</strong>. The catch is remote work specifically. Several of the big work-from-home employers we checked ask for <strong>six months to a year of customer service experience</strong> for their remote roles, and the BLS projects the occupation to <strong>shrink by 5 percent</strong> over the next decade.</p>

<p>This guide covers what employers actually ask for, what the pay data says, the equipment and state rules that trip beginners up, and how to spot the scams aimed at people searching for exactly this job. It is written for the US market; for remote support work from Pakistan, see our <a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> guide.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-remote-customer-service-no-experience-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127482;&#127480; Browse Remote Customer Service Jobs &rarr;
    </a>
</div>

<h2>What the Official Data Says</h2>

<p>Many guides claim remote customer service has "double-digit growth" ahead. <strong>The BLS projects the opposite</strong>: employment of customer service representatives is projected to decline 5 percent from 2025 to 2035. The job is still large, and people leave it often, so openings keep appearing.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Customer service representatives</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Jobs, 2025</td><td style="padding:10px;">2,666,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Projected change, 2025&ndash;35</strong></td><td style="padding:10px;"><strong>&minus;5% (about 141,800 fewer jobs)</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Openings projected each year</td><td style="padding:10px;">About 289,500</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Typical entry-level education</td><td style="padding:10px;">High school diploma or equivalent</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Related work experience</td><td style="padding:10px;">None typically required</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Training</td><td style="padding:10px;">Short-term on-the-job, typically 2 to 4 weeks</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Bureau of Labor Statistics, Occupational Outlook Handbook, Customer Service Representatives (43-4051), 2025 data and 2025&ndash;35 projections.</p>
</div>

<p>We could not find any official source for the "1.35 million support positions posted in 2025" figure that circulates online, so we have left it out.</p>

<h2>What Remote Customer Service Pays</h2>

<p>The BLS does not split pay by remote or entry-level roles, but its national percentiles show where a starting wage usually sits:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Percentile, May 2025</th>
            <th style="padding:10px;text-align:left;">Hourly wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">10th</td><td style="padding:10px;">$15.27</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">25th</td><td style="padding:10px;">$17.72</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median</strong></td><td style="padding:10px;"><strong>$21.53 ($44,770 a year)</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">75th</td><td style="padding:10px;">$24.89</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">90th</td><td style="padding:10px;">Above $30.57</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Bureau of Labor Statistics, Occupational Employment and Wage Statistics, May 2025.</p>
</div>

<p>Starting rates in the remote postings we checked sat toward the bottom of that range:</p>

<ul>
    <li><strong>TTEC</strong>, Customer Service Representative, remote in the USA: a base wage from <strong>$13.00 to $16.35</strong> an hour.</li>
    <li><strong>Concentrix</strong>, remote customer service postings: <strong>$15</strong> an hour for a financial services account, <strong>$15 to $17</strong> for a healthcare account and <strong>$18.31</strong> for a bilingual support role.</li>
    <li><strong>Amazon</strong> advertises remote Customer Service Associate roles, but the pay was not readable on its own job pages when we checked, so we have not quoted a figure.</li>
    <li><strong>Apple</strong> had no At Home Advisor posting open on its careers site when we checked.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-get-a-remote-customer-service-job-with-no-experience-home-desk.jpg"
         alt="A smiling remote customer service representative wearing a headset types on a laptop at a wooden desk in a bright home office with plants, a notebook and a mug"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Experience Catch in Remote Postings</h2>

<p>The occupation as a whole needs no prior experience, but remote roles are more competitive, and employers can ask for more. In the postings we checked:</p>

<ul>
    <li>TTEC's remote customer service posting asks for <strong>six months or more</strong> of customer service experience.</li>
    <li>Concentrix's financial services remote posting asks for <strong>at least a year</strong> of customer service experience.</li>
</ul>

<p>That does not make a first remote job impossible, but it changes the strategy. The fastest route for many beginners is to <strong>count the experience they already have</strong>, or to <strong>get a few months of customer-facing work first</strong>, in retail, food service or an on-site call centre, and then apply for remote roles with that on the CV.</p>

<h3>Experience you may already have</h3>

<ul>
    <li><strong>Retail, food service or hospitality:</strong> helping customers, handling complaints and working a till all count as customer service.</li>
    <li><strong>Tutoring, babysitting or volunteering:</strong> dealing with parents or the public, managing expectations and rearranging schedules.</li>
    <li><strong>Any software you used at work:</strong> a till system, booking tool or ticketing system. Name it on your CV.</li>
</ul>

<h2>Equipment and State Rules That Trip Beginners Up</h2>

<p>Remote postings come with conditions office jobs do not. These are the ones in the postings we checked:</p>

<ul>
    <li><strong>Your own computer, sometimes.</strong> TTEC's FAQ says most work-from-home roles require a Windows-based PC, high-speed internet and a headset. One Concentrix posting says a work computer <em>may</em> be provided but is not guaranteed; its bilingual posting says one will be.</li>
    <li><strong>A fast, wired connection.</strong> TTEC's posting asks for internet faster than 25 Mbps and a <strong>hardwired</strong> connection to your router, not Wi-Fi. Amazon says its recruiters may run a speed test for remote roles.</li>
    <li><strong>Where you live.</strong> TTEC's posting says it is not hiring from <strong>Alaska, California, Hawaii, Illinois or Montana</strong>. The Concentrix postings require you to live in the United States with a valid US address.</li>
    <li><strong>Minimum hours.</strong> TTEC's FAQ sets a minimum of 20 hours a week.</li>
</ul>

<h2>Paid Training Is Normal. Paying for Training Is Not.</h2>

<p>Large remote employers train new starters and pay them while they do it. TTEC says all training is <strong>paid, mandatory</strong> and done online and over the phone, lasting from a few days to several weeks. Concentrix's posting lists <strong>paid training</strong> as a benefit.</p>

<p>That makes one scam easy to spot. The Federal Trade Commission warns that in job scams "you end up paying for starter kits, so-called training, or certifications that are useless", and that "honest employers, including the federal government, will never ask you to pay to get a job. Anyone who does is a scammer."</p>

<h3>Red flags the FTC names</h3>

<ul>
    <li><strong>A request to pay</strong> for a starter kit, training or a certification.</li>
    <li><strong>A cheque to deposit</strong>, with instructions to send part of the money on or buy gift cards with it. That is a fake check scam.</li>
    <li><strong>Personal details requested for "payroll"</strong> before you have a genuine offer, which can lead to identity theft.</li>
</ul>

<p>Apply through the employer's own careers site, not through a link in a message from a "recruiter" you have never heard of.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-get-a-remote-customer-service-job-with-no-experience-headset-call.jpg"
         alt="A remote customer service agent in a headset smiles on a call at her laptop in a cosy home office, with a dog asleep on the sofa and a city skyline at sunset through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Get Hired, Step by Step</h2>

<ol>
    <li><strong>Check your setup first.</strong> A quiet room, a computer, a headset and a wired internet connection faster than 25 Mbps cover what most postings ask for.</li>
    <li><strong>Rewrite your CV around customer contact.</strong> List every job where you dealt with customers or the public, what you handled and any tools you used.</li>
    <li><strong>Read the eligibility lines before you apply.</strong> Check the state list, the experience requirement and whether equipment is provided.</li>
    <li><strong>Apply on the employer's own careers site.</strong> TTEC says its application takes up to an hour; Amazon's process runs from application to a work assessment and pre-hire orientation.</li>
    <li><strong>If remote postings keep asking for experience, get it on site.</strong> A few months in retail, food service or an on-site call centre is often the quickest way to qualify.</li>
    <li><strong>Never pay anything.</strong> Real employers pay you to train.</li>
</ol>

<p>Some guides say you can be hired within a week, that you need to type 40 to 45 words per minute, or that most applications are rejected by software before a person sees them. We found no employer or official source for any of these claims. None of the postings we checked set a typing speed.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I really get a remote customer service job with no experience?</h3>
<p>Yes, but expect competition. The BLS says customer service representatives typically need no related work experience, yet remote postings from TTEC and Concentrix that we checked asked for six months to a year of it. Counting retail or food service experience helps.</p>

<h3>Do I need a degree for remote customer service?</h3>
<p>No. The BLS lists a high school diploma or equivalent as the typical entry-level education, followed by short-term on-the-job training that typically lasts two to four weeks.</p>

<h3>How much do remote customer service jobs pay?</h3>
<p>The BLS median for customer service representatives was $21.53 an hour in May 2025, with the lowest 10 percent earning under $15.27. Remote postings we checked started at $13.00 to $16.35 at TTEC and $15 to $18.31 at Concentrix.</p>

<h3>Is customer service a growing career?</h3>
<p>No. The BLS projects employment of customer service representatives to decline 5 percent from 2025 to 2035. It still expects about 289,500 openings a year, mostly from people leaving the job.</p>

<h3>Do remote employers provide a computer?</h3>
<p>It depends on the employer and role. TTEC says most of its work-from-home roles require your own Windows PC, high-speed internet and headset. One Concentrix posting says a computer may be provided but is not guaranteed.</p>

<h3>Can I work remote customer service from any state?</h3>
<p>Not always. TTEC's remote posting excludes applicants in Alaska, California, Hawaii, Illinois and Montana. The Concentrix postings require you to live in the United States.</p>

<h3>Do I have to pay for training?</h3>
<p>No. TTEC and Concentrix pay new starters during training. The FTC warns that paying for starter kits, training or certifications is a hallmark of job scams.</p>

<h3>How do I spot a work-from-home job scam?</h3>
<p>The FTC says honest employers never ask you to pay to get a job, never send a cheque for you to deposit and forward part of, and a request for personal details "for payroll" before a real offer can lead to identity theft.</p>

<h2>People Also Search For</h2>

<h3>Work from home customer service jobs no experience</h3>
<p>Possible, but many large remote employers ask for six to twelve months of experience.</p>

<h3>TTEC work from home pay</h3>
<p>$13.00 to $16.35 an hour base in the remote posting we checked.</p>

<h3>Concentrix work from home pay</h3>
<p>$15 to $18.31 an hour in the remote postings we checked.</p>

<h3>Customer service representative salary</h3>
<p>A BLS median of $21.53 an hour, or $44,770 a year, in May 2025.</p>

<h3>Amazon remote customer service jobs</h3>
<p>Amazon advertises remote Customer Service Associate roles and may run an internet speed test.</p>

<h3>Entry level remote jobs</h3>
<p>Customer service is one of the few with no degree requirement and short on-the-job training.</p>

<h3>Is customer service a dying job</h3>
<p>The BLS projects a 5 percent decline by 2035, with about 289,500 openings a year.</p>

<h3>Work from home job scams</h3>
<p>Never pay for training, equipment or a starter kit, and never forward money from a cheque.</p>

<h2>More Job Guides</h2>

<p>Looking at other remote or entry-level routes? These cover them:</p>

<ul>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the same work from Pakistan, with local pay floors and shift realities.</li>
    <li><a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a> &mdash; on-site American roles, which often build the experience remote postings ask for.</li>
    <li><a href="/blog/work-from-home-jobs-in-usa">Work From Home Jobs in USA</a> &mdash; other remote roles and how to tell real listings from scams.</li>
    <li><a href="/blog/remote-jobs-in-usa">Remote Jobs in USA</a> &mdash; the wider remote market beyond customer support.</li>
    <li><a href="/blog/remote-data-entry-jobs">Remote Data Entry Jobs</a> &mdash; another entry-level remote route, and its own scam warnings.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Pay, eligibility and equipment requirements come from individual job postings checked in September 2026 and change often. Confirm the details on the employer's own careers site and with the Bureau of Labor Statistics before relying on them.</p>
HTML;
    }
}
