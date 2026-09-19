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
 * "How to Get a Delivery Job in Australia" — the employee award route beside
 * the gig platform route, both priced from the current Fair Work pay guide
 * and the first minimum standards order for on-demand delivery.
 *
 * Corrections to the draft (checked against the Fair Work Ombudsman pay guide
 * for MA000038, awards.fairwork.gov.au, fwc.gov.au, ato.gov.au, afp.gov.au,
 * nsw.gov.au and the platforms' own pages, September 2026):
 *
 * 1. Every award figure in the draft is out of date. From the first full pay
 *    period on or after 1 July 2026 the grade 2 rate is $27.51 an hour, not
 *    $25.28, grade 3 is $27.83 and grade 4 is $28.32.
 *
 * 2. The draft gives a casual rate of $31.60. With the 25% loading the
 *    current casual grade 2 rate is $34.39.
 *
 * 3. The draft gives an annual figure of $49,953. The current grade 2 weekly
 *    rate of $1,045.50 works out at about $54,366 a year.
 *
 * 4. The draft says gig workers are still waiting for minimum standards. The
 *    Fair Work Commission made the Interim On-Demand Delivery Employee-like
 *    Worker Minimum Standards Order on 11 August 2026, in force from
 *    17 August 2026, with an hourly floor of $31.30 to $32.00 on engaged time.
 *
 * 5. The draft says food delivery riders register for GST only above $75,000.
 *    That is right, and worth stating clearly, because the ATO's
 *    register-from-the-first-dollar rule applies to passenger ride-sourcing,
 *    not food delivery.
 *
 * 6. The draft attributes the 2019 Fair Work Ombudsman finding to Uber Eats
 *    riders. It concerned Uber Australia drivers, and found the relationship
 *    was not an employment relationship.
 *
 * 7. The draft says a bicycle courier sits at grade 2. Schedule B puts a foot
 *    or bicycle courier at grade 1.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DeliveryJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-delivery-driver-jobs.html';

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
        $title = 'How to Get a Delivery Job in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Employed delivery drivers are on $27.51 an hour under the award from July 2026, and gig riders now have a legal floor of $31.30 to $32.00 an hour of engaged time under the first minimum standards order.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-a-delivery-job-in-australia.jpg',
                'tags' => 'delivery jobs australia, delivery driver pay rate, road transport and distribution award, uber eats australia, doordash australia, amazon flex australia, gig worker minimum standards, abn delivery driver',
                'meta_title' => 'How to Get a Delivery Job in Australia: Pay and Rules',
                'meta_description' => 'Delivery jobs in Australia: award pay of $27.51 an hour, the new gig minimum standards order, ABN and GST rules, and what each platform requires.',
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
            ['name' => 'Australian Courier, Parcel and Delivery Platform Operators (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'au-delivery-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Delivery Driver — Employed Courier and Platform Delivery Roles Across Australia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, part-time or casual for employees; self-rostered for platform contractors',
                'language' => 'English',
                // Award rates change every July and platform earnings vary, so
                // the guide quotes the official rates rather than a band here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Courier, parcel and food delivery roles across Australia, as an award-covered employee or a platform contractor with an ABN.',
                'seo_keywords' => 'delivery jobs australia, delivery driver jobs, courier jobs australia, uber eats jobs, amazon flex australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Courier companies, supermarkets, parcel carriers and delivery platforms hire across Australia, either as an award-covered employee or as an independent contractor with an ABN.</p>

<h3>What the work involves</h3>
<p>Collecting and delivering parcels, groceries or meals, planning routes, scanning and proof of delivery, loading your vehicle safely and dealing with customers at the door.</p>

<h3>Common requirements</h3>
<ul>
    <li>A current driver licence for the vehicle you use, or a rider licence for a motorcycle or scooter</li>
    <li>An ABN for platform and contractor work</li>
    <li>A police check for many employed roles</li>
    <li>Your own vehicle for most contractor roles, and insurance to match</li>
    <li>The right to work in Australia</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> award rates, minimum standards and tax rules are set by the Fair Work Commission, the Fair Work Ombudsman and the ATO &mdash; not by JobGader. Check the current pay guide and platform terms before you start.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Delivery work in Australia splits into two different jobs: an employed driver covered by the Road Transport and Distribution Award, and a platform contractor with an ABN.</strong> From the first full pay period on or after <strong>1 July 2026</strong> the award pays a grade 2 driver <strong>$27.51 an hour</strong>, and since <strong>17 August 2026</strong> gig delivery workers have a legal earnings floor of <strong>$31.30 to $32.00</strong> an hour of engaged time.</p>

<p>This guide covers both routes: the real rates, the new gig rules, what the ATO expects, and what each platform actually requires.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-delivery-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128230; Browse Delivery Jobs in Australia &rarr;
    </a>
</div>

<h2>Employee or Contractor: The Decision That Sets Everything Else</h2>

<ul>
    <li><strong>Employed driver.</strong> Covered by the <strong>Road Transport and Distribution Award 2020</strong>, with minimum hourly rates, penalty rates, casual loading and leave entitlements.</li>
    <li><strong>Platform contractor.</strong> You work through an app with your own ABN, set your own hours, pay your own tax, and are now covered by a minimum standards order rather than the award.</li>
</ul>

<h2>Award Pay: The Rates Most Guides Have Wrong</h2>

<p>Award rates rise every July. These are the current rates from the Fair Work pay guide, effective from the first full pay period on or after <strong>1 July 2026</strong>:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Classification</th>
            <th style="padding:10px;text-align:left;">Weekly</th>
            <th style="padding:10px;text-align:left;">Hourly</th>
            <th style="padding:10px;text-align:left;">Casual hourly</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Grade 1</strong> (foot or bicycle courier)</td><td style="padding:10px;">$1,021.00</td><td style="padding:10px;">$26.87</td><td style="padding:10px;">$33.59</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Grade 2</strong> (rigid vehicle up to 4.5 t)</td><td style="padding:10px;">$1,045.50</td><td style="padding:10px;"><strong>$27.51</strong></td><td style="padding:10px;">$34.39</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Grade 3</strong> (over 4.5 t up to 13.9 t)</td><td style="padding:10px;">$1,057.60</td><td style="padding:10px;">$27.83</td><td style="padding:10px;">$34.79</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Grade 4</strong> (three-axle rigid)</td><td style="padding:10px;">$1,076.20</td><td style="padding:10px;">$28.32</td><td style="padding:10px;">$35.40</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>Casual loading is 25%</strong>, which is where the casual column comes from.</li>
    <li><strong>Saturday</strong> pays 150% and <strong>Sunday</strong> 200% of the ordinary hourly rate &mdash; for a grade 2 driver, $41.27 and $55.02.</li>
    <li>A full-time grade 2 driver's weekly rate of $1,045.50 works out at about <strong>$54,366 a year</strong>, not the $49,953 quoted in older guides.</li>
    <li>The <strong>national minimum wage</strong> from 1 July 2026 is $26.44 an hour, or $1,004.90 a week.</li>
</ul>

<p>One classification detail worth knowing: a <strong>foot or bicycle courier is grade 1</strong>, not grade 2. Grade 2 starts with a rigid vehicle, including a motorcycle, up to 4.5 tonnes.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-delivery-job-in-australia-courier.jpg" alt="A delivery driver carrying a parcel beside a van on a suburban street with Sydney Harbour behind" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Employed drivers get award rates, penalty rates and leave; contractors trade those for flexibility.</figcaption>
</figure>

<h2>Gig Delivery Now Has a Legal Pay Floor</h2>

<p>This is the biggest change in the sector, and most guides have not caught up. Under the <strong>Closing Loopholes</strong> laws that commenced on <strong>26 August 2024</strong>, the Fair Work Commission gained power to set minimum standards for "employee-like" workers on digital labour platforms.</p>

<p>On <strong>11 August 2026</strong> the Commission made the <strong>Interim On-Demand Delivery Employee-like Worker Minimum Standards Order</strong>, in force from <strong>17 August 2026</strong>. It is the first such order ever made, and it covers workers engaged through an app who mainly collect and deliver food, drinks or groceries for immediate delivery, in vehicles up to one tonne capacity.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Vehicle</th>
            <th style="padding:10px;text-align:left;">17 Aug &ndash; 31 Dec 2026</th>
            <th style="padding:10px;text-align:left;">From 1 Jan 2027</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">No vehicle, pedal bicycle, e-bike or e-scooter</td><td style="padding:10px;">$31.30</td><td style="padding:10px;">$31.80</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Combustion motorcycle or scooter</td><td style="padding:10px;">$31.50</td><td style="padding:10px;">$32.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Other motor vehicles up to 1 tonne</td><td style="padding:10px;">$32.00</td><td style="padding:10px;">&mdash;</td></tr>
    </tbody>
</table>
</div>

<p>The floor applies to <strong>engaged time</strong>, measured over an earnings period the platform sets of up to <strong>21 days</strong>, with any shortfall topped up in the next period or within seven days after it. The order also covers vehicle standards, records, insurance, consultation, unpaid time away and information that must come with each engagement request.</p>

<p>Alongside it, the same laws give employee-like workers access to <strong>unfair deactivation</strong> claims, with a 21-day filing limit.</p>

<h2>ABN, GST and Tax for Platform Work</h2>

<ul>
    <li><strong>ABN.</strong> Every major platform requires one. Apply through the Australian Business Register; a successful online application usually returns the number immediately.</li>
    <li><strong>GST.</strong> Food delivery follows the ordinary rule: register once your turnover reaches <strong>$75,000</strong>. The rule that forces registration from the first dollar applies to <strong>passenger ride-sourcing</strong>, which the ATO treats as taxi travel &mdash; not to delivering meals or parcels.</li>
    <li><strong>Car expenses.</strong> The cents per kilometre rate is <strong>91 cents</strong> for 2026-27, capped at 5,000 work-related kilometres per car, and it already covers fuel, servicing, registration, insurance and depreciation. The alternative is the logbook method, claiming the work-related percentage of actual costs with no cap.</li>
    <li><strong>Records.</strong> You pay tax on profit, not on gross earnings, so keep the kilometres, the phone bills and the equipment receipts from day one.</li>
</ul>

<h2>What the Platforms Actually Require</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Platform</th>
            <th style="padding:10px;text-align:left;">What its own page requires</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Uber Eats</strong></td><td style="padding:10px;">ABN, proof of ID, a background check and a work-rights check. A full licence for motorbikes, and for cars in NSW, ACT and NT; P2 accepted for cars in VIC, QLD, WA, SA and TAS. Bicycle riders must pass a safety test at 100%. Recreational e-scooters are not accepted.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>DoorDash</strong></td><td style="padding:10px;">18 or older, any car, scooter or bicycle, an ABN, consent to a free background check and the right to work in Australia.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Amazon Flex</strong></td><td style="padding:10px;">20 or older, a <strong>full unrestricted</strong> licence (provisional and international licences are not accepted), an eligible four-door car or van, CTP plus third-party property insurance, a compatible phone, an Australian bank account and an ABN in your own name.</td></tr>
    </tbody>
</table>
</div>

<p>Amazon also runs <strong>Delivery Service Partners</strong>, which is a different thing entirely: a DSP is a small business running 20 to 40 vans that <strong>employs</strong> its drivers. If you want employee conditions in parcel delivery, a DSP is the employer to apply to, not Amazon itself.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-delivery-job-in-australia-city-run.jpg" alt="A courier loading parcels into a van on an Australian city street" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Platform work now has a floor, but you still carry the vehicle costs.</figcaption>
</figure>

<h2>Licences and Checks</h2>

<ul>
    <li><strong>Licence.</strong> Each state and territory issues its own. A NSW class C car licence covers vehicles up to 4.5 tonnes; motorcycles and scooters need a separate rider licence, and bicycle delivery needs none.</li>
    <li><strong>Police check.</strong> The official federal route is an <strong>AFP National Police Check</strong>, currently <strong>$56</strong>, or <strong>$113</strong> where fingerprints are required. Digital certificates usually arrive within days, and the certificate is tied to the purpose you gave.</li>
</ul>

<h2>What the 2019 Uber Finding Actually Said</h2>

<p>The Fair Work Ombudsman finished its Uber investigation on <strong>7 June 2019</strong> and concluded that "the relationship between Uber Australia and the drivers is not an employment relationship". Two things guides get wrong: it concerned <strong>Uber drivers generally</strong>, not Uber Eats riders, and the Ombudsman said the investigation "related solely to Uber Australia and was not an investigation of the gig economy more generally". The new minimum standards order, not that finding, is what governs gig delivery pay today.</p>

<h2>How to Get Started</h2>

<ol>
    <li><strong>Decide employee or contractor</strong> first &mdash; award conditions and leave, or flexible hours and your own costs.</li>
    <li><strong>Check the award rate</strong> against any employed offer, using the grade that matches the vehicle.</li>
    <li><strong>Get your ABN</strong> before signing up to a platform.</li>
    <li><strong>Match the licence to the vehicle</strong>, and check the platform's state-by-state licence rules.</li>
    <li><strong>Order a police check</strong> if you are targeting employed courier or parcel work.</li>
    <li><strong>Track kilometres and expenses</strong> from the first shift, whichever route you take.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum pay for a delivery driver in Australia?</h3>
<p>Under the Road Transport and Distribution Award from 1 July 2026, a grade 2 driver gets $27.51 an hour, or $34.39 as a casual. Saturday pays 150% and Sunday 200%.</p>

<h3>Do gig delivery riders have a minimum rate now?</h3>
<p>Yes. The interim minimum standards order in force since 17 August 2026 sets a floor of $31.30 an hour of engaged time for bicycles and e-bikes, $31.50 for motorcycles and $32.00 for other vehicles up to one tonne.</p>

<h3>Do I need an ABN to deliver?</h3>
<p>For platform work, yes: Uber Eats, DoorDash and Amazon Flex all require one. Employed courier roles do not, because you are an employee.</p>

<h3>Do food delivery riders have to register for GST?</h3>
<p>Only once turnover reaches $75,000. The register-from-the-first-dollar rule applies to passenger ride-sourcing, not to food or parcel delivery.</p>

<h3>What can I claim at tax time?</h3>
<p>Work-related car costs, either at 91 cents a kilometre up to 5,000 kilometres for 2026-27 or by logbook, plus phone, equipment and other work expenses. You are taxed on profit.</p>

<h3>What licence do I need?</h3>
<p>A car licence for van and car work, a rider licence for motorcycles and scooters, and no licence for bicycle delivery. Amazon Flex requires a full unrestricted licence.</p>

<h3>How much is a police check?</h3>
<p>An AFP national police check costs $56, or $113 if fingerprints are needed.</p>

<h3>Is Amazon Flex the same as being an Amazon driver?</h3>
<p>No. Flex is contractor work with your own vehicle and ABN. Amazon's Delivery Service Partners are separate businesses that employ their drivers.</p>

<h2>People Also Search For</h2>

<h3>Delivery driver pay rate Australia</h3>
<p>$27.51 an hour for award grade 2 from July 2026.</p>

<h3>Uber Eats requirements Australia</h3>
<p>ABN, ID, background and work-rights checks, and a licence that matches your state.</p>

<h3>Amazon Flex Australia</h3>
<p>20 or older, full unrestricted licence, eligible vehicle and an ABN in your own name.</p>

<h3>Gig worker minimum standards</h3>
<p>The first order took effect on 17 August 2026 with an engaged-time floor.</p>

<h3>ABN for delivery driver</h3>
<p>Free to apply through the Australian Business Register and required by every platform.</p>

<h3>GST threshold Australia</h3>
<p>$75,000 for delivery work; ride-sourcing must register from the first dollar.</p>

<h3>Cents per kilometre 2026-27</h3>
<p>91 cents, capped at 5,000 work-related kilometres per car.</p>

<h3>Casual loading Australia</h3>
<p>25% on the ordinary hourly rate under this award.</p>

<h2>More Job Guides</h2>

<p>Comparing delivery and driving routes? These cover them:</p>

<ul>
    <li><a href="/blog/taxi-driver-jobs-in-australia">Taxi Driver Jobs in Australia</a> &mdash; passenger work, its licensing and what it pays.</li>
    <li><a href="/blog/how-to-get-a-warehouse-driver-job-in-uk">How to Get a Warehouse Driver Job in UK</a> &mdash; the British licence ladder and ONS pay.</li>
    <li><a href="/blog/how-to-get-a-fleet-driver-job-in-canada">How to Get a Fleet Driver Job in Canada</a> &mdash; provincial licences, training hours and Job Bank wages.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; the American van and app market.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; which Australian jobs can actually be sponsored.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; other entry-level routes and their award rates.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the Fair Work Ombudsman pay guide for MA000038, Fair Work Commission decisions and orders, ATO guidance, AFP fees and the platforms' own requirement pages. Award rates change every July and platform rules change often. Always check the current pay guide and platform terms before you start.</p>
HTML;
    }
}
