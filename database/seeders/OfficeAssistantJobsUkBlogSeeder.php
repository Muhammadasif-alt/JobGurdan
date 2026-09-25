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
 * "Office Assistant Jobs in UK" — a first administrative job in Britain. The
 * UK-for-foreigners guide owns the Skilled Worker visa in general and the
 * healthcare and IT support guides own their sectors; this one owns the office
 * assistant reality: what the role really pays against the minimum wage, why
 * the visa route is effectively closed for it, and the right-to-work routes
 * that open it.
 *
 * Corrections to the draft (checked September 2026 against gov.uk, the National
 * Careers Service and ONS):
 *
 * 1. The draft's entry pay of GBP 11.50 an hour and GBP 18,000 a year full-time
 *    is below the legal minimum. The National Living Wage for age 21+ is GBP
 *    12.71 an hour from 1 April 2026, about GBP 24,800 a year full-time. The
 *    National Careers Service puts admin assistants at GBP 21,000 starter to
 *    GBP 28,000 experienced, and even that starter figure now sits below the
 *    full-time minimum.
 *
 * 2. The draft names the Skilled Worker general salary threshold as GBP 38,700.
 *    It rose to GBP 41,700 (or the going rate, whichever is higher) on 22 July
 *    2025.
 *
 * 3. The draft says the role falls below "RQF Level 3+". Since 22 July 2025 the
 *    Skilled Worker skill bar was raised back to RQF Level 6 (degree level) for
 *    new applicants, and administrative roles were removed from the eligible
 *    list. So the decisive blocker is the skill level, not just the salary.
 *
 * 4. The draft states "over 2,000 active Indeed listings at any given time".
 *    Job-board counts are not an authoritative figure, so it is reworded as an
 *    approximate, high-volume description.
 *
 * 5. The Graduate visa is described as open-ended. It runs 2 years for
 *    applications up to 31 December 2026, then drops to 18 months from 1
 *    January 2027 (36 months for PhDs).
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class OfficeAssistantJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/Office-Assistant-jobs';

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
        $title = 'Office Assistant Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'GBP 18,000 full-time and GBP 11.50 an hour are below the April 2026 minimum wage of GBP 12.71, the Skilled Worker threshold is GBP 41,700 not 38,700, and office assistant roles are blocked from sponsorship by the RQF Level 6 skill bar, not just pay.',
                'content' => $content,
                'featured_image' => 'blogs/office-assistant-jobs-in-uk.jpg',
                'tags' => 'office assistant jobs in uk, office assistant salary uk, admin assistant jobs uk, office administrator jobs, entry level office jobs uk, office assistant visa sponsorship, skilled worker visa office jobs, administrative assistant jobs uk, office jobs london, right to work uk',
                'meta_title' => 'Office Assistant Jobs in UK 2026: Pay & Visa Reality',
                'meta_description' => 'Office assistant jobs in the UK in 2026: what they really pay against the minimum wage, the visa sponsorship reality, requirements and how to apply.',
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
            ['name' => 'UK Employers Hiring Office and Admin Support (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-office-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'England, Scotland, Wales and Northern Ireland', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Office Assistant — Admin, Reception and Clerical Support, UK Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Typically 37 to 40 hours a week; many part-time and hybrid roles',
                'language' => 'English',
                // Pay runs from the minimum wage to senior EA level, so no
                // single range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Office assistant, admin assistant and clerical support roles with UK employers across London and the wider UK. Most require the right to work in the UK.',
                'seo_keywords' => 'office assistant jobs in uk, admin assistant jobs uk, office administrator jobs, entry level office jobs uk, office jobs london',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Law firms, hospitals, schools, construction companies, charities and government departments across the UK hire office assistants to keep daily operations running. The title overlaps with admin assistant, office administrator and receptionist, so it is worth searching all of them.</p>

<h3>What the work involves</h3>
<p>Managing paper and digital filing, answering phones and greeting visitors, data entry and record-keeping, ordering supplies, and supporting diaries, meeting rooms and basic finance admin.</p>

<h3>Requirements</h3>
<ul>
    <li>GCSEs at grades 9 to 4 (A* to C) including English and maths, or an equivalent</li>
    <li>Practical Microsoft 365 skills and comfortable, accurate typing</li>
    <li>The right to work in the UK &mdash; most office assistant roles cannot be sponsored</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>At or above the minimum wage.</strong> The National Living Wage for age 21+ is GBP 12.71 an hour from April 2026, about GBP 24,800 a year full-time; the National Careers Service puts experienced admin assistants near GBP 28,000</li>
    <li><strong>Extras.</strong> Public sector and larger employers often add pension, above-statutory leave and hybrid working</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Confirm your right to work first,</strong> since it decides which roles are open to you, and be wary of any listing paying below the legal minimum wage.</p>

<p><strong>Note:</strong> pay, requirements and visa eligibility are set by employers and the Home Office &mdash; not by JobGader. Confirm current rules on gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Office assistant roles are one of the most consistently available ways into UK administrative work, from law firms and hospitals to construction companies and government departments. They are also one of the most misreported jobs online, especially on pay and on whether an employer can sponsor a visa. Before you apply, it helps to know what the role really pays against the minimum wage, why the visa route is effectively closed for it, and which right-to-work routes open it.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/Office-Assistant-jobs" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127468;&#127463; Browse Office Assistant Jobs in the UK &rarr;
    </a>
</div>

<h2>What Does an Office Assistant Do?</h2>

<p>Day-to-day work usually includes:</p>

<ul>
    <li>Managing filing systems, both physical and electronic</li>
    <li>Photocopying, scanning and mailing documents</li>
    <li>Answering phones and welcoming visitors as the first point of contact</li>
    <li>Data entry and keeping accurate records</li>
    <li>Ordering and managing office supplies</li>
    <li>Supporting diaries, meeting-room bookings and scheduling</li>
    <li>Helping with invoicing or basic finance admin in smaller offices</li>
</ul>

<p>Listings blend into related titles &mdash; <strong>Administrative Assistant, Office Administrator and Receptionist</strong> &mdash; so search all of them when job hunting.</p>

<h2>What Office Assistant Jobs Really Pay in 2026</h2>

<p>Some guides quote entry pay of GBP 11.50 an hour or GBP 18,000 a year full-time. Both are <strong>below the legal minimum</strong>. The National Living Wage for age 21 and over is <strong>GBP 12.71 an hour from 1 April 2026</strong>, which is about <strong>GBP 24,800 a year</strong> at full-time hours. The National Careers Service puts admin assistants at GBP 21,000 starter to GBP 28,000 experienced, and even that starter figure now sits below the full-time minimum. Realistic figures:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Level</th>
            <th style="padding:10px;text-align:left;">Approx. annual, full-time (GBP)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Entry level (minimum-wage floor)</td><td style="padding:10px;">24,800 &ndash; 25,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">1 to 3 years' experience</td><td style="padding:10px;">25,000 &ndash; 28,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Senior / office manager / executive assistant</td><td style="padding:10px;">30,000 &ndash; 40,000+</td></tr>
    </tbody>
</table>
</div>

<p>Part-time and hourly roles pay from <strong>GBP 12.71 an hour</strong>, the April 2026 minimum for age 21+. Anything advertising GBP 11.50 an hour or GBP 18,000 full-time is below the legal floor. London roles sit at the higher end; smaller towns at the lower.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/office-assistant-jobs-in-uk-desk.jpg"
         alt="A smiling office assistant in a blazer working at a laptop in a bright London office, colleagues and a Union Jack flag nearby and Big Ben visible through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Visa Sponsorship: The Hard Truth</h2>

<p>This matters before you apply: <strong>most standard office assistant roles cannot be sponsored on a Skilled Worker visa in 2026.</strong> Two rules block it, and the first is decisive:</p>

<ul>
    <li><strong>The skill level.</strong> Since 22 July 2025 the Skilled Worker skill bar was raised back to <strong>RQF Level 6</strong> (degree level) for new applicants, and administrative roles were removed from the eligible occupation list. Office assistant work sits below this and is not on the Immigration Salary List or the Temporary Shortage List, so it is not an eligible occupation at all.</li>
    <li><strong>The salary.</strong> Even where a role were eligible, the general threshold is now <strong>GBP 41,700</strong> a year, or the going rate if higher. Typical office assistant pay of GBP 24,000 to GBP 28,000 is far below it.</li>
</ul>

<p>If sponsorship is essential, look instead at higher-skilled <strong>business support, office manager or specialist administrator</strong> roles that can clear both bars, target employers listed as licensed Skilled Worker sponsors, and remember the general requirements: a Certificate of Sponsorship, proof of English, and <strong>GBP 1,270 held for 28 consecutive days</strong> unless the sponsor certifies maintenance.</p>

<p>If you already have the right to work, standard office roles are fully open with no sponsorship:</p>

<ul>
    <li><strong>Settled or pre-settled status</strong> under the EU Settlement Scheme.</li>
    <li><strong>Graduate visa</strong> &mdash; work in almost any job. It runs 2 years for applications up to 31 December 2026, then 18 months from 1 January 2027 (36 months for PhDs).</li>
    <li><strong>Youth Mobility Scheme</strong> &mdash; most work allowed, no job offer or sponsor needed.</li>
    <li><strong>Dependant or partner visa</strong> &mdash; generally permits work without separate sponsorship.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/office-assistant-jobs-in-uk-reception.jpg"
         alt="An office assistant on the phone taking notes at a reception desk in a bright UK office, a map of the United Kingdom on the wall and a Union Jack flag on the desk"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Requirements to Apply</h2>

<ul>
    <li>GCSEs at grades 9 to 4 (A* to C) including English and maths, or an equivalent</li>
    <li>Practical Microsoft 365 skills (Word, Excel, Outlook, Teams)</li>
    <li>Fast, accurate typing &mdash; many employers value around 40 WPM, though this is an employer expectation, not an official requirement</li>
    <li>Strong organisation, attention to detail and clear communication</li>
    <li>The right to work in the UK</li>
</ul>

<p>Experience is not always required &mdash; many part-time and junior listings are genuinely open to first-time candidates.</p>

<h2>How to Apply for an Office Assistant Job</h2>

<ol>
    <li><strong>Confirm your right-to-work status</strong> first, since it decides which roles are realistically open to you.</li>
    <li><strong>Search broadly</strong> &mdash; add "Administrative Assistant", "Office Administrator" and "Receptionist" to "Office Assistant".</li>
    <li><strong>Tailor your CV</strong> to organisation, IT skills and any clerical or customer-facing experience.</li>
    <li><strong>Apply through Indeed, LinkedIn or company career pages</strong> rather than unverified agencies.</li>
    <li><strong>Prepare for a practical interview</strong> &mdash; many employers test basic Microsoft Office skills or typing.</li>
    <li><strong>Ask about progression</strong> &mdash; office assistant roles often lead to office manager, executive assistant or coordinator jobs.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Do office assistant jobs in the UK offer visa sponsorship?</h3>
<p>Rarely. Since 22 July 2025 the Skilled Worker route needs an RQF Level 6 occupation, and admin roles were removed from the eligible list, so the standard office assistant title cannot be sponsored. Higher business-support or office-manager roles occasionally qualify.</p>

<h3>Do I need a degree for an office assistant job?</h3>
<p>No. Most employers ask only for GCSE-level education, with practical computer skills and organisation mattering more than formal qualifications.</p>

<h3>What does an office assistant earn in the UK?</h3>
<p>The National Careers Service puts admin assistants at GBP 21,000 starter to GBP 28,000 experienced. Full-time pay cannot legally fall below about GBP 24,800, the April 2026 minimum wage.</p>

<h3>Is GBP 18,000 a year legal for a full-time office assistant?</h3>
<p>No. At full-time hours that is below the National Living Wage of GBP 12.71 an hour, which works out to about GBP 24,800 a year.</p>

<h3>What is the Skilled Worker salary threshold in 2026?</h3>
<p>GBP 41,700 a year, or the occupation's going rate if that is higher. It rose from GBP 38,700 on 22 July 2025.</p>

<h3>Can office assistant work lead to career progression?</h3>
<p>Yes. It is a common stepping stone to office manager, executive assistant, HR assistant or department coordinator roles.</p>

<h3>Which right-to-work routes let me take an office job without sponsorship?</h3>
<p>Settled or pre-settled status, a Graduate visa, the Youth Mobility Scheme and most dependant or partner visas all allow office work without employer sponsorship.</p>

<h3>What skills and qualifications do I need?</h3>
<p>GCSE English and maths at grades 9 to 4, practical Microsoft 365 skills, accurate typing and strong organisation.</p>

<h2>People Also Search For</h2>

<h3>Office assistant salary UK</h3>
<p>About GBP 24,800 to GBP 28,000 full-time, rising to GBP 30,000 and above for senior and executive assistant roles.</p>

<h3>Admin assistant jobs UK</h3>
<p>The same work under a different title; search both, plus office administrator and receptionist.</p>

<h3>Office assistant visa sponsorship UK</h3>
<p>Effectively closed since 22 July 2025, because the role is below the RQF Level 6 skill bar and off the eligible list.</p>

<h3>Entry level office jobs UK</h3>
<p>Many part-time and junior office roles are open to first-time candidates with GCSE-level education.</p>

<h3>Office administrator jobs</h3>
<p>A closely related title, often with more responsibility for systems, suppliers and scheduling.</p>

<h3>Skilled Worker visa salary threshold 2026</h3>
<p>GBP 41,700 a year, or the going rate if higher, plus GBP 1,270 held for 28 days.</p>

<h3>Office assistant jobs London</h3>
<p>The highest-paying region for the role, at the upper end of the national bands.</p>

<h3>Right to work UK jobs</h3>
<p>Settled status, a Graduate visa, the Youth Mobility Scheme and dependant visas all allow office work without sponsorship.</p>

<h2>More Job Guides</h2>

<p>Looking at other UK routes and related roles? These cover them:</p>

<ul>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the Skilled Worker visa in full, the thresholds and which jobs can be sponsored.</li>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; the same role and its pay across the Atlantic.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; the role, the award pay and the visa position in Australia.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; remote and hybrid admin roles, and how the pay compares.</li>
    <li><a href="/blog/healthcare-support-jobs-in-uk">Healthcare Support Jobs in UK</a> &mdash; an entry route that can still be sponsored, and the NHS pay bands.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; another first job in Britain, and what it pays.</li>
    <li><a href="/blog/school-administrator-jobs-in-uk">School Administrator Jobs in UK</a> &mdash; school office duties, FTE vs actual pay on term-time contracts, routes in and where schools advertise.</li>
    <li><a href="/blog/business-analyst-jobs-in-uk">Business Analyst Jobs in UK</a> &mdash; the ONS salary picture, apprenticeship and Civil Service routes, and the SOC 2431 visa rules.</li>
    <li><a href="/blog/how-to-apply-for-lloyds-graduate-jobs-in-the-uk">How to Apply for Lloyds Graduate Jobs in the UK</a> &mdash; ten schemes with published salaries, and the sponsorship answer that rules most readers out.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not immigration or careers advice. Minimum wage rates, visa thresholds and skill rules change. Confirm current pay and right-to-work and Skilled Worker requirements with gov.uk before applying.</p>
HTML;
    }
}
