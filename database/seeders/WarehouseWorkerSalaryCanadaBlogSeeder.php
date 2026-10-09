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

/**
 * "How Much Does a Warehouse Worker Make in Canada in 2026?"
 *
 * Wage figures checked on 9 October 2026 against the Job Bank wage report for
 * material handlers (NOC 75101), published 19 November 2025 (reference period
 * 2023-2024): Canada C$16.55 / C$22.00 / C$30.29, Ontario C$17.95 / C$21.50 /
 * C$29.96, Quebec C$16.94 / C$22.00 / C$31.32, and about 81.6% of workers with
 * a non-wage benefit.
 *
 * What changed against the brief:
 *
 * 1. Ontario's low end is C$17.95, not C$17.60, and the benefits share is
 *    81.6% (shown as about 82%).
 * 2. The "Quebec job ads from C$19 to C$27.59" and "regional medians from C$21
 *    to C$23" lines are replaced with the Job Bank Quebec figures above.
 * 3. The UK and US comparison in the FAQ is dropped; the UK figure was an
 *    Indeed average. The reader is pointed to the US pay guide instead.
 * 4. "Rules for lower-wage foreign worker hiring have been tightened" is kept
 *    only as a pointer to IRCC, with no detail the page cannot confirm.
 * 5. The Apply Now buttons are gone; the one official link sits on the job
 *    listing.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class WarehouseWorkerSalaryCanadaBlogSeeder extends Seeder
{
    public const SLUG = 'warehouse-worker-salary-canada-2026';

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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'How Much Does a Warehouse Worker Make in Canada in 2026?',
                'excerpt' => 'The median Canadian warehouse worker (material handler) earns C$22.00 an hour, about C$45,760 a year at 40 hours a week. See the low and high wages, Ontario and Quebec figures, PKR conversions and the work-permit rules.',
                'content' => $content,
                'featured_image' => 'blogs/warehouse-worker-salary-canada.jpg',
                'tags' => 'warehouse worker salary canada, material handler wages canada, canada warehouse hourly rate, warehouse pay ontario, warehouse salary in pkr, job bank wages, canada work permit warehouse, warehouse jobs canada pakistan',
                'meta_title' => 'How Much Does a Warehouse Worker Make in Canada (2026)?',
                'meta_description' => 'Canada warehouse worker pay in 2026: Job Bank median, low and high wages, Ontario and Quebec figures, PKR examples and the work-permit rules.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Canadian Warehouse and Logistics Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'canada-warehouse-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        Job::updateOrCreate(
            ['position' => 'Warehouse Worker — Canadian Employers (Material Handler)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Job Bank is the Government of Canada's official job site. It lists material handler and warehouse vacancies from Canadian employers, with the wage and location set out in each posting.</p>

<h3>Requirements</h3>
<ul>
    <li>Legal permission to work in Canada, such as a valid work permit; foreign workers should check the current rules on the official IRCC website</li>
    <li>Employment standards, including overtime, differ by province, so read the posting and the provincial rules</li>
    <li>No real employer or agency may charge you a fee for a job</li>
</ul>

<p><strong>Note:</strong> immigration and wage rules are set by the Government of Canada and the provinces, not by JobGader. This link opens the official job site; it is not an application form, and a visible posting does not prove the employer is hiring.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Set by each employer, often shifts',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => 'https://www.jobbank.gc.ca/findajob',
                'meta_description' => 'Material handler and warehouse vacancies from Canadian employers on the Government of Canada Job Bank. Legal permission to work in Canada is needed.',
                'seo_keywords' => 'warehouse worker salary canada, material handler jobs canada, job bank warehouse, warehouse jobs canada',
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>The median Canadian warehouse worker (material handler) earns <strong>C$22.00 an hour</strong>, or about C$45,760 a year at 40 hours a week. That is roughly C$3,813 a month before tax, or about PKR 762,600 at an illustrative PKR 200 to the Canadian dollar. Pay across the country runs from about C$16.55 to C$30.29 an hour, and individual jobs vary.</p>

<p>The figures come from the Government of Canada Job Bank wage report for material handlers (NOC 75101), published 19 November 2025 and based on 2023&ndash;2024 data. Wages have likely moved since, so treat them as a guide and check the wage in each job posting.</p>

<h2>What Is the Average Hourly Rate for a Warehouse Worker in Canada?</h2>

<p>The national median is C$22.00 an hour. Half of workers earn more and half earn less. The low and high figures show the usual spread.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Level (Canada, hourly)</th>
            <th style="padding:10px;text-align:left;">Hourly</th>
            <th style="padding:10px;text-align:left;">Yearly (40 hrs)</th>
            <th style="padding:10px;text-align:left;">Monthly (gross)</th>
            <th style="padding:10px;text-align:left;">Monthly in PKR (illustrative)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Low</strong></td><td style="padding:10px;">C$16.55</td><td style="padding:10px;">C$34,424</td><td style="padding:10px;">C$2,869</td><td style="padding:10px;">PKR 573,800</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median (typical)</strong></td><td style="padding:10px;">C$22.00</td><td style="padding:10px;">C$45,760</td><td style="padding:10px;">C$3,813</td><td style="padding:10px;">PKR 762,600</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>High</strong></td><td style="padding:10px;">C$30.29</td><td style="padding:10px;">C$63,003</td><td style="padding:10px;">C$5,250</td><td style="padding:10px;">PKR 1,050,000</td></tr>
    </tbody>
</table>
</div>

<p>Yearly pay is the hourly rate times 40 hours times 52 weeks. Monthly figures are the yearly pay divided by 12, gross, before tax. The PKR column uses <strong>C$1 = PKR 200</strong>, an illustrative rate rather than a verified exchange quote, so replace it with your bank's current rate.</p>

<h2>How Does Warehouse Pay Differ by Province?</h2>

<p>Job Bank also reports wages for some provinces. Minimum wages differ by province, so check the local rules.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Low</th>
            <th style="padding:10px;text-align:left;">Median</th>
            <th style="padding:10px;text-align:left;">High</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">C$16.55</td><td style="padding:10px;">C$22.00</td><td style="padding:10px;">C$30.29</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Ontario</strong></td><td style="padding:10px;">C$17.95</td><td style="padding:10px;">C$21.50</td><td style="padding:10px;">C$29.96</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Quebec</strong></td><td style="padding:10px;">C$16.94</td><td style="padding:10px;">C$22.00</td><td style="padding:10px;">C$31.32</td></tr>
    </tbody>
</table>
</div>

<h2>How Much Is a Warehouse Worker's Salary in PKR?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Period (median)</th>
            <th style="padding:10px;text-align:left;">CAD</th>
            <th style="padding:10px;text-align:left;">Illustrative PKR</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per hour</td><td style="padding:10px;">C$22.00</td><td style="padding:10px;">PKR 4,400</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per month (gross)</td><td style="padding:10px;">C$3,813</td><td style="padding:10px;">PKR 762,600</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Per year (gross)</td><td style="padding:10px;">C$45,760</td><td style="padding:10px;">PKR 9,152,000</td></tr>
    </tbody>
</table>
</div>

<p>These are gross amounts. Federal and provincial income tax, Canada Pension Plan (CPP) contributions and Employment Insurance (EI) premiums reduce your pay, and rent and winter costs can be high. Check the current exchange rate before you plan.</p>

<h2>What Makes Warehouse Pay Higher or Lower?</h2>

<ul>
    <li><strong>Location:</strong> big cities and provinces with higher living costs often pay more.</li>
    <li><strong>Equipment skills:</strong> forklift or reach-truck operators usually earn more than general handlers.</li>
    <li><strong>Shift:</strong> nights and weekends often pay a premium.</li>
    <li><strong>Overtime:</strong> overtime rules differ by province, and many workers get extra pay after a set number of hours a week.</li>
    <li><strong>Benefits:</strong> Job Bank reports that about 82% of material handlers in Canada receive at least one non-wage benefit, such as health coverage or a pension plan.</li>
    <li><strong>Employer type:</strong> large distribution centres, retailers and logistics firms vary a lot.</li>
</ul>

<h2>Do Foreign Workers Earn the Same as Canadians?</h2>

<p>Employment standards apply to everyone working legally in Canada, including foreign workers. Employers hiring foreign workers through government programs must also meet program wage rules.</p>

<p><strong>Important:</strong> you need legal permission to work in Canada first, such as a valid work permit. Rules for hiring foreign workers have changed in recent years, and some international students can work only limited hours off campus, so check the current rules on the official IRCC website. Never pay anyone for a "guaranteed warehouse job in Canada." A real employer should not charge you fees. For the US route, see <a href="/blog/warehouse-worker-jobs-usa-visa-sponsorship-2026">Warehouse Worker Jobs in the USA With Visa Sponsorship (2026)</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the monthly salary of a warehouse worker in Canada?</h3>
<p>About C$3,813 a month before tax at the median wage and 40 hours a week. The usual range is roughly C$2,869 to C$5,250 a month.</p>

<h3>Do Canadian warehouse jobs pay more than US ones?</h3>
<p>Pay depends on the country's currency and costs, so compare take-home pay and rent, not just the number. The US figures are in our <a href="/blog/warehouse-worker-salary-usa-2026">US warehouse salary guide</a>.</p>

<h3>Do warehouse workers in Canada get overtime pay?</h3>
<p>Usually yes, but overtime rules differ by province and employer. Check the job offer and your province's employment standards.</p>

<h3>Can I work in a Canadian warehouse from Pakistan?</h3>
<p>Only if you have legal permission to work in Canada, such as a work permit. Do not pay agents for guaranteed jobs, and verify every offer on official Canadian government websites.</p>

<h3>What is the minimum wage for a warehouse worker in Canada?</h3>
<p>Minimum wages are set by each province, so check the rate where the job is. Job Bank's national low figure for the occupation is C$16.55 an hour.</p>

<h3>Where do these wage figures come from?</h3>
<p>From the Government of Canada Job Bank wage report for material handlers (NOC 75101), published 19 November 2025.</p>

<h3>Do Canadian warehouse jobs come with benefits?</h3>
<p>Job Bank reports that about 82% of material handlers receive at least one non-wage benefit, such as health coverage or a pension plan.</p>

<h3>Can an international student work in a warehouse in Canada?</h3>
<p>Some can, within limited off-campus hours. Check the current rules on the official IRCC website.</p>

<h2>People Also Search For</h2>

<h3>Warehouse worker salary Canada</h3>
<p>Median C$22.00 an hour per Job Bank.</p>

<h3>Material handler wages Canada</h3>
<p>C$16.55 to C$30.29 an hour nationally.</p>

<h3>Warehouse pay Ontario</h3>
<p>Median C$21.50 an hour per Job Bank.</p>

<h3>Warehouse pay Quebec</h3>
<p>Median C$22.00 an hour per Job Bank.</p>

<h3>Warehouse salary in PKR</h3>
<p>About PKR 762,600 gross a month at the median.</p>

<h3>Canada work permit warehouse</h3>
<p>Legal permission to work is needed first.</p>

<h3>Job Bank warehouse jobs</h3>
<p>The official Government of Canada job site.</p>

<h3>Warehouse jobs USA visa sponsorship</h3>
<p>The US has a different, seasonal route covered in our guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/warehouse-worker-salary-usa-2026">Warehouse Worker Salary in the USA (2026)</a> &mdash; the US pay picture.</li>
    <li><a href="/blog/warehouse-worker-jobs-usa-visa-sponsorship-2026">Warehouse Worker Jobs in the USA With Visa Sponsorship (2026)</a> &mdash; the H-2B route.</li>
    <li><a href="/blog/warehouse-operative-salary-uk-2026">Warehouse Operative Salary in the UK (2026)</a> &mdash; the UK pay picture.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or financial advice. Wage figures were checked against Job Bank on 9 October 2026 and change often, so confirm them on the official Canadian government sites before you act or pay anyone.</p>
HTML;
    }
}
