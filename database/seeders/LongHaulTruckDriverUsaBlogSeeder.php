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
 * "How to Become a Long-Haul Truck Driver in USA" — the route from no licence
 * to a first over-the-road job: the CLP, ELDT, the skills test, choosing
 * training and what the first year is really like.
 *
 * The CDL Driver Jobs in USA guide owns pay tables, hours of service, the
 * shortage argument and owner-operator economics, and the visa sponsorship
 * guide owns EB-3 and H-2B, so this page stays on the training pathway and
 * links to both rather than repeating them.
 *
 * Corrections to the draft (checked against the BLS Occupational Outlook
 * Handbook, 49 CFR via eCFR, fmcsa.dot.gov and tsa.gov, September 2026):
 *
 * 1. The draft puts the top 10% at "$78,800+". The BLS says the highest 10%
 *    earned more than $79,380 and the lowest 10% less than $40,140 in
 *    May 2025.
 *
 * 2. The draft says "over 237,600 trucking-related jobs open annually". The
 *    BLS projects about 214,500 openings a year for heavy and tractor-trailer
 *    truck drivers, and the draft already quotes that number elsewhere.
 *
 * 3. The draft gives cents-per-mile rates of $0.45 to $0.75, annual mileage
 *    of 120,000 to 130,000 and entry-level pay of $50,000 to $55,000. No
 *    official source publishes any of them. The BLS confirms only that
 *    drivers "usually are paid by how many miles they have driven, plus
 *    bonuses".
 *
 * 4. The draft leaves out the CLP rule that decides the timeline: a commercial
 *    learner's permit must come first, and the holder may not take the skills
 *    test in the first 14 days.
 *
 * 5. The draft does not mention that ELDT has no minimum behind-the-wheel
 *    hours but requires an 80% theory score, which is why school lengths
 *    differ so much.
 *
 * 6. The draft omits two rules that now stop people driving: Clearinghouse-II
 *    state downgrades from 18 November 2024, and National Registry II
 *    electronic medical certification from 23 June 2025.
 *
 * 7. The draft does not mention that the Safe Driver Apprenticeship Pilot
 *    Program, the only federal route for 18 to 20-year-olds to drive
 *    interstate, concluded on 7 November 2025.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class LongHaulTruckDriverUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-otr-truck-driver-jobs.html';

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
        $title = 'How to Become a Long-Haul Truck Driver in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'You need a Class A CDL, ELDT from a registered provider and to be 21 for interstate work. The BLS median is $58,640, the route takes weeks not days, and the 14-day permit rule is why "CDL in 3 days" offers cannot be real.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-become-a-long-haul-truck-driver-in-usa.jpg',
                'tags' => 'long haul truck driver usa, how to become a truck driver, class a cdl, eldt training, commercial learner permit, otr driver pay, hazmat endorsement, fmcsa clearinghouse',
                'meta_title' => 'How to Become a Long-Haul Truck Driver in USA (2026)',
                'meta_description' => 'How to become a long-haul truck driver in the USA: the CLP, ELDT and skills test, training costs and contracts, BLS pay of $58,640 and the age rules.',
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
            ['name' => 'US Over-the-Road Trucking Carriers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'us-otr-trucking-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Long-Haul Truck Driver — Over-the-Road Class A CDL Roles with US Carriers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Federal hours-of-service limits: up to 11 driving hours in a 14-hour window, 60 hours in 7 days or 70 in 8',
                'language' => 'English',
                // Most carriers pay by the mile plus bonuses, so no annual
                // band is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Over-the-road Class A CDL driving jobs with US carriers. A commercial learner permit, registered ELDT training and a DOT medical certificate are required.',
                'seo_keywords' => 'long haul truck driver jobs, otr driver jobs, class a cdl jobs, cdl training jobs, truck driving jobs usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US carriers hire over-the-road Class A drivers to move freight between states, with drivers away from home for days or weeks at a time.</p>

<h3>What the work involves</h3>
<p>Driving a tractor-trailer on long routes, pre-trip and post-trip inspections, securing loads, logging hours electronically, and planning fuel, parking and rest stops within federal hours-of-service limits.</p>

<h3>Common requirements</h3>
<ul>
    <li>At least 21 years old for interstate driving</li>
    <li>A Class A commercial driver's license, and a commercial learner's permit before that</li>
    <li>Entry-Level Driver Training from a provider on the FMCSA Training Provider Registry</li>
    <li>A DOT medical examiner's certificate, renewed at least every two years</li>
    <li>A clean driving record and no prohibited status in the FMCSA Drug and Alcohol Clearinghouse</li>
    <li>Endorsements such as tank (N) or hazardous materials (H) for some freight</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, training contracts and hiring rules are set by each carrier and by federal and state law &mdash; not by JobGader. Read the carrier's current posting and training agreement before signing.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To become a long-haul truck driver in the USA you must be 21 to drive across state lines, hold a Class A commercial driver's license, and complete Entry-Level Driver Training with a provider on the federal Training Provider Registry before you can take the skills test.</strong> The Bureau of Labor Statistics (BLS) puts the median wage for heavy and tractor-trailer truck drivers at <strong>$58,640</strong> a year in May 2025, and says drivers are usually paid by the mile plus bonuses.</p>

<p>This guide walks the route from no licence to a first over-the-road job: the permit, the training, the tests, the medical, and what the first year actually looks like. Our <a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> guide covers pay, hours of service and owner-operator economics in detail.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-otr-truck-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128667; Browse Long-Haul Truck Driver Jobs &rarr;
    </a>
</div>

<h2>The Route From No Licence to Your First OTR Job</h2>

<ol>
    <li><strong>Check your driving record first.</strong> Carriers pull your motor vehicle record, and federal rules require CDL drivers to keep a clean record. Ordering your own state record before you spend money tells you what a recruiter will see.</li>
    <li><strong>Pass the knowledge tests and get a commercial learner's permit (CLP).</strong> Federal rule 49 CFR 383.25 makes the CLP "a precondition to the initial issuance of a CDL".</li>
    <li><strong>Wait out the 14 days.</strong> A CLP holder "is not eligible to take the CDL skills test in the first 14 days after initial issuance of the CLP".</li>
    <li><strong>Complete Entry-Level Driver Training (ELDT)</strong> with a provider listed on the <strong>Training Provider Registry</strong>. Since 7 February 2022 this is mandatory for a first Class A or Class B CDL, for upgrading Class B to Class A, and for a first S, P or H endorsement.</li>
    <li><strong>Pass the DOT physical</strong> with a medical examiner on the National Registry, and keep that certificate current.</li>
    <li><strong>Pass the three-part skills test:</strong> pre-trip vehicle inspection, basic vehicle control and safe on-road driving.</li>
    <li><strong>Register in the FMCSA Drug and Alcohol Clearinghouse</strong> and pass a pre-employment query and drug test.</li>
    <li><strong>Start with a carrier</strong>, usually with several weeks of paid finishing training alongside a trainer before you run solo.</li>
</ol>

<h2>Why "Get Your CDL in 3 Days" Cannot Be Real</h2>

<p>The federal rules make the timeline arithmetic simple. You cannot take the skills test until <strong>14 days</strong> after your permit is issued, and you cannot take it at all until a <strong>registered ELDT provider</strong> has reported your theory and behind-the-wheel training to the registry. Theory requires a score of at least <strong>80%</strong>.</p>

<p>There is no federal minimum number of behind-the-wheel hours &mdash; the rule says the instructor must cover every topic in the curriculum instead &mdash; which is why school lengths vary from about three weeks to two months. What cannot vary is the permit wait and the registry report, so any advert promising a licence in days is selling something that federal rules do not allow.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-become-a-long-haul-truck-driver-in-usa-highway.jpg" alt="A long-haul truck driver beside his tractor-trailer on an American interstate at sunrise" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Interstate driving is limited to drivers aged 21 and over.</figcaption>
</figure>

<h2>Choosing How You Train</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">What you pay</th>
            <th style="padding:10px;text-align:left;">What to check</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Private CDL school</strong></td><td style="padding:10px;">Tuition up front or financed</td><td style="padding:10px;">That the school appears on the Training Provider Registry, and its testing pass record</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Company-sponsored training</strong></td><td style="padding:10px;">Little or nothing up front, repaid through work</td><td style="padding:10px;">The contract length, the buyout figure if you leave early, and what you are paid during training</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Community college</strong></td><td style="padding:10px;">Tuition, sometimes covered by grants or veterans' benefits</td><td style="padding:10px;">Registry listing, course length and whether job placement is included</td></tr>
    </tbody>
</table>
</div>

<p>Company-sponsored training is a legitimate and common route, but it is a contract: read the commitment period and the buyout clause before you sign, because leaving early usually means repaying the training at full price.</p>

<h2>The Age Rule, and What Changed in 2025</h2>

<p>Federal rule <strong>49 CFR 391.11</strong> requires an interstate driver to be <strong>at least 21</strong>. Many states issue a CDL at 18 for driving within that state only, so an 18-year-old can start a career locally and move to long-haul at 21.</p>

<p>The one federal route that let 18 to 20-year-olds drive interstate, the <strong>Safe Driver Apprenticeship Pilot Program</strong>, closed to new applications on 31 August 2025 and <strong>concluded on 7 November 2025</strong>. Guides that still recommend it are out of date.</p>

<h2>What Long-Haul Life Actually Looks Like</h2>

<p>The BLS is blunt about it: "Working as a long-haul truck driver is a lifestyle choice because these drivers can be away from home for days or weeks at a time." Federal hours-of-service limits shape every day:</p>

<ul>
    <li>no more than <strong>14 hours</strong> on duty at a stretch, including up to <strong>11 hours</strong> driving;</li>
    <li>at least <strong>10 hours off duty</strong> before the next shift;</li>
    <li>a maximum of <strong>60 hours in 7 days or 70 in 8</strong>, then 34 hours off to reset.</li>
</ul>

<p>Sleeping in the truck, planning parking before you are out of hours, and eating well on the road are the parts of the job nobody trains you for.</p>

<h2>What You Will Actually Earn</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">BLS figure, May 2025</th>
            <th style="padding:10px;text-align:left;">Heavy and tractor-trailer drivers</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Median</td><td style="padding:10px;"><strong>$58,640</strong> a year ($28.19 an hour)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lowest 10%</td><td style="padding:10px;">less than $40,140</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Highest 10%</td><td style="padding:10px;">more than <strong>$79,380</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Truck transportation industry</td><td style="padding:10px;">$60,320</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Delivery drivers, for comparison</td><td style="padding:10px;">$43,950</td></tr>
    </tbody>
</table>
</div>

<p>The BLS confirms the pay structure &mdash; drivers "usually are paid by how many miles they have driven, plus bonuses", and some long-distance drivers, especially owner-operators, take a share of the load revenue &mdash; but <strong>no federal source publishes cents-per-mile rates or average annual mileage</strong>. Treat the CPM ranges and "$50,000 to $55,000 starting" figures in most guides as marketing, not data.</p>

<p>What to ask a recruiter instead: the carrier's <strong>published average weekly pay</strong> for drivers at your experience level, the <strong>average miles per week actually run</strong> by that fleet, whether detention and layover are paid, and how home time is scheduled. A high cents-per-mile rate on low miles pays less than a lower rate on steady miles.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-become-a-long-haul-truck-driver-in-usa-truck-stop.jpg" alt="Trucks parked at a US truck stop at dusk while a driver checks his trailer" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Miles per week, not cents per mile, decide what a long-haul job really pays.</figcaption>
</figure>

<h2>Endorsements That Raise What You Can Haul</h2>

<ul>
    <li><strong>T</strong> &mdash; double and triple trailers (knowledge test only);</li>
    <li><strong>N</strong> &mdash; tank vehicles (knowledge test only);</li>
    <li><strong>H</strong> &mdash; hazardous materials (knowledge test plus a TSA security threat assessment);</li>
    <li><strong>X</strong> &mdash; tank and hazmat combined;</li>
    <li><strong>P</strong> and <strong>S</strong> &mdash; passenger and school bus (knowledge and skills tests).</li>
</ul>

<p>The hazmat endorsement is the one with a background check: the TSA assessment costs <strong>$85.25</strong> for a new application or renewal, with a reduced rate of $41.00 for existing TWIC holders in some states, and lasts five years.</p>

<h2>Staying Qualified: Medical and Clearinghouse</h2>

<ul>
    <li><strong>DOT physical.</strong> Your examiner must be on the <strong>National Registry of Certified Medical Examiners</strong>, and you must be re-examined at least every <strong>24 months</strong>.</li>
    <li><strong>Electronic medical certification.</strong> Under National Registry II, states had to start receiving medical certificates electronically from FMCSA and posting them to your driver record by <strong>23 June 2025</strong>. Some states are still catching up, so keep your paper certificate until yours confirms it is on your record.</li>
    <li><strong>Clearinghouse downgrades.</strong> Since <strong>18 November 2024</strong>, a state must check the Drug and Alcohol Clearinghouse before issuing, renewing or upgrading a permit or licence, and must remove your commercial driving privilege if you are in prohibited status.</li>
    <li><strong>Employer queries.</strong> Carriers must query the Clearinghouse before letting you drive and once a year after that.</li>
</ul>

<h2>Long-Haul, Regional or Local?</h2>

<ul>
    <li><strong>Over-the-road:</strong> the most miles and usually the highest pay, with weeks away from home.</li>
    <li><strong>Regional:</strong> a defined multi-state area, usually home most weekends.</li>
    <li><strong>Local:</strong> home daily, more hand work and city traffic, often paid hourly rather than by the mile.</li>
</ul>

<p>Many drivers start over-the-road because carriers hire new CDL holders there, then move to regional or local work once they have a year of verifiable experience.</p>

<h2>Is the Job Outlook Strong?</h2>

<p>The BLS projects employment of heavy and tractor-trailer truck drivers to grow <strong>4% from 2025 to 2035</strong>, about as fast as average, from 2,221,200 jobs in 2025, with about <strong>214,500 openings a year</strong>. The BLS attributes most of those openings to replacing drivers who move to other work or retire, not to growth &mdash; and it does not use the word "shortage".</p>

<h2>Frequently Asked Questions</h2>

<h3>How long does it take to get a CDL?</h3>
<p>Usually several weeks. You must hold a commercial learner's permit for at least 14 days before the skills test, and complete registered ELDT training first. School lengths vary because there is no federal minimum for behind-the-wheel hours.</p>

<h3>Can I become a long-haul truck driver at 18?</h3>
<p>Not across state lines. Federal rules require interstate drivers to be 21. Most states issue a CDL at 18 for driving inside that state, and the federal apprenticeship pilot for 18 to 20-year-olds ended on 7 November 2025.</p>

<h3>Are truck drivers paid by salary or by the mile?</h3>
<p>The BLS says drivers of heavy trucks and tractor-trailers usually are paid by how many miles they have driven, plus bonuses. Local roles are more often hourly. No federal source publishes cents-per-mile rates.</p>

<h3>Is a "CDL in 3 days" program legitimate?</h3>
<p>No. The 14-day permit rule and mandatory ELDT from a registered provider make that timeline impossible under federal rules.</p>

<h3>How much do long-haul truck drivers earn?</h3>
<p>The BLS median was $58,640 in May 2025. The lowest 10% earned under $40,140 and the highest 10% over $79,380.</p>

<h3>What does the hazmat endorsement involve?</h3>
<p>A knowledge test plus a TSA security threat assessment costing $85.25, valid for five years.</p>

<h3>Do I need training if I already have a CDL?</h3>
<p>ELDT is not retroactive. It applies to a first Class A or Class B CDL, an upgrade from Class B to Class A, and a first school bus, passenger or hazmat endorsement.</p>

<h3>What can stop me driving after I qualify?</h3>
<p>A lapsed DOT medical certificate, or prohibited status in the FMCSA Drug and Alcohol Clearinghouse, which states must act on by removing your commercial driving privilege.</p>

<h2>People Also Search For</h2>

<h3>CDL training cost</h3>
<p>Varies by school; company-sponsored training trades tuition for a work commitment.</p>

<h3>ELDT requirements</h3>
<p>Theory at 80% plus behind-the-wheel training from a Training Provider Registry provider.</p>

<h3>Commercial learner's permit rules</h3>
<p>Required before a CDL, with no skills test in the first 14 days.</p>

<h3>OTR driver pay</h3>
<p>$58,640 median; carriers publish weekly averages rather than salaries.</p>

<h3>Truck driver age requirement</h3>
<p>21 for interstate driving; 18 intrastate in most states.</p>

<h3>Hazmat endorsement cost</h3>
<p>$85.25 for the TSA security threat assessment, valid five years.</p>

<h3>FMCSA Clearinghouse</h3>
<p>Employers query it before hiring and once a year; states act on prohibited status.</p>

<h3>Company sponsored CDL training</h3>
<p>Legitimate, but check the contract length and the buyout figure.</p>

<h2>More Job Guides</h2>

<p>Comparing driving routes? These cover them:</p>

<ul>
    <li><a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> &mdash; pay by state, hours of service, endorsements and owner-operator economics.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; the EB-3 and H-2B routes for drivers abroad.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; van and app work on an ordinary licence.</li>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a> &mdash; passenger driving and Job Bank pay.</li>
    <li><a href="/blog/heavy-truck-driver-jobs-in-saudi-arabia">Heavy Truck Driver Jobs in Saudi Arabia</a> &mdash; Gulf driving work and its licence rules.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the genuine entry-level visa routes.</li>
    <li><a href="/blog/how-to-get-a-transport-job-in-germany">How to Get a Transport Job in Germany</a> &mdash; the CE licence and code 95 rules, official pay and the driver visa route.</li>
    <li><a href="/blog/how-to-get-a-fleet-driver-job-in-canada">How to Get a Fleet Driver Job in Canada</a> &mdash; AZ and Class 1 licences, provincial training hours, air brakes and Job Bank wages.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the BLS Occupational Outlook Handbook, 49 CFR, FMCSA and TSA guidance. Rules, fees and pay change. Always check the current federal and state requirements and the carrier's own terms before applying.</p>
HTML;
    }
}
