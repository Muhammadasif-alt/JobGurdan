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
 * "Delivery Driver Jobs in UK" — the UK parcel and multi-drop market, written
 * around the one thing that decides what these advertisements are worth: the
 * employed and self-employed figures are not in the same units and cannot be
 * compared as though they were.
 *
 * Corrections to the draft:
 *
 * 1. It quotes a GBP 155 day rate and a GBP 13.96 hourly rate in the same
 *    paragraph. The day rate belongs to self-employed courier work and is
 *    turnover before van, fuel and insurance; the hourly rate belongs to
 *    employed work and is pay. Reading them as one range overstates the
 *    self-employed side by whatever the vehicle costs.
 *
 * 2. It reports a self-employed average of GBP 22,596 a year without noting
 *    that it is below a full-time year at the National Minimum Wage, which
 *    from 1 April 2026 is GBP 12.71 an hour. That is lawful only because the
 *    minimum wage does not reach the genuinely self-employed, and that is the
 *    fact a reader needs.
 *
 * 3. It says one employer's GBP 183 day rate is "roughly 29% above the
 *    national average" two paragraphs after giving the average as GBP 155.
 *    183 against 155 is 18 per cent. The two figures use different bases.
 *
 * 4. It treats self-employed status as settled. It is not: Uber BV v Aslam
 *    (2021) made drivers workers with minimum wage and holiday rights, and
 *    IWGB v Central Arbitration Committee (2023) went the other way for
 *    Deliveroo riders because of a substitution clause. The clause is the
 *    thing to read for, and the draft never mentions it.
 *
 * 5. It merges the 7.5-tonne licence and the Driver CPC into one line about
 *    "additional licensing". They are separate gates with different triggers,
 *    and the CPC does not apply to the parcel vans most of these jobs use.
 *
 * 6. It omits hire and reward insurance entirely. Delivering for payment on
 *    an ordinary van policy is driving uninsured under section 143 of the
 *    Road Traffic Act 1988, which is the most expensive thing a new courier
 *    can get wrong.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DeliveryDriverJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-delivery-driver-jobs.html';

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
        $title = 'Delivery Driver Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Why a day rate and an hourly rate in the same advert are not the same money, the self-employed average that sits below a minimum wage year, the insurance most new couriers do not know they need, and what the substitution clause decides.',
                'content' => $content,
                'featured_image' => 'blogs/delivery-driver-jobs-in-uk.jpg',
                'tags' => 'delivery driver jobs uk, multi drop delivery driver, self employed courier jobs, van driver jobs uk, owner driver jobs, delivery driver salary uk, 7.5 tonne driver jobs, parcel delivery jobs',
                'meta_title' => 'Delivery Driver Jobs in UK',
                'meta_description' => 'Delivery driver jobs in the UK: why day rates and hourly rates are not comparable, the insurance you must hold, and what self-employed really means.',
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
            ['name' => 'UK Parcel & Multi-Drop Delivery Operators (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-delivery-driver-aggregated']
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
                'position' => 'Delivery Driver — Multi-Drop, Parcel and Home Delivery, UK Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift-based, commonly nine to eleven hours per delivery round',
                'language' => 'English',
                // Employed roles are paid hourly and self-employed rounds are
                // paid by the day before vehicle costs. One band cannot hold
                // both without misleading whoever reads it.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Parcel, multi-drop and home delivery driver roles with UK operators. Check whether the rate is employed pay or self-employed turnover before you apply.',
                'seo_keywords' => 'delivery driver jobs uk, multi drop delivery driver, van driver jobs uk, self employed courier jobs, owner driver jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Parcel carriers, supermarkets, retailers and independent courier operators across the United Kingdom hire delivery drivers for multi-drop parcel rounds, home delivery and collections work. Roles are advertised in two entirely different shapes &mdash; employed and paid by the hour, or self-employed and paid by the day or the parcel &mdash; and the advertisement does not always make clear which one it is.</p>

<h3>What the work involves</h3>
<p>Loading a round at a depot, running a sequenced route of anything from sixty to two hundred stops, handling failed deliveries and returns, and completing everything on a handheld scanner. Home delivery and appliance work adds two-person lifts and installation. Collections work runs the same route in reverse, picking up returns rather than dropping off.</p>

<h3>Requirements</h3>
<ul>
    <li>A valid UK driving licence, usually held for at least twelve months, commonly with no more than six penalty points</li>
    <li>Category B covers vans up to 3,500kg &mdash; the weight almost all parcel work sits under</li>
    <li>Category C1 for 3,500&ndash;7,500kg vehicles, plus Driver CPC and a driver medical for anything above 3.5 tonnes</li>
    <li>Comfort with handheld scanners, route apps and satellite navigation</li>
    <li>Physical fitness for repeated lifting and a full day in and out of the vehicle</li>
    <li>Hire and reward insurance if you supply your own vehicle &mdash; an ordinary van policy does not cover paid delivery</li>
</ul>

<h3>Two different pay structures</h3>
<ul>
    <li><strong>Employed and hourly.</strong> Advertised around &pound;13.70 to &pound;13.96 an hour. Against a National Minimum Wage of &pound;12.71 from 1 April 2026, that is roughly &pound;1 to &pound;1.25 above the legal floor, and it includes paid holiday, sick pay and pension enrolment</li>
    <li><strong>Self-employed and by the day.</strong> Advertised around &pound;155 a day in England. That is turnover, not pay &mdash; van, fuel, insurance and your own National Insurance come out of it, and there is no holiday pay behind it</li>
    <li><strong>Owner driver.</strong> The same day rate with every vehicle cost on your side of the line</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask which of the two you are being offered, and ask for the contract.</strong> If it is self-employed, read it for a substitution clause &mdash; the right to send someone else in your place. The Supreme Court treated that clause as decisive when it found Deliveroo riders were not workers, and its absence was part of why Uber drivers were. It is the single line that decides whether the minimum wage and holiday pay reach you at all.</p>

<p><strong>Note:</strong> pay, vehicle arrangements, insurance responsibility and employment status are set by each operator &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Delivery driving is one of the easiest jobs in the United Kingdom to get an interview for and one of the hardest to compare offers in. The reason is not complicated, but almost no guide says it out loud: the industry advertises in two different currencies. Some roles quote an hourly rate that is your pay. Others quote a day rate that is your turnover. Put side by side, they look like one range. They are not.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-delivery-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128666; Browse Delivery Driver Jobs in the UK &rarr;
    </a>
</div>

<h2>The &pound;155 Day and the &pound;13.96 Hour Are Not the Same Money</h2>

<p>The two figures quoted most often for this job are an average of <strong>&pound;155 a day</strong> in England and an hourly rate of around <strong>&pound;13.70 to &pound;13.96</strong>. A reader naturally puts them together. A ten-hour round at &pound;155 works out at &pound;15.50 an hour, so the day rate looks like the better deal by a comfortable margin.</p>

<p>It is not, because the two numbers are measuring different things.</p>

<p>The hourly rate is <strong>employed pay</strong>. Behind it sit 5.6 weeks of statutory paid holiday, statutory sick pay, pension auto-enrolment with an employer contribution, and a legal floor under the rate itself. The employer supplies the van, the fuel, the insurance and the uniform.</p>

<p>The day rate is <strong>self-employed turnover</strong>. Out of it come the van, the fuel, the insurance, your accountant if you use one, and your own National Insurance. There is no holiday behind it: a day not worked is a day not paid. Nobody is obliged to keep it above any floor at all.</p>

<p>So the comparison people should make is not &pound;155 against &pound;15.50 an hour. It is &pound;155 <em>minus your vehicle costs</em> against a rate that already has holiday, sick pay and a pension attached. Work out your own weekly cost of van and fuel before you accept a round, because that single subtraction reorders every offer on the page.</p>

<h2>The Self-Employed Average Sits Below a Minimum Wage Year</h2>

<p>This is the number that should stop you.</p>

<p>One frequently quoted courier operator reports an average of <strong>&pound;22,596 a year</strong> for self-employed drivers, fuel card included. That is presented as a salary alongside employed figures like <strong>&pound;25,609</strong> at a large appliance retailer.</p>

<p>From <strong>1 April 2026 the National Minimum Wage for workers aged 21 and over is &pound;12.71 an hour</strong>. A full-time employed year at that rate, on 37.5 hours a week, is <strong>&pound;24,784.50</strong>.</p>

<p>The self-employed average is <strong>&pound;2,188.50 below the legal minimum for an employee</strong> &mdash; before the van, before the fuel, before the insurance, and before any of the holiday an employee would have been paid for. Multi-drop rounds are rarely 37.5 hours a week, so the gap on an hourly basis is wider still.</p>

<p>That is not an accusation against any operator. It is lawful, and the reason it is lawful is the whole point: <strong>the National Minimum Wage does not reach the genuinely self-employed.</strong> When a job advertisement says "self-employed" and "flexible" in the same breath, the flexibility is real and so is the absence of a floor.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/delivery-driver-jobs-in-uk-self-employed.jpg"
         alt="A self-employed delivery driver loading parcels into a van on a UK street"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Percentage That Does Not Add Up</h2>

<p>A smaller point, but it tells you how to read these pages. One operator's day rate is reported as <strong>&pound;183</strong>, described as roughly <strong>29 per cent above the national average</strong>. Two paragraphs earlier the national average is given as <strong>&pound;155</strong>.</p>

<p>&pound;183 against &pound;155 is <strong>18 per cent</strong>, not 29. For 29 per cent to be right, the average being compared against would have to be about &pound;142 &mdash; a different figure from a different sample, quietly swapped in mid-article.</p>

<p>Neither number is dishonest on its own. The lesson is that job board averages are recomputed constantly, over different regions and different mixes of employed and self-employed postings, and two of them in the same paragraph rarely share a base. Treat any "X per cent above average" claim as a marketing line rather than a measurement.</p>

<h2>Self-Employed Is Not a Settled Fact &mdash; Read for the Substitution Clause</h2>

<p>Every guide to this job repeats the word "self-employed" as though it were a property of the work. In UK law it is a conclusion, and the courts have reached it both ways.</p>

<p>In <strong>Uber BV v Aslam</strong> the Supreme Court held in February 2021 that Uber drivers were <strong>workers</strong>, not independent contractors &mdash; entitled to the National Minimum Wage and paid annual leave, counted from the moment the app is switched on and the driver is available, not merely while carrying a passenger. What the contract called them did not decide it.</p>

<p>In <strong>IWGB v Central Arbitration Committee</strong> the Supreme Court held in November 2023 that <strong>Deliveroo riders were not workers</strong>. The deciding feature was a substitution clause: riders could send someone else to do the work without approval, which meant there was no obligation of personal service.</p>

<p>Those two results give you something concrete to do. <strong>Ask for the contract before you sign, and look for the right of substitution.</strong> A genuine, unfettered right to send a substitute points towards self-employment and away from minimum wage and holiday rights. Heavy control over your route, hours, appearance and rate, with no realistic ability to send anyone else, points the other way &mdash; whatever the heading on page one says.</p>

<p>If you are weighing this against other entry routes, our <a href="/blog/warehouse-jobs-uk-visa-sponsorship">warehouse jobs UK guide</a> covers the depot side of the same supply chain and its employment terms.</p>

<h2>The Insurance Nobody Mentions, and It Is a Criminal Offence</h2>

<p>This is the most expensive thing a new courier can get wrong, and the draft market guides leave it out completely.</p>

<p>If you carry other people's goods for payment, you need <strong>hire and reward</strong> cover. It is a distinct class of motor insurance. An ordinary van policy does not cover it, and <em>neither does adding "business use"</em> &mdash; business use covers driving to your own work, not carrying goods for a fee.</p>

<p>Delivering for payment without it means driving without valid insurance under <strong>section 143 of the Road Traffic Act 1988</strong>. The consequences are not limited to a refused claim: it carries a fine, penalty points and the possibility of the vehicle being seized. It applies to parcel rounds, to food delivery, and to app-based work in your own car.</p>

<p>Most drivers will also want <strong>goods in transit</strong> cover, which is separate again and pays for the parcels themselves rather than the vehicle. If a job is advertised as owner driver, price both before you price the round.</p>

<h2>The 7.5-Tonne Licence and the CPC Are Two Different Gates</h2>

<p>Guides tend to compress these into one line about "additional licensing". They are separate, they trigger at different points, and knowing which is which tells you what a job advertisement is really offering.</p>

<ul>
    <li><strong>Category B</strong> &mdash; the ordinary car licence &mdash; covers vehicles up to <strong>3,500kg</strong>, rising to <strong>4,250kg for electric and hydrogen vehicles</strong>. Essentially all parcel and multi-drop van work sits under this. You need nothing extra.</li>
    <li><strong>Category C1</strong> covers <strong>3,500kg to 7,500kg</strong>. If you passed your car test <strong>on or after 1 January 1997</strong> you must take a separate test for it. If you passed <strong>before</strong> that date, you have wider entitlements already on your licence &mdash; worth checking before you pay for training you do not need.</li>
    <li><strong>Driver CPC</strong> is not a licence category. It is a professional qualification required to drive goods vehicles <strong>over 3.5 tonnes</strong> for a living, and it is maintained with <strong>35 hours of periodic training every five years</strong>.</li>
</ul>

<p>The practical consequence: <strong>an advertisement offering to pay for your CPC is not describing a parcel van job.</strong> It is describing 7.5-tonne or HGV work, which pays more, is a different day, and needs a medical. Read that offer as information about the vehicle, not as a benefit.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/delivery-driver-jobs-in-uk-licence.jpg"
         alt="A UK delivery driver at the rear doors of a parcel van checking a handheld scanner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Multi-drop delivery driver.</strong> The core of the market. Sixty to two hundred stops a day, usually self-employed, usually a day rate.</li>
    <li><strong>Van driver, employed.</strong> Company van, hourly pay, holiday and pension. Fewer of these, and worth more than the headline gap suggests.</li>
    <li><strong>Home delivery driver.</strong> Supermarkets and retailers, into customers' homes. Employed more often than parcel work, and heavier on customer contact.</li>
    <li><strong>Owner driver.</strong> Your van, your costs, your hire and reward policy. The highest day rates and the thinnest margins.</li>
    <li><strong>Collections driver.</strong> Returns and pickups rather than drops. Similar rate, more variable route.</li>
    <li><strong>7.5-tonne driver.</strong> A different licence, a CPC and a medical. Furniture, appliances and trunking work.</li>
</ul>

<h2>Where the Jobs Are</h2>

<p>Volume follows the depot network rather than the population, which is why the same names keep appearing: London and the South East including Dartford and Grays, the Midlands corridor around Corby and Tamworth, and Greater Manchester. Postings described as "UK-wide" almost always mean self-employed courier work where you choose your own delivery zone.</p>

<p>One thing to weigh geographically: an employed round in an expensive city and a self-employed round in a cheaper one can produce very different results once vehicle costs and rent are both counted. The day rate does not vary between regions nearly as much as the cost of running the van does.</p>

<h2>What About Visa Sponsorship?</h2>

<p>Asked constantly, so it is worth answering plainly. <strong>Delivery driving is not a realistic UK sponsorship route.</strong> Van-based delivery sits below the skill level the Skilled Worker route requires, and self-employed courier work cannot be sponsored at all, because sponsorship needs an employer and a self-employed contract does not create one.</p>

<p>These jobs are for people who already hold the right to work in the UK &mdash; British and Irish citizens, settled and pre-settled status, dependants with work rights, graduate visa holders, and Youth Mobility Scheme participants. Our <a href="/blog/warehouse-jobs-uk-visa-sponsorship">warehouse jobs UK visa sponsorship guide</a> sets out why operative-level roles fail the same tests and where sponsorship in logistics genuinely does happen.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do delivery drivers earn in the UK?</h3>
<p>Employed roles are advertised at roughly &pound;13.70 to &pound;13.96 an hour, against a National Minimum Wage of &pound;12.71 from April 2026. Self-employed rounds average about &pound;155 a day in England, but that is turnover before the van, fuel and insurance rather than pay.</p>

<h3>Is a self-employed delivery job better paid than an employed one?</h3>
<p>Only if the day rate beats the hourly rate by more than your vehicle costs plus the value of the holiday, sick pay and pension you give up. One quoted self-employed average, &pound;22,596 a year, is &pound;2,188.50 below a full-time year at the minimum wage.</p>

<h3>Do self-employed delivery drivers get the minimum wage?</h3>
<p>Not if they are genuinely self-employed. The minimum wage reaches employees and workers, not independent contractors. Whether you fall inside it depends on the reality of the arrangement, not the label on the contract.</p>

<h3>What is a substitution clause and why does it matter?</h3>
<p>It is the right to send someone else to do your work. The Supreme Court treated an almost unfettered substitution right as decisive when it found Deliveroo riders were not workers in 2023. Its presence or absence is the clearest signal in the contract.</p>

<h3>What insurance do I need to deliver parcels in my own van?</h3>
<p>Hire and reward cover. An ordinary van policy will not do, and adding business use is not enough. Delivering for payment without it is driving uninsured under section 143 of the Road Traffic Act 1988. Goods in transit cover, for the parcels themselves, is separate.</p>

<h3>Do I need a Driver CPC to be a delivery driver?</h3>
<p>Not for vans up to 3.5 tonnes, which is virtually all parcel and multi-drop work. The CPC applies above 3.5 tonnes and is kept current with 35 hours of periodic training every five years. An advert offering to fund your CPC is describing a larger vehicle.</p>

<h3>Can I drive a 7.5-tonne lorry on a normal car licence?</h3>
<p>Only if you passed your car test before 1 January 1997, in which case wider entitlements are already on your licence. Anyone who passed on or after that date needs a separate category C1 test, plus a CPC and a medical for professional work.</p>

<h3>Can I get UK visa sponsorship as a delivery driver?</h3>
<p>Realistically no. The role sits below the Skilled Worker skill threshold, and self-employed courier work cannot be sponsored because there is no employer to sponsor it. These jobs suit people who already hold the right to work.</p>

<h2>People Also Search For</h2>

<h3>Multi drop delivery driver jobs</h3>
<p>The largest slice of the market, almost always self-employed and paid by the day rather than the hour.</p>

<h3>Self employed courier jobs</h3>
<p>Flexible and without a wage floor. Read the contract for the substitution clause before deciding what it is worth.</p>

<h3>Van driver jobs UK</h3>
<p>The employed end of the same work. Lower headline rate, with holiday, sick pay and pension behind it.</p>

<h3>Owner driver jobs</h3>
<p>Highest day rates, and every vehicle cost on your side. Hire and reward insurance is compulsory, not optional.</p>

<h3>Delivery driver salary UK</h3>
<p>Two incompatible answers depending on employment status. Compare day rate minus costs against hourly pay plus entitlements.</p>

<h3>7.5 tonne driver jobs</h3>
<p>Category C1, a Driver CPC and a medical. Different pay, different day, and free if you passed your car test before 1997.</p>

<h3>Delivery driver jobs no experience</h3>
<p>Genuinely open to newcomers. The licence, the points on it and the insurance class matter far more than a CV.</p>

<h3>Delivery driver jobs with visa sponsorship UK</h3>
<p>Not a real route. The role is below the Skilled Worker threshold and self-employed work has no sponsor.</p>

<h2>More Job Guides</h2>

<p>Looking at other routes into UK and international work? These cover them:</p>

<ul>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; the depot end of the same supply chain, and the honest answer on sponsorship.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London</a> &mdash; another entry-level London route and what it actually pays.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the same casual-versus-permanent trade-off under Australian award rules.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; driving work on a Gulf sponsorship route instead.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; a licensed Gulf role where the basic-versus-allowance split decides your gratuity.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; where UK sponsorship genuinely is available.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; the care route and its current restrictions.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the equivalent question on the other side of the Atlantic.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; a UK trade with a real qualification barrier, and the same CIS deduction on day rates.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; the American market, where app pay guarantees count only engaged time.</li>
    <li><a href="/blog/cook-jobs-in-uk">Cook Jobs in UK</a> &mdash; shift work in UK kitchens, and the 2024 tips law behind the service charge.</li>
    <li><a href="/blog/taxi-driver-jobs-in-australia">Taxi Driver Jobs in Australia</a></li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, insurance, tax or immigration advice. Wage rates, licence rules, insurance requirements and employment status tests change, and pay figures on any job board are a moving average rather than a statistic. Confirm the current position with GOV.UK, your insurer and the employer's own advertisement before applying or paying for any training.</p>
HTML;
    }
}
