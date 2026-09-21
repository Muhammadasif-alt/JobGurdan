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
 * "How Foreign Workers Can Get a Job in Australia" — the question a foreign
 * worker asks before choosing a visa. The Visa Sponsorship Jobs in Australia
 * guide goes deep on the 482, 186 and 494, so this one compares every route in:
 * employer sponsorship, points-tested visas that need no job offer, working
 * holidays, the Pacific scheme and student work rights, and who each one fits.
 *
 * Corrections and clarifications to the draft (checked against the Department
 * of Home Affairs visa pages, salary requirements, English language
 * requirements and Working Holiday Maker country caps, September 2026):
 *
 * 1. The draft says 186 applicants need an overall IELTS score of 6.0. The
 *    Direct Entry stream needs Competent English, which for IELTS is at least 6
 *    in each of the four test components, not an overall score.
 *
 * 2. The draft says 482 applicants need an overall IELTS score of 5.0, from a
 *    migration agent. The Core Skills stream page sets "minimum standards of
 *    English language proficiency" with exemptions, so readers are sent to the
 *    official test score table instead of a quoted number.
 *
 * 3. The draft says a 482 leads to a 186 after "roughly 2 years in the same
 *    occupation". The Temporary Residence Transition stream asks for 2 years of
 *    eligible sponsored employment in the 3 years before applying.
 *
 * 4. The draft says the 494 leads to permanent residence after 3 years without
 *    naming the visa. It is the subclass 191, and the 494 limits you to
 *    designated regional areas for 5 years.
 *
 * 5. The draft quotes nomination processing of "17 days to 9 months" from a
 *    private site. Home Affairs publishes processing times in its own tool.
 *
 * 6. The draft leaves out the points-tested visas that need no job offer, the
 *    Working Holiday age limits and ballot, and student work rights, and names
 *    the "Seasonal Worker Programme", which is now part of the PALM scheme.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ForeignWorkerJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-482-visa-sponsorship-jobs.html';

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
        $title = 'How Foreign Workers Can Get a Job in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Foreign workers get jobs in Australia through employer sponsorship on the 482, points-tested visas that need no job offer, working holidays and student visas. The 2026 income threshold is AUD 79,423, and Pakistan is not in the working holiday program.',
                'content' => $content,
                'featured_image' => 'blogs/how-foreign-workers-can-get-a-job-in-australia.jpg',
                'tags' => 'how can foreign workers get a job in australia, jobs in australia for foreigners, skills in demand visa 482, core skills income threshold 2026, australia work visa without job offer, working holiday visa australia, palm scheme australia, student visa work hours australia',
                'meta_title' => 'How Foreign Workers Can Get a Job in Australia (2026)',
                'meta_description' => 'How foreign workers get jobs in Australia in 2026: employer sponsorship, skilled visas without a job offer, working holidays and student work rights.',
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
            ['name' => 'Australian Employers Hiring Overseas Workers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-overseas-worker-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Overseas Worker Jobs — Sponsored Skilled, Regional and Working Holiday Roles, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time for sponsored roles; working holiday roles are often casual or seasonal',
                'language' => 'English',
                // Sponsored pay must meet the market rate and income threshold,
                // and casual pay varies by award, so no band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Roles with Australian employers open to overseas workers: sponsored skilled and regional positions, and casual work for working holiday visa holders.',
                'seo_keywords' => 'jobs in australia for foreigners, overseas worker jobs australia, 482 visa sponsorship jobs, regional sponsorship jobs, working holiday jobs australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australian employers hire overseas workers through the Skills in Demand (subclass 482) and regional (subclass 494) sponsored visas, and hire working holiday makers for casual and seasonal work.</p>

<h3>Requirements for sponsored roles</h3>
<ul>
    <li>An occupation the visa stream accepts, such as one on the Core Skills Occupation List</li>
    <li>At least 1 year of relevant work experience for the 482 Core Skills stream</li>
    <li>A skills assessment where your occupation requires one, and minimum English standards</li>
    <li>Pay at the market rate and no less than the Core Skills Income Threshold, AUD 79,423 for nominations from 1 July 2026</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> sponsorship decisions, pay and visa outcomes are set by each employer and the Department of Home Affairs &mdash; not by JobGader. Never pay an employer or agent in return for a sponsored job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Most foreign workers get a job in Australia through employer sponsorship on the Skills in Demand visa (subclass 482), which needs a job offer, at least 1 year of relevant experience and pay of at least AUD 79,423 in the Core Skills stream.</strong> Skilled workers can also apply for points-tested visas <strong>without a job offer</strong>, people aged 18 to 30 (35 for some countries) from eligible countries can come on a <strong>Working Holiday</strong> visa, and international students can work <strong>48 hours a fortnight</strong> during term. Which route fits depends on your occupation, age and passport.</p>

<p>This guide compares every route, shows who each one suits, and explains how to apply without falling for sponsorship scams.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-482-visa-sponsorship-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127462;&#127482; Browse Visa Sponsorship Jobs in Australia &rarr;
    </a>
</div>

<h2>Every Route In, Compared</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Visa</th>
            <th style="padding:10px;text-align:left;">Job offer needed?</th>
            <th style="padding:10px;text-align:left;">Best for</th>
            <th style="padding:10px;text-align:left;">Length and next step</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skills in Demand (482)</strong></td><td style="padding:10px;">Yes, a sponsoring employer</td><td style="padding:10px;">Skilled workers with an offer</td><td style="padding:10px;">Up to 4 years; can lead to the 186</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Employer Nomination Scheme (186)</strong></td><td style="padding:10px;">Yes</td><td style="padding:10px;">Permanent residence through an employer</td><td style="padding:10px;">Permanent</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skilled Employer Sponsored Regional (494)</strong></td><td style="padding:10px;">Yes, a regional employer</td><td style="padding:10px;">Workers willing to live regionally</td><td style="padding:10px;">5 years; permanent residence through the 191 after 3 years</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skilled Independent (189)</strong></td><td style="padding:10px;"><strong>No</strong></td><td style="padding:10px;">Skilled workers who score 65 points or more</td><td style="padding:10px;">Permanent, by invitation</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Working Holiday (417) and Work and Holiday (462)</strong></td><td style="padding:10px;">No</td><td style="padding:10px;">Ages 18 to 30, or 35 for some countries</td><td style="padding:10px;">Temporary; usually 6 months per employer</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Student (500)</strong></td><td style="padding:10px;">No</td><td style="padding:10px;">People who want to study first</td><td style="padding:10px;">In line with your course; 48 hours' work a fortnight in term</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>PALM scheme</strong></td><td style="padding:10px;">Yes, through an approved employer</td><td style="padding:10px;">Citizens of Pacific Island countries and Timor-Leste</td><td style="padding:10px;">Seasonal and longer-term placements</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Department of Home Affairs visa listings, September 2026.</p>
</div>

<p>If you are weighing up sponsorship in detail, our <a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> guide covers the 482, 186 and 494 rules step by step.</p>

<h2>Route 1: Employer Sponsorship on the 482</h2>

<p>The Skills in Demand visa replaced the Temporary Skill Shortage (TSS) visa on <strong>7 December 2024</strong>. It keeps the subclass number 482 and runs in Specialist Skills, Core Skills and Labour Agreement streams. For the <strong>Core Skills stream</strong>, Home Affairs says you must:</p>

<ul>
    <li>be nominated by an approved sponsor for an occupation on the <strong>Core Skills Occupation List (CSOL)</strong>;</li>
    <li>have <strong>at least 1 year</strong> of relevant work experience in the occupation or a related field;</li>
    <li>have a skills assessment if your occupation requires one;</li>
    <li>meet minimum standards of English language proficiency, unless you are exempt;</li>
    <li>be paid the annual market salary rate for the job and <strong>no less than the Core Skills Income Threshold</strong>.</li>
</ul>

<p>The visa lets you stay <strong>up to 4 years</strong>, and you work only for your sponsor or an associated entity.</p>

<h3>The income thresholds from 1 July 2026</h3>

<ul>
    <li><strong>Core Skills Income Threshold (CSIT): AUD 79,423</strong> for nominations lodged between 1 July 2026 and 30 June 2027, up from AUD 76,515. It also applies to 186 nominations.</li>
    <li><strong>Specialist Skills Income Threshold: AUD 146,576</strong> for the same period.</li>
</ul>

<p>The threshold is a floor, not the offer: the employer must also pay what an Australian worker in the same job would earn.</p>

<h3>From the 482 to permanent residence</h3>

<p>The <strong>Employer Nomination Scheme (subclass 186)</strong> Temporary Residence Transition stream asks for <strong>2 years of eligible sponsored employment in the 3 years</strong> before you apply. The Direct Entry stream is for people who apply without that history, and usually needs you to be <strong>under 45</strong>, have <strong>at least 3 years</strong> of relevant work experience, hold a positive skills assessment and have Competent English. For IELTS, Competent English means <strong>at least 6 in each of the four test components</strong>, not an overall score of 6.</p>

<h2>Route 2: Regional Sponsorship on the 494</h2>

<p>The <strong>Skilled Employer Sponsored Regional (subclass 494)</strong> visa lets you live, work and study only in designated regional areas for <strong>5 years</strong>. After holding it for <strong>at least 3 years</strong>, you may apply for permanent residence through the <strong>Permanent Residence (Skilled Regional) visa (subclass 191)</strong>. You usually need to be under 45 and have a relevant skills assessment.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-foreign-workers-can-get-a-job-in-australia-visa-steps.jpg"
         alt="A smiling traveller with a backpack holding a passport and boarding pass at an airport window, beside steps reading find a suitable job opportunity, get the right visa, meet the requirements, prepare your documents and travel to Australia"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Route 3: A Skilled Visa Without a Job Offer</h2>

<p>Many guides say you cannot work in Australia without an employer. The <strong>Skilled Independent visa (subclass 189)</strong> is permanent and needs no sponsor. Home Affairs says you must:</p>

<ul>
    <li>submit an expression of interest in <strong>SkillSelect</strong> and be <strong>invited to apply</strong>;</li>
    <li>score <strong>65 points or more</strong> on the points test;</li>
    <li>be <strong>under 45</strong> when you are invited;</li>
    <li>have a suitable skills assessment for an occupation on the relevant skilled occupation list.</li>
</ul>

<p>Invitations go to the highest-scoring candidates, so 65 points is the minimum, not a guarantee. State and territory nominated (190) and regional (491) visas work in a similar way with a nomination instead.</p>

<h2>Route 4: Working Holiday Visas</h2>

<p>The <strong>Working Holiday (417)</strong> and <strong>Work and Holiday (462)</strong> visas are for people aged <strong>18 to 30, or 35 for some countries</strong>. You can do any kind of work, but usually only for <strong>6 months with the same employer</strong>.</p>

<ul>
    <li><strong>India, China and Vietnam</strong> passport holders must enter a <strong>ballot</strong> and be randomly selected before applying for a first 462 visa.</li>
    <li><strong>Pakistan, Nigeria, Bangladesh and many other countries are not in the program</strong>, so check the Home Affairs list of eligible countries before planning around it.</li>
</ul>

<h2>Route 5: Study First, or the PALM Scheme</h2>

<ul>
    <li><strong>Student visa (subclass 500):</strong> you can work up to <strong>48 hours a fortnight</strong> while your course is in session. Students doing a masters by research or doctorate have no work limit. It is a study visa, not a work visa, so your course must come first.</li>
    <li><strong>Pacific Australia Labour Mobility (PALM) scheme:</strong> brings workers from Pacific Island countries and Timor-Leste to approved Australian employers in sectors such as agriculture, meat processing and care. It replaced the old Seasonal Worker Programme and Pacific Labour Scheme, and is not open to other nationalities.</li>
</ul>

<h2>How to Apply for a Sponsored Job, Step by Step</h2>

<ol>
    <li><strong>Check your occupation</strong> on the Core Skills Occupation List and whether it needs a skills assessment.</li>
    <li><strong>Get your skills assessment and English test</strong> done early, as they take time and every route asks for them.</li>
    <li><strong>Find an employer willing to sponsor.</strong> Hospitals, aged care providers, construction and mining firms, and hospitality groups are common sponsors.</li>
    <li><strong>Your employer lodges the nomination</strong> and pays the Skilling Australians Fund levy; you then lodge the visa application.</li>
    <li><strong>Complete health checks and police certificates</strong>, then check the official processing times tool rather than third-party estimates.</li>
</ol>

<p><strong>Watch for scams.</strong> Home Affairs sets rules so that employers do not "exploit skilled workers to recoup financial benefits in return for visa sponsorship". Never pay an employer or agent for a sponsored job, and check any migration agent on the official register.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I work in Australia without a job offer?</h3>
<p>Yes, on some visas. The Skilled Independent visa (subclass 189) needs no sponsor if you are invited and score at least 65 points, and Working Holiday and student visas let you look for work once you arrive.</p>

<h3>What is the minimum salary for a 482 visa in 2026?</h3>
<p>For the Core Skills stream, the Core Skills Income Threshold is AUD 79,423 for nominations lodged from 1 July 2026 to 30 June 2027. The employer must also pay the market rate if that is higher.</p>

<h3>How much work experience do I need for a 482 visa?</h3>
<p>At least 1 year of relevant work experience in your nominated occupation or a related field for the Core Skills stream, plus a skills assessment if your occupation requires one.</p>

<h3>Does a 482 visa lead to permanent residence?</h3>
<p>It can. The 186 Temporary Residence Transition stream asks for 2 years of eligible sponsored employment in the 3 years before you apply.</p>

<h3>What English score do I need for a 186 visa?</h3>
<p>The Direct Entry stream needs Competent English. For IELTS, that is at least 6 in each of the four test components, not an overall score.</p>

<h3>Can Pakistani citizens get a working holiday visa for Australia?</h3>
<p>No. Pakistan is not in Australia's Working Holiday Maker program. Indian citizens can apply for the Work and Holiday (462) visa only if they are selected in the ballot.</p>

<h3>How many hours can international students work in Australia?</h3>
<p>Up to 48 hours a fortnight while the course is in session. Students studying a masters by research or a doctorate have no work limit.</p>

<h3>Is there an age limit for skilled visas?</h3>
<p>Yes. The 189 needs you to be under 45 when invited, and the 186 Direct Entry stream and the 494 usually need you to be under 45 when you apply, with some exemptions.</p>

<h2>People Also Search For</h2>

<h3>Jobs in Australia for foreigners</h3>
<p>Mostly sponsored skilled roles, plus casual work for working holiday makers.</p>

<h3>Skills in Demand visa requirements</h3>
<p>A sponsor, a CSOL occupation, 1 year of experience, English and the income threshold.</p>

<h3>Core Skills Income Threshold 2026</h3>
<p>AUD 79,423 for nominations lodged from 1 July 2026.</p>

<h3>Australia work visa without job offer</h3>
<p>The points-tested Skilled Independent visa (subclass 189).</p>

<h3>Working holiday visa age limit Australia</h3>
<p>18 to 30, or 35 for some countries.</p>

<h3>494 visa to PR</h3>
<p>Through the subclass 191 after holding the 494 for at least 3 years.</p>

<h3>Student visa work hours Australia</h3>
<p>48 hours a fortnight while the course is in session.</p>

<h3>PALM scheme countries</h3>
<p>Pacific Island countries and Timor-Leste.</p>

<h2>More Job Guides</h2>

<p>Comparing countries or looking at a specific job in Australia? These cover it:</p>

<ul>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; the 482, 186 and 494 rules in detail.</li>
    <li><a href="/blog/personal-care-assistant-jobs-in-australia">Personal Care Assistant Jobs in Australia</a> &mdash; aged care, one of the sectors that sponsors most.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; trades work and award pay.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; casual work for working holiday makers and students.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; the same question for Canada.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the UK's Skilled Worker route compared.</li>
    <li><a href="/blog/education-assistant-jobs-in-australia">Education Assistant Jobs in Australia</a> &mdash; the job title in each state, WA pay of $34.75 to $43.28 an hour, and the checks and certificates schools ask for.</li>
    <li><a href="/blog/how-to-apply-for-qantas-ground-staff-jobs-in-australia">How to Apply for Qantas Ground Staff Jobs in Australia</a> &mdash; the pay Qantas will not publish, from the agreement that does.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not migration advice. Visa rules, income thresholds, occupation lists and eligible countries change. Confirm the current position with the Department of Home Affairs or a registered migration agent before applying.</p>
HTML;
    }
}
