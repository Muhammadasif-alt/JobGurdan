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
 * "Heavy Equipment Operator Jobs in Canada" — excavator, dozer, loader and
 * grader operators in construction, mining, forestry and road work. The
 * welder guide owns Red Seal compulsory status for welding and the LMIA
 * streams; this one owns Job Bank wages and outlooks for NOC 73400, the Red
 * Seal operator trades, crane certification, Ontario's working at heights
 * rule and the Express Entry position.
 *
 * Corrections to the draft:
 *
 * 1. It says operators are in steady demand coast to coast and that Alberta
 *    and British Columbia pay premium wages for remote work. Job Bank rates
 *    the 2025-2027 outlook as Limited in Alberta, British Columbia, Quebec and
 *    Manitoba, and the national 2024-2033 projection is Balance.
 *
 * 2. It says some provinces offer apprenticeships without naming them. Heavy
 *    Equipment Operator (Dozer), (Excavator) and (Tractor-Loader-Backhoe) are
 *    Red Seal trades, and the Dozer trade is designated in New Brunswick,
 *    Newfoundland and Labrador, Nova Scotia, Nunavut, Ontario, Prince Edward
 *    Island and Quebec, not in Alberta or British Columbia.
 *
 * 3. It gives Ontario and Alberta as examples of crane tickets. Mobile crane
 *    certification for specified cranes is compulsory in seven provinces:
 *    Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Alberta and
 *    British Columbia. Crane operators are a separate occupation (NOC 72500).
 *
 * 4. It lists fall protection training as commonly required. In Ontario,
 *    construction workers who use fall protection must complete working at
 *    heights training approved by the Chief Prevention Officer, valid for
 *    three years.
 *
 * 5. It says nothing about immigration. NOC 73400 is not in the Express Entry
 *    trade occupations category, although heavy-duty equipment mechanics
 *    (72401) are.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class HeavyEquipmentOperatorJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-heavy-equipment-operator-jobs.html';

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
        $title = 'Heavy Equipment Operator Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Job Bank puts the median at $32.50 an hour, the 2025-2027 outlook is Limited in Alberta and British Columbia, crane certification is compulsory in seven provinces, and operators are not in the Express Entry trades category.',
                'content' => $content,
                'featured_image' => 'blogs/heavy-equipment-operator-jobs-in-canada.jpg',
                'tags' => 'heavy equipment operator jobs canada, heavy equipment operator salary canada, excavator operator jobs, red seal heavy equipment operator, mobile crane operator certification, working at heights ontario, noc 73400, heavy equipment operator jobs alberta, heavy equipment operator jobs bc, operating engineers union',
                'meta_title' => 'Heavy Equipment Operator Jobs in Canada 2026: Pay, Tickets',
                'meta_description' => 'Heavy equipment operator jobs in Canada: Job Bank pay by province, Limited outlook in Alberta and BC, Red Seal and crane tickets, and Express Entry rules.',
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
            ['name' => 'Canadian Civil Contractors, Mining & Forestry Operators (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-heavy-equipment-operator-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Heavy Equipment Operator — Excavator, Dozer, Loader and Grader Work in Construction, Mining and Forestry, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Long days in the construction season; camp rotations on remote mining and forestry sites',
                'language' => 'English',
                // Wages vary widely by province and by season, so no single
                // range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Heavy equipment operator roles in Canadian construction, road building, mining and forestry. Excavator, dozer, loader and grader work.',
                'seo_keywords' => 'heavy equipment operator jobs canada, excavator operator jobs, dozer operator jobs, loader operator jobs, grader operator jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Civil contractors, road builders, mining companies, forestry operators and municipalities across Canada hire heavy equipment operators for excavators, dozers, loaders, backhoes and graders.</p>

<h3>What the work involves</h3>
<p>Excavating, grading and levelling sites, loading and moving material, pre-shift inspections and basic maintenance, working to site plans and grade stakes, and coordinating with ground crews and spotters.</p>

<h3>Requirements</h3>
<ul>
    <li>Operating experience or training on the machines listed; Red Seal certification helps in provinces that offer the operator trades</li>
    <li>A crane certificate or registered apprenticeship for crane work in the provinces where it is compulsory</li>
    <li>In Ontario, valid working at heights training if you use fall protection on a construction project</li>
    <li>A driver's licence, and the right to work in Canada</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured wages.</strong> Job Bank reports $24.00 low, $32.50 median and $45.00 high an hour for NOC 73400</li>
    <li><strong>Seasonal hours.</strong> Road and civil work slows in winter in most provinces, which affects annual earnings</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask which machines you will run, the expected season length and whether camp or travel costs are covered,</strong> before accepting an offer.</p>

<p><strong>Note:</strong> pay, certification and immigration rules are set by employers, provincial apprenticeship authorities and IRCC &mdash; not by JobGader. Confirm the details with the employer before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Heavy equipment operators run the excavators, dozers, loaders, backhoes and graders that build Canada's roads, subdivisions, pipelines and mines. It is hands-on, well-paid work that can be learned through a short course and on-site experience, with apprenticeships for those who want a certificate. Before you train or apply, it helps to know what operators really earn in each province, where the outlook is weakest, which tickets are compulsory, and what the job does for immigration.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-heavy-equipment-operator-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128679; Browse Heavy Equipment Operator Jobs in Canada &rarr;
    </a>
</div>

<h2>What the Official Outlook Says</h2>

<p>Guides say skilled operators remain in steady demand from coast to coast. Canada classifies the job as <strong>NOC 73400, heavy equipment operators</strong>, a TEER 3 occupation, and the official outlook is more uneven:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Job Bank outlook 2025-2027</th>
            <th style="padding:10px;text-align:left;">Provinces and territories</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Good</strong></td><td style="padding:10px;">Saskatchewan, Prince Edward Island, Nunavut</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Moderate</strong></td><td style="padding:10px;">Ontario, Nova Scotia, New Brunswick, Newfoundland and Labrador, Yukon</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Limited</strong></td><td style="padding:10px;">Alberta, British Columbia, Quebec, Manitoba, Northwest Territories</td></tr>
    </tbody>
</table>
</div>

<p>Nationally, the 2024 to 2033 projection is <strong>Balance</strong>: labour demand and supply are expected to be broadly in line. Guides single out Alberta and British Columbia for remote resource work, but both are rated <strong>Limited</strong> for 2025 to 2027. Those provinces still pay well, as the table below shows, but new operators compete for fewer openings.</p>

<h2>What a Heavy Equipment Operator Does</h2>

<ul>
    <li>Operating excavators, dozers, loaders, backhoes and graders</li>
    <li>Digging, grading and levelling to site plans and grade stakes</li>
    <li>Loading and moving gravel, soil, rock and debris</li>
    <li>Pre-shift inspections, greasing and basic maintenance</li>
    <li>Working with ground crews and spotters around workers and utilities</li>
    <li>Reporting mechanical problems before they become breakdowns</li>
</ul>

<p>Guides include cranes in the list of machines. Crane operators are a <strong>separate occupation (NOC 72500)</strong> with their own compulsory certification in most provinces, covered below.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/heavy-equipment-operator-jobs-in-canada-excavator.jpg"
         alt="An operator in a yellow hard hat and high-visibility vest at the controls of an excavator digging soil, with a wheel loader, snow-capped mountains and a Canadian flag in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Heavy Equipment Operator Salary by Province</h2>

<p>Guides quote CAD 45,000 to 55,000 for entry-level operators, 55,000 to 75,000 for experienced operators and 75,000 to 100,000 or more for specialized operators. Job Bank's wages for NOC 73400, updated in November 2025, are close to that spread. Annual figures below assume 2,080 hours, a full year of 40-hour weeks:</p>

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
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Canada</td><td style="padding:10px;">$24.00 ($49,920)</td><td style="padding:10px;">$32.50 ($67,600)</td><td style="padding:10px;">$45.00 ($93,600)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">$27.00 ($56,160)</td><td style="padding:10px;">$35.75 ($74,360)</td><td style="padding:10px;">$46.00 ($95,680)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">$24.00 ($49,920)</td><td style="padding:10px;">$36.00 ($74,880)</td><td style="padding:10px;">$43.40 ($90,272)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">$26.00 ($54,080)</td><td style="padding:10px;">$34.30 ($71,344)</td><td style="padding:10px;">$45.00 ($93,600)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">$23.63 ($49,150)</td><td style="padding:10px;">$32.00 ($66,560)</td><td style="padding:10px;">$47.70 ($99,216)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nova Scotia</td><td style="padding:10px;">$18.00 ($37,440)</td><td style="padding:10px;">$25.00 ($52,000)</td><td style="padding:10px;">$35.50 ($73,840)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Northwest Territories</td><td style="padding:10px;">$26.80 ($55,744)</td><td style="padding:10px;">$43.73 ($90,958)</td><td style="padding:10px;">$64.01 ($133,141)</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>Quebec, not Alberta, has the highest provincial median</strong>, at $36.00 an hour, with British Columbia close behind. The Northwest Territories pay the most overall.</li>
    <li><strong>Atlantic Canada pays much less.</strong> Medians in Nova Scotia and New Brunswick are $25.00 an hour.</li>
    <li><strong>Annual figures assume year-round work.</strong> Road and civil construction slows in winter in most provinces, so a seasonal operator can earn well below the annual equivalent. Camp and overtime hours can push it above.</li>
</ul>

<h2>Training, Red Seal and Apprenticeships</h2>

<p>Most operators start with a private or college operator course lasting weeks to months, then build seat time on site. Guides say some provinces offer apprenticeships. The national standard is the Red Seal program, which has <strong>three heavy equipment operator trades</strong>:</p>

<ul>
    <li><strong>Heavy Equipment Operator (Dozer)</strong></li>
    <li><strong>Heavy Equipment Operator (Excavator)</strong></li>
    <li><strong>Heavy Equipment Operator (Tractor-Loader-Backhoe)</strong></li>
</ul>

<p>The Dozer trade is designated in <strong>New Brunswick, Newfoundland and Labrador, Nova Scotia, Nunavut, Ontario, Prince Edward Island and Quebec</strong>. It is <strong>not designated in Alberta or British Columbia</strong>, where employers rely on experience, operator courses and in-house assessments instead. In Quebec, construction industry work is regulated by the Commission de la construction du Qu&eacute;bec, which issues competency certificates for construction trades.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/heavy-equipment-operator-jobs-in-canada-site.jpg"
         alt="A site worker in a hard hat and safety vest using a two-way radio beside site plans while an excavator loads a dump truck, with mountains and a city skyline across the water"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Crane Tickets Are Compulsory</h2>

<p>Guides give Ontario and Alberta as examples of provinces with a crane operator ticket. The requirement is wider: <strong>mobile crane operator certification, for specified types of cranes, is compulsory in Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Alberta and British Columbia</strong>.</p>

<ul>
    <li><strong>Ontario.</strong> Hoisting Engineer &mdash; Mobile Crane Operator 1 and Mobile Crane Operator 2 are trades with apprenticeship programs administered by Skilled Trades Ontario. Mobile Crane Operator 1 covers lattice and telescopic boom cranes lifting more than 16,000 pounds.</li>
    <li><strong>Alberta.</strong> You must hold a recognized certificate or be a registered apprentice to operate a boom truck, tower crane or mobile crane, with mobile crane certification applying from 15 tons of lifting capacity.</li>
</ul>

<h2>Safety Training</h2>

<ul>
    <li><strong>Working at heights in Ontario.</strong> Construction workers who use fall protection must complete a working at heights program approved by the Chief Prevention Officer and delivered by an approved provider. It is <strong>valid for three years</strong>, and the province warns about fraudulent providers.</li>
    <li><strong>WHMIS.</strong> Workers who handle hazardous products must receive WHMIS education and training from their employer.</li>
    <li><strong>Site orientation and ground disturbance.</strong> Pipeline and oil and gas sites commonly ask for ground disturbance training before an operator digs.</li>
</ul>

<h2>Immigration: What the Job Does and Does Not Do</h2>

<ul>
    <li><strong>Not in the Express Entry trades category.</strong> IRCC's trade occupations list, last modified on 22 June 2026, does not include heavy equipment operators (73400) or crane operators (72500). It does include <strong>heavy-duty equipment mechanics (72401)</strong>.</li>
    <li><strong>Federal Skilled Trades Program.</strong> The occupation falls in NOC major group 73, which the program covers, but you still need a qualifying job offer or a provincial certificate of qualification, plus the language minimums.</li>
    <li><strong>LMIA jobs.</strong> Employers can hire through the Temporary Foreign Worker Program, and the wage offered decides which stream applies. See our visa sponsorship guide for how the streams work.</li>
</ul>

<h2>Where to Find Heavy Equipment Operator Jobs</h2>

<ol>
    <li><strong>Job boards.</strong> Indeed Canada and Job Bank list civil, municipal, mining and forestry postings.</li>
    <li><strong>Union hiring halls.</strong> International Union of Operating Engineers locals dispatch members to union contractors and run training.</li>
    <li><strong>Contractors and mining companies.</strong> Large civil and resource companies post directly, often before the spring season.</li>
    <li><strong>Municipalities.</strong> Cities and counties hire operators for road maintenance and snow clearing, which evens out the season.</li>
</ol>

<h2>Tips for Landing a Heavy Equipment Operator Job</h2>

<ul>
    <li><strong>List machines and hours.</strong> Name each machine, the size class and roughly how many hours you have on it.</li>
    <li><strong>Carry your tickets.</strong> Working at heights in Ontario, WHMIS and any ground disturbance or crane certificates.</li>
    <li><strong>Apply before spring.</strong> Civil contractors hire for the season in late winter and early spring.</li>
    <li><strong>Expect a seat test.</strong> Many employers assess you on the machine before hiring.</li>
    <li><strong>Consider snow clearing.</strong> Municipal winter work fills the gap in the construction season.</li>
</ul>

<h2>Career Progression</h2>

<p>Operators move into lead operator, foreman and site supervisor roles, equipment training and safety, or fleet management. Some add a crane certificate through a compulsory crane apprenticeship, and others move into heavy-duty equipment mechanics, a separate Red Seal trade that is in the Express Entry trades category.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do heavy equipment operators make in Canada?</h3>
<p>Job Bank reports a national median of $32.50 an hour for NOC 73400, about $67,600 a year for full-year 40-hour weeks, with provincial medians from $25.00 in Nova Scotia to $36.00 in Quebec.</p>

<h3>Which province pays heavy equipment operators the most?</h3>
<p>Quebec has the highest provincial median at $36.00 an hour, followed by British Columbia at $35.75. The Northwest Territories median is $43.73.</p>

<h3>Is there a Red Seal for heavy equipment operators?</h3>
<p>Yes. Heavy Equipment Operator (Dozer), (Excavator) and (Tractor-Loader-Backhoe) are Red Seal trades, though not every province designates them.</p>

<h3>Do I need a ticket to operate a crane in Canada?</h3>
<p>For specified types of mobile cranes, certification is compulsory in Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Alberta and British Columbia.</p>

<h3>Is working at heights training required in Ontario?</h3>
<p>Yes, for construction workers who use fall protection. The training must be approved by the Chief Prevention Officer and is valid for three years.</p>

<h3>Is the job outlook good in Alberta?</h3>
<p>Job Bank rates the 2025-2027 outlook for heavy equipment operators as Limited in Alberta and British Columbia, and Good in Saskatchewan.</p>

<h3>Are heavy equipment operators in the Express Entry trades category?</h3>
<p>No. The trade occupations list does not include NOC 73400, although heavy-duty equipment mechanics are included.</p>

<h3>How long does heavy equipment operator training take?</h3>
<p>Operator courses usually run from a few weeks to several months; a Red Seal apprenticeship takes longer and combines on-site hours with technical training.</p>

<h2>People Also Search For</h2>

<h3>Excavator operator jobs Canada</h3>
<p>The most common operator posting, with a Red Seal trade of its own.</p>

<h3>Heavy equipment operator salary Alberta</h3>
<p>A Job Bank median of $34.30 an hour, with a Limited outlook for 2025-2027.</p>

<h3>Heavy equipment operator jobs BC</h3>
<p>A $35.75 median, but a Limited outlook.</p>

<h3>Red Seal excavator</h3>
<p>One of three Red Seal heavy equipment operator trades.</p>

<h3>Mobile crane operator certification</h3>
<p>Compulsory for specified cranes in seven provinces.</p>

<h3>Working at heights Ontario</h3>
<p>Required with fall protection on construction projects, valid three years.</p>

<h3>Operating engineers union Canada</h3>
<p>IUOE locals run hiring halls and training for operators.</p>

<h3>Heavy equipment operator NOC code</h3>
<p>NOC 73400, a TEER 3 occupation.</p>

<h2>More Job Guides</h2>

<p>Comparing trades and driving jobs? These cover them:</p>

<ul>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; a Red Seal trade on the same sites, and where certification is compulsory.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; how the LMIA streams work for trades employers.</li>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a> &mdash; another licensed machine job with provincial rules.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; seasonal work on another Canadian program.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; site work under Australian awards and the White Card.</li>
    <li><a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> &mdash; heavy vehicle work and licence classes across the border.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Wage data, outlooks, trade certification rules, safety training requirements and Express Entry categories change and differ by province. Confirm the current position with Job Bank, the Red Seal program, your provincial apprenticeship authority and IRCC before training, applying or accepting an offer.</p>
HTML;
    }
}
