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
 * "Personal Care Assistant Jobs in Australia" — aged care is one of the few
 * entry-level roles in Australia that is genuinely sponsorable, so the visa
 * pathway and the real award pay are the heart of the guide. The sponsorship
 * guide owns the wider Skills in Demand mechanics; this one covers the aged
 * care labour agreement route in particular.
 *
 * Corrections and clarifications to the draft (checked against the Department
 * of Home Affairs, the Fair Work Ombudsman, the Aged Care Award MA000018 and
 * the ATO, September 2026):
 *
 * 1. The draft calls the sponsorship visa the "482 visa" without its current
 *    name. The subclass number is still 482, but the visa was renamed the
 *    Skills in Demand visa on 7 December 2024, with Core Skills and Specialist
 *    Skills streams. Aged care is sponsored through the Aged Care Industry
 *    Labour Agreement (ACILA), which covers Personal Care Assistant (ANZSCO
 *    423313) and Aged or Disabled Carer (423111), but not registered nurses.
 *
 * 2. The draft treats "$51,222" as a general minimum salary. It is the ACILA
 *    concessional floor (or the annual market salary rate, whichever is higher),
 *    which sits far below the general Core Skills Income Threshold, now $79,499
 *    from 1 July 2026. The guide frames it as the labour-agreement concession,
 *    not a market wage.
 *
 * 3. The draft's entry pay of "$51,000-$65,000" predates the Aged Care Work
 *    Value Case. After the final increase on 1 October 2025, a Certificate III
 *    direct-care worker (Level 3) is on about $36.23 an hour, roughly $71,600
 *    full-time, so the $51,000 floor is below the lawful full-time award rate.
 *    The guide lifts the entry figure.
 *
 * 4. The draft implies both a police check and an NDIS check are always needed.
 *    An aged care worker needs a National Police Certificate (issued within
 *    three years) OR an NDIS Worker Screening Clearance; the NDIS clearance is
 *    accepted in aged care and removes the need for a separate police check.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class PersonalCareAssistantJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-sponsorship-visa,-personal-care-assistant-jobs.html';

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
        $title = 'Personal Care Assistant Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Aged care work is one of Australia\'s most sponsorable jobs: the Cert III award rate is about $36.23 an hour after the October 2025 rise, sponsorship runs through the Skills in Demand visa and ACILA at a $51,222 floor, and two years leads to PR.',
                'content' => $content,
                'featured_image' => 'blogs/personal-care-assistant-jobs-in-australia.jpg',
                'tags' => 'personal care assistant jobs in australia, aged care jobs australia, pca jobs australia, aged care worker salary, skills in demand visa, acila sponsorship, certificate iii individual support, aged care visa sponsorship australia',
                'meta_title' => 'Personal Care Assistant Jobs in Australia 2026: Pay & Visa',
                'meta_description' => 'Personal care assistant jobs in Australia in 2026: the aged care award pay, the Skills in Demand visa and ACILA sponsorship, and the PR pathway.',
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
            ['name' => 'Australian Aged Care Providers Hiring Personal Care Assistants (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-personal-care-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Personal Care Assistant — Aged Care, Disability and Home Care Support, Australian Providers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, part-time and casual shifts across a 24-hour roster',
                'language' => 'English',
                // Award rates, casual loading and penalty rates make one figure
                // misleading, and the sponsorship floor is a visa rule, not a
                // wage, so no salary is quoted on the listing itself.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Personal care assistant and aged care roles with Australian providers, including labour-agreement sponsored positions. Pay follows the Aged Care Award, not a quoted band.',
                'seo_keywords' => 'personal care assistant jobs in australia, aged care jobs australia, acila sponsorship, aged care worker salary, skills in demand visa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australia's aged care sector has one of the country's most persistent labour shortages, which makes Personal Care Assistant (PCA) roles among the most consistently available &mdash; and genuinely sponsorable &mdash; jobs for local and international candidates. A PCA supports elderly residents or clients with daily living: showering, dressing, mobility, meals, medication reminders under a nurse's direction, and companionship.</p>

<h3>How it works</h3>
<p>Work is delivered under a Registered or Enrolled Nurse across residential aged care, disability support (NDIS) and home care. Sponsorship, where offered, runs through the Aged Care Industry Labour Agreement on the Skills in Demand visa (subclass 482).</p>

<h3>Requirements</h3>
<ul>
    <li>A Certificate III in Individual Support (Ageing), or at least 12 months of relevant experience</li>
    <li>A National Police Certificate (within three years) or an NDIS Worker Screening Clearance</li>
    <li>Valid work rights in Australia, and strong communication and compassion</li>
    <li>For sponsorship: an employer with access to the Aged Care Industry Labour Agreement</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Aged Care Award.</strong> A Certificate III direct-care worker (Level 3) is on about $36.23 an hour after the 1 October 2025 increase, roughly $71,600 a year full-time; casual base is about $45.29</li>
    <li><strong>Sponsorship floor.</strong> The ACILA concession is $51,222 or the annual market salary rate, whichever is higher &mdash; a visa rule, not the market wage</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> award rates, screening rules and visa requirements are set by the Fair Work Commission, the Department of Health and the Department of Home Affairs &mdash; not by JobGader. Confirm current rules on fairwork.gov.au and immi.homeaffairs.gov.au before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Australia's aged care sector faces one of the country's most persistent labour shortages in 2026, which makes Personal Care Assistant (PCA) roles among the most consistently available &mdash; and genuinely sponsorable &mdash; jobs for both local and international candidates. With an ageing population driving demand, providers are hiring and, in many cases, supporting skilled visa pathways. Here is what the role involves, what it really pays under the award, and how sponsorship actually works.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-sponsorship-visa,-personal-care-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#00247d;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127462;&#127482; Browse Personal Care Assistant Jobs in Australia &rarr;
    </a>
</div>

<h2>What a Personal Care Assistant Does</h2>

<p>A PCA supports elderly residents or clients with daily living, usually in residential aged care, hospitals, or community and home care. Core duties include:</p>

<ul>
    <li>Assisting with showering, dressing, grooming and mobility</li>
    <li>Supporting toileting and continence care</li>
    <li>Preparing and serving meals, and monitoring dietary needs</li>
    <li>Assisting with medication reminders under a Registered Nurse's guidance</li>
    <li>Providing companionship and emotional support, and keeping accurate care records</li>
</ul>

<h2>What the Job Really Pays</h2>

<p>Most guides quote a band with no source, and their entry figures are now below the law. The Aged Care Award (MA000018) sets the floor, and after the Aged Care Work Value Case &mdash; a total increase of about 23%, with the final stage on <strong>1 October 2025</strong> &mdash; those floors rose sharply.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#00247d;color:#fff;">
            <th style="padding:10px;text-align:left;">Aged Care Award, direct care</th>
            <th style="padding:10px;text-align:left;">Hourly (full-time)</th>
            <th style="padding:10px;text-align:left;">Approx. annual (AUD)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Level 2 (assistant in nursing)</td><td style="padding:10px;">~$34.42</td><td style="padding:10px;">~$68,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Level 3 (Certificate III PCW)</strong></td><td style="padding:10px;"><strong>~$36.23</strong></td><td style="padding:10px;"><strong>~$71,600</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Experienced / senior residential</td><td style="padding:10px;">&mdash;</td><td style="padding:10px;">$65,000 &ndash; $78,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Casual base (before penalties)</td><td style="padding:10px;">~$45.29</td><td style="padding:10px;">Hourly</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Aged Care Award MA000018 direct-care rates after the 1 October 2025 work-value increase; rates are indexed again each 1 July.</p>
</div>

<p>Two things follow. First, a full-time Certificate III worker cannot lawfully be paid the "$51,000" some older guides quote &mdash; that echoes the visa floor, not the award. Second, casual rates of "$48 to $80 an hour" are only reachable with weekend, evening and public-holiday penalties or agency loading; the casual base is about $45.29.</p>

<p>Many aged care employers are not-for-profit Public Benevolent Institutions, so their staff can salary-package up to <strong>$15,900 of tax-free benefits a year</strong>, plus a separate meal-entertainment card of about $5,000 grossed-up. That can lift take-home pay meaningfully beyond the base rate.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/personal-care-assistant-jobs-in-australia-home-care.jpg"
         alt="A smiling personal care assistant in blue uniform resting a hand on the shoulder of an older woman on a sunny terrace, the Sydney Opera House and Harbour Bridge behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Qualifications and Checks</h2>

<ul>
    <li><strong>Certificate III in Individual Support (Ageing)</strong> is the standard entry qualification; Certificate IV in Ageing Support is valued for senior roles. Some employers accept substantial caregiving experience instead, particularly for casual or home care work.</li>
    <li><strong>A National Police Certificate (within three years) or an NDIS Worker Screening Clearance.</strong> These are alternatives &mdash; an NDIS clearance is accepted in aged care and removes the need for a separate police check. Disability roles require the NDIS check specifically.</li>
    <li><strong>Valid work rights</strong>, strong communication, and genuine compassion for client wellbeing.</li>
</ul>

<h2>Visa Sponsorship: The Aged Care Labour Agreement Route</h2>

<p>Unlike many entry-level roles, PCA positions can genuinely be sponsored &mdash; this is one of the more reliable sponsored occupations in Australia.</p>

<ul>
    <li><strong>The visa.</strong> Sponsorship is on the <strong>Skills in Demand visa (subclass 482)</strong> &mdash; the number is unchanged, but the visa was renamed from the TSS visa on 7 December 2024, with Core Skills and Specialist Skills streams.</li>
    <li><strong>The pathway.</strong> Aged care is sponsored through the <strong>Aged Care Industry Labour Agreement (ACILA)</strong>, which covers Personal Care Assistant (ANZSCO 423313) and Aged or Disabled Carer (423111). Only employers who hold access to the agreement can use it, so confirm this before applying.</li>
    <li><strong>The requirement.</strong> A Certificate III-equivalent qualification or at least 12 months of relevant experience.</li>
    <li><strong>The salary rule.</strong> The role must pay at least the ACILA concession of <strong>$51,222</strong>, or the annual market salary rate, whichever is higher. This concession sits below the general Core Skills Income Threshold, which is $79,499 from 1 July 2026, precisely because aged care wages are lower &mdash; it is a visa floor, not a market wage.</li>
    <li><strong>The pathway to PR.</strong> After <strong>two years</strong> of full-time work under this pathway, PCAs can be nominated for the subclass <strong>186</strong> visa, leading to permanent residency. Since 7 December 2024 those two years no longer need to be with the same employer.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/personal-care-assistant-jobs-in-australia-daily-support.jpg"
         alt="A personal care assistant in navy uniform serving a meal on a tray to a smiling older man in his living room, an Australian flag and the Sydney skyline in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply for a PCA Job in Australia</h2>

<ol>
    <li><strong>Complete or plan your Certificate III in Individual Support (Ageing)</strong> if you do not already hold it &mdash; it is the standard entry qualification.</li>
    <li><strong>Target employers with Labour Agreement access</strong> if you need sponsorship &mdash; not all providers hold it.</li>
    <li><strong>Prepare your CV</strong> to highlight caregiving experience, certifications and English proficiency.</li>
    <li><strong>Apply through verified channels</strong> &mdash; job boards, established aged care recruiters, or provider career pages.</li>
    <li><strong>Complete required checks</strong> &mdash; a police certificate or NDIS Worker Screening Clearance.</li>
    <li><strong>Ask directly about the ACILA pathway</strong> during interview, including whether the employer currently holds access.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can a personal care assistant get sponsored for an Australian visa?</h3>
<p>Yes. PCAs can be sponsored on the Skills in Demand visa (subclass 482) through the Aged Care Industry Labour Agreement, provided the employer holds access to that agreement.</p>

<h3>What does a personal care assistant earn in Australia?</h3>
<p>Under the Aged Care Award, a Certificate III direct-care worker is on about $36.23 an hour after the 1 October 2025 increase, roughly $71,600 a year full-time, before penalties and salary packaging.</p>

<h3>Do I need a qualification to work as a PCA?</h3>
<p>Most employers require a Certificate III in Individual Support (Ageing), though some accept 12 months or more of relevant caregiving experience instead.</p>

<h3>What is the minimum salary for sponsorship?</h3>
<p>The Aged Care Industry Labour Agreement sets a concessional floor of $51,222, or the annual market salary rate, whichever is higher. That is below the general Core Skills Income Threshold of $79,499 because aged care wages are lower.</p>

<h3>Which police or background check do I need?</h3>
<p>A National Police Certificate issued within three years, or an NDIS Worker Screening Clearance. The NDIS clearance is accepted in aged care, and disability roles require it specifically.</p>

<h3>Can PCA work lead to permanent residency?</h3>
<p>Yes. After two years of full-time work under the labour-agreement pathway, PCAs can be nominated for the subclass 186 visa for permanent residency, and since 7 December 2024 those years need not be with one employer.</p>

<h3>Is the 482 visa still called that?</h3>
<p>The subclass number is still 482, but the visa was renamed the Skills in Demand visa on 7 December 2024, with Core Skills and Specialist Skills streams.</p>

<h3>Can I salary-package as an aged care worker?</h3>
<p>If your employer is a not-for-profit Public Benevolent Institution, you can package up to $15,900 of tax-free benefits a year, plus a separate meal-entertainment card, which lifts your take-home pay.</p>

<h2>People Also Search For</h2>

<h3>Aged care jobs Australia visa sponsorship</h3>
<p>Personal care roles sponsored through the Aged Care Industry Labour Agreement on the Skills in Demand visa, subclass 482.</p>

<h3>Certificate III in Individual Support</h3>
<p>The standard entry qualification for a personal care assistant, covering ageing, disability and home care support.</p>

<h3>Aged Care Award pay rates 2026</h3>
<p>Direct-care rates set by the Aged Care Award MA000018, with a Certificate III worker on about $36.23 an hour after the October 2025 increase.</p>

<h3>ACILA labour agreement</h3>
<p>The Aged Care Industry Labour Agreement, the sponsorship route covering Personal Care Assistant (ANZSCO 423313) and Aged or Disabled Carer roles.</p>

<h3>NDIS Worker Screening Check</h3>
<p>The clearance required for disability support roles, valid five years and accepted in aged care in place of a police certificate.</p>

<h3>Skills in Demand visa 482</h3>
<p>The renamed subclass 482 visa, with Core Skills and Specialist Skills streams, used for aged care sponsorship through a labour agreement.</p>

<h3>Personal care assistant salary Australia</h3>
<p>About $36.23 an hour full-time under the award, roughly $71,600 a year, before penalties and salary packaging.</p>

<h3>Aged care to permanent residency Australia</h3>
<p>Two years of full-time sponsored work can lead to a subclass 186 nomination and permanent residency.</p>

<h2>More Job Guides</h2>

<p>Weighing aged care against other Australian and care-sector routes? These cover them:</p>

<ul>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; the Skills in Demand visa, the occupation lists and the PR routes in full.</li>
    <li><a href="/blog/medical-receptionist-jobs-in-australia">Medical Receptionist Jobs in Australia</a> &mdash; a front-desk healthcare role and what it pays.</li>
    <li><a href="/blog/receptionist-jobs-in-australia">Receptionist Jobs in Australia</a> &mdash; an entry-level office route priced against the award.</li>
    <li><a href="/blog/healthcare-support-jobs-in-uk">Healthcare Support Jobs in UK</a> &mdash; the comparable care role in the UK, and its sponsorship reality.</li>
    <li><a href="/blog/occupational-therapist-jobs-in-canada">Occupational Therapist Jobs in Canada</a> &mdash; an allied-health route with a clear registration path.</li>
    <li><a href="/blog/physical-therapist-jobs-in-usa">Physical Therapist Jobs in the USA</a> &mdash; a higher-paid allied-health market and how sponsorship works there.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Award rates, screening rules and visa requirements change over time. Confirm the current position with the Fair Work Ombudsman, the Department of Health and the Department of Home Affairs before applying.</p>
HTML;
    }
}
