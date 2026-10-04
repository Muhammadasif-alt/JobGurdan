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
 * "Electrician Salary in the UAE, UK and USA" — the pay half of the
 * electrician cluster, built only on figures a government, a statutory body
 * or a collective agreement publishes: BLS OEWS May 2025 for the US, the
 * 2026 JIB national working rules for the UK, and UAE federal law for what
 * the UAE does and does not guarantee.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Every UAE and UK headline figure came from Indeed, Glassdoor and
 *    SalaryExpert job-posting averages, including an AED 1,672 monthly
 *    "average" and a ninefold disagreement it then tried to explain.
 *
 * 2. "£39,647 ONS typical pay" and "the £41,190 ONS average" are not ONS
 *    publications for electricians.
 *
 * 3. "London pays 15-30% above the national average." The JIB London rate
 *    for the Electrician grade is £20.58 against £18.38, about 12 per cent.
 *
 * 4. A state table that matched BLS for only some rows: Hawaii, California
 *    and Mississippi were all understated, and Mississippi was placed below
 *    Florida when BLS has it above.
 *
 * 5. Five Saudi "Apply Now" pairs: an Indeed search, a Marriott URL carrying
 *    a job ID, and a CCC careers page that returns 404.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class ElectricianSalaryUaeUkUsaBlogSeeder extends Seeder
{
    private const UK_APPLY_URL = 'https://www.gov.uk/find-a-job';

    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

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
        $title = 'Electrician Salary in the UAE, UK and USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'US electricians earn a $63,190 median on BLS data. UK pay starts from the 2026 JIB rate of £18.38 an hour. The UAE publishes no electrician wage figure and sets no minimum wage, so the contract is the only number that binds.',
                'content' => $content,
                'featured_image' => 'blogs/electrician-salary-uae-uk-usa.jpg',
                'tags' => 'electrician salary, electrician salary uk, electrician salary usa, electrician salary uae, jib rates 2026, bls electrician pay, electrician pay by state, electrician salary dubai',
                'meta_title' => 'Electrician Salary: UAE vs UK vs USA (2026 Compared)',
                'meta_description' => 'Electrician pay compared: the BLS median and state figures, the 2026 JIB hourly rates for UK grades, and why UAE salary averages mislead.',
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
            ['name' => 'UK Electrical Contracting Employers — JIB Graded Roles (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-jib-electrician-aggregated']
        );

        $ukLocation = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        Job::updateOrCreate(
            [
                'position' => 'Electrician and Approved Electrician — UK Electrical Contractors on JIB Grades',
                'advertiser_id' => $ukAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $ukLocation->id,
                'description' => $this->ukJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => '37.5-hour standard week, Monday to Friday',
                'language' => 'English',
                // JIB rates are hourly minimums that vary by grade, transport
                // and London working, so no single range describes the group.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UK_APPLY_URL,
                'meta_description' => 'Electrician and Approved Electrician roles with UK contractors working to JIB grades. The 2026 JIB minimum is £18.38 an hour for an Electrician.',
                'seo_keywords' => 'electrician jobs uk, jib electrician, approved electrician, jib rates 2026, ecs card',
            ]
        );

        $uaeAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'UAE MEP, Facilities Management & Construction Contractors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-electrician-aggregated']
        );

        $uaeLocation = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            [
                'position' => 'Electrician — UAE MEP, Facilities Management and Construction Contractors',
                'advertiser_id' => $uaeAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $uaeLocation->id,
                'description' => $this->uaeJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, often on shifts for facilities maintenance',
                'language' => 'English',
                // The UAE sets no statutory minimum wage, so the offer letter
                // is the only figure that applies.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UAE_APPLY_URL,
                'meta_description' => 'Electrician roles with UAE MEP, facilities and construction contractors. The UAE sets no minimum wage, and recruitment costs fall on the employer by law.',
                'seo_keywords' => 'electrician jobs uae, electrician jobs dubai, mep electrician, facilities electrician uae, electrician salary dubai',
            ]
        );
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Electrical contractors across England, Wales and Northern Ireland recruit to the grades set by the Joint Industry Board for the Electrical Contracting Industry.</p>

<h3>2026 JIB national standard rates, from 5 January 2026</h3>
<ul>
    <li><strong>Technician:</strong> £22.70 an hour, transport provided</li>
    <li><strong>Approved Electrician:</strong> £20.08 an hour</li>
    <li><strong>Electrician:</strong> £18.38 an hour</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li>An NVQ Level 3 or equivalent electrotechnical qualification and the AM2 assessment</li>
    <li>An ECS card matching the grade</li>
    <li>Current wiring regulations qualification</li>
</ul>

<p><strong>Note:</strong> JIB rates are minimums for employers that follow the JIB agreement; they are set by the JIB, not by JobGader. Check the grade and rate in the written offer.</p>
JOBHTML;
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>MEP contractors, facilities management companies and construction firms in the UAE recruit electricians for building services, maintenance and site work.</p>

<h3>What the law guarantees</h3>
<ul>
    <li>There is no statutory minimum wage, so the wage in the offer letter is the figure that binds</li>
    <li>Under Article 6 of Federal Decree-Law No. 33 of 2021, an employer may not charge the worker recruitment and employment costs, directly or indirectly</li>
    <li>Wages are paid through the Wage Protection System under Ministerial Resolution No. 340 of 2026</li>
</ul>

<p><strong>Note:</strong> employment terms are set by each employer under UAE law, not by JobGader. Never pay a recruiter for a UAE job offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Comparing electrician pay across the UAE, the UK and the USA is mostly a problem of sources. The United States publishes a federal survey of what electricians actually earn. The UK has a collectively agreed hourly rate for each grade. The UAE publishes neither. Most comparison pages fill those gaps with job-board averages, which is how a UAE "average" can differ ninefold from one site to the next. This page uses only figures an official or statutory body publishes, and says plainly where there are none.</p>

<h2>The Short Answer</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Best official figure</th>
            <th style="padding:10px;text-align:left;">Source</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>USA</strong></td><td style="padding:10px;">$63,190 median a year, $30.38 an hour</td><td style="padding:10px;">BLS, May 2025</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UK</strong></td><td style="padding:10px;">£18.38 an hour minimum for a JIB Electrician, about £35,841 a year</td><td style="padding:10px;">JIB, from 5 January 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UAE</strong></td><td style="padding:10px;">No official electrician wage figure, and no minimum wage</td><td style="padding:10px;">Federal Decree-Law No. 33 of 2021</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/electrician-salary-uae-uk-usa-panorama.jpg"
         alt="An electrician working on a distribution board, with the Dubai, London and New York skylines behind him and a passport, a model aircraft and stacked coins on the table"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Electrician Salary in the USA</h2>

<p>The Bureau of Labor Statistics surveys employers directly. For electricians (SOC 47-2111) in May 2025:</p>

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
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Median</td><td style="padding:10px;">$63,190</td><td style="padding:10px;">$30.38</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mean</td><td style="padding:10px;">$71,490</td><td style="padding:10px;">$34.37</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lowest 10 per cent earned less than</td><td style="padding:10px;">$42,640</td><td style="padding:10px;"></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Highest 10 per cent earned more than</td><td style="padding:10px;">$108,510</td><td style="padding:10px;"></td></tr>
    </tbody>
</table>
</div>

<p>The survey counted 757,220 electricians in wage-and-salary jobs. The BLS employment projections put the occupation at <strong>821,000 jobs in 2025</strong>, growing <strong>9 per cent to 2035</strong>, much faster than average, with about <strong>72,700 openings a year</strong>.</p>

<h3>Pay by state</h3>

<p>BLS publishes an annual mean wage for each state. The spread is real, but the figures circulating in most guides are wrong for several states:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">State</th>
            <th style="padding:10px;text-align:left;">Annual mean wage, May 2025</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Hawaii</td><td style="padding:10px;">$92,870</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Illinois</td><td style="padding:10px;">$92,230</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">California</td><td style="padding:10px;">$85,860</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New York</td><td style="padding:10px;">$84,860</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Texas</td><td style="padding:10px;">$59,280</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mississippi</td><td style="padding:10px;">$58,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Florida</td><td style="padding:10px;">$57,660</td></tr>
    </tbody>
</table>
</div>

<p>Mississippi pays electricians slightly more on average than Florida, which most rankings get backwards. The gap between the top and bottom of this list is about $35,000 a year for the same trade.</p>

<h3>Pay by employer type</h3>

<p>BLS also publishes medians by industry. Government ($79,820) and manufacturing ($74,550) pay more than electrical contractors ($61,570), who employ most electricians. The <a href="/blog/industrial-vs-house-wiring-electrician-which-pays-more">industrial versus house wiring guide</a> takes that comparison further.</p>

<h2>Electrician Salary in the UK</h2>

<p>The UK has something the other two countries do not: a published, collectively agreed minimum rate for each electrician grade. The Joint Industry Board's rates apply to employers that follow the JIB agreement in England, Wales and Northern Ireland. The 2026 rates took effect on <strong>5 January 2026</strong>, the first year of a three-year deal.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">JIB grade</th>
            <th style="padding:10px;text-align:left;">Transport provided</th>
            <th style="padding:10px;text-align:left;">Own transport</th>
            <th style="padding:10px;text-align:left;">London rate, transport provided</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Technician</td><td style="padding:10px;">£22.70</td><td style="padding:10px;">£23.87</td><td style="padding:10px;">£25.47</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Approved Electrician</td><td style="padding:10px;">£20.08</td><td style="padding:10px;">£21.19</td><td style="padding:10px;">£22.48</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Electrician</td><td style="padding:10px;">£18.38</td><td style="padding:10px;">£19.54</td><td style="padding:10px;">£20.58</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Apprentice, stage 4</td><td style="padding:10px;" colspan="2">£14.03</td><td style="padding:10px;">£15.72</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Apprentice, stage 1</td><td style="padding:10px;" colspan="2">£8.16</td><td style="padding:10px;">£9.14</td></tr>
    </tbody>
</table>
</div>

<p>On the JIB's 37.5-hour standard week, the Electrician rate comes to about <strong>£35,841 a year</strong>, the Approved Electrician rate about <strong>£39,156</strong> and the Technician rate about <strong>£44,265</strong>, before overtime. The London rate for the Electrician grade is about <strong>12 per cent</strong> above the national one, not the 15 to 30 per cent often quoted.</p>

<p>These are floors for JIB employers, not averages. Self-employed electricians set their own prices and are paid under the Construction Industry Scheme, which the <a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK guide</a> covers in detail.</p>

<h3>Sponsorship for overseas electricians</h3>

<p>Electricians and electrical fitters (SOC 5241) are on the UK's <strong>Temporary Shortage List</strong>, so they can be sponsored as Skilled Workers. The going rate is <strong>£38,800</strong> (£19.90 an hour), with a lower rate of £31,500 for new entrants. The list is under review by the Migration Advisory Committee, so confirm the entry before applying.</p>

<h2>Electrician Salary in the UAE</h2>

<p>The UAE does not publish an official wage survey for electricians, and its labour law sets <strong>no statutory minimum wage</strong>. Every "average electrician salary in Dubai" figure you will find comes from job-board postings or self-reported salary sites, and they describe very different populations: a site electrician on a construction project and a certified electrician offshore are both "electricians". That is why the averages differ so widely, and why none of them tells you what your offer should be.</p>

<p>What the law does guarantee is more useful than any average:</p>

<ul>
    <li><strong>The employer pays recruitment costs.</strong> Under Article 6 of Federal Decree-Law No. 33 of 2021, an employer may not charge the worker, or collect from the worker, recruitment and employment costs either directly or indirectly.</li>
    <li><strong>Wages are protected.</strong> The Wage Protection System runs under Ministerial Resolution No. 340 of 2026. Wages are due on the first day of each Gregorian month, and at least 85 per cent must be transferred on time.</li>
    <li><strong>The night overtime premium does not reach shift workers.</strong> Overtime is paid at basic wage plus 25 per cent, or plus 50 per cent between 10pm and 4am, but that rule does not apply to workers on shifts. Facilities electricians are often on shifts, so get any allowance written into the contract.</li>
    <li><strong>Free zones are different.</strong> Free zone employees are generally not governed by the UAE Labour Law; each zone has its own employment rules.</li>
</ul>

<p>Housing, transport and food are frequently provided or paid as allowances in Gulf construction packages, but no law requires a fixed amount. Compare the full written package, not a basic salary against a UK or US gross figure.</p>

<h2>What About Saudi Arabia?</h2>

<p>Saudi Arabia also sets <strong>no statutory minimum wage for expatriate workers</strong>; the SAR 4,000 figure that circulates applies to Saudi nationals for Saudisation counting. Electricians recruited from abroad must pass the <strong>Professional Verification</strong> trade assessment in their home country before the work visa is issued. The <a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Gulf plumber guide</a> walks through that exam, which covers the electrical trades too.</p>

<p>Large hotel groups and contractors in the Kingdom post maintenance electrician roles on their own careers sites. Apply there or through the Ministry of Human Resources and Social Development's channels, and never pay an agent for a visa.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does an electrician earn in the USA?</h3>
<p>The BLS median was $63,190 a year, or $30.38 an hour, in May 2025. The mean was $71,490, and the top 10 per cent earned more than $108,510.</p>

<h3>Which US state pays electricians the most?</h3>
<p>Of the states in the table above, Hawaii had the highest annual mean wage at $92,870, just ahead of Illinois at $92,230. Florida, at $57,660, was the lowest of the seven.</p>

<h3>What is the JIB rate for an electrician in 2026?</h3>
<p>£18.38 an hour on the national standard rate with transport provided, £19.54 with own transport, and £20.58 on the London rate, from 5 January 2026.</p>

<h3>How much does an Approved Electrician earn in the UK?</h3>
<p>The 2026 JIB minimum is £20.08 an hour, about £39,156 a year on a 37.5-hour week before overtime.</p>

<h3>Can a UK employer sponsor an electrician?</h3>
<p>Yes. SOC 5241 is on the Temporary Shortage List, with a going rate of £38,800, or £31,500 for new entrants.</p>

<h3>Is there a minimum wage for electricians in the UAE?</h3>
<p>No. The UAE sets no statutory minimum wage, so the wage in your offer letter is the figure that binds. Make sure it is written down before you travel.</p>

<h3>Why do UAE electrician salary figures vary so much?</h3>
<p>Because they come from job boards and self-reported surveys mixing very different roles, from site electricians to certified offshore specialists. There is no official UAE wage figure for the occupation.</p>

<h3>Who pays visa and recruitment costs for a UAE electrician job?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 prohibits charging the worker recruitment and employment costs, directly or indirectly.</p>

<h2>People Also Search For</h2>

<h3>Electrician salary USA</h3>
<p>$63,190 median, $71,490 mean, BLS May 2025.</p>

<h3>Electrician salary by state</h3>
<p>Hawaii $92,870 and Illinois $92,230 at the top of the table; Florida $57,660.</p>

<h3>JIB rates 2026</h3>
<p>Electrician £18.38, Approved £20.08, Technician £22.70 an hour.</p>

<h3>Electrician salary UK</h3>
<p>About £35,841 a year at the JIB Electrician minimum, before overtime.</p>

<h3>Electrician salary Dubai</h3>
<p>No official figure and no minimum wage; the contract is what counts.</p>

<h3>Electrician jobs UK visa sponsorship</h3>
<p>SOC 5241 is on the Temporary Shortage List, going rate £38,800.</p>

<h3>Electrician salary Saudi Arabia</h3>
<p>No expatriate minimum wage; Professional Verification is required before the visa.</p>

<h3>BLS electrician job outlook</h3>
<p>Up 9 per cent from 2025 to 2035, about 72,700 openings a year.</p>

<h2>More Job Guides</h2>

<p>The rest of the electrician cluster, and the trades around it:</p>

<ul>
    <li><a href="/blog/electrician-jobs-abroad-with-visa-sponsorship">Electrician Jobs Abroad with Visa Sponsorship</a> &mdash; the Canadian and Australian certification and visa routes.</li>
    <li><a href="/blog/industrial-vs-house-wiring-electrician-which-pays-more">Industrial vs House Wiring Electrician: Which Pays More</a> &mdash; what the BLS industry data shows.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; the wiring regulations, Part P and what CIS takes from a day rate.</li>
    <li><a href="/blog/plumber-salary-in-the-uk-and-usa">Plumber Salary in the UK and USA</a> &mdash; the same comparison for the plumbing trade.</li>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; recruitment costs and the Saudi trade exam.</li>
    <li><a href="/blog/uk-jobs-with-visa-sponsorship">UK Jobs with Visa Sponsorship</a> &mdash; how the Skilled Worker route and the shortage list work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. Pay rates and immigration rules change; confirm current figures with bls.gov, the Joint Industry Board, gov.uk and u.ae before relying on them.</p>
HTML;
    }
}
