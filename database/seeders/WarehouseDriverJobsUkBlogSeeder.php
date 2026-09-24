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
 * "How to Get a Warehouse Driver Job in UK" — the route from a car licence to
 * an HGV licence, priced from ONS earnings rather than recruiter blogs, with
 * the Skilled Worker position stated plainly because it decides whether an
 * overseas reader should read on at all.
 *
 * Corrections to the draft (checked against gov.uk, the National Careers
 * Service, ONS ASHE 2025, HMRC manual EIM66110 and DfT statistics,
 * September 2026):
 *
 * 1. The draft's salary bands come from recruiters. ONS ASHE 2025 puts SOC
 *    8211 large goods vehicle drivers at a median of GBP 39,141 for all
 *    employees and GBP 39,905 full-time, with an hourly median of GBP 16.25.
 *
 * 2. The draft gives a tax-free night-out allowance of GBP 26.20. HMRC's
 *    approved figure is GBP 34.90 a night; the 75% sleeper-cab rate is
 *    GBP 26.18, and it needs an HMRC approval notice.
 *
 * 3. The draft implies HGV work is a route into the UK for overseas
 *    applicants. SOC 8211 is listed as ineligible for the Skilled Worker
 *    visa, so these jobs cannot be sponsored.
 *
 * 4. The draft calls the Driver CPC one qualification. Since 3 December 2024
 *    there are National and International Driver CPC routes, and a Return to
 *    Driving course exists for people whose CPC lapsed within two years.
 *
 * 5. The draft says the process is provisional licence then theory and
 *    practical tests. The official route is a D2 application with a D4
 *    medical, then four CPC modules, with the theory module booked as two
 *    separate parts.
 *
 * 6. The draft treats "no more than 6 points" as a rule. The only statutory
 *    thresholds are 12 points for disqualification and 6 points within two
 *    years of passing for new drivers; a 6-point cap is employer or insurer
 *    policy.
 *
 * 7. The draft names employers as "the largest single HGV employer" and so
 *    on. No employer publishes that claim, and the official demand measure is
 *    the DfT vacancy survey: 26% of HGV businesses had driver vacancies in
 *    quarter 4 of 2025.
 *
 * 8. The draft ignores Skills Bootcamps, the funded English route that pays
 *    for the provisional licence, medical and CPC tests.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class WarehouseDriverJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-warehouse-driver-jobs.html';

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
        $title = 'How to Get a Warehouse Driver Job in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Van-based warehouse driving needs only a car licence, while HGV work needs a D4 medical, a provisional lorry licence and Driver CPC. ONS puts lorry drivers at a 39,141 pound median, and funded Skills Bootcamps cover the tests.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-a-warehouse-driver-job-in-uk.jpg',
                'tags' => 'warehouse driver jobs uk, hgv driver jobs, category c licence, driver cpc, d4 medical, skills bootcamp hgv, lorry driver salary uk, class 1 driver jobs',
                'meta_title' => 'How to Get a Warehouse Driver Job in UK: Licence and Pay',
                'meta_description' => 'Warehouse driver jobs in the UK: which licence you need, the D4 medical and Driver CPC route, funded training and what ONS says lorry drivers earn.',
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
            ['name' => 'UK Warehouse, Distribution and Haulage Employers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'uk-warehouse-driver-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Warehouse Driver — Van, Category C and Category CE Roles with UK Distribution and Haulage Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, typically 38 to 52 hours a week including early starts, nights and weekends',
                'language' => 'English',
                // Bands vary by licence category, shift and employer, so only
                // official ONS figures appear in the guide.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Warehouse and distribution driving roles across the UK, from van work on a car licence to category C and CE lorry driving with Driver CPC.',
                'seo_keywords' => 'warehouse driver jobs uk, hgv driver jobs, class 2 driver jobs, class 1 driver jobs, driver cpc jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>UK warehouses, distribution centres and hauliers hire drivers for van, rigid and articulated work, often combining driving with loading, picking and stock duties on site.</p>

<h3>What the work involves</h3>
<p>Loading and securing goods, multi-drop or trunking routes, vehicle checks, tachograph and delivery paperwork, and working alongside warehouse teams between runs.</p>

<h3>Common requirements</h3>
<ul>
    <li>A full UK car licence for van and site work</li>
    <li>A category C or CE licence plus Driver CPC for lorry work</li>
    <li>A driver qualification card carried while driving professionally</li>
    <li>A clean-enough licence for the employer's insurer</li>
    <li>The right to work in the UK: these roles cannot be sponsored on a Skilled Worker visa</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, licence checks and training support are set by each employer, and licensing rules by DVLA and DVSA &mdash; not by JobGader. Check the current vacancy and gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Warehouse driver jobs in the UK split into two very different jobs: van and site driving, which needs only a full car licence, and lorry driving, which needs a category C or CE licence plus the Driver Certificate of Professional Competence.</strong> The route to the lorry licence starts with a <strong>D4 medical</strong> and a provisional entitlement, and ONS earnings data puts large goods vehicle drivers at a median of <strong>&pound;39,141</strong> a year.</p>

<p>This guide covers which licence each job needs, the official training route and what it costs, funded training, real pay data and where the demand is.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-warehouse-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128230; Browse Warehouse Driver Jobs in UK &rarr;
    </a>
</div>

<h2>Not Every Warehouse Driver Job Needs an HGV Licence</h2>

<ul>
    <li><strong>Van and site driving.</strong> Multi-drop delivery, warehouse-to-customer runs and internal site transport usually ask for a full UK car licence and nothing more.</li>
    <li><strong>Category C (informally "class 2").</strong> Vehicles over 3,500 kg with a trailer up to 750 kg &mdash; rigid lorries.</li>
    <li><strong>Category CE (informally "class 1").</strong> Category C vehicles with a trailer over 750 kg &mdash; articulated work.</li>
</ul>

<p>"Class 1" and "class 2" are industry shorthand: they appear in employers' adverts, not in DVLA's own categories, so search for both spellings when you look for work.</p>

<h2>The Official Route to an HGV Licence</h2>

<ol>
    <li><strong>Hold a full car licence</strong> and be over 18. The default age for category C and CE is 21, reduced to <strong>18</strong> if you hold the Driver CPC initial qualification.</li>
    <li><strong>Apply for provisional lorry entitlement</strong> with form <strong>D2</strong>, together with a <strong>D4 medical examination report</strong> completed by a doctor. There is <strong>no DVLA application fee</strong>, and the licence normally arrives within three weeks.</li>
    <li><strong>Pass the Driver CPC modules:</strong> the theory module &mdash; booked as two separate parts, multiple choice and hazard perception &mdash; then case studies, the off-road exercises, the on-road driving test, and the practical demonstration test.</li>
    <li><strong>Keep it current:</strong> 35 hours of periodic training every five years, or you cannot drive professionally.</li>
</ol>

<p>DVSA's published fees: theory multiple choice <strong>&pound;26</strong>, hazard perception <strong>&pound;11</strong>, case studies <strong>&pound;23</strong>, off-road exercises <strong>&pound;40</strong>, on-road driving <strong>&pound;115</strong> on a weekday (&pound;141 evenings, weekends and bank holidays) and the practical demonstration <strong>&pound;55</strong> (&pound;63 outside weekday hours). Training itself is charged separately by the school.</p>

<h2>Driver CPC: What Changed in 2024 and 2025</h2>

<ul>
    <li><strong>National and International routes.</strong> Driving in the UK and Europe requires 35 hours of <strong>International</strong> Driver CPC training. UK-only driving can be met with National courses, or a mix.</li>
    <li><strong>Return to Driving.</strong> If your Driver CPC expired <strong>less than two years ago</strong>, a seven-hour Return to Driving course puts you back on the road, with 28 further hours to complete within 12 months. It can be used once every five years.</li>
    <li><strong>Carry the card.</strong> The driver qualification card (DQC) must be with you: driving professionally without Driver CPC can bring a fine of up to <strong>&pound;1,000</strong>, and driving without the card a <strong>&pound;50</strong> fixed penalty.</li>
</ul>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-warehouse-driver-job-in-uk-loading-bay.jpg" alt="A warehouse driver in a high-visibility vest beside a delivery van at a UK distribution centre loading bay" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Van and site roles hire on a car licence; the lorry licence is the pay step up.</figcaption>
</figure>

<h2>Funded Training: Skills Bootcamps</h2>

<p>England runs government-funded <strong>Skills Bootcamps in HGV driving</strong>, free and lasting up to <strong>16 weeks</strong>. You must be <strong>19 or older</strong> with a full category B car licence. The funding covers getting the provisional licence <strong>including the medical</strong>, each of the Driver CPC tests, and one re-sit per test. Funding allocations run into 2026 to 2027.</p>

<p>Outside that, the National Careers Service route is an <strong>LGV Driver C and E Level 2 apprenticeship</strong>, about a year long, or an employer that sponsors your licence &mdash; which is what "warehouse now, class C within 6 to 12 months" adverts are offering. Get that promise in writing, with who pays if you leave.</p>

<h2>What Warehouse and Lorry Drivers Actually Earn</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Official source</th>
            <th style="padding:10px;text-align:left;">Figure</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>ONS ASHE 2025, large goods vehicle drivers</strong> (SOC 8211)</td><td style="padding:10px;">Median <strong>&pound;39,141</strong> a year, all employees; <strong>&pound;39,905</strong> full-time; <strong>&pound;16.25</strong> an hour</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">ONS ASHE 2025, delivery drivers and couriers (SOC 8214)</td><td style="padding:10px;">Median &pound;24,627 all employees; &pound;27,854 full-time</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">National Careers Service, HGV driver</td><td style="padding:10px;">&pound;27,000 starter to &pound;47,000 experienced; 38 to 52 hours a week</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">National Careers Service, delivery van driver</td><td style="padding:10px;">&pound;20,000 starter to &pound;27,000 experienced</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">National Living Wage, from April 2026</td><td style="padding:10px;">&pound;12.71 an hour for 21 and over</td></tr>
    </tbody>
</table>
</div>

<p>The gap between the two ONS rows is the honest reason to get the lorry licence: about <strong>&pound;14,500 a year</strong> between the median delivery driver and the median lorry driver.</p>

<h3>The night-out allowance most guides get wrong</h3>

<p>HMRC's approved overnight subsistence amount for lorry drivers is <strong>&pound;34.90 a night</strong>, not &pound;26.20. Where the driver sleeps in a <strong>sleeper cab</strong>, HMRC accepts <strong>75% of that figure &mdash; &pound;26.18</strong> &mdash; which is where the number in circulation comes from. Paying it tax-free needs an HMRC approval notice, so ask how your employer handles it.</p>

<h2>Where the Demand Actually Is</h2>

<p>The Department for Transport measures this directly. In its official statistics published on <strong>27 May 2026</strong>, <strong>26% of HGV businesses reported driver vacancies in quarter 4 of 2025</strong>, up from 24% a year earlier but far below the 43% peak of quarter 4 2021. Businesses gave the top reasons as better pay elsewhere (42%), drivers leaving the industry (38%) and retirements (33%), and 23% reported missed deliveries because no driver was available.</p>

<p>That is a real but cooling shortage: enough to keep hiring open, not enough to justify paying for training you have not checked.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-warehouse-driver-job-in-uk-hgv.jpg" alt="An HGV parked at a UK warehouse yard with pallets and a forklift in the background" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Around one in four HGV businesses reported driver vacancies at the end of 2025.</figcaption>
</figure>

<h2>Can Overseas Applicants Be Sponsored?</h2>

<p><strong>No.</strong> On the Home Office list of eligible occupations, <strong>SOC 8211, heavy and large goods vehicle drivers, is ineligible</strong> for the Skilled Worker visa, and so is SOC 8214 for delivery drivers and couriers. There is no going rate for these jobs because they cannot be sponsored at all, and they do not appear on the Immigration Salary List.</p>

<p>These roles are therefore for people who already have the right to work in the UK. If you are looking for routes that can be sponsored, our <a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> guide covers what the Skilled Worker rules now allow.</p>

<h2>Do Penalty Points Stop You Being Hired?</h2>

<p>Employers and their insurers often set a limit &mdash; six points is a common one &mdash; but that is company policy, not law. The statutory thresholds are <strong>12 or more points in three years</strong> for disqualification, and, for drivers within two years of passing their first test, <strong>6 or more points revokes the licence</strong>. Ask the employer what its insurer accepts rather than assuming.</p>

<h2>How to Get Hired</h2>

<ol>
    <li><strong>Start on the licence you already hold.</strong> Van and site driver roles hire on a car licence and get you inside a depot.</li>
    <li><strong>Book the D4 medical early</strong> &mdash; the provisional application needs it, and it is the step people leave too late.</li>
    <li><strong>Check Skills Bootcamps</strong> if you are in England and 19 or over before you pay a training school.</li>
    <li><strong>Get employer-funded training in writing</strong>, including the repayment terms if you leave.</li>
    <li><strong>Decide C or CE.</strong> C is quicker and cheaper; CE is where the money is.</li>
    <li><strong>Compare the whole package:</strong> shift premiums, overnight allowance, whether periodic CPC training is paid for, and how many hours are guaranteed.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Do I need an HGV licence for a warehouse driver job?</h3>
<p>Not always. Van, multi-drop and internal site roles usually need only a full UK car licence. Category C and CE work needs the lorry licence and Driver CPC.</p>

<h3>What is the difference between class 1 and class 2?</h3>
<p>Industry shorthand: class 2 is category C, a rigid lorry over 3,500 kg with a trailer up to 750 kg, and class 1 is category CE, which adds a trailer over 750 kg.</p>

<h3>How much do lorry drivers earn in the UK?</h3>
<p>ONS puts large goods vehicle drivers at a median of &pound;39,141 a year for all employees and &pound;39,905 for full-time, at &pound;16.25 an hour, in its 2025 survey.</p>

<h3>What is Driver CPC?</h3>
<p>The professional driving qualification taken alongside the licence: four modules to qualify, then 35 hours of periodic training every five years. Driving professionally without it can cost up to &pound;1,000.</p>

<h3>Is HGV training free in the UK?</h3>
<p>It can be. Skills Bootcamps in England are free for people 19 or over with a car licence, and cover the provisional licence, the medical and the CPC tests with one re-sit each.</p>

<h3>How much is the night-out allowance?</h3>
<p>HMRC's approved rate is &pound;34.90 a night, or 75% of it &mdash; &pound;26.18 &mdash; where the driver uses a sleeper cab, subject to an HMRC approval notice.</p>

<h3>Can I get a UK visa as a lorry driver?</h3>
<p>No. SOC 8211 is ineligible for the Skilled Worker visa, so HGV driving jobs cannot be sponsored.</p>

<h3>Are HGV drivers still in short supply?</h3>
<p>Partly. DfT found 26% of HGV businesses had driver vacancies in quarter 4 of 2025, up from 24% a year earlier but well below the 43% peak in 2021.</p>

<h2>People Also Search For</h2>

<h3>HGV driver salary UK</h3>
<p>&pound;39,141 median a year on ONS 2025 figures.</p>

<h3>D4 medical</h3>
<p>The doctor's report sent with form D2 to get provisional lorry entitlement.</p>

<h3>Driver CPC 35 hours</h3>
<p>Periodic training due every five years, National or International.</p>

<h3>Skills Bootcamp HGV</h3>
<p>Free English course up to 16 weeks covering licence, medical and CPC tests.</p>

<h3>Category C vs CE</h3>
<p>C is rigid with a small trailer; CE adds a trailer over 750 kg.</p>

<h3>Class 1 driver jobs</h3>
<p>Employer shorthand for category CE articulated work.</p>

<h3>HGV driver shortage 2026</h3>
<p>26% of HGV businesses reported vacancies in quarter 4 of 2025.</p>

<h3>Lorry driver visa sponsorship UK</h3>
<p>Not available: SOC 8211 is ineligible for the Skilled Worker route.</p>

<h2>More Job Guides</h2>

<p>Comparing UK and driving routes? These cover them:</p>

<ul>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; van and courier work, its pay and the gig platforms.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; the honest answer on sponsorship for warehouse work.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the Skilled Worker threshold and which jobs qualify.</li>
    <li><a href="/blog/how-to-get-a-transport-job-in-germany">How to Get a Transport Job in Germany</a> &mdash; the European route, where drivers can be sponsored.</li>
    <li><a href="/blog/how-to-become-a-long-haul-truck-driver-in-usa">How to Become a Long-Haul Truck Driver in USA</a> &mdash; the American licence route and its pay.</li>
    <li><a href="/blog/store-assistant-jobs-in-uk">Store Assistant Jobs in UK</a> &mdash; another entry-level route into UK retail and logistics.</li>
    <li><a href="/blog/how-to-get-a-delivery-job-in-australia">How to Get a Delivery Job in Australia</a> &mdash; award pay, the new gig minimum standards order, ABN and GST rules.</li>
    <li><a href="/blog/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk">How to Apply for Royal Mail Delivery Jobs in the UK</a> &mdash; the union pay scale, and why no delivery role can be sponsored.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using gov.uk licensing and Driver CPC guidance, DVSA fees, ONS ASHE 2025, HMRC manual EIM66110 and DfT road freight statistics. Rules, fees and pay change. Always check gov.uk and the current vacancy before applying.</p>
HTML;
    }
}
