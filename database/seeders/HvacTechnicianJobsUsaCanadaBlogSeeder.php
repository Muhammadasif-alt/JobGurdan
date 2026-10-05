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
 * "HVAC Technician Jobs in the USA and Canada" — the demand and pay page of
 * the HVAC/R cluster, built on BLS OEWS May 2025, the BLS 2025-2035
 * projections and Job Bank wages for NOC 72402.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Projections from the superseded 2024-2034 round (8 per cent, 40,100
 *    openings a year). The current round is 11 per cent and 40,600.
 *
 * 2. A top-decile figure of $91,020 and metro medians of $84,100 for
 *    Manhattan and $47,560 for Jackson, Mississippi. BLS reports $95,210,
 *    $77,990 for the New York metro area and $52,060 for Jackson.
 *
 * 3. Canadian pay from Red Seal Recruiting and Indeed, and a province table
 *    with no source. Job Bank publishes the official wages and is used instead.
 *
 * 4. "California, Arizona, Texas and Florida require a technician licence".
 *    California, Arizona and Florida license contractors; Texas registers or
 *    certifies technicians through TDLR.
 *
 * 5. Ten Indeed buttons and Trane hourly rates quoted from listings.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class HvacTechnicianJobsUsaCanadaBlogSeeder extends Seeder
{
    private const US_APPLY_URL = 'https://www.usa.gov/job-search';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch';

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
        $title = 'HVAC Technician Jobs in the USA and Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'BLS projects 11 per cent growth for US HVAC technicians to 2035, with 40,600 openings a year and a $61,010 median. In Canada, Job Bank puts the median at C$37.50 an hour, and certification is compulsory in seven provinces.',
                'content' => $content,
                'featured_image' => 'blogs/hvac-technician-jobs-usa-canada.jpg',
                'tags' => 'hvac technician jobs usa, hvac technician jobs canada, hvac technician salary, hvac demand 2035, noc 72402, epa 608 certification, red seal refrigeration mechanic, hvac licence by state',
                'meta_title' => 'HVAC Technician Jobs in USA and Canada: Pay and Demand',
                'meta_description' => 'HVAC technician demand and pay in the USA and Canada on BLS and Job Bank data: 11% growth to 2035, wages by state and province, and how to qualify.',
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

        $usAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'US Mechanical Contractors & Building Services Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-hvac-technician-aggregated']
        );

        $usLocation = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            [
                'position' => 'HVAC Technician — US Mechanical Contractors and Building Services',
                'advertiser_id' => $usAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $usLocation->id,
                'description' => $this->usJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with seasonal overtime and on-call rotas in summer and winter peaks',
                'language' => 'English',
                // Pay ranges from a $48,680 median in Mississippi to $84,390 in
                // the District of Columbia, so no single range is honest here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::US_APPLY_URL,
                'meta_description' => 'HVAC technician roles with US mechanical contractors and building services firms. EPA 608 certification is required to handle refrigerant.',
                'seo_keywords' => 'hvac technician jobs usa, hvac service technician, hvac installer jobs, epa 608 jobs, hvac jobs',
            ]
        );

        $canadaAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Canadian Mechanical Contractors & Refrigeration Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'canada-hvac-mechanic-aggregated']
        );

        $canadaLocation = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        Job::updateOrCreate(
            [
                'position' => 'Refrigeration and Air Conditioning Mechanic — Canadian Mechanical Contractors',
                'advertiser_id' => $canadaAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $canadaLocation->id,
                'description' => $this->canadaJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, often with service-call rotations',
                'language' => 'English',
                // Job Bank medians run from C$29.76 in New Brunswick to C$40.00
                // in Alberta, BC and Saskatchewan; no one range covers them.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::CANADA_APPLY_URL,
                'meta_description' => 'Refrigeration and air conditioning mechanic roles in Canada (NOC 72402). Certification is compulsory in seven provinces, including Ontario.',
                'seo_keywords' => 'hvac jobs canada, refrigeration mechanic canada, noc 72402, red seal refrigeration, 313a ontario',
            ]
        );
    }

    private function usJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Mechanical contractors, building services companies, hospitals, universities and public bodies across the United States recruit HVAC technicians to install and service heating, cooling and refrigeration systems.</p>

<h3>What the work involves</h3>
<ul>
    <li>Installing and commissioning furnaces, heat pumps, split systems and rooftop units</li>
    <li>Diagnosing electrical, airflow and refrigerant faults</li>
    <li>Recovering, recharging and recording refrigerant under EPA rules</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li><strong>EPA Section 608 certification</strong> to handle refrigerant. It does not expire</li>
    <li>A postsecondary HVAC programme or apprenticeship</li>
    <li>A state or local licence or registration where the jurisdiction requires one, such as TDLR registration in Texas</li>
    <li>A driving licence for service roles</li>
</ul>

<p><strong>Pay:</strong> BLS reports a May 2025 median of $61,010, with the top tenth above $95,210. Individual employers set their own rates.</p>

<p><strong>Note:</strong> licensing rules are set by each state and city, not by JobGader. Check the state board before applying.</p>
JOBHTML;
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Mechanical contractors, refrigeration firms, property managers and institutions across Canada recruit refrigeration and air conditioning mechanics under NOC 72402.</p>

<h3>What the work involves</h3>
<ul>
    <li>Installing, maintaining and repairing commercial and residential cooling, heat pump and refrigeration systems</li>
    <li>Reading blueprints and wiring diagrams</li>
    <li>Handling refrigerants under provincial environmental rules</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>A three- to five-year apprenticeship, or over five years of experience plus trade courses</li>
    <li>Trade certification, which is <strong>compulsory in Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan and Alberta</strong></li>
    <li>In Ontario, an Ozone Depletion Prevention card for refrigerant handling</li>
</ul>

<p><strong>Pay:</strong> Job Bank reports a national median of C$37.50 an hour for 2023-2024. Individual employers set their own rates.</p>

<p><strong>Note:</strong> certification and immigration rules are set by provincial regulators and IRCC, not by JobGader. Confirm them before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>HVAC technicians are in demand on both sides of the border, and the official data says so without needing a job board's help. In the United States, the Bureau of Labor Statistics projects employment to grow 11 per cent over the decade to 2035. In Canada, the trade is regulated, certification is compulsory in most of the large provinces, and Job Bank publishes wages by province. This page sets the two countries side by side using only those sources.</p>

<h2>How Much Demand Is There?</h2>

<h3>United States</h3>

<p>The BLS Occupational Outlook Handbook, in its <strong>2025 to 2035</strong> projections, puts heating, air conditioning and refrigeration mechanics and installers at:</p>

<ul>
    <li><strong>440,900 jobs</strong> in 2025</li>
    <li><strong>11 per cent growth</strong>, which BLS describes as much faster than average, adding about 48,200 jobs</li>
    <li><strong>About 40,600 openings a year</strong>, most of them created by workers retiring or leaving the trade</li>
</ul>

<p>Pages still quoting 8 per cent growth are using the previous 2024 to 2034 round.</p>

<h3>Canada</h3>

<p>Canada does not publish a single national growth percentage in the same way. The useful signals are that the trade is regulated, that Job Bank lists it as NOC 72402 with its own wage data, and that certification is <strong>compulsory in Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan and Alberta</strong>. A compulsory trade cannot be filled by unqualified labour, which is what keeps certified mechanics in demand.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/hvac-technician-jobs-usa-canada-rooftop.jpg"
         alt="Two HVAC technicians on rooftops, one checking refrigerant gauges in front of the New York skyline and one working on condenser pipework in front of a Canadian city and mountains"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What HVAC Technicians Earn in the USA</h2>

<p>BLS Occupational Employment and Wage Statistics, May 2025, for 409,670 employed mechanics and installers:</p>

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
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Bottom 10 per cent earned less than</td><td style="padding:10px;">$40,050</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">25th percentile</td><td style="padding:10px;">$48,360</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median</strong></td><td style="padding:10px;"><strong>$61,010</strong></td><td style="padding:10px;"><strong>$29.33</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mean</td><td style="padding:10px;">$64,780</td><td style="padding:10px;">$31.14</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">75th percentile</td><td style="padding:10px;">$77,060</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Top 10 per cent earned more than</td><td style="padding:10px;">$95,210</td><td style="padding:10px;">&mdash;</td></tr>
    </tbody>
</table>
</div>

<h3>Selected states and metro areas</h3>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Median annual wage, May 2025</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">District of Columbia</td><td style="padding:10px;">$84,390</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">San Jose metro area</td><td style="padding:10px;">$82,050</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">San Francisco metro area</td><td style="padding:10px;">$78,490</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New York metro area</td><td style="padding:10px;">$77,990</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alaska</td><td style="padding:10px;">$77,430</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Illinois</td><td style="padding:10px;">$77,410</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Massachusetts</td><td style="padding:10px;">$77,300</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Washington</td><td style="padding:10px;">$75,660</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New Jersey</td><td style="padding:10px;">$74,450</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New York State</td><td style="padding:10px;">$74,430</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">California</td><td style="padding:10px;">$72,560</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Hawaii</td><td style="padding:10px;">$65,450</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Texas</td><td style="padding:10px;">$57,760</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Florida</td><td style="padding:10px;">$56,670</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Jackson, Mississippi metro area</td><td style="padding:10px;">$52,060</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mississippi</td><td style="padding:10px;">$48,680</td></tr>
    </tbody>
</table>
</div>

<p>Metro figures that circulate online for New York and Jackson do not match the survey, which puts them at $77,990 and $52,060. Salary sites that blend job adverts with self-reported pay routinely drift from the official series, which is why this page uses only BLS.</p>

<h2>What HVAC Technicians Earn in Canada</h2>

<p>Job Bank reports hourly wages for heating, refrigeration and air conditioning mechanics (NOC 72402), based on 2023 to 2024 data and updated on 19 November 2025:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Province</th>
            <th style="padding:10px;text-align:left;">Low</th>
            <th style="padding:10px;text-align:left;">Median</th>
            <th style="padding:10px;text-align:left;">High</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">C$22.00</td><td style="padding:10px;"><strong>C$37.50</strong></td><td style="padding:10px;">C$56.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">C$25.00</td><td style="padding:10px;">C$40.00</td><td style="padding:10px;">C$54.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">C$24.23</td><td style="padding:10px;">C$40.00</td><td style="padding:10px;">C$62.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Saskatchewan</td><td style="padding:10px;">C$28.50</td><td style="padding:10px;">C$40.00</td><td style="padding:10px;">C$59.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">C$22.40</td><td style="padding:10px;">C$38.00</td><td style="padding:10px;">C$46.08</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">C$21.00</td><td style="padding:10px;">C$37.00</td><td style="padding:10px;">C$58.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Manitoba</td><td style="padding:10px;">C$18.00</td><td style="padding:10px;">C$36.07</td><td style="padding:10px;">C$45.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Newfoundland and Labrador</td><td style="padding:10px;">C$20.00</td><td style="padding:10px;">C$36.00</td><td style="padding:10px;">C$46.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Prince Edward Island</td><td style="padding:10px;">C$21.02</td><td style="padding:10px;">C$31.21</td><td style="padding:10px;">C$34.02</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nova Scotia</td><td style="padding:10px;">C$20.70</td><td style="padding:10px;">C$30.84</td><td style="padding:10px;">C$53.85</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New Brunswick</td><td style="padding:10px;">C$20.50</td><td style="padding:10px;">C$29.76</td><td style="padding:10px;">C$45.63</td></tr>
    </tbody>
</table>
</div>

<p>Job Bank publishes no figure for the three territories. At the national median, a 40-hour week over 52 weeks comes to about <strong>C$78,000</strong> before overtime. Alberta, British Columbia and Saskatchewan share the highest median, not Alberta alone.</p>

<h2>Which Country Pays More?</h2>

<p>On the medians, a Canadian mechanic earns about C$78,000 and an American technician $61,010. The currencies differ and so do taxes, health cover and living costs, so the honest comparison is not a conversion but a question: which country will let you work? In Canada the trade is certified and provincially regulated; in the United States the entry requirement is federal EPA certification plus whatever your state and city add.</p>

<h2>How to Qualify in the USA</h2>

<ol>
    <li><strong>Training.</strong> BLS describes the typical entry route as a postsecondary programme of six months to two years, or an apprenticeship lasting several years.</li>
    <li><strong>EPA Section 608 certification.</strong> Required to maintain, service, repair or dispose of equipment that could release refrigerant. There are four certifications: Type I for small appliances, Type II for high-pressure equipment, Type III for low-pressure equipment, and Universal for all three. Each exam pairs a core section with the type section, and the credential never expires.</li>
    <li><strong>The apprentice exception is narrow.</strong> An apprentice may work without certification only while closely and continually supervised by a certified technician, and only if registered with the Department of Labor's Office of Apprenticeship or a recognised state council &mdash; for no more than two years from first registration.</li>
    <li><strong>State and local licensing.</strong> It varies widely. California, Arizona and Florida license HVAC <em>contractors</em>, so an employed technician works under the contractor's licence. Texas goes further: anyone doing air conditioning and refrigeration work for a licensed contractor must hold a TDLR technician registration or certification. Some states leave licensing to cities and counties.</li>
    <li><strong>Optional credentials.</strong> NATE certification is voluntary but widely recognised by employers.</li>
</ol>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/hvac-technician-jobs-usa-canada-gauges.jpg"
         alt="An HVAC technician in a navy cap connecting refrigerant gauges to a condenser unit, with a passport, a visa document and a hard hat beside a US and Canada skyline"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Qualify in Canada</h2>

<ol>
    <li><strong>Apprenticeship.</strong> Job Bank lists a three- to five-year apprenticeship, or over five years of work experience combined with trade courses, as the route to certification.</li>
    <li><strong>Certificate of Qualification.</strong> Compulsory in Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan and Alberta; voluntary in the other provinces and the territories.</li>
    <li><strong>Red Seal.</strong> The interprovincial Red Seal endorsement lets a certified mechanic work across provinces. In Ontario the trade is Refrigeration and Air Conditioning Systems Mechanic, code <strong>313A</strong>, administered by Skilled Trades Ontario.</li>
    <li><strong>Refrigerant handling.</strong> EPA 608 is a US credential and does not apply in Canada. In Ontario, refrigerant handling needs an <strong>Ozone Depletion Prevention</strong> card regulated by the Ministry of the Environment, Conservation and Parks; other provinces set their own rules.</li>
</ol>

<h2>Applying from Abroad</h2>

<ul>
    <li><strong>United States.</strong> There is no HVAC-specific work visa. Permanent sponsorship for a skilled trade usually runs through an employer's labour certification and an EB-3 petition, which takes years. Be wary of anyone selling a quick US trade visa.</li>
    <li><strong>Canada.</strong> Foreign mechanics normally need their trade experience assessed by the province before they can sit the certification exam, and an employer offer or an immigration programme for the work permit. Check the provincial apprenticeship authority first.</li>
    <li><strong>Never pay for a job offer.</strong> Legitimate employers in either country do not charge for one.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much does an HVAC technician earn in the USA?</h3>
<p>BLS reports a May 2025 median of $61,010 a year, or $29.33 an hour. The bottom tenth earned under $40,050 and the top tenth over $95,210.</p>

<h3>How much does an HVAC technician earn in Canada?</h3>
<p>Job Bank reports a national median of C$37.50 an hour for NOC 72402, from C$22.00 at the low end to C$56.00 at the high end. Alberta, British Columbia and Saskatchewan have the highest median, C$40.00.</p>

<h3>Is HVAC technician demand growing?</h3>
<p>Yes. BLS projects 11 per cent growth from 2025 to 2035 in the United States, much faster than average, with about 40,600 openings a year.</p>

<h3>Do I need EPA 608 certification?</h3>
<p>In the United States, yes, to work on equipment that could release refrigerant. A registered apprentice may work under close supervision for up to two years. The certification never expires.</p>

<h3>Do I need EPA 608 in Canada?</h3>
<p>No. Canada uses provincial rules. In Ontario, refrigerant handling needs an Ozone Depletion Prevention card, alongside the trade certificate.</p>

<h3>In which provinces is HVAC certification compulsory?</h3>
<p>Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan and Alberta. It is voluntary in the other provinces and the territories.</p>

<h3>Which US states license HVAC technicians?</h3>
<p>It varies. Texas requires technicians working for a licensed contractor to hold a TDLR registration or certification. California, Arizona and Florida license contractors rather than employed technicians. Check your state board and city.</p>

<h3>How long does it take to become an HVAC technician?</h3>
<p>In the US, a postsecondary programme takes six months to two years and an apprenticeship several years. In Canada, the certification route is a three- to five-year apprenticeship.</p>

<h2>People Also Search For</h2>

<h3>HVAC technician salary USA</h3>
<p>$61,010 median in May 2025; top tenth above $95,210.</p>

<h3>HVAC technician salary Canada</h3>
<p>C$37.50 an hour national median on Job Bank.</p>

<h3>HVAC job outlook 2035</h3>
<p>11 per cent growth and about 40,600 openings a year.</p>

<h3>NOC 72402</h3>
<p>Heating, refrigeration and air conditioning mechanics in Canada.</p>

<h3>313A Ontario</h3>
<p>Refrigeration and Air Conditioning Systems Mechanic, a compulsory trade.</p>

<h3>EPA 608 apprentice exemption</h3>
<p>Registered apprentices only, under close supervision, for up to two years.</p>

<h3>Texas HVAC technician registration</h3>
<p>Required by TDLR for anyone working under a licensed contractor.</p>

<h3>Highest paying state for HVAC</h3>
<p>The District of Columbia at $84,390 among the areas shown here.</p>

<h2>More Job Guides</h2>

<p>The rest of the HVAC/R cluster and related trades:</p>

<ul>
    <li><a href="/blog/how-to-start-a-career-in-refrigeration-and-air-conditioning">How to Start a Career in Refrigeration and Air Conditioning</a> &mdash; training, EPA 608 and the industrial refrigeration route.</li>
    <li><a href="/blog/ac-technician-jobs-in-dubai-and-saudi-arabia">AC Technician Jobs in Dubai and Saudi Arabia</a> &mdash; the Gulf rules on recruitment costs, summer hours and the Saudi skills exam.</li>
    <li><a href="/blog/maintenance-technician-jobs-in-usa">Maintenance Technician Jobs in USA</a> &mdash; building maintenance work that often includes HVAC.</li>
    <li><a href="/blog/electrician-jobs-abroad-with-visa-sponsorship">Electrician Jobs Abroad with Visa Sponsorship</a> &mdash; Canadian trade certification for a neighbouring trade.</li>
    <li><a href="/blog/how-to-become-a-licensed-plumber-in-canada-or-australia">How to Become a Licensed Plumber in Canada or Australia</a> &mdash; the same provincial certification system.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; the wider US trades market and its visa routes.</li>
    <li><a href="/blog/car-mechanic-jobs-in-australia-uk-and-canada">Car Mechanic Jobs in Australia, UK and Canada</a> &mdash; another Red Seal trade, compared across three countries.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not career, legal or immigration advice. US pay data is from BLS OEWS May 2025 and projections from the BLS Occupational Outlook Handbook; Canadian wages are from Job Bank. Confirm licensing with your state board or provincial apprenticeship authority.</p>
HTML;
    }
}
