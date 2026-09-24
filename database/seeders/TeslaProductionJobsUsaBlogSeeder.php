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
 * "How to Apply for Tesla Production Jobs in the USA" - an employer guide
 * rebuilt on Tesla's own careers postings, its factory pages and its SEC
 * filings, rather than the Indeed listings and review snippets the draft
 * leaned on.
 *
 * Corrections to the draft (checked against tesla.com careers postings,
 * tesla.com/fremont-factory, tesla.com/giga-nevada, Tesla SEC filings,
 * Electrek and the Kyle Economic Development Corporation, September 2026):
 *
 * 1. The draft made Indeed the primary apply link and used a single Tesla job
 *    ID, /careers/search/job/...-256718. Job IDs expire within weeks and
 *    aggregator links are against site policy, so both are replaced with
 *    Tesla's own careers search.
 *
 * 2. The draft quoted "around $22+/hour" from Indeed employee reviews. Tesla
 *    prints its own range on the posting under state pay transparency law:
 *    $21.00 to $30.00 an hour plus cash and stock awards and benefits.
 *
 * 3. The draft said "48 Tesla Production Associate jobs available on Indeed".
 *    That count is an aggregator snapshot that changes daily, and is dropped.
 *
 * 4. The draft omitted the physical requirements entirely. Tesla's posting
 *    requires repeatedly lifting, pushing and carrying up to 35 lbs, and
 *    standing and walking for up to 12 hours a day over uneven ground. That
 *    is the bar applicants actually fail, so it leads the guide.
 *
 * 5. The draft said "401k match" with no figure. Tesla's filings put the match
 *    at 50% of contributions up to 3% of eligible pay, capped at $3,000 a year.
 *
 * 6. The draft treated Cybercab as an established line. Production only began
 *    at Giga Texas during 2026 and is ramping slowly, so the wording is
 *    softened.
 *
 * 7. The draft's "offer within minutes" and "no cover letter required" claims
 *    are review anecdotes with no Tesla source, and are dropped.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TeslaProductionJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.tesla.com/careers/search/';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on finding work, applying well and understanding what a job really pays.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Apply for Tesla Production Jobs in the USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Tesla prints a pay range on its own postings, so this guide drops the job-board estimates. It covers the hourly band Tesla publishes, the 35 lb and 12 hour physical bar most guides omit, the five US sites hiring, and how the application works.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-tesla-production-jobs-in-the-usa.jpg',
                'tags' => 'tesla production jobs, tesla production associate, tesla careers usa, giga texas jobs, fremont factory jobs, gigafactory nevada jobs, tesla megafactory lathrop, tesla jobs no experience',
                'meta_title' => 'Tesla Production Jobs USA: Pay, Shifts and How to Apply',
                'meta_description' => 'Tesla Production Associate jobs in the USA: the hourly range Tesla prints itself, the 35 lb lifting bar, the five sites hiring and how to apply.',
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
            ['name' => 'Tesla, US Factories'],
            ['type' => 'Company', 'display_reference' => 'tesla-us-factories']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Production Associate, Tesla US Factories',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rotating shifts including days, nights, overnight and weekends, with overtime',
                'language' => 'English',
                // Tesla prints this range in the Expected Compensation block on
                // its own Production Associate postings under state pay
                // transparency law, so it is the employer's own figure rather
                // than a job board estimate.
                'salary_currency' => 'USD',
                'salary_period' => 'Hourly',
                'salary_minimum' => 21,
                'salary_maximum' => 30,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Production Associate roles on Tesla assembly, battery and energy lines in Texas, California and Nevada, open to applicants with no manufacturing background.',
                'seo_keywords' => 'tesla production jobs, tesla production associate, giga texas jobs, fremont factory jobs, tesla careers usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Tesla hires Production Associates for vehicle assembly, battery and drive unit lines, and energy storage manufacturing across its United States factories.</p>

<h3>What the work involves</h3>
<p>Following standard work instructions on a moving line, rotating between stations and production lines as volume requires, and working to the safety and quality standards set for each station.</p>

<h3>Common requirements</h3>
<ul>
    <li>No degree or prior manufacturing experience required</li>
    <li>Ability to repeatedly lift, push and carry up to 35 lbs</li>
    <li>Ability to stand and walk for up to 12 hours a day, including over uneven ground</li>
    <li>Openness to days, nights, overnight and weekend shifts, plus overtime</li>
    <li>The legal right to work in the United States</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> the hourly range, shift pattern and benefits on any given role are set by Tesla and printed on its own posting &mdash; not by JobGader. Apply directly on Tesla Careers and never pay anyone for a Tesla job or an interview slot.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on Tesla Careers, filter by Manufacturing and the site you can reach, and submit the online application.</strong> No degree and no factory background is required, and Tesla prints the hourly range on the posting itself.</p>

<p>Two things are worth knowing before you spend an evening on this. <strong>Tesla publishes its own pay range</strong>, so you never need a job board's estimate. And the posting carries a physical bar that most guides skip entirely &mdash; it is the part that decides whether this job suits you.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.tesla.com/careers/search/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#cc0000;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Search Tesla Careers &rarr;
    </a>
</div>

<h2>What a Tesla Production Associate Actually Does</h2>

<p>You work on the manufacturing floor, on vehicle lines such as Model Y, Cybertruck and Cybercab, on drive units and battery cells, or on energy products such as Powerwall and Megapack. Tesla's own posting describes a team-oriented production environment rather than a specialist trade.</p>

<p>The duties it lists are deliberately plain:</p>

<ul>
    <li>Following standard work processes, procedures and safety instructions</li>
    <li>Rotating across different stations and production lines as the business needs</li>
    <li>Consistent attendance and punctuality for scheduled shifts</li>
    <li>Learning new tools and tasks quickly as the line changes</li>
</ul>

<p>Notice what is not there: no certification, no minimum years, no portfolio. Tesla is screening for reliability and adaptability, and it says so.</p>

<h2>The Physical Bar Tesla Prints, and Most Guides Skip</h2>

<p>This is the single most useful paragraph on this page. Tesla's Production Associate posting sets out physical requirements in detail, and they are not a formality:</p>

<ul>
    <li><strong>Frequently and repetitively lift, push and carry up to 35 lbs</strong></li>
    <li><strong>Stand and walk for up to 12 hours a day</strong>, including over varied and uneven ground</li>
    <li>Frequently stoop, bend, reach, squat, kneel, crouch, twist and crawl for extended periods, again up to 12 hours a day</li>
</ul>

<p>Two practical readings of that. First, <strong>35 lbs is the repeated lift, not a one-off maximum</strong>, so the question is whether you can do it hundreds of times in a shift, not once. Second, a 12-hour standing day is why people who are otherwise well suited to the pay leave in the first month. Decide this honestly before the interview, because the interview will ask.</p>

<h2>Where Tesla Builds in the USA</h2>

<p>Production Associate roles cluster around five American sites, each building something different:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#cc0000;color:#fff;">
            <th style="padding:10px;text-align:left;">Site</th>
            <th style="padding:10px;text-align:left;">What is built there</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Austin, TX</strong> (Giga Texas)</td><td style="padding:10px;">Model Y, Cybertruck and Cybercab, plus batteries and new AI and robotics lines. Around 16,500 employees.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Fremont, CA</strong></td><td style="padding:10px;">Vehicle assembly and, since 2023, an on-site lithium-ion battery factory.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sparks, NV</strong> (Giga Nevada)</td><td style="padding:10px;">Electric motors, powertrains, cells and energy storage, plus an LFP cell factory and the first high-volume Semi factory.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Lathrop, CA</strong> (Megafactory)</td><td style="padding:10px;">Megapack grid-scale energy storage, running since 2022.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Kyle, TX</strong></td><td style="padding:10px;">Around a million square feet leased at a logistics park south of Austin, used for subassembly and supply chain work.</td></tr>
    </tbody>
</table>
</div>

<p>On Cybercab specifically, be careful with what you read. <strong>Production only began at Giga Texas during 2026</strong>, and Tesla itself said the early ramp would be slow because the manufacturing method is new. Roles tied to that line are real, but it is a line being built out rather than a mature one.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-tesla-production-jobs-in-the-usa-battery.jpg" alt="Production associates fitting a battery pack to an electric vehicle chassis" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Shifts Are a Requirement, Not a Preference</h2>

<p>Tesla's plants run around the clock, and the posting is blunt about it: you must be <strong>open to schedule flexibility that allows for a variety of shifts, including days, nights, overnight and weekends, as well as potential overtime</strong>.</p>

<p>What that means in practice:</p>

<ul>
    <li>Rotating patterns are the norm, not an occasional exception</li>
    <li>Overtime is common while a line is ramping</li>
    <li>Saying yes to weekends and nights genuinely widens what you are offered</li>
</ul>

<h2>What It Pays</h2>

<p>You do not have to guess, and you should not trust a review snippet. Tesla prints an <strong>Expected Compensation</strong> block on the posting itself, because California, Washington, New York and Colorado require it. On Production Associate roles that block reads:</p>

<div style="background:#f9fafb;border-left:4px solid #cc0000;padding:16px 20px;margin:24px 0;">
    <p style="margin:0;"><strong>$21.00 to $30.00 an hour, plus cash and stock awards and benefits</strong>, with the note that pay offered may vary depending on market location, job-related knowledge, skills and experience.</p>
</div>

<p>Read the range correctly. The bottom is an entry rate at a lower-cost site; the top reflects a higher-cost market, a harder shift or relevant experience. A guide quoting one Tesla number is flattening a range Tesla deliberately published as a range.</p>

<h3>The benefits, from Tesla's own pages</h3>

<ul>
    <li><strong>Medical, dental and vision</strong>, with plan options carrying no payroll deduction, and a company contribution to the health savings account</li>
    <li><strong>401(k) with a company match</strong> of 50% of what you contribute, up to 3% of eligible pay, capped at $3,000 a year</li>
    <li><strong>Stock awards</strong>, which is why the pay block says cash <em>and</em> stock</li>
    <li>Maternity and paternity leave, family-building and infertility benefits, and a confidential mental wellness programme</li>
    <li>Paid time off, financial assistance for sudden hardship, free EV charging, free shuttles and carpool subsidies at the larger sites</li>
    <li>Voluntary extras including critical illness, hospital indemnity, accident, theft and legal services, and pet insurance</li>
</ul>

<p>The stock component is the part people underrate. It is a small grant on a production wage, but it is real compensation that no warehouse competitor offers on the same terms.</p>

<h2>Is Experience Required?</h2>

<p><strong>No.</strong> Tesla's posting asks for a demonstrated ability to learn new skills and adapt to new work environments. It does not ask for a degree, a certificate or prior manufacturing time.</p>

<p>What genuinely helps:</p>

<ul>
    <li>A clean attendance record you can speak to, because the posting names attendance directly</li>
    <li>Honest comfort with repetitive physical work across a long shift</li>
    <li>Any evidence of picking things up fast, from any industry</li>
    <li>Willingness to rotate stations rather than owning one task</li>
</ul>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-tesla-production-jobs-in-the-usa-quality.jpg" alt="Technician carrying out a quality inspection on a vehicle body in an assembly plant" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Open Tesla Careers</strong> and search the Manufacturing job family rather than a job title, because Tesla names lines differently across sites.</li>
    <li><strong>Filter by the site you can actually commute to</strong> &mdash; Austin, Fremont, Sparks, Lathrop or Kyle. These are on-site roles with no remote option.</li>
    <li><strong>Read the Expected Compensation block on that specific posting</strong> before anything else. It tells you the real range for that site.</li>
    <li><strong>Check the physical requirements section</strong> on the same posting, and be honest with yourself about the 12-hour standing day.</li>
    <li><strong>Apply through Tesla's own system.</strong> Applying through a reposting site adds a middle step and can lose your application.</li>
    <li><strong>Prepare for questions on availability.</strong> Shift flexibility and attendance come up early, and vague answers cost offers.</li>
    <li><strong>Never pay for a Tesla job.</strong> Tesla does not charge applicants and does not use paid agents for production hiring.</li>
</ol>

<p>If Tesla is not hiring at a site you can reach, the nearest comparable employers are covered in our <a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">Amazon fulfillment center guide</a> and our <a href="/blog/how-to-apply-for-ups-package-handler-jobs-in-the-usa">UPS package handler guide</a>, both of which hire on the same no-experience basis.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does a Tesla Production Associate get paid?</h3>
<p>Tesla prints the range on the posting itself: $21.00 to $30.00 an hour plus cash and stock awards and benefits. Where you land depends on the site, the shift and your experience.</p>

<h3>Do I need manufacturing experience to work at Tesla?</h3>
<p>No. The posting asks for an ability to learn new skills and adapt to new work environments, not a degree or prior factory work.</p>

<h3>How much lifting does the job involve?</h3>
<p>Tesla's posting requires frequently and repetitively lifting, pushing and carrying up to 35 lbs, plus standing and walking for up to 12 hours a day.</p>

<h3>Which US sites hire Production Associates?</h3>
<p>Austin and Kyle in Texas, Fremont and Lathrop in California, and Sparks in Nevada. All are on-site roles with no remote option.</p>

<h3>Does Tesla match 401(k) contributions?</h3>
<p>Yes. Tesla matches 50% of your contribution up to 3% of eligible pay, with a cap of $3,000 a year.</p>

<h3>Are night and weekend shifts compulsory?</h3>
<p>Effectively yes. The posting requires openness to days, nights, overnight and weekends plus potential overtime, because the plants run continuously.</p>

<h3>Is Tesla really building Cybercab already?</h3>
<p>Yes, but early. Production started at Giga Texas during 2026, and Tesla said the initial ramp would be slow because the manufacturing approach is new.</p>

<h3>Can I apply to Tesla from outside the United States?</h3>
<p>These are on-site production roles that require the legal right to work in the US. Tesla does not sponsor visas for entry-level production work.</p>

<h2>People Also Search For</h2>

<h3>Tesla production associate pay</h3>
<p>$21.00 to $30.00 an hour on Tesla's own postings, plus cash and stock awards.</p>

<h3>Giga Texas jobs Austin</h3>
<p>Around 16,500 employees building Model Y, Cybertruck and Cybercab, plus batteries and robotics lines.</p>

<h3>Tesla Fremont factory hiring</h3>
<p>Vehicle assembly plus an on-site lithium-ion battery factory opened in 2023.</p>

<h3>Gigafactory Nevada jobs Sparks</h3>
<p>Motors, powertrains, cells and energy storage, with an LFP cell factory and the Semi factory added.</p>

<h3>Tesla Megafactory Lathrop</h3>
<p>Megapack grid-scale energy storage, in production since 2022.</p>

<h3>Tesla jobs no experience</h3>
<p>Production Associate is the entry route: no degree, no certificate and no prior manufacturing time required.</p>

<h3>Tesla shift schedule production</h3>
<p>Rotating days, nights, overnight and weekends, with overtime during ramps.</p>

<h3>Tesla benefits 401k match</h3>
<p>50% of your contribution up to 3% of eligible pay, capped at $3,000 a year.</p>

<h2>More Job Guides</h2>

<p>Comparing entry-level industrial work across the US? These cover the alternatives:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-ups-package-handler-jobs-in-the-usa">How to Apply for UPS Package Handler Jobs in the USA</a> &mdash; a union contract that publishes the wage scale in writing.</li>
    <li><a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">How to Apply for Amazon Fulfillment Center Jobs in USA</a> &mdash; the closest comparison on pay and shift patterns.</li>
    <li><a href="/blog/forklift-operator-jobs-in-usa">Forklift Operator Jobs in USA</a> &mdash; the certificate that lifts you off the line and out of the lifting.</li>
    <li><a href="/blog/maintenance-technician-jobs-in-usa">Maintenance Technician Jobs in USA</a> &mdash; the usual internal step up from a production line.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; what the work authorisation rules actually allow.</li>
    <li><a href="/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa">How to Apply for Walmart Store Associate Jobs in the USA</a> &mdash; the retail alternative with predictable hours.</li>
    <li><a href="/blog/how-to-apply-for-toyota-factory-jobs-in-japan">How to Apply for Toyota Factory Jobs in Japan</a> &mdash; the same question in Japan, where the residence status decides it.</li>
    <li><a href="/blog/how-to-apply-for-bmw-factory-jobs-in-germany">How to Apply for BMW Factory Jobs in Germany</a> &mdash; a car plant that publishes no pay at all, and why.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Tesla Careers postings, Tesla's Fremont factory and Gigafactory Nevada pages, Tesla SEC filings and reporting on Cybercab production. Pay ranges, benefits and shift patterns are set by Tesla and differ by site and posting. Always read the live posting before you apply.</p>
HTML;
    }
}
