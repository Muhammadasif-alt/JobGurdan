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
 * "Welder Salary in USA" — the US pay page for welders, built on BLS OEWS
 * May 2025 (SOC 51-4121), the BLS 2025-2035 projections and Job Bank wages
 * for NOC 72106. Canada is kept short and handed to "Welder Jobs in Canada".
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Indeed averages for TIG and MIG welders ($25.48 and $22.14 an hour,
 *    with ranges) and Indeed city figures. BLS does not split pay by
 *    welding process, so the claim that one process pays more is replaced
 *    by what does move pay: industry, pipe and structural codes, AWS
 *    certification.
 *
 * 2. Pay quoted from individual listings ($45-65 an hour, $125-175k, a
 *    Ford rate of $44.77) and every Indeed link.
 *
 * 3. A manufacturing median of $51,210. No OEWS series for manufacturing as
 *    a whole could be confirmed, so the page gives the three manufacturing
 *    subsectors BLS publishes instead.
 *
 * Confirmed against the BLS API: $53,750 / $25.84 median, $39,240 and
 * $77,530 deciles, $59,440 specialty trade contractors, $56,080 repair and
 * maintenance. Job Bank: C$30.00 national median, Alberta C$38.00, BC
 * C$35.00, Manitoba C$27.00.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class WelderSalaryUsaBlogSeeder extends Seeder
{
    private const US_APPLY_URL = 'https://www.usa.gov/job-search';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Guides on qualifications, pay, progression and how to get hired.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Welder Salary in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'US welders earned a median $53,750 in May 2025, or $25.84 an hour, on BLS data. Heavy and civil engineering construction pays $76,690. Here is what moves welder pay, and the 2025-2035 outlook.',
                'content' => $content,
                'featured_image' => 'blogs/welder-salary-in-usa.jpg',
                'tags' => 'welder salary usa, welder hourly wage, welder pay by industry, bls welder median, aws certified welder, pipe welder pay, welding job outlook 2035, welder jobs usa',
                'meta_title' => 'Welder Salary in USA 2026: BLS Pay by Industry',
                'meta_description' => 'Welder salary in the USA on BLS May 2025 data: a $53,750 median, pay by percentile and industry, what certification adds, and the 2025-2035 outlook.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Fabrication, Construction & Repair Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-welder-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            [
                'position' => 'Welder — US Fabrication, Construction and Repair Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, often with overtime; shift work is common in manufacturing',
                'language' => 'English',
                // Industry medians run from $50,870 in fabricated metal products
                // to $76,690 in heavy and civil engineering, so no one range fits.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::US_APPLY_URL,
                'meta_description' => 'Welder roles with US fabricators, contractors and repair shops. Employers test welds against AWS or ASME codes before hiring.',
                'seo_keywords' => 'welder jobs usa, structural welder, pipe welder jobs, aws certified welder, welding jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Fabrication shops, manufacturers, construction contractors and repair businesses across the United States recruit welders for structural, pipe and production work.</p>

<h3>What the work involves</h3>
<ul>
    <li>Reading blueprints and weld symbols</li>
    <li>Welding steel, stainless and aluminium with MIG, TIG, stick or flux-cored processes</li>
    <li>Inspecting and grinding welds to meet the code the job is built to</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li>A high school diploma plus technical training or an apprenticeship</li>
    <li>A weld test, often to AWS D1.1 for structural steel or ASME Section IX for pressure pipe</li>
    <li>AWS Certified Welder status or a union card for some contractors</li>
</ul>

<p><strong>Pay:</strong> BLS reports a May 2025 median of $53,750 a year, or $25.84 an hour. Individual employers set their own rates.</p>

<p><strong>Note:</strong> certification and work authorisation rules are set by employers, code bodies and US immigration law, not by JobGader. Confirm them before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Welding pays a solid middle-class wage in the United States, and the spread between the lowest and highest earners is wide. That spread is not about whether you weld MIG or TIG. It is about which industry you weld in and which codes you are certified to. This page uses only the Bureau of Labor Statistics, which surveys employers directly, rather than the job-board averages that circulate online.</p>

<h2>What Welders Earn in the USA</h2>

<p>BLS Occupational Employment and Wage Statistics, May 2025, for welders, cutters, solderers and brazers (SOC 51-4121), 416,210 employed:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Annual</th>
            <th style="padding:10px;text-align:left;">Hourly</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Bottom 10 per cent earned less than</td><td style="padding:10px;">$39,240</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">25th percentile</td><td style="padding:10px;">$46,790</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median</strong></td><td style="padding:10px;"><strong>$53,750</strong></td><td style="padding:10px;"><strong>$25.84</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mean</td><td style="padding:10px;">$56,760</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">75th percentile</td><td style="padding:10px;">$63,010</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Top 10 per cent earned more than</td><td style="padding:10px;">$77,530</td><td style="padding:10px;">&mdash;</td></tr>
    </tbody>
</table>
</div>

<p>Half of all welders earn between $46,790 and $63,010. Getting into the top tenth, above $77,530, usually means changing industry or adding a certification, not just putting in more years.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/welder-salary-in-usa-skyline.jpg"
         alt="A welder in a mask and leather apron welding a steel beam, with stacks of coins rising beside a US map, the New York skyline and the Statue of Liberty behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Welder Pay by Industry</h2>

<p>The same survey publishes medians by industry. These are the gaps that matter:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Industry</th>
            <th style="padding:10px;text-align:left;">Median annual wage, May 2025</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Heavy and civil engineering construction</td><td style="padding:10px;">$76,690</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Specialty trade contractors</td><td style="padding:10px;">$59,440</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Repair and maintenance</td><td style="padding:10px;">$56,080</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Machinery manufacturing</td><td style="padding:10px;">$53,660</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Transportation equipment manufacturing</td><td style="padding:10px;">$51,100</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Fabricated metal product manufacturing</td><td style="padding:10px;">$50,870</td></tr>
    </tbody>
</table>
</div>

<p>Heavy and civil engineering covers pipelines, bridges, power and utility work, where code welding on pressure pipe and structural steel is the norm. Most welders, though, work in manufacturing, where medians sit close to the national figure.</p>

<h2>Does TIG Pay More Than MIG?</h2>

<p>You will see hourly averages for "TIG welders" and "MIG welders" quoted as if they were official. They are not. <strong>BLS does not split welder pay by welding process</strong>, and the averages online come from job adverts, which mix entry-level production roles with specialist ones.</p>

<p>What does move pay is specialisation:</p>

<ul>
    <li><strong>Pipe welding.</strong> Pressure pipe is welded to ASME Section IX, and pipelines to API 1104. Passing those tests opens pipeline, refinery and power-plant work.</li>
    <li><strong>Structural code work.</strong> AWS D1.1 is the structural steel code most contractors test to.</li>
    <li><strong>Aerospace and precision work.</strong> Thin-gauge TIG on aluminium and exotic alloys, usually with company or AWS D17.1 qualification.</li>
    <li><strong>AWS certification.</strong> The American Welding Society's Certified Welder programme records which tests you have passed, so a new employer can see it. Certified Welding Inspector is the step after that.</li>
</ul>

<p>TIG skills often lead to those jobs, which is where the impression that TIG "pays more" comes from. The process is the route; the code and the industry set the pay.</p>

<h2>Job Outlook to 2035</h2>

<p>The BLS Occupational Outlook Handbook, in its <strong>2025 to 2035</strong> projections, puts welders, cutters, solderers and brazers at:</p>

<ul>
    <li><strong>437,700 jobs</strong> in 2025</li>
    <li><strong>2 per cent growth</strong>, slower than average</li>
    <li><strong>About 40,300 openings a year</strong>, most of them from welders retiring or leaving the trade</li>
</ul>

<p>Slow growth does not mean few jobs. Replacement demand is what creates most openings, and it is steady.</p>

<h2>How to Raise Your Welder Salary</h2>

<ol>
    <li><strong>Get code-tested.</strong> Pass AWS D1.1 for structural work or ASME Section IX for pipe, and keep your continuity log current.</li>
    <li><strong>Move into pipe.</strong> Pipe welding is the most direct route into the higher-paying construction and energy work.</li>
    <li><strong>Consider a union apprenticeship.</strong> Pipefitter and ironworker apprenticeships train welders and pay on a scale while you learn.</li>
    <li><strong>Learn to read prints and fit up.</strong> Fitter-welders who can lay out work command more than operators who only run beads.</li>
    <li><strong>Look at inspection.</strong> AWS Certified Welding Inspector is the usual next step off the tools.</li>
</ol>

<h2>Welder Pay in Canada, Briefly</h2>

<p>Job Bank reports a national median of <strong>C$30.00 an hour</strong> for welders (NOC 72106), from C$22.00 at the low end to C$47.00 at the high end, based on 2023 to 2024 data. Provincial medians include C$38.00 in Alberta, C$35.00 in British Columbia and C$27.00 in Manitoba. Certification, provincial rules and how to apply from abroad are covered in <a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/welder-salary-in-usa-harbour.jpg"
         alt="A welder in a mask working on steel beside a US flag, with blueprints, a tape measure and a stars-and-stripes hard hat in front of the Statue of Liberty and Brooklyn Bridge"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Working as a Welder in the USA from Abroad</h2>

<ul>
    <li><strong>There is no welder-specific visa.</strong> Permanent sponsorship for a skilled trade usually runs through an employer's labour certification and an EB-3 petition, which takes years.</li>
    <li><strong>Temporary work</strong> is possible through H-2B only where an employer has a genuinely temporary or seasonal need, and the category is capped each year.</li>
    <li><strong>Never pay for a job offer.</strong> Anyone selling a quick US welding visa is not describing a legal route.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much does a welder earn in the USA?</h3>
<p>BLS reports a May 2025 median of $53,750 a year, or $25.84 an hour. The bottom tenth earned under $39,240 and the top tenth over $77,530.</p>

<h3>Which welding industry pays the most?</h3>
<p>Among the industries BLS publishes, heavy and civil engineering construction has the highest median at $76,690. Specialty trade contractors follow at $59,440.</p>

<h3>Do TIG welders earn more than MIG welders?</h3>
<p>BLS does not split pay by process. TIG skills often lead to pipe, aerospace and precision work that pays more, but it is the code and the industry that set the rate.</p>

<h3>Is welding a growing job?</h3>
<p>BLS projects 2 per cent growth from 2025 to 2035, slower than average, but about 40,300 openings a year because of retirements and people leaving the trade.</p>

<h3>Does AWS certification increase pay?</h3>
<p>It helps you get hired into code work, which pays more. The certification records which tests you have passed so employers can check them.</p>

<h3>How long does it take to become a welder?</h3>
<p>BLS lists a high school diploma plus technical training and moderate on-the-job training. Programmes run from a few months to two years; union apprenticeships take longer.</p>

<h3>What does a welder earn in Canada?</h3>
<p>Job Bank reports a national median of C$30.00 an hour for NOC 72106, with C$38.00 in Alberta and C$35.00 in British Columbia.</p>

<h3>Can a foreign welder get a US work visa?</h3>
<p>Only through an employer. Permanent routes usually mean labour certification and an EB-3 petition; H-2B covers temporary needs only and is capped.</p>

<h2>People Also Search For</h2>

<h3>Welder salary per hour USA</h3>
<p>$25.84 median in May 2025.</p>

<h3>Highest paying welding jobs</h3>
<p>Heavy and civil engineering construction, at a $76,690 median.</p>

<h3>Pipe welder salary</h3>
<p>BLS does not publish it separately; pipe work sits mostly in the higher-paying construction industries.</p>

<h3>Welder job outlook 2035</h3>
<p>2 per cent growth and about 40,300 openings a year.</p>

<h3>AWS certified welder</h3>
<p>The American Welding Society programme that records the weld tests you have passed.</p>

<h3>Top 10 percent welder salary</h3>
<p>Above $77,530 a year.</p>

<h3>Welder salary Canada</h3>
<p>C$30.00 an hour national median on Job Bank.</p>

<h3>Structural welding certification</h3>
<p>Usually a test to AWS D1.1.</p>

<h2>More Job Guides</h2>

<p>Related trades and markets:</p>

<ul>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; provincial certification, wages and applying from abroad.</li>
    <li><a href="/blog/carpenter-jobs-in-the-uk-canada-and-australia">Carpenter Jobs in the UK, Canada and Australia</a> &mdash; another construction trade across three visa systems.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; the wider US trades market and its visa routes.</li>
    <li><a href="/blog/hvac-technician-jobs-in-the-usa-and-canada">HVAC Technician Jobs in the USA and Canada</a> &mdash; official pay data for a neighbouring trade.</li>
    <li><a href="/blog/carpenter-jobs-in-usa">Carpenter Jobs in USA</a> &mdash; the US carpentry market.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not career, legal or immigration advice. US pay data is from BLS OEWS May 2025 and projections from the BLS Occupational Outlook Handbook; Canadian wages are from Job Bank. Individual employers set their own rates.</p>
HTML;
    }
}
