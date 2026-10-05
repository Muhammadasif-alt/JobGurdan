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
 * "Taxi and Uber Driver Requirements by Country" — the permit-by-permit page
 * for passenger driving in the UAE, UK, US, Canada and Australia. The
 * Australian taxi page already covers that market's jobs; this one compares
 * the licensing gate across countries.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. "SOC 8215" for UK taxi drivers. 8215 is driving instructors; taxi and
 *    cab drivers and chauffeurs are SOC 8213, listed as ineligible for
 *    Skilled Worker sponsorship.
 *
 * 2. A Dubai permit fee and validity period taken from secondary sites. The
 *    RTA permit, its checks and the English and psychological assessment
 *    are kept; the fee and validity are left to the operator to confirm.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class TaxiUberDriverRequirementsBlogSeeder extends Seeder
{
    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

    private const UK_APPLY_URL = 'https://www.gov.uk/find-a-job';

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
        $title = 'Taxi and Uber Driver Requirements by Country';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Driving a taxi or Uber needs more than a licence. Dubai requires an RTA driver permit, the UK a council or TfL licence, Uber in the US a year of US driving, and NSW a Passenger Transport Licence Code.',
                'content' => $content,
                'featured_image' => 'blogs/taxi-uber-driver-requirements-by-country.jpg',
                'tags' => 'taxi driver requirements, uber driver requirements, rta driver permit dubai, taxi licence uk, tfl private hire licence, uber driver requirements usa, passenger transport licence code nsw, taxi driver jobs abroad',
                'meta_title' => 'Taxi and Uber Driver Requirements by Country',
                'meta_description' => 'Taxi and Uber driver requirements in the UAE, UK, US, Canada and Australia: the permit, licence history, checks and right to work each country asks for.',
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
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        $listings = [
            [
                'advertiser' => ['UAE Taxi & Limousine Operators (Aggregated)', 'uae-taxi-driver-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Taxi and Limousine Driver — UAE Taxi and Limousine Operators',
                'apply' => self::UAE_APPLY_URL,
                'hours' => 'Shift-based, including nights and weekends',
                'description' => $this->uaeJobDescription(),
                'meta' => 'Taxi and limousine driver roles with UAE operators. In Dubai an RTA professional driver permit is required, with English and psychological assessments.',
                'keywords' => 'taxi driver jobs dubai, limousine driver uae, rta driver permit, taxi driver uae visa, chauffeur jobs dubai',
            ],
            [
                'advertiser' => ['UK Private Hire Operators (Aggregated)', 'uk-private-hire-aggregated'],
                'location' => ['United Kingdom', 'United Kingdom'],
                'position' => 'Private Hire Driver — UK Private Hire Operators (Right to Work Required)',
                'apply' => self::UK_APPLY_URL,
                'hours' => 'Variable, including evenings, weekends and bank holidays',
                'description' => $this->ukJobDescription(),
                'meta' => 'Private hire driver roles with UK operators. A council or TfL licence and the right to work in the UK are required; the role is not open to Skilled Worker sponsorship.',
                'keywords' => 'private hire driver jobs uk, taxi driver jobs uk, phv licence, tfl private hire, minicab driver jobs',
            ],
        ];

        foreach ($listings as $listing) {
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location'][0]],
                ['area' => 'Nationwide', 'country' => $listing['location'][1]]
            );

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => $listing['hours'],
                    'language' => 'English',
                    // Pay depends on the operator, the fare split and the
                    // hours driven; this site does not republish job-board rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['apply'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Taxi and limousine operators across the UAE recruit drivers from overseas, usually on employer-sponsored work permits.</p>

<h3>Requirements</h3>
<ul>
    <li>A MOHRE work permit and residence visa arranged by the employer. You cannot work on a visit or tourist visa</li>
    <li>A UAE driving licence, an Emirates ID and a police good conduct certificate</li>
    <li>In Dubai, an RTA professional driver permit, which includes a medical, an English test and psychological assessment</li>
</ul>

<p><strong>Who pays:</strong> under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge the worker recruitment and employment costs, directly or indirectly.</p>

<p><strong>Note:</strong> permit and wage rules are set by the RTA and MOHRE, not by JobGader. Confirm them before accepting an offer.</p>
JOBHTML;
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Private hire and taxi operators across the UK engage licensed drivers, often on a self-employed basis.</p>

<h3>Requirements</h3>
<ul>
    <li>A taxi or private hire driver licence from the local council, or from Transport for London in London</li>
    <li>The right to work in the UK. Taxi and cab drivers and chauffeurs (SOC 8213) are ineligible for Skilled Worker sponsorship</li>
    <li>A full driving licence held for at least 12 months, and the "fit and proper person" checks the licensing authority sets</li>
</ul>

<p><strong>Note:</strong> licensing rules are set by councils and TfL, and visa rules by the Home Office, not by JobGader. Check gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Taxi and ride-hailing work looks like the easiest job to start abroad: you already drive, and the app does the rest. In practice, carrying paying passengers is licensed almost everywhere, and the licence comes on top of your right to work and your ordinary driving licence. This guide sets out what the UAE, the UK, the US, Canada and Australia actually ask for, so you can see which gate stands between you and the first fare.</p>

<h2>The Requirements at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Passenger permit</th>
            <th style="padding:10px;text-align:left;">Licence history</th>
            <th style="padding:10px;text-align:left;">Right to work</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UAE (Dubai)</strong></td><td style="padding:10px;">RTA professional driver permit</td><td style="padding:10px;">UAE driving licence</td><td style="padding:10px;">MOHRE work permit via the employer</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UK</strong></td><td style="padding:10px;">Council or TfL taxi / private hire licence</td><td style="padding:10px;">Full licence for at least 12 months</td><td style="padding:10px;">Required; SOC 8213 cannot be sponsored</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>US (Uber)</strong></td><td style="padding:10px;">Set by state and city</td><td style="padding:10px;">1 year of US driving, 3 if under 25</td><td style="padding:10px;">Required</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">Set by city</td><td style="padding:10px;">Varies by city</td><td style="padding:10px;">Required</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australia (NSW)</strong></td><td style="padding:10px;">Passenger Transport Licence Code</td><td style="padding:10px;">Unrestricted Australian licence 12 months in the last 4 years</td><td style="padding:10px;">Required</td></tr>
    </tbody>
</table>
</div>

<h2>UAE: The RTA Professional Driver Permit</h2>

<p>In Dubai, anyone driving a taxi or limousine for pay needs a <strong>professional driver permit from the Roads and Transport Authority (RTA)</strong>. Drivers taking ride-hailing bookings for pay need the same permit.</p>

<p>The permit sits on top of the basics:</p>

<ul>
    <li>A <strong>MOHRE work permit</strong> and residence visa. You cannot work on a visit or tourist visa.</li>
    <li>A <strong>UAE driving licence</strong> and an <strong>Emirates ID</strong>.</li>
    <li>A <strong>police good conduct certificate</strong> and an RTA <strong>medical and eye test</strong>.</li>
    <li>An <strong>English language test and psychological and behavioural assessment</strong>; the RTA has run the English test with the British Council.</li>
    <li>RTA training and theory and practical tests.</li>
</ul>

<p>New drivers usually go through all of this with the operator that hires them, which is also the company that must hold your work permit. Under Article 6 of Federal Decree-Law No. 33 of 2021, that employer may not charge you recruitment and employment costs, directly or indirectly. Ask the operator for the current permit fee and validity, as RTA charges change.</p>

<p>Wages must be paid through the Wage Protection System, now under <strong>Ministerial Resolution No. 340 of 2026</strong>. If the pay is commission on fares, get the split and any minimum guarantee in writing.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/taxi-uber-driver-requirements-by-country-hotel.jpg"
         alt="A smiling driver leaning from a black taxi with a yellow roof sign to open the rear door for a woman with a suitcase and backpack, on a palm-lined Dubai street with the Burj Khalifa behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>UK: A Council or TfL Licence</h2>

<p>Taxi and private hire vehicle (PHV) driver licences are issued by the <strong>local council</strong>, or by <strong>Transport for London (TfL)</strong> in London. To apply you must:</p>

<ul>
    <li>Be able to <strong>work legally in the UK</strong>.</li>
    <li>Usually have held a full GB, Northern Ireland or EU driving licence for at least <strong>12 months</strong>.</li>
    <li>Be a <strong>"fit and proper person"</strong>, which means background and character checks, often including an enhanced DBS check.</li>
    <li>Pass whatever medical, knowledge or driving test the authority sets. In London, taxi drivers must be over 21.</li>
</ul>

<p><strong>Sponsorship is not available.</strong> Taxi and cab drivers and chauffeurs (SOC 8213) are listed as ineligible for the Skilled Worker visa. Since 22 July 2025, below-degree jobs need a Temporary Shortage List entry, and the occupation does not have one. UK taxi and Uber work is only open to people who already have the right to work.</p>

<p>The National Careers Service puts taxi driver pay at about <strong>£20,000 for starters and £35,000 for experienced drivers</strong>, on variable hours that include evenings, weekends and bank holidays.</p>

<h2>United States: Uber's Minimum Requirements</h2>

<p>Uber's US requirements are:</p>

<ul>
    <li>Meet the <strong>minimum age to drive in your state</strong>.</li>
    <li>At least <strong>one year of licensed driving experience in the US</strong>, or <strong>three years if you are under 25</strong>.</li>
    <li>A <strong>valid US driver's licence</strong>; some states require an in-state licence.</li>
    <li>An eligible four-door vehicle, with insurance if you use your own.</li>
</ul>

<p>A foreign licence does not count towards the year of US driving, so a newcomer usually spends their first year driving on a US licence before they can sign up. Cities such as New York add their own taxi and for-hire licences. You also need US work authorisation before you can sign up.</p>

<h2>Canada: It Depends on the City</h2>

<p>Uber Canada states that <strong>requirements vary by city</strong>, with minimum requirements on top and each city setting its own vehicle rules. Taxi licensing is municipal too. Check the city where you will live, and make sure your work permit allows the work: some permits are tied to a single employer, and app-based driving is self-employment.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/taxi-uber-driver-requirements-by-country-airport.jpg"
         alt="A driver in a black car smiling at a woman with a suitcase opening the rear door at an airport pick-up lane, with a plane taking off over the Dubai skyline at sunset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Australia: The NSW Passenger Transport Licence Code</h2>

<p>In New South Wales, taxi and rideshare drivers need a <strong>Passenger Transport Licence Code</strong> on their licence. To be eligible you must have held an <strong>unrestricted Australian driver licence for at least 12 months in the last 4 years</strong> and meet the <strong>medical standards for commercial vehicle drivers</strong> in Austroads' Assessing Fitness to Drive. Other states run their own schemes. For the jobs side, see our Australian taxi guide linked below.</p>

<h2>What Every Country Has in Common</h2>

<ol>
    <li><strong>The right to work comes first.</strong> No passenger permit gives you a visa.</li>
    <li><strong>A local licence comes second.</strong> Most schemes need a period of driving on the country's own licence.</li>
    <li><strong>Checks come third:</strong> criminal record, medical and often a knowledge or language test.</li>
    <li><strong>Never pay an agent for a taxi visa.</strong> In the UAE the employer pays recruitment costs by law; in the UK the job cannot be sponsored at all.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What do I need to become a taxi driver in Dubai?</h3>
<p>A MOHRE work permit, a UAE driving licence, an Emirates ID, a good conduct certificate and an RTA professional driver permit, which includes a medical, an English test and psychological assessment.</p>

<h3>Can I drive Uber in Dubai on a visit visa?</h3>
<p>No. Paid driving needs a work permit, a UAE licence and the RTA permit, none of which a visit visa allows.</p>

<h3>Who issues taxi licences in the UK?</h3>
<p>The local council, or Transport for London in London.</p>

<h3>Can I get a UK visa as a taxi driver?</h3>
<p>No. Taxi and cab drivers and chauffeurs (SOC 8213) are ineligible for the Skilled Worker visa.</p>

<h3>How much do taxi drivers earn in the UK?</h3>
<p>The National Careers Service gives about £20,000 for starters and £35,000 for experienced drivers.</p>

<h3>Can I drive for Uber in the US with a foreign licence?</h3>
<p>No. Uber requires a valid US licence and at least one year of US driving experience, three if you are under 25.</p>

<h3>What are Uber driver requirements in Canada?</h3>
<p>They vary by city. Check the city's rules and that your work permit allows self-employed driving.</p>

<h3>What is a Passenger Transport Licence Code in NSW?</h3>
<p>The code taxi and rideshare drivers need in NSW. It requires an unrestricted Australian licence for 12 months in the last 4 years and commercial vehicle medical standards.</p>

<h2>People Also Search For</h2>

<h3>RTA driver permit Dubai</h3>
<p>Required for taxi and limousine drivers, with English and psychological tests.</p>

<h3>Uber driver requirements USA</h3>
<p>One year of US driving, three if under 25, and a US licence.</p>

<h3>TfL private hire licence</h3>
<p>London's licence for private hire drivers, issued by Transport for London.</p>

<h3>Taxi licence UK requirements</h3>
<p>Right to work, 12 months' full licence and fit and proper checks.</p>

<h3>SOC 8213 Skilled Worker</h3>
<p>Taxi and cab drivers and chauffeurs are listed as ineligible.</p>

<h3>Passenger Transport Licence Code NSW</h3>
<p>12 months on an unrestricted Australian licence in the last 4 years.</p>

<h3>Uber driver Canada requirements</h3>
<p>Set city by city on top of Uber's minimums.</p>

<h3>Delivery driver jobs abroad</h3>
<p>The work permit and licence steps for courier work.</p>

<h2>More Job Guides</h2>

<p>Related driving guides:</p>

<ul>
    <li><a href="/blog/delivery-driver-and-courier-jobs-how-to-start-in-a-new-country">Delivery Driver and Courier Jobs: How to Start in a New Country</a> &mdash; work permit, licence and employment status for courier work.</li>
    <li><a href="/blog/taxi-driver-jobs-in-australia">Taxi Driver Jobs in Australia</a> &mdash; the Australian taxi market in detail.</li>
    <li><a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">How to Get a Logistics Driver Job in the UAE</a> &mdash; UAE licence categories and exchange.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the Saudi route.</li>
    <li><a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> &mdash; commercial driving in the US.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. Requirements are from the RTA, GOV.UK, the National Careers Service, Uber and Transport for NSW. Confirm them with the licensing authority before applying.</p>
HTML;
    }
}
