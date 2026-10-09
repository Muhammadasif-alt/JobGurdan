<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Landscaper Salary in USA (2026)".
 *
 * Every BLS figure in the brief was read back from the BLS API (May 2025,
 * national, occupations 37-3011, 37-3013 and 37-1012) and matches. The monthly
 * figures, the overtime example, the seasonal total and the PKR conversions
 * were recomputed. What changed:
 *
 * 1. Ten Apply Now buttons are gone; the guide links only to our own pages.
 *
 * 2. The brief had five FAQs; the house format is eight, with eight People Also
 *    Search For entries.
 *
 * 3. The PKR table stays, labelled as an illustrative rate, as in the farm
 *    worker salary guide.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class LandscaperSalaryUsaBlogSeeder extends Seeder
{
    public const SLUG = 'landscaper-salary-usa-2026';

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
                'title' => 'Landscaper Salary in USA (2026): Average Pay, Hourly Rate and Monthly Income',
                'excerpt' => 'US landscaping and groundskeeping workers averaged $20.33 an hour and $42,290 a year in the BLS May 2025 estimates. See hourly rates by role, monthly and overtime examples, PKR conversions and the wage rules for H-2B workers.',
                'content' => $content,
                'featured_image' => 'blogs/landscaper-salary-usa.jpg',
                'tags' => 'landscaper salary usa, average landscaper salary, landscaper hourly rate usa, landscaper monthly pay, landscaper salary in pkr, landscaper pay for foreigners, groundskeeper wages usa, h-2b landscaper wages',
                'meta_title' => 'Landscaper Salary in USA (2026): Hourly and Monthly Pay',
                'meta_description' => 'Landscaper salary in the USA: BLS average and median hourly pay, monthly and overtime examples, PKR conversions and H-2B wage rules for foreign workers.',
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
<p>Landscaper salary in the USA averages <strong>$20.33 an hour and $42,290 a year</strong> for landscaping and groundskeeping workers, according to the Bureau of Labor Statistics (BLS) May 2025 estimates. The median hourly wage is $18.82. At the average rate for 40 paid hours a week all year, gross monthly income is about $3,524. Actual earnings depend on location, duties, hours and season length, and these figures describe national wages, not guaranteed job offers or take-home pay.</p>

<h2>Average Landscaper Salary in the USA: Mean Versus Median</h2>

<p>The mean is the arithmetic average. The median is the midpoint, with half of workers earning more and half less. Looking at both helps you judge whether an advertised rate is realistic.</p>

<p>The data refer to May 2025, not to completed full-year 2026 earnings. Wage statistics appear only after their reference period, so a guide published in 2026 has to name the underlying date.</p>

<p>A national benchmark does not set the legal minimum for a particular employer, occupation or worksite. Use the written offer to calculate your expected income.</p>

<h2>Landscaper Hourly Rate in the USA by Role</h2>

<p>Related occupations have different wage estimates. The BLS national table gives:</p>

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
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Landscaping and groundskeeping workers</strong></td><td style="padding:10px;">$20.33</td><td style="padding:10px;">$18.82</td><td style="padding:10px;">$42,290</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Tree trimmers and pruners</strong></td><td style="padding:10px;">$26.91</td><td style="padding:10px;">$24.50</td><td style="padding:10px;">$55,970</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Landscaping, lawn service and groundskeeping supervisors</strong></td><td style="padding:10px;">$29.31</td><td style="padding:10px;">$28.09</td><td style="padding:10px;">$60,960</td></tr>
    </tbody>
</table>
</div>

<p>These are separate occupational categories, not guaranteed promotions or experience-based pay bands. A beginner doing routine lawn maintenance should not assume an entitlement to the supervisor average.</p>

<h2>Landscaper Monthly Pay in the USA</h2>

<p>For an average monthly estimate, use <strong>hourly rate &times; weekly paid hours &times; 52 &divide; 12</strong>. The examples assume 40 paid hours every week throughout a year, with no overtime.</p>

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
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$18</td><td style="padding:10px;">$720</td><td style="padding:10px;">$3,120</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$20</td><td style="padding:10px;">$800</td><td style="padding:10px;">$3,467</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$22</td><td style="padding:10px;">$880</td><td style="padding:10px;">$3,813</td></tr>
    </tbody>
</table>
</div>

<p>Amounts are rounded to the nearest dollar. They are calculations, not verified offers or legal minimums. Four weeks at this schedule is 160 hours, while an average calendar month is about 173.33 hours. A worker paid weekly or every two weeks will see payment dates that differ from the monthly estimate.</p>

<h2>Overtime and Extra Hours</h2>

<p>Covered, non-exempt employees generally receive at least one and a half times their regular rate after 40 hours worked in a workweek. Check coverage, any exemptions and your state's rules.</p>

<p>For a simple example, take a $20 regular rate with qualifying overtime: 40 hours produce $800, and ten overtime hours at $30 produce $300, so the weekly gross is $1,100. The example assumes the regular rate equals the stated hourly wage. Do not budget for overtime the employer has not scheduled or promised, and record your working time so you can check your payslip.</p>

<h2>Landscaper Salary in PKR</h2>

<p>For illustration, assume <strong>$1 = PKR 280</strong>. That is a planning assumption, not a verified October 2026 exchange quote. Replace it with your bank's or remittance provider's current receiving rate.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Monthly gross (USD)</th>
            <th style="padding:10px;text-align:left;">Illustrative gross (PKR)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$3,120</td><td style="padding:10px;">PKR 873,600</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$3,467</td><td style="padding:10px;">PKR 970,760</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">$3,813</td><td style="padding:10px;">PKR 1,067,640</td></tr>
    </tbody>
</table>
</div>

<p>The conversions use the rounded dollar figures shown. Gross PKR is not what your family necessarily receives, because payroll deductions, living costs, transfer fees and exchange-rate margins all reduce the money available to remit.</p>

<h2>Gross Pay, Take-Home Income and Savings</h2>

<p>Gross pay is what you earn before deductions. Take-home income is what remains after the deductions that apply, and savings are what is left after your own spending.</p>

<p>Budget separately for accommodation, food, phone service, personal transport and emergencies. Housing costs vary, and H-2B landscaping work should not be assumed to include free accommodation.</p>

<p>For illustration, $3,467 gross minus $1,200 in combined deductions and personal spending leaves $2,267. The $1,200 is hypothetical, not a standard tax bill or a typical US monthly budget. Ask the employer to explain deductions in writing before you accept.</p>

<h2>Landscaper Pay for Foreigners in the USA</h2>

<p>Eligible temporary non-agricultural landscaping can involve the H-2B programme. Employers must follow the programme's wage requirements, and a national salary average cannot replace the required contract rate. The route, requirements and cap position are in <a href="/blog/landscaper-jobs-usa-visa-sponsorship-2026">Landscaper Jobs in USA With Visa Sponsorship (2026)</a>.</p>

<p>Other federal wage protections, including the overtime rules, apply to H-2B workers as they do to other US workers, and employers must follow the rules on payment frequency. Workers must not pay prohibited employer recruitment, petition or attorney costs, and required job tools are subject to employer obligations. Review deductions carefully and keep wage records. Foreign nationality is never a reason to accept less than the required wage.</p>

<h2>Seasonal Contracts and Offer Comparisons</h2>

<p>Annual averages do not guarantee twelve months of work. A contract at $20 an hour for 40 paid hours over 26 weeks produces $20,800 gross, assuming those hours are worked. Do not multiply a seasonal monthly estimate by twelve unless the employment really covers the year.</p>

<p>Compare written offers on these points:</p>

<ul>
    <li>Hourly rate and overtime terms.</li>
    <li>Scheduled hours and contract dates.</li>
    <li>Duties, worksite and required experience.</li>
    <li>Housing arrangements and disclosed charges.</li>
    <li>Transport, tools and how often you are paid.</li>
</ul>

<p>A higher hourly headline can leave lower savings if the season is shorter or the accommodation costs more.</p>

<p>Before you accept, ask whether the quoted wage applies to every listed task and worksite. Clarify how the employer records travel between customer properties, loading equipment and other work. Ask for an explanation of any bonus, attendance incentive or performance payment instead of adding it automatically to your expected earnings. Save the original advertisement and the written contract, then compare each payslip with your own record of hours. If a recruiter advertises unusually high income, ask for the hourly rate, the scheduled hours and the exact calculation behind the claim. A monthly headline can hide assumptions about overtime or uninterrupted employment, so build a conservative budget from ordinary scheduled hours first and treat genuinely available extra earnings separately.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the average hourly landscaper wage?</h3>
<p>The BLS estimates show $20.33 an hour for landscaping and groundskeeping workers in May 2025.</p>

<h3>Is the median the same as the average?</h3>
<p>No. The median was $18.82 an hour and the mean was $20.33.</p>

<h3>Can a landscaper earn more than $3,000 a month?</h3>
<p>Suitable wages and hours can produce that gross amount, but it is not guaranteed.</p>

<h3>Is the PKR figure take-home salary?</h3>
<p>No. It converts gross dollars at an assumed rate, before expenses and transfer costs.</p>

<h3>Does every landscaping vacancy offer visa sponsorship?</h3>
<p>No. Confirm the employer's participation and the immigration requirements independently.</p>

<h3>Do landscapers get overtime?</h3>
<p>Covered, non-exempt workers generally do, at one and a half times the regular rate after 40 hours a week. Check coverage and your state's rules.</p>

<h3>Who earns more, a landscaper or a supervisor?</h3>
<p>In the BLS table, supervisors have the higher mean wage, but they are a separate occupation and need experience.</p>

<h3>Is accommodation included in H-2B landscaping jobs?</h3>
<p>Do not assume it is. Ask for the housing terms and any deductions in writing.</p>

<h2>People Also Search For</h2>

<h3>Landscaper salary in USA</h3>
<p>$20.33 an hour on average in the BLS May 2025 estimates.</p>

<h3>Average landscaper salary USA</h3>
<p>Mean and median differ, so compare both.</p>

<h3>Landscaper hourly rate USA</h3>
<p>The median for landscaping workers was $18.82 an hour.</p>

<h3>Landscaper monthly pay USA 2026</h3>
<p>Hourly rate &times; weekly hours &times; 52 &divide; 12.</p>

<h3>Landscaper salary in PKR</h3>
<p>Convert at your provider's real rate, after expenses.</p>

<h3>Landscaper pay for foreigners USA</h3>
<p>H-2B workers must be paid the wage the programme requires.</p>

<h3>Tree trimmer salary USA</h3>
<p>A separate BLS occupation, with a higher mean wage.</p>

<h3>Landscaper jobs in USA with visa sponsorship</h3>
<p>Covered in the visa sponsorship guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/landscaper-jobs-usa-visa-sponsorship-2026">Landscaper Jobs in USA With Visa Sponsorship (2026)</a> &mdash; H-2B rules and how to apply.</li>
    <li><a href="/blog/farm-worker-salary-usa-2026">Farm Worker Salary in USA (2026)</a> &mdash; the same breakdown for farm work.</li>
    <li><a href="/blog/landscaper-and-gardener-jobs-in-canada-and-the-uae">Landscaper and Gardener Jobs in Canada and the UAE</a> &mdash; the same trade elsewhere.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or financial advice. Wage figures are from the US Bureau of Labor Statistics, May 2025 national estimates, reviewed on 8 October 2026. Monthly calculations and PKR conversions are editorial examples, not official statistics or job offers.</p>
HTML;
    }
}
