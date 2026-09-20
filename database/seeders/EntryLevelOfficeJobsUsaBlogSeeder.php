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
 * "How to Get an Entry-Level Office Job With No Experience" — the US
 * entry-level office hub, priced from BLS rather than job boards, and honest
 * about an occupational group the BLS projects to shrink.
 *
 * Corrections to the draft (checked against the BLS Occupational Outlook
 * Handbook, OEWS wage tables and the FTC, September 2026):
 *
 * 1. The draft's headline pay, "$18.94 an hour, or $46,000 to $52,000 a
 *    year", is both inflated and internally inconsistent: $18.94 an hour over
 *    2,080 hours is $39,395, not $46,000. BLS medians for these roles run
 *    from $38,010 to $47,540.
 *
 * 2. The draft puts customer service representatives at $38,964. The BLS
 *    median is $44,770.
 *
 * 3. The draft puts "medical biller" at $46,294 and lists it as a
 *    no-experience office job. The closest BLS occupation, medical records
 *    specialists, pays a median of $51,140 and is the one role here that
 *    needs a postsecondary nondegree award, with no on-the-job training.
 *
 * 4. The draft puts order entry clerks at $48,034. The BLS median for order
 *    clerks is $46,170.
 *
 * 5. The draft's city figures are inflated, the two Texas metros badly. For
 *    general office clerks the BLS annual mean was $46,870 in New Jersey,
 *    $40,930 in Houston and $39,800 in San Antonio, against the draft's
 *    $52,261, $49,158 and $46,431.
 *
 * 6. The biggest correction is the one the draft never mentions. BLS projects
 *    the whole office and administrative support group to decline: general
 *    office clerks -6%, customer service representatives -5%, receptionists
 *    -2% and secretaries and administrative assistants -2% over 2025-35. The
 *    openings are real but they come from turnover, not growth.
 *
 * 7. The draft says employers consistently provide on-the-job training across
 *    all these roles. That holds for six of them and not for medical records
 *    specialists, where formal schooling replaces it.
 *
 * 8. The draft has no scam section. The FTC's rule is that honest employers
 *    never ask you to pay to get a job, and entry-level office and data roles
 *    are a standard target for fake-check and reshipping schemes.
 *
 * Percentile and state or metro figures are the May 2023 OEWS tables, the
 * most recent published in that form; medians are May 2025.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EntryLevelOfficeJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-entry-level-office-jobs.html';

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
        $title = 'How to Get an Entry-Level Office Job With No Experience';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A high school diploma and Microsoft Office are genuinely enough to get hired, but the pay is lower than the job boards claim and BLS projects most of these roles to shrink. Here are the real medians and where the openings come from.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-an-entry-level-office-job-with-no-experience.jpg',
                'tags' => 'entry level office jobs, office jobs no experience, general office clerk salary, receptionist jobs usa, customer service representative pay, administrative assistant entry level, office jobs no degree, bls office wages',
                'meta_title' => 'Entry-Level Office Jobs With No Experience: Real BLS Pay',
                'meta_description' => 'Entry-level office jobs in the USA: the real BLS medians, why most of these roles are projected to decline, and what employers actually require.',
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
            ['name' => 'US Employers Hiring Entry-Level Office Support (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'us-entry-office-aggregated']
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
                'position' => 'Entry-Level Office Assistant, Receptionist and Clerical Support, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with part-time and temp-to-hire openings common',
                'language' => 'English',
                // Pay spans several BLS occupations from $38,010 to $47,540,
                // so the guide quotes each occupation rather than one band.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Entry-level office, reception and clerical openings with US employers, most requiring a high school diploma and short on-the-job training.',
                'seo_keywords' => 'entry level office jobs, office jobs no experience, receptionist jobs, general office clerk jobs, clerical jobs usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Offices, clinics, insurance agencies, schools and logistics firms hire entry-level clerical staff continuously, mostly to replace people who move on rather than to expand their teams.</p>

<h3>What the work involves</h3>
<p>Answering and routing calls, greeting visitors, filing and scanning, entering and checking records, scheduling, handling mail and supporting the people who do the specialist work.</p>

<h3>Common requirements</h3>
<ul>
    <li>A high school diploma or GED</li>
    <li>Working knowledge of Word, Excel and Outlook, or Google Workspace</li>
    <li>Clear phone and written communication</li>
    <li>Accuracy with records and attention to detail</li>
    <li>Authorization to work in the United States</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> the wages and employment projections in this guide are published by the Bureau of Labor Statistics &mdash; not by JobGader. No legitimate employer charges you to be hired; the FTC's rule is that honest employers never ask you to pay to get a job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>A high school diploma or GED, working knowledge of Word, Excel and Outlook, and the ability to hold a professional phone conversation really are enough to be hired into most entry-level office roles.</strong> No degree, no prior office experience, and employers train you on their own systems.</p>

<p>Two things the job boards will not tell you, though. The pay is lower than their averages suggest, and the Bureau of Labor Statistics expects this whole group of occupations to <strong>shrink</strong> over the next decade. Neither is a reason to avoid the work. Both are reasons to go in with the right plan.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-entry-level-office-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; Browse Entry-Level Office Jobs &rarr;
    </a>
</div>

<h2>What These Jobs Actually Pay</h2>

<p>Most guides blend every clerical title into one number, usually around $18.94 an hour or "$46,000 to $52,000 a year". Those two claims do not even agree with each other: <strong>$18.94 an hour across a 2,080-hour year is $39,395</strong>, not $46,000. The honest answer is that these are different occupations with different medians.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Occupation</th>
            <th style="padding:10px;text-align:left;">Median a year</th>
            <th style="padding:10px;text-align:left;">Median an hour</th>
            <th style="padding:10px;text-align:left;">Employed</th>
            <th style="padding:10px;text-align:left;">Projected 2025-35</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">General office clerks</td><td style="padding:10px;"><strong>$45,010</strong></td><td style="padding:10px;">$21.64</td><td style="padding:10px;">2.6 million</td><td style="padding:10px;"><strong>-6%</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Customer service representatives</td><td style="padding:10px;"><strong>$44,770</strong></td><td style="padding:10px;">$21.53</td><td style="padding:10px;">2.67 million</td><td style="padding:10px;"><strong>-5%</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Receptionists and information clerks</td><td style="padding:10px;"><strong>$38,010</strong></td><td style="padding:10px;">$18.27</td><td style="padding:10px;">947,500</td><td style="padding:10px;"><strong>-2%</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Secretaries and administrative assistants</td><td style="padding:10px;"><strong>$47,540</strong></td><td style="padding:10px;">$22.86</td><td style="padding:10px;">1.89 million</td><td style="padding:10px;"><strong>-2%</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Order clerks</td><td style="padding:10px;"><strong>$46,170</strong></td><td style="padding:10px;">$22.20</td><td style="padding:10px;">89,500</td><td style="padding:10px;">Declining</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Medical records specialists</td><td style="padding:10px;"><strong>$51,140</strong></td><td style="padding:10px;">$24.59</td><td style="padding:10px;">200,700</td><td style="padding:10px;"><strong>+8%</strong></td></tr>
    </tbody>
</table>
</div>

<p>Three corrections worth naming, because they run in both directions. Customer service representatives are usually quoted at about <strong>$38,964</strong>; the BLS median is <strong>$44,770</strong>, so that one is understated by nearly $6,000. Order entry clerks are usually quoted at <strong>$48,034</strong> against a real median of <strong>$46,170</strong>. And "medical biller", quoted at <strong>$46,294</strong>, is not really one of these jobs at all &mdash; see below.</p>

<p>Across the whole office and administrative support group, <strong>17.75 million people</strong> are employed at a median of <strong>$47,450</strong>.</p>

<h2>The Part the Job Boards Leave Out: This Group Is Shrinking</h2>

<p>Every guide presents entry-level office work as a wide-open field. BLS does not. It projects the <strong>office and administrative support group as a whole to decline</strong> through 2035, and the individual declines are not trivial: general office clerks <strong>-6%</strong>, customer service representatives <strong>-5%</strong>, receptionists and administrative assistants <strong>-2%</strong> each.</p>

<p>And yet BLS also projects roughly <strong>1.7 million openings a year</strong> in this group &mdash; about 249,000 for general office clerks and 289,500 for customer service representatives alone. Both things are true at once, and the explanation matters for how you job hunt:</p>

<ul>
    <li><strong>The openings are replacement openings.</strong> People leave these roles constantly, for other jobs or out of the workforce. That churn, not growth, is what you are applying into.</li>
    <li><strong>So getting in is genuinely easy, and staying still is not a plan.</strong> Treat the first office job as a doorway. The people who do well move into a specialism &mdash; records, billing, HR support, scheduling, payroll &mdash; within a couple of years.</li>
    <li><strong>Avoid the roles automation is hitting hardest.</strong> Pure keying work is the clearest example; our <a href="/blog/data-entry-jobs-in-usa">data entry jobs guide</a> covers how steep that particular decline is.</li>
</ul>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-get-an-entry-level-office-job-with-no-experience-desk.jpg" alt="Office assistant reviewing files and a spreadsheet at a desk" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The One Role on This List That Is Not a No-Experience Job</h2>

<p>Most guides list "medical biller" alongside receptionist and data entry clerk, as though it were the same kind of opening. It is not, and the difference is worth knowing before you apply.</p>

<p><strong>Medical records specialists</strong> are the only occupation here that BLS expects to <strong>grow, by 8%</strong>, and they are also the only one where the typical entry requirement is a <strong>postsecondary nondegree award</strong> rather than a high school diploma, with <strong>no on-the-job training</strong> listed because the schooling does that job instead. The median is <strong>$51,140</strong>, the highest on the list.</p>

<p>That is the actual trade. If you want the growing, better-paid end of office work, it costs you a certificate first. If you want to start next month with what you already have, the other roles are open to you today. Note too that "medical biller" in the strict sense sits in a different occupation again, billing and posting clerks, so job titles in this corner of the market are unusually loose.</p>

<h2>Where You Work Changes the Number</h2>

<p>City comparisons circulating online are inflated, in some metros badly. These are the BLS annual mean wages for general office clerks, from the most recent OEWS tables published in this form (May 2023), against the figures the job boards quote:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">BLS annual mean</th>
            <th style="padding:10px;text-align:left;">Commonly quoted</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New Jersey</td><td style="padding:10px;"><strong>$46,870</strong></td><td style="padding:10px;">$52,261</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Houston-The Woodlands-Sugar Land</td><td style="padding:10px;"><strong>$40,930</strong></td><td style="padding:10px;">$49,158</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">San Antonio-New Braunfels</td><td style="padding:10px;"><strong>$39,800</strong></td><td style="padding:10px;">$46,431</td></tr>
    </tbody>
</table>
</div>

<p>Wages have risen since that reference period, but not by the 20% to 30% the Houston and San Antonio gaps would require. Use these as your floor when you negotiate, and check the current figure for your own metro.</p>

<h2>What Employers Actually Ask For</h2>

<p>For six of the seven occupations above, BLS lists the typical entry-level education as a <strong>high school diploma or equivalent</strong>, no prior work experience, and <strong>short-term on-the-job training</strong>. That is not marketing; it is the official profile of the work.</p>

<ul>
    <li><strong>Microsoft Office or Google Workspace.</strong> The single most consistently requested technical skill. Word, Excel and Outlook at a working level beats a certificate in something unrelated.</li>
    <li><strong>Phone and in-person communication.</strong> Retail and food service experience transfers here directly, and saying so plainly is more persuasive than calling yourself a people person.</li>
    <li><strong>Accuracy.</strong> Most of this work is records that other people rely on. Give an example of catching an error rather than claiming attention to detail.</li>
    <li><strong>Reliability.</strong> In a job filled mostly because someone left, an employer is buying the odds that you will still be there in a year.</li>
</ul>

<h2>Before You Accept Anything: The Scam Pattern</h2>

<p>Entry-level office and data roles are one of the most heavily targeted categories for job scams, because the applicant pool is large and often new to hiring. The Federal Trade Commission's rule is short enough to memorise: <strong>honest employers, including the federal government, will never ask you to pay to get a job.</strong></p>

<ul>
    <li><strong>Any payment request is the end of the conversation</strong> &mdash; training, equipment, background check, software, "starter kit".</li>
    <li><strong>Fake check schemes.</strong> You are sent a check, told to buy equipment and wire back the balance. The check bounces weeks later and the money is yours to repay.</li>
    <li><strong>Reshipping "quality control" roles.</strong> Repackaging goods at home is a way of moving stolen merchandise, and you carry the risk.</li>
    <li><strong>Bank details or your Social Security number before a real offer.</strong> Those belong on payroll paperwork after you are hired, not in an application.</li>
    <li><strong>Search the company name with the word scam</strong> before you accept, and report anything suspicious at ReportFraud.ftc.gov.</li>
</ul>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-get-an-entry-level-office-job-with-no-experience-interview.jpg" alt="Candidate in an interview with a hiring panel in a meeting room" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>How to Actually Get Hired</h2>

<ol>
    <li><strong>Apply across titles.</strong> Office clerk, receptionist, administrative assistant and customer service postings overlap heavily in what they ask for. Applying to one title at a time is the most common way people slow themselves down.</li>
    <li><strong>Put the software at the top of the resume.</strong> Name the programs. A hiring manager scanning for "Excel" should not have to hunt.</li>
    <li><strong>Translate the job you already have.</strong> Handling a register, a queue or a phone is the same competence, described differently.</li>
    <li><strong>Use temp agencies deliberately.</strong> In a market where most openings come from turnover, temp-to-hire is a fast way in, and the agency does the searching.</li>
    <li><strong>Aim at a specialism from day one.</strong> Records, billing, payroll or HR support pay more and, in the medical records case, actually grow.</li>
    <li><strong>Only pay for a credential with a purpose.</strong> A general business certificate rarely changes an entry-level decision. The postsecondary award for medical records work does, because it is the requirement.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a degree for an entry-level office job?</h3>
<p>No. BLS lists a high school diploma or equivalent as the typical entry-level education for office clerks, receptionists, customer service representatives and administrative assistants, with no prior experience required.</p>

<h3>What do entry-level office jobs really pay?</h3>
<p>By BLS medians: receptionists $38,010, customer service representatives $44,770, general office clerks $45,010, order clerks $46,170 and administrative assistants $47,540 a year.</p>

<h3>Is the blended "$18.94 an hour" figure accurate?</h3>
<p>It is close to the receptionist median and low for the others, and it does not support the $46,000 to $52,000 annual range quoted beside it. That hourly rate works out at $39,395 a year.</p>

<h3>Are office jobs a growing field?</h3>
<p>No. BLS projects the office and administrative support group to decline through 2035, with general office clerks down 6% and customer service representatives down 5%. The openings come from turnover.</p>

<h3>If the field is shrinking, is it still worth entering?</h3>
<p>Yes, because roughly 1.7 million openings a year still arise from replacement. Treat it as an entry point and move toward a specialism rather than staying in a general clerical role indefinitely.</p>

<h3>Which entry-level office job pays best?</h3>
<p>Medical records specialist, at a median of $51,140, and it is the only one BLS projects to grow. It needs a postsecondary nondegree award rather than just a diploma.</p>

<h3>What software should I learn before applying?</h3>
<p>Word, Excel and Outlook, or the Google Workspace equivalents. It is the most consistently requested requirement across these postings and free tutorials are enough to start.</p>

<h3>How do I spot an entry-level office job scam?</h3>
<p>Any request for payment ends it. The FTC's rule is that honest employers never ask you to pay to get a job, and fake-check and reshipping schemes target this category heavily.</p>

<h2>People Also Search For</h2>

<h3>General office clerk salary</h3>
<p>A median of $45,010 a year, or $21.64 an hour, across 2.6 million jobs.</p>

<h3>Receptionist pay per hour</h3>
<p>A median of $18.27 an hour, the lowest of the common entry-level office roles.</p>

<h3>Office jobs with no degree</h3>
<p>Clerk, receptionist, customer service and administrative assistant roles all list a high school diploma as the typical entry.</p>

<h3>Customer service representative salary</h3>
<p>A median of $44,770 a year, well above the $38,964 quoted on most salary pages.</p>

<h3>Medical records specialist requirements</h3>
<p>A postsecondary nondegree award, with a median of $51,140 and 8% projected growth.</p>

<h3>Office and administrative support outlook</h3>
<p>Projected to decline through 2035, with about 1.7 million replacement openings a year.</p>

<h3>Entry-level office jobs near me</h3>
<p>Wages vary by metro: the mean for general office clerks ran from about $39,800 in San Antonio to $46,870 in New Jersey.</p>

<h3>Temp to hire office jobs</h3>
<p>A practical route in a market where most openings come from turnover rather than new positions.</p>

<h2>More Job Guides</h2>

<p>Looking at the specific roles? These go deeper:</p>

<ul>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; the executive, medical and legal branches and what each pays.</li>
    <li><a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a> &mdash; pay, outlook and the skills that move you up.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; the real median and the steep decline behind it.</li>
    <li><a href="/blog/how-to-become-a-remote-virtual-assistant">How to Become a Remote Virtual Assistant</a> &mdash; the remote version of this work and what it pays after platform fees.</li>
    <li><a href="/blog/online-data-entry-jobs">Online Data Entry Jobs</a> &mdash; real pay and the scam signs to check first.</li>
    <li><a href="/blog/remote-jobs-in-usa">Remote Jobs in USA</a> &mdash; which office work is genuinely remote.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Bureau of Labor Statistics Occupational Outlook Handbook profiles, OEWS wage tables and Employment Projections, and Federal Trade Commission consumer guidance. Medians are May 2025; state and metro figures are the May 2023 OEWS tables. Wages and projections are revised regularly, so check the current BLS page before you rely on a number.</p>
HTML;
    }
}
