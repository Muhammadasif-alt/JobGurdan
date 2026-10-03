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
 * "Plumber Salary in the UK and USA" — the pay half of the plumber cluster.
 * The Gulf guide owns recruitment cost and the Saudi exam; the Canada and
 * Australia guide owns licensing; this one owns what the trade pays and why
 * every source disagrees about it.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Seven "Apply Now - Indeed" buttons and two Indeed search links. No
 *    aggregator is linked on this site.
 *
 * 2. Headline pay figures taken from Indeed: GBP 36,339 for the UK from
 *    6.8k job-posting salaries, and $30.77 an hour for the US from 21.7k
 *    self-reported ones. Aggregator pay data is not republished here.
 *
 * 3. A BLS outlook from the superseded 2024-2034 projection round: "4%
 *    employment growth through 2034 with about 44,000 openings a year". The
 *    current 2025-2035 round gives 7 per cent, 34,500 jobs and about 42,000
 *    openings a year.
 *
 * 4. An ONS median of GBP 37,881 for plumbers specifically. ONS does not
 *    publish that figure anywhere reachable; its ASHE occupation ad-hoc for
 *    2025 does not cover SOC 5314. The verified ONS figure is the all
 *    full-time median of GBP 39,039 for April 2025, and this page uses that
 *    rather than inventing an occupation median.
 *
 * 5. A US state-by-state salary table and employer pay claims (Roto-Rooter
 *    "$80,000 to $130,000+", Pimlico Plumbers "up to GBP 100,000") sourced
 *    from job-board listings rather than from the employers or from BLS.
 *
 * What the draft got right and this page keeps: that the BLS occupation
 * code bundles plumbers with pipefitters and steamfitters, which is why the
 * mean and the median describe different workers.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class PlumberSalaryUkUsaBlogSeeder extends Seeder
{
    private const UK_APPLY_URL = 'https://www.gov.uk/find-a-job';

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
        $title = 'Plumber Salary in the UK and USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The BLS median for US plumbers is $63,800 and the job is growing 7 per cent to 2035. In the UK the trade is not licensed at all - but gas work is, and that single legal line explains most of the pay gap between two plumbers on the same street.',
                'content' => $content,
                'featured_image' => 'blogs/plumber-salary-uk-and-usa.jpg',
                'tags' => 'plumber salary uk, plumber salary usa, plumbing pay rates, gas safe register, plumber apprenticeship wage, bls plumber median, plumber job outlook, plumbing licence usa',
                'meta_title' => 'Plumber Salary UK and USA 2026: What the Official Data Says',
                'meta_description' => 'Plumber salary in the UK and USA on official data: the $63,800 BLS median, 7 per cent growth to 2035, and why UK gas work pays more than plumbing.',
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

        $ukAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Plumbing, Heating & Facilities Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-plumber-aggregated']
        );

        $ukLocation = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        Job::updateOrCreate(
            [
                'position' => 'Plumber and Heating Engineer — UK Domestic, Commercial and Facilities Maintenance',
                'advertiser_id' => $ukAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $ukLocation->id,
                'description' => $this->ukJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with call-out rotas common in domestic and facilities work; 5.6 weeks paid holiday is a statutory minimum',
                'language' => 'English',
                // Pay runs from the apprentice minimum to self-employed day
                // rates across the same trade, and ONS publishes no reachable
                // occupation median, so no honest band can be stated here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UK_APPLY_URL,
                'meta_description' => 'Plumbing and heating roles across UK domestic, commercial and facilities maintenance employers. Gas work requires Gas Safe registration by law.',
                'seo_keywords' => 'plumber jobs uk, heating engineer jobs, gas safe engineer jobs, plumbing apprenticeship uk, plumber salary uk',
            ]
        );

        $usAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'US Plumbing, Heating & Air-Conditioning Contractors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-plumber-aggregated']
        );

        $usLocation = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            [
                'position' => 'Plumber, Pipefitter and Steamfitter — US Contractors, Manufacturing and Government',
                'advertiser_id' => $usAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $usLocation->id,
                'description' => $this->usJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with evening, weekend and emergency call-out work common in service plumbing',
                'language' => 'English',
                // Licensing and pay are set state by state, and the BLS
                // figures below describe the occupation rather than any one
                // employer's offer, so the salary fields stay empty.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::US_APPLY_URL,
                'meta_description' => 'Plumber, pipefitter and steamfitter roles with US contractors, manufacturers and government employers. Licensing is set state by state.',
                'seo_keywords' => 'plumber jobs usa, pipefitter jobs, steamfitter jobs, plumbing apprenticeship usa, plumber salary usa',
            ]
        );
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Plumbing and heating employers across the UK recruit into domestic service, commercial installation and facilities maintenance work, and into apprenticeships that lead to all three.</p>

<h3>The legal line that decides your pay</h3>
<p>Plumbing itself is not a licensed trade in the UK. <strong>Gas work is.</strong> Under the Gas Safety (Installation and Use) Regulations 1998, anyone carrying out gas work must be on the Gas Safe Register and qualified for that category of work. Doing it without registration is illegal. That single requirement is the main reason two plumbers with the same years of experience can be paid very differently.</p>

<h3>What employers ask for</h3>
<ul>
    <li>A Level 2 or Level 3 plumbing qualification, or an apprenticeship in progress</li>
    <li>Gas Safe registration for heating and boiler roles, with the relevant appliance categories</li>
    <li>Unvented hot water, water regulations or legionella certification for commercial work</li>
    <li>A driving licence for mobile service roles</li>
    <li>A CSCS card for construction sites</li>
</ul>

<h3>Statutory minimums</h3>
<ul>
    <li>National Living Wage &pound;12.71 an hour for workers aged 21 and over from 1 April 2026</li>
    <li>&pound;10.85 for 18 to 20 year olds, and &pound;8.00 for 16 to 17 year olds and apprentices</li>
    <li>5.6 weeks of paid holiday a year, which is 28 days for a five-day week</li>
</ul>

<p><strong>Note:</strong> pay above those floors is set by the employer and the certifications you hold. Wage rates and gas safety law are set by government, not by JobGader; confirm them on gov.uk and hse.gov.uk.</p>
JOBHTML;
    }

    private function usJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Plumbing, heating and air-conditioning contractors, manufacturers, heavy and civil engineering firms and government employers recruit plumbers, pipefitters and steamfitters across the United States.</p>

<h3>What the federal data says about the occupation</h3>
<ul>
    <li>Median annual wage <strong>$63,800</strong> in May 2025; the lowest 10 per cent earned less than $44,150 and the highest 10 per cent more than $108,420</li>
    <li><strong>510,600 jobs</strong> in 2025, projected to grow <strong>7 per cent</strong> by 2035, an increase of 34,500</li>
    <li>About <strong>42,000 openings</strong> a year on average over the decade</li>
    <li>Medians by industry: government $71,660, manufacturing $65,770, heavy and civil engineering construction $63,270, plumbing, heating and air-conditioning contractors $63,010</li>
</ul>

<h3>Entry and training</h3>
<p>A high school diploma or equivalent is typically required, and most workers learn through a four to five year apprenticeship combining paid work with classroom instruction.</p>

<h3>Licensing</h3>
<p><strong>There is no national plumbing licence in the United States.</strong> Licensing is set state by state, and in many states by the county or city as well. A licence earned in one state does not automatically transfer to another, so check the licensing board for the state you intend to work in before accepting a role or planning a move.</p>

<p><strong>Note:</strong> the figures above are from the US Bureau of Labor Statistics Occupational Outlook Handbook, not by JobGader. BLS reports plumbers together with pipefitters and steamfitters in one occupation, so the group median is not the same as a residential service plumber's pay.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Search for what a plumber earns and you will be given four different numbers in the first four results, none of which agree and none of which say where they came from. This page uses only figures published by the US Bureau of Labor Statistics and the UK Office for National Statistics, says plainly where no official figure exists, and explains the one legal difference that moves UK plumbing pay more than experience does.</p>

<h2>Why Every Source Gives a Different Number</h2>

<p>The disagreement is not noise. The sources are measuring different things:</p>

<ul>
    <li><strong>Government surveys</strong> ask employers what they actually paid. They are the most reliable and the slowest, so the latest US figures describe May 2025 and the latest UK ones April 2025.</li>
    <li><strong>Job-board averages</strong> read salaries out of adverts. Adverts advertise, so they skew toward whatever attracts applicants, and an advert quoting "up to" a figure gets counted as that figure.</li>
    <li><strong>Self-reported salary pages</strong> rely on whoever chose to type a number in. A few hundred responses can set an "average" for a whole country.</li>
    <li><strong>Mean against median.</strong> The mean is dragged upward by the highest earners; the median is the person standing in the middle. For this trade the gap is large, and the reason is in the occupation code.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-salary-uk-and-usa-compare.jpg"
         alt="A plumber fitting a pipe under a sink, with the Union Jack and Big Ben on one side and the Statue of Liberty, the US flag and the New York skyline on the other"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What US Plumbers Earn, on BLS Data</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Figure</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Median annual wage, May 2025</td><td style="padding:10px;"><strong>$63,800</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lowest 10 per cent</td><td style="padding:10px;">less than $44,150</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Highest 10 per cent</td><td style="padding:10px;">more than $108,420</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Jobs in 2025</td><td style="padding:10px;">510,600</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Projected change, 2025 to 2035</td><td style="padding:10px;">+7 per cent, +34,500 jobs</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Openings each year, on average</td><td style="padding:10px;">about 42,000</td></tr>
    </tbody>
</table>
</div>

<p><strong>If a page tells you the outlook is 4 per cent and 44,000 openings, it is quoting the previous projection round.</strong> Those were the 2024 to 2034 figures. The current round runs 2025 to 2035 and is both faster and smaller in absolute openings.</p>

<h3>Where the employer sits changes the number</h3>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Industry</th>
            <th style="padding:10px;text-align:left;">Median annual wage, May 2025</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Government</td><td style="padding:10px;">$71,660</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Manufacturing</td><td style="padding:10px;">$65,770</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Heavy and civil engineering construction</td><td style="padding:10px;">$63,270</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Plumbing, heating and air-conditioning contractors</td><td style="padding:10px;">$63,010</td></tr>
    </tbody>
</table>
</div>

<h3>The occupation code explains the spread</h3>

<p>BLS does not count plumbers on their own. It reports <strong>plumbers, pipefitters and steamfitters together</strong>. Pipefitters and steamfitters work on industrial process, power and refinery systems, and are far more likely to be unionised and on shutdown or travel rates. They sit at the top of the distribution and pull every average upward.</p>

<p>So a residential service plumber should read the median, $63,800, as the figure closest to them, and should treat any quoted "average" above that as describing a different kind of work.</p>

<h2>What UK Plumbers Earn, and the Figure Nobody Can Source</h2>

<p>Here the honest answer is partly a negative one. <strong>ONS publishes a median gross annual pay of £39,039 for all UK full-time employees in April 2025</strong>, up 4.3 per cent from £37,439 the year before. That is the benchmark a plumbing salary should be measured against.</p>

<p>ONS does not publish an easily reachable median for plumbers as a separate occupation, and its 2025 occupation release does not cover the plumbing SOC code at all. Pages quoting a precise "ONS plumber median" to the pound are not quoting anything you can open and check. We would rather tell you that than repeat a number we cannot source.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-salary-uk-and-usa-worksite.jpg"
         alt="A plumber tightening a pipe joint with a wrench, with British and American landmarks, a stack of dollar bills and a rising chart arranged around the scene"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The UK Rule That Moves Pay More Than Experience</h2>

<p><strong>Plumbing is not a licensed trade in the United Kingdom. Gas work is.</strong></p>

<p>Under the Gas Safety (Installation and Use) Regulations 1998, anyone carrying out gas work must be on the <strong>Gas Safe Register</strong> and hold qualifications showing competence for that category of work. Doing gas work without it is illegal, not merely uninsured.</p>

<p>That is the single most important fact about UK plumbing pay, and almost no salary article mentions it. Two people can both call themselves plumbers, work the same hours on the same street, and earn very different money, because one of them may legally touch a boiler and the other may not. If you are deciding where to put your training money, that is where it goes.</p>

<p>The same logic applies further up: unvented hot water, water regulations and commercial heating certifications each open work that uncertified plumbers cannot take.</p>

<h2>What the Law Guarantees, and What It Does Not</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">United Kingdom</th>
            <th style="padding:10px;text-align:left;">United States</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Statutory wage floor</strong></td><td style="padding:10px;">£12.71 an hour at 21 and over, from 1 April 2026</td><td style="padding:10px;">Federal minimum wage, with higher state and city floors</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Apprentice floor</strong></td><td style="padding:10px;">£8.00 an hour</td><td style="padding:10px;">Set by the apprenticeship programme and state law</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Paid holiday</strong></td><td style="padding:10px;">5.6 weeks a year by law</td><td style="padding:10px;">No federal entitlement</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Is plumbing licensed?</strong></td><td style="padding:10px;">No &mdash; but gas work is, by law</td><td style="padding:10px;">Yes, state by state and often by city</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Does the licence travel?</strong></td><td style="padding:10px;">Gas Safe registration is UK-wide</td><td style="padding:10px;">No. A state licence does not transfer automatically</td></tr>
    </tbody>
</table>
</div>

<h2>How to Raise What You Are Paid</h2>

<ol>
    <li><strong>In the UK, get Gas Safe registered.</strong> It is the one credential that changes which jobs you may legally do, and therefore the one that changes the rate.</li>
    <li><strong>In the US, check which state you are licensed in before you move.</strong> There is no national licence; the board in your destination state decides whether your experience counts.</li>
    <li><strong>Follow the industry, not the job title.</strong> US government and manufacturing employers pay above the contractor median. The work is less varied and more secure.</li>
    <li><strong>Add the certifications that gate work</strong> &mdash; unvented hot water, water regulations, commercial heating in the UK; medical gas, backflow and process piping endorsements in the US.</li>
    <li><strong>Ask what the call-out rota is worth</strong> in writing. Emergency and out-of-hours work is where service plumbing pay is actually made, and it is rarely in the headline figure.</li>
    <li><strong>Count the apprenticeship as paid training.</strong> Four to five years on the tools with no fees, ending in a credential, is a different proposition from a degree.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What is the average plumber salary in the USA?</h3>
<p>The BLS median was $63,800 a year in May 2025. The lowest 10 per cent earned under $44,150 and the highest 10 per cent over $108,420, across 510,600 jobs.</p>

<h3>Is plumbing a growing trade in the US?</h3>
<p>Yes. BLS projects 7 per cent growth from 2025 to 2035, an increase of 34,500 jobs, with about 42,000 openings a year. Figures of 4 per cent and 44,000 openings come from the superseded 2024 to 2034 round.</p>

<h3>What is the average plumber salary in the UK?</h3>
<p>ONS does not publish a reachable median for plumbers as a separate occupation. The benchmark it does publish is £39,039, the median gross annual pay for all UK full-time employees in April 2025. Treat any precise "ONS plumber median" with caution.</p>

<h3>Do you need a licence to be a plumber in the UK?</h3>
<p>Not for plumbing itself. Gas work is different: the Gas Safety (Installation and Use) Regulations 1998 require anyone carrying it out to be on the Gas Safe Register and qualified for that category of work, and doing it unregistered is illegal.</p>

<h3>Why does the US plumber average differ from the median?</h3>
<p>BLS counts plumbers, pipefitters and steamfitters as one occupation. Industrial pipefitters and steamfitters earn more and pull the mean up, so the median is the better guide for a residential service plumber.</p>

<h3>Does a US plumbing licence work in another state?</h3>
<p>Not automatically. There is no national plumbing licence; each state, and often each city or county, licenses separately. Check the destination state's board before moving.</p>

<h3>What does a plumbing apprentice earn?</h3>
<p>In the UK the apprentice minimum is £8.00 an hour from 1 April 2026, rising to the age-related rate as the apprenticeship progresses. In the US, apprentice pay is set by the programme and rises in steps through a four to five year term.</p>

<h3>Which pays better, employed or self-employed plumbing?</h3>
<p>Self-employment usually pays more per hour worked and carries no sick pay, holiday pay, pension contribution or guaranteed work. Compare the day rate against the whole employed package, not against the headline salary.</p>

<h2>People Also Search For</h2>

<h3>Plumber salary USA</h3>
<p>$63,800 median in May 2025, from under $44,150 to over $108,420.</p>

<h3>Plumber salary UK</h3>
<p>Measured against the £39,039 all-employee full-time median; no official occupation median is published.</p>

<h3>Gas Safe Register</h3>
<p>Legally required for gas work in the UK under the 1998 regulations.</p>

<h3>Plumber job outlook</h3>
<p>7 per cent growth 2025 to 2035 in the US, about 42,000 openings a year.</p>

<h3>Plumbing apprenticeship wage</h3>
<p>£8.00 an hour minimum in the UK; programme-set and rising in steps in the US.</p>

<h3>Pipefitter vs plumber pay</h3>
<p>One BLS occupation code, which is why industrial work lifts every average.</p>

<h3>Plumbing licence by state USA</h3>
<p>No national licence; state and often city boards license separately.</p>

<h3>Highest paying plumbing industry</h3>
<p>Government, at a $71,660 median, above the contractor median of $63,010.</p>

<h2>More Job Guides</h2>

<p>The rest of the plumbing cluster:</p>

<ul>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; who pays the recruitment cost, and the Saudi trade exam sat before the visa.</li>
    <li><a href="/blog/how-to-become-a-licensed-plumber-in-canada-or-australia">How to Become a Licensed Plumber in Canada or Australia</a> &mdash; the Red Seal, the compulsory provinces and the state licence.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; the licensing route in a country where the trade is fully regulated.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; the wider US trades market and its visa routes.</li>
    <li><a href="/blog/uk-jobs-with-visa-sponsorship">UK Jobs with Visa Sponsorship</a> &mdash; what a trade has to pay to be sponsorable in the UK.</li>
    <li><a href="/blog/vacancies-in-london">Vacancies in London</a> &mdash; the statutory pay floors and the right-to-work check.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why low-paid UK sponsorship adverts do not add up.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, employment or financial advice. US pay and projection data is from the Bureau of Labor Statistics, UK earnings data from the Office for National Statistics, and gas safety law from the Health and Safety Executive. Figures are updated annually; confirm the current ones on bls.gov, ons.gov.uk and hse.gov.uk.</p>
HTML;
    }
}
