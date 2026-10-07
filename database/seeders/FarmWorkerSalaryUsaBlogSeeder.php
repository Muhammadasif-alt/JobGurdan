<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Farm Worker Salary in USA (2026)".
 *
 * Every BLS figure in the brief was read back from the BLS API (May 2025,
 * national, occupations 45-2092, 45-2093 and 45-2091) and matches. The worked
 * monthly figures and the PKR conversions were recomputed. What changed:
 *
 * 1. Ten Apply Now buttons are gone; the guide links only to our own pages.
 *
 * 2. "In September 2026, DOL announced potential future wage adjustments" could
 *    not be verified, so it is replaced with the court challenge to the H-2A
 *    wage method and a pointer to DOL's current rates.
 *
 * 3. The brief had five FAQs; the house format is eight, with eight People Also
 *    Search For entries.
 *
 * 4. The PKR table stays, labelled as an illustrative rate, because the brief
 *    labels it that way and the readers are largely Pakistani.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class FarmWorkerSalaryUsaBlogSeeder extends Seeder
{
    public const SLUG = 'farm-worker-salary-usa-2026';

    public function run(): void
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
                'title' => 'Farm Worker Salary in USA (2026): Average Pay, Hourly Rate and Monthly Income',
                'excerpt' => 'US crop, nursery and greenhouse farmworkers averaged $18.09 an hour and $37,630 a year in the BLS May 2025 estimates. See the hourly rates by occupation, monthly income examples, PKR conversions and how H-2A pay works.',
                'content' => $content,
                'featured_image' => 'blogs/farm-worker-salary-usa.jpg',
                'tags' => 'farm worker salary usa, average farm worker salary, farm worker hourly rate usa, farm worker monthly pay, farm worker salary in pkr, h-2a wages, farm worker pay for foreigners, bls farmworker wages',
                'meta_title' => 'Farm Worker Salary in USA (2026): Hourly and Monthly Pay',
                'meta_description' => 'Farm worker salary in the USA: BLS average and median hourly wages, monthly income examples, PKR conversions and H-2A pay rules for foreign workers.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Farm worker salary in the USA averages <strong>$18.09 per hour and $37,630 a year</strong> for crop, nursery and greenhouse workers, according to the Bureau of Labor Statistics (BLS) May 2025 estimates. The median hourly wage is $17.15. At $18.09 for 40 hours every week all year, gross monthly income works out to about $3,136. Seasonal workers often earn less over a year because their contracts cover fewer months. These figures are benchmarks, not guaranteed offers or take-home pay.</p>

<h2>Average Farm Worker Salary in the USA: What the Numbers Mean</h2>

<p>There is no single salary for every agricultural job. Crop harvesting, animal care and machinery operation are separate occupations with different national wage estimates.</p>

<p>The mean is the arithmetic average; the median is the midpoint, with half of workers earning more and half less. Compare both when you judge an offer. A national average cannot tell you the legally required rate for a particular contract.</p>

<p>These are survey estimates for May 2025, not completed full-year 2026 earnings. Offers in 2026 may differ with location, duties and the wage rules that apply to the job.</p>

<h2>Farm Worker Hourly Rate in the USA by Occupation</h2>

<p>The BLS national table gives these wage estimates:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Occupation</th>
            <th style="padding:10px;text-align:left;">Mean hourly</th>
            <th style="padding:10px;text-align:left;">Median hourly</th>
            <th style="padding:10px;text-align:left;">Mean annual</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Crop, nursery and greenhouse farmworkers</strong></td><td style="padding:10px;">$18.09</td><td style="padding:10px;">$17.15</td><td style="padding:10px;">$37,630</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Farm, ranch and aquacultural animal workers</strong></td><td style="padding:10px;">$18.88</td><td style="padding:10px;">$17.63</td><td style="padding:10px;">$39,260</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Agricultural equipment operators</strong></td><td style="padding:10px;">$21.02</td><td style="padding:10px;">$20.06</td><td style="padding:10px;">$43,710</td></tr>
    </tbody>
</table>
</div>

<p>The annual amounts are published wage estimates, not promised seasonal earnings. Equipment work needs different skills and carries different responsibilities, so do not assume a beginner starts at the equipment-operator average.</p>

<h2>Farm Worker Monthly Pay in the USA</h2>

<p>Estimate average monthly gross pay with this formula: <strong>hourly rate &times; weekly paid hours &times; 52 &divide; 12</strong>. The examples assume 40 paid hours every week and no overtime premium.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Illustrative hourly rate</th>
            <th style="padding:10px;text-align:left;">Weekly gross</th>
            <th style="padding:10px;text-align:left;">Average monthly gross</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$15</td><td style="padding:10px;">$600</td><td style="padding:10px;">$2,600</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$18</td><td style="padding:10px;">$720</td><td style="padding:10px;">$3,120</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$20</td><td style="padding:10px;">$800</td><td style="padding:10px;">$3,467</td></tr>
    </tbody>
</table>
</div>

<p>These are calculations, not recommended wages. Actual pay depends on the lawful contract rate and your recorded hours. Four weeks at this schedule is 160 hours, while an average calendar month is about 173.33 hours.</p>

<h2>Farm Worker Salary in PKR</h2>

<p>For planning only, this table assumes <strong>$1 = PKR 280</strong>. That is an illustrative rate, not a verified October 2026 exchange quote. Replace it with your bank's or remittance provider's current receiving rate before you budget.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Monthly gross (USD)</th>
            <th style="padding:10px;text-align:left;">Illustrative gross (PKR)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$2,600</td><td style="padding:10px;">PKR 728,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$3,120</td><td style="padding:10px;">PKR 873,600</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$3,467</td><td style="padding:10px;">PKR 970,760</td></tr>
    </tbody>
</table>
</div>

<p>The last row uses the rounded dollar amount shown. Your family will not receive the gross PKR figure. Living costs, deductions, transfer fees and exchange-rate margins all reduce what you can send home.</p>

<h2>Gross Income Versus Take-Home Pay</h2>

<p>Gross income is what you earn before deductions. Take-home pay is what remains after the deductions that apply. Your savings are lower again after your own spending.</p>

<p>Build a budget with separate lines for food, phone service, clothing, personal travel and emergencies, and treat each cost as location-specific. Ask for an explanation of payroll deductions instead of assuming a fixed percentage applies to everyone.</p>

<p>For illustration, $3,120 gross minus $700 in combined deductions and personal spending leaves $2,420. That $700 is a made-up budget, not a typical tax bill or a guaranteed monthly cost.</p>

<h2>Farm Worker Pay for Foreigners in the USA</h2>

<p>For eligible temporary agricultural work, H-2A wage obligations apply, and a national average does not replace the required wage for the occupation and worksite. Check the written job order and the Department of Labor's current wage information. The method DOL uses to set these wage rates has been challenged in court, so look for the latest published rates before you rely on an old table.</p>

<p>Foreign nationality is not a reason to accept less than the contract wage. Keep your payslips and your own record of hours, and ask the employer to explain any discrepancy promptly. The visa rules, requirements and application steps are in <a href="/blog/farm-worker-jobs-usa-visa-sponsorship-2026">Farm Worker Jobs in USA With Visa Sponsorship (2026)</a>.</p>

<h2>Housing, Transport and Seasonal Earnings</h2>

<p>H-2A employers must provide housing without charge to eligible workers who cannot return home daily, plus the required tools and transport between the housing and the worksite. Travel reimbursement has conditions and timing: inbound costs are generally repaid after you complete half the contract.</p>

<p>These benefits affect your budget but are not cash salary. Ask about meals and any authorised charges.</p>

<p>A six-month contract does not deliver twelve months of earnings. For example, $18 &times; 40 hours &times; 26 weeks equals $18,720 gross, assuming every scheduled hour is worked. The three-fourths guarantee applies to the contract period as a whole, not to full hours every week.</p>

<h2>How Do I Compare Two Farm Job Offers?</h2>

<p>Compare the whole contract, not only the hourly headline:</p>

<ul>
    <li>Exact duties, worksite and employment dates.</li>
    <li>Hourly or piece-rate payment.</li>
    <li>Scheduled hours and any guarantees.</li>
    <li>Housing eligibility, meals and transport.</li>
    <li>Disclosed deductions and how often you are paid.</li>
    <li>Experience requirements and the employer's contact details.</li>
</ul>

<p>Do not assume overtime automatically pays time-and-a-half. Agricultural exemptions under federal law and differing state rules can affect entitlement, so check the conditions that apply before you count extra earnings.</p>

<p>For piece-rate work, ask which unit earns payment, how completed units are counted and how the employer records hours. A per-bucket or per-box figure is hard to compare with an hourly offer unless you know realistic output and the wage protections that apply. Never budget around the fastest worker's reported production, because weather, crop quality and assignment changes all affect output. Keep a daily record of your start time, finish time, breaks and completed units, and ask for a sample explanation of the wage calculation before you accept. If an advertisement lists a broad earnings range, ask which part is guaranteed by the contract and which depends on performance.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the average hourly farmworker wage?</h3>
<p>Crop, nursery and greenhouse workers averaged $18.09 an hour in the BLS May 2025 estimates.</p>

<h3>Is the median the same as the average?</h3>
<p>No. The crop-worker median was $17.15 an hour and the mean was $18.09.</p>

<h3>Can farmworkers earn $3,000 a month?</h3>
<p>That is arithmetically possible with suitable rates and hours, but no national average guarantees it.</p>

<h3>Is the PKR amount money I can send home?</h3>
<p>No. Convert only your savings after expenses, using the actual remittance rate and fees.</p>

<h3>Does a seasonal offer guarantee annual employment?</h3>
<p>No. Use the contract's dates and hours to calculate what a season will pay.</p>

<h3>Do H-2A workers pay for their own housing?</h3>
<p>Eligible workers who cannot return home daily must receive housing at no cost, but check the contract for the terms.</p>

<h3>Do farmworkers get overtime?</h3>
<p>Not automatically. Federal agricultural exemptions and state rules decide it, so check before you count on it.</p>

<h3>Which farm job pays most?</h3>
<p>In the BLS table, agricultural equipment operators have the highest mean wage, but they need experience that beginners may not have.</p>

<h2>People Also Search For</h2>

<h3>Farm worker salary in USA</h3>
<p>$18.09 an hour on average for crop, nursery and greenhouse workers in May 2025.</p>

<h3>Average farm worker salary USA</h3>
<p>Mean and median differ, so compare both.</p>

<h3>Farm worker hourly rate USA</h3>
<p>Crop workers had a $17.15 median; equipment operators had $20.06.</p>

<h3>Farm worker monthly pay USA 2026</h3>
<p>Hourly rate &times; weekly hours &times; 52 &divide; 12.</p>

<h3>Farm worker salary in PKR</h3>
<p>Convert at your provider's real rate, after expenses.</p>

<h3>Farm worker pay for foreigners USA</h3>
<p>H-2A workers must be paid at least the required wage for the job order.</p>

<h3>H-2A wage rates</h3>
<p>Published by the Department of Labor and set by occupation and location.</p>

<h3>Farm worker jobs in USA with visa sponsorship</h3>
<p>Covered in the visa sponsorship guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/farm-worker-jobs-usa-visa-sponsorship-2026">Farm Worker Jobs in USA With Visa Sponsorship (2026)</a> &mdash; H-2A requirements and how to apply.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the Canadian route in detail.</li>
    <li><a href="/blog/fruit-picking-and-farm-jobs-in-australia-canada-and-the-uk">Fruit Picking and Farm Jobs in Australia, Canada and the UK</a> &mdash; three more seasonal routes.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or financial advice. Wage figures are from the US Bureau of Labor Statistics, May 2025 national estimates, reviewed on 7 October 2026. Monthly calculations and PKR conversions are editorial examples, not official statistics or job offers.</p>
HTML;
    }
}
