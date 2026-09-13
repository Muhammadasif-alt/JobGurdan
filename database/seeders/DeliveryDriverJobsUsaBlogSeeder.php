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
 * "Delivery Driver Jobs in USA" — the American companion to the UK delivery
 * driver guide. Same work, a different legal frame, and the same central
 * problem: the hourly figures being compared are not measuring the same hour.
 *
 * Corrections to the draft:
 *
 * 1. It quotes app-based drivers at "$15 to $25+ per hour, including tips"
 *    without saying which hours. California's Proposition 22, upheld by the
 *    state Supreme Court on 25 July 2024, guarantees 120 per cent of the
 *    minimum wage for engaged time only, and time spent waiting between
 *    orders is not engaged time.
 *
 * 2. It never prices the car, which is the largest cost a gig driver carries.
 *    The IRS standard business mileage rate is 72.5 cents a mile for the first
 *    half of 2026 and 76 cents from 1 July 2026, a rare mid-year increase.
 *
 * 3. It groups "Amazon, FedEx, UPS" as company drivers on one pay band. Those
 *    are three different employment structures: Amazon's drivers mostly work
 *    for independent Delivery Service Partners that set their own pay and
 *    benefits, FedEx Ground contracts routes to businesses that employ the
 *    drivers, and UPS drivers are UPS employees under a Teamsters contract.
 *
 * 4. It gives national ranges when the floor depends on where you drive. New
 *    York City sets $22.13 an hour before tips for app-based delivery from
 *    1 April 2026; the federal minimum for employees is $7.25; and there is no
 *    federal minimum at all for an independent contractor.
 *
 * 5. It says nothing about self-employment tax or insurance, both of which
 *    come out of the independent contractor figures it quotes.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DeliveryDriverJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-delivery-driver-jobs.html';

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
        $title = 'Delivery Driver Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'App pay guarantees count only the minutes you are on an order, your car costs 76 cents a mile at the IRS rate, and Amazon, FedEx and UPS drivers mostly do not share an employer. What the hourly figures leave out.',
                'content' => $content,
                'featured_image' => 'blogs/delivery-driver-jobs-in-usa.jpg',
                'tags' => 'delivery driver jobs usa, amazon dsp driver jobs, doordash driver pay, fedex ground driver jobs, ups driver jobs, courier jobs, gig delivery jobs, delivery driver salary usa',
                'meta_title' => 'Delivery Driver Jobs in USA',
                'meta_description' => 'Delivery driver jobs in the USA: why gig pay counts only engaged time, what your car costs per mile, and who actually employs Amazon and FedEx drivers.',
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
            ['name' => 'US Package, Courier & Delivery Operators (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-delivery-driver-aggregated']
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
                'position' => 'Package and Food Delivery Driver — Route, Courier and App-Based Work, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Route shifts of eight to ten hours for employed drivers; self-scheduled blocks for app-based work',
                'language' => 'English',
                // Employed hourly pay and app-based engaged-time pay are not the
                // same quantity, and the legal floor depends on the city and
                // on whether the driver is an employee at all.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Route, courier and app-based delivery roles across the US. Check who the employer is and what your vehicle costs before comparing hourly rates.',
                'seo_keywords' => 'delivery driver jobs usa, amazon dsp driver jobs, fedex ground driver jobs, ups driver jobs, gig delivery jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Package carriers, delivery contractors, grocers, restaurants and app-based platforms across the United States hire delivery drivers for route, courier and on-demand work. It is one of the fastest ways into paid work in the country, and one where two jobs with the same hourly figure can be very different jobs once the employer and the vehicle are accounted for.</p>

<h3>What the work involves</h3>
<p>Loading and running a sequenced route of package stops, or accepting individual food, grocery and courier orders through an app; scanning, photographing and confirming each delivery; handling returns and failed deliveries; and managing the vehicle, whether a supplied van or your own car.</p>

<h3>Requirements</h3>
<ul>
    <li>A valid driver's license and a clean driving record, with minimum age and license history set by each employer</li>
    <li>For app-based work, your own insured vehicle &mdash; check that your policy covers delivering for pay</li>
    <li>Smartphone navigation and delivery app competence</li>
    <li>Physical ability to lift and carry packages for a full shift</li>
    <li>A commercial driver's license only for heavier vehicles, generally 26,001 pounds gross vehicle weight rating and above</li>
    <li>Authorisation to work in the United States; delivery driving is not a visa sponsorship route</li>
</ul>

<h3>Three very different pay structures</h3>
<ul>
    <li><strong>Employed route driver.</strong> Hourly pay with overtime rules, often in a supplied van. Who the employer actually is &mdash; a carrier or its contractor &mdash; decides the benefits</li>
    <li><strong>App-based driver.</strong> Paid per order. Any guarantee usually counts only engaged time, and the vehicle cost is yours</li>
    <li><strong>Independent courier.</strong> Contract rates with self-employment tax, insurance and vehicle costs all on your side</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask who employs you, and price your own car before trusting any app's hourly figure.</strong> At the IRS rate of 76 cents a mile from 1 July 2026, a 100-mile shift represents $76 of vehicle cost before a single tip is counted.</p>

<p><strong>Note:</strong> pay, employment status, vehicle arrangements and insurance requirements are set by each employer and platform &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Delivery driving is one of the quickest ways to start earning in the United States. You can often be approved by an app within days, and package contractors hire constantly. The catch is that every published pay figure for this work describes a different hour, a different employer or a different car, and the guides that compare them rarely say which.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-delivery-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128666; Browse Delivery Driver Jobs in the USA &rarr;
    </a>
</div>

<h2>"$15 to $25 an Hour" Counts Only Some of Your Hours</h2>

<p>App-based delivery is usually described as paying <strong>$15 to $25 or more an hour, including tips</strong>. The figure is not invented. The question is which hours it divides by.</p>

<p>The clearest answer comes from California, where the rules for app-based drivers were settled by <strong>Proposition 22</strong>. On <strong>25 July 2024</strong>, the California Supreme Court unanimously upheld it. Under it, drivers are guaranteed earnings of <strong>120 per cent of the minimum wage</strong> &mdash; but only for <strong>engaged time</strong>, which runs from accepting an order to completing it.</p>

<p>Time spent logged in and waiting for the next order is <strong>not engaged time</strong>, and the guarantee does not cover it.</p>

<p>That distinction is the whole story of gig pay. A driver who is logged in for ten hours and engaged for six can have a respectable "hourly rate" on the app and a much lower one across the day they actually gave up. When an app or a forum quotes an hourly figure, ask whether it is per engaged hour or per logged-in hour. Almost every attractive number you will see is the first.</p>

<h2>Your Car Costs 76 Cents a Mile</h2>

<p>The second thing the published ranges leave out is the vehicle, and for app-based and courier work it is the largest cost you carry.</p>

<p>The most useful measure is the one the tax authorities use. The <strong>IRS standard business mileage rate</strong> was set at <strong>72.5 cents a mile</strong> for the start of 2026, and the IRS raised it to <strong>76 cents a mile from 1 July 2026</strong> &mdash; a rare mid-year increase, made because of rising fuel costs. The rate is meant to reflect the full cost of running a vehicle: fuel, maintenance, tyres, insurance and depreciation.</p>

<p>So do the arithmetic before you judge an offer. <strong>A shift that puts 100 miles on your car costs about $76</strong> at that rate. If the shift paid $180 including tips, the vehicle took more than two-fifths of it, and that is before tax.</p>

<p>You will not pay that $76 in cash on the day &mdash; much of it arrives later as a repair bill, a replacement tyre or a car that is worth less when you sell it. That is exactly why drivers underestimate it. Track your miles from the first shift, both for your own decisions and because business miles are deductible at tax time.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/delivery-driver-jobs-in-usa-gig.jpg"
         alt="A delivery driver carrying a parcel from a van on a US city street"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Amazon, FedEx and UPS Are Three Different Kinds of Employer</h2>

<p>Guides put "company delivery drivers (Amazon, FedEx, UPS)" on a single line at <strong>$18 to $25 an hour</strong>, as though you would be working for one of three big companies on similar terms. In most cases you would not be working for Amazon or FedEx at all.</p>

<ul>
    <li><strong>Amazon.</strong> Most drivers in Amazon-branded vans are employed by independent <strong>Delivery Service Partners</strong>. Each DSP hires, manages and pays its own drivers, and each sets its own wages, bonuses and benefits. Two drivers in identical vans on neighbouring routes can be on different terms.</li>
    <li><strong>FedEx Ground.</strong> Routes are contracted to independent businesses that own or lease the vehicles and hire the drivers. In 2014 a federal appeals court ruled that a class of about 2,300 FedEx Ground drivers had been misclassified as independent contractors, and the model today puts a contractor business between the driver and FedEx.</li>
    <li><strong>UPS.</strong> Package drivers are UPS employees, and UPS is the largest single employer in the <strong>Teamsters</strong> union. UPS says its full-time drivers average around $145,000 in total compensation including benefits. Those full-time driving jobs are competitive and governed by the union contract.</li>
</ul>

<p>The practical point: <strong>the name on the van is not the name on your paycheck.</strong> When you apply for an "Amazon driver" or "FedEx driver" role, find out which company actually employs you, then ask that company about pay progression, health insurance, paid time off and how routes are assigned. The answers vary far more between contractors than the job title suggests.</p>

<h2>Where You Drive Sets the Floor</h2>

<p>A national range hides the fact that the legal minimum for this work varies enormously by location and by employment status.</p>

<ul>
    <li><strong>New York City</strong> sets a minimum pay rate for app-based restaurant and grocery delivery workers of <strong>$22.13 an hour before tips</strong> from <strong>1 April 2026</strong>. It adjusts every April, and tips must be paid on top of it rather than counted towards it.</li>
    <li><strong>California</strong> runs the Proposition 22 guarantee described above, at 120 per cent of the minimum wage for engaged time.</li>
    <li><strong>Employed drivers</strong> elsewhere are covered by the federal minimum wage of <strong>$7.25</strong> or the higher state or city rate where one applies &mdash; and most drivers live somewhere with a higher one.</li>
    <li><strong>Independent contractors</strong> have <strong>no federal minimum wage at all</strong>. Outside places that legislate for app-based work specifically, there is no floor under a contractor's earnings.</li>
</ul>

<p>So before comparing two offers, establish two things: are you an employee or a contractor, and does the city or state you drive in set a delivery-specific rate? Those answers outweigh the difference between most advertised ranges.</p>

<h2>What an Independent Contractor Pays That an Employee Does Not</h2>

<p>App-based and many courier roles treat you as an independent contractor. That brings real flexibility, and three costs the hourly figure does not show.</p>

<p><strong>Self-employment tax.</strong> An employer pays half of Social Security and Medicare for an employee. A contractor pays both halves, which comes to <strong>15.3 per cent</strong> of net earnings on top of income tax. Set money aside from every payout rather than meeting it for the first time at tax time.</p>

<p><strong>Insurance.</strong> Many personal auto policies exclude driving for pay. Check with your insurer before your first delivery, and ask about a delivery or rideshare endorsement. A claim refused because the car was being used commercially is a far larger loss than the cost of the right cover.</p>

<p><strong>Benefits.</strong> No employer health plan, no paid time off and no retirement contribution. Price them in when you compare a contractor role with an employed route driver job at a lower headline rate.</p>

<p>The same arithmetic runs through delivery work in other countries &mdash; our <a href="/blog/delivery-driver-jobs-in-uk">delivery driver jobs in UK guide</a> works through the British version, where the self-employed day rate can fall below a minimum wage year.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/delivery-driver-jobs-in-usa-route.jpg"
         alt="A delivery driver holding a package beside a van with a route map on a phone"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>When You Need a CDL</h2>

<p>Most package and food delivery is done in cars, minivans and cargo vans on an ordinary driver's license. A <strong>commercial driver's license</strong> generally becomes necessary for heavier vehicles &mdash; a single vehicle with a gross vehicle weight rating of <strong>26,001 pounds or more</strong>, heavier combinations, or vehicles carrying hazardous materials.</p>

<p>That changes the job as well as the license: box trucks at that weight and tractor-trailers are a different career with different pay. If that is the direction you want, our <a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">truck driver jobs in USA guide</a> covers CDL requirements and the routes into long-haul work.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Package route driver.</strong> A sequenced daily route in a supplied van, usually for a carrier or its contractor. Employed, hourly.</li>
    <li><strong>Food delivery driver.</strong> App-based orders in your own car. Paid per order, with engaged-time guarantees only where the law provides them.</li>
    <li><strong>Grocery delivery driver.</strong> Retailer or app-based, heavier orders and more time per stop.</li>
    <li><strong>Courier driver.</strong> Documents, parts and medical supplies, often on contract rates.</li>
    <li><strong>Route sales or service driver.</strong> A fixed customer route for one business, frequently with a CDL at the heavier end.</li>
    <li><strong>Independent contractor driver.</strong> Maximum flexibility, with the tax, insurance and vehicle costs on your side.</li>
</ul>

<h2>Coming from Outside the United States</h2>

<p>Delivery driving is not a visa sponsorship route. App-based platforms require authorisation to work in the US before they will activate an account, and carriers and their contractors hire people who already hold it. There is no US work visa category designed for delivery driving, and anyone offering to arrange one for a fee is not offering something real. Our <a href="/blog/unskilled-jobs-in-usa-for-foreigners">unskilled jobs in USA for foreigners guide</a> sets out where the genuine entry-level visa routes are.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do delivery drivers earn in the USA?</h3>
<p>App-based drivers are commonly quoted at $15 to $25 or more an hour including tips, usually measured over engaged time and before vehicle costs. Employed route drivers are quoted around $18 to $25 an hour, with terms set by whichever company actually employs them.</p>

<h3>Do app-based delivery drivers get paid for waiting time?</h3>
<p>Generally not. California's Proposition 22, upheld on 25 July 2024, guarantees 120 per cent of the minimum wage for engaged time only, which runs from accepting an order to completing it. Waiting between orders is not covered.</p>

<h3>How much does it cost to use my own car for deliveries?</h3>
<p>The IRS standard business mileage rate is 72.5 cents a mile for the first half of 2026 and 76 cents a mile from 1 July 2026. At that rate a 100-mile shift represents about $76 of fuel, maintenance, insurance and depreciation.</p>

<h3>Do Amazon delivery drivers work for Amazon?</h3>
<p>Most do not. Drivers in Amazon-branded vans are usually employed by independent Delivery Service Partners, each of which sets its own wages, bonuses and benefits. FedEx Ground similarly contracts routes to businesses that hire the drivers, while UPS drivers are UPS employees.</p>

<h3>What is the minimum pay for delivery workers in New York City?</h3>
<p>$22.13 an hour before tips for app-based restaurant and grocery delivery workers from 1 April 2026. The rate adjusts every April, and tips must be paid on top of it.</p>

<h3>Do independent contractor drivers pay more tax?</h3>
<p>They pay self-employment tax of 15.3 per cent of net earnings, covering both halves of Social Security and Medicare, on top of income tax. Business miles are deductible, which is a good reason to track them from the first shift.</p>

<h3>Do I need a CDL to be a delivery driver?</h3>
<p>Not for most package and food delivery, which uses cars and vans on an ordinary license. A CDL is generally required for single vehicles of 26,001 pounds gross vehicle weight rating or more, heavier combinations and hazardous materials.</p>

<h3>Can I get a US visa to work as a delivery driver?</h3>
<p>No. There is no US work visa category for delivery driving, and platforms and carriers require existing authorisation to work before they will hire or activate you.</p>

<h2>People Also Search For</h2>

<h3>Amazon DSP driver jobs</h3>
<p>Employed by independent Delivery Service Partners, each setting its own pay and benefits. Ask the DSP, not Amazon.</p>

<h3>DoorDash driver pay per hour</h3>
<p>Quoted per engaged hour including tips. Subtract waiting time and 76 cents a mile before comparing it with an employed job.</p>

<h3>FedEx Ground driver jobs</h3>
<p>Usually with a contractor business that holds the route, not with FedEx directly.</p>

<h3>UPS driver salary</h3>
<p>UPS says full-time drivers average around $145,000 in total compensation including benefits, under a Teamsters contract.</p>

<h3>Delivery driver jobs no experience</h3>
<p>Genuinely open to newcomers. A clean driving record and the right insurance matter more than a r&eacute;sum&eacute;.</p>

<h3>Prop 22 California driver pay</h3>
<p>120 per cent of the minimum wage for engaged time, upheld by the California Supreme Court in July 2024.</p>

<h3>NYC delivery worker minimum pay</h3>
<p>$22.13 an hour before tips from 1 April 2026, with tips paid on top.</p>

<h3>Courier jobs near me</h3>
<p>Often contract work. Price self-employment tax, insurance and vehicle costs into the rate before accepting.</p>

<h2>More Job Guides</h2>

<p>Comparing driving work and other entry routes? These cover them:</p>

<ul>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; the British version of this work, and the insurance class new couriers miss.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; CDL requirements and the heavier end of driving work.</li>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; the other big American entry route, and its state-by-state wage floors.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; where genuine entry-level visa routes exist.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; an indoor entry route, and the projection to see before choosing it.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; manual work and the visa categories that reach it.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; the depot end of the delivery chain, in Britain.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; driving work on a sponsored Gulf contract instead.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; hourly work with a state wage floor instead of engaged-time pay.</li>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a> &mdash; hourly passenger driving north of the border, with Job Bank pay by province.</li>
    <li><a href="/blog/taxi-driver-jobs-in-australia">Taxi Driver Jobs in Australia</a></li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax, insurance or immigration advice. Minimum pay rules, mileage rates, tax rates and employment status tests change, and pay figures on any job board or app are a moving average rather than a statistic. Confirm the current position with the IRS, your state and city labor authorities, your insurer and the employer's own advertisement before applying.</p>
HTML;
    }
}
