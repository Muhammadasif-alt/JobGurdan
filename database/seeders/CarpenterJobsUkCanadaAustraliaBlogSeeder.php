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
 * "Carpenter Jobs in the UK, Canada and Australia" — the overseas companion
 * to "Carpenter Jobs in USA", covering who can actually be hired from abroad
 * in each country and what the official wage data says.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. UK Immigration Salary List rates for carpenters (GBP 33,400 and a lower
 *    GBP 27,800). The ISL has gone. Since 22 July 2025, Skilled Worker
 *    sponsorship for new applicants in RQF 3-5 occupations depends on the
 *    Temporary Shortage List, and SOC 5316 carpenters and joiners is not on
 *    it, so most overseas carpenters cannot currently be newly sponsored.
 *
 * 2. Find a Job vacancy pay (GBP 24-25 an hour, GBP 33,400 in London), LMIA
 *    example rates, SEEK listings with A$60-70 an hour, and job counts
 *    (1,200 / 154 / 1,222) that were snapshots of live boards.
 *
 * 3. Jobs and Skills Australia employment and earnings figures, which could
 *    not be re-checked when this was written; the page points readers to
 *    JSA instead of quoting them.
 *
 * Job Bank NOC 72310 confirmed: C$32.12 median (C$22.00-C$44.23), Alberta
 * C$34.00, BC C$32.00, Manitoba C$27.00, Quebec C$36.84.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class CarpenterJobsUkCanadaAustraliaBlogSeeder extends Seeder
{
    private const UK_APPLY_URL = 'https://www.gov.uk/find-a-job';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch';

    private const AUSTRALIA_APPLY_URL = 'https://www.workforceaustralia.gov.au/individuals/jobs/search';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
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
        $title = 'Carpenter Jobs in the UK, Canada and Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Carpenters are wanted in all three countries, but only two can sponsor you now. The UK dropped carpenters from new Skilled Worker sponsorship in July 2025; Canada pays a C$32.12 median and Australia assesses carpenters through TRA.',
                'content' => $content,
                'featured_image' => 'blogs/carpenter-jobs-uk-canada-australia.jpg',
                'tags' => 'carpenter jobs uk, carpenter jobs canada, carpenter jobs australia, carpenter visa sponsorship, noc 72310, anzsco 331212, soc 5316 carpenter, skills in demand visa carpenter',
                'meta_title' => 'Carpenter Jobs in the UK, Canada and Australia: Visa Rules',
                'meta_description' => 'Carpenter jobs in the UK, Canada and Australia: who can still sponsor overseas carpenters, Job Bank wages, TRA skills assessment and the 2025 UK rule change.',
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

        $listings = [
            [
                'advertiser' => 'UK Building Contractors & Joinery Firms (Aggregated)',
                'reference' => 'uk-carpenter-aggregated',
                'location' => 'United Kingdom',
                'position' => 'Carpenter and Joiner — UK Building Contractors (Right to Work Required)',
                'description' => $this->ukJobDescription(),
                'work_hours' => 'Full-time, site hours',
                'url' => self::UK_APPLY_URL,
                'meta' => 'Carpenter and joiner roles with UK contractors, open to people who already have the right to work. SOC 5316 is not eligible for new Skilled Worker sponsorship.',
                'keywords' => 'carpenter jobs uk, joiner jobs uk, site carpenter, cscs card carpenter, soc 5316',
            ],
            [
                'advertiser' => 'Canadian Residential & Commercial Builders (Aggregated)',
                'reference' => 'canada-carpenter-aggregated',
                'location' => 'Canada',
                'position' => 'Carpenter — Canadian Residential and Commercial Builders',
                'description' => $this->canadaJobDescription(),
                'work_hours' => 'Full-time, seasonal peaks in spring and summer',
                'url' => self::CANADA_APPLY_URL,
                'meta' => 'Carpenter roles in Canada under NOC 72310. Job Bank reports a C$32.12 national median; certification rules vary by province.',
                'keywords' => 'carpenter jobs canada, noc 72310, red seal carpenter, framing carpenter canada, finish carpenter canada',
            ],
            [
                'advertiser' => 'Australian Residential & Commercial Builders (Aggregated)',
                'reference' => 'australia-carpenter-aggregated',
                'location' => 'Australia',
                'position' => 'Carpenter — Australian Residential and Commercial Builders',
                'description' => $this->australiaJobDescription(),
                'work_hours' => 'Full-time, site hours',
                'url' => self::AUSTRALIA_APPLY_URL,
                'meta' => 'Carpenter roles in Australia under ANZSCO 331212. Overseas applicants usually need a TRA skills assessment and an employer-sponsored visa.',
                'keywords' => 'carpenter jobs australia, anzsco 331212, tra skills assessment carpenter, skills in demand visa, carpenter visa australia',
            ],
        ];

        foreach ($listings as $listing) {
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser']],
                ['type' => 'Private', 'display_reference' => $listing['reference']]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location']],
                ['area' => 'Nationwide', 'country' => $listing['location']]
            );

            Job::updateOrCreate(
                [
                    'position' => $listing['position'],
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => $listing['work_hours'],
                    'language' => 'English',
                    // Pay varies by region and employer, and this site does not
                    // republish pay from individual vacancies.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['url'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>House builders, fit-out contractors and joinery firms across the UK recruit carpenters and joiners for first fix, second fix and bench joinery.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>An NVQ Level 2 or 3 in carpentry and joinery, or equivalent experience</li>
    <li>A CSCS card for work on most construction sites</li>
    <li>The right to work in the UK</li>
</ul>

<p><strong>Visa note:</strong> since 22 July 2025, carpenters and joiners (SOC 5316) are not eligible for new Skilled Worker sponsorship because the occupation is not on the Temporary Shortage List. These roles suit people who already have the right to work.</p>

<p><strong>Note:</strong> immigration rules are set by the Home Office, not by JobGader. Check gov.uk before applying from abroad.</p>
JOBHTML;
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Residential and commercial builders, renovation firms and formwork contractors across Canada recruit carpenters under NOC 72310.</p>

<h3>Requirements</h3>
<ul>
    <li>A three- to four-year apprenticeship, or equivalent experience plus trade courses</li>
    <li>Provincial trade certification where the province requires it, with the Red Seal for interprovincial work</li>
    <li>An employer offer or immigration programme for the work permit if applying from abroad</li>
</ul>

<p><strong>Pay:</strong> Job Bank reports a national median of C$32.12 an hour for 2023-2024. Individual employers set their own rates.</p>

<p><strong>Note:</strong> certification and immigration rules are set by provincial regulators and IRCC, not by JobGader. Confirm them before applying.</p>
JOBHTML;
    }

    private function australiaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Residential builders, commercial contractors and shopfitters across Australia recruit carpenters under ANZSCO 331212.</p>

<h3>Requirements</h3>
<ul>
    <li>A Certificate III in Carpentry or an equivalent overseas qualification and experience</li>
    <li>A skills assessment from Trades Recognition Australia where the visa requires one</li>
    <li>A White Card for construction induction</li>
    <li>State licensing for some residential work</li>
</ul>

<p><strong>Note:</strong> visa and assessment rules are set by the Department of Home Affairs and TRA, not by JobGader. Confirm them on the official sites before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Carpenters are short in the UK, Canada and Australia alike, and that is where most guides stop. The question that matters to someone applying from abroad is different: which of the three can still sponsor a carpenter? Since July 2025 the answer has changed for the UK. This page sets the three countries side by side on official sources, and leaves out the vacancy pay and job counts that job boards change daily. For the US market, see <a href="/blog/carpenter-jobs-in-usa">Carpenter Jobs in USA</a>.</p>

<h2>The Short Answer</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">United Kingdom</th>
            <th style="padding:10px;text-align:left;">Canada</th>
            <th style="padding:10px;text-align:left;">Australia</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Occupation code</strong></td><td style="padding:10px;">SOC 5316</td><td style="padding:10px;">NOC 72310</td><td style="padding:10px;">ANZSCO 331212</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>New employer sponsorship from abroad</strong></td><td style="padding:10px;">Not currently, for most applicants</td><td style="padding:10px;">Yes, with an employer offer and work permit</td><td style="padding:10px;">Yes, through employer-sponsored visas</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skills recognition</strong></td><td style="padding:10px;">NVQ and CSCS card</td><td style="padding:10px;">Provincial certification and Red Seal</td><td style="padding:10px;">Trades Recognition Australia</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Official wage source</strong></td><td style="padding:10px;">ONS</td><td style="padding:10px;">Job Bank</td><td style="padding:10px;">Jobs and Skills Australia</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/carpenter-jobs-uk-canada-australia-saw.jpg"
         alt="A carpenter in a yellow hard hat and safety glasses cutting timber with a circular saw, with the Houses of Parliament, the Toronto skyline and the Sydney Opera House behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>United Kingdom: the Rule Change Most Guides Missed</h2>

<p>Carpenters and joiners are SOC 2020 code <strong>5316</strong>, a medium-skilled occupation at RQF levels 3 to 5. On <strong>22 July 2025</strong> the Home Office raised the skill threshold for the Skilled Worker visa. Since then, a medium-skilled occupation can only be sponsored for a new applicant if it appears on the <strong>Temporary Shortage List</strong>.</p>

<p>When we checked the list for this page, <strong>carpenters and joiners were not on it</strong>. Neighbouring trades such as plumbers, painters and decorators, and floorers and wall tilers were. In practice:</p>

<ul>
    <li><strong>Most overseas carpenters cannot currently be newly sponsored</strong> under the Skilled Worker route.</li>
    <li>Pages quoting an Immigration Salary List rate for carpenters are out of date. The Immigration Salary List was replaced by the Temporary Shortage List.</li>
    <li>Workers who already held Skilled Worker permission before 22 July 2025 are covered by transitional arrangements and may be able to change employer or extend in the same occupation.</li>
    <li>The general Skilled Worker salary threshold is now <strong>GBP 41,700</strong>, with English at B2, but it applies to occupations that remain eligible.</li>
</ul>

<p>The list is temporary and under review, so it can change. <strong>Check the current Temporary Shortage List on gov.uk</strong> before you pay for any qualification, assessment or travel on the strength of a UK offer.</p>

<p>If you already have the right to work in the UK, the trade is open: employers look for an NVQ Level 2 or 3 in carpentry and joinery and a CSCS card for site access.</p>

<h2>Canada: Job Bank Wages and Provincial Certification</h2>

<p>Job Bank reports hourly wages for carpenters (NOC 72310), based on 2023 to 2024 data and updated on 19 November 2025:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Median hourly wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong> (low C$22.00, high C$44.23)</td><td style="padding:10px;"><strong>C$32.12</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">C$36.84</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">C$34.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">C$32.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">C$32.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Saskatchewan</td><td style="padding:10px;">C$30.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Manitoba</td><td style="padding:10px;">C$27.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nova Scotia</td><td style="padding:10px;">C$25.00</td></tr>
    </tbody>
</table>
</div>

<p>Quebec, not Alberta, has the highest provincial median. At the national median, a 40-hour week over 52 weeks comes to about <strong>C$66,800</strong> before overtime.</p>

<p><strong>Certification.</strong> Carpentry is a Red Seal trade, which lets a certified carpenter work across provinces. Whether certification is compulsory depends on the province, so check the apprenticeship authority where you plan to work. Foreign carpenters normally need their experience assessed by that authority before they can sit the certification exam.</p>

<p><strong>Getting there.</strong> Most foreign carpenters arrive with an employer job offer and a work permit, sometimes supported by a Labour Market Impact Assessment, or through a provincial nominee programme. Never pay an employer or agent for an LMIA; it is illegal for an employer to recover that cost from you.</p>

<h2>Australia: Skills Assessment First</h2>

<p>Carpenter is ANZSCO <strong>331212</strong>. Australia's main employer-sponsored route is the <strong>Skills in Demand visa (subclass 482)</strong>, which replaced the Temporary Skill Shortage visa in December 2024. Whether a carpenter qualifies depends on the occupation lists in force, so check the Core Skills Occupation List on the Home Affairs site.</p>

<ul>
    <li><strong>Trades Recognition Australia (TRA)</strong> is the assessing authority for carpenters. Depending on your visa and passport, you may need a TRA skills assessment, which usually includes a technical interview and a practical assessment.</li>
    <li><strong>Construction induction.</strong> A White Card is required before working on any Australian construction site.</li>
    <li><strong>State licensing.</strong> Some residential building work requires a state licence or registration on top of the trade qualification.</li>
</ul>

<p>Jobs and Skills Australia publishes employment, earnings and hours for carpenters and joiners. Use it rather than the hourly rates quoted in individual adverts, which reflect one employer on one day.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/carpenter-jobs-uk-canada-australia-framing.jpg"
         alt="A carpenter in a yellow hard hat and checked shirt marking timber framing, with the Westminster skyline, the CN Tower and the Sydney Harbour Bridge in three panels behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which Country Should You Target?</h2>

<ul>
    <li><strong>Already in the UK with the right to work?</strong> The trade is open and short of workers. From abroad, the sponsorship door is shut for now.</li>
    <li><strong>Want official wage data before you move?</strong> Canada's Job Bank publishes it by province, and the national median is C$32.12 an hour.</li>
    <li><strong>Have a formal trade qualification and site experience?</strong> Australia's TRA assessment is demanding but clear about what it wants.</li>
</ul>

<h2>Avoiding Scams</h2>

<ul>
    <li><strong>No genuine employer charges for a job offer</strong> or a sponsorship certificate.</li>
    <li><strong>A UK carpenter "visa" offer is a red flag</strong> while the occupation is off the shortage list.</li>
    <li><strong>Check the employer</strong> on the official register: the UK register of licensed sponsors, Job Bank for Canada, and ABN Lookup for Australia.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a UK Skilled Worker visa as a carpenter?</h3>
<p>Not as a new applicant at present. Since 22 July 2025, medium-skilled occupations need to be on the Temporary Shortage List, and carpenters and joiners (SOC 5316) were not on it when we checked. Check the current list on gov.uk.</p>

<h3>Is the Immigration Salary List rate for carpenters still valid?</h3>
<p>No. The Immigration Salary List was replaced by the Temporary Shortage List, so rates quoted from the old list no longer apply.</p>

<h3>How much does a carpenter earn in Canada?</h3>
<p>Job Bank reports a national median of C$32.12 an hour for NOC 72310, from C$22.00 to C$44.23. Quebec has the highest provincial median at C$36.84.</p>

<h3>What is the ANZSCO code for carpenter?</h3>
<p>331212. Trades Recognition Australia is the assessing authority.</p>

<h3>Which Australian visa do carpenters use?</h3>
<p>Employer-sponsored carpenters usually apply for the Skills in Demand visa (subclass 482), which replaced the Temporary Skill Shortage visa in December 2024. Check the occupation lists before applying.</p>

<h3>Is carpentry a Red Seal trade?</h3>
<p>Yes. Red Seal certification lets a carpenter work across Canadian provinces. Whether certification is compulsory depends on the province.</p>

<h3>Do I need a CSCS card to work as a carpenter in the UK?</h3>
<p>Most construction sites require one. It shows you have the training and qualifications for the work you do on site.</p>

<h3>Which country is easiest for a foreign carpenter?</h3>
<p>Canada and Australia, because both still sponsor carpenters through employers. The UK currently does not for new applicants.</p>

<h2>People Also Search For</h2>

<h3>Carpenter visa sponsorship UK</h3>
<p>SOC 5316 is not on the Temporary Shortage List, so new sponsorship is closed for most applicants.</p>

<h3>NOC 72310</h3>
<p>Carpenters in Canada; C$32.12 national median on Job Bank.</p>

<h3>ANZSCO 331212</h3>
<p>Carpenter in Australia, assessed by Trades Recognition Australia.</p>

<h3>Temporary Shortage List construction</h3>
<p>Includes plumbers, painters and decorators, and floorers, but not carpenters.</p>

<h3>Red Seal carpenter</h3>
<p>The interprovincial certification for carpenters in Canada.</p>

<h3>TRA skills assessment carpenter</h3>
<p>Usually a technical interview and practical assessment.</p>

<h3>Carpenter salary Alberta</h3>
<p>C$34.00 an hour median on Job Bank.</p>

<h3>Skills in Demand visa 482</h3>
<p>Australia's employer-sponsored visa since December 2024.</p>

<h2>More Job Guides</h2>

<p>Related trades and markets:</p>

<ul>
    <li><a href="/blog/carpenter-jobs-in-usa">Carpenter Jobs in USA</a> &mdash; the US carpentry market.</li>
    <li><a href="/blog/welder-salary-in-usa">Welder Salary in USA</a> &mdash; official pay by industry for another construction trade.</li>
    <li><a href="/blog/painter-and-decorator-jobs-in-the-gulf">Painter and Decorator Jobs in the Gulf</a> &mdash; a finishing trade in the UAE and Saudi Arabia.</li>
    <li><a href="/blog/how-to-become-a-licensed-plumber-in-canada-or-australia">How to Become a Licensed Plumber in Canada or Australia</a> &mdash; the same certification systems for another trade.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the wider Australian site market.</li>
    <li><a href="/blog/uk-jobs-with-visa-sponsorship">UK Jobs with Visa Sponsorship</a> &mdash; which UK routes are still open.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. UK rules are from gov.uk, Canadian wages from Job Bank, and Australian occupation details from Home Affairs and Trades Recognition Australia. Immigration rules change; confirm the current position before applying.</p>
HTML;
    }
}
