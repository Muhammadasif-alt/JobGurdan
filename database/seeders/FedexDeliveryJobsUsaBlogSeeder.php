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
 * "How to Apply for FedEx Delivery Jobs in the USA" — an employer guide built on
 * FedEx's own careers site, FedEx's own contracting site for FedEx Ground
 * service providers, FedEx investor announcements and Bureau of Labor
 * Statistics wage data, rather than the aggregator listings the draft relied on.
 *
 * Corrections to the draft (checked against careers.fedex.com,
 * buildagroundbiz.com, newsroom.fedex.com, investors.fedex.com and bls.gov,
 * September 2026):
 *
 * 1. The draft says to apply "via FedEx Careers or Indeed". Every aggregator
 *    reference is removed. The larger problem is that most US "FedEx driver"
 *    adverts on job boards are placed by contracted independent businesses, so
 *    applying through them is not applying to FedEx at all.
 *
 * 2. The draft treats every FedEx driving job as a FedEx job. FedEx's own
 *    contracting site says those independent businesses are "responsible for
 *    hiring and training drivers and staff", and that Federal Express
 *    Corporation "contracts only with businesses that are established under
 *    state/provincial law as for-profit corporations". Their drivers are
 *    employees of the service provider, not of FedEx. The guide leads on this.
 *
 * 3. The draft misses the corporate structure entirely. FedEx Ground Package
 *    System merged into Federal Express Corporation on 1 June 2024, and FedEx
 *    Freight was spun off as an independent public company (NYSE: FDXF) on
 *    1 June 2026 with its own careers site. FedEx Freight driving jobs are no
 *    longer FedEx jobs.
 *
 * 4. The draft's lifting wording, "sometimes above 50 lbs with equipment or
 *    assistance", garbles it. FedEx's own courier posting reads "Ability to
 *    lift 50 lbs. Ability to maneuver packages of any weight above 50 lbs. with
 *    appropriate equipment and/or assistance." There is no stated upper weight.
 *
 * 5. The draft gives no minimum age. FedEx's own Courier/DOT postings state
 *    "Must be at least 21 years of age" and require a licence held for the past
 *    three years, because the role must meet section 391 of the Federal Motor
 *    Carrier Safety Regulations and pass a DOT medical exam.
 *
 * 6. The draft says package handlers must hold a driver's licence "even for
 *    in-warehouse Material Handler roles". FedEx's package handler page says
 *    only "Material Handlers must possess a valid driver's license." Package
 *    handler roles themselves carry no licence requirement.
 *
 * 7. The draft's "Non-Class CDL C license within 60 days of hire" is garbled.
 *    FedEx's own postings say the candidate "must obtain a Class C Non CDL
 *    within 60 days of employment", and only on particular courier postings.
 *
 * 8. The draft says paid time off "grows the longer you stay". FedEx's own
 *    wording caps it: "Generous paid time off program - work your way up to
 *    5 weeks of PTO a year."
 *
 * 9. The draft publishes no pay and implies none is available. FedEx publishes
 *    rates on its own postings: a part-time Package Handler posting in
 *    Henderson, Colorado showed "$20.20 / hr - $21.70 / hr to start" and a
 *    Courier/DOT posting in Phoenix, Arizona showed "$22.10". No aggregator
 *    figure is used anywhere in this guide.
 *
 * 10. The draft attributes "3 to 6 hours a day" to part-time employees
 *     generally. FedEx's wording is narrower and volume-driven: "Shift lengths
 *     vary based on package volume - generally part time employees work between
 *     3 and 6 hours a day." Casual package handlers work up to 24 hours a week.
 *
 * 11. The draft presents "2pm-8pm, Monday through Saturday with a rotating day
 *     off" as the part-time driver shift. That is one posting's schedule.
 *     FedEx's Courier/DOT postings say "Schedule is TBD by manager".
 *
 * 12. The draft's "over 1,000 open Driver roles globally" was 1,073 on the
 *     careers.fedex.com driver search in September 2026, across all countries
 *     rather than the US alone, and it moves daily. Contractor driver
 *     vacancies never appear in that count.
 *
 * 13. The draft omits the largest cash benefit. FedEx's own postings state
 *     "$5,250 tuition reimbursement every year with no lifetime cap",
 *     available from the first day of employment.
 *
 * 14. No job-ID URL is linked. Several FedEx posting IDs checked during
 *     research returned 404 within days, so only stable career-area pages are
 *     used. Tracking parameters and citation artifacts in the draft were
 *     stripped.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class FedexDeliveryJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.fedex.com/jobs';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on finding work, applying well and understanding what a job really pays.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Apply for FedEx Delivery Jobs in the USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Many US FedEx driver adverts are placed by independent contractors, not by FedEx. This guide separates the three employers behind the purple logo, uses only the hourly rates FedEx publishes itself, and gives the age 21 DOT rule the brief left out.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-fedex-delivery-jobs-in-the-usa.jpg',
                'tags' => 'fedex delivery jobs usa, fedex package handler jobs, fedex courier jobs, fedex careers apply, fedex driver pay, fedex ground service provider, delivery driver jobs usa, warehouse jobs usa',
                'meta_title' => 'FedEx Delivery Jobs USA: Pay, Age Rules and How to Apply',
                'meta_description' => 'FedEx delivery jobs in the USA: the hourly rates FedEx publishes itself, the age 21 DOT rule, and why many FedEx driver ads are contractor jobs.',
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
            ['name' => 'FedEx, US Stations and Sort Facilities'],
            ['type' => 'Company', 'display_reference' => 'fedex-us-stations']
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
                'position' => 'Delivery Driver and Package Handler, FedEx US',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Part-time shifts generally 3 to 6 hours a day, varying with package volume; casual handlers up to 24 hours a week',
                'language' => 'English',
                // Both figures are published by FedEx on its own careers
                // postings: a part-time Package Handler posting in Henderson,
                // Colorado at $20.20 to $21.70 an hour to start, and a
                // Courier/DOT posting in Phoenix, Arizona at $22.10 an hour.
                // Rates are location-specific and no aggregator estimate is
                // used here.
                'salary_currency' => 'USD',
                'salary_period' => 'Hourly',
                'salary_minimum' => 20.20,
                'salary_maximum' => 22.10,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Courier, swing driver and package handler roles at FedEx stations and sort facilities across the United States, with FedEx published hourly rates.',
                'seo_keywords' => 'fedex delivery jobs usa, fedex courier jobs, fedex package handler jobs, delivery driver jobs usa, warehouse jobs usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>FedEx hires couriers, swing drivers, shuttle drivers and package handlers directly at its own US stations and sort facilities. These are Federal Express Corporation roles, which is not the same thing as driving a FedEx-branded van for a contracted service provider.</p>

<h3>What the work involves</h3>
<p>Driving a company vehicle on an assigned route, picking up and delivering packages, scanning shipments and obtaining signatures, and in the sort facilities loading, unloading and sorting packages of every size in a fast-paced warehouse environment.</p>

<h3>Common requirements</h3>
<ul>
    <li>At least 21 years of age for Courier/DOT roles, with a driver's licence held for the past three years</li>
    <li>A driving record that meets section 391 of the Federal Motor Carrier Safety Regulations, plus a DOT medical exam</li>
    <li>High school diploma or GED for most courier roles</li>
    <li>Ability to lift 50 lbs, and to manoeuvre packages of any weight above that with appropriate equipment or assistance</li>
    <li>The right to work in the United States; these roles are not visa sponsored</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay rates, shift lengths and hiring standards are set by FedEx and by each independent service provider &mdash; not by JobGader. Apply directly on FedEx Careers, check whether the advert comes from FedEx or from a contractor, and never pay anyone for a FedEx job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on FedEx Careers, pick the Drivers or Package Handlers career area, filter by your city and submit online.</strong> FedEx publishes an hourly rate on most of its own postings, so you can see the pay before you apply.</p>

<p>One warning first, because it decides everything else. <strong>A large share of the "FedEx delivery driver" adverts you will find are not FedEx jobs.</strong> They are placed by independent businesses that contract with FedEx to run pickup and delivery routes. Their drivers wear the uniform and drive FedEx-liveried vans, but the employer, the pay, the benefits and the holiday are all the contractor's, not FedEx's. Working out which is which is the single most useful thing this guide can do for you.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.fedex.com/jobs" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#4d148c;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128666; Search FedEx Jobs &rarr;
    </a>
</div>

<h2>The Three Different Employers Behind the Purple Logo</h2>

<p>The corporate picture has changed twice since 2024, and most guides have not caught up. Here is who actually employs you:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#4d148c;color:#fff;">
            <th style="padding:10px;text-align:left;">Who hires you</th>
            <th style="padding:10px;text-align:left;">What that means</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Federal Express Corporation</strong></td><td style="padding:10px;">The real FedEx job. Couriers, swing drivers, shuttle drivers and package handlers, advertised on FedEx Careers with FedEx pay and FedEx benefits.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>A contracted service provider</strong></td><td style="padding:10px;">An independent company running FedEx Ground routes. It buys or leases the vans, hires the drivers and sets the pay. You are its employee, not FedEx's.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>FedEx Freight</strong></td><td style="padding:10px;">A different company since <strong>1 June 2026</strong>, trading as FDXF on the New York Stock Exchange, with its own separate careers site for its freight drivers.</td></tr>
    </tbody>
</table>
</div>

<p>On the corporate side, <strong>FedEx Ground Package System merged into Federal Express Corporation on 1 June 2024</strong>, which is why package handler postings now describe you as a "Federal Express Corporation (FEC)" employee rather than a FedEx Ground one. Then on <strong>1 June 2026 FedEx completed the spin-off of FedEx Freight</strong> as an independent, publicly traded company, retaining only a 19.9% stake. If you want heavy freight driving work, that is now a different employer and a different application.</p>

<h3>How to tell a FedEx job from a contractor job</h3>

<ul>
    <li><strong>Where it is advertised.</strong> Genuine FedEx roles appear on FedEx's own careers site. Contractor routes are mostly advertised on job boards and local classifieds.</li>
    <li><strong>The job title.</strong> FedEx's own driving titles read Courier, Courier/DOT, Courier/Swing Drvr/DOT, Shuttle Driver/DOT and Courier/Handler. A plain "FedEx Ground delivery driver" advert is usually a contractor.</li>
    <li><strong>The benefits on offer.</strong> Flat daily rates and small supplemental insurance plans are contractor hallmarks. FedEx's own postings quote an hourly rate and the company benefits package.</li>
    <li><strong>Who signs the offer.</strong> Ask for the legal name of the employer before you accept. FedEx's contracting rules say it contracts only with for-profit corporations, so the name on your paperwork will be a company you have never heard of.</li>
</ul>

<p>None of this makes contractor work bad work. It means you should know what you are signing. FedEx's own contracting site is explicit that these independent businesses are "responsible for hiring and training drivers and staff, and planning and executing day-to-day operations", and that they carry the wages, employment taxes, workers' compensation cover and Fair Labor Standards Act obligations themselves.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-fedex-delivery-jobs-in-the-usa-van.jpg" alt="Delivery van being loaded with parcels at a depot" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What FedEx Actually Pays, in Its Own Words</h2>

<p>This is the rare employer guide where you do not need an estimate, because <strong>FedEx puts the rate on the advert</strong>. Two examples checked in September 2026:</p>

<ul>
    <li><strong>Package Handler, part time, Henderson, Colorado:</strong> <strong>$20.20 to $21.70 an hour to start</strong>.</li>
    <li><strong>Courier/DOT, Phoenix, Arizona:</strong> <strong>$22.10 an hour</strong>.</li>
</ul>

<p>FedEx attaches a pay transparency note to these: "The salary information represents the job level minimum and the job level maximum. Actual starting pay would be determined by experience relative to the job, market level, pay at the location for this job and other job-related factors permitted by law." In plain terms, the rate is a local one. A courier rate in Phoenix tells you very little about a courier rate in rural Mississippi.</p>

<p>For a national reference point that is not an employer's own advert, the Bureau of Labor Statistics puts the 2025 median for <strong>delivery truck drivers and driver/sales workers at $21.13 an hour, or $43,950 a year</strong>, across 1,511,400 jobs, with 7% growth projected to 2035. For <strong>hand laborers and material movers</strong>, the warehouse comparison, the median is <strong>$18.38 an hour, or $38,220 a year</strong>. FedEx's published rates sit at or above both.</p>

<p>What you will not find here is a salary scraped from a job board. If a number is not published by FedEx itself or by a government statistical agency, we do not print it.</p>

<h2>Delivery Driver and Courier Requirements</h2>

<p>FedEx's driver careers page states the baseline plainly: <strong>"A standard driver's license and good driving record in accordance with Federal Motor Carrier safety regulations are required."</strong> It adds that <strong>"Swing, part-time, and casual driver positions are also available"</strong> alongside full-time work.</p>

<p>The individual postings go further, and this is where the real gates are:</p>

<ul>
    <li><strong>Age 21 minimum</strong> on Courier/DOT roles, with a driver's licence held for the past three years. This is not a FedEx preference; DOT-regulated driving carries a statutory age floor.</li>
    <li><strong>Section 391 compliance and a DOT medical exam.</strong> FedEx's wording: "Must meet qualifications as outlined in section 391 of the Federal Motor Carrier safety regulations. Requires medical exam in accordance with FMCSA or FAA regulations."</li>
    <li><strong>High school diploma or GED</strong>, confirmed on courier postings.</li>
    <li><strong>Enough English to read traffic signs</strong> and communicate with traffic safety officials, which is a federal requirement rather than a company one.</li>
    <li><strong>A Class C non-CDL licence within 60 days</strong> on some postings. FedEx's exact phrasing is "must obtain a Class C Non CDL within 60 days of employment". It appears on specific courier roles, not across the board, so read the advert.</li>
    <li><strong>Lifting.</strong> "Ability to lift 50 lbs. Ability to maneuver packages of any weight above 50 lbs. with appropriate equipment and/or assistance." Note the wording: 50 lbs is the floor you lift unaided, and above it there is no stated ceiling.</li>
</ul>

<p>Roles titled Courier (Non-DOT), often marked rural or residential, use lighter vehicles and sit outside the DOT rules. If age 21 or a medical certificate is an obstacle for you, those are the postings worth filtering for.</p>

<h3>No CDL is needed for most of this</h3>

<p>Ordinary FedEx delivery work does not require a commercial driver's licence. Some larger-vehicle courier titles are marked Courier/Dot/CDL, and the freight side, now a separate company, is where CDL work really lives. If you want that route, our <a href="/blog/cdl-driver-jobs-in-usa">CDL driver jobs in USA guide</a> covers the licence classes and endorsements.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-fedex-delivery-jobs-in-the-usa-sort.jpg" alt="Parcels moving along a sorting belt inside a warehouse" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Package Handler Requirements</h2>

<p>FedEx describes the job as working "in a fast-paced warehouse-like environment taking responsibility for tracking shipments and working safely and efficiently while sorting, processing, loading, and unloading packages", and notes that "You may be called upon to use equipment such as hydraulic conveyor belts in your work."</p>

<p>Two details are routinely reported wrong elsewhere:</p>

<ul>
    <li><strong>You do not need a driver's licence to be a package handler.</strong> FedEx's page says only that "Material Handlers must possess a valid driver's license." Material Handler is a separate title that involves moving vehicles and powered equipment. Standard package handler roles carry no licence requirement at all.</li>
    <li><strong>The hours are short by design.</strong> FedEx's own wording: "Part time Federal Express Corporation (FEC) employees work one shift a day; full time Federal Express Corporation (FEC) employees work two shifts. Shift lengths vary based on package volume &mdash; generally part time employees work between 3 and 6 hours a day." Casual handlers work up to 24 hours a week.</li>
</ul>

<p>That volume clause matters more than the headline rate. A three-hour shift at $20.20 is about $60 before tax. Treat part-time handler work as a second income or a foot in the door, not as a full-time wage.</p>

<h2>The Benefits FedEx Publishes</h2>

<p>These come from FedEx's own postings, not from a benefits summary site:</p>

<ul>
    <li><strong>$5,250 tuition reimbursement every year with no lifetime cap</strong>, and FedEx's postings say employees are eligible from their first day.</li>
    <li><strong>Paid time off, working up to 5 weeks a year.</strong> The often-repeated claim that it simply "grows the longer you stay" leaves out the ceiling.</li>
    <li><strong>Medical, dental and vision</strong> after a waiting period.</li>
    <li><strong>Paid parental leave</strong>, and employee discounts on phone plans, electronics, cars and restaurants.</li>
</ul>

<p>Every one of these attaches to the FedEx job. A contracted service provider sets its own benefits, and they are usually thinner. That is the practical cost of taking the contractor route without realising it.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Start on FedEx Careers</strong> and choose the Drivers or Package Handlers career area rather than a general search, so you only see FedEx's own roles.</li>
    <li><strong>Filter by your city first.</strong> Rates and shift patterns are set locally, so a posting 200 miles away tells you nothing useful.</li>
    <li><strong>Read the age and licence lines before anything else.</strong> If a posting says 21 and DOT and you are 19, move to the Non-DOT rural and residential courier titles instead.</li>
    <li><strong>Apply through FedEx's own system.</strong> Postings expire quickly, so bookmark the career area page rather than an individual advert.</li>
    <li><strong>Check the employer name on any offer</strong> that reaches you through a job board. If it is not Federal Express Corporation, you are joining a contractor, and you should ask about pay, overtime and holiday in writing.</li>
    <li><strong>Never pay anyone for a FedEx job.</strong> FedEx does not charge candidates at any stage, including training, visa processing or background checks. Anyone asking for money is running a recruitment scam.</li>
</ol>

<p>If you want a side-by-side comparison of the two biggest parcel employers before you commit, our <a href="/blog/how-to-apply-for-ups-package-handler-jobs-in-the-usa">UPS package handler jobs guide</a> covers the union contract that governs pay there.</p>

<h2>Frequently Asked Questions</h2>

<h3>Are all FedEx delivery drivers employed by FedEx?</h3>
<p>No. FedEx directly employs couriers and swing drivers, but pickup and delivery on the Ground network is largely run by independent businesses that hire and pay their own drivers. FedEx's own contracting site says those service providers are responsible for hiring, training and every employer obligation.</p>

<h3>How old do you have to be to be a FedEx courier?</h3>
<p>FedEx's Courier/DOT postings state you must be at least 21 years of age and have held a driver's licence for the past three years, because the role must meet section 391 of the Federal Motor Carrier Safety Regulations. Non-DOT courier roles sit outside that rule.</p>

<h3>What does FedEx pay package handlers?</h3>
<p>FedEx publishes the rate on its own postings. A part-time Package Handler role in Henderson, Colorado showed $20.20 to $21.70 an hour to start in September 2026. Rates are set locally, so check the posting for your own city.</p>

<h3>Do I need a CDL to drive for FedEx?</h3>
<p>Not for ordinary delivery work. Some larger-vehicle courier titles are marked CDL, and certain postings ask you to obtain a Class C non-CDL licence within 60 days of employment. Heavy freight driving now belongs to FedEx Freight, a separate company.</p>

<h3>How much do you have to lift at FedEx?</h3>
<p>FedEx's wording is "Ability to lift 50 lbs. Ability to maneuver packages of any weight above 50 lbs. with appropriate equipment and/or assistance." Fifty pounds is what you lift unaided; above that there is no stated upper limit.</p>

<h3>How many hours is a FedEx part-time shift?</h3>
<p>FedEx says shift lengths vary with package volume, and part-time employees generally work between three and six hours a day. Part-time employees work one shift, full-time employees work two. Casual handlers work up to 24 hours a week.</p>

<h3>Does a package handler need a driver's licence?</h3>
<p>No. FedEx's package handler page states only that Material Handlers must possess a valid driver's licence. That is a separate job title involving vehicle and powered-equipment movement.</p>

<h3>Is FedEx Freight still part of FedEx?</h3>
<p>No. FedEx completed the spin-off on 1 June 2026, establishing FedEx Freight as an independent, publicly traded company on the New York Stock Exchange under the ticker FDXF, with FedEx retaining 19.9% of the shares. It recruits through its own careers site.</p>

<h2>People Also Search For</h2>

<h3>FedEx courier pay per hour</h3>
<p>A Courier/DOT posting in Phoenix, Arizona showed $22.10 an hour in September 2026. FedEx publishes the rate on the advert itself.</p>

<h3>FedEx package handler hours</h3>
<p>Generally three to six hours a day for part-time staff, varying with package volume; casual handlers work up to 24 hours a week.</p>

<h3>FedEx Ground contractor requirements</h3>
<p>FedEx contracts only with businesses established under state law as for-profit corporations, not with LLCs, sole proprietorships or partnerships.</p>

<h3>FedEx tuition reimbursement amount</h3>
<p>$5,250 a year with no lifetime cap, available from the first day of employment according to FedEx's own postings.</p>

<h3>FedEx driver age requirement 21</h3>
<p>Courier/DOT roles require you to be 21 with three years of licensed driving, under section 391 of the Federal Motor Carrier Safety Regulations.</p>

<h3>FedEx open driver jobs count</h3>
<p>The driver search on FedEx Careers showed 1,073 results across all countries in September 2026. The figure moves daily and excludes contractor vacancies entirely.</p>

<h3>Delivery truck driver median pay USA</h3>
<p>$21.13 an hour, or $43,950 a year, per Bureau of Labor Statistics 2025 data across 1,511,400 jobs.</p>

<h3>FedEx job scam warning</h3>
<p>FedEx never charges candidates at any stage, including training, visa processing or background checks. Any request for payment is fraud.</p>

<h2>More Job Guides</h2>

<p>Comparing US delivery and warehouse employers? These cover the rest of the market:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-ups-package-handler-jobs-in-the-usa">How to Apply for UPS Package Handler Jobs in the USA</a> &mdash; the union contract that sets the pay scale, shift by shift.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; the wider market beyond the two big carriers.</li>
    <li><a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> &mdash; the licence classes and endorsements, and what each one unlocks.</li>
    <li><a href="/blog/how-to-become-a-long-haul-truck-driver-in-usa">How to Become a Long Haul Truck Driver in USA</a> &mdash; the step up from local delivery routes.</li>
    <li><a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">How to Apply for Amazon Fulfillment Center Jobs in USA</a> &mdash; the closest warehouse comparison on pay and shift length.</li>
    <li><a href="/blog/forklift-operator-jobs-in-usa">Forklift Operator Jobs in USA</a> &mdash; the certification that lifts you out of hand sorting.</li>
    <li><a href="/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa">How to Apply for Walmart Store Associate Jobs in the USA</a> &mdash; another employer that publishes its own starting rates.</li>
    <li><a href="/blog/how-to-apply-for-tesla-production-jobs-in-the-usa">How to Apply for Tesla Production Jobs in the USA</a> &mdash; factory work with a published hourly range and stock awards.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; the honest answer on sponsorship for driving work.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; which entry-level routes are genuinely open from abroad.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using FedEx Careers career-area pages and FedEx's own job postings, FedEx's published contracting standards for FedEx Ground service providers, FedEx investor and newsroom announcements on the 2024 operating company consolidation and the 2026 FedEx Freight spin-off, and Bureau of Labor Statistics occupational wage data. FedEx pay rates are set locally and postings expire quickly, and service provider terms are set by each independent business. Always check the live posting, and confirm who your employer would be, before you accept an offer.</p>
HTML;
    }
}
