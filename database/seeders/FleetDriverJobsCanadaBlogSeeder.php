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
 * "How to Get a Fleet Driver Job in Canada" — provincial licence classes,
 * mandatory entry-level training and what Job Bank says the work pays, for
 * drivers choosing between local and long-haul fleets.
 *
 * Corrections to the draft (checked against jobbank.gc.ca NOC 73300,
 * ontario.ca, alberta.ca, icbc.com, sgi.sk.ca, cbsa-asfc.gc.ca and
 * canada.ca, September 2026):
 *
 * 1. The draft says drivers need "a valid CVOR record". In Ontario law the
 *    Commercial Vehicle Operator's Registration belongs to the carrier or
 *    operator, not the employee. The driver-level record is the driver's
 *    record, and MTO also sells a CVOR driver abstract.
 *
 * 2. The draft prices the work from job boards: $21 to $23 an hour locally
 *    and $51,000 to $142,000 long-haul. Job Bank's national wages for
 *    NOC 73300 are $19.45 low, $26.42 median and $37.00 high, updated
 *    19 November 2025.
 *
 * 3. The draft says nothing about mandatory entry-level training, which now
 *    decides how long qualifying takes: Ontario requires 103.5 hours, British
 *    Columbia 140, Saskatchewan and Manitoba 121.5.
 *
 * 4. Alberta no longer runs MELT. Since 1 April 2025 the Class 1 Learning
 *    Pathway replaced it, and from 1 September 2026 its Core Learning stage
 *    runs 67 hours.
 *
 * 5. The draft treats the air brake endorsement as one national rule. It is
 *    the Z endorsement in Ontario, the Q endorsement in Alberta, which must
 *    be obtained before Class 1, and in British Columbia it is included in
 *    the Class 1 training.
 *
 * 6. The draft does not mention Ontario's manual transmission rule: since
 *    1 July 2022 a Class A road test in an automatic brings a restriction.
 *
 * 7. The draft calls the outlook a shortage without a source. Job Bank rates
 *    NOC 73300 as a moderate risk of labour shortage for 2024-2033.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class FleetDriverJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-az-driver-jobs.html';

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
        $title = 'How to Get a Fleet Driver Job in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Job Bank puts transport truck drivers at a $26.42 median hourly wage, with Alberta and BC highest. Licence classes, mandatory training hours by province, the air brake endorsement and why CVOR is not a driver record.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-a-fleet-driver-job-in-canada.jpg',
                'tags' => 'fleet driver jobs canada, az driver jobs, class 1 licence canada, melt training, air brake endorsement, cvor, truck driver wages canada, noc 73300',
                'meta_title' => 'How to Get a Fleet Driver Job in Canada: Licences and Pay',
                'meta_description' => 'Fleet driver jobs in Canada: AZ and Class 1 licences, mandatory entry-level training by province, air brake rules, CVOR explained and Job Bank wages.',
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
            ['name' => 'Canadian Trucking Fleets and Carriers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'ca-fleet-driver-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Fleet Driver — AZ, Class 1, DZ and Class 3 Roles with Canadian Carriers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, local shifts or multi-day long-haul runs under federal and provincial hours-of-service rules',
                'language' => 'English or French',
                // Wages differ by province, fleet and freight, so only Job
                // Bank figures appear in the guide.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Fleet driving roles with Canadian carriers, from local straight-truck work to long-haul tractor-trailer runs. Licence class and air brake endorsement required.',
                'seo_keywords' => 'fleet driver jobs canada, az driver jobs, class 1 driver jobs, dz driver jobs, truck driver jobs canada',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian carriers and private fleets hire drivers for local, regional and long-haul work, from straight trucks to tractor-trailers, across every province.</p>

<h3>What the work involves</h3>
<p>Pre-trip and post-trip inspections, loading and securing freight, driving to schedule within hours-of-service limits, electronic logging, and customer paperwork at each stop.</p>

<h3>Common requirements</h3>
<ul>
    <li>A Class 1 or AZ licence for tractor-trailers, or Class 3 or DZ for straight trucks</li>
    <li>An air brake endorsement: Z in Ontario, Q in Alberta, included in Class 1 training in British Columbia</li>
    <li>Mandatory entry-level training where the province requires it</li>
    <li>A clean driver's record, which employers request directly</li>
    <li>TDG certification for dangerous goods work</li>
    <li>Authorization to work in Canada</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> licensing rules are set by each province and pay by each carrier &mdash; not by JobGader. Check your province's licensing authority and the current posting before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To drive for a Canadian fleet you need the right provincial licence class &mdash; Class 1 or Ontario's AZ for tractor-trailers, Class 3 or DZ for straight trucks &mdash; an air brake endorsement, and, in most provinces, mandatory entry-level training before the road test.</strong> Job Bank puts transport truck drivers at a <strong>median of $26.42 an hour</strong>, with Alberta and British Columbia paying the most.</p>

<p>This guide covers the licence classes province by province, the training hours each one requires, what CVOR really is, and what the work pays on official figures.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-az-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128666; Browse Fleet Driver Jobs in Canada &rarr;
    </a>
</div>

<h2>Licence Classes: The Naming Changes at the Border of Every Province</h2>

<ul>
    <li><strong>Ontario</strong> uses letters. <strong>Class A</strong> covers a vehicle towing over 4,600 kg, so tractor-trailers; <strong>Class D</strong> covers a truck over 11,000 kg towing no more than 4,600 kg. The <strong>Z</strong> added to each &mdash; AZ, DZ &mdash; is the air brake endorsement.</li>
    <li><strong>Most other provinces</strong> use numbers. <strong>Class 1</strong> is the tractor-trailer equivalent of AZ; <strong>Class 3</strong> covers trucks with three or more axles, roughly Ontario's D.</li>
</ul>

<p>Job adverts print both ("Class 1/AZ") because fleets hire across provinces. If you are moving province, confirm how your licence transfers before you accept &mdash; Ontario, for example, requires its full training course from holders of a commercial licence issued in another country, and from holders of an out-of-province Class 1 held for less than 12 months.</p>

<h2>Mandatory Entry-Level Training, Province by Province</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Province</th>
            <th style="padding:10px;text-align:left;">Required training before the Class 1 or A road test</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Ontario</strong></td><td style="padding:10px;">At least <strong>103.5 hours</strong> of mandatory entry-level training, valid for life</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>British Columbia</strong></td><td style="padding:10px;"><strong>140 hours</strong>, including air brake training and six hours of flexible practical training</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Saskatchewan</strong></td><td style="padding:10px;"><strong>121.5 hours</strong>: 47 classroom or online, 17.5 in the yard, 57 behind the wheel</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Manitoba</strong></td><td style="padding:10px;"><strong>121.5 hours</strong>, mandatory since 2019</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Alberta</strong></td><td style="padding:10px;">MELT replaced on 1 April 2025 by the <strong>Class 1 Learning Pathway</strong>: a 40-hour entry program, then Core Learning of <strong>67 hours</strong> from 1 September 2026, plus 8 hours of air brake training if you have no Q endorsement</td></tr>
    </tbody>
</table>
</div>

<p>Alberta's pathway has a twist worth planning around: passing it gives a <strong>provincially restricted Class 1</strong> valid in Alberta only, and a further Competence Building Program removes the restriction for interprovincial work.</p>

<h2>Air Brakes: Three Different Mechanisms</h2>

<ul>
    <li><strong>Ontario:</strong> a separate <strong>Z endorsement</strong>, taken through an air brake course or the DriveTest assessments, needed for any vehicle with air or air-over-hydraulic brakes.</li>
    <li><strong>Alberta:</strong> the <strong>Q endorsement</strong>, and Alberta requires you to <strong>complete it before</strong> the Class 1 licence.</li>
    <li><strong>British Columbia:</strong> an air brake endorsement is required to drive an air-braked vehicle, but Class 1 applicants do not take a separate course because it is built into the training.</li>
</ul>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-fleet-driver-job-in-canada-fleet-yard.jpg" alt="A Canadian fleet driver standing in front of a row of trucks and vans in a yard with mountains behind" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Licence classes, training hours and air brake rules all change at provincial borders.</figcaption>
</figure>

<h2>CVOR Is Not Your Record &mdash; Here Is What Is</h2>

<p>This is the detail most guides get wrong. In Ontario, a <strong>Commercial Vehicle Operator's Registration certificate belongs to the operator</strong> &mdash; the carrier, or an owner-operator running as a business. Anyone operating a commercial vehicle over 4,500 kg registered weight in Ontario must hold one, and a copy travels in the vehicle. As an employee driver you do not "have a CVOR".</p>

<p>What employers actually pull about you:</p>

<ul>
    <li>your <strong>driver's record (abstract)</strong> &mdash; the three-year version is the one commonly used for employment, at <strong>$12</strong> uncertified or $18 certified;</li>
    <li>a <strong>CVOR driver abstract</strong> &mdash; a five-year record of collisions, safety-related convictions and inspections while you were driving commercially in Ontario, at <strong>$5</strong> uncertified or $10 certified.</li>
</ul>

<p>Order both before you apply. Knowing what a recruiter sees is worth more than hoping.</p>

<h2>Ontario's Manual Transmission Rule</h2>

<p>Since <strong>1 July 2022</strong>, an Ontario Class A road test must be taken in a vehicle with a <strong>manual transmission of at least eight forward gears with a high-low range</strong>. Pass in anything else and your licence carries a restriction barring you from manual Class A vehicles in Ontario. That is why adverts still ask for 13 or 18-speed experience even as fleets buy automated transmissions.</p>

<h2>What Fleet Drivers Actually Earn</h2>

<p>Job Bank's wages for <strong>NOC 73300, transport truck drivers</strong>, updated <strong>19 November 2025</strong>:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Where</th>
            <th style="padding:10px;text-align:left;">Low</th>
            <th style="padding:10px;text-align:left;">Median</th>
            <th style="padding:10px;text-align:left;">High</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">$19.45</td><td style="padding:10px;"><strong>$26.42</strong></td><td style="padding:10px;">$37.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">$21.00</td><td style="padding:10px;">$30.00</td><td style="padding:10px;">$42.31</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">$22.00</td><td style="padding:10px;">$30.77</td><td style="padding:10px;">$40.38</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Saskatchewan</td><td style="padding:10px;">$18.50</td><td style="padding:10px;">$27.00</td><td style="padding:10px;">$37.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">$19.23</td><td style="padding:10px;">$26.00</td><td style="padding:10px;">$35.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">$19.45</td><td style="padding:10px;">$25.20</td><td style="padding:10px;">$34.50</td></tr>
    </tbody>
</table>
</div>

<p>Two things follow. <strong>Alberta and British Columbia pay noticeably more than Ontario</strong>, and the "$21 to $23 an hour" starting figure quoted on job boards sits <strong>below</strong> Ontario's own median of $26.00. Within Ontario, Job Bank's regional medians run from $25.00 in Kingston-Pembroke to $28.00 in Kitchener-Waterloo-Barrie and Stratford-Bruce, with Toronto at $25.10.</p>

<p>No official Canadian source publishes pay premiums by trailer type, so treat "flatbed pays more" claims as recruiter talk until a specific posting proves it.</p>

<h2>Is There Really a Driver Shortage?</h2>

<p>Job Bank rates NOC 73300 as a <strong>moderate risk of labour shortage</strong> over 2024 to 2033, which is more measured than the language most adverts use. The structural reason is visible in the same data: <strong>319,400</strong> people were employed in 2023, <strong>48% of them aged 50 or over</strong>, with a median retirement age of 67.</p>

<p>Three-year prospects are rated Moderate in Ontario, Alberta and British Columbia, Good in Saskatchewan, and Limited in Quebec and Manitoba.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-fleet-driver-job-in-canada-highway.jpg" alt="A tractor-trailer on a Canadian highway with mountains in the distance" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Almost half of Canada's transport truck drivers are 50 or older.</figcaption>
</figure>

<h2>Local or Long-Haul?</h2>

<ul>
    <li><strong>Local fleet work:</strong> home daily, hourly pay, more loading and city driving. Job Bank's provincial medians above are the honest benchmark to compare an offer against.</li>
    <li><strong>Long-haul:</strong> multi-day runs, more miles, and cross-border work if the carrier runs into the United States.</li>
</ul>

<p>For cross-border work, the <strong>FAST</strong> programme run by CBSA and US Customs speeds up clearance for approved low-risk shipments and enrolled drivers. It is an expedited-processing programme, <strong>not a legal requirement</strong> to cross the border &mdash; check what your carrier's lanes actually need.</p>

<h2>What Job Bank Says You Need</h2>

<ul>
    <li>completion of <strong>secondary school</strong> is usually required;</li>
    <li>on-the-job training is provided;</li>
    <li>an accredited driver training course of up to three months may be required;</li>
    <li><strong>Class 1 or A</strong> for long combination vehicles, <strong>Class 3 or D</strong> for straight trucks;</li>
    <li>the <strong>air brake (Z) endorsement</strong> for air-braked vehicles;</li>
    <li><strong>TDG certification</strong> for dangerous goods.</li>
</ul>

<h2>Applying From Outside Canada</h2>

<p>Transport truck driver sits in <strong>TEER category 3</strong> of the national occupational classification. One correction worth knowing before you plan a move: the federal <strong>Express Entry transport category does not include truck drivers</strong> &mdash; its listed occupations are aircraft mechanics and inspectors, pilots and flight engineers, avionics technicians, and automotive and truck and bus mechanics. Check the current category-based selection page before acting on any advert that says otherwise.</p>

<h2>How to Get Hired</h2>

<ol>
    <li><strong>Confirm the class</strong> your target fleet needs, in your province's naming.</li>
    <li><strong>Budget the training hours</strong> your province requires, and the air brake step if it is separate.</li>
    <li><strong>Pull your driver's record and CVOR driver abstract</strong> before applying.</li>
    <li><strong>Take the road test in a manual</strong> in Ontario unless you are content with the restriction.</li>
    <li><strong>Compare the offer with Job Bank's provincial median</strong>, not with a job board's "starting rate".</li>
    <li><strong>Ask about the collective agreement</strong> if the posting mentions a probationary rate.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What is the difference between AZ and DZ?</h3>
<p>AZ is Ontario's Class A with the air brake endorsement, for tractor-trailers. DZ is Class D with air brakes, for straight trucks over 11,000 kg. Elsewhere they are Class 1 and Class 3.</p>

<h3>Do fleet drivers need a CVOR?</h3>
<p>No. The CVOR certificate belongs to the carrier or operator. Employers check your driver's record, and may order a CVOR driver abstract that shows your commercial driving history in Ontario.</p>

<h3>How much do truck drivers earn in Canada?</h3>
<p>Job Bank's national median is $26.42 an hour, with a low of $19.45 and a high of $37.00. Alberta ($30.00) and British Columbia ($30.77) have the highest provincial medians.</p>

<h3>How many training hours do I need?</h3>
<p>Ontario requires at least 103.5 hours, British Columbia 140, and Saskatchewan and Manitoba 121.5. Alberta replaced MELT with the Class 1 Learning Pathway in 2025.</p>

<h3>Do I need a manual transmission for the road test?</h3>
<p>In Ontario, yes, for an unrestricted Class A: the test vehicle must have a manual transmission with at least eight forward gears and a high-low range.</p>

<h3>Is there a truck driver shortage in Canada?</h3>
<p>Job Bank rates it a moderate risk of labour shortage to 2033. Almost half of drivers are 50 or older, so replacement demand is the main driver.</p>

<h3>Do I need a FAST card to cross into the United States?</h3>
<p>No. FAST is an expedited clearance programme for approved low-risk shipments and enrolled drivers, not a requirement to cross.</p>

<h3>Can truck drivers immigrate through Express Entry?</h3>
<p>Not through the transport category, which covers aviation and mechanic occupations. Check the current category-based selection rules before relying on any other claim.</p>

<h2>People Also Search For</h2>

<h3>AZ driver jobs Ontario</h3>
<p>Ontario's median for transport truck drivers is $26.00 an hour.</p>

<h3>Class 1 licence Alberta</h3>
<p>Now the Class 1 Learning Pathway, with a 40-hour entry program and 67 hours of core learning.</p>

<h3>MELT training hours</h3>
<p>103.5 in Ontario, 140 in BC, 121.5 in Saskatchewan and Manitoba.</p>

<h3>Air brake endorsement</h3>
<p>Z in Ontario, Q in Alberta before Class 1, built into BC's Class 1 course.</p>

<h3>CVOR abstract cost</h3>
<p>$5 uncertified, $10 certified; a driver's record is $12 or $18.</p>

<h3>Truck driver wages Alberta</h3>
<p>$30.00 an hour at the median, the highest of the big provinces.</p>

<h3>Long haul vs local driving</h3>
<p>Local is hourly and home daily; long-haul runs multi-day and sometimes cross-border.</p>

<h3>NOC 73300</h3>
<p>The classification covering transport truck drivers, TEER category 3.</p>

<h2>More Job Guides</h2>

<p>Comparing driving routes and Canadian work? These cover them:</p>

<ul>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a> &mdash; passenger driving, its licence classes and Job Bank pay.</li>
    <li><a href="/blog/how-to-become-a-long-haul-truck-driver-in-usa">How to Become a Long-Haul Truck Driver in USA</a> &mdash; the American route, its permit rules and pay.</li>
    <li><a href="/blog/how-to-get-a-transport-job-in-germany">How to Get a Transport Job in Germany</a> &mdash; the European route, where drivers can be sponsored.</li>
    <li><a href="/blog/how-to-get-a-warehouse-driver-job-in-uk">How to Get a Warehouse Driver Job in UK</a> &mdash; the British licence ladder and what it pays.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; which sectors hire and the routes in.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; the LMIA and the routes to permanent residence.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Job Bank data for NOC 73300 and official provincial licensing information from Ontario, Alberta, British Columbia and Saskatchewan, plus CBSA and canada.ca. Rules, fees and wages change. Always check your province's licensing authority and the current posting before applying.</p>
HTML;
    }
}
