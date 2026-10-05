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
 * "Delivery Driver and Courier Jobs: How to Start in a New Country" — the
 * cross-country starter page for delivery work. The country pages already
 * cover pay and employers; this one covers the order of steps: work
 * authorisation first, then the local licence, then the employment status.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Links to five courier companies and a recruiter advertising "we sponsor
 *    visas, recruit from Pakistan". The recruiter's claims could not be
 *    verified, so the page warns about that pattern generically instead.
 *
 * 2. A Find a Job day rate (£165-£220) and a courier network's weekly
 *    earnings claim (£300-£500+). Job-board and recruiter pay is not
 *    republished on this site.
 *
 * 3. No statement that UK delivery drivers and couriers (SOC 8214) are
 *    ineligible for Skilled Worker sponsorship, which decides whether the UK
 *    is open to an overseas applicant at all.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class DeliveryCourierJobsNewCountryBlogSeeder extends Seeder
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
        $title = 'Delivery Driver and Courier Jobs: How to Start in a New Country';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Delivery work abroad starts with the right to work, not the job advert. The UAE needs a MOHRE work permit and a UAE licence, the UK does not sponsor couriers, and self-employed courier work changes who pays tax and costs.',
                'content' => $content,
                'featured_image' => 'blogs/delivery-driver-courier-jobs-new-country.jpg',
                'tags' => 'delivery driver jobs abroad, courier jobs abroad, delivery rider jobs dubai, uae work permit delivery, courier jobs uk right to work, self-employed courier, soc 8214 delivery driver, delivery job scams',
                'meta_title' => 'Delivery Driver and Courier Jobs in a New Country',
                'meta_description' => 'How to start delivery or courier work in a new country: work permit first, the local licence, employed vs self-employed, budgeting and scam checks.',
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
                'advertiser' => ['UAE Courier & Delivery Companies (Aggregated)', 'uae-delivery-rider-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Delivery Rider and Driver — UAE Courier and Delivery Companies',
                'apply' => self::UAE_APPLY_URL,
                'hours' => 'Shift-based, including evenings and weekends',
                'description' => $this->uaeJobDescription(),
                'meta' => 'Delivery rider and van driver roles with UAE courier and delivery companies, recruited on MOHRE work permits. A UAE licence for the vehicle is required.',
                'keywords' => 'delivery rider jobs dubai, delivery driver uae, courier jobs uae, motorcycle delivery dubai, mohre work permit',
            ],
            [
                'advertiser' => ['UK Parcel & Courier Networks (Aggregated)', 'uk-courier-aggregated'],
                'location' => ['United Kingdom', 'United Kingdom'],
                'position' => 'Courier and Delivery Driver — UK Parcel Networks (Right to Work Required)',
                'apply' => self::UK_APPLY_URL,
                'hours' => 'Employed shifts or self-employed rounds, often including weekends',
                'description' => $this->ukJobDescription(),
                'meta' => 'Courier and delivery driver roles with UK parcel networks, employed or self-employed. The right to work in the UK is required; the role is not open to Skilled Worker sponsorship.',
                'keywords' => 'courier jobs uk, delivery driver jobs uk, self-employed courier uk, parcel delivery jobs, van driver jobs uk',
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
                    // Pay depends on employer, route and employment status;
                    // this site does not republish job-board or recruiter rates.
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
<p>Courier, parcel and food delivery companies across the UAE recruit motorcycle riders and van drivers from overseas.</p>

<h3>Requirements</h3>
<ul>
    <li>A MOHRE work permit and residence visa arranged by the employer. You cannot work on a visit or tourist visa</li>
    <li>A UAE driving licence for the vehicle: a motorcycle licence for riders, a light vehicle licence for vans</li>
    <li>For Dubai riders: a Dubai Police good conduct certificate and RTA rider training and permit</li>
</ul>

<p><strong>Who pays:</strong> under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge the worker recruitment and employment costs, directly or indirectly.</p>

<p><strong>Note:</strong> permit, licence and wage rules are set by MOHRE and the RTA, not by JobGader. Confirm them on u.ae before accepting an offer.</p>
JOBHTML;
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Parcel networks, retailers and courier firms across the UK recruit delivery drivers as employees and engage couriers on a self-employed basis.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>The right to work in the UK. Delivery drivers and couriers (SOC 8214) are ineligible for Skilled Worker sponsorship</li>
    <li>A full driving licence valid in the UK, and insurance that covers business deliveries if you use your own vehicle</li>
    <li>For self-employed rounds: registration with HMRC and your own tax and National Insurance</li>
</ul>

<p><strong>Note:</strong> visa and employment-status rules are set by the Home Office and HMRC, not by JobGader. Check gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Delivery and courier work is one of the most common first jobs for people arriving in a new country: the training is short, demand is steady, and a driving licence is often the only qualification asked for. But the order in which you do things matters more than the job itself. A delivery company cannot hire you until you are allowed to work, and you cannot drive for them until you hold a licence the country accepts. This guide walks through that order, using the UAE and the UK as the two markets where the question comes up most.</p>

<p>For pay and employers in a single country, see the country guides linked at the end. This page is about getting to the starting line.</p>

<h2>Step 1: Get the Right to Work Before Anything Else</h2>

<p>Every other step depends on this one. A delivery job does not create the right to work; it can only be taken once you have it.</p>

<ul>
    <li><strong>UAE.</strong> You cannot work on a visit or tourist visa. Under Article 6 of Federal Decree-Law No. 33 of 2021, nobody may be employed without a <strong>work permit from MOHRE</strong>, the Ministry of Human Resources and Emiratisation. The employer applies for it and then sponsors your residence visa.</li>
    <li><strong>UK.</strong> Delivery drivers and couriers (SOC 8214) are listed as <strong>ineligible</strong> for the Skilled Worker visa. Since 22 July 2025, jobs below degree level can only be sponsored if they are on the Temporary Shortage List, and delivery driving is not. In practice UK delivery work is open to people who already have the right to work through another route.</li>
</ul>

<p>Any advert that suggests you can travel first and sort out the paperwork later is asking you to work illegally, which can lead to fines, detention and a ban on returning.</p>

<h2>Step 2: Get a Licence the Country Accepts</h2>

<p>A licence from home rarely carries over automatically for work.</p>

<ul>
    <li><strong>UAE.</strong> Once you live and work there, you need a UAE licence for the vehicle you will drive: a <strong>motorcycle licence</strong> for delivery riders and a light vehicle licence for vans. Some countries' licences can be exchanged; others, including Pakistan's, cannot, so budget for training and tests after arrival.</li>
    <li><strong>Dubai riders.</strong> The RTA's delivery rules add a Dubai Police good conduct certificate, a residence visa sponsored as a driver, an age of 21 to 55, and professional training and a permit through an RTA-accredited institute.</li>
    <li><strong>UK.</strong> Employers ask for a full licence valid in the UK. Check on gov.uk how long your foreign licence can be used and whether it can be exchanged.</li>
</ul>

<h2>Step 3: Gather the Documents Employers Ask For</h2>

<ol>
    <li>Passport valid for the length of the contract</li>
    <li>Proof of the right to work: a residence visa and work permit in the UAE, or a share code or visa in the UK</li>
    <li>Your driving licence, plus any exchange or translation paperwork</li>
    <li>A police or good conduct certificate</li>
    <li>For van work, proof of insurance if you supply your own vehicle</li>
    <li>A smartphone that can run the delivery app, and a mobile data plan</li>
</ol>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/delivery-driver-courier-jobs-new-country-handover.jpg"
         alt="A courier in a blue cap and polo shirt handing a parcel to a customer at a doorway, with an open van full of boxes, a delivery rider on a motorcycle and the Dubai skyline behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Step 4: Understand Whether You Are Employed or Self-Employed</h2>

<p>Delivery work is offered in two very different ways, and the difference decides who pays for the van, the fuel and the tax.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Question</th>
            <th style="padding:10px;text-align:left;">Employed driver</th>
            <th style="padding:10px;text-align:left;">Self-employed courier</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Vehicle</strong></td><td style="padding:10px;">Usually supplied</td><td style="padding:10px;">Usually your own</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Fuel and insurance</strong></td><td style="padding:10px;">Usually paid by the employer</td><td style="padding:10px;">Paid by you</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Tax</strong></td><td style="padding:10px;">Deducted from pay</td><td style="padding:10px;">You register and pay it yourself</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Holiday and sick pay</strong></td><td style="padding:10px;">Statutory entitlements apply</td><td style="padding:10px;">Generally none, unless the contract adds them</td></tr>
    </tbody>
</table>
</div>

<p>In the UK, large parcel networks run on self-employed couriers. Evri, for example, describes a network of more than 30,000 self-employed couriers and still requires each one to have the right to work in the UK. Self-employment is not a way round immigration rules.</p>

<p>In the UAE, delivery riders are employed on a MOHRE work permit held by a company, which is not always the app or brand on the delivery box. Ask which company is your legal employer, because that is the company responsible for your visa and your wages.</p>

<h2>Step 5: Budget Before You Accept</h2>

<p>Delivery pay is usually quoted per hour, per drop or per route, and the headline figure is not what you keep. Before accepting, work out:</p>

<ul>
    <li><strong>Vehicle costs</strong> if you supply your own: lease or finance, maintenance and the insurance that covers business deliveries.</li>
    <li><strong>Fuel</strong> for the route length you will actually drive.</li>
    <li><strong>Phone and data</strong>, which the app needs all day.</li>
    <li><strong>Tax set aside</strong> if you are self-employed.</li>
    <li><strong>Housing and transport</strong> in the first months, including whether the employer provides accommodation.</li>
</ul>

<p>In the UAE, ask for the basic salary, allowances and accommodation in writing. Wages must be paid through the Wage Protection System, which now runs under <strong>Ministerial Resolution No. 340 of 2026</strong>, so you should be paid into a bank or approved account, not in cash.</p>

<h2>Step 6: Check for Scams</h2>

<ol>
    <li><strong>Never pay a recruitment or visa fee.</strong> In the UAE, Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs, directly or indirectly.</li>
    <li><strong>Be wary of agents promising sponsorship for delivery jobs in the UK.</strong> The occupation is not eligible, so the promise cannot be kept.</li>
    <li><strong>Treat "we sponsor visas, we recruit from your country" adverts as unverified</strong> until you can confirm the company is licensed and the offer exists on the employer's own careers page or an official job portal.</li>
    <li><strong>Check the offer letter names the legal employer</strong>, the salary and the visa type before you travel.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can I work as a delivery rider in Dubai on a visit visa?</h3>
<p>No. You need a MOHRE work permit and a residence visa arranged by the employer before you start work.</p>

<h3>Do I need a UAE licence to deliver by motorcycle?</h3>
<p>Yes. Riders need a UAE motorcycle licence, and Dubai adds a good conduct certificate and RTA rider training and permit.</p>

<h3>Who pays for a UAE delivery job visa?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs.</p>

<h3>Can I get a UK Skilled Worker visa as a delivery driver?</h3>
<p>No. Delivery drivers and couriers (SOC 8214) are listed as ineligible for the Skilled Worker visa.</p>

<h3>Can I work as a self-employed courier in the UK without a visa?</h3>
<p>No. Self-employed courier networks still require the right to work in the UK.</p>

<h3>What is the difference between an employed and a self-employed courier?</h3>
<p>Employed drivers usually get a vehicle, fuel and tax deducted from pay. Self-employed couriers cover their own vehicle, fuel, insurance and tax.</p>

<h3>What documents do delivery companies ask for?</h3>
<p>A passport, proof of the right to work, a licence valid in the country, a police certificate and, for some roles, vehicle insurance.</p>

<h3>How do I spot a fake delivery job offer?</h3>
<p>It asks for a fee, promises a visa the country does not offer for the role, or cannot be found on the employer's own site or an official job portal.</p>

<h2>People Also Search For</h2>

<h3>Delivery rider jobs Dubai</h3>
<p>UAE motorcycle licence, MOHRE work permit and RTA rider permit.</p>

<h3>Courier jobs UK for foreigners</h3>
<p>Open only to people who already have the right to work.</p>

<h3>SOC 8214 Skilled Worker</h3>
<p>Delivery drivers and couriers are listed as ineligible.</p>

<h3>Self-employed courier UK</h3>
<p>Own vehicle, own tax, and the right to work still required.</p>

<h3>UAE work permit for drivers</h3>
<p>Issued by MOHRE to the employer before you start work.</p>

<h3>Recruitment fees UAE</h3>
<p>Barred under Article 6 of Federal Decree-Law No. 33 of 2021.</p>

<h3>Wage Protection System UAE</h3>
<p>Runs under Ministerial Resolution No. 340 of 2026.</p>

<h3>Taxi driver requirements</h3>
<p>Passenger driving needs a separate permit in most countries.</p>

<h2>More Job Guides</h2>

<p>Country guides and related driving work:</p>

<ul>
    <li><a href="/blog/taxi-and-uber-driver-requirements-by-country">Taxi and Uber Driver Requirements by Country</a> &mdash; the permits for passenger driving.</li>
    <li><a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">How to Get a Logistics Driver Job in the UAE</a> &mdash; licence categories, exchange and the full rider rules.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; UK employers and pay.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; the US market.</li>
    <li><a href="/blog/how-to-get-a-delivery-job-in-australia">How to Get a Delivery Job in Australia</a> &mdash; the Australian market.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the Saudi route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. UAE rules are from MOHRE, the RTA and u.ae; UK visa rules from GOV.UK. Confirm them before applying.</p>
HTML;
    }
}
