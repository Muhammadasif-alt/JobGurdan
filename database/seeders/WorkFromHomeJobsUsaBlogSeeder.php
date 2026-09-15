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
 * "Work From Home Jobs in USA" — the money and rights side of working from
 * home for an American employer or client. The remote jobs guide owns the
 * telework rate, the I-9 and who a US remote job is open to; this one owns
 * BLS pay for the categories people search for, W-2 versus 1099 work and the
 * 2026 reporting threshold, home office and equipment costs, paid time at
 * home under the FLSA, and how today's job scams work.
 *
 * Corrections to the draft:
 *
 * 1. Its entry band of $30,000 to $40,000 for customer service and data entry
 *    is below the BLS May 2025 medians of $44,770 for customer service
 *    representatives and $41,340 for data entry keyers, and its specialized
 *    band tops out far below the $135,980 median for software developers.
 *
 * 2. It mentions freelance work without the tax consequences. A contractor
 *    pays 15.3 per cent self-employment tax, and for payments made in 2026 a
 *    client files Form 1099-NEC from $2,000, up from $600.
 *
 * 3. It tells readers to set up a home office without saying who pays. W-2
 *    employees cannot deduct a home office or unreimbursed work expenses; the
 *    deduction is for the self-employed, and California requires employers to
 *    reimburse necessary work expenses.
 *
 * 4. Its scam list misses the fastest-growing type. The FTC reports job scam
 *    losses rose from $90 million in 2020 to $501 million in 2024, driven by
 *    task scams that ask workers to pay to unlock earnings.
 *
 * 5. Its apply link searches Indeed with a query string rather than the
 *    site's own work from home search page.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class WorkFromHomeJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-work-from-home-jobs.html';

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
        $title = 'Work From Home Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Customer service and data entry pay more than the usual entry band, 1099 contractors pay 15.3 per cent self-employment tax and get a 1099-NEC from $2,000 in 2026, W-2 employees cannot deduct a home office, and short breaks at home are paid.',
                'content' => $content,
                'featured_image' => 'blogs/work-from-home-jobs-in-usa.jpg',
                'tags' => 'work from home jobs usa, work from home jobs salary, w2 vs 1099 remote work, 1099-nec threshold 2026, home office deduction employees, remote work expense reimbursement california, paid breaks working from home, work from home job scams, task scams, remote customer service jobs',
                'meta_title' => 'Work From Home Jobs in USA 2026: Pay, Taxes and Your Rights',
                'meta_description' => 'Work from home jobs in the USA: BLS pay for remote roles, the 2026 $2,000 1099 rule, who can deduct a home office, paid breaks at home and job scam signs.',
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
            ['name' => 'U.S. Remote-First & Hybrid Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-work-from-home-aggregated']
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
                'position' => 'Work From Home — Remote Customer Service, Administrative, Data and Technology Roles, U.S. Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Full-time and part-time; many roles set core hours in a U.S. time zone',
                'language' => 'English',
                // Remote pay depends entirely on the occupation, so no single
                // range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Work from home roles with U.S. employers in customer service, administration, data, healthcare billing and technology.',
                'seo_keywords' => 'work from home jobs, remote customer service jobs, remote admin jobs, remote data entry jobs, remote medical coding jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Remote-first and hybrid employers across the United States hire home-based staff for customer service, administrative support, data work, medical billing and coding, and technology roles.</p>

<h3>What the work involves</h3>
<p>Handling customer calls and chats, scheduling and email support, data entry and records work, claims and billing, or technical support, using video, messaging and ticketing tools from a home workspace.</p>

<h3>Requirements</h3>
<ul>
    <li>Work authorization in the United States for W-2 roles, verified on Form I-9</li>
    <li>Reliable high-speed internet and a quiet workspace; some employers supply the computer and headset</li>
    <li>Availability during the employer's core hours and time zone</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured pay.</strong> BLS May 2025 medians include $44,770 for customer service representatives and $41,340 for data entry keyers</li>
    <li><strong>W-2 or 1099.</strong> Employees have taxes withheld; contractors pay 15.3 per cent self-employment tax on their own</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay for training, equipment or access to work,</strong> and confirm whether the role is W-2 employment or 1099 contract work.</p>

<p><strong>Note:</strong> pay, classification and equipment policies are set by employers under federal and state law &mdash; not by JobGader. Confirm the details with the employer before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Work from home jobs exist in almost every American industry, from customer service and medical billing to software and accounting. The listing is the easy part. What decides whether a home-based job actually pays off is what the occupation earns, whether you are an employee or a contractor, who pays for your equipment and internet, whether your breaks are paid, and whether the "job" is a scam. This guide covers those questions. For how many Americans work remotely and who a US remote job is open to, see our <a href="/blog/remote-jobs-in-usa">Remote Jobs in USA guide</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-work-from-home-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127968; Browse Work From Home Jobs in the USA &rarr;
    </a>
</div>

<h2>What Work From Home Jobs Pay</h2>

<p>Guides quote <strong>$30,000 to $40,000</strong> for entry-level remote roles such as customer service and data entry, $45,000 to $65,000 for mid-level roles and $65,000 to $100,000 or more for specialized roles. Working from home does not have its own wage statistic; pay follows the occupation. BLS median wages for May 2025:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Occupation often done from home</th>
            <th style="padding:10px;text-align:left;">BLS median, May 2025</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Data entry keyers</td><td style="padding:10px;">$41,340</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Customer service representatives</td><td style="padding:10px;">$44,770</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Secretaries and administrative assistants</td><td style="padding:10px;">$47,540</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Medical records specialists (coding and billing)</td><td style="padding:10px;">$51,140</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Accountants and auditors</td><td style="padding:10px;">$83,680</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Software developers</td><td style="padding:10px;">$135,980</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">All occupations</td><td style="padding:10px;">$50,980</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>The entry band is low.</strong> Both customer service and data entry medians sit above its $40,000 ceiling.</li>
    <li><strong>The specialized band is far too low for tech.</strong> The software developer median is $135,980, not $65,000 to $100,000.</li>
    <li><strong>Remote does not mean higher pay.</strong> Some employers pay by location, and home-based customer service work is often hourly.</li>
</ul>

<h2>Employee or Contractor: It Changes Your Take-Home Pay</h2>

<p>Guides mention freelance and contract work as an option with less predictable income. The bigger difference is tax:</p>

<ul>
    <li><strong>W-2 employees</strong> have income and payroll taxes withheld, and the employer pays its share of Social Security and Medicare.</li>
    <li><strong>1099 contractors</strong> pay <strong>15.3 per cent self-employment tax</strong> (12.4 per cent Social Security and 2.9 per cent Medicare) on their net earnings themselves, usually through quarterly estimated payments.</li>
    <li><strong>The 2026 reporting threshold.</strong> For payments made after 31 December 2025, a business files <strong>Form 1099-NEC</strong> for a contractor paid <strong>$2,000 or more</strong> in the year, up from $600. Payment apps and platforms file Form 1099-K above <strong>$20,000 and 200 transactions</strong>. The income is taxable either way, even with no form.</li>
</ul>

<p>A $25-an-hour contract role is not the same as a $25-an-hour employee role. Before accepting 1099 work, set aside money for self-employment tax and compare it with W-2 offers on that basis.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/work-from-home-jobs-in-usa-laptop.jpg"
         alt="A smiling woman wearing white headphones typing on a laptop at a wooden desk in a bright home office overlooking a city skyline, with an American flag and a notebook beside her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Who Pays for the Home Office</h2>

<p>Guides tell you to set up a professional home office. They do not say who pays for it:</p>

<ul>
    <li><strong>W-2 employees cannot deduct it.</strong> The IRS says employees are not eligible for the home office deduction, and unreimbursed employee expenses are not deductible for most workers.</li>
    <li><strong>The self-employed can.</strong> Contractors who use part of their home regularly and exclusively for business can claim it, including under the IRS simplified method.</li>
    <li><strong>Some states require reimbursement.</strong> California Labor Code section 2802 requires employers to reimburse employees for necessary expenses they incur doing their job, and California courts have held that this covers a reasonable share of a personal phone used for work.</li>
    <li><strong>Federal minimum wage protection.</strong> Under the FLSA, the cost of tools an employer requires cannot reduce a nonexempt worker's pay below the minimum wage or overtime due.</li>
</ul>

<p>Ask at the offer stage whether the employer supplies the laptop and headset, and whether it contributes to internet or phone costs.</p>

<h2>Your Time at Home Is Still Work Time</h2>

<p>For nonexempt, hourly remote workers, the Fair Labor Standards Act applies at home the same way it does in an office. The Department of Labor's <strong>Field Assistance Bulletin 2023-1</strong> explains that:</p>

<ul>
    <li><strong>Short breaks of 20 minutes or less are paid.</strong> Getting a coffee or stretching counts as hours worked, whether you are in an office or at home.</li>
    <li><strong>Longer, genuinely free breaks can be unpaid</strong> if you are fully relieved of duties.</li>
    <li><strong>Teleworkers are covered by the PUMP Act</strong> for break time to express breast milk, including freedom from observation on a work video camera.</li>
    <li><strong>FMLA eligibility</strong> applies to teleworking employees on the same basis as office staff.</li>
</ul>

<h2>How Work From Home Scams Work Now</h2>

<p>Guides list the classic red flags: upfront fees, vague jobs with high pay, and chat-only contact. The FTC's data shows how much bigger the problem has become. Reported losses to job and employment agency scams rose from <strong>$90 million in 2020 to $501 million in 2024</strong>, and total fraud losses reported to the FTC reached <strong>$15.9 billion in 2025</strong>.</p>

<ul>
    <li><strong>Task scams.</strong> A text or WhatsApp message offers easy online work "optimizing" products or rating apps. You earn small amounts at first, then are told to deposit money, often in cryptocurrency, to unlock higher earnings you never receive. The FTC says reports went from none in 2020 to about 20,000 in the first half of 2024.</li>
    <li><strong>Fake check scams.</strong> A "new employer" sends a check to buy home office equipment from its chosen vendor. The check bounces after you have paid.</li>
    <li><strong>Reshipping and payment processing.</strong> Jobs that ask you to receive packages or move money for the company can make you part of a fraud.</li>
    <li><strong>Identity theft.</strong> Requests for your Social Security number or bank details before a verifiable offer and I-9 process.</li>
</ul>

<p>A legitimate employer never asks you to pay to work, never sends you money to forward, and verifies your work authorization through a proper onboarding process.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/work-from-home-jobs-in-usa-home-office.jpg"
         alt="A woman in headphones working on a laptop in a cozy apartment with a dog asleep on the rug and the Statue of Liberty and Manhattan skyline outside the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Popular Work From Home Job Categories</h2>

<ul>
    <li><strong>Customer service and support</strong> &mdash; phone, chat and email support, often hourly with scheduled shifts</li>
    <li><strong>Administrative and virtual assistant work</strong> &mdash; scheduling, inbox and document support</li>
    <li><strong>Medical coding and billing</strong> &mdash; usually needs a coding credential and experience</li>
    <li><strong>Sales and account management</strong> &mdash; inside sales with base pay plus commission</li>
    <li><strong>Writing, editing and marketing</strong> &mdash; often contract work paid per project</li>
    <li><strong>IT, software and QA</strong> &mdash; the highest-paid remote category</li>
    <li><strong>Tutoring and online teaching</strong> &mdash; often part-time or contract</li>
    <li><strong>Bookkeeping and accounting</strong> &mdash; W-2 and freelance roles</li>
</ul>

<h2>Where to Find Legitimate Work From Home Jobs</h2>

<ol>
    <li><strong>Job boards.</strong> Indeed's work from home search and remote filters list W-2 and contract roles.</li>
    <li><strong>Company career pages.</strong> Apply through the employer's own site to avoid impersonators.</li>
    <li><strong>LinkedIn.</strong> Check that the recruiter works for the company named in the job.</li>
    <li><strong>Freelance platforms.</strong> Useful for building experience, with the 1099 tax rules above.</li>
</ol>

<h2>Tips for Landing a Work From Home Job</h2>

<ul>
    <li><strong>Show remote habits.</strong> Written communication, meeting deadlines without supervision and familiarity with Slack, Zoom or Teams.</li>
    <li><strong>Test your setup.</strong> Internet speed, camera, microphone and a quiet space before a video interview.</li>
    <li><strong>Confirm the classification.</strong> Ask whether the role is W-2 or 1099 and whether equipment is provided.</li>
    <li><strong>State your time zone and hours.</strong> Many roles require overlap with a specific U.S. time zone.</li>
    <li><strong>Verify before sharing documents.</strong> Only provide your Social Security number and bank details after a verified offer.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do work from home jobs pay in the USA?</h3>
<p>Pay follows the occupation. BLS May 2025 medians include $41,340 for data entry keyers, $44,770 for customer service representatives, $51,140 for medical records specialists and $135,980 for software developers.</p>

<h3>What is the 1099-NEC threshold for 2026?</h3>
<p>For payments made after 31 December 2025, businesses file Form 1099-NEC for contractors paid $2,000 or more in the year, up from $600. The income is taxable even without a form.</p>

<h3>How much tax do 1099 contractors pay?</h3>
<p>On top of income tax, contractors pay 15.3 per cent self-employment tax on net earnings: 12.4 per cent for Social Security and 2.9 per cent for Medicare.</p>

<h3>Can I deduct my home office if I work from home as an employee?</h3>
<p>No. The IRS says W-2 employees are not eligible for the home office deduction. Self-employed workers who use space regularly and exclusively for business can claim it.</p>

<h3>Does my employer have to pay for my internet when I work from home?</h3>
<p>Not under federal law, unless the costs push pay below the minimum wage. Some states, such as California, require employers to reimburse necessary work expenses.</p>

<h3>Are breaks paid when working from home?</h3>
<p>For hourly nonexempt employees, breaks of 20 minutes or less count as hours worked, whether at home or in an office.</p>

<h3>What is a task scam?</h3>
<p>A fake online job, usually offered by text or WhatsApp, that pays small amounts at first and then asks you to deposit money to unlock earnings you never receive.</p>

<h3>How can I tell if a work from home job is legitimate?</h3>
<p>A real employer interviews you, never charges you to work, never sends checks for you to forward, and verifies work authorization through a formal onboarding process.</p>

<h2>People Also Search For</h2>

<h3>Work from home jobs no experience</h3>
<p>Customer service and data entry, with medians of $44,770 and $41,340.</p>

<h3>W-2 vs 1099 remote jobs</h3>
<p>Contractors pay 15.3 per cent self-employment tax themselves.</p>

<h3>1099-NEC threshold 2026</h3>
<p>$2,000 for payments made after 31 December 2025.</p>

<h3>Home office deduction for employees</h3>
<p>Not available to W-2 employees; available to the self-employed.</p>

<h3>Remote work reimbursement California</h3>
<p>Labor Code section 2802 requires reimbursement of necessary expenses.</p>

<h3>Paid breaks working from home</h3>
<p>Breaks of 20 minutes or less count as hours worked.</p>

<h3>Work from home job scams</h3>
<p>Task scams, fake checks and reshipping jobs lead the list.</p>

<h3>Remote medical coding jobs</h3>
<p>A $51,140 median for medical records specialists.</p>

<h2>More Job Guides</h2>

<p>Comparing remote and office jobs? These cover them:</p>

<ul>
    <li><a href="/blog/remote-jobs-in-usa">Remote Jobs in USA</a> &mdash; how many Americans telework, the I-9 and who remote jobs are open to.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the most common home-based job, in detail.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; what data entry pays and how the work is changing.</li>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a> &mdash; remote IT support and the certifications it asks for.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; the UK version, with flexible working rights.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or careers advice. Wage data, tax thresholds, reimbursement rules and scam patterns change and differ by state. Confirm the current position with the IRS, the Department of Labor, your state labor agency and the FTC before accepting work.</p>
HTML;
    }
}
